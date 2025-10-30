<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\CuponModel;
use App\Models\PedidoModel;
use App\Models\ProductoBaseModel;
use App\Models\ProductoModel;

class CuponController extends BaseController
{
    public function validarCupon()
    {
        if ($usuario = session()->get('usuarioSesion')) {

            $codigo = $this->request->getPost("codigo");
            $idusuario = $usuario->idusuario;

            $errores = [];
            if (empty($codigo)) {
                array_push($errores, ["campo" => "cupon", "valor" => "Ingrese el código de cupón"]);
            } else {
                $newCupon = CuponModel::cuponByCodigo($codigo, 0, 337);
                if (!$newCupon)
                    array_push($errores, ["campo" => "cupon", "valor" => "No existe el código ingresado"]);
            }

            if (count($errores) > 0)
                return json_encode(['errors' => $errores, 'status' => 'error']);

            $pedidoModel = new PedidoModel();
            $productoModel = new ProductoModel();

            if ($newCupon->idptipo == 543) {
                $pedidos = $pedidoModel->obtenerByIdCuponIdUsuario($newCupon->idcupon, $idusuario);

                if ($pedidos > 0)
                    return json_encode(['status' => 'error', 'errors' => [["campo" => "cupon", "valor" => "El cupón ya ha sido utilizado"]]]);
                elseif (date("Y-m-d H:i:s") > $newCupon->termino)
                    return json_encode(['status' => 'error', 'errors' => [["campo" => "cupon", "valor" => "Cupón caducado"]]]);
                elseif ($newCupon->inicio > date("Y-m-d H:i:s"))
                    return json_encode(['status' => 'error', 'errors' => [["campo" => "cupon", "valor" => "El cupón aún no ha sido habilitado"]]]);
            }

            // Aquí es donde cambiamos de ProductoTalla a ProductoBase
            $productos = $productoModel->buscarPor("", "", "", "", 325, 0, 0, 0,  0, [$newCupon->idcupon], 0, 0, 0);


            return $this->response->setJSON([
                "cupon" => $newCupon,
                "codigo" => $codigo,
                "productos" => $productos,

                "mensaje" => "Cupón registrado correctamente"
            ]);
        }
    }
}
