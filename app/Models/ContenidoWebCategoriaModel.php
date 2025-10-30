<?php

namespace App\Models;

use App\Entities\ContenidoWebCategoriaEntity;
use CodeIgniter\Model;

class ContenidoWebCategoriaModel extends Model
{
    protected $table            = 'contenidowebcategoria';
    protected $primaryKey       = 'idcontenidowebcategoria';
    protected $useAutoIncrement = true;
    protected $returnType       = ContenidoWebCategoriaEntity::class;
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['idestado', 'idrcontenidowebcategoria', 'miniatura', 'banner', 'nombre', 'urlamigable', 'descripcionseo', 'orden'];

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


    public function obtenerPorId($idcontenidowebcategoria)
    {
        return $this->where('idcontenidowebcategoria', $idcontenidowebcategoria)->first();
    }


    public function eliminar($idcontenidowebcategoria): bool
    {
        $this->db->transStart();
        try {
            if (!$this->where('idcontenidowebcategoria', $idcontenidowebcategoria)->first()) {
                return false;
            }

            $resultado = $this->delete($idcontenidowebcategoria);

            $this->db->transComplete();
            return $resultado;
        } catch (\Throwable $e) {
            $this->db->transRollback();
            log_message('error', 'Eliminar contenido web categoria falló: ' . $e->getMessage());
            return false;
        }
    }

    public function guardar($data): int
    {
        $this->db->transStart();
        try {
            if (empty($data['idcontenidowebcategoria'])) {
                $this->insert($data);
                $id = $this->getInsertID();
            } else {
                $this->update($data['idcontenidowebcategoria'], $data);
                $id = $data['idcontenidowebcategoria'];
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


    // public function buscarPor($ordencriterio, $ordentipo, $parametro, $valor, $idestado, $idrcontenidowebcategoria, $inicio, $registros)
    // {
    //     $builder = $this->db->table($this->table);
    //     $builder->select('*');

    //     // Filtro por búsqueda
    //     if (!empty($parametro) && !empty($valor)) {
    //         $builder->like($parametro, trim($valor), 'both');
    //     }

    //     if ($idestado > 0)
    //         $builder->where('idestado', $idestado);

    //     if ($idrcontenidowebcategoria > 0)
    //         $builder->where('idrcontenidowebcategoria', $idrcontenidowebcategoria);

    //     // Ordenamiento
    //     if (!empty($ordencriterio) && !empty($ordentipo)) {
    //         $builder->orderBy($ordencriterio, $ordentipo);
    //     }


    //     // Paginación
    //     if ($registros > 0) {
    //         $builder->limit($registros, $inicio);
    //     }
    //     return $builder->get()->getResult();
    // }

    public function buscarPor($ordencriterio, $ordentipo, $parametro, $valor, $idestado, $idrcontenidowebcategoria, $inicio, $registros)
    {
        $builder = $this->db->table($this->table);
        $builder->select('*');

        // Filtro por búsqueda
        if (!empty($parametro) && !empty($valor)) {
            $builder->like($parametro, trim($valor), 'both');
        }

        // Filtro por estado
        if ($idestado > 0) {
            $builder->where('idestado', $idestado);
        }

        // Filtro por idrcontenidowebcategoria
        if ($idrcontenidowebcategoria > 0) {
            $builder->where('idrcontenidowebcategoria', $idrcontenidowebcategoria);
        } elseif ($idrcontenidowebcategoria < 0) {
            $builder->where('idrcontenidowebcategoria IS NULL', null, false); // <- Aquí va la magia
        }
        // Si es 0, no se aplica ningún filtro

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

    // public function buscarPorTotal($parametro, $valor, $idestado, $idrcontenidowebcategoria)
    // {
    //     $builder = $this->db->table($this->table);
    //     $builder->select('COUNT(*) as total');

    //     // Filtro por búsqueda
    //     if (!empty($parametro) && !empty($valor)) {
    //         $builder->like($parametro, trim($valor), 'both');
    //     }

    //     if ($idestado > 0)
    //         $builder->where('idestado', $idestado);

    //     if ($idrcontenidowebcategoria > 0)
    //         $builder->where('idrcontenidowebcategoria', $idrcontenidowebcategoria);



    //     return $builder->countAllResults();
    // }
    public function buscarPorTotal($parametro, $valor, $idestado, $idrcontenidowebcategoria)
    {
        $builder = $this->db->table($this->table);
        $builder->select('COUNT(*) as total');

        // Filtro por búsqueda
        if (!empty($parametro) && !empty($valor)) {
            $builder->like($parametro, trim($valor), 'both');
        }

        // Filtro por estado
        if ($idestado > 0) {
            $builder->where('idestado', $idestado);
        }

        // Filtro por idrcontenidowebcategoria
        if ($idrcontenidowebcategoria > 0) {
            $builder->where('idrcontenidowebcategoria', $idrcontenidowebcategoria);
        } elseif ($idrcontenidowebcategoria < 0) {
            $builder->where('idrcontenidowebcategoria IS NULL', null, false);
        }
        // Si es 0, no aplica ningún filtro

        return $builder->countAllResults();
    }
}
