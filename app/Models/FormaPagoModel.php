<?php

namespace App\Models;

use App\Entities\EmpresaEntity;
use App\Entities\FormaPagoEntity;
use App\Entities\Noticia;
use App\Entities\NoticiaEntity;
use CodeIgniter\Model;

class FormaPagoModel extends Model
{
    protected $table      = 'formapago';
    protected $primaryKey = 'idformapago';

    protected $useAutoIncrement = true;

    protected $returnType     = FormaPagoEntity::class;
    protected $useSoftDeletes = false;

    protected $allowedFields = [
        'idestado',
        'idformapago',
        
        'nombre',
        'abr',
        'comision',
        'contenido',
        'contenido2',
        'orden',
        'fecha',
    ];

    protected $useTimestamps = false;
    protected $createdField  = 'fecha';

    protected $validationRules    = [];
    protected $validationMessages = [];
    protected $skipValidation     = false;



    // Obtener curso por ID
    public  function obtenerPorId($idformapago)
    {
        return $this->where('idformapago', $idformapago)->first();
    }




    public function obtenerPorUrlAmigable($urlamigable)
    {
        return $this->where('urlamigable', $urlamigable)->first();
    }



    public function buscarPor($ordencriterio, $ordentipo, $parametro, $valor, $idestado,  $inicio, $registros)
    {
        $builder = $this->db->table($this->table);
        $builder->select('*');

        // Filtro por búsqueda
        if (!empty($parametro) && !empty($valor)) {
            $builder->like($parametro, trim($valor), 'both');
        }

        if ($idestado > 0)
            $builder->where('idestado', $idestado);


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


    public function buscarPorTotal($parametro, $valor, $idestado,)
    {
        $builder = $this->db->table($this->table);
        $builder->select('COUNT(*) as total');

        // Filtro por búsqueda
        if (!empty($parametro) && !empty($valor)) {
            $builder->like($parametro, trim($valor), 'both');
        }


        if ($idestado > 0)
            $builder->where('idestado', $idestado);

        return $builder->countAllResults();
    }



    public function eliminar($idformapago): bool
    {
        $this->db->transStart();
        try {
            if (!$this->where('idformapago', $idformapago)->first()) {
                return false;
            }

            $resultado = $this->delete($idformapago);

            $this->db->transComplete();
            return $resultado;
        } catch (\Throwable $e) {
            $this->db->transRollback();
            log_message('error', 'Eliminar formapago falló: ' . $e->getMessage());
            return false;
        }
    }

    public function guardar($data): int
    {
        $this->db->transStart();
        try {
            if (empty($data['idformapago'])) {
                $this->insert($data);
                $id = $this->getInsertID();
            } else {
                $this->update($data['idformapago'], $data);
                $id = $data['idformapago'];
            }
            // echo $this->db->getLastQuery();
            $this->db->transComplete();
            return $id;
        } catch (\Throwable $e) {
            $this->db->transRollback();
            // var_dump($data);
            // echo $this->db->getLastQuery();
            // log_message('error', 'Consulta fallida: ' . $this->db->getLastQuery());
            log_message('error', 'Error en guardar: ' . $e->getMessage());
            throw $e;
        }
    }
}
