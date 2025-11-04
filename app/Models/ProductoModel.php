<?php

namespace App\Models;


use App\Entities\ProductoEntity;
use CodeIgniter\Model;

class ProductoModel extends Model
{
    protected $table      = 'producto';
    protected $primaryKey = 'idproducto';

    protected $useAutoIncrement = true;

    protected $returnType     = ProductoEntity::class;
    protected $useSoftDeletes = false;

    protected $allowedFields = [
        'idestado',
        'idproductocategoria',
        'idpdestacado',
        'idppromocion',        // corregido a 'idppromocion' según tabla (no 'idpromocion')
        'idpcomplemento',        // corregido a 'idppromocion' según tabla (no 'idpromocion')
        'idpajuste',        // corregido a 'idppromocion' según tabla (no 'idpromocion')
        'idplongitud',        // corregido a 'idppromocion' según tabla (no 'idpromocion')
        'idpcontrolstock',        // corregido a 'idppromocion' según tabla (no 'idpromocion')
        'idmarca',
        'idcolor',
        'codigo',
        'nombre',
        'urlamigable',
        'resumen',
        'resumen2',
        'contenido',           // reemplaza 'descripcionseo' y 'descripcion' por 'contenido' si así es el campo correcto
        'stock',               // agregado para que esté permitido si usas ese campo
        'preciolista',
        'precioventa',
        'peso',
        'orden',
        'compraxcliente',      // agregado para permitir ese campo
        'fechapublicacion',
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

    public function obtenerPorIdPublico($idproducto)
    {
        // 📌 1. Traer producto principal
        $producto = $this->where('idproducto', $idproducto)->first();

        if ($producto) {

            // 📌 2. Subconsulta para traer imagen destacada por producto (no por productocolor)
            $subQuery = "
            SELECT idproducto, urlimagen
            FROM (
                SELECT 
                    pi.idproducto,
                    pi.urlimagen,
                    ROW_NUMBER() OVER (
                        PARTITION BY pi.idproducto 
                        ORDER BY 
                            CASE WHEN pi.idpdestacado = 1 THEN 0 ELSE 1 END,
                            pi.orden ASC,
                            pi.idproductoimagen ASC
                    ) AS rn
                FROM productoimagen pi
            ) AS imagenes
            WHERE rn = 1
        ";

            // 📌 3. Builder principal para complementos con imagen destacada (relacionados con producto)
            $builder = $this->db->table('producto_complemento pc');
            $builder->select('p.*, img.urlimagen AS urlimagen');
            $builder->join('producto p', 'p.idproducto = pc.idcomplemento', 'inner');
            // Eliminamos el join con productocolor porque ya no se usa para imagenes
            $builder->join("($subQuery) img", 'img.idproducto = p.idproducto', 'left', false);
            $builder->where('pc.idproducto', $idproducto);

            $complementos = $builder->get()->getResult();

            // 📌 4. Agregamos los complementos al objeto producto
            $producto->complementos = $complementos;
        }

        return $producto;
    }


    public function obtenerPorUrlAmigable($urlamigable)
    {
        // Subconsulta para imagen destacada por producto
        $subQuery = "
    SELECT idproducto, urlimagen
    FROM (
        SELECT 
            pi.idproducto,
            pi.urlimagen,
            ROW_NUMBER() OVER (
                PARTITION BY pi.idproducto 
                ORDER BY 
                    CASE WHEN pi.idpdestacado = 1 THEN 0 ELSE 1 END,
                    pi.orden ASC,
                    pi.idproductoimagen ASC
            ) AS rn
        FROM productoimagen pi
    ) AS imagenes
    WHERE rn = 1
   ";

        $builder = $this->db->table('producto p');
        $builder->select('p.*, img.urlimagen AS urlimagen');
        $builder->join("($subQuery) img", 'img.idproducto = p.idproducto', 'left', false);
        $builder->where('p.urlamigable', $urlamigable);

        $producto = $builder->get()->getRow();

        if ($producto) {
            // Obtener todas las imágenes ordenadas para el producto
            $builderImgs = $this->db->table('productoimagen pi');
            $builderImgs->select('pi.urlimagen');
            $builderImgs->where('pi.idproducto', $producto->idproducto);
            $builderImgs->orderBy('pi.idpdestacado DESC, pi.orden ASC, pi.idproductoimagen ASC');
            $imagenes = $builderImgs->get()->getResult();

            // Asignar la segunda imagen si existe
            $producto->urlimagen2 = isset($imagenes[1]) ? $imagenes[1]->urlimagen : null;

            // Complementos con imagen destacada
            $subQueryComplementos = "
        SELECT idproducto, urlimagen
        FROM (
            SELECT 
                pi.idproducto,
                pi.urlimagen,
                ROW_NUMBER() OVER (
                    PARTITION BY pi.idproducto 
                    ORDER BY 
                        CASE WHEN pi.idpdestacado = 1 THEN 0 ELSE 1 END,
                        pi.orden ASC,
                        pi.idproductoimagen ASC
                ) AS rn
            FROM productoimagen pi
        ) AS imagenes
        WHERE rn = 1
     ";

            $builderComp = $this->db->table('producto_complemento pc');
            $builderComp->select('p.*, img.urlimagen AS urlimagen');
            $builderComp->join('producto p', 'p.idproducto = pc.idcomplemento', 'inner');
            $builderComp->join("($subQueryComplementos) img", 'img.idproducto = p.idproducto', 'left', false);
            $builderComp->where('pc.idproducto', $producto->idproducto);

            $producto->complementos = $builderComp->get()->getResult();

            // Obtener la categoría completa del producto con join
            $builderCat = $this->db->table('productocategoria pc');
            $builderCat->select('pc.*');
            $builderCat->where('pc.idproductocategoria', $producto->idproductocategoria);

            $categoria = $builderCat->get()->getRow();

            $producto->categoria = $categoria ? $categoria : null;
        }

        return $producto;
    }

    public function buscarPor(
        $ordencriterio,
        $ordentipo,
        $parametro,
        $valor,
        $idestado,
        $idproductocategoria,
        $idrproductocategoria,
        $idpdestacado,
        $idpcomplemento,
        $idcupon,
        $idcolor,
        $inicio,
        $registros
    ) {
        $builder = $this->db->table($this->table . ' p');

        $builder->distinct();
        $builder->select('
        p.idproducto,
        p.*,
        pi.urlimagen,
        pi.urlimagen2,
        pc.urlamigable AS categoria_urlamigable
      ');

        // JOIN con categoría
        $builder->join('productocategoria pc', 'pc.idproductocategoria = p.idproductocategoria', 'left');

        // Subconsulta para imágenes
        $subQuerySql = "
        SELECT 
            ordenadas.idproducto,
            MAX(CASE WHEN rn = 1 THEN ordenadas.urlimagen END) AS urlimagen,
            MAX(CASE WHEN rn = 2 THEN ordenadas.urlimagen END) AS urlimagen2
        FROM (
            SELECT 
                pi.idproducto,
                pi.urlimagen,
                ROW_NUMBER() OVER (
                    PARTITION BY pi.idproducto 
                    ORDER BY 
                        CASE WHEN pi.idpdestacado = 568 THEN 0 ELSE 1 END,
                        pi.orden ASC,
                        pi.idproductoimagen ASC
                ) AS rn
            FROM productoimagen pi
        ) AS ordenadas
        WHERE rn <= 2
        GROUP BY ordenadas.idproducto
     ";

        $builder->join("({$subQuerySql}) pi", 'pi.idproducto = p.idproducto', 'left', false);

        // JOIN con productoimagen y cupon solo si hay filtro de cupón
        if ($idcupon > 0) {
            $builder->join('productoimagen pi_cupon', 'pi_cupon.idproducto = p.idproducto', 'left');
            $builder->join(
                'producto_cupon pcupon',
                'pcupon.idproducto = pi_cupon.idproducto',
                'inner'
            );
            $builder->where('pcupon.idcupon', $idcupon);
        }

        // Filtros
        if (!empty($parametro) && !empty($valor)) {
            $builder->like('p.' . $parametro, trim($valor), 'both');
        }

        if ($idestado > 0) {
            $builder->where('p.idestado', $idestado);
        }

        if ($idproductocategoria > 0) {
            $builder->where('p.idproductocategoria', $idproductocategoria);
        }

        if ($idrproductocategoria > 0) {
            $builder->where('pc.idrproductocategoria', $idrproductocategoria);
        }

        // if ($idpcomplemento > 0) {
        //     $builder->where('p.idpcomplemento', $idpcomplemento);
        // }
        // Filtro por complementos
        if (!empty($idpcomplemento)) {
            if (is_array($idpcomplemento)) {
                $builder->whereIn('p.idpcomplemento', $idpcomplemento);
            } else {
                $builder->where('p.idpcomplemento', $idpcomplemento);
            }
        }


        if ($idpdestacado > 0) {
            $builder->where('p.idpdestacado', $idpdestacado);
        }

        // Filtro por color en la tabla producto (p)
        if ($idcolor > 0) {
            $builder->where('p.idcolor', $idcolor);
        }

        // Orden
        if (!empty($ordencriterio) && !empty($ordentipo)) {
            $builder->orderBy('p.' . $ordencriterio, $ordentipo);
        }

        // Paginación
        if ($registros > 0) {
            $builder->limit($registros, $inicio);
        }

        return $builder->get()->getResult();
    }


    public function buscarPorTotal(
        $parametro,
        $valor,
        $idestado,
        $idproductocategoria,
        $idrproductocategoria,
        $idpdestacado,
        $idpcomplemento,
        $idcupon,
        $idcolor
    ) {
        $builder = $this->db->table($this->table . ' p');
        $builder->select('COUNT(DISTINCT p.idproducto) as total');

        $builder->join('productocategoria pc', 'pc.idproductocategoria = p.idproductocategoria', 'left');

        // JOIN con productoimagen y cupon solo si hay filtro de cupón
        if ($idcupon > 0) {
            $builder->join('productoimagen pi', 'pi.idproducto = p.idproducto', 'left');
            $builder->join(
                'producto_cupon pcupon',
                'pcupon.idproducto = pi.idproducto',
                'inner'
            );
            $builder->where('pcupon.idcupon', $idcupon);
        }

        if (!empty($parametro) && !empty($valor)) {
            $builder->like('p.' . $parametro, trim($valor), 'both');
        }

        if ($idestado > 0) {
            $builder->where('p.idestado', $idestado);
        }

        if ($idproductocategoria > 0) {
            $builder->where('p.idproductocategoria', $idproductocategoria);
        }

        if ($idrproductocategoria > 0) {
            $builder->where('pc.idrproductocategoria', $idrproductocategoria);
        }

        // if ($idpcomplemento > 0) {
        //     $builder->where('p.idpcomplemento', $idpcomplemento);
        // }

        // Filtro por complementos
        if (!empty($idpcomplemento)) {
            if (is_array($idpcomplemento)) {
                $builder->whereIn('p.idpcomplemento', $idpcomplemento);
            } else {
                $builder->where('p.idpcomplemento', $idpcomplemento);
            }
        }


        if ($idpdestacado > 0) {
            $builder->where('p.idpdestacado', $idpdestacado);
        }

        // Filtro por color en la tabla producto (p)
        if ($idcolor > 0) {
            $builder->where('p.idcolor', $idcolor);
        }

        return $builder->get()->getRow()->total;
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

    public function guardar($data)
    {
        $this->db->transStart();

        try {

            if (!empty($data['idproducto'])) {
                $this->update($data['idproducto'], $data);
                $productoId = $data['idproducto'];
            } else {
                $this->insert($data);
                $productoId = $this->getInsertID();
            }
            $this->db->transComplete();

            if ($this->db->transStatus() === false) {
                throw new \Exception('Error al guardar el producto');
            }

            return $productoId;
        } catch (\Throwable $e) {
            $this->db->transRollback();
            log_message('error', 'Error en guardar Producto: ' . $e->getMessage());
            throw $e;
        }
    }
}
