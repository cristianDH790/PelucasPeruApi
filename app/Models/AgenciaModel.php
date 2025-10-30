<?php

namespace App\Models;

use App\Entities\AgenciaEntity;
use App\Entities\DestinoEntity;
use CodeIgniter\Model;

class AgenciaModel extends Model
{
    protected $table = 'agencia';
    protected $primaryKey = 'idagencia';
    protected  $returnType = AgenciaEntity:: class;
    protected $allowedFields = ['idestado', 'idusuario', 'idubigeo', 'agencia', 'direccion', 'nombres', 'apellidos', 'dni', 'telefono'];

    public function agenciaFind($ordencriterio, $ordentipo, $parametro, $valor, $idestado, $idusuario, $idubigeo, $inicio, $registros)
    {
        $builder = $this->db->table($this->table)->where('idagencia >=', 1);

        if ($idestado > 0)
            $builder->where('idestado', $idestado);

        if ($idusuario > 0)
            $builder->where('idusuario', $idusuario);

        if ($idubigeo > 0)
            $builder->where('idubigeo', $idubigeo);

        if ($parametro != '' && $valor != '')
            $builder->like($parametro, $valor);

        if ($ordencriterio != '' && $ordentipo != '')
            $builder->orderBy($ordencriterio, $ordentipo);

        if ($inicio >= 0 && $registros > 0)
            $builder->limit($registros, $inicio);

        return $builder->get()->getResult();
    }

    public function agenciaFindTotal($parametro, $valor, $idestado, $idusuario, $idubigeo)
    {
        $builder = $this->db->table($this->table)->where('idagencia >=', 1);

        if ($idestado > 0)
            $builder->where('idestado', $idestado);

        if ($idusuario > 0)
            $builder->where('idusuario', $idusuario);

        if ($idubigeo > 0)
            $builder->where('idubigeo', $idubigeo);

        if ($parametro != '' && $valor != '')
            $builder->like($parametro, $valor);

        return $builder->countAllResults();
    }
}
