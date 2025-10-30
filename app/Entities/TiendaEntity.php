<?php

namespace App\Entities;

class TiendaEntity
{
    public $idtienda;
    public $idestado;
    public $idubigeo;
    public $nombre;
    public $telefono;
    public $direccion;
    public $horario1;
    public $horario2;
    public $horario3;
    public $delivery;
    public $horainicio;
    public $horatermino;
    public $latitud;
    public $longitud;
    public $urlimagen;
    public $ventaxmayor;
    public $orden;
    public $fecha;

    // Relaciones (puedes completar según tu modelo)
    public $estado;
    public $ubigeo;

    public function __construct($data = null)
    {
        if (!$data) return;

        if (is_array($data)) {
            $this->idtienda     = $data['idtienda'] ?? null;
            $this->idestado     = $data['idestado'] ?? null;
            $this->idubigeo     = $data['idubigeo'] ?? null;
            $this->nombre       = $data['nombre'] ?? null;
            $this->telefono     = $data['telefono'] ?? null;
            $this->direccion    = $data['direccion'] ?? null;
            $this->horario1     = $data['horario1'] ?? null;
            $this->horario2     = $data['horario2'] ?? null;
            $this->horario3     = $data['horario3'] ?? null;
            $this->delivery     = $data['delivery'] ?? null;
            $this->horainicio   = $data['horainicio'] ?? null;
            $this->horatermino  = $data['horatermino'] ?? null;
            $this->latitud      = $data['latitud'] ?? null;
            $this->longitud     = $data['longitud'] ?? null;
            $this->urlimagen    = $data['urlimagen'] ?? null;
            $this->ventaxmayor  = $data['ventaxmayor'] ?? null;
            $this->orden        = $data['orden'] ?? null;
            $this->fecha        = $data['fecha'] ?? null;
        } elseif (is_object($data)) {
            $this->idtienda     = $data->idtienda ?? null;
            $this->idestado     = $data->idestado ?? null;
            $this->idubigeo     = $data->idubigeo ?? null;
            $this->nombre       = $data->nombre ?? null;
            $this->telefono     = $data->telefono ?? null;
            $this->direccion    = $data->direccion ?? null;
            $this->horario1     = $data->horario1 ?? null;
            $this->horario2     = $data->horario2 ?? null;
            $this->horario3     = $data->horario3 ?? null;
            $this->delivery     = $data->delivery ?? null;
            $this->horainicio   = $data->horainicio ?? null;
            $this->horatermino  = $data->horatermino ?? null;
            $this->latitud      = $data->latitud ?? null;
            $this->longitud     = $data->longitud ?? null;
            $this->urlimagen    = $data->urlimagen ?? null;
            $this->ventaxmayor  = $data->ventaxmayor ?? null;
            $this->orden        = $data->orden ?? null;
            $this->fecha        = $data->fecha ?? null;
        }
    }

    public function toArray()
    {
        $data = [
            'idtienda'    => (int) $this->idtienda,
            'idEstado'    => (int) $this->idestado,
            'idUbigeo'    => (int) $this->idubigeo,
            'nombre'      => $this->nombre,
            'telefono'    => $this->telefono,
            'direccion'   => $this->direccion,
            'horario1'    => $this->horario1,
            'horario2'    => $this->horario2,
            'horario3'    => $this->horario3,
            'delivery'    => $this->delivery,
            'horaInicio'  => $this->horainicio,
            'horaTermino' => $this->horatermino,
            'latitud'     => $this->latitud,
            'longitud'    => $this->longitud,
            'urlImagen'   => $this->urlimagen,
            'ventaxMayor' => $this->ventaxmayor,
            'orden'       => $this->orden,
            'fecha'       => $this->fecha,
        ];

        if ($this->estado !== null && method_exists($this->estado, 'toArray')) {
            $data['estado'] = $this->estado->toArray();
        }

        if ($this->ubigeo !== null) {
            $data['ubigeo'] = $this->convertirUbigeoJerarquicoAArray($this->ubigeo);
        }

        return $data;
    }

    private function convertirUbigeoJerarquicoAArray($ubigeo)
    {
        if (!$ubigeo) return null;

        $resultado = [
            'idUbigeo' => $ubigeo->idubigeo ?? null,
            'idEstado' => $ubigeo->idestado ?? null,
            'idrUbigeo' => $ubigeo->idrubigeo ?? null,
            'nombre'   => $ubigeo->nombre ?? null,
            'codigo'   => $ubigeo->codigo ?? null,
            'gentilicio' => $ubigeo->gentilicio ?? null,
            'fecha'    => $ubigeo->fecha ?? null,
        ];

        if (!empty($ubigeo->rubigeo)) {
            $resultado['rUbigeo'] = $this->convertirUbigeoJerarquicoAArray($ubigeo->rubigeo);
        }

        return $resultado;
    }
}
