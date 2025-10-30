<?php

namespace App\Entities;

use CodeIgniter\Entity\Entity;

class FormaPagoEntity
{
    public $idformapago;
    public $idempresa;
    public $idestado;
    public $idptipo;
    public $nombre;
    public $abr;
    public $comision;
    public $contenido;
    public $contenido2;
    public $orden;
    public $fecha;

    // Relaciones
    public $estado;
    public $empresa;
    public $ptipo;

    public function __construct($data = null)
    {
        if ($data) {
            if (is_array($data)) {
                $this->idformapago = $data['idformapago'] ?? null;
                $this->idempresa = $data['idempresa'] ?? null;
                $this->idestado = $data['idestado'] ?? null;
                $this->idptipo = $data['idptipo'] ?? null;
                $this->nombre = $data['nombre'] ?? null;
                $this->abr = $data['abr'] ?? null;
                $this->comision = $data['comision'] ?? null;
                $this->contenido = $data['contenido'] ?? null;
                $this->contenido2 = $data['contenido2'] ?? null;
                $this->orden = $data['orden'] ?? null;
                $this->fecha = $data['fecha'] ?? null;
            } elseif (is_object($data)) {
                $this->idformapago = $data->idformapago ?? null;
                $this->idempresa = $data->idempresa ?? null;
                $this->idestado = $data->idestado ?? null;
                $this->idptipo = $data->idptipo ?? null;
                $this->nombre = $data->nombre ?? null;
                $this->abr = $data->abr ?? null;
                $this->comision = $data->comision ?? null;
                $this->contenido = $data->contenido ?? null;
                $this->contenido2 = $data->contenido2 ?? null;
                $this->orden = $data->orden ?? null;
                $this->fecha = $data->fecha ?? null;
            }
        }
    }

    public function toArray()
    {
        $data = [
            'idFormaPago'          => (int) $this->idformapago,
            'idEmpresa'          => (int) $this->idempresa,
            'idEstado'           => (int) $this->idestado,
            'pTipo'           => (int) $this->idptipo,
            'nombre'             => $this->nombre,
            'abr'        => $this->abr,
            'comision'        => $this->comision,
            'contenido'        => $this->contenido,
            'contenido2'        => $this->contenido2,
            'orden'              => $this->orden,
            'fecha'              => $this->fecha
        ];

        if ($this->estado !== null) {
            $data['estado'] = $this->estado->toArray();
        }
        if ($this->empresa !== null) {
            $data['empresa'] = $this->empresa->toArray();
        }
        if ($this->ptipo !== null) {
            $data['pTipo'] = $this->ptipo->toArray();
        }

        return $data;
    }
}
