<?php

namespace App\Controllers;

use App\Entities\CarreraCorta;
use App\Entities\CarreraCortaCurricula;
use App\Entities\CarreraProfesional;
use App\Entities\CarreraProfesionalCurricula;
use App\Entities\Categoria;
use App\Entities\Coleccion;
use App\Entities\Configuracion;
use App\Entities\ContenidoWeb;
use App\Entities\CursoLibre;
use App\Entities\Evento;
use App\Entities\Noticia;
use App\Entities\Parametro;
use App\Entities\Producto;
use App\Entities\ProductoImagen;
use App\Entities\ProductoTalla;
use App\Entities\Slider;
use App\Entities\Testimonio;
use App\Helpers\Paginator;
use App\Models\AgenciaModel;
use App\Models\ColorModel;
use App\Models\ComprobanteModel;
use App\Models\ConfiguracionModel;
use App\Models\ContenidoWebModel;
use App\Models\DestinoModel;
use App\Models\EntregaModel;
use App\Models\EstadoModel;
use App\Models\FormaPagoModel;
use App\Models\NoticiaCategoriaModel;
use App\Models\NoticiaModel;
use App\Models\ParametroModel;
use App\Models\PedidoDetalleModel;
use App\Models\PedidoModel;
use App\Models\ProductoCategoriaModel;
use App\Models\ProductoColorModel;
use App\Models\ProductoModel;
use App\Models\RecojoModel;
use App\Models\SliderModel;

class Front extends BaseController
{
	public $session;
	public $slider;
	public $parametro;
	public $productocategoria;
	public $color;
	public $entrega;
	public $contenidoweb;
	public $productos;
	public $configuracion;
	public $productocolor;
	public $recojo;
	public $destino;
	public $estado;
	public $comprobante;
	public $formaPago;
	protected $pedidoModel;
	protected $pedidoDetalleModel;
	protected $noticia;
	protected $noticiaCategoria;
	protected $mantenimiento;
	public function __construct()
	{
		helper('Captcha');
		helper('url');


		$this->session = \Config\Services::session();
		$this->session->start();
		$this->slider = new SliderModel();
		$this->productocategoria = new ProductoCategoriaModel();
		$this->parametro = new ParametroModel();
		$this->entrega = new EntregaModel();
		$this->estado = new EstadoModel();
		$this->color = new ColorModel();
		$this->contenidoweb = new ContenidoWebModel();
		$this->productos = new ProductoModel();
		$this->configuracion = new ConfiguracionModel();
		$this->productocolor = new ProductoColorModel();
		$this->recojo = new RecojoModel();
		$this->destino = new DestinoModel();
		$this->comprobante = new ComprobanteModel();
		$this->formaPago = new FormaPagoModel();
		$this->pedidoDetalleModel = new PedidoDetalleModel();
		$this->pedidoModel = new PedidoModel();
		$this->noticia = new NoticiaModel();
		$this->noticiaCategoria = new NoticiaCategoriaModel();
		$this->mantenimiento = $this->configuracion->obtenerPorId(33);
	}

	public function inicio()
	{
		if ($this->mantenimiento->valor == 'si' || $this->mantenimiento->valor == 'Si' || $this->mantenimiento->valor == 'SI') {
			$data["seccion"] = "inicio";
			$data["titulo"] = "Pelucas Perú - Marca N° 1 de pelcuas del Perú";
			$data["url"] = "";
			return view('front/body/mantenimiento');
		}
		$url = uri_string();
		$data["seccion"] = "inicio";
		$data["titulo"] = "Pelucas Perú - Marca N° 1 de pelcuas del Perú";
		$data["url"] = "";

		if ($url) {
			$productocategoria = $this->productocategoria->obtenerPorUrlAmigable($url);
			if ($productocategoria) {



				if ($productocategoria->idproductocategoria == 2) {


					$data["sliders"] = $this->slider->obtenerPorCategoria($productocategoria->idproductocategoria);
					$data["bannerpelucas"] = $this->contenidoweb->obtenerPorId(1);
					$data["bannercolores"] = $this->contenidoweb->obtenerPorId(2);
					$data["comonidad"] = $this->contenidoweb->obtenerPorId(3);

					$data["productocategorias"] = $this->productocategoria->buscarPor('orden', 'ASC', '', '', 325, 0, $productocategoria->idproductocategoria, 0, 0, 0);
					$data["productosfavoritos"] = $this->productos->buscarPor('orden', 'ASC', '', '', 325, 0, $productocategoria->idproductocategoria, 394, 400, 0, 0, 0, 8);
					$data["productoscombos"] = $this->productos->buscarPor('orden', 'ASC', '', '', 325, 0, $productocategoria->idproductocategoria, 394, 402, 0, 0, 0, 8);
					// $ordencriterio = '', $ordentipo = '', $parametro = '', $valor = '', $idestado = 0, $idnoticiacategoria = 0, $idpdestacado = 0, $inicio = null, $registros = null
					$data["noticiasdestacadas"] = $this->noticia->buscarPor('orden', 'ASC', '', '', 423, 0, 576, 0, 8);
					// var_dump($data["noticiasdestacadas"]);
					$this->front_views('front/body/inicio', $data);
				} else {
					$data["productocategorias"] = $this->productocategoria->buscarPor('orden', 'ASC', '', '', 325, 0, $productocategoria->idproductocategoria, 0, 0, 0);
					// var_dump($data["productocategorias"]);
					$this->front_views("front/body/lentesContacto", $data);
				}
			} else {
				return redirect()->to(base_url());
			}
		} else {
			return redirect()->to(base_url("peluca"));
		}
	}

