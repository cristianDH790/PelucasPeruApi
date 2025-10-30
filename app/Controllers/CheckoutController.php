<?php

namespace App\Controllers;

use App\Entities\PedidoDetalle;
use App\Entities\Producto;
use App\Entities\ProductoTalla;
use App\Models\ConfiguracionModel;
use App\Models\ContenidoWebModel;
use App\Models\PedidoDetalleModel;
use App\Models\ProductoBaseModel;
use App\Models\ProductoModel;

class CheckoutController extends BaseController
{
	protected $productoBaseModel;
	protected $productoModel;
	protected $pedidoDetalleModel;
	protected $configuracion;
	protected $wsp;

	public function __construct()
	{
		$this->productoModel = new ProductoModel();
		$this->pedidoDetalleModel = new PedidoDetalleModel();
		$this->configuracion = new ConfiguracionModel();
		$wsp = $this->configuracion->obtenerPorId(40);
	}


	public function checkFormCarritoCompras()
	{
		log_message('debug', '📨 Datos brutos recibidos: ' . file_get_contents('php://input'));

		$errores = [];
		$errores2 = [];

		$idEntrega = $this->request->getPost("entrega");
		$formapago = $this->request->getPost("formapago");
		$constancia = $this->request->getFile("constancia");
		$comprobante = $this->request->getPost("tipocomprobante");
		$bnombres = $this->request->getPost("bnombres");
		$bdocumento = $this->request->getPost("bdocumento");
		$fnombres = $this->request->getPost("fnombres");
		$fdocumento = $this->request->getPost("fdocumento");
		$fdireccion = $this->request->getPost("fdireccion");
		$terminos = $this->request->getPost("terminos");
		$idProductos = $this->request->getPost("idProductos");
		$cantidades = $this->request->getPost("cantidades");
		$fechaEntrega = $this->request->getPost("fechaEntrega");
		$misDireccionesDestino = $this->request->getPost("misDireccionesDestino");
		$misDireccionesRecojo = $this->request->getPost("misDireccionesRecojo");
		$referencia = $this->request->getPost("referencia");

		$postData = $this->request->getPost();



		if (empty($idEntrega))
			array_push($errores, ['campo' => 'entrega', 'valor' => 'Seleccione.']);

		if ($idEntrega == 1 && !$misDireccionesDestino) {
			// $errores = $this->_checkDelivery($postData);
		} elseif ($idEntrega == 2 && !$misDireccionesRecojo) {
			// $errores = $this->_checkRecojoTienda($postData);
		} elseif ($idEntrega == 3) {
			$errores = $this->_checkEnvioProvincia($postData);
		}

		if (empty($formapago))
			array_push($errores, ['campo' => 'formapago', 'valor' => 'Seleccione.']);
		elseif ($formapago != 1) {
			if ($constancia == "" || $constancia == null)
				array_push($errores, ['campo' => 'constancia', 'valor' => 'Complete.']);
			elseif (!in_array(strtolower($constancia->getClientExtension()), ['png', 'jpg', 'jpeg', 'webp']))
				array_push($errores, ['campo' => 'urlimagen1', 'valor' => 'Formato de imagen incorrecto']);
		}

		// if (empty($fechaEntrega))
		// 	array_push($errores, ['campo' => 'fechaEntrega', 'valor' => 'Complete.']);

		if (empty($comprobante))
			array_push($errores, ['campo' => 'tipocomprobante', 'valor' => 'Seleccione.']);
		elseif ($comprobante == 582) {
			if (empty($bnombres))
				array_push($errores, ['campo' => 'bnombres', 'valor' => 'Complete.']);
			if (empty($bdocumento))
				array_push($errores, ['campo' => 'bdocumento', 'valor' => 'Complete.']);
		} elseif ($comprobante == 583) {
			if (empty($fnombres))
				array_push($errores, ['campo' => 'fnombres', 'valor' => 'Complete.']);
			if (empty($fdocumento))
				array_push($errores, ['campo' => 'fdocumento', 'valor' => 'Complete.']);
			if (empty($fdireccion))
				array_push($errores, ['campo' => 'fdireccion', 'valor' => 'Complete.']);
		}

		if (empty($terminos))
			array_push($errores, ['campo' => 'terminos', 'valor' => 'Acepte los términos y condiciones.']);

		$this->eliminarPedidoExistente($referencia);

		// Suponiendo que ahora llegan como strings con comas
		$idProductos = explode(",", $idProductos[0]);
		$cantidades = explode(",", $cantidades[0]);

		foreach ($idProductos as $key => $idProducto) {
			// Buscar directamente por idproducto

			$producto = $this->productoModel->obtenerPorId($idProducto);

			if (!$producto) {
				array_push($errores2, [
					"codigo" => $idProducto,
					"stock" => 0,
					"incidencia" => "Producto no disponible",
					"accion" => "Se elimina del carrito"
				]);
				continue;
			}

			$stock = intval($producto->stock);

			if ($stock < $cantidades[$key]) {
				if ($stock == 0) {
					array_push($errores2, [
						"codigo" => $idProducto,
						"stock" => 0,
						"incidencia" => "No hay stock disponible",
						"accion" => "Se elimina el producto del carrito",
						"referencia" => $referencia
					]);
				} else {
					array_push($errores2, [
						"codigo" => $idProducto,
						"stock" => $stock,
						"incidencia" => "Stock insuficiente",
						"accion" => "Se actualiza al stock disponible"
					]);
				}
			}

			if ($producto->idestado == 402) {
				array_push($errores2, [
					"codigo" => $idProducto,
					"stock" => 0,
					"incidencia" => "Producto no disponible",
					"accion" => "Se elimina el producto del carrito"
				]);
			}
		}

		if (count($errores) > 0 || count($errores2) > 0) {
			return $this->response->setJSON([
				"refes" => $referencia,
				"errors" => $errores,
				"errors2" => $errores2,
				"status" => "error"
			]);
		}

		return $this->response->setJSON(["status" => "exito", "ref" => $referencia]);
	}


