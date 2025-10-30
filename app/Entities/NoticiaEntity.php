<?php

namespace App\Entities;

use CodeIgniter\Entity\Entity;

class NoticiaEntity
{
    public $idnoticia;
    public $idestado;
    public $idnoticiacategoria;
    public $idusuario;
    public $idpdestacado;
    public $nombre;
    public $urlamigable;
    public $descripcionseo;
    public $palabrasclaveseo;
    public $tituloseo;
    public $resumen;
    public $contenido;
    public $urlimagen;
    public $orden;
    public $fechapublicacion;
    public $fecha;

    // Relaciones
    public $estado;
    public $pdestacado;
    public $noticiacategoria;

    public function __construct( $data = null)
    {
        if ($data) {
        if (is_array($data)) {
            $this->idnoticia = $data['idnoticia'] ?? null;
            $this->idestado = $data['idestado'] ?? null;
            $this->idnoticiacategoria = $data['idnoticiacategoria'] ?? null;
            $this->idusuario = $data['idusuario'] ?? null;
            $this->idpdestacado = $data['idpdestacado'] ?? null;
            $this->nombre = $data['nombre'] ?? null;
            $this->urlamigable = $data['urlamigable'] ?? null;
            $this->descripcionseo = $data['descripcionseo'] ?? null;
            $this->palabrasclaveseo = $data['palabrasclaveseo'] ?? null;
            $this->tituloseo = $data['tituloseo'] ?? null;
            $this->resumen = $data['resumen'] ?? null;
            $this->contenido = $data['contenido'] ?? null;
            $this->urlimagen = $data['urlimagen'] ?? null;
            $this->orden = $data['orden'] ?? null;
            $this->fechapublicacion = $data['fechapublicacion'] ?? null;
            $this->fecha = $data['fecha'] ?? null;
        } elseif (is_object($data)) {
            $this->idnoticia = $data->idnoticia ?? null;
            $this->idestado = $data->idestado ?? null;
            $this->idnoticiacategoria = $data->idnoticiacategoria ?? null;
            $this->idusuario = $data->idusuario ?? null;
            $this->idpdestacado = $data->idpdestacado ?? null;
            $this->nombre = $data->nombre ?? null;
            $this->urlamigable = $data->urlamigable ?? null;
            $this->descripcionseo = $data->descripcionseo ?? null;
            $this->palabrasclaveseo = $data->palabrasclaveseo ?? null;
            $this->tituloseo = $data->tituloseo ?? null;
            $this->resumen = $data->resumen ?? null;
            $this->contenido = $data->contenido ?? null;
            $this->urlimagen = $data->urlimagen ?? null;
            $this->orden = $data->orden ?? null;
            $this->fechapublicacion = $data->fechapublicacion ?? null;
            $this->fecha = $data->fecha ?? null;
        }
    }
    }

    public function toArray()
    {
        $data = [
            'idNoticia'          => (int) $this->idnoticia,
            'idEstado'           => (int) $this->idestado,
            'idNoticiaCategoria' => (int) $this->idnoticiacategoria,
            'idUsuario'          => (int) $this->idusuario,
            'idpDestacado'       => (int) $this->idpdestacado,
            'nombre'             => $this->nombre,
            'urlAmigable'        => $this->urlamigable,
            'descripcionSeo'     => $this->descripcionseo,
            'palabrasclaveseo'   => $this->palabrasclaveseo,
            'tituloseo'          => $this->tituloseo,
            'resumen'            => $this->resumen,
            'contenido'          => $this->contenido,
            'urlImagen'          => $this->urlimagen,
            'orden'              => $this->orden,
            'fechaPublicacion'   => $this->fechapublicacion,
            'fecha'              => $this->fecha
        ];

        if ($this->estado !== null) {
            $data['estado'] = $this->estado->toArray();
        }

        if ($this->pdestacado !== null) {
            $data['pDestacado'] = $this->pdestacado->toArray();
        }

        if ($this->noticiacategoria !== null) {
            $data['noticiaCategoria'] = $this->noticiacategoria->toArray();
        }

        return $data;
    }
}
