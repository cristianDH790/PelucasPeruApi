<?php

namespace App\Entities;

use CodeIgniter\Entity\Entity;

class AgenciaEntity
{
    // Campos de la tabla `textos_completos` para agencia
    public $idagencia;
    public $idusuario;
    public $idestado;
    public $idubigeo;
    public $agencia;
    public $direccion;
    public $referencia;
    public $nombres;
    public $apellidos;
    public $dni;
    public $telefono;
    public $latitud;
    public $longitud;
    public $fecha;

    // Relaciones
    public $usuario;
    public $ubigeo;

    public function __construct($data = null)
    {
        if ($data) {
            if (is_array($data)) {
                $this->idagencia  = $data['idagencia'] ?? null;
                $this->idusuario  = $data['idusuario'] ?? null;
                $this->idestado   = $data['idestado'] ?? null;
                $this->idubigeo   = $data['idubigeo'] ?? null;
                $this->agencia    = $data['agencia'] ?? null;
                $this->direccion  = $data['direccion'] ?? null;
                $this->referencia = $data['referencia'] ?? null;
                $this->nombres    = $data['nombres'] ?? null;
                $this->apellidos  = $data['apellidos'] ?? null;
                $this->dni        = $data['dni'] ?? null;
                $this->telefono   = $data['telefono'] ?? null;
                $this->latitud    = $data['latitud'] ?? null;
                $this->longitud   = $data['longitud'] ?? null;
                $this->fecha      = $data['fecha'] ?? null;
            } elseif (is_object($data)) {
                $this->idagencia  = $data->idagencia ?? null;
                $this->idusuario  = $data->idusuario ?? null;
                $this->idestado   = $data->idestado ?? null;
                $this->idubigeo   = $data->idubigeo ?? null;
                $this->agencia    = $data->agencia ?? null;
                $this->direccion  = $data->direccion ?? null;
                $this->referencia = $data->referencia ?? null;
                $this->nombres    = $data->nombres ?? null;
                $this->apellidos  = $data->apellidos ?? null;
                $this->dni        = $data->dni ?? null;
                $this->telefono   = $data->telefono ?? null;
                $this->latitud    = $data->latitud ?? null;
                $this->longitud   = $data->longitud ?? null;
                $this->fecha      = $data->fecha ?? null;
            }
        }
    }

    public function toArray()
    {
        $data = [
            'idAgencia'  => $this->idagencia,
            'idUsuario'  => $this->idusuario,
            'idEstado'   => $this->idestado,
            'idUbigeo'   => $this->idubigeo,
            'agencia'    => $this->agencia,
            'direccion'  => $this->direccion,
            'referencia' => $this->referencia,
            'nombres'    => $this->nombres,
            'apellidos'  => $this->apellidos,
            'dni'        => $this->dni,
            'telefono'   => $this->telefono,
            'latitud'    => $this->latitud,
            'longitud'   => $this->longitud,
            'fecha'      => $this->fecha,
        ];

        if ($this->usuario !== null) {
            $data['usuario'] = $this->usuario->toArray();
        }

        if ($this->ubigeo !== null) {
            $data['ubigeo'] = $this->ubigeo->toArray();
        }

        return $data;
    }
}
