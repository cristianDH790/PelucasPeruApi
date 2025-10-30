<?php

namespace App\Models;

use CodeIgniter\Model;

class ComentarioModel extends Model
{
    protected $table = 'comentario';
    protected $primaryKey = 'idcomentario';

    protected $allowedFields = [
        'idestado',
        'idrcomentario',
        'idclase',
        'idreferencia',
        'idusuario',
        'contenido',
        'fecha'
    ];

    function obtenerById($idcomentario)
    {
        $comentario = new ComentarioModel();
        return $comentario->find($idcomentario);
    }


    function eliminar($idcomentario)
    {
        $builder = new ComentarioModel();
        $builder->delete(['idcomentario' => $idcomentario]);
    }

    function getTotalComentarios($idclase, $idreferencia)
    {

        $builder = new ComentarioModel();
        $builder->select('comentario.id');
        $builder->where('comentario.idclase', $idclase);

        $builder->where('comentario.idreferencia', $idreferencia);
    }

    // function buscarPor( $ordencriterio,$ordentipo,$parametro, $valor, $idestado, $idusuario,$idclase,$idrcomentario,$idreferencia, $inicio, $registros ) {

    //     $builder=new ComentarioModel();
    //     $builder->select('comentario.*, (select e.nombre from estado e where comentario.idestado=e.idestado) as estado,
    //     (select u.nombres from usuario u where comentario.idusuario=u.idusuario) as miembronombre,
    //     (select u.papellido from usuario u where comentario.idusuario=u.idusuario) as miembropapellido,
    //     (select u.fecha from usuario u where comentario.idusuario=u.idusuario) as miembrofecha');
    //     if($ordencriterio!="" && $ordentipo!=""){
    //         $builder->orderBy($ordencriterio, $ordentipo);
    //     }

    //     if($parametro!="" && $valor!=""){
    //         $builder->like($parametro, $valor);
    //     }

    //     if($idestado>0){
    //         $builder->where('comentario.idestado', $idestado);
    //     }

    //     if($idusuario>0){
    //         $builder->where('comentario.idusuario', $idusuario);
    //     }

    //     if($idclase>0){
    //         $builder->where('comentario.idclase', $idclase);
    //     } 
    //     if($idrcomentario!=0){
    //         $builder->where('comentario.idrcomentario', $idrcomentario);
    //     }
    //     if($idreferencia>0){
    //         $builder->where('comentario.idreferencia', $idreferencia);
    //     }


    //     if($inicio >= 0 && $registros > 0){
    //         $builder->limit($registros, $inicio);
    //     }

    //     if($inicio >= 0 && $registros > 0){
    //         $query=$builder->findAll($registros, $inicio);
    //     }else{
    //         $query=$builder->findAll();
    //     }
    //     return $query;



    // }
    function buscarPor($ordencriterio, $ordentipo, $parametro, $valor, $idestado, $idusuario, $idclase, $idrcomentario, $idreferencia, $inicio, $registros)
    {
        $builder = new ComentarioModel();

        // Seleccionamos columnas con alias claros
        $builder->select('
        comentario.*,
        e.idestado AS estado_idestado,
        e.nombre AS estado_nombre,
        e.descripcion AS estado_descripcion,
        u.nombres AS miembronombre,
        u.papellido AS miembropapellido,
        u.fecha AS miembrofecha,
        p.idproducto AS producto_idproducto,
        p.nombre AS producto_nombre,
      
      
   
    ');

        // JOIN con las tablas relacionadas
        $builder->join('estado e', 'comentario.idestado = e.idestado', 'left');
        $builder->join('usuario u', 'comentario.idusuario = u.idusuario', 'left');
        $builder->join('producto p', 'comentario.idreferencia = p.idproducto', 'left'); // 🔗 relación con producto

        // Orden
        if ($ordencriterio != "" && $ordentipo != "") {
            $builder->orderBy($ordencriterio, $ordentipo);
        }

        // Filtros dinámicos
        if ($parametro != "" && $valor != "") {
            $builder->like($parametro, $valor);
        }

        if ($idestado > 0) {
            $builder->where('comentario.idestado', $idestado);
        }

        if ($idusuario > 0) {
            $builder->where('comentario.idusuario', $idusuario);
        }

        if ($idclase > 0) {
            $builder->where('comentario.idclase', $idclase);
        }

        if ($idrcomentario != 0) {
            $builder->where('comentario.idrcomentario', $idrcomentario);
        }

        if ($idreferencia > 0) {
            $builder->where('comentario.idreferencia', $idreferencia);
        }

        // Paginación
        if ($inicio >= 0 && $registros > 0) {
            $builder->limit($registros, $inicio);
        }

        $query = $builder->get()->getResultArray();

        // 🔁 Convertimos el resultado para anidar estado y producto
        foreach ($query as &$item) {
            $item['estado'] = [
                'idestado'     => $item['estado_idestado'],
                'nombre'       => $item['estado_nombre'],
                'descripcion'  => $item['estado_descripcion'],
            ];

            $item['producto'] = [
                'idproducto'   => $item['producto_idproducto'],
                'nombre'       => $item['producto_nombre'],

            ];

            // Eliminamos los alias temporales
            unset(
                $item['estado_idestado'],
                $item['estado_nombre'],
                $item['estado_descripcion'],
                $item['producto_idproducto'],
                $item['producto_nombre'],
                $item['producto_descripcion'],
                $item['producto_precio'],
                $item['producto_imagen']
            );
        }

        return $query;
    }



    function guardar($data)
    {
        $builder = new ComentarioModel();
        if (!isset($data['idcomentario'])) {
            $builder->save($data);
            return $builder->insertID();
        } else {
            $builder->update($data['idcomentario'], $data);
            return $data['idcomentario'];
        }
    }


    function buscarTotalPor($parametro, $valor, $idestado, $idusuario, $idclase, $idrcomentario, $idreferencia)
    {

        $builder = new ComentarioModel();
        $builder->select('comentario.*');


        if ($parametro != "" && $valor != "") {
            $builder->like($parametro, $valor);
        }

        if ($idestado > 0) {
            $builder->where('comentario.idestado', $idestado);
        }

        if ($idusuario > 0) {
            $builder->where('comentario.idusuario', $idusuario);
        }
        if ($idclase > 0) {
            $builder->where('comentario.idclase', $idclase);
        }
        if ($idrcomentario != 0) {
            $builder->where('comentario.idrcomentario', $idrcomentario);
        }
        if ($idreferencia > 0) {
            $builder->where('comentario.idreferencia', $idreferencia);
        }

        $query = $builder->countAllResults();

        return $query;
    }
}
