<?php

namespace App\Models;

use App\Entities\RecojoEntity;
use CodeIgniter\Model;

class RecojoModel extends Model
{
    protected $table            = 'recojo';
    protected $primaryKey       = 'idrecojo';
    protected $useAutoIncrement = true;
    protected $returnType       = RecojoEntity::class;
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;

    protected $allowedFields    = [
        'idrecojo',
        'idestado',
        'idusuario',
        'idtienda', // cambiado idsede a idtienda
        'dni',
        'nombres',
        'apellidos',
        'telefono',
        'fecha'
    ];

    // Timestamps
    protected $useTimestamps = false;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'fecha';
    protected $updatedField  = null;
    protected $deletedField  = null;

    // Guardar
    public function guardar($data)
    {
        $this->db->transStart();
        try {
            if (empty($data['idrecojo'])) {
                $this->insert($data);
                $id = $this->getInsertID();
            } else {
                $this->update($data['idrecojo'], $data);
                $id = $data['idrecojo'];
            }
            $this->db->transComplete();
            return $id;
        } catch (\Throwable $th) {
            $this->db->transRollback();
            return false;
        }
    }

    // Eliminar
    public function eliminar($idrecojo)
    {
        $this->db->transStart();
        try {
            if (!$this->find($idrecojo)) {
                return false;
            }
            $resultado = $this->delete($idrecojo);
            $this->db->transComplete();
            return $resultado;
        } catch (\Throwable $th) {
            $this->db->transRollback();
            return false;
        }
    }

    // Buscar con filtros y relaciones
    public function buscarPor(
        string $ordencriterio,
        string $ordentipo,
        string $parametro,
        string $valor,
        int $idestado,
        int $idusuario,
        int $idtienda, // cambiado idsede a idtienda
        int $inicio,
        int $registros
    ) {
        $builder = $this->db->table($this->table . ' as recojo');

        $builder->select('
            recojo.*, 
            (select e.nombre from estado e where recojo.idestado = e.idestado) as estado,
            (select t.nombre from tienda t where recojo.idtienda = t.idtienda) as tienda,
            (select t.direccion from tienda t where t.idtienda = recojo.idtienda limit 1) as direccion_tienda
        ');

        if (!empty($parametro) && !empty($valor)) {
            $builder->like('recojo.' . $parametro, $valor);
        }
        if ($idestado > 0) {
            $builder->where('recojo.idestado', $idestado);
        }
        if ($idusuario > 0) {
            $builder->where('recojo.idusuario', $idusuario);
        }
        if ($idtienda > 0) {
            $builder->where('recojo.idtienda', $idtienda);
        }

        if (!empty($ordencriterio) && !empty($ordentipo)) {
            $builder->orderBy('recojo.' . $ordencriterio, $ordentipo);
        }

        if ($registros > 0) {
            $builder->limit($registros, $inicio);
        }

        return $builder->get()->getResultArray();
    }

    // Contar total con filtros
    public function buscarPorTotal(
        string $parametro,
        string $valor,
        int $idestado,
        int $idusuario,
        int $idtienda
    ) {
        $builder = $this->db->table($this->table);

        if (!empty($parametro) && !empty($valor)) {
            $builder->like($parametro, $valor);
        }
        if ($idestado > 0) {
            $builder->where('idestado', $idestado);
        }
        if ($idusuario > 0) {
            $builder->where('idusuario', $idusuario);
        }
        if ($idtienda > 0) {
            $builder->where('idtienda', $idtienda);
        }

        return $builder->countAllResults();
    }
}
