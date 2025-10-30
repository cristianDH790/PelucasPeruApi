<?php

namespace App\Controllers\Api;

use App\Controllers\BaseController;
use App\Entities\NoticiaCategoriaEntity;
use App\Helpers\Paginator;
use App\Models\EstadoModel;
use App\Models\NoticiaCategoriaModel;
use App\Validation\NoticiaCategoriaValidation;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\RESTful\ResourceController;

class NoticiaCategoriaController extends ResourceController
{

    protected $noticiacategoria;
    protected $estado;

    public function __construct()
    {
        $this->noticiacategoria = new NoticiaCategoriaModel();
        $this->estado = new EstadoModel();
    }

    public  function obtenerPorId($idnoticiacategoria)
    {
        $noticiacategoria = $this->noticiacategoria->obtenerPorId($idnoticiacategoria);

        if (!$noticiacategoria) {
            return $this->respond(['mensaje' => 'No existe la noticia categoria solicitada'], 404);
        } else {

            $noticiaCategoriaEntity = new NoticiaCategoriaEntity();
            $noticiaCategoriaEntity->idnoticiacategoria  = $noticiacategoria->idnoticiacategoria;
            $noticiaCategoriaEntity->idestado  = $noticiacategoria->idestado;
            // $noticiaCategoriaEntity->idrnoticiacategoria  = $noticiacategoria->idrnoticiacategoria;
            $noticiaCategoriaEntity->nombre  = $noticiacategoria->nombre;

            $noticiaCategoriaEntity->urlamigable  = $noticiacategoria->urlamigable;
            $noticiaCategoriaEntity->descripcionseo  = $noticiacategoria->descripcionseo;
            $noticiaCategoriaEntity->fecha  = $noticiacategoria->fecha;

            $noticiaCategoriaEntity->orden  = $noticiacategoria->orden;
            $noticiaCategoriaEntity->estado = $this->estado->obtenerPorId($noticiacategoria->idestado);
            // $noticiaCategoriaEntity->rnoticiacategoria = $this->noticiacategoria->obtenerPorId($noticiacategoria->idrnoticiacategoria);


            // Convertir a array
            $resultado = $noticiaCategoriaEntity->toArray();

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
        $idpdestacado = (int) ($request->getVar('idpDestacado') ?? 0);
        $idrnoticiacategoria = (int) ($request->getVar('idrNoticiaCategoria') ?? 0);

        // Parámetros de paginación
        $pagina = (int) ($request->getVar('pagina') ?? 1);
        $registros = (int) ($request->getVar('registros') ?? 10);

        // Total de registros
        $total = $this->noticiacategoria->buscarPorTotal(
            $parametro,
            $valor,
            $idestado,
            $idrnoticiacategoria,
            $idpdestacado,
        );

        $paginator = new Paginator($pagina, $registros, $total);
        // Consulta paginada
        $noticiacategorias = $this->noticiacategoria->buscarPor(
            $ordencriterio,
            $ordentipo,
            $parametro,
            $valor,
            $idestado,
            $idrnoticiacategoria,
            $idpdestacado,
            $paginator->getFirstElement(),
            $paginator->getSize()
        );

        // Convertir resultados a entidad
        $resultado = [];
        foreach ($noticiacategorias as $row) {

            $noticiaCategoriaEntity = new NoticiaCategoriaEntity();
            $noticiaCategoriaEntity->idnoticiacategoria  = $row->idnoticiacategoria;
            $noticiaCategoriaEntity->idestado  = $row->idestado;
            // $noticiaCategoriaEntity->idrnoticiacategoria  = $row->idrnoticiacategoria;
            $noticiaCategoriaEntity->nombre  = $row->nombre;

            $noticiaCategoriaEntity->orden  = $row->orden;
            $noticiaCategoriaEntity->urlamigable  = $row->urlamigable;
            $noticiaCategoriaEntity->descripcionseo  = $row->descripcionseo;
            $noticiaCategoriaEntity->fecha  = $row->fecha;


            $noticiaCategoriaEntity->estado = $this->estado->obtenerPorId($row->idestado);
            // $noticiaCategoriaEntity->rnoticiacategoria = $this->noticiacategoria->obtenerPorId($row->idrnoticiacategoria);


            $resultado[] = $noticiaCategoriaEntity->toArray();
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
        $noticiacategoriaRequest = new NoticiaCategoriaValidation();
        $errores = $noticiacategoriaRequest->NoticiaCategoriaGuardarValidation($data);

        if (!empty($errores)) {
            return $this->response
                ->setStatusCode(422)
                ->setJSON(['errors' => $errores]);
        }

        $datosValidados = [
            'idestado' => $data['estado']['idEstado'] ?? null,
            // 'idrnoticiacategoria' => $data['rNoticiaCategoria']['idNoticiaCategoria'] ?? null,

            'orden' => $data['orden'] ?? null,
            'nombre' => $data['nombre'] ?? null,
            'urlamigable' => $data['urlAmigable'] ?? null,
            'descripcionseo' => $data['descripcionSeo'] ?? null,

        ];



        $noticiacategoriaId = $this->noticiacategoria->guardar($datosValidados);
        $noticiacategoria = $this->noticiacategoria->find($noticiacategoriaId);
        if ($noticiacategoria) {



            return $this->respond([
                "mensaje" => 'noticiacategoria registrado con éxito',
                "noticiacategoria" => $noticiacategoria->toArray()
            ], 201);
        } else {

            return $this->respond(["mensaje" => "Error al registrar noticiacategoria"], 500);
        }
    }

    public function actualizar()
    {
        $request = $this->request;

        $data = $request->getJSON(true);
        $noticiacategoriaRequest = new NoticiaCategoriaValidation();
        $errores = $noticiacategoriaRequest->NoticiaCategoriaActualizarValidation($data);

        if (!empty($errores)) {
            return $this->response
                ->setStatusCode(422)
                ->setJSON(['errors' => $errores]);
        }
        $datosValidados = [
            'idnoticiacategoria' => (int) $data['idNoticiaCategoria'] ?? null,
            'idestado' => $data['estado']['idEstado'] ?? null,
            // 'idrnoticiacategoria' => $data['rNoticiaCategoria']['idNoticiaCategoria'] ?? null,
            'orden' => $data['orden'] ?? null,
            'nombre' => $data['nombre'] ?? null,
            'urlamigable' => $data['urlAmigable'] ?? null,
            'descripcionseo' => $data['descripcionSeo'] ?? null,
        ];

        $noticiacategoriaId = $this->noticiacategoria->guardar($datosValidados);
        $noticiacategoria = $this->noticiacategoria->find($noticiacategoriaId);
        if ($noticiacategoria) {


            return $this->respond([
                "mensaje" => 'noticiacategoria actualizado con éxito',
                "noticiacategoria" =>  $noticiacategoria->toArray()
            ], 201);
        } else {

            return $this->respond(["mensaje" => "Error al actualizar el noticiacategoria"], 500);
        }
    }

    public function eliminar($idnoticiacategoria)
    {
        if ($this->noticiacategoria->eliminar($idnoticiacategoria)) {
            return $this->respond(['mensaje' => 'noticiacategoria eliminado con éxito']);
        } else {
            return $this->failNotFound('No se encontró la noticiacategoria');
        }
    }
}
