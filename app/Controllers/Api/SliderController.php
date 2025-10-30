<?php

namespace App\Controllers\Api;

use App\Entities\SliderEntity;
use App\Helpers\Paginator;
use App\Helpers\Permisos;
use App\Helpers\Util;
use App\Models\EstadoModel;
use App\Models\ParametroModel;
use App\Models\ProductoCategoriaModel;
use App\Models\SliderModel;
use App\Validation\SliderValidation;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\RESTful\ResourceController;

class SliderController extends ResourceController
{
    protected $permiso;
    protected $slider;
    protected $estado;
    protected $parametro;
    protected $productocategoria;

    public function __construct()
    {
        $this->permiso = new Permisos();
        $this->slider = new SliderModel();
        $this->estado = new EstadoModel();
        $this->parametro = new ParametroModel();
        $this->productocategoria = new ProductoCategoriaModel();
    }


    public  function obtenerPorId($idslider)
    {

        $slider = $this->slider->obtenerPorId($idslider);

        if (!$slider) {
            return $this->respond(['mensaje' => 'No existe la slider solicitada'], 404);
        } else {

            $sliderEntity = new SliderEntity($slider);


            $sliderEntity->estado = $this->estado->obtenerPorId($slider->idestado);
            $sliderEntity->productocategoria = $this->productocategoria->obtenerPorId($slider->idproductocategoria);
            $sliderEntity->precurso = $this->parametro->obtenerPorId($slider->idptiporecurso);
            // Convertir a array
            $resultado = $sliderEntity->toArray();

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
        $idpcategoria = (int) ($request->getVar('idpCategoria') ?? 0);



        // Parámetros de paginación
        $pagina = (int) ($request->getVar('pagina') ?? 1);
        $registros = (int) ($request->getVar('registros') ?? 10);

        // Total de registros
        $total = $this->slider->buscarPorTotal(
            $parametro,
            $valor,
            $idestado,
            $idpcategoria,

        );

        $paginator = new Paginator($pagina, $registros, $total);
        // Consulta paginada
        $sliders = $this->slider->buscarPor(
            $ordencriterio,
            $ordentipo,
            $parametro,
            $valor,
            $idestado,
            $idpcategoria,
            $paginator->getFirstElement(),
            $paginator->getSize()
        );

        // Convertir resultados a entidad
        $resultado = [];
        foreach ($sliders as $row) {
            $sliderEntity = new SliderEntity($row);


            $sliderEntity->estado = $this->estado->obtenerPorId($row->idestado);
            $sliderEntity->productocategoria = $this->productocategoria->obtenerPorId($row->idproductocategoria);
            $sliderEntity->precurso = $this->parametro->obtenerPorId($row->idptiporecurso);


            $resultado[] = $sliderEntity->toArray();
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
        $sliderRequest = new SliderValidation();
        $errores = $sliderRequest->sliderGuardarValidation($data);

        if (!empty($errores)) {
            return $this->response
                ->setStatusCode(422)
                ->setJSON(['errors' => $errores]);
        }

        $datosValidados = [
            'idestado'      => $data['estado']['idEstado'] ?? null,
            'nombre'        => $data['nombre'] ?? null,
            'descripcion'   => $data['descripcion'] ?? null,
            // 'idpcategoria'  =>  $data['pCategoria']['idParametro'] ?? null,
            'idptiporecurso'  =>  $data['pRecurso']['idParametro'] ?? null,
            'idproductocategoria'  =>  $data['rProductoCategoria']['idProductoCategoria'] ?? null,
            'urlrecurso'    => $data['urlRecurso'] ?? null,
            'orden'         => $data['orden'] ?? null,
        ];


        $sliderId = $this->slider->guardar($datosValidados);
        $slider = $this->slider->find($sliderId);
        if ($slider) {
            $sliderEntity = new SliderEntity($slider);
            $sliderEntity->estado = $this->estado->obtenerPorId($slider->idestado);
            $sliderEntity->productocategoria = $this->productocategoria->obtenerPorId($slider->idproductocategoria);
            $sliderEntity->precurso = $this->parametro->obtenerPorId($slider->idptiporecurso);
            return $this->respond([
                "mensaje" => 'slider registrado con éxito',
                "slider" => $sliderEntity->toArray()
            ], 201);
        } else {

            return $this->respond(["mensaje" => "Error al registrar slider"], 500);
        }
    }

    public function actualizar()
    {

        $request = $this->request;

        $data = $request->getJSON(true);
        $sliderRequest = new SliderValidation();
        $errores = $sliderRequest->SliderActualizarValidation($data);

        if (!empty($errores)) {
            return $this->response
                ->setStatusCode(422)
                ->setJSON(['errors' => $errores]);
        }
        $datosValidados = [
            'idslider' => (int) $data['idSlider'] ?? null,
            'idestado'      => $data['estado']['idEstado'] ?? null,
            'nombre'        => $data['nombre'] ?? null,
            'descripcion'   => $data['descripcion'] ?? null,
            // 'idpcategoria'  =>  $data['pCategoria']['idParametro'] ?? null,
            'idptiporecurso'  =>  $data['pRecurso']['idParametro'] ?? null,
            'idproductocategoria'  =>  $data['rProductoCategoria']['idProductoCategoria'] ?? null,
            'urlrecurso'    => $data['urlRecurso'] ?? null,
            'orden'         => $data['orden'] ?? null,
        ];


        $sliderId = $this->slider->guardar($datosValidados);
        $slider = $this->slider->find($sliderId);
        if ($slider) {

            $sliderEntity = new SliderEntity($slider);
            $sliderEntity->estado = $this->estado->obtenerPorId($slider->idestado);
            $sliderEntity->productocategoria = $this->productocategoria->obtenerPorId($slider->idproductocategoria);
            $sliderEntity->precurso = $this->parametro->obtenerPorId($slider->idptiporecurso);
            return $this->respond([
                "mensaje" => 'slider actualizado con éxito',
                "slider" =>  $sliderEntity->toArray()
            ], 201);
        } else {

            return $this->respond(["mensaje" => "Error al actualizar el slider"], 500);
        }
    }

    public function eliminar($idslider)
    {
        if ($respuesta = $this->verificarPermiso('api_slider_eliminar')) {
            return $respuesta;
        }
        if ($this->slider->eliminar($idslider)) {
            return $this->respond(['mensaje' => 'slider eliminado con éxito']);
        } else {
            return $this->failNotFound('No se encontró la slider');
        }
    }


    public function uploadImagen1()
    {

        $idslider = $this->request->getPost('idSlider');
        $slider = $this->slider->find($idslider);

        if (!$slider) {
            return $this->response->setJSON(["mensaje" => 'No existe la slider solicitada'])->setStatusCode(404);
        }

        // Manejo como array para evitar errores con objetos
        if (!is_array($slider)) {
            $slider = (array) $slider;
        }

        $file = $this->request->getFile('archivo');
        if (!$file || !$file->isValid()) {
            return $this->response->setJSON(["mensaje" => 'Debe de seleccionar una imagen'])->setStatusCode(400);
        }

        // Elimina imagen anterior
        $imgPath = FCPATH . env('URL_IMAGE') . '/slider/' . ($slider['urlimagen1'] ?? '');
        if (!empty($slider['urlimagen1']) && file_exists($imgPath)) {
            unlink($imgPath);
        }

        // Genera nombre amigable
        $nombre = is_array($slider) ? ($slider['nombre'] ?? '') : ($slider->nombre ?? '');


        $nombreCompleto = trim($nombre);
        $urlamigable = Util::urls_amigables($nombreCompleto ?: 'slider');
        $nombrearchivo = $slider['idslider'] . '-' . $urlamigable . '-escritorio.' . $file->getExtension();

        // Asegura carpeta
        $destino = FCPATH . env('URL_IMAGE') . '/slider';
        if (!is_dir($destino)) {
            mkdir($destino, 0777, true);
        }

        // Mueve el archivo
        $file->move($destino, $nombrearchivo);

        // Actualiza en DB
        $this->slider->update($idslider, ['urlimagen1' => $nombrearchivo]);

        // Obtener actualizado y convertir si es necesario
        $sliderActualizado = $this->slider->find($idslider);


        $sliderEntity = new SliderEntity($sliderActualizado);
        $sliderEntity->estado = $this->estado->obtenerPorId($sliderActualizado->idestado);
        $sliderEntity->productocategoria = $this->productocategoria->obtenerPorId($sliderActualizado->idproductocategoria);
        $sliderEntity->precurso = $this->parametro->obtenerPorId($sliderActualizado->idptiporecurso);

        return $this->response->setJSON([
            "slider" => $sliderActualizado->toArray(),
            "mensaje" => "Imagen cargada con éxito",
            "request" => $this->request->getPost()
        ])->setStatusCode(200);
    }
    public function uploadImagen2()
    {

        $idslider = $this->request->getPost('idSlider');
        $slider = $this->slider->find($idslider);

        if (!$slider) {
            return $this->response->setJSON(["mensaje" => 'No existe la slider solicitada'])->setStatusCode(404);
        }

        // Manejo como array para evitar errores con objetos
        if (!is_array($slider)) {
            $slider = (array) $slider;
        }

        $file = $this->request->getFile('archivo');
        if (!$file || !$file->isValid()) {
            return $this->response->setJSON(["mensaje" => 'Debe de seleccionar una imagen'])->setStatusCode(400);
        }

        // Elimina imagen anterior
        $imgPath = FCPATH . env('URL_IMAGE') . '/slider/' . ($slider['urlimagen2'] ?? '');
        if (!empty($slider['urlimagen2']) && file_exists($imgPath)) {
            unlink($imgPath);
        }

        // Genera nombre amigable
        $nombre = is_array($slider) ? ($slider['nombre'] ?? '') : ($slider->nombre ?? '');

        $nombreCompleto = trim($nombre);
        $urlamigable = Util::urls_amigables($nombreCompleto ?: 'slider');
        $nombrearchivo = $slider['idslider'] . '-' . $urlamigable . '-celular.' . $file->getExtension();

        // Asegura carpeta
        $destino = FCPATH . env('URL_IMAGE') . '/slider';
        if (!is_dir($destino)) {
            mkdir($destino, 0777, true);
        }

        // Mueve el archivo
        $file->move($destino, $nombrearchivo);

        // Actualiza en DB
        $this->slider->update($idslider, ['urlimagen2' => $nombrearchivo]);

        // Obtener actualizado y convertir si es necesario
        $sliderActualizado = $this->slider->find($idslider);


        $sliderEntity = new SliderEntity($sliderActualizado);
        $sliderEntity->estado = $this->estado->obtenerPorId($sliderActualizado->idestado);
        $sliderEntity->productocategoria = $this->productocategoria->obtenerPorId($sliderActualizado->idproductocategoria);
        $sliderEntity->precurso = $this->parametro->obtenerPorId($sliderActualizado->idptiporecurso);

        return $this->response->setJSON([
            "slider" => $sliderEntity->toArray(),
            "mensaje" => "Imagen cargada con éxito",
            "request" => $this->request->getPost()
        ])->setStatusCode(200);
    }



    public function eliminarImagen()
    {


        $data = $this->request->getPost() ?: $this->request->getJSON(true);
        $idslider = $data['idSlider'] ?? null;
        $tipo = $data['tipo'] ?? null;

        if (empty($idslider)) {
            return $this->response->setJSON(['errors' => ['ID de slider no recibido']])->setStatusCode(400);
        }

        // Solo aceptar 'urlimagen1' o 'urlimagen2'
        if (!in_array($tipo, ['urlimagen1', 'urlimagen2'])) {
            return $this->response->setJSON(['errors' => ['Tipo de imagen no válido']])->setStatusCode(400);
        }

        $slider = $this->slider->find($idslider);
        if (!$slider) {
            return $this->response->setJSON(['errors' => ['No existe el slider solicitado']])->setStatusCode(404);
        }

        // Obtener la URL de la imagen según el tipo
        $urlimagen = is_array($slider) ? ($slider[$tipo] ?? null) : $slider->{$tipo};

        $imgPath = FCPATH . env('URL_IMAGE') . '/slider/' . $urlimagen;
        if (!empty($urlimagen) && file_exists($imgPath)) {
            unlink($imgPath);
        }

        // Actualizar el campo a null en la BD
        $this->slider->update($idslider, [$tipo => null]);

        // Obtener slider actualizado
        $sliderActualizado = $this->slider->find($idslider);
        $sliderEntity = new SliderEntity($sliderActualizado);

        // Agregar relaciones
        $sliderEntity->estado = $this->estado->obtenerPorId($sliderActualizado->idestado);
        $sliderEntity->productocategoria = $this->parametro->obtenerPorId($sliderActualizado->idproductocategoria);
        $sliderEntity->precurso = $this->parametro->obtenerPorId($sliderActualizado->idptiporecurso);

        return $this->response->setJSON([
            "slider" => $sliderEntity->toArray(),
            "mensaje" => "Imagen eliminada con éxito"
        ])->setStatusCode(200);
    }

    // public function eliminarImagen()
    // {
    //     if ($respuesta = $this->verificarPermiso('api_slider_eliminar_imagen')) {
    //         return $respuesta;
    //     }
    //     // $idslider = $this->request->getPost('idslider');

    //     $idslider = $this->request->getPost('idSlider') ?? $this->request->getJSON(true)['idSlider'] ?? null;
    //     $tipo = $this->request->getPost('tipo') ?? $this->request->getJSON(true)['tipo'] ?? null;

    //     if (empty($idslider)) {
    //         return $this->response->setJSON(['errors' => ['ID de slider no recibido']])->setStatusCode(400);
    //     }

    //     $slider = $this->slider->find($idslider);

    //     if (!$slider) {
    //         return $this->response->setJSON(['errors' => ['No existe el slider solicitado']])->setStatusCode(404);
    //     }

    //     $urlimagen = is_array($slider) ? ($slider['urlimagen1'] ?? null) : $slider->urlimagen;
    //     $imgPath = FCPATH . env('URL_IMAGE') . '/slider/' . $urlimagen;
    //     if (!empty($urlimagen) && file_exists($imgPath)) {
    //         unlink($imgPath);
    //     }

    //     // Aquí $idslider nunca será null
    //     $this->slider->update($idslider, ['urlimagen1' => null]);

    //     $sliderActualizado = $this->slider->find($idslider);
    //     $sliderEntity = new sliderEntity($sliderActualizado);


    //     $sliderEntity->estado = $this->estado->obtenerPorId($sliderActualizado->idestado);
    //     $sliderEntity->pcategoria = $this->parametro->obtenerPorId($sliderActualizado->idpcategoria);
    //     $sliderEntity->precurso = $this->parametro->obtenerPorId($sliderActualizado->idptiporecurso);



    //     // Convertir a array
    //     $resultado = $sliderEntity->toArray();

    //     return $this->response->setJSON([
    //         "slider" => $resultado,
    //         "mensaje" => "Imagen de producto eliminada con éxito"
    //     ])->setStatusCode(200);
    // }

}
