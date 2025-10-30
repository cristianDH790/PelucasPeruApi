<?php

namespace App\Controllers\Api\Base;

use App\Controllers\BaseController;
use App\Entities\ClaseEntity;
use App\Helpers\Paginator;
use App\Models\clase;
use App\Models\ClaseModel;
use App\Models\EstadoModel;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\RESTful\ResourceController;

class ClaseController extends ResourceController
{
    protected $clase;
    protected $session;
    public function __construct()
    {
        $this->clase = new ClaseModel();
        $this->session = session();
    }



    public function listar()
    {

        $data = $this->request->getJSON(true);

        $ordencriterio = $data['ordenCriterio'] ?? '';
        $ordentipo = $data['ordenTipo'] ?? '';
        $parametro = $data['parametro'] ?? '';
        $valor = $data['valor'] ?? '';

        $idclase = isset($data['idClase']) ? (int)$data['idClase'] : 0;
        $registros = isset($data['registros']) ? (int)$data['registros'] : 10;
        $pagina = isset($data['pagina']) ? (int)$data['pagina'] : 1;


        // Total de 
        // $parametro, $valor, $idclase
        $total = $this->clase->buscarPorTotal(
            $parametro,
            $valor,
            $idclase,
        );
        $paginator = new Paginator($pagina, $registros, $total);
        // $ordencriterio,$ordentipo,$parametro, $valor, $idclase, $inicio, $registros
        $clases = $this->clase->buscarPor(
            $ordencriterio,
            $ordentipo,
            $parametro,
            $valor,
            $idclase,
            $paginator->getFirstElement(),
            $paginator->getSize()
        );
         $resultado = [];
        foreach ($clases as $row) {
            $claseEntity = new ClaseEntity($row);


            //Relaciones

            $claseEntity->rclase = $this->clase->obtenerPorId($row->idclase);
            $resultado[] = $claseEntity->toArray();
        };
        return $this->respond([
            'paginator' => $paginator->enviar(),
            'content' => $resultado
        ]);
    }
}
