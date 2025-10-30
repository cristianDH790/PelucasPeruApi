<?php

namespace App\Controllers\Api;

use App\Entities\DestinoEntity;

use App\Helpers\Paginator;
use App\Models\DestinoModel;
use App\Models\EstadoModel;
use App\Models\ParametroModel;
use App\Models\UbigeoModel;
use App\Models\UsuarioModel;
use App\Validation\DestinoValidation;
use App\Validation\empresaValidation;
use CodeIgniter\RESTful\ResourceController;

class DestinoController extends ResourceController
{

    protected $destino;
    protected $estado;
    protected $parametro;
    protected $ubigeo;
    protected $usuario;



    public function __construct()
    {
        $this->destino = new DestinoModel();
        $this->estado = new EstadoModel();
        $this->parametro = new ParametroModel();
        $this->ubigeo = new UbigeoModel();
        $this->usuario = new UsuarioModel();
    }

    public  function obtenerPorId($iddestino)
    {
        $destino = $this->destino->obtenerPorId($iddestino);

        if (!$destino) {
            return $this->respond(['mensaje' => 'No existe la destino solicitada'], 404);
        } else {

            $destinoEntity = new DestinoEntity($destino);
            // Relaciones
            $destinoEntity->estado = $this->estado->obtenerPorId($destino->idestado);
            $destinoEntity->ptipo = $this->parametro->obtenerPorId($destino->idptipo);
            $destinoEntity->usuario = $this->usuario->obtenerPorId($destino->idusuario);
            $destinoEntity->ubigeo = $this->ubigeo->obtenerPorId($destino->idubigeo);
            // Convertir a array
            $resultado = $destinoEntity->toArray();

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
        $ordencriterio = $request->getVar('ordenCriterio') ?? 'fechapublicacion';
        $ordentipo = $request->getVar('ordenTipo') ?? 'asc';
        $parametro = $request->getVar('parametro') ?? '';
        $valor = $request->getVar('valor') ?? '';
        $idestado = (int) ($request->getVar('idEstado') ?? 0);
        $idusuario = (int) ($request->getVar('idUsuario') ?? 0);
        $idubigeo = (int) ($request->getVar('idUbigeo') ?? 0);
        $idptipo = (int) ($request->getVar('idpTipo') ?? 0);

        // Parámetros de paginación
        $pagina = (int) ($request->getVar('pagina') ?? 1);
        $registros = (int) ($request->getVar('registros') ?? 10);

        // Total de registros
        $total = $this->destino->buscarPorTotal(
            $parametro,
            $valor,
            $idestado,
            $idusuario,
            $idubigeo,
            $idptipo

        );

        $paginator = new Paginator($pagina, $registros, $total);
        // Consulta paginada
        $empresas = $this->destino->buscarPor(
            $ordencriterio,
            $ordentipo,
            $parametro,
            $valor,
            $idestado,
            $idusuario,
            $idubigeo,
            $idptipo,
            $paginator->getFirstElement(),
            $paginator->getSize()
        );

        // Convertir resultados a entidad
        $resultado = [];
        foreach ($empresas as $row) {
            $destinoEntity = new DestinoEntity($row);
            // Relaciones
            $destinoEntity->estado = $this->estado->obtenerPorId($row->idestado);
            $destinoEntity->ptipo = $this->parametro->obtenerPorId($row->idptipo);
            $destinoEntity->usuario = $this->usuario->obtenerPorId($row->idusuario);
            $destinoEntity->ubigeo = $this->ubigeo->obtenerPorId($row->idubigeo);

            $resultado[] = $destinoEntity->toArray();
        }

        // Respuesta JSON con paginación y datos
        return $this->respond([
            'paginator' => $paginator->enviar(),
            'content' => $resultado
        ]);
    }
    public function guardar()
    {
        $request = $this->request;

        $data = $request->getJSON(true);
        $destinoRequest = new DestinoValidation();
        $errores = $destinoRequest->destinoGuardarValidation($data);

        if (!empty($errores)) {
            return $this->response
                ->setStatusCode(422)
                ->setJSON(['errors' => $errores]);
        }

        $datosValidados =
            [
                'idestado' => $data['estado']['idEstado'] ?? null,
                'idubigeo' => $data['ubigeo']['idUbigeo'] ?? null,
                'idusuario' => $data['usuario']['idUsuario'] ?? null,
                'idptipo' => $data['pTipo']['idParametro'] ?? null,
                'alias' => $data['alais'] ?? null,
                'nombres' => $data['nombres'] ?? null,
                'apellidos' => $data['apellidos'] ?? null,
                'dni' => $data['dni'] ?? null,
                'direccion' => $data['direccion'] ?? null,
                'referencia' => $data['referencia'] ?? null,
                'telefono' => $data['telefono'] ?? null,
                'latitud' => $data['latitud'] ?? null,
                'longitud' => $data['longitud'] ?? null,
                'fecha' => $data['fecha'] ?? null,
            ];



        $destinoId = $this->destino->guardar($datosValidados);
        $destino = $this->destino->find($destinoId);
        if ($destino) {

            $destinoEntity = new destinoEntity($destino);
            $destinoEntity->estado = $this->estado->obtenerPorId($destino->idestado);

            return $this->respond([
                "mensaje" => 'destino registrado con éxito',
                "destino" => $destinoEntity->toArray()
            ], 201);
        } else {

            return $this->respond(["mensaje" => "Error al registrar destino"], 500);
        }
    }

    public function actualizar()
    {
        $request = $this->request;

        $data = $request->getJSON(true);
        $destinoRequest = new DestinoValidation();
        $errores = $destinoRequest->destinoGuardarValidation($data);

        if (!empty($errores)) {
            return $this->response
                ->setStatusCode(422)
                ->setJSON(['errors' => $errores]);
        }
        $datosValidados = [
            'iddestino' => (int) $data['idDestino'] ?? null,
            'idestado' => $data['estado']['idEstado'] ?? null,
            'idubigeo' => $data['ubigeo']['idUbigeo'] ?? null,
            'idusuario' => $data['usuario']['idUsuario'] ?? null,
            'idptipo' => $data['pTipo']['idParametro'] ?? null,
            'alias' => $data['alais'] ?? null,
            'nombres' => $data['nombres'] ?? null,
            'apellidos' => $data['apellidos'] ?? null,
            'dni' => $data['dni'] ?? null,
            'direccion' => $data['direccion'] ?? null,
            'referencia' => $data['referencia'] ?? null,
            'telefono' => $data['telefono'] ?? null,
            'latitud' => $data['latitud'] ?? null,
            'longitud' => $data['longitud'] ?? null,
            'fecha' => $data['fecha'] ?? null,
        ];



        $destinoId = $this->destino->guardar($datosValidados);
        $destino = $this->destino->find($destinoId);
        if ($destino) {

            $destinoEntity = new destinoEntity($destino);
            $destinoEntity->estado = $this->estado->obtenerPorId($destino->idestado);

            return $this->respond([
                "mensaje" => 'destino actualizado con éxito',
                "destino" =>  $destinoEntity->toArray()
            ], 201);
        } else {

            return $this->respond(["mensaje" => "Error al actualizar el destino"], 500);
        }
    }

    public function eliminar($iddestino)
    {
        if ($this->destino->eliminar($iddestino)) {
            return $this->respond(['mensaje' => 'destino eliminado con éxito']);
        } else {
            return $this->failNotFound('No se encontró la destino');
        }
    }
}
