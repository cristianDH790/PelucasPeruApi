<?php

namespace App\Controllers\Api\Publico;

use App\Entities\MarcaEntity;
use App\Entities\ProductoEntity;
use App\Helpers\Excel\ReporteExcelProductos;
use App\Helpers\Paginator;
use App\Helpers\Permisos;
use App\Models\EmpresaModel;

use App\Models\EstadoModel;
use App\Models\MarcaModel;
use App\Models\ParametroModel;

use App\Models\ProductoCategoriaModel;
use App\Models\ProductoModel;
use App\Models\PromocionModel;
use App\Validation\ProductoValidation;
use CodeIgniter\RESTful\ResourceController;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;


class ProductoPublicoController extends ResourceController
{

    protected $producto;
    protected $productocategoria;
    protected $estado;
    protected $promocion;
    protected $parametro;
    protected $marca;
    protected $permiso;

    public function __construct()
    {
        $this->permiso = new Permisos();
        $this->producto = new ProductoModel();
        $this->productocategoria = new ProductoCategoriaModel();
        $this->estado = new EstadoModel();
        $this->promocion = new PromocionModel();
        $this->parametro = new ParametroModel();
        $this->marca = new MarcaModel();
    }


    public  function obtenerPorId($idproducto)
    {


        $producto = $this->producto->obtenerPorId(
            $idproducto
        );

        if (!$producto) {
            return $this->respond(['mensaje' => 'No existe la forma pago solicitada'], 404);
        } else {

            $productobaseEntity = new ProductoEntity($producto);
            // Relaciones
            $productobaseEntity->estado = $this->estado->obtenerPorId($producto->idestado);
            $productobaseEntity->ppromocion = $this->parametro->obtenerPorId($producto->idppromocion);
            $productobaseEntity->pdestacado = $this->parametro->obtenerPorId($producto->idpdestacado);
            $productobaseEntity->plongitud = $this->parametro->obtenerPorId($producto->idplongitud);
            $productobaseEntity->pajuste = $this->parametro->obtenerPorId($producto->idpajuste);
            $productobaseEntity->pcontrolstock = $this->parametro->obtenerPorId($producto->idpcontrolstock);
            $productobaseEntity->productocategoria = $this->productocategoria->obtenerPorId($producto->idproductocategoria);
            $marcaObjeto = $this->marca->obtenerMarcaPorProductoBase($producto->idproducto);
            $productobaseEntity->marca = $marcaObjeto ? new MarcaEntity($marcaObjeto) : null;

            // Convertir a array
            $resultado = $productobaseEntity->toArray();

            return $this->respond($resultado, 200);
        }
    }

