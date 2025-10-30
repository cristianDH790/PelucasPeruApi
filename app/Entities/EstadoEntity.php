<?php

namespace App\Entities;

use CodeIgniter\Entity\Entity;

class EstadoEntity
{
    public $idestado;
    public $idclase;
    public $nombre;
    public $abr;
    public $descripcion;
    public $orden;
    public $fecha;

    // Relaciones
    public $clase;

    public function __construct(array $data = null)
    {
        if ($data) {
            if (is_array($data)) {
                $this->idestado = $data['idestado'] ?? null;
                $this->idclase = $data['idclase'] ?? null;
                $this->nombre = $data['nombre'] ?? null;
                $this->abr = $data['abr'] ?? null;
                $this->descripcion = $data['descripcion'] ?? null;
                $this->orden = $data['orden'] ?? null;
                $this->fecha = $data['fecha'] ?? null;
            } elseif (is_object($data)) {
                $this->idestado = $data->idestado ?? null;
                $this->idclase = $data->idclase ?? null;
                $this->nombre = $data->nombre ?? null;
                $this->abr = $data->abr ?? null;
                $this->descripcion = $data->descripcion ?? null;
                $this->orden = $data->orden ?? null;
                $this->fecha = $data->fecha ?? null;
            }
        }
    }

    public function toArray()
    {
        $data = [
            'idEstado' => (int) $this->idestado,
            'idClase' => (int) $this->idclase,
            'nombre' => $this->nombre,
            'abr' => $this->abr,
            'descripcion' => $this->descripcion,
            'orden' => $this->orden,
            'fecha' => $this->fecha
        ];

        if ($this->clase !== null) {
            $data['clase'] = $this->clase->toArray();
        }

        return $data;
    }
}
