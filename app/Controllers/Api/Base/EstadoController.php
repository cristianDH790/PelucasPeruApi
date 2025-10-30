<?php

namespace App\Controllers\Api\Base;

use App\Controllers\BaseController;
use App\Entities\EstadoEntity;
use App\Helpers\Paginator;
use App\Models\ClaseModel;
use App\Models\EstadoModel;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\RESTful\ResourceController;

class EstadoController extends ResourceController
{
    protected $estado;
    protected $clase;
    protected $session;
    public function __construct()
    {
        $this->estado = new EstadoModel();
        $this->clase = new ClaseModel();
        $this->session = session();
    }

    public function listar()
    {
        $request = $this->request;


        $ordencriterio = $request->getVar('ordenCriterio') ?? 'nombres';
        $ordentipo = $request->getVar('ordenTipo') ?? 'asc';
        $parametro = $request->getVar('parametro') ?? '';
        $valor = $request->getVar('valor') ?? '';
        $idclase = (int) ($request->getVar('idClase') ?? 0);
        // Parámetros de paginación
        $pagina = (int) ($request->getVar('pagina') ?? 1);
        $registros = (int) ($request->getVar('registros') ?? 10);

        // Total de 
        // $parametro, $valor, $idclase
        $total = $this->estado->buscarPorTotal(
            $parametro,
            $valor,
            $idclase,
        );
        $paginator = new Paginator($pagina, $registros, $total);
        // $ordencriterio,$ordentipo,$parametro, $valor, $idclase, $inicio, $registros
        $estados = $this->estado->buscarPor(
            $ordencriterio,
            $ordentipo,
            $parametro,
            $valor,
            $idclase,
            $paginator->getFirstElement(),
            $paginator->getSize()
        );

        $resultado = [];
        foreach ($estados as $row) {
            $estadoEntity = new EstadoEntity();

            $estadoEntity->idestado = $row->idestado;
            $estadoEntity->idclase = $row->idclase;
            $estadoEntity->nombre = $row->nombre;
            $estadoEntity->descripcion = $row->descripcion;
            $estadoEntity->orden = $row->orden;
            $estadoEntity->fecha = $row->fecha;

            //Relaciones

            $estadoEntity->clase = $this->clase->obtenerPorId($row->idclase);
            $resultado[] = $estadoEntity->toArray();
        };
        return $this->respond([
            'paginator' => $paginator->enviar(),
            'content' => $resultado
        ]);
    }
}
