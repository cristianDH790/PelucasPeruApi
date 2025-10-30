<?php

namespace App\Models;

use App\Entities\MensajeEntity;
use App\Entities\UbigeoEntity;
use CodeIgniter\Model;

class UbigeoModel extends Model
{
    protected $table            = 'ubigeo';
    protected $primaryKey       = 'idubigeo';
    protected $useAutoIncrement = true;
    protected $returnType       = UbigeoEntity::class;
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'idubigeo',
        'idrubigeo',
        'idestado',
        'nombre',
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

    public function obtenerPorId($idubigeo)
    {
        return $this->where('idubigeo', $idubigeo)->first();
    }

    public function buscarPor($ordencriterio , $ordentipo ,  $parametro, $valor, $idrubigeo , $registros , $inicio )
    {
        $builder = $this->db->table($this->table);
        $builder->select('*');

        if ($idrubigeo > 0) $builder->where('idrubigeo', $idrubigeo);

        // Filtro por b煤squeda
        if (!empty($parametro) && !empty($valor)) {
            $builder->like($parametro, trim($valor), 'both');
        }

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


    public function buscarPorTotal($idrubigeo , $idestado )
    {
        $builder = $this->db->table($this->table);
        $builder->select('COUNT(*) as total');

        if ($idrubigeo > 0) $builder->where('idrubigeo', $idrubigeo);

        if ($idestado > 0) $builder->where('idestado', $idestado);

        return $builder->countAllResults();
    }

    public function guardar($mensaje)
    {
        $this->db->transStart();
        try {
            if (empty($mensaje['idubigeo']) || $mensaje['idubigeo'] == 0) {
                $this->insert($mensaje);
                $id = $this->getInsertID();
            } else {
                $this->update($mensaje['idubigeo'], $mensaje);
                $id = $mensaje['idubigeo'];
            }
            $this->db->transComplete();
            return $id;
        } catch (\Throwable $e) {
            $this->db->transRollback();
            log_message('error', 'Error en guardar: ' . $e->getMessage());
            throw $e;
        }
    }

    public function eliminar($idubigeo): bool
    {
        $this->db->transStart();
        try {
            if (!$this->where('idubigeo', $idubigeo)->first()) {
                return false;
            }

            $resultado = $this->delete($idubigeo);

            $this->db->transComplete();
            return $resultado;
        } catch (\Throwable $e) {
            $this->db->transRollback();
            log_message('error', 'Eliminar usuario fall贸: ' . $e->getMessage());
            return false;
        }
    }
}
