<?php

namespace App\Controllers\Api\Publico;

use App\Controllers\BaseController;
use App\Entities\ProductoImagenEntity;
use App\Helpers\Paginator;
use App\Helpers\Permisos;
use App\Helpers\Util;
use App\Models\ColorModel;
use App\Models\EstadoModel;
use App\Models\ParametroModel;
use App\Models\ProductoBaseModel;
use App\Models\ProductoColorModel;
use App\Models\ProductoImagenModel;
use App\Models\ProductoModel;
use App\Validation\ProductoImagenValidation;
use CodeIgniter\RESTful\ResourceController;

class ProductoImagenPublicoController extends ResourceController
{

    protected $productoImagen;
    protected $producto;
    protected $estado;
    protected $parametro;
    protected $permiso;
    protected $productocolor;
    protected $color;
    public function __construct()
    {
        $this->productoImagen = new ProductoImagenModel();
        $this->productocolor = new ProductoColorModel();
        $this->producto = new ProductoModel();
        $this->estado = new EstadoModel();
        $this->parametro = new ParametroModel();
        $this->permiso = new Permisos();
        $this->color = new ColorModel();
    }

    public  function obtenerPorId($idproductoImagen)
    {

        $productoImagen = $this->productoImagen->obtenerPorId($idproductoImagen);

        if (!$productoImagen) {
            return $this->respond(['mensaje' => 'No existe la producto Imagen solicitada'], 404);
        } else {

            $productoImagenEntity = new ProductoImagenEntity($productoImagen);


            $productoImagenEntity->estado = $this->estado->obtenerPorId($productoImagen->idestado);
            $productoImagenEntity->producto = $this->producto->obtenerPorId($productoImagen->idproducto);
            $productoImagenEntity->pdestacado = $this->parametro->obtenerPorId($productoImagen->idpdestacado);

            // Convertir a array
            $resultado = $productoImagenEntity->toArray();

            return $this->respond($resultado, 200);
        }
    }

    public function listar()
    {

        // Verificar si es POST
        if (!$this->request->is('post')) {
            return $this->fail('Método no permitido. Se requiere POST.', 405);
        }

        $request = $this->request;

        // Parámetros de búsqueda y orden
        $ordencriterio = $request->getVar('ordenCriterio') ?? 'fecha';
        $ordentipo = $request->getVar('ordenTipo') ?? 'asc';
        $parametro = $request->getVar('parametro') ?? '';
        $valor = $request->getVar('valor') ?? '';
        $idestado = (int) ($request->getVar('idEstado') ?? 0);
        $idproducto = (int) ($request->getVar('idProducto') ?? 0);
        $idproductocolor = (int) ($request->getVar('idProductoColor') ?? 0);
        $idpdestacado = (int) ($request->getVar('idpDestacado') ?? 0);



        // Parámetros de paginación
        $pagina = (int) ($request->getVar('pagina') ?? 1);
        $registros = (int) ($request->getVar('registros') ?? 10);

        // Total de registros
        $total = $this->productoImagen->buscarPorTotal(
            $parametro,
            $valor,
            $idestado,
            $idproducto,
            $idproductocolor,
            $idpdestacado
        );
        // $ordencriterio, $ordentipo, $parametro, $valor, $idestado, $idproducto, $idproductocolor, $idptipo, $inicio, $registros
        $paginator = new Paginator($pagina, $registros, $total);
        // Consulta paginada
        $productoImagens = $this->productoImagen->buscarPor(
            $ordencriterio,
            $ordentipo,
            $parametro,
            $valor,
            $idestado,
            $idproducto,
            $idproductocolor,
            $idpdestacado,
            $paginator->getFirstElement(),
            $paginator->getSize()
        );

        // Convertir resultados a entidad
        $resultado = [];
        foreach ($productoImagens as $row) {
            $productoImagenEntity = new ProductoImagenEntity($row);
            $productoImagenEntity->estado = $this->estado->obtenerPorId($row->idestado);
            $productoImagenEntity->pdestacado = $this->parametro->obtenerPorId($row->idpdestacado);

            $productocolorEntity = $this->productocolor->obtenerPorId($row->idproductocolor);

            if ($productocolorEntity) {
                $productocolorEntity->color = $this->color->obtenerPorId($productocolorEntity->idcolor);
            }

            $productoImagenEntity->productocolor = $productocolorEntity;

            $resultado[] = $productoImagenEntity->toArray();
        }



        return $this->respond([
            'paginator' => $paginator->enviar(),
            'content' => $resultado
        ]);
    }
    public function guardar()
    {

        $request = $this->request;

        $data = $request->getJSON(true);
        $productoImagenRequest = new ProductoImagenValidation();
        $errores = $productoImagenRequest->productoImagenGuardarValidation($data);

        if (!empty($errores)) {
            return $this->response
                ->setStatusCode(422)
                ->setJSON(['errors' => $errores]);
        }

        $datosValidados = [
            'idestado'      => $data['estado']['idEstado'] ?? null,
            'idproducto'      => $data['producto']['idProducto'] ?? null,
            'idpdestacado'      => $data['pDestacado']['idParametro'] ?? null,
            'nombre'        => $data['nombre'] ?? null,
            'orden'   => $data['descripcion'] ?? null,
            'urlimagen'    => $data['urlImagen'] ?? null,
            'orden'         => $data['orden'] ?? null,
        ];


        $productoImagenId = $this->productoImagen->guardar($datosValidados);
        $productoImagen = $this->productoImagen->find($productoImagenId);
        if ($productoImagen) {
            $productoImagenEntity = new ProductoImagenEntity($productoImagen);
            $productoImagenEntity->estado = $this->estado->obtenerPorId($productoImagen->idestado);
            $productoImagenEntity->producto = $this->producto->obtenerPorId($productoImagen->idproducto);
            $productoImagenEntity->pdestacado = $this->parametro->obtenerPorId($productoImagen->idpdestacado);

            return $this->respond([
                "mensaje" => 'productoImagen registrado con éxito',
                "productoImagen" => $productoImagenEntity->toArray()
            ], 201);
        } else {

            return $this->respond(["mensaje" => "Error al registrar productoImagen"], 500);
        }
    }

