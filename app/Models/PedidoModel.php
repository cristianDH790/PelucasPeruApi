<?php

namespace App\Models;

use App\Entities\EmpresaEntity;
use App\Entities\Noticia;
use App\Entities\NoticiaEntity;
use App\Entities\PedidoEntity;
use App\Entities\ProductoBaseEntity;
use App\Entities\ProductoEntity;
use CodeIgniter\Model;

class PedidoModel extends Model
{
    protected $table      = 'pedido';
    protected $primaryKey = 'idpedido';

    protected $useAutoIncrement = true;

    protected $returnType     = PedidoEntity::class;
    protected $useSoftDeletes = false;

    protected $allowedFields = [
        'idestado',
        'idusuario',
        'idformapago',
        'identrega',
        'idppago',
        'referencia',
        'peso',
        'costoenvio',
        'comision',
        'subtotal',
        'descuento',
        'total',
        'fechapedido',
        'fechaentrega',
        'observacion',
        'urlconstancia',
        'fechareporte',
        'fechaconfirmacion',

    ];

    protected $useTimestamps = false;
    protected $createdField  = 'fecha';

    protected $validationRules    = [];
    protected $validationMessages = [];
    protected $skipValidation     = false;


    public function asociarComprobante($idPedido, $idComprobante)
    {
        if (is_array($idComprobante)) {
            // Si son varios comprobantes, guardamos el primero (o podrías crear relación aparte)
            $idComprobante = $idComprobante[0];
        }

        // Insertar en la tabla de relación pedido_comprobante
        $builder = $this->db->table('pedido_comprobante');
        return $builder->insert([
            'idpedido' => $idPedido,
            'idcomprobante' => $idComprobante
        ]);
    }

    /**
     * Asocia un cupón a un pedido.
     */
    public function asociarCupon($idPedido, $idCupon)
    {
        // Insertar en la tabla de relación pedido_cupon
        $builder = $this->db->table('pedido_cupon');
        return $builder->insert([
            'idpedido' => $idPedido,
            'idcupon' => $idCupon
        ]);
    }

    /**
     * Asocia una agencia a un pedido.
     */
    // public function asociarAgencia($idPedido, $idAgencia)
    // {
    //     return $this->update($idPedido, ['idagencia' => $idAgencia]);
    // }
    public function guardarOrActualizarPedido($referencia, $usuario, $entrega, $costoenvio, $comision, $subtotal, $descuento, $total, $fechaentrega, $observacion, $formapago)
    {
        $pedido = $this->where('referencia', $referencia)->first(); // Devuelve Entity si $returnType está definido

        $data = [
            'idestado' => 403,
            'idusuario' => $usuario['idUsuario'] ?? $usuario['idusuario'] ?? 0,
            'idformapago' => $formapago['idFormaPago'] ?? $formapago['idformapago'] ?? 0,
            'identrega' => $entrega['idEntrega'] ?? $entrega['identrega'] ?? 0,
            'idppago' => ($formapago['idFormaPago'] ?? $formapago['idformapago'] ?? 0) == 1 ? 452 : 454,
            'referencia' => $referencia,
            'peso' => 0,
            'costoenvio' => $costoenvio,
            'comision' => $comision,
            'subtotal' => $subtotal,
            'descuento' => $descuento ? floatval($descuento) : 0,
            'total' => $total,
            'fechapedido' => date('Y-m-d H:i:s'),
            'fechaentrega' => date('Y-m-d', strtotime($fechaentrega)),
            'observacion' => $observacion,
            'urlconstancia' => null,
            'fechareporte' => date('Y-m-d H:i:s'),
            'fechaconfirmacion' => null,
        ];

        log_message('error', '=== MODELO PEDIDO ===');
        log_message('error', 'Datos del pedido a procesar: ' . json_encode($data));
        log_message('error', 'Referencia buscada: ' . $referencia);
        log_message('error', 'Pedido encontrado: ' . ($pedido ? 'SÍ' : 'NO'));

        if ($pedido) {
            // Acceder como Entity
            log_message('error', 'Actualizando pedido ID: ' . $pedido->idpedido);

            // Verificar si hay cambios antes de actualizar
            $cambios = [];
            foreach ($data as $key => $value) {
                if (isset($pedido->$key) && $pedido->$key != $value) {
                    $cambios[$key] = ['anterior' => $pedido->$key, 'nuevo' => $value];
                }
            }

            log_message('error', 'Cambios detectados: ' . json_encode($cambios));

            if (empty($cambios)) {
                log_message('error', 'No hay cambios, devolviendo pedido existente');
                return $pedido;
            }

            $result = $this->update($pedido->idpedido, $data);
            log_message('error', 'Resultado del update: ' . ($result ? 'ÉXITO' : 'FALLO'));

            if (!$result) {
                log_message('error', 'Error al actualizar pedido: ' . $this->db->error()['message']);
            }

            return $this->find($pedido->idpedido);
        } else {
            log_message('debug', 'Insertando nuevo pedido');
            $idPedido = $this->insert($data);
            log_message('debug', 'ID del nuevo pedido: ' . $idPedido);

            if (!$idPedido) {
                log_message('error', 'Error al insertar pedido: ' . $this->db->error()['message']);
                throw new \Exception('Error al guardar el pedido');
            }

            return $this->find($idPedido);
        }
    }


    public function asociarAgencia($idPedido, $idAgencia)
    {
        // Suponiendo que tienes una tabla pivot: pedido_agencia (idpedido, idagencia)
        $builder = $this->db->table('pedido_agencia');
        $builder->insert([
            'idpedido' => $idPedido,
            'idagencia' => $idAgencia
        ]);
    }

    // public function getPedidoConUsuario($idpedido)
    // {
    //     $pedido = $this->find($idpedido);

    //     if ($pedido) {
    //         $usuarioModel = new UsuarioModel();
    //         $pedido->usuario = $usuarioModel->find($pedido->idusuario);
    //     }

    //     return $pedido;
    // }

    // public function getPedidoConUsuario($idpedido)
    // {
    //     $pedido = $this->find($idpedido);

    //     if (!$pedido) {
    //         log_message('error', '❌ Pedido no encontrado con ID: ' . $idpedido);
    //         return null;
    //     }

    //     log_message('debug', '🟢 Pedido encontrado: ' . json_encode($pedido));

    //     // Modelos relacionados
    //     $usuarioModel = new \App\Models\UsuarioModel();
    //     $estadoModel = new \App\Models\EstadoModel();
    //     $ppagoModel = new \App\Models\ParametroModel();
    //     $formaPagoModel = new \App\Models\FormaPagoModel();
    //     $cuponModel = new \App\Models\CuponModel();
    //     $entregaModel = new \App\Models\EntregaModel();
    //     $destinoModel = new \App\Models\DestinoModel();
    //     $recojoModel = new \App\Models\RecojoModel();
    //     $agenciaModel = new \App\Models\AgenciaModel();

    //     // Relaciones principales
    //     $pedido->usuario = $usuarioModel->find($pedido->idusuario);
    //     $pedido->estado = $estadoModel->find($pedido->idestado);
    //     $pedido->ppago = $ppagoModel->find($pedido->idppago);
    //     $pedido->formapago = $formaPagoModel->find($pedido->idformapago);
    //     $pedido->entrega = $entregaModel->find($pedido->identrega);

    //     // Relaciones opcionales (colecciones)
    //     // $pedido->cupones = $cuponModel->where('idpedido', $idpedido)->findAll();
    //     // $pedido->destino = $destinoModel->where('idpedido', $idpedido)->findAll();
    //     // $pedido->recojo = $recojoModel->where('idpedido', $idpedido)->findAll();
    //     // $pedido->agencia = $agenciaModel->where('idpedido', $idpedido)->findAll();
    //     $pedido->cupones = $cuponModel->where('idpedido', $idpedido)->findAll();
    //     $pedido->destino = $destinoModel->where('idpedido', $idpedido)->findAll();
    //     $pedido->recojo = $recojoModel->where('idpedido', $idpedido)->findAll();
    //     $pedido->agencia = $agenciaModel->where('idpedido', $idpedido)->findAll();


    //     // Log para depuración
    //     log_message('debug', '✅ Pedido con relaciones: ' . json_encode($pedido));

    //     return $pedido;
    // }
    // public function getPedidoConUsuario($idpedido)
    // {
    //     $pedido = $this->find($idpedido);

    //     if (!$pedido) {
    //         log_message('error', '❌ Pedido no encontrado con ID: ' . $idpedido);
    //         return null;
    //     }

    //     log_message('debug', '🟢 Pedido encontrado: ' . json_encode($pedido));

    //     // Modelos principales
    //     $usuarioModel = new \App\Models\UsuarioModel();
    //     $estadoModel = new \App\Models\EstadoModel();
    //     $ppagoModel = new \App\Models\ParametroModel();
    //     $formaPagoModel = new \App\Models\FormaPagoModel();
    //     $entregaModel = new \App\Models\EntregaModel();
    //     $destinoModel = new \App\Models\DestinoModel();
    //     $recojoModel = new \App\Models\RecojoModel();

    //     // Relaciones principales
    //     $pedido->usuario = $usuarioModel->find($pedido->idusuario);
    //     $pedido->estado = $estadoModel->find($pedido->idestado);
    //     $pedido->ppago = $ppagoModel->find($pedido->idppago);
    //     $pedido->formapago = $formaPagoModel->find($pedido->idformapago);
    //     $pedido->entrega = $entregaModel->find($pedido->identrega);

    //     // 🔹 Consultas directas sin modelos adicionales
    //     $db = \Config\Database::connect();

    //     // Cupones asociados al pedido
    //     $pedido->cupones = $db->table('pedido_cupon pc')
    //         ->select('c.*')
    //         ->join('cupon c', 'c.idcupon = pc.idcupon', 'left')
    //         ->where('pc.idpedido', $idpedido)
    //         ->get()
    //         ->getResult();

    //     log_message('debug', '🎟️ Cupones asociados al pedido: ' . json_encode($pedido->cupones));

    //     // Agencia (solo si identrega == 3)
    //     if ($pedido->identrega == 3) {
    //         $pedido->agencia = $db->table('pedido_agencia')
    //             ->where('idpedido', $idpedido)
    //             ->get()
    //             ->getResult();
    //     } else {
    //         $pedido->agencia = [];
    //     }

    //     $comprobantesRaw = $db->table('pedido_comprobante pc')
    //         ->select('pc.*, c.*, p.nombre AS ptipo_nombre, p.idparametro AS ptipo_idparametro')
    //         ->join('comprobante c', 'c.idcomprobante = pc.idcomprobante', 'left')
    //         ->join('parametro p', 'p.idparametro = c.idptipo', 'left')
    //         ->where('pc.idpedido', $idpedido)
    //         ->get()
    //         ->getResult();

    //     $pedido->comprobante = [];

    //     foreach ($comprobantesRaw as $comp) {
    //         $comp->ptipo = (object)[
    //             'nombre' => $comp->ptipo_nombre,
    //             'idparametro' => $comp->ptipo_idparametro
    //         ];
    //         // Elimina las propiedades planas que ya están en ptipo para evitar confusión
    //         unset($comp->ptipo_nombre, $comp->ptipo_idparametro);

    //         $pedido->comprobante[] = $comp;
    //     }



    //     // Log final
    //     log_message('debug', '✅ Pedido completo con relaciones directas: ' . json_encode($pedido));

    //     return $pedido;
    // }
    // public function getPedidoConUsuario($idpedido)
    // {
    //     $pedido = $this->find($idpedido);

    //     if (!$pedido) {
    //         log_message('error', '❌ Pedido no encontrado con ID: ' . $idpedido);
    //         return null;
    //     }

    //     log_message('debug', '🟢 Pedido encontrado: ' . json_encode($pedido));

    //     // Modelos principales
    //     $usuarioModel = new \App\Models\UsuarioModel();
    //     $estadoModel = new \App\Models\EstadoModel();
    //     $ppagoModel = new \App\Models\ParametroModel();
    //     $formaPagoModel = new \App\Models\FormaPagoModel();
    //     $entregaModel = new \App\Models\EntregaModel();
    //     $destinoModel = new \App\Models\DestinoModel();
    //     $recojoModel = new \App\Models\RecojoModel();

    //     // Relaciones principales
    //     $pedido->usuario = $usuarioModel->find($pedido->idusuario);
    //     $pedido->estado = $estadoModel->find($pedido->idestado);
    //     $pedido->ppago = $ppagoModel->find($pedido->idppago);
    //     $pedido->formapago = $formaPagoModel->find($pedido->idformapago);
    //     $pedido->entrega = $entregaModel->find($pedido->identrega);

    //     // 🔹 Consultas directas sin modelos adicionales
    //     $db = \Config\Database::connect();

    //     // Cupones asociados al pedido
    //     $pedido->cupones = $db->table('pedido_cupon pc')
    //         ->select('c.*')
    //         ->join('cupon c', 'c.idcupon = pc.idcupon', 'left')
    //         ->where('pc.idpedido', $idpedido)
    //         ->get()
    //         ->getResult();

