<?php

namespace App\Controllers\Api;

use App\Entities\SedeEntity;
use App\Entities\ZonaRepartoEntity;
use App\Helpers\Paginator;
use App\Helpers\Permisos;
use App\Models\EmpresaModel;
use App\Models\EstadoModel;
use App\Models\SedeModel;
use App\Models\UbigeoModel;
use App\Models\ZonaRepartoModel;
use App\Validation\SedeValidation;
use App\Validation\ZonaRepartoValidation;
use CodeIgniter\RESTful\ResourceController;

class ZonaRepartoController extends ResourceController
{

    protected $zonaReparto;
    protected $estado;
    protected $ubigeo;
    protected $permiso;



    public function __construct()
    {
        $this->zonaReparto = new ZonaRepartoModel();
        $this->estado = new EstadoModel();
        $this->ubigeo = new UbigeoModel();
         $this->permiso = new Permisos();
    }



   

    private function anidarUbigeo($ubigeo)
    {

        
        $ubigeoModel = new \App\Models\UbigeoModel();
        $ubigeoEntity = new \App\Entities\UbigeoEntity($ubigeo);
        $ubigeoEntity->estado = (new \App\Models\EstadoModel())->obtenerPorId($ubigeo->idestado);

        $nivel = $ubigeoEntity;
        $padre = $ubigeo;
        for ($i = 0; $i < 5; $i++) {
            if (!empty($padre->idrubigeo)) {
                $padre = $ubigeoModel->obtenerPorId($padre->idrubigeo);
                if ($padre) {
                    $nivel->rubigeo = $padre;
                    $nivel = $nivel->rubigeo;
                } else {
                    break;
                }
            } else {
                break;
            }
        }

        return $ubigeoEntity;
    }

    public function obtenerPorId($idzonareparto)
    {
        

        $zonaReparto = $this->zonaReparto->obtenerPorId($idzonareparto);

        if (!$zonaReparto) {
            return $this->respond(['mensaje' => 'No existe la zona reparto solicitada'], 404);
        }

        $zonaRepartoEntity = new ZonaRepartoEntity($zonaReparto);
        $zonaRepartoEntity->estado = $this->estado->obtenerPorId($zonaReparto->idestado);

        // Ubigeo principal
        if (!empty($zonaReparto->idubigeo)) {
            $ubigeo = (new \App\Models\UbigeoModel())->obtenerPorId($zonaReparto->idubigeo);
            if ($ubigeo) {
                $zonaRepartoEntity->ubigeo = $this->anidarUbigeo($ubigeo);
            }
        }

        // Ubigeos asociados
        $db = \Config\Database::connect();
        $ubigeosAsociados = $db->table('zonareparto_ubigeo')
            ->where('idzonareparto', $idzonareparto)
            ->get()->getResult();

        $ubigeosArray = [];
        $ubigeoModel = new \App\Models\UbigeoModel();

        foreach ($ubigeosAsociados as $rel) {
            $ubigeo = $ubigeoModel->obtenerPorId($rel->idubigeo);
            if ($ubigeo) {
                $ubigeosArray[] = $this->anidarUbigeo($ubigeo)->toArray();
            }
        }

        $resultado = $zonaRepartoEntity->toArray();
        $resultado['ubigeos'] = $ubigeosArray;

        return $this->respond($resultado, 200);
    }


    public function listar()
    {
       
        if (!$this->request->is('post')) {
            return $this->fail('Método no permitido. Se requiere POST.', 405);
        }

        $request = $this->request;
        $ordencriterio = $request->getVar('ordenCriterio') ?? 'fechapublicacion';
        $ordentipo = $request->getVar('ordenTipo') ?? 'asc';
        $parametro = $request->getVar('parametro') ?? '';
        $valor = $request->getVar('valor') ?? '';
        $idestado = (int) ($request->getVar('idEstado') ?? 0);
        $pagina = (int) ($request->getVar('pagina') ?? 1);
        $registros = (int) ($request->getVar('registros') ?? 10);

        $total = $this->zonaReparto->buscarPorTotal($parametro, $valor, $idestado);
        $paginator = new Paginator($pagina, $registros, $total);

        $zonas = $this->zonaReparto->buscarPor(
            $ordencriterio,
            $ordentipo,
            $parametro,
            $valor,
            $idestado,
            $paginator->getFirstElement(),
            $paginator->getSize()
        );

        $resultado = [];
        $ubigeoModel = new \App\Models\UbigeoModel();
        $db = \Config\Database::connect();

        foreach ($zonas as $zona) {
            $zonaEntity = new ZonaRepartoEntity($zona);
            $zonaEntity->estado = $this->estado->obtenerPorId($zona->idestado);

            // Ubigeo principal
            if (!empty($zona->idubigeo)) {
                $ubigeo = $ubigeoModel->obtenerPorId($zona->idubigeo);
                if ($ubigeo) {
                    $zonaEntity->ubigeo = $this->anidarUbigeo($ubigeo);
                }
            }

            // Ubigeos asociados
            $ubigeosAsociados = $db->table('zonareparto_ubigeo')
                ->where('idzonareparto', $zona->idzonareparto)
                ->get()->getResult();

            $ubigeosArray = [];
            foreach ($ubigeosAsociados as $rel) {
                $ubigeo = $ubigeoModel->obtenerPorId($rel->idubigeo);
                if ($ubigeo) {
                    $ubigeosArray[] = $this->anidarUbigeo($ubigeo)->toArray();
                }
            }

            $item = $zonaEntity->toArray();
            $item['ubigeos'] = $ubigeosArray;

            $resultado[] = $item;
        }

        return $this->respond([
            'paginator' => $paginator->enviar(),
            'content' => $resultado
        ]);
    }


