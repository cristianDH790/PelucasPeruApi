<?php

namespace App\Controllers\Api;

use App\Entities\MarcaEntity;
use App\Entities\ProductoEntity;
use App\Exports\ProductoActualizarExport;
use App\Helpers\Excel\ReporteExcelProductos;
use App\Helpers\Excel\ReporteExcelProductosActualizar;
use App\Helpers\Paginator;
use App\Helpers\Permisos;
use App\Models\ColorModel;
use App\Models\CuponModel;
use App\Models\EmpresaModel;

use App\Models\EstadoModel;
use App\Models\MarcaModel;
use App\Models\ParametroModel;

use App\Models\ProductoCategoriaModel;
use App\Models\ProductoModel;
use App\Models\PromocionModel;
use App\Validation\ProductoValidation;
use CodeIgniter\RESTful\ResourceController;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

use PhpOffice\PhpSpreadsheet\IOFactory;
use App\Models\ProductoColorModel;
use App\Models\ProductoImagenModel;
use PhpOffice\PhpSpreadsheet\Spreadsheet;

class ProductoController extends ResourceController
{

    protected $producto;
    protected $productocategoria;
    protected $estado;
    protected $promocion;
    protected $parametro;
    protected $marca;
    protected $permiso;
    protected $color;
    protected $cupon;

    public function __construct()
    {
        $this->permiso = new Permisos();
        $this->producto = new ProductoModel();
        $this->productocategoria = new ProductoCategoriaModel();
        $this->estado = new EstadoModel();
        $this->promocion = new PromocionModel();
        $this->parametro = new ParametroModel();
        $this->marca = new MarcaModel();
        $this->color = new ColorModel();
        $this->cupon = new CuponModel();
    }


