<?php

namespace App\Controllers\Api\Base;

use App\Entities\ConfiguracionEntity;
use App\Helpers\Paginator;
use App\Helpers\Permisos;
use App\Helpers\Util;
use App\Models\ConfiguracionModel;
use App\Models\ParametroModel;
use App\Validation\ConfiguracionValidation;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\RESTful\ResourceController;

class ConfiguracionController extends ResourceController
{
    protected $configuracion;
    protected $parametro;
    protected $permiso;
    public function __construct()
    {
        $this->permiso = new Permisos();
        $this->configuracion = new ConfiguracionModel();
        $this->parametro = new ParametroModel();
    }

    
    public  function obtenerPorId($idconfiguracion)
    {
       
        $configuracion = $this->configuracion->obtenerPorId($idconfiguracion);
        if (!$configuracion) {
            return $this->respond(['mensaje' => 'No existe el configuracion solicitado'], 404);
        } else {

            $configuracionBase = new ConfiguracionEntity($configuracion);

            $configuracionBase->ptipo = $this->parametro->obtenerPorId($configuracion->idptipo);
            $configuracionBase->precurso = $this->parametro->obtenerPorId($configuracion->idprecurso);

            // Convertir a array
            $resultado = $configuracionBase->toArray();



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
        $ordencriterio = $request->getVar('ordenCriterio') ?? 'nombre';
        $ordentipo = $request->getVar('ordenTipo') ?? 'asc';
        $idptipo = (int) ($request->getVar('idpTipo') ?? 0);
        $idprecurso = (int) ($request->getVar('idpRecurso') ?? 0);



        // Parámetros de paginación
        $pagina = (int) ($request->getVar('pagina') ?? 1);
        $registros = (int) ($request->getVar('registros') ?? 10);

        // Total de registros
        $total = $this->configuracion->buscarPorTotal(
            $idptipo,
            $idprecurso
        );

        $paginator = new Paginator($pagina, $registros, $total);

        // Consulta paginada
        $configuracion = $this->configuracion->buscarPor(
            $ordencriterio,
            $ordentipo,
            $idptipo,
            $idprecurso,
            $paginator->getFirstElement(),
            $paginator->getSize()
        );

        // Convertir resultados a entidad
        $resultado = [];
        foreach ($configuracion as $row) {
            $configuracionBase = new ConfiguracionEntity($row);


            $configuracionBase->ptipo = $this->parametro->obtenerPorId($row->idptipo);
            $configuracionBase->precurso = $this->parametro->obtenerPorId($row->idprecurso);
            $resultado[] = $configuracionBase->toArray();
        }

        // Respuesta JSON con paginación y datos
        return $this->respond([
            'paginator' => $paginator->enviar(),
            'content' => $resultado,

        ]);
    }
    public function guardar()
    {
       
        $request = $this->request;

        $data = $request->getJSON(true);
        $configuracionRequest = new ConfiguracionValidation();
        $errores = $configuracionRequest->ConfiguracionGuardarValidation($data);

        if (!empty($errores)) {
            return $this->response
                ->setStatusCode(422)
                ->setJSON(['errors' => $errores]);
        }

        $datosValidados = [
            'idptipo' => $data['pTipo']['idParametro'] ?? 292,
            'idprecurso' => $data['pRecurso']['idParametro'] ?? 292,
            'nombre' => $data['nombre'] ?? null,
            'valor' => $data['valor'] ?? null,
            'urlimagen' => $data['urlImagen'] ?? null,
            'descripcion' => $data['descripcion'] ?? null,

        ];


        $configId = $this->configuracion->guardar($datosValidados);
        $configuracion = $this->configuracion->find($configId);
        if ($configuracion) {

            // $configuracionBase = new CetacEntity($configuracion->toArray());
            $configuracionBase = new ConfiguracionEntity($configuracion);
            $configuracionBase->ptipo = $this->parametro->obtenerPorId($configuracion->idptipo);
            $configuracionBase->precurso = $this->parametro->obtenerPorId($configuracion->idprecurso);
            return $this->respond([
                "mensaje" => 'configuracion registrado con éxito',
                "configuracion" => $configuracion->toArray()
            ], 201);
        } else {

            return $this->respond(["mensaje" => "Error al registrar configuracion"], 500);
        }
    }

    public function actualizar()
    {
       
        $request = $this->request;

        $data = $request->getJSON(true);
        $configuracionRequest = new ConfiguracionValidation();
        $errores = $configuracionRequest->ConfiguracionActualizarValidation($data);

        if (!empty($errores)) {
            return $this->response
                ->setStatusCode(422)
                ->setJSON(['errors' => $errores]);
        }

        $datosValidados = [
            'idconfiguracion' => (int) $data['idConfiguracion'] ?? null,
            'idptipo' => $data['pTipo']['idParametro'] ?? 292,
            'idprecurso' => $data['pRecurso']['idParametro'] ?? 292,
            'nombre' => $data['nombre'] ?? null,
            'valor' => $data['valor'] ?? null,
            'urlimagen' => $data['urlImagen'] ?? null,
            'descripcion' => $data['descripcion'] ?? null,
        ];

        // return $this->response
        //     ->setStatusCode(422)
        //     ->setJSON(['errors' => ['asociado' => $datosValidados]]);

        $cetacId = $this->configuracion->guardar($datosValidados);
        $configuracion = $this->configuracion->find($cetacId);
        if ($configuracion) {

            $configuracionBase = new ConfiguracionEntity($configuracion);
            $configuracionBase->ptipo = $this->parametro->obtenerPorId($configuracion->idptipo);
            $configuracionBase->precurso = $this->parametro->obtenerPorId($configuracion->idprecurso);
            return $this->respond([
                "mensaje" => 'configuracion actualizado con éxito',
                "configuracion" =>  $configuracion->toArray()
            ], 201);
        } else {

            return $this->respond(["mensaje" => "Error al actualizar el configuracion"], 500);
        }
    }

    public function eliminar($idconfiguracion)
    {
       
        if ($this->configuracion->eliminar($idconfiguracion)) {
            return $this->respond(['mensaje' => 'configuracion eliminado con éxito']);
        } else {
            return $this->failNotFound('No se encontró el configuracion');
        }
    }


    public function uploadImagen()
    {

        $idconfiguracion = $this->request->getPost('idConfiguracion');
        $configuracion = $this->configuracion->find($idconfiguracion);

        if (!$configuracion) {
            return $this->response->setJSON(["mensaje" => 'No existe la configuracion solicitada'])->setStatusCode(404);
        }

        // Convierte a array para evitar errores (si quieres)
        if (!is_array($configuracion)) {
            $configuracion = (array) $configuracion;
        }

        $file = $this->request->getFile('archivo');
        if (!$file || !$file->isValid()) {
            return $this->response->setJSON(["mensaje" => 'Debe de seleccionar una imagen'])->setStatusCode(400);
        }

        // Elimina imagen anterior
        $imgPath = FCPATH . env('URL_IMAGE') . '/configuracion/' . ($configuracion['urlimagen'] ?? '');
        if (!empty($configuracion['urlimagen']) && file_exists($imgPath)) {
            unlink($imgPath);
        }

        // Genera nombre amigable
        $nombreCompleto = trim($configuracion['nombre'] ?? '');
        $urlamigable = Util::urls_amigables($nombreCompleto ?: 'configuracion');

        // Usa el id para formar nombre único
        $nombrearchivo = $idconfiguracion . '-' . $urlamigable . '.' . $file->getExtension();

        // Asegura carpeta destino
        $destino = FCPATH . env('URL_IMAGE') . '/configuracion';
        if (!is_dir($destino)) {
            mkdir($destino, 0777, true);
        }

        // Mueve el archivo
        $file->move($destino, $nombrearchivo);

        // Actualiza en DB
        $this->configuracion->update($idconfiguracion, ['urlimagen' => $nombrearchivo]);

        // Obtener actualizado y convertir si es necesario
        $configuracionImagen = $this->configuracion->find($idconfiguracion);
        $configuracionEntity = new ConfiguracionEntity($configuracionImagen);
        $configuracionEntity->ptipo = $this->parametro->obtenerPorId($configuracionImagen->idptipo);
        $configuracionEntity->precurso = $this->parametro->obtenerPorId($configuracionImagen->idprecurso);


        return $this->response->setJSON([
            "configuracion" => $configuracionEntity->toArray(),
            "mensaje" => "Imagen cargada con éxito",
            "request" => $this->request->getPost()
        ])->setStatusCode(200);
    }



    public function eliminarImagen()
    {

        // $idconfiguracion = $this->request->getPost('idconfiguracion');

        $idconfiguracion = $this->request->getPost('idConfiguracion') ?? $this->request->getJSON(true)['idConfiguracion'] ?? null;

        if (empty($idconfiguracion)) {
            return $this->response->setJSON(['errors' => ['ID de configuracion no recibido']])->setStatusCode(400);
        }

        $configuracion = $this->configuracion->find($idconfiguracion);

        if (!$configuracion) {
            return $this->response->setJSON(['errors' => ['No existe el configuracion solicitado']])->setStatusCode(404);
        }

        $urlimagen = is_array($configuracion) ? ($configuracion['urlimagen'] ?? null) : $configuracion->urlimagen;
        $imgPath = FCPATH . env('URL_IMAGE') . '/configuracion/' . $urlimagen;
        if (!empty($urlimagen) && file_exists($imgPath)) {
            unlink($imgPath);
        }

        // Aquí $idconfiguracion nunca será null
        $this->configuracion->update($idconfiguracion, ['urlimagen' => null]);

        $configuracionImagen = $this->configuracion->find($idconfiguracion);
        $configuracionEntity = new configuracionEntity($configuracionImagen);


        $configuracionEntity->ptipo = $this->parametro->obtenerPorId($configuracionImagen->idptipo);
        $configuracionEntity->precurso = $this->parametro->obtenerPorId($configuracionImagen->idprecurso);


        // Convertir a array
        $resultado = $configuracionEntity->toArray();

        return $this->response->setJSON([
            "configuracion" => $resultado,
            "mensaje" => "Imagen de producto eliminada con éxito"
        ])->setStatusCode(200);
    }
}
