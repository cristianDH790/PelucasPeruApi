<?php

namespace App\Controllers\Api;

use App\Entities\ContenidoWebEntity;
use App\Helpers\Paginator;
use App\Helpers\Permisos;
use App\Helpers\Util;
use App\Models\ContenidoWebCategoriaModel;
use App\Models\ContenidoWebModel;
use App\Models\EstadoModel;
use App\Models\ParametroModel;
use App\Validation\ContenidoWebValidation;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\RESTful\ResourceController;

class ContenidoWebController extends ResourceController
{
    protected $permiso;
    protected $contenidoweb;
    protected $estado;
    protected $parametro;
    protected $contenidowebcategoria;

    public function __construct()
    {
        $this->permiso = new Permisos();
        $this->contenidoweb = new ContenidoWebModel();
        $this->estado = new EstadoModel();
        $this->parametro = new ParametroModel();
        $this->contenidowebcategoria = new ContenidoWebCategoriaModel();
    }

    public  function obtenerPorId($idcontenidoweb)
    {

        $contenidoweb = $this->contenidoweb->obtenerPorId($idcontenidoweb);
        if (!$contenidoweb) {
            return $this->respond(['mensaje' => 'No existe el contenidoweb solicitado'], 404);
        } else {
            $contenidoWebEntity = new ContenidoWebEntity($contenidoweb);


            $contenidoWebEntity->estado = $this->estado->obtenerPorId($contenidoweb->idestado);
            $contenidoWebEntity->ptipo = $this->parametro->obtenerPorId($contenidoweb->idptipo);
            $contenidoWebEntity->categoria = $this->contenidowebcategoria->obtenerPorId($contenidoweb->idcontenidowebcategoria);

            // Convertir a array
            $resultado = $contenidoWebEntity->toArray();

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
        $idpcategoria = (int) ($request->getVar('idContenidoWebCategoria') ?? 0);
        $idptipo = (int) ($request->getVar('idpTipo') ?? 0);


        // Parámetros de paginación
        $pagina = (int) ($request->getVar('pagina') ?? 1);
        $registros = (int) ($request->getVar('registros') ?? 10);

        // Total de registros
        $total = $this->contenidoweb->buscarPorTotal(
            $parametro,
            $valor,
            $idestado,
            $idpcategoria,
            $idptipo
        );

        $paginator = new Paginator($pagina, $registros, $total);

        // Consulta paginada
        $contenidoWebs = $this->contenidoweb->buscarPor(
            $ordencriterio,
            $ordentipo,
            $parametro,
            $valor,
            $idestado,
            $idpcategoria,
            $idptipo,
            $paginator->getFirstElement(),
            $paginator->getSize()
        );

        // Convertir resultados a entidad
        $resultado = [];
        foreach ($contenidoWebs as $row) {
            $contenidoWebEntity = new ContenidoWebEntity($row);


            $contenidoWebEntity->estado = $this->estado->obtenerPorId($row->idestado);
            $contenidoWebEntity->ptipo = $this->parametro->obtenerPorId($row->idptipo);
            $contenidoWebEntity->categoria = $this->contenidowebcategoria->obtenerPorId($row->idcontenidowebcategoria);


            $resultado[] = $contenidoWebEntity->toArray();
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
        $contenidoWebRequest = new ContenidoWebValidation();
        $errores = $contenidoWebRequest->ContenidoWebGuardarValidation($data);

        if (!empty($errores)) {
            return $this->response
                ->setStatusCode(422)
                ->setJSON(['errors' => $errores]);
        }

        $datosValidados = [
            'idestado' => (int) $data['estado']['idEstado'] ?? null,
            'idcontenidowebcategoria' => (int) $data['contenidoWebCategoria']['idContenidoWebCategoria'] ?? null,
            'idptipo' => (int) $data['pTipo']['idParametro'] ?? null,
            'nombre' => $data['nombre'] ?? null,
            'urlamigable' => $data['urlAmigable'] ?? null,
            'resumen' => $data['resumen'] ?? null,
            'contenido' => $data['contenido'] ?? null,
            'seccion' => $data['seccion'] ?? null,
            'urlimagen' => $data['urlImagen'] ?? null,
            'urlbanner' => $data['urlBanner'] ?? null,
            'orden' => $data['orden'] ?? null,
            'tituloseo' => $data['tituloSeo'] ?? null,
            'descripcionseo' => $data['descripcionSeo'] ?? null,
            'palabrasclaveseo' => $data['palabrasClaveSeo'] ?? null,
        ];



        $contenidowebId = $this->contenidoweb->guardar($datosValidados);
        $contenidoweb = $this->contenidoweb->find($contenidowebId);
        if ($contenidoweb) {

            $contenidoWebEntity = new ContenidoWebEntity($contenidoweb);
            $contenidoWebEntity->estado = $this->estado->obtenerPorId($contenidoweb->idestado);
            $contenidoWebEntity->ptipo = $this->parametro->obtenerPorId($contenidoweb->idptipo);
            $contenidoWebEntity->categoria = $this->contenidowebcategoria->obtenerPorId($contenidoweb->idcontenidowebcategoria);

            return $this->respond([
                "mensaje" => 'contenidoweb registrado con éxito',
                "contenidoweb" => $contenidoWebEntity->toArray()
            ], 201);
        } else {

            return $this->respond(["mensaje" => "Error al registrar contenidoweb"], 500);
        }
    }

    public function actualizar()
    {

        $request = $this->request;

        $data = $request->getJSON(true);
        $contenidoWebRequest = new ContenidoWebValidation();
        $errores = $contenidoWebRequest->ContenidoWebActualizarValidation($data);

        if (!empty($errores)) {
            return $this->response
                ->setStatusCode(422)
                ->setJSON(['errors' => $errores]);
        }
        $datosValidados = [
            'idcontenidoweb' => (int) $data['idContenidoWeb'] ?? null,
            'idestado' => (int) $data['estado']['idEstado'] ?? null,
            'idcontenidowebcategoria' => (int) $data['contenidoWebCategoria']['idContenidoWebCategoria'] ?? null,
            'idptipo' => (int) $data['pTipo']['idParametro'] ?? null,
            'nombre' => $data['nombre'] ?? null,
            'urlamigable' => $data['urlAmigable'] ?? null,
            'resumen' => $data['resumen'] ?? null,
            'contenido' => $data['contenido'] ?? null,
            'seccion' => $data['seccion'] ?? null,
            'urlimagen' => $data['urlImagen'] ?? null,
            'urlbanner' => $data['urlBanner'] ?? null,
            'orden' => $data['orden'] ?? null,

            'tituloseo' => $data['tituloSeo'] ?? null,
            'descripcionseo' => $data['descripcionSeo'] ?? null,
            'palabrasclaveseo' => $data['palabrasClaveSeo'] ?? null,
        ];


        $contenidowebId = $this->contenidoweb->guardar($datosValidados);
        $contenidoweb = $this->contenidoweb->find($contenidowebId);
        if ($contenidoweb) {

            $contenidoWebEntity = new ContenidoWebEntity($contenidoweb);
            $contenidoWebEntity->estado = $this->estado->obtenerPorId($contenidoweb->idestado);
            $contenidoWebEntity->ptipo = $this->parametro->obtenerPorId($contenidoweb->idptipo);
            $contenidoWebEntity->categoria = $this->contenidowebcategoria->obtenerPorId($contenidoweb->idcontenidowebcategoria);

            return $this->respond([
                "mensaje" => 'contenidoweb actualizado con éxito',
                "contenidoweb" =>  $contenidoWebEntity->toArray()
            ], 201);
        } else {

            return $this->respond(["mensaje" => "Error al actualizar el contenidoweb"], 500);
        }
    }

    public function eliminar($idcontenidoweb)
    {

        if ($this->contenidoweb->eliminar($idcontenidoweb)) {
            return $this->respond(['mensaje' => 'contenidoweb eliminado con éxito']);
        } else {
            return $this->failNotFound('No se encontró el contenidoweb');
        }
    }


    public function uploadImagen()
    {

        $idcontenidoweb = $this->request->getPost('idContenidoWeb');
        $contenidoweb = $this->contenidoweb->find($idcontenidoweb);

        if (!$contenidoweb) {
            return $this->response->setJSON(["mensaje" => 'No existe el contenidoweb solicitado'])->setStatusCode(404);
        }

        $file = $this->request->getFile('archivo');
        if (!$file || !$file->isValid()) {
            return $this->response->setJSON(["mensaje" => 'Debe de seleccionar una imagen'])->setStatusCode(404);
        }
        $urlimagen = is_array($contenidoweb) ? ($contenidoweb['urlimagen'] ?? null) : $contenidoweb->urlimagen;
        // Elimina la imagen anterior si existe
        $imgPath = FCPATH . env('URL_IMAGE') . '/contenidoweb/' . $urlimagen;
        if (!empty($contenidoweb->urlimagen) && file_exists($imgPath)) {
            unlink($imgPath);
        }

        // Genera nombre amigable
        $urlamigable = Util::urls_amigables($contenidoweb->nombre);
        $nombrearchivo = $contenidoweb->idcontenidoweb . '-' . $urlamigable . '.' . $file->getExtension();

        // Asegura que la carpeta exista
        $destino = FCPATH . env('URL_IMAGE') . '/contenidoweb';
        if (!is_dir($destino)) {
            mkdir($destino, 0777, true);
        }
        log_message('debug', 'Destino imagen: ' . $destino);

        // Mueve el archivo
        $file->move($destino, $nombrearchivo);

        // Actualiza contenidoweb
        $datosUpdate = [
            'urlimagen' => $nombrearchivo,
            'urlamigable' => $urlamigable
        ];
        $this->contenidoweb->update($idcontenidoweb, $datosUpdate);

        $cursoActualizado = $this->contenidoweb->find($idcontenidoweb);
        $contenidoWebEntity = new ContenidoWebEntity($cursoActualizado);


        $contenidoWebEntity->estado = $this->estado->obtenerPorId($contenidoweb->idestado);
        $contenidoWebEntity->ptipo = $this->parametro->obtenerPorId($contenidoweb->idptipo);
        $contenidoWebEntity->categoria = $this->contenidowebcategoria->obtenerPorId($contenidoweb->idcontenidowebcategoria);

        // Si ya es entidad, llama directamente a toArray()
        return $this->response->setJSON([
            "contenidoweb" => $cursoActualizado->toArray(),
            "mensaje" => "Imagen cargada con éxito",
            "request" => $this->request->getPost()
        ])->setStatusCode(200);
    }



    public function eliminarImagen()
    {


        $idcontenidoweb = $this->request->getPost('idContenidoWeb') ?? $this->request->getJSON(true)['idContenidoWeb'] ?? null;

        if (empty($idcontenidoweb)) {
            return $this->response->setJSON(['errors' => ['ID de contenidoweb no recibido']])->setStatusCode(400);
        }

        $contenidoweb = $this->contenidoweb->find($idcontenidoweb);

        if (!$contenidoweb) {
            return $this->response->setJSON(['errors' => ['No existe el contenidoweb solicitado']])->setStatusCode(404);
        }

        $urlimagen = is_array($contenidoweb) ? ($contenidoweb['urlimagen'] ?? null) : $contenidoweb->urlimagen;
        $imgPath = FCPATH . env('URL_IMAGE') . '/contenidoweb/' . $urlimagen;
        if (!empty($urlimagen) && file_exists($imgPath)) {
            unlink($imgPath);
        }

        // Aquí $idcontenidoweb nunca será null
        $this->contenidoweb->update($idcontenidoweb, ['urlimagen' => null]);

        $contenidoWebEntity = $this->contenidoweb->find($idcontenidoweb);
        $contenidoWebEntity = new ContenidoWebEntity($contenidoWebEntity);


        $contenidoWebEntity->estado = $this->estado->obtenerPorId($contenidoweb->idestado);
        $contenidoWebEntity->ptipo = $this->parametro->obtenerPorId($contenidoweb->idptipo);
        $contenidoWebEntity->categoria = $this->contenidowebcategoria->obtenerPorId($contenidoweb->idcontenidowebcategoria);

        // Convertir a array
        $resultado = $contenidoWebEntity->toArray();
        return $this->response->setJSON([
            "contenidoweb" => $resultado,
            "mensaje" => "Imagen de producto eliminada con éxito"
        ])->setStatusCode(200);
    }
}