	private function _checkDelivery($data)
	{
		$errores = [];

		// $ddireccion = $data['ddireccion'];
		// $dreferencia = $data['dreferencia'];
		$dnombres = $data['dnombres'];
		$dapellidos = $data['dapellidos'];
		$ddocumento = $data['ddocumento'];
		$dtelefono = $data['dtelefono'];


		if (empty($ddireccion))
			array_push($errores, ['campo' => 'ddireccion', 'valor' => 'Complete.']);

		if (empty($dnombres))
			array_push($errores, ['campo' => 'dnombres', 'valor' => 'Complete.']);

		if (empty($dapellidos))
			array_push($errores, ['campo' => 'dapellidos', 'valor' => 'Complete.']);

		if (empty($ddocumento))
			array_push($errores, ['campo' => 'ddocumento', 'valor' => 'Complete.']);

		if (empty($dtelefono))
			array_push($errores, ['campo' => 'dtelefono', 'valor' => 'Complete.']);

		return $errores;
	}

	private function _checkRecojoTienda($data)
	{
		$errores = [];

		$tienda = $data['tienda'];
		$rnombres = $data['rnombres'];
		$rapellidos = $data['rapellidos'];
		$rdocumento = $data['rdocumento'];
		$rtelefono = $data['rtelefono'];

		if (empty($tienda))
			array_push($errores, ['campo' => 'tienda', 'valor' => 'Seleccione.']);

		if (empty($rnombres))
			array_push($errores, ['campo' => 'rnombres', 'valor' => 'Complete.']);

		if (empty($rapellidos))
			array_push($errores, ['campo' => 'rapellidos', 'valor' => 'Complete.']);

		if (empty($rdocumento))
			array_push($errores, ['campo' => 'rdocumento', 'valor' => 'Complete.']);

		if (empty($rtelefono))
			array_push($errores, ['campo' => 'rtelefono', 'valor' => 'Complete.']);

		return $errores;
	}

	// private function _checkEnvioProvincia($data)
	// {
	// 	$errores = [];

