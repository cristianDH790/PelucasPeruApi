<?php

namespace App\Entities;

class UbigeoEntity
{
    public $idubigeo;
    public $idrubigeo;
    public $idestado;
    public $nombre;
    public $codigopostal;
    public $gentilicio;
    public $fecha;
    public $nivel;

    public $codigo; // ➕ Añadido
    public $ubigeolocalidad; // ➕ Añadido

    // Relaciones
    public $estado;
    public $rubigeo;

    public function __construct($data = null)
    {
        if ($data) {
            if (is_array($data)) {
                $this->idubigeo = $data['idubigeo'] ?? null;
                $this->idestado = $data['idestado'] ?? null;
                $this->idrubigeo = $data['idrubigeo'] ?? null;
                $this->nombre = $data['nombre'] ?? null;
                $this->gentilicio = $data['gentilicio'] ?? null;
                $this->nivel = $data['nivel'] ?? null;
                $this->codigopostal = $data['codigopostal'] ?? null;
                $this->fecha = $data['fecha'] ?? null;
                $this->codigo = $data['codigo'] ?? null;
                $this->ubigeolocalidad = $data['ubigeolocalidad'] ?? null;
            } elseif (is_object($data)) {
                $this->idubigeo = $data->idubigeo ?? null;
                $this->idestado = $data->idestado ?? null;
                $this->idrubigeo = $data->idrubigeo ?? null;
                $this->nombre = $data->nombre ?? null;
                $this->gentilicio = $data->gentilicio ?? null;
                $this->nivel = $data->nivel ?? null;
                $this->codigopostal = $data->codigopostal ?? null;
                $this->fecha = $data->fecha ?? null;
                $this->codigo = $data->codigo ?? null;
                $this->ubigeolocalidad = $data->ubigeolocalidad ?? null;
            }
        }
    }

    public function toArray()
    {
        $data = [
            'idUbigeo' => (int) $this->idubigeo,
            'idEstado' => (int) $this->idestado,
            'idrUbigeo' => (int) $this->idrubigeo,
            'nombre' => $this->nombre,
            'gentilicio' => $this->gentilicio,
            'nivel' => $this->nivel,
            'codigoPostal' => $this->codigopostal,
            'fecha' => $this->fecha,
            'codigo' => $this->codigo,
            'ubigeoLocalidad' => $this->ubigeolocalidad
        ];

        if ($this->estado !== null) {
            $data['estado'] = $this->estado->toArray();
        }

        if ($this->rubigeo !== null) {
            $data['rUbigeo'] = $this->rubigeo->toArray();
        }

        return $data;
    }
}
