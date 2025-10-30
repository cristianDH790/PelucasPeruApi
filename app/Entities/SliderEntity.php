<?php

namespace App\Entities;

use CodeIgniter\Entity\Entity;

class SliderEntity
{

    public $idslider;
    public $idestado;
    public $nombre;
    public $descripcion;
    public $urlimagen1;
    public $urlimagen2;
    public $idptiporecurso;
    public $idproductocategoria;
    public $urlrecurso;
    public $orden;
    public $fecha;
    public $urlarchivo1;
    public $urlarchivo2;

    public $estado;
    public $productocategoria;
    public $precurso;




    public function __construct($data = null)
    {
        if ($data) {
            if (is_array($data)) {
                $this->idslider = $data['idslider'] ?? null;
                $this->idestado = $data['idestado'] ?? null;
                $this->nombre = $data['nombre'] ?? null;
                $this->descripcion = $data['descripcion'] ?? null;
                $this->idptiporecurso = $data['idptiporecurso'] ?? null;
                $this->idproductocategoria = $data['idproductocategoria'] ?? null;
                $this->urlarchivo1 = $data['urlarchivo1'] ?? null;
                $this->urlarchivo2 = $data['urlarchivo2'] ?? null;
                $this->urlimagen1 = $data['urlimagen1'] ?? null;
                $this->urlimagen2 = $data['urlimagen2'] ?? null;
                $this->urlrecurso = $data['urlrecurso'] ?? null;
                $this->orden = $data['orden'] ?? null;
                $this->fecha = $data['fecha'] ?? null;
            } elseif (is_object($data)) {
                $this->idslider = $data->idslider ?? null;
                $this->idestado = $data->idestado ?? null;
                $this->nombre = $data->nombre ?? null;
                $this->descripcion = $data->descripcion ?? null;
                $this->idproductocategoria = $data->idproductocategoria ?? null;
                $this->idptiporecurso = $data->idptiporecurso ?? null;
                $this->urlarchivo1 = $data->urlarchivo1 ?? null;
                $this->urlarchivo2 = $data->urlarchivo2 ?? null;
                $this->urlimagen1 = $data->urlimagen1 ?? null;
                $this->urlimagen2 = $data->urlimagen2 ?? null;
                $this->urlrecurso = $data->urlrecurso ?? null;
                $this->orden = $data->orden ?? null;
                $this->fecha = $data->fecha ?? null;
            }
        }
    }



    public function toArray(): array
    {
        $data = [
            'idSlider'  => (int)$this->idslider,
            'idEstado' => (int)$this->idestado,
            'nombre' => $this->nombre,
            'descripcion' => $this->descripcion,
            'urlArchivo1' => $this->urlarchivo1,
            'urlArchivo2' => $this->urlarchivo2,
            'urlImagen1' => $this->urlimagen1,
            'urlImagen2' => $this->urlimagen2,
            'idpRpecurso' => $this->idptiporecurso,
            'idProductoCategoria' => $this->idproductocategoria,
            'urlRecurso' => $this->urlrecurso,
            'orden' => $this->orden,
            'fecha' => $this->fecha,

        ];

        // Relaciones (solo si están cargadas)
        if ($this->estado !== null) {
            $data['estado'] = $this->estado->toArray();
        }
        if ($this->productocategoria !== null) {
            $data['rProductoCategoria'] = $this->productocategoria->toArray();
        }
        if ($this->precurso !== null) {
            $data['pRecurso'] = $this->precurso->toArray();
        }

        return $data;
    }
}
