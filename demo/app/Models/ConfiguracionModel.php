<?php

namespace App\Models;

use CodeIgniter\Database\ConnectionInterface;
use CodeIgniter\Model;

class ConfiguracionModel extends Model
{
    
    protected $table      = 'configuraciones';
    protected $primaryKey = 'idconfiguracion';

    protected $useAutoIncrement = true;

    protected $returnType     = \App\Entities\Configuracion::class;
    protected $useSoftDeletes = false;

    protected $allowedFields = ['idptipo','nombre','valor','descripcion','urlimagen','fecha'];

    protected $useTimestamps = false;
    protected $createdField  = 'fecha';
    

    protected $validationRules    = [];
    protected $validationMessages = [];
    protected $skipValidation     = false;


    

    

}
