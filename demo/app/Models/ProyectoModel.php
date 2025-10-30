<?php

namespace App\Models;

use CodeIgniter\Model;

class ProyectoModel extends Model
{

    protected $table      = 'proyectos';
    protected $primaryKey = 'idproyecto';

    protected $useAutoIncrement = true;

    protected $returnType     = \App\Entities\Proyecto::class;
    protected $useSoftDeletes = false;

    protected $allowedFields = ['idestado', 'idpcategoria',  'idcliente', 'usuario', 'nombre', 'resumen', 'descripcion', 'urlimagen', 'urlamigable', 'orden', 'palabrasclaveseo', 'tituloseo',  'descripcionseo', 'fechapublicacion', 'fecha'];

    protected $useTimestamps = false;
    protected $createdField  = 'fecha';


    protected $validationRules    = [];
    protected $validationMessages = [];
    protected $skipValidation     = false;
}
