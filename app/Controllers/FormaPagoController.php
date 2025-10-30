<?php

namespace App\Controllers;

use App\Helpers\Paginator;
use App\Models\FormaPagoModel;

class FormaPagoController extends BaseController
{

	protected $formaPagoModel;

	public function __construct()
	{
		$this->formaPagoModel = new FormaPagoModel();
	}


	public function formaPagoPorIdFormaPago()
	{
		$idFormaPago = $this->request->getPost("idFormaPago") ?: "";

		$formapago = $this->formaPagoModel->obtenerPorId($idFormaPago);

		if ($formapago && $formapago->idestado == 365) {
			return $this->response->setJSON($formapago);	
		}
	}

	public function getFormaPagos()
	{
		$ordenCriterio = $this->request->getPost("ordenCriterio") ?: "";
		$ordenTipo = $this->request->getPost("ordenTipo") ?: "";
		$parametro = $this->request->getPost("parametro") ?: "";
		$valor = $this->request->getPost("valor") ?: "";
		$pagina = $this->request->getPost("pagina") ?: 0;
		$registros = $this->request->getPost("registros") ?: 0;

		$inicio = ($pagina - 1) * $registros;

		$total = $this->formaPagoModel->buscarTotalPor($parametro, $valor, 365, 0, 0) ?: 0;

		$paginator = new Paginator($pagina, $registros, $total);

		$lista = $this->formaPagoModel->buscarPor($ordenCriterio, $ordenTipo, $parametro, $valor, 365, 0, 0, $inicio, intval($registros));

		$data = array(
			"lista" => $lista,
			"paginator" => $paginator->enviar(),
		);

		return $this->response->setJSON($data);
	}
}