    //     log_message('debug', '🎟️ Cupones asociados al pedido: ' . json_encode($pedido->cupones));

    //     // Agencia (solo si identrega == 3)
    //     if ($pedido->identrega == 3) {
    //         $pedido->agencia = $db->table('pedido_agencia')
    //             ->where('idpedido', $idpedido)
    //             ->get()
    //             ->getResult();
    //     } else {
    //         $pedido->agencia = [];
    //     }

    //     // Comprobantes con join para ubigeo y rubigeo anidados
    //     $comprobantesRaw = $db->table('pedido_comprobante pc')
    //         ->select('pc.*, c.*, p.nombre AS ptipo_nombre, p.idparametro AS ptipo_idparametro, 
    //         u.idubigeo AS ubigeo_idubigeo, u.nombre AS ubigeo_nombre, u.idrubigeo AS ubigeo_idrubigeo,
    //         ru1.idrubigeo AS rubigeo1_idrubigeo, ru1.nombre AS rubigeo1_nombre, ru1.idrubigeo_padre AS rubigeo1_padre,
    //         ru2.idrubigeo AS rubigeo2_idrubigeo, ru2.nombre AS rubigeo2_nombre')
    //         ->join('comprobante c', 'c.idcomprobante = pc.idcomprobante', 'left')
    //         ->join('parametro p', 'p.idparametro = c.idptipo', 'left')
    //         ->join('ubigeo u', 'u.idubigeo = pc.idubigeo', 'left')
    //         ->join('rubigeo ru1', 'ru1.idrubigeo = u.idrubigeo', 'left')
    //         ->join('rubigeo ru2', 'ru2.idrubigeo = ru1.idrubigeo_padre', 'left')
    //         ->where('pc.idpedido', $idpedido)
    //         ->get()
    //         ->getResult();

    //     $pedido->comprobante = [];

    //     foreach ($comprobantesRaw as $comp) {
    //         // Construimos ptipo
    //         $comp->ptipo = (object)[
    //             'nombre' => $comp->ptipo_nombre,
    //             'idparametro' => $comp->ptipo_idparametro
    //         ];
    //         unset($comp->ptipo_nombre, $comp->ptipo_idparametro);

    //         // Construimos ubigeo con rubigeo anidado (para usar en tu vista sin errores)
    //         if ($comp->ubigeo_idubigeo !== null) {
    //             $comp->ubigeo = (object)[
    //                 'idubigeo' => $comp->ubigeo_idubigeo,
    //                 'nombre' => $comp->ubigeo_nombre,
    //                 'rubigeo' => (object)[
    //                     'nombre' => $comp->rubigeo1_nombre,
    //                     'rubigeo' => (object)[
    //                         'nombre' => $comp->rubigeo2_nombre
    //                     ]
    //                 ]
    //             ];
    //         } else {
    //             $comp->ubigeo = null;
    //         }

    //         // Limpieza de propiedades planas para evitar confusión
    //         unset(
    //             $comp->ubigeo_idubigeo,
    //             $comp->ubigeo_nombre,
    //             $comp->ubigeo_idrubigeo,
    //             $comp->rubigeo1_idrubigeo,
    //             $comp->rubigeo1_nombre,
    //             $comp->rubigeo1_padre,
    //             $comp->rubigeo2_idrubigeo,
    //             $comp->rubigeo2_nombre
    //         );

    //         $pedido->comprobante[] = $comp;
    //     }

    //     log_message('debug', '✅ Pedido completo con relaciones directas: ' . json_encode($pedido));

    //     return $pedido;
    // }
    // public function getPedidoConUsuario($idpedido)
    // {
    //     $pedido = $this->find($idpedido);

    //     if (!$pedido) {
    //         log_message('error', '❌ Pedido no encontrado con ID: ' . $idpedido);
    //         return null;
    //     }

    //     log_message('debug', '🟢 Pedido encontrado: ' . json_encode($pedido));

    //     // Modelos principales
    //     $usuarioModel = new \App\Models\UsuarioModel();
    //     $estadoModel = new \App\Models\EstadoModel();
    //     $ppagoModel = new \App\Models\ParametroModel();
    //     $formaPagoModel = new \App\Models\FormaPagoModel();
    //     $entregaModel = new \App\Models\EntregaModel();
    //     $destinoModel = new \App\Models\DestinoModel();
    //     $recojoModel = new \App\Models\RecojoModel();

    //     // Relaciones principales
    //     $pedido->usuario = $usuarioModel->find($pedido->idusuario);
    //     $pedido->estado = $estadoModel->find($pedido->idestado);
    //     $pedido->ppago = $ppagoModel->find($pedido->idppago);
    //     $pedido->formapago = $formaPagoModel->find($pedido->idformapago);
    //     $pedido->entrega = $entregaModel->find($pedido->identrega);

    //     // DB Connection
    //     $db = \Config\Database::connect();

    //     // Cupones asociados al pedido
    //     $pedido->cupones = $db->table('pedido_cupon pc')
    //         ->select('c.*')
    //         ->join('cupon c', 'c.idcupon = pc.idcupon', 'left')
    //         ->where('pc.idpedido', $idpedido)
    //         ->get()
    //         ->getResult();

    //     log_message('debug', '🎟️ Cupones asociados al pedido: ' . json_encode($pedido->cupones));

    //     // Agencia (solo si identrega == 3)
    //     if ($pedido->identrega == 3) {
    //         $pedido->agencia = $db->table('pedido_agencia')
    //             ->where('idpedido', $idpedido)
    //             ->get()
    //             ->getResult();
    //     } else {
    //         $pedido->agencia = [];
    //     }

    //     // Comprobantes con join para ubigeo y jerarquía padre (idrubigeo)
    //     $comprobantesRaw = $db->table('pedido_comprobante pc')
    //         ->select(
    //             'pc.*, c.*, p.nombre AS ptipo_nombre, p.idparametro AS ptipo_idparametro, 
    //         u.idubigeo AS ubigeo_idubigeo, u.nombre AS ubigeo_nombre, u.idrubigeo AS ubigeo_idrubigeo, u.nivel AS ubigeo_nivel,
    //         padre.idubigeo AS padre_idubigeo, padre.nombre AS padre_nombre, padre.idrubigeo AS padre_idrubigeo, padre.nivel AS padre_nivel'
    //         )
    //         ->join('comprobante c', 'c.idcomprobante = pc.idcomprobante', 'left')
    //         ->join('parametro p', 'p.idparametro = c.idptipo', 'left')
    //         ->join('ubigeo u', 'u.idubigeo = pc.idubigeo', 'left')
    //         ->join('ubigeo padre', 'padre.idubigeo = u.idrubigeo', 'left')  // unión con padre
    //         ->where('pc.idpedido', $idpedido)
    //         ->get()
    //         ->getResult();

    //     $pedido->comprobante = [];

    //     foreach ($comprobantesRaw as $comp) {
    //         // Construimos ptipo
    //         $comp->ptipo = (object)[
    //             'nombre' => $comp->ptipo_nombre,
    //             'idparametro' => $comp->ptipo_idparametro
    //         ];
    //         unset($comp->ptipo_nombre, $comp->ptipo_idparametro);

    //         // Construimos ubigeo con padre (jerarquía)
    //         if ($comp->ubigeo_idubigeo !== null) {
    //             $comp->ubigeo = (object)[
    //                 'idubigeo' => $comp->ubigeo_idubigeo,
    //                 'nombre' => $comp->ubigeo_nombre,
    //                 'idrubigeo' => $comp->ubigeo_idrubigeo,
    //                 'nivel' => $comp->ubigeo_nivel,
    //                 'padre' => $comp->padre_idubigeo !== null ? (object)[
    //                     'idubigeo' => $comp->padre_idubigeo,
    //                     'nombre' => $comp->padre_nombre,
    //                     'idrubigeo' => $comp->padre_idrubigeo,
    //                     'nivel' => $comp->padre_nivel
    //                 ] : null
    //             ];
    //         } else {
    //             $comp->ubigeo = null;
    //         }

    //         // Limpiar propiedades planas para no confundir
    //         unset(
    //             $comp->ubigeo_idubigeo,
    //             $comp->ubigeo_nombre,
    //             $comp->ubigeo_idrubigeo,
    //             $comp->ubigeo_nivel,
    //             $comp->padre_idubigeo,
    //             $comp->padre_nombre,
    //             $comp->padre_idrubigeo,
    //             $comp->padre_nivel
    //         );

    //         $pedido->comprobante[] = $comp;
    //     }

    //     log_message('debug', '✅ Pedido completo con relaciones directas: ' . json_encode($pedido));

    //     return $pedido;
    // }
    // public function getPedidoConUsuario($idpedido)
    // {
    //     $pedido = $this->find($idpedido);

    //     if (!$pedido) {
    //         log_message('error', '❌ Pedido no encontrado con ID: ' . $idpedido);
    //         return null;
    //     }

    //     log_message('debug', '🟢 Pedido encontrado: ' . json_encode($pedido));

    //     // Modelos principales
    //     $usuarioModel = new \App\Models\UsuarioModel();
    //     $estadoModel = new \App\Models\EstadoModel();
    //     $ppagoModel = new \App\Models\ParametroModel();
    //     $formaPagoModel = new \App\Models\FormaPagoModel();
    //     $entregaModel = new \App\Models\EntregaModel();
    //     $destinoModel = new \App\Models\DestinoModel();
    //     $recojoModel = new \App\Models\RecojoModel();

    //     // Relaciones principales
    //     $pedido->usuario = $usuarioModel->find($pedido->idusuario);
    //     $pedido->estado = $estadoModel->find($pedido->idestado);
    //     $pedido->ppago = $ppagoModel->find($pedido->idppago);
    //     $pedido->formapago = $formaPagoModel->find($pedido->idformapago);
    //     $pedido->entrega = $entregaModel->find($pedido->identrega);

    //     // 🔹 Consultas directas sin modelos adicionales
    //     $db = \Config\Database::connect();

    //     // Cupones asociados al pedido
    //     $pedido->cupones = $db->table('pedido_cupon pc')
    //         ->select('c.*')
    //         ->join('cupon c', 'c.idcupon = pc.idcupon', 'left')
    //         ->where('pc.idpedido', $idpedido)
    //         ->get()
    //         ->getResult();

    //     log_message('debug', '🎟️ Cupones asociados al pedido: ' . json_encode($pedido->cupones));

    //     // Agencia (solo si identrega == 3)
    //     if ($pedido->identrega == 3) {
    //         $pedido->agencia = $db->table('pedido_agencia')
    //             ->where('idpedido', $idpedido)
    //             ->get()
    //             ->getResult();
    //     } else {
    //         $pedido->agencia = [];
    //     }

    //     // Comprobantes con join para ubigeo (jerarquía padre-hijo en la misma tabla ubigeo)
    //     $comprobantesRaw = $db->table('pedido_comprobante pc')
    //         ->select('pc.*, c.*, p.nombre AS ptipo_nombre, p.idparametro AS ptipo_idparametro, 
    //         u.idubigeo AS ubigeo_idubigeo, u.nombre AS ubigeo_nombre, u.idrubigeo AS ubigeo_idrubigeo')
    //         ->join('comprobante c', 'c.idcomprobante = pc.idcomprobante', 'left')
    //         ->join('parametro p', 'p.idparametro = c.idptipo', 'left')
    //         ->join('ubigeo u', 'u.idubigeo = c.idubigeo', 'left')  // IMPORTANTE: usas c.idubigeo, no pc.idubigeo
    //         ->where('pc.idpedido', $idpedido)
    //         ->get()
    //         ->getResult();

    //     $pedido->comprobante = [];

    //     foreach ($comprobantesRaw as $comp) {
    //         // Construimos ptipo
    //         $comp->ptipo = (object)[
    //             'nombre' => $comp->ptipo_nombre,
    //             'idparametro' => $comp->ptipo_idparametro
    //         ];
    //         unset($comp->ptipo_nombre, $comp->ptipo_idparametro);

    //         // Construimos ubigeo con jerarquía padre (idrubigeo apunta a padre en misma tabla)
    //         if ($comp->ubigeo_idubigeo !== null) {
    //             $parentUbigeo = null;
    //             if ($comp->ubigeo_idrubigeo) {
    //                 // Buscar nombre del rubigeo padre (o asignar null si no se tiene)
    //                 $parentUbigeoRow = $db->table('ubigeo')
    //                     ->select('idubigeo, nombre')
    //                     ->where('idubigeo', $comp->ubigeo_idrubigeo)
    //                     ->get()
    //                     ->getRow();

    //                 if ($parentUbigeoRow) {
    //                     $parentUbigeo = (object)[
    //                         'idubigeo' => $parentUbigeoRow->idubigeo,
    //                         'nombre' => $parentUbigeoRow->nombre
    //                     ];
    //                 }
    //             }

