<?php

namespace App\Models;

use CodeIgniter\Database\ConnectionInterface;
use CodeIgniter\Model;

class MensajeModel extends Model
{
    
    protected $table      = 'mensaje';
    protected $primaryKey = 'idmensaje';

    protected $useAutoIncrement = true;

    protected $returnType     = \App\Entities\Mensaje::class;
    protected $useSoftDeletes = false;

    protected $allowedFields = ['idestado','idclase','nombre','asunto','contenido','observacion','fecha'];

    protected $useTimestamps = false;
    protected $createdField  = 'fecha';
    

    protected $validationRules    = [];
    protected $validationMessages = [];
    protected $skipValidation     = false;


    

    

}