	// 	$agencia = $data['agencia'];
	// 	$adireccion = $data['adireccion'];
	// 	$adepartamento = $data['adepartamento'];
	// 	$aprovincia = $data['aprovincia'];
	// 	$adistrito = $data['adistrito'];
	// 	$anombres = $data['anombres'];
	// 	$aapellidos = $data['aapellidos'];
	// 	$adocumento = $data['adocumento'];
	// 	$atelefono = $data['atelefono'];

	// 	if (empty($agencia))
	// 		array_push($errores, ['campo' => 'agencia', 'valor' => 'Seleccione.']);

	// 	if (empty($adireccion))
	// 		array_push($errores, ['campo' => 'adireccion', 'valor' => 'Complete.']);

	// 	if (empty($adepartamento))
	// 		array_push($errores, ['campo' => 'adepartamento', 'valor' => 'Complete.']);

	// 	if (empty($aprovincia))
	// 		array_push($errores, ['campo' => 'aprovincia', 'valor' => 'Complete.']);

	// 	if (empty($adistrito))
	// 		array_push($errores, ['campo' => 'adistrito', 'valor' => 'Complete.']);

	// 	if (empty($anombres))
	// 		array_push($errores, ['campo' => 'anombres', 'valor' => 'Seleccione.']);

	// 	if (empty($aapellidos))
	// 		array_push($errores, ['campo' => 'aapellidos', 'valor' => 'Complete.']);

	// 	if (empty($adocumento))
	// 		array_push($errores, ['campo' => 'adocumento', 'valor' => 'Complete.']);

	// 	if (empty($atelefono))
	// 		array_push($errores, ['campo' => 'atelefono', 'valor' => 'Complete.']);


	// 	return $errores;
	// }

	// private function _checkEnvioProvincia($data)
	// {
	// 	$errores = [];

	// 	// ✅ Decodificar el JSON de 'agencia'
	// 	$agenciaJson = $data['agencia'] ?? '{}';
	// 	$agencia = json_decode($agenciaJson, true);

	// 	if (json_last_error() !== JSON_ERROR_NONE) {
	// 		array_push($errores, ['campo' => 'agencia', 'valor' => 'Formato de agencia inválido.']);
	// 		return $errores;
	// 	}

	// 	// Extraer datos de la agencia
	// 	$nombreAgencia = $agencia['agencia'] ?? null;
	// 	$direccion = $agencia['direccion'] ?? null;
	// 	$nombres = $agencia['nombres'] ?? null;
	// 	$apellidos = $agencia['apellidos'] ?? null;
	// 	$dni = $agencia['dni'] ?? null;
	// 	$telefono = $agencia['telefono'] ?? null;
	// 	$idUsuario = $agencia['usuario']['idUsuario'] ?? null;
	// 	$idUbigeo = $agencia['ubigeo']['idUbigeo'] ?? null;
	// 	$idParametro = $agencia['ptipo']['idParametro'] ?? null;

	// 	// Validaciones
	// 	if (empty($nombreAgencia))
	// 		array_push($errores, ['campo' => 'agencia', 'valor' => 'Seleccione.']);

	// 	if (empty($direccion))
	// 		array_push($errores, ['campo' => 'adireccion', 'valor' => 'Complete.']);

	// 	if (empty($nombres))
	// 		array_push($errores, ['campo' => 'anombres', 'valor' => 'Complete.']);

	// 	if (empty($apellidos))
	// 		array_push($errores, ['campo' => 'aapellidos', 'valor' => 'Complete.']);

	// 	if (empty($dni))
	// 		array_push($errores, ['campo' => 'adocumento', 'valor' => 'Complete.']);

	// 	if (empty($telefono))
	// 		array_push($errores, ['campo' => 'atelefono', 'valor' => 'Complete.']);

	// 	if (empty($idUbigeo))
	// 		array_push($errores, ['campo' => 'adistrito', 'valor' => 'Seleccione distrito.']);

	// 	// (Opcional) log de depuración
	// 	log_message('debug', '🟢 Agencia decodificada: ' . json_encode($agencia));

