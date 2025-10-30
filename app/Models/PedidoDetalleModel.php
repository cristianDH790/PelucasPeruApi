<?php

namespace App\Models;

use App\Entities\EmpresaEntity;
use App\Entities\Noticia;
use App\Entities\NoticiaEntity;
use App\Entities\PedidoEntity;
use App\Entities\ProductoBaseEntity;
use App\Entities\ProductoEntity;
use CodeIgniter\Model;

class PedidoDetalleModel extends Model
{
    protected $table      = 'pedidodetalle';
    protected $primaryKey = 'idpedidodetalle';

    protected $useAutoIncrement = true;

    protected $returnType     = PedidoEntity::class;
    protected $useSoftDeletes = false;

    protected $allowedFields = [
        'idestado',
        'idpedido',
        'idproducto',
        'cantidad',
        'peso',
        'precio',
        'descuento',
        'total',


    ];

    protected $useTimestamps = false;
    protected $createdField  = 'fecha';

    protected $validationRules    = [];
    protected $validationMessages = [];
    protected $skipValidation     = false;

    public function eliminarPorIdPedido($idPedido)
    {
        return $this->where('idpedido', $idPedido)->delete();
    }

    // public function pedidoDetalleFind($ordencriterio, $ordentipo, $parametro, $valor, $idpedido, $inicio, $registros)
    // {
    //     $builder = $this->db->table('pedidodetalle as p');

    //     $builder->where('p.idpedidodetalle >=', 1);

    //     if (!empty($parametro) && !empty($valor)) {
    //         $builder->like($parametro, $valor);
    //     }

    //     if ($idpedido > 0) {
    //         $builder->where('p.idpedido', $idpedido);
    //     }

    //     if (!empty($ordencriterio) && !empty($ordentipo)) {
    //         $builder->orderBy($ordencriterio, $ordentipo);
    //     }

    //     if ($inicio >= 0 && $registros > 0) {
    //         $builder->limit($registros, $inicio);
    //     }

    //     return $builder->get()->getResult(); // Devuelve array de objetos
    // }
    // public function pedidoDetalleFind($ordencriterio, $ordentipo, $parametro, $valor, $idpedido, $inicio, $registros)
    // {
    //     $builder = $this->db->table('pedidodetalle as p');

    //     // Seleccionar todas las columnas de 'pedidodetalle', 'producto' y 'productoimagen'
    //     $builder->select('p.*, 
    //     producto.*, 
    //     productoimagen.*, 
    //     IFNULL((SELECT urlImagen FROM productoimagen WHERE idproducto = p.idproducto AND idpdestacado = 572 LIMIT 1), 
    //     (SELECT urlImagen FROM productoimagen WHERE idproducto = p.idproducto ORDER BY idproductoimagen LIMIT 1)) as urlImagen');

    //     // Filtros
    //     $builder->where('p.idpedidodetalle >=', 1);

    //     if (!empty($parametro) && !empty($valor)) {
    //         $builder->like($parametro, $valor);
    //     }

    //     if ($idpedido > 0) {
    //         $builder->where('p.idpedido', $idpedido);
    //     }

    //     // Ordenar
    //     if (!empty($ordencriterio) && !empty($ordentipo)) {
    //         $builder->orderBy($ordencriterio, $ordentipo);
    //     }

    //     // Paginación
    //     if ($inicio >= 0 && $registros > 0) {
    //         $builder->limit($registros, $inicio);
    //     }

    //     // Hacer JOIN con las tablas 'producto' y 'productoimagen'
    //     $builder->join('producto', 'producto.idproducto = p.idproducto', 'left');
    //     $builder->join('productoimagen', 'productoimagen.idproducto = p.idproducto', 'left');

    //     // Ejecutar la consulta y devolver el resultado
    //     return $builder->get()->getResult(); // Devuelve array de objetos
    // }
    public function pedidoDetalleFind($ordencriterio, $ordentipo, $parametro, $valor, $idpedido, $inicio, $registros)
    {
        $builder = $this->db->table('pedidodetalle as p');

        // Seleccionar las columnas necesarias
        $builder->select('p.*, 
    producto.urlamigable, 
    producto.nombre, 
    IFNULL((SELECT urlImagen FROM productoimagen WHERE idproducto = p.idproducto AND idpdestacado = 572 LIMIT 1), 
    (SELECT urlImagen FROM productoimagen WHERE idproducto = p.idproducto ORDER BY idproductoimagen LIMIT 1)) as urlImagen');

        // Filtros
        $builder->where('p.idpedidodetalle >=', 1);

        if (!empty($parametro) && !empty($valor)) {
            $builder->like($parametro, $valor);
        }

        if ($idpedido > 0) {
            $builder->where('p.idpedido', $idpedido);
        }

        // Ordenar
        if (!empty($ordencriterio) && !empty($ordentipo)) {
            $builder->orderBy($ordencriterio, $ordentipo);
        }

        // Paginación
        if ($inicio >= 0 && $registros > 0) {
            $builder->limit($registros, $inicio);
        }

        // Hacer JOIN con la tabla producto
        $builder->join('producto', 'producto.idproducto = p.idproducto', 'left');

        // Ejecutar la consulta y devolver el resultado
        return $builder->get()->getResult(); // Devuelve array de objetos
    }



    public function pedidoDetalleFindTotal($parametro, $valor, $idpedido)
    {
        $builder = $this->db->table('pedidodetalle as p');
        $builder->where('p.idpedidodetalle >=', 1);

        if (!empty($parametro) && !empty($valor)) {
            $builder->like($parametro, $valor);
        }

        if ($idpedido > 0) {
            $builder->where('p.idpedido', $idpedido);
        }

        return $builder->countAllResults();
    }

    public function pedidoDetalleByPedido($idpedido)
    {
        $builder = $this->db->table('pedidodetalle as pd');
        $builder->join('producto p', 'p.idproducto = pd.idproducto');
        $builder->where('pd.idpedido', $idpedido);

        $builder->select('pd.*, p.nombre, p.codigo');

        return $builder->get()->getResult();
    }

    public function obtenerAtributos($idpedidodetalle)
    {
        $builder = $this->db->table('pedidodetalle as pd');
        $builder->distinct();
        $builder->select('pa.*, p.nombre as preferencianombre');
        $builder->join('pedido_preferencia', 'pedido_preferencia.idpedidodetalle = pd.idpedidodetalle', 'left');
        $builder->join('preferenciaatributo as pa', 'pa.idpreferenciaatributo = pedido_preferencia.idpreferenciaatributo', 'left');
        $builder->join('preferencia as p', 'p.idpreferencia = pa.idpreferencia', 'left');
        $builder->where('pd.idpedidodetalle', $idpedidodetalle);

        return $builder->get()->getResult();
    }

    public function pedidoDetalleByProducto($idproducto)
    {
        $builder = $this->db->table('pedidodetalle as pd');
        $builder->where('pd.idproducto', $idproducto);

        return $builder->countAllResults();
    }

    public function getPedidoDetalleByReferencia(string $referencia)
    {
        return $this->select('pedidodetalle.*, pedido.referencia, pedido.fecha') // campos que quieras traer
            ->join('pedido', 'pedido.idpedido = pedidodetalle.idpedido')
            ->where('pedido.referencia', $referencia)
            ->findAll();
    }
}
