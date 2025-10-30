<?php

namespace App\Controllers\Api\Base;

use App\Controllers\BaseController;
use App\Models\PedidoModel;
use App\Models\UsuarioModel;
use CodeIgniter\RESTful\ResourceController;

class DashboardController extends ResourceController
{
    public function dashboardStats()
    {
        helper('date'); // Por si usamos helpers de fecha
        $pedidoModel = new PedidoModel();
        $usuarioModel = new UsuarioModel();

        $pedidoNuevoMeses = [];
        $pedidoAtendidoMeses = [];
        $pedidoTransitoMeses = [];
        $pedidoEntregadoMeses = [];
        $pedidoAnuladoMeses = [];
        $pedidoValorRealizadoMeses = [];
        $pedidoValorNoRealizadoMeses = [];
        $pedidoValorReportadoMeses = [];
        $meses = [];

        for ($i = 0; $i <= 11; $i++) {

            $mesNumero = date("m", mktime(0, 0, 0, date("m") - $i, date("d"), date("Y")));

            switch ($mesNumero) {
                case '01':
                    $mes = "Enero";
                    break;
                case '02':
                    $mes = "Febrero";
                    break;
                case '03':
                    $mes = "Marzo";
                    break;
                case '04':
                    $mes = "Abril";
                    break;
                case '05':
                    $mes = "Mayo";
                    break;
                case '06':
                    $mes = "Junio";
                    break;
                case '07':
                    $mes = "Julio";
                    break;
                case '08':
                    $mes = "Agosto";
                    break;
                case '09':
                    $mes = "Septiembre";
                    break;
                case '10':
                    $mes = "Octubre";
                    break;
                case '11':
                    $mes = "Noviembre";
                    break;
                case '12':
                    $mes = "Diciembre";
                    break;
            }

            // Totales por tipo de pedido
            $pedidoNuevoMes = $pedidoModel->pedidoFindTotal("mes", $mesNumero, 329, 0, 0, 0, 0, "") ?: 0;
            $pedidoAtendidoMes = $pedidoModel->pedidoFindTotal("mes", $mesNumero, 330, 0, 0, 0, 0, "") ?: 0;
            $pedidoTransitoMes = $pedidoModel->pedidoFindTotal("mes", $mesNumero, 331, 0, 0, 0, 0, "") ?: 0;
            $pedidoEntregadoMes = $pedidoModel->pedidoFindTotal("mes", $mesNumero, 332, 0, 0, 0, 0, "") ?: 0;
            $pedidoAnuladoMes = $pedidoModel->pedidoFindTotal("mes", $mesNumero, 333, 0, 0, 0, 0, "") ?: 0;

            // Valores por pago
            $pedidoValorRealizadoMes = $pedidoModel->pedidoFindTotal("mes", $mesNumero, 0, 0, 0, 0, 453, "") ?: 0;
            $pedidoValorNoRealizadoMes = $pedidoModel->pedidoTotalSuma("mes", $mesNumero, 0, 452) ?: 0;
            $pedidoValorReportadoMes = $pedidoModel->pedidoFindTotal("mes", $mesNumero, 0, 0, 0, 0, 454, "") ?: 0;

            // Guardamos en arrays
            $pedidoNuevoMeses[] = $pedidoNuevoMes;
            $pedidoAtendidoMeses[] = $pedidoAtendidoMes;
            $pedidoTransitoMeses[] = $pedidoTransitoMes;
            $pedidoEntregadoMeses[] = $pedidoEntregadoMes;
            $pedidoAnuladoMeses[] = $pedidoAnuladoMes;
            $pedidoValorRealizadoMeses[] = $pedidoValorRealizadoMes;
            $pedidoValorNoRealizadoMeses[] = $pedidoValorNoRealizadoMes;
            $pedidoValorReportadoMeses[] = $pedidoValorReportadoMes;

            $meses[] = $mes;
        }

        // Invertimos los arrays para que queden cronológicos
        $pedidoNuevoMeses = array_reverse($pedidoNuevoMeses);
        $pedidoAtendidoMeses = array_reverse($pedidoAtendidoMeses);
        $pedidoTransitoMeses = array_reverse($pedidoTransitoMeses);
        $pedidoEntregadoMeses = array_reverse($pedidoEntregadoMeses);
        $pedidoAnuladoMeses = array_reverse($pedidoAnuladoMeses);
        $pedidoValorRealizadoMeses = array_reverse($pedidoValorRealizadoMeses);
        $pedidoValorNoRealizadoMeses = array_reverse($pedidoValorNoRealizadoMeses);
        $pedidoValorReportadoMeses = array_reverse($pedidoValorReportadoMeses);
        $meses = array_reverse($meses);

        $data = [
            'meses' => $meses,
            'totalUsuarios' => $usuarioModel->buscarPorTotal("", "", 110, 0, 0),
            'totalClientes' => $usuarioModel->buscarPorTotal("", "", 110, 2, 0),
            'totalPedidos' => $pedidoModel->pedidoFindTotal("", "", 0, 0, 0, 0, 0, 0, ""),
            'totalValorPedidos' => $pedidoModel->pedidoTotalSuma("", "", 0, 0),
            'pedidoNuevoMeses' => $pedidoNuevoMeses,
            'pedidoAtendidoMeses' => $pedidoAtendidoMeses,
            'pedidoTransitoMeses' => $pedidoTransitoMeses,
            'pedidoEntregadoMeses' => $pedidoEntregadoMeses,
            'pedidoAnuladoMeses' => $pedidoAnuladoMeses,
            'pedidoValorRealizadoMeses' => $pedidoValorRealizadoMeses,
            'pedidoValorNoRealizadoMeses' => $pedidoValorNoRealizadoMeses,
            'pedidoValorReportadoMeses' => $pedidoValorReportadoMeses,
        ];

        return $this->response->setJSON($data);
    }
}
