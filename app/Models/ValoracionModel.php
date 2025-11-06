<?php

namespace App\Models;

use CodeIgniter\Model;

class ValoracionModel extends Model
{
    protected $table = 'valoracion';
    protected $primaryKey = 'idvaloracion';
    protected $allowedFields = [
        'idestado',
        'idrvaloracion',
        'idclase',
        'idreferencia',
        'idusuario',
        'valor',
        'fecha'
    ];
    function obtenerById($idvaloracion)
    {
        $valoracion = new ValoracionModel();
        return $valoracion->find($idvaloracion);
    }

    function eliminar($idvaloracion)
    {
        $builder = new ValoracionModel();
        $builder->delete(['idvaloracion' => $idvaloracion]);
    }

    function guardar($data)
    {
        $builder = new ValoracionModel();
        if (!isset($data['idvaloracion'])) {
            $builder->save($data);
            return $builder->insertID();
        } else {
            $builder->update($data['idvaloracion'], $data);
            return $data['idvaloracion'];
        }
    }


    public function buscarPor($ordencriterio, $ordentipo, $parametro, $valor, $idestado, $idclase, $idusuario, $idrvaloracion, $idreferencia, $inicio, $registros)
    {
        $builder = $this->select('valoracion.*, (select e.nombre from estado e where valoracion.idestado=e.idestado) as estado');

        if (!empty($ordencriterio) && !empty($ordentipo)) {
            $builder->orderBy($ordencriterio, $ordentipo);
        }

        if (!empty($parametro) && !empty($valor)) {
            $builder->like($parametro, $valor);
        }

        if (!empty($idestado)) {
            $builder->where('valoracion.idestado', $idestado);
        }
        if (!empty($idclase)) {
            $builder->where('valoracion.idclase', $idclase);
        }
        if (!empty($idusuario)) {
            $builder->where('valoracion.idusuario', $idusuario);
        }
        if (!empty($idrvaloracion)) {
            $builder->where('valoracion.idrvaloracion', $idrvaloracion);
        }
        if (!empty($idreferencia)) {
            $builder->where('valoracion.idreferencia', $idreferencia);
        }

        if ($inicio >= 0 && $registros > 0) {
            $builder->limit($registros, $inicio);
        }

        return $builder->get()->getResult();
    }


    public function obtenerResumenValoraciones($idreferencia)
    {
        $db = \Config\Database::connect();

        // Consulta para agrupar los votos por número de estrellas
        $query = $db->table($this->table)
            ->select('valor, COUNT(*) as total')
            ->where('idreferencia', $idreferencia)
            ->groupBy('valor')
            ->get();

        // Inicializamos todas las estrellas con 0
        $result = [
            5 => 0,
            4 => 0,
            3 => 0,
            2 => 0,
            1 => 0
        ];

        foreach ($query->getResult() as $row) {
            $result[$row->valor] = (int) $row->total;
        }

        // Calcular promedio general
        $promedioQuery = $db->table($this->table)
            ->selectAvg('valor', 'promedio')
            ->where('idreferencia', $idreferencia)
            ->get()
            ->getRow();

        $average = round($promedioQuery->promedio ?? 0, 1);

        // Devolvemos el arreglo completo
        return [
            'ratings' => $result,
            'average' => $average
        ];
    }
}
