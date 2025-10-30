<?php

namespace App\Entities;

use CodeIgniter\Entity\Entity;

class MensajeEntity
{

    // idmensaje 	idestado 	idclase 	nombre 	asunto 	contenido 	variables 	fecha
    public $idmensaje;
    public $idestado;
    public $idclase;
    public $nombre;
    public $asunto;
    public $contenido;
    public $variables;
    public $fecha;

    // Relaciones
    public $estado;
    public $clase;


    public function __construct($data = null)
    {

        if ($data) {
            if (is_array($data)) {
                $this->idmensaje = $data['idmensaje'] ?? null;
                $this->idestado = $data['idestado'] ?? null;
                $this->idclase = $data['idclase'] ?? null;
                $this->nombre = $data['nombre'] ?? null;
                $this->asunto = $data['asunto'] ?? null;
                $this->contenido = $data['contenido'] ?? null;
                $this->variables = $data['variables'] ?? null;
                $this->fecha = $data['fecha'] ?? null;
            } elseif (is_object($data)) {
                $this->idmensaje = $data->idmensaje ?? null;
                $this->idestado = $data->idestado ?? null;
                $this->idclase = $data->idclase ?? null;
                $this->nombre = $data->nombre ?? null;
                $this->asunto = $data->asunto ?? null;
                $this->contenido = $data->contenido ?? null;
                $this->variables = $data->variables ?? null;
                $this->fecha = $data->fecha ?? null;
            }
        }
    }

    public function toArray()
    {
        $data = [
            'idMensaje' => (int) $this->idmensaje,
            'idEstado' => (int) $this->idestado,
            'idClase' => (int) $this->idclase,
            'nombre' => $this->nombre,
            'asunto' => $this->asunto,
            'contenido' => $this->contenido,
            'variables' => $this->variables,
            'fecha' => $this->fecha
        ];

        if ($this->clase !== null) {
            $data['clase'] = $this->clase->toArray();
        }
        if ($this->estado !== null) {
            $data['estado'] = $this->estado->toArray();
        }

        return $data;
    }
}
