<?php

namespace App\Models;

use App\Entities\EmpresaEntity;
use App\Entities\Noticia;
use App\Entities\NoticiaEntity;
use App\Entities\ProductoBaseEntity;
use App\Entities\ProductoEntity;
use CodeIgniter\Model;

class ProductooModel extends Model
{
    protected $table      = 'producto';
    protected $primaryKey = 'idproducto';

    protected $useAutoIncrement = true;

    protected $returnType     = ProductoEntity::class;
    protected $useSoftDeletes = false;

    protected $allowedFields = [
        'idestado',
        'idproductobase',
        'idempresa',
        'stock',
        'fecha'
    ];

    protected $useTimestamps = false;
    protected $createdField  = 'fecha';

    protected $validationRules    = [];
    protected $validationMessages = [];
    protected $skipValidation     = false;



    // Obtener curso por ID
    public  function obtenerPorId($idproducto)
    {
        return $this->where('idproducto', $idproducto)->first();
    }




    public function obtenerPorUrlAmigable($urlamigable)
    {
        return $this->where('urlamigable', $urlamigable)->first();
    }



    public function buscarPor($ordencriterio = '', $ordentipo = '', $parametro = '', $valor = '', $idestado = 0, $idempresa = 0, $idproductobase = 0,   $inicio = null, $registros = null)
    {
        $builder = $this->db->table($this->table);
        $builder->select('*');

        // Filtro por búsqueda
        if (!empty($parametro) && !empty($valor)) {
            $builder->like($parametro, trim($valor), 'both');
        }

        if ($idestado > 0)
            $builder->where('idestado', $idestado);
        if ($idproductobase > 0)
            $builder->where('idproductobase', $idproductobase);
        if ($idempresa > 0)
            $builder->where('idempresa', $idempresa);



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




    public function buscarPorTotal($parametro = '', $valor = '',  $idestado = 0, $idempresa = 0, $idproductobase = 0)
    {
        $builder = $this->db->table($this->table);
        $builder->select('COUNT(*) as total');

        // Filtro por búsqueda
        if (!empty($parametro) && !empty($valor)) {
            $builder->like($parametro, trim($valor), 'both');
        }


        if ($idestado > 0)
            $builder->where('idestado', $idestado);
        if ($idproductobase > 0)
            $builder->where('idproductobase', $idproductobase);
        if ($idempresa > 0)
            $builder->where('idempresa', $idempresa);




        return $builder->countAllResults();
    }



    public function eliminar($idproducto): bool
    {
        $this->db->transStart();
        try {
            if (!$this->where('idproducto', $idproducto)->first()) {
                return false;
            }

            $resultado = $this->delete($idproducto);

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
            if (empty($data['idproducto'])) {
                $this->insert($data);
                $id = $this->getInsertID();
            } else {
                $this->update($data['idproducto'], $data);
                $id = $data['idproducto'];
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
    // public function guardar($data)
    // {
    //     $this->db->transStart();

    //     try {
    //         // 1. Guardar en PRODUCTOBASE
    //         $datosProductoBase = [
    //             'idestado' => $data['estado']['idEstado'] ?? null,
    //             'idproductocategoria' => $data['productoCategoria']['idProductoCategoria'] ?? null,
    //             'idpromocion' => $data['promocion']['idParametro'] ?? null,
    //             'idpdestacado' => $data['pDestacado']['idParametro'] ?? null,
    //             'codigo' => $data['codigo'] ?? null,
    //             'nombre' => $data['nombre'] ?? null,
    //             'urlamigable' => $data['urlAmigable'] ?? null,
    //             'resumen' => $data['resumen'] ?? null,
    //             'descripcionseo' => $data['descripcionSeo'] ?? null,
    //             'descripcion' => $data['descripcion'] ?? null,
    //             'urlimagen' => $data['urlImagen'] ?? null,
    //             'preciolista' => $data['precioLista'] ?? null,
    //             'precioventa' => $data['precioVenta'] ?? null,
    //             'peso' => $data['peso'] ?? null,
    //             'fechapublicacion' => $data['fechaPublicacion'] ?? null,
    //             'fecha' => $data['fecha'] ?? null,
    //         ];

    //         if (!empty($data['idproductoBase'])) {
    //             $datosProductoBase['idproductoBase'] = $data['idproductoBase'];
    //         }

    //         $productobaseId = $this->productobase->guardar($datosProductoBase);

    //         // // 2. Guardar en PRODUCTO
    //         // $productoData = [
    //         //     'idestado' => $data['estado']['idEstado'] ?? null,
    //         //     'idproductobase' => $productobaseId,
    //         //     'idempresa' => $data['producto']['idempresa'] ?? null,
    //         //     'stock' => $data['producto']['stock'] ?? 0,

    //         // ];

    //         // $this->producto->insert($productoData);

    //         // 3. Guardar en PRODUCTOBASE_MARCA
    //         $marcaId = $data['marca']['idMarca'] ?? null;
    //         if ($marcaId) {
    //             $this->db->table('productobase_marca')->insert([
    //                 'idproductobase' => $productobaseId,
    //                 'idmarca' => $marcaId
    //             ]);
    //         }

    //         $this->db->transComplete();

    //         if ($this->db->transStatus() === false) {
    //             throw new \Exception('Error al guardar el producto');
    //         }

    //         return $this->respond(['mensaje' => 'Guardado correctamente', 'id' => $productobaseId]);
    //     } catch (\Throwable $e) {
    //         $this->db->transRollback();
    //         log_message('error', 'Error en guardarProductoCompleto: ' . $e->getMessage());
    //         return $this->failServerError('Error al guardar: ' . $e->getMessage());
    //     }
    // }
    // public function guardar($data)
    // {
    //     $this->db->transStart();

    //     try {
    //         // Guardar en PRODUCTOBASE
    //         $datosProductoBase = [
    //             'idestado' => $data['idestado'] ?? null,
    //             'idproductocategoria' => $data['idproductocategoria'] ?? null,
    //             'idpromocion' => $data['idpromocion'] ?? null,
    //             'idpdestacado' => $data['idpdestacado'] ?? null,
    //             'codigo' => $data['codigo'] ?? null,
    //             'nombre' => $data['nombre'] ?? null,
    //             'urlamigable' => $data['urlamigable'] ?? null,
    //             'resumen' => $data['resumen'] ?? null,
    //             'descripcionseo' => $data['descripcionseo'] ?? null,
    //             'descripcion' => $data['descripcion'] ?? null,
    //             'urlimagen' => $data['urlimagen'] ?? null,
    //             'preciolista' => $data['preciolista'] ?? null,
    //             'precioventa' => $data['precioventa'] ?? null,
    //             'peso' => $data['peso'] ?? null,
    //             'fechapublicacion' => $data['fechapublicacion'] ?? null,

    //         ];

    //         // Verifica si es actualización o inserción
    //         if (!empty($data['idproductobase'])) {
    //             $this->update($data['idproductobase'], $datosProductoBase);
    //             $productobaseId = $data['idproductobase'];
    //         } else {
    //             $this->insert($datosProductoBase);
    //             $productobaseId = $this->getInsertID();
    //         }

    //         // Guardar en PRODUCTOBASE_MARCA
    //         $marcaId = $data['idmarca'] ?? null;
    //         if ($marcaId) {
    //             // Elimina la relación previa si es actualización
    //             if (!empty($data['idproductobase'])) {
    //                 $this->db->table('productobase_marca')
    //                     ->where('idproductobase', $productobaseId)
    //                     ->delete();
    //             }

    //             $this->db->table('productobase_marca')->insert([
    //                 'idproductobase' => $productobaseId,
    //                 'idmarca' => $marcaId
    //             ]);
    //         }


    //         $this->db->transComplete();

    //         if ($this->db->transStatus() === false) {
    //             throw new \Exception('Error al guardar el producto base');
    //         }

    //         return $productobaseId;
    //     } catch (\Throwable $e) {
    //         $this->db->transRollback();
    //         log_message('error', 'Error en guardarProductoBase: ' . $e->getMessage());
    //         throw $e;
    //     }
    // }

    public function contarProductosPorEmpresa($idempresa): int
    {
        return $this->db->table('producto')
            ->where('idempresa', $idempresa)
            ->countAllResults();
    }
}
