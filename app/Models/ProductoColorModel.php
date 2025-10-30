<?php

namespace App\Models;

use App\Entities\ProductoColorEntity;
use CodeIgniter\Model;

class ProductoColorModel extends Model
{
    protected $table            = 'productocolor';
    protected $primaryKey       = 'idproductocolor';
    protected $useAutoIncrement = true;
    protected $returnType       = ProductoColorEntity::class;
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;

    protected $allowedFields    = [
        'idestado',
        'idproducto',
        'idcolor',
        'nombre',
        'urlamigable',
        'orden',
        'stock',
        'destacado',
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

    // 🔍 Obtener por ID
    public function obtenerPorId($idproductocolor)
    {
        return $this->where('idproductocolor', $idproductocolor)->first();
    }
    public function obtenerPorUrlAmigable($urlamigable)
    {
        return $this->where('urlamigable', $urlamigable)->first();
    }

    public function obtenerPorProducto($idproducto)
    {
        return $this->db->table('productocolor pc')
            ->select('pc.*, c.codigoproductocolor')
            ->join('color c', 'c.idcolor = pc.idcolor')
            ->where('pc.idproducto', $idproducto)
            ->get()
            ->getResult();
    }


    // 🔍 Buscar con filtros
    public function buscarPor($parametro, $valor, $idestado, $idproducto, $registros, $inicio)
    {
        $builder = $this->db->table($this->table);
        $builder->select('*');

        if (!empty($parametro) && !empty($valor)) {
            $builder->like($parametro, $valor);
        }

        if ($idestado > 0) {
            $builder->where('idestado', $idestado);
        }
        if ($idproducto > 0) {
            $builder->where('idproducto', $idproducto);
        }

        if (!empty($registros) && $registros > 0) {
            $builder->limit($registros, $inicio);
        }

        return $builder->get()->getResult();
    }

    // 🔢 Total de registros
    public function buscarPorTotal($idestado, $idproducto)
    {
        $builder = $this->db->table($this->table);
        $builder->select('COUNT(*) as total');

        if ($idestado > 0) {
            $builder->where('idestado', $idestado);
        }
        if ($idproducto > 0) {
            $builder->where('idproducto', $idproducto);
        }

        return $builder->countAllResults();
    }

    // 💾 Insertar o actualizar
    public function guardar($productoColor)
    {
        $this->db->transStart();

        try {
            log_message('debug', 'Datos a guardar en productocolor: ' . json_encode($productoColor));

            if (empty($productoColor['idproductocolor']) || $productoColor['idproductocolor'] == 0) {
                $result = $this->insert($productoColor);
                if ($result === false) {
                    $error = $this->db->error();
                    throw new \Exception('Error en insert: ' . print_r($error, true));
                }
                $id = $this->getInsertID();
            } else {
                $result = $this->update($productoColor['idproductocolor'], $productoColor);
                if ($result === false) {
                    $error = $this->db->error();
                    throw new \Exception('Error en update: ' . print_r($error, true));
                }
                $id = $productoColor['idproductocolor'];
            }

            $this->db->transComplete();

            if ($this->db->transStatus() === false) {
                throw new \Exception('Error en la transacción de base de datos');
            }

            return $id;
        } catch (\Throwable $e) {
            $this->db->transRollback();
            log_message('error', 'Error en guardar productocolor: ' . $e->getMessage());
            throw $e;
        }
    }

    // 🗑️ Eliminar
    public function eliminar($idproductocolor): bool
    {
        $this->db->transStart();
        try {
            if (!$this->where('idproductocolor', $idproductocolor)->first()) {
                return false;
            }

            $resultado = $this->delete($idproductocolor);
            $this->db->transComplete();

            return $resultado;
        } catch (\Throwable $e) {
            $this->db->transRollback();
            log_message('error', 'Eliminar productocolor falló: ' . $e->getMessage());
            return false;
        }
    }
}
