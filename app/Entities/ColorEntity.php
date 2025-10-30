<?php

namespace App\Entities;

use CodeIgniter\Entity\Entity;

class ColorEntity
{
    // Campos de la tabla `color`
    public $idcolor;
    public $idestado;
    public $nombre;
    public $codigo;
    public $codigoproductocolor;
    public $fecha;

    // Relaciones
    public $estado;

    public function __construct($data = null)
    {
        if ($data) {
            if (is_array($data)) {
                $this->idcolor = $data['idcolor'] ?? null;
                $this->idestado = $data['idestado'] ?? null;
                $this->nombre = $data['nombre'] ?? null;
                $this->codigo = $data['codigo'] ?? null;
                $this->codigoproductocolor = $data['codigoproductocolor'] ?? null;
                $this->fecha = $data['fecha'] ?? null;
            } elseif (is_object($data)) {
                $this->idcolor = $data->idcolor ?? null;
                $this->idestado = $data->idestado ?? null;
                $this->nombre = $data->nombre ?? null;
                $this->codigo = $data->codigo ?? null;
                $this->codigoproductocolor = $data->codigoproductocolor ?? null;
                $this->fecha = $data->fecha ?? null;
            }
        }
    }

    public function toArray()
    {
        $data = [
            'idColor' => (int) $this->idcolor,
            'idEstado' => (int) $this->idestado,
            'nombre' => $this->nombre,
            'codigo' => $this->codigo,
            'codigoProductoColor' => $this->codigoproductocolor,
            'fecha' => $this->fecha
        ];

        if ($this->estado !== null) {
            $data['estado'] = $this->estado->toArray();
        }

        return $data;
    }
}
