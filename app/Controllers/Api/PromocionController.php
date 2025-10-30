<?php

namespace App\Controllers\Api;

use App\Entities\promocionEntity;
use App\Helpers\Paginator;
use App\Models\PromocionModel;
use App\Models\EstadoModel;
use App\Validation\PromocionValidation;
use CodeIgniter\RESTful\ResourceController;

class PromocionController extends ResourceController
{

    protected $promocion;
    protected $estado;



    public function __construct()
    {
        $this->promocion = new PromocionModel();
        $this->estado = new EstadoModel();
    }

    public  function obtenerPorId($idpromocion)
    {
        $promocion = $this->promocion->obtenerPorId($idpromocion);

        if (!$promocion) {
            return $this->respond(['mensaje' => 'No existe la promocion solicitada'], 404);
        } else {

            $promocionEntity = new PromocionEntity($promocion);
            // Relaciones
            $promocionEntity->estado = $this->estado->obtenerPorId($promocion->idestado);

            // Convertir a array
            $resultado = $promocionEntity->toArray();

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


        // Parámetros de paginación
        $pagina = (int) ($request->getVar('pagina') ?? 1);
        $registros = (int) ($request->getVar('registros') ?? 10);

        // Total de registros
        $total = $this->promocion->buscarPorTotal(
            $parametro,
            $valor,
            $idestado,


        );

        $paginator = new Paginator($pagina, $registros, $total);
        // Consulta paginada
        $promocions = $this->promocion->buscarPor(
            $ordencriterio,
            $ordentipo,
            $parametro,
            $valor,
            $idestado,
            $paginator->getFirstElement(),
            $paginator->getSize()
        );

        // Convertir resultados a entidad
        $resultado = [];
        foreach ($promocions as $row) {
            $promocionEntity = new promocionEntity($row);
            // Relaciones
            $promocionEntity->estado = $this->estado->obtenerPorId($row->idestado);
            $resultado[] = $promocionEntity->toArray();
        }

        // Respuesta JSON con paginación y datos
        return $this->respond([
            'paginator' => $paginator->enviar(),
            'content' => $resultado
        ]);
    }
    public function guardar()
    {
        $request = $this->request;

        $data = $request->getJSON(true);
        $promocionRequest = new PromocionValidation();
        $errores = $promocionRequest->promocionGuardarValidation($data);

        if (!empty($errores)) {
            return $this->response
                ->setStatusCode(422)
                ->setJSON(['errors' => $errores]);
        }

        $datosValidados =
            [
                'idestado' => $data['estado']['idEstado'] ?? null,
                'nombre' => $data['nombre'] ?? null,
                'urlamigable' => $data['urlAmigable'] ?? null,
                'resumen' => $data['resumen'] ?? null,
                'contenido' => $data['contenido'] ?? null,
                'urlminiatura' => $data['urlMiniatura'] ?? null,
                'urlimagen' => $data['urlImagen'] ?? null,
                'urlredireccion' => $data['urlRedireccion'] ?? null,
                'terminos' => $data['terminos'] ?? null,
                'fechainicio' => $data['fechaInicio'] ?? null,
                'fechafin' => $data['fechaFin'] ?? null,
                'accesos' => $data['accesos'] ?? null,
                'fecha' => $data['fecha'] ?? null,
            ];



        $promocionId = $this->promocion->guardar($datosValidados);
        $promocion = $this->promocion->find($promocionId);
        if ($promocion) {

            $promocionEntity = new PromocionEntity($promocion);

            $promocionEntity->estado = $this->estado->obtenerPorId($promocion->idestado);


            return $this->respond([
                "mensaje" => 'promocion registrado con éxito',
                "promocion" => $promocionEntity->toArray()
            ], 201);
        } else {

            return $this->respond(["mensaje" => "Error al registrar promocion"], 500);
        }
    }

    public function actualizar()
    {
        $request = $this->request;

        $data = $request->getJSON(true);
        $promocionRequest = new PromocionValidation();
        $errores = $promocionRequest->promocionGuardarValidation($data);

        if (!empty($errores)) {
            return $this->response
                ->setStatusCode(422)
                ->setJSON(['errors' => $errores]);
        }
        $datosValidados = [
            'idpromocion' => (int) $data['idpromocion'] ?? null,
            'idestado' => $data['estado']['idEstado'] ?? null,
            'nombre' => $data['nombre'] ?? null,
            'urlamigable' => $data['urlAmigable'] ?? null,
            'resumen' => $data['resumen'] ?? null,
            'contenido' => $data['contenido'] ?? null,
            'urlminiatura' => $data['urlMiniatura'] ?? null,
            'urlimagen' => $data['urlImagen'] ?? null,
            'urlredireccion' => $data['urlRedireccion'] ?? null,
            'terminos' => $data['terminos'] ?? null,
            'fechainicio' => $data['fechaInicio'] ?? null,
            'fechafin' => $data['fechaFin'] ?? null,
            'accesos' => $data['accesos'] ?? null,
            'fecha' => $data['fecha'] ?? null,
        ];



        $promocionId = $this->promocion->guardar($datosValidados);
        $promocion = $this->promocion->find($promocionId);
        if ($promocion) {

            $promocionEntity = new PromocionEntity($promocion);

            $promocionEntity->estado = $this->estado->obtenerPorId($promocion->idestado);

            return $this->respond([
                "mensaje" => 'Promocion actualizado con éxito',
                "promocion" =>  $promocionEntity->toArray()
            ], 201);
        } else {

            return $this->respond(["mensaje" => "Error al actualizar el producto base"], 500);
        }
    }

    public function eliminar(
        $idpromocion
    ) {
        if ($this->promocion->eliminar(
            $idpromocion
        )) {
            return $this->respond(['mensaje' => 'Promocion base eliminado con éxito']);
        } else {
            return $this->failNotFound('No se encontró la promocion');
        }
    }
}
