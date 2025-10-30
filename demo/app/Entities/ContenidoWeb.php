<?php

namespace App\Entities;
use App\Models\ContenidoWebModel;
use CodeIgniter\Entity\Entity;

class ContenidoWeb extends Entity
{


    protected $attributes = [
        'idcontenidoweb'      => null,
        'idestado'      => null,
        'idcontenidowebcategoria'      => null,
        'idptipo'      => null,
        'nombre'      => null,
        'urlamigable'      => null,
        'resumen'      => null,
        'contenido'      => null,
        'urlimagen'      => null,
        'urlbanner'      => null,
        'orden'      => null,
        'fecha'      => null,
        'tituloseo'      => null,
        'descripcionseo'      => null,
        'palabrasclaveseo'      => null,
    ];

    protected $datamap = [
        'idContenidoWeb'      => "idcontenidoweb",
        'idEstado'      => "idestado",
        'idContenidoWebCategoria'      => "idcontenidowebcategoria",
        'idPtipo'      => "idptipo",
        'nombre'      => "nombre",
        'urlAmigable'      => "urlamigable",
        'resumen'      => "resumen",
        'contenido'      => "contenido",
        'urlImagen'      => "urlimagen",
        'urlBanner'      => "urlbanner",
        'orden'      => "orden",
        'fecha'      => "fecha",
        'tituloSeo'      => "tituloseo",
        'descripcionSeo'      => "descripcionseo",
        'palabrasClaveSeo'      => "palabrasclaveseo",
    ];


    public static function obtenerById($idcontenidoweb)
    {
        $contenidoWeb = new ContenidoWebModel();
        return $contenidoWeb->find($idcontenidoweb);
    }

    public static function obtenerContenidoByUrl($url, $idestado)
    {
        $contenidoweb = new ContenidoWebModel();
        return $contenidoweb->where("contenidoweb.urlamigable", $url)
            ->where("idptipo", 417)
            ->where("idestado", $idestado)
            ->select("contenidoweb.*")->first();
    }



    public static function buscarPor($ordencriterio, $ordentipo, $parametro, $valor, $idestado, $idcontenidowebcategoria, $idptipo, $inicio, $registros)
    {

        $builder = new ContenidoWebModel();
        $builder->select('contenidoweb.*, (select e.nombre from estado e where contenidoweb.idestado=e.idestado) as estado,
        (select cc.nombre from contenidowebcategoria cc where contenidoweb.idcontenidowebcategoria=cc.idcontenidowebcategoria) as categoria');
        if ($ordencriterio != "" && $ordentipo != "") {
            $builder->orderBy($ordencriterio, $ordentipo);
        }

        if ($parametro != "" && $valor != "") {
            $builder->like($parametro, $valor);
        }

        if ($idestado > 0) {
            $builder->where('contenidoweb.idestado', $idestado);
        }
        if ($idcontenidowebcategoria > 0) {
            $builder->where('contenidoweb.idcontenidowebcategoria', $idcontenidowebcategoria);
        }
        if ($idptipo > 0) {
            $builder->where('contenidoweb.idptipo', $idptipo);
        }


        if ($inicio >= 0 && $registros > 0) {
            $builder->limit($registros, $inicio);
        }

        if ($inicio >= 0 && $registros > 0) {
            $query = $builder->findAll($registros, $inicio);
        } else {
            $query = $builder->findAll();
        }
        return $query;
    }

    function buscarPorServicios($ordencriterio, $ordentipo, $parametro, $valor, $idestado, $idcontenidowebcategoria, $idptipo, $inicio, $registros)
    {

        $builder = new ContenidoWebModel();
        $builder->select('contenidoweb.*, (select e.nombre from estado e where contenidoweb.idestado=e.idestado) as estado,
        (select cc.nombre from contenidowebcategoria cc where contenidoweb.idcontenidowebcategoria=cc.idcontenidowebcategoria) as categoria');
        $builder->join("contenidowebcategoria cat", "cat.idcontenidowebcategoria =contenidoweb.idcontenidowebcategoria");
        $builder->where('cat.idrcontenidowebcategoria', 2);
        if ($ordencriterio != "" && $ordentipo != "") {
            $builder->orderBy($ordencriterio, $ordentipo);
        }

        if ($parametro != "" && $valor != "") {
            $builder->like($parametro, $valor);
        }

        if ($idestado > 0) {
            $builder->where('contenidoweb.idestado', $idestado);
        }
        if ($idcontenidowebcategoria > 0) {
            $builder->where('contenidoweb.idcontenidowebcategoria', $idcontenidowebcategoria);
        }
        if ($idptipo > 0) {
            $builder->where('contenidoweb.idptipo', $idptipo);
        }


        if ($inicio >= 0 && $registros > 0) {
            $builder->limit($registros, $inicio);
        }

        if ($inicio >= 0 && $registros > 0) {
            $query = $builder->findAll($registros, $inicio);
        } else {
            $query = $builder->findAll();
        }
        return $query;
    }
}
