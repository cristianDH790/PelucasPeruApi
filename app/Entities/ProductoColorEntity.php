<?php

namespace App\Entities;

use CodeIgniter\Entity\Entity;

class ProductoColorEntity
{
    public $idproductocolor;
    public $idestado;
    public $idproducto;
    public $idcolor;
    public $nombre;
    public $urlamigable;
    public $orden;
    public $destacado;
    public $fecha;
    public $stock;

    public $estado;
    public $producto;
    public $color;

    public function __construct($data = null)
    {
        if ($data) {
            if (is_array($data)) {
                foreach ($data as $key => $value) {
                    $this->$key = $value ?? null;
                }
            } elseif (is_object($data)) {
                foreach ($data as $key => $value) {
                    $this->$key = $value ?? null;
                }
            }
        }
    }

    public function toArray()
    {
        $data = [
            'idProductoColor' => (int) $this->idproductocolor,
            'idEstado' => (int) $this->idestado,
            'idProducto' => (int) $this->idproducto,
            'idColor' => (int) $this->idcolor,
            'nombre' => $this->nombre,
            'urlAmigable' => $this->urlamigable,
            'orden' => $this->orden,
            'stock' => $this->stock,
            'destacado' => (int)$this->destacado,
            'fecha' => $this->fecha,
        ];

        if ($this->estado !== null) {
            $data['estado'] = $this->estado->toArray();
        }
        if ($this->producto !== null) {
            $data['producto'] = $this->producto->toArray();
        }
        if ($this->color !== null) {
            $data['color'] = $this->color->toArray();
        }

        return $data;
    }
}
