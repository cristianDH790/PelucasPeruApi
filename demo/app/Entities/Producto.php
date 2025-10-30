<?php

namespace App\Entities;

use App\Models\ProductoModel;
use CodeIgniter\Entity\Entity;

class Producto extends Entity
{

    protected $attributes = [
        'idEstado',
        'idPdestacado',
        'sku',
        'nombre',
        'urlAmigable',
        'resumen',
        'contenido',
        'urlImagen',
        'urlBrochure',
        'precioLista',
        'precioVenta',
        'peso',
        'orden',
        'fechaPublicacion',
        'fecha',
    ];

    protected $datamap = [
        'idEstado' => 'idestado',
        'idPdestacado' => 'idpdestacado',
        'sku' => 'sku',
        'nombre' => 'nombre',
        'urlAmigable' => 'urlamigable',
        'resumen' => 'resumen',
        'contenido' => 'contenido',
        'urlImagen' => 'urlimagen',
        'urlBrochure' => 'urlbrochure',
        'precioLista' => 'preciolista',
        'precioVenta' => 'precioventa',
        'peso' => 'peso',
        'orden' => 'orden',
        'fechaPublicacion' => 'fechapublicacion',
        'fecha' => 'fecha',
    ];

    static function obtenerById($idproducto)
    {

        $producto = new ProductoModel();
        return $producto->find($idproducto);
    }

    public static function obtenerByUrl($url)
    {
        $producto = new ProductoModel();
        return $producto->select('productos.*, (select e.nombre from estados e where productos.idestado=e.idestado) as estado',)
            ->where("productos.urlamigable", $url)->first();
    }

    public static function buscarPor($ordencriterio, $ordentipo, $parametro, $valor, $idestado, $idpdestacado, $inicio, $registros)
    {

        $builder = new ProductoModel();
        $builder->select('productos.*, (select e.nombre from estados e where productos.idestado=e.idestado) as estado');

        if ($ordencriterio != "" && $ordentipo != "") {
            if ($ordencriterio == "fecha_desc")
                $builder->orderBy("fecha", "desc");
            elseif ($ordencriterio == "nombre_asc")
                $builder->orderBy("nombre", "asc");
                elseif ($ordencriterio == "nombre_desc")
                $builder->orderBy("nombre", "desc");
        }

        if ($parametro != "" && $valor != "") {
            if ($parametro == 'nombre')
                $builder->where('productos.nombre LIKE "%' . $valor . '%"');
            else
                $builder->where($parametro, 'like', '%' . $valor . '%');
        }


        if ($idestado > 0)
            $builder->where('productos.idestado', $idestado);

        if ($idpdestacado > 0)
            $builder->where('productos.idpdestacado', $idpdestacado);

        if ($inicio >= 0 && $registros > 0)
            $builder->limit($registros, $inicio);

        if ($inicio >= 0 && $registros > 0)
            $query = $builder->findAll($registros, $inicio);
        else
            $query = $builder->findAll();

        return $query;
    }

    public static function buscarTotalPor($parametro, $valor, $idestado, $idpdestacado)
    {

        $builder = new ProductoModel();
        $builder->select('productos.*, (select e.nombre from estado e where productos.idestado=e.idestado) as estado');


        if ($parametro != "" && $valor != "") {
            if ($parametro == 'nombre')
                $builder->where('productos.nombre LIKE "%' . $valor . '%"');
            else
                $builder->where($parametro, 'like', '%' . $valor . '%');
        }


        if ($idestado > 0)
            $builder->where('productos.idestado', $idestado);

        if ($idpdestacado > 0)
            $builder->where('productos.idpdestacado', $idpdestacado);

        $query = $builder->countAllResults();

        return $query;
    }
}
