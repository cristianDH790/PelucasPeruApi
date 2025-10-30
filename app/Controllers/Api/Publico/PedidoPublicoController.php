<?php

namespace App\Controllers\Api\Publico;

use App\Controllers\BaseController;
use App\Models\PedidoModel;
use App\Models\PedidoDetalleModel;
use App\Models\ProductoModel;
use App\Models\ComprobanteModel;
use App\Models\CuponModel;

class PedidoPublicoController extends BaseController
{
    public function checkPedido()
    {
        log_message('error', '=== CHECKPEDIDO INICIADO ===');
        try {
            $request = service('request');
            date_default_timezone_set('America/Lima');

            // ---------- CAPTURA DE DATOS ----------
            $idProductosRaw = $request->getPost('idProductos') ?? '';
            $cantidadesRaw = $request->getPost('cantidades') ?? '';
            $descuentoProductosRaw = $request->getPost('descuentoProductos') ?? '';

            $idProductos = array_filter(array_map('trim', explode(',', $idProductosRaw)));
            $cantidades = array_filter(array_map('trim', explode(',', $cantidadesRaw)));
            $descuentoProductos = array_filter(array_map('trim', explode(',', $descuentoProductosRaw)));

            if (empty($idProductos) || empty($cantidades) || count($idProductos) !== count($cantidades)) {
                return $this->response->setJSON([
                    'status' => 'error',
                    'errors' => ['No existen productos o cantidades válidas']
                ]);
            }

            $usuario = json_decode($request->getPost('usuario'), true) ?? [];
            $formapago = json_decode($request->getPost('formapago'), true) ?? [];
            $entrega = json_decode($request->getPost('entrega'), true) ?? [];

            // Validar datos críticos
            if (empty($usuario) || !isset($usuario['idUsuario'])) {
                return $this->response->setJSON([
                    'status' => 'error',
                    'mensaje' => 'Datos de usuario inválidos'
                ]);
            }

            if (empty($formapago) || !isset($formapago['idFormaPago'])) {
                return $this->response->setJSON([
                    'status' => 'error',
                    'mensaje' => 'Datos de forma de pago inválidos'
                ]);
            }

            if (empty($entrega) || !isset($entrega['idEntrega'])) {
                return $this->response->setJSON([
                    'status' => 'error',
                    'mensaje' => 'Datos de entrega inválidos'
                ]);
            }
            $costoEnvio = floatval($request->getPost('costoEnvio') ?? 0);
            $comision = floatval($request->getPost('comision') ?? 0);
            $referencia = $request->getPost('referencia') ?? $request->getPost('codigo') ?? 'PED-' . time();
            $subtotal = floatval($request->getPost('subtotal') ?? 0);
            $descuento = floatval($request->getPost('descuento') ?? 0);
            $total = floatval($request->getPost('total') ?? 0);
            $fechaEntrega = $request->getPost('fechaEntrega') ?? date('Y-m-d');
            $observacion = $request->getPost('observacion') ?? '';
            $cupon = $request->getPost('cupon') ?? '';
            $misComprobantes = $request->getPost('misComprobantes') ?? '';
            $comprobante = json_decode($request->getPost('comprobante'), true);

            // Log de datos recibidos
            log_message('error', '=== INICIO PROCESAMIENTO PEDIDO ===');
            log_message('error', 'Datos recibidos en controlador:');
            log_message('error', 'Usuario: ' . json_encode($usuario));
            log_message('error', 'FormaPago: ' . json_encode($formapago));
            log_message('error', 'Entrega: ' . json_encode($entrega));
            log_message('error', 'Referencia: ' . $referencia);
            log_message('error', 'Subtotal: ' . $subtotal);
            log_message('error', 'Total: ' . $total);

            // ---------- GUARDAR O ACTUALIZAR PEDIDO ----------
            $pedidoModel = new PedidoModel();
            $pedido = $pedidoModel->guardarOrActualizarPedido(
                $referencia,
                $usuario,
                $entrega,
                $costoEnvio,
                $comision,
                $subtotal,
                $descuento,
                $total,
                $fechaEntrega,
                $observacion,
                $formapago
            ); // $pedido es un objeto PedidoEntity

            // ---------- GUARDAR CONSTANCIA (ARCHIVO OPCIONAL) ----------
            // if ($file = $request->getFile('constancia')) {
            //     if ($file->isValid() && !$file->hasMoved()) {
            //         $nombreArchivo = $pedido->idpedido . '-' . url_title($referencia, '-', true) . '.' . $file->getClientExtension();
            //         $file->move(WRITEPATH . 'uploads/pedido', $nombreArchivo);
            //         $pedidoModel->update($pedido->idpedido, ['urlconstancia' => $nombreArchivo]);
            //     }
            // }

            if ($file = $request->getFile('constancia')) {
                if ($file->isValid() && !$file->hasMoved()) {
                    // Generar nombre de archivo
                    $nombreArchivo = $pedido->idpedido . '-' . url_title($referencia, '-', true) . '.' . $file->getClientExtension();

                    // Ruta destino dentro del public_html/public/archivos/pedido
                    $rutaDestino = FCPATH . 'archivos/pedido/';

                    // Crear carpeta si no existe
                    if (!is_dir($rutaDestino)) {
                        mkdir($rutaDestino, 0777, true);
                    }

                    // Mover archivo
                    $file->move($rutaDestino, $nombreArchivo);

                    // Guardar en la base de datos
                    $pedidoModel->update($pedido->idpedido, ['urlconstancia' => $nombreArchivo]);
                }
            }

            // ---------- GUARDAR DETALLES DE PRODUCTOS ----------
            // $productoModel = new ProductoModel();
            // $pedidoDetalleModel = new PedidoDetalleModel();

            // foreach ($idProductos as $key => $idProducto) {
            //     $producto = $productoModel->find($idProducto);
            //     if (!$producto) continue;

            //     $cantidad = intval($cantidades[$key] ?? 1);
            //     $descuentoProd = isset($descuentoProductos[$key]) ? floatval($descuentoProductos[$key]) : 0;

            //     $pedidoDetalleModel->insert([
            //         'idpedido' => $pedido->idpedido,
            //         'idproducto' => $idProducto,
            //         'cantidad' => $cantidad,
            //         'peso' => $producto->peso ?? 0,
            //         'precio' => $producto->precioventa ?? 0,
            //         'descuento' => $descuentoProd,
            //         'total' => ($producto->precioventa ?? 0) * $cantidad - $descuentoProd
            //     ]);
            // }

            // stock disminuir probar 

            $productoModel = new ProductoModel();
            $pedidoDetalleModel = new PedidoDetalleModel();

            foreach ($idProductos as $key => $idProducto) {
                $producto = $productoModel->find($idProducto);
                if (!$producto) continue;

                $cantidad = intval($cantidades[$key] ?? 1);
                $descuentoProd = isset($descuentoProductos[$key]) ? floatval($descuentoProductos[$key]) : 0;

                // Insertar detalle del pedido
                $pedidoDetalleModel->insert([
                    'idpedido' => $pedido->idpedido,
                    'idproducto' => $idProducto,
                    'cantidad' => $cantidad,
                    'peso' => $producto->peso ?? 0,
                    'precio' => $producto->precioventa ?? 0,
                    'descuento' => $descuentoProd,
                    'total' => ($producto->precioventa ?? 0) * $cantidad - $descuentoProd
                ]);

                // Disminuir stock
                $nuevoStock = ($producto->stock ?? 0) - $cantidad;
                $productoModel->update($idProducto, ['stock' => $nuevoStock]);
            }




            // ---------- COMPROBANTES ----------
            // $comprobanteModel = new ComprobanteModel();
            // if (empty($misComprobantes) || in_array($misComprobantes, ['undefined', 'null'])) {
            //     $newComprobante = $comprobanteModel->crearComprobante(json_decode($request->getPost('comprobante'), true), $usuario);
            //     $pedidoModel->asociarComprobante($pedido->idpedido, $newComprobante->idcomprobante);
            // } else {
            //     $pedidoModel->asociarComprobante($pedido->idpedido, explode(',', $misComprobantes));
            // }

            // ---------- COMPROBANTES ----------
            // $comprobanteModel = new ComprobanteModel();

            // if (empty($misComprobantes) || in_array($misComprobantes, ['undefined', 'null'])) {
            //     // Obtener los datos del comprobante desde la solicitud
            //     $comprobante = json_decode($request->getPost('comprobante'), true);

            //     if ($comprobante) {
            //         // Verificar tipo de comprobante
            //         $idPtipo = $comprobante['ptipo']['idParametro'] ?? null;

            //         // --- Lógica según tipo de comprobante ---
            //         if ($idPtipo == 445) {
            //             // 🧾 Boleta: usar nombres/documento como razón social y ruc
            //             $comprobante['razonSocial'] = $comprobante['nombres'] ?? null;
            //             $comprobante['ruc'] = $comprobante['documento'] ?? null;
            //         } elseif ($idPtipo == 446) {
            //             // 🧾 Factura: usar razón social y ruc normales
            //             $comprobante['razonSocial'] = $comprobante['razonSocial'] ?? null;
            //             $comprobante['ruc'] = $comprobante['ruc'] ?? null;
            //         } else {
            //             // Otros tipos (por seguridad)
            //             $comprobante['razonSocial'] = $comprobante['razonSocial'] ?? null;
            //             $comprobante['ruc'] = $comprobante['ruc'] ?? null;
            //         }

            //         // Crear comprobante en base de datos usando tu método del modelo
            //         $newComprobante = $comprobanteModel->crearComprobante($comprobante, $usuario);

            //         // Asociar comprobante con el pedido
            //         $pedidoModel->asociarComprobante($pedido->idpedido, $newComprobante->idcomprobante);
            //     }
            // } else {
            //     // Asociar comprobante existente
            //     $pedidoModel->asociarComprobante($pedido->idpedido, explode(',', $misComprobantes));
            // }
            $comprobanteModel = new ComprobanteModel();
            log_message('info', '📄 Datos comprobante recibidos: ' . json_encode($comprobante));

            $idPtipo = $comprobante['ptipo']['idParametro'] ?? null;

            // --- Lógica según tipo de comprobante ---
            if ($idPtipo == 445) {
                // 🧾 Boleta: guardar "nombres" y "documento" en "razonsocial" y "ruc"
                $razonSocial = $comprobante['nombres'] ?? null;
                $ruc = $comprobante['documento'] ?? null;
            } elseif ($idPtipo == 446) {
                // 🧾 Factura: usar los campos normales
                $razonSocial = $comprobante['razonSocial'] ?? null;
                $ruc = $comprobante['ruc'] ?? null;
            } else {
                // Otros tipos (por seguridad)
                $razonSocial = $comprobante['razonSocial'] ?? null;
                $ruc = $comprobante['ruc'] ?? null;
            }

            $comprobanteData = [
                'idusuario'   => $pedido->idusuario,
                'idestado'    => 363,
                'idptipo'     => $idPtipo,
                'razonsocial' => $razonSocial,
                'ruc'         => $ruc,
                'direccion'   => $comprobante['direccion'] ?? null,
                'idubigeo'    => $comprobante['ubigeo']['idUbigeo'] ?? null,
                'fecha'       => date('Y-m-d H:i:s'),
            ];

            if (!empty($comprobante['idcomprobante'])) {
                // Actualizar comprobante existente
                $comprobanteModel->update($comprobante['idcomprobante'], $comprobanteData);
                $idComprobante = $comprobante['idcomprobante'];
                log_message('info', '✅ Comprobante actualizado: ' . $idComprobante);
            } else {
                // Insertar nuevo comprobante
                $idComprobante = $comprobanteModel->insert($comprobanteData);
                log_message('info', '🆕 Comprobante insertado con ID: ' . $idComprobante);
            }

            // 🔗 Asociar comprobante con el pedido
            $db = \Config\Database::connect();
            $db->table('pedido_comprobante')->replace([
                'idpedido'     => $pedido->idpedido,
                'idcomprobante' => $idComprobante,
            ]);
            log_message('info', '🔗 Relación pedido_comprobante insertada o actualizada correctamente.');




            // ---------- CUPON ----------
            if (!empty($cupon)) {
                $cuponModel = new CuponModel();
                $cuponData = $cuponModel->cuponByCodigo($cupon, 0, 0);
                if ($cuponData) {
                    $pedidoModel->asociarCupon($pedido->idpedido, $cuponData->idcupon);
                }
            }

            // ---------- ENTREGA 3 (AGENCIA) ----------
            // if (!empty($entrega) && isset($entrega['idEntrega']) && $entrega['idEntrega'] == 3) {
            //     $agenciaData = json_decode($request->getPost('agencia'), true);
            //     if ($agenciaData) {
            //         $newAgencia = $this->crearAgencia($agenciaData);
            //         $pedidoModel->asociarAgencia($pedido->idpedido, $newAgencia->idagencia);
            //     }
            // }

            // if (!empty($entrega) && isset($entrega['idEntrega']) && $entrega['idEntrega'] == 3) {
            //     $agenciaData = json_decode($request->getPost('agencia'), true);
            //     if ($agenciaData) {
            //         $newAgencia = $this->crearAgencia($agenciaData);
            //         $pedidoModel->asociarAgencia($pedido->idpedido, $newAgencia->idagencia);
            //         log_message('info', '✅ Agencia creada y asociada al pedido: ' . $pedido->idpedido);
            //     }
            // }

            // ---------- ENTREGA 3 (AGENCIA) ----------
            if (!empty($entrega) && isset($entrega['idEntrega']) && $entrega['idEntrega'] == 3) {
                $agenciaData = json_decode($request->getPost('agencia'), true);
                if ($agenciaData) {
                    $newAgencia = $this->crearAgencia($agenciaData);
                    // $pedidoModel->asociarAgencia($pedido->idpedido, $newAgencia['idagencia']);
                    $pedidoModel->asociarAgencia($pedido->idpedido, $newAgencia->idagencia);

                    log_message('info', '✅ Agencia creada y asociada al pedido: ' . $pedido->idpedido);
                }
            }

            // ---------- ENVIAR CORREO DE CONFIRMACIÓN ----------
            try {
                $util = new \App\Helpers\Util();
                $util->mailPedido($pedido->idpedido, 5); // ID del mensaje para el correo
                log_message('error', 'Correo de pedido enviado exitosamente para pedido: ' . $pedido->idpedido);
            } catch (\Exception $e) {
                log_message('error', 'Error enviando correo de pedido: ' . $e->getMessage());
            }

            return $this->response->setJSON([
                'status' => 'exito',
                'pedido' => $pedido,
                'mensaje' => 'Pedido registrado con éxito'
            ]);
        } catch (\Throwable $th) {
            return $this->response->setJSON([
                'status' => 'error',
                'mensaje' => $th->getMessage()
            ]);
        }
    }

