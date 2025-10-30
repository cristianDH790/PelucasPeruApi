<?php

namespace App\Entities;

use App\Models\ClienteModel;
use App\Models\ProyectoModel;
use CodeIgniter\Entity\Entity;

class Cliente extends Entity
{

    protected $attributes = [
        'idEstado',
        'nombre',
        'descripcion',
        'urlImagen',
        'orden',
        'fecha',
    ];

    protected $datamap = [
        'idEstado' => 'idestado',
        'nombre' => 'nombre',
        'descripcion' => 'descripcion',
        'urlImagen' => 'urlimagen',
        'orden' => 'orden',
    ];


    function obtenerByUrl($url)
    {
        $proyecto = new ClienteModel();
        return $proyecto->select('clientes.*, (select e.nombre from estados e where clientes.idestado=e.idestado) as estado',)
            ->where("clientes.urlamigable", $url)->first();
    }

    public static function buscarPor($ordencriterio, $ordentipo, $parametro, $valor, $idestado, $inicio, $registros)
    {

        $builder = new ClienteModel();
        $builder->select('clientes.*, (select e.nombre from estados e where clientes.idestado=e.idestado) as estado');

        if ($ordencriterio != "" && $ordentipo != "") {
            if ($ordencriterio == "fecha")
                $builder->orderBy("fecha", $ordentipo);
        }

        if ($parametro != "" && $valor != "") {
            if ($parametro == 'nombre')
                $builder->where('clientes.nombre LIKE "%' . $valor . '%"');
            else
                $builder->where($parametro, 'like', '%' . $valor . '%');
        }


        if ($idestado > 0)
            $builder->where('clientes.idestado', $idestado);

        if ($inicio >= 0 && $registros > 0)
            $builder->limit($registros, $inicio);

        if ($inicio >= 0 && $registros > 0)
            $query = $builder->findAll($registros, $inicio);
        else
            $query = $builder->findAll();

        return $query;
    }

    public static function buscarTotalPor($parametro, $valor, $idestado)
    {

        $builder = new ClienteModel();
        $builder->select('clientes.*, (select e.nombre from estado e where clientes.idestado=e.idestado) as estado');


        if ($parametro != "" && $valor != "") {
            if ($parametro == 'nombre')
                $builder->where('clientes.nombre LIKE "%' . $valor . '%"');
            else
                $builder->where($parametro, 'like', '%' . $valor . '%');
        }


        if ($idestado > 0)
            $builder->where('clientes.idestado', $idestado);

        $query = $builder->countAllResults();

        return $query;
    }
}
