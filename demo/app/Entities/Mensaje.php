<?php

namespace App\Entities;

use App\Models\CModel;
use App\Models\MensajeModel;
use App\Models\LaboralModel;
use App\Models\UsuarioModel;
use CodeIgniter\Entity\Entity;

class Mensaje extends Entity
{


    protected $attributes = [
        'idmensaje'  => null,
        'idestado'  => null,
        'idclase'  => null,
        'nombre'  => null,
        'asunto'  => null,
        'contenido'  => null,
        'observacion'  => null,
        'fecha'  => null,
        

    ];

    protected $datamap = [
        'idMensaje' => "idmensaje",
        'idEstado' => "idestado",
        'idClase' => "idclase",
        'nombre' => "nombre",
        'asunto' => "asunto",
        'contenido' => "contenido",
        'observacion' => "observacion",
        'fecha' => "fecha",
     

    ];

    function obtenerById($idmensaje)
    {
        $mensaje = new MensajeModel();
        return $mensaje->find($idmensaje);
    }

    function guardar($data){
        $builder = new MensajeModel();
        if(!isset($data['idmensaje'])){
            $builder->save($data);
            return $builder->insertID();
        }else{
            $builder->update($data['idmensaje'],$data);
            return $data['idmensaje'];
        }
    }

    function eliminar($idmensaje){
        $builder = new MensajeModel();
        $builder->delete(['idmensaje'=>$idmensaje]);
    }

    function buscarPor($ordencriterio, $ordentipo, $parametro, $valor,$idestado, $idclase, $inicio, $registros)
    {

        $builder = new MensajeModel();
        $builder->select('mensaje.*,(select e.nombre from estado e where mensaje.idestado=e.idestado) as estado');
        if ($ordencriterio != "" && $ordentipo != "") {
            $builder->orderBy($ordencriterio, $ordentipo);
        }

        if ($parametro != "" && $valor != "") {
            $builder->like($parametro, $valor);
        }
        if ($idestado > 0) {
            $builder->where('mensaje.idestado', $idestado);
        }

        if ($idclase > 0) {
            $builder->where('mensaje.idclase', $idclase);
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

    function buscarTotalPor($parametro, $valor,$idestado, $idclase)
    {

        $builder = new MensajeModel();
        $builder->select('mensaje.*');
       
        if ($parametro != "" && $valor != "") {
            $builder->like($parametro, $valor);
        }

        if ($idestado > 0) {
            $builder->where('mensaje.idestado', $idestado);
        }

        if ($idclase > 0) {
            $builder->where('mensaje.idclase', $idclase);
        }
        $query=$builder->countAllResults();
       
        return $query;


    }
}
