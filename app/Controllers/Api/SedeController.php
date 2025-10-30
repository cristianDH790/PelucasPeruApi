<?php

namespace App\Controllers\Api;

use App\Entities\SedeEntity;
use App\Helpers\Paginator;
use App\Helpers\Permisos;
use App\Models\EmpresaModel;
use App\Models\EstadoModel;
use App\Models\SedeModel;
use App\Models\UbigeoModel;
use App\Validation\SedeValidation;
use CodeIgniter\RESTful\ResourceController;

class SedeController extends ResourceController
{

    protected $sede;
    protected $estado;
    protected $empresa;
    protected $ubigeo;
    protected $ubigeo2;
    protected $permiso;

    public function __construct()
    {
        $this->permiso = new Permisos();
        $this->sede = new SedeModel();
        $this->estado = new EstadoModel();
        $this->empresa = new EmpresaModel();
        $this->ubigeo = new UbigeoModel();
        $this->ubigeo2 = new UbigeoController();
    }

    //para verificar los permisos
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

    public  function obtenerPorId($idsede)
    {
        if ($respuesta = $this->verificarPermiso('api_sede_obtenerPorId')) {
            return $respuesta;
        }
        $sede = $this->sede->obtenerPorId($idsede);

        if (!$sede) {
            return $this->respond(['mensaje' => 'No existe la sede solicitada'], 404);
        } else {

            $sedeEntity = new SedeEntity($sede);
            // Relaciones
            $sedeEntity->estado              = $this->estado->obtenerPorId($sede->idestado);
            $sedeEntity->empresa              = $this->empresa->obtenerPorId($sede->idempresa);
            // Aquí usas el método con jerarquía
            $sedeEntity->ubigeo = $this->obtenerUbigeoConJerarquia($sede->idubigeo);
            // Convertir a array
            $resultado = $sedeEntity->toArray();

            return $this->respond($resultado, 200);
        }
    }

