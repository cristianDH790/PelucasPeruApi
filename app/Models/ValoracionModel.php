<?php

namespace App\Models;

use CodeIgniter\Model;

class ValoracionModel extends Model
{
    protected $table = 'valoracion';
    protected $primaryKey = 'idvaloracion';
    protected $allowedFields = [
        'idestado',
        'idrvaloracion',
        'idclase',
        'idreferencia',
        'idusuario',
        'valor',
        'fecha'
    ];
    function obtenerById($idvaloracion)
    {
        $valoracion = new ValoracionModel();
        return $valoracion->find($idvaloracion);
    }

    function eliminar($idvaloracion)
    {
        $builder = new ValoracionModel();
        $builder->delete(['idvaloracion' => $idvaloracion]);
    }

    function guardar($data)
    {
        $builder = new ValoracionModel();
        if (!isset($data['idvaloracion'])) {
            $builder->save($data);
            return $builder->insertID();
        } else {
            $builder->update($data['idvaloracion'], $data);
            return $data['idvaloracion'];
        }
    }


    public function buscarPor($ordencriterio, $ordentipo, $parametro, $valor, $idestado, $idclase, $idusuario, $idrvaloracion, $idreferencia, $inicio, $registros)
    {
        $builder = $this->select('valoracion.*, (select e.nombre from estado e where valoracion.idestado=e.idestado) as estado');

        if (!empty($ordencriterio) && !empty($ordentipo)) {
            $builder->orderBy($ordencriterio, $ordentipo);
        }

        if (!empty($parametro) && !empty($valor)) {
            $builder->like($parametro, $valor);
        }

        if (!empty($idestado)) {
            $builder->where('valoracion.idestado', $idestado);
        }
        if (!empty($idclase)) {
            $builder->where('valoracion.idclase', $idclase);
        }
        if (!empty($idusuario)) {
            $builder->where('valoracion.idusuario', $idusuario);
        }
        if (!empty($idrvaloracion)) {
            $builder->where('valoracion.idrvaloracion', $idrvaloracion);
        }
        if (!empty($idreferencia)) {
            $builder->where('valoracion.idreferencia', $idreferencia);
        }

        if ($inicio >= 0 && $registros > 0) {
            $builder->limit($registros, $inicio);
        }

        return $builder->get()->getResult();
    }
}
