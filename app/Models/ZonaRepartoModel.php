<?php

namespace App\Models;

use App\Entities\SedeEntity;
use App\Entities\ZonaRepartoEntity;
use CodeIgniter\Model;

class ZonaRepartoModel extends Model
{
    protected $table            = 'zonareparto';
    protected $primaryKey       = 'idzonareparto';
    protected $useAutoIncrement = true;
    protected $returnType       = ZonaRepartoEntity::class;
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['idestado', 'nombre', 'costo'];

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

    public function obtenerPorId($idzonareparto)
    {
        return $this->where('idzonareparto', $idzonareparto)->first();
    }


    function buscarPor($ordencriterio, $ordentipo, $parametro, $valor, $idestado, $inicio, $registros)
    {

        $builder = $this->db->table($this->table);
        $builder->select('*');
        // Filtro por búsqueda
        if (!empty($parametro) && !empty($valor)) $builder->like($parametro, $valor);

        // Filtros por ID
        if ($idestado > 0) $builder->where('idestado', $idestado);

        // Ordenamiento
        if (!empty($ordencriterio) &&  !empty($ordentipo)) $builder->orderBy($ordencriterio, $ordentipo);

        // Paginación
        if ($registros > 0) {
            $builder->limit($registros, $inicio);
        }

        return $builder->get()->getResult();
    }

    public function buscarPorTotal($parametro, $valor,  $idestado)
    {
        $builder = $this->db->table($this->table);

        // Filtro por búsqueda
        if (!empty($parametro) && !empty($valor)) {
            $builder->like($parametro, trim($valor), 'both');
        }

        //Filtros por ID
        if ($idestado > 0)  $builder->where('idestado', $idestado);


        return $builder->countAllResults();
    }

    public function eliminar($idzonareparto): bool
    {
        $this->db->transStart();
        try {
            // Verificar si existe la zona
            if (!$this->where('idzonareparto', $idzonareparto)->first()) {
                return false;
            }

            //  Eliminar relaciones en tabla intermedia
            $this->db->table('zonareparto_ubigeo')
                ->where('idzonareparto', $idzonareparto)
                ->delete();

            //  Eliminar zona principal
            $resultado = $this->delete($idzonareparto);

            $this->db->transComplete();
            return $resultado;
        } catch (\Throwable $e) {
            $this->db->transRollback();
            log_message('error', 'Eliminar zona de reparto falló: ' . $e->getMessage());
            return false;
        }
    }

    //   public function eliminar($idzonareparto): bool
    // {
    //     $this->db->transStart();
    //     try {
    //         if (!$this->where('idzonareparto', $idzonareparto)->first()) {
    //             return false;
    //         }

    //         $resultado = $this->delete($idzonareparto);

    //         $this->db->transComplete();
    //         return $resultado;
    //     } catch (\Throwable $e) {
    //         $this->db->transRollback();
    //         log_message('error', 'Eliminar sede falló: ' . $e->getMessage());
    //         return false;
    //     }
    // }

    public function guardar($data): int
    {
        $this->db->transStart();
        try {
            if (empty($data['idzonareparto'])) {
                $this->insert($data);
                $id = $this->getInsertID();
            } else {
                $this->update($data['idzonareparto'], $data);
                $id = $data['idzonareparto'];
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