    //             $comp->ubigeo = (object)[
    //                 'idubigeo' => $comp->ubigeo_idubigeo,
    //                 'nombre' => $comp->ubigeo_nombre,
    //                 'rubigeo' => $parentUbigeo
    //             ];
    //         } else {
    //             $comp->ubigeo = null;
    //         }

    //         // Limpiar propiedades planas
    //         unset(
    //             $comp->ubigeo_idubigeo,
    //             $comp->ubigeo_nombre,
    //             $comp->ubigeo_idrubigeo
    //         );

    //         $pedido->comprobante[] = $comp;
    //     }

    //     log_message('debug', '✅ Pedido completo con relaciones directas: ' . json_encode($pedido));

    //     return $pedido;
    // }

    // public function getPedidoConUsuario($idpedido)
    // {
    //     $pedido = $this->find($idpedido);

    //     if (!$pedido) {
    //         return null;
    //     }

    //     $db = \Config\Database::connect();

    //     // === MODELOS BASE ===
    //     $usuarioModel = new UsuarioModel();
    //     $formapagoModel = new FormaPagoModel();
    //     $entregaModel = new EntregaModel();
    //     $estadoModel = new EstadoModel();
    //     $ppagoModel = new ParametroModel();

    //     $comprobanteModel = new ComprobanteModel();
    //     $agenciaModel = new AgenciaModel();
    //     $cuponModel = new CuponModel();
    //     $pedidoDetalleModel = new PedidoDetalleModel();

    //     $ubigeoModel = new UbigeoModel();
    //     $parametroModel = new ParametroModel();

    //     // === RELACIONES PRINCIPALES ===
    //     $pedido->usuario = $usuarioModel->find($pedido->idusuario);
    //     $pedido->formapago = $formapagoModel->find($pedido->idformapago);
    //     $pedido->entrega = $entregaModel
    //         ->where('identrega', $pedido->identrega)
    //         ->first();
    //     $pedido->estado = $estadoModel->find($pedido->idestado);
    //     $pedido->ppago = $ppagoModel->find($pedido->idppago);
    //     $pedido->entregaparametro = $parametroModel->find($pedido->identrega);

    //     // === COMPROBANTES ===
    //     $pedidoComprobantes = $db->table('pedido_comprobante')
    //         ->where('idpedido', $pedido->idpedido)
    //         ->get()
    //         ->getResultArray();

    //     $comprobantes = [];
    //     foreach ($pedidoComprobantes as $pc) {
    //         $comprobante = $comprobanteModel->find($pc['idcomprobante']);
    //         if ($comprobante) {
    //             $comprobante->ptipo = $parametroModel->find($comprobante->idptipo);

    //             if ($comprobante->idubigeo) {
    //                 $comprobante->ubigeo = $ubigeoModel->find($comprobante->idubigeo);
    //                 if ($comprobante->ubigeo) {
    //                     $comprobante->ubigeo->rubigeo = $ubigeoModel->find($comprobante->ubigeo->idrubigeo);
    //                     if ($comprobante->ubigeo->rubigeo)
    //                         $comprobante->ubigeo->rubigeo->rubigeo = $ubigeoModel->find($comprobante->ubigeo->rubigeo->idrubigeo);
    //                 }
    //             }

    //             $comprobantes[] = $comprobante;
    //         }
    //     }
    //     $pedido->comprobante = $comprobantes;

    //     // === AGENCIAS ===
    //     $pedidoAgencias = $db->table('pedido_agencia')
    //         ->where('idpedido', $pedido->idpedido)
    //         ->get()
    //         ->getResultArray();

    //     $agencias = [];
    //     foreach ($pedidoAgencias as $pa) {
    //         $agencia = $agenciaModel->find($pa['idagencia']);
    //         if ($agencia) {
    //             if ($agencia->idubigeo) {
    //                 $agencia->ubigeo = $ubigeoModel->find($agencia->idubigeo);
    //                 if ($agencia->ubigeo) {
    //                     $agencia->ubigeo->rubigeo = $ubigeoModel->find($agencia->ubigeo->idrubigeo);
    //                     if ($agencia->ubigeo->rubigeo)
    //                         $agencia->ubigeo->rubigeo->rubigeo = $ubigeoModel->find($agencia->ubigeo->rubigeo->idrubigeo);
    //                 }
    //             }

    //             $agencias[] = $agencia;
    //         }
    //     }
    //     $pedido->agencia = $agencias;

    //     // === CUPONES ===
    //     $pedidoCupones = $db->table('pedido_cupon')
    //         ->where('idpedido', $pedido->idpedido)
    //         ->get()
    //         ->getResultArray();

    //     $cupones = [];
    //     foreach ($pedidoCupones as $pc) {
    //         $cupon = $cuponModel->find($pc['idcupon']);
    //         if ($cupon) $cupones[] = $cupon;
    //     }
    //     $pedido->cupones = $cupones;

    //     // === DETALLES DEL PEDIDO ===
    //     $pedido->pedidoDetalle = $pedidoDetalleModel
    //         ->where('idpedido', $pedido->idpedido)
    //         ->findAll();

    //     return $pedido;
    // }


    // public function getPedidoConUsuario($idpedido)
    // {
    //     log_message('debug', '🔹 getPedidoConUsuario() llamado con ID: ' . $idpedido);

    //     $pedido = $this->find($idpedido);

    //     if (!$pedido) {
    //         log_message('error', '❌ Pedido no encontrado con ID: ' . $idpedido);
    //         return null;
    //     }

    //     $db = \Config\Database::connect();

    //     log_message('debug', '✅ Pedido base: ' . json_encode($pedido));

    //     // === MODELOS BASE ===
    //     $usuarioModel = new UsuarioModel();
    //     $formapagoModel = new FormaPagoModel();
    //     $entregaModel = new EntregaModel();
    //     $estadoModel = new EstadoModel();
    //     $ppagoModel = new ParametroModel();

    //     $comprobanteModel = new ComprobanteModel();
    //     $agenciaModel = new AgenciaModel();
    //     $cuponModel = new CuponModel();
    //     $pedidoDetalleModel = new PedidoDetalleModel();

    //     $ubigeoModel = new UbigeoModel();
    //     $parametroModel = new ParametroModel();

    //     // === RELACIONES PRINCIPALES ===
    //     $pedido->usuario = $usuarioModel->find($pedido->idusuario);
    //     log_message('debug', '👤 Usuario asociado: ' . json_encode($pedido->usuario));

    //     $pedido->formapago = $formapagoModel->find($pedido->idformapago);
    //     $pedido->entrega = $entregaModel->where('identrega', $pedido->identrega)->first();
    //     $pedido->estado = $estadoModel->find($pedido->idestado);
    //     $pedido->ppago = $ppagoModel->find($pedido->idppago);
    //     $pedido->entregaparametro = $parametroModel->find($pedido->identrega);

    //     // === COMPROBANTES ===
    //     $pedidoComprobantes = $db->table('pedido_comprobante')
    //         ->where('idpedido', $pedido->idpedido)
    //         ->get()
    //         ->getResultArray();

    //     $comprobantes = [];
    //     foreach ($pedidoComprobantes as $pc) {
    //         $comprobante = $comprobanteModel->find($pc['idcomprobante']);
    //         if ($comprobante) {
    //             // Subrelaciones del comprobante
    //             $comprobante->ptipo = $parametroModel->find($comprobante->idptipo);
    //             // === COMPROBANTES ===
    //             if ($comprobante->idubigeo) {
    //                 $comprobante->ubigeo = $ubigeoModel->find($comprobante->idubigeo);
    //                 if ($comprobante->ubigeo) {
    //                     $comprobante->ubigeo->rubigeo = $ubigeoModel->find($comprobante->ubigeo->idrubigeo);
    //                     if ($comprobante->ubigeo->rubigeo)
    //                         $comprobante->ubigeo->rubigeo->rubigeo = $ubigeoModel->find($comprobante->ubigeo->rubigeo->idrubigeo);
    //                 }
    //             }

    //             $comprobantes[] = $comprobante;
    //         }
    //     }
    //     $pedido->comprobante = $comprobantes;
    //     log_message('debug', '📑 Comprobantes anidados: ' . json_encode($comprobantes));

    //     // === AGENCIAS ===
    //     $pedidoAgencias = $db->table('pedido_agencia')
    //         ->where('idpedido', $pedido->idpedido)
    //         ->get()
    //         ->getResultArray();

    //     $agencias = [];
    //     foreach ($pedidoAgencias as $pa) {
    //         $agencia = $agenciaModel->find($pa['idagencia']);
    //         if ($agencia) {
    //             // Subrelaciones de agencia
    //             // === AGENCIA ===
    //             if ($agencia->idubigeo) {
    //                 $agencia->ubigeo = $ubigeoModel->find($agencia->idubigeo);
    //                 if ($agencia->ubigeo) {
    //                     $agencia->ubigeo->rubigeo = $ubigeoModel->find($agencia->ubigeo->idrubigeo);
    //                     if ($agencia->ubigeo->rubigeo)
    //                         $agencia->ubigeo->rubigeo->rubigeo = $ubigeoModel->find($agencia->ubigeo->rubigeo->idrubigeo);
    //                 }
    //             }

    //             $agencias[] = $agencia;
    //         }
    //     }
    //     $pedido->agencia = $agencias;

    //     log_message('debug', '🏬 Agencias anidadas: ' . json_encode($agencias));

    //     // === CUPONES ===
    //     $pedidoCupones = $db->table('pedido_cupon')
    //         ->where('idpedido', $pedido->idpedido)
    //         ->get()
    //         ->getResultArray();
    //     log_message('debug', '🎟️ Pedido cupones: ' . json_encode($pedidoCupones));

    //     $cupones = [];
    //     foreach ($pedidoCupones as $pc) {
    //         $cupon = $cuponModel->find($pc['idcupon']);
    //         if ($cupon) $cupones[] = $cupon;
    //     }
    //     $pedido->cupones = $cupones;
    //     log_message('debug', '🎫 Cupones anidados: ' . json_encode($cupones));

    //     // === DETALLES DEL PEDIDO ===
    //     $pedido->pedidoDetalle = $pedidoDetalleModel
    //         ->where('idpedido', $pedido->idpedido)
    //         ->findAll();
    //     log_message('debug', '📦 Detalles del pedido: ' . json_encode($pedido->pedidoDetalle));

    //     log_message('debug', '✅ Pedido final completo: ' . json_encode($pedido));

    //     return $pedido;
    // }

    // public function getPedidoConUsuario($idpedido)
    // {
    //     log_message('debug', '🔹 getPedidoConUsuario() llamado con ID: ' . $idpedido);

    //     $pedido = $this->find($idpedido);

    //     if (!$pedido) {
    //         log_message('error', '❌ Pedido no encontrado con ID: ' . $idpedido);
    //         return null;
    //     }

    //     $db = \Config\Database::connect();

    //     log_message('debug', '✅ Pedido base: ' . json_encode($pedido));

    //     // === MODELOS BASE ===
    //     $usuarioModel = new UsuarioModel();
    //     $formapagoModel = new FormaPagoModel();
    //     $entregaModel = new EntregaModel();
    //     $estadoModel = new EstadoModel();
    //     $ppagoModel = new ParametroModel();

    //     $comprobanteModel = new ComprobanteModel();
    //     $agenciaModel = new AgenciaModel();
    //     $cuponModel = new CuponModel();
    //     $pedidoDetalleModel = new PedidoDetalleModel();
    //     $ubigeoModel = new UbigeoModel();
    //     $parametroModel = new ParametroModel();

    //     // === RELACIONES PRINCIPALES ===
    //     $pedido->usuario = $usuarioModel->find($pedido->idusuario);
    //     log_message('debug', '👤 Usuario asociado: ' . json_encode($pedido->usuario));

    //     $pedido->formapago = $formapagoModel->find($pedido->idformapago);
    //     $pedido->entrega = $entregaModel->where('identrega', $pedido->identrega)->first();
    //     $pedido->estado = $estadoModel->find($pedido->idestado);
    //     $pedido->ppago = $ppagoModel->find($pedido->idppago);
    //     $pedido->entregaparametro = $parametroModel->find($pedido->identrega);

    //     // === COMPROBANTES ===
    //     $pedidoComprobantes = $db->table('pedido_comprobante')
    //         ->where('idpedido', $pedido->idpedido)
    //         ->get()
    //         ->getResultArray();

    //     $comprobantes = [];
    //     foreach ($pedidoComprobantes as $pc) {
    //         $comprobante = $comprobanteModel->find($pc['idcomprobante']);
    //         if ($comprobante) {

    //             // Convertir array a objeto para poder usar ->idubigeo
    //             if (is_array($comprobante)) {
    //                 $comprobante = (object) $comprobante;
    //             }

    //             $comprobante->ptipo = $parametroModel->find($comprobante->idptipo ?? 0);

    //             if (!empty($comprobante->idubigeo)) {
    //                 $comprobante->ubigeo = $ubigeoModel->find($comprobante->idubigeo);

