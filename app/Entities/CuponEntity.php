<?php

namespace App\Entities;

class CuponEntity
{
    public $idcupon;
    public $idptipo;
    public $idestado;
    public $codigo;
    public $nombre;
    public $limite;
    public $descuento;
    public $inicio;
    public $termino;
    public $fecha;

    // Relaciones
    public $estado;
    public $ptipo;
    public $productos;
    public $usos;

    public function __construct($data = null)
    {
        if ($data) {
            if (is_array($data)) {
                $this->idcupon = $data['idcupon'] ?? null;
                $this->idptipo = $data['idptipo'] ?? null;
                $this->idestado = $data['idestado'] ?? null;
                $this->codigo = $data['codigo'] ?? null;
                $this->nombre = $data['nombre'] ?? null;
                $this->limite = $data['limite'] ?? null;
                $this->descuento = $data['descuento'] ?? null;
                $this->inicio = $data['inicio'] ?? null;
                $this->termino = $data['termino'] ?? null;
                $this->fecha = $data['fecha'] ?? null;
            } elseif (is_object($data)) {
                $this->idcupon = $data->idcupon ?? null;
                $this->idptipo = $data->idptipo ?? null;
                $this->idestado = $data->idestado ?? null;
                $this->codigo = $data->codigo ?? null;
                $this->nombre = $data->nombre ?? null;
                $this->limite = $data->limite ?? null;
                $this->descuento = $data->descuento ?? null;
                $this->inicio = $data->inicio ?? null;
                $this->termino = $data->termino ?? null;
                $this->fecha = $data->fecha ?? null;
            }
        }
    }

    public function toArray()
    {
        $data = [
            'idCupon'           => (int) $this->idcupon,
            'idpTipo'           => (int) $this->idptipo,
            'idEstado'           => (int) $this->idestado,
            'codigo' =>   $this->codigo,
            'nombre'             => $this->nombre,
            'limite'            => $this->limite,
            'descuento'          => $this->descuento,
            'inicio'          => $this->inicio,
            'termino'          => $this->termino,
            'productos'          => (int)$this->productos ?? 0,
            'usos'          => (int)$this->usos ?? 0,
            'fecha'              => $this->fecha
        ];

        if ($this->estado !== null) {
            $data['estado'] = $this->estado->toArray();
        }

        if ($this->ptipo !== null) {
            $data['pTipo'] = $this->ptipo->toArray();
        }


        return $data;
    }
}
