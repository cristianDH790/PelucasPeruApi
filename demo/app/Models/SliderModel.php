<?php

namespace App\Models;

use CodeIgniter\Model;

class SliderModel extends Model
{
    
    protected $table      = 'sliders';
    protected $primaryKey = 'idslider';

    protected $useAutoIncrement = true;

    protected $returnType     = \App\Entities\Slider::class;
    protected $useSoftDeletes = false;

    protected $allowedFields = ['idestado','idpcategoria','nombre','urlrecurso','descripcion','urlimagenescritorio','urlimagenmovil','orden','fecha'];

    protected $useTimestamps = false;
    protected $createdField  = 'fecha';
    

    protected $validationRules    = [];
    protected $validationMessages = [];
    protected $skipValidation     = false;


    

    

}
