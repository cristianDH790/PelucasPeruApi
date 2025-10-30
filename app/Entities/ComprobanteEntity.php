<?php

namespace App\Entities;

use CodeIgniter\Entity\Entity;

class ComprobanteEntity
{
    // Campos de la tabla `textos_completos`
    public $idcomprobante;
    public $idestado;
    public $idusuario;
    public $idubigeo;
    public $idptipo;
    public $ruc;
    public $razonsocial;
    public $direccion;
    public $fecha;

    // Relaciones
    public $usuario;
    public $ubigeo;
    public $ptipo;

    public function __construct($data = null)
    {
        if ($data) {
            if (is_array($data)) {
                $this->idcomprobante = $data['idcomprobante'] ?? null;
                $this->idestado      = $data['idestado'] ?? null;
                $this->idusuario     = $data['idusuario'] ?? null;
                $this->idubigeo      = $data['idubigeo'] ?? null;
                $this->idptipo       = $data['idptipo'] ?? null;
                $this->ruc           = $data['ruc'] ?? null;
                $this->razonsocial   = $data['razonsocial'] ?? null;
                $this->direccion     = $data['direccion'] ?? null;
                $this->fecha         = $data['fecha'] ?? null;
            } elseif (is_object($data)) {
                $this->idcomprobante = $data->idcomprobante ?? null;
                $this->idestado      = $data->idestado ?? null;
                $this->idusuario     = $data->idusuario ?? null;
                $this->idubigeo      = $data->idubigeo ?? null;
                $this->idptipo       = $data->idptipo ?? null;
                $this->ruc           = $data->ruc ?? null;
                $this->razonsocial   = $data->razonsocial ?? null;
                $this->direccion     = $data->direccion ?? null;
                $this->fecha         = $data->fecha ?? null;
            }
        }
    }

    public function toArray()
    {
        $data = [
            'idComprobante' => $this->idcomprobante,
            'idEstado'      => $this->idestado,
            'idUsuario'     => $this->idusuario,
            'idUbigeo'      => $this->idubigeo,
            'idPTipo'       => $this->idptipo,
            'ruc'           => $this->ruc,
            'razonSocial'   => $this->razonsocial,
            'direccion'     => $this->direccion,
            'fecha'         => $this->fecha,
        ];

        if ($this->usuario !== null) {
            $data['usuario'] = $this->usuario->toArray();
        }

        if ($this->ubigeo !== null) {
            $data['ubigeo'] = $this->ubigeo->toArray();
        }

        if ($this->ptipo !== null) {
            $data['ptipo'] = $this->ptipo->toArray();
        }

        return $data;
    }
}
