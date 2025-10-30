<?php

namespace App\Models;

use CodeIgniter\Model;

class ProductoModel extends Model
{

    protected $table      = 'productos';
    protected $primaryKey = 'idproducto';

    protected $useAutoIncrement = true;

    protected $returnType     = \App\Entities\Producto::class;
    protected $useSoftDeletes = false;

    protected $allowedFields = ['idestado', 'idpdestacado',  'sku', 'nombre', 'urlamigable', 'resumen', 'contenido', 'urlimagen', 'urlbrochure', 'preciolista', 'precioventa',  'peso', 'orden', 'fechapublicacion', 'fecha'];

    protected $useTimestamps = false;
    protected $createdField  = 'fecha';


    protected $validationRules    = [];
    protected $validationMessages = [];
    protected $skipValidation     = false;
}
