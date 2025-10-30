<?php

namespace App\Models;

use App\Entities\ProductoBaseEntity;
use App\Entities\PromocionEntity;
use CodeIgniter\Model;

class PromocionModel extends Model
{
    protected $table      = 'promocion';
    protected $primaryKey = 'idpromocion';

    protected $useAutoIncrement = true;

    protected $returnType     = PromocionEntity::class;
    protected $useSoftDeletes = false;

    protected $allowedFields = [
        'idestado',
        'nombre',
        'urlamigable',
        'resumen',
        'contenido',
        'urlminiatura',
        'urlimagen',
        'urlredireccion',
        'terminos',
        'fechainicio',
        'fechafin',
        'accesos',
        'fecha'
    ];

    protected $useTimestamps = false;
    protected $createdField  = 'fecha';

    protected $validationRules    = [];
    protected $validationMessages = [];
    protected $skipValidation     = false;



    // Obtener curso por ID
    public  function obtenerPorId($idpromocion)
    {
        return $this->where('idpromocion', $idpromocion)->first();
    }




    public function obtenerPorUrlAmigable($urlamigable)
    {
        return $this->where('urlamigable', $urlamigable)->first();
    }

   

    public function buscarPor($ordencriterio = '', $ordentipo = '', $parametro = '', $valor = '', $idestado = 0, $inicio = null, $registros = null)
    {
        $builder = $this->db->table($this->table);
        $builder->select('*');

          // Filtro por búsqueda
        if (!empty($parametro) && !empty($valor)) {
            $builder->like($parametro, trim($valor), 'both');
        }

        if ($idestado > 0)
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


        if ($idestado > 0)
            $builder->where('idestado', $idestado);


        return $builder->countAllResults();
    }



     public function eliminar($idpromocion): bool
    {
        $this->db->transStart();
        try {
            if (!$this->where('idpromocion', $idpromocion)->first()) {
                return false;
            }

            $resultado = $this->delete($idpromocion);

            $this->db->transComplete();
            return $resultado;
        } catch (\Throwable $e) {
            $this->db->transRollback();
            log_message('error', 'Eliminar promocion falló: ' . $e->getMessage());
            return false;
        }
    }

    public function guardar($data): int
    {
        $this->db->transStart();
        try {
            if (empty($data['idpromocion'])) {
                $this->insert($data);
                $id = $this->getInsertID();
            } else {
                $this->update($data['idpromocion'], $data);
                $id = $data['idpromocion'];
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
