<?php

namespace App\Models;

use App\Entities\EmpresaEntity;
use App\Entities\Noticia;
use App\Entities\NoticiaEntity;
use App\Entities\ProductoBaseEntity;
use App\Entities\ProductoCategoriaEntity;
use CodeIgniter\Model;

class ProductoCategoriaModel extends Model
{
    protected $table      = 'productocategoria';
    protected $primaryKey = 'idproductocategoria';

    protected $useAutoIncrement = true;

    protected $returnType     = ProductoCategoriaEntity::class;
    protected $useSoftDeletes = false;

    protected $allowedFields = [
        'idestado',
        'nombre',
        'idrproductocategoria',
        'contenido',
        'urlamigable',
        'urlimagen',
        'urlimagenbanner',
        'orden',
        'fecha'
    ];

    protected $useTimestamps = false;
    protected $createdField  = 'fecha';

    protected $validationRules    = [];
    protected $validationMessages = [];
    protected $skipValidation     = false;



    // Obtener curso por ID
    public  function obtenerPorId($idproductocategoria)
    {
        return $this->where('idproductocategoria', $idproductocategoria)->first();
    }




    public function obtenerPorUrlAmigable($urlamigable)
    {
        return $this->where('urlamigable', $urlamigable)->first();
    }

    public function obtenerCadenaConCategoria($categoriaActual)
    {
        if (!$categoriaActual) {
            return null;
        }

        $idsVisitados = [];

        // Clonamos para no modificar el objeto original si quieres evitar efectos secundarios
        $objActual = clone $categoriaActual;

        $idsVisitados[] = $objActual->idproductocategoria;

        $padre = null;
        $actual = $objActual;

        while ($actual->idrproductocategoria) {
            if (in_array($actual->idrproductocategoria, $idsVisitados)) {
                // Evita bucles infinitos
                break;
            }

            $padre = $this->obtenerPorId($actual->idrproductocategoria);
            if ($padre) {
                $padre = clone $padre; // clonamos para evitar referencias compartidas
                $actual->rproductocategoria = $padre;
                $idsVisitados[] = $padre->idproductocategoria;
                $actual = $padre;
            } else {
                break;
            }
        }

        return $objActual; // objeto con anidamiento en rproductocategoria
    }





    public function buscarPor($ordencriterio, $ordentipo, $parametro, $valor, $idestado, $idproductocategoria, $idrproductocategoria, $inicio, $registros)
    {
        $builder = $this->db->table('productocategoria pc');
        $builder->select('pc.*, COUNT(p.idproducto) AS num_producto');
        $builder->join('producto p', 'p.idproductocategoria = pc.idproductocategoria', 'left');

        // Filtro por búsqueda (en la tabla de categorías)
        if (!empty($parametro) && !empty($valor)) {
            $builder->like("pc.$parametro", trim($valor), 'both');
        }

        // Filtro por estado (de la categoría)
        if ($idestado > 0) {
            $builder->where('pc.idestado', $idestado);
        }
        if ($idrproductocategoria > 0) {
            $builder->where('pc.idrproductocategoria', $idrproductocategoria);
        }
        if ($idproductocategoria > 0) {
            $builder->where('pc.idproductocategoria', $idproductocategoria);
        }

        // Agrupamiento para contar productos por categoría
        $builder->groupBy('pc.idproductocategoria');

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

    // public function buscarPor($ordencriterio , $ordentipo , $parametro , $valor , $idestado,  $inicio , $registros )
    // {
    //     $builder = $this->db->table($this->table);
    //     $builder->select('*');

    //     // Filtro por búsqueda
    //     if (!empty($parametro) && !empty($valor)) {
    //         $builder->like($parametro, trim($valor), 'both');
    //     }

    //     if ($idestado > 0)
    //         $builder->where('idestado', $idestado);



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


    public function buscarPorTotal($parametro, $valor,  $idestado, $idproductocategoria, $idrproductocategoria,)
    {
        $builder = $this->db->table($this->table);
        $builder->select('COUNT(*) as total');

        // Filtro por búsqueda
        if (!empty($parametro) && !empty($valor)) {
            $builder->like($parametro, trim($valor), 'both');
        }


        if ($idestado > 0)
            $builder->where('idestado', $idestado);
        if ($idrproductocategoria > 0) {
            $builder->where('pc.idrproductocategoria', $idrproductocategoria);
        }
        if ($idproductocategoria > 0) {
            $builder->where('pc.idproductocategoria', $idproductocategoria);
        }


        return $builder->countAllResults();
    }



    public function eliminar($idproductocategoria): bool
    {
        $this->db->transStart();
        try {
            if (!$this->where('idproductocategoria', $idproductocategoria)->first()) {
                return false;
            }

            $resultado = $this->delete($idproductocategoria);

            $this->db->transComplete();
            return $resultado;
        } catch (\Throwable $e) {
            $this->db->transRollback();
            log_message('error', 'Eliminar producto categoria falló: ' . $e->getMessage());
            return false;
        }
    }

    public function guardar($data): int
    {
        $this->db->transStart();
        try {
            if (empty($data['idproductocategoria'])) {
                $this->insert($data);
                $id = $this->getInsertID();
            } else {
                $this->update($data['idproductocategoria'], $data);
                $id = $data['idproductocategoria'];
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
