<?php

namespace App\Controllers;

use App\Entities\Entrega;
use App\Entities\FormaPago;
use App\Helpers\Paginator;
use App\Models\EntregaModel;

class EntregaController extends BaseController
{

	public function entregaPorIdEntrega()
	{
		$idEntrega = $this->request->getPost("idEntrega") ?: "";
		

		$EntregaModel = new EntregaModel();
		$entrega = $EntregaModel->obtenerPorId($idEntrega);

		if ($entrega && $entrega->idestado == 375) {
			return $this->response->setJSON($entrega);
		}
		return null;
	}
}