	public function nosotros()
	{
		if ($this->mantenimiento->valor == 'si' || $this->mantenimiento->valor == 'Si' || $this->mantenimiento->valor == 'SI') {
			$data["seccion"] = "inicio";
			$data["titulo"] = "Pelucas Perú - Marca N° 1 de pelcuas del Perú";
			$data["url"] = "";
			return view('front/body/mantenimiento');
		}
		$data["seccion"] = "nosotros";

		$this->front_views("front/body/nosotros", $data);
	}

	public function productos($urlcategoria = null, $urlcategoria2 = null)
	{
		if ($this->mantenimiento->valor == 'si' || $this->mantenimiento->valor == 'Si' || $this->mantenimiento->valor == 'SI') {
			$data["seccion"] = "inicio";
			$data["titulo"] = "Pelucas Perú - Marca N° 1 de pelcuas del Perú";
			$data["url"] = "";
			return view('front/body/mantenimiento');
		}
		$data["seccion"] = "productos";
		// var_dump($data["productoscombos"]);
		// $ordencriterio, $ordentipo, $parametro, $valor, $idestado, $idproductocategoria, $idrproductocategoria, $inicio, $registros

		if ($urlcategoria) {

			$categoria = $this->productocategoria->obtenerPorUrlAmigable($urlcategoria);
			$data["categoriaproducto"] = $categoria;
			// $data["categoriapadre"] = $categoria->idproductocategoria;
			if ($categoria) {


				$data["productocategorias"] = $this->productocategoria->buscarPor("orden", "asc", "", "", 325, 0, $categoria->idproductocategoria, 0, 0);
				// $parametro, $valor, $idestado, $registros, $inicio
				$data["colores"] = $this->color->buscarPor("", "", 397,  0, 0);
				$data["productocategoriaurl"] = $this->productocategoria->obtenerPorUrlAmigable($urlcategoria2);
				if ($data["productocategoriaurl"]) {
				} else {
					$data["productocategoriaurl"] = (object)[
						'idproductocategoria' => 0,
						'urlamigable' => ''
					];
				}
			} else {
				return redirect()->to(base_url() . "productos/peluca");
			}
		} else {
			return redirect()->to(base_url() . "productos/peluca");
		}

		$this->front_views("front/body/productos", $data);
	}

	public function productoDetalle($url = null)
	{
		if ($this->mantenimiento->valor == 'si' || $this->mantenimiento->valor == 'Si' || $this->mantenimiento->valor == 'SI') {
			$data["seccion"] = "inicio";
			$data["titulo"] = "Pelucas Perú - Marca N° 1 de pelcuas del Perú";
			$data["url"] = "";
			return view('front/body/mantenimiento');
		}
		$data["seccion"] = "productos";
		if ($url) {
			$producto = $this->productos->obtenerPorUrlAmigable($url);
			$data["producto"] = $producto;
			$data["wspventa"] = $this->configuracion->obtenerPorId(40);
			$data["productosrelacionados"] = $this->productos->buscarpor("fecha", "asc", "", "", 325, $producto->categoria->idproductocategoria, 0, 0, 0, 0, 0, 0, 9);
			$this->front_views("front/body/productoDetalle", $data);
		} else {
			return redirect()->to(base_url() . "productos/peluca");
		}
	}

