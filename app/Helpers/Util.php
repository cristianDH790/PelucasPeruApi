<?php

namespace App\Helpers;

use App\Models\ConfiguracionModel;
use App\Models\MensajeModel;
use App\Models\PedidoDetalleModel;
use App\Models\PedidoModel;

class Util
{

    public static function urls_amigables($url)
    {

        // Tranformamos todo a minusculas

        $url = strtolower($url);
        //Rememplazamos caracteres especiales latinos
        $find = array('á', 'é', 'í', 'ó', 'ú', 'ñ');
        $repl = array('a', 'e', 'i', 'o', 'u', 'n');
        $url = str_replace($find, $repl, $url);

        $find = array('Á', 'É', 'Í', 'Ó', 'Ú', 'Ñ');
        $repl = array('A', 'E', 'I', 'O', 'U', 'N');
        $url = str_replace($find, $repl, $url);

        // Añaadimos los guiones
        $find = array(' ', '&', '\r\n', '\n', '+');
        $url = str_replace($find, '-', $url);
        // Eliminamos y Reemplazamos demás caracteres especiales
        $find = array('/[^a-z0-9\-<>]/', '/[\-]+/', '/<[^>]*>/');
        $repl = array('', '-', '');
        $url = preg_replace($find, $repl, $url);
        return $url;
    }

    public static function generatePassword($length)
    {
        $key = "";
        $pattern = "1234567890abcdefghijklmnopqrstuvwxyz";
        $max = strlen($pattern) - 1;
        for ($i = 0; $i < $length; $i++) {
            $key .= substr($pattern, mt_rand(0, $max), 1);
        }
        return $key;
    }

    public function compararExtension($extension)
    {
        $extImage = ['jpg', 'jpeg', 'png', 'gif', 'svg', 'tiff', 'bmp', 'ai', 'cmp', 'avif', 'heif', 'webp', 'jpg'];
        $extPowerPoint = ['pptx', 'ppt', 'ppsx', 'odp', 'pps'];
        $extPdf = ['pdf'];
        $extExcel = ['xlsx', 'xls', 'csv', 'xlsm', 'xlsb', 'pps', 'xltx', 'xltm', 'xlt'];
        $extWord = ['doc', 'docm', 'docx', 'dot', 'dotm', 'dotx', 'xltm', 'html'];
        $extAudio = ['mp3', 'wav', 'ogg', 'webm', 'aac'];
        $extVideo = ['mp4', 'webm', 'mov', 'wmv', 'avi', 'flv', 'mkv'];
        $extZip = ['zip', 'gzip', 'bzip2', 'tar', 'rar', '7z'];

        if (in_array($extension, $extImage)) {
            return "imagen";
        } else if (in_array($extension, $extPowerPoint)) {
            return "powerPoint";
        } else if (in_array($extension, $extPdf)) {
            return "pdf";
        } else if (in_array($extension, $extExcel)) {
            return "excel";
        } else if (in_array($extension, $extWord)) {
            return "word";
        } else if (in_array($extension, $extAudio)) {
            return "audio";
        } else if (in_array($extension, $extVideo)) {
            return "video";
        } else if (in_array($extension, $extZip)) {
            return "compress";
        } else {
            return "archivo";
        }
    }

    public static function reemplazo($valor1, $valor2, $cadena)
    {
        return str_replace($valor1, $valor2, $cadena);
    }


