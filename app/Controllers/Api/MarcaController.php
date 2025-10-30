<?php

namespace App\Controllers\Api;

use App\Controllers\BaseController;
use App\Entities\EmpresaEntity;
use App\Entities\MarcaEntity;
use App\Entities\ProductoImagenEntity;
use App\Helpers\Paginator;
use App\Helpers\Permisos;
use App\Helpers\Util;
use App\Models\EmpresaModel;
use App\Models\EstadoModel;
use App\Models\MarcaModel;
use App\Models\ParametroModel;
use App\Models\ProductoBaseModel;
use App\Models\ProductoImagenModel;
use App\Validation\MarcaValidation;
use App\Validation\ProductoImagenValidation;
use CodeIgniter\RESTful\ResourceController;

class MarcaController extends ResourceController
{

    protected $marca;
    protected $productoBase;
    protected $estado;
    protected $empresa;
    protected $parametro;
       protected $permiso;

    public function __construct()
    {
        $this->marca = new MarcaModel();
        $this->estado = new EstadoModel();

         $this->permiso = new Permisos();
    }

    private function verificarPermiso(string $permiso)
    {

        
        $token = $this->request->getHeaderLine('X-Authorization');
        $token = str_replace('Bearer ', '', $token);

        if (!$token) {
            return $this->failUnauthorized('Token no proporcionado');
        }
        $resultado = $this->permiso->obtenerPermisosDesdeToken($token);

        if (isset($resultado['error'])) {
            return $this->failUnauthorized($resultado['error']);
        }

        $permisos = $resultado['authorities'] ?? [];

        if (!in_array($permiso, $permisos)) {
            return $this->failForbidden("No tienes permiso: {$permiso}");
        }

        return null; // Permiso concedido
    }

    public  function obtenerPorId($idmarca)
    {
         if ($respuesta = $this->verificarPermiso('api_marca_obtenerPorId')) {
            return $respuesta;
        }
        $marca = $this->marca->obtenerPorId($idmarca);

        if (!$marca) {
            return $this->respond(['mensaje' => 'No existe la marca solicitada'], 404);
        } else {

            $marcaEntity = new MarcaEntity($marca);


            $marcaEntity->estado = $this->estado->obtenerPorId($marca->idestado);
          
            // Convertir a array
            $resultado = $marcaEntity->toArray();

            return $this->respond($resultado, 200);
        }
    }

