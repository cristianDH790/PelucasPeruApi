<?php

namespace App\Entities;

use CodeIgniter\Entity\Entity;

class EntregaEntity
{
    public $identrega;
    public $idestado;

    public $nombre;
    public $dias;
    public $diashabiles;
    public $importeminimo;
    public $minimogratis;
    public $costoenvio;
    public $horareferencia;
    public $pesoxcostoenvio;
    public $orden;
    public $fecha;

    // Relaciones
    public $estado;


    public function __construct($data = null)
    {
        if ($data) {
            if (is_array($data)) {
                $this->identrega = $data['identrega'] ?? null;
                $this->idestado = $data['idestado'] ?? null;
              
                $this->nombre = $data['nombre'] ?? null;
                $this->dias = $data['dias'] ?? null;
                $this->diashabiles = $data['diashabiles'] ?? null;
                $this->importeminimo = $data['importeminimo'] ?? null;
                $this->minimogratis = $data['minimogratis'] ?? null;
                $this->costoenvio = $data['costoenvio'] ?? null;
                $this->horareferencia = $data['horareferencia'] ?? null;
                $this->pesoxcostoenvio = $data['pesoxcostoenvio'] ?? null;
                $this->orden = $data['orden'] ?? null;
                $this->fecha = $data['fecha'] ?? null;
            } elseif (is_object($data)) {
                $this->identrega = $data->identrega ?? null;
                $this->idestado = $data->idestado ?? null;
               
                $this->nombre = $data->nombre ?? null;
                $this->dias = $data->dias ?? null;
                $this->diashabiles = $data->diashabiles ?? null;
                $this->importeminimo = $data->importeminimo ?? null;
                $this->minimogratis = $data->minimogratis ?? null;
                $this->costoenvio = $data->costoenvio ?? null;
                $this->horareferencia = $data->horareferencia ?? null;
                $this->pesoxcostoenvio = $data->pesoxcostoenvio ?? null;
                $this->orden = $data->orden ?? null;
                $this->fecha = $data->fecha ?? null;
            }
        }
    }

    public function toArray()
    {
        $data = [
            'idEntrega' => (int) $this->identrega,
            'idEstado' => (int) $this->idestado,
           
            'nombre' => $this->nombre,
            'dias' => $this->dias,
            'diasHabiles' => $this->diashabiles,
            'importeMinimo' => $this->importeminimo,
            'minimoGratis' => $this->minimogratis,
            'costoEnvio' => $this->costoenvio,
            'horaReferencia' => $this->horareferencia,
            'pesoxCostoEnvio' => $this->pesoxcostoenvio,
            'orden' => $this->orden,
            'fecha' => $this->fecha
        ];

        if ($this->estado !== null) {
            $data['estado'] = $this->estado->toArray();
        }
       

        return $data;
    }
}