    //                 if ($comprobante->ubigeo) {
    //                     $comprobante->ubigeo->rubigeo = $ubigeoModel->find($comprobante->ubigeo->idrubigeo);
    //                     if ($comprobante->ubigeo->rubigeo)
    //                         $comprobante->ubigeo->rubigeo->rubigeo = $ubigeoModel->find($comprobante->ubigeo->rubigeo->idrubigeo);
    //                 }
    //             }

    //             $comprobantes[] = $comprobante;
    //         }
    //     }

    //     $pedido->comprobante = $comprobantes;
    //     log_message('debug', '📑 Comprobantes anidados: ' . json_encode($comprobantes));

    //     // === AGENCIAS ===
    //     $pedidoAgencias = $db->table('pedido_agencia')
    //         ->where('idpedido', $pedido->idpedido)
    //         ->get()
    //         ->getResultArray();

    //     $agencias = [];
    //     foreach ($pedidoAgencias as $pa) {
    //         $agencia = $agenciaModel->find($pa['idagencia']);
    //         if ($agencia) {
    //             if ($agencia->idubigeo) {
    //                 $agencia->ubigeo = $ubigeoModel->find($agencia->idubigeo);
    //                 if ($agencia->ubigeo) {
    //                     $agencia->ubigeo->rubigeo = $ubigeoModel->find($agencia->ubigeo->idrubigeo);
    //                     if ($agencia->ubigeo->rubigeo)
    //                         $agencia->ubigeo->rubigeo->rubigeo = $ubigeoModel->find($agencia->ubigeo->rubigeo->idrubigeo);
    //                 }
    //             }
    //             $agencias[] = $agencia;
    //         }
    //     }
    //     $pedido->agencia = $agencias;
    //     log_message('debug', '🏬 Agencias anidadas: ' . json_encode($agencias));

    //     // === CUPONES ===
    //     $pedidoCupones = $db->table('pedido_cupon')
    //         ->where('idpedido', $pedido->idpedido)
    //         ->get()
    //         ->getResultArray();
    //     log_message('debug', '🎟️ Pedido cupones: ' . json_encode($pedidoCupones));

    //     $cupones = [];
    //     foreach ($pedidoCupones as $pc) {
    //         $cupon = $cuponModel->find($pc['idcupon']);
    //         if ($cupon) $cupones[] = $cupon;
    //     }
    //     $pedido->cupones = $cupones;
    //     log_message('debug', '🎫 Cupones anidados: ' . json_encode($cupones));

    //     // === DETALLES DEL PEDIDO ===
    //     $pedido->pedidoDetalle = $pedidoDetalleModel
    //         ->where('idpedido', $pedido->idpedido)
    //         ->findAll();
    //     log_message('debug', '📦 Detalles del pedido: ' . json_encode($pedido->pedidoDetalle));

    //     log_message('debug', '✅ Pedido final completo: ' . json_encode($pedido));

    //     var_dump($pedido);
    //     die();
    //     return $pedido;
    // }

    // public function getPedidoConUsuario($idpedido)
    // {
    //     $pedido = $this->find($idpedido);

    //     if (!$pedido) {
    //         return null;
    //     }

    //     $db = \Config\Database::connect();

    //     // === MODELOS BASE ===
    //     $usuarioModel = new UsuarioModel();
    //     $formapagoModel = new FormaPagoModel();
    //     $entregaModel = new EntregaModel();
    //     $estadoModel = new EstadoModel();
    //     $ppagoModel = new ParametroModel();

    //     $comprobanteModel = new ComprobanteModel();
    //     $agenciaModel = new AgenciaModel();

    //     $cuponModel = new CuponModel();
    //     $pedidoDetalleModel = new PedidoDetalleModel();


    //     $ubigeoModel = new UbigeoModel();
    //     $parametroModel = new ParametroModel();

    //     // === RELACIONES PRINCIPALES ===
    //     $pedido->usuario = $usuarioModel->find($pedido->idusuario);
    //     $pedido->formapago = $formapagoModel->find($pedido->idformapago);
    //     $pedido->entrega = $entregaModel
    //         ->where('identrega', $pedido->identrega)
    //         ->first();
    //     $pedido->estado = $estadoModel->find($pedido->idestado);
    //     $pedido->ppago = $ppagoModel->find($pedido->idppago);
    //     $pedido->entregaparametro = $parametroModel->find($pedido->identrega);

    //     // === COMPROBANTES ===
    //     $pedidoComprobantes = $db->table('pedido_comprobante')
    //         ->where('idpedido', $pedido->idpedido)
    //         ->get()
    //         ->getResultArray();

    //     $comprobantes = [];
    //     foreach ($pedidoComprobantes as $pc) {
    //         $comprobante = $comprobanteModel->find($pc['idcomprobante']);
    //         if ($comprobante) {
    //             // Subrelaciones del comprobante
    //             $comprobante->ptipo = $parametroModel->find($comprobante->idptipo);
    //             // === COMPROBANTES ===
    //             if ($comprobante->idubigeo) {
    //                 $comprobante->ubigeo = $ubigeoModel->find($comprobante->idubigeo);
    //                 if ($comprobante->ubigeo) {
    //                     $comprobante->ubigeo->rubigeo = $ubigeoModel->find($comprobante->ubigeo->idrubigeo);
    //                     if ($comprobante->ubigeo->rubigeo)
    //                         $comprobante->ubigeo->rubigeo->rubigeo = $ubigeoModel->find($comprobante->ubigeo->rubigeo->idrubigeo);
    //                 }
    //             }

    //             $comprobantes[] = $comprobante;
    //         }
    //     }
    //     $pedido->comprobante = $comprobantes;

    //     // === AGENCIAS ===
    //     $pedidoAgencias = $db->table('pedido_agencia')
    //         ->where('idpedido', $pedido->idpedido)
    //         ->get()
    //         ->getResultArray();

    //     $agencias = [];
    //     foreach ($pedidoAgencias as $pa) {
    //         $agencia = $agenciaModel->find($pa['idagencia']);
    //         if ($agencia) {
    //             // Subrelaciones de agencia
    //             // === AGENCIA ===
    //             if ($agencia->idubigeo) {
    //                 $agencia->ubigeo = $ubigeoModel->find($agencia->idubigeo);
    //                 if ($agencia->ubigeo) {
    //                     $agencia->ubigeo->rubigeo = $ubigeoModel->find($agencia->ubigeo->idrubigeo);
    //                     if ($agencia->ubigeo->rubigeo)
    //                         $agencia->ubigeo->rubigeo->rubigeo = $ubigeoModel->find($agencia->ubigeo->rubigeo->idrubigeo);
    //                 }
    //             }

    //             $agencias[] = $agencia;
    //         }
    //     }
    //     $pedido->agencia = $agencias;

    //     // === CUPONES ===
    //     $pedidoCupones = $db->table('pedido_cupon')
    //         ->where('idpedido', $pedido->idpedido)
    //         ->get()
    //         ->getResultArray();

    //     $cupones = [];
    //     foreach ($pedidoCupones as $pc) {
    //         $cupon = $cuponModel->find($pc['idcupon']);
    //         if ($cupon) $cupones[] = $cupon;
    //     }
    //     $pedido->cupones = $cupones;

    //     // === DETALLES DEL PEDIDO ===
    //     $pedido->pedidoDetalle = $pedidoDetalleModel
    //         ->where('idpedido', $pedido->idpedido)
    //         ->findAll();

    //     return $pedido;
    // }



    public function getPedidoConUsuario($idpedido)
    {
        $pedido = $this->find($idpedido);

        if (!$pedido) {
            return null;
        }

        $db = \Config\Database::connect();

        // === MODELOS BASE ===
        $usuarioModel = new UsuarioModel();
        $formapagoModel = new FormaPagoModel();
        $entregaModel = new EntregaModel();
        $estadoModel = new EstadoModel();
        $ppagoModel = new ParametroModel();

        $comprobanteModel = new ComprobanteModel();
        $agenciaModel = new AgenciaModel();
        $cuponModel = new CuponModel();
        $pedidoDetalleModel = new PedidoDetalleModel();
        $ubigeoModel = new UbigeoModel();
        $parametroModel = new ParametroModel();

        // === RELACIONES PRINCIPALES ===
        $pedido->usuario = $usuarioModel->find($pedido->idusuario);
        $pedido->formapago = $formapagoModel->find($pedido->idformapago);
        $pedido->entrega = $entregaModel->where('identrega', $pedido->identrega)->first();
        $pedido->estado = $estadoModel->find($pedido->idestado);
        $pedido->ppago = $ppagoModel->find($pedido->idppago);
        $pedido->entrega = $entregaModel->find($pedido->identrega);

        // === COMPROBANTES ===
        $pedidoComprobantes = $db->table('pedido_comprobante')
            ->where('idpedido', $pedido->idpedido)
            ->get()
            ->getResultArray();

        $comprobantes = [];
        foreach ($pedidoComprobantes as $pc) {
            $comprobante = $comprobanteModel->find($pc['idcomprobante']);
            if ($comprobante) {
                $comprobante->ptipo = $parametroModel->find($comprobante->idptipo);

                if ($comprobante->idubigeo) {
                    $comprobante->ubigeo = $ubigeoModel->find($comprobante->idubigeo);
                    if ($comprobante->ubigeo) {
                        $comprobante->ubigeo->rubigeo = $ubigeoModel->find($comprobante->ubigeo->idrubigeo);
                        if ($comprobante->ubigeo->rubigeo)
                            $comprobante->ubigeo->rubigeo->rubigeo = $ubigeoModel->find($comprobante->ubigeo->rubigeo->idrubigeo);
                    }
                }

                $comprobantes[] = $comprobante;
            }
        }
        $pedido->comprobante = $comprobantes;

        // === AGENCIAS ===
        $pedidoAgencias = $db->table('pedido_agencia')
            ->where('idpedido', $pedido->idpedido)
            ->get()
            ->getResultArray();

        $agencias = [];
        foreach ($pedidoAgencias as $pa) {
            $agencia = $agenciaModel->find($pa['idagencia']);
            if ($agencia) {
                if ($agencia->idubigeo) {
                    $agencia->ubigeo = $ubigeoModel->find($agencia->idubigeo);
                    if ($agencia->ubigeo) {
                        $agencia->ubigeo->rubigeo = $ubigeoModel->find($agencia->ubigeo->idrubigeo);
                        if ($agencia->ubigeo->rubigeo)
                            $agencia->ubigeo->rubigeo->rubigeo = $ubigeoModel->find($agencia->ubigeo->rubigeo->idrubigeo);
                    }
                }
                $agencias[] = $agencia;
            }
        }
        $pedido->agencia = $agencias;

        // === CUPONES ===
        $pedidoCupones = $db->table('pedido_cupon')
            ->where('idpedido', $pedido->idpedido)
            ->get()
            ->getResultArray();

        $cupones = [];
        foreach ($pedidoCupones as $pc) {
            $cupon = $cuponModel->find($pc['idcupon']);
            if ($cupon) $cupones[] = $cupon;
        }
        $pedido->cupones = $cupones;

        // === DETALLES DEL PEDIDO ===
        // $pedido->pedidoDetalle = $pedidoDetalleModel
        //     ->where('idpedido', $pedido->idpedido)
        //     ->findAll();
        $pedido->pedidoDetalle = array_map(function ($d) {
            return [
                'idPedidoDetalle' => $d->idpedidodetalle,
                'idProducto'      => $d->idproducto,
                'cantidad'        => $d->cantidad,
                'peso'            => $d->peso,
                'precio'          => $d->precio,
                'descuento'       => $d->descuento,
                'total'           => $d->total,
                'fecha'           => $d->fecha,
            ];
        }, $pedidoDetalleModel->where('idpedido', $pedido->idpedido)->findAll());


        // 🔥 Conversión recursiva final

        return $pedido;
    }

    // public function getPedidoConUsuario($idpedido)
    // {
    //     log_message('info', "🔍 Iniciando getPedidoConUsuario para ID: {$idpedido}");

    //     $pedido = $this->find($idpedido);

    //     if (!$pedido) {
    //         log_message('error', "❌ Pedido no encontrado con ID: {$idpedido}");
    //         return null;
    //     }

    //     $db = \Config\Database::connect();

    //     // === MODELOS BASE ===
    //     $usuarioModel = new UsuarioModel();
    //     $formapagoModel = new FormaPagoModel();
    //     $entregaModel = new EntregaModel();
    //     $estadoModel = new EstadoModel();
    //     $ppagoModel = new ParametroModel();

    //     $comprobanteModel = new ComprobanteModel();
    //     $agenciaModel = new AgenciaModel();
    //     $cuponModel = new CuponModel();
    //     $pedidoDetalleModel = new PedidoDetalleModel();
    //     $ubigeoModel = new UbigeoModel();
    //     $parametroModel = new ParametroModel();

    //     // === RELACIONES PRINCIPALES ===
    //     log_message('info', "📦 Cargando relaciones principales del pedido...");