	public function lentesContacto()
	{
		$data["seccion"] = "prooductos";

		$this->front_views("front/body/lentesContacto", $data);
	}

	public function lentesContactoListado()
	{
		$data["seccion"] = "prooductos";

		$this->front_views("front/body/lentesContactoListado", $data);
	}

	public function lentesContactoDetalle()
	{
		$data["seccion"] = "productos";

		$this->front_views("front/body/lentesContactoDetalle", $data);
	}

	public function carteras()
	{
		$data["seccion"] = "prooductos";

		$this->front_views("front/body/carteras", $data);
	}

	public function carteraDetalle()
	{
		$data["seccion"] = "productos";

		$this->front_views("front/body/carteraDetalle", $data);
	}

	public function carritoCompras()
	{
		if ($this->mantenimiento->valor == 'si' || $this->mantenimiento->valor == 'Si' || $this->mantenimiento->valor == 'SI') {
			$data["seccion"] = "inicio";
			$data["titulo"] = "Pelucas Perú - Marca N° 1 de pelcuas del Perú";
			$data["url"] = "";
			return view('front/body/mantenimiento');
		}
		$data["seccion"] = "carrito";
		$usuarioSesion = session()->get('usuarioSesion');

		$data["WSPVENTA"] = $this->configuracion->obtenerPorId(40);
		$data["ptipodocumentos"] = $this->parametro->buscarPor("", "", 104, 293, 0, 0);
		$data["entregas"] = $this->entrega->buscarPor("", "", "", "", 375, 0, 0);

		if ($usuarioSesion) {
			// $data["misDireccionesRecojo"] = $this->recojo->buscarPor('fecha', 'desc', '', '', 337, $usuarioSesion->idusuario, 0, 0, 0);
			// $data["misDireccionesDestino"] = $this->destino->buscarPor('fecha', 'desc', '', '', 223, $usuarioSesion->idusuario, 0, 0, 0, 0);
			$data["misComprobantes"] = $this->comprobante->buscarPor('fecha', 'desc', '', '', 415, $usuarioSesion->idusuario, 445, 0, 0);
		} else {
			// Definir variables para que existan en la vista y no den error
			$data["misDireccionesRecojo"] = [];
			// $data["misDireccionesDestino"] = [];
			$data["misComprobantes"] = [];
		}
		$data["formaPagos"] = $this->formaPago->buscarPor("orden", "asc", "", "", 365, 0, 0, 0, 0);
		$data["tipoComprobantes"] = $this->parametro->buscarPor("", "",  104, 243, 0, 0);
		// $data["tiendas"] = $this->tienda->buscarPor("", "", "", "", 391, 0, 0, 0, 0);

		// var_dump($data["tipoComprobantes"]);
		$this->front_views("front/body/carritoCompras", $data);
	}

	public function blog()
	{
		if ($this->mantenimiento->valor == 'si' || $this->mantenimiento->valor == 'Si' || $this->mantenimiento->valor == 'SI') {
			$data["seccion"] = "inicio";
			$data["titulo"] = "Pelucas Perú - Marca N° 1 de pelcuas del Perú";
			$data["url"] = "";
			return view('front/body/mantenimiento');
		}
		$data["seccion"] = "blog";
		// $ordencriterio = '', $ordentipo = '', $parametro = '', $valor = '', $idestado = 0, $inicio = 0, $registros = 0
		$data["noticiacategorias"] = $this->noticiaCategoria->buscarPor('orden', 'ASC', '', '', 421, 0, 8);
		// $data["noticias"] = $this->noticia->buscarPor('orden', 'ASC', '', '', 423, 0, 576, 0, 8);

		// var_dump($data["noticiacategorias"]);
		$this->front_views("front/body/blog", $data);
	}