    public function listar()
    {
         if ($respuesta = $this->verificarPermiso('api_marca_listar')) {
            return $respuesta;
        }
        // Verificar si es POST
        if (!$this->request->is('post')) {
            return $this->fail('Método no permitido. Se requiere POST.', 405);
        }

        $request = $this->request;

        // Parámetros de búsqueda y orden
        $ordencriterio = $request->getVar('ordenCriterio') ?? 'fecha';
        $ordentipo = $request->getVar('ordenTipo') ?? 'asc';
        $parametro = $request->getVar('parametro') ?? '';
        $valor = $request->getVar('valor') ?? '';
        $idestado = (int) ($request->getVar('idEstado') ?? 0);

        // Parámetros de paginación
        $pagina = (int) ($request->getVar('pagina') ?? 1);
        $registros = (int) ($request->getVar('registros') ?? 10);

        // Total de registros
        $total = $this->marca->buscarPorTotal(
            $parametro,
            $valor,
            $idestado
        );

        $paginator = new Paginator($pagina, $registros, $total);
        // Consulta paginada
        $productoImagens = $this->marca->buscarPor(
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
        foreach ($productoImagens as $row) {
            $marcaEntity = new MarcaEntity($row);
            $marcaEntity->estado = $this->estado->obtenerPorId($row->idestado);
            $resultado[] = $marcaEntity->toArray();
        }


        return $this->respond([
            'paginator' => $paginator->enviar(),
            'content' => $resultado
        ]);
    }
    public function guardar()
    {
         if ($respuesta = $this->verificarPermiso('api_marca_guardar')) {
            return $respuesta;
        }
        $request = $this->request;

        $data = $request->getJSON(true);
        $marcaRequest = new MarcaValidation();
        $errores = $marcaRequest->marcaGuardarValidation($data);

        if (!empty($errores)) {
            return $this->response
                ->setStatusCode(422)
                ->setJSON(['errors' => $errores]);
        }

        $datosValidados = [
            'idestado'      => $data['estado']['idEstado'] ?? null,
            'nombre'        => $data['nombre'] ?? null,
            'descripcion'        => $data['descripcion'] ?? null,
            'contenido'        => $data['contenido'] ?? null,
            //'urlamigable'      => $data['urlAmigable'] ?? null,
            'urlimagen'    => $data['urlImagen'] ?? null,
            'orden'   => $data['orden'] ?? null,
        ];


        $marcaId = $this->marca->guardar($datosValidados);
        $marca = $this->marca->find($marcaId);
        if ($marca) {
            $marcaEntity = new MarcaEntity($marca);
            $marcaEntity->estado = $this->estado->obtenerPorId($marca->idestado);
          

            return $this->respond([
                "mensaje" => 'marca registrado con éxito',
                "marca" => $marcaEntity->toArray()
            ], 201);
        } else {

            return $this->respond(["mensaje" => "Error al registrar marca"], 500);
        }
    }

    public function actualizar()
    {
         if ($respuesta = $this->verificarPermiso('api_marca_actualizar')) {
            return $respuesta;
        }
        $request = $this->request;

        $data = $request->getJSON(true);
        $marcaRequest = new MarcaValidation();
        $errores = $marcaRequest->marcaGuardarValidation($data);

        if (!empty($errores)) {
            return $this->response
                ->setStatusCode(422)
                ->setJSON(['errors' => $errores]);
        }
        $datosValidados = [
            'idmarca' => (int) $data['idMarca'] ?? null,
            'idestado'      => $data['estado']['idEstado'] ?? null,
            'nombre'        => $data['nombre'] ?? null,
             'descripcion'        => $data['descripcion'] ?? null,
            'contenido'        => $data['contenido'] ?? null,
            //'urlamigable'      => $data['urlAmigable'] ?? null,
            'urlimagen'    => $data['urlImagen'] ?? null,
            'orden'   => $data['orden'] ?? null,
        ];


        $marcaId = $this->marca->guardar($datosValidados);
        $marca = $this->marca->find($marcaId);
        if ($marca) {

            $marcaEntity = new MarcaEntity($marca);
            $marcaEntity->estado = $this->estado->obtenerPorId($marca->idestado);
          
            return $this->respond([
                "mensaje" => 'producto Imagen actualizado con éxito',
                "marca" =>  $marcaEntity->toArray()
            ], 201);
        } else {

            return $this->respond(["mensaje" => "Error al actualizar el producto Imagen"], 500);
        }
    }

    public function eliminar($idmarca)
    {
         if ($respuesta = $this->verificarPermiso('api_marca_eliminar')) {
            return $respuesta;
        }
        if ($this->marca->eliminar($idmarca)) {
            return $this->respond(['mensaje' => 'marca eliminado con éxito']);
        } else {
            return $this->failNotFound('No se encontró la producto Imagen');
        }
    }


    public function uploadImagen()
    {
         if ($respuesta = $this->verificarPermiso('api_marca_upload1')) {
            return $respuesta;
        }
        $idmarca = $this->request->getPost('idMarca');
        $marca = $this->marca->find($idmarca);

        if (!$marca) {
            return $this->response->setJSON(["mensaje" => 'No existe la marca solicitada'])->setStatusCode(404);
        }

        // Manejo como array para evitar errores con objetos
        if (!is_array($marca)) {
            $marca = (array) $marca;
        }

        $file = $this->request->getFile('archivo');
        if (!$file || !$file->isValid()) {
            return $this->response->setJSON(["mensaje" => 'Debe de seleccionar una imagen'])->setStatusCode(400);
        }

        // Elimina imagen anterior
        $imgPath = FCPATH . env('URL_IMAGE') . '/marca/' . ($marca['urlimagen'] ?? '');
        if (!empty($marca['urlimagen']) && file_exists($imgPath)) {
            unlink($imgPath);
        }

        // Genera nombre amigable
        $nombre = is_array($marca) ? ($marca['nombre'] ?? '') : ($marca->nombre ?? '');


        $nombreCompleto = trim($nombre);
        $urlamigable = Util::urls_amigables($nombreCompleto ?: 'marca');
        $nombrearchivo = $marca['idmarca'] . '-' . $urlamigable . '.' . $file->getExtension();

        // Asegura carpeta
        $destino = FCPATH . env('URL_IMAGE') . '/marca';
        if (!is_dir($destino)) {
            mkdir($destino, 0777, true);
        }

        // Mueve el archivo
        $file->move($destino, $nombrearchivo);

        // Actualiza en DB
        $this->marca->update($idmarca, ['urlimagen' => $nombrearchivo]);

        // Obtener actualizado y convertir si es necesario
        $marcaActualizado = $this->marca->find($idmarca);

        $marcaEntity = new MarcaEntity($marcaActualizado);
        $marcaEntity->estado = $this->estado->obtenerPorId($marcaActualizado->idestado);
     


        return $this->response->setJSON([
            "marca" => $marcaActualizado->toArray(),
            "mensaje" => "Imagen cargada con éxito",
            "request" => $this->request->getPost()
        ])->setStatusCode(200);
    }




    public function eliminarImagen()
    {
         if ($respuesta = $this->verificarPermiso('api_marca_eliminar imagen')) {
            return $respuesta;
        }
        // $idmarca = $this->request->getPost('idmarca');

        $idmarca = $this->request->getPost('idMarca') ?? $this->request->getJSON(true)['idMarca'] ?? null;

        if (empty($idmarca)) {
            return $this->response->setJSON(['errors' => ['ID de marca no recibido']])->setStatusCode(400);
        }

        $marca = $this->marca->find($idmarca);

        if (!$marca) {
            return $this->response->setJSON(['errors' => ['No existe el marca solicitado']])->setStatusCode(404);
        }

        $urlimagen = is_array($marca) ? ($marca['urlimagen'] ?? null) : $marca->urlimagen;
        $imgPath = FCPATH . env('URL_IMAGE') . '/marca/' . $urlimagen;
        if (!empty($urlimagen) && file_exists($imgPath)) {
            unlink($imgPath);
        }

        // Aquí $idmarca nunca será null
        $this->marca->update($idmarca, ['urlimagen' => null]);

        $marcaActualizado = $this->marca->find($idmarca);
        $marcaEntity = new MarcaEntity($marcaActualizado);

        $marcaEntity->estado = $this->estado->obtenerPorId($marcaActualizado->idestado);
      




        // Convertir a array
        $resultado = $marcaEntity->toArray();

        return $this->response->setJSON([
            "marca" => $resultado,
            "mensaje" => "Imagen de producto eliminada con éxito"
        ])->setStatusCode(200);
    }
}
