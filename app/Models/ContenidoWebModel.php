<?php

namespace App\Models;

use App\Entities\ContenidoWebEntity;
use CodeIgniter\Model;

class ContenidoWebModel extends Model
{
    protected $table            = 'contenidoweb';
    protected $primaryKey       = 'idcontenidoweb';
    protected $useAutoIncrement = true;
    protected $returnType       = ContenidoWebEntity::class;
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'idcontenidoweb',
        'idestado',
        'idcontenidowebcategoria',
        'idptipo',
        'nombre',
        'urlamigable',
        'resumen',
        'contenido',
        'seccion',
        'urlimagen',
        'urlbanner',
        'orden',
        'tituloseo',
        'descripcionseo',
        'palabrasclaveseo',
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

    public function obtenerPorId($idcontenidoweb)
    {
        return $this->where('idcontenidoweb', $idcontenidoweb)->first();
    }

    public function obtenerPorUrlAmigable($urlamigable)
    {
        return $this->where('urlamigable', $urlamigable)->first();
    }

    public function eliminar($idcontenidoweb): bool
    {
        $this->db->transStart();
        try {
            if (!$this->where('idcontenidoweb', $idcontenidoweb)->first()) {
                return false;
            }

            $resultado = $this->delete($idcontenidoweb);

            $this->db->transComplete();
            return $resultado;
        } catch (\Throwable $e) {
            $this->db->transRollback();
            log_message('error', 'Eliminar contenidoweb falló: ' . $e->getMessage());
            return false;
        }
    }

    public function guardar($data): int
    {
        $this->db->transStart();
        try {
            if (empty($data['idcontenidoweb'])) {
                $this->insert($data);
                $id = $this->getInsertID();
            } else {
                $this->update($data['idcontenidoweb'], $data);
                $id = $data['idcontenidoweb'];
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
    public function buscarPor($ordencriterio, $ordentipo, $parametro, $valor, $idestado, $idcontenidowebcategoria, $idptipo, $inicio, $registros)
    {
        $builder = $this->db->table($this->table);
        $builder->select('*');

        // Filtro por búsqueda
        if (!empty($parametro) && !empty($valor)) {
            $builder->like($parametro, trim($valor), 'both');
        }

        if ($idestado > 0)
            $builder->where('idestado', $idestado);

        if ($idcontenidowebcategoria > 0)
            $builder->where('idcontenidowebcategoria', $idcontenidowebcategoria);

        if ($idptipo > 0)
            $builder->where('idptipo', $idptipo);

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

    public function buscarPorTotal($parametro, $valor, $idestado, $idcontenidowebcategoria, $idptipo)
    {
        $builder = $this->db->table($this->table);
        $builder->select('COUNT(*) as total');

        // Filtro por búsqueda
        if (!empty($parametro) && !empty($valor)) {
            $builder->like($parametro, trim($valor), 'both');
        }

        if ($idestado > 0)
            $builder->where('idestado', $idestado);

        if ($idcontenidowebcategoria > 0)
            $builder->where('idcontenidowebcategoria', $idcontenidowebcategoria);

        if ($idptipo > 0)
            $builder->where('idptipo', $idptipo);



        return $builder->countAllResults();
    }
}
