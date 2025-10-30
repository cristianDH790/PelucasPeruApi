<?php

namespace App\Entities;

class MarcaEntity
{
    public $idmarca;
    public $idestado;
    public $nombre;
    public $urlamigable;
    public $urlimagen;
    public $orden;
    public $fecha;
    public $contenido;
    public $descripcion;

    // Relaciones
    public $estado;
 


    public function __construct($data = null)
    {
        if ($data) {
            if (is_array($data)) {
                $this->idmarca = $data['idmarca'] ?? null;
                $this->idestado = $data['idestado'] ?? null;
                $this->nombre = $data['nombre'] ?? null;
                $this->urlamigable = $data['urlamigable'] ?? null;
                $this->contenido = $data['contenido'] ?? null;
                $this->descripcion = $data['descripcion'] ?? null;
                $this->urlimagen = $data['urlimagen'] ?? null;
                $this->orden = $data['orden'] ?? null;
                $this->fecha = $data['fecha'] ?? null;
            } elseif (is_object($data)) {
                $this->idmarca = $data->idmarca ?? null;
                $this->idestado = $data->idestado ?? null;
                $this->nombre = $data->nombre ?? null;
                $this->urlamigable = $data->urlamigable ?? null;
                $this->contenido = $data->contenido ?? null;
                $this->descripcion = $data->descripcion ?? null;
                $this->urlimagen = $data->urlimagen ?? null;
                $this->orden = $data->orden ?? null;
                $this->fecha = $data->fecha ?? null;
            }
        }
    }

    public function toArray()
    {
        $data = [
            'idMarca'           => (int) $this->idmarca,
            'idEstado'           => (int) $this->idestado,
            'urlAmigable' => (int) $this->urlamigable,
            'urlImagen'          => $this->urlimagen,
            'nombre'             => $this->nombre,
            'descripcion'             => $this->descripcion,
            'contenido'             => $this->contenido,
            'orden'            => $this->orden,
            'fecha'              => $this->fecha
        ];

        if ($this->estado !== null) {
            $data['estado'] = $this->estado->toArray();
        }
       


        return $data;
    }
}
