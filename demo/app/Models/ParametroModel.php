<?php

namespace App\Models;

use CodeIgniter\Database\ConnectionInterface;
use CodeIgniter\Model;

class ParametroModel extends Model
{
    
    protected $table      = 'parametros';
    protected $primaryKey = 'idparametro';

    protected $useAutoIncrement = true;

    protected $returnType     = \App\Entities\Parametro::class;
    protected $useSoftDeletes = false;

    protected $allowedFields = ['idestado','idtipo','nombre','orden','fecha'];

    protected $useTimestamps = false;
    protected $createdField  = 'fecha';
    

    protected $validationRules    = [];
    protected $validationMessages = [];
    protected $skipValidation     = false;


    

    

}
