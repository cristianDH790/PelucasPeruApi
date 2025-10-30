<?php

namespace App\Controllers\Api;

use App\Entities\PedidoEntity;
use App\Helpers\Paginator;
use App\Helpers\Permisos;
use App\Models\PedidoModel;
use App\Models\EstadoModel;
use CodeIgniter\RESTful\ResourceController;

class PedidoController extends ResourceController
{
    protected $pedido;
    protected $estado;
    protected $permiso;
    public function __construct()
    {
        $this->permiso = new Permisos();
        $this->pedido = new PedidoModel();
        $this->estado = new EstadoModel();
    }
    //para verificar los permisos
    // private function verificarPermiso(string $permiso)
    // {

    //     $token = $this->request->getHeaderLine('X-Authorization');
    //     $token = str_replace('Bearer ', '', $token);

    //     if (!$token) {
    //         return $this->failUnauthorized('Token no proporcionado');
    //     }
    //     $resultado = $this->permiso->obtenerPermisosDesdeToken($token);

    //     if (isset($resultado['error'])) {
    //         return $this->failUnauthorized($resultado['error']);
    //     }

    //     $permisos = $resultado['authorities'] ?? [];

    //     if (!in_array($permiso, $permisos)) {
    //         return $this->failForbidden("No tienes permiso: {$permiso}");
    //     }

    //     return null; // Permiso concedido
    // }

    public function obtenerPorId($idPedido)
    {

        $pedido = $this->pedido->find($idPedido);
        if (!$pedido) {
            return $this->respond(['mensaje' => 'No existe el pedido solicitado'], 404);
        }

        $pedidoEntity = new PedidoEntity($pedido);
        return $this->respond($pedidoEntity->toArray(), 200);
    }

    public function eliminar($idPedido)
    {

        $pedido = $this->pedido->find($idPedido);
        if (!$pedido) {
            return $this->respond(['mensaje' => 'No existe el pedido solicitado'], 404);
        }

        $this->pedido->delete($idPedido);
        return $this->respond(['mensaje' => 'Pedido eliminado con éxito'], 200);
    }

    // public function listar()
    // {

    //     $request = $this->request;

    //     $ordencriterio = $request->getVar('ordenCriterio') ?? '';
    //     $ordentipo = $request->getVar('ordenTipo') ?? '';
    //     $parametro = $request->getVar('parametro') ?? '';
    //     $valor = $request->getVar('valor') ?? '';
    //     $idestado = (int) $request->getVar('idEstado') ?? 0;
    //     $idusuario = (int) $request->getVar('idUsuario') ?? 0;
    //     $idformapago = (int) $request->getVar('idFormaPago') ?? 0;
    //     $identrega = (int) $request->getVar('idEntrega') ?? 0;
    //     $idppago = (int) $request->getVar('idpPago') ?? 0;
    //     $fecharango = $request->getVar('fechaRango') ?? '';
    //     $pagina = (int) $request->getVar('pagina') ?? 1;
    //     $registros = (int) $request->getVar('registros') ?? 10;

    //     $total = $this->pedido->pedidoFindTotal(
    //         $parametro,
    //         $valor,
    //         $idestado,
    //         $idusuario,
    //         $idformapago,
    //         $identrega,
    //         $idppago,
    //         $fecharango
    //     );

    //     $paginator = new Paginator($pagina, $registros, $total);

    //     $resultados = $this->pedido->pedidoFind(
    //         $ordencriterio,
    //         $ordentipo,
    //         $parametro,
    //         $valor,
    //         $idestado,
    //         $idusuario,
    //         $idformapago,
    //         $identrega,
    //         $idppago,
    //         $fecharango,
    //         $paginator->getFirstElement(),
    //         $paginator->getSize()
    //     );

    //     $contenido = [];
    //     foreach ($resultados as $row) {
    //         $pedidoEntity = new PedidoEntity($row);
    //         $contenido[] = $pedidoEntity->toArray();
    //     }

