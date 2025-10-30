<?php

namespace App\Models;

use App\Entities\PerfilEntity;

use CodeIgniter\Model;

class PerfilModel extends Model
{
    protected $table            = 'perfil';
    protected $primaryKey       = 'idperfil';
    protected $useAutoIncrement = true;
    protected $returnType       = PerfilEntity::class;
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['idPerfil','idestado', 'nombre', 'abr', 'descripcion'];

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

    public function obtenerPorId($idPerfil)
    {
        return $this->where('idperfil', $idPerfil)->first();
    }
      public function guardar($data)
    {
        $this->db->transStart();
        try {
            if (empty($data['idperfil'])) {
                $this->insert($data);
                $id = $this->getInsertID();
            } else {
                $this->update($data['idperfil'], $data);
                $id = $data['idperfil'];
            }
            $this->db->transComplete();
            return $id;
        } catch (\Throwable $th) {
            $this->db->transRollback();
        }
    }

    public function eliminar($idperfil)
    {
        $this->db->transStart();
        try {
            if (!$this->where('idperfil', $idperfil)->first()) {
                return false;
            }
            $resultado = $this->delete($idperfil);
            $this->db->transComplete();
            return $resultado;
        } catch (\Throwable $th) {
            $this->db->transRollback();
        }
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

    public function buscarPorTotal($parametro, $valor, $idestado)
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
}
