<?php

namespace App\Models;

use CodeIgniter\Model;

class ClienteModel extends Model
{
    
    protected $table      = 'clientes';
    protected $primaryKey = 'idcliente';

    protected $useAutoIncrement = true;

    protected $returnType     = \App\Entities\Cliente::class;
    protected $useSoftDeletes = false;

    protected $allowedFields = ['idestado','nombre','descripcion','urlimagen','orden','fecha'];

    protected $useTimestamps = false;
    protected $createdField  = 'fecha';
    

    protected $validationRules    = [];
    protected $validationMessages = [];
    protected $skipValidation     = false;


    

    

}