	public function blogDetalle($urlamigable)
	{
		if ($this->mantenimiento->valor == 'si' || $this->mantenimiento->valor == 'Si' || $this->mantenimiento->valor == 'SI') {
			$data["seccion"] = "inicio";
			$data["titulo"] = "Pelucas Perú - Marca N° 1 de pelcuas del Perú";
			$data["url"] = "";
			return view('front/body/mantenimiento');
		}

		$data["seccion"] = "blog";
		if ($urlamigable) {
			$data['blogdetalle'] = $this->noticia->obtenerPorUrlAmigable($urlamigable);
			// $ordencriterio , $ordentipo , $parametro , $valor , $idestado = 0, $idnoticiacategoria = 0, $idpdestacado = 0, $inicio = null, $registros = null
			$data['noticias'] = $this->noticia->buscarPor("orden", "asc", "", "", 407, $data['blogdetalle']->idnoticiacategoria, 0, 0, 4);
			$data["configuracionBanner"] = $this->configuracion->obtenerPorId(41);
			$data["configuracionBannerMovil"] = $this->configuracion->obtenerPorId(42);
		} else {
			return redirect()->to(base_url('blog'));
		}
		// var_dump($data['blogdetalle']);
		// die();



		$this->front_views("front/body/blogDetalle", $data);
	}

	public function miCuenta()
	{
		if ($this->mantenimiento->valor == 'si' || $this->mantenimiento->valor == 'Si' || $this->mantenimiento->valor == 'SI') {
			$data["seccion"] = "inicio";
			$data["titulo"] = "Pelucas Perú - Marca N° 1 de pelcuas del Perú";
			$data["url"] = "";
			return view('front/body/mantenimiento');
		}
		$data["seccion"] = "miCuenta";

		$usuarioSesion = session()->get('usuarioSesion');
		if ($usuarioSesion) {
			$data["seccion"] = "cuenta";
			$data["codigoVerificacion"] = random_int(1000, 9999);
			$data["url"] = 'mi-cuenta';
			$newDestino = new DestinoModel();
			$newAgencia = new AgenciaModel();
			$newRecojo = new RecojoModel();
			$newComprobante = new ComprobanteModel();

			$data['agencias'] = (object) $newAgencia->agenciaFind('fecha', 'desc', '', '', 367, $usuarioSesion->idusuario, 0, 0, 0);
			$data['destinos'] = $newDestino->buscarPor('fecha', 'desc', '', '', 243, $usuarioSesion->idusuario, 0, 0, 0);
			// $data['recojos'] = $newRecojo->buscarPor('fecha', 'desc', '', '', 335, $usuarioSesion->idusuario, 0, 0, 0);
			$data['comprobantes'] = $newComprobante->buscarPor('fecha', 'desc', '', '', 363, $usuarioSesion->idusuario, 0, 0, 0);
			$data['usuario'] = $usuarioSesion;
			// var_dump($data['comprobantes']);
			// die();	
			$this->front_views("front/body/miCuenta", $data);
		} else {
			return redirect()->to(base_url());
		}
		// $this->front_views("front/body/miCuenta", $data);
	}

	public function miCuentaEditar()
	{
		if ($this->mantenimiento->valor == 'si' || $this->mantenimiento->valor == 'Si' || $this->mantenimiento->valor == 'SI') {
			$data["seccion"] = "inicio";
			$data["titulo"] = "Pelucas Perú - Marca N° 1 de pelcuas del Perú";
			$data["url"] = "";
			return view('front/body/mantenimiento');
		}
		$data["seccion"] = "miCuentaEditar";

		$usuarioSesion = session()->get('usuarioSesion');
		$editarDatosSesion = session()->get('editarDatos');
		$data["seccion"] = "miCuenta";
		if ($usuarioSesion && $editarDatosSesion && (time() - $editarDatosSesion) < 300) {

			$data['pdocumentos'] = $this->parametro->buscarPor('', '', 104, 293, 0, 0);
			$data["codigoVerificacion"] = random_int(1000, 9999);
			$data["url"] = 'mi-cuenta-editar';
			$data["seccion"] = "cuenta";
			$data['usuario'] = $usuarioSesion;
			// var_dump($data['usuario']);
			$this->front_views("front/body/miCuentaEditar", $data);
		} else {
			return redirect()->to(base_url());
		}

		// $this->front_views("front/body/miCuentaEditar", $data);
	}