    public function listar()
    {
        if ($respuesta = $this->verificarPermiso('api_sede_listar')) {
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
        $idempresa = (int) ($request->getVar('idEmpresa') ?? 0);
        $idubigeo = (int) ($request->getVar('idUbigeo') ?? 0);

        // Parámetros de paginación
        $pagina = (int) ($request->getVar('pagina') ?? 1);
        $registros = (int) ($request->getVar('registros') ?? 10);

        // Total de registros
        $total = $this->sede->buscarPorTotal(
            $parametro,
            $valor,
            $idestado,
            $idempresa,
            $idubigeo

        );

        $paginator = new Paginator($pagina, $registros, $total);
        // Consulta paginada
        $sedes = $this->sede->buscarPor(
            $ordencriterio,
            $ordentipo,
            $parametro,
            $valor,
            $idestado,
            $idempresa,
            $idubigeo,
            $paginator->getFirstElement(),
            $paginator->getSize()
        );

        // Convertir resultados a entidad
        $resultado = [];
        foreach ($sedes as $row) {
            $sedeEntity = new SedeEntity($row);
            // Relaciones
            $sedeEntity->estado = $this->estado->obtenerPorId($row->idestado);
            $sedeEntity->empresa = $this->empresa->obtenerPorId($row->idempresa);
            //  $sedeEntity->ubigeo = $this->ubigeo->obtenerPorId($row->idubigeo);
            // Aquí usas el método con jerarquía
            $sedeEntity->ubigeo = $this->obtenerUbigeoConJerarquia($row->idubigeo);
            //$sedeEntity->ubigeo = $this->armarJerarquiaUbigeo($this->ubigeo->obtenerPorId($row->idubigeo));

            $resultado[] = $sedeEntity->toArray();
        }

        // Respuesta JSON con paginación y datos
        return $this->respond([
            'paginator' => $paginator->enviar(),
            'content' => $resultado
        ]);
    }
    public function guardar()
    {
        if ($respuesta = $this->verificarPermiso('api_sede_guardar')) {
            return $respuesta;
        }
        $request = $this->request;

        $data = $request->getJSON(true);
        $sedeRequest = new SedeValidation();
        $errores = $sedeRequest->sedeGuardarValidation($data);

        if (!empty($errores)) {
            return $this->response
                ->setStatusCode(422)
                ->setJSON(['errors' => $errores]);
        }
        // return $this->respond([
        //     "mensaje" => 'sede registrado con éxito',
        //     "sede" => $data['ubigeo']
        // ], 201);
        $datosValidados =
            [
                'idestado' => (int) $data['estado']['idEstado'] ?? null,
                'idempresa' => (int)$data['empresa']['idEmpresa'] ?? null,
                'idubigeo' => (int)$data['ubigeo']['idUbigeo'] ?? null,
                'nombre' => $data['nombre'] ?? null,
                'urlcabecera' => $data['urlCabecera'] ?? null,
                'telefono' => $data['telefono'] ?? null,
                'direccion' => $data['direccion'] ?? null,
                'orden' => $data['orden'] ?? null,
                'latitud' => $data['latitud'] ?? null,
                'longitud' => $data['longitud'] ?? null
            ];


        // return $this->respond([
        //     "mensaje" => 'sede registrado con éxito',
        //     "sede" => $datosValidados
        // ], 201);

        $sedeId = $this->sede->guardar($datosValidados);
        $sede = $this->sede->find($sedeId);
        if ($sede) {

            $sedeEntity = new SedeEntity($sede);
            $sedeEntity->estado              = $this->estado->obtenerPorId($sede->idestado);
            $sedeEntity->empresa              = $this->empresa->obtenerPorId($sede->idempresa);
            // $sedeEntity->ubigeo             = $this->ubigeo->obtenerPorId($sede->idubigeo);
            // Aquí usas el método con jerarquía
            $sedeEntity->ubigeo = $this->obtenerUbigeoConJerarquia($sede->idubigeo);
            // $sedeEntity->ubigeo = $this->armarJerarquiaUbigeo($this->ubigeo->obtenerPorId($sede->idubigeo));

            return $this->respond([
                "mensaje" => 'sede registrado con éxito',
                "sede" => $sedeEntity->toArray()
            ], 201);
        } else {

            return $this->respond(["mensaje" => "Error al registrar sede"], 500);
        }
    }

    public function actualizar()
    {
        if ($respuesta = $this->verificarPermiso('api_sede_actualizar')) {
            return $respuesta;
        }
        $request = $this->request;

        $data = $request->getJSON(true);
        $sedeRequest = new sedeValidation();
        $errores = $sedeRequest->sedeGuardarValidation($data);

        if (!empty($errores)) {
            return $this->response
                ->setStatusCode(422)
                ->setJSON(['errors' => $errores]);
        }
        $datosValidados = [
            'idsede' => (int) $data['idSede'] ?? null,
            'idestado' => (int) $data['estado']['idEstado'] ?? null,
            'idempresa' => (int)$data['empresa']['idEmpresa'] ?? null,
            'idubigeo' => (int)$data['ubigeo']['idUbigeo'] ?? null,
            'nombre' => $data['nombre'] ?? null,
            'urlcabecera' => $data['urlCabecera'] ?? null,
            'telefono' => $data['telefono'] ?? null,
            'direccion' => $data['direccion'] ?? null,
            'orden' => $data['orden'] ?? null,
            'latitud' => $data['latitud'] ?? null,
            'longitud' => $data['longitud'] ?? null

        ];



        $sedeId = $this->sede->guardar($datosValidados);
        $sede = $this->sede->find($sedeId);
        if ($sede) {

            $sedeEntity = new SedeEntity($sede);
            $sedeEntity->estado = $this->estado->obtenerPorId($sede->idestado);
            $sedeEntity->empresa              = $this->empresa->obtenerPorId($sede->idestado);
            //$sedeEntity->ubigeo             = $this->ubigeo->obtenerPorId($sede->idubigeo);
            // Aquí usas el método con jerarquía
            $sedeEntity->ubigeo = $this->obtenerUbigeoConJerarquia($sede->idubigeo);
            //$sedeEntity->ubigeo = $this->armarJerarquiaUbigeo($this->ubigeo->obtenerPorId($sede->idubigeo));

            return $this->respond([
                "mensaje" => 'sede actualizado con éxito',
                "sede" =>  $sedeEntity->toArray()
            ], 201);
        } else {

            return $this->respond(["mensaje" => "Error al actualizar el sede"], 500);
        }
    }

    public function eliminar($idsede)
    {
        if ($respuesta = $this->verificarPermiso('api_sede_eliminar')) {
            return $respuesta;
        }
        if ($this->sede->eliminar($idsede)) {
            return $this->respond(['mensaje' => 'sede eliminado con éxito']);
        } else {
            return $this->failNotFound('No se encontró la sede');
        }
    }

    private function obtenerUbigeoConJerarquia($idubigeo)
    {
        if ($respuesta = $this->verificarPermiso('api_sede_obtenerUbigeo')) {
            return $respuesta;
        }
        $ubigeo = $this->ubigeo->obtenerPorId($idubigeo);
        if (!$ubigeo) {
            return null;
        }

        if (!empty($ubigeo->idrubigeo)) {
            $nivel1 = $this->ubigeo->obtenerPorId($ubigeo->idrubigeo);
            $ubigeo->rUbigeo = $nivel1;

            if ($nivel1 && !empty($nivel1->idrubigeo)) {
                $nivel2 = $this->ubigeo->obtenerPorId($nivel1->idrubigeo);
                $ubigeo->rUbigeo->rUbigeo = $nivel2;

                if ($nivel2 && !empty($nivel2->idrubigeo)) {
                    $nivel3 = $this->ubigeo->obtenerPorId($nivel2->idrubigeo);
                    $ubigeo->rUbigeo->rUbigeo->rUbigeo = $nivel3;
                }
            }
        }

        return $ubigeo;
    }
}
