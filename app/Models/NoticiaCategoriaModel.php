<?php

namespace App\Models;

use App\Entities\NoticiaCategoria;
use App\Entities\NoticiaCategoriaEntity;
use CodeIgniter\Model;

class NoticiaCategoriaModel extends Model
{

    protected $table      = 'noticiacategoria';
    protected $primaryKey = 'idnoticiacategoria';

    protected $useAutoIncrement = true;

    protected $returnType     = NoticiaCategoriaEntity::class;
    protected $useSoftDeletes = false;

    protected $allowedFields = [
        'idestado',
        'nombre',
        'urlamigable',
        'descripcionseo',
        'orden',
        'fecha',
    ];

    protected $useTimestamps = false;
    protected $createdField  = 'fecha';

    protected $validationRules    = [];
    protected $validationMessages = [];
    protected $skipValidation     = false;

    public function obtenerPorId($idnoticiacategoria)
    {
        return $this->where('idnoticiacategoria', $idnoticiacategoria)->first();
    }
    public function obtenerPorIdNoticia($idnoticiacategoria)
    {
        $builder = $this->db->table('noticia');
        $builder->where('idnoticiacategoria', $idnoticiacategoria);

        return $builder->countAllResults(); // ← cuenta y retorna directamente
    }



    public function obtenerPorUrlAmigable($urlamigable)
    {
        return $this->where('urlamigable', $urlamigable)->first();
    }



    public function eliminar($idnoticiacategoria): bool
    {
        $this->db->transStart();
        try {
            if (!$this->where('idnoticiacategoria', $idnoticiacategoria)->first()) {
                return false;
            }

            $resultado = $this->delete($idnoticiacategoria);

            $this->db->transComplete();
            return $resultado;
        } catch (\Throwable $e) {
            $this->db->transRollback();
            log_message('error', 'Eliminar noticiacategoria falló: ' . $e->getMessage());
            return false;
        }
    }

    public function guardar($data): int
    {
        $this->db->transStart();
        try {
            if (empty($data['idnoticiacategoria'])) {
                $this->insert($data);
                $id = $this->getInsertID();
            } else {
                $this->update($data['idnoticiacategoria'], $data);
                $id = $data['idnoticiacategoria'];
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

    public function buscarPor($ordencriterio = '', $ordentipo = '', $parametro = '', $valor = '', $idestado = 0, $inicio = 0, $registros = 0)
    {
        $builder = $this->db->table($this->table);
        $builder->select('*');

        // Filtro por búsqueda
        if (!empty($parametro) && !empty($valor)) {
            $builder->like($parametro, trim($valor), 'both');
        }

        if ($idestado != 0)
            $builder->where('idestado', $idestado);

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

    public function buscarPorTotal($parametro = '', $valor = '', $idestado = 0)
    {
        $builder = $this->db->table($this->table);
        $builder->select('COUNT(*) as total');
        // Filtro por búsqueda
        if (!empty($parametro) && !empty($valor)) {
            $builder->like($parametro, trim($valor), 'both');
        }

        if ($idestado != 0)
            $builder->where('idestado', $idestado);

        return $builder->countAllResults();
    }
}