    public  function obtenerPorId($idproducto)
    {


        $producto = $this->producto->obtenerPorId(
            $idproducto
        );

        if (!$producto) {
            return $this->respond(['mensaje' => 'No existe la forma pago solicitada'], 404);
        } else {

            $productobaseEntity = new ProductoEntity($producto);
            // Relaciones
            $productobaseEntity->estado = $this->estado->obtenerPorId($producto->idestado);

            $productobaseEntity->pdestacado = $this->parametro->obtenerPorId($producto->idpdestacado);

            $productobaseEntity->pcomplemento = $this->parametro->obtenerPorId($producto->idpcomplemento);

            $productobaseEntity->productocategoria = $this->productocategoria->obtenerPorId($producto->idproductocategoria);
            $productobaseEntity->color = $this->color->obtenerPorId($producto->idcolor);

            // Convertir a array
            $resultado = $productobaseEntity->toArray();

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
        $ordencriterio = $request->getVar('ordenCriterio') ?? 'fecha';
        $ordentipo = $request->getVar('ordenTipo') ?? 'asc';
        $parametro = $request->getVar('parametro') ?? '';
        $valor = $request->getVar('valor') ?? '';
        $idestado = (int) ($request->getVar('idEstado') ?? 0);
        $idcolor = (int) ($request->getVar('idColor') ?? 0);
        $idproductocategoria = (int) ($request->getVar('idProductoCategoria') ?? 0);
        $idrproductocategoria = (int) ($request->getVar('idrProductoCategoria') ?? 0);
        $idpdestacado = (int) ($request->getVar('idpDestacado') ?? 0);
        $idpcomplemento = (int) ($request->getVar('idpComplemento') ?? 0);
        $idcupon = (int) ($request->getVar('idCupon') ?? 0);
        // Parámetros de paginación
        $pagina = (int) ($request->getVar('pagina') ?? 1);
        $registros = (int) ($request->getVar('registros') ?? 10);

        // Total de registros
        $total = $this->producto->buscarPorTotal(
            $parametro,
            $valor,
            $idestado,
            $idproductocategoria,
            $idrproductocategoria,
            $idpdestacado,
            $idpcomplemento,
            $idcupon,
            $idcolor
        );

        $paginator = new Paginator($pagina, $registros, $total);
        // Consulta paginada
        $productobases = $this->producto->buscarPor(
            $ordencriterio,
            $ordentipo,
            $parametro,
            $valor,
            $idestado,
            $idproductocategoria,
            $idrproductocategoria,
            $idpdestacado,
            $idpcomplemento,
            $idcupon,
            $idcolor,
            $paginator->getFirstElement(),
            $paginator->getSize()
        );

        // Convertir resultados a entidad
        $resultado = [];
        foreach ($productobases as $row) {
            $productobaseEntity = new ProductoEntity($row);
            // Relaciones

            $productobaseEntity->estado = $this->estado->obtenerPorId($row->idestado);

            $productobaseEntity->pdestacado = $this->parametro->obtenerPorId($row->idpdestacado);
            $productobaseEntity->pcomplemento = $this->parametro->obtenerPorId($row->idpcomplemento);

            $productobaseEntity->productocategoria = $this->productocategoria->obtenerPorId($row->idproductocategoria);
            $productobaseEntity->color = $this->color->obtenerPorId($row->idcolor);
            // **Cupones**
            $productobaseEntity->cupones = $this->cupon->obtenerPorProducto($row->idproducto);
            $resultado[] = $productobaseEntity->toArray();
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
        $productobaseRequest = new ProductoValidation();
        $errores = $productobaseRequest->productoGuardarValidation($data);

        if (!empty($errores)) {
            return $this->response
                ->setStatusCode(422)
                ->setJSON(['errors' => $errores]);
        }


        $datosValidados =
            [
                'idestado' => $data['estado']['idEstado'] ?? null,
                'idproductocategoria' => $data['productoCategoria']['idProductoCategoria'] ?? null,

                'idpdestacado' => $data['pDestacado']['idParametro'] ?? null,
                'idpcomplemento' => $data['pComplemento']['idParametro'] ?? null,
                'idcolor' => $data['color']['idColor'] ?? null,

                'stock' => $data['stock'],
                'codigo' => $data['codigo'] ?? null,
                'nombre' => $data['nombre'] ?? null,
                'urlamigable' => $data['urlAmigable'] ?? null,
                'resumen' => $data['resumen'] ?? null,

                'contenido' => $data['contenido'] ?? null,

                'orden' => $data['orden'] ?? null,

                'preciolista' => (float) $data['precioLista'] ?? null,
                'precioventa' => (float) $data['precioVenta'] ?? null,
                'peso' => $data['peso'] ?? null,
                'fechapublicacion' => $data['fechaPublicacion'] ?? null,

            ];



        $productobaseId = $this->producto->guardar($datosValidados);
        $producto = $this->producto->find($productobaseId);
        if ($producto) {

            $productobaseEntity = new ProductoEntity($producto);

            $productobaseEntity->estado = $this->estado->obtenerPorId($producto->idestado);

            $productobaseEntity->pdestacado = $this->parametro->obtenerPorId($producto->idpdestacado);

            $productobaseEntity->pcomplemento = $this->parametro->obtenerPorId($producto->idpcomplemento);

            $productobaseEntity->productocategoria = $this->productocategoria->obtenerPorId($producto->idproductocategoria);

            return $this->respond([
                "mensaje" => 'forma pago registrado con éxito',
                "producto" => $productobaseEntity->toArray()
            ], 201);
        } else {

            return $this->respond(["mensaje" => "Error al registrar producto"], 500);
        }
    }

    public function actualizar()
    {

        $request = $this->request;

        $data = $request->getJSON(true);
        $productobaseRequest = new ProductoValidation();
        $errores = $productobaseRequest->productoActualizarValidation($data);

        if (!empty($errores)) {
            return $this->response
                ->setStatusCode(422)
                ->setJSON(['errors' => $errores]);
        }

        $datosValidados = [
            'idproducto' => (int) $data['idProducto'] ?? null,
            'idestado' => $data['estado']['idEstado'] ?? null,
            'idproductocategoria' => $data['productoCategoria']['idProductoCategoria'] ?? null,

            'idpdestacado' => $data['pDestacado']['idParametro'] ?? null,
            'idpcomplemento' => $data['pComplemento']['idParametro'] ?? null,
            'idcolor' => $data['color']['idColor'] ?? null,

            'stock' => $data['stock'],
            'codigo' => $data['codigo'] ?? null,
            'orden' => $data['orden'] ?? null,
            'nombre' => $data['nombre'] ?? null,
            'urlamigable' => $data['urlAmigable'] ?? null,
            'resumen' => $data['resumen'] ?? null,
            'contenido' => $data['contenido'] ?? null,

            'urlimagen' => $data['urlImagen'] ?? null,
            'preciolista' => (float) $data['precioLista'] ?? null,
            'precioventa' => (float) $data['precioVenta'] ?? null,
            'peso' => $data['peso'] ?? null,
            'fechapublicacion' => $data['fechaPublicacion'] ?? null,

        ];



        $productobaseId = $this->producto->guardar($datosValidados);
        $producto = $this->producto->find($productobaseId);
        if ($producto) {

            $productobaseEntity = new ProductoEntity($producto);

            $productobaseEntity->estado = $this->estado->obtenerPorId($producto->idestado);

            $productobaseEntity->pdestacado = $this->parametro->obtenerPorId($producto->idpdestacado);

            $productobaseEntity->pcomplemento = $this->parametro->obtenerPorId($producto->idpcomplemento);

            $productobaseEntity->productocategoria = $this->productocategoria->obtenerPorId($producto->idproductocategoria);

            return $this->respond([
                "mensaje" => 'Producto base actualizado con éxito',
                "producto" =>  $productobaseEntity->toArray()
            ], 201);
        } else {

            return $this->respond(["mensaje" => "Error al actualizar el producto base"], 500);
        }
    }

    public function eliminar($idproducto)
    {

        if ($this->producto->eliminar(
            $idproducto
        )) {
            return $this->respond(['mensaje' => 'Producto base eliminado con éxito']);
        } else {
            return $this->failNotFound('No se encontró la producto base');
        }
    }

    

    public function reporte()
    {
        header("Access-Control-Allow-Origin: *");
        header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
        header("Access-Control-Allow-Headers: Content-Type, Authorization");

        if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
            exit; // para la petición preflight
        }
        $request = $this->request;

        // Parámetros de búsqueda y orden
        $ordencriterio = $request->getVar('ordenCriterio') ?? 'fecha';
        $ordentipo = $request->getVar('ordenTipo') ?? 'asc';
        $parametro = $request->getVar('parametro') ?? '';
        $valor = $request->getVar('valor') ?? '';
        $idestado = (int) ($request->getVar('idEstado') ?? 0);
        $idcolor = (int) ($request->getVar('idColor') ?? 0);
        $idproductocategoria = (int) ($request->getVar('idProductoCategoria') ?? 0);
        $idrproductocategoria = (int) ($request->getVar('idrProductoCategoria') ?? 0);
        $idpdestacado = (int) ($request->getVar('idpDestacado') ?? 0);
        $idpcomplemento = (int) ($request->getVar('idpComplemento') ?? 0);
        $idcupon = (int) ($request->getVar('idCupon') ?? 0);
        // Parámetros de paginación
        $pagina = (int) ($request->getVar('pagina') ?? 1);
        $registros = (int) ($request->getVar('registros') ?? 10);

        $usuarioAdm = $request->getVar('usuario') ?? '';
        // Total de registros
        $total = $this->producto->buscarPorTotal(
            $parametro,
            $valor,
            $idestado,
            $idproductocategoria,
            $idrproductocategoria,
            $idpdestacado,
            $idpcomplemento,
            $idcupon,
            $idcolor
        );

        $paginator = new Paginator($pagina, $registros, $total);



        $productos = $this->producto->buscarPor(
            $ordencriterio,
            $ordentipo,
            $parametro,
            $valor,
            $idestado,
            $idproductocategoria,
            $idrproductocategoria,
            $idpdestacado,
            $idpcomplemento,
            $idcupon,
            $idcolor,
            $paginator->getFirstElement(),
            $paginator->getSize()
        );

        $resultado = [];
        foreach ($productos as $row) {
            $productoEntity = new ProductoEntity($row);

            $productoEntity->estado = $this->estado->obtenerPorId($row->idestado);
            // $productoEntity->ppromocion = $this->parametro->obtenerPorId($row->idppromocion);
            $productoEntity->pdestacado = $this->parametro->obtenerPorId($row->idpdestacado);
            // $productoEntity->plongitud = $this->parametro->obtenerPorId($row->idplongitud);
            // $productoEntity->pajuste = $this->parametro->obtenerPorId($row->idpajuste);
            // $productoEntity->pcontrolstock = $this->parametro->obtenerPorId($row->idpcontrolstock);
            $productoEntity->productocategoria = $this->productocategoria->obtenerPorId($row->idproductocategoria);
            // $marcaObjeto = $this->marca->obtenerMarcaPorProductoBase($row->idproducto);
            // $productoEntity->marca = $marcaObjeto ? new MarcaEntity($marcaObjeto) : null;

            $resultado[] = $productoEntity->toArray();
        }
        $nombreUsuario = trim(
            ($usuarioAdm->nombres ?? '') . ' ' .
                ($usuarioAdm->pApellido ?? '') . ' ' .
                ($usuarioAdm->sApellido ?? '')
        );

        // Generar Excel
        $spreadsheet = ReporteExcelProductos::generarExcel($resultado, $nombreUsuario);

        $filename = "Reporte-de-usuarios-" . date("d-m-Y-H-i-s") . ".xlsx";

        // Limpiar buffer para evitar errores con headers
        if (ob_get_length()) ob_end_clean();

        // Headers para descarga
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header("Content-Disposition: attachment; filename=\"$filename\"");
        header('Cache-Control: max-age=0');
        header('Expires: 0');
        header('Pragma: public');

        // Escribir archivo
        $writer = new Xlsx($spreadsheet);
        $writer->save('php://output');

        exit;
    }



    // public function productosCargaMasiva()
    // {
    //     $archivo = $this->request->getFile('archivo');
    //     if (!$archivo || !$archivo->isValid()) {
    //         return $this->respond([
    //             'mensajes' => [['referencia' => 'N/A', 'mensaje' => 'Debe incluir un archivo Excel', 'estado' => 0]]
    //         ], 400);
    //     }

    //     $spreadsheet = IOFactory::load($archivo->getTempName());
    //     $sheet = $spreadsheet->getActiveSheet();
    //     $rows = $sheet->toArray();

    //     // Normalizar encabezados
    //     $headers = array_map(function ($h) {
    //         $h = strtolower(trim($h));
    //         $h = str_replace([' ', '_', 'á', 'é', 'í', 'ó', 'ú', 'ñ'], ['', '', 'a', 'e', 'i', 'o', 'u', 'n'], $h);
    //         if ($h === 'categoria') return 'categorias';
    //         return $h;
    //     }, $rows[0]);
    //     unset($rows[0]);

    //     $respuestaFinal = [];

    //     foreach ($rows as $row) {
    //         $value = array_combine($headers, array_map('trim', $row));
    //         if (empty($value['codigo']) && empty($value['producto'])) continue;

    //         // Validar campos obligatorios
    //         $faltantes = $this->validarDatosCargaMasiva($value);
    //         if (!empty($faltantes)) {
    //             $respuestaFinal[] = [
    //                 'referencia' => $value['codigo'] ?? 'SIN CÓDIGO',
    //                 'mensaje' => 'Faltan los siguientes campos: ' . implode(', ', $faltantes),
    //                 'estado' => 0
    //             ];
    //             continue;
    //         }

    //         // Verificar si el producto ya existe
    //         if ($this->producto->where('codigo', $value['codigo'])->first()) {
    //             $respuestaFinal[] = [
    //                 'referencia' => $value['codigo'],
    //                 'mensaje' => 'El código ya se encuentra registrado',
    //                 'estado' => 0
    //             ];
    //             continue;
    //         }

    //         // Verificar categoría
    //         $categoria = $this->verificarCategorias($value['categorias']);
    //         if (!$categoria) {
    //             $respuestaFinal[] = [
    //                 'referencia' => $value['codigo'],
    //                 'mensaje' => 'Categoría no existe',
    //                 'estado' => 0
    //             ];
    //             continue;
    //         }

    //         // Estado
    //         $estado = (isset($value['estado']) && strtolower($value['estado']) === 'activo') ? 325 : 326;

    //         // Destacado
    //         $idDestacado = (isset($value['destacado']) && strtolower($value['destacado']) === 'si') ? 394 : 395;

    //         // Complemento / Combo / Producto
    //         try {
    //             $tipo = strtolower(trim($value['complemento'] ?? ''));
    //             switch ($tipo) {
    //                 case 'producto':
    //                     $idComplemento = 400;
    //                     break;
    //                 case 'complemento':
    //                     $idComplemento = 401;
    //                     break;
    //                 case 'combo':
    //                     $idComplemento = 402;
    //                     break;
    //                 default:
    //                     throw new \Exception("Valor inválido en 'complemento': '{$value['complemento']}'. Debe ser 'producto', 'complemento' o 'combo'.");
    //             }
    //         } catch (\Throwable $e) {
    //             $respuestaFinal[] = [
    //                 'referencia' => $value['codigo'],
    //                 'mensaje' => $e->getMessage(),
    //                 'estado' => 0
    //             ];
    //             continue;
    //         }

    //         // Guardar producto
    //         try {
    //             $productoId = $this->producto->guardar([
    //                 'codigo' => strtoupper($value['codigo']),
    //                 'nombre' => strtoupper($value['producto']),
    //                 'urlamigable' => strtolower(preg_replace('/\s+/', '-', trim($value['producto']))),
    //                 'peso' => $value['peso'] ?? 0,
    //                 // 'preciolista' => floatval($value['preciolista'] ?? 0),
    //                 // 'precioventa' => floatval($value['precioventa'] ?? 0),

    //                 'preciolista' => $this->limpiarPrecio($value['preciolista'] ?? 0),
    //                 'precioventa' => $this->limpiarPrecio($value['precioventa'] ?? 0),

    //                 // 'preciolista'       => $this->limpiarPrecio($value['preciolista'] ?? 0),
    //                 // 'precioventa'       => $this->limpiarPrecio($value['precioventa'] ?? 0),
    //                 'idestado' => $estado,
    //                 'idproductocategoria' => $categoria->idproductocategoria,
    //                 'idpdestacado' => $idDestacado,
    //                 'idpcomplemento' => $idComplemento,
    //                 'fechapublicacion' => date('Y-m-d H:i:s'),
    //                 'fecha' => date('Y-m-d H:i:s')
    //             ]);
    //         } catch (\Throwable $e) {
    //             $respuestaFinal[] = [
    //                 'referencia' => $value['codigo'],
    //                 'mensaje' => 'Error al guardar producto: ' . $e->getMessage(),
    //                 'estado' => 0
    //             ];
    //             continue;
    //         }

    //         // Guardar stocks
    //         $productoColorModel = new ProductoColorModel();
    //         $colorModel = new ColorModel(); // Asegúrate de tener este modelo cargado

    //         foreach ($value as $key => $val) {
    //             if (strtolower(substr($key, 0, 5)) === 'stock' && !empty($val)) {
    //                 $partes = explode('-', $val);
    //                 $codigoColor = strtoupper($partes[0] ?? null);
    //                 $cantidad = isset($partes[1]) ? (int)$partes[1] : 0;

    //                 if (!$codigoColor || $cantidad <= 0) {
    //                     $respuestaFinal[] = [
    //                         'referencia' => $value['codigo'],
    //                         'mensaje' => "Stock inválido en $key: $val",
    //                         'estado' => 0
    //                     ];
    //                     continue;
    //                 }

    //                 // Buscar color por código
    //                 $color = $colorModel->buscarPorAbr($codigoColor);
    //                 if (!$color) {
    //                     $respuestaFinal[] = [
    //                         'referencia' => $value['codigo'],
    //                         'mensaje' => "Color no encontrado para el código: $codigoColor",
    //                         'estado' => 0
    //                     ];
    //                     continue;
    //                 }

    //                 // Guardar color-producto
    //                 try {
    //                     $productoColorModel->guardar([
    //                         'idproducto'    => $productoId,
    //                         'idcolor'       => $color['idcolor'],
    //                         'nombre'        => $color['nombre'],
    //                         'urlamigable'   => strtolower(preg_replace('/\s+/', '-', trim($value['producto'] . ' ' . $color['nombre']))),
    //                         'stock'         => $cantidad,
    //                         'idestado'      => 405,
    //                         'fecha'         => date('Y-m-d H:i:s')
    //                     ]);
    //                 } catch (\Throwable $e) {
    //                     $respuestaFinal[] = [
    //                         'referencia' => $value['codigo'],
    //                         'mensaje' => "Error al guardar stock $key ($codigoColor): " . $e->getMessage(),
    //                         'estado' => 0
    //                     ];
    //                     continue;
    //                 }
    //             }
    //         }

    //         $respuestaFinal[] = [
    //             'referencia' => $value['codigo'],
    //             'mensaje' => 'Producto registrado exitosamente',
    //             'estado' => 1
    //         ];
    //     }

    //     return $this->respond(['mensajes' => $respuestaFinal], 200);
    // }

    // public function productosActualizacionMasiva()
    // {
    //     $archivo = $this->request->getFile('archivo');
    //     if (!$archivo || !$archivo->isValid()) {
    //         return $this->respond([
    //             'mensajes' => [['referencia' => 'N/A', 'mensaje' => 'Debe incluir un archivo Excel', 'estado' => 0]]
    //         ], 400);
    //     }

    //     $spreadsheet = IOFactory::load($archivo->getTempName());
    //     $sheet = $spreadsheet->getActiveSheet();
    //     $rows = $sheet->toArray();

    //     // Normalizar encabezados
    //     $headers = array_map(function ($h) {
    //         $h = strtolower(trim($h));
    //         $h = str_replace([' ', '_', 'á', 'é', 'í', 'ó', 'ú', 'ñ'], ['', '', 'a', 'e', 'i', 'o', 'u', 'n'], $h);
    //         if ($h === 'categoria') return 'categorias';
    //         return $h;
    //     }, $rows[0]);
    //     unset($rows[0]);

    //     $respuestaFinal = [];

    //     foreach ($rows as $row) {
    //         $value = array_combine($headers, array_map('trim', $row));
    //         if (empty($value['codigo']) && empty($value['producto'])) continue;

    //         // Validar datos
    //         $faltantes = $this->validarDatosActualizacion($value);
    //         if (!empty($faltantes)) {
    //             $respuestaFinal[] = [
    //                 'referencia' => $value['codigo'] ?? 'SIN CÓDIGO',
    //                 'mensaje' => 'Faltan los siguientes campos: ' . implode(', ', $faltantes),
    //                 'estado' => 0
    //             ];
    //             continue;
    //         }

    //         // Verificar existencia del producto
    //         $producto = $this->producto->where('codigo', $value['codigo'])->first();
    //         if (!$producto) {
    //             $respuestaFinal[] = [
    //                 'referencia' => $value['codigo'],
    //                 'mensaje' => 'El código no existe en la base de datos',
    //                 'estado' => 0
    //             ];
    //             continue;
    //         }

    //         // Estado
    //         $estado = (isset($value['estado']) && strtolower($value['estado']) === 'activo') ? 325 : 326;

    //         // Destacado
    //         $idDestacado = (isset($value['destacado']) && strtolower($value['destacado']) === 'si') ? 394 : 395;

    //         // Complemento / Combo / Producto
    //         try {
    //             $tipo = strtolower(trim($value['complemento'] ?? ''));
    //             switch ($tipo) {
    //                 case 'producto':
    //                     $idComplemento = 400;
    //                     break;
    //                 case 'complemento':
    //                     $idComplemento = 401;
    //                     break;
    //                 case 'combo':
    //                     $idComplemento = 402;
    //                     break;
    //                 default:
    //                     throw new \Exception("Valor inválido en 'complemento': '{$value['complemento']}'. Debe ser 'producto', 'complemento' o 'combo'.");
    //             }
    //         } catch (\Throwable $e) {
    //             $respuestaFinal[] = [
    //                 'referencia' => $value['codigo'],
    //                 'mensaje' => $e->getMessage(),
    //                 'estado' => 0
    //             ];
    //             continue;
    //         }

    //         // Verificar categoría
    //         $categoriaId = $producto->idproductocategoria;
    //         if (!empty($value['categorias'])) {
    //             $categoria = $this->verificarCategorias($value['categorias']);
    //             if (!$categoria) {
    //                 $respuestaFinal[] = [
    //                     'referencia' => $value['codigo'],
    //                     'mensaje' => 'Categoría no existe: ' . $value['categorias'],
    //                     'estado' => 0
    //                 ];
    //                 continue;
    //             }
    //             $categoriaId = $categoria->idproductocategoria;
    //         }

    //         // Actualizar producto
    //         try {
    //             $this->producto->guardar([
    //                 'idproducto' => $producto->idproducto,
    //                 'peso' => $value['peso'] ?? 0,
    //                 // 'preciolista' => floatval($value['preciolista'] ?? 0),
    //                 // 'precioventa' => floatval($value['precioventa'] ?? 0),
    //                 'preciolista' => $this->limpiarPrecio($value['preciolista'] ?? 0),
    //                 'precioventa' => $this->limpiarPrecio($value['precioventa'] ?? 0),

    //                 // 'preciolista'       => $this->limpiarPrecio($value['preciolista'] ?? 0),
    //                 // 'precioventa'       => $this->limpiarPrecio($value['precioventa'] ?? 0),
    //                 'idestado' => $estado,
    //                 'idproductocategoria' => $categoriaId,
    //                 'idpdestacado' => $idDestacado,
    //                 'idpcomplemento' => $idComplemento
    //             ]);
    //         } catch (\Throwable $e) {
    //             $respuestaFinal[] = [
    //                 'referencia' => $value['codigo'],
    //                 'mensaje' => 'Error al actualizar producto: ' . $e->getMessage(),
    //                 'estado' => 0
    //             ];
    //             continue;
    //         }

    //         // Eliminar stocks existentes
    //         $productoColorModel = new ProductoColorModel();
    //         $productoColorModel->where('idproducto', $producto->idproducto)->delete();

    //         // Guardar nuevos stocks
    //         $colorModel = new ColorModel();
    //         foreach ($value as $key => $val) {
    //             if (strtolower(substr($key, 0, 5)) === 'stock' && !empty($val)) {
    //                 $partes = explode('-', $val);
    //                 $codigoColor = strtoupper($partes[0] ?? null);
    //                 $cantidad = isset($partes[1]) ? (int)$partes[1] : 0;

    //                 if (!$codigoColor || $cantidad <= 0) {
    //                     $respuestaFinal[] = [
    //                         'referencia' => $value['codigo'],
    //                         'mensaje' => "Stock inválido en $key: $val",
    //                         'estado' => 0
    //                     ];
    //                     continue;
    //                 }

    //                 // Buscar color por código
    //                 $color = $colorModel->buscarPorAbr($codigoColor);
    //                 if (!$color) {
    //                     $respuestaFinal[] = [
    //                         'referencia' => $value['codigo'],
    //                         'mensaje' => "Color no encontrado para el código: $codigoColor",
    //                         'estado' => 0
    //                     ];
    //                     continue;
    //                 }

    //                 // Guardar color-producto
    //                 try {
    //                     $productoColorModel->guardar([
    //                         'idproducto'    => $producto->idproducto,
    //                         'idcolor'       => $color['idcolor'],
    //                         'nombre'        => $color['nombre'],
    //                         'urlamigable'   => strtolower(preg_replace('/\s+/', '-', trim($value['producto'] . ' ' . $color['nombre']))),
    //                         'stock'         => $cantidad,
    //                         'idestado'      => 405,
    //                         'fecha'         => date('Y-m-d H:i:s')
    //                     ]);
    //                 } catch (\Throwable $e) {
    //                     $respuestaFinal[] = [
    //                         'referencia' => $value['codigo'],
    //                         'mensaje' => "Error al guardar stock $key ($codigoColor): " . $e->getMessage(),
    //                         'estado' => 0
    //                     ];
    //                     continue;
    //                 }
    //             }
    //         }

    //         $respuestaFinal[] = [
    //             'referencia' => $value['codigo'],
    //             'mensaje' => 'Producto actualizado correctamente',
    //             'estado' => 1
    //         ];
    //     }

    //     return $this->respond(['mensajes' => $respuestaFinal], 200);
    // }
    public function productosCargaMasiva()
    {
        $archivo = $this->request->getFile('archivo');
        if (!$archivo || !$archivo->isValid()) {
            return $this->respond([
                'mensajes' => [[
                    'referencia' => 'N/A',
                    'mensaje' => 'Debe incluir un archivo Excel',
                    'estado' => 0
                ]]
            ], 400);
        }

        $spreadsheet = IOFactory::load($archivo->getTempName());
        $sheet = $spreadsheet->getActiveSheet();
        $rows = $sheet->toArray();

        // 🟡 Normalizar encabezados
        $headers = array_map(function ($h) {
            $h = strtolower(trim($h));
            $h = str_replace([' ', '_', 'á', 'é', 'í', 'ó', 'ú', 'ñ'], ['', '', 'a', 'e', 'i', 'o', 'u', 'n'], $h);
            if ($h === 'categoria') return 'categorias';
            return $h;
        }, $rows[0]);
        unset($rows[0]);

        $respuestaFinal = [];
        $colorModel = new ColorModel();

        foreach ($rows as $row) {
            $value = array_combine($headers, array_map('trim', $row));

            if (empty($value['codigo']) && empty($value['producto'])) continue;

            // 🟡 Validar campos obligatorios
            $faltantes = $this->validarDatosCargaMasiva($value);
            if (!empty($faltantes)) {
                $respuestaFinal[] = [
                    'referencia' => $value['codigo'] ?? 'SIN CÓDIGO',
                    'mensaje' => 'Faltan los siguientes campos: ' . implode(', ', $faltantes),
                    'estado' => 0
                ];
                continue;
            }

            // 🟡 Verificar duplicado
            if ($this->producto->where('codigo', $value['codigo'])->first()) {
                $respuestaFinal[] = [
                    'referencia' => $value['codigo'],
                    'mensaje' => 'El código ya se encuentra registrado',
                    'estado' => 0
                ];
                continue;
            }

            // 🟡 Categoría
            $categoria = $this->verificarCategorias($value['categorias']);
            if (!$categoria) {
                $respuestaFinal[] = [
                    'referencia' => $value['codigo'],
                    'mensaje' => 'Categoría no existe',
                    'estado' => 0
                ];
                continue;
            }

            // 🟡 Estado y destacado
            $estado = (isset($value['estado']) && strtolower($value['estado']) === 'activo') ? 325 : 326;
            $idDestacado = (isset($value['destacado']) && strtolower($value['destacado']) === 'si') ? 394 : 395;

            // 🟡 Complemento
            try {
                $tipo = strtolower(trim($value['complemento'] ?? ''));
                switch ($tipo) {
                    case 'producto':
                        $idComplemento = 400;
                        break;
                    case 'complemento':
                        $idComplemento = 401;
                        break;
                    case 'combo':
                        $idComplemento = 402;
                        break;
                    default:
                        throw new \Exception("Valor inválido en 'complemento'.");
                }
            } catch (\Throwable $e) {
                $respuestaFinal[] = [
                    'referencia' => $value['codigo'],
                    'mensaje' => $e->getMessage(),
                    'estado' => 0
                ];
                continue;
            }

            // 🟡 Buscar color
            $idColor = null;
            if (!empty($value['color'])) {
                $color = $colorModel->where('codigoproductocolor', strtoupper($value['color']))->first();
                if (!$color) {
                    $respuestaFinal[] = [
                        'referencia' => $value['codigo'],
                        'mensaje' => 'Color no encontrado: ' . $value['color'],
                        'estado' => 0
                    ];
                    continue;
                }
                $idColor = $color->idcolor;
            }

            // 🟢 Guardar producto
            try {
                $this->producto->guardar([
                    'codigo'                => strtoupper($value['codigo']),
                    'nombre'                => strtoupper($value['producto']),
                    'urlamigable'           => strtolower(preg_replace('/\s+/', '-', trim($value['producto']))),
                    'peso'                  => $value['peso'] ?? 0,
                    'preciolista'           => $this->limpiarPrecio($value['preciolista'] ?? 0),
                    'precioventa'           => $this->limpiarPrecio($value['precioventa'] ?? 0),
                    'resumen'               => $value['resumen'] ?? null,
                    'contenido'             => $value['contenido'] ?? null,
                    'idestado'              => $estado,
                    'idproductocategoria'   => $categoria->idproductocategoria,
                    'idpdestacado'          => $idDestacado,
                    'idpcomplemento'        => $idComplemento,
                    'idcolor'               => $idColor,
                    'stock'                 => (int)($value['stock'] ?? 0),
                    'fechapublicacion'      => date('Y-m-d H:i:s'),
                    'fecha'                 => date('Y-m-d H:i:s')
                ]);

                $respuestaFinal[] = [
                    'referencia' => $value['codigo'],
                    'mensaje' => 'Producto registrado exitosamente',
                    'estado' => 1
                ];
            } catch (\Throwable $e) {
                $respuestaFinal[] = [
                    'referencia' => $value['codigo'],
                    'mensaje' => 'Error al guardar producto: ' . $e->getMessage(),
                    'estado' => 0
                ];
            }
        }

        return $this->respond(['mensajes' => $respuestaFinal], 200);
    }


    public function productosActualizacionMasiva()
    {
        $archivo = $this->request->getFile('archivo');
        if (!$archivo || !$archivo->isValid()) {
            return $this->respond([
                'mensajes' => [[
                    'referencia' => 'N/A',
                    'mensaje' => 'Debe incluir un archivo Excel',
                    'estado' => 0
                ]]
            ], 400);
        }

        $spreadsheet = IOFactory::load($archivo->getTempName());
        $sheet = $spreadsheet->getActiveSheet();
        $rows = $sheet->toArray();

        // 🟡 Normalizar encabezados
        $headers = array_map(function ($h) {
            $h = strtolower(trim($h));
            $h = str_replace([' ', '_', 'á', 'é', 'í', 'ó', 'ú', 'ñ'], ['', '', 'a', 'e', 'i', 'o', 'u', 'n'], $h);
            if ($h === 'categoria') return 'categorias';
            return $h;
        }, $rows[0]);
        unset($rows[0]);

        $respuestaFinal = [];
        $colorModel = new ColorModel();

        foreach ($rows as $row) {
            $value = array_combine($headers, array_map('trim', $row));
            if (empty($value['codigo'])) continue;

            // 🟡 Buscar producto
            $producto = $this->producto->where('codigo', $value['codigo'])->first();
            if (!$producto) {
                $respuestaFinal[] = [
                    'referencia' => $value['codigo'],
                    'mensaje' => 'El código no existe',
                    'estado' => 0
                ];
                continue;
            }

            // 🟡 Estado
            $estado = (isset($value['estado']) && strtolower($value['estado']) === 'activo') ? 325 : 326;
            $idDestacado = (isset($value['destacado']) && strtolower($value['destacado']) === 'si') ? 394 : 395;

            // 🟡 Complemento
            try {
                $tipo = strtolower(trim($value['complemento'] ?? ''));
                switch ($tipo) {
                    case 'producto':
                        $idComplemento = 400;
                        break;
                    case 'complemento':
                        $idComplemento = 401;
                        break;
                    case 'combo':
                        $idComplemento = 402;
                        break;
                    default:
                        throw new \Exception("Valor inválido en 'complemento'.");
                }
            } catch (\Throwable $e) {
                $respuestaFinal[] = [
                    'referencia' => $value['codigo'],
                    'mensaje' => $e->getMessage(),
                    'estado' => 0
                ];
                continue;
            }

            // 🟡 Categoría (si viene)
            $categoriaId = $producto->idproductocategoria;
            if (!empty($value['categorias'])) {
                $categoria = $this->verificarCategorias($value['categorias']);
                if (!$categoria) {
                    $respuestaFinal[] = [
                        'referencia' => $value['codigo'],
                        'mensaje' => 'Categoría no existe: ' . $value['categorias'],
                        'estado' => 0
                    ];
                    continue;
                }
                $categoriaId = $categoria->idproductocategoria;
            }

            // 🟡 Color (si viene)
            $idColor = $producto->idcolor;
            if (!empty($value['color'])) {
                $color = $colorModel->where('codigoproductocolor', strtoupper($value['color']))->first();
                if (!$color) {
                    $respuestaFinal[] = [
                        'referencia' => $value['codigo'],
                        'mensaje' => 'Color no encontrado: ' . $value['color'],
                        'estado' => 0
                    ];
                    continue;
                }
                $idColor = $color->idcolor;
            }

            // 🟢 Actualizar producto
            try {
                $this->producto->guardar([
                    'idproducto'             => $producto->idproducto,
                    'peso'                   => $value['peso'] ?? $producto->peso,
                    'preciolista'            => $this->limpiarPrecio($value['preciolista'] ?? $producto->preciolista),
                    'precioventa'            => $this->limpiarPrecio($value['precioventa'] ?? $producto->precioventa),
                    'stock'                  => isset($value['stock']) ? (int)$value['stock'] : $producto->stock,
                    'resumen'                => $value['resumen'] ?? $producto->resumen,
                    'contenido'              => $value['contenido'] ?? $producto->contenido,
                    'idestado'               => $estado,
                    'idproductocategoria'    => $categoriaId,
                    'idpdestacado'           => $idDestacado,
                    'idpcomplemento'         => $idComplemento,
                    'idcolor'                => $idColor
                ]);

                $respuestaFinal[] = [
                    'referencia' => $value['codigo'],
                    'mensaje' => 'Producto actualizado correctamente',
                    'estado' => 1
                ];
            } catch (\Throwable $e) {
                $respuestaFinal[] = [
                    'referencia' => $value['codigo'],
                    'mensaje' => 'Error al actualizar producto: ' . $e->getMessage(),
                    'estado' => 0
                ];
                continue;
            }
        }

        return $this->respond(['mensajes' => $respuestaFinal], 200);
    }



    private function limpiarPrecio($valor)
    {
        if ($valor === null) {
            return 0;
        }

        // Eliminar todo lo que no sea dígito o punto
        $valorLimpio = preg_replace('/[^0-9.]/', '', trim($valor));

        // Si está vacío después de limpiar, devolvemos 0
        if ($valorLimpio === '' || $valorLimpio === null) {
            return 0;
        }

        return (float)$valorLimpio;
    }



    private function validarDatosCargaMasiva($value)
    {
        $faltantes = [];
        if (empty($value['codigo'])) $faltantes[] = 'codigo';
        if (empty($value['producto'])) $faltantes[] = 'producto';
        if (empty($value['categorias'])) $faltantes[] = 'categorias';
        if (!isset($value['peso'])) $faltantes[] = 'peso';
        return $faltantes;
    }

    private function validarDatosActualizacion($value)
    {
        $faltantes = [];
        if (empty($value['codigo'])) $faltantes[] = 'codigo';
        if (!isset($value['peso'])) $faltantes[] = 'peso';
        if (!isset($value['preciolista'])) $faltantes[] = 'preciolista';
        if (!isset($value['precioventa'])) $faltantes[] = 'precioventa';
        if (empty($value['estado'])) $faltantes[] = 'estado';
        return $faltantes;
    }

    private function verificarCategorias($categoriasString)
    {
        $categorias = array_map('trim', explode('/', $categoriasString));

        $categoriaPadre = (new ProductoCategoriaModel())
            ->where('LOWER(nombre)', strtolower($categorias[0]))
            ->first();

        if (!$categoriaPadre) return false;

        if (count($categorias) === 1) {
            return $categoriaPadre;
        }

        $categoriaHijo = (new ProductoCategoriaModel())
            ->where('LOWER(nombre)', strtolower($categorias[1]))
            ->where('idrproductocategoria', $categoriaPadre->idproductocategoria)
            ->first();

        return $categoriaHijo ?: false;
    }


    public function productoActualizarExcel()
    {
        // 🔹 Configuración CORS
        header("Access-Control-Allow-Origin: *");
        header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
        header("Access-Control-Allow-Headers: Content-Type, Authorization");

        if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
            exit;
        }

        $request = $this->request;

        // 🔹 Parámetros de búsqueda y orden
        $ordencriterio = $request->getVar('ordenCriterio') ?? 'fecha';
        $ordentipo = $request->getVar('ordenTipo') ?? 'asc';
        $parametro = $request->getVar('parametro') ?? '';
        $valor = $request->getVar('valor') ?? '';
        $idestado = (int) ($request->getVar('idEstado') ?? 0);
        $idcolor = (int) ($request->getVar('idColor') ?? 0);
        $idproductocategoria = (int) ($request->getVar('idProductoCategoria') ?? 0);
        $idrproductocategoria = (int) ($request->getVar('idrProductoCategoria') ?? 0);
        $idpdestacado = (int) ($request->getVar('idpDestacado') ?? 0);
        $idpcomplemento = (int) ($request->getVar('idpComplemento') ?? 0);
        $idcupon = (int) ($request->getVar('idCupon') ?? 0);

        // 🔹 Paginación
        $pagina = (int) ($request->getVar('pagina') ?? 1);
        $registros = (int) ($request->getVar('registros') ?? 10);
        $usuarioAdm = $request->getVar('usuario') ?? '';

        // 🔹 Total de registros
        $total = $this->producto->buscarPorTotal(
            $parametro,
            $valor,
            $idestado,
            $idproductocategoria,
            $idrproductocategoria,
            $idpdestacado,
            $idpcomplemento,
            $idcupon,
            $idcolor
        );

        $paginator = new Paginator($pagina, $registros, $total);

        // 🔹 Obtener productos
        $productos = $this->producto->buscarPor(
            $ordencriterio,
            $ordentipo,
            $parametro,
            $valor,
            $idestado,
            $idproductocategoria,
            $idrproductocategoria,
            $idpdestacado,
            $idpcomplemento,
            $idcupon,
            $idcolor,
            $paginator->getFirstElement(),
            $paginator->getSize()
        );

        if (empty($productos)) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'No se encontraron productos con los filtros seleccionados.'
            ]);
        }

        $resultado = [];

        foreach ($productos as $row) {
            $productoEntity = new ProductoEntity($row);

            // 🔸 Obtener datos relacionados
            $estado = $this->estado->obtenerPorId($row->idestado);
            $destacado = $this->parametro->obtenerPorId($row->idpdestacado);
            $categoria = $this->productocategoria->obtenerPorId($row->idproductocategoria);
            $complemento = $this->parametro->obtenerPorId($row->idpcomplemento);

            // 🔸 Obtener color
            $color = null;
            if (!empty($row->idcolor)) {
                $colorEntity = $this->color->obtenerPorId($row->idcolor);
                $color = $colorEntity ? $colorEntity->codigoproductocolor : '';
            }

            $resultado[] = [
                'codigo' => $row->codigo ?? '',
                'nombre' => $row->nombre ?? '',
                'categoria' => $categoria->nombre ?? '',
                'destacado' => isset($destacado->nombre) && strtolower($destacado->nombre) === 'si' ? 'Si' : 'No',
                'complemento' => $complemento->nombre ?? '',
                'estado' => $estado->nombre ?? '',
                'peso' => $row->peso ?? 0,
                'precioLista' => $row->preciolista ?? 0,
                'precioVenta' => $row->precioventa ?? 0,
                'resumen' => $row->resumen ?? '',
                'contenido' => $row->contenido ?? '',
                'color' => $color ?? '',
                'stock' => $row->stock ?? 0,
            ];
        }

        // 🔹 Generar Excel
        $spreadsheet = \App\Helpers\Excel\ReporteExcelProductosActualizar::generarExcel($resultado);

        $filename = "Productos-actualizacion-" . date("d-m-Y-H-i-s") . ".xlsx";

        if (ob_get_length()) ob_end_clean();

        // 🔹 Headers HTTP
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header("Content-Disposition: attachment; filename=\"$filename\"");
        header('Cache-Control: max-age=0');
        header('Expires: 0');
        header('Pragma: public');

        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
        $writer->save('php://output');
        exit;
    }
}
