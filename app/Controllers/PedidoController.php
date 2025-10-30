<?php

namespace App\Controllers;

use App\Helpers\Paginator as HelpersPaginator;
use App\Models\PedidoModel; // Modelo de Pedidos
use App\Libraries\Paginator;

class PedidoController extends BaseController
{
    public $pedidoModel;
    public function __construct()
    {
        $this->pedidoModel = new PedidoModel();
    }
    // public function getPedidos()
    // {
    //     $session = session();
    //     $usuario = $session->get('usuarioSesion');

    //     if (!$usuario) {
    //         return $this->response->setJSON([
    //             'status' => 'error',
    //             'message' => 'No hay sesión activa'
    //         ]);
    //     }

    //     // Recoger parámetros POST
    //     $ordenCriterio = $this->request->getPost('ordenCriterio') ?? '';
    //     $ordenTipo     = $this->request->getPost('ordenTipo') ?? '';
    //     $parametro     = $this->request->getPost('parametro') ?? '';
    //     $valor         = $this->request->getPost('valor') ?? '';
    //     $idEstado      = $this->request->getPost('idEstado') ?? 0;
    //     $idFormaPago   = $this->request->getPost('idFormaPago') ?? 0;
    //     $idEntrega     = $this->request->getPost('idEntrega') ?? 0;
    //     $idpPago       = $this->request->getPost('idpPago') ?? 0;
    //     $pagina        = $this->request->getPost('pagina') ?? 1;
    //     $registros     = $this->request->getPost('registros') ?? 10;

    //     $inicio = ($pagina - 1) * $registros;

    //     $pedidoModel = new PedidoModel();

    //     // Total de registros
    //     $total = $pedidoModel->buscarTotalPor(
    //         $parametro,
    //         $valor,
    //         $idEstado,
    //         $usuario['idUsuario'],
    //         $idFormaPago,
    //         $idEntrega,
    //         $idpPago
    //     ) ?? 0;

    //     $paginator = new HelpersPaginator($pagina, $registros, $total);

    //     // Lista de pedidos
    //     $lista = $pedidoModel->buscarPor(
    //         $ordenCriterio,
    //         $ordenTipo,
    //         $parametro,
    //         $valor,
    //         $idEstado,
    //         $usuario['idUsuario'],
    //         $idFormaPago,
    //         $idEntrega,
    //         $idpPago,
    //         $inicio,
    //         intval($registros)
    //     );

    //     // Retornar JSON
    //     return $this->response->setJSON([
    //         'lista' => $lista,
    //         'paginator' => $paginator->enviar()
    //     ]);
    // }
    public function getPedidos()
    {
        if ($usuario = session()->get('usuarioSesion')) {
            $ordenCriterio = $this->request->getPost("ordenCriterio") ?: "";
            $ordenTipo = $this->request->getPost("ordenTipo") ?: "";
            $parametro = $this->request->getPost("parametro") ?: "";
            $valor = $this->request->getPost("valor") ?: "";
            $idEstado = $this->request->getPost("idEstado") ?: 0;
            $idFormaPago = $this->request->getPost("idFormaPago") ?: 0;
            $idEntrega = $this->request->getPost("idEntrega") ?: 0;
            $idpPago = $this->request->getPost("idpPago") ?: 0;
            $pagina = $this->request->getPost("pagina") ?: 1;
            $registros = $this->request->getPost("registros") ?: 10;
            $idempresa = $this->request->getPost("idempresa") ?: 0;

            $inicio = ($pagina - 1) * $registros;

            $total = $this->pedidoModel->pedidoFindTotal(
                $parametro,
                $valor,
                $idEstado,
                $usuario->idusuario,
                $idFormaPago,
                $idEntrega,
                $idpPago,
                0
            ) ?: 0;

            $paginator = new HelpersPaginator($pagina, $registros, $total);

            $pedidos = $this->pedidoModel->pedidoFind(
                $ordenCriterio,
                $ordenTipo,
                $parametro,
                $valor,
                $idEstado,
                $usuario->idusuario,
                $idFormaPago,
                $idEntrega,
                $idpPago,
                0,
                $inicio,
                intval($registros)
            );

            $lista = [];

            foreach ($pedidos as $pedidoBase) {
                // Reutilizamos tu método completo para traer un pedido con todas las relaciones
                $pedido = $this->pedidoModel->getPedidoConUsuario($pedidoBase['idPedido'], $idempresa);
                // $pedido = $this->pedidoModel->getPedidoConUsuario($pedidoBase->idpedido, $idempresa);
                if ($pedido) {
                    $lista[] = $pedido;
                }
            }

            echo json_encode([
                "lista" => $lista,
                "paginator" => $paginator->enviar()
            ]);
        }
    }
}