	public function misPedidos()
	{
		if ($this->mantenimiento->valor == 'si' || $this->mantenimiento->valor == 'Si' || $this->mantenimiento->valor == 'SI') {
			$data["seccion"] = "inicio";
			$data["titulo"] = "Pelucas Perú - Marca N° 1 de pelcuas del Perú";
			$data["url"] = "";
			return view('front/body/mantenimiento');
		}
		$data["seccion"] = "misPedidos";
		$usuarioSesion = session()->get('usuarioSesion');
		if ($usuarioSesion) {
			$data["seccion"] = "pedidos";
			$data["url"] = 'mis-pedidos';
			$data['ppagos'] = $this->parametro->buscarPor('', '', 104, 288, 0, 0);
			$data['estados'] = $this->estado->buscarPor('orden', 'asc', '', '', 283, 0, 0);
			$data['usuario'] = $usuarioSesion;

			// var_dump($data['ppagos']);
			$this->front_views("front/body/misPedidos", $data);

			// $this->front_views("front/body/misPedidos", $data);

		} else {
			return redirect()->to(base_url());
		}
		// $this->front_views("front/body/misPedidos", $data);
	}

	public function misPedidosDetalle($idPedido)
	{

		if ($this->mantenimiento->valor == 'si' || $this->mantenimiento->valor == 'Si' || $this->mantenimiento->valor == 'SI') {
			$data["seccion"] = "inicio";
			$data["titulo"] = "Pelucas Perú - Marca N° 1 de pelcuas del Perú";
			$data["url"] = "";
			return view('front/body/mantenimiento');
		}
		$data["seccion"] = "misPedidosDetalle";
		$usuarioSesion = session()->get('usuarioSesion');
		if ($usuarioSesion) {
			$data['pedido'] = (object)$this->pedidoModel->obtenerByIdPedidoIdUsuario($idPedido, $usuarioSesion->idusuario);
			if ($data['pedido']) {
				$data["seccion"] = "pedidos";
				$data['pedidoDetalles'] = (object) $this->pedidoDetalleModel->pedidoDetalleFind(
					'',
					'',
					'',
					'',
					$data['pedido']->idpedido,
					0,
					0,
					0
				);
				$data["url"] = 'pedido/' . $idPedido;

				$data['usuario'] = $usuarioSesion;
				$data["WSPVENTA"] = $this->configuracion->obtenerPorId(40);

				// var_dump($data['pedido']);
				// die();
				$this->front_views("front/body/misPedidosDetalle", $data);


				// $this->front_views("front/body/misPedidosDetalle", $data);
			} else {
				return redirect()->to(base_url());
			}
		} else {
			return redirect()->to(base_url());
		}


		// $this->front_views("front/body/misPedidosDetalle", $data);
	}

	public function registrarme()
	{
		$data["seccion"] = "registrarme";

		$this->front_views("front/body/registrarme", $data);
	}

	public function contactenos()
	{
		$data["seccion"] = "contactenos";

		$this->front_views("front/body/contactenos", $data);
	}

	public function setSesionEditarDatos()
	{
		$codigo = $this->request->getPost("codigo");
		if ($_SESSION['codigo_editarMisDatos'] == $codigo) {
			session()->set('editarDatos', time());
		}
		return $this->response->setJSON(["status" => "exito"]);
	}

	public function creaCaptcha()
	{
		ob_start();

		$permitted_chars = 'ABCDEFGHJKLMNPQRSTUVWXYZ';

		$image = imagecreatetruecolor(200, 50);

		imageantialias($image, true);

		$colors = [];

		$red = rand(125, 175);
		$green = rand(125, 175);
		$blue = rand(125, 175);

		for ($i = 0; $i < 5; $i++) {
			$colors[] = imagecolorallocate($image, $red - 20 * $i, $green - 20 * $i, $blue - 20 * $i);
		}

		imagefill($image, 0, 0, $colors[0]);

		for ($i = 0; $i < 10; $i++) {
			imagesetthickness($image, rand(2, 10));
			$line_color = $colors[rand(1, 4)];
			imagerectangle($image, rand(-10, 190), rand(-10, 10), rand(-10, 190), rand(40, 60), $line_color);
		}

		$black = imagecolorallocate($image, 0, 0, 0);
		$white = imagecolorallocate($image, 255, 255, 255);
		$textcolors = [$black, $white];

		// $fonts = [dirname(dirname(dirname(__FILE__))) . '/public/template/fonts/Roboto-Regular.ttf', dirname(dirname(dirname(__FILE__))) . '/public/template/fonts/Roboto-Bold.ttf'];
		$fonts = [FCPATH . 'template/fonts/Roboto-Regular.ttf', FCPATH . 'template/fonts/Roboto-Bold.ttf'];
		// echo FCPATH . 'template/fonts/Roboto-Regular.ttf';
		// exit;
		$string_length = 6;
		$captcha_string = $this->generate_string($permitted_chars, $string_length);



		// $_SESSION['captcha_text'] = $captcha_string;

		$this->session->set('captcha_text', $captcha_string);


		// Llamada a session_write_close() para asegurar que la sesi贸n se guarda
		session_write_close();  // Esto asegura que los cambios en la sesi贸n se guardan antes
		for ($i = 0; $i < $string_length; $i++) {
			$letter_space = 170 / $string_length;
			$initial = 15;

			imagettftext($image, 24, rand(-15, 15), $initial + $i * $letter_space, rand(25, 45), $textcolors[rand(0, 1)], $fonts[array_rand($fonts)], $captcha_string[$i]);
		}

		header('Content-type: image/png');
		imagepng($image);
		imagedestroy($image);
		exit;
	}

