<?php

namespace App\Models;

use App\Entities\ComprobanteEntity;
use App\Entities\DestinoEntity;

use CodeIgniter\Model;

class ComprobanteModel extends Model
{
    protected $table      = 'comprobante';
    protected $primaryKey = 'idcomprobante';

    protected $useAutoIncrement = true;

    protected  $returnType = ComprobanteEntity::class;

    // protected $returnType     =  DestinoEntity::class;
    protected $useSoftDeletes = false;

    protected $allowedFields = [
        'idcomprobante',
        'idusuario',
        'idestado',
        'idubigeo',
        'idptipo',
        'ruc',
        'razonsocial',
        'fecha',

    ];

    protected $useTimestamps = false;
    protected $createdField  = 'fecha';

    protected $validationRules    = [];
    protected $validationMessages = [];
    protected $skipValidation     = false;



    // Obtener curso por ID
    public  function obtenerPorId($idcomprobante)
    {
        return $this->where('idcomprobante', $idcomprobante)->first();
    }



    public function crearComprobante($comprobanteData, $usuario)
    {
        // Validar datos mínimos
        if (empty($usuario) || !isset($usuario['idUsuario'])) {
            throw new \Exception('Faltan datos del usuario para el comprobante');
        }

        $data = [
            'idusuario' => $usuario['idUsuario'],
            'idtipocomprobante' => $comprobanteData['idTipoComprobante'] ?? 1, // por defecto boleta
            'idestado' => 1,
            'numero' => $comprobanteData['numero'] ?? null,
            'ruc' => $comprobanteData['ruc'] ?? null,
            'razonsocial' => $comprobanteData['razonSocial'] ?? null,
            'direccion' => $comprobanteData['direccion'] ?? null,
            'fechaemision' => date('Y-m-d'),
            'subtotal' => $comprobanteData['subtotal'] ?? 0,
            'igv' => $comprobanteData['igv'] ?? 0,
            'total' => $comprobanteData['total'] ?? 0,
            'observacion' => $comprobanteData['observacion'] ?? null,
            'fecharegistro' => date('Y-m-d H:i:s'),
        ];

        $idComprobante = $this->insert($data);
        return $this->find($idComprobante);
    }


    // MÉTODO: Buscar con filtros y joins virtuales
    public function buscarPor($ordencriterio, $ordentipo, $parametro, $valor, $idestado, $idusuario, $idptipo, $inicio, $registros)
    {
        $builder = $this->builder(); // Usamos el builder del modelo actual

        $builder->select('comprobante.*,
            (SELECT e.nombre FROM estado e WHERE comprobante.idestado = e.idestado) as estado,
            (SELECT p.nombre FROM parametro p WHERE comprobante.idptipo = p.idparametro) as ptipo,
            (SELECT u.nombre FROM ubigeo u WHERE comprobante.idubigeo = u.idubigeo) as ubigeo');

        // Filtros de búsqueda
        if (!empty($parametro) && !empty($valor)) {
            $builder->like($parametro, $valor);
        }

        if ($idestado > 0) {
            $builder->where('comprobante.idestado', $idestado);
        }

        if ($idusuario > 0) {
            $builder->where('comprobante.idusuario', $idusuario);
        }

        if ($idptipo > 0) {
            $builder->where('comprobante.idptipo', $idptipo);
        }

        // Orden
        if (!empty($ordencriterio) && !empty($ordentipo)) {
            $builder->orderBy($ordencriterio, $ordentipo);
        }

        // Paginación
        if ($registros > 0 && $inicio >= 0) {
            $builder->limit($registros, $inicio);
        }

        return $builder->get()->getResult();
    }

    // MÉTODO: Buscar total para paginación
    public function buscarTotalPor($parametro, $valor, $idestado, $idusuario, $idptipo)
    {
        $builder = $this->builder();

        if (!empty($parametro) && !empty($valor)) {
            $builder->like($parametro, $valor);
        }

        if ($idestado > 0) {
            $builder->where('idestado', $idestado);
        }

        if ($idusuario > 0) {
            $builder->where('idusuario', $idusuario);
        }

        if ($idptipo > 0) {
            $builder->where('idptipo', $idptipo);
        }

        return $builder->countAllResults();
    }


    public function eliminar($iddestino): bool
    {
        $this->db->transStart();
        try {
            if (!$this->where('iddestino', $iddestino)->first()) {
                return false;
            }

            $resultado = $this->delete($iddestino);

            $this->db->transComplete();
            return $resultado;
        } catch (\Throwable $e) {
            $this->db->transRollback();
            log_message('error', 'Eliminar destino falló: ' . $e->getMessage());
            return false;
        }
    }

    public function guardar($data): int
    {
        $this->db->transStart();
        try {
            if (empty($data['iddestino'])) {
                $this->insert($data);
                $id = $this->getInsertID();
            } else {
                $this->update($data['iddestino'], $data);
                $id = $data['iddestino'];
            }
            $this->db->transComplete();
            return $id;
        } catch (\Throwable $e) {
            $this->db->transRollback();
            var_dump($data);

            log_message('error', 'Error en guardar: ' . $e->getMessage());
            throw $e;
        }
    }
}