    public function mailPedido($idpedido, $idmensaje)
    {
        $mensajeModel = new MensajeModel();
        $configuracionModel = new ConfiguracionModel();
        $pedidoModel = new PedidoModel();
        $pedidoDetalleModel = new PedidoDetalleModel();

        $mensaje = $mensajeModel->find($idmensaje);

        if ($mensaje) {
            $correos = $configuracionModel->obtenerPorId(15);
            $destinoCorreo = explode(',', $correos->valor);

            $pedido = $pedidoModel->getPedidoConUsuario($idpedido);
            // var_dump($pedido);
            // die();

            // Agregar el correo del usuario
            if ($pedido && $pedido->usuario && !empty($pedido->usuario->correo)) {
                $destinoCorreo[] = $pedido->usuario->correo;
            }


            /* #1: Cliente */
            $cliente = '<table style="width:640px;background:#fff;padding:0px 20px;margin-top:0px;margin-bottom:0px" align="center">
                            <tbody>
                                <tr>
                                    <td colspan="2" style="background: #fff;padding:15px 10px 5px;font-family: sans-serif;font-weight: 600;">
                                        Nombre: ' . $pedido->usuario->nombres . ' ' . $pedido->usuario->papellido . ' ' . $pedido->usuario->sapellido . '
                                    </td>
                                    <td colspan="2" style="background: #fff;padding:15px 10px 5px;font-family: sans-serif;">
                                        Teléfono: ' . $pedido->usuario->telefono . '
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="2" style="background: #fff;padding: 5px 10px 15px;font-family: sans-serif;">
                                        DNI:' . $pedido->usuario->documento . '
                                    </td>
                                    <td colspan="2" style="background: #fff;padding: 5px 10px 15px;font-family: sans-serif;">
                                        Correo: ' . $pedido->usuario->correo . '
                                    </td>
                                </tr>
                            </tbody>
                        </table>';

            /*#2: Pedido */

            //Colores según el estado
            $arrayEstados = array(
                329 => '#36bea6',
                330 => '#ffbc34',
                331 => '#008839',
                332 => '#f62d51',
                333 => '#f62d51',
                403 => '#f62d51'
            );

            //Set estado
            $estado = '<strong style="background:' . $arrayEstados[$pedido->idestado] . ';padding:5px 15px;color:#fff;border-radius:5px;">' . $pedido->estado->nombre . '</strong>';

            //Colores según el pago
            $arraypPago = array(
                452 => '#36bea6',
                453 => '#008839',
                454 => '#ffbc34',
            );

            //Set ppago
            $ppago = '<strong style="background:' . $arraypPago[$pedido->idppago] . ';padding:5px 15px;color:#fff;border-radius:5px;">' . $pedido->ppago->nombre . '</strong>';
        }
        // Set cupones
        $cupon = "";
        if ($pedido && !empty($pedido->cupones) && is_array($pedido->cupones) && count($pedido->cupones) > 0) {
            $primerCupon = $pedido->cupones[0];
            $codigo = esc($primerCupon->codigo);       // Escapar salida para seguridad
            $descuento = esc($primerCupon->descuento);

            $cupon = "
        <tr>
            <td colspan='2' style='background: #fff; padding: 5px 10px 15px; font-family: sans-serif;'>
                Cupón: <strong>{$codigo}</strong>
            </td>
            <td colspan='2' style='background: #fff; padding: 5px 10px 15px; font-family: sans-serif;'>
                Descuento: <strong>{$descuento}%</strong>
            </td>
        </tr>";
        }

        // Set observación
        $observacion = "";
        if ($pedido && !empty($pedido->observacion)) {
            $observacionTexto = esc($pedido->observacion);

            $observacion = "
        <tr>
            <td colspan='2' style='background: #fff; padding: 5px 10px 15px; font-family: sans-serif;'>
                Observación:
            </td>
            <td colspan='2' style='background: #fff; padding: 5px 10px 15px; font-family: sans-serif;'>
                <strong>{$observacionTexto}</strong>
            </td>
        </tr>";
        }
        //Set pedidos
        $pedidos = '
                <table style="width:640px;background:#fff;padding:0px 20px;margin-top:0px;margin-bottom:20px" align="center">
                    <tbody>
                        <tr>
							<td colspan="4" style="background: #fff;padding:15px 10px 5px;font-family: sans-serif;font-weight: 600;">
								Número de pedido: <strong>' . $pedido->referencia . '</strong>
                            </td>
						</tr>
						<tr>
                            <td colspan="2" style="background: #fff;padding:15px 10px 5px;font-family: sans-serif;">
                                Pago: ' . $ppago . '
                            </td>
                            <td colspan="2" style="background: #fff;padding: 5px 10px;font-family: sans-serif;">
									Estado: ' . $estado . '
                            </td>
						</tr>
						<tr>
							<td colspan="2" style="background: #fff;padding: 5px 10px;font-family: sans-serif;">
								Importe total: S/ ' . $pedido->total . '
                            </td>
                            <td colspan="2" style="background: #fff;padding: 5px 10px;font-family: sans-serif;">
								Fecha de pedido: ' . date("d/m/Y H:i:s", strtotime($pedido->fechapedido)) . '
                            </td>
						</tr>
						<tr>
							<td colspan="2" style="background: #fff;padding: 5px 10px 15px;font-family: sans-serif;">
                                Forma de pago: <strong>' . $pedido->formapago->nombre . '</strong>
                            </td>
                            <td colspan="2" style="background: #fff;padding: 5px 10px 15px;font-family: sans-serif;">
                                <strong></strong>
                            </td>
						</tr>
                        ' . $cupon . '
                        ' . $observacion . '
                    </tbody>
                </table>';

        $detalles = $pedidoDetalleModel->pedidoDetalleByPedido($pedido->idpedido);
        $comision = '';
        $descuento = '';
        $distrito = '';

        //Set detalles
        if ($detalles) {
            //Set cabezera
            $productos = '
                <table style="width: 640px;background: #fff;padding: 0px 20px 20px;margin-bottom: 20px;" border="0" width="640" cellspacing="0" cellpadding="0" align="center">
                    <tbody style="padding: 20px 15px;box-sizing: border-box;background: #fff;">
                        <tr>
                            <td style="background: #cd2a1f;padding: 5px 20px;color: #ffffff;font-family: sans-serif;margin: 0 20px;border:1px solid #e5e5e5;border-left: none;border-bottom:none;"> Producto</td>
                            <td style="background: #cd2a1f;padding: 5px 20px;color: #ffffff;font-family: sans-serif;margin: 0 20px;border:1px solid #e5e5e5;border-left: none;border-bottom:none;"> Cant.</td>
                            <td style="background: #cd2a1f;padding: 5px 30px;color: #ffffff;font-family: sans-serif;margin: 0 20px;border:1px solid #e5e5e5;border-left: none;border-bottom:none;"> P.U.</td>
                            <td style="background: #cd2a1f;padding: 5px 30px;color: #ffffff;font-family: sans-serif;margin: 0 20px;border:1px solid #e5e5e5;border-left: none;border-bottom:none;"> Desc.</td>
                            <td style="background: #cd2a1f;padding: 5px 30px;color: #ffffff;font-family: sans-serif;margin: 0 20px;border:1px solid #e5e5e5;border-left: none;border-bottom:none;"> Total</td>
                        </tr>';

            //Set comisión
            if ($pedido->comision > 0) {
                $comision = '
                        <tr>
                            <td style="border:1px solid #e5e5e5;padding:5px 10px;font-family:sans-serif;border-bottom:none;background:#fff;text-align: right;border-right:none;">
                                Comisión
                            </td>
                            <td style="border:1px solid #e5e5e5;padding:5px 10px;font-family:sans-serif;border-bottom:none;background:#fff;text-align: right;">
                                S/ ' . $pedido->comision . '
                            </td>
                        </tr>';
            }
            //Set descuento
            if ($pedido->descuento > 0) {
                $descuento = '
                        <tr>
                            <td style="border:1px solid #e5e5e5;padding:5px 10px;font-family:sans-serif;border-bottom:none;background:#fff;text-align: right;border-right:none;">
                                Descuento
                            </td>
                            <td style="border:1px solid #e5e5e5;padding:5px 10px;font-family:sans-serif;border-bottom:none;background:#fff;text-align: right;">
                                S/ ' . $pedido->descuento . '
                            </td>
                        </tr>';
            }

            foreach ($detalles as $key => $value) {
                $productos .= '
                                <tr>
                                    <td style="border: 1px solid #e5e5e5;padding: 10px;border-right: none;font-family:sans-serif;border-bottom:none;background:#fff;">
                                        <span style="font-weight:600"> ' . $value->codigo . '-' . $value->nombre . '</span>
                                    </td>
                                    <td style="border:1px solid #e5e5e5;padding: 10px;font-family:sans-serif;border-bottom:none;background:#fff;text-align: center;border-right:none;">
                                        ' . $value->cantidad . '
                                    </td>
                                    <td style="border:1px solid #e5e5e5;padding: 10px;font-family:sans-serif;border-bottom:none;background:#fff;border-right:none;text-align: right;">
                                        S/ ' . number_format($value->precio, 2, '.', '') . '
                                    </td>
                                    <td style="border:1px solid #e5e5e5;padding: 10px;font-family:sans-serif;border-bottom:none;background:#fff;border-right:none;text-align: right;">
                                        S/ ' . number_format($value->descuento, 2, '.', '') . '
                                    </td>
                                    <td style="border:1px solid #e5e5e5;padding: 10px;font-family:sans-serif;border-bottom:none;background:#fff;text-align: right;">
                                        S/ ' . $value->total . '
                                    </td>
                                </tr>';
            }

            //Set valores totales
            $productos .=   '
                                <tr>
                                    <td colspan="3" rowspan="5" style="border: 1px solid #e5e5e5;padding: 10px;border-right: none;font-family:sans-serif;background:#f3f3f3;"></td>
                                    <td style="border:1px solid #e5e5e5;padding:5px 10px;font-family:sans-serif;border-bottom:none;background:#fff;text-align: right;border-right:none;"> Subtotal</td>
                                    <td style="border:1px solid #e5e5e5;padding:5px 10px;font-family:sans-serif;border-bottom:none;background:#fff;text-align: right;"> S/ ' . $pedido->subtotal . '</td>
                                </tr>
                                ' . $comision . '
                                ' . $descuento . '
                                <tr>
                                    <td style="border:1px solid #e5e5e5;padding:5px 10px;font-family:sans-serif;border-bottom:none;background:#fff;text-align: right;border-right:none;"> Envío</td>
                                    <td style="border:1px solid #e5e5e5;padding:5px 10px;font-family:sans-serif;border-bottom:none;background:#fff;text-align: right;"> S/ ' . $pedido->costoenvio . '</td>
                                </tr>
                                <tr>
                                    <td style="border:1px solid #e5e5e5;padding:5px 10px;font-family:sans-serif;background:#fff;text-align: right;border-right:none;"> Total</td>
                                    <td style="border:1px solid #e5e5e5;padding:5px 10px;font-family:sans-serif;background:#fff;text-align: right;"> S/ ' . $pedido->total . '</td>
                                </tr>
                            </tbody>
                        </table>';
        }

        $entrega = '<table style="width:640px;background:#fff;padding: 0px 20px;margin-top: 10px;margin-bottom: 30px;" align="center"><tbody>
                            <tr>
								<td colspan="4" style="background: #cd2a1f;padding: 5px 20px;color: #ffffff;font-family: sans-serif;margin: 0 20px;width:640px;">
									' . $pedido->entrega->nombre . '
                                </td>
							</tr>';

        // =========== Fin Detalle =========== \\

        if ($pedido->identrega == 3) {
            if ($pedido->agencia[0]->ubigeo->idubigeo != null)
                $distrito = $pedido->agencia[0]->ubigeo->rubigeo->rubigeo->nombre . " - " . $pedido->agencia[0]->ubigeo->rubigeo->nombre . " - " . $pedido->agencia[0]->ubigeo->nombre;

            /* Agencia */
            $entrega .= '
                                <tr>
                                    <td colspan="1" style="border: 1px solid #e5e5e5;padding: 10px;width: 25%;border-right: none;font-family:sans-serif;border-bottom:none;background:#fff;">
                                        Agencia:
                                    </td>
                                    <td colspan="3" style="border:1px solid #e5e5e5;padding: 10px;font-family:sans-serif;border-bottom:none;background:#fff;">
                                        ' . $pedido->agencia[0]->agencia . '
                                    </td>
                                </tr>
                                 <tr>
                                    <td colspan="1" style="border: 1px solid #e5e5e5;padding: 10px;width: 25%;border-right: none;font-family:sans-serif;border-bottom:none;background:#fff;">
                                        Agencia - dirección:
                                    </td>
                                    <td colspan="3" style="border:1px solid #e5e5e5;padding: 10px;font-family:sans-serif;border-bottom:none;background:#fff;">
                                        ' . $pedido->agencia[0]->direccion . '
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="1" style="border: 1px solid #e5e5e5;padding: 10px;width: 25%;border-right: none;font-family:sans-serif;border-bottom:none;background:#fff;">
                                        Nombres:
                                    </td>
                                    <td colspan="3" style="border:1px solid #e5e5e5;padding: 10px;font-family:sans-serif;border-bottom:none;background:#fff;">
                                        ' . $pedido->agencia[0]->nombres . ' ' . $pedido->agencia[0]->apellidos . '
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="1" style="border: 1px solid #e5e5e5;padding: 10px;width: 25%;border-right: none;font-family:sans-serif;border-bottom:none;background:#fff;">
                                        DNI:
                                    </td>
                                    <td colspan="3" style="border:1px solid #e5e5e5;padding: 10px;font-family:sans-serif;border-bottom:none;background:#fff;">
                                        ' . $pedido->agencia[0]->dni . '
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="1" style="border: 1px solid #e5e5e5;padding: 10px;width: 25%;border-right: none;font-family:sans-serif;border-bottom:none;background:#fff;">
                                        Teléfono:
                                    </td>
                                    <td colspan="3" style="border:1px solid #e5e5e5;padding: 10px;font-family:sans-serif;border-bottom:none;background:#fff;">
                                        ' . $pedido->agencia[0]->telefono . '
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="1" style="border: 1px solid #e5e5e5;padding: 10px;width: 25%;border-right: none;font-family:sans-serif;background:#fff;">
                                        Ubigeo:
                                    </td>
                                    <td colspan="3" style="border:1px solid #e5e5e5;padding: 10px;font-family:sans-serif;background:#fff;">
                                        ' . $distrito . '
                                    </td>
                                </tr>';
        }
        $entrega .= '</tbody></table>';
        // =========== Fin Entrega =========== \\

        // $comprobantes = '<table style="width:640px;background:#fff;padding:0px 20px;margin-top:10px;margin-bottom:20px" align="center"><tbody>
        //             <tr>
        //                 <td colspan="4" style="background: #cd2a1f;padding: 5px 20px;color: #ffffff;font-family: sans-serif;margin: 0 20px;width:640px;">
        //                     ' . $pedido->comprobante[0]->ptipo->nombre . '
        //                 </td>
        //             </tr>';

        // if ($pedido->comprobante[0]->ptipo->idparametro == 445) {
        //     /*#5: Comprobante*/

        //     // Validación segura para ubigeo
        //     if (
        //         isset($pedido->comprobante[0]->ubigeo) &&
        //         $pedido->comprobante[0]->ubigeo != null &&
        //         isset($pedido->comprobante[0]->ubigeo->idubigeo) &&
        //         $pedido->comprobante[0]->ubigeo->idubigeo != null
        //     ) {
        //         $distrito =
        //             ($pedido->comprobante[0]->ubigeo->rubigeo->rubigeo->nombre ?? '') . " - " .
        //             ($pedido->comprobante[0]->ubigeo->rubigeo->nombre ?? '') . " - " .
        //             ($pedido->comprobante[0]->ubigeo->nombre ?? '');
        //     } else {
        //         $distrito = 'No definido';
        //     }

        //     $comprobantes .= '
        // <tr>
        //     <td colspan="1" style="border: 1px solid #e5e5e5;padding: 10px;width: 25%;border-right: none;font-family:sans-serif;border-bottom:none;background:#fff;">
        //         Nombres:
        //     </td>
        //     <td colspan="3" style="border:1px solid #e5e5e5;padding: 10px;font-family:sans-serif;border-bottom:none;background:#fff;">
        //         ' . htmlspecialchars($pedido->comprobante[0]->razonsocial) . '
        //     </td>
        // </tr>
        // <tr>
        //     <td colspan="1" style="border: 1px solid #e5e5e5;padding: 10px;width: 25%;border-right: none;font-family:sans-serif;border-bottom:none;background:#fff;">
        //         Documento:
        //     </td>
        //     <td colspan="3" style="border:1px solid #e5e5e5;padding: 10px;font-family:sans-serif;border-bottom:none;background:#fff;">
        //         ' . htmlspecialchars($pedido->comprobante[0]->ruc) . '
        //     </td>
        // </tr>';
        // } elseif ($pedido->comprobante[0]->ptipo->idparametro == 446) {

        //     $comprobantes .= '
        // <tr>
        //     <td colspan="1" style="border: 1px solid #e5e5e5;padding: 10px;width: 25%;border-right: none;font-family:sans-serif;border-bottom:none;background:#fff;">
        //         Razón social:
        //     </td>
        //     <td colspan="3" style="border:1px solid #e5e5e5;padding: 10px;font-family:sans-serif;border-bottom:none;background:#fff;">
        //         ' . htmlspecialchars($pedido->comprobante[0]->razonsocial) . '
        //     </td>
        // </tr>
        // <tr>
        //     <td colspan="1" style="border: 1px solid #e5e5e5;padding: 10px;width: 25%;border-right: none;font-family:sans-serif;border-bottom:none;background:#fff;">
        //         RUC:
        //     </td>
        //     <td colspan="3" style="border:1px solid #e5e5e5;padding: 10px;font-family:sans-serif;border-bottom:none;background:#fff;">
        //         ' . htmlspecialchars($pedido->comprobante[0]->ruc) . '
        //     </td>
        // </tr>
        // <tr>
        //     <td colspan="1" style="border: 1px solid #e5e5e5;padding: 10px;width: 25%;border-right: none;font-family:sans-serif;border-bottom:none;background:#fff;">
        //         Dirección:
        //     </td>
        //     <td colspan="3" style="border:1px solid #e5e5e5; padding: 10px; font-family:sans-serif; border-bottom:none;background:#fff;">
        //         ' . htmlspecialchars($pedido->comprobante[0]->direccion) . '
        //     </td>
        // </tr>';
        // }

        // $comprobantes .= '</tbody></table>';

        $comprobantes = '<table style="width:640px;background:#fff;padding:0px 20px;margin-top:10px;margin-bottom:20px" align="center"><tbody>
                                <tr>
        						    <td colspan="4" style="background: #cd2a1f;padding: 5px 20px;color: #ffffff;font-family: sans-serif;margin: 0 20px;width:640px;">
        							    ' . $pedido->comprobante[0]->ptipo->nombre . '
                                    </td>
        					    </tr>';

        if ($pedido->comprobante[0]->ptipo->idparametro == 445) {
            /*#5: Comprobante*/

            if ($pedido->comprobante[0]->ubigeo != null && $pedido->comprobante[0]->ubigeo->idubigeo != null)
                $distrito = $pedido->comprobante[0]->ubigeo->rubigeo->rubigeo->nombre . " - " . $pedido->comprobante[0]->ubigeo->rubigeo->nombre . " - " . $pedido->comprobante[0]->ubigeo->nombre;

            $comprobantes .= '
                                <tr>
                                    <td colspan="1" style="border: 1px solid #e5e5e5;padding: 10px;width: 25%;border-right: none;font-family:sans-serif;border-bottom:none;background:#fff;">
                                        Nombres:
                                    </td>
                                    <td colspan="3" style="border:1px solid #e5e5e5;padding: 10px;font-family:sans-serif;border-bottom:none;background:#fff;">
                                        ' . $pedido->comprobante[0]->razonsocial . '
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="1" style="border: 1px solid #e5e5e5;padding: 10px;width: 25%;border-right: none;font-family:sans-serif;border-bottom:none;background:#fff;">
                                        Documento:
                                    </td>
                                    <td colspan="3" style="border:1px solid #e5e5e5;padding: 10px;font-family:sans-serif;border-bottom:none;background:#fff;">
                                        ' . $pedido->comprobante[0]->ruc . '
                                    </td>
                                </tr>';
        } elseif ($pedido->comprobante[0]->ptipo->idparametro == 446) {

            $comprobantes .= '
                                <tr>
                                    <td colspan="1" style="border: 1px solid #e5e5e5;padding: 10px;width: 25%;border-right: none;font-family:sans-serif;border-bottom:none;background:#fff;">
                                        Razón social:
                                    </td>
                                    <td colspan="3" style="border:1px solid #e5e5e5;padding: 10px;font-family:sans-serif;border-bottom:none;background:#fff;">
                                        ' . $pedido->comprobante[0]->razonsocial . '
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="1" style="border: 1px solid #e5e5e5;padding: 10px;width: 25%;border-right: none;font-family:sans-serif;border-bottom:none;background:#fff;">
                                        RUC:
                                    </td>
                                    <td colspan="3" style="border:1px solid #e5e5e5;padding: 10px;font-family:sans-serif;border-bottom:none;background:#fff;">
                                        ' . $pedido->comprobante[0]->ruc . '
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="1" style="border: 1px solid #e5e5e5;padding: 10px;width: 25%;border-right: none;font-family:sans-serif;border-bottom:none;background:#fff;">
                                        Dirección:
                                    </td>
                                    <td colspan="3" style="border:1px solid #e5e5e5; padding: 10px; font-family:sans-serif; border-bottom:none;background:#fff;">
                                        ' . $pedido->comprobante[0]->direccion . '
                                    </td>
                                </tr>';
        }

        $comprobantes .= '</tbody></table>';
        // =========== Fin Comprobante =========== \\

        //Set variables de mensaje
        $variables = array(
            '1' => $cliente,
            '2' => $pedidos,
            '3' => $productos,
            '4' => $entrega,
            '5' => $comprobantes,
            '6' => $pedido->subtotal,
            '7' => $comision,
            '8' => 'https://yhassir.com/',
            '9' => $pedido->costoenvio,
            '10' => $pedido->total,
        );

        $resultado = $mensaje->contenido;
        $asunto = $mensaje->asunto;

        //Reemplazar variables
        for ($i = 1; $i <= count($variables); $i++) {
            $resultado = Util::reemplazo("{{" . $i . "}}", $variables[$i], $resultado);
        }

        // $emailreply = $configuracionModel->obtenerPorId(3);
        $email = \Config\Services::email();

        //============= DESARROLLO =============\\
        // $email->clear();
        // $email->setTo('desarrollo1@onlinesolutions.com.pe');
        // $email->setSubject($asunto);
        // $email->setFrom('plucas@pelucas.com', 'Pelucas');
        // $email->setMessage($resultado);

        // if (!$email->send()) {
        //     log_message('error', 'Error enviando correo en desarrollo: ' . $email->printDebugger(['headers']));
        // }

        //============= PRODUCCIÓN =============\\
        // foreach ($destinoCorreo as $item) {
        //     $email->clear();
        //     $email->setTo($item);
        //     $email->setSubject($asunto);
        //     $email->setFrom('plucas@pelucas.com', 'Pelucas');
        //     $email->setReplyTo('plucas@pelucas.com', 'Pelucas - Tienda de Pelucas');
        //     $email->setMessage($resultado);

        //     if (!$email->send()) {
        //         log_message('error', 'Error enviando correo a ' . $item . ': ' . $email->printDebugger(['headers']));
        //     }
        // }
        foreach ($destinoCorreo as $item) {
            log_message('debug', '📤 Iniciando envío de correo a: ' . $item);

            $email->clear();
            $email->setTo($item);
            $email->setSubject($asunto);
            $email->setFrom('plucas@pelucas.com', 'Pelucas');
            $email->setReplyTo('plucas@pelucas.com', 'Pelucas - Tienda de Pelucas');
            $email->setMessage($resultado);
            $email->setNewLine("\r\n");

            log_message('debug', '⏳ Intentando enviar correo a: ' . $item);

            $sent = $email->send();

            log_message('debug', '📩 Resultado del envío a ' . $item . ': ' . ($sent ? 'Éxito' : 'Fallo'));

            if ($sent) {
                log_message('debug', '✅ Correo enviado correctamente a: ' . $item);
            } else {
                $debugInfo = print_r($email->printDebugger(), true);
                log_message('error', '❌ Error enviando correo a ' . $item . ': ' . $debugInfo);
            }
        }

        log_message('debug', '🟢 Finalizado el proceso de envío de correos.');
    }
}