    public function listar()
    {

        // Verificar si es POST
        if (!$this->request->is('post')) {
            return $this->fail('Método no permitido. Se requiere POST.', 405);
        }

        $request = $this->request;

        // Parámetros de búsqueda y orden
        $ordencriterio = $request->getVar('ordenCriterio') ?? 'fecha';
        $ordentipo = $request->getVar('ordenTipo') ?? 'asc';
        $parametro = $request->getVar('parametro') ?? '';
        $valor = $request->getVar('valor') ?? '';
        $idestado = (int) ($request->getVar('idEstado') ?? 0);
        // $idpcomplemento = (int) ($request->getVar('idpComplemento') ?? 0);
        $idpcomplemento = $request->getVar('idpComplemento') ?? [];
        if (!is_array($idpcomplemento)) {
            // Si viene como string "400,402", convertirlo a array de enteros
            $idpcomplemento = array_map('intval', explode(',', $idpcomplemento));
        } else {
            // Asegurarse de que todos sean enteros
            $idpcomplemento = array_map('intval', $idpcomplemento);
        }

        $idproductocategoria = (int) ($request->getVar('idProductoCategoria') ?? 0);
        $idrproductocategoria = (int) ($request->getVar('idrProductoCategoria') ?? 0);
        $idpdestacado = (int) ($request->getVar('idpDestacado') ?? 0);
        $idmarca = (int) ($request->getVar('idMarca') ?? 0);
        $idcupon = (int) ($request->getVar('idCupon') ?? 0);
        $idcolor = (int) ($request->getVar('idpColor') ?? 0);
        // Parámetros de paginación
        $pagina = (int) ($request->getVar('pagina') ?? 1);
        $registros = (int) ($request->getVar('registros') ?? 10);

        // Total de registros
        $total = $this->producto->buscarPorTotal(
            $parametro,
            $valor,
            $idestado,
            $idproductocategoria,
            $idrproductocategoria,
            $idpdestacado,
            $idpcomplemento,
            $idcupon,
            $idcolor



        );

        $paginator = new Paginator($pagina, $registros, $total);
        // Consulta paginada
        $productobases = $this->producto->buscarPor(
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
            $paginator->getFirstElement(),
            $paginator->getSize()
        );

        // Convertir resultados a entidad
        $resultado = [];
        foreach ($productobases as $row) {
            $productobaseEntity = new ProductoEntity($row);
            // Relaciones

            $productobaseEntity->estado = $this->estado->obtenerPorId($row->idestado);
            // $productobaseEntity->ppromocion = $this->parametro->obtenerPorId($row->idppromocion);
            $productobaseEntity->pdestacado = $this->parametro->obtenerPorId($row->idpdestacado);
            $productobaseEntity->pcomplemento = $this->parametro->obtenerPorId($row->idpcomplemento);
            // $productobaseEntity->plongitud = $this->parametro->obtenerPorId($row->idplongitud);
            // $productobaseEntity->pajuste = $this->parametro->obtenerPorId($row->idpajuste);
            // $productobaseEntity->pcontrolstock = $this->parametro->obtenerPorId($row->idpcontrolstock);
            $productobaseEntity->productocategoria = $this->productocategoria->obtenerPorId($row->idproductocategoria);
            // $marcaObjeto = $this->marca->obtenerMarcaPorProductoBase($row->idproducto);
            // $productobaseEntity->marca = $marcaObjeto ? new MarcaEntity($marcaObjeto) : null;
            $resultado[] = $productobaseEntity->toArray();
        }

        // Respuesta JSON con paginación y datos
        return $this->respond([
            'paginator' => $paginator->enviar(),
            'content' => $resultado
        ]);
    }
    public function guardar()
    {

        $request = $this->request;

        $data = $request->getJSON(true);
        $productobaseRequest = new ProductoValidation();
        $errores = $productobaseRequest->productoGuardarValidation($data);

        if (!empty($errores)) {
            return $this->response
                ->setStatusCode(422)
                ->setJSON(['errors' => $errores]);
        }

        $datosValidados =
            [
                'idestado' => $data['estado']['idEstado'] ?? null,
                'idproductocategoria' => $data['productoCategoria']['idProductoCategoria'] ?? null,
                'idppromocion' => $data['pPromocion']['idParametro'] ?? null,
                'idpdestacado' => $data['pDestacado']['idParametro'] ?? null,
                'idpcontrolstock' => $data['pControlStock']['idParametro'] ?? null,
                'idpajuste' => (isset($data['pAjuste']['idParametro']) && $data['pAjuste']['idParametro'] != 0) ? $data['pAjuste']['idParametro'] : null,
                'idplongitud' => (isset($data['pLongitud']['idParametro']) && $data['pLongitud']['idParametro'] != 0) ? $data['pLongitud']['idParametro'] : null,
                'stock' => $data['stock'] ?? null,
                'codigo' => $data['codigo'] ?? null,
                'nombre' => $data['nombre'] ?? null,
                'urlamigable' => $data['urlAmigable'] ?? null,
                'resumen' => $data['resumen'] ?? null,
                'contenido' => $data['contenido'] ?? null,
                'urlimagen' => $data['urlImagen'] ?? null,
                'orden' => $data['orden'] ?? null,
                'compraxcliente' => $data['compraXCliente'] ?? null,
                'preciolista' => $data['precioLista'] ?? null,
                'precioventa' => $data['precioVenta'] ?? null,
                'peso' => $data['peso'] ?? null,
                'fechapublicacion' => $data['fechaPublicacion'] ?? null,
                'idmarca' => $data['marca']['idMarca'] ?? null,
            ];



        $productobaseId = $this->producto->guardar($datosValidados);
        $producto = $this->producto->find($productobaseId);
        if ($producto) {

            $productobaseEntity = new ProductoEntity($producto);

            $productobaseEntity->estado = $this->estado->obtenerPorId($producto->idestado);
            $productobaseEntity->ppromocion = $this->parametro->obtenerPorId($producto->idppromocion);
            $productobaseEntity->pdestacado = $this->parametro->obtenerPorId($producto->idpdestacado);
            $productobaseEntity->plongitud = $this->parametro->obtenerPorId($producto->idplongitud);
            $productobaseEntity->pajuste = $this->parametro->obtenerPorId($producto->idpajuste);
            $productobaseEntity->pcontrolstock = $this->parametro->obtenerPorId($producto->idpcontrolstock);
            $productobaseEntity->productocategoria = $this->productocategoria->obtenerPorId($producto->idproductocategoria);
            $marcaObjeto = $this->marca->obtenerMarcaPorProductoBase($producto->idproducto);
            $productobaseEntity->marca = $marcaObjeto ? new MarcaEntity($marcaObjeto) : null;
            return $this->respond([
                "mensaje" => 'forma pago registrado con éxito',
                "producto" => $productobaseEntity->toArray()
            ], 201);
        } else {

            return $this->respond(["mensaje" => "Error al registrar producto"], 500);
        }
    }

