<?php

namespace App\Entities;

class PublicidadEntity
{
    public $idpublicidad;
    public $idestado;
    public $idpdestino;
    public $nombre;
    public $titulo;
    public $urlimagen;
    public $urlrecurso;
    public $inicio;
    public $termino;
    public $fecha;

    // Relaciones
    public $estado;
    public $destino;


    public function __construct($data = null)
    {
        if ($data) {
            if (is_array($data)) {
                $this->idpublicidad = $data['idpublicidad'] ?? null;
                $this->idestado = $data['idestado'] ?? null;
                $this->idpdestino = $data['idpdestino'] ?? null;
                $this->nombre = $data['nombre'] ?? null;
                $this->titulo = $data['titulo'] ?? null;
                $this->urlimagen = $data['urlimagen'] ?? null;
                $this->urlrecurso = $data['urlrecurso'] ?? null;
                $this->inicio = $data['inicio'] ?? null;
                $this->termino = $data['termino'] ?? null;
                $this->fecha = $data['fecha'] ?? null;
            } elseif (is_object($data)) {
                $this->idpublicidad = $data->idpublicidad ?? null;
                $this->idestado = $data->idestado ?? null;
                $this->idpdestino = $data->idpdestino ?? null;
                $this->nombre = $data->nombre ?? null;
                $this->titulo = $data->titulo ?? null;
                $this->urlrecurso = $data->urlrecurso ?? null;
                $this->urlimagen = $data->urlimagen ?? null;
                $this->inicio = $data->inicio ?? null;
                $this->termino = $data->termino ?? null;
                $this->fecha = $data->fecha ?? null;
            }
        }
    }

    public function toArray()
    {
        $data = [
            'idPublicidad'           => (int) $this->idpublicidad,
            'idEstado'           => (int) $this->idestado,
            'idpDestino'           => (int) $this->idpdestino,
            'urlRecurso' =>  $this->urlrecurso,
            'nombre'             => $this->nombre,
            'titulo'             => $this->titulo,
            'urlImagen'          => $this->urlimagen,
            'inicio'            => $this->inicio,
            'termino'            => $this->termino,
            'fecha'              => $this->fecha
        ];

        if ($this->estado !== null) {
            $data['estado'] = $this->estado->toArray();
        }
        if ($this->destino !== null) {
            $data['destino'] = $this->destino->toArray();
        }


        return $data;
    }
}