    public function guardar()
    {
        
        $request = $this->request;
        $data = $request->getJSON(true);

        $validator = new ZonaRepartoValidation();
        $errores = $validator->zonaRepartoGuardarValidation($data);
        if (!empty($errores)) {
            return $this->response->setStatusCode(422)->setJSON(['errors' => $errores]);
        }

        $datosValidados = [
            'idestado' => $data['estado']['idEstado'] ?? null,
            'nombre' => $data['nombre'] ?? null,
            'costo' => $data['costo'] ?? null,
            'longitud' => $data['longitud'] ?? null
        ];

        $zonaId = $this->zonaReparto->guardar($datosValidados);
        $zonaReparto = $this->zonaReparto->find($zonaId);

        if (!$zonaReparto) {
            return $this->respond(["mensaje" => "Error al registrar zona reparto"], 500);
        }

        // Guardar ubigeos asociados
        if (!empty($data['ubigeos']) && is_array($data['ubigeos'])) {
            $db = \Config\Database::connect();
            foreach ($data['ubigeos'] as $ubigeo) {
                if (!empty($ubigeo['idUbigeo'])) {
                    $db->table('zonareparto_ubigeo')->insert([
                        'idzonareparto' => $zonaId,
                        'idubigeo' => $ubigeo['idUbigeo']
                    ]);
                }
            }
        }

        $zonaEntity = new ZonaRepartoEntity($zonaReparto);
        $zonaEntity->estado = $this->estado->obtenerPorId($zonaReparto->idestado);

        // Ubigeo principal (si existe)
        if (!empty($zonaReparto->idubigeo)) {
            $ubigeo = (new \App\Models\UbigeoModel())->obtenerPorId($zonaReparto->idubigeo);
            if ($ubigeo) {
                $zonaEntity->ubigeo = $this->anidarUbigeo($ubigeo);
            }
        }

        return $this->respond([
            "mensaje" => 'Zona reparto registrada con éxito',
            "zonaReparto" => $zonaEntity->toArray()
        ], 201);
    }


    public function actualizar()
    {
        
        $request = $this->request;
        $data = $request->getJSON(true);

        $validator = new ZonaRepartoValidation();
        $errores = $validator->zonaRepartoGuardarValidation($data);
        if (!empty($errores)) {
            return $this->response->setStatusCode(422)->setJSON(['errors' => $errores]);
        }

        $datosValidados = [
            'idzonareparto' => (int) ($data['idZonaReparto'] ?? null),
            'idestado' => $data['estado']['idEstado'] ?? null,
            'nombre' => $data['nombre'] ?? null,
            'costo' => $data['costo'] ?? null,
            'longitud' => $data['longitud'] ?? null
        ];

        $zonaId = $this->zonaReparto->guardar($datosValidados);
        $zonaReparto = $this->zonaReparto->find($zonaId);

        if (!$zonaReparto) {
            return $this->respond(["mensaje" => "Error al actualizar zona reparto"], 500);
        }
        $db = \Config\Database::connect();
        // Primero eliminar relaciones anteriores
        $db->table('zonareparto_ubigeo')->where('idzonareparto', $zonaId)->delete();

        // Guardar ubigeos asociados
        if (!empty($data['ubigeos']) && is_array($data['ubigeos'])) {

            // Luego insertar nuevas relaciones
            foreach ($data['ubigeos'] as $ubigeo) {
                if (!empty($ubigeo['idUbigeo'])) {
                    $db->table('zonareparto_ubigeo')->insert([
                        'idzonareparto' => $zonaId,
                        'idubigeo' => $ubigeo['idUbigeo']
                    ]);
                }
            }
        }

        $zonaEntity = new ZonaRepartoEntity($zonaReparto);
        $zonaEntity->estado = $this->estado->obtenerPorId($zonaReparto->idestado);

        // Ubigeo principal (si existe)
        if (!empty($zonaReparto->idubigeo)) {
            $ubigeo = (new \App\Models\UbigeoModel())->obtenerPorId($zonaReparto->idubigeo);
            if ($ubigeo) {
                $zonaEntity->ubigeo = $this->anidarUbigeo($ubigeo);
            }
        }

        return $this->respond([
            "mensaje" => 'Zona reparto actualizada con éxito',
            "zonaReparto" => $zonaEntity->toArray()
        ], 201);
    }

    public function eliminar($idzonareparto)
    {
       
        if ($this->zonaReparto->eliminar($idzonareparto)) {
            return $this->respond(['mensaje' => 'Zona reparto eliminado con éxito']);
        } else {
            return $this->failNotFound('No se encontró la zona reparto');
        }
    }
}
