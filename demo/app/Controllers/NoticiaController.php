<?php

namespace App\Controllers;

use App\Entities\Noticia;
use App\Entities\Proyecto;
use App\Helpers\Paginator;

class NoticiaController extends BaseController
{

	public function noticias()
	{
		$ordenCriterio = $this->request->getPost("ordenCriterio") ?: "";
		$ordenTipo = $this->request->getPost("ordenTipo") ?: "";
		$parametro = $this->request->getPost("parametro") ?: "";
		$valor = $this->request->getPost("valor") ?: "";
		$idnoticiacategoria = $this->request->getPost("idNoticiaCategoria") ?: "";
		$pagina = $this->request->getPost("pagina") ?: 0;
		$registros = $this->request->getPost("registros") ?: 0;

		$inicio = ($pagina - 1) * $registros;

		$total = Noticia::buscarTotalPor($parametro, $valor, 25,$idnoticiacategoria) ?: 0;

		$paginator = new Paginator($pagina, $registros, $total);

		$lista = Noticia::buscarPor($ordenCriterio, $ordenTipo, $parametro, $valor, 25,$idnoticiacategoria, $inicio, intval($registros));

		$data = array(
			"content" => $lista,
			"paginator" => $paginator->enviar(),
		);

		echo json_encode($data);
	}
}
