<?php

namespace App\Controllers\Api;

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

class ProductoImagenController extends ResourceController
{

    protected $productoImagen;
    protected $producto;
    protected $estado;
    protected $parametro;
    protected $productocolor;
    protected $color;
    protected $permiso;
    public function __construct()
    {
        $this->productoImagen = new ProductoImagenModel();
        $this->producto = new ProductoModel();
        $this->productocolor = new ProductoColorModel();
        $this->estado = new EstadoModel();
        $this->color = new ColorModel();
        $this->parametro = new ParametroModel();
        $this->permiso = new Permisos();
    }

    public  function obtenerPorId($idproductoImagen)
    {

        $productoImagen = $this->productoImagen->obtenerPorId($idproductoImagen);

        if (!$productoImagen) {
            return $this->respond(['mensaje' => 'No existe la producto Imagen solicitada'], 404);
        } else {

            $productoImagenEntity = new ProductoImagenEntity($productoImagen);


            $productoImagenEntity->estado = $this->estado->obtenerPorId($productoImagen->idestado);
            // $productoImagenEntity->producto = $this->producto->obtenerPorId($productoImagen->idproducto);
            $productoImagenEntity->pdestacado = $this->parametro->obtenerPorId($productoImagen->idpdestacado);
            $productocolorEntity = $this->productocolor->obtenerPorId($productoImagen->idproductocolor);

            if ($productocolorEntity) {
                $productocolorEntity->color = $this->color->obtenerPorId($productocolorEntity->idcolor);
            }

            $productoImagenEntity->productocolor = $productocolorEntity;
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
            'idproductocolor'      => $data['productoColor']['idProductoColor'] ?? null,
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
            // $productoImagenEntity->producto = $this->producto->obtenerPorId($productoImagen->idproducto);
            $productoImagenEntity->pdestacado = $this->parametro->obtenerPorId($productoImagen->idpdestacado);

            $productocolorEntity = $this->productocolor->obtenerPorId($productoImagen->idproductocolor);

            if ($productocolorEntity) {
                $productocolorEntity->color = $this->color->obtenerPorId($productocolorEntity->idcolor);
            }

            $productoImagenEntity->productocolor = $productocolorEntity;
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
            'idproductocolor'      => $data['productoColor']['idProductoColor'] ?? null,
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
            // $productoImagenEntity->producto = $this->producto->obtenerPorId($productoImagen->idproducto);
            $productoImagenEntity->pdestacado = $this->parametro->obtenerPorId($productoImagen->idpdestacado);
            $productocolorEntity = $this->productocolor->obtenerPorId($productoImagen->idproductocolor);

            if ($productocolorEntity) {
                $productocolorEntity->color = $this->color->obtenerPorId($productocolorEntity->idcolor);
            }

            $productoImagenEntity->productocolor = $productocolorEntity;
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


    // public function uploadImagen1()
    // {
    //     $idproductoImagen = $this->request->getPost('idProductoImagen');
    //     $productoImagen = $this->productoImagen->find($idproductoImagen);

    //     if (!$productoImagen) {
    //         return $this->response->setJSON(["mensaje" => 'No existe la productoImagen solicitada'])->setStatusCode(404);
    //     }

    //     // Manejo como array para evitar errores con objetos
    //     if (!is_array($productoImagen)) {
    //         $productoImagen = (array) $productoImagen;
    //     }

    //     $file = $this->request->getFile('archivo');
    //     if (!$file || !$file->isValid()) {
    //         return $this->response->setJSON(["mensaje" => 'Debe de seleccionar una imagen'])->setStatusCode(400);
    //     }

    //     // Elimina imagen anterior
    //     $imgPath = FCPATH . env('URL_IMAGE') . '/productoimagen/' . ($productoImagen['urlimagen'] ?? '');
    //     if (!empty($productoImagen['urlimagen']) && file_exists($imgPath)) {
    //         unlink($imgPath);
    //     }

    //     // Genera nombre amigable
    //     $nombre = is_array($productoImagen) ? ($productoImagen['nombre'] ?? '') : ($productoImagen->nombre ?? '');


    //     $nombreCompleto = trim($nombre);
    //     $urlamigable = Util::urls_amigables($nombreCompleto ?: 'productoImagen');
    //     $nombrearchivo = $productoImagen[''] . '-' . $urlamigable . '-escritorio.' . $file->getExtension();

    //     // Asegura carpeta
    //     $destino = FCPATH . env('URL_IMAGE') . '/productoImagen';
    //     if (!is_dir($destino)) {
    //         mkdir($destino, 0777, true);
    //     }

    //     // Mueve el archivo
    //     $file->move($destino, $nombrearchivo);

    //     // Actualiza en DB
    //     $this->productoImagen->update($idproductoImagen, ['urlimagen' => $nombrearchivo]);

    //     // Obtener actualizado y convertir si es necesario
    //     $productoImagenActualizado = $this->productoImagen->find($idproductoImagen);

    //     $productoImagenEntity = new \App\Entities\ProductoImagenEntity($productoImagenActualizado);
    //     $productoImagenEntity->estado = $this->estado->obtenerPorId($productoImagenActualizado->idestado);
    //     $productoImagenEntity->producto = $this->producto->obtenerPorId($productoImagenActualizado->idproducto);
    //     $productoImagenEntity->pdestacado = $this->parametro->obtenerPorId($productoImagenActualizado->idpdestacado);


    //     return $this->response->setJSON([
    //         "productoimagen" => $productoImagenActualizado->toArray(),
    //         "mensaje" => "Imagen cargada con éxito",
    //         "request" => $this->request->getPost()
    //     ])->setStatusCode(200);
    // }

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
        // $productoImagenEntity->producto = $this->producto->obtenerPorId($productoImagenActualizado->idproducto);
        $productoImagenEntity->pdestacado = $this->parametro->obtenerPorId($productoImagenActualizado->idpdestacado);
        $productocolorEntity = $this->productocolor->obtenerPorId($productoImagenActualizado->idproductocolor);

        if ($productocolorEntity) {
            $productocolorEntity->color = $this->color->obtenerPorId($productocolorEntity->idcolor);
        }

        $productoImagenEntity->productocolor = $productocolorEntity;

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
        // $productoImagenEntity->producto = $this->producto->obtenerPorId($productoImagenActualizado->idproducto);
        $productoImagenEntity->pdestacado = $this->parametro->obtenerPorId($productoImagenActualizado->idpdestacado);

        $productocolorEntity = $this->productocolor->obtenerPorId($productoImagenActualizado->idproductocolor);

        if ($productocolorEntity) {
            $productocolorEntity->color = $this->color->obtenerPorId($productocolorEntity->idcolor);
        }

        $productoImagenEntity->productocolor = $productocolorEntity;


        // Convertir a array
        $resultado = $productoImagenEntity->toArray();

        return $this->response->setJSON([
            "productoImagen" => $resultado,
            "mensaje" => "Imagen de producto eliminada con éxito"
        ])->setStatusCode(200);
    }


    public function imagenesCargaMasiva()
    {
        $archivo = $this->request->getFile('archivo');
        if (!$archivo || !$archivo->isValid()) {
            return $this->respond([
                'mensajes' => [['referencia' => 'N/A', 'mensaje' => 'Debe incluir un archivo Excel', 'estado' => 0]]
            ], 400);
        }

        $spreadsheet = IOFactory::load($archivo->getTempName());
        $sheet = $spreadsheet->getActiveSheet();
        $rows = $sheet->toArray();

        $headers = array_map(function ($h) {
            $h = strtolower(trim($h));
            $h = str_replace([' ', '_', 'á', 'é', 'í', 'ó', 'ú', 'ñ'], ['', '', 'a', 'e', 'i', 'o', 'u', 'n'], $h);
            return $h;
        }, $rows[0]);
        unset($rows[0]);

        $respuestaFinal = [];

        foreach ($rows as $row) {
            $value = array_combine($headers, array_map('trim', $row));

            if (empty($value['nombre']) || empty($value['producto color']) || empty($value['imagen'])) continue;

            // Buscar productocolor
            $productocolor = $this->productocolor->where('nombre', strtoupper($value['producto color']))->first();
            if (!$productocolor) {
                $respuestaFinal[] = [
                    'referencia' => $value['nombre'],
                    'mensaje' => 'No existe el productocolor',
                    'estado' => 0
                ];
                continue;
            }

            // Estado y destacado
            $idestado = (isset($value['estado']) && strtolower($value['estado']) === 'activo') ? 346 : 347;
            $idpdestacado = (isset($value['destacado']) && strtolower($value['destacado']) === 'si') ? 572 : 573;

            // Generar nombre amigable
            $nombreCompleto = $value['nombre'] . ' ' . $value['producto color'];
            $urlamigable = Util::urls_amigables($nombreCompleto);

            $filePath = FCPATH . 'uploads/temp/' . $value['imagen']; // suponiendo que las imágenes estén en temp
            if (!file_exists($filePath)) {
                $respuestaFinal[] = [
                    'referencia' => $value['nombre'],
                    'mensaje' => 'Archivo de imagen no encontrado: ' . $value['imagen'],
                    'estado' => 0
                ];
                continue;
            }

            // Asegura carpeta destino
            $destino = FCPATH . env('URL_IMAGE') . '/productoimagen';
            if (!is_dir($destino)) mkdir($destino, 0777, true);

            $extension = pathinfo($value['imagen'], PATHINFO_EXTENSION);
            $nombreArchivo = $productocolor->idproductocolor . '-' . $urlamigable . '.' . $extension;

            if (!copy($filePath, $destino . '/' . $nombreArchivo)) {
                $respuestaFinal[] = [
                    'referencia' => $value['nombre'],
                    'mensaje' => 'Error al mover la imagen',
                    'estado' => 0
                ];
                continue;
            }

            // Guardar en DB
            try {
                $this->productoImagen->insert([
                    'idproductocolor' => $productocolor->idproductocolor,
                    'idpdestacado' => $idpdestacado,
                    'idestado' => $idestado,
                    'nombre' => $nombreCompleto,
                    'urlimagen' => $nombreArchivo,
                    'fecha' => date('Y-m-d H:i:s')
                ]);
            } catch (\Throwable $e) {
                $respuestaFinal[] = [
                    'referencia' => $value['nombre'],
                    'mensaje' => 'Error al guardar en DB: ' . $e->getMessage(),
                    'estado' => 0
                ];
                continue;
            }

            $respuestaFinal[] = [
                'referencia' => $value['nombre'],
                'mensaje' => 'Imagen cargada correctamente',
                'estado' => 1
            ];
        }

        return $this->respond(['mensajes' => $respuestaFinal], 200);
    }
}
