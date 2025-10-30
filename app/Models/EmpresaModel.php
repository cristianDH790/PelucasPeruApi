<?php

namespace App\Models;

use App\Entities\EmpresaEntity;
use CodeIgniter\Model;

class EmpresaModel extends Model
{
    protected $table      = 'empresa';
    protected $primaryKey = 'idempresa';

    protected $useAutoIncrement = true;

    protected $returnType     = EmpresaEntity::class;
    protected $useSoftDeletes = false;

    protected $allowedFields = [
        'idestado',
        'nombre',
        'razonsocial',
        'ruc',
        'direccion',
        'orden',
        'fecha',
    ];

    protected $useTimestamps = false;
    protected $createdField  = 'fecha';

    protected $validationRules    = [];
    protected $validationMessages = [];
    protected $skipValidation     = false;



    // Obtener curso por ID
    public  function obtenerPorId($idempresa)
    {
        return $this->where('idempresa', $idempresa)->first();
    }






    public function obtenerPorUrlAmigable($urlamigable)
    {
        return $this->where('urlamigable', $urlamigable)->first();
    }



    public function buscarPor($ordencriterio = '', $ordentipo = '', $parametro = '', $valor = '', $idestado = 0,  $inicio = null, $registros = null)
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


    public function buscarPorTotal($parametro = '', $valor = '', $idestado = 0)
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



    public function eliminar($idempresa): bool
    {
        $this->db->transStart();
        try {
            if (!$this->where('idempresa', $idempresa)->first()) {
                return false;
            }

            $resultado = $this->delete($idempresa);

            $this->db->transComplete();
            return $resultado;
        } catch (\Throwable $e) {
            $this->db->transRollback();
            log_message('error', 'Eliminar empresa falló: ' . $e->getMessage());
            return false;
        }
    }

    public function guardar($data): int
    {
        $this->db->transStart();
        try {
            if (empty($data['idempresa'])) {
                $this->insert($data);
                $id = $this->getInsertID();
            } else {
                $this->update($data['idempresa'], $data);
                $id = $data['idempresa'];
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

    //obtener la empresa del usuario
    public function obtenerEmpresaPorUsuario($idusuario)
    {
        return $this->db->table('empresa e')
            ->select('e.*') // Seleccionamos los datos de la empresa
            ->join('usuario_empresa ue', 'ue.idempresa = e.idempresa')
            ->where('ue.idusuario', $idusuario)
            ->get()
            ->getRow(); // Opcional: obtener solo una fila
    }
     public function obtenerEmpresaPorMarca($idmarca)
    {
        return $this->db->table('empresa e')
            ->select('e.*') // Seleccionamos los datos de la empresa
            ->join('marca_empresa ue', 'ue.idempresa = e.idempresa')
            ->where('ue.idmarca', $idmarca)
            ->get()
            ->getRow(); // Opcional: obtener solo una fila
    }
}
