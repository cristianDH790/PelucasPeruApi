<?php

namespace App\Controllers\Api;

use App\Controllers\BaseController;
use App\Entities\ColorEntity;
use App\Helpers\Paginator;
use App\Models\ColorModel;
use App\Models\EstadoModel;
use App\Validation\ColorValidation;
use CodeIgniter\RESTful\ResourceController;

class ColorController extends ResourceController
{
    protected $color;
    protected $estado;

    public function __construct()
    {
        $this->color = new ColorModel();
        $this->estado = new EstadoModel();
    }

    // ✅ OBTENER POR ID
    public function colorPorIdColor($idColor)
    {
        $color = $this->color->obtenerPorId($idColor);

        if (!$color) {
            return $this->respond(['mensaje' => 'No existe el color solicitado'], 404);
        }

        $colorEntity = new ColorEntity($color);
        $colorEntity->estado = $this->estado->obtenerPorId($color->idestado);

        return $this->respond($colorEntity->toArray(), 200);
    }

    // ✅ LISTAR (POST)
    public function colores()
    {
        if (!$this->request->is('post')) {
            return $this->fail('Método no permitido. Se requiere POST.', 405);
        }

        $request = $this->request;
        $parametro = $request->getVar('parametro') ?? null;
        $valor = $request->getVar('valor') ?? null;
        $ordencriterio = $request->getVar('ordenCriterio') ?? 'fecha';
        $ordentipo = $request->getVar('ordenTipo') ?? 'asc';
        $idestado = (int) ($request->getVar('idEstado') ?? 0);
        $pagina = (int) ($request->getVar('pagina') ?? 1);
        $registros = (int) ($request->getVar('registros') ?? 10);


        // Total de registros
        $total = $this->color->buscarPorTotal($idestado);

        $paginator = new Paginator($pagina, $registros, $total);

        // Consulta paginada
        $colores = $this->color->buscarPor(
            $parametro,
            $valor,
            $idestado,
            $paginator->getSize(),
            $paginator->getFirstElement(),
            $ordencriterio,
            $ordentipo
        );


        $resultado = [];
        foreach ($colores as $row) {
            $colorEntity = new ColorEntity($row);
            $colorEntity->estado = $this->estado->obtenerPorId($row->idestado);
            $resultado[] = $colorEntity->toArray();
        }

        return $this->respond([
            'paginator' => $paginator->enviar(),
            'content' => $resultado
        ], 200);
    }

    // ✅ GUARDAR
    public function colorGuardar()
    {
        $data = $this->request->getJSON(true);

        $contenidoWebRequest = new ColorValidation();
        $errores = $contenidoWebRequest->colorActualizarValidation($data);

        if (!empty($errores)) {
            return $this->response
                ->setStatusCode(422)
                ->setJSON(['errors' => $errores]);
        }

        $datosValidados = [
            'idestado' => (int) ($data['estado']['idEstado'] ?? null),
            'nombre' => $data['nombre'] ?? null,
            'codigo' => $data['codigo'] ?? null,
            'codigoproductocolor' => $data['codigoProductoColor'] ?? null
        ];

        $colorId = $this->color->guardar($datosValidados);
        $color = $this->color->find($colorId);

        if ($color) {
            $colorEntity = new ColorEntity($color);
            $colorEntity->estado = $this->estado->obtenerPorId($color->idestado);

            return $this->respond([
                'mensaje' => 'Color registrado con éxito',
                'color' => $colorEntity->toArray()
            ], 201);
        } else {
            return $this->respond(['mensaje' => 'Error al registrar color'], 500);
        }
    }

    // ✅ ACTUALIZAR
    public function colorActualizar($idColor)
    {
        $data = $this->request->getJSON(true);

        $contenidoWebRequest = new ColorValidation();
        $errores = $contenidoWebRequest->colorActualizarValidation($data);

        if (!empty($errores)) {
            return $this->response
                ->setStatusCode(422)
                ->setJSON(['errors' => $errores]);
        }

        $datosValidados = [
            'idcolor' => (int) $idColor,
            'idestado' => (int) ($data['estado']['idEstado'] ?? null),
            'nombre' => $data['nombre'] ?? null,
            'codigo' => $data['codigo'] ?? null,
            'codigoproductocolor' => $data['codigoProductoColor'] ?? null
        ];

        $colorId = $this->color->guardar($datosValidados);
        $color = $this->color->find($colorId);

        if ($color) {
            $colorEntity = new ColorEntity($color);
            $colorEntity->estado = $this->estado->obtenerPorId($color->idestado);

            return $this->respond([
                'mensaje' => 'Color actualizado con éxito',
                'color' => $colorEntity->toArray()
            ], 200);
        } else {
            return $this->respond(['mensaje' => 'Error al actualizar color'], 500);
        }
    }

    // ✅ ELIMINAR
    public function colorEliminar($idColor)
    {
        if ($this->color->eliminar($idColor)) {
            return $this->respond(['mensaje' => 'Color eliminado con éxito'], 200);
        } else {
            return $this->failNotFound('No se encontró el color');
        }
    }
}