    //     $pedido->usuario = $usuarioModel->find($pedido->idusuario);
    //     $pedido->formapago = $formapagoModel->find($pedido->idformapago);
    //     $pedido->entrega = $entregaModel->where('identrega', $pedido->identrega)->first();
    //     $pedido->estado = $estadoModel->find($pedido->idestado);
    //     $pedido->ppago = $ppagoModel->find($pedido->idppago);
    //     $pedido->entregaparametro = $parametroModel->find($pedido->identrega);

    //     // === COMPROBANTES ===
    //     log_message('info', "🧾 Cargando comprobantes del pedido...");

    //     $pedidoComprobantes = $db->table('pedido_comprobante')
    //         ->where('idpedido', $pedido->idpedido)
    //         ->get()
    //         ->getResultArray();

    //     $comprobantes = [];
    //     foreach ($pedidoComprobantes as $pc) {
    //         $comprobante = $comprobanteModel->find($pc['idcomprobante']);
    //         log_message('info', "➡️ Comprobante encontrado: " . json_encode($pc));

    //         if ($comprobante) {
    //             log_message('info', "   ✅ Tipo comprobante: " . gettype($comprobante));

    //             if (is_array($comprobante)) {
    //                 log_message('error', "   ⚠️ El comprobante {$pc['idcomprobante']} es un array, no un objeto.");
    //             }

    //             $comprobante->ptipo = $parametroModel->find($comprobante->idptipo ?? null);

    //             if (isset($comprobante->idubigeo)) {
    //                 log_message('info', "   🌎 Comprobante ID {$pc['idcomprobante']} tiene idubigeo={$comprobante->idubigeo}");

    //                 $comprobante->ubigeo = $ubigeoModel->find($comprobante->idubigeo);
    //                 if ($comprobante->ubigeo) {
    //                     log_message('info', "      🗺️ Ubigeo encontrado: {$comprobante->ubigeo->idubigeo}");
    //                 } else {
    //                     log_message('error', "      ❌ Ubigeo no encontrado para comprobante {$pc['idcomprobante']}");
    //                 }
    //             } else {
    //                 log_message('warning', "   ⚠️ Comprobante {$pc['idcomprobante']} no tiene idubigeo definido");
    //             }

    //             $comprobantes[] = $comprobante;
    //         } else {
    //             log_message('error', "❌ No se encontró comprobante con ID {$pc['idcomprobante']}");
    //         }
    //     }
    //     $pedido->comprobante = $comprobantes;

    //     // === AGENCIAS ===
    //     log_message('info', "🏢 Cargando agencias del pedido...");

    //     $pedidoAgencias = $db->table('pedido_agencia')
    //         ->where('idpedido', $pedido->idpedido)
    //         ->get()
    //         ->getResultArray();

    //     $agencias = [];
    //     foreach ($pedidoAgencias as $pa) {
    //         $agencia = $agenciaModel->find($pa['idagencia']);
    //         log_message('info', "➡️ Agencia encontrada: " . json_encode($pa));

    //         if ($agencia) {
    //             log_message('info', "   ✅ Tipo agencia: " . gettype($agencia));

    //             if (is_array($agencia)) {
    //                 log_message('error', "   ⚠️ La agencia {$pa['idagencia']} es un array, no un objeto.");
    //             }

    //             if (isset($agencia->idubigeo)) {
    //                 log_message('info', "   🌎 Agencia ID {$pa['idagencia']} tiene idubigeo={$agencia->idubigeo}");
    //                 $agencia->ubigeo = $ubigeoModel->find($agencia->idubigeo);
    //             } else {
    //                 log_message('warning', "   ⚠️ Agencia {$pa['idagencia']} no tiene idubigeo definido");
    //             }

    //             $agencias[] = $agencia;
    //         } else {
    //             log_message('error', "❌ No se encontró agencia con ID {$pa['idagencia']}");
    //         }
    //     }
    //     $pedido->agencia = $agencias;

    //     // === CUPONES ===
    //     log_message('info', "🎟️ Cargando cupones del pedido...");

    //     $pedidoCupones = $db->table('pedido_cupon')
    //         ->where('idpedido', $pedido->idpedido)
    //         ->get()
    //         ->getResultArray();

    //     $cupones = [];
    //     foreach ($pedidoCupones as $pc) {
    //         $cupon = $cuponModel->find($pc['idcupon']);
    //         if ($cupon) {
    //             log_message('info', "✅ Cupón encontrado ID: {$pc['idcupon']}");
    //             $cupones[] = $cupon;
    //         } else {
    //             log_message('error', "❌ Cupón no encontrado ID: {$pc['idcupon']}");
    //         }
    //     }
    //     $pedido->cupones = $cupones;

    //     // === DETALLES DEL PEDIDO ===
    //     log_message('info', "📦 Cargando detalles del pedido...");
    //     $pedido->pedidoDetalle = $pedidoDetalleModel
    //         ->where('idpedido', $pedido->idpedido)
    //         ->findAll();

    //     log_message('info', "✅ Pedido cargado completamente. Preparando retorno...");

    //     return $pedido;
    // }


    public function pedidoFindTotal($parametro, $valor, $idestado, $idusuario, $idformapago, $identrega, $idppago, $fecharango)
    {
        $db = \Config\Database::connect();
        $builder = $db->table('pedido p');

        $builder->select('p.*');

        if ($parametro != '' && $valor != '') {
            if ($parametro == 'referencia') {
                $builder->like('p.referencia', $valor);
            } elseif ($parametro == 'fecha') {
                $builder->where('p.fecha', $valor);
            } elseif ($parametro == 'nombres') {
                $builder->join('usuario u', 'u.idusuario = p.idusuario');
                $builder->where("CONCAT(u.nombres, ' ', u.papellido, ' ', u.sapellido) LIKE", "%$valor%");
            } elseif ($parametro == 'mes') {
                $builder->where('MONTH(p.fecha)', $valor);
            }
        }

        if ($idestado > 0) $builder->where('p.idestado', $idestado);
        if ($idusuario > 0) $builder->where('p.idusuario', $idusuario);
        if ($idformapago > 0) $builder->where('p.idformapago', $idformapago);
        if ($identrega > 0) $builder->where('p.identrega', $identrega);
        if ($idppago > 0) $builder->where('p.idppago', $idppago);

        if (!empty($fecharango)) {
            $builder->where('p.fechapedido >=', "$fecharango 00:00:00");
            $builder->where('p.fechapedido <=', "$fecharango 23:59:59");
        }

        return $builder->countAllResults();
    }

    // public function pedidoFind($ordencriterio, $ordentipo, $parametro, $valor, $idestado, $idusuario, $idformapago, $identrega, $idppago, $fecharango, $inicio, $registros)
    // {
    //     $db = \Config\Database::connect();
    //     $builder = $db->table('pedido p');

    //     $builder->select('p.*');

    //     if ($parametro != '' && $valor != '') {
    //         if ($parametro == 'referencia') {
    //             $builder->like('p.referencia', $valor);
    //         } elseif ($parametro == 'fecha') {
    //             $builder->where('p.fecha', $valor);
    //         } elseif ($parametro == 'nombres') {
    //             $builder->join('usuario u', 'u.idusuario = p.idusuario');
    //             $builder->where("CONCAT(u.nombres, ' ', u.papellido, ' ', u.sapellido) LIKE", "%$valor%");
    //         } elseif ($parametro == 'mes') {
    //             $builder->where('MONTH(p.fecha)', $valor);
    //         }
    //     }

    //     if ($idestado > 0) $builder->where('p.idestado', $idestado);
    //     if ($idusuario > 0) $builder->where('p.idusuario', $idusuario);
    //     if ($idformapago > 0) $builder->where('p.idformapago', $idformapago);
    //     if ($identrega > 0) $builder->where('p.identrega', $identrega);
    //     if ($idppago > 0) $builder->where('p.idppago', $idppago);

    //     if (!empty($fecharango)) {
    //         $builder->where('p.fechapedido >=', "$fecharango 00:00:00");
    //         $builder->where('p.fechapedido <=', "$fecharango 23:59:59");
    //     }

    //     if ($ordencriterio !== '' && $ordentipo !== '') {
    //         $builder->orderBy($ordencriterio, $ordentipo);
    //     }

    //     if ($inicio >= 0 && $registros > 0) {
    //         $builder->limit($registros, $inicio);
    //     }

