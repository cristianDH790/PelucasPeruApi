<?php

namespace App\Controllers\Api;

use App\Entities\EmpresaEntity;
use App\Entities\UsuarioEntity;
use App\Helpers\Excel\ReporteExcelUsuarios;
use App\Helpers\Paginator;
use App\Helpers\Permisos;
use App\Models\EmpresaModel;
use App\Models\EstadoModel;
use App\Models\ParametroModel;
use App\Models\PerfilModel;
use App\Models\UsuarioModel;
use App\Validation\UsuarioValidation;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\RESTful\ResourceController;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class UsuarioController extends ResourceController
{
    protected $permiso;
    protected $usuario;
    protected $perfil;
    protected $parametro;
    protected $estado;
    protected $empresa;

    public function __construct()
    {
        $this->permiso = new Permisos();
        $this->usuario = new UsuarioModel();
        $this->perfil = new PerfilModel();
        $this->estado = new EstadoModel();
        $this->parametro = new ParametroModel();
        $this->empresa = new EmpresaModel();
    }
  
    public  function obtenerPorId($idusuario)
    {
       
        $usuario = $this->usuario->obtenerPorId($idusuario);
        if (!$usuario) {
            return $this->respond(['mensaje' => 'No existe el usuario solicitado'], 404);
        } else {

            $usuarioEntity = new UsuarioEntity($usuario);


            // Relaciones
            $usuarioEntity->estado = $this->estado->obtenerPorId($usuario->idestado);
            $usuarioEntity->perfil = $this->perfil->obtenerPorId($usuario->idperfil);
            $usuarioEntity->pdocumento = $this->parametro->obtenerPorId($usuario->idpdocumento);

            // Convertir a array
            $resultado = $usuarioEntity->toArray();



            return $this->respond($resultado, 200);
        }
    }
    public function listar()
    {
        
        $request = $this->request;

        // Parámetros de búsqueda y orden
        $ordencriterio = $request->getVar('ordenCriterio') ?? 'idusuario';
        $ordentipo = $request->getVar('ordenTipo') ?? 'asc';
        $parametro = $request->getVar('parametro') ?? '';
        $valor = $request->getVar('valor') ?? '';
        $idestado = (int) ($request->getVar('idEstado') ?? 0);
        $idperfil = (int) ($request->getVar('idPerfil') ?? 0);
        // $fecharango = (int) ($request->getVar('fechaRango') ?? 0);

        // Parámetros de paginación
        $pagina = (int) ($request->getVar('pagina') ?? 1);
        $registros = (int) ($request->getVar('registros') ?? 10);

        // Total de registros
        $total = $this->usuario->buscarPorTotal(
            $parametro,
            $valor,
            $idestado,
            $idperfil
        );

        $paginator = new Paginator($pagina, $registros, $total);

        // Consulta paginada
        $usuarios = $this->usuario->buscarPor(
            $ordencriterio,
            $ordentipo,
            $parametro,
            $valor,
            $idestado,
            $idperfil,
            $paginator->getFirstElement(),
            $paginator->getSize()
        );

        $resultado = [];
        foreach ($usuarios as $row) {
            $usuarioEntity = new UsuarioEntity($row);
            // Relaciones
            $usuarioEntity->estado = $this->estado->obtenerPorId($row->idestado);
            $usuarioEntity->perfil = $this->perfil->obtenerPorId($row->idperfil);
            $usuarioEntity->pdocumento = $this->parametro->obtenerPorId($row->idpdocumento);

            $resultado[] = $usuarioEntity->toArray();
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
        $usuarioRequest = new UsuarioValidation();
        $errores = $usuarioRequest->UsuarioGuardarValidation($data);

        if (!empty($errores)) {
            return $this->response
                ->setStatusCode(422)
                ->setJSON(['errors' => $errores]);
        }

        $datosValidados = [
            'idestado' => $data['estado']['idEstado'] ?? null,
            'idperfil' => $data['perfil']['idPerfil'] ?? null,
            'idpdocumento' => $data['pDocumento']['idParametro'] ?? null,
            'documento' => $data['documento'] ?? null,
            'nombres' => $data['nombres'] ?? null,
            'papellido' => $data['pApellido'] ?? null,
            'sapellido' => $data['sApellido'] ?? null,
            'fechanacimiento' => $data['fechaNacimiento'] ?? null,
            'sexo' => $data['sexo'] ?? null,
            'correo' => $data['correo'] ?? null,
            'telefono' => $data['telefono'] ?? null,
            'login' => $data['login'] ?? null,
            'password' => password_hash($data['password'] ?? null, PASSWORD_DEFAULT),

        ];


        $usuarioId = $this->usuario->guardar($datosValidados);
        $usuario = $this->usuario->find($usuarioId);
        if ($usuario) {

            $usuarioEntity = new UsuarioEntity($usuario);
            $usuarioEntity->estado = $this->estado->obtenerPorId($usuario->idestado);
            $usuarioEntity->perfil = $this->perfil->obtenerPorId($usuario->idperfil);
            $usuarioEntity->pdocumento = $this->parametro->obtenerPorId($usuario->idpdocumento);

            return $this->respond([
                "mensaje" => 'Usuario registrado con éxito',
                "usuario" => $usuarioEntity->toArray()
            ], 201);
        } else {
            return $this->respond(["mensaje" => "Error al registrar usuario"], 500);
        }
    }

    public function actualizar()
    {
        
        $request = $this->request;

        $data = $request->getJSON(true);
        $usuarioRequest = new UsuarioValidation();
        $errores = $usuarioRequest->UsuarioGuardarValidation($data);

        if (!empty($errores)) {
            return $this->response
                ->setStatusCode(422)
                ->setJSON(['errors' => $errores]);
        }

        $datosValidados = [
            'idusuario' => $data['idUsuario'] ?? null,
            'idestado' => $data['estado']['idEstado'] ?? null,
            'idperfil' => $data['perfil']['idPerfil'] ?? null,
            'idpdocumento' => $data['pDocumento']['idParametro'] ?? null,
            'documento' => $data['documento'] ?? null,
            'nombres' => $data['nombres'] ?? null,
            'papellido' => $data['pApellido'] ?? null,
            'sapellido' => $data['sApellido'] ?? null,
            'fechanacimiento' => $data['fechaNacimiento'] ?? null,
            'sexo' => $data['sexo'] ?? null,
            'correo' => $data['correo'] ?? null,
            'telefono' => $data['telefono'] ?? null,
            'boletin' => $data['boletin'] ?? null,
            'login' => $data['login'] ?? null,
            'password' => password_hash($data['password'] ?? null, PASSWORD_DEFAULT),
        ];

        $usuarioId = $this->usuario->guardar($datosValidados);
        $usuario = $this->usuario->find($usuarioId);
        if ($usuario) {

            $usuarioEntity = new UsuarioEntity($usuario);
            $usuarioEntity->estado = $this->estado->obtenerPorId($usuario->idestado);
            $usuarioEntity->perfil = $this->perfil->obtenerPorId($usuario->idperfil);
            $usuarioEntity->pdocumento = $this->parametro->obtenerPorId($usuario->idpdocumento);

            return $this->respond([
                "mensaje" => 'Usuario actualizado con éxito',
                "usuario" => $usuarioEntity->toArray()
            ], 201);
        } else {
            return $this->respond(["mensaje" => "Error al actualizar usuario"], 500);
        }
    }

    public function eliminar($idusuario)
    {
       
        if ($this->usuario->eliminar($idusuario)) {
            return $this->respond(['mensaje' => 'Usuario eliminado con éxito']);
        } else {
            return $this->failNotFound('No se encontró el usuario');
        }
    }

    public function reporte()
    {
        header("Access-Control-Allow-Origin: *");
        header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
        header("Access-Control-Allow-Headers: Content-Type, Authorization");

        if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
            exit; // para la petición preflight
        }
        $request = $this->request;


        //           ordenCriterio: string;
        //   ordenTipo: string;
        //   parametro: string;
        //   valor: string;
        //   idEstado: number;
        //   idPerfil: number;
        //   idEmpresa: number;
        //   idUbigeo: number;
        //   pagina: number;
        //   registros: number;

        // Parámetros de búsqueda y orden
        $ordencriterio = $request->getVar('ordenCriterio') ?? 'fecha';
        $ordentipo = $request->getVar('ordenTipo') ?? 'asc';
        $parametro = $request->getVar('parametro') ?? '';
        $valor = $request->getVar('valor') ?? '';
        $idestado = (int) ($request->getVar('idEstado') ?? 0);
        $idperfil = (int) ($request->getVar('idPerfil') ?? 0);
        $idempresa = (int) ($request->getVar('idEmpresa') ?? 0);
        $idubigeo = (int) ($request->getVar('idUbigeo') ?? 0);

        $usuarioAdm = $request->getVar('usuario') ?? '';
        // Parámetros de paginación
        $pagina = (int) ($request->getVar('pagina') ?? 1);
        $registros = (int) ($request->getVar('registros') ?? 10);

        // Total de registros
        $total = $this->usuario->buscarPorTotal(
            $parametro,
            $valor,
            $idestado,
            $idperfil,
            $idempresa,
            $idubigeo
        );

        $paginator = new Paginator($pagina, $registros, $total);

        // Consulta paginada
        $productos = $this->usuario->buscarPor(
            $ordencriterio,
            $ordentipo,
            $parametro,
            $valor,
            $idestado,
            $idperfil,
            $idempresa,
            $idubigeo,
            $paginator->getFirstElement(),
            $paginator->getSize()
        );

        $resultado = [];
        foreach ($productos as $row) {
            $usuarioEntity = new UsuarioEntity($row);
            $usuarioEntity->estado = $this->estado->obtenerPorId($row->idestado);
            $usuarioEntity->perfil = $this->perfil->obtenerPorId($row->idperfil);
            $usuarioEntity->pdocumento = $this->parametro->obtenerPorId($row->idpdocumento);
            $usuarioEntity->pedidos = $this->usuario->obtenerPedidos($row->idusuario);
            $usuarioEntity->importetotal = $this->usuario->obtenerImporteTotal($row->idusuario);
            $resultado[] = $usuarioEntity->toArray();
        }
        $nombreUsuario = trim(
            ($usuarioAdm->nombres ?? '') . ' ' .
                ($usuarioAdm->pApellido ?? '') . ' ' .
                ($usuarioAdm->sApellido ?? '')
        );

        // Generar Excel
        $spreadsheet = ReporteExcelUsuarios::generarExcel($resultado, $nombreUsuario);

        $filename = "Reporte-de-usuarios-" . date("d-m-Y-H-i-s") . ".xlsx";

        // Limpiar buffer para evitar errores con headers
        if (ob_get_length()) ob_end_clean();

        // Headers para descarga
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header("Content-Disposition: attachment; filename=\"$filename\"");
        header('Cache-Control: max-age=0');
        header('Expires: 0');
        header('Pragma: public');

        // Escribir archivo
        $writer = new Xlsx($spreadsheet);
        $writer->save('php://output');

        exit;
    }
}