	// 	return $errores;
	// }
	// private function _checkEnvioProvincia($postData)
	// {
	// 	log_message('debug', '📨 Datos brutos recibidos: ' . file_get_contents('php://input'));

	// 	// Obtener la agencia del form-data
	// 	$agenciaRaw = $this->request->getPost('agencia');
	// 	log_message('debug', '🟢 Agencia RAW: ' . $agenciaRaw);

	// 	$agencia = json_decode($agenciaRaw, true);
	// 	log_message('debug', '🟢 Agencia decodificada: ' . print_r($agencia, true));

	// 	$errores = [];

	// 	// Validaciones
	// 	if (empty($agencia['agencia']))
	// 		array_push($errores, ['campo' => 'agencia', 'valor' => 'Seleccione.']);
	// 	if (empty($agencia['direccion']))
	// 		array_push($errores, ['campo' => 'adireccion', 'valor' => 'Complete.']);
	// 	if (empty($agencia['nombres']))
	// 		array_push($errores, ['campo' => 'anombres', 'valor' => 'Complete.']);
	// 	if (empty($agencia['apellidos']))
	// 		array_push($errores, ['campo' => 'aapellidos', 'valor' => 'Complete.']);
	// 	if (empty($agencia['dni']))
	// 		array_push($errores, ['campo' => 'adocumento', 'valor' => 'Complete.']);
	// 	if (empty($agencia['telefono']))
	// 		array_push($errores, ['campo' => 'atelefono', 'valor' => 'Complete.']);
	// 	if (empty($agencia['ubigeo']['idUbigeo']))
	// 		array_push($errores, ['campo' => 'adistrito', 'valor' => 'Seleccione distrito.']);

	// 	return $errores;
	// }


	private function _checkEnvioProvincia($data)
	{
		// var_dump($data);
		// die();
		$errores = [];

		$agencia = $data['agencia'];
		$adireccion = $data['adireccion'];
		$adepartamento = $data['adepartamento'];
		$aprovincia = $data['aprovincia'];
		$adistrito = $data['adistrito'];
		$anombres = $data['anombres'];
		$aapellidos = $data['aapellidos'];
		$adocumento = $data['adocumento'];
		$atelefono = $data['atelefono'];

		if (empty($agencia))
			array_push($errores, ['campo' => 'agencia', 'valor' => 'Seleccione.']);

		if (empty($adireccion))
			array_push($errores, ['campo' => 'adireccion', 'valor' => 'Complete.']);

		if (empty($adepartamento))
			array_push($errores, ['campo' => 'adepartamento', 'valor' => 'Complete.']);

		if (empty($aprovincia))
			array_push($errores, ['campo' => 'aprovincia', 'valor' => 'Complete.']);

		if (empty($adistrito))
			array_push($errores, ['campo' => 'adistrito', 'valor' => 'Complete.']);

		if (empty($anombres))
			array_push($errores, ['campo' => 'anombres', 'valor' => 'Seleccione.']);

		if (empty($aapellidos))
			array_push($errores, ['campo' => 'aapellidos', 'valor' => 'Complete.']);

		if (empty($adocumento))
			array_push($errores, ['campo' => 'adocumento', 'valor' => 'Complete.']);

		if (empty($atelefono))
			array_push($errores, ['campo' => 'atelefono', 'valor' => 'Complete.']);


		return $errores;
	}

