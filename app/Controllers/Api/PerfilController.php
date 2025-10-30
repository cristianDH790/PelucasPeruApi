<?php

namespace App\Controllers\Api;

use App\Controllers\BaseController;
use App\Entities\PerfilEntity;
use App\Helpers\Paginator;
use App\Models\ClaseModel;
use App\Models\EstadoModel;
use App\Models\PerfilModel;
use App\Validation\PerfilValidation;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\RESTful\ResourceController;

class PerfilController extends ResourceController
{
    protected $estado;
    protected $perfil;
    protected $session;
    public function __construct()
    {
        $this->estado = new EstadoModel();
        $this->perfil = new PerfilModel();
        $this->session = session();
    }
    public  function obtenerPorId($idperfil)
    {
        $perfil = $this->perfil->obtenerPorId($idperfil);

        if (!$perfil) {
            return $this->respond(['mensaje' => 'No existe el perfil solicitado'], 404);
        } else {

            $perfilEntity = new PerfilEntity($perfil);
            //Relaciones
            $perfilEntity->estado = $this->estado->obtenerPorId($perfil->idestado);
            // Convertir a array
            $resultado = $perfilEntity->toArray();
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
        $total = $this->perfil->buscarPorTotal(
            $parametro,
            $valor,
            $idestado,
        );
        $paginator = new Paginator($pagina, $registros, $total);
        // $ordencriterio,$ordentipo,$parametro, $valor, $idclase, $inicio, $registros
        $estados = $this->perfil->buscarPor(
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
            $perfilEntity = new PerfilEntity($row);


            //Relaciones
            $perfilEntity->estado = $this->estado->obtenerPorId($row->idestado);
            $resultado[] = $perfilEntity->toArray();
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
        $perfilRequest = new PerfilValidation();
        $errores = $perfilRequest->perfilGuardarValidation($data);

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



        $perfilId = $this->perfil->guardar($datosValidados);
        $perfil = $this->perfil->find($perfilId);
        if ($perfil) {

            $perfilEntity = new perfilEntity($perfil);
            $perfilEntity->estado = $this->estado->obtenerPorId($perfil->idestado);
            return $this->respond([
                "mensaje" => 'perfil registrado con éxito',
                "perfil" => $perfilEntity->toArray()
            ], 201);
        } else {

            return $this->respond(["mensaje" => "Error al registrar perfil"], 500);
        }
    }

    public function actualizar()
    {
        $request = $this->request;
        //$data['idperfil'] = $id;
        $data = $request->getJSON(true);

        $perfilRequest = new perfilValidation();
        $errores = $perfilRequest->perfilActualizarValidation($data);

        if (!empty($errores)) {
            return $this->response
                ->setStatusCode(422)
                ->setJSON(['errors' => $errores]);
        }
        $datosValidados = [
            'idperfil' => (int) $data['idperfil'] ?? null,
            'nombre' => $data['nombre'] ?? null,
            'idestado' => $data['estado']['idestado'] ?? null,
            'abr' => $data['abr'] ?? null,
        ];

        $perfilId = $this->perfil->guardar($datosValidados);
        $perfil = $this->perfil->find($perfilId);

        if ($perfil) {
            $perfilEntity = new perfilEntity($perfil);
            $perfilEntity->estado = $this->estado->obtenerPorId($perfil->idestado);
            return $this->respond([
                "mensaje" => 'perfil actualizado con éxito',
                "perfil" =>  $perfilEntity->toArray()
            ], 201);
        } else {

            return $this->respond(["mensaje" => "Error al actualizar el perfil"], 500);
        }
    }



    public function eliminar($idperfil)
    {
        if ($this->perfil->eliminar($idperfil)) {
            return $this->respond(['mensaje' => 'perfil eliminado con éxito']);
        } else {
            return $this->failNotFound('No se encontró la perfil');
        }
    }
}
