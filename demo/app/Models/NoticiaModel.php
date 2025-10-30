<?php

namespace App\Models;

use CodeIgniter\Model;

class NoticiaModel extends Model
{

    protected $table      = 'noticias';
    protected $primaryKey = 'idnoticia';

    protected $useAutoIncrement = true;

    protected $returnType     = \App\Entities\Noticia::class;
    protected $useSoftDeletes = false;

    protected $allowedFields = [
        'idestado',
        'titulo',
        'reusmen',
        'contenido',
        'urlimagen',
        'urlamigable',
        'orden',
        'tituloseo',
        'descripcionseo',
        'palabrasclaveseo',
        'fechapublicacion',
        'fecha',
    ];

    protected $useTimestamps = false;
    protected $createdField  = 'fecha';


    protected $validationRules    = [];
    protected $validationMessages = [];
    protected $skipValidation     = false;
}
