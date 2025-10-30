<?php

namespace App\Models;

use App\Entities\ProductoBaseEntity;
use App\Entities\ProductoImagenEntity;
use CodeIgniter\Model;

class ProductoImagenModel extends Model
{
    protected $table      = 'productoimagen';
    protected $primaryKey = 'idproductoimagen';

    protected $useAutoIncrement = true;

    protected $returnType     = ProductoImagenEntity::class;
    protected $useSoftDeletes = false;

    protected $allowedFields = [
        'idestado',
        'idproductocolor',
        'idproducto',
        'idpdestacado',
        'nombre',
        'orden',
        'urlimagen',
        'fecha'
    ];

    protected $useTimestamps = false;
    protected $createdField  = 'fecha';

    protected $validationRules    = [];
    protected $validationMessages = [];
    protected $skipValidation     = false;



    // Obtener curso por ID
    public  function obtenerPorId($idproductoImagen)
    {
        return $this->where('idproductoimagen', $idproductoImagen)->first();
    }




    public function obtenerPorUrlAmigable($urlamigable)
    {
        return $this->where('urlamigable', $urlamigable)->first();
    }



    public function buscarPor($ordencriterio, $ordentipo, $parametro, $valor, $idestado, $idproducto, $idproductocolor, $idptipo, $inicio, $registros)
    {
        $builder = $this->db->table($this->table);
        $builder->select('*');

        // Filtro por búsqueda
        if (!empty($parametro) && !empty($valor)) {
            $builder->like($parametro, trim($valor), 'both');
        }

        if ($idestado > 0)
            $builder->where('idestado', $idestado);

        if ($idproductocolor > 0)
            $builder->where('idproductocolor', $idproductocolor);
        if ($idproducto > 0)
            $builder->where('idproducto', $idproducto);
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


    public function buscarPorTotal($parametro, $valor,  $idestado, $idproducto, $idproductocolor, $idptipo)
    {
        $builder = $this->db->table($this->table);
        $builder->select('COUNT(*) as total');

        // Filtro por búsqueda
        if (!empty($parametro) && !empty($valor)) {
            $builder->like($parametro, trim($valor), 'both');
        }


        if ($idestado > 0)
            $builder->where('idestado', $idestado);

        if ($idproductocolor > 0)
            $builder->where('idproductocolor', $idproductocolor);
        if ($idproducto > 0)
            $builder->where('idproducto', $idproducto);
        if ($idptipo > 0)
            $builder->where('idptipo', $idptipo);


        return $builder->countAllResults();
    }



    public function eliminar($idproductoImagen): bool
    {
        $this->db->transStart();
        try {
            if (!$this->where('idproductoimagen', $idproductoImagen)->first()) {
                return false;
            }

            $resultado = $this->delete($idproductoImagen);

            $this->db->transComplete();
            return $resultado;
        } catch (\Throwable $e) {
            $this->db->transRollback();
            log_message('error', 'Eliminar producto base falló: ' . $e->getMessage());
            return false;
        }
    }

    public function guardar($data): int
    {
        $this->db->transStart();
        try {
            if (empty($data['idproductoimagen'])) {
                $this->insert($data);
                $id = $this->getInsertID();
            } else {
                $this->update($data['idproductoimagen'], $data);
                $id = $data['idproductoimagen'];
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
