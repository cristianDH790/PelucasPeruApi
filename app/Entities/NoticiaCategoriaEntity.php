<?php

namespace App\Entities;

use CodeIgniter\Entity\Entity;

class NoticiaCategoriaEntity
{
    // idmensaje 	idestado 	idclase 	nombre 	asunto 	contenido 	variables 	fecha
    public $idnoticiacategoria;
    public $idestado;
    public $idrnoticiacategoria;
    public $nombre;
    public $urlamigable;
    public $descripcionseo;
    public $fecha;
    public $orden;

    // Relaciones
    public $estado;
    public $rnoticiacategoria;

    public function __construct($data = null)
    {
        if ($data) {
            if (is_array($data)) {
                $this->idnoticiacategoria = $data['idnoticiacategoria'] ?? null;
                $this->idestado = $data['idestado'] ?? null;
                $this->idrnoticiacategoria = $data['idnoticiacategoria'] ?? null;
                $this->nombre = $data['nombre'] ?? null;
                $this->orden = $data['orden'] ?? null;
                $this->urlamigable = $data['urlamigable'] ?? null;
                $this->descripcionseo = $data['descripcionseo'] ?? null;
                $this->fecha = $data['fecha'] ?? null;
            } elseif (is_object($data)) {
                $this->idnoticiacategoria = $data->idnoticiacategoria ?? null;
                $this->idestado = $data->idestado ?? null;
                $this->idrnoticiacategoria = $data->idnoticiacategoria ?? null;
                $this->nombre = $data->nombre ?? null;
                $this->orden = $data->orden ?? null;
                $this->urlamigable = $data->urlamigable ?? null;
                $this->descripcionseo = $data->descripcionseo ?? null;
                $this->fecha = $data->fecha ?? null;
            }
        }
    }

    public function toArray()
    {
        $data = [
            'idNoticiaCategoria' => (int)$this->idnoticiacategoria,
            'idEstado' => (int) $this->idestado,
            'idrNoticiCategoria' =>  (int)$this->idrnoticiacategoria,
            'nombre' => $this->nombre,
            'orden' => $this->orden,
            'urlAmigable' => $this->urlamigable,
            'descripcionSeo' => $this->descripcionseo,
            'fecha' => $this->fecha
        ];

        if ($this->rnoticiacategoria !== null) {
            $data['rNoticiaCategoria'] = $this->rnoticiacategoria->toArray();
        }
        if ($this->estado !== null) {
            $data['estado'] = $this->estado->toArray();
        }


        return $data;
    }
}


//nuevo version 

// namespace App\Entities;

// use CodeIgniter\Entity\Entity;

// class NoticiaCategoriaEntity extends Entity
// {
//     protected $attributes = [
//         'idnoticiacategoria' => null,
//         'idestado' => null,
//         'idrnoticiacategoria' => null,
//         'nombres' => null,
//         'urlamigable' => null,
//         'descripcionseo' => null,
//         'fecha' => null,
//     ];

//     protected $datamap = [
//         'idNoticiaCategoria' => 'idnoticiacategoria',
//         'idEstado' => 'idestado',
//         'idrNoticiaCategoria' => 'idrnoticiacategoria',
//         'nombre' => 'nombres',
//         'urlAmigable' => 'urlamigable',
//         'descripcionSeo' => 'descripcionseo',
//     ];

//     protected $casts   = [
//         'idnoticiacategoria' => 'integer',
//         'idestado' => 'integer',
//         'idrnoticiacategoria' => '?integer',
//         'fecha' => 'datetime',
//     ];

//     // Relaciones manuales (estado y rnoticiacategoria)
//     public $estado;
//     public $rnoticiacategoria;

//     public function toArray(bool $onlyChanged = true, bool $recursive = false): array
//     {
//         $data = parent::toArray($onlyChanged, $recursive);

//         if ($this->rnoticiacategoria !== null && method_exists($this->rnoticiacategoria, 'toArray')) {
//             $data['rNoticiaCategoria'] = $this->rnoticiacategoria->toArray();
//         }

//         if ($this->estado !== null && method_exists($this->estado, 'toArray')) {
//             $data['estado'] = $this->estado->toArray();
//         }

//         return $data;
//     }
// }