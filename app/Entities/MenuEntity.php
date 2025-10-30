<?php

namespace App\Entities;

class MenuEntity
{
    public $idmenu;
    public $idestado;
    public $idrmenu;
    public $idptipo;
    public $idpubicacion;
    public $idpdestino;
    public $nombre;
    public $destino;
    public $seccion;
    public $orden;
    public $fecha;

    // Relaciones (si existen modelos relacionados)
    public $estado;
    public $pdestino;
    public $ptipo;
    public $pubicacion; // ubicación del menú
    public $rmenu; // menú padre, si aplica

    public function __construct($data = null)
    {
        if ($data) {
            if (is_array($data)) {
                $this->idmenu        = $data['idmenu']        ?? null;
                $this->idestado      = $data['idestado']      ?? null;
                $this->idrmenu       = $data['idrmenu']       ?? null;
                $this->idptipo       = $data['idptipo']       ?? null;
                $this->idpubicacion  = $data['idpubicacion']  ?? null;
                $this->idpdestino    = $data['idpdestino']    ?? null;
                $this->nombre        = $data['nombre']        ?? null;
                $this->destino       = $data['destino']       ?? null;
                $this->seccion       = $data['seccion']       ?? null;
                $this->orden         = $data['orden']         ?? null;
                $this->fecha         = $data['fecha']         ?? null;
            } elseif (is_object($data)) {
                $this->idmenu        = $data->idmenu        ?? null;
                $this->idestado      = $data->idestado      ?? null;
                $this->idrmenu       = $data->idrmenu       ?? null;
                $this->idptipo       = $data->idptipo       ?? null;
                $this->idpubicacion  = $data->idpubicacion  ?? null;
                $this->idpdestino    = $data->idpdestino    ?? null;
                $this->nombre        = $data->nombre        ?? null;
                $this->destino       = $data->destino       ?? null;
                $this->seccion       = $data->seccion       ?? null;
                $this->orden         = $data->orden         ?? null;
                $this->fecha         = $data->fecha         ?? null;
            }
        }
    }

    public function toArray()
    {
        $data = [
            'idMenu'        => (int) $this->idmenu,
            'idEstado'      => (int) $this->idestado,
            'idRMenu'       =>(int) $this->idrmenu,
            'idPTipo'       =>(int) $this->idptipo,
            'idPUbicacion'  => (int)$this->idpubicacion,
            'idPDestino'    =>(int) $this->idpdestino,
            'nombre'        => $this->nombre,
            'destino'       => $this->destino,
            'seccion'       => $this->seccion,
            'orden'         => $this->orden,
            'fecha'         => $this->fecha
        ];

        if ($this->estado !== null && method_exists($this->estado, 'toArray')) {
            $data['estado'] = $this->estado->toArray();
        }

        if ($this->rmenu !== null && method_exists($this->rmenu, 'toArray')) {
            $data['rMenu'] = $this->rmenu->toArray();
        }
        if ($this->ptipo !== null && method_exists($this->ptipo, 'toArray')) {
            $data['pTipo'] = $this->ptipo->toArray();
        }
        if ($this->pdestino !== null && method_exists($this->pdestino, 'toArray')) {
            $data['pDestino'] = $this->pdestino->toArray();
        }
        if ($this->pubicacion !== null && method_exists($this->pubicacion, 'toArray')) {
            $data['pUbicacion'] = $this->pubicacion->toArray();
        }

        return $data;
    }
}
