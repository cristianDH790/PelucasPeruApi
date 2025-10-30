<?php

namespace App\Controllers\Api;

use App\Entities\UbigeoEntity;
use App\Helpers\Paginator;
use App\Models\EstadoModel;

use App\Models\UbigeoModel;

use App\Validation\UbigeoValidation;

use CodeIgniter\RESTful\ResourceController;

class UbigeoPublicoController extends ResourceController
{

    protected $ubigeo;
    protected $estado;
    protected $parametro;
    protected $ubigeoCategoria;

    public function __construct()
    {
        $this->ubigeo = new UbigeoModel();
        $this->estado = new EstadoModel();
    }

    public  function obtenerPorId($idubigeo)
    {
        $ubigeo = $this->ubigeo->obtenerPorId($idubigeo);

        if (!$ubigeo) {
            return $this->respond(['mensaje' => 'No existe la ubigeo solicitada'], 404);
        } else {

            $ubigeoEntity = new UbigeoEntity($ubigeo);



            // Relaciones
            $ubigeoEntity->estado = $this->estado->obtenerPorId($ubigeo->idestado);
            // $ubigeoEntity->rubigeo = $this->ubigeo->obtenerPorId($ubigeo->idrubigeo);
            // $ubigeoEntity->estado = $this->estado->obtenerPorId($row->idestado);

            // Nivel 1: padre inmediato (nivel 3)
            if (!empty($ubigeo->idrubigeo)) {
                $nivel1 = $this->ubigeo->obtenerPorId($ubigeo->idrubigeo);
                $ubigeoEntity->rubigeo = $nivel1;

                // Nivel 2: padre del padre (nivel 2)
                if ($nivel1 && !empty($nivel1->idrubigeo)) {
                    $nivel2 = $this->ubigeo->obtenerPorId($nivel1->idrubigeo);
                    $ubigeoEntity->rubigeo->rubigeo = $nivel2;

                    // Nivel 3: padre del padre del padre (nivel 1)
                    if ($nivel2 && !empty($nivel2->idrubigeo)) {
                        $nivel3 = $this->ubigeo->obtenerPorId($nivel2->idrubigeo);
                        $ubigeoEntity->rubigeo->rubigeo->rubigeo = $nivel3;
                    }
                }
            }

            // Convertir a array
            $resultado = $ubigeoEntity->toArray();

            return $this->respond($resultado, 200);
        }
    }

    public function listar()
    {
        // Verificar si es POST
        if (!$this->request->is('post')) {
            return $this->fail('Método no permitido. Se requiere POST.', 405);
        }

        $request = $this->request;

        // Parámetros de búsqueda y orden
        $ordencriterio = $request->getVar('ordenCriterio') ?? 'fechapublicacion';
        $ordentipo = $request->getVar('ordenTipo') ?? 'asc';
        $parametro = $request->getVar('parametro') ?? '';
        $valor = $request->getVar('valor') ?? '';
        $idestado = (int) ($request->getVar('idEstado') ?? 0);
        $idrubigeo = (int) ($request->getVar('idrUbigeo') ?? 0);


        // Parámetros de paginación
        $pagina = (int) ($request->getVar('pagina') ?? 1);
        $registros = (int) ($request->getVar('registros') ?? 10);

        // Total de registros
        $total = $this->ubigeo->buscarPorTotal(
            $idrubigeo,
            $idestado
        );

        $paginator = new Paginator($pagina, $registros, $total);
        // Consulta paginada
        $ubigeos = $this->ubigeo->buscarPor(
            $ordencriterio,
            $ordentipo,
            $parametro,
            $valor,
            $idrubigeo,
            $idestado,
            $paginator->getFirstElement(),
            $paginator->getSize()
        );

        // Convertir resultados a entidad
        $resultado = [];
        foreach ($ubigeos as $row) {
            $ubigeoEntity = new UbigeoEntity($row);

            // Relaciones
            $ubigeoEntity->estado = $this->estado->obtenerPorId($row->idestado);

            // Nivel 1: padre inmediato (nivel 3)
            if (!empty($row->idrubigeo)) {
                $nivel1 = $this->ubigeo->obtenerPorId($row->idrubigeo);
                $ubigeoEntity->rubigeo = $nivel1;

                // Nivel 2: padre del padre (nivel 2)
                if ($nivel1 && !empty($nivel1->idrubigeo)) {
                    $nivel2 = $this->ubigeo->obtenerPorId($nivel1->idrubigeo);
                    $ubigeoEntity->rubigeo->rubigeo = $nivel2;

                    // Nivel 3: padre del padre del padre (nivel 1)
                    if ($nivel2 && !empty($nivel2->idrubigeo)) {
                        $nivel3 = $this->ubigeo->obtenerPorId($nivel2->idrubigeo);
                        $ubigeoEntity->rubigeo->rubigeo->rubigeo = $nivel3;
                    }
                }
            }

            $resultado[] = $ubigeoEntity->toArray();
        }


        // Respuesta JSON con paginación y datos
        return $this->respond([
            'paginator' => $paginator->enviar(),
            'content' => $resultado,
            'ubigeos1' => $ubigeos,
            // 'ubigeos2' => $nivel1
        ]);
    }
    public function guardar()
    {
        $request = $this->request;

        $data = $request->getJSON(true);
        $ubigeoRequest = new UbigeoValidation();
        $errores = $ubigeoRequest->ubigeoGuardarValidation($data);

        if (!empty($errores)) {
            return $this->response
                ->setStatusCode(422)
                ->setJSON(['errors' => $errores]);
        }

        $datosValidados =
            [
                'idestado'             => $data['estado']['idEstado'] ?? null,
                'idrubigeo'   => $data['rUbigeo']['idrubigeo'] ?? null,
                'nombre'               => $data['nombre'] ?? null,
            ];



        $ubigeoId = $this->ubigeo->guardar($datosValidados);
        $ubigeo = $this->ubigeo->find($ubigeoId);
        if ($ubigeo) {

            $ubigeoEntity = new UbigeoEntity($ubigeo);

            // Relaciones
            $ubigeoEntity->estado = $this->estado->obtenerPorId($ubigeo->idestado);
            $ubigeoEntity->rubigeo = $this->ubigeo->obtenerPorId($ubigeo->idrubigeo);

            return $this->respond([
                "mensaje" => 'ubigeo registrado con éxito',
                "ubigeo" => $ubigeoEntity->toArray()
            ], 201);
        } else {

            return $this->respond(["mensaje" => "Error al registrar ubigeo"], 500);
        }
    }

