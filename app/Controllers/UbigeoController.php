<?php

namespace App\Controllers;

use App\Entities\Marca;
use App\Entities\Producto;
use App\Entities\ProductoPrecio;
use App\Entities\Slider;
use App\Entities\Ubigeo;
use App\Entities\ZonaReparto;
use App\Helpers\Paginator;
use App\Models\UbigeoModel;
use App\Models\ZonaRepartoModel;

class UbigeoController extends BaseController
{

	protected $ubigeo;
	protected $zona;
	public function __construct()
	{
		$this->ubigeo = new UbigeoModel();
		$this->zona = new ZonaRepartoModel();
	}
	
	public function checkEntregaUbigeo()
	{

		$codigoPostal = $this->request->getPost('codigoPostal');

		
		$ubi = $this->ubigeo ->obtenerByCodigoPostal($codigoPostal);
		
		$zonareparto = $this->zona->obtenerByCodigoPostal($codigoPostal);
		if ($zonareparto && $ubi) {
			$data = [
				"encontrado" => true,
				"idUbigeo" => $ubi->idubigeo,
				"zonaReparto" => $zonareparto
			];
		} else {
			$data = [
				"encontrado" => false,
			];
		}
		return $this->response->setJSON($data);
	}
// $ordencriterio, $ordentipo,  $parametro, $valor, $idrubigeo, $registros, $inicio
	public function getUbigeos()
	{
		$ordenCriterio = $this->request->getPost("ordenCriterio") ?: "";
		$ordenTipo = $this->request->getPost("ordenTipo") ?: "";
		$parametro = $this->request->getPost("parametro") ?: "";
		$valor = $this->request->getPost("valor") ?: "";
		$idrUbigeo = $this->request->getPost("idrUbigeo") ?: "";
		$pagina = $this->request->getPost("pagina") ?: 0;
		$registros = $this->request->getPost("registros") ?: 0;

		$inicio = ($pagina - 1) * $registros;

		$total = $this->ubigeo->buscarTotalPor( $parametro, $valor,$idrUbigeo) ?: 0;

		$paginator = new Paginator($pagina, $registros, $total);

		$lista =$this->ubigeo->buscarPor($ordenCriterio, $ordenTipo, $parametro, $valor, $idrUbigeo, $inicio, intval($registros));

		$data = array(
			"lista" => $lista,
			"paginator" => $paginator->enviar(),
		);

		return $this->response->setJSON($data);
	}
}
