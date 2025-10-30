<?php

namespace App\Models;

use App\Entities\ParametroEntity;
use CodeIgniter\Model;

class ParametroModel extends Model
{
    protected $table            = 'parametro';
    protected $primaryKey       = 'idparametro';
    protected $useAutoIncrement = true;
    protected $returnType       = ParametroEntity::class;
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['idtipo', 'idestado', 'nombre', 'abr', 'descripcion', 'orden'];

    protected bool $allowEmptyInserts = false;
    protected bool $updateOnlyChanged = true;

    protected array $casts = [];
    protected array $castHandlers = [];

    // Dates
    protected $useTimestamps = false;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'fecha';
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



    public function obtenerPorId($idparametro)
    {
        return $this->where('idparametro', $idparametro)->first();
    }

    public function buscarPor($ordenCampo, $ordenTipo, $idestado, $idtipo, $inicio, $registros)
    {
        $builder = $this->db->table($this->table);
        $builder->select('*');

        if ($idtipo > 0)
            $builder->where('idtipo', $idtipo);

        if ($idestado > 0)
            $builder->where('idestado', $idestado);

        if (!empty($ordenCampo) && !empty($ordenTipo))
            $builder->orderBy($ordenCampo, $ordenTipo);

        // Paginación
        if ($registros > 0) {
            $builder->limit($registros, $inicio);
        }

        return $builder->get()->getResult();
    }

    public function buscarPorTotal($idestado, $idtipo)
    {
        $builder = $this->db->table($this->table);

        if ($idtipo > 0) $builder->where('idtipo', $idtipo);

        if ($idestado > 0) $builder->where('idestado', $idestado);


        return $builder->countAllResults();
    }
}