	function generate_string($input, $strength = 10)
	{
		$input_length = strlen($input);
		$random_string = '';
		for ($i = 0; $i < $strength; $i++) {
			$random_character = $input[mt_rand(0, $input_length - 1)];
			$random_string .= $random_character;
		}

		return $random_string;
	}





	public function generaToken()
	{
		try {
			helper('filesystem');
			$session = session();

			// Capturar datos JSON correctamente
			$data = $this->request->getJSON(true); // true = devuelve array asociativo

			log_message('debug', '📦 [IZIPAY] Iniciando generaToken()');
			log_message('debug', '📝 Datos recibidos del frontend: ' . json_encode($data, JSON_PRETTY_PRINT));

			$session->set('pedido', $data);

			// Asignar variables
			$operacion  = $data['codigo']     ?? null;
			$documento  = $data['documento']  ?? null;
			$correo     = $data['correo']     ?? null;
			$telefono   = $data['telefono']   ?? null;
			$nombres    = $data['nombres']    ?? null;
			$pApellido  = $data['pApellido']  ?? null;
			$total      = $data['total']      ?? null;

			if (empty($operacion) || empty($total)) {
				log_message('error', '❌ [IZIPAY] Código o total vacío.');
				return $this->response->setJSON([
					'status' => 'error',
					'mensaje' => 'Datos insuficientes.'
				]);
			}
			$amount = number_format(floatval($total), 2, "", "");

			$key    = env('KEY');
			$pubKey = env('PUBLIC_KEY');

			if (empty($key) || empty($pubKey)) {
				log_message('error', '❌ [IZIPAY] Claves de entorno vacías. Verifica KEY y PUBLIC_KEY en .env');
				return $this->response->setJSON(['status' => 'error', 'mensaje' => 'Faltan credenciales de Izipay.']);
			}

			log_message('debug', '🔑 KEY cargada correctamente.');

			$pedidoTemporal = $this->guardarPedidoTemporal();

			if (!isset($pedidoTemporal->status) || $pedidoTemporal->status !== 'exito') {
				log_message('error', '❌ [IZIPAY] Error al guardar pedido temporal: ' . json_encode($pedidoTemporal));
				return $this->response->setJSON(['status' => 'error', 'mensaje' => 'No se pudo guardar el pedido temporal.']);
			}

			log_message('debug', '📝 Pedido temporal guardado correctamente: ' . json_encode($pedidoTemporal));

			$filtro = [
				"amount"   => $amount,
				"currency" => 'PEN',
				"orderId"  => $operacion,

				"customer" => [
					"email" => $correo,
					"reference" => $documento,
					"billingDetails" => [
						"cellPhoneNumber" => $telefono,
						"firstName" => $nombres,
						"lastName" => $pApellido,
					],
					"shoppingCart" => [
						"cartItemInfo" => [
							[
								"productRef"    => $operacion,
								"productAmount" => $amount,
								"productLabel"  => $operacion,
								"productQty"    => "1"
							]
						]
					]

				]
			];

			log_message('debug', '📤 Enviando solicitud a Izipay: ' . json_encode($filtro));

			// Generar token Izipay usando cURL
			$curl = curl_init();
			curl_setopt_array($curl, [
				CURLOPT_URL => 'https://api.micuentaweb.pe/api-payment/V4/Charge/CreatePayment',
				// CURLOPT_URL => 'https://sandbox.api.izipay.pe/v4/WebService/Charge/CreatePayment',

				CURLOPT_RETURNTRANSFER => true,
				CURLOPT_ENCODING => "",
				CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
				CURLOPT_CUSTOMREQUEST => "POST",
				CURLOPT_HTTPHEADER => [
					"Accept: application/json",
					"Content-Type: application/json",
					'Authorization: Basic ' . base64_encode($key)
				],
				CURLOPT_POSTFIELDS => json_encode($filtro)
			]);

			$responseRaw = curl_exec($curl);
			$curlError   = curl_error($curl);
			curl_close($curl);

			if ($curlError) {
				log_message('error', '❌ [IZIPAY] Error CURL: ' . $curlError);
				return $this->response->setJSON(['status' => 'error', 'mensaje' => 'Error de conexión con Izipay.']);
			}

			log_message('debug', '📥 Respuesta cruda de Izipay: ' . $responseRaw);

			$response = json_decode($responseRaw);

			if (!isset($response->answer->formToken)) {
				log_message('error', '❌ [IZIPAY] Respuesta inválida de Izipay: ' . json_encode($response));
				return $this->response->setJSON(['status' => 'error', 'mensaje' => 'Error al generar token.']);
			}

			log_message('debug', '✅ Token generado correctamente: ' . $response->answer->formToken);

			$data = [
				'peticion'      => $response,
				'token'         => $response->answer->formToken,
				'publicKey'     => $pubKey,
				'lenguajeform'  => 'es-ES',
				'status'        => 'exito',
				'pedido'        => $pedidoTemporal->pedido ?? null
			];

			return $this->response->setJSON($data);
		} catch (\Throwable $th) {
			log_message('critical', '💥 [IZIPAY] Excepción en generaToken(): ' . $th->getMessage());
			log_message('critical', '🧱 Trace: ' . $th->getTraceAsString());

			return $this->response->setJSON([
				'status' => 'error',
				'mensaje' => 'Error interno en el servidor'
			]);
		}
	}