    //     return $builder->get()->getResult();
    // }
    public function pedidoFind($ordencriterio, $ordentipo, $parametro, $valor, $idestado, $idusuario, $idformapago, $identrega, $idppago, $fecharango, $inicio, $registros)
    {
        $db = \Config\Database::connect();
        $builder = $db->table('pedido p');

        // --- Selección de campos principales ---
        $builder->select('p.*');
        if ($parametro != '' && $valor != '') {
            if ($parametro == 'referencia') {
                $builder->like('p.referencia', $valor);
            } elseif ($parametro == 'fecha') {
                $builder->where('p.fecha', $valor);
            } elseif ($parametro == 'nombres') {
                $builder->where("CONCAT(u.nombres, ' ', u.papellido, ' ', u.sapellido) LIKE", "%$valor%");
            } elseif ($parametro == 'mes') {
                $builder->where('MONTH(p.fecha)', $valor);
            }
        }

        if ($idestado > 0) $builder->where('p.idestado', $idestado);
        if ($idusuario > 0) $builder->where('p.idusuario', $idusuario);
        if ($idformapago > 0) $builder->where('p.idformapago', $idformapago);
        if ($identrega > 0) $builder->where('p.identrega', $identrega);
        if ($idppago > 0) $builder->where('p.idppago', $idppago);

        if (!empty($fecharango)) {
            $builder->where('p.fechapedido >=', "$fecharango 00:00:00");
            $builder->where('p.fechapedido <=', "$fecharango 23:59:59");
        }

        if ($ordencriterio !== '' && $ordentipo !== '') {
            $builder->orderBy($ordencriterio, $ordentipo);
        }

        if ($inicio >= 0 && $registros > 0) {
            $builder->limit($registros, $inicio);
        }

        $rows = $builder->get()->getResultArray();

        // --- Construir anidamientos ---
        $pedidos = [];
        $pedidoIds = [];
        foreach ($rows as $p) {
            $pedidoId = $p['idpedido'];
            $pedidoIds[] = $pedidoId;

            $pedidos[$pedidoId] = [
                'idPedido'     => $p['idpedido'],
                'referencia'   => $p['referencia'],
                'fechaPedido'  => $p['fechapedido'],
                'peso'  => $p['peso'],
                'costoEnvio'  => $p['costoenvio'],
                'comision'  => $p['comision'],
                'idEntrega'  => $p['identrega'],
                'subTotal'  => $p['subtotal'],
                'descuento'  => $p['descuento'],
                'fechaPedido'  => $p['fechapedido'],
                'fechaEntrega'  => $p['fechaentrega'],
                'observacion'  => $p['observacion'],
                'urlConstancia'  => $p['urlconstancia'],
                'fechaReporte'  => $p['fechareporte'],
                'fechaConfirmacion'  => $p['fechaconfirmacion'],
                'fecha'  => $p['fecha'],
                'fechaPedido'  => $p['fechapedido'],
                'total'        => $p['total'],
                'estado'       => [], // se cargará luego
                'usuario'      => [], // se cargará luego
                'formaPago'    => [], // se cargará luego
                'entrega'      => [], // se cargará luego
                'ppago'        => [], // se cargará luego
                'pedidoDetalle' => [],
                'agencia'      => [],
                'recojo'       => [],
                'destino'      => [],
                'comprobante'  => []
            ];
        }

        if (empty($pedidoIds)) {
            return []; // no hay pedidos
        }

        // --- CARGAR ESTADOS ---
        $estadoRows = $db->table('estado')->get()->getResultArray();
        $estados = [];
        foreach ($estadoRows as $e) {
            $estados[$e['idestado']] = $e;
        }

        // --- CARGAR FORMAS DE PAGO ---
        $formaPagoRows = $db->table('formapago')->get()->getResultArray();
        $formasPago = [];
        foreach ($formaPagoRows as $fp) {
            $fpEstado = $estados[$fp['idestado']] ?? null;
            $formasPago[$fp['idformapago']] = $fp;
            $formasPago[$fp['idformapago']]['estado'] = $fpEstado ? ['idEstado' => $fpEstado['idestado'], 'nombre' => $fpEstado['nombre']] : null;
        }

        // entrega
        $entregas = [];
        foreach ($pedidos as $pedidoId => $pedido) {


            $entregaData = $db->table('entrega')
                ->where('identrega', $pedido['idEntrega'])
                ->get()
                ->getRowArray();

            if (!$entregaData) continue;

            $enEstado = $estados[$entregaData['idestado']] ?? null;

            // Usar $pedido['idEntrega'] como clave
            $entregas[$pedido['idEntrega']] = [
                'idEntrega' => $entregaData['identrega'],
                'estado' => $enEstado ? ['idEstado' => $enEstado['idestado'], 'nombre' => $enEstado['nombre']] : null,
                'nombre' => $entregaData['nombre'],
                'dias' => $entregaData['dias'],
                'diasHabiles' => $entregaData['diashabiles'] ?? '',
                'importeMinimo' => $entregaData['importeminimo'] ?? '0.00',
                'minimoGratis' => $entregaData['minimogratis'] ?? '0.00',
                'costoEnvio' => $entregaData['costoenvio'] ?? '0.00',
                'horaReferencia' => $entregaData['horareferencia'] ?? 0,
                'pesoXcostoEnvio' => $entregaData['pesoxcostoenvio'] ?? '0.00',
                'fecha' => $entregaData['fecha'],
            ];
        }





        // --- CARGAR PPAGO ---
        $ppagoRows = $db->table('parametro')->get()->getResultArray();
        $ppagos = [];
        foreach ($ppagoRows as $pp) {
            $ppagos[$pp['idparametro']] = $pp;
        }

        // --- CARGAR USUARIOS ---
        $usuarioRows = $db->table('usuario')
            ->whereIn('idusuario', array_column($rows, 'idusuario'))
            ->get()
            ->getResultArray();

        $usuarios = [];
        foreach ($usuarioRows as $u) {
            // Estado del usuario
            $uEstado = $estados[$u['idestado']] ?? null;

            // Tipo de documento (pdocumento)
            $tipoDoc = null;
            if (!empty($u['idpdocumento'])) {
                $tipoDocRow = $db->table('parametro')->select('idparametro, nombre')->where('idparametro', $u['idpdocumento'])->get()->getRowArray();
                if ($tipoDocRow) {
                    $tipoDoc = [
                        'idParametro' => $tipoDocRow['idparametro'],
                        'nombre' => $tipoDocRow['nombre']
                    ];
                }
            }

            // Perfil
            $perfil = null;
            if (!empty($u['idperfil'])) {
                $perfilRow = $db->table('perfil')->where('idperfil', $u['idperfil'])->get()->getRowArray();
                if ($perfilRow) {
                    $perfilEstado = $estados[$perfilRow['idestado']] ?? null;
                    $perfilRow['estado'] = $perfilEstado ? ['idEstado' => $perfilEstado['idestado'], 'nombre' => $perfilEstado['nombre']] : null;
                    $perfil = $perfilRow;
                }
            }

            $usuarios[$u['idusuario']] = $u;
            $usuarios[$u['idusuario']]['estado'] = $uEstado ? ['idEstado' => $uEstado['idestado'], 'nombre' => $uEstado['nombre']] : null;
            $usuarios[$u['idusuario']]['pdocumento'] = $tipoDoc;
            $usuarios[$u['idusuario']]['perfil'] = $perfil;
        }


        // --- ASIGNAR RELACIONES PRINCIPALES ---
        foreach ($pedidos as $id => &$p) {
            $row = array_filter($rows, fn($r) => $r['idpedido'] == $id);
            $row = array_values($row)[0];
            // $p['estado'] = $estados[$row['idestado']] ? ['idEstado' => $estados[$row['idestado']]['idestado'], 'nombre' => $estados[$row['idestado']]['nombre']] : null;
            //estado
            $estadoRow = $estados[$row['idestado']] ?? null;

            $p['estado'] = $estadoRow ? [
                'idEstado' => $estadoRow['idestado'] ?? null,
                'nombre' => $estadoRow['nombre'] ?? null
            ] : null;


            $p['usuario'] = $usuarios[$row['idusuario']] ?? null;
            $p['formaPago'] = $formasPago[$row['idformapago']] ?? null;
            // $p['entrega'] = $entregas[$row['identrega']] ?? null;
            $p['entrega'] = $entregas[$row['identrega']] ?? null;

            // $p['ppago'] = $ppagos[$row['idppago']] ?? null;
            //ppago
            $ppagoRow = $ppagos[$row['idppago']] ?? null;

            $p['ppago'] = $ppagoRow ? [
                'idParametro' => $ppagoRow['idparametro'] ?? null,
                'idEstado' => $ppagoRow['idestado'] ?? null,
                'idTipo' => $ppagoRow['idtipo'] ?? null,
                'nombre' => $ppagoRow['nombre'] ?? null,
                'editable' => $ppagoRow['editable'] ?? null,
                'requerido' => $ppagoRow['requerido'] ?? null,
                'orden' => $ppagoRow['orden'] ?? null,
                'descripcion' => $ppagoRow['descripcion'] ?? null,
                'fecha' => $ppagoRow['fecha'] ?? null
            ] : null;
        }



        // $detalleRows = $db->table('pedidodetalle')->whereIn('idpedido', $pedidoIds)->get()->getResultArray();
        // $productoIds = array_column($detalleRows, 'idproducto');
        // $productos = [];

        // if (!empty($productoIds)) {
        //     $prodRows = $db->table('producto')->whereIn('idproducto', $productoIds)->get()->getResultArray();
        //     foreach ($prodRows as $prod) {
        //         $productobase = $db->table('productobase')->where('idproductobase', $prod['idproductobase'])->get()->getRowArray();

        //         // Cargar imágenes desde productoimagen
        //         $prodImagenes = $db->table('productoimagen')
        //             ->where('idproductobase', $prod['idproductobase'])
        //             ->get()
        //             ->getResultArray();

        //         foreach ($prodImagenes as &$img) {
        //             if (($img['idpdestacado'] ?? null) == 578) {
        //                 $img['urlimagen'] = $img['url'] ?? null;
        //             }
        //         }

        //         // Convertir productobase a camelCase
        //         $prod['productobase'] = $productobase ? [
        //             'idProductoBase' => $productobase['idproductobase'],
        //             'idEstado' => $productobase['idestado'],
        //             'idProductoCategoria' => $productobase['idproductocategoria'],
        //             'idPPromocion' => $productobase['idppromocion'] ?? null,
        //             'idPDestacado' => $productobase['idpdestacado'] ?? null,
        //             'codigo' => $productobase['codigo'],
        //             'nombre' => $productobase['nombre'],
        //             'urlAmigable' => $productobase['urlamigable'] ?? null,
        //             'resumen' => $productobase['resumen'] ?? null,
        //             'descripcionSeo' => $productobase['descripcionseo'] ?? null,
        //             'descripcion' => $productobase['descripcion'] ?? null,
        //             'urlImagen' => $productobase['urlimagen'] ?? null,
        //             'precioLista' => $productobase['preciolista'] ?? null,
        //             'precioVenta' => $productobase['precioventa'] ?? null,
        //             'peso' => $productobase['peso'] ?? null,
        //             'orden' => $productobase['orden'] ?? null,
        //             'fechaPublicacion' => $productobase['fechapublicacion'] ?? null,
        //             'fecha' => $productobase['fecha'] ?? null,
        //             'imagenes' => array_map(function ($img) {
        //                 return [
        //                     'idProductoImagen' => $img['idproductoimagen'],
        //                     'idProductoBase' => $img['idproductobase'],
        //                     'idEstado' => $img['idestado'],
        //                     'idPTipo' => $img['idptipo'],
        //                     'nombre' => $img['nombre'],
        //                     'orden' => $img['orden'],
        //                     'urlImagen' => $img['urlimagen'],
        //                     'fecha' => $img['fecha']
        //                 ];
        //             }, $prodImagenes)
        //         ] : null;

        //         // Convertir producto a camelCase
        //         $productos[$prod['idproducto']] = [
        //             'idProducto' => $prod['idproducto'],
        //             'idEstado' => $prod['idestado'],
        //             'idProductoBase' => $prod['idproductobase'],
        //             'idEmpresa' => $prod['idempresa'],
        //             'stock' => $prod['stock'],
        //             'fecha' => $prod['fecha'],
        //             'productoBase' => $prod['productobase']
        //         ];
        //     }
        // }

        // $detalleRows = $db->table('pedidodetalle')->whereIn('idpedido', $pedidoIds)->get()->getResultArray();
        // $productoIds = array_column($detalleRows, 'idproducto');
        // $productos = [];

        // if (!empty($productoIds)) {
        //     // Obtener productos
        //     $prodRows = $db->table('producto')->whereIn('idproducto', $productoIds)->get()->getResultArray();

        //     foreach ($prodRows as $prod) {
        //         // Obtener imágenes de producto
        //         $prodImagenes = $db->table('productoimagen')
        //             ->where('idproducto', $prod['idproducto'])
        //             ->get()
        //             ->getResultArray();

        //         foreach ($prodImagenes as &$img) {
        //             // Ajustar urlImagen si es destacado
        //             if (($img['idpdestacado'] ?? null) == 572) {
        //                 $img['urlimagen'] = $img['urlimagen'] ?? $img['url'] ?? null;
        //             }
        //         }

        //         // Producto con todos sus campos y sus imágenes
        //         $productos[$prod['idproducto']] = [
        //             'idProducto' => $prod['idproducto'],
        //             'idEstado' => $prod['idestado'],
        //             'idPDestacado' => $prod['idpdestacado'] ?? null,
        //             'idProductoCategoria' => $prod['idproductocategoria'] ?? null,
        //             'idPComplemento' => $prod['idpcomplemento'] ?? null,
        //             'idMarca' => $prod['idmarca'] ?? null,
        //             'idColor' => $prod['idcolor'] ?? null,
        //             'idPAjuste' => $prod['idpajuste'] ?? null,
        //             'idPLongitud' => $prod['idplongitud'] ?? null,
        //             'codigo' => $prod['codigo'] ?? null,
        //             'nombre' => $prod['nombre'] ?? null,
        //             'urlAmigable' => $prod['urlamigable'] ?? null,
        //             'urlImagen' => $prod['urlimagen'] ?? null,
        //             'resumen' => $prod['resumen'] ?? null,
        //             'contenido' => $prod['contenido'] ?? null,
        //             'guiaTalla' => $prod['guiatalla'] ?? null,
        //             'precioLista' => $prod['preciolista'] ?? null,
        //             'precioVenta' => $prod['precioventa'] ?? null,
        //             'stock' => $prod['stock'] ?? 0,
        //             'peso' => $prod['peso'] ?? 0,
        //             'orden' => $prod['orden'] ?? null,
        //             'compraXCliente' => $prod['compraxcliente'] ?? null,
        //             'fechaPublicacion' => $prod['fechapublicacion'] ?? null,
        //             'fecha' => $prod['fecha'] ?? null,
        //             'imagenes' => array_map(function ($img) {
        //                 return [
        //                     'idProductoImagen' => $img['idproductoimagen'],
        //                     'idProductoColor' => $img['idproductocolor'] ?? null,
        //                     'idProducto' => $img['idproducto'] ?? null,
        //                     'idPDestacado' => $img['idpdestacado'] ?? null,
        //                     'idEstado' => $img['idestado'] ?? null,
        //                     'nombre' => $img['nombre'] ?? null,
        //                     'urlImagen' => $img['urlimagen'] ?? null,
        //                     'orden' => $img['orden'] ?? null,
        //                     'destacado' => $img['destacado'] ?? null,
        //                     'fecha' => $img['fecha'] ?? null
        //                 ];
        //             }, $prodImagenes)
        //         ];
        //     }
        // }

        // // Asignar producto a cada pedidodetalle
        // foreach ($detalleRows as $d) {
        //     $pedidoDetalle = [
        //         'idPedidoDetalle' => $d['idpedidodetalle'],
        //         'idPedido' => $d['idpedido'],
        //         'idProducto' => $d['idproducto'],
        //         'cantidad' => $d['cantidad'],
        //         'peso' => $d['peso'],
        //         'precio' => $d['precio'],
        //         'descuento' => $d['descuento'],
        //         'total' => $d['total'],
        //         'fecha' => $d['fecha'],
        //         'producto' => $productos[$d['idproducto']] ?? null
        //     ];

        //     $pedidos[$d['idpedido']]['pedidoDetalle'][] = $pedidoDetalle;
        // }


        // --- Obtener detalles de pedidos ---
        $detalleRows = $db->table('pedidodetalle')->whereIn('idpedido', $pedidoIds)->get()->getResultArray();
        $productoIds = array_column($detalleRows, 'idproducto');
        $productos = [];

        if (!empty($productoIds)) {
            // --- Obtener productos ---
            $prodRows = $db->table('producto')->whereIn('idproducto', $productoIds)->get()->getResultArray();

            foreach ($prodRows as $prod) {
                // --- Obtener imágenes de producto ---
                $prodImagenes = $db->table('productoimagen')
                    ->where('idproducto', $prod['idproducto'])
                    ->get()
                    ->getResultArray();

                $selectedImage = null;

                if (!empty($prodImagenes)) {
                    // Priorizar imagen destacada
                    foreach ($prodImagenes as &$img) {
                        if (($img['idpdestacado'] ?? null) == 572) {
                            $img['urlimagen'] = $img['urlimagen'] ?? $img['url'] ?? null;
                            $selectedImage = $img['urlimagen'];
                            break;
                        }
                    }

                    // Si no hay destacada, usar la primera según orden
                    if (!$selectedImage) {
                        usort($prodImagenes, fn($a, $b) => ($a['orden'] ?? 0) <=> ($b['orden'] ?? 0));
                        $firstImg = $prodImagenes[0] ?? null;
                        if ($firstImg) {
                            $selectedImage = $firstImg['urlimagen'] ?? $firstImg['url'] ?? null;
                        }
                    }
                }

                // --- Producto con todos sus campos y sus imágenes ---
                $productos[$prod['idproducto']] = [
                    'idProducto' => $prod['idproducto'],
                    'idEstado' => $prod['idestado'],
                    'idPDestacado' => $prod['idpdestacado'] ?? null,
                    'idProductoCategoria' => $prod['idproductocategoria'] ?? null,
                    'idPComplemento' => $prod['idpcomplemento'] ?? null,
                    'idMarca' => $prod['idmarca'] ?? null,
                    'idColor' => $prod['idcolor'] ?? null,
                    'idPAjuste' => $prod['idpajuste'] ?? null,
                    'idPLongitud' => $prod['idplongitud'] ?? null,
                    'codigo' => $prod['codigo'] ?? null,
                    'nombre' => $prod['nombre'] ?? null,
                    'urlAmigable' => $prod['urlamigable'] ?? null,
                    'urlImagen' => $selectedImage, // Imagen principal
                    'resumen' => $prod['resumen'] ?? null,
                    'contenido' => $prod['contenido'] ?? null,
                    'guiaTalla' => $prod['guiatalla'] ?? null,
                    'precioLista' => $prod['preciolista'] ?? null,
                    'precioVenta' => $prod['precioventa'] ?? null,
                    'stock' => $prod['stock'] ?? 0,
                    'peso' => $prod['peso'] ?? 0,
                    'orden' => $prod['orden'] ?? null,
                    'compraXCliente' => $prod['compraxcliente'] ?? null,
                    'fechaPublicacion' => $prod['fechapublicacion'] ?? null,
                    'fecha' => $prod['fecha'] ?? null,
                    'imagenes' => array_map(function ($img) {
                        return [
                            'idProductoImagen' => $img['idproductoimagen'],
                            'idProductoColor' => $img['idproductocolor'] ?? null,
                            'idProducto' => $img['idproducto'] ?? null,
                            'idPDestacado' => $img['idpdestacado'] ?? null,
                            'idEstado' => $img['idestado'] ?? null,
                            'nombre' => $img['nombre'] ?? null,
                            'urlImagen' => $img['urlimagen'] ?? null,
                            'orden' => $img['orden'] ?? null,
                            'destacado' => $img['destacado'] ?? null,
                            'fecha' => $img['fecha'] ?? null
                        ];
                    }, $prodImagenes)
                ];
            }
        }

        // --- Asignar producto a cada detalle del pedido ---
        foreach ($detalleRows as $d) {
            $pedidoDetalle = [
                'idPedidoDetalle' => $d['idpedidodetalle'],
                'idPedido' => $d['idpedido'],
                'idProducto' => $d['idproducto'],
                'cantidad' => $d['cantidad'],
                'peso' => $d['peso'],
                'precio' => $d['precio'],
                'descuento' => $d['descuento'],
                'total' => $d['total'],
                'fecha' => $d['fecha'],
                'producto' => $productos[$d['idproducto']] ?? null
            ];

            $pedidos[$d['idpedido']]['pedidoDetalle'][] = $pedidoDetalle;
        }


        // ---------- AGENCIA ----------
        // var_dump($pedidoIds);
        $agenciaRows = $this->db->table('pedido_agencia pa')
            ->join('agencia a', 'a.idagencia = pa.idagencia')
            ->whereIn('pa.idpedido', $pedidoIds)
            ->get()
            ->getResultArray();

        foreach ($agenciaRows as $a) {
            // Estado
            $idEstado = $a['idestado'] ?? null;
            $estadoRow = $idEstado ? $this->db->table('estado')
                ->select('idestado, nombre')
                ->where('idestado', $idEstado)
                ->get()
                ->getRowArray() : null;

            $estado = $estadoRow ? ['idEstado' => $estadoRow['idestado'], 'nombre' => $estadoRow['nombre']] : null;

            // Ubigeo
            $ubigeo = $this->anidarUbigeo($a['idubigeo'] ?? null);

            // Usuario
            $usuarioRow = $usuarios[$a['idusuario'] ?? 0] ?? null;

            // Transformar agencia a camelCase
            $agenciaCamel = [
                'idPedido' => $a['idpedido'] ?? null,
                'idAgencia' => $a['idagencia'] ?? null,
                'idUsuario' => $a['idusuario'] ?? null,
                'idEstado' => $idEstado,
                'idUbigeo' => $a['idubigeo'] ?? null,
                'agencia' => $a['agencia'] ?? null,
                'direccion' => $a['direccion'] ?? null,
                'referencia' => $a['referencia'] ?? null,
                'nombres' => $a['nombres'] ?? null,
                'apellidos' => $a['apellidos'] ?? null,
                'ruc' => $a['dni'] ?? null,
                'telefono' => $a['telefono'] ?? null,
                'latitud' => $a['latitud'] ?? null,
                'longitud' => $a['longitud'] ?? null,
                'fecha' => $a['fecha'] ?? null,
                'estado' => $estado,
                'ubigeo' => $ubigeo,
                'usuario' => $usuarioRow
            ];

            $pedidos[$a['idpedido']]['agencia'][] = $agenciaCamel;
        }


        // // ---------- RECOJO ----------
        // $recojoRows = $this->db->table('pedido_recojo pr')
        //     ->join('recojo r', 'r.idrecojo = pr.idrecojo')
        //     ->whereIn('pr.idpedido', $pedidoIds)
        //     ->get()->getResultArray();

        // foreach ($recojoRows as $r) {
        //     $estado = $this->db->table('estado')->select('idestado, nombre')->where('idestado', $r['idestado'])->get()->getRowArray();
        //     $r['estado'] = $estado ? ['idEstado' => $estado['idestado'], 'nombre' => $estado['nombre']] : null;

        //     $r['ubigeo'] = $this->anidarUbigeo($r['idubigeo']);
        //     $r['usuario'] = $usuarios[$r['idusuario']] ?? null;
        //     $pedidos[$r['idpedido']]['recojo'][] = $r;
        // }

        // // ---------- DESTINO ----------
        // $destinoRows = $this->db->table('pedido_destino pd')
        //     ->join('destino d', 'd.iddestino = pd.iddestino')
        //     ->whereIn('pd.idpedido', $pedidoIds)
        //     ->get()->getResultArray();

        // foreach ($destinoRows as $d) {
        //     $estado = $this->db->table('estado')->select('idestado, nombre')->where('idestado', $d['idestado'])->get()->getRowArray();
        //     $d['estado'] = $estado ? ['idEstado' => $estado['idestado'], 'nombre' => $estado['nombre']] : null;

        //     $d['ubigeo'] = $this->anidarUbigeo($d['idubigeo']);
        //     $d['usuario'] = $usuarios[$d['idusuario']] ?? null;
        //     $pedidos[$d['idpedido']]['destino'][] = $d;
        // }


        // --- COMPROBANTE ---
        $compRows = $db->table('pedido_comprobante pc')
            ->select('pc.idpedido, c.idcomprobante, c.ruc, c.razonsocial, c.direccion, c.fecha, c.idestado, c.idusuario, c.idubigeo, c.idptipo')
            ->join('comprobante c', 'c.idcomprobante = pc.idcomprobante')
            ->whereIn('pc.idpedido', $pedidoIds)
            ->get()->getResultArray();

        foreach ($compRows as $c) {

            // --- Estado del comprobante ---
            $estadoRow = $db->table('estado')->where('idestado', $c['idestado'])->get()->getRowArray();
            $estado = $estadoRow ? ['idEstado' => $estadoRow['idestado'], 'nombre' => $estadoRow['nombre']] : null;

            // --- Usuario ---
            $usuario = $db->table('usuario u')
                ->select('u.idusuario, u.documento, u.nombres, u.papellido, u.sapellido, u.fechanacimiento, u.sexo, u.correo, u.telefono, u.login, u.password, u.fecha, u.idestado, u.idpdocumento, u.idperfil')
                ->where('u.idusuario', $c['idusuario'])
                ->get()->getRowArray();

            if ($usuario) {
                // Estado del usuario
                $uEstadoRow = $db->table('estado')->where('idestado', $usuario['idestado'])->get()->getRowArray();
                $usuarioEstado = $uEstadoRow ? ['idEstado' => $uEstadoRow['idestado'], 'nombre' => $uEstadoRow['nombre']] : null;

                // Tipo de documento
                $tipoDoc = null;
                if (!empty($usuario['idpdocumento'])) {
                    $tipoDocRow = $db->table('parametro')->select('idparametro, nombre')->where('idparametro', $usuario['idpdocumento'])->get()->getRowArray();
                    if ($tipoDocRow) {
                        $tipoDoc = ['idParametro' => $tipoDocRow['idparametro'], 'nombre' => $tipoDocRow['nombre']];
                    }
                }

                // Perfil
                $perfil = null;
                if (!empty($usuario['idperfil'])) {
                    $perfilRow = $db->table('perfil')->where('idperfil', $usuario['idperfil'])->get()->getRowArray();
                    if ($perfilRow) {
                        $perfilEstadoRow = $db->table('estado')->where('idestado', $perfilRow['idestado'])->get()->getRowArray();
                        $perfil['estado'] = $perfilEstadoRow ? ['idEstado' => $perfilEstadoRow['idestado'], 'nombre' => $perfilEstadoRow['nombre']] : null;
                        $perfil = array_merge($perfilRow, ['estado' => $perfil['estado']]);
                    }
                }

                $usuario['estado'] = $usuarioEstado;
                $usuario['pdocumento'] = $tipoDoc;
                $usuario['perfil'] = $perfil;
            }

            // --- PTipo ---
            $ptipo = null;
            if (!empty($c['idptipo'])) {
                $ptipoRow = $db->table('parametro')->where('idparametro', $c['idptipo'])->get()->getRowArray();
                if ($ptipoRow) {
                    $ptipoEstado = $db->table('estado')->where('idestado', $ptipoRow['idestado'])->get()->getRowArray();
                    $ptipo['estado'] = $ptipoEstado ? ['idEstado' => $ptipoEstado['idestado'], 'nombre' => $ptipoEstado['nombre']] : null;
                    $ptipo = array_merge($ptipoRow, ['estado' => $ptipo['estado']]);
                }
            }

            // --- Armar comprobante anidado ---
            $pedidos[$c['idpedido']]['comprobante'][] = [
                'idComprobante' => $c['idcomprobante'],
                'ruc' => $c['ruc'],
                'razonSocial' => $c['razonsocial'],
                'direccion' => $c['direccion'],
                'fecha' => $c['fecha'],
                'estado' => $estado,
                'usuario' => $usuario,
                'ubigeo' => $c['idubigeo'], // puedes anidar ubigeo si quieres detalles
                'ptipo' => $ptipo
            ];
        }
        // Convertir todo a camelCase
        // $pedidosCamelCase = $this->arrayKeysToCamelCase($pedidos);
        return array_values($pedidos);
    }

