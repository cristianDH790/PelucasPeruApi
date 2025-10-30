<?php

namespace App\Controllers\Api;

use App\Controllers\BaseController;
use App\Entities\EmpresaEntity;
use App\Entities\MarcaEntity;
use App\Entities\ProductoImagenEntity;
use App\Entities\PublicidadEntity;
use App\Helpers\Paginator;
use App\Helpers\Permisos;
use App\Helpers\Util;
use App\Models\EmpresaModel;
use App\Models\EstadoModel;
use App\Models\MarcaModel;
use App\Models\ParametroModel;
use App\Models\ProductoBaseModel;
use App\Models\ProductoImagenModel;
use App\Models\PublicidadModel;
use App\Validation\MarcaValidation;
use App\Validation\ProductoImagenValidation;
use App\Validation\PublicidadValidation;
use CodeIgniter\RESTful\ResourceController;

class PublicidadController extends ResourceController
{

    protected $publicidad;
    protected $productoBase;
    protected $estado;
    protected $parametro;
    protected $permiso;

    public function __construct()
    {
        $this->publicidad = new PublicidadModel();
        $this->estado = new EstadoModel();
        $this->parametro = new ParametroModel();
        $this->permiso = new Permisos();
    }

    private function verificarPermiso(string $permiso)
    {


        $token = $this->request->getHeaderLine('X-Authorization');
        $token = str_replace('Bearer ', '', $token);

        if (!$token) {
            return $this->failUnauthorized('Token no proporcionado');
        }
        $resultado = $this->permiso->obtenerPermisosDesdeToken($token);

        if (isset($resultado['error'])) {
            return $this->failUnauthorized($resultado['error']);
        }

        $permisos = $resultado['authorities'] ?? [];

        if (!in_array($permiso, $permisos)) {
            return $this->failForbidden("No tienes permiso: {$permiso}");
        }

        return null; // Permiso concedido
    }

    public  function obtenerPorId($idpublicidad)
    {
        if ($respuesta = $this->verificarPermiso('api_publicidad_ObtenerPorId')) {
            return $respuesta;
        }
        $publicidad = $this->publicidad->obtenerPorId($idpublicidad);

        if (!$publicidad) {
            return $this->respond(['mensaje' => 'No existe la publicidad solicitada'], 404);
        } else {

            $publicidadEntity = new PublicidadEntity($publicidad);


            $publicidadEntity->estado = $this->estado->obtenerPorId($publicidad->idestado);
            $publicidadEntity->destino = $this->parametro->obtenerPorId($publicidad->idpdestino);

            // Convertir a array
            $resultado = $publicidadEntity->toArray();

            return $this->respond($resultado, 200);
        }
    }

