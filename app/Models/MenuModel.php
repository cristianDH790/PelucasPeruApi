<?php

namespace App\Models;

use App\Entities\MarcaEntity;
use App\Entities\MenuEntity;
use App\Entities\ProductoBaseEntity;
use App\Entities\ProductoImagenEntity;
use CodeIgniter\Model;

class MenuModel extends Model
{
    protected $table      = 'menu';
    protected $primaryKey = 'idmenu';

    protected $useAutoIncrement = true;

    protected $returnType     = MenuEntity::class;
    protected $useSoftDeletes = false;

    protected $allowedFields = [
        'idestado',
        'idrmenu',
        'idptipo',
        'idpubicacion',
        'idpdestino',
        'nombre',
        'destino',
        'seccion',
        'orden',
        'fecha'
    ];


    protected $useTimestamps = false;
    protected $createdField  = 'fecha';

    protected $validationRules    = [];
    protected $validationMessages = [];
    protected $skipValidation     = false;



    // Obtener curso por ID
    public  function obtenerPorId($idmarca)
    {
        return $this->where('idmenu', $idmarca)->first();
    }




    public function obtenerPorUrlAmigable($urlamigable)
    {
        return $this->where('urlamigable', $urlamigable)->first();
    }



    public function buscarPor($ordencriterio, $ordentipo, $parametro, $valor, $idestado, $idrmenu, $idptipo, $idpubicacion, $inicio, $registros)
    {
        $builder = $this->db->table($this->table);
        $builder->select('*');

        // Filtro por búsqueda
        if (!empty($parametro) && !empty($valor)) {
            $builder->like($parametro, trim($valor), 'both');
        }

        if ($idestado > 0)
            $builder->where('idestado', $idestado);
        if ($idpubicacion > 0)
            $builder->where('menu.idpubicacion', $idpubicacion);

        if ($idptipo > 0)
            $builder->where('menu.idptipo', $idptipo);

        // Filtro por idrcontenidowebcategoria
        if ($idrmenu > 0) {
            $builder->where('idrmenu', $idrmenu);
        } elseif ($idrmenu < 0) {
            $builder->where('idrmenu IS NULL', null, false); // <- Aquí va la magia
        }

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


    public function buscarPorTotal($parametro, $valor,  $idestado, $idrmenu, $idptipo, $idpubicacion)
    {
        $builder = $this->db->table($this->table);
        $builder->select('COUNT(*) as total');

        // Filtro por búsqueda
        if (!empty($parametro) && !empty($valor)) {
            $builder->like($parametro, trim($valor), 'both');
        }
        if ($idpubicacion > 0)
            $builder->where('menu.idpubicacion', $idpubicacion);

        if ($idptipo > 0)
            $builder->where('menu.idptipo', $idptipo);

        if ($idestado > 0)
            $builder->where('idestado', $idestado);

        // Filtro por idrcontenidowebcategoria
        if ($idrmenu > 0) {
            $builder->where('idrmenu', $idrmenu);
        } elseif ($idrmenu == -9999) {
            $builder->where('idrmenu IS NULL', null, false); // Padres sin relación
        }
        // Si es 0, no agregamos ningún filtro => trae todos



        return $builder->countAllResults();
    }



    public function eliminar($idmarca): bool
    {
        $this->db->transStart();
        try {
            if (!$this->where('idmenu', $idmarca)->first()) {
                return false;
            }

            $resultado = $this->delete($idmarca);

            $this->db->transComplete();
            return $resultado;
        } catch (\Throwable $e) {
            $this->db->transRollback();
            log_message('error', 'Eliminar menu base falló: ' . $e->getMessage());
            return false;
        }
    }

    public function guardar($data): int
    {
        $this->db->transStart();
        try {
            if (empty($data['idmenu'])) {
                $this->insert($data);
                $id = $this->getInsertID();
            } else {
                $this->update($data['idmenu'], $data);
                $id = $data['idmenu'];
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
