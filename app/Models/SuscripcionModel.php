<?php

namespace App\Models;

use App\Entities\ConfiguracionEntity;
use App\Entities\SuscripcionEntity;
use CodeIgniter\Model;

use function PHPUnit\Framework\isEmpty;

class SuscripcionModel extends Model
{
    protected $table            = 'suscripcion';
    protected $primaryKey       = 'idsuscripcion';
    protected $useAutoIncrement = true;
    protected $returnType       = SuscripcionEntity::class;
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['correo', 'fecha'];

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


    public function obtenerPorId($idsuscripcion)
    {
        return $this->where('idsuscripcion', $idsuscripcion)->first();
    }
    public static function suscripcionPorCorreo($correo, $idsuscripcion)
    {
        $suscripcion = new SuscripcionModel();
        $suscripcion->where('correo', $correo);

        if ($idsuscripcion > 0)
            $suscripcion->where('idsuscripcion !=', $idsuscripcion);

        return $suscripcion->first();
    }

    public function guardar($data)
    {
        $this->db->transStart();
        try {
            if (empty($data['idsuscripcion'])) {
                $this->insert($data);
                $id = $this->getInsertID();
            } else {
                $this->update($data['idsuscripcion'], $data);
                $id = $data['idsuscripcion'];
            }
            $this->db->transComplete();
            return $id;
        } catch (\Throwable $th) {
            $this->db->transRollback();
        }
    }

    public function eliminar($idsuscripcion)
    {
        $this->db->transStart();
        try {
            if (!$this->where('idsuscripcion', $idsuscripcion)->first()) {
                return false;
            }
            $resultado = $this->delete($idsuscripcion);
            $this->db->transComplete();
            return $resultado;
        } catch (\Throwable $th) {
            $this->db->transRollback();
        }
    }


       public function buscarPor($ordencriterio = '', $ordentipo = '', $parametro = '', $valor = '',  $inicio = 0, $registros = 0)
    {
        $builder = $this->db->table($this->table);

        // Filtro por búsqueda
        if (!empty($parametro) && !empty($valor)) {
            $builder->like($parametro, trim($valor), 'both');
        }

        // Ordenamiento
        if (!empty($ordencriterio) && !empty($ordentipo)) {
            $builder->orderBy($ordencriterio, $ordentipo);
        }

        // Paginación
        if ($registros > 0) {
            $builder->limit($registros, $inicio);
        }

        return $builder->get()->getResult();
    }

    public  function buscarPorTotal($parametro, $valor)
    {
       $builder = $this->db->table($this->table);

        // Select con alias para evitar colisiones
        $builder->select('*');

        // Filtro por búsqueda
        if (!empty($parametro) && !empty($valor))  $builder->like($parametro, trim($valor), 'both');

        return $builder->countAllResults();
    }
}
