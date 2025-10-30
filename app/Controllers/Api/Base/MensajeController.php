<?php

namespace App\Controllers\Api\Base;

use App\Entities\MensajeEntity;
use App\Helpers\Paginator;
use App\Helpers\Permisos;
use App\Models\ClaseModel;
use App\Models\EstadoModel;
use App\Models\MensajeModel;
use App\Validation\MensajeValidation;

use CodeIgniter\RESTful\ResourceController;

class MensajeController extends ResourceController
{
    protected $mensaje;
    protected $estado;
    protected $clase;
    protected $session;
    protected $permiso;
    public function __construct()
    {
        $this->permiso = new Permisos();
        $this->mensaje = new MensajeModel();
        $this->estado = new EstadoModel();
        $this->clase = new ClaseModel();

        $this->session = session();
    }

    

    public  function obtenerPorId($idconfiguracion)
    {
        
        $mensaje = $this->mensaje->obtenerPorId($idconfiguracion);
        if (!$mensaje) {
            return $this->respond(['mensaje' => 'No existe el mensaje solicitado'], 404);
        } else {

            $mensajeEntity = new MensajeEntity($mensaje);





            $mensajeEntity->estado = $this->estado->obtenerPorId($mensaje->idestado);
            $mensajeEntity->clase = $this->clase->obtenerPorId($mensaje->idclase);


            // Convertir a array
            $resultado = $mensajeEntity->toArray();



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
        $ordencriterio = $request->getVar('ordenCriterio') ?? 'nombre';
        $ordentipo = $request->getVar('ordenTipo') ?? 'asc';
        $idestado = (int) ($request->getVar('idEstado') ?? 0);
        $idclase = (int) ($request->getVar('idClase') ?? 0);


        // Parámetros de paginación
        $pagina = (int) ($request->getVar('pagina') ?? 1);
        $registros = (int) ($request->getVar('registros') ?? 10);

        // Total de registros
        $total = $this->mensaje->buscarPorTotal(
            $idclase,
            $idestado

        );

        $paginator = new Paginator($pagina, $registros, $total);

        // Consulta paginada
        $mensaje = $this->mensaje->buscarPor(
            $ordencriterio,
            $ordentipo,
            $idclase,
            $idestado .
                $paginator->getFirstElement(),
            $paginator->getSize()
        );

        // Convertir resultados a entidad
        $resultado = [];
        foreach ($mensaje as $row) {
            $mensaje = new MensajeEntity($row);

            // $mensaje->idmensaje = $row->idmensaje;
            // $mensaje->idestado = $row->idestado;
            // $mensaje->idclase = $row->idclase;
            // $mensaje->nombre = $row->nombre;
            // $mensaje->asunto = $row->asunto;
            // $mensaje->contenido = $row->contenido;
            // $mensaje->variables = $row->variables;
            // $mensaje->fecha = $row->fecha;

            $mensaje->estado = $this->estado->obtenerPorId($row->idestado);
            $mensaje->clase = $this->clase->obtenerPorId($row->idclase);


            $resultado[] = $mensaje->toArray();
        }

        // Respuesta JSON con paginación y datos
        return $this->respond([
            'paginator' => $paginator->enviar(),
            'content' => $resultado,

        ]);
    }
    public function guardar()
    {
      
        $request = $this->request;

        $data = $request->getJSON(true);
        $configuracionRequest = new MensajeValidation();
        $errores = $configuracionRequest->MensajeGuardarValidation($data);

        if (!empty($errores)) {
            return $this->response
                ->setStatusCode(422)
                ->setJSON(['errors' => $errores]);
        }

        $datosValidados = [
            'idestado' => $data['estado']['idEstado'] ?? null,
            'idclase' => $data['clase']['idClase'] ?? null,
            'nombre' => $data['nombre'] ?? null,
            'asunto' => $data['asunto'] ?? null,
            'contenido' => $data['contenido'] ?? null,
            // 'variables' => $data['variables'] ?? null,
        ];



        $mensajeId = $this->mensaje->guardar($datosValidados);
        $mensaje = $this->mensaje->find($mensajeId);
        if ($mensaje) {

            $mensajef = new MensajeEntity($mensaje);
            $mensajef->estado = $this->estado->obtenerPorId($mensaje->idestado);
            $mensajef->clase = $this->clase->obtenerPorId($mensaje->idclase);

            return $this->respond([
                "mensaje" => 'mensaje registrado con éxito',
                "mensaje" => $mensajef->toArray()
            ], 201);
        } else {

            return $this->respond(["mensaje" => "Error al registrar mensaje"], 500);
        }
    }

    public function actualizar()
    {
        
        $request = $this->request;

        $data = $request->getJSON(true);
        $configuracionRequest = new MensajeValidation();
        $errores = $configuracionRequest->MensajeGuardarValidation($data);

        if (!empty($errores)) {
            return $this->response
                ->setStatusCode(422)
                ->setJSON(['errors' => $errores]);
        }
        $datosValidados = [
            'idmensaje' => (int) $data['idMensaje'] ?? null,
            'idestado' => $data['estado']['idEstado'] ?? null,
            'idclase' => $data['clase']['idClase'] ?? null,
            'nombre' => $data['nombre'] ?? null,
            'asunto' => $data['asunto'] ?? null,
            'contenido' => $data['contenido'] ?? null,
            //'variables' => $data['variables'] ?? null,

        ];


        $mensajeId = $this->mensaje->guardar($datosValidados);
        $mensaje = $this->mensaje->find($mensajeId);
        if ($mensaje) {

            $mensajef = new MensajeEntity($mensaje);
            $mensajef->estado = $this->estado->obtenerPorId($mensaje->idestado);
            $mensajef->clase = $this->clase->obtenerPorId($mensaje->idclase);

            return $this->respond([
                "mensaje" => 'mensaje actualizado con éxito',
                "mensaje" =>  $mensajef->toArray()
            ], 201);
        } else {

            return $this->respond(["mensaje" => "Error al actualizar el mensaje"], 500);
        }
    }

    public function eliminar($idmensaje)
    {
       
        if ($this->mensaje->eliminar($idmensaje)) {
            return $this->respond(['mensaje' => 'mensaje eliminado con éxito']);
        } else {
            return $this->failNotFound('No se encontró el mensaje');
        }
    }
}
