<?php

namespace App\Controllers\Api\Publico;

use App\Controllers\BaseController;
use App\Entities\ProductoColorEntity;
use App\Helpers\Paginator;
use App\Models\ColorModel;
use App\Models\ProductoColorModel;
use App\Models\EstadoModel;
use App\Models\ProductoModel;
use App\Validation\ProductoColorValidation;
use CodeIgniter\RESTful\ResourceController;

class ProductoColorPublicoController extends ResourceController
{
    protected $productoColor;
    protected $estado;
    protected $color;
    protected $producto;

    public function __construct()
    {
        $this->productoColor = new ProductoColorModel();
        $this->estado = new EstadoModel();
        $this->color = new ColorModel();
        $this->producto = new ProductoModel();
    }

    // ✅ OBTENER POR ID
    public function productoColorPorIdProductoColor($idProductoColor)
    {
        $productoColor = $this->productoColor->obtenerPorId($idProductoColor);

        if (!$productoColor) {
            return $this->respond(['mensaje' => 'No existe el producto color solicitado'], 404);
        }

        $entity = new ProductoColorEntity($productoColor);
        $entity->color = $this->color->obtenerPorId($productoColor->idcolor);
        $entity->estado = $this->estado->obtenerPorId($productoColor->idestado);
        $entity->producto = $this->producto->obtenerPorId($productoColor->idproducto);


        return $this->respond($entity->toArray(), 200);
    }

    // ✅ LISTAR (POST)
    public function productoColores()
    {
        if (!$this->request->is('post')) {
            return $this->fail('Método no permitido. Se requiere POST.', 405);
        }

        $request = $this->request;
        $parametro = $request->getVar('parametro') ?? null;
        $valor = $request->getVar('valor') ?? null;
        $ordencriterio = $request->getVar('ordenCriterio') ?? 'fecha';
        $ordentipo = $request->getVar('ordenTipo') ?? 'asc';
        $idestado = (int) ($request->getVar('idEstado') ?? 0);
        $idproducto = (int) ($request->getVar('idProducto') ?? 0);
        $pagina = (int) ($request->getVar('pagina') ?? 1);
        $registros = (int) ($request->getVar('registros') ?? 10);

        // Total de registros
        $total = $this->productoColor->buscarPorTotal($idestado, $idproducto);

        $paginator = new Paginator($pagina, $registros, $total);

        // $parametro, $valor, $idestado, $idproducto, $registros, $inicio
        // Consulta paginada
        $colores = $this->productoColor->buscarPor(
            $parametro,
            $valor,
            $idestado,
            $idproducto,
            $paginator->getSize(),
            $paginator->getFirstElement()
        );

        $resultado = [];
        foreach ($colores as $row) {
            $entity = new ProductoColorEntity($row);
            $entity->color = $this->color->obtenerPorId($row->idcolor);
            $entity->estado = $this->estado->obtenerPorId($row->idestado);
            $entity->producto = $this->producto->obtenerPorId($row->idproducto);
            $resultado[] = $entity->toArray();
        }

        return $this->respond([
            'paginator' => $paginator->enviar(),
            'content' => $resultado
        ], 200);
    }

    // ✅ GUARDAR
    public function productoColorGuardar()
    {
        $data = $this->request->getJSON(true);

        $validacion = new ProductoColorValidation();
        $errores = $validacion->productoColorGuardarValidation($data);

        if (!empty($errores)) {
            return $this->response
                ->setStatusCode(422)
                ->setJSON(['errors' => $errores]);
        }

        $datosValidados = [
            'idestado' => (int) ($data['estado']['idEstado'] ?? null),
            'idproducto' => (int) ($data['producto']['idProducto'] ?? null),
            'idcolor' => (int)$data['color']['idColor'],
            'nombre' => $data['nombre'] ?? null,
            'codigo' => $data['codigo'] ?? null,
            'urlamigable' => $data['urlAmigable'] ?? null,
            'orden' => $data['orden'] ?? null,
            'stock' => $data['stock'] ?? null,
            'destacado' => $data['destacado'] ?? null,
        ];

        $id = $this->productoColor->guardar($datosValidados);
        $registro = $this->productoColor->find($id);

        if ($registro) {
            $entity = new ProductoColorEntity($registro);
            $entity->color = $this->color->obtenerPorId($registro->idcolor);
            $entity->estado = $this->estado->obtenerPorId($registro->idestado);
            $entity->producto = $this->producto->obtenerPorId($registro->idproducto);


            return $this->respond([
                'mensaje' => 'Producto color registrado con éxito',
                'productoColor' => $entity->toArray()
            ], 201);
        } else {
            return $this->respond(['mensaje' => 'Error al registrar producto color'], 500);
        }
    }

    // ✅ ACTUALIZAR
    public function productoColorActualizar($idProductoColor)
    {
        $data = $this->request->getJSON(true);

        $validacion = new ProductoColorValidation();
        $errores = $validacion->productoColorActualizarValidation($data);

        if (!empty($errores)) {
            return $this->response
                ->setStatusCode(422)
                ->setJSON(['errors' => $errores]);
        }

        $datosValidados = [
            'idproductocolor' => (int) $idProductoColor,
            'idestado' => (int) ($data['estado']['idEstado'] ?? null),
            'idcolor' => (int)$data['color']['idColor'],
            'idproducto' => (int) ($data['producto']['idProducto'] ?? null),
            'nombre' => $data['nombre'] ?? null,
            'codigo' => $data['codigo'] ?? null,
            'urlamigable' => $data['urlAmigable'] ?? null,
            'orden' => $data['orden'] ?? null,
            'stock' => $data['stock'] ?? null,
            'destacado' => $data['destacado'] ?? null,
        ];

        $id = $this->productoColor->guardar($datosValidados);
        $registro = $this->productoColor->find($id);

        if ($registro) {
            $entity = new ProductoColorEntity($registro);
            $entity->color = $this->color->obtenerPorId($registro->idcolor);
            $entity->estado = $this->estado->obtenerPorId($registro->idestado);
            $entity->producto = $this->producto->obtenerPorId($registro->idproducto);


            return $this->respond([
                'mensaje' => 'Producto color actualizado con éxito',
                'productoColor' => $entity->toArray()
            ], 200);
        } else {
            return $this->respond(['mensaje' => 'Error al actualizar producto color'], 500);
        }
    }

    // ✅ ELIMINAR
    public function productoColorEliminar($idProductoColor)
    {
        if ($this->productoColor->eliminar($idProductoColor)) {
            return $this->respond(['mensaje' => 'Producto color eliminado con éxito'], 200);
        } else {
            return $this->failNotFound('No se encontró el producto color');
        }
    }
}
