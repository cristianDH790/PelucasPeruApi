<?php

namespace App\Controllers\Api;

use App\Controllers\BaseController;
use App\Entities\CuponEntity;
use App\Entities\ProductoCuponEntity;
use App\Helpers\Paginator;
use App\Helpers\Permisos;
use App\Helpers\Util;
use App\Models\CuponModel;
use App\Models\EstadoModel;
use App\Models\ParametroModel;
use App\Models\ProductoBaseModel;
use App\Models\ProductoCaracteristicaModel;
use App\Models\ProductoModel;
use App\Validation\CuponValidation;
use App\Validation\ProductoImagenValidation;
use CodeIgniter\RESTful\ResourceController;

class ProductoCuponController extends ResourceController
{

    protected $productoCupon;
    protected $producto;
    protected $estado;
    protected $parametro;
    protected $permiso;
    public function __construct()
    {
        $this->productoCupon = new CuponModel();

        $this->estado = new EstadoModel();
        $this->parametro = new ParametroModel();
        $this->permiso = new Permisos();
    }

    public  function obtenerPorId($idcupon)
    {

        $productoCupon = $this->productoCupon->obtenerPorId($idcupon);

        if (!$productoCupon) {
            return $this->respond(['mensaje' => 'No existe la producto Imagen solicitada'], 404);
        } else {

            $productoCuponEntity = new CuponEntity($productoCupon);


            $productoCuponEntity->estado = $this->estado->obtenerPorId($productoCupon->idestado);
            $productoCuponEntity->ptipo = $this->parametro->obtenerPorId($productoCupon->idptipo);
            $productoCuponEntity->productos = $this->productoCupon->totalCuponesPorProducto($productoCupon->idcupon);


            // Convertir a array
            $resultado = $productoCuponEntity->toArray();

            return $this->respond($resultado, 200);
        }
    }




    //metodo apra verificar  si el cupoon esta asocaido al producto  tengo una tabla producto_cupon
    //si esta asociado no se puede volver a asociar

    public function asociarCupon()
    {

        $idcupon = $this->request->getVar('idCupon');
        $idproducto = $this->request->getVar('idProductoColor');


        $productoCupon = $this->productoCupon->obtenerPorId($idcupon);

        if (!$productoCupon) {
            return $this->respond(['mensaje' => 'No existe la cupon Imagen solicitada'], 404);
        } else {


            if ($this->productoCupon->verificarCupon($idcupon, $idproducto)) {
                return $this->respond(['mensaje' => 'El cupón ya está asociado a este producto'], 400);
            } else {


                if ($this->productoCupon->asociarCuponAProducto($idcupon, $idproducto)) {
                    return $this->respond(['mensaje' => 'Cupón asociado al producto con éxito'], 200);
                } else {
                    return $this->respond(['mensaje' => 'Cupón asociado al producto erroneo'], 200);
                }
            }
        }
    }
    public function eliminarAsociacion()
    {
        // Cargar el modelo que maneja las asociaciones
        $idProducto = (int) $this->request->getVar('idProducto');
        $idCupon    = (int) $this->request->getVar('idCupon');

        if (!$idProducto || !$idCupon) {
            return $this->fail('Parámetros incompletos', 400);
        }

        $eliminado = $this->productoCupon->eliminarAsociacion($idProducto, $idCupon);

        if ($eliminado) {
            return $this->respond([
                'status' => true,
                'message' => 'Asociación eliminada correctamente'
            ]);
        } else {
            return $this->fail('No se pudo eliminar la asociación', 400);
        }
    }


    public function listarCuponesAsociados($idproducto)
    {


        $productoCupones = $this->productoCupon->listarCuponesAsociados($idproducto);

        // Convertir resultados a entidad
        $resultado = [];
        foreach ($productoCupones as $row) {
            $productoCuponEntity = new CuponEntity($row);
            $productoCuponEntity->estado = $this->estado->obtenerPorId($row->idestado);
            $productoCuponEntity->ptipo = $this->parametro->obtenerPorId($row->idptipo);
            $productoCuponEntity->productos = $this->productoCupon->totalCuponesPorProducto($row->idcupon);

            $resultado[] = $productoCuponEntity->toArray();
        }
        return $this->respond([
            "mensaje" => 'Cupon con éxito',
            "cupon" => $resultado
        ], 201);
    }

