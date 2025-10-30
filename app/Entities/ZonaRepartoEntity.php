<?php

namespace App\Entities;

use CodeIgniter\Entity\Entity;

class ZonaRepartoEntity
{

    public $idzonareparto;
    public $idestado;
    public $nombre;
    public $costo;
    public $fecha;

    // Relaciones
    public $estado;
    public $ubigeo;
    public $rubigeo;
    public function __construct($data = null)
    {
        if ($data) {
            if (is_array($data)) {
                $this->idzonareparto = $data['idzonareparto'] ?? null;
                $this->idestado = $data['idestado'] ?? null;
                $this->nombre = $data['nombre'] ?? null;
                $this->costo = $data['costo'] ?? null;
                $this->fecha = $data['fecha'] ?? null;
            } elseif (is_object($data)) {
                $this->idzonareparto = $data->idzonareparto ?? null;
                $this->idestado = $data->idestado ?? null;
                $this->nombre = $data->nombre ?? null;
                $this->costo = $data->costo ?? null;
                $this->fecha = $data->fecha ?? null;
            }
        }
    }

    public function toArray()
    {
        $data = [
            'idZonaReparto' => (int) $this->idzonareparto,
            'idEstado' => (int) $this->idestado,
            'nombre' => $this->nombre,
            'costo' => $this->costo,
            'fecha' => $this->fecha
        ];

        if ($this->estado !== null) {
            $data['estado'] = $this->estado->toArray();
        }
        if ($this->ubigeo !== null) {
            $data['ubigeo'] = $this->ubigeo->toArray();
        }
        if ($this->rubigeo !== null) {
            $data['rUbigeo'] = $this->rubigeo->toArray();
        }
        return $data;
    }
}
