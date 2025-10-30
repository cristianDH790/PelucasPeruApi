<?php

namespace App\Models;

use App\Entities\CuponEntity;
use App\Entities\ProductoBaseEntity;
use App\Entities\ProductoImagenEntity;
use CodeIgniter\Model;

class CuponModel extends Model
{
    protected $table      = 'cupon';
    protected $primaryKey = 'idcupon';

    protected $useAutoIncrement = true;

    protected $returnType     = ProductoImagenEntity::class;
    protected $useSoftDeletes = false;

    protected $allowedFields = [
        'idestado',
        'idptipo',
        'codigo',
        'nombre',
        'limite',
        'descuento',
        'inicio',
        'termino',
        'fecha'
    ];

    protected $useTimestamps = false;
    protected $createdField  = 'fecha';

    protected $validationRules    = [];
    protected $validationMessages = [];
    protected $skipValidation     = false;



    // Obtener curso por ID
    public  function obtenerPorId($idcupon)
    {
        return $this->where('idcupon', $idcupon)->first();
    }
    public static function cuponByCodigo($codigo, $idcupon, $idestado)
    {
        $cupon = new CuponModel();
        $cupon->where('codigo', $codigo);

        if ($idcupon > 0)
            $cupon->where('idcupon !=', $idcupon);
        if ($idestado > 0)
            $cupon->where('idestado', $idestado);

        return $cupon->first();
    }
    public function obtenerPorProducto(int $idProducto): array
    {
        $builder = $this->db->table('producto_cupon cp')
            ->select('c.idcupon, c.nombre, c.codigo, c.descuento, c.inicio, c.termino, c.idestado')
            ->join('cupon c', 'c.idcupon = cp.idcupon')
            ->where('cp.idproducto', $idProducto);

        $cuponesRaw = $builder->get()->getResultArray();

        // Convertir cada resultado en una entidad CuponEntity
        $cupones = array_map(fn($c) => (new CuponEntity($c))->toArray(), $cuponesRaw);

        return $cupones;
    }

    public function eliminarAsociacion(int $idProducto, int $idCupon): bool
    {
        return (bool) $this->db->table('producto_cupon')
            ->where('idproducto', $idProducto)
            ->where('idcupon', $idCupon)
            ->delete();
    }


    public function verificarCupon($idcupon, $idproducto)
    {

        $builder = $this->db->table('producto_cupon');
        $builder->select('*');
        $builder->where('idcupon', $idcupon);
        $builder->where('idproducto', $idproducto);
        return $builder->get()->getRow();
    }

    //asociarCuponAProducto

    public function asociarCuponAProducto($idcupon, $idproducto)
    {
        $data = [
            'idcupon' => $idcupon,
            'idproducto' => $idproducto
        ];
        $this->db->table('producto_cupon')->insert($data);
        return $this->db->insertID();
    }

    //eliminar asociacion de cupon a producto
    public function eliminarAsociacionCuponProducto($idcupon)
    {
        $builder = $this->db->table('producto_cupon');
        $builder->where('idcupon', $idcupon);
        return $builder->delete();
    }



    //listar cupones asociados a un producto
    public function listarCuponesAsociados($idproducto)
    {
        $builder = $this->db->table('producto_cupon pc');
        $builder->select('c.*');
        $builder->join('cupon c', 'pc.idcupon = c.idcupon');
        $builder->where('pc.idproducto', $idproducto);
        return $builder->get()->getResult();
    }


    public function obtenerPorUrlAmigable($urlamigable)
    {
        return $this->where('urlamigable', $urlamigable)->first();
    }



    public function buscarPor($ordencriterio = '', $ordentipo = '', $parametro = '', $valor = '', $idestado = 0,  $idptipo = 0, $inicio = null, $registros = null)
    {
        $builder = $this->db->table($this->table);
        $builder->select('*');

        // Filtro por búsqueda
        if (!empty($parametro) && !empty($valor)) {
            $builder->like($parametro, trim($valor), 'both');
        }

        if ($idestado > 0)
            $builder->where('idestado', $idestado);


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


    public function buscarPorTotal($parametro = '', $valor = '',  $idestado = 0,  $idptipo = 0)
    {
        $builder = $this->db->table($this->table);
        $builder->select('COUNT(*) as total');

        // Filtro por búsqueda
        if (!empty($parametro) && !empty($valor)) {
            $builder->like($parametro, trim($valor), 'both');
        }


        if ($idestado > 0)
            $builder->where('idestado', $idestado);


        if ($idptipo > 0)
            $builder->where('idptipo', $idptipo);


        return $builder->countAllResults();
    }



    public function eliminar($idcupon): bool
    {
        $this->db->transStart();
        try {
            if (!$this->where('idcupon', $idcupon)->first()) {
                return false;
            }

            $resultado = $this->delete($idcupon);

            $this->db->transComplete();
            return $resultado;
        } catch (\Throwable $e) {
            $this->db->transRollback();
            log_message('error', 'Eliminar cupon falló: ' . $e->getMessage());
            return false;
        }
    }

    public function guardar($data): int
    {
        $this->db->transStart();
        try {
            if (empty($data['idcupon'])) {
                $this->insert($data);
                $id = $this->getInsertID();
            } else {
                $this->update($data['idcupon'], $data);
                $id = $data['idcupon'];
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


    //total de cupones asociado a un producto x id cupon
    public function totalCuponesPorProducto($idcupon)
    {
        $builder = $this->db->table('producto_cupon');
        $builder->select('COUNT(*) as total');
        $builder->where('idcupon', $idcupon);
        $row = $builder->get()->getRow();
        return $row ? (int)$row->total : 0;
    }
}
