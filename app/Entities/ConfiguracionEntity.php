<?php

namespace App\Entities;

use CodeIgniter\Entity\Entity;

class ConfiguracionEntity
{
    // Propiedades principales (campos directos de la tabla)
    public $idconfiguracion;
    public $idptipo;
    public $nombre;
    public $urlimagen;
    public $idprecurso;
    public $valor;
    public $descripcion;
    public $fecha;


    // Relaciones (deben cargarse externamente)
    public $ptipo;
    public $precurso;

    public function __construct($data = null)
    {
        if ($data) {
            if (is_array($data)) {
                $this->idconfiguracion = $data['idconfiguracion'] ?? null;
                $this->idptipo = $data['idptipo'] ?? null;
                $this->nombre = $data['nombre'] ?? null;
                $this->valor = $data['valor'] ?? null;
                $this->idprecurso = $data['idprecurso'] ?? null;
                $this->urlimagen = $data['urlimagen'] ?? null;
                $this->descripcion = $data['descripcion'] ?? null;
                $this->fecha = $data['fecha'] ?? null;
            } elseif (is_object($data)) {
                $this->idconfiguracion = $data->idconfiguracion ?? null;
                $this->idptipo = $data->idptipo ?? null;
                $this->nombre = $data->nombre ?? null;
                $this->idprecurso = $data->idprecurso ?? null;
                $this->urlimagen = $data->urlimagen ?? null;
                $this->valor = $data->valor ?? null;
                $this->descripcion = $data->descripcion ?? null;
                $this->fecha = $data->fecha ?? null;
            }
        }
    }


    public function toArray(): array
    {
        // Datos base del asociado
        $data = [
            'idConfiguracion' => $this->idconfiguracion,
            'idpTipo' => $this->idptipo,
            'nombre' => $this->nombre,
            'urlImagen' => $this->urlimagen,
            'idpRecurso' => $this->idprecurso,
            'valor' => $this->valor,
            'descripcion' => $this->descripcion,
            'fecha' => $this->fecha,
        ];

        // Relaciones (solo si están cargadas)
        if ($this->ptipo !== null) {
            $data['pTipo'] = $this->ptipo->toArray();
        }
        if ($this->precurso !== null) {
            $data['pRecurso'] = $this->precurso->toArray();
        }
        return $data;
    }
}
