<?php

namespace App\Models;

use App\Entities\EstadoEntity;
use CodeIgniter\Model;

class EstadoModel extends Model
{
    protected $table            = 'estado';
    protected $primaryKey       = 'idestado';
    protected $useAutoIncrement = true;
    protected $returnType       = EstadoEntity::class;
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['idclase', 'nombre', 'abr', 'descripcion', 'orden'];

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

    public function obtenerPorId($idEstado)
    {
        return $this->where('idestado', $idEstado)->first();
    }


    function buscarPor($ordencriterio, $ordentipo, $parametro, $valor, $idclase, $inicio, $registros)
    {

        $builder = $this->db->table($this->table);
        $builder->select('*');
        // Filtro por búsqueda
        if (!empty($parametro) && !empty($valor)) $builder->like($parametro, $valor);

        // Filtros por ID
        if ($idclase > 0) $builder->where('idclase', $idclase);

        // Ordenamiento
        if (!empty($ordencriterio) &&  !empty($ordentipo)) $builder->orderBy($ordencriterio, $ordentipo);

        // Paginación
        if ($registros > 0) {
            $builder->limit($registros, $inicio);
        }

        return $builder->get()->getResult();
    }

    public function buscarPorTotal($parametro, $valor, $idclase)
    {
        $builder = $this->db->table($this->table);

        // Filtro por búsqueda
        if (!empty($parametro) && !empty($valor)) {
            $builder->like($parametro, trim($valor), 'both');
        }

        //Filtros por ID
        if ($idclase > 0)  $builder->where('idclase', $idclase);

        return $builder->countAllResults();
    }
}