    //     return $this->respond([
    //         'paginator' => $paginator->enviar(),
    //         'content' => $contenido
    //     ], 200);
    // }
    public function listar()
    {
        $request = $this->request;

        $ordencriterio = $request->getVar('ordenCriterio') ?? '';
        $ordentipo = $request->getVar('ordenTipo') ?? '';
        $parametro = $request->getVar('parametro') ?? '';
        $valor = $request->getVar('valor') ?? '';
        $idestado = (int) $request->getVar('idEstado') ?? 0;
        $idusuario = (int) $request->getVar('idUsuario') ?? 0;
        $idformapago = (int) $request->getVar('idFormaPago') ?? 0;
        $identrega = (int) $request->getVar('idEntrega') ?? 0;
        $idppago = (int) $request->getVar('idpPago') ?? 0;
        $fecharango = $request->getVar('fechaRango') ?? '';
        $pagina = (int) $request->getVar('pagina') ?? 1;
        $registros = (int) $request->getVar('registros') ?? 10;

        $db = \Config\Database::connect();

        $total = $this->pedido->pedidoFindTotal(
            $parametro,
            $valor,
            $idestado,
            $idusuario,
            $idformapago,
            $identrega,
            $idppago,
            $fecharango
        );

        $paginator = new Paginator($pagina, $registros, $total);

        $resultados = $this->pedido->pedidoFind(
            $ordencriterio,
            $ordentipo,
            $parametro,
            $valor,
            $idestado,
            $idusuario,
            $idformapago,
            $identrega,
            $idppago,
            $fecharango,
            $paginator->getFirstElement(),
            $paginator->getSize()
        );

        // var_dump($resultados);

        return $this->respond([
            'paginator' => $paginator->enviar(),
            'content' => $resultados
        ], 200);
    }


    public function cambiarEstado()
    {

        $data = $this->request->getJSON(true);
        $pedidoId = $data['pedido']['idPedido'] ?? null;
        $idestado = $data['estado']['idEstado'] ?? null;

        $pedido = $this->pedido->find($pedidoId);
        if (!$pedido) {
            return $this->failNotFound("No existe el pedido seleccionado");
        }

        $pedido->idestado = $idestado;
        $this->pedido->save($pedido);

        $pedidoActualizado = new PedidoEntity($this->pedido->find($pedidoId));
        return $this->respond([
            "pedido" => $pedidoActualizado->toArray(),
            "mensaje" => "Pedido actualizado con éxito"
        ], 200);
    }

    public function cambiarPago()
    {

        date_default_timezone_set('America/Lima');

        $data = $this->request->getJSON(true);
        $pedidoId = $data['pedido']['idPedido'] ?? null;
        $idppago = $data['pPago']['idParametro'] ?? null;

        $pedido = $this->pedido->find($pedidoId);
        if (!$pedido) {
            return $this->failNotFound("No existe el pedido seleccionado");
        }

        if ($idppago == 454)
            $pedido->fechareporte = date("Y-m-d H:i:s");
        elseif ($idppago == 453)
            $pedido->fechaconfirmacion = date("Y-m-d H:i:s");

        $pedido->idppago = $idppago;
        $this->pedido->save($pedido);

        return $this->respond([
            "pedido" => (new PedidoEntity($pedido))->toArray(),
            "mensaje" => "Pedido actualizado con éxito"
        ], 200);
    }

    public function enviarCorreo()
    {

        $data = $this->request->getJSON(true);
        $idpedido = $data['idPedido'] ?? null;
        $idmensaje = $data['idMensaje'] ?? null;

        try {
            $util = new \App\Helpers\Util();
            $util->mailPedido($idpedido, $idmensaje);
            return $this->respond(['mensaje' => "Correo enviado con éxito"], 200);
        } catch (\Throwable $th) {
            return $this->fail("Error al enviar el correo", 400);
        }
    }

    public function reporteExcel()
    {

        // Código para exportar Excel (usando PhpSpreadsheet o similar si estás en CI4)
        return $this->fail("Funcionalidad no implementada en esta versión.");
    }
}
