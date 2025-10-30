<?php

namespace App\Controllers\Api;

use App\Entities\EntregaEntity;
use App\Helpers\Paginator;
use App\Helpers\Permisos;
use App\Models\EmpresaModel;
use App\Models\EstadoModel;
use App\Models\EntregaModel;
use App\Validation\EntregaValidation;
use CodeIgniter\RESTful\ResourceController;

class EntregaController extends ResourceController
{

    protected $entrega;
    protected $estado;
    protected $empresa;
    protected $permiso;

    public function __construct()
    {
        $this->permiso = new Permisos();
        $this->entrega = new EntregaModel();
        $this->estado = new EstadoModel(); $this->empresa = new EmpresaModel();
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
    public  function obtenerPorId($identrega)
    {
        if ($respuesta = $this->verificarPermiso('api_entrega_obtenerPorId')) {
            return $respuesta;
        }
        $entrega = $this->entrega->obtenerPorId($identrega);

        if (!$entrega) {
            return $this->respond(['mensaje' => 'No existe la entrega solicitada'], 404);
        } else {

            $entregaEntity = new EntregaEntity($entrega);
            // Relaciones
            $entregaEntity->estado = $this->estado->obtenerPorId($entrega->idestado);
          
            // Convertir a array
            $resultado = $entregaEntity->toArray();

            return $this->respond($resultado, 200);
        }
    }

    public function listar()
    {
        if ($respuesta = $this->verificarPermiso('api_entrega_listar')) {
            return $respuesta;
        }
        // Verificar si es POST
        if (!$this->request->is('post')) {
            return $this->fail('Método no permitido. Se requiere POST.', 405);
        }

        $request = $this->request;

        // Parámetros de búsqueda y orden
        $ordencriterio = $request->getVar('ordenCriterio') ?? 'fechapublicacion';
        $ordentipo = $request->getVar('ordenTipo') ?? 'asc';
        $parametro = $request->getVar('parametro') ?? '';
        $valor = $request->getVar('valor') ?? '';
        $idestado = (int) ($request->getVar('idEstado') ?? 0);
        $idempresa = (int) ($request->getVar('idEmpresa') ?? 0);

        // Parámetros de paginación
        $pagina = (int) ($request->getVar('pagina') ?? 1);
        $registros = (int) ($request->getVar('registros') ?? 10);

        // Total de registros
        $total = $this->entrega->buscarPorTotal(
            $parametro,
            $valor,
            $idestado,


        );

        $paginator = new Paginator($pagina, $registros, $total);
        // Consulta paginada
        $entregas = $this->entrega->buscarPor(
            $ordencriterio,
            $ordentipo,
            $parametro,
            $valor,
            $idestado,

            $paginator->getFirstElement(),
            $paginator->getSize()
        );

        // Convertir resultados a entidad
        $resultado = [];
        foreach ($entregas as $row) {
            $entregaEntity = new entregaEntity($row);
            // Relaciones
            $entregaEntity->estado = $this->estado->obtenerPorId($row->idestado);


            $resultado[] = $entregaEntity->toArray();
        }

        // Respuesta JSON con paginación y datos
        return $this->respond([
            'paginator' => $paginator->enviar(),
            'content' => $resultado
        ]);
    }
    public function guardar()
    {
        if ($respuesta = $this->verificarPermiso('api_entrega_guardar')) {
            return $respuesta;
        }
        $request = $this->request;

        $data = $request->getJSON(true);
        $entregaRequest = new EntregaValidation();
        $errores = $entregaRequest->entregaGuardarValidation($data);

        if (!empty($errores)) {
            return $this->response
                ->setStatusCode(422)
                ->setJSON(['errors' => $errores]);
        }

        $datosValidados =
            [
                'idestado' => $data['estado']['idEstado'] ?? null,
                'nombre' => $data['nombre'] ?? null,
                'dias' => (int) $data['dias'] ?? null,
                'diashabiles' => $data['diasHabiles'] ?? null,
                'importeminimo' => (int) $data['importeMinimo'] ?? null,
                'minimogratis' => (int) $data['minimoGratis'] ?? null,
                'costoenvio' => (int) $data['costoEnvio'] ?? null,
                'horareferencia' => (int) $data['horaReferencia'] ?? null,
                'pesoxcostoenvio' => (int)$data['pesoxCostoEnvio'] ?? null,


            ];



        $entregaId = $this->entrega->guardar($datosValidados);
        $entrega = $this->entrega->find($entregaId);
        if ($entrega) {

            $entregaEntity = new EntregaEntity($entrega);
            $entregaEntity->estado = $this->estado->obtenerPorId($entrega->idestado);


            return $this->respond([
                "mensaje" => 'entrega registrado con éxito',
                "entrega" => $entregaEntity->toArray()
            ], 201);
        } else {

            return $this->respond(["mensaje" => "Error al registrar entrega"], 500);
        }
    }

    public function actualizar()
    {
        if ($respuesta = $this->verificarPermiso('api_entrega_actualizar')) {
            return $respuesta;
        }
        $request = $this->request;

        $data = $request->getJSON(true);
        $entregaRequest = new EntregaValidation();
        $errores = $entregaRequest->entregaGuardarValidation($data);

        if (!empty($errores)) {
            return $this->response
                ->setStatusCode(422)
                ->setJSON(['errors' => $errores]);
        }
        $datosValidados = [
            'identrega' => (int) $data['idEntrega'] ?? null,
            'idestado' => $data['estado']['idEstado'] ?? null,

            'nombre' => $data['nombre'] ?? null,
            'dias' => (int) $data['dias'] ?? null,
            'diashabiles' => $data['diasHabiles'] ?? null,
            'importeminimo' => (int) $data['importeMinimo'] ?? null,
            'minimogratis' => (int) $data['minimoGratis'] ?? null,
            'costoenvio' => (int) $data['costoEnvio'] ?? null,
            'horareferencia' => (int) $data['horaReferencia'] ?? null,
            'pesoxcostoenvio' => (int)$data['pesoxCostoEnvio'] ?? null,


        ];



        $entregaId = $this->entrega->guardar($datosValidados);
        $entrega = $this->entrega->find($entregaId);
        if ($entrega) {

            $entregaEntity = new EntregaEntity($entrega);
            $entregaEntity->estado = $this->estado->obtenerPorId($entrega->idestado);


            return $this->respond([
                "mensaje" => 'entrega actualizado con éxito',
                "entrega" =>  $entregaEntity->toArray()
            ], 201);
        } else {

            return $this->respond(["mensaje" => "Error al actualizar el entrega"], 500);
        }
    }

    public function eliminar($identrega)
    {
        if ($respuesta = $this->verificarPermiso('api_entrega_eliminar')) {
            return $respuesta;
        }
        if ($this->entrega->eliminar($identrega)) {
            return $this->respond(['mensaje' => 'entrega eliminado con éxito']);
        } else {
            return $this->failNotFound('No se encontró la entrega');
        }
    }
}
