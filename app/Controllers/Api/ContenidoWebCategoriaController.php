<?php

namespace App\Controllers\Api;

use App\Entities\ContenidoWebCategoriaEntity;
use App\Helpers\Paginator;
use App\Helpers\Permisos;
use App\Helpers\Util;
use App\Models\ContenidoWebCategoriaModel;
use App\Models\EstadoModel;
use App\Models\ParametroModel;
use App\Validation\ContenidoWebCategoriaValidation;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\RESTful\ResourceController;

class ContenidoWebCategoriaController extends  ResourceController
{
    protected $permiso;
    protected $contenidowebcategoria;
    protected $estado;
    protected $parametro;

    public function __construct()
    {
        $this->permiso = new Permisos();
        $this->contenidowebcategoria = new ContenidoWebCategoriaModel();
        $this->estado = new EstadoModel();
        $this->parametro = new ParametroModel();
    }

   
    public  function obtenerPorId($idcontenidowebcategoriacategoria)
    {
        
        $contenidowebcategoria = $this->contenidowebcategoria->obtenerPorId($idcontenidowebcategoriacategoria);
        if (!$contenidowebcategoria) {
            return $this->respond(['mensaje' => 'No existe el contenido web categoria solicitado'], 404);
        } else {

            $contenidowebcategoriaEntity = new ContenidoWebCategoriaEntity($contenidowebcategoria);





            $contenidowebcategoriaEntity->estado = $this->estado->obtenerPorId($contenidowebcategoria->idestado);
            $contenidowebcategoriaEntity->rcontenidowebcategoria  = $this->contenidowebcategoria->obtenerPorId($contenidowebcategoria->idrcontenidowebcategoria);

            // Convertir a array
            $resultado = $contenidowebcategoriaEntity->toArray();

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
        $parametro = $request->getVar('parametro') ?? '';
        $valor = $request->getVar('valor') ?? '';
        $idestado = (int) ($request->getVar('idEstado') ?? 0);
        $idrcontenidowebcategoria = (int) ($request->getVar('idrContenidoWebCategoria') ?? 0);


        // Parámetros de paginación
        $pagina = (int) ($request->getVar('pagina') ?? 1);
        $registros = (int) ($request->getVar('registros') ?? 10);

        // Total de registros
        $total = $this->contenidowebcategoria->buscarPorTotal(
            $parametro,
            $valor,
            $idestado,
            $idrcontenidowebcategoria
        );

        $paginator = new Paginator($pagina, $registros, $total);

        // Consulta paginada
        $contenidowebcategorias = $this->contenidowebcategoria->buscarPor(
            $ordencriterio,
            $ordentipo,
            $parametro,
            $valor,
            $idestado,
            $idrcontenidowebcategoria,
            $paginator->getFirstElement(),
            $paginator->getSize()
        );

        // Convertir resultados a entidad
        $resultado = [];
        foreach ($contenidowebcategorias as $row) {
            $contenidowebcategoriaEntity = new ContenidoWebCategoriaEntity($row);


            $contenidowebcategoriaEntity->estado = $this->estado->obtenerPorId($row->idestado);
            $contenidowebcategoriaEntity->rcontenidowebcategoria   = $this->contenidowebcategoria->obtenerPorId($row->idrcontenidowebcategoria);



            $resultado[] = $contenidowebcategoriaEntity->toArray();
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
        $contenidowebcategoriaRequest = new ContenidoWebCategoriaValidation();
        $errores = $contenidowebcategoriaRequest->contenidoWebCategoriaGuardarValidation($data);

        if (!empty($errores)) {
            return $this->response
                ->setStatusCode(422)
                ->setJSON(['errors' => $errores]);
        }

        $datosValidados = [
            'idestado' => (int) $data['estado']['idEstado'] ?? null,
            'nombre' => $data['nombre'] ?? null,
            // 'urlamigable' => $data['urlAmigable'] ?? null,
            // 'descripcionseo' => $data['descripcionSeo'] ?? null,
            'idrcontenidowebcategoria' =>  $data['rContenidoWebCategoria']['idContenidoWebCategoria'] != 0 ? $data['rContenidoWebCategoria']['idContenidoWebCategoria'] : null,
            'orden' => $data['orden'] ?? null,
            //'urlimagen' => $data['urlImagen'] ?? null,
        ];



        $contenidowebcategoriaId = $this->contenidowebcategoria->guardar($datosValidados);
        $contenidowebcategoria = $this->contenidowebcategoria->find($contenidowebcategoriaId);
        if ($contenidowebcategoria) {
            $contenidowebcategoriaEntity = new ContenidoWebCategoriaEntity($contenidowebcategoria);
            $contenidowebcategoriaEntity->estado = $this->estado->obtenerPorId($contenidowebcategoria->idestado);
            $contenidowebcategoriaEntity->rcontenidowebcategoria   = $this->contenidowebcategoria->obtenerPorId($contenidowebcategoria->idrcontenidowebcategoria);


            return $this->respond([
                "mensaje" => 'contenido web categoria registrado con éxito',
                "contenidowebcategoria" => $contenidowebcategoriaEntity->toArray()
            ], 201);
        } else {

            return $this->respond(["mensaje" => "Error al registrar contenido web categoria"], 500);
        }
    }

    public function actualizar()
    {
       
        $request = $this->request;

        $data = $request->getJSON(true);
        $contenidowebcategoriaRequest = new ContenidoWebCategoriaValidation();
        $errores = $contenidowebcategoriaRequest->contenidowebcategoriaActualizarValidation($data);

        if (!empty($errores)) {
            return $this->response
                ->setStatusCode(422)
                ->setJSON(['errors' => $errores]);
        }
        $datosValidados = [
            'idcontenidowebcategoria' => (int) $data['idContenidoWebCategoria'] ?? null,
            'idestado' => (int) $data['estado']['idEstado'] ?? null,
            'nombre' => $data['nombre'] ?? null,
            // 'urlamigable' => $data['urlAmigable'] ?? null,
            'idrcontenidowebcategoria' =>  $data['rContenidoWebCategoria']['idContenidoWebCategoria'] != 0 ? $data['rContenidoWebCategoria']['idContenidoWebCategoria'] : null,
            //'descripcionseo' => $data['descripcionSeo'] ?? null,
            'orden' => $data['orden'] ?? null,
        ];

        $contenidowebcategoriaId = $this->contenidowebcategoria->guardar($datosValidados);
        $contenidowebcategoria = $this->contenidowebcategoria->find($contenidowebcategoriaId);
        if ($contenidowebcategoria) {

            $contenidowebcategoriaEntity = new ContenidoWebCategoriaEntity($contenidowebcategoria);
            $contenidowebcategoriaEntity->estado = $this->estado->obtenerPorId($contenidowebcategoria->idestado);
            $contenidowebcategoriaEntity->rcontenidowebcategoria   = $this->contenidowebcategoria->obtenerPorId($contenidowebcategoria->idrcontenidowebcategoria);


            return $this->respond([
                "mensaje" => 'contenido web categoria actualizado con éxito',
                "contenidowebcategoria" =>  $contenidowebcategoriaEntity->toArray()
            ], 201);
        } else {

            return $this->respond(["mensaje" => "Error al actualizar el contenido web categoria"], 500);
        }
    }

    public function eliminar($idcontenidowebcategoriacategoria)
    {
       
        if ($this->contenidowebcategoria->eliminar($idcontenidowebcategoriacategoria)) {
            return $this->respond(['mensaje' => 'contenido web categoria eliminado con éxito']);
        } else {
            return $this->failNotFound('No se encontró el contenido web categoria');
        }
    }


    public function uploadImagen()
    {
       
        $idcontenidowebcategoriacategoria = $this->request->getPost('idcontenidowebcategoriacategoria');
        $contenidowebcategoria = $this->contenidowebcategoria->find($idcontenidowebcategoriacategoria);

        if (!$contenidowebcategoria) {
            return $this->response->setJSON(["mensaje" => 'No existe el contenidowebcategoria solicitado'])->setStatusCode(404);
        }

        $file = $this->request->getFile('archivo');
        if (!$file || !$file->isValid()) {
            return $this->response->setJSON(["mensaje" => 'Debe de seleccionar una imagen'])->setStatusCode(404);
        }
        $urlimagen = is_array($contenidowebcategoria) ? ($contenidowebcategoria['urlimagen'] ?? null) : $contenidowebcategoria->urlimagen;
        // Elimina la imagen anterior si existe
        $imgPath = FCPATH . env('URL_IMAGE') . '/contenidowebcategoria/' . $urlimagen;
        if (!empty($contenidowebcategoria->urlimagen) && file_exists($imgPath)) {
            unlink($imgPath);
        }

        // Genera nombre amigable
        $urlamigable = Util::urls_amigables($contenidowebcategoria->nombre);
        $nombrearchivo = $contenidowebcategoria->idcontenidowebcategoriacategoria . '-' . $urlamigable . '.' . $file->getExtension();

        // Asegura que la carpeta exista
        $destino = FCPATH . env('URL_IMAGE') . '/contenidowebcategoria';
        if (!is_dir($destino)) {
            mkdir($destino, 0777, true);
        }
        log_message('debug', 'Destino imagen: ' . $destino);

        // Mueve el archivo
        $file->move($destino, $nombrearchivo);

        // Actualiza contenidowebcategoria
        $datosUpdate = [
            'urlimagen' => $nombrearchivo,
            'urlamigable' => $urlamigable
        ];
        $this->contenidowebcategoria->update($idcontenidowebcategoriacategoria, $datosUpdate);

        $cursoActualizado = $this->contenidowebcategoria->find($idcontenidowebcategoriacategoria);

        // Si ya es entidad, llama directamente a toArray()
        return $this->response->setJSON([
            "contenidowebcategoria" => $cursoActualizado->toArray(),
            "mensaje" => "Imagen cargada con éxito",
            "request" => $this->request->getPost()
        ])->setStatusCode(200);
    }



    public function eliminarImagen()
    {
        
        $idcontenidowebcategoriacategoria = $this->request->getPost('idcontenidowebcategoriacategoria') ?? $this->request->getJSON(true)['idcontenidowebcategoriacategoria'] ?? null;

        if (empty($idcontenidowebcategoriacategoria)) {
            return $this->response->setJSON(['errors' => ['ID de contenidowebcategoria no recibido']])->setStatusCode(400);
        }

        $contenidowebcategoria = $this->contenidowebcategoria->find($idcontenidowebcategoriacategoria);

        if (!$contenidowebcategoria) {
            return $this->response->setJSON(['errors' => ['No existe el contenidowebcategoria solicitado']])->setStatusCode(404);
        }

        $urlimagen = is_array($contenidowebcategoria) ? ($contenidowebcategoria['urlimagen'] ?? null) : $contenidowebcategoria->urlimagen;
        $imgPath = FCPATH . env('URL_IMAGE') . '/contenidowebcategoria/' . $urlimagen;
        if (!empty($urlimagen) && file_exists($imgPath)) {
            unlink($imgPath);
        }

        // Aquí $idcontenidowebcategoriacategoria nunca será null
        $this->contenidowebcategoria->update($idcontenidowebcategoriacategoria, ['urlimagen' => null]);

        $cursoActualizado = $this->contenidowebcategoria->find($idcontenidowebcategoriacategoria);
        $contenidowebcategoriaEntity = new ContenidoWebCategoriaEntity($cursoActualizado);
        // $contenidowebcategoriaEntity->idcontenidowebcategoria  = $contenidowebcategoria->idcontenidowebcategoria;
        // $contenidowebcategoriaEntity->idestado  = $contenidowebcategoria->idestado;
        // $contenidowebcategoriaEntity->idrcontenidowebcategoria  = $contenidowebcategoria->idrcontenidowebcategoria;
        // $contenidowebcategoriaEntity->miniatura  = $contenidowebcategoria->miniatura;
        // $contenidowebcategoriaEntity->banner  = $contenidowebcategoria->banner;
        // $contenidowebcategoriaEntity->nombre  = $contenidowebcategoria->nombre;
        // $contenidowebcategoriaEntity->orden   = $contenidowebcategoria->orden;
        // $contenidowebcategoriaEntity->fecha = $contenidowebcategoria->fecha;




        $contenidowebcategoriaEntity->estado = $this->estado->obtenerPorId($contenidowebcategoria->idestado);
        $contenidowebcategoriaEntity->rcontenidowebcategoria  = $this->contenidowebcategoria->obtenerPorId($contenidowebcategoria->idrcontenidowebcategoria);


        // Convertir a array
        $resultado = $contenidowebcategoriaEntity->toArray();
        return $this->response->setJSON([
            "contenidowebcategoria" => $resultado,
            "mensaje" => "Imagen de producto eliminada con éxito"
        ])->setStatusCode(200);
    }
}
