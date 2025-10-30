<?php

namespace App\Entities;

use CodeIgniter\Entity\Entity;

class ProductoBaseEntity
{
    public $idproductobase;
    public $idestado;
    public $idproductocategoria;
    public $idpromocion;
    public $idpdestacado;
    public $codigo;
    public $nombre;
    public $urlamigable;
    public $resumen;
    public $descripcionseo;
    public $descripcion;
    public $urlimagen;
    public $preciolista;
    public $precioventa;
    public $peso;
    public $fechapublicacion;
    public $fecha;

    // Relaciones
    public $estado;
    public $productocategoria;
    public $ppromocion;
    public $pdestacado;
    public $marca;

    public function __construct($data = null)
    {
        if ($data) {
            if (is_array($data)) {

                $this->idproductobase = $data['idproductobase'] ?? null;
                $this->idestado = $data['idestado'] ?? null;
                $this->idproductocategoria = $data['idproductocategoria'] ?? null;
                $this->idpromocion = $data['idpromocion'] ?? null;
                $this->idpdestacado = $data['idpdestacado'] ?? null;
                $this->codigo = $data['codigo'] ?? null;
                $this->urlamigable = $data['urlamigable'] ?? null;
                $this->nombre = $data['nombre'] ?? null;
                $this->resumen = $data['resumen'] ?? null;
                $this->descripcionseo = $data['descripcionseo'] ?? null;
                $this->descripcion = $data['descripcion'] ?? null;
                $this->urlimagen = $data['urlimagen'] ?? null;
                $this->preciolista = $data['preciolista'] ?? null;
                $this->precioventa = $data['precioventa'] ?? null;
                $this->peso = $data['peso'] ?? null;
                $this->fechapublicacion = $data['fechapublicacion'] ?? null;
                $this->fecha = $data['fecha'] ?? null;
            } elseif (is_object($data)) {
                $this->idproductobase = $data->idproductobase ?? null;
                $this->idestado = $data->idestado ?? null;
                $this->idproductocategoria = $data->idproductocategoria ?? null;
                $this->idpromocion = $data->idpromocion ?? null;
                $this->idpdestacado = $data->idpdestacado ?? null;
                $this->codigo = $data->codigo ?? null;
                $this->urlamigable = $data->urlamigable ?? null;
                $this->nombre = $data->nombre ?? null;
                $this->resumen = $data->resumen ?? null;
                $this->descripcionseo = $data->descripcionseo ?? null;
                $this->descripcion = $data->descripcion ?? null;
                $this->urlimagen = $data->urlimagen ?? null;
                $this->preciolista = $data->preciolista ?? null;
                $this->precioventa = $data->precioventa ?? null;
                $this->peso = $data->peso ?? null;
                $this->fechapublicacion = $data->fechapublicacion ?? null;
                $this->fecha = $data->fecha ?? null;
            }
        }
    }

    public function toArray()
    {
        $data = [
            'idProductoBase' => (int) $this->idproductobase,
            'idEstado'           => (int) $this->idestado,
            'idProductoCategoria' => (int) $this->idproductocategoria,
            'pPromocion' => (int) $this->idpromocion,
            'pDestacado'       => (int) $this->idpdestacado,
            'codigo'             => $this->codigo,
            'urlAmigable'        => $this->urlamigable,
            'nombre'             => $this->nombre,
            'resumen'            => $this->resumen,
            'descripcionSeo'     => $this->descripcionseo,
            'descripcion'        => $this->descripcion,
            'urlImagen'          => $this->urlimagen,
            'precioLista'        => $this->preciolista,
            'precioVenta'        => $this->precioventa,
            'peso'               => $this->peso,
            'fechaPublicacion'   => $this->fechapublicacion,
            'fecha'              => $this->fecha
        ];

        if ($this->estado !== null) {
            $data['estado'] = $this->estado->toArray();
        }
        if ($this->productocategoria !== null) {
            $data['productoCategoria'] = $this->productocategoria->toArray();
        }
        if ($this->pdestacado !== null) {
            $data['pDestacado'] = $this->pdestacado->toArray();
        }
        if ($this->ppromocion !== null) {
            $data['pPromocion'] = $this->ppromocion->toArray();
        }
        if ($this->marca !== null) {
            $data['marca'] = $this->marca->toArray();
        }

        return $data;
    }
}
