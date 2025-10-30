<?php

namespace App\Models;

use CodeIgniter\Model;

class ContenidoWebModel extends Model
{

    protected $table      = 'contenidoweb';
    protected $primaryKey = 'idcontenidoweb';

    protected $useAutoIncrement = true;

    protected $returnType     = \App\Entities\ContenidoWeb::class;
    protected $useSoftDeletes = false;

    protected $allowedFields = [
        'idestado',
        'idcontenidowebcategoria',
        'idptipo',
        'nombre',
        'urlamigable',
        'resumen',
        'contenido',
        'urlimagen',
        'urlbanner',
        'orden',
        'tituloseo',
        'descripcionseo',
        'palabrasclaveseo',
        'fecha'
    ];

    protected $useTimestamps = false;
    protected $createdField  = 'fecha';


    protected $validationRules    = [];
    protected $validationMessages = [];
    protected $skipValidation     = false;
}
