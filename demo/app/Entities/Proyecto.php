<?php

namespace App\Entities;

use App\Models\ProyectoModel;
use CodeIgniter\Entity\Entity;

class Proyecto extends Entity
{

    protected $attributes = [
        'idEstado',
        'idPcategoria',
        'idCliente',
        'usuario',
        'nombre',
        'resumen',
        'descripcion',
        'urlImagen',
        'urlAmigable',
        'orden',
        'palabrasClaveSeo',
        'tituloSeo',
        'descripcionSeo',
        'fechaPublicacion',
        'fecha',
    ];

    protected $datamap = [
        'idEstado' => 'idestado',
        'idPcategoria' => 'idpcategoria',
        'idCliente' => 'idcliente',
        'usuario' => 'usuario',
        'nombre' => 'nombre',
        'resumen' => 'resumen',
        'descripcion' => 'descripcion',
        'urlImagen' => 'urlimagen',
        'urlAmigable' => 'urlamigable',
        'orden' => 'orden',
        'palabrasClaveSeo' => 'palabrasclaveseo',
        'tituloSeo' => 'tituloseo',
        'descripcionSeo' => 'descripcionseo',
        'fechaPublicacion' => 'fechapublicacion',
        'fecha' => 'fecha',
    ];

    static function obtenerById($idproyecto)
    {

        $proyecto = new ProyectoModel();
        return $proyecto->find($idproyecto);
    }

    function obtenerByUrl($url)
    {
        $proyecto = new ProyectoModel();
        return $proyecto->select('proyectos.*, (select e.nombre from estados e where proyectos.idestado=e.idestado) as estado',)
            ->where("proyectos.urlamigable", $url)->first();
    }

    public static function buscarPor($ordencriterio, $ordentipo, $parametro, $valor, $idestado, $idpcategoria, $idcliente, $inicio, $registros)
    {

        $builder = new ProyectoModel();
        $builder->select("proyectos.*, 
    (SELECT GROUP_CONCAT(urlimagen ORDER BY proyectoimagen.orden ASC) 
     FROM proyectoimagen 
     WHERE proyectoimagen.idproyecto = proyectos.idproyecto) AS imagenes,
    (SELECT e.nombre FROM estados e WHERE proyectos.idestado = e.idestado) AS estado");


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
                $builder->where('proyectos.nombre LIKE "%' . $valor . '%"');
            else
                $builder->where($parametro, 'like', '%' . $valor . '%');
        }


        if ($idestado > 0)
            $builder->where('proyectos.idestado', $idestado);

        if ($idpcategoria > 0)
            $builder->where('proyectos.idpcategoria', $idpcategoria);

        if ($idcliente > 0)
            $builder->where('proyectos.idpcontrolstock', $idcliente);

        if ($inicio >= 0 && $registros > 0)
            $builder->limit($registros, $inicio);

        if ($inicio >= 0 && $registros > 0)
            $query = $builder->findAll($registros, $inicio);
        else
            $query = $builder->findAll();

        return $query;
    }

    public static function buscarTotalPor($parametro, $valor, $idestado, $idpcategoria, $idcliente)
    {

        $builder = new ProyectoModel();
        $builder->select('proyectos.*, (select e.nombre from estado e where proyectos.idestado=e.idestado) as estado');


        if ($parametro != "" && $valor != "") {
            if ($parametro == 'nombre')
                $builder->where('proyectos.nombre LIKE "%' . $valor . '%"');
            else
                $builder->where($parametro, 'like', '%' . $valor . '%');
        }


        if ($idestado > 0)
            $builder->where('proyectos.idestado', $idestado);

        if ($idpcategoria > 0)
            $builder->where('proyectos.idpcategoria', $idpcategoria);

        if ($idcliente > 0)
            $builder->where('proyectos.idpcontrolstock', $idcliente);

        $query = $builder->countAllResults();

        return $query;
    }
}
