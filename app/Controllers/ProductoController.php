<?php

namespace App\Controllers;

use App\Entities\Producto;
use App\Helpers\Paginator;

class ProductoController extends BaseController
{

	public function productos()
	{
		$ordenCriterio = $this->request->getPost("ordenCriterio") ?: "";
		$ordenTipo = $this->request->getPost("ordenTipo") ?: "";
		$parametro = $this->request->getPost("parametro") ?: "";
		$valor = $this->request->getPost("valor") ?: "";
		$idPdestacado = $this->request->getPost("idPdestacado") ?: 0;
		$pagina = $this->request->getPost("pagina") ?: 0;
		$registros = $this->request->getPost("registros") ?: 0;

		$inicio = ($pagina - 1) * $registros;

		$total = Producto::buscarTotalPor($parametro, $valor, 19, $idPdestacado) ?: 0;

		$paginator = new Paginator($pagina, $registros, $total);

		$lista = Producto::buscarPor($ordenCriterio, $ordenTipo, $parametro, $valor, 19, $idPdestacado, $inicio, intval($registros));

		$data = array(
			"content" => $lista,
			"paginator" => $paginator->enviar(),
		);

		return $this->response->setJSON($data);
	}
}
