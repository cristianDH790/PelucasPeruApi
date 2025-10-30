<?php

namespace App\Controllers\Api;

use App\Entities\TiendaEntity;
use App\Helpers\Paginator;
use App\Models\EstadoModel;
use App\Models\TiendaModel;
use App\Models\UbigeoModel;
use App\Validation\TiendaValidation;
use CodeIgniter\RESTful\ResourceController;

class TiendaController extends ResourceController
{
    protected $tienda;
    protected $estado;
    protected $ubigeo;

    public function __construct()
    {
        $this->tienda = new TiendaModel();
        $this->estado = new EstadoModel();
        $this->ubigeo = new UbigeoModel();
    }

    public function obtenerPorId($idTienda)
    {
        $tienda = $this->tienda->find($idTienda);
        if (!$tienda) {
            return $this->failNotFound('No existe la tienda solicitada');
        }

        $tiendaEntity = new TiendaEntity($tienda);
        $tiendaEntity->estado = $this->estado->find($tienda->idestado);
        $tiendaEntity->ubigeo = $this->ubigeo->find($tienda->idubigeo);

        return $this->respond($tiendaEntity->toArray(), 200);
    }

    public function listar()
    {
        if (!$this->request->is('post')) {
            return $this->fail('Método no permitido. Se requiere POST.', 405);
        }

        $request = $this->request;

        $ordencriterio = $request->getVar('ordenCriterio') ?? 'nombre';
        $ordentipo = $request->getVar('ordenTipo') ?? 'asc';
        $parametro = $request->getVar('parametro') ?? '';
        $valor = $request->getVar('valor') ?? '';
        $idestado = (int) $request->getVar('idEstado') ?? 0;
        $idubigeo = (int) $request->getVar('idUbigeo') ?? 0;
        $pagina = (int) $request->getVar('pagina') ?? 1;
        $registros = (int) $request->getVar('registros') ?? 10;

        $total = $this->tienda->buscarPorTotal($parametro, $valor, $idestado, $idubigeo);
        $paginator = new Paginator($pagina, $registros, $total);

        $tiendas = $this->tienda->buscarPor(
            $ordencriterio,
            $ordentipo,
            $parametro,
            $valor,
            $idestado,
            $idubigeo,
            $paginator->getFirstElement(),
            $paginator->getSize()
        );

        $resultado = [];
        foreach ($tiendas as $row) {
            $tiendaEntity = new TiendaEntity($row);
            $tiendaEntity->estado = $this->estado->find($row->idestado);
            $tiendaEntity->ubigeo = $this->ubigeo->find($row->idubigeo);
            $resultado[] = $tiendaEntity->toArray();
        }

        return $this->respond([
            'paginator' => $paginator->enviar(),
            'content' => $resultado
        ]);
    }

    public function guardar()
    {
        $data = $this->request->getJSON(true);
        $validation = new TiendaValidation();
        $errores = $validation->tiendaGuardarValidation($data);
        if (!empty($errores)) {
            return $this->response
                ->setStatusCode(422)
                ->setJSON(['errors' => $errores]);
        }

        $datos = [
            'idestado' => $data['estado']['idEstado'],
            'idubigeo' => $data['ubigeo']['idUbigeo'],
            'nombre' => $data['nombre'],
            'telefono' => $data['telefono'],
            'direccion' => $data['direccion'],
            'horario1' => $data['horario1'],
            'horario2' => $data['horario2'],
            'horario3' => $data['horario3'],
            'delivery' => $data['delivery'],
            'horainicio' => $data['horaInicio'],
            'horatermino' => $data['horaTermino'],
            'latitud' => $data['latitud'],
            'longitud' => $data['longitud'],
            'urlimagen' => null,
            'ventaxmayor' => $data['ventaXmayor'],
            'orden' => $data['orden']
        ];

        $idTienda = $this->tienda->insert($datos, true);
        $tienda = $this->tienda->find($idTienda);

        $tiendaEntity = new TiendaEntity($tienda);
        $tiendaEntity->estado = $this->estado->find($tienda->idestado);
        $tiendaEntity->ubigeo = $this->ubigeo->find($tienda->idubigeo);

        return $this->respond([
            'mensaje' => 'Tienda registrada con éxito',
            'tienda' => $tiendaEntity->toArray()
        ], 201);
    }

    public function actualizar($idTienda)
    {
        $data = $this->request->getJSON(true);
        $validation = new TiendaValidation();
        $errores = $validation->tiendaActualizarValidation($data);

        if (!empty($errores)) {
            return $this->response
                ->setStatusCode(422)
                ->setJSON(['errors' => $errores]);
        }

        $tienda = $this->tienda->find($idTienda);
        if (!$tienda) {
            return $this->failNotFound('No existe la tienda solicitada');
        }

        $datos = [
            'idestado' => $data['estado']['idEstado'],
            'idubigeo' => $data['ubigeo']['idUbigeo'],
            'nombre' => $data['nombre'],
            'telefono' => $data['telefono'],
            'direccion' => $data['direccion'],
            'horario1' => $data['horario1'],
            'horario2' => $data['horario2'],
            'horario3' => $data['horario3'],
            'delivery' => $data['delivery'],
            'horainicio' => $data['horaInicio'],
            'horatermino' => $data['horaTermino'],
            'latitud' => $data['latitud'],
            'longitud' => $data['longitud'],
            'ventaxmayor' => $data['ventaXmayor'],
            'orden' => $data['orden']
        ];

        $this->tienda->update($idTienda, $datos);
        $tienda = $this->tienda->find($idTienda);

        $tiendaEntity = new TiendaEntity($tienda);
        $tiendaEntity->estado = $this->estado->find($tienda->idestado);
        $tiendaEntity->ubigeo = $this->ubigeo->find($tienda->idubigeo);

        return $this->respond([
            'mensaje' => 'Tienda actualizada con éxito',
            'tienda' => $tiendaEntity->toArray()
        ]);
    }

    public function eliminar($idTienda)
    {
        $tienda = $this->tienda->find($idTienda);

        if (!$tienda) {
            return $this->failNotFound('No existe la tienda solicitada');
        }

        if (!empty($tienda->urlimagen)) {
            $ruta = WRITEPATH . 'uploads/tienda/' . $tienda->urlimagen;
            if (file_exists($ruta)) {
                unlink($ruta);
            }
        }

        $this->tienda->delete($idTienda);

        return $this->respond(['mensaje' => 'Tienda eliminada con éxito']);
    }
}
