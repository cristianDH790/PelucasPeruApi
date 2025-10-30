<?php

namespace App\Models;

use App\Entities\MarcaEntity;
use App\Entities\ProductoBaseEntity;
use App\Entities\ProductoImagenEntity;
use CodeIgniter\Model;

class MarcaModel extends Model
{
    protected $table      = 'marca';
    protected $primaryKey = 'idmarca';

    protected $useAutoIncrement = true;

    protected $returnType     = MarcaEntity::class;
    protected $useSoftDeletes = false;

    protected $allowedFields = [
        'idestado',
        'nombre',
        'urlimagen',
        'descripcion',
        'contenido',
        'orden',
        'fecha'
    ];

    protected $useTimestamps = false;
    protected $createdField  = 'fecha';

    protected $validationRules    = [];
    protected $validationMessages = [];
    protected $skipValidation     = false;



    // Obtener curso por ID
    public  function obtenerPorId($idmarca)
    {
        return $this->where('idmarca', $idmarca)->first();
    }




    public function obtenerPorUrlAmigable($urlamigable)
    {
        return $this->where('urlamigable', $urlamigable)->first();
    }



    public function buscarPor($ordencriterio , $ordentipo , $parametro , $valor , $idestado , $inicio , $registros )
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


    public function buscarPorTotal($parametro , $valor ,  $idestado)
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



    public function eliminar($idmarca): bool
    {
        $this->db->transStart();
        try {
            if (!$this->where('idmarca', $idmarca)->first()) {
                return false;
            }

            $resultado = $this->delete($idmarca);

            $this->db->transComplete();
            return $resultado;
        } catch (\Throwable $e) {
            $this->db->transRollback();
            log_message('error', 'Eliminar marca base falló: ' . $e->getMessage());
            return false;
        }
    }

    public function guardar($data): int
    {
        $this->db->transStart();
        try {
            if (empty($data['idmarca'])) {
                $this->insert($data);
                $id = $this->getInsertID();
            } else {
                $this->update($data['idmarca'], $data);
                $id = $data['idmarca'];
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


    public function obtenerMarcaPorProductoBase($idProducto)
    {
        return $this->db->table('marca e')
            ->join('producto_marca ue', 'ue.idmarca = e.idmarca')
            ->where('ue.idproducto', $idProducto)
            ->get()
            ->getRow();
    }
}
