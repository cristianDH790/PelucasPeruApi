<?php

namespace App\Entities;

use App\Models\CModel;
use App\Models\ParametroModel;
use App\Models\LaboralModel;
use App\Models\UsuarioModel;
use CodeIgniter\Entity\Entity;

class Parametro extends Entity
{


    protected $attributes = [
        'idparametro'  => null,
        'idestado'  => null,
        'idtipo'  => null,
        'nombre'  => null,
        'abr'  => null,
        'descripcion'  => null,
        'orden'  => null,
        'fecha'  => null,
        

    ];

    protected $datamap = [
        'idParametro' => "idparametro",
        'idEstado' => "idestado",
        'idTipo' => "idtipo",
        'nombre' => "nombre",
        'abr' => "abr",
        'descripcion' => "descripcion",
        'orden' => "orden",
        'fecha' => "fecha",
     

    ];

    function obtenerById($idparametro)
    {
        $parametro = new ParametroModel();
        return $parametro->find($idparametro);
    }

    static function buscarPor($ordencriterio, $ordentipo, $parametro, $valor, $idestado, $idtipo, $inicio, $registros)
    {

        $builder = new ParametroModel();
        $builder->select('parametros.*');
        if ($ordencriterio != "" && $ordentipo != "") {
            $builder->orderBy($ordencriterio, $ordentipo);
        }

        if ($parametro != "" && $valor != "") {
            $builder->like($parametro, $valor);
        }

        if ($idestado > 0) {
            $builder->where('parametros.idestado', $idestado);
        }

        if ($idtipo > 0) {
            $builder->where('parametros.idtipo', $idtipo);
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