    // private function crearAgencia($agencia)
    // {
    //     $agenciaModel = new \App\Models\AgenciaModel();

    //     $newAgencia = [
    //         'idusuario' => $agencia['usuario']['idUsuario'],
    //         'idestado' => 367,
    //         'idubigeo' => $agencia['ubigeo']['idUbigeo'],
    //         'agencia' => $agencia['agencia'],
    //         'direccion' => $agencia['direccion'],
    //         'referencia' => null,
    //         'nombres' => $agencia['nombres'],
    //         'apellidos' => $agencia['apellidos'],
    //         'dni' => $agencia['dni'],
    //         'telefono' => $agencia['telefono'],
    //         'latitud' => null,
    //         'longitud' => null
    //     ];

    //     $idAgencia = $agenciaModel->insert($newAgencia);
    //     return $agenciaModel->find($idAgencia); // Devuelve Entity
    // }

    private function crearAgencia($agencia)
    {
        $agenciaModel = new \App\Models\AgenciaModel();

        // Registrar para depuración
        log_message('info', '📦 Datos recibidos para crear agencia: ' . json_encode($agencia));

        $newAgencia = [
            'idusuario'     => $agencia['usuario']['idUsuario'], // Ahora sí lo recibes correctamente
            'idestado'      => 367, // Estado por defecto
            'idubigeo'      => $agencia['ubigeo']['idUbigeo'] ?? null,
            'agencia'       => $agencia['agencia'] ?? null,
            'direccion'     => $agencia['direccion'] ?? null,
            'referencia'    => $agencia['referencia'] ?? null,
            'nombres'       => $agencia['nombres'] ?? null,
            'apellidos'     => $agencia['apellidos'] ?? null,
            'dni'           => $agencia['dni'] ?? null,
            'telefono'      => $agencia['telefono'] ?? null,
            'latitud'       => $agencia['latitud'] ?? null,
            'longitud'      => $agencia['longitud'] ?? null,
            'fecha_agencia' => date('Y-m-d H:i:s'),
        ];

        $idAgencia = $agenciaModel->insert($newAgencia);

        if (!$idAgencia) {
            log_message('error', '❌ Error al insertar agencia: ' . json_encode($agenciaModel->errors()));
            throw new \Exception('Error al registrar la agencia');
        }

        log_message('info', '✅ Agencia registrada con ID: ' . $idAgencia);

        return $agenciaModel->find($idAgencia);
    }