    //eliminar cupons asociados a un producto
    public function eliminarCuponDeProducto($idcupon)
    {



        if ($this->productoCupon->eliminarAsociacionCuponProducto($idcupon)) {
            return $this->respond(['mensaje' => 'Cupón desasociado del producto con éxito'], 200);
        } else {
            return $this->respond(['mensaje' => 'Error al desasociar el cupón del producto'], 500);
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
        $iptipo = (int) ($request->getVar('idpTipo') ?? 0);




        // Parámetros de paginación
        $pagina = (int) ($request->getVar('pagina') ?? 1);
        $registros = (int) ($request->getVar('registros') ?? 10);

        // Total de registros
        $total = $this->productoCupon->buscarPorTotal(
            $parametro,
            $valor,
            $idestado,
            $iptipo
        );

        $paginator = new Paginator($pagina, $registros, $total);
        // Consulta paginada
        $productoImagens = $this->productoCupon->buscarPor(
            $ordencriterio,
            $ordentipo,
            $parametro,
            $valor,
            $idestado,
            $iptipo,
            $paginator->getFirstElement(),
            $paginator->getSize()
        );

        // Convertir resultados a entidad
        $resultado = [];
        foreach ($productoImagens as $row) {
            $productoCuponEntity = new CuponEntity($row);
            $productoCuponEntity->estado = $this->estado->obtenerPorId($row->idestado);
            $productoCuponEntity->ptipo = $this->parametro->obtenerPorId($row->idptipo);
            $productoCuponEntity->productos = $this->productoCupon->totalCuponesPorProducto($row->idcupon);




            $resultado[] = $productoCuponEntity->toArray();
        }


        return $this->respond([
            'paginator' => $paginator->enviar(),
            'content' => $resultado
        ]);
    }
    public function guardar()
    {

        $request = $this->request;

        $data = $request->getJSON(true);
        $ProductoCuponValidation = new CuponValidation();
        $errores = $ProductoCuponValidation->cuponGuardarValidation($data, $this->productoCupon);

        if (!empty($errores)) {
            return $this->response
                ->setStatusCode(422)
                ->setJSON(['errors' => $errores]);
        }

        $datosValidados = [
            'idestado'      => $data['estado']['idEstado'] ?? null,
            'idptipo'      => $data['pTipo']['idParametro'] ?? null,
            'codigo'      => $data['codigo'] ?? null,
            'nombre'        => $data['nombre'] ?? null,
            'limite'   => $data['limite'] ?? null,
            'descuento'   => $data['descuento'] ?? null,
            'inicio'   => $data['inicio'] ?? null,
            'termino'    => $data['termino'] ?? null,
            'orden'         => $data['orden'] ?? null,
        ];


        $ProductoCuponId = $this->productoCupon->guardar($datosValidados);
        $productoCupon = $this->productoCupon->find($ProductoCuponId);
        if ($productoCupon) {
            $productoCuponEntity = new CuponEntity($productoCupon);
            $productoCuponEntity->estado = $this->estado->obtenerPorId($productoCupon->idestado);
            $productoCuponEntity->ptipo = $this->parametro->obtenerPorId($productoCupon->idptipo);
            $productoCuponEntity->productos = $this->productoCupon->totalCuponesPorProducto($productoCupon->idcupon);

            return $this->respond([
                "mensaje" => 'Cupon registrado con éxito',
                "productoCupon" => $productoCuponEntity->toArray()
            ], 201);
        } else {

            return $this->respond(["mensaje" => "Error al registrar Cupon"], 500);
        }
    }

    public function actualizar()
    {

        $request = $this->request;

        $data = $request->getJSON(true);
        $ProductoCuponValidation = new CuponValidation();
        $errores = $ProductoCuponValidation->cuponActualizarValidation($data, $this->productoCupon);

        if (!empty($errores)) {
            return $this->response
                ->setStatusCode(422)
                ->setJSON(['errors' => $errores]);
        }
        $datosValidados = [
            'idcupon' => (int) $data['idCupon'] ?? null,
            'idestado'      => $data['estado']['idEstado'] ?? null,
            'idptipo'      => $data['pTipo']['idParametro'] ?? null,
            'codigo'      => $data['codigo'] ?? null,
            'nombre'        => $data['nombre'] ?? null,
            'limite'   => $data['limite'] ?? null,
            'descuento'   => $data['descuento'] ?? null,
            'inicio'   => $data['inicio'] ?? null,
            'termino'    => $data['termino'] ?? null,
            'orden'         => $data['orden'] ?? null,
        ];


        $ProductoCuponId = $this->productoCupon->guardar($datosValidados);
        $productoCupon = $this->productoCupon->find($ProductoCuponId);
        if ($productoCupon) {

            $productoCuponEntity = new CuponEntity($productoCupon);
            $productoCuponEntity->estado = $this->estado->obtenerPorId($productoCupon->idestado);
            $productoCuponEntity->ptipo = $this->parametro->obtenerPorId($productoCupon->idptipo);
            $productoCuponEntity->productos = $this->productoCupon->totalCuponesPorProducto($productoCupon->idcupon);
            return $this->respond([
                "mensaje" => 'Cupon actualizado con éxito',
                "productoCupon" =>  $productoCuponEntity->toArray()
            ], 201);
        } else {

            return $this->respond(["mensaje" => "Error al actualizar el producto Imagen"], 500);
        }
    }

    public function eliminar($idcupon)
    {

        if ($this->productoCupon->eliminar($idcupon)) {
            return $this->respond(['mensaje' => 'productoCupon eliminado con éxito']);
        } else {
            return $this->failNotFound('No se encontró la producto Imagen');
        }
    }
}