    public function actualizar()
    {

        $request = $this->request;

        $data = $request->getJSON(true);
        $productoImagenRequest = new ProductoImagenValidation();
        $errores = $productoImagenRequest->productoImagenActualizarValidation($data);

        if (!empty($errores)) {
            return $this->response
                ->setStatusCode(422)
                ->setJSON(['errors' => $errores]);
        }
        $datosValidados = [
            'idproductoimagen' => (int) $data['idProductoImagen'] ?? null,
            'idestado'      => $data['estado']['idEstado'] ?? null,
            'idproducto'      => $data['producto']['idProducto'] ?? null,
            'idpdestacado'      => $data['pDestacado']['idParametro'] ?? null,
            'nombre'        => $data['nombre'] ?? null,
            'orden'   => $data['descripcion'] ?? null,
            'urlimagen'    => $data['urlImagen'] ?? null,
            'orden'         => $data['orden'] ?? null,
        ];


        $productoImagenId = $this->productoImagen->guardar($datosValidados);
        $productoImagen = $this->productoImagen->find($productoImagenId);
        if ($productoImagen) {

            $productoImagenEntity = new productoImagenEntity($productoImagen);
            $productoImagenEntity->estado = $this->estado->obtenerPorId($productoImagen->idestado);
            $productoImagenEntity->producto = $this->producto->obtenerPorId($productoImagen->idproducto);
            $productoImagenEntity->pdestacado = $this->parametro->obtenerPorId($productoImagen->idpdestacado);
            return $this->respond([
                "mensaje" => 'producto Imagen actualizado con éxito',
                "productoImagen" =>  $productoImagenEntity->toArray()
            ], 201);
        } else {

            return $this->respond(["mensaje" => "Error al actualizar el producto Imagen"], 500);
        }
    }

    public function eliminar($idproductoImagen)
    {

        if ($this->productoImagen->eliminar($idproductoImagen)) {
            return $this->respond(['mensaje' => 'productoImagen eliminado con éxito']);
        } else {
            return $this->failNotFound('No se encontró la producto Imagen');
        }
    }


