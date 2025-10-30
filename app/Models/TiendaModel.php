<?php

namespace App\Models;

use App\Entities\TiendaEntity;
use CodeIgniter\Model;

class TiendaModel extends Model
{
    protected $table            = 'tienda';
    protected $primaryKey       = 'idtienda';
    protected $useAutoIncrement = true;
    protected $returnType       = TiendaEntity::class; // Cambia el nombre si tienes otra entidad para tienda
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;

    protected $allowedFields    = [
        'idestado',
        'idubigeo',
        'nombre',
        'telefono',
        'direccion',
        'horario1',
        'horario2',
        'horario3',
        'delivery',
        'horainicio',
        'horatermino',
        'latitud',
        'longitud',
        'urlimagen',
        'ventaxmayor',
        'orden'
    ];

    // Fechas
    protected $useTimestamps = false;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'fecha';
    protected $updatedField  = null;
    protected $deletedField  = null;

    // Obtener tienda por idtienda
    public function obtenerPorId($idtienda)
    {
        return $this->where('idtienda', $idtienda)->first();
    }

    // Buscar tiendas con filtros
    public function buscarPor(
        $ordencriterio = null,
        $ordentipo = null,
        $parametro = null,
        $valor = null,
        $idestado = 0,
        $idubigeo = 0,
        $inicio = 0,
        $registros = 0
    ) {
        $builder = $this->db->table($this->table);
        $builder->select('*');

        if (!empty($parametro) && !empty($valor)) {
            $builder->like($parametro, $valor);
        }

        if ($idestado > 0) {
            $builder->where('idestado', $idestado);
        }

        if ($idubigeo > 0) {
            $builder->where('idubigeo', $idubigeo);
        }

        if (!empty($ordencriterio) && !empty($ordentipo)) {
            $builder->orderBy($ordencriterio, $ordentipo);
        }

        if ($registros > 0) {
            $builder->limit($registros, $inicio);
        }

        return $builder->get()->getResult();
    }

    // Contar total tiendas con filtros
    public function buscarPorTotal($parametro = null, $valor = null, $idestado = 0, $idubigeo = 0)
    {
        $builder = $this->db->table($this->table);

        if (!empty($parametro) && !empty($valor)) {
            $builder->like($parametro, trim($valor), 'both');
        }

        if ($idestado > 0) {
            $builder->where('idestado', $idestado);
        }

        if ($idubigeo > 0) {
            $builder->where('idubigeo', $idubigeo);
        }

        return $builder->countAllResults();
    }

    // Eliminar tienda por idtienda
    public function eliminar($idtienda): bool
    {
        $this->db->transStart();
        try {
            if (!$this->where('idtienda', $idtienda)->first()) {
                return false;
            }

            $resultado = $this->delete($idtienda);

            $this->db->transComplete();
            return $resultado;
        } catch (\Throwable $e) {
            $this->db->transRollback();
            log_message('error', 'Eliminar tienda falló: ' . $e->getMessage());
            return false;
        }
    }

    // Guardar tienda (insertar o actualizar)
    public function guardar($data): int
    {
        $this->db->transStart();
        try {
            if (empty($data['idtienda'])) {
                $this->insert($data);
                $id = $this->getInsertID();
            } else {
                $this->update($data['idtienda'], $data);
                $id = $data['idtienda'];
            }
            $this->db->transComplete();
            return $id;
        } catch (\Throwable $e) {
            $this->db->transRollback();
            log_message('error', 'Error en guardar tienda: ' . $e->getMessage());
            throw $e;
        }
    }
}