    public function checkPedidoIzipay()
    {
        log_message('error', '=== CHECKPEDIDO INICIADO ===');
        try {
            $request = service('request');
            date_default_timezone_set('America/Lima');

            // 🔹 Detectar si la petición viene en JSON o como form-data
            $input = $request->getJSON(true);

            if ($input) {
                // --- Cuando viene JSON ---
                $idProductos = is_array($input['idProductos'])
                    ? $input['idProductos']
                    : explode(',', $input['idProductos'] ?? '');
                $cantidades = is_array($input['cantidades'])
                    ? $input['cantidades']
                    : explode(',', $input['cantidades'] ?? '');
                $descuentoProductos = is_array($input['descuentoProductos'])
                    ? $input['descuentoProductos']
                    : explode(',', $input['descuentoProductos'] ?? []);

                $usuario = $input['usuario'] ?? [];
                $formapago = $input['formapago'] ?? [];
                $entrega = $input['entrega'] ?? [];
                $comprobante = $input['comprobante'] ?? [];
                $cupon = $input['cupon'] ?? '';
                $referencia = $input['referencia'] ?? $input['codigo'] ?? 'PED-' . time();
                $subtotal = floatval($input['subtotal'] ?? 0);
                $total = floatval($input['total'] ?? 0);
                $costoEnvio = floatval($input['costoEnvio'] ?? 0);
                $comision = floatval($input['comision'] ?? 0);
                $fechaEntrega = $input['fechaEntrega'] ?? date('Y-m-d');
                $observacion = $input['observacion'] ?? '';
                $misComprobantes = $input['misComprobantes'] ?? '';
            } else {
                // --- Cuando viene como form-data ---
                $idProductosRaw = $request->getPost('idProductos') ?? '';
                $cantidadesRaw = $request->getPost('cantidades') ?? '';
                $descuentoProductosRaw = $request->getPost('descuentoProductos') ?? '';

                $idProductos = array_filter(array_map('trim', explode(',', $idProductosRaw)));
                $cantidades = array_filter(array_map('trim', explode(',', $cantidadesRaw)));
                $descuentoProductos = array_filter(array_map('trim', explode(',', $descuentoProductosRaw)));

                $usuario = json_decode($request->getPost('usuario'), true) ?? [];
                $formapago = json_decode($request->getPost('formapago'), true) ?? [];
                $entrega = json_decode($request->getPost('entrega'), true) ?? [];
                $comprobante = json_decode($request->getPost('comprobante'), true) ?? [];
                $cupon = $request->getPost('cupon') ?? '';
                $referencia = $request->getPost('referencia') ?? 'PED-' . time();
                $subtotal = floatval($request->getPost('subtotal') ?? 0);
                $total = floatval($request->getPost('total') ?? 0);
                $costoEnvio = floatval($request->getPost('costoEnvio') ?? 0);
                $comision = floatval($request->getPost('comision') ?? 0);
                $fechaEntrega = $request->getPost('fechaEntrega') ?? date('Y-m-d');
                $observacion = $request->getPost('observacion') ?? '';
                $misComprobantes = $request->getPost('misComprobantes') ?? '';
            }

            // 🔹 Validación básica
            if (empty($idProductos) || empty($cantidades) || count($idProductos) !== count($cantidades)) {
                return $this->response->setJSON([
                    'status' => 'error',
                    'errors' => ['No existen productos o cantidades válidas']
                ]);
            }

            // 🔹 Validar usuario, forma de pago, entrega
            if (empty($usuario['idUsuario'] ?? null)) {
                return $this->response->setJSON(['status' => 'error', 'mensaje' => 'Datos de usuario inválidos']);
            }
            if (empty($formapago['idFormaPago'] ?? null)) {
                return $this->response->setJSON(['status' => 'error', 'mensaje' => 'Datos de forma de pago inválidos']);
            }
            if (empty($entrega['idEntrega'] ?? null)) {
                return $this->response->setJSON(['status' => 'error', 'mensaje' => 'Datos de entrega inválidos']);
            }

            log_message('error', '=== INICIO PROCESAMIENTO PEDIDO ===');
            log_message('error', 'Usuario: ' . json_encode($usuario));
            log_message('error', 'FormaPago: ' . json_encode($formapago));
            log_message('error', 'Entrega: ' . json_encode($entrega));
            log_message('error', 'Referencia: ' . $referencia);
            log_message('error', 'Subtotal: ' . $subtotal);
            log_message('error', 'Total: ' . $total);

            // 🔹 GUARDAR PEDIDO
            $pedidoModel = new PedidoModel();
            $pedido = $pedidoModel->guardarOrActualizarPedido(
                $referencia,
                $usuario,
                $entrega,
                $costoEnvio,
                $comision,
                $subtotal,
                0,
                $total,
                $fechaEntrega,
                $observacion,
                $formapago
            );

            // 🔹 GUARDAR CONSTANCIA
            if ($file = $request->getFile('constancia')) {
                if ($file->isValid() && !$file->hasMoved()) {
                    $nombreArchivo = $pedido->idpedido . '-' . url_title($referencia, '-', true) . '.' . $file->getClientExtension();
                    $rutaDestino = FCPATH . 'archivos/pedido/';
                    if (!is_dir($rutaDestino)) mkdir($rutaDestino, 0777, true);
                    $file->move($rutaDestino, $nombreArchivo);
                    $pedidoModel->update($pedido->idpedido, ['urlconstancia' => $nombreArchivo]);
                }
            }

            // 🔹 GUARDAR DETALLES DE PRODUCTOS
            $productoModel = new ProductoModel();
            $pedidoDetalleModel = new PedidoDetalleModel();
            foreach ($idProductos as $key => $idProducto) {
                $producto = $productoModel->find($idProducto);
                if (!$producto) continue;

                $cantidad = intval($cantidades[$key] ?? 1);
                $descuentoProd = isset($descuentoProductos[$key]) ? floatval($descuentoProductos[$key]) : 0;

                $pedidoDetalleModel->insert([
                    'idpedido' => $pedido->idpedido,
                    'idproducto' => $idProducto,
                    'cantidad' => $cantidad,
                    'peso' => $producto->peso ?? 0,
                    'precio' => $producto->precioventa ?? 0,
                    'descuento' => $descuentoProd,
                    'total' => ($producto->precioventa ?? 0) * $cantidad - $descuentoProd
                ]);

                // disminuir stock
                $productoModel->update($idProducto, ['stock' => max(0, ($producto->stock ?? 0) - $cantidad)]);
            }

            // 🔹 COMPROBANTE
            $comprobanteModel = new ComprobanteModel();
            log_message('info', '📄 Datos comprobante recibidos: ' . json_encode($comprobante));

            $idPtipo = $comprobante['ptipo']['idParametro'] ?? null;
            // $razonSocial = ($idPtipo == 445)
            //     ? ($comprobante['nombres'] ?? null)
            //     : ($comprobante['razonSocial'] ?? null);
            // $ruc = ($idPtipo == 446)
            //     ? ($comprobante['documento'] ?? null)
            //     : ($comprobante['ruc'] ?? null);
            if ($idPtipo == 445) {
                // 🧾 Boleta: guardar "nombres" y "documento" en "razonSocial" y "ruc"
                $razonSocial = $comprobante['nombres'] ?? null;
                $ruc = $comprobante['documento'] ?? null;
            } elseif ($idPtipo == 446) {
                // 🧾 Factura: usar los campos normales
                $razonSocial = $comprobante['razonSocial'] ?? null;
                $ruc = $comprobante['ruc'] ?? null;
            } else {
                // Otros tipos (por seguridad)
                $razonSocial = $comprobante['razonSocial'] ?? null;
                $ruc = $comprobante['ruc'] ?? null;
            }


            $comprobanteData = [
                'idusuario'   => $pedido->idusuario,
                'idestado'    => 363,
                'idptipo'     => $idPtipo,
                'razonsocial' => $razonSocial,
                'ruc'         => $ruc,
                'direccion'   => $comprobante['direccion'] ?? null,
                'idubigeo'    => $comprobante['ubigeo']['idUbigeo'] ?? null,
                'fecha'       => date('Y-m-d H:i:s'),
            ];

            // 🔹 Insertar o actualizar comprobante correctamente en CodeIgniter
            if (!empty($comprobante['idcomprobante'])) {
                $idComprobante = $comprobante['idcomprobante'];
                $comprobanteModel->update($idComprobante, $comprobanteData);
            } else {
                $idComprobante = $comprobanteModel->insert($comprobanteData);
            }


            // Relacionar pedido-comprobante
            $db = \Config\Database::connect();
            $db->table('pedido_comprobante')->replace([
                'idpedido'      => $pedido->idpedido,
                'idcomprobante' => $idComprobante,
            ]);

            // 🔹 CUPON
            if (!empty($cupon)) {
                $cuponModel = new CuponModel();
                if ($cuponData = $cuponModel->cuponByCodigo($cupon, 0, 0)) {
                    $pedidoModel->asociarCupon($pedido->idpedido, $cuponData->idcupon);
                }
            }

            // 🔹 ENTREGA: Agencia
            if (($entrega['idEntrega'] ?? null) == 3) {
                $agenciaData = $input ? ($input['agencia'] ?? []) : json_decode($request->getPost('agencia'), true);
                if ($agenciaData) {
                    $newAgencia = $this->crearAgencia($agenciaData);
                    $pedidoModel->asociarAgencia($pedido->idpedido, $newAgencia->idagencia);
                    log_message('info', '✅ Agencia asociada al pedido: ' . $pedido->idpedido);
                }
            }

            // ---------- ENVIAR CORREO DE CONFIRMACIÓN ----------
            // try {
            //     $util = new \App\Helpers\Util();
            //     $util->mailPedido($pedido->idpedido, 5); // ID del mensaje para el correo
            //     log_message('error', 'Correo de pedido enviado exitosamente para pedido: ' . $pedido->idpedido);
            // } catch (\Exception $e) {
            //     log_message('error', 'Error enviando correo de pedido: ' . $e->getMessage());
            // }
            // 🔹 RESPUESTA FINAL
            return $this->response->setJSON([
                'status' => 'exito',
                'pedido' => $pedido,
                'mensaje' => 'Pedido registrado con éxito'
            ]);
        } catch (\Throwable $th) {
            return $this->response->setJSON([
                'status' => 'error',
                'mensaje' => $th->getMessage()
            ]);
        }
    }


