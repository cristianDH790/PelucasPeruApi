<?php

namespace App\Controllers\Api;

use App\Controllers\BaseController;
use App\Entities\ProductoCategoriaEntity;
use App\Helpers\Paginator;
use App\Helpers\Permisos;
use App\Helpers\Util;
use App\Models\EstadoModel;
use App\Models\ProductoCategoriaModel;
use App\Validation\ProductoCategoriaValidation;

use CodeIgniter\RESTful\ResourceController;

class ProductoCategoriaController extends ResourceController
{

    protected $productocategoria;
    protected $estado;
    protected $permiso;
    public function __construct()
    {
        $this->permiso = new Permisos();
        $this->productocategoria = new ProductoCategoriaModel();
        $this->estado = new EstadoModel();
    }

    public  function obtenerPorId($idproductocategoria)
    {

        $productocategoria = $this->productocategoria->obtenerPorId($idproductocategoria);

        if (!$productocategoria) {
            return $this->respond(['mensaje' => 'No existe el producto categoria solicitada'], 404);
        } else {

            $productocategoriaEntity = new ProductoCategoriaEntity($productocategoria);

            $productocategoriaEntity->estado = $this->estado->obtenerPorId($productocategoria->idestado);
            $productocategoria = $this->productocategoria->obtenerPorId($productocategoria->idrproductocategoria);
            $productocategoriaEntity->rproductocategoria = $this->productocategoria->obtenerCadenaConCategoria($productocategoria);

            // Convertir a array
            $resultado = $productocategoriaEntity->toArray();

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
        $idproductocategoria = (int) ($request->getVar('idProductoCategoria') ?? 0);
        $idrproductocategoria = (int) ($request->getVar('idrProductoCategoria') ?? 0);

        // Parámetros de paginación
        $pagina = (int) ($request->getVar('pagina') ?? 1);
        $registros = (int) ($request->getVar('registros') ?? 10);

        // Total de registros
        $total = $this->productocategoria->buscarPorTotal(
            $parametro,
            $valor,
            $idestado,
            $idproductocategoria,
            $idrproductocategoria

        );

        $paginator = new Paginator($pagina, $registros, $total);
        // Consulta paginada
        $productocategorias = $this->productocategoria->buscarPor(
            $ordencriterio,
            $ordentipo,
            $parametro,
            $valor,
            $idestado,
            $idproductocategoria,
            $idrproductocategoria,
            $paginator->getFirstElement(),
            $paginator->getSize()
        );

        // Convertir resultados a entidad
        $resultado = [];
        foreach ($productocategorias as $row) {

            $productocategoriaEntity = new ProductoCategoriaEntity($row);

            $productocategoriaEntity->estado = $this->estado->obtenerPorId($row->idestado);
            $productocategoria = $this->productocategoria->obtenerPorId($row->idrproductocategoria);
            $productocategoriaEntity->rproductocategoria = $this->productocategoria->obtenerCadenaConCategoria($productocategoria);


            $resultado[] = $productocategoriaEntity->toArray();
        }

        // Respuesta JSON con paginación y datos
        return $this->respond([
            'paginator' => $paginator->enviar(),
            'content' => $resultado
        ]);
    }
    public function guardar()
    {

        $request = $this->request;

        $data = $request->getJSON(true);
        $productocategoriaRequest = new ProductoCategoriaValidation();
        $errores = $productocategoriaRequest->productoCategoriaGuardarValidation($data);

        if (!empty($errores)) {
            return $this->response
                ->setStatusCode(422)
                ->setJSON(['errors' => $errores]);
        }
        $productocategoria = $data['rProductoCategoria']['idProductoCategoria'] == 0 ? 0 : $data['rProductoCategoria']['idProductoCategoria'];


        $datosValidados = [
            'idestado' => $data['estado']['idEstado'] ?? null,
            'nombre' => $data['nombre'] ?? null,
            'contenido' => $data['contenido'] ?? null,
            'idrproductocategoria' => $productocategoria,
            'orden' => $data['orden'] ?? null,
            'urlamigable' => $data['urlAmigable'] ?? null,

        ];



        $productocategoriaId = $this->productocategoria->guardar($datosValidados);
        $productocategoria = $this->productocategoria->find($productocategoriaId);
        if ($productocategoria) {


            $productocategoriaEntity = new ProductoCategoriaEntity($productocategoria);
            $productocategoriaEntity->estado = $this->estado->obtenerPorId($productocategoria->idestado);
            $productocategoria = $this->productocategoria->obtenerPorId($productocategoria->idrproductocategoria);
            $productocategoriaEntity->rproductocategoria = $this->productocategoria->obtenerCadenaConCategoria($productocategoria);


            return $this->respond([
                "mensaje" => 'Producto categoria registrado con éxito',
                "productocategoria" => $productocategoriaEntity->toArray()
            ], 201);
        } else {

            return $this->respond(["mensaje" => "Error al registrar producto categoria"], 500);
        }
    }
    public function actualizar()
    {

        $request = $this->request;

        $data = $request->getJSON(true);
        $productocategoriaRequest = new ProductoCategoriaValidation();
        $errores = $productocategoriaRequest->productoCategoriaActualizarValidation($data);

        if (!empty($errores)) {
            return $this->response
                ->setStatusCode(422)
                ->setJSON(['errors' => $errores]);
        }

        $productocategoria = $data['rProductoCategoria']['idProductoCategoria'] == 0 ? 0 : $data['rProductoCategoria']['idProductoCategoria'];


        $datosValidados = [
            'idproductocategoria' => (int) $data['idProductoCategoria'] ?? null,
            'idestado' => $data['estado']['idEstado'] ?? null,
            'nombre' => $data['nombre'] ?? null,
            'contenido' => $data['contenido'] ?? null,
            'idrproductocategoria' => $productocategoria,
            'orden' => $data['orden'] ?? null,
            'urlamigable' => $data['urlAmigable'] ?? null,
        ];

        $productocategoriaId = $this->productocategoria->guardar($datosValidados);
        $productocategoria = $this->productocategoria->find($productocategoriaId);
        if ($productocategoria) {

            $productocategoriaEntity = new ProductoCategoriaEntity($productocategoria);
            $productocategoriaEntity->estado = $this->estado->obtenerPorId($productocategoria->idestado);
            $productocategoria = $this->productocategoria->obtenerPorId($productocategoria->idrproductocategoria);
            $productocategoriaEntity->rproductocategoria = $this->productocategoria->obtenerCadenaConCategoria($productocategoria);


            return $this->respond([
                "mensaje" => 'Producto categoria actualizado con éxito',
                "productocategoria" =>  $productocategoriaEntity->toArray()
            ], 201);
        } else {

            return $this->respond(["mensaje" => "Error al actualizar el producto categoria"], 500);
        }
    }
    public function eliminar($idproductocategoria)
    {

        if ($this->productocategoria->eliminar($idproductocategoria)) {
            return $this->respond(['mensaje' => 'Producto categoria eliminado con éxito']);
        } else {
            return $this->failNotFound('No se encontró la producto categoria');
        }
    }


    public function uploadImagen1()
    {



        $idproductocategoria = $this->request->getPost('idProductoCategoria');
        $productocategoriaimagen = $this->productocategoria->find($idproductocategoria);

        if (!$productocategoriaimagen) {
            return $this->response->setJSON(["mensaje" => 'No existe el producto categoria solicitada'])->setStatusCode(404);
        }

        // Manejo como array para evitar errores con objetos
        if (!is_array($productocategoriaimagen)) {
            $productocategoriaimagen = (array) $productocategoriaimagen;
        }

        $file = $this->request->getFile('archivo');
        if (!$file || !$file->isValid()) {
            return $this->response->setJSON(["mensaje" => 'Debe de seleccionar una imagen'])->setStatusCode(400);
        }

        // Elimina imagen anterior
        $imgPath = FCPATH . env('URL_IMAGE') . '/productocategoria/' . ($productocategoriaimagen['urlimagen'] ?? '');
        if (!empty($productocategoriaimagen['urlimagen']) && file_exists($imgPath)) {
            unlink($imgPath);
        }

        // Genera nombre amigable
        $nombre = is_array($productocategoriaimagen) ? ($productocategoriaimagen['nombre'] ?? '') : ($productocategoriaimagen->nombres ?? '');


        $nombreCompleto = trim($nombre);
        $urlamigable = Util::urls_amigables($nombreCompleto ?: 'productocategoria');
        $nombrearchivo = $productocategoriaimagen['idproductocategoria'] . '-' . $urlamigable . '-imagen.' . $file->getExtension();

        // Asegura carpeta
        $destino = FCPATH . env('URL_IMAGE') . '/productocategoria';
        if (!is_dir($destino)) {
            mkdir($destino, 0777, true);
        }

        // Mueve el archivo
        $file->move($destino, $nombrearchivo);

        // Actualiza en DB
        $this->productocategoria->update($idproductocategoria, ['urlimagen' => $nombrearchivo]);

        // Obtener actualizado y convertir si es necesario
        $productocategoriaimagenActualizado = $this->productocategoria->find($idproductocategoria);

        $productocategoriaimagenActualizado = new productoCategoriaEntity($productocategoriaimagenActualizado);
        $productocategoriaimagenActualizado->estado = $this->estado->obtenerPorId($productocategoriaimagenActualizado->idestado);
        $productocategoria = $this->productocategoria->obtenerPorId($productocategoriaimagenActualizado->idrproductocategoria);
        $productocategoriaimagenActualizado->rproductocategoria = $this->productocategoria->obtenerCadenaConCategoria($productocategoria);

        return $this->response->setJSON([
            "productocategoria" => $productocategoriaimagenActualizado->toArray(),
            "mensaje" => "Imagen cargada con éxito",
            "request" => $this->request->getPost()
        ])->setStatusCode(200);
    }
    public function uploadImagen2()
    {

        $idproductocategoria = $this->request->getPost('idProductoCategoria');
        $productocategoria = $this->productocategoria->find($idproductocategoria);

        if (!$productocategoria) {
            return $this->response->setJSON(["mensaje" => 'No existe la producto categoria solicitada'])->setStatusCode(404);
        }

        // Manejo como array para evitar errores con objetos
        if (!is_array($productocategoria)) {
            $productocategoria = (array) $productocategoria;
        }

        $file = $this->request->getFile('archivo');
        if (!$file || !$file->isValid()) {
            return $this->response->setJSON(["mensaje" => 'Debe de seleccionar una imagen'])->setStatusCode(400);
        }

        // Elimina imagen anterior
        $imgPath = FCPATH . env('URL_IMAGE') . '/productocategoria/' . ($productocategoria['urlimagenbanner'] ?? '');
        if (!empty($productocategoria['urlimagenbanner']) && file_exists($imgPath)) {
            unlink($imgPath);
        }

        // Genera nombre amigable
        $nombre = is_array($productocategoria) ? ($productocategoria['nombre'] ?? '') : ($productocategoria->nombres ?? '');

        $nombreCompleto = trim($nombre);
        $urlamigable = Util::urls_amigables($nombreCompleto ?: 'productocategoria');
        $nombrearchivo = $productocategoria['idproductocategoria'] . '-' . $urlamigable . '-banner.' . $file->getExtension();

        // Asegura carpeta
        $destino = FCPATH . env('URL_IMAGE') . '/productocategoria';
        if (!is_dir($destino)) {
            mkdir($destino, 0777, true);
        }

        // Mueve el archivo
        $file->move($destino, $nombrearchivo);

        // Actualiza en DB
        $this->productocategoria->update($idproductocategoria, ['urlimagenbanner' => $nombrearchivo]);

        // Obtener actualizado y convertir si es necesario
        $productocategoriaActualizado = $this->productocategoria->find($idproductocategoria);


        $productocategoriaimagenActualizado = new ProductoCategoriaEntity($productocategoriaActualizado);
        $productocategoriaimagenActualizado->estado = $this->estado->obtenerPorId($productocategoriaimagenActualizado->idestado);

        return $this->response->setJSON([
            "productocategoria" => $productocategoriaimagenActualizado->toArray(),
            "mensaje" => "Imagen cargada con éxito",
            "request" => $this->request->getPost()
        ])->setStatusCode(200);
    }



    // public function eliminarImagen2()
    // {
    //     // $idproductocategoria = $this->request->getPost('idproductocategoria');

    //     $idproductocategoria = $this->request->getPost('idProductoCategoria') ?? $this->request->getJSON(true)['idProductoCategoria'] ?? null;
    //     $tipo = $this->request->getPost('tipo') ?? $this->request->getJSON(true)['tipo'] ?? null;

    //     if (empty($idproductocategoria)) {
    //         return $this->response->setJSON(['errors' => ['ID de producto categoria imagen no recibido']])->setStatusCode(400);
    //     }

    //     $productocategoriaimagen = $this->productocategoria->find($idproductocategoria);

    //     if (!$productocategoriaimagen) {
    //         return $this->response->setJSON(['errors' => ['No existe el productocategoriaimagen solicitado']])->setStatusCode(404);
    //     }

    //     $urlimagen = is_array($productocategoriaimagen) ? ($productocategoriaimagen['urlimagen'] ?? null) : $productocategoriaimagen->urlimagen;
    //     $imgPath = FCPATH . env('URL_IMAGE') . '/productocategoria/' . $urlimagen;
    //     if (!empty($urlimagen) && file_exists($imgPath)) {
    //         unlink($imgPath);
    //     }

    //     // Aquí $idproductocategoria nunca será null
    //     $this->productocategoria->update($idproductocategoria, ['urlimagen' => null]);

    //     $productocategoriaimagenActualizado = new ProductoCategoriaEntity($this->productocategoria);
    //     $productocategoriaimagenActualizado->estado = $this->estado->obtenerPorId($this->productocategoria->idestado);




    //     // Convertir a array
    //     $resultado = $productocategoriaimagenActualizado->toArray();

    //     return $this->response->setJSON([
    //         "productocategoria" => $resultado,
    //         "mensaje" => "Imagen de producto eliminada con éxito"
    //     ])->setStatusCode(200);
    // }
    public function eliminarImagen()
    {

        $idproductocategoria = $this->request->getPost('idProductoCategoria') ?? $this->request->getJSON(true)['idProductoCategoria'] ?? null;
        $tipo = $this->request->getPost('tipo') ?? $this->request->getJSON(true)['tipo'] ?? null;

        if (empty($idproductocategoria)) {
            return $this->response->setJSON(['errors' => ['ID de producto categoria imagen no recibido']])->setStatusCode(400);
        }

        if (empty($tipo)) {
            return $this->response->setJSON(['errors' => ['Tipo de imagen no especificado']])->setStatusCode(400);
        }

        $productocategoriaimagen = $this->productocategoria->find($idproductocategoria);

        if (!$productocategoriaimagen) {
            return $this->response->setJSON(['errors' => ['No existe el producto categoria imagen solicitado']])->setStatusCode(404);
        }

        // Determinar el campo y nombre de archivo según tipo
        if ($tipo === 'urlImagen1') {
            $campoImagen = 'urlimagen';
        } elseif ($tipo === 'urlImagen2') {
            $campoImagen = 'urlimagenbanner';
        } else {
            return $this->response->setJSON(['errors' => ['Tipo de imagen no válido']])->setStatusCode(400);
        }

        $urlimagen = is_array($productocategoriaimagen) ? ($productocategoriaimagen[$campoImagen] ?? null) : $productocategoriaimagen->$campoImagen;

        $imgPath = FCPATH . env('URL_IMAGE') . '/productocategoria/' . $urlimagen;

        if (!empty($urlimagen) && file_exists($imgPath)) {
            unlink($imgPath);
        }

        // Actualizar el campo específico a null
        $this->productocategoria->update($idproductocategoria, [$campoImagen => null]);

        $productocategoriaActualizado = $this->productocategoria->find($idproductocategoria);
        $productocategoriaimagenActualizado = new ProductoCategoriaEntity($productocategoriaActualizado);
        $productocategoriaimagenActualizado->estado = $this->estado->obtenerPorId($productocategoriaimagenActualizado->idestado);
        $productocategoria = $this->productocategoria->obtenerPorId($productocategoriaimagenActualizado->idrproductocategoria);
        $productocategoriaimagenActualizado->rproductocategoria = $this->productocategoria->obtenerCadenaConCategoria($productocategoria);

        $resultado = $productocategoriaimagenActualizado->toArray();

        return $this->response->setJSON([
            "productocategoria" => $resultado,
            "mensaje" => "Imagen de producto eliminada con éxito"
        ])->setStatusCode(200);
    }
}