    public function listar()
    {
        if ($respuesta = $this->verificarPermiso('api_publicidad_listar')) {
            return $respuesta;
        }
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
        $idpdestino = (int) ($request->getVar('idPdestino') ?? 0);

        // Parámetros de paginación
        $pagina = (int) ($request->getVar('pagina') ?? 1);
        $registros = (int) ($request->getVar('registros') ?? 10);

        // Total de registros
        $total = $this->publicidad->buscarPorTotal(
            $parametro,
            $valor,
            $idestado,
            $idpdestino
        );

        $paginator = new Paginator($pagina, $registros, $total);
        // Consulta paginada
        $productoImagens = $this->publicidad->buscarPor(
            $ordencriterio,
            $ordentipo,
            $parametro,
            $valor,
            $idestado,
            $idpdestino,
            $paginator->getFirstElement(),
            $paginator->getSize()
        );

        // Convertir resultados a entidad
        $resultado = [];
        foreach ($productoImagens as $row) {
            $publicidadEntity = new PublicidadEntity($row);
            $publicidadEntity->estado = $this->estado->obtenerPorId($row->idestado);
            $publicidadEntity->destino = $this->parametro->obtenerPorId($row->idpdestino);


            $resultado[] = $publicidadEntity->toArray();
        }


        return $this->respond([
            'paginator' => $paginator->enviar(),
            'content' => $resultado
        ]);
    }
    public function guardar()
    {
        if ($respuesta = $this->verificarPermiso('api_publicidad_guardar')) {
            return $respuesta;
        }
        $request = $this->request;

        $data = $request->getJSON(true);
        $publicidadRequest = new PublicidadValidation();
        $errores = $publicidadRequest->publicidadGuardarValidation($data);

        if (!empty($errores)) {
            return $this->response
                ->setStatusCode(422)
                ->setJSON(['errors' => $errores]);
        }

        $datosValidados = [
            'idestado'      => $data['estado']['idEstado'] ?? null,
            'idpdestino'  => $data['destino']['idParametro'] ?? null,
            'nombre'        => $data['nombre'] ?? null,
            'titulo'        => $data['titulo'] ?? null,
            'urlimagen'    => $data['urlImagen'] ?? null,
            'urlrecurso'      => $data['urlRecurso'] ?? null,
            'inicio'   => $data['inicio'] ?? null,
            'termino'   => $data['termino'] ?? null,
        ];


        $publicidadId = $this->publicidad->guardar($datosValidados);
        $publicidad = $this->publicidad->find($publicidadId);
        if ($publicidad) {
            $publicidadEntity = new PublicidadEntity($publicidad);
            $publicidadEntity->estado = $this->estado->obtenerPorId($publicidad->idestado);
            $publicidadEntity->destino = $this->parametro->obtenerPorId($publicidad->idpdestino);


            return $this->respond([
                "mensaje" => 'publicidad registrado con éxito',
                "publicidad" => $publicidadEntity->toArray()
            ], 201);
        } else {

            return $this->respond(["mensaje" => "Error al registrar publicidad"], 500);
        }
    }

    public function actualizar()
    {
        if ($respuesta = $this->verificarPermiso('api_publicidad_actualizar')) {
            return $respuesta;
        }
        $request = $this->request;

        $data = $request->getJSON(true);
        $publicidadRequest = new PublicidadValidation();
        $errores = $publicidadRequest->publicidadGuardarValidation($data);

        if (!empty($errores)) {
            return $this->response
                ->setStatusCode(422)
                ->setJSON(['errors' => $errores]);
        }
        $datosValidados = [
            'idpublicidad' => (int) $data['idPublicidad'] ?? null,
            'idestado'      => $data['estado']['idEstado'] ?? null,
            'idpdestino'  => $data['destino']['idParametro'] ?? null,
            'nombre'        => $data['nombre'] ?? null,
            'titulo'        => $data['titulo'] ?? null,
            'urlimagen'    => $data['urlImagen'] ?? null,
            'urlrecurso'      => $data['urlRecurso'] ?? null,
            'inicio'   => $data['inicio'] ?? null,
            'termino'   => $data['termino'] ?? null,
        ];


        $publicidadId = $this->publicidad->guardar($datosValidados);
        $publicidad = $this->publicidad->find($publicidadId);
        if ($publicidad) {

            $publicidadEntity = new PublicidadEntity($publicidad);
            $publicidadEntity->estado = $this->estado->obtenerPorId($publicidad->idestado);
            $publicidadEntity->destino = $this->parametro->obtenerPorId($publicidad->idpdestino);

            return $this->respond([
                "mensaje" => 'Publicidad actualizado con éxito',
                "publicidad" =>  $publicidadEntity->toArray()
            ], 201);
        } else {

            return $this->respond(["mensaje" => "Error al actualizar el producto Imagen"], 500);
        }
    }

    public function eliminar($idpublicidad)
    {
        if ($respuesta = $this->verificarPermiso('api_publicidad_eliminar')) {
            return $respuesta;
        }
        if ($this->publicidad->eliminar($idpublicidad)) {
            return $this->respond(['mensaje' => 'Publicidad eliminado con éxito']);
        } else {
            return $this->failNotFound('No se encontró la publicidad');
        }
    }