    public function actualizar()
    {

        $request = $this->request;

        $data = $request->getJSON(true);
        $productobaseRequest = new ProductoValidation();
        $errores = $productobaseRequest->productoGuardarValidation($data);

        if (!empty($errores)) {
            return $this->response
                ->setStatusCode(422)
                ->setJSON(['errors' => $errores]);
        }
        $datosValidados = [
            'idproducto' => (int) $data['idProducto'] ?? null,
            'idestado' => $data['estado']['idEstado'] ?? null,
            'idproductocategoria' => $data['productoCategoria']['idProductoCategoria'] ?? null,
            'idppromocion' => $data['pPromocion']['idParametro'] ?? null,
            'idpdestacado' => $data['pDestacado']['idParametro'] ?? null,
            'idpcontrolstock' => $data['pControlStock']['idParametro'] ?? null,
            'idpajuste' => (isset($data['pAjuste']['idParametro']) && $data['pAjuste']['idParametro'] != 0) ? $data['pAjuste']['idParametro'] : null,
            'idplongitud' => (isset($data['pLongitud']['idParametro']) && $data['pLongitud']['idParametro'] != 0) ? $data['pLongitud']['idParametro'] : null,

            'stock' => $data['stock'] ?? null,
            'codigo' => $data['codigo'] ?? null,
            'orden' => $data['orden'] ?? null,
            'nombre' => $data['nombre'] ?? null,
            'urlamigable' => $data['urlAmigable'] ?? null,
            'resumen' => $data['resumen'] ?? null,
            'contenido' => $data['contenido'] ?? null,
            'compraxcliente' => $data['compraXCliente'] ?? null,
            'urlimagen' => $data['urlImagen'] ?? null,
            'preciolista' => $data['precioLista'] ?? null,
            'precioventa' => $data['precioVenta'] ?? null,
            'peso' => $data['peso'] ?? null,
            'fechapublicacion' => $data['fechaPublicacion'] ?? null,
            'idmarca' => $data['marca']['idMarca'] ?? null,
        ];



        $productobaseId = $this->producto->guardar($datosValidados);
        $producto = $this->producto->find($productobaseId);
        if ($producto) {

            $productobaseEntity = new ProductoEntity($producto);

            $productobaseEntity->estado = $this->estado->obtenerPorId($producto->idestado);
            $productobaseEntity->ppromocion = $this->parametro->obtenerPorId($producto->idppromocion);
            $productobaseEntity->pdestacado = $this->parametro->obtenerPorId($producto->idpdestacado);
            $productobaseEntity->plongitud = $this->parametro->obtenerPorId($producto->idplongitud);
            $productobaseEntity->pajuste = $this->parametro->obtenerPorId($producto->idpajuste);
            $productobaseEntity->pcontrolstock = $this->parametro->obtenerPorId($producto->idpcontrolstock);
            $productobaseEntity->productocategoria = $this->productocategoria->obtenerPorId($producto->idproductocategoria);
            $marcaObjeto = $this->marca->obtenerMarcaPorProductoBase($producto->idproducto);
            $productobaseEntity->marca = $marcaObjeto ? new MarcaEntity($marcaObjeto) : null;
            return $this->respond([
                "mensaje" => 'Producto base actualizado con éxito',
                "producto" =>  $productobaseEntity->toArray()
            ], 201);
        } else {

            return $this->respond(["mensaje" => "Error al actualizar el producto base"], 500);
        }
    }

    public function eliminar($idproducto)
    {

        if ($this->producto->eliminar(
            $idproducto
        )) {
            return $this->respond(['mensaje' => 'Producto base eliminado con éxito']);
        } else {
            return $this->failNotFound('No se encontró la producto base');
        }
    }
}
