<?php

namespace App\Entities;

use CodeIgniter\Entity\Entity;

class PedidoEntity
{
    public $idpedido;
    public $idusuario;
    public $idempresa;
    public $idformapago;
    public $identrega;
    public $idppago;
    public $idestado;
    public $referencia;
    public $peso;
    public $costoenvio;
    public $comision;
    public $subtotal;
    public $descuento;
    public $total;
    public $fechapedido;
    public $fechaentrega;
    public $observacion;
    public $urlconstancia;
    public $fechareporte;
    public $fecha;
    public $fechaconfirmacion;

    // Relaciones
    public $estado;
    public $usuario;
    public $formapago;
    public $entrega;
    public $ppago;


    // Relaciones adicionales
    public $agencia;
    public $sede;
    public $destino;
    public $recojo;
    public $cupones;
    public $comprobante;
    public $pedidoDetalle;
    public $entregaparametro;




    public function __construct($data = null)
    {
        if ($data) {
            if (is_array($data)) {
                $this->idpedido          = $data['idpedido'] ?? null;
                $this->idusuario         = $data['idusuario'] ?? null;
                $this->idformapago       = $data['idformapago'] ?? null;
                $this->idempresa       = $data['idempresa'] ?? null;
                $this->identrega         = $data['identrega'] ?? null;
                $this->idppago           = $data['idppago'] ?? null;
                $this->idestado          = $data['idestado'] ?? null;
                $this->referencia        = $data['referencia'] ?? null;
                $this->fecha        = $data['fecha'] ?? null;
                $this->peso              = $data['peso'] ?? null;
                $this->costoenvio        = $data['costoenvio'] ?? null;
                $this->comision          = $data['comision'] ?? null;
                $this->subtotal          = $data['subtotal'] ?? null;
                $this->descuento         = $data['descuento'] ?? null;
                $this->total             = $data['total'] ?? null;
                $this->fechapedido       = $data['fechapedido'] ?? null;
                $this->fechaentrega      = $data['fechaentrega'] ?? null;
                $this->observacion       = $data['observacion'] ?? null;
                $this->urlconstancia     = $data['urlconstancia'] ?? null;
                $this->fechareporte      = $data['fechareporte'] ?? null;
                $this->fechaconfirmacion = $data['fechaconfirmacion'] ?? null;
            } elseif (is_object($data)) {
                $this->idpedido          = $data->idpedido ?? null;
                $this->idusuario         = $data->idusuario ?? null;
                $this->idformapago       = $data->idformapago ?? null;
                $this->idempresa       = $data->idempresa ?? null;
                $this->identrega         = $data->identrega ?? null;
                $this->idppago           = $data->idppago ?? null;
                $this->idestado          = $data->idestado ?? null;
                $this->referencia        = $data->referencia ?? null;
                $this->peso              = $data->peso ?? null;
                $this->costoenvio        = $data->costoenvio ?? null;
                $this->comision          = $data->comision ?? null;
                $this->fecha          = $data->fecha ?? null;
                $this->subtotal          = $data->subtotal ?? null;
                $this->descuento         = $data->descuento ?? null;
                $this->total             = $data->total ?? null;
                $this->fechapedido       = $data->fechapedido ?? null;
                $this->fechaentrega      = $data->fechaentrega ?? null;
                $this->observacion       = $data->observacion ?? null;
                $this->urlconstancia     = $data->urlconstancia ?? null;
                $this->fechareporte      = $data->fechareporte ?? null;
                $this->fechaconfirmacion = $data->fechaconfirmacion ?? null;
            }
        }
    }

    public function toArray()
    {
        $data = [
            'idPedido'          => (int) $this->idpedido,
            'idUsuario'         => (int) $this->idusuario,
            'idFormaPago'       => (int) $this->idformapago,
            'idEntrega'         => (int) $this->identrega,
            'idEmpresa'         => (int) $this->idempresa,
            'idpPago'           => (int) $this->idppago,
            'idEstado'          => (int) $this->idestado,
            'referencia'        => $this->referencia,
            'peso'              => $this->peso,
            'costoEnvio'        => $this->costoenvio,
            'fecha'             => $this->fecha,
            'comision'          => $this->comision,
            'subtotal'          => $this->subtotal,
            'descuento'         => $this->descuento,
            'total'             => $this->total,
            'fechaPedido'       => $this->fechapedido,
            'fechaEntrega'      => $this->fechaentrega,
            'observacion'       => $this->observacion,
            'urlConstancia'     => $this->urlconstancia,
            'fechaReporte'      => $this->fechareporte,
            'fechaConfirmacion' => $this->fechaconfirmacion,
        ];

        // Relaciones principales
        if ($this->estado !== null) {
            $data['estado'] = method_exists($this->estado, 'toArray')
                ? $this->estado->toArray()
                : (array) $this->estado;
        }

        if ($this->usuario !== null) {
            $data['usuario'] = method_exists($this->usuario, 'toArray')
                ? $this->usuario->toArray()
                : (array) $this->usuario;
        }

        if ($this->formapago !== null) {
            $data['formaPago'] = method_exists($this->formapago, 'toArray')
                ? $this->formapago->toArray()
                : (array) $this->formapago;
        }

        if ($this->entrega !== null) {
            $data['entrega'] = method_exists($this->entrega, 'toArray')
                ? $this->entrega->toArray()
                : (array) $this->entrega;
        }

        if ($this->ppago !== null) {
            $data['ppago'] = method_exists($this->ppago, 'toArray')
                ? $this->ppago->toArray()
                : (array) $this->ppago;
        }

        // Relaciones múltiples
        $data['sede'] = $this->sede ? array_map(function ($agencia) {
            return method_exists($agencia, 'toArray') ? $agencia->toArray() : (array) $agencia;
        }, $this->sede) : [];

        $data['recojo'] = $this->recojo ? array_map(function ($r) {
            return method_exists($r, 'toArray') ? $r->toArray() : (array) $r;
        }, $this->recojo) : [];

        $data['destino'] = $this->destino ? array_map(function ($d) {
            return method_exists($d, 'toArray') ? $d->toArray() : (array) $d;
        }, $this->destino) : [];

        $data['cupones'] = $this->cupones ? array_map(function ($c) {
            return method_exists($c, 'toArray') ? $c->toArray() : (array) $c;
        }, $this->cupones) : [];

        $data['comprobante'] = $this->comprobante ? array_map(function ($c) {
            return method_exists($c, 'toArray') ? $c->toArray() : (array) $c;
        }, $this->comprobante) : [];

        $data['pedidoDetalle'] = $this->pedidoDetalle ? array_map(function ($d) {
            return (array) $d;
        }, $this->pedidoDetalle) : [];

        // Entrega parámetro
        if ($this->entregaparametro !== null) {
            $data['entregaparametro'] = method_exists($this->entregaparametro, 'toArray')
                ? $this->entregaparametro->toArray()
                : (array) $this->entregaparametro;
        }

        return $data;
    }
}
