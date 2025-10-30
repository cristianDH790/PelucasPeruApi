<?php

namespace App\Controllers\Api;

use App\Controllers\BaseController;
use App\Entities\ProductoEntity;
use App\Models\ParametroModel;
use App\Models\ProductoCategoriaModel;
use App\Models\ProductoModel;
use CodeIgniter\RESTful\ResourceController;

class ProductoComplementoController extends ResourceController
{
    protected $db;
    protected $estado;
    protected $parametro;
    protected $productocategoria;
    protected $productoModel;
    protected $tabla = 'producto_complemento';

    public function __construct()
    {
        $this->db = \Config\Database::connect();
        $this->estado = model('EstadoModel');
        $this->parametro = new ParametroModel();
        $this->productoModel = new ProductoModel();
        $this->productocategoria = new ProductoCategoriaModel();
    }

    /**
     * 🟢 Asociar un complemento a un producto
     */
    public function asociarComplemento()
    {
        $idProducto = $this->request->getVar('idProducto');
        $idComplemento = $this->request->getVar('idComplemento');

        if (!$idProducto || !$idComplemento) {
            return $this->respond([
                'mensaje' => 'Debe enviar idProducto e idComplemento'
            ], 400);
        }

        // Verificar si ya existe la asociación
        $existe = $this->db->table($this->tabla)
            ->where('idProducto', $idProducto)
            ->where('idComplemento', $idComplemento)
            ->get()
            ->getRow();

        if ($existe) {
            return $this->respond([
                'mensaje' => 'El complemento ya está asociado a este producto'
            ], 400);
        }

        $this->db->table($this->tabla)->insert([
            'idProducto'    => $idProducto,
            'idComplemento' => $idComplemento
        ]);

        return $this->respond([
            'mensaje' => 'Complemento asociado correctamente'
        ], 201);
    }

    /**
     * 🗑️ Eliminar asociación entre producto y complemento
     */
    public function eliminarComplemento($idProducto, $idComplemento)
    {
        $deleted = $this->db->table($this->tabla)
            ->where('idProducto', $idProducto)
            ->where('idComplemento', $idComplemento)
            ->delete();

        if ($deleted) {
            return $this->respond([
                'mensaje' => 'Asociación eliminada con éxito'
            ]);
        } else {
            return $this->failNotFound('No se encontró la asociación');
        }
    }


    /**
     * 📋 Listar todos los complementos asociados a un producto
     */
    // public function listarComplementos($idProducto)
    // {
    //     // 📌 Primero obtenemos los ID de los complementos relacionados
    //     $complementosIds = $this->db->table('producto_complemento')
    //         ->select('idComplemento')
    //         ->where('idProducto', $idProducto)
    //         ->get()
    //         ->getResultArray();

    //     if (empty($complementosIds)) {
    //         return $this->respond([
    //             'mensaje' => 'Este producto no tiene complementos',
    //             'content' => []
    //         ], 200);
    //     }

    //     // 📌 Extraemos solo los IDs en un array plano
    //     $ids = array_column($complementosIds, 'idComplemento');

    //     // 📌 Configuramos filtros (puedes modificar según tus necesidades)
    //     $ordencriterio = 'idproducto';
    //     $ordentipo = 'DESC';
    //     $parametro = '';
    //     $valor = '';
    //     $idestado = 0;
    //     $idproductocategoria = 0;
    //     $idpdestacado = 0;
    //     $idppromocion = 0;
    //     $idcupon = 0;
    //     $inicio = 0;
    //     $registros = 0; // 0 = sin límite

    //     // 📌 Usamos buscarPor pero filtrando por IDs de complementos
    //     $builder = $this->productoModel->buscarPor(
    //         $ordencriterio,
    //         $ordentipo,
    //         $parametro,
    //         $valor,
    //         $idestado,
    //         $idproductocategoria,
    //         $idpdestacado,
    //         $idppromocion,
    //         $idcupon,
    //         $inicio,
    //         $registros
    //     );

    //     // 📌 Filtramos solo los productos que están en el array de complementos
    //     $complementos = array_filter($builder, function ($producto) use ($ids) {
    //         return in_array($producto->idproducto, $ids);
    //     });

