<?php

namespace App\Models;

use App\Entities\ClaseEntity;
use CodeIgniter\Model;

class ClaseModel extends Model
{
    protected $table            = 'clase';
    protected $primaryKey       = 'idclase';
    protected $useAutoIncrement = true;
    protected $returnType       = ClaseEntity::class;
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'idrclase',
        'nombre',
        'descripcion',
        'orden',
        'fecha'
    ];

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


    public function obtenerPorId($idClase)
    {
        return $this->where('idclase', $idClase)->first();
    }


    public function buscarPor($ordencriterio, $ordentipo, $parametro, $valor, $idclase, $inicio, $registros)
    {
        $builder = $this->db->table($this->table);
        $builder->select('*');
        // Filtro por búsqueda
        if (!empty($parametro) && !empty($valor)) {
            $builder->like($parametro, $valor);
        }
        // Filtros por ID
        if ($idclase > 0) $builder->where('idclase', $idclase);
        // Ordenamiento
        if (!empty($ordencriterio) && !empty($ordentipo)) $builder->orderBy($ordencriterio, $ordentipo);

        // Paginación
        if ($registros > 0) {
            $builder->limit($registros, $inicio);
        }

        return $builder->get()->getResult();
    }

    public function buscarPorTotal($parametro, $valor, $idclase)
    {
        $builder = $this->db->table($this->table); // usa la tabla 'clase'

        if (!empty($parametro) && !empty($valor)) {
            $builder->like($parametro, $valor);
        }

        if ($idclase > 0) $builder->where('idclase', $idclase);


        return $builder->countAllResults();
    }
}
