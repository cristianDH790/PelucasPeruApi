<?php

namespace App\Controllers\Api;

use App\Entities\MarcaEntity;
use App\Entities\ProductoBaseEntity;
use App\Helpers\Paginator;
use App\Helpers\Permisos;
use App\Models\PromocionModel;
use App\Models\EstadoModel;
use App\Models\MarcaModel;
use App\Models\ProductoBaseModel;
use App\Models\ParametroModel;
use App\Models\ProductoCategoriaModel;
use App\Validation\ProductoBaseValidation;
use CodeIgniter\RESTful\ResourceController;

class ProductoBaseController extends ResourceController
{

    protected $productobase;
    protected $productocategoria;
    protected $estado;
    protected $promocion;
    protected $parametro;
    protected $marca;
    protected $permiso;

    public function __construct()
    {
        $this->permiso = new Permisos();
        $this->productobase = new ProductoBaseModel();
        $this->productocategoria = new ProductoCategoriaModel();
        $this->estado = new EstadoModel();
        $this->promocion = new PromocionModel();
        $this->parametro = new ParametroModel();
        $this->marca = new MarcaModel();
    }
    private function verificarPermiso(string $permiso)
    {

        $token = $this->request->getHeaderLine('X-Authorization');
        $token = str_replace('Bearer ', '', $token);

        if (!$token) {
            return $this->failUnauthorized('Token no proporcionado');
        }
        $resultado = $this->permiso->obtenerPermisosDesdeToken($token);

        if (isset($resultado['error'])) {
            return $this->failUnauthorized($resultado['error']);
        }

        $permisos = $resultado['authorities'] ?? [];

        if (!in_array($permiso, $permisos)) {
            return $this->failForbidden("No tienes permiso: {$permiso}");
        }

        return null; // Permiso concedido
    }

    public  function obtenerPorId($idproductobase)
    {

        if ($respuesta = $this->verificarPermiso('api_producto_base_obtenerPorId')) {
            return $respuesta;
        }
        $productobase = $this->productobase->obtenerPorId(
            $idproductobase
        );

        if (!$productobase) {
            return $this->respond(['mensaje' => 'No existe la forma pago solicitada'], 404);
        } else {

            $productobaseEntity = new ProductoBaseEntity($productobase);
            // Relaciones
            $productobaseEntity->estado = $this->estado->obtenerPorId($productobase->idestado);
            $productobaseEntity->ppromocion = $this->parametro->obtenerPorId($productobase->idpromocion);
            $productobaseEntity->pdestacado = $this->parametro->obtenerPorId($productobase->idpdestacado);
            $productobaseEntity->productocategoria = $this->productocategoria->obtenerPorId($productobase->idproductocategoria);
            $marcaObjeto = $this->marca->obtenerMarcaPorProductoBase($productobase->idproductobase);
            $productobaseEntity->marca = $marcaObjeto ? new MarcaEntity($marcaObjeto) : null;

            // Convertir a array
            $resultado = $productobaseEntity->toArray();

            return $this->respond($resultado, 200);
        }
    }

