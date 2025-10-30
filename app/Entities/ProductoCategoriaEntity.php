<?php

namespace App\Entities;

use CodeIgniter\Entity\Entity;

class ProductoCategoriaEntity
{
    public $idestado;
    public $idproductocategoria;
    public $nombre;
    public $contenido;
    public $idrproductocategoria;
    public $urlamigable;
    public $urlimagen;
    public $urlimagenbanner;
    public $orden;
    public $fecha;

    // Relaciones
    public $estado;
    public $rproductocategoria;


    public function __construct( $data = null )
    {
        if ($data) {
            if (is_array($data)) {
                $this->idproductocategoria = $data['idproductocategoria'] ?? null;
                $this->idestado = $data['idestado'] ?? null;
                $this->urlamigable = $data['urlamigable'] ?? null;
                $this->nombre = $data['nombre'] ?? null;
                $this->contenido = $data['contenido'] ?? null;
                $this->idrproductocategoria = $data['idrproductocategoria'] ?? null;
                $this->urlimagen = $data['urlimagen'] ?? null;
                $this->urlimagenbanner = $data['urlimagen'] ?? null;
                $this->orden = $data['orden'] ?? null;
                $this->fecha = $data['fecha'] ?? null;
            } elseif (is_object($data)) {
                $this->idproductocategoria = $data->idproductocategoria ?? null;
                $this->idestado = $data->idestado ?? null;
                $this->nombre = $data->nombre ?? null;
                $this->contenido = $data->contenido ?? null;
                $this->idrproductocategoria = $data->idrproductocategoria ?? null;
                $this->urlamigable = $data->urlamigable ?? null;
                $this->urlimagen = $data->urlimagen ?? null;
                $this->urlimagenbanner = $data->urlimagenbanner ?? null;
                $this->orden = $data->orden ?? null;
                $this->fecha = $data->fecha ?? null;
            }
        }
    }

    public function toArray()
    {
        $data = [
            'idProductoCategoria' => (int) $this->idproductocategoria,
            'idEstado'           => (int) $this->idestado,
            'nombre'             => $this->nombre,
            'contenido'             => $this->contenido,
            'idrProductoCategoria'  => (int)$this->idrproductocategoria,
            'urlAmigable'        => $this->urlamigable,
            'urlImagen'          => $this->urlimagen,
            'urlImagenBanner'        => $this->urlimagenbanner,
            'orden'              => $this->orden,
            'fecha'              => $this->fecha,
        ];

        if ($this->estado !== null) {
            $data['estado'] = $this->estado->toArray();
        }
        if ($this->rproductocategoria !== null) {
            $data['rProductoCategoria'] = $this->rproductocategoria->toArray();
        }

        return $data;
    }
}
