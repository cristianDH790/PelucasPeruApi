<?php

namespace App\Entities;

use App\Models\SliderModel;
use CodeIgniter\Entity\Entity;

class Slider extends Entity
{
    protected $attributes = [
        'idSlider'      => null,
        'idEstado'      => null,
        'idPcategoria'      => null,
        'nombre'      => null,
        'descripcion'      => null,
        'urlRecurso'      => null,
        'urlImagenEscritorio'      => null,
        'urlImagenMovil'      => null,
        'orden'      => null,
        'fecha'      => null,
    ];

    protected $datamap = [
        'idSlider'      => "idslider",
        'idEstado'      => "idestado",
        'idPcategoria'      => "idpcategoria",
        'nombre'      => "nombre",
        'urlRecurso'      => "urlrecurso",
        'descripcion'      => "descripcion",
        'urlImagenEscritorio'      => "urlimagenescritorio",
        'urlImagenMovil'      => "urlimagenmovil",
        'orden'      => "orden",
        'fecha'      => "fecha",
    ];

    public static function obtenerById($idslider)
    {

        $slider = new SliderModel();
        return $slider->find($idslider);
    }

    public static function buscarPor($ordencriterio, $ordentipo, $parametro, $valor, $idestado, $idpcategoria, $inicio, $registros)
    {

        $builder = new SliderModel();
        $builder->select('sliders.*, (select e.nombre from estados e where sliders.idestado=e.idestado) as estado, 
                        (select p.nombre from parametros p where sliders.idpcategoria=p.idparametro) as pcategoria');
        if ($ordencriterio != "" && $ordentipo != "") {
            $builder->orderBy($ordencriterio, $ordentipo);
        }

        if ($parametro != "" && $valor != "") {
            $builder->like($parametro, $valor);
        }

        if ($idestado > 0) {
            $builder->where('sliders.idestado', $idestado);
        }

        if ($idpcategoria > 0) {
            $builder->where('sliders.idpcategoria', $idpcategoria);
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
