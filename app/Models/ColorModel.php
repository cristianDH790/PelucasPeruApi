<?php

namespace App\Models;

use App\Entities\ColorEntity;
use CodeIgniter\Model;

class ColorModel extends Model
{
    protected $table            = 'color';
    protected $primaryKey       = 'idcolor';
    protected $useAutoIncrement = true;
    protected $returnType       = ColorEntity::class;
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;

    protected $allowedFields    = [
        'idestado',
        'nombre',
        'codigo',
        'codigoproductocolor',
        'fecha',
    ];

    protected bool $allowEmptyInserts = false;
    protected bool $updateOnlyChanged = true;

    // Fechas
    protected $useTimestamps = false;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    // Validación
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

    // 🔍 Obtener color por ID
    public function obtenerPorId($idcolor)
    {
        return $this->where('idcolor', $idcolor)->first();
    }

    public function buscarPorAbr(string $abr)
    {
        return $this->db->table('color')
            ->where('codigoproductocolor', $abr)
            ->get()
            ->getRowArray(); // o getRow() dependiendo del framework
    }


    // 🔍 Buscar colores con filtros
    public function buscarPor($parametro, $valor, $idestado, $registros, $inicio)
    {
        $builder = $this->db->table($this->table);
        $builder->select('*');

        if (!empty($parametro) && !empty($valor)) {
            $builder->like($parametro, $valor);
        }

        if ($idestado > 0) {
            $builder->where('idestado', $idestado);
        }

        if (!empty($registros) && $registros > 0) {
            $builder->limit($registros, $inicio);
        }



        return $builder->get()->getResult();
    }

    // 🔢 Total de registros
    public function buscarPorTotal($idestado)
    {
        $builder = $this->db->table($this->table);
        $builder->select('COUNT(*) as total');

        if ($idestado > 0) {
            $builder->where('idestado', $idestado);
        }

        return $builder->countAllResults();
    }

    // 💾 Insertar o actualizar
    public function guardar($color)
    {
        $this->db->transStart();

        try {
            log_message('debug', 'Datos a guardar en color: ' . json_encode($color));

            if (empty($color['idcolor']) || $color['idcolor'] == 0) {
                $result = $this->insert($color);
                if ($result === false) {
                    $error = $this->db->error();
                    throw new \Exception('Error en insert: ' . print_r($error, true));
                }
                $id = $this->getInsertID();
            } else {
                $result = $this->update($color['idcolor'], $color);
                if ($result === false) {
                    $error = $this->db->error();
                    throw new \Exception('Error en update: ' . print_r($error, true));
                }
                $id = $color['idcolor'];
            }

            $this->db->transComplete();

            if ($this->db->transStatus() === false) {
                throw new \Exception('Error en la transacción de base de datos');
            }

            return $id;
        } catch (\Throwable $e) {
            $this->db->transRollback();
            log_message('error', 'Error en guardar color: ' . $e->getMessage());
            throw $e;
        }
    }

    // 🗑️ Eliminar color
    public function eliminar($idcolor): bool
    {
        $this->db->transStart();
        try {
            if (!$this->where('idcolor', $idcolor)->first()) {
                return false;
            }

            $resultado = $this->delete($idcolor);
            $this->db->transComplete();

            return $resultado;
        } catch (\Throwable $e) {
            $this->db->transRollback();
            log_message('error', 'Eliminar color falló: ' . $e->getMessage());
            return false;
        }
    }
}
