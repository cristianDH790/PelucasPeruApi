<?php

namespace App\Entities;

class ProductoCaracteristicaEntity
{
    public $idproductoimagen;
    public $idproducto;
    public $idestado;
    public $idpdestacado;
    public $nombre;
    public $orden;
    public $urlimagen;
    public $fecha;

    // Relaciones
    public $estado;
    public $producto;
    public $promocion;
    public $pdestacado;

    public function __construct($data = null)
    {
        if ($data) {
            if (is_array($data)) {
                $this->idproductoimagen = $data['idproductoimagen'] ?? null;
                $this->idproducto = $data['idproducto'] ?? null;
                $this->idestado = $data['idestado'] ?? null;
                $this->idpdestacado = $data['idpdestacado'] ?? null;
                $this->nombre = $data['nombre'] ?? null;
                $this->orden = $data['orden'] ?? null;
                $this->urlimagen = $data['urlimagen'] ?? null;
                $this->fecha = $data['fecha'] ?? null;
            } elseif (is_object($data)) {
                $this->idproductoimagen = $data->idproductoimagen ?? null;
                $this->idproducto = $data->idproducto ?? null;
                $this->idestado = $data->idestado ?? null;
                $this->idpdestacado = $data->idpdestacado ?? null;
                $this->nombre = $data->nombre ?? null;
                $this->orden = $data->orden ?? null;
                $this->urlimagen = $data->urlimagen ?? null;
                $this->fecha = $data->fecha ?? null;
            }
        }
    }

    public function toArray()
    {
        $data = [
            'idProductoImagen'           => (int) $this->idproductoimagen,
            'idEstado'           => (int) $this->idestado,
            'idProductoBase' => (int) $this->idproducto,
            'nombre'             => $this->nombre,
            'orden'            => $this->orden,
            'urlImagen'          => $this->urlimagen,
            'fecha'              => $this->fecha
        ];

        if ($this->estado !== null) {
            $data['estado'] = $this->estado->toArray();
        }

        if ($this->producto !== null) {
            $data['producto'] = $this->producto->toArray();
        }
        if ($this->pdestacado !== null) {
            $data['pDestacado'] = $this->pdestacado->toArray();
        }

        return $data;
    }
}