    public function uploadImagen1()
    {

        $idproductoImagen = $this->request->getPost('idProductoImagen');
        $productoImagen = $this->productoImagen->find($idproductoImagen);

        if (!$productoImagen) {
            return $this->response->setJSON(["mensaje" => 'No existe la productoImagen solicitada'])->setStatusCode(404);
        }

        // Convierte a array para evitar errores (si quieres)
        if (!is_array($productoImagen)) {
            $productoImagen = (array) $productoImagen;
        }

        $file = $this->request->getFile('archivo');
        if (!$file || !$file->isValid()) {
            return $this->response->setJSON(["mensaje" => 'Debe de seleccionar una imagen'])->setStatusCode(400);
        }

        // Elimina imagen anterior
        $imgPath = FCPATH . env('URL_IMAGE') . '/productoimagen/' . ($productoImagen['urlimagen'] ?? '');
        if (!empty($productoImagen['urlimagen']) && file_exists($imgPath)) {
            unlink($imgPath);
        }

        // Genera nombre amigable
        $nombreCompleto = trim($productoImagen['nombre'] ?? '');
        $urlamigable = Util::urls_amigables($nombreCompleto ?: 'productoImagen');

        // Usa el id para formar nombre único
        $nombrearchivo = $idproductoImagen . '-' . $urlamigable . '-escritorio.' . $file->getExtension();

        // Asegura carpeta destino
        $destino = FCPATH . env('URL_IMAGE') . '/productoimagen';
        if (!is_dir($destino)) {
            mkdir($destino, 0777, true);
        }

        // Mueve el archivo
        $file->move($destino, $nombrearchivo);

        // Actualiza en DB
        $this->productoImagen->update($idproductoImagen, ['urlimagen' => $nombrearchivo]);

        // Obtener actualizado y convertir si es necesario
        $productoImagenActualizado = $this->productoImagen->find($idproductoImagen);
        $productoImagenEntity = new ProductoImagenEntity($productoImagenActualizado);
        $productoImagenEntity->estado = $this->estado->obtenerPorId($productoImagenActualizado->idestado);
        $productoImagenEntity->producto = $this->producto->obtenerPorId($productoImagenActualizado->idproducto);
        $productoImagenEntity->pdestacado = $this->parametro->obtenerPorId($productoImagenActualizado->idpdestacado);

        return $this->response->setJSON([
            "productoimagen" => $productoImagenEntity->toArray(),
            "mensaje" => "Imagen cargada con éxito",
            "request" => $this->request->getPost()
        ])->setStatusCode(200);
    }



    public function eliminarImagen()
    {

        // $idproductoImagen = $this->request->getPost('idproductoImagen');

        $idproductoImagen = $this->request->getPost('idProductoImagen') ?? $this->request->getJSON(true)['idProductoImagen'] ?? null;

        if (empty($idproductoImagen)) {
            return $this->response->setJSON(['errors' => ['ID de productoImagen no recibido']])->setStatusCode(400);
        }

        $productoImagen = $this->productoImagen->find($idproductoImagen);

        if (!$productoImagen) {
            return $this->response->setJSON(['errors' => ['No existe el productoImagen solicitado']])->setStatusCode(404);
        }

        $urlimagen = is_array($productoImagen) ? ($productoImagen['urlimagen'] ?? null) : $productoImagen->urlimagen;
        $imgPath = FCPATH . env('URL_IMAGE') . '/productoImagen/' . $urlimagen;
        if (!empty($urlimagen) && file_exists($imgPath)) {
            unlink($imgPath);
        }

        // Aquí $idproductoImagen nunca será null
        $this->productoImagen->update($idproductoImagen, ['urlimagen' => null]);

        $productoImagenActualizado = $this->productoImagen->find($idproductoImagen);
        $productoImagenEntity = new productoImagenEntity($productoImagenActualizado);

        $productoImagenEntity->estado = $this->estado->obtenerPorId($productoImagenActualizado->idestado);
        $productoImagenEntity->producto = $this->producto->obtenerPorId($productoImagenActualizado->idproducto);
        $productoImagenEntity->pdestacado = $this->parametro->obtenerPorId($productoImagenActualizado->idpdestacado);




        // Convertir a array
        $resultado = $productoImagenEntity->toArray();

        return $this->response->setJSON([
            "productoImagen" => $resultado,
            "mensaje" => "Imagen de producto eliminada con éxito"
        ])->setStatusCode(200);
    }
}
