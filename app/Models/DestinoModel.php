<?php

namespace App\Models;

use App\Entities\DestinoEntity;

use CodeIgniter\Model;

class DestinoModel extends Model
{
    protected $table      = 'destino';
    protected $primaryKey = 'idestino';

    protected $useAutoIncrement = true;

    protected $returnType     = DestinoEntity::class;
    protected $useSoftDeletes = false;

    protected $allowedFields = [
        'idubigeo',
        'idusuario',
        'idestado',
        'idptipo',
        'alias',
        'nombres',
        'apellidos',
        'dni',
        'direccion',
        'referencia',
        'telefono',
        'latitud',
        'longitud',
        'fecha',
       
    ];

    protected $useTimestamps = false;
    protected $createdField  = 'fecha';

    protected $validationRules    = [];
    protected $validationMessages = [];
    protected $skipValidation     = false;



    // Obtener curso por ID
    public  function obtenerPorId($iddestino)
    {
        return $this->where('iddestino', $iddestino)->first();
    }




    public function obtenerPorUrlAmigable($urlamigable)
    {
        return $this->where('urlamigable', $urlamigable)->first();
    }

   

    public function buscarPor($ordencriterio = '', $ordentipo = '', $parametro = '', $valor = '', $idestado = 0, $idubigeo = 0, $idusuario = 0, $idptipo = 0,   $inicio = null, $registros = null)
    {
        $builder = $this->db->table($this->table);
        $builder->select('*');

          // Filtro por búsqueda
        if (!empty($parametro) && !empty($valor)) {
            $builder->like($parametro, trim($valor), 'both');
        }
        if ($idestado > 0)
            $builder->where('idestado', $idestado);
        if ($idubigeo > 0)
            $builder->where('idubigeo', $idubigeo);
        if ($idusuario > 0)
            $builder->where('idusuario', $idusuario);
        if ($idptipo > 0)
            $builder->where('idptipo', $idptipo);

    
        // Ordenamiento
        if (!empty($ordencriterio) && !empty($ordentipo)) {
            $builder->orderBy($ordencriterio, $ordentipo);
        }

        // Paginación
        if ($registros > 0) {
            $builder->limit($registros, $inicio);
        }

        return $builder->get()->getResult();
    }


    public function buscarPorTotal($parametro = '', $valor = '', $idestado = 0, $idubigeo = 0, $idusuario = 0, $idptipo = 0,)
    {
        $builder = $this->db->table($this->table);
        $builder->select('COUNT(*) as total');

            // Filtro por búsqueda
        if (!empty($parametro) && !empty($valor)) {
            $builder->like($parametro, trim($valor), 'both');
        }


      if ($idestado > 0)
            $builder->where('idestado', $idestado);
        if ($idubigeo > 0)
            $builder->where('idubigeo', $idubigeo);
        if ($idusuario > 0)
            $builder->where('idusuario', $idusuario);
        if ($idptipo > 0)
            $builder->where('idptipo', $idptipo);


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