    public function ipnIzipay()
    {
        // Obtener la respuesta enviada por Izipay
        $krAnswer = $this->request->getPost('kr-answer');
        $respuesta = json_decode($krAnswer);

        log_message('info', '📩 Notificación Izipay recibida: ' . json_encode($respuesta));

        if (!isset($respuesta->orderStatus) || !isset($respuesta->orderDetails->orderId)) {
            log_message('error', '❌ Datos inválidos en la notificación Izipay.');
            return $this->response->setStatusCode(400)->setJSON(['error' => 'Datos inválidos']);
        }

        $pedidoModel = new PedidoModel();

        // Buscar el pedido por referencia/orderId
        $pedido = $pedidoModel->where('referencia', $respuesta->orderDetails->orderId)->first();

        if (!$pedido) {
            log_message('error', '❌ Pedido no encontrado para referencia: ' . $respuesta->orderDetails->orderId);
            return $this->response->setStatusCode(404)->setJSON(['error' => 'Pedido no encontrado']);
        }

        // Mapear estados Izipay a tus estados de pedido
        $PagoMap = [
            'PAID'      => 453, // Aceptado
            'CANCELLED' => 452, // Anulado
            'FAILED'    => 452, // Anulado
        ];

        $idpago = $PagoMap[$respuesta->orderStatus] ?? null;

        if ($idpago) {
            // Actualizar estado del pedido
            $pedidoModel->update($pedido->idpedido, [
                'idestado'          => 403,
                'idppago'           => $idpago, // Ajusta si es necesario
                'fechaconfirmacion' => date('Y-m-d H:i:s')
            ]);


            // ---------- ENVIAR CORREO DE CONFIRMACIÓN ----------
            if ($idpago == 453) { // Solo enviar correo si está aceptado
                try {
                    $util = new \App\Helpers\Util();
                    $util->mailPedido($pedido->idpedido, 5); // ID del mensaje para el correo
                    log_message('info', '📧 Correo de pedido enviado exitosamente para PedidoID: ' . $pedido->idpedido);
                } catch (\Exception $e) {
                    log_message('error', '❌ Error enviando correo de pedido: ' . $e->getMessage());
                }
            }
        } else {
            log_message('warning', '⚠️ Estado Izipay no manejado: ' . $respuesta->orderStatus);
        }

        // Responder 200 OK para que Izipay sepa que tu servidor recibió la notificación
        return $this->response->setStatusCode(200)->setJSON(['success' => true]);
    }
}
