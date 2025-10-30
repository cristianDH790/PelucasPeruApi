<?php

namespace App\Controllers\Api;

use App\Controllers\BaseController;
use App\Helpers\Paginator;
use App\Helpers\Permisos;
use App\Models\ComentarioModel;
use App\Models\EstadoModel;
use CodeIgniter\RESTful\ResourceController;

class ComentarioController extends ResourceController
{
    protected $comentario;
    protected $estado;
    protected $permiso;

    public function __construct()
    {
        $this->comentario = new ComentarioModel();
        $this->estado = new EstadoModel();
        $this->permiso = new Permisos();
    }



    public function obtenerPorId($idcomentario)
    {


        $comentario = $this->comentario->obtenerById($idcomentario);

        if (!$comentario) {
            return $this->respond(['mensaje' => 'No existe el comentario solicitado'], 404);
        }

        return $this->respond($comentario, 200);
    }

    public function listar()
    {


        if (!$this->request->is('post')) {
            return $this->fail('Método no permitido. Se requiere POST.', 405);
        }

        $request = $this->request;

        $ordencriterio = $request->getVar('ordenCriterio') ?? 'fecha';
        $ordentipo = $request->getVar('ordenTipo') ?? 'desc';
        $parametro = $request->getVar('parametro') ?? '';
        $valor = $request->getVar('valor') ?? '';
        $idestado = (int)($request->getVar('idEstado') ?? 0);
        $idusuario = (int)($request->getVar('idUsuario') ?? 0);
        $idclase = (int)($request->getVar('idClase') ?? 0);
        $idrcomentario = (int)($request->getVar('idRcomentario') ?? 0);
        $idreferencia = (int)($request->getVar('idReferencia') ?? 0);

        $pagina = (int)($request->getVar('pagina') ?? 1);
        $registros = (int)($request->getVar('registros') ?? 10);

        $total = $this->comentario->buscarTotalPor($parametro, $valor, $idestado, $idusuario, $idclase, $idrcomentario, $idreferencia);
        $paginator = new Paginator($pagina, $registros, $total);

        $comentarios = $this->comentario->buscarPor(
            $ordencriterio,
            $ordentipo,
            $parametro,
            $valor,
            $idestado,
            $idusuario,
            $idclase,
            $idrcomentario,
            $idreferencia,
            $paginator->getFirstElement(),
            $paginator->getSize()
        );

        return $this->respond([
            'paginator' => $paginator->enviar(),
            'content' => $comentarios
        ]);
    }

    public function guardar()
    {


        $data = $this->request->getJSON(true);

        $datosValidados = [
            'idestado'      => $data['idestado'] ?? 1,
            'idrcomentario' => $data['idrcomentario'] ?? 0,
            'idclase'       => $data['idclase'] ?? null,
            'idreferencia'  => $data['idreferencia'] ?? null,
            'idusuario'     => $data['idusuario'] ?? null,
            'contenido'     => $data['contenido'] ?? null,
            'fecha'         => date('Y-m-d H:i:s'),
        ];

        $comentarioId = $this->comentario->guardar($datosValidados);
        $comentario = $this->comentario->find($comentarioId);

        if ($comentario) {
            return $this->respond([
                "mensaje" => "Tu opinión se ha guardado correctamente. Será verificada antes de publicarse.",
                "comentario" => $comentario
            ], 201);
        } else {
            return $this->respond(["mensaje" => "Error al registrar comentario"], 500);
        }
    }

    public function actualizar()
    {

        $data = $this->request->getJSON(true);

        $datosValidados = [
            'idcomentario'  => $data['idcomentario'] ?? null,
            'idestado'      => $data['idestado'] ?? 1,
            'idrcomentario' => $data['idrcomentario'] ?? 0,
            'idclase'       => $data['idclase'] ?? null,
            'idreferencia'  => $data['idreferencia'] ?? null,
            'idusuario'     => $data['idusuario'] ?? null,
            'contenido'     => $data['contenido'] ?? null,
            'fecha'         => date('Y-m-d H:i:s'),
        ];

        $comentarioId = $this->comentario->guardar($datosValidados);
        $comentario = $this->comentario->find($comentarioId);

        if ($comentario) {
            return $this->respond([
                "mensaje" => "Comentario actualizado correctamente.",
                "comentario" => $comentario
            ], 201);
        } else {
            return $this->respond(["mensaje" => "Error al actualizar el comentario"], 500);
        }
    }

    public function actualizarEstado($id = null)
    {
        $data = $this->request->getJSON(true);

        // Si no viene por parámetro, intentar obtenerlo del JSON
        if (!$id && isset($data['idcomentario'])) {
            $id = $data['idcomentario'];
        }

        if (!$id) {
            return $this->respond([
                "mensaje" => "ID del comentario no proporcionado."
            ], 400);
        }

        // Buscar el comentario
        $comentario = $this->comentario->find($id);
        if (!$comentario) {
            return $this->respond([
                "mensaje" => "El comentario con ID $id no existe."
            ], 404);
        }

        // Alternar estado (427 <-> 428) o usar el valor enviado
        $nuevoEstado = $data['idestado'] ?? (
            $comentario['idestado'] == 427 ? 428 : 427
        );

        // Actualizar solo el campo idestado
        $resultado = $this->comentario->update($id, [
            'idestado' => $nuevoEstado
        ]);

        if ($resultado) {
            $comentarioActualizado = $this->comentario->find($id);
            return $this->respond([
                "mensaje" => "Estado del comentario actualizado correctamente.",
                "comentario" => $comentarioActualizado
            ], 200);
        } else {
            return $this->respond([
                "mensaje" => "Error al actualizar el estado del comentario."
            ], 500);
        }
    }



    public function eliminar($idcomentario)
    {


        if ($this->comentario->delete($idcomentario)) {
            return $this->respond(['mensaje' => 'Comentario eliminado con éxito']);
        } else {
            return $this->failNotFound('No se encontró el comentario');
        }
    }
}
