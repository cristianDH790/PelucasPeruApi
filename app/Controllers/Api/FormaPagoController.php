<?php

namespace App\Controllers\Api;

use App\Entities\FormaPagoEntity;
use App\Helpers\Paginator;
use App\Helpers\Permisos;
use App\Models\EmpresaModel;
use App\Models\EstadoModel;
use App\Models\FormaPagoModel;
use App\Models\ParametroModel;
use App\Validation\FormaPagoValidation;
use CodeIgniter\RESTful\ResourceController;

class FormaPagoController extends ResourceController
{

    protected $formapago;
    protected $estado;
    protected $empresa;
    protected $parametro;
    protected $permiso;

    public function __construct()
    {
        $this->permiso = new Permisos();
        $this->formapago = new FormaPagoModel();
        $this->estado = new EstadoModel();
     
        $this->parametro = new ParametroModel();
    }
   
    public  function obtenerPorId($idformapago)
    {
        
        $formapago = $this->formapago->obtenerPorId($idformapago);

        if (!$formapago) {
            return $this->respond(['mensaje' => 'No existe la forma pago solicitada'], 404);
        } else {

            $formapagoEntity = new FormaPagoEntity($formapago);
            // Relaciones
            $formapagoEntity->estado = $this->estado->obtenerPorId($formapago->idestado);
          

            // Convertir a array
            $resultado = $formapagoEntity->toArray();

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
        $total = $this->formapago->buscarPorTotal(
            $parametro,
            $valor,
            $idestado
          

        );

        $paginator = new Paginator($pagina, $registros, $total);
        // Consulta paginada
        $formapagos = $this->formapago->buscarPor(
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
        foreach ($formapagos as $row) {
            $formapagoEntity = new FormaPagoEntity($row);
            // Relaciones
            $formapagoEntity->estado = $this->estado->obtenerPorId($row->idestado);
            // $formapagoEntity->empresa = $this->empresa->obtenerPorId($row->idempresa);
            // $formapagoEntity->ptipo = $this->parametro->obtenerPorId($row->idptipo);

            $resultado[] = $formapagoEntity->toArray();
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
        $formapagoRequest = new formapagoValidation();
        $errores = $formapagoRequest->formapagoGuardarValidation($data);

        if (!empty($errores)) {
            return $this->response
                ->setStatusCode(422)
                ->setJSON(['errors' => $errores]);
        }

        $datosValidados =
            [
                'idestado' => (int) $data['estado']['idEstado'] ?? null,
                // 'idempresa' => (int) $data['empresa']['idEmpresa'] ?? null,
                // 'idptipo' =>  (int)$data['pTipo']['idParametro'] ?? null,
                'nombre' => $data['nombre'] ?? null,
                'abr' => $data['abr'] ?? null,
                'comision' => $data['comision'] ?? null,
                'contenido' => $data['contenido'] ?? null,
                'contenido2' => $data['contenido2'] ?? null,
                'orden' => (int)$data['orden'] ?? null,

            ];



        $formapagoId = $this->formapago->guardar($datosValidados);
        $formapago = $this->formapago->find($formapagoId);
        if ($formapago) {

            $formapagoEntity = new FormaPagoEntity($formapago);
            $formapagoEntity->estado = $this->estado->obtenerPorId($formapago->idestado);
            

            return $this->respond([
                "mensaje" => 'forma pago registrado con éxito',
                "formapago" => $formapagoEntity->toArray()
            ], 201);
        } else {

            return $this->respond(["mensaje" => "Error al registrar formapago"], 500);
        }
    }

    public function actualizar()
    {
        $request = $this->request;

        $data = $request->getJSON(true);
        $formapagoRequest = new FormaPagoValidation();
        $errores = $formapagoRequest->formapagoGuardarValidation($data);

        if (!empty($errores)) {
            return $this->response
                ->setStatusCode(422)
                ->setJSON(['errors' => $errores]);
        }
        $datosValidados = [
            'idformapago' => (int) $data['idFormaPago'] ?? null,
            'idestado' => (int) $data['estado']['idEstado'] ?? null,
            // 'idempresa' => (int) $data['empresa']['idEmpresa'] ?? null,
            // 'idptipo' => (int) $data['pTipo']['idParametro'] ?? null,
            'nombre' => $data['nombre'] ?? null,
            'abr' => $data['abr'] ?? null,
            'comision' => $data['comision'] ?? null,
            'contenido' => $data['contenido'] ?? null,
            'contenido2' => $data['contenido2'] ?? null,
            'orden' => (int)$data['orden'] ?? null,

        ];



        $formapagoId = $this->formapago->guardar($datosValidados);
        $formapago = $this->formapago->find($formapagoId);
        if ($formapago) {

            $formapagoEntity = new FormaPagoEntity($formapago);
            $formapagoEntity->estado = $this->estado->obtenerPorId($formapago->idestado);
            // $formapagoEntity->empresa = $this->empresa->obtenerPorId($formapago->idempresa);

            return $this->respond([
                "mensaje" => 'forma pago actualizado con éxito',
                "formapago" =>  $formapagoEntity->toArray()
            ], 201);
        } else {

            return $this->respond(["mensaje" => "Error al actualizar el forma pago"], 500);
        }
    }

    public function eliminar($idformapago)
    {
        
        if ($this->formapago->eliminar($idformapago)) {
            return $this->respond(['mensaje' => 'forma pago eliminado con éxito']);
        } else {
            return $this->failNotFound('No se encontró la forma pago');
        }
    }
}
