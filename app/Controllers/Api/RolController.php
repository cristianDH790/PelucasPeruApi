<?php

namespace App\Controllers\Api;

use App\Controllers\BaseController;
use App\Entities\rolEntity;
use App\Helpers\Paginator;
use App\Models\ClaseModel;
use App\Models\EstadoModel;
use App\Models\RolModel;
use App\Validation\RolValidation;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\RESTful\ResourceController;

class RolController extends ResourceController
{
    protected $estado;
    protected $rol;
    protected $session;
    public function __construct()
    {
        $this->estado = new EstadoModel();
        $this->rol = new RolModel();
        $this->session = session();
    }
    public  function obtenerPorId($idrol)
    {
        $rol = $this->rol->obtenerPorId($idrol);

        if (!$rol) {
            return $this->respond(['mensaje' => 'No existe el rol solicitado'], 404);
        } else {

            $rolEntity = new RolEntity($rol);
            // Convertir a array

            //Relaciones
            $rolEntity->estado = $this->estado->obtenerPorId($rol->idestado);
            $resultado = $rolEntity->toArray();
            return $this->respond($resultado, 200);
        }
    }
    public function listar()
    {
        $request = $this->request;


        $ordencriterio = $request->getVar('ordenCriterio') ?? 'nombres';
        $ordentipo = $request->getVar('ordenTipo') ?? 'asc';
        $parametro = $request->getVar('parametro') ?? '';
        $valor = $request->getVar('valor') ?? '';
        $idestado = (int) ($request->getVar('idEstado') ?? 0);
        // Parámetros de paginación
        $pagina = (int) ($request->getVar('pagina') ?? 1);
        $registros = (int) ($request->getVar('registros') ?? 10);


        // Total de 
        // $parametro, $valor, $idclase
        $total = $this->rol->buscarPorTotal(
            $parametro,
            $valor,
            $idestado,
        );
        $paginator = new Paginator($pagina, $registros, $total);
        // $ordencriterio,$ordentipo,$parametro, $valor, $idclase, $inicio, $registros
        $estados = $this->rol->buscarPor(
            $ordencriterio,
            $ordentipo,
            $parametro,
            $valor,
            $idestado,
            $paginator->getFirstElement(),
            $paginator->getSize()
        );

        $resultado = [];
        foreach ($estados as $row) {
            $rolEntity = new rolEntity($row);


            //Relaciones
            $rolEntity->estado = $this->estado->obtenerPorId($row->idestado);
            $resultado[] = $rolEntity->toArray();
        };
        return $this->respond([
            'paginator' => $paginator->enviar(),
            'content' => $resultado
        ]);
    }

    public function guardar()
    {
        $request = $this->request;

        $data = $request->getJSON(true);
        $rolRequest = new RolValidation();
        $errores = $rolRequest->rolGuardarValidation($data);

        if (!empty($errores)) {
            return $this->response
                ->setStatusCode(422)
                ->setJSON(['errors' => $errores]);
        }

        $datosValidados = [
            'nombre' => $data['nombre'] ?? null,
            'idestado' => $data['estado']['idestado'] ?? null,
            'abr' => $data['abr'] ?? null,
        ];



        $rolId = $this->rol->guardar($datosValidados);
        $rol = $this->rol->find($rolId);
        if ($rol) {

            $rolEntity = new rolEntity($rol);
            $rolEntity->estado = $this->estado->obtenerPorId($rol->idestado);
            return $this->respond([
                "mensaje" => 'rol registrado con éxito',
                "rol" => $rolEntity->toArray()
            ], 201);
        } else {

            return $this->respond(["mensaje" => "Error al registrar rol"], 500);
        }
    }

    public function actualizar()
    {
        $request = $this->request;
        //$data['idrol'] = $id;
        $data = $request->getJSON(true);

        $rolRequest = new RolValidation();
        $errores = $rolRequest->rolActualizarValidation($data);

        if (!empty($errores)) {
            return $this->response
                ->setStatusCode(422)
                ->setJSON(['errors' => $errores]);
        }
        $datosValidados = [
            'idrol' => (int) $data['idrol'] ?? null,
            'nombre' => $data['nombre'] ?? null,
            'idestado' => $data['estado']['idestado'] ?? null,
            'abr' => $data['abr'] ?? null,
        ];

        $rolId = $this->rol->guardar($datosValidados);
        $rol = $this->rol->find($rolId);

        if ($rol) {
            $rolEntity = new rolEntity($rol);
            $rolEntity->estado = $this->estado->obtenerPorId($rol->idestado);
            return $this->respond([
                "mensaje" => 'rol actualizado con éxito',
                "rol" =>  $rolEntity->toArray()
            ], 201);
        } else {

            return $this->respond(["mensaje" => "Error al actualizar el rol"], 500);
        }
    }



    public function eliminar($idrol)
    {
        if ($this->rol->eliminar($idrol)) {
            return $this->respond(['mensaje' => 'rol eliminado con éxito']);
        } else {
            return $this->failNotFound('No se encontró la rol');
        }
    }
}
