<?php

namespace App\Models;

use CodeIgniter\Model;

class NoticiCategoriaModel extends Model
{
    
    protected $table      = 'noticiacategoria';
    protected $primaryKey = 'idnoticiacategoria';

    protected $useAutoIncrement = true;

    protected $returnType     = \App\Entities\NoticiaCategoria::class;
    protected $useSoftDeletes = false;

    protected $allowedFields = ['idestado','nombre','orden','fecha'];

    protected $useTimestamps = false;
    protected $createdField  = 'fecha';
    

    protected $validationRules    = [];
    protected $validationMessages = [];
    protected $skipValidation     = false;


    

    

}