	private function guardarPedidoTemporal()
	{
		$pedido = session()->get('pedido');

		// Log inicial
		log_message('info', '🟢 Iniciando guardarPedidoTemporal');
		log_message('info', '📦 Datos del pedido: ' . json_encode($pedido));

		try {
			$client = \Config\Services::curlrequest([
				'baseURI' => "https://pelucasperu.com/",
			]);

			log_message('info', '🌐 Enviando solicitud POST a API: api/publico/pedido/guardar-izipay');

			$res = $client->request('POST', 'api/publico/pedido/guardar-izipay', [
				'json'    => $pedido,
				'headers' => ["Accept" => "application/json"]
			]);

			$statusCode = $res->getStatusCode();
			$body = $res->getBody();

			log_message('info', "✅ Respuesta HTTP {$statusCode}");
			log_message('info', "📨 Cuerpo de respuesta: {$body}");

			return json_decode($body);
		} catch (\Throwable $th) {
			// Captura el error y lo registra con el mensaje completo
			log_message('error', '❌ Error en guardarPedidoTemporal: ' . $th->getMessage());
			log_message('error', '🧩 Trace: ' . $th->getTraceAsString());

			return json_decode(json_encode(['status' => 'error', 'error' => $th->getMessage()]));
		}
	}


	// public function generaToken()
	// {
	// 	try {
	// 		helper('filesystem');
	// 		$session = session();

	// 		// Capturar datos del frontend
	// 		$data = $this->request->getJSON(true);
	// 		log_message('debug', '📦 [IZIPAY] Iniciando generaToken()');
	// 		log_message('debug', '📝 Datos recibidos del frontend: ' . json_encode($data, JSON_PRETTY_PRINT));

	// 		$session->set('pedido', $data);

	// 		// Validación básica
	// 		$operacion  = $data['codigo']     ?? null;
	// 		$documento  = $data['documento']  ?? null;
	// 		$correo     = $data['correo']     ?? null;
	// 		$telefono   = $data['telefono']   ?? null;
	// 		$nombres    = $data['nombres']    ?? null;
	// 		$pApellido  = $data['pApellido']  ?? null;
	// 		$total      = $data['total']      ?? null;

	// 		if (empty($operacion) || empty($total)) {
	// 			log_message('error', '❌ [IZIPAY] Código o total vacío.');
	// 			return $this->response->setJSON(['status' => 'error', 'mensaje' => 'Datos insuficientes.']);
	// 		}

