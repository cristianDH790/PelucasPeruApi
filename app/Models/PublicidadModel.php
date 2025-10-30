<?php

namespace App\Models;

use App\Entities\MarcaEntity;
use App\Entities\ProductoBaseEntity;
use App\Entities\ProductoImagenEntity;
use App\Entities\PublicidadEntity;
use CodeIgniter\Model;

class PublicidadModel extends Model
{
    protected $table      = 'publicidad';
    protected $primaryKey = 'idpublicidad';

    protected $useAutoIncrement = true;

    protected $returnType     = PublicidadEntity::class;
    protected $useSoftDeletes = false;

    protected $allowedFields = [
        'idpublicidad',
        'idestado',
        'idpdestino',
        'nombre',
        'titulo',
        'urlimagen',
        'urlrecurso',
        'inicio',
        'termino',
    ];


    protected $useTimestamps = false;
    protected $createdField  = 'fecha';

    protected $validationRules    = [];
    protected $validationMessages = [];
    protected $skipValidation     = false;



    // Obtener curso por ID
    public  function obtenerPorId($idpublicidad)
    {
        return $this->where('idpublicidad', $idpublicidad)->first();
    }




    public function obtenerPorUrlAmigable($urlamigable)
    {
        return $this->where('urlamigable', $urlamigable)->first();
    }



    public function buscarPor($ordencriterio = '', $ordentipo = '', $parametro = '', $valor = '', $idestado = 0, $ipdestino = 0, $inicio = null, $registros = null)
    {
        $builder = $this->db->table($this->table);
        $builder->select('*');

        // Filtro por búsqueda
        if (!empty($parametro) && !empty($valor)) {
            $builder->like($parametro, trim($valor), 'both');
        }

        if ($idestado > 0)
            $builder->where('idestado', $idestado);
        if ($ipdestino > 0)
            $builder->where('idpdestino', $ipdestino);


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


    public function buscarPorTotal($parametro = '', $valor = '',  $idestado = 0, $ipdestino = 0)
    {
        $builder = $this->db->table($this->table);
        $builder->select('COUNT(*) as total');

        // Filtro por búsqueda
        if (!empty($parametro) && !empty($valor)) {
            $builder->like($parametro, trim($valor), 'both');
        }


        if ($idestado > 0)
            $builder->where('idestado', $idestado);
        if ($ipdestino > 0)
            $builder->where('idpdestino', $ipdestino);



        return $builder->countAllResults();
    }



    public function eliminar($idpublicidad): bool
    {
        $this->db->transStart();
        try {
            if (!$this->where('idpublicidad', $idpublicidad)->first()) {
                return false;
            }

            $resultado = $this->delete($idpublicidad);

            $this->db->transComplete();
            return $resultado;
        } catch (\Throwable $e) {
            $this->db->transRollback();
            log_message('error', 'Eliminar publicidad base falló: ' . $e->getMessage());
            return false;
        }
    }

    public function guardar($data): int
    {

        $this->db->transStart();
        try {
            if (empty($data['idpublicidad'])) {
                $this->insert($data);
                $id = $this->getInsertID();
            } else {
                $this->update($data['idpublicidad'], $data);
                $id = $data['idpublicidad'];
            }
            $this->db->transComplete();
            return $id;
        } catch (\Throwable $e) {
            $this->db->transRollback();

            log_message('error', 'SQL generado: ' . $this->sede->db->getLastQuery());
            log_message('error', 'Error en guardar: ' . $e->getMessage());
            throw $e;
        }
    }
    //marcas count
    public function contarMarcasPorEmpresa($idempresa): int
    {
        return $this->db->table('marca_empresa')
            ->where('idempresa', $idempresa)
            ->countAllResults();
    }



    public function obtenerMarcaPorProductoBase($idProductoBase)
    {
        return $this->db->table('marca e')
            ->join('productobase_marca ue', 'ue.idpublicidad = e.idpublicidad')
            ->where('ue.idproductobase', $idProductoBase)
            ->get()
            ->getRow();
    }
}
