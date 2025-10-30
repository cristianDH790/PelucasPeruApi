<?php

namespace App\Controllers\Api\Base;

use App\Helpers\Paginator;
use App\Models\EstadoModel;
use App\Models\ParametroModel;
use App\Models\TipoModel;

use CodeIgniter\RESTful\ResourceController;

class ParametroController extends ResourceController
{
    protected $parametro;
    protected $estado;
    protected $tipo;
    protected $session;
    public function __construct()
    {
        $this->parametro = new ParametroModel();
        $this->estado = new EstadoModel();
        $this->tipo = new TipoModel();
        $this->session = session();
    }

    public function listar()
    {

        $request = $this->request;

        $ordenCampo = $request->getVar('ordenCampo') ?? 'nombre';
        $ordenTipo = $request->getVar('ordenTipo') ?? 'asc';
        $idtipo = (int) ($request->getVar('idTipo') ?? 0);
        $idestado = (int) ($request->getVar('idEstado') ?? 0);
        // Parámetros de paginación
        $pagina = (int) ($request->getVar('pagina') ?? 1);
        $registros = (int) ($request->getVar('registros') ?? 10);

        // Total de registros
        $total = $this->parametro->buscarPorTotal($idestado, $ordenTipo);
        // Paginador
        $inicio = ($pagina - 1) * $registros;
        $paginator = new Paginator($pagina, $registros, $total);
        // Obtener lista de parametros
        $parametros = $this->parametro->buscarPor(
            $ordenCampo,
            $ordenTipo,
            $idestado,
            $idtipo,
            $paginator->getFirstElement(),
            $paginator->getSize()
        );

        $resultado = [];
        foreach ($parametros as $row) {

            $parametroEntity = new \App\Entities\ParametroEntity($row);


            //Relaciones
           // $parametroEntity->tipo = $this->tipo->obtenerPorId($row->idtipo);
            $parametroEntity->estado = $this->estado->obtenerPorId($row->idestado);

            $resultado[] = $parametroEntity->toArray();
        }

        return $this->respond([
            'paginator' => $paginator->enviar(),
            'content' => $resultado
        ]);
    }
}
