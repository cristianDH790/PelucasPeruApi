<?php

namespace App\Models;

use App\Entities\ConfiguracionEntity;
use CodeIgniter\Model;

class ConfiguracionModel extends Model
{
    protected $table            = 'configuracion';
    protected $primaryKey       = 'idconfiguracion';
    protected $useAutoIncrement = true;
    protected $returnType       = ConfiguracionEntity::class;
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields = [
        'idptipo',
        'nombre',
        'urlimagen',
        'idprecurso',
        'valor',
        'descripcion',
        'urlimagen',
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

    public function obtenerPorId($idconfiguracion)
    {
        return $this->where('idconfiguracion', $idconfiguracion)->first();
    }
    public function buscarPor($ordencriterio, $ordentipo, $idptipo, $idprecurso, $registros, $inicio)
    {
        $builder = $this->db->table($this->table);

        $builder->select('*');



        if ($idptipo > 0) {
            $builder->where('idptipo', $idptipo);
        }

        if ($idprecurso > 0)
            $builder->where('idprecurso', $idprecurso);

        // Ordenamiento
        if (!empty($ordencriterio) && !empty($ordentipo)) {
            $builder->orderBy($ordencriterio, $ordentipo);
        }

        //  Paginación
        if ($registros > 0) {
            $builder->limit($registros, $inicio);
        }


        return $builder->get()->getResult();
    }
    public function buscarPorTotal($idptipo, $idprecurso)
    {
        $builder = $this->db->table($this->table);
        $builder->select('COUNT(*) as total');

        if ($idptipo > 0)
            $builder->where('idptipo', $idptipo);
        if ($idprecurso > 0)
            $builder->where('idprecurso', $idprecurso);

        return $builder->countAllResults();
    }
    public function eliminar($idconfiguracion): bool
    {
        $this->db->transStart();
        try {
            if (!$this->where('idconfiguracion', $idconfiguracion)->first()) {
                return false;
            }

            $resultado = $this->delete($idconfiguracion);

            $this->db->transComplete();
            return $resultado;
        } catch (\Throwable $e) {
            $this->db->transRollback();
            log_message('error', 'Eliminar configuracion falló: ' . $e->getMessage());
            return false;
        }
    }

    public function guardar($data): int
    {
        $this->db->transStart();
        try {
            if (empty($data['idconfiguracion'])) {
                $this->insert($data);
                $id = $this->getInsertID();
            } else {
                $this->update($data['idconfiguracion'], $data);
                $id = $data['idconfiguracion'];
            }
            $this->db->transComplete();
            return $id;
        } catch (\Throwable $e) {
            $this->db->transRollback();
            var_dump($data);

            log_message('error', 'Error en guardar: ' . $e->getMessage());
            throw $e;
        }
    }
}
