<?php

namespace App\Models;

use App\Entities\EmpresaEntity;
use CodeIgniter\Model;

class OpcionModel extends Model
{
    protected $table      = 'opcion';
    protected $primaryKey = 'idopcion';

    protected $useAutoIncrement = true;

    protected $returnType     = 'array';
    protected $useSoftDeletes = false;

    protected $allowedFields = [];

    protected $useTimestamps = false;
    protected $createdField  = 'fecha';

    protected $validationRules    = [];
    protected $validationMessages = [];
    protected $skipValidation     = false;


    public function listarCodigosPorUsuario($idUsuario)
    {
        $builder = $this->db->table('usuario_rol ur');
        $builder->select('r.abr, o.codigo');
        $builder->join('rol r', 'ur.idrol = r.idrol');
        $builder->join('opcion_rol orol', 'ur.idrol = orol.idrol');
        $builder->join('opcion o', 'orol.idopcion = o.idopcion');
        $builder->where('ur.idusuario', $idUsuario);

        $query = $builder->get();
        $results = $query->getResultArray();

        $permisos = [];

        foreach ($results as $row) {
            // Agregar rol (abr) si no está vacío y no existe
            if (!empty($row['abr']) && !in_array($row['abr'], $permisos)) {
                $permisos[] = $row['abr'];
            }

            // Agregar código de permiso si no está vacío y no existe
            if (!empty($row['codigo']) && !in_array($row['codigo'], $permisos)) {
                $permisos[] = $row['codigo'];
            }
        }

        return $permisos;
    }
}
