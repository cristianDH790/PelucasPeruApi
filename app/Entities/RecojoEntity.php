<?php

namespace App\Entities;

class RecojoEntity
{
    public $idrecojo;
    public $idusuario;
    public $idsede;
    public $idestado;
    public $dni;
    public $nombres;
    public $apellidos;
    public $telefono;
    public $fecha;

    // Relaciones (opcionalmente puedes agregarlas si las usarás)
    public $usuario;
    public $sede;
    public $estado;

    public function __construct( $data = null)
    {
        if ($data) {
            if (is_array($data)) {
                $this->idrecojo   = $data['idrecojo'] ?? null;
                $this->idusuario   = $data['idusuario'] ?? null;
                $this->idsede    = $data['idsede'] ?? null;
                $this->idestado    = $data['idestado'] ?? null;
                $this->dni         = $data['dni'] ?? null;
                $this->nombres     = $data['nombres'] ?? null;
                $this->apellidos   = $data['apellidos'] ?? null;
                $this->telefono    = $data['telefono'] ?? null;
                $this->fecha       = $data['fecha'] ?? null;
            } elseif (is_object($data)) {
                $this->idrecojo   = $data->iderecojo ?? null;
                $this->idusuario   = $data->idusuario ?? null;
                $this->idsede    = $data->idsede ?? null;
                $this->idestado    = $data->idestado ?? null;
                $this->dni         = $data->dni ?? null;
                $this->nombres     = $data->nombres ?? null;
                $this->apellidos   = $data->apellidos ?? null;
                $this->telefono    = $data->telefono ?? null;
                $this->fecha       = $data->fecha ?? null;
            }
        }
    }

    public function toArray(): array
    {
        $data = [
            'idRecojo'   => (int) $this->idrecojo,
            'idUsuario'  => (int) $this->idusuario,
            'idTienda'   => (int) $this->idsede,
            'idEstado'   => (int) $this->idestado,
            'dni'        => $this->dni,
            'nombres'    => $this->nombres,
            'apellidos'  => $this->apellidos,
            'telefono'   => $this->telefono,
            'fecha'      => $this->fecha
        ];

        if ($this->usuario !== null) {
            $data['usuario'] = $this->usuario->toArray();
        }
        if ($this->sede !== null) {
            $data['sede'] = $this->sede->toArray();
        }
        if ($this->estado !== null) {
            $data['estado'] = $this->estado->toArray();
        }

        return $data;
    }
}
