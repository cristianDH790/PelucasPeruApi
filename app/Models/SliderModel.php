<?php

namespace App\Models;

use App\Entities\SliderEntity;
use CodeIgniter\Model;

class SliderModel extends Model
{
    protected $table            = 'slider';
    protected $primaryKey       = 'idslider';
    protected $useAutoIncrement = true;
    protected $returnType       = SliderEntity::class;
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'idestado',
        'nombre',
        'descripcion',
        'idptiporecurso',
        'idproductocategoria',
        'urlimagen1',
        'urlimagen2',
        'urlrecurso',
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

    public function obtenerPorId($idslider)
    {
        return $this->where('idslider', $idslider)->first();
    }

    public function obtenerPorCategoria($idCategoria)
    {
        if (!$idCategoria) return [];
        return $this->where('idproductocategoria', $idCategoria)
            ->orderBy('orden', 'ASC')
            ->findAll(); // devuelve entidades
    }



    public function eliminar($idslider): bool
    {
        $this->db->transStart();
        try {
            if (!$this->where('idslider', $idslider)->first()) {
                return false;
            }

            $resultado = $this->delete($idslider);

            $this->db->transComplete();
            return $resultado;
        } catch (\Throwable $e) {
            $this->db->transRollback();
            log_message('error', 'Eliminar slider falló: ' . $e->getMessage());
            return false;
        }
    }

    public function guardar($data): int
    {
        $this->db->transStart();
        try {
            if (empty($data['idslider'])) {
                $this->insert($data);
                $id = $this->getInsertID();
            } else {
                $this->update($data['idslider'], $data);
                $id = $data['idslider'];
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

    public function buscarPor($ordencriterio = '', $ordentipo = '', $parametro = '', $valor = '', $idestado = 0, $idpcategoria = 0, $idprecurso = 0, $inicio = 0, $registros = 0)
    {
        $builder = $this->db->table($this->table);
        $builder->select('*');

        // Filtro por búsqueda
        if (!empty($parametro) && !empty($valor)) {
            $builder->like($parametro, trim($valor), 'both');
        }
        if ($idestado > 0)
            $builder->where('idestado', $idestado);

        if ($idpcategoria > 0)
            $builder->where('idpcategoria', $idpcategoria);
        if ($idprecurso > 0)
            $builder->where('idptiporecurso', $idprecurso);

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

    public function buscarPorTotal($parametro = '', $valor = '', $idestado = 0, $idpcategoria = 0, $idprecurso = 0)
    {
        $builder = $this->db->table($this->table);
        $builder->select('COUNT(*) as total');

        // Filtro por búsqueda
        if (!empty($parametro) && !empty($valor)) {
            $builder->like($parametro, trim($valor), 'both');
        }
        if ($idestado > 0)
            $builder->where('idestado', $idestado);

        if ($idpcategoria > 0)
            $builder->where('idpcategoria', $idpcategoria);
        if ($idprecurso > 0)
            $builder->where('idptiporecurso', $idprecurso);

        return $builder->countAllResults();
    }
}
