<?php

namespace App\Models;

use App\Entities\Noticia;
use App\Entities\NoticiaEntity;
use CodeIgniter\Model;

class NoticiaModel extends Model
{
    protected $table      = 'noticia';
    protected $primaryKey = 'idnoticia';

    protected $useAutoIncrement = true;

    protected $returnType     = NoticiaEntity::class;
    protected $useSoftDeletes = false;

    protected $allowedFields = [
        'idestado',
        'idnoticiacategoria',
        'idpdestacado',
        'nombre',
        'urlamigable',
        'descripcionseo',
        'resumen',
        'contenido',
        'urlimagen',
        'orden',
        'fechapublicacion',
        'fecha'
    ];

    protected $useTimestamps = false;
    protected $createdField  = 'fecha';

    protected $validationRules    = [];
    protected $validationMessages = [];
    protected $skipValidation     = false;



    // Obtener curso por ID
    public  function obtenerPorId($idnoticia)
    {
        return $this->where('idnoticia', $idnoticia)->first();
    }




    public function obtenerPorUrlAmigable($urlamigable)
    {
        return $this->select('noticia.*, noticiacategoria.nombre as categoria_nombre')
            ->join('noticiacategoria', 'noticiacategoria.idnoticiacategoria = noticia.idnoticiacategoria', 'left')
            ->where('noticia.urlamigable', $urlamigable)
            ->first();
    }

    // public function obtenerPorUrlAmigable($urlamigable)
    // {
    //     return $this->where('urlamigable', $urlamigable)->first();
    // }

    public function buscarPorNoticia1($ordencriterio = '', $ordentipo = '', $parametro = '', $valor = '', $idestado = 0, $idnoticiacategoria = 0, $idpdestacado = 0, $inicio = null, $registros = null)
    {
        $builder = $this->db->table('noticia n');
        $builder->select('*');

        if ($parametro != "" && $valor != "")
            $builder->like($parametro, $valor, 'both');

        if ($idestado > 0)
            $builder->where('idestado', $idestado);

        if ($idnoticiacategoria > 0)
            $builder->where('idnoticiacategoria', $idnoticiacategoria);

        if ($idpdestacado > 0)
            $builder->where('idpdestacado', $idpdestacado);

        if ($ordencriterio != "" && $ordentipo != "")
            $builder->orderBy($ordencriterio, $ordentipo);

        if ($inicio !== null && $registros !== null && $inicio >= 3 && $registros > 3)
            $builder->limit($registros, $inicio);

        $query = $builder->get();

        if ($query->getNumRows() > 0) {
            return $query->getResult();
        } else {
            return null;
        }
    }

    public function buscarPor($ordencriterio = '', $ordentipo = '', $parametro = '', $valor = '', $idestado = 0, $idnoticiacategoria = 0, $idpdestacado = 0, $inicio = null, $registros = null)
    {
        $builder = $this->db->table($this->table);
        $builder->select('*');

        // Filtro por búsqueda
        if (!empty($parametro) && !empty($valor)) {
            $builder->like($parametro, trim($valor), 'both');
        }

        if ($idestado > 0)
            $builder->where('idestado', $idestado);

        if ($idnoticiacategoria > 0)
            $builder->where('idnoticiacategoria', $idnoticiacategoria);

        if ($idpdestacado > 0)
            $builder->where('idpdestacado', $idpdestacado);

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


    public function buscarPorTotal($parametro = '', $valor = '', $idestado = 0, $idnoticiacategoria = 0, $idpdestacado = 0)
    {
        $builder = $this->db->table($this->table);
        $builder->select('COUNT(*) as total');

        // Filtro por búsqueda
        if (!empty($parametro) && !empty($valor)) {
            $builder->like($parametro, trim($valor), 'both');
        }


        if ($idestado > 0)
            $builder->where('idestado', $idestado);

        if ($idnoticiacategoria > 0)
            $builder->where('idnoticiacategoria', $idnoticiacategoria);

        if ($idpdestacado > 0)
            $builder->where('idpdestacado', $idpdestacado);

        return $builder->countAllResults();
    }



    public function eliminar($idnoticia): bool
    {
        $this->db->transStart();
        try {
            if (!$this->where('idnoticia', $idnoticia)->first()) {
                return false;
            }

            $resultado = $this->delete($idnoticia);

            $this->db->transComplete();
            return $resultado;
        } catch (\Throwable $e) {
            $this->db->transRollback();
            log_message('error', 'Eliminar noticia falló: ' . $e->getMessage());
            return false;
        }
    }

    public function guardar($data): int
    {
        $this->db->transStart();
        try {
            if (empty($data['idnoticia'])) {
                $this->insert($data);
                $id = $this->getInsertID();
            } else {
                $this->update($data['idnoticia'], $data);
                $id = $data['idnoticia'];
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