	public function checkPedido()
	{
		$errores = [];

		$idProductos = $this->request->getPost("idProductos"); // <- Son idproductobase
		$cantidades = $this->request->getPost("cantidades");

		if (!$idProductos || !$cantidades) {
			return response()->setJSON(["errors" => $errores, "status" => "error"]);
		}

		// Obtener empresa actual (ajusta según tu lógica)
		$idEmpresa = $this->idempresa ?? session('idempresa') ?? 1;

		foreach ($idProductos as $key => $idProductobase) {
			// Buscar en tabla 'producto' el registro por empresa y productobase
			$producto = $this->productoModel
				->where('idproductobase', $idProductobase)
				->where('idempresa', $idEmpresa)
				->first();

			$cantidad = intval($cantidades[$key]);

			if (!$producto) {
				array_push($errores, [
					"codigo" => $idProductobase,
					"stock" => 0,
					"incidencia" => "Producto no disponible para esta empresa",
					"accion" => "Se elimina del carrito"
				]);
				continue;
			}

			$stock = intval($producto->stock);

			// Solo valida si el producto tiene control de stock
			if ($producto->idpcontrolstock == 399) {
				if ($stock < $cantidad) {
					if ($stock == 0) {
						array_push($errores, [
							"codigo" => $producto->idproductobase,
							"stock" => 0,
							"incidencia" => "No hay stock disponible",
							"accion" => "Se elimina el producto del carrito"
						]);
					} else {
						array_push($errores, [
							"codigo" => $producto->idproductobase,
							"stock" => $stock,
							"incidencia" => "Stock insuficiente",
							"accion" => "Se actualiza al stock disponible"
						]);
					}
				}
			}
		}

		return $this->response->setJSON([
			"errors" => $errores,
			"status" => count($errores) > 0 ? "error" : "ok"
		]);
	}


	public function pagoProcesado()
	{
		$respuesta = $this->request->getPost('kr-answer');
		$respuesta = json_decode($respuesta);

		if ($respuesta->orderStatus == 'PAID') {
			$pedido = session()->get("pedido");
			session()->remove('pedido');

			try {
				$client = \Config\Services::curlrequest([
					'baseURI' => "https://pelucasperu.com/",
				]);

				$ped = $client->request('post', 'api/publico/pedido/guardar-izipay', [
					'json' =>  $pedido,
					'headers' => ["Accept" => "application/json"]
				],)->getBody();


				if ($ped) {
					$ped = json_decode($ped);

					$data['pedido'] = $ped->pedido;
				}
				$data["pago"] = 'success';
				$data["seccion"] = "mispedidos";
				// $this->front_views("front/body/misPedidosDetalle", $data);
				return redirect()->to(base_url() . 'pedido/' . $ped->pedido->idpedido);
			} catch (\Throwable $th) {

				print_r($th->getMessage());
				print_r($th);
			}
		} else {

			$data["seccion"] = "carrito";

			$data["codigo"] = strtotime(date("y-m-d H:i:s"));

			$configuracion = new ConfiguracionModel();
			$fraccionPeso = $configuracion->obtenerById(9);
			$horaLimite = $configuracion->obtenerById(7);
			$data["horaServidor"] = Date('h:i', time());
			$data["horaLimite"] = $horaLimite;
			$data["fraccionPeso"] = $fraccionPeso->valor;

			$contenido = new ContenidoWebModel();
			$data['termino'] = $contenido->obtenerById(6);

			session()->set('codigo', $data["codigo"]);

			$data["errorNumero"] = 1;
			$data["mensaje"] = 'No se pudo generar el pedido';
			$data["codigoant"] = session()->get('codigo');

			$this->front_views("front/body/carritoCompras", $data);
		}
	}

	public function eliminarPedidoExistente($referencia)
	{
		$pedidoDetalleModel = new PedidoDetalleModel();
		$productoModel = new ProductoModel(); // Asegúrate de tener el modelo de la tabla 'producto'

		$pedidosDetalle = $pedidoDetalleModel->getPedidoDetalleByReferencia($referencia);

		if ($pedidosDetalle) {
			foreach ($pedidosDetalle as $detalle) {
				// Obtener el producto (tabla intermedia) por idproducto
				$producto = $productoModel->find($detalle->idproducto);

				if ($producto) {
					// Sumar la cantidad de nuevo al stock
					$nuevoStock = intval($producto->stock) + intval($detalle->cantidad);

					$productoModel->update($producto->idproducto, [
						'stock' => $nuevoStock
					]);
				}
			}

			$this->pedidoDetalleModel->eliminarPorIdPedido($pedidosDetalle[0]->idpedido);

			log_message('info', 'Pedido eliminado: ' . $pedidosDetalle[0]->idpedido);
			return true;
		}

		return false;
	}
}
