<?php

namespace App\Controllers\Api;

use App\Entities\EmpresaEntity;
use App\Helpers\Paginator;
use App\Helpers\Permisos;
use App\Models\EstadoModel;
use App\Models\EmpresaModel;
use App\Models\MarcaModel;
use App\Models\PedidoModel;
use App\Models\ProductoModel;
use App\Models\SedeModel;
use App\Models\UsuarioModel;
use App\Validation\EmpresaValidation;
use CodeIgniter\RESTful\ResourceController;



class EmpresaController extends ResourceController
{

    protected $empresa;
    protected $estado;
    protected $sede;
    protected $marca;
    protected $producto;
    protected $usuario;
    protected $parametro;
    protected $permiso;
    protected $pedido;


    public function __construct()
    {
        $this->permiso = new Permisos();
        $this->empresa = new EmpresaModel();
        $this->estado = new EstadoModel();
        $this->sede = new SedeModel();
        $this->marca = new MarcaModel();
        $this->pedido = new PedidoModel();
        $this->producto = new ProductoModel();
        $this->usuario = new UsuarioModel();
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

    public  function obtenerPorId($idempresa)
    {
        //verificamos permiso
        if ($respuesta = $this->verificarPermiso('api_empresa_obtenerPorId')) {
            return $respuesta;
        }

        $empresa = $this->empresa->obtenerPorId($idempresa);

        if (!$empresa) {
            return $this->respond(['mensaje' => 'No existe la empresa solicitada'], 404);
        } else {

            $empresaEntity = new EmpresaEntity($empresa);
            // Relaciones
            $empresaEntity->estado              = $this->estado->obtenerPorId($empresa->idestado);
            // Convertir a array
            $resultado = $empresaEntity->toArray();

            return $this->respond($resultado, 200);
        }
    }

    public function listar()
    {
        if ($respuesta = $this->verificarPermiso('api_empresa_listar')) {
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

        // Parámetros de paginación
        $pagina = (int) ($request->getVar('pagina') ?? 1);
        $registros = (int) ($request->getVar('registros') ?? 10);

        // Total de registros
        $total = $this->empresa->buscarPorTotal(
            $parametro,
            $valor,
            $idestado

        );

        $paginator = new Paginator($pagina, $registros, $total);
        // Consulta paginada
        $empresas = $this->empresa->buscarPor(
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
        foreach ($empresas as $row) {
            $empresaEntity = new EmpresaEntity($row);
            // Relaciones
            $empresaEntity->estado = $this->estado->obtenerPorId($row->idestado);
            //campos extras
            $empresaEntity->sedes = $this->sede->contarSedesPorEmpresa($row->idempresa);
            $empresaEntity->marcas = $this->marca->contarMarcasPorEmpresa($row->idempresa);
            $empresaEntity->productos = $this->producto->contarProductosPorEmpresa($row->idempresa);
            $empresaEntity->usuarios = $this->usuario->obtenerAdministradoresPorEmpresa($row->idempresa);
            $empresaEntity->clientes = $this->usuario->obtenerClientesPorEmpresa($row->idempresa);
            $empresaEntity->pedidos = $this->pedido->contarPedidosPorEmpresa($row->idempresa);
            $empresaEntity->importetotal = $this->pedido->sumarTotalPedidosPorEmpresa($row->idempresa);

            $resultado[] = $empresaEntity->toArray();
        }

        // Respuesta JSON con paginación y datos
        return $this->respond([
            'paginator' => $paginator->enviar(),
            'content' => $resultado
        ]);
    }
    public function guardar()
    {
        if ($respuesta = $this->verificarPermiso('api_empresa_guardar')) {
            return $respuesta;
        }
        $request = $this->request;

        $data = $request->getJSON(true);
        $empresaRequest = new EmpresaValidation();
        $errores = $empresaRequest->empresaGuardarValidation($data);

        if (!empty($errores)) {
            return $this->response
                ->setStatusCode(422)
                ->setJSON(['errors' => $errores]);
        }

        $datosValidados =
            [
                'idestado' => $data['estado']['idEstado'] ?? null,
                'nombre' => $data['nombre'] ?? null,
                'razonsocial' => $data['razonSocial'] ?? null,
                'ruc' => $data['ruc'] ?? null,
                'direccion' => $data['direccion'] ?? null,
                'orden' => $data['orden'] ?? null,

            ];



        $empresaId = $this->empresa->guardar($datosValidados);
        $empresa = $this->empresa->find($empresaId);
        if ($empresa) {

            $empresaEntity = new EmpresaEntity($empresa);
            $empresaEntity->estado              = $this->estado->obtenerPorId($empresa->idestado);

            return $this->respond([
                "mensaje" => 'empresa registrado con éxito',
                "empresa" => $empresaEntity->toArray()
            ], 201);
        } else {

            return $this->respond(["mensaje" => "Error al registrar empresa"], 500);
        }
    }

    public function actualizar()
    {
        if ($respuesta = $this->verificarPermiso('api_empresa_actualizar')) {
            return $respuesta;
        }
        $request = $this->request;

        $data = $request->getJSON(true);
        $empresaRequest = new EmpresaValidation();
        $errores = $empresaRequest->empresaGuardarValidation($data);

        if (!empty($errores)) {
            return $this->response
                ->setStatusCode(422)
                ->setJSON(['errors' => $errores]);
        }
        $datosValidados = [
            'idempresa' => (int) $data['idEmpresa'] ?? null,
            'idestado' => $data['estado']['idEstado'] ?? null,
            'nombre' => $data['nombre'] ?? null,
            'razonsocial' => $data['razonSocial'] ?? null,
            'ruc' => $data['ruc'] ?? null,
            'direccion' => $data['direccion'] ?? null,
            'orden' => $data['orden'] ?? null,

        ];



        $empresaId = $this->empresa->guardar($datosValidados);
        $empresa = $this->empresa->find($empresaId);
        if ($empresa) {

            $empresaEntity = new EmpresaEntity($empresa);
            $empresaEntity->estado = $this->estado->obtenerPorId($empresa->idestado);

            return $this->respond([
                "mensaje" => 'empresa actualizado con éxito',
                "empresa" =>  $empresaEntity->toArray()
            ], 201);
        } else {

            return $this->respond(["mensaje" => "Error al actualizar el empresa"], 500);
        }
    }

    public function eliminar($idempresa)
    {
        if ($respuesta = $this->verificarPermiso('api_empresa_eliminar')) {
            return $respuesta;
        }
        if ($this->empresa->eliminar($idempresa)) {
            return $this->respond(['mensaje' => 'empresa eliminado con éxito']);
        } else {
            return $this->failNotFound('No se encontró la empresa');
        }
    }
}
