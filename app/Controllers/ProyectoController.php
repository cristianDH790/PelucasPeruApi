<?php

namespace App\Controllers;

use App\Entities\Proyecto;
use App\Helpers\Paginator;

class ProyectoController extends BaseController
{

	public function proyectos()
	{
		$ordenCriterio = $this->request->getPost("ordenCriterio") ?: "";
		$ordenTipo = $this->request->getPost("ordenTipo") ?: "";
		$parametro = $this->request->getPost("parametro") ?: "";
		$valor = $this->request->getPost("valor") ?: "";
		$idPcategoria = $this->request->getPost("idPcategoria") ?: 0;
		$idCliente = $this->request->getPost("idCliente") ?: 0;
		$pagina = $this->request->getPost("pagina") ?: 0;
		$registros = $this->request->getPost("registros") ?: 0;

		$inicio = ($pagina - 1) * $registros;

		$total = Proyecto::buscarTotalPor($parametro, $valor,21,$idPcategoria,$idCliente) ?: 0;

		$paginator = new Paginator($pagina, $registros, $total);

		$lista = Proyecto::buscarPor($ordenCriterio, $ordenTipo, $parametro, $valor, 21,$idPcategoria,$idCliente,$inicio, intval($registros));

		$data = array(
			"content" => $lista,
			"paginator" => $paginator->enviar(),
		);

		return $this->response->setJSON($data);
	}
}
