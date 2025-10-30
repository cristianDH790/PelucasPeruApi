<?php

namespace App\Entities;

use CodeIgniter\Entity\Entity;

class ParametroEntity
{
    public $idparametro;
    public $idtipo;
    public $idestado;
    public $nombre;
    public $abr;
    public $descripcion;
    public $orden;
    public $fecha;
    // Relaciones
    public $tipo;
    public $estado;

     public $editable;
    public $requerido;

    public function __construct($data = null)
    {
        if ($data) {
            if (is_array($data)) {
                $this->idparametro = $data['idparametro'] ?? null;
                $this->idtipo = $data['idtipo'] ?? null;
                $this->idestado = $data['idestado'] ?? null;
                $this->nombre = $data['nombre'] ?? null;
                $this->abr = $data['abr'] ?? null;
                $this->descripcion = $data['descripcion'] ?? null;
                $this->orden = $data['orden'] ?? null;
                $this->fecha = $data['fecha'] ?? null;
            } elseif (is_object($data)) {
                $this->idparametro = $data->idparametro ?? null;
                $this->idtipo = $data->idtipo ?? null;
                $this->idestado = $data->idestado ?? null;
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
            'idParametro' => (int)$this->idparametro,
            'idTipo' => (int) $this->idtipo,
            'idEstado' => $this->idestado,
            'nombre' => $this->nombre,
            'abr' => $this->abr,
            'descripcion' => $this->descripcion,
            'orden' => $this->orden,
            'fecha' => $this->fecha
        ];

        if ($this->tipo !== null) {
            $data['tipo'] = $this->tipo->toArray();
        }

        if ($this->estado !== null) {
            $data['estado'] = $this->estado->toArray();
        }

        return $data;
    }
}
