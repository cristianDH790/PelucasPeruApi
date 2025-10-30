<?php

namespace App\Entities;

use App\Models\SedeModel;
use CodeIgniter\Entity\Entity;

class EmpresaEntity
{
    public $idempresa;
    public $idestado;
    public $nombre;
    public $razonsocial;
    public $ruc;
    public $direccion;
    public $orden;
    public $fecha;

    //campos extra
    public $sede;
    public $importetotal;
    public $usuarios;
    public $clientes;
    public $pedidos;
    public $sedes;
    public $marcas;


    // Relaciones
    public $estado;

    public function __construct($data = null)
    {
        if ($data) {
            if (is_array($data)) {
                $this->idempresa = $data['idempresa'] ?? null;
                $this->idestado = $data['idestado'] ?? null;
                $this->nombre = $data['nombre'] ?? null;
                $this->razonsocial = $data['razonsocial'] ?? null;
                $this->ruc = $data['ruc'] ?? null;
                $this->direccion = $data['direccion'] ?? null;
                $this->orden = $data['orden'] ?? null;
                $this->fecha = $data['fecha'] ?? null;
            } elseif (is_object($data)) {
                $this->idempresa = $data->idempresa ?? null;
                $this->idestado = $data->idestado ?? null;
                $this->nombre = $data->nombre ?? null;
                $this->razonsocial = $data->razonsocial ?? null;
                $this->ruc = $data->ruc ?? null;
                $this->direccion = $data->direccion ?? null;
                $this->orden = $data->orden ?? null;
                $this->fecha = $data->fecha ?? null;
            }
        }
    }

    public function toArray()
    {
        //      importeTotal!:number;
        // usuarios!:number;
        // clientes!:number;
        // pedidos!: number;
        // sedes!: number;
        // marcas!: number

        //sede 

        $data = [
            'idEmpresa'          => (int) $this->idempresa,
            'idEstado'           => (int) $this->idestado,
            'nombre'             => $this->nombre,
            'razonSocial'        => $this->razonsocial,
            'ruc'                => $this->ruc,
            'direccion'          => $this->direccion,
            'orden'              => $this->orden,
            'fecha'              => $this->fecha,
            //campos extra (traer las relaciones con sede usuarios marcas por el id)
            'sede' => $this->sede,
            'importeTotal' => $this->importetotal,
            'usuarios' => $this->usuarios,
            'clientes' => $this->clientes,
            'pedidos' => $this->pedidos,
            'sedes' => $this->sedes,
            'marcas' => $this->marcas,


        ];

        if ($this->estado !== null) {
            $data['estado'] = $this->estado->toArray();
        }

        return $data;
    }
}
