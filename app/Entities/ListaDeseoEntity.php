<?php

namespace App\Entities;

class ListaDeseoEntity
{
    public $idlistadeseo;
    public $idestado;
    public $idusuario;
    public $fecha;
    public $idproducto;


    public $estado;
    public $usuario;
    public $producto;

    public function __construct($data = null)
    {
        if ($data) {
            if (is_array($data)) {
                $this->idlistadeseo = $data['idlistadeseo'] ?? null;
                $this->idestado = $data['idestado'] ?? null;
                $this->idusuario = $data['idusuario'] ?? null;
                $this->fecha = $data['fecha'] ?? null;
                $this->idproducto = $data['idproducto'] ?? null;
            } elseif (is_object($data)) {
                $this->idlistadeseo = $data->idlistadeseo ?? null;
                $this->idestado = $data->idestado ?? null;
                $this->idusuario = $data->idusuario ?? null;
                $this->fecha = $data->fecha ?? null;
                $this->idproducto = $data->idproducto ?? null;
            }
        }
    }

    public function toArray()
    {
        $data = [
            'idListadeseo' => (int) $this->idlistadeseo,
            'idEstado'     => (int) $this->idestado,
            'idUsuario'    => (int) $this->idusuario,
            'fecha'        => $this->fecha,
            'idProducto'   => (int) $this->idproducto,
        ];

        if ($this->estado !== null) {
            $data['estado'] = is_object($this->estado) && method_exists($this->estado, 'toArray')
                ? $this->estado->toArray()
                : $this->estado;
        }
        if ($this->usuario !== null) {
            $data['usuario'] = is_object($this->usuario) && method_exists($this->usuario, 'toArray')
                ? $this->usuario->toArray()
                : $this->usuario;
        }
        if ($this->producto !== null) {
            $data['producto'] = is_object($this->producto) && method_exists($this->producto, 'toArray')
                ? $this->producto->toArray()
                : $this->producto;
        }

        return $data;
    }
}
