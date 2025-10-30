<?php

namespace App\Controllers\Api;

use App\Entities\SuscripcionEntity; // ✅ correcto

use App\Helpers\Paginator;
use App\Helpers\Permisos;
use App\Models\SuscripcionModel;
use App\Validation\SuscripcionValidation;
use CodeIgniter\RESTful\ResourceController;

class SuscripcionController extends ResourceController
{

    protected $suscripcion;
    protected $estado;
    protected $permiso;
    public function __construct()
    {
        $this->permiso = new Permisos();
        $this->suscripcion = new SuscripcionModel();
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

    public  function obtenerPorId($idsuscripcion)
    {
        if ($respuesta = $this->verificarPermiso('api_suscripcion_obtenerPorId')) {
            return $respuesta;
        }
        $suscripcion = $this->suscripcion->obtenerPorId($idsuscripcion);

        if (!$suscripcion) {
            return $this->respond(['mensaje' => 'No existe la suscripcion solicitada'], 404);
        } else {

            $suscripcionEntity = new SuscripcionEntity($suscripcion);
            // $suscripcionEntity->idsuscripcion  = $suscripcion->idsuscripcion;
            // $suscripcionEntity->correo  = $suscripcion->correo;
            // $suscripcionEntity->fecha  = $suscripcion->fecha;


            // Convertir a array
            $resultado = $suscripcionEntity->toArray();

            return $this->respond($resultado, 200);
        }
    }

    public function listar()
    {
        if ($respuesta = $this->verificarPermiso('api_suscripcion_listar')) {
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
        // Parámetros de paginación
        $pagina = (int) ($request->getVar('pagina') ?? 1);
        $registros = (int) ($request->getVar('registros') ?? 10);

        // Total de registros
        $total = $this->suscripcion->buscarPorTotal(
            $parametro,
            $valor,
        );

        $paginator = new Paginator($pagina, $registros, $total);
        // Consulta paginada
        $suscripcions = $this->suscripcion->buscarPor(
            $ordencriterio,
            $ordentipo,
            $parametro,
            $valor,
            $paginator->getFirstElement(),
            $paginator->getSize()
        );

        // Convertir resultados a entidad
        $resultado = [];
        foreach ($suscripcions as $row) {

            $suscripcionEntity = new SuscripcionEntity($row);
            // $suscripcionEntity->idsuscripcion  = $row->idsuscripcion;
            // $suscripcionEntity->correo  = $row->correo;
            // $suscripcionEntity->fecha  = $row->fecha;

            $resultado[] = $suscripcionEntity->toArray();
        }

        // Respuesta JSON con paginación y datos
        return $this->respond([
            'paginator' => $paginator->enviar(),
            'content' => $resultado
        ]);
    }
    public function guardar()
    {
        if ($respuesta = $this->verificarPermiso('api_suscripcion_guardar')) {
            return $respuesta;
        }
        $request = $this->request;

        $data = $request->getJSON(true);
        $suscripcionRequest = new SuscripcionValidation();
        $errores = $suscripcionRequest->suscripcionGuardarValidation($data);

        if (!empty($errores)) {
            return $this->response
                ->setStatusCode(422)
                ->setJSON(['errors' => $errores]);
        }

        $datosValidados = [
            'correo' => $data['correo'] ?? null,
        ];



        $suscripcionId = $this->suscripcion->guardar($datosValidados);
        $suscripcion = $this->suscripcion->find($suscripcionId);
        if ($suscripcion) {

            $suscripcionEntity = new suscripcionEntity($suscripcion);

            return $this->respond([
                "mensaje" => 'suscripcion registrado con éxito',
                "suscripcion" => $suscripcionEntity->toArray()
            ], 201);
        } else {

            return $this->respond(["mensaje" => "Error al registrar suscripcion"], 500);
        }
    }

    public function actualizar()
    {
        if ($respuesta = $this->verificarPermiso('api_suscripcion_actualizar')) {
            return $respuesta;
        }
        $request = $this->request;
        //$data['idsuscripcion'] = $id;
        $data = $request->getJSON(true);

        $suscripcionRequest = new SuscripcionValidation();
        $errores = $suscripcionRequest->suscripcionActualizarValidation($data);

        if (!empty($errores)) {
            return $this->response
                ->setStatusCode(422)
                ->setJSON(['errors' => $errores]);
        }
        $datosValidados = [
            'idsuscripcion' => (int) $data['idsuscripcion'] ?? null,
            'correo' => $data['correo'] ?? null,
        ];

        $suscripcionId = $this->suscripcion->guardar($datosValidados);
        $suscripcion = $this->suscripcion->find($suscripcionId);
        // log_message('debug', 'ID de suscripción recibido tras guardar: ' . print_r($suscripcionId, true));

        if ($suscripcion) {
            $suscripcionEntity = new SuscripcionEntity($suscripcion);

            return $this->respond([
                "mensaje" => 'suscripcion actualizado con éxito',
                "suscripcion" =>  $suscripcionEntity->toArray()
            ], 201);
        } else {

            return $this->respond(["mensaje" => "Error al actualizar el suscripcion"], 500);
        }
    }


    public function eliminar($idsuscripcion)
    {
        if ($respuesta = $this->verificarPermiso('api_suscripcion_eliminar')) {
            return $respuesta;
        }
        if ($this->suscripcion->eliminar($idsuscripcion)) {
            return $this->respond(['mensaje' => 'suscripcion eliminado con éxito']);
        } else {
            return $this->failNotFound('No se encontró la suscripcion');
        }
    }
}