	// 		$amount = number_format(floatval($total), 2, '.', '');
	// 		$currency = 'PEN';

	// 		// 🔐 Credenciales desde .env
	// 		$secretKey     = env('IZIPAY_SECRET_KEY');  // antes KEY
	// 		$publicKey     = env('IZIPAY_PUBLIC_KEY');  // antes PUBLIC_KEY
	// 		$merchantCode  = env('IZIPAY_MERCHANT_CODE');

	// 		if (empty($secretKey) || empty($publicKey) || empty($merchantCode)) {
	// 			log_message('error', '❌ [IZIPAY] Faltan credenciales en .env');
	// 			return $this->response->setJSON(['status' => 'error', 'mensaje' => 'Faltan credenciales de Izipay.']);
	// 		}

	// 		// 🧾 Guardar pedido temporal antes del pago
	// 		$pedidoTemporal = $this->guardarPedidoTemporal();

	// 		if (!isset($pedidoTemporal->status) || $pedidoTemporal->status !== 'exito') {
	// 			log_message('error', '❌ [IZIPAY] Error al guardar pedido temporal: ' . json_encode($pedidoTemporal));
	// 			return $this->response->setJSON(['status' => 'error', 'mensaje' => 'No se pudo guardar el pedido temporal.']);
	// 		}

	// 		// 🧮 Generar firma HMAC SHA256
	// 		$signatureData = $merchantCode . $operacion . $currency . $amount;
	// 		$signature = base64_encode(hash_hmac('sha256', $signatureData, $secretKey, true));

	// 		log_message('debug', '🔏 Firma generada correctamente.');

	// 		// 📦 Estructura de datos para el frontend
	// 		$payload = [
	// 			'status'        => 'exito',
	// 			'merchantCode'  => $merchantCode,
	// 			'publicKey'     => $publicKey,
	// 			'signature'     => $signature,
	// 			'order' => [
	// 				'orderNumber' => $operacion,
	// 				'currency'    => $currency,
	// 				'amount'      => $amount,
	// 				'description' => 'Compra en PelucasPeru',
	// 			],
	// 			'buyer' => [
	// 				'firstName'      => $nombres,
	// 				'lastName'       => $pApellido,
	// 				'email'          => $correo,
	// 				'documentType'   => 'DNI',
	// 				'documentNumber' => $documento,
	// 				'phoneNumber'    => $telefono,
	// 			],
	// 			'pedido' => $pedidoTemporal->pedido ?? null
	// 		];

	// 		log_message('debug', '✅ [IZIPAY] Token listo para el frontend: ' . json_encode($payload));
	// 		return $this->response->setJSON($payload);
	// 	} catch (\Throwable $th) {
	// 		log_message('critical', '💥 [IZIPAY] Error en generaToken(): ' . $th->getMessage());
	// 		log_message('critical', '🧱 Trace: ' . $th->getTraceAsString());

	// 		return $this->response->setJSON([
	// 			'status' => 'error',
	// 			'mensaje' => 'Error interno en el servidor'
	// 		]);
	// 	}
	// }

	// private function guardarPedidoTemporal()
	// {
	// 	$pedido = session()->get('pedido');

	// 	log_message('info', '🟢 Iniciando guardarPedidoTemporal');
	// 	log_message('info', '📦 Datos del pedido: ' . json_encode($pedido));

	// 	try {
	// 		$client = \Config\Services::curlrequest([
	// 			'baseURI' => "https://pelucasperu.com/",
	// 		]);

	// 		$res = $client->request('POST', 'api/publico/pedido/guardar-izipay', [
	// 			'json'    => $pedido,
	// 			'headers' => ["Accept" => "application/json"]
	// 		]);

	// 		$statusCode = $res->getStatusCode();
	// 		$body = $res->getBody();

	// 		log_message('info', "✅ Respuesta HTTP {$statusCode}");
	// 		log_message('info', "📨 Cuerpo de respuesta: {$body}");

	// 		return json_decode($body);
	// 	} catch (\Throwable $th) {
	// 		log_message('error', '❌ Error en guardarPedidoTemporal: ' . $th->getMessage());
	// 		log_message('error', '🧩 Trace: ' . $th->getTraceAsString());

	// 		return json_decode(json_encode(['status' => 'error', 'error' => $th->getMessage()]));
	// 	}
	// }
}
