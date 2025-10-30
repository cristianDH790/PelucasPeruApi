<?php

namespace App\Entities;

use CodeIgniter\Entity\Entity;

class ClaseEntity
{
    public $idclase;
    public $idrclase;
    public $nombre;
    public $descripcion;
    public $orden;
    public $fecha;


    //relacion
    public $rclase;

    public function __construct($data = null)
    {
        if ($data) {
            if (is_array($data)) {
                $this->idrclase = $data['idrclase'] ?? null;
                $this->idclase = $data['idclase'] ?? null;
                $this->nombre = $data['nombre'] ?? null;
                $this->descripcion = $data['descripcion'] ?? null;
                $this->orden = $data['orden'] ?? null;
                $this->fecha = $data['fecha'] ?? null;
            } elseif (is_object($data)) {
                $this->idrclase = $data->idrclase ?? null;
                $this->idclase = $data->idclase ?? null;
                $this->nombre = $data->nombre ?? null;
                $this->descripcion = $data->descripcion ?? null;
                $this->orden = $data->orden ?? null;
                $this->fecha = $data->fecha ?? null;
            }
        }
    }

    public function toArray()
    {
        $data = [
            'idClase' => (int) $this->idclase,
            'idrClase' => (int) $this->idrclase,
            'nombre' => $this->nombre,
            'descripcion' => $this->descripcion,
            'orden' => $this->orden,
            'fecha' => $this->fecha
        ];
        if ($this->rclase !== null) {
            $data['rClase'] = $this->rclase->toArray();
        }
        return $data;
    }
}