    //     // 📌 Obtenemos total con buscarPorTotal (opcional)
    //     $total = count($complementos);

    //     return $this->respond([
    //         'mensaje' => 'Complementos obtenidos correctamente',
    //         'total' => $total,
    //         'content' => array_values($complementos)
    //     ], 200);
    // }

    public function listarComplementos($idProducto)
    {
        // 📌 Primero obtenemos los ID de los complementos relacionados
        $complementosIds = $this->db->table('producto_complemento')
            ->select('idComplemento')
            ->where('idProducto', $idProducto)
            ->get()
            ->getResultArray();

        if (empty($complementosIds)) {
            return $this->respond([
                'mensaje' => 'Este producto no tiene complementos',
                'content' => []
            ], 200);
        }

        // 📌 Extraemos solo los IDs en un array plano
        $ids = array_column($complementosIds, 'idComplemento');

        // 📌 Configuramos filtros
        $ordencriterio = 'idproducto';
        $ordentipo = 'DESC';
        $parametro = '';
        $valor = '';
        $idestado = 0;
        $idproductocategoria = 0;
        $idrproductocategoria = 0;
        $idpdestacado = 0;
        $idppromocion = 0;
        $idcupon = 0;
        $idcolor = 0;
        $inicio = 0;
        $registros = 0; // sin límite

        // 📌 Obtenemos todos los productos
        $productos = $this->productoModel->buscarPor(
            $ordencriterio,
            $ordentipo,
            $parametro,
            $valor,
            $idestado,
            $idproductocategoria,
            $idrproductocategoria,
            $idpdestacado,
            $idppromocion,
            $idcupon,
            $idcolor,
            $inicio,
            $registros
        );

        // 📌 Filtramos solo los complementos
        $complementosFiltrados = array_filter($productos, function ($producto) use ($ids) {
            return in_array($producto->idproducto, $ids);
        });

        // 📌 Convertimos a Entity para añadir relaciones anidadas
        $resultado = [];
        foreach ($complementosFiltrados as $row) {
            $productoEntity = new \App\Entities\ProductoEntity($row);

            // 🔸 Relaciones anidadas igual que en listar()
            $productoEntity->estado = $this->estado->obtenerPorId($row->idestado);
            $productoEntity->pdestacado = $this->parametro->obtenerPorId($row->idpdestacado);
            $productoEntity->pcomplemento = $this->parametro->obtenerPorId($row->idpcomplemento);
            $productoEntity->productocategoria = $this->productocategoria->obtenerPorId($row->idproductocategoria);

            // 🔸 Si también usas marca u otras relaciones, puedes descomentarlas
            // $marcaObjeto = $this->marca->obtenerMarcaPorProductoBase($row->idproducto);
            // $productoEntity->marca = $marcaObjeto ? new \App\Entities\MarcaEntity($marcaObjeto) : null;

            $resultado[] = $productoEntity->toArray();
        }

        return $this->respond([
            'mensaje' => 'Complementos obtenidos correctamente',
            'total' => count($resultado),
            'content' => $resultado
        ], 200);
    }


    /**
     * ✏️ Editar asociación de complemento
     */
    public function editarComplemento()
    {
        $idProducto         = $this->request->getVar('idProducto');
        $idComplementoAnt   = $this->request->getVar('idComplementoAnt');
        $idComplementoNuevo = $this->request->getVar('idComplementoNuevo');

        if (!$idProducto || !$idComplementoAnt || !$idComplementoNuevo) {
            return $this->respond([
                'mensaje' => 'Faltan parámetros requeridos'
            ], 400);
        }

        $updated = $this->db->table($this->tabla)
            ->where('idProducto', $idProducto)
            ->where('idComplemento', $idComplementoAnt)
            ->update([
                'idComplemento' => $idComplementoNuevo
            ]);

        if ($updated) {
            return $this->respond([
                'mensaje' => 'Asociación actualizada correctamente'
            ], 200);
        }

        return $this->respond([
            'mensaje' => 'No se pudo actualizar la asociación'
        ], 400);
    }
}
