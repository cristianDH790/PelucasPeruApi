<?php

namespace App\Entities;

use CodeIgniter\Entity\Entity;

class PedidoDetalleEntity
{
    public $idpedidodetalle;
    public $idpedido;
    public $idproducto;
    public $cantidad;
    public $peso;
    public $precio;
    public $descuento;
    public $total;


    // Relaciones
    public $pedido;
    public $producto;




    public function __construct($data = null)
    {
        if ($data) {
            if (is_array($data)) {
                $this->idpedidodetalle = $data['idpedidodetalle'] ?? null;
                $this->idpedido        = $data['idpedido'] ?? null;
                $this->idproducto      = $data['idproducto'] ?? null;
                $this->cantidad        = $data['cantidad'] ?? null;
                $this->peso            = $data['peso'] ?? null;
                $this->precio          = $data['precio'] ?? null;
                $this->descuento       = $data['descuento'] ?? null;
                $this->total           = $data['total'] ?? null;
            } elseif (is_object($data)) {
                $this->idpedidodetalle = $data->idpedidodetalle ?? null;
                $this->idpedido        = $data->idpedido ?? null;
                $this->idproducto      = $data->idproducto ?? null;
                $this->cantidad        = $data->cantidad ?? null;
                $this->peso            = $data->peso ?? null;
                $this->precio          = $data->precio ?? null;
                $this->descuento       = $data->descuento ?? null;
                $this->total           = $data->total ?? null;
            }
        }
    }

    public function toArray()
    {
        $data = [
            'idPedidoDetalle' => (int) $this->idpedidodetalle,
            'idPedido'        => (int) $this->idpedido,
            'idProducto'      => (int) $this->idproducto,
            'cantidad'        => $this->cantidad,
            'peso'            => $this->peso,
            'precio'          => $this->precio,
            'descuento'       => $this->descuento,
            'total'           => $this->total,
        ];

        // Relaciones si están cargadas
        if ($this->pedido !== null && method_exists($this->pedido, 'toArray')) {
            $data['pedido'] = $this->pedido->toArray();
        }

        if ($this->producto !== null && method_exists($this->producto, 'toArray')) {
            $data['producto'] = $this->producto->toArray();
        }

        return $data;
    }
}
