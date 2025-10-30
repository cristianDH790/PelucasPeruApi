<?php

namespace App\Models;

use App\Entities\MensajeEntity;
use CodeIgniter\Model;

class MensajeModel extends Model
{
    protected $table            = 'mensaje';
    protected $primaryKey       = 'idmensaje';
    protected $useAutoIncrement = true;
    protected $returnType       = MensajeEntity::class;
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'idestado',
        'idclase',
        'nombre',
        'asunto',
        'contenido',
        'variables',
        'fecha',
    ];

    protected bool $allowEmptyInserts = false;
    protected bool $updateOnlyChanged = true;

    protected array $casts = [];
    protected array $castHandlers = [];

    // Dates
    protected $useTimestamps = false;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    // Validation
    protected $validationRules      = [];
    protected $validationMessages   = [];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert   = [];
    protected $afterInsert    = [];
    protected $beforeUpdate   = [];
    protected $afterUpdate    = [];
    protected $beforeFind     = [];
    protected $afterFind      = [];
    protected $beforeDelete   = [];
    protected $afterDelete    = [];

    public function obtenerPorId($idmensaje)
    {
        return $this->where('idmensaje', $idmensaje)->first();
    }

    public function buscarPor($ordencriterio = '', $ordentipo = '', $idclase = 0, $idestado = 0, $registros = null, $inicio = null)
    {
        $builder = $this->db->table($this->table);
        $builder->select('*');

        if ($idclase > 0) $builder->where('idclase', $idclase);

        if ($idestado > 0) $builder->where('idestado', $idestado);


        if (!empty($ordencriterio) && !empty($ordentipo)) {
            $builder->orderBy($ordencriterio, $ordentipo);
        }

        if (!empty($registros) && !empty($inicio)) {
            $builder->limit($registros, $inicio);
        }


        if ($registros > 0) {
            $builder->limit($registros, $inicio);
        }

        return $builder->get()->getResult();
    }


    public function buscarPorTotal($idclase = '', $idestado = 0)
    {
        $builder = $this->db->table($this->table);
        $builder->select('COUNT(*) as total');

        if ($idclase > 0) $builder->where('idclase', $idclase);

        if ($idestado > 0) $builder->where('idestado', $idestado);


        return $builder->countAllResults();
    }

    // public function guardar($mensaje)
    // {
    //     $this->db->transStart();
    //     try {
    //         if (empty($mensaje['idmensaje']) || $mensaje['idmensaje'] == 0) {
    //             $this->insert($mensaje);
    //             $id = $this->getInsertID();
    //         } else {
    //             $this->update($mensaje['idmensaje'], $mensaje);
    //             $id = $mensaje['idmensaje'];
    //         }
    //         $this->db->transComplete();
    //         return $id;
    //     } catch (\Throwable $e) {
    //         $this->db->transRollback();
    //         log_message('error', 'Error en guardar: ' . $e->getMessage());
    //         throw $e;
    //     }

    // }

    public function guardar($mensaje)
    {
        $this->db->transStart();

        try {
            // Loguear los datos que se intentan insertar o actualizar
            log_message('debug', 'Datos a guardar en mensaje: ' . json_encode($mensaje));

            if (empty($mensaje['idmensaje']) || $mensaje['idmensaje'] == 0) {
                $result = $this->insert($mensaje);
                if ($result === false) {
                    $error = $this->db->error();
                    throw new \Exception('Error en insert: ' . print_r($error, true));
                }
                $id = $this->getInsertID();
            } else {
                $result = $this->update($mensaje['idmensaje'], $mensaje);
                if ($result === false) {
                    $error = $this->db->error();
                    throw new \Exception('Error en update: ' . print_r($error, true));
                }
                $id = $mensaje['idmensaje'];
            }

            $this->db->transComplete();

            // Confirmar que la transacción se completó correctamente
            if ($this->db->transStatus() === false) {
                throw new \Exception('Error en la transacción de base de datos');
            }

            return $id;
        } catch (\Throwable $e) {
            $this->db->transRollback();
            log_message('error', 'Error en guardar mensaje: ' . $e->getMessage());
            throw $e;
        }
    }


    public function eliminar($idmensaje): bool
    {
        $this->db->transStart();
        try {
            if (!$this->where('idmensaje', $idmensaje)->first()) {
                return false;
            }

            $resultado = $this->delete($idmensaje);

            $this->db->transComplete();
            return $resultado;
        } catch (\Throwable $e) {
            $this->db->transRollback();
            log_message('error', 'Eliminar usuario falló: ' . $e->getMessage());
            return false;
        }
    }
}
