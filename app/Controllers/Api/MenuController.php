<?php

namespace App\Controllers\Api;

use App\Controllers\BaseController;
use App\Entities\EmpresaEntity;
use App\Entities\MenuEntity;
use App\Entities\ProductoImagenEntity;
use App\Helpers\Paginator;
use App\Helpers\Permisos;
use App\Helpers\Util;
use App\Models\EmpresaModel;
use App\Models\EstadoModel;
use App\Models\MenuModel;
use App\Models\ParametroModel;
use App\Validation\MenuValidation;
use App\Validation\ProductoImagenValidation;
use CodeIgniter\RESTful\ResourceController;

class MenuController extends ResourceController
{
    protected $permiso;
    protected $menu;
    protected $productoBase;
    protected $estado;
    protected $empresa;
    protected $parametro;

    public function __construct()
    {
        $this->permiso = new Permisos();
        $this->menu = new MenuModel();
        $this->estado = new EstadoModel();
        $this->parametro = new ParametroModel();
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


    public  function obtenerPorId($idmenu)
    {
        if ($respuesta = $this->verificarPermiso('api_menu_obtenerPorId')) {
            return $respuesta;
        }
        $menu = $this->menu->obtenerPorId($idmenu);

        if (!$menu) {
            return $this->respond(['mensaje' => 'No existe la menu solicitada'], 404);
        } else {

            $menuEntity = new MenuEntity($menu);

            $menuEntity->estado = $this->estado->obtenerPorId($menu->idestado);
            $menuEntity->pdestino = $this->parametro->obtenerPorId($menu->idpdestino);
            $menuEntity->pubicacion = $this->parametro->obtenerPorId($menu->idpubicacion);
            $menuEntity->ptipo = $this->parametro->obtenerPorId($menu->idptipo);
            $menuEntity->rmenu = $this->menu->obtenerPorId($menu->idrmenu);

            // Convertir a array
            $resultado = $menuEntity->toArray();

            return $this->respond($resultado, 200);
        }
    }

    public function listar()
    {
        if ($respuesta = $this->verificarPermiso('api_menu_listar')) {
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
        $idrmenu = (int) ($request->getVar('idrMenu') ?? 0);

        $idptipo = (int) ($request->getVar('idTipo') ?? 0);
        $idpubicacion = (int) ($request->getVar('idpUbicacion') ?? 0);

        // Parámetros de paginación
        $pagina = (int) ($request->getVar('pagina') ?? 1);
        $registros = (int) ($request->getVar('registros') ?? 10);

        // Total de registros
        $total = $this->menu->buscarPorTotal(
            $parametro,
            $valor,
            $idestado,
            $idrmenu,
            $idptipo,
            $idpubicacion
        );

        $paginator = new Paginator($pagina, $registros, $total);
        // Consulta paginada
        $productoImagens = $this->menu->buscarPor(
            $ordencriterio,
            $ordentipo,
            $parametro,
            $valor,
            $idestado,
            $idrmenu,
            $idptipo,
            $idpubicacion,
            $paginator->getFirstElement(),
            $paginator->getSize()
        );

        // Convertir resultados a entidad
        $resultado = [];
        foreach ($productoImagens as $row) {
            $menuEntity = new MenuEntity($row);
            $menuEntity->estado = $this->estado->obtenerPorId($row->idestado);
            $menuEntity->pdestino = $this->parametro->obtenerPorId($row->idpdestino);
            $menuEntity->pubicacion = $this->parametro->obtenerPorId($row->idpubicacion);
            $menuEntity->ptipo = $this->parametro->obtenerPorId($row->idptipo);
            $menuEntity->rmenu = $this->menu->obtenerPorId($row->idrmenu);

            $resultado[] = $menuEntity->toArray();
        }


        return $this->respond([
            'paginator' => $paginator->enviar(),
            'content' => $resultado
        ]);
    }
    public function guardar()
    {
        if ($respuesta = $this->verificarPermiso('api_menu_guardar')) {
            return $respuesta;
        }
        $request = $this->request;

        $data = $request->getJSON(true);
        $menuRequest = new MenuValidation();
        $errores = $menuRequest->menuGuardarValidation($data);

        if (!empty($errores)) {
            return $this->response
                ->setStatusCode(422)
                ->setJSON(['errors' => $errores]);
        }

        $datosValidados = [
            'idestado'      => $data['estado']['idEstado'] ?? null,
            'idptipo'  => $data['pTipo']['idParametro'] ?? null,
            'idpubicacion'  => $data['pUbicacion']['idParametro'] ?? null,
            'idpdestino'  => $data['pDestino']['idParametro'] ?? null,
            'idrmenu'       => (!empty($data['rMenu']['idMenu']) && $data['rMenu']['idMenu'] != 0)
                ? $data['rMenu']['idMenu']
                : null,
            'nombre'        => $data['nombre'] ?? null,
            'destino'      => $data['destino'] ?? null,
            'orden'    => $data['orden'] ?? null,
            'urlrecurso'   => $data['urlrecurso'] ?? null,
            'seccion'   => $data['seccion'] ?? null,
        ];


        $menuId = $this->menu->guardar($datosValidados);
        $menu = $this->menu->find($menuId);
        if ($menu) {
            $menuEntity = new MenuEntity($menu);
            $menuEntity->estado = $this->estado->obtenerPorId($menu->idestado);
            $menuEntity->pdestino = $this->parametro->obtenerPorId($menu->idpdestino);
            $menuEntity->pubicacion = $this->parametro->obtenerPorId($menu->idpubicacion);
            $menuEntity->ptipo = $this->parametro->obtenerPorId($menu->idptipo);
            $menuEntity->rmenu = $this->menu->obtenerPorId($menu->idrmenu);

            return $this->respond([
                "mensaje" => 'menu registrado con éxito',
                "menu" => $menuEntity->toArray()
            ], 201);
        } else {

            return $this->respond(["mensaje" => "Error al registrar menu"], 500);
        }
    }

    public function actualizar()
    {
        if ($respuesta = $this->verificarPermiso('api_menu_actualizar')) {
            return $respuesta;
        }
        $request = $this->request;

        $data = $request->getJSON(true);
        $menuRequest = new MenuValidation();
        $errores = $menuRequest->menuGuardarValidation($data);

        if (!empty($errores)) {
            return $this->response
                ->setStatusCode(422)
                ->setJSON(['errors' => $errores]);
        }
        $datosValidados = [
            'idmenu' => (int) $data['idMenu'] ?? null,
            'idestado'      => $data['estado']['idEstado'] ?? null,
            'idptipo'  => $data['pTipo']['idParametro'] ?? null,
            'idpubicacion'  => $data['pUbicacion']['idParametro'] ?? null,
            'idpdestino'  => $data['pDestino']['idParametro'] ?? null,
            'idrmenu'       => (!empty($data['rMenu']['idMenu']) && $data['rMenu']['idMenu'] != 0)
                ? $data['rMenu']['idMenu']
                : null,
            'nombre'        => $data['nombre'] ?? null,
            'destino'      => $data['destino'] ?? null,
            'orden'    => $data['orden'] ?? null,
            'urlrecurso'   => $data['urlrecurso'] ?? null,
            'seccion'   => $data['seccion'] ?? null,
        ];


        $menuId = $this->menu->guardar($datosValidados);
        $menu = $this->menu->find($menuId);
        if ($menu) {

            $menuEntity = new MenuEntity($menu);
            $menuEntity->estado = $this->estado->obtenerPorId($menu->idestado);
            $menuEntity->pdestino = $this->parametro->obtenerPorId($menu->idpdestino);
            $menuEntity->pubicacion = $this->parametro->obtenerPorId($menu->idpubicacion);
            $menuEntity->ptipo = $this->parametro->obtenerPorId($menu->idptipo);
            $menuEntity->rmenu = $this->menu->obtenerPorId($menu->idrmenu);
            return $this->respond([
                "mensaje" => 'producto Imagen actualizado con éxito',
                "menu" =>  $menuEntity->toArray()
            ], 201);
        } else {

            return $this->respond(["mensaje" => "Error al actualizar el producto Imagen"], 500);
        }
    }

    public function eliminar($idmenu)
    {
        if ($respuesta = $this->verificarPermiso('api_menu_eliminar')) {
            return $respuesta;
        }
        if ($this->menu->eliminar($idmenu)) {
            return $this->respond(['mensaje' => 'menu eliminado con éxito']);
        } else {
            return $this->failNotFound('No se encontró la producto Imagen');
        }
    }
}
