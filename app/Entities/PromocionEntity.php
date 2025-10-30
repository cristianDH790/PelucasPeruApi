<?php

namespace App\Entities;

use CodeIgniter\Entity\Entity;

class PromocionEntity
{
    public $idpromocion;
    public $idestado;
    public $nombre;
    public $urlamigable;
    public $resumen;
    public $contenido;
    public $urlminiatura;
    public $urlimagen;
    public $urlredireccion;
    public $terminos;
    public $fechainicio;
    public $fechafin;
    public $accesos;
    public $fecha;

    // Relaciones
    public $estado;

    public function __construct(array $data = null)
    {
        if ($data) {
            if (is_array($data)) {
                $this->idpromocion = $data['idpromocion'] ?? null;
                $this->idestado = $data['idestado'] ?? null;
                $this->urlamigable = $data['urlamigable'] ?? null;
                $this->nombre = $data['nombre'] ?? null;
                $this->resumen = $data['resumen'] ?? null;
                $this->contenido = $data['contenido'] ?? null;
                $this->urlminiatura = $data['urlminiatura'] ?? null;
                $this->urlimagen = $data['urlimagen'] ?? null;
                $this->urlredireccion = $data['urlredireccion'] ?? null;
                $this->terminos = $data['terminos'] ?? null;
                $this->fechainicio = $data['fechainicio'] ?? null;
                $this->fechafin = $data['fechafin'] ?? null;
                $this->accesos = $data['accesos'] ?? null;
                $this->fecha = $data['fecha'] ?? null;
            } elseif (is_object($data)) {
                $this->idpromocion = $data['idpromocion'] ?? null;
                $this->idestado = $data->idestado ?? null;
                $this->urlamigable = $data->urlamigable ?? null;
                $this->nombre = $data->nombre ?? null;
                $this->resumen = $data->resumen ?? null;
                $this->contenido = $data->contenido ?? null;
                $this->urlminiatura = $data->urlminiatura ?? null;
                $this->urlimagen = $data->urlimagen ?? null;
                $this->urlredireccion = $data->urlredireccion ?? null;
                $this->terminos = $data->terminos ?? null;
                $this->fechainicio = $data->fechainicio ?? null;
                $this->fechafin = $data->fechafin ?? null;
                $this->accesos = $data->accesos ?? null;
                $this->fecha = $data->fecha ?? null;
            }
        }
    }

    public function toArray()
    {
        $data = [
            'idPromocion'           => (int) $this->idpromocion,
            'idEstado'           => (int) $this->idestado,
            'urlAmigable'        => $this->urlamigable,
            'nombre'             => $this->nombre,
            'resumen'            => $this->resumen,
            'contenido'     => $this->contenido,
            'urlminiatura'        => $this->urlminiatura,
            'urlImagen'          => $this->urlimagen,
            'urlredireccion'        => $this->urlredireccion,
            'terminos'        => $this->terminos,
            'fechainicio'               => $this->fechainicio,
            'fechafin'   => $this->fechafin,
            'accesos'   => $this->accesos,
            'fecha'              => $this->fecha
        ];

        if ($this->estado !== null) {
            $data['estado'] = $this->estado->toArray();
        }
       

        return $data;
    }
}
