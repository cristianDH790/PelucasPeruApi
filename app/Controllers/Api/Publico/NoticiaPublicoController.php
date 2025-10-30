<?php

namespace App\Controllers\Api\Publico;

use App\Entities\NoticiaEntity;
use App\Helpers\Paginator;
use App\Helpers\Util;
use App\Models\EstadoModel;
use App\Models\NoticiaCategoriaModel;
use App\Models\NoticiaModel;
use App\Models\ParametroModel;
use App\Validation\NoticiaValidation;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\RESTful\ResourceController;

class NoticiaPublicoController extends ResourceController
{

    protected $noticia;
    protected $estado;
    protected $parametro;
    protected $noticiaCategoria;

    public function __construct()
    {
        $this->noticia = new NoticiaModel();
        $this->estado = new EstadoModel();
        $this->noticiaCategoria = new NoticiaCategoriaModel();
        $this->parametro = new ParametroModel();
    }

    public  function obtenerPorId($idnoticia)
    {
        $noticia = $this->noticia->obtenerPorId($idnoticia);

        if (!$noticia) {
            return $this->respond(['mensaje' => 'No existe la noticia solicitada'], 404);
        } else {

            $noticiaEntity = new NoticiaEntity();

            $noticiaEntity->idnoticia           = $noticia->idnoticia;
            $noticiaEntity->idestado            = $noticia->idestado;
            $noticiaEntity->idnoticiacategoria  = $noticia->idnoticiacategoria;
            // $noticiaEntity->idusuario           = $noticia->idusuario;
            $noticiaEntity->idpdestacado        = $noticia->idpdestacado;

            $noticiaEntity->nombre              = $noticia->nombre;
            $noticiaEntity->urlamigable         = $noticia->urlamigable;
            $noticiaEntity->descripcionseo      = $noticia->descripcionseo;
            // $noticiaEntity->palabrasclaveseo    = $noticia->palabrasclaveseo;
            // $noticiaEntity->tituloseo           = $noticia->tituloseo;

            $noticiaEntity->resumen             = $noticia->resumen;
            $noticiaEntity->contenido           = $noticia->contenido;
            $noticiaEntity->urlimagen           = $noticia->urlimagen;
            $noticiaEntity->orden               = $noticia->orden;
            $noticiaEntity->fechapublicacion    = $noticia->fechapublicacion;
            $noticiaEntity->fecha               = $noticia->fecha;

            // Relaciones
            $noticiaEntity->estado              = $this->estado->obtenerPorId($noticia->idestado);
            $noticiaEntity->pdestacado          = $this->parametro->obtenerPorId($noticia->idpdestacado);
            $noticiaEntity->noticiacategoria    = $this->noticiaCategoria->obtenerPorId($noticia->idnoticiacategoria);



            // Convertir a array
            $resultado = $noticiaEntity->toArray();

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
        $idpdestacado = (int) ($request->getVar('idPdestacado') ?? 0);
        $idnoticiacategoria = (int) ($request->getVar('idNoticiaCategoria') ?? 0);

        // Parámetros de paginación
        $pagina = (int) ($request->getVar('pagina') ?? 1);
        $registros = (int) ($request->getVar('registros') ?? 10);

        // Total de registros
        $total = $this->noticia->buscarPorTotal(
            $parametro,
            $valor,
            $idestado,
            $idnoticiacategoria,
            $idpdestacado,
        );

        $paginator = new Paginator($pagina, $registros, $total);
        // Consulta paginada
        $noticias = $this->noticia->buscarPor(
            $ordencriterio,
            $ordentipo,
            $parametro,
            $valor,
            $idestado,
            $idnoticiacategoria,
            $idpdestacado,
            $paginator->getFirstElement(),
            $paginator->getSize()
        );

        // Convertir resultados a entidad
        $resultado = [];
        foreach ($noticias as $row) {
            $noticiaEntity = new NoticiaEntity();

            $noticiaEntity->idnoticia           = $row->idnoticia;
            $noticiaEntity->idestado            = $row->idestado;
            $noticiaEntity->idnoticiacategoria  = $row->idnoticiacategoria;
            // $noticiaEntity->idusuario           = $row->idusuario;
            $noticiaEntity->idpdestacado        = $row->idpdestacado;

            $noticiaEntity->nombre              = $row->nombre;
            $noticiaEntity->urlamigable         = $row->urlamigable;
            $noticiaEntity->descripcionseo      = $row->descripcionseo;
            // $noticiaEntity->palabrasclaveseo    = $row->palabrasclaveseo;
            // $noticiaEntity->tituloseo           = $row->tituloseo;

            $noticiaEntity->resumen             = $row->resumen;
            $noticiaEntity->contenido           = $row->contenido;
            $noticiaEntity->urlimagen           = $row->urlimagen;
            $noticiaEntity->orden               = $row->orden;
            $noticiaEntity->fechapublicacion    = $row->fechapublicacion;
            $noticiaEntity->fecha               = $row->fecha;

            // Relaciones
            $noticiaEntity->estado              = $this->estado->obtenerPorId($row->idestado);
            $noticiaEntity->pdestacado          = $this->parametro->obtenerPorId($row->idpdestacado);
            $noticiaEntity->noticiacategoria    = $this->noticiaCategoria->obtenerPorId($row->idnoticiacategoria);



            $resultado[] = $noticiaEntity->toArray();
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
        $noticiaRequest = new NoticiaValidation();
        $errores = $noticiaRequest->NoticiaGuardarValidation($data);

        if (!empty($errores)) {
            return $this->response
                ->setStatusCode(422)
                ->setJSON(['errors' => $errores]);
        }

        $datosValidados =
            [
                'idestado'             => $data['estado']['idEstado'] ?? null,
                'idnoticiacategoria'   => $data['noticiaCategoria']['idNoticiaCategoria'] ?? null,
               
                'idpdestacado'         => $data['pDestacado']['idParametro'] ?? null,
                'nombre'               => $data['nombre'] ?? null,
                'urlamigable'          => $data['urlAmigable'] ?? null,
                'descripcionseo'       => $data['descripcionSeo'] ?? null,
                // 'palabrasclaveseo'     => $data['palabrasClaveSeo'] ?? null,
                // 'tituloseo'            => $data['tituloSeo'] ?? null,
                'resumen'              => $data['resumen'] ?? null,
                'contenido'            => $data['contenido'] ?? null,
                'urlimagen'            => $data['urlImagen'] ?? null,
                'orden'                => $data['orden'] ?? null,
                'fechapublicacion'     => $data['fechaPublicacion'] ?? null,
            ];



        $noticiaId = $this->noticia->guardar($datosValidados);
        $noticia = $this->noticia->find($noticiaId);
        if ($noticia) {

            return $this->respond([
                "mensaje" => 'noticia registrado con éxito',
                "noticia" => $noticia->toArray()
            ], 201);
        } else {

            return $this->respond(["mensaje" => "Error al registrar noticia"], 500);
        }
    }

    public function actualizar()
    {
        $request = $this->request;

        $data = $request->getJSON(true);
        $noticiaRequest = new NoticiaValidation();
        $errores = $noticiaRequest->NoticiaGuardarValidation($data);

        if (!empty($errores)) {
            return $this->response
                ->setStatusCode(422)
                ->setJSON(['errors' => $errores]);
        }
        $datosValidados = [
            'idnoticia' => (int) $data['idNoticia'] ?? null,
            'idestado'             => $data['estado']['idEstado'] ?? null,
            'idnoticiacategoria'   => $data['noticiaCategoria']['idNoticiaCategoria'] ?? null,
          
            'idpdestacado'         => $data['pDestacado']['idParametro'] ?? null,
            'nombre'               => $data['nombre'] ?? null,
            'urlamigable'          => $data['urlAmigable'] ?? null,
            'descripcionseo'       => $data['descripcionSeo'] ?? null,
            // 'palabrasclaveseo'     => $data['palabrasClaveSeo'] ?? null,
            // 'tituloseo'            => $data['tituloSeo'] ?? null,
            'resumen'              => $data['resumen'] ?? null,
            'contenido'            => $data['contenido'] ?? null,
            'urlimagen'            => $data['urlImagen'] ?? null,
            'orden'                => $data['orden'] ?? null,
            'fechapublicacion'     => $data['fechaPublicacion'] ?? null,
        ];



        $noticiaId = $this->noticia->guardar($datosValidados);
        $noticia = $this->noticia->find($noticiaId);
        if ($noticia) {


            return $this->respond([
                "mensaje" => 'noticia actualizado con éxito',
                "noticia" =>  $noticia->toArray()
            ], 201);
        } else {

            return $this->respond(["mensaje" => "Error al actualizar el noticia"], 500);
        }
    }

    public function eliminar($idnoticia)
    {
        if ($this->noticia->eliminar($idnoticia)) {
            return $this->respond(['mensaje' => 'noticia eliminado con éxito']);
        } else {
            return $this->failNotFound('No se encontró la noticia');
        }
    }


    public function uploadImagen()
    {
        $idnoticia = $this->request->getPost('idNoticia');
        $noticia = $this->noticia->find($idnoticia);

        if (!$noticia) {
            return $this->response->setJSON(["mensaje" => 'No existe la noticia solicitada'])->setStatusCode(404);
        }

        // Manejo como array para evitar errores con objetos
        if (!is_array($noticia)) {
            $noticia = (array) $noticia;
        }

        $file = $this->request->getFile('archivo');
        if (!$file || !$file->isValid()) {
            return $this->response->setJSON(["mensaje" => 'Debe de seleccionar una imagen'])->setStatusCode(400);
        }

        // Elimina imagen anterior
        $imgPath = FCPATH . env('URL_IMAGE') . '/noticia/' . ($noticia['urlimagen'] ?? '');
        if (!empty($noticia['urlimagen']) && file_exists($imgPath)) {
            unlink($imgPath);
        }

        // Genera nombre amigable
        $nombre = is_array($noticia) ? ($noticia['nombres'] ?? '') : ($noticia->nombres ?? '');
        $apellido = is_array($noticia) ? ($noticia['apellidos'] ?? '') : ($noticia->apellidos ?? '');

        $nombreCompleto = trim($nombre . ' ' . $apellido);
        $urlamigable = Util::urls_amigables($nombreCompleto ?: 'noticia');
        $nombrearchivo = $noticia['idnoticia'] . '-' . $urlamigable . '.' . $file->getExtension();

        // Asegura carpeta
        $destino = FCPATH . env('URL_IMAGE') . '/noticia';
        if (!is_dir($destino)) {
            mkdir($destino, 0777, true);
        }

        // Mueve el archivo
        $file->move($destino, $nombrearchivo);

        // Actualiza en DB
        $this->noticia->update($idnoticia, ['urlimagen' => $nombrearchivo]);

        // Obtener actualizado y convertir si es necesario
        $noticiaActualizado = $this->noticia->find($idnoticia);
        if (is_array($noticiaActualizado)) {
            $noticiaActualizado = new \App\Entities\NoticiaEntity($noticiaActualizado);
        }

        return $this->response->setJSON([
            "noticia" => $noticiaActualizado->toArray(),
            "mensaje" => "Imagen cargada con éxito",
            "request" => $this->request->getPost()
        ])->setStatusCode(200);
    }



    public function eliminarImagen()
    {
        // $idnoticia = $this->request->getPost('idnoticia');

        $idnoticia = $this->request->getPost('idNoticia') ?? $this->request->getJSON(true)['idNoticia'] ?? null;

        if (empty($idnoticia)) {
            return $this->response->setJSON(['errors' => ['ID de noticia no recibido']])->setStatusCode(400);
        }

        $noticia = $this->noticia->find($idnoticia);

        if (!$noticia) {
            return $this->response->setJSON(['errors' => ['No existe el noticia solicitado']])->setStatusCode(404);
        }

        $urlimagen = is_array($noticia) ? ($noticia['urlimagen'] ?? null) : $noticia->urlimagen;
        $imgPath = FCPATH . env('URL_IMAGE') . '/noticia/' . $urlimagen;
        if (!empty($urlimagen) && file_exists($imgPath)) {
            unlink($imgPath);
        }

        // Aquí $idnoticia nunca será null
        $this->noticia->update($idnoticia, ['urlimagen' => null]);

        $noticiaActualizado = $this->noticia->find($idnoticia);
        if (is_array($noticiaActualizado)) {
            $noticiaActualizado = new \App\Entities\NoticiaEntity($noticiaActualizado);
        }

        return $this->response->setJSON([
            "noticia" => $noticiaActualizado->toArray(),
            "mensaje" => "Imagen de producto eliminada con éxito"
        ])->setStatusCode(200);
    }
}
