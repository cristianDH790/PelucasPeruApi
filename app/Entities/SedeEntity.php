<?php

namespace App\Entities;

use CodeIgniter\Entity\Entity;

class SedeEntity
{
    public $idsede;
    public $idestado;
    public $idempresa;
    public $idubigeo;
    public $nombre;
    public $urlcabecera;
    public $telefono;
    public $direccion;
    public $orden;
    public $latitud;
    public $longitud;
    public $fecha;

    // Relaciones
    public $estado;
    public $empresa;
    public $ubigeo;

    public function __construct($data = null)
    {
        if ($data) {
            if (is_array($data)) {
                $this->idsede = $data['idsede'] ?? null;
                $this->idestado = $data['idestado'] ?? null;
                $this->idempresa = $data['idempresa'] ?? null;
                $this->idubigeo = $data['idubigeo'] ?? null;
                $this->nombre = $data['nombre'] ?? null;
                $this->urlcabecera = $data['urlcabecera'] ?? null;
                $this->telefono = $data['telefono'] ?? null;
                $this->direccion = $data['direccion'] ?? null;
                $this->orden = $data['orden'] ?? null;
                $this->latitud = $data['latitud'] ?? null;
                $this->longitud = $data['longitud'] ?? null;
                $this->fecha = $data['fecha'] ?? null;
            } elseif (is_object($data)) {
                $this->idsede = $data->idsede ?? null;
                $this->idestado = $data->idestado ?? null;
                $this->idempresa = $data->idempresa ?? null;
                $this->idubigeo = $data->idubigeo ?? null;
                $this->nombre = $data->nombre ?? null;
                $this->urlcabecera = $data->urlcabecera ?? null;
                $this->telefono = $data->telefono ?? null;
                $this->direccion = $data->direccion ?? null;
                $this->orden = $data->orden ?? null;
                $this->latitud = $data->latitud ?? null;
                $this->longitud = $data->longitud ?? null;
                $this->fecha = $data->fecha ?? null;
            }
        }
    }

    public function toArray()
    {
        $data = [
            'idSede' => (int) $this->idsede,
            'idEstado' => (int) $this->idestado,
            'idEmpresa' => (int) $this->idempresa,
            'idUbigeo' => (int) $this->idubigeo,
            'nombre' => $this->nombre,
            'urlCabecera' => $this->urlcabecera,
            'telefono' => $this->telefono,
            'direccion' => $this->direccion,
            'orden' => $this->orden,
            'latitud' => $this->latitud,
            'longitud' => $this->longitud,
            'fecha' => $this->fecha
        ];

        if ($this->estado !== null) {
            $data['estado'] = $this->estado->toArray();
        }

        if ($this->empresa !== null) {
            $data['empresa'] = $this->empresa->toArray();
        }

        // if ($this->ubigeo !== null) {
        //     $data['ubigeo'] = $this->ubigeo->toArray();
        // }
        if ($this->ubigeo !== null) {
            $data['ubigeo'] = $this->convertirUbigeoJerarquicoAArray($this->ubigeo);
        }


        return $data;
    }
    private function convertirUbigeoJerarquicoAArray($ubigeo)
    {
        if (!$ubigeo) return null;

        $resultado = [
            'idUbigeo' => $ubigeo->idUbigeo ?? null,
            'idEstado' => $ubigeo->idEstado ?? null,
            'idrUbigeo' => $ubigeo->idrUbigeo ?? null,
            'nombre' => $ubigeo->nombre ?? null,
            'codigo' => $ubigeo->codigo ?? null,
            'gentilicio' => $ubigeo->gentilicio ?? null,
            'fecha' => $ubigeo->fecha ?? null
        ];

        if (!empty($ubigeo->rUbigeo)) {
            $resultado['rUbigeo'] = $this->convertirUbigeoJerarquicoAArray($ubigeo->rUbigeo);
        }

        return $resultado;
    }
}