    // Función auxiliar: anidar ubigeos recursivamente
    public function anidarUbigeo($idUbigeo)
    {
        // Traer el registro actual
        $ubigeo = $this->db->table('ubigeo')
            ->where('idubigeo', $idUbigeo)
            ->get()
            ->getRowArray();

        if (!$ubigeo) {
            return null;
        }

        $result = [
            'idUbigeo' => $ubigeo['idubigeo'],
            'nombre' => $ubigeo['nombre'],
            'codigoPostal' => $ubigeo['codigopostal'] ?? null,
            'codigo' => $ubigeo['codigo'] ?? null,
            'nivel' => $ubigeo['nivel'],
            'fecha' => $ubigeo['fecha'],
            'estado' => null,
            'rUbigeo' => null,
        ];

        // Traer estado si existe
        if (!empty($ubigeo['idestado'])) {
            $estado = $this->db->table('estado')
                ->select('idestado, nombre')
                ->where('idestado', $ubigeo['idestado'])
                ->get()
                ->getRowArray();

            if ($estado) {
                $result['estado'] = [
                    'idEstado' => $estado['idestado'],
                    'nombre' => $estado['nombre']
                ];
            }
        }

        // Si tiene padre, lo anidamos recursivamente
        if (!empty($ubigeo['idrubigeo'])) {
            $result['rUbigeo'] = $this->anidarUbigeo($ubigeo['idrubigeo']);
        }

        return $result;
    }

    public function keysToCamelCase(array $array): array
    {
        $result = [];
        foreach ($array as $key => $value) {
            $newKey = lcfirst(str_replace(' ', '', ucwords(str_replace('_', ' ', $key))));
            if (is_array($value)) {
                $value = $this->keysToCamelCase($value);
            }
            $result[$newKey] = $value;
        }
        return $result;
    }





