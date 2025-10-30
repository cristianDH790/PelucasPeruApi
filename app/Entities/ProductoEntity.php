<?php

namespace App\Entities;

use CodeIgniter\Entity\Entity;

class ProductoEntity
{
    public $idproducto;
    public $idestado;
    public $idproductocategoria;
    public $idpromocion;
    public $idpcomplemento;
    public $idpdestacado;
    public $compraxcliente;
    public $idpajuste;
    public $idplongitud;
    public $idpcontrolstock;
    public $codigo;
    public $idmarca;
    public $guiatalla;
    public $nombre;
    public $urlamigable;
    public $color_urlamigable;
    public $resumen;
    public $resumen2;
    public $idcolor;
    public $contenido;
    public $stock;
    public $orden;
    public $urlimagen;
    public $urlimagen2;
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
    public $pajuste;
    public $plongitud;
    public $pcontrolstock;
    public $pcomplemento;
    public $marca;
    public $color;


    // Nueva propiedad para cupones
    public $cupones = [];
    public function __construct($data = null)
    {
        if ($data) {
            if (is_array($data)) {

                $this->idproducto = $data['idproducto'] ?? null;
                $this->idestado = $data['idestado'] ?? null;
                $this->idproductocategoria = $data['idproductocategoria'] ?? null;
                $this->idpromocion = $data['idpromocion'] ?? null;
                $this->idpajuste = $data['idpajuste'] ?? null;
                $this->idplongitud = $data['idplongitud'] ?? null;
                $this->idpcontrolstock = $data['idpcontrolstock'] ?? null;
                $this->idpdestacado = $data['idpdestacado'] ?? null;
                $this->compraxcliente = $data['compraxcliente'] ?? null;
                $this->idpcomplemento = $data['idpcomplemento'] ?? null;
                $this->codigo = $data['codigo'] ?? null;
                $this->idcolor = $data['idcolor'] ?? null;
                $this->idmarca = $data['idmarca'] ?? null;
                $this->guiatalla = $data['guiatalla'] ?? null;
                $this->urlamigable = $data['urlamigable'] ?? null;
                $this->nombre = $data['nombre'] ?? null;
                $this->orden = $data['orden'] ?? null;
                $this->resumen = $data['resumen'] ?? null;
                $this->resumen2 = $data['resumen2'] ?? null;
                $this->color_urlamigable = $data['color_urlamigable'] ?? null;
                $this->contenido = $data['contenido'] ?? null;
                $this->stock = $data['stock'] ?? null;
                $this->urlimagen = $data['urlimagen'] ?? null;
                $this->urlimagen2 = $data['urlimagen2'] ?? null;
                $this->preciolista = $data['preciolista'] ?? null;
                $this->precioventa = $data['precioventa'] ?? null;
                $this->peso = $data['peso'] ?? null;
                $this->fechapublicacion = $data['fechapublicacion'] ?? null;
                $this->fecha = $data['fecha'] ?? null;
                // Si vienen cupones, asignarlos
                $this->cupones = $data['cupones'] ?? [];
            } elseif (is_object($data)) {
                $this->idproducto = $data->idproducto ?? null;
                $this->idestado = $data->idestado ?? null;
                $this->idproductocategoria = $data->idproductocategoria ?? null;
                $this->idpromocion = $data->idpromocion ?? null;
                $this->idpdestacado = $data->idpdestacado ?? null;
                $this->idplongitud = $data->idplongitud ?? null;
                $this->idpcontrolstock = $data->idpcontrolstock ?? null;
                $this->compraxcliente = $data->compraxcliente ?? null;
                $this->idmarca = $data->idmarca ?? null;
                $this->guiatalla = $data->guiatalla ?? null;
                $this->idpajuste = $data->idpajuste ?? null;
                $this->codigo = $data->codigo ?? null;
                $this->urlamigable = $data->urlamigable ?? null;
                $this->nombre = $data->nombre ?? null;
                $this->resumen = $data->resumen ?? null;
                $this->color_urlamigable = $data->color_urlamigable ?? null;
                $this->idpcomplemento = $data->idpcomplemento ?? null;
                $this->resumen2 = $data->resumen2 ?? null;
                $this->contenido = $data->contenido ?? null;
                $this->stock = $data->stock ?? null;
                $this->idcolor = $data->idcolor ?? null;
                $this->orden = $data->orden ?? null;
                $this->urlimagen = $data->urlimagen ?? null;
                $this->urlimagen2 = $data->urlimagen2 ?? null;
                $this->preciolista = $data->preciolista ?? null;
                $this->precioventa = $data->precioventa ?? null;
                $this->peso = $data->peso ?? null;
                $this->fechapublicacion = $data->fechapublicacion ?? null;
                $this->fecha = $data->fecha ?? null;
                // Cupones
                $this->cupones = $data->cupones ?? [];
            }
        }
    }

    public function toArray()
    {
        $data = [
            'idProducto' => (int) $this->idproducto,
            'idEstado'           => (int) $this->idestado,
            'idProductoCategoria' => (int) $this->idproductocategoria,
            'idColor' => (int) $this->idcolor,
            'pPromocion' => (int) $this->idpromocion,
            'pDestacado'       => (int) $this->idpdestacado,
            'codigo'             => $this->codigo,
            'urlAmigable'        => $this->urlamigable,
            'nombre'             => $this->nombre,
            'resumen'            => $this->resumen,
            'resumen2'            => $this->resumen2,
            'compraXCliente'     => $this->compraxcliente,
            'contenido'        => $this->contenido,
            'urlImagen'          => $this->urlimagen,
            'urlImagen2'          => $this->urlimagen2,
            'colorUrlamigable'          => $this->color_urlamigable,
            'stock'          => $this->stock,
            'orden'          => $this->orden,
            'precioLista'        => $this->preciolista,
            'precioVenta'        => $this->precioventa,
            'peso'               => $this->peso,
            'fechaPublicacion'   => $this->fechapublicacion,
            // 'descuento'   =>  round((($this->preciolista - $this->precioventa) / $this->preciolista) * 100),
            'fecha'              => $this->fecha,

             'cupones' => $this->cupones
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
        if ($this->pcomplemento !== null) {
            $data['pComplemento'] = $this->pcomplemento->toArray();
        }
        if ($this->pajuste !== null) {
            $data['pAjuste'] = $this->pajuste->toArray();
        }
        if ($this->plongitud !== null) {
            $data['pLongitud'] = $this->plongitud->toArray();
        }
        if ($this->pcontrolstock !== null) {
            $data['pControlStock'] = $this->pcontrolstock->toArray();
        }
        if ($this->marca !== null) {
            $data['marca'] = $this->marca->toArray();
        }
        if ($this->color !== null) {
            $data['color'] = $this->color->toArray();
        }

        return $data;
    }
}
