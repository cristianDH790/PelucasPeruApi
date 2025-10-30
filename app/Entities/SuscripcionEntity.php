<?php

namespace App\Entities;

use CodeIgniter\Entity\Entity;

class SuscripcionEntity
{
    // Propiedades principales (campos directos de la tabla)
    public $idsuscripcion;
    public $correo;
    public $fecha;


    // Relaciones (deben cargarse externamente)
    public $ptipo;

    public function __construct($data = null)
    {
        if ($data) {
            if (is_array($data)) {
                $this->idsuscripcion = $data['idsuscripcion'] ?? null;
                $this->correo = $data['correo'] ?? null;
                $this->fecha = $data['fecha'] ?? null;
            } elseif (is_object($data)) {
                $this->idsuscripcion = $data->idsuscripcion ?? null;
                $this->correo = $data->correo ?? null;
                $this->fecha = $data->fecha ?? null;
            }
        }
    }


    public function toArray(): array
    {
        // Datos base del asociado
        $data = [
            'idSuscripcion' => (int) $this->idsuscripcion,
            'correo' => $this->correo,
            'fecha' => $this->fecha,
        ];

        return $data;
    }
}
