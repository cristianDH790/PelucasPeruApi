<?php

namespace App\Entities;

use App\Models\NoticiaModel;
use App\Models\ProyectoModel;
use CodeIgniter\Entity\Entity;

class Noticia extends Entity
{

    protected $attributes = [
        'idEstado',
        'idNoticiaCategoria',
        'titulo',
        'reusmen',
        'contenido',
        'urlImagen',
        'urlAmigable',
        'orden',
        'tituloSeo',
        'descripcionSeo',
        'palabrasClaveSeo',
        'fechaPublicacion',
        'fecha',
    ];

    protected $datamap = [
        'idEstado' => 'idestado',
        'idNoticiaCategoria' => 'idnoticiacategoria',
        'titulo' => 'titulo',
        'reusmen' => 'reusmen',
        'contenido' => 'contenido',
        'urlImagen' => 'urlimagen',
        'urlAmigable' => 'urlamigable',
        'orden' => 'orden',
        'tituloSeo' => 'tituloseo',
        'descripcionSeo' => 'descripcionseo',
        'palabrasClaveSeo' => 'palabrasclaveseo',
        'fechaPublicacion' => 'fechapublicacion',
        'fecha' => 'fecha',
    ];

    public static function obtenerById($idnoticia)
    {

        $noticia = new NoticiaModel();
        return $noticia->find($idnoticia);
    }

    public static function obtenerByUrl($url)
    {
        $noticia = new NoticiaModel();
        return $noticia->select('noticias.*, (select e.nombre from estados e where noticias.idestado=e.idestado) as estado,
        (select p.nombre from noticiacategoria p where noticias.idnoticiacategoria=p.idnoticiacategoria) as noticiacategoria',)
            ->where("noticias.urlamigable", $url)->first();
    }

    public static function buscarPor($ordencriterio, $ordentipo, $parametro, $valor, $idestado, $idnoticiacategoria, $inicio, $registros)
    {

        $builder = new NoticiaModel();
        $builder->select('noticias.*,
            (select e.nombre from estados e where noticias.idestado=e.idestado) as estado,
            (select p.nombre from noticiacategoria p where noticias.idnoticiacategoria=p.idnoticiacategoria) as noticiacategoria');

        if ($ordencriterio != "" && $ordentipo != "") {
            if ($ordencriterio == "fecha_asc")
                $builder->orderBy("fechapublicacion", "asc");
            elseif ($ordencriterio == "fecha_desc")
                $builder->orderBy("fechapublicacion", "desc");
            elseif ($ordencriterio == "titulo_asc")
                $builder->orderBy("titulo", "asc");
            elseif ($ordencriterio == "titulo_desc")
                $builder->orderBy("titulo", "desc");
            elseif ($ordencriterio == "random")
                $builder->orderBy('RAND()');
        }

        if ($parametro != "" && $valor != "") {
            if ($parametro == 'nombre')
                $builder->where('noticias.titulo LIKE "%' . $valor . '%"');
            else
                $builder->where($parametro, 'like', '%' . $valor . '%');
        }


        if ($idestado > 0)
            $builder->where('noticias.idestado', $idestado);

        if ($idnoticiacategoria > 0)
            $builder->where('noticias.idnoticiacategoria', $idnoticiacategoria);

        if ($inicio >= 0 && $registros > 0)
            $builder->limit($registros, $inicio);

        if ($inicio >= 0 && $registros > 0)
            $query = $builder->findAll($registros, $inicio);
        else
            $query = $builder->findAll();

        return $query;
    }

    public static function buscarTotalPor($parametro, $valor, $idestado,$idnoticiacategoria)
    {

        $builder = new NoticiaModel();
        $builder->select('noticias.*, (select e.nombre from estado e where noticias.idestado=e.idestado) as estado');


        if ($parametro != "" && $valor != "") {
            if ($parametro == 'nombre')
                $builder->where('noticias.titulo LIKE "%' . $valor . '%"');
            else
                $builder->where($parametro, 'like', '%' . $valor . '%');
        }


        if ($idestado > 0)
            $builder->where('noticias.idestado', $idestado);

        if ($idnoticiacategoria > 0)
            $builder->where('noticias.idnoticiacategoria', $idnoticiacategoria);

        $query = $builder->countAllResults();

        return $query;
    }
}
