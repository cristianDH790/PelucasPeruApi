<?php

namespace App\Entities;

use App\Models\NoticiaModel;
use App\Models\NoticiCategoriaModel;
use App\Models\ProyectoModel;
use CodeIgniter\Entity\Entity;

class NoticiaCategoria extends Entity
{

    protected $attributes = [
        'idEstado',
        'nombre',
        'orden',
        'fecha',
    ];

    protected $datamap = [
        'idEstado' => 'idestado',
        'nombre' => 'nombre',
        'orden' => 'orden',
        'fecha' => 'fecha',
    ];

    public static function buscarPor($ordencriterio, $ordentipo, $parametro, $valor, $idestado, $inicio, $registros)
    {

        $builder = new NoticiCategoriaModel();
        $builder->select('noticiacategoria.*,
            (select e.nombre from estados e where noticiacategoria.idestado=e.idestado) as estado');

        if ($ordencriterio != "" && $ordentipo != "") {
            $builder->orderBy($ordencriterio, $ordentipo);
        }

        if ($parametro != "" && $valor != "") {
            if ($parametro == 'nombre')
                $builder->where('noticiacategoria.titulo LIKE "%' . $valor . '%"');
            else
                $builder->where($parametro, 'like', '%' . $valor . '%');
        }


        if ($idestado > 0)
            $builder->where('noticiacategoria.idestado', $idestado);

        if ($inicio >= 0 && $registros > 0)
            $builder->limit($registros, $inicio);

        if ($inicio >= 0 && $registros > 0)
            $query = $builder->findAll($registros, $inicio);
        else
            $query = $builder->findAll();

        return $query;
    }

}
