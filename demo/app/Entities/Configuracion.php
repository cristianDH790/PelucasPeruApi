<?php

namespace App\Entities;

use App\Models\CModel;
use App\Models\ConfiguracionModel;
use CodeIgniter\Entity\Entity;

class Configuracion extends Entity
{
    

    protected $attributes = [
        
        'idConfiguracion'      => null,
        'idPtipo'      => null,
        'nombre'      => null,
        'urlAmigable'      => null,
        'descripcion'      => null,
        'urlImagen'      => null,
        'fecha'      => null,
        
        
    ];

    protected $datamap = [
        'idConfiguracion'      => "idconfiguracion",
        'idPtipo'      => "idptipo",
        'nombre'      => "nombre",
        'urlAmigable'      => "urlamigable",
        'descripcion'      => "descripcion",
        'urlImagen'      => "urlimagen",
        'orden'      => "orden",
        'fecha'      => "fecha",
        
         
    ];

    function obtenerById( $idconfiguracion ) {
        
        $configuracion=new ConfiguracionModel();
        return $configuracion->find($idconfiguracion);
    }

    function buscarPor( $ordencriterio,$ordentipo,$parametro, $valor, $idptipo, $inicio, $registros ) {
       
        $builder=new ConfiguracionModel();
        $builder->select('configuracion.*, 
                        (select p.nombre from parametro p where configuracion.idptipo =p.idparametro) as tipo');
        if($ordencriterio!="" && $ordentipo!=""){
            $builder->orderBy($ordencriterio, $ordentipo);
        }

        if($parametro!="" && $valor!=""){
            $builder->like($parametro, $valor);
        }

        if($idptipo>0){
            $builder->where('configuracion.idptipo', $idptipo);
        }
    
        if($inicio >= 0 && $registros > 0){
            $builder->limit($registros, $inicio);
        }

        if($inicio >= 0 && $registros > 0){
            $query=$builder->findAll($registros, $inicio);
        }else{
            $query=$builder->findAll();
        }
                return $query;
    }

    

}