    public function uploadImagen()
    {
        if ($respuesta = $this->verificarPermiso('api_publicidad_upload1')) {
            return $respuesta;
        }
        $idpublicidad = $this->request->getPost('idPublicidad');
        $publicidad = $this->publicidad->find($idpublicidad);

        if (!$publicidad) {
            return $this->response->setJSON(["mensaje" => 'No existe la publicidad solicitada'])->setStatusCode(404);
        }

        // Manejo como array para evitar errores con objetos
        if (!is_array($publicidad)) {
            $publicidad = (array) $publicidad;
        }

        $file = $this->request->getFile('archivo');
        if (!$file || !$file->isValid()) {
            return $this->response->setJSON(["mensaje" => 'Debe de seleccionar una imagen'])->setStatusCode(400);
        }

        // Elimina imagen anterior
        $imgPath = FCPATH . env('URL_IMAGE') . '/publicidad/' . ($publicidad['urlimagen'] ?? '');
        if (!empty($publicidad['urlimagen']) && file_exists($imgPath)) {
            unlink($imgPath);
        }

        // Genera nombre amigable
        $nombre = is_array($publicidad) ? ($publicidad['nombre'] ?? '') : ($publicidad->nombre ?? '');


        $nombreCompleto = trim($nombre);
        $urlamigable = Util::urls_amigables($nombreCompleto ?: 'publicidad');
        $nombrearchivo = $publicidad['idpublicidad'] . '-' . $urlamigable . '.' . $file->getExtension();

        // Asegura carpeta
        $destino = FCPATH . env('URL_IMAGE') . '/publicidad';
        if (!is_dir($destino)) {
            mkdir($destino, 0777, true);
        }

        // Mueve el archivo
        $file->move($destino, $nombrearchivo);

        // Actualiza en DB
        $this->publicidad->update($idpublicidad, ['urlimagen' => $nombrearchivo]);

        // Obtener actualizado y convertir si es necesario
        $publicidadActualizado = $this->publicidad->find($idpublicidad);

        $publicidadEntity = new PublicidadEntity($publicidadActualizado);
        $publicidadEntity->estado = $this->estado->obtenerPorId($publicidadActualizado->idestado);
        $publicidadEntity->destino = $this->parametro->obtenerPorId($publicidadActualizado->idpdestino);



        return $this->response->setJSON([
            "publicidad" => $publicidadActualizado->toArray(),
            "mensaje" => "Imagen cargada con éxito",
            "request" => $this->request->getPost()
        ])->setStatusCode(200);
    }




    public function eliminarImagen()
    {
        if ($respuesta = $this->verificarPermiso('api_publicidad_eliminar_imagen')) {
            return $respuesta;
        }
        // $idpublicidad = $this->request->getPost('idpublicidad');

        $idpublicidad = $this->request->getPost('idPublicidad') ?? $this->request->getJSON(true)['idPublicidad'] ?? null;

        if (empty($idpublicidad)) {
            return $this->response->setJSON(['errors' => ['ID de publicidad no recibido']])->setStatusCode(400);
        }

        $publicidad = $this->publicidad->find($idpublicidad);

        if (!$publicidad) {
            return $this->response->setJSON(['errors' => ['No existe el publicidad solicitado']])->setStatusCode(404);
        }

        $urlimagen = is_array($publicidad) ? ($publicidad['urlimagen'] ?? null) : $publicidad->urlimagen;
        $imgPath = FCPATH . env('URL_IMAGE') . '/publicidad/' . $urlimagen;
        if (!empty($urlimagen) && file_exists($imgPath)) {
            unlink($imgPath);
        }

        // Aquí $idpublicidad nunca será null
        $this->publicidad->update($idpublicidad, ['urlimagen' => null]);

        $publicidadActualizado = $this->publicidad->find($idpublicidad);
        $publicidadEntity = new PublicidadEntity($publicidadActualizado);

        $publicidadEntity->estado = $this->estado->obtenerPorId($publicidadActualizado->idestado);
        $publicidadEntity->destino = $this->parametro->obtenerPorId($publicidadActualizado->idpdestino);





        // Convertir a array
        $resultado = $publicidadEntity->toArray();

        return $this->response->setJSON([
            "publicidad" => $resultado,
            "mensaje" => "Imagen de producto eliminada con éxito"
        ])->setStatusCode(200);
    }
}
