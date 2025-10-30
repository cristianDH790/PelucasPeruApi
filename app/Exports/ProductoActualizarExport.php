<?php

namespace App\Exports;

use App\Models\ProductoModel;
use App\Helpers\Paginator;

class ProductoActualizarExport
{
    protected $filtro;

    public function __construct(array $filtro = [])
    {
        $this->filtro = [
            'ordencriterio' => $filtro['ordencriterio'] ?? 'idproducto',
            'ordentipo' => $filtro['ordentipo'] ?? 'ASC',
            'parametro' => $filtro['parametro'] ?? '',
            'valor' => $filtro['valor'] ?? '',
            'idestado' => $filtro['idestado'] ?? 0,
            'idpdestacado' => $filtro['idpdestacado'] ?? 0,
            'idproductocategoria' => $filtro['idproductocategoria'] ?? 0,
            'idptipo' => $filtro['idptipo'] ?? 0,
            'idmarca' => $filtro['idmarca'] ?? 0,
            'idproductogrupos' => $filtro['idproductogrupos'] ?? [],
            'idcupones' => $filtro['idcupones'] ?? [],
            'idtallas' => $filtro['idtallas'] ?? [],
            'idcolores' => $filtro['idcolores'] ?? [],
            'pagina' => $filtro['pagina'] ?? 1,
            'registros' => $filtro['registros'] ?? 50,
        ];
    }

    /**
     * Devuelve la colección de productos filtrados para exportar.
     */
    public function collection(): array
    {
        $productoModel = new ProductoModel();

        // Total de registros
        $total = $productoModel->productoFindTotal(
            $this->filtro['parametro'],
            $this->filtro['valor'],
            $this->filtro['idestado'],
            $this->filtro['idpdestacado'],
            $this->filtro['idproductocategoria'],
            $this->filtro['idptipo'],
            $this->filtro['idmarca'],
            $this->filtro['idproductogrupos'],
            $this->filtro['idcupones'],
            $this->filtro['idtallas'],
            $this->filtro['idcolores']
        );

        // Paginador
        $paginator = new Paginator($this->filtro['pagina'], $this->filtro['registros'], $total);

        // Obtener productos
        $productos = $productoModel->productoFind(
            $this->filtro['ordencriterio'],
            $this->filtro['ordentipo'],
            $this->filtro['parametro'],
            $this->filtro['valor'],
            $this->filtro['idestado'],
            $this->filtro['idpdestacado'],
            $this->filtro['idproductocategoria'],
            $this->filtro['idptipo'],
            $this->filtro['idmarca'],
            $this->filtro['idproductogrupos'],
            $this->filtro['idcupones'],
            $this->filtro['idtallas'],
            $this->filtro['idcolores'],
            $paginator->getFirstElement(),
            $paginator->getSize()
        );

        // Convertir a array simple (en caso de objetos)
        $resultados = [];
        foreach ($productos as $p) {
            $resultados[] = [
                'codigo' => $p['codigo'] ?? '',
                'producto' => $p['nombre'] ?? '',
                'categorias' => $p['categoria'] ?? '',
                'estado' => $p['estado'] ?? '',
                'peso' => $p['peso'] ?? 0,
                'precio_lista' => $p['preciolista'] ?? 0,
                'precio_venta' => $p['precioventa'] ?? 0,
                'stock1' => $p['stock1'] ?? '',
                'stock2' => $p['stock2'] ?? '',
                'stock3' => $p['stock3'] ?? '',
                'stock4' => $p['stock4'] ?? '',
                'stock5' => $p['stock5'] ?? '',
                'stock6' => $p['stock6'] ?? '',
                'stock7' => $p['stock7'] ?? '',
            ];
        }

        return $resultados;
    }
}
