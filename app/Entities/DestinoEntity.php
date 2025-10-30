<?php

namespace App\Entities;

use CodeIgniter\Entity\Entity;

class DestinoEntity
{
    public $iddestino;
    public $idubigeo;
    public $idusuario;
    public $idestado;
    public $idptipo;
    public $alias;
    public $nombres;
    public $apellidos;
    public $dni;
    public $direccion;
    public $referencia;
    public $telefono;
    public $latitud;
    public $longitud;
    public $fecha;

    // Relaciones
    public $estado;
    public $ubigeo;
    public $usuario;
    public $ptipo;


    public function __construct($data = null)
    {
        if ($data) {
            if (is_array($data)) {
                $this->iddestino   = $data['iddestino'] ?? null;
                $this->idubigeo    = $data['idubigeo'] ?? null;
                $this->idusuario   = $data['idusuario'] ?? null;
                $this->idestado    = $data['idestado'] ?? null;
                $this->idptipo     = $data['idptipo'] ?? null;
                $this->alias       = $data['alias'] ?? null;
                $this->nombres     = $data['nombres'] ?? null;
                $this->apellidos   = $data['apellidos'] ?? null;
                $this->dni         = $data['dni'] ?? null;
                $this->direccion   = $data['direccion'] ?? null;
                $this->referencia  = $data['referencia'] ?? null;
                $this->telefono    = $data['telefono'] ?? null;
                $this->latitud     = $data['latitud'] ?? null;
                $this->longitud    = $data['longitud'] ?? null;
                $this->fecha       = $data['fecha'] ?? null;
            } elseif (is_object($data)) {
                $this->iddestino   = $data->iddestino ?? null;
                $this->idubigeo    = $data->idubigeo ?? null;
                $this->idusuario   = $data->idusuario ?? null;
                $this->idestado    = $data->idestado ?? null;
                $this->idptipo     = $data->idptipo ?? null;
                $this->alias       = $data->alias ?? null;
                $this->nombres     = $data->nombres ?? null;
                $this->apellidos   = $data->apellidos ?? null;
                $this->dni         = $data->dni ?? null;
                $this->direccion   = $data->direccion ?? null;
                $this->referencia  = $data->referencia ?? null;
                $this->telefono    = $data->telefono ?? null;
                $this->latitud     = $data->latitud ?? null;
                $this->longitud    = $data->longitud ?? null;
                $this->fecha       = $data->fecha ?? null;
            }
        }
    }

    public function toArray()
    {
        $data = [
            'idDestino'   => (int) $this->iddestino,
            'idUbigeo'    => (int) $this->idubigeo,
            'idUsuario'   => (int) $this->idusuario,
            'idEstado'    => (int) $this->idestado,
            'idpTipo'     => (int) $this->idptipo,
            'alias'       => $this->alias,
            'nombres'     => $this->nombres,
            'apellidos'   => $this->apellidos,
            'dni'         => $this->dni,
            'direccion'   => $this->direccion,
            'referencia'  => $this->referencia,
            'telefono'    => $this->telefono,
            'latitud'     => $this->latitud,
            'longitud'    => $this->longitud,
            'fecha'       => $this->fecha
        ];

        if ($this->estado !== null) {
            $data['estado'] = $this->estado->toArray();
        }
        if ($this->ubigeo !== null) {
            $data['ubigeo'] = $this->ubigeo->toArray();
        }
        if ($this->usuario !== null) {
            $data['usuario'] = $this->usuario->toArray();
        }
        if ($this->ptipo !== null) {
            $data['pTipo'] = $this->ptipo->toArray();
        }

        return $data;
    }
}
