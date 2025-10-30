<?php

namespace App\Models;

use App\Entities\SedeEntity;
use CodeIgniter\Model;

class SedeModel extends Model
{
    protected $table            = 'sede';
    protected $primaryKey       = 'idsede';
    protected $useAutoIncrement = true;
    protected $returnType       = SedeEntity::class;
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['idestado', 'idempresa', 'idubigeo', 'nombre', 'urlcabecera', 'telefono', 'direccion', 'orden', 'latitud', 'longitud'];

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

    public function obtenerPorId($idSede)
    {
        return $this->where('idsede', $idSede)->first();
    }


    function buscarPor($ordencriterio, $ordentipo, $parametro, $valor, $idestado, $idempresa, $idubigeo, $inicio, $registros)
    {

        $builder = $this->db->table($this->table);
        $builder->select('*');
        // Filtro por búsqueda
        if (!empty($parametro) && !empty($valor)) $builder->like($parametro, $valor);

        // Filtros por ID
        if ($idestado > 0) $builder->where('idestado', $idestado);
        // Filtros por ID
        if ($idempresa > 0) $builder->where('idempresa', $idempresa);
        // Filtros por ID
        if ($idubigeo > 0) $builder->where('idubigeo', $idubigeo);

        // Ordenamiento
        if (!empty($ordencriterio) &&  !empty($ordentipo)) $builder->orderBy($ordencriterio, $ordentipo);

        // Paginación
        if ($registros > 0) {
            $builder->limit($registros, $inicio);
        }

        return $builder->get()->getResult();
    }

    public function buscarPorTotal($parametro, $valor,  $idestado, $idempresa, $idubigeo,)
    {
        $builder = $this->db->table($this->table);

        // Filtro por búsqueda
        if (!empty($parametro) && !empty($valor)) {
            $builder->like($parametro, trim($valor), 'both');
        }

        //Filtros por ID
        if ($idestado > 0)  $builder->where('idestado', $idestado);
        //Filtros por ID
        if ($idempresa > 0)  $builder->where('idempresa', $idempresa);
        //Filtros por ID
        if ($idubigeo > 0)  $builder->where('idubigeo', $idubigeo);

        return $builder->countAllResults();
    }

    public function eliminar($idsede): bool
    {
        $this->db->transStart();
        try {
            if (!$this->where('idsede', $idsede)->first()) {
                return false;
            }

            $resultado = $this->delete($idsede);

            $this->db->transComplete();
            return $resultado;
        } catch (\Throwable $e) {
            $this->db->transRollback();
            log_message('error', 'Eliminar sede falló: ' . $e->getMessage());
            return false;
        }
    }

    public function guardar($data): int
    {
        $this->db->transStart();
        try {
            if (empty($data['idsede'])) {
                $this->insert($data);
                $id = $this->getInsertID();
            } else {
                $this->update($data['idsede'], $data);
                $id = $data['idsede'];
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
    //
    public function contarSedesPorEmpresa($idempresa)
    {
        return $this->where('idempresa', $idempresa)->countAllResults();
    }
}
