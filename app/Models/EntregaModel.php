<?php

namespace App\Models;

use App\Entities\EntregaEntity;
use App\Entities\EstadoEntity;
use CodeIgniter\Model;

class EntregaModel extends Model
{
    protected $table            = 'entrega';
    protected $primaryKey       = 'identrega';
    protected $useAutoIncrement = true;
    protected $returnType       = EntregaEntity::class;
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['idestado', 'nombre', 'dias', 'diashabiles', 'importeminimo', 'minimogratis', 'costoenvio', 'horareferencia', 'pesoxcostoenvio', 'orden', 'fecha'];

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

    public function obtenerPorId($identrega)
    {
        return $this->where('identrega', $identrega)->first();
    }


    function buscarPor($ordencriterio, $ordentipo, $parametro, $valor, $idestado, $inicio, $registros)
    {

        $builder = $this->db->table($this->table);
        $builder->select('*');
        // Filtro por búsqueda
        if (!empty($parametro) && !empty($valor)) $builder->like($parametro, $valor);

        // Filtros por ID
        if ($idestado > 0) $builder->where('idestado', $idestado);
        // Filtros por ID
     
        // Ordenamiento
        if (!empty($ordencriterio) &&  !empty($ordentipo)) $builder->orderBy($ordencriterio, $ordentipo);

        // Paginación
        if ($registros > 0) {
            $builder->limit($registros, $inicio);
        }

        return $builder->get()->getResult();
    }

    public function buscarPorTotal($parametro, $valor, $idestado)
    {
        $builder = $this->db->table($this->table);

        // Filtro por búsqueda
        if (!empty($parametro) && !empty($valor)) {
            $builder->like($parametro, trim($valor), 'both');
        }

        // Filtros por ID
        if ($idestado > 0) $builder->where('idestado', $idestado);
        // Filtros por ID
      


        return $builder->countAllResults();
    }
    public function guardar($mensaje)
    {
        $this->db->transStart();
        try {
            if (empty($mensaje['identrega']) || $mensaje['identrega'] == 0) {
                $this->insert($mensaje);
                $id = $this->getInsertID();
            } else {
                $this->update($mensaje['identrega'], $mensaje);
                $id = $mensaje['identrega'];
            }
            $this->db->transComplete();
            return $id;
        } catch (\Throwable $e) {
            $this->db->transRollback();
            log_message('error', 'Error en guardar: ' . $e->getMessage());
            throw $e;
        }
    }

    public function eliminar($identrega): bool
    {
        $this->db->transStart();
        try {
            if (!$this->where('identrega', $identrega)->first()) {
                return false;
            }

            $resultado = $this->delete($identrega);

            $this->db->transComplete();
            return $resultado;
        } catch (\Throwable $e) {
            $this->db->transRollback();
            log_message('error', 'Eliminar entrega falló: ' . $e->getMessage());
            return false;
        }
    }
}