    public function listar()
    {
        if ($respuesta = $this->verificarPermiso('api_producto_base_listar')) {
            return $respuesta;
        }
        // Verificar si es POST
        if (!$this->request->is('post')) {
            return $this->fail('Método no permitido. Se requiere POST.', 405);
        }

        $request = $this->request;

        // Parámetros de búsqueda y orden
        $ordencriterio = $request->getVar('ordenCriterio') ?? 'fechapublicacion';
        $ordentipo = $request->getVar('ordenTipo') ?? 'asc';
        $parametro = $request->getVar('parametro') ?? '';
        $valor = $request->getVar('valor') ?? '';
        $idestado = (int) ($request->getVar('idEstado') ?? 0);
        $idpromocion = (int) ($request->getVar('idPromocion') ?? 0);
        $idproductocategoria = (int) ($request->getVar('idProductoCategoria') ?? 0);
        $idpdestacado = (int) ($request->getVar('idpDestacado') ?? 0);

        // Parámetros de paginación
        $pagina = (int) ($request->getVar('pagina') ?? 1);
        $registros = (int) ($request->getVar('registros') ?? 10);

        // Total de registros
        $total = $this->productobase->buscarPorTotal(
            $parametro,
            $valor,
            $idestado,
            $idproductocategoria,
            $idpdestacado,
            $idpromocion


        );

        $paginator = new Paginator($pagina, $registros, $total);
        // Consulta paginada
        $productobases = $this->productobase->buscarPor(
            $ordencriterio,
            $ordentipo,
            $parametro,
            $valor,
            $idestado,
            $idproductocategoria,
            $idpdestacado,
            $idpromocion,
            $paginator->getFirstElement(),
            $paginator->getSize()
        );

        // Convertir resultados a entidad
        $resultado = [];
        foreach ($productobases as $row) {
            $productobaseEntity = new ProductoBaseEntity($row);
            // Relaciones

            $productobaseEntity->estado = $this->estado->obtenerPorId($row->idestado);
            $productobaseEntity->ppromocion = $this->parametro->obtenerPorId($row->idpromocion);
            $productobaseEntity->pdestacado = $this->parametro->obtenerPorId($row->idpdestacado);
            $productobaseEntity->productocategoria = $this->productocategoria->obtenerPorId($row->idproductocategoria);
            $marcaObjeto = $this->marca->obtenerMarcaPorProductoBase($row->idproductobase);
            $productobaseEntity->marca = $marcaObjeto ? new MarcaEntity($marcaObjeto) : null;
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
        if ($respuesta = $this->verificarPermiso('api_producto_base_guardar')) {
            return $respuesta;
        }
        $request = $this->request;

        $data = $request->getJSON(true);
        $productobaseRequest = new ProductoBaseValidation();
        $errores = $productobaseRequest->productobaseGuardarValidation($data);

        if (!empty($errores)) {
            return $this->response
                ->setStatusCode(422)
                ->setJSON(['errors' => $errores]);
        }

        $datosValidados =
            [
                'idestado' => $data['estado']['idEstado'] ?? null,
                'idproductocategoria' => $data['productoCategoria']['idProductoCategoria'] ?? null,
                'idpromocion' => $data['pPromocion']['idParametro'] ?? null,
                'idpdestacado' => $data['pDestacado']['idParametro'] ?? null,
                'codigo' => $data['codigo'] ?? null,
                'nombre' => $data['nombre'] ?? null,
                'urlamigable' => $data['urlAmigable'] ?? null,
                'resumen' => $data['resumen'] ?? null,
                'descripcionseo' => $data['descripcionSeo'] ?? null,
                'descripcion' => $data['descripcion'] ?? null,
                'urlimagen' => $data['urlImagen'] ?? null,
                'preciolista' => $data['precioLista'] ?? null,
                'precioventa' => $data['precioVenta'] ?? null,
                'peso' => $data['peso'] ?? null,
                'fechapublicacion' => $data['fechaPublicacion'] ?? null,
                'idmarca' => $data['marca']['idMarca'] ?? null,
            ];



        $productobaseId = $this->productobase->guardar($datosValidados);
        $productobase = $this->productobase->find($productobaseId);
        if ($productobase) {

            $productobaseEntity = new ProductoBaseEntity($productobase);

            $productobaseEntity->estado = $this->estado->obtenerPorId($productobase->idestado);
            $productobaseEntity->ppromocion = $this->parametro->obtenerPorId($productobase->idpromocion);
            $productobaseEntity->pdestacado = $this->parametro->obtenerPorId($productobase->idpdestacado);
            $productobaseEntity->productocategoria = $this->productocategoria->obtenerPorId($productobase->idproductocategoria);
            $marcaObjeto = $this->marca->obtenerMarcaPorProductoBase($productobase->idproductobase);
            $productobaseEntity->marca = $marcaObjeto ? new MarcaEntity($marcaObjeto) : null;
            return $this->respond([
                "mensaje" => 'forma pago registrado con éxito',
                "producto" => $productobaseEntity->toArray()
            ], 201);
        } else {

            return $this->respond(["mensaje" => "Error al registrar productobase"], 500);
        }
    }

    public function actualizar()
    {
        if ($respuesta = $this->verificarPermiso('api_producto_base_actualizar')) {
            return $respuesta;
        }
        $request = $this->request;

        $data = $request->getJSON(true);
        $productobaseRequest = new ProductoBaseValidation();
        $errores = $productobaseRequest->productobaseGuardarValidation($data);

        if (!empty($errores)) {
            return $this->response
                ->setStatusCode(422)
                ->setJSON(['errors' => $errores]);
        }
        $datosValidados = [
            'idproductobase' => (int) $data['idProductoBase'] ?? null,
            'idestado' => $data['estado']['idEstado'] ?? null,
            'idproductocategoria' => $data['productoCategoria']['idProductoCategoria'] ?? null,
            'idpromocion' => $data['pPromocion']['idParametro'] ?? null,
            'idpdestacado' => $data['pDestacado']['idParametro'] ?? null,
            'codigo' => $data['codigo'] ?? null,
            'nombre' => $data['nombre'] ?? null,
            'urlamigable' => $data['urlAmigable'] ?? null,
            'resumen' => $data['resumen'] ?? null,
            'descripcionseo' => $data['descripcionSeo'] ?? null,
            'descripcion' => $data['descripcion'] ?? null,
            'urlimagen' => $data['urlImagen'] ?? null,
            'preciolista' => $data['precioLista'] ?? null,
            'precioventa' => $data['precioVenta'] ?? null,
            'peso' => $data['peso'] ?? null,
            'fechapublicacion' => $data['fechaPublicacion'] ?? null,
            'marca' => $data['marca']['idMarca'] ?? null,
        ];



        $productobaseId = $this->productobase->guardar($datosValidados);
        $productobase = $this->productobase->find($productobaseId);
        if ($productobase) {

            $productobaseEntity = new ProductoBaseEntity($productobase);

            $productobaseEntity->estado = $this->estado->obtenerPorId($productobase->idestado);
            $productobaseEntity->ppromocion = $this->parametro->obtenerPorId($productobase->idpromocion);
            $productobaseEntity->pdestacado = $this->parametro->obtenerPorId($productobase->idpdestacado);
            $productobaseEntity->productocategoria = $this->productocategoria->obtenerPorId($productobase->idproductocategoria);
            $marcaObjeto = $this->marca->obtenerMarcaPorProductoBase($productobase->idproductobase);
            $productobaseEntity->marca = $marcaObjeto ? new MarcaEntity($marcaObjeto) : null;
            return $this->respond([
                "mensaje" => 'Producto base actualizado con éxito',
                "producto" =>  $productobaseEntity->toArray()
            ], 201);
        } else {

            return $this->respond(["mensaje" => "Error al actualizar el producto base"], 500);
        }
    }

    public function eliminar($idproductobase)
    {
        if ($respuesta = $this->verificarPermiso('api_producto_base_eliminar')) {
            return $respuesta;
        }
        if ($this->productobase->eliminar(
            $idproductobase
        )) {
            return $this->respond(['mensaje' => 'Producto base eliminado con éxito']);
        } else {
            return $this->failNotFound('No se encontró la producto base');
        }
    }

    
}