    public function obtenerPedido($idpedido)
    {
        $db = \Config\Database::connect();
        $builder = $db->table('pedido p');

        $builder->select('p.*, (SELECT pa.nombre FROM parametro pa WHERE pa.idparametro = p.idppago) as pago');
        $builder->where('p.idpedido', $idpedido);

        return $builder->get()->getRow();
    }

    public function pedidoTotalSuma($parametro, $valor, $idestado, $idppago)
    {
        $builder = $this->builder();

        if ($parametro != '' && $valor != '') {
            if ($parametro == 'mes') {
                $builder->where('MONTH(fecha)', $valor);
            } else {
                $builder->like($parametro, $valor);
            }
        }

        if ($idestado > 0) $builder->where('idestado', $idestado);
        if ($idppago > 0) $builder->where('idppago', $idppago);

        return $builder->selectSum('total')->get()->getRow()->total ?? 0;
    }
    //total de pedidos
    public function contarPedidosPorEmpresa(int $idempresa): int
    {
        return $this->where('idempresa', $idempresa)
            ->countAllResults();
    }
    //suma de total de pedidos 
    public function sumarTotalPedidosPorEmpresa(int $idempresa): float
    {
        return $this->selectSum('total')
            ->where('idempresa', $idempresa)
            ->get()
            ->getRow()
            ->total ?? 0;
    }

    public function pedidoTotalCantidad($parametro, $valor, $idestado, $idppago)
    {
        $builder = $this->builder();

        if ($parametro != '' && $valor != '') {
            if ($parametro == 'mes') {
                $builder->where('MONTH(fecha)', $valor);
            } else {
                $builder->like($parametro, $valor);
            }
        }

        if ($idestado > 0) $builder->where('idestado', $idestado);
        if ($idppago > 0) $builder->where('idppago', $idppago);

        return $builder->countAllResults();
    }

    public function obtenerPedidosPorCupon($idcupon)
    {
        $db = \Config\Database::connect();
        $builder = $db->table('pedido p');
        $builder->join('pedido_cupon', 'pedido_cupon.idpedido = p.idpedido');
        $builder->where('pedido_cupon.idcupon', $idcupon);

        return $builder->countAllResults();
    }

    public function obtenerByIdCuponIdProducto($idcupon, $idproducto)
    {
        $db = \Config\Database::connect();
        $builder = $db->table('pedido p');
        $builder->join('pedido_cupon', 'pedido_cupon.idpedido = p.idpedido');
        $builder->join('pedidodetalle', 'pedidodetalle.idpedido = p.idpedido');
        $builder->where('pedido_cupon.idcupon', $idcupon);
        $builder->where('pedidodetalle.idproducto', $idproducto);

        return $builder->countAllResults();
    }

    public function obtenerByIdCuponIdUsuario($idcupon, $idusuario)
    {
        $db = \Config\Database::connect();
        $builder = $db->table('pedido p');
        $builder->join('pedido_cupon', 'pedido_cupon.idpedido = p.idpedido');
        $builder->where('pedido_cupon.idcupon', $idcupon);
        $builder->where('p.idusuario', $idusuario);

        return $builder->countAllResults();
    }

    public function pedidoTotalSumaFiltrado($idusuario = null, $idestado = null)
    {
        $builder = $this->builder();

        if ($idusuario !== null) {
            $builder->where('idusuario', $idusuario);
        }

        if ($idestado !== null) {
            $builder->where('idestado', $idestado);
        }

        return $builder->selectSum('total')->get()->getRow()->total ?? 0;
    }

    public function pedidoTotalSumaUsuario($parametro, $valor, $idestado, $idppago, $idusuario)
    {
        $builder = $this->builder();

        if ($parametro != '' && $valor != '') {
            if ($parametro == 'mes') {
                $builder->where('MONTH(fecha)', $valor);
            } else {
                $builder->like($parametro, $valor);
            }
        }

        if ($idestado > 0) $builder->where('idestado', $idestado);
        if ($idppago > 0) $builder->where('idppago', $idppago);
        if ($idusuario > 0) $builder->where('idusuario', $idusuario);

        return $builder->selectSum('total')->get()->getRow()->total ?? 0;
    }

    public function obtenerByIdCuponIdProductoTalla($idcupon, $idproductotalla)
    {
        $db = \Config\Database::connect();
        $builder = $db->table('pedido p');
        $builder->join('pedido_cupon', 'pedido_cupon.idpedido = p.idpedido');
        $builder->join('pedidodetalle', 'pedidodetalle.idpedido = p.idpedido');
        $builder->where('pedido_cupon.idcupon', $idcupon);
        $builder->where('pedidodetalle.idproductotalla', $idproductotalla);

        return $builder->countAllResults();
    }

    public function obtenerPorCodigo($numero)
    {
        return $this->where('numero', $numero)->first();
    }

    // public function obtenerByIdPedidoIdUsuario(int $idpedido, int $idusuario = 0)
    // {
    //     $builder = $this->db->table($this->table);

    //     // Columnas a seleccionar
    //     $builder->select([
    //         'pedido.*',
    //         'comprobante.ruc as comprobante_ruc',
    //         'comprobante.razonsocial as comprobante_razonsocial',
    //         'comprobante.direccion as comprobante_direccion',
    //         'agencia.agencia as agencia_agencia',
    //         'agencia.direccion as agencia_direccion',
    //         'agencia.nombres as agencia_nombres',
    //         'agencia.apellidos as agencia_apellidos',
    //         'agencia.dni as agencia_dni',
    //         'agencia.telefono as agencia_telefono',
    //         '(select e.nombre from estado e where pedido.idestado = e.idestado) as estado',
    //         '(select p.nombre from parametro p where pedido.idppago = p.idparametro) as ppago',
    //         '(select e.nombre from entrega e where pedido.identrega = e.identrega) as entrega',
    //         '(select e.nombre from parametro e where comprobante.idptipo = e.idparametro) as comprobante_ptipo',
    //         '(select fp.nombre from formapago fp where pedido.idformapago = fp.idformapago) as formapago'
    //     ]);

    //     // Joins
    //     $builder->join('pedido_comprobante', 'pedido_comprobante.idpedido = pedido.idpedido', 'left');
    //     $builder->join('comprobante', 'comprobante.idcomprobante = pedido_comprobante.idcomprobante', 'left');

    //     // Asumiendo que existe la tabla pedido_destino


    //     // Asumiendo que existe la tabla pedido_recojo


    //     $builder->join('pedido_agencia', 'pedido_agencia.idpedido = pedido.idpedido', 'left');
    //     $builder->join('agencia', 'agencia.idagencia = pedido_agencia.idagencia', 'left');

    //     // Filtros
    //     $builder->where('pedido.idpedido', $idpedido);
    //     if ($idusuario > 0) {
    //         $builder->where('pedido.idusuario', $idusuario);
    //     }

    //     return $builder->get()->getRowArray();
    // }




    public function obtenerByIdPedidoIdUsuario(int $idpedido, int $idusuario = 0)
    {
        $builder = $this->db->table($this->table);

        // Columnas a seleccionar
        // $builder->select([
        //     'pedido.*',
        //     'comprobante.ruc as comprobante_ruc',
        //     'comprobante.razonsocial as comprobante_razonsocial',
        //     'comprobante.direccion as comprobante_direccion',
        //     'agencia.agencia as agencia_agencia',
        //     'agencia.direccion as agencia_direccion',
        //     'agencia.nombres as agencia_nombres',
        //     'agencia.apellidos as agencia_apellidos',
        //     'agencia.dni as agencia_dni',
        //     'agencia.telefono as agencia_telefono',
        //     '(select e.nombre from estado e where pedido.idestado = e.idestado) as estado',
        //     '(select p.nombre from parametro p where pedido.idppago = p.idparametro) as ppago',
        //     '(select e.nombre from entrega e where pedido.identrega = e.identrega) as entrega',
        //     '(select e.nombre from parametro e where comprobante.idptipo = e.idparametro) as comprobante_ptipo',
        //     '(select fp.nombre from formapago fp where pedido.idformapago = fp.idformapago) as formapago',
        //     'producto.urlamigable as urlamigable',
        //     'IFNULL((SELECT urlImagen FROM productoimagen WHERE idproducto = pedidodetalle.idproducto AND idpdestacado = 572 LIMIT 1), 
        //         (SELECT urlImagen FROM productoimagen WHERE idproducto = pedidodetalle.idproducto ORDER BY idproductoimagen LIMIT 1)) as urlImagen'
        // ]);

        $builder->select([
            'pedido.*',
            'comprobante.ruc as comprobante_ruc',
            'comprobante.razonsocial as comprobante_razonsocial',
            'comprobante.direccion as comprobante_direccion',
            'agencia.agencia as agencia_agencia',
            'agencia.direccion as agencia_direccion',
            'agencia.referencia as agencia_referencia',
            'agencia.nombres as agencia_nombres',
            'agencia.apellidos as agencia_apellidos',
            'agencia.dni as agencia_dni',
            'agencia.telefono as agencia_telefono',
            'agencia.idubigeo as agencia_idubigeo',
            'agencia.latitud as agencia_latitud',
            'agencia.longitud as agencia_longitud',
            'agencia.fecha as agencia_fecha',
            '(select e.nombre from estado e where pedido.idestado = e.idestado) as estado',
            '(select p.nombre from parametro p where pedido.idppago = p.idparametro) as ppago',
            '(select e.nombre from entrega e where pedido.identrega = e.identrega) as entrega',
            '(select e.nombre from parametro e where comprobante.idptipo = e.idparametro) as comprobante_ptipo',
            '(select fp.nombre from formapago fp where pedido.idformapago = fp.idformapago) as formapago',
            'producto.urlamigable as urlamigable',
            'IFNULL(
                (SELECT urlImagen FROM productoimagen WHERE idproducto = pedidodetalle.idproducto AND idpdestacado = 572 LIMIT 1),
                (SELECT urlImagen FROM productoimagen WHERE idproducto = pedidodetalle.idproducto ORDER BY idproductoimagen LIMIT 1)
            ) as urlImagen'
        ]);


        // Joins
        $builder->join('pedido_comprobante', 'pedido_comprobante.idpedido = pedido.idpedido', 'left');
        $builder->join('comprobante', 'comprobante.idcomprobante = pedido_comprobante.idcomprobante', 'left');
        $builder->join('pedido_agencia', 'pedido_agencia.idpedido = pedido.idpedido', 'left');
        $builder->join('agencia', 'agencia.idagencia = pedido_agencia.idagencia', 'left');

        // Cambiar el JOIN para referirse a 'pedidodetalle' en lugar de 'pedido_detalle'
        $builder->join('pedidodetalle', 'pedidodetalle.idpedido = pedido.idpedido', 'left');

        // JOIN para obtener 'urlamigable' de la tabla 'producto'
        $builder->join('producto', 'producto.idproducto = pedidodetalle.idproducto', 'left');

        // Filtros
        $builder->where('pedido.idpedido', $idpedido);
        if ($idusuario > 0) {
            $builder->where('pedido.idusuario', $idusuario);
        }

        return $builder->get()->getRowArray();
    }


    // public function obtenerByIdPedidoIdUsuario(int $idpedido, int $idusuario = 0)
    // {
    //     $builder = $this->db->table($this->table);
    //     $builder->select('pedido.*,

    //         comprobante.ruc as comprobante_ruc,
    //         comprobante.razonsocial as comprobante_razonsocial,
    //         comprobante.direccion as comprobante_direccion,

    //         agencia.agencia as agencia_agencia,
    //         agencia.direccion as agencia_direccion,
    //         agencia.nombres as agencia_nombres,
    //         agencia.apellidos as agencia_apellidos,
    //         agencia.dni as agencia_dni,
    //         agencia.telefono as agencia_telefono,
    //         (select e.nombre from estado e where pedido.idestado = e.idestado) as estado,
    //         (select p.nombre from parametro p where pedido.idppago = p.idparametro) as ppago,
    //         (select e.nombre from entrega e where pedido.identrega = e.identrega) as entrega,
    //         (select e.nombre from parametro e where comprobante.idptipo = e.idparametro) as comprobante_ptipo,
    //         (select fp.nombre from formapago fp where pedido.idformapago = fp.idformapago) as formapago');

    //     $builder->join('pedido_comprobante', 'pedido_comprobante.idpedido = pedido.idpedido', 'left');
    //     $builder->join('comprobante', 'comprobante.idcomprobante = pedido_comprobante.idcomprobante', 'left');

    //     $builder->join('destino', 'destino.iddestino = pedido_destino.iddestino', 'left');

    //     $builder->join('recojo', 'recojo.idrecojo = pedido_recojo.idrecojo', 'left');
    //     $builder->join('pedido_agencia', 'pedido_agencia.idpedido = pedido.idpedido', 'left');
    //     $builder->join('agencia', 'agencia.idagencia = pedido_agencia.idagencia', 'left');

    //     $builder->where('pedido.idpedido', $idpedido);
    //     if ($idusuario > 0) {
    //         $builder->where('pedido.idusuario', $idusuario);
    //     }

    //     return $builder->get()->getRowArray();
    // }
}
