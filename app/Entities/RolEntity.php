<?php

namespace App\Entities;


class RolEntity
{
    public $idrol;
    public $idestado;
    public $nombre;
    public $abr;
    public $descripcion;
    public $fecha;

    // Relaciones
    public $estado;

    public function __construct(array $data = null)
    {
        if ($data) {
            if (is_array($data)) {
                $this->idrol = $data['idrol'] ?? null;
                $this->idestado = $data['idestado'] ?? null;
                $this->nombre = $data['nombre'] ?? null;
                $this->abr = $data['abr'] ?? null;
                $this->descripcion = $data['descripcion'] ?? null;
                $this->fecha = $data['fecha'] ?? null;
            } elseif (is_object($data)) {
                $this->idrol = $data->idrol ?? null;
                $this->idestado = $data->idestado ?? null;
                $this->nombre = $data->nombre ?? null;
                $this->abr = $data->abr ?? null;
                $this->descripcion = $data->descripcion ?? null;
                $this->fecha = $data->fecha ?? null;
            }
        }
    }

    public function toArray()
    {
        $data = [
            'idRol' => (int) $this->idrol,
            'idEstado' => (int) $this->idestado,
            'nombre' => $this->nombre,
            'abr' => $this->abr,
            'descripcion' => $this->descripcion,
            'fecha' => $this->fecha
        ];

        if ($this->estado !== null) {
            $data['estado'] = $this->estado->toArray();
        }

        return $data;
    }
}