    public function actualizar()
    {
        $request = $this->request;

        $data = $request->getJSON(true);
        $ubigeoRequest = new ubigeoValidation();
        $errores = $ubigeoRequest->ubigeoGuardarValidation($data);

        if (!empty($errores)) {
            return $this->response
                ->setStatusCode(422)
                ->setJSON(['errors' => $errores]);
        }
        $datosValidados = [
            'idubigeo' => (int) $data['idubigeo'] ?? null,
            'idestado'             => $data['estado']['idEstado'] ?? null,
            'idrubigeo'   => $data['rUbigeo']['idrubigeo'] ?? null,
            'nombre'               => $data['nombre'] ?? null,
        ];



        $ubigeoId = $this->ubigeo->guardar($datosValidados);
        $ubigeo = $this->ubigeo->find($ubigeoId);
        if ($ubigeo) {

            $ubigeoEntity = new UbigeoEntity($ubigeo);

            // Relaciones
            $ubigeoEntity->estado = $this->estado->obtenerPorId($ubigeo->idestado);
            $ubigeoEntity->rubigeo = $this->ubigeo->obtenerPorId($ubigeo->idrubigeo);

            return $this->respond([
                "mensaje" => 'ubigeo actualizado con éxito',
                "ubigeo" =>  $ubigeoEntity->toArray()
            ], 201);
        } else {

            return $this->respond(["mensaje" => "Error al actualizar el ubigeo"], 500);
        }
    }

    public function eliminar($idubigeo)
    {
        if ($this->ubigeo->eliminar($idubigeo)) {
            return $this->respond(['mensaje' => 'ubigeo eliminado con éxito']);
        } else {
            return $this->failNotFound('No se encontró la ubigeo');
        }
    }
    public function ubigeosDisponibles()
    {
        // Verificar si es POST
        if (!$this->request->is('post')) {
            return $this->fail('Método no permitido. Se requiere POST.', 405);
        }

        $request = $this->request;

        $ordencriterio = $request->getVar('ordenCriterio') ?? '';
        $ordentipo     = $request->getVar('ordenTipo') ?? '';
        $parametro     = $request->getVar('parametro') ?? '';
        $valor         = $request->getVar('valor') ?? '';
        $idestado      = (int) ($request->getVar('idEstado') ?? 0);
        $idrubigeo     = (int) ($request->getVar('idrUbigeo') ?? 0);
        $pagina        = (int) ($request->getVar('pagina') ?? 1);
        $registros     = (int) ($request->getVar('registros') ?? 10);

        // Total de registros
        $total = $this->ubigeo->buscarPorTotal(
            $idrubigeo,
            $idestado
        );

        $paginator = new Paginator($pagina, $registros, $total);

        // Consulta paginada
        $ubigeos = $this->ubigeo->buscarPor(
            $ordencriterio,
            $ordentipo,
            $parametro,
            $valor,
            $idrubigeo,
            $idestado,
            $paginator->getFirstElement(),
            $paginator->getSize()
        );

        // Convertir resultados a entidad
        $resultado = [];
        foreach ($ubigeos as $row) {
            $ubigeoEntity = new UbigeoEntity($row);
            $ubigeoEntity->estado = $this->estado->obtenerPorId($row->idestado);

            // Relaciones de padres
            if (!empty($row->idrubigeo)) {
                $nivel1 = $this->ubigeo->obtenerPorId($row->idrubigeo);
                $ubigeoEntity->rubigeo = $nivel1;
                if ($nivel1 && !empty($nivel1->idrubigeo)) {
                    $nivel2 = $this->ubigeo->obtenerPorId($nivel1->idrubigeo);
                    $ubigeoEntity->rubigeo->rubigeo = $nivel2;
                    if ($nivel2 && !empty($nivel2->idrubigeo)) {
                        $nivel3 = $this->ubigeo->obtenerPorId($nivel2->idrubigeo);
                        $ubigeoEntity->rubigeo->rubigeo->rubigeo = $nivel3;
                    }
                }
            }

            $resultado[] = $ubigeoEntity->toArray();
        }

        return $this->respond([
            'paginator' => $paginator->enviar(),
            'content'   => $resultado
        ], 200);
    }
}
