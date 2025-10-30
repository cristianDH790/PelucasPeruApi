<?php

namespace App\Models;

use App\Entities\ListaDeseo;
use App\Entities\ListaDeseoEntity;
use CodeIgniter\Model;

class ListaDeseoModel extends Model
{
    protected $table      = 'listadeseo';
    protected $primaryKey = 'idlistadeseo';

    protected $returnType = ListaDeseoEntity::class;
    protected $allowedFields = [
        'idestado',
        'idproductocolor',
        'idusuario',
        'fecha'
    ];

    public function obtenerById($idlistadeseo)
    {
        return $this->find($idlistadeseo);
    }

    public function obtenerByIdProductoAndIdUsuario($idproductocolor, $idusuario, $idestado = 0)
    {
        $this->where('idproductocolor', $idproductocolor)
            ->where('idusuario', $idusuario);

        if ($idestado > 0) {
            $this->where('idestado', $idestado);
        }

        return $this->first();
    }

    public function guardar($data)
    {
        if (empty($data['idlistadeseo'])) {
            $this->insert($data);
            return $this->find($this->insertID());
        } else {
            $this->update($data['idlistadeseo'], $data);
            return $this->find($data['idlistadeseo']);
        }
    }

    public function buscarPor($ordencriterio, $ordentipo, $parametro, $valor, $idestado, $idproducto, $idusuario, $inicio, $registros)
    {
        $this->select('listadeseo.*, 
                       (SELECT nombre FROM estado e WHERE listadeseo.idestado = e.idestado) AS estado,
                       producto.idestado AS producto_idestado,
                       producto.preciolista AS producto_precioLista,
                       producto.precioventa AS producto_precioVenta,
                       producto.nombre AS producto_nombre,
                       (SELECT urlimagen FROM productoimagen WHERE idproductocolor = listadeseo.idproductocolor AND idestado = 346 ORDER BY orden ASC LIMIT 1) AS producto_urlImagen,
                       productocolor.urlamigable AS producto_urlAmigable,
                       (SELECT pc.nombre FROM productocategoria pc 
                        INNER JOIN producto ON producto.idproducto = 
                          (SELECT idproducto FROM productocolor WHERE idproductocolor = listadeseo.idproductocolor LIMIT 1) 
                        LIMIT 1) AS producto_categoria')
            ->join('productocolor', 'productocolor.idproductocolor = listadeseo.idproductocolor')
            ->join('producto', 'producto.idproducto = productocolor.idproducto');

        if (!empty($ordencriterio) && !empty($ordentipo)) {
            $this->orderBy($ordencriterio, $ordentipo);
        }

        if (!empty($parametro) && !empty($valor)) {
            $this->like($parametro, $valor);
        }

        if ($idestado > 0) {
            $this->where('listadeseo.idestado', $idestado);
        }

        if ($idproducto > 0) {
            $this->where('listadeseo.idproducto', $idproducto);
        }

        if ($idusuario > 0) {
            $this->where('listadeseo.idusuario', $idusuario);
        }

        return $this->findAll($registros, $inicio);
    }

    public function buscarTotalPor($parametro, $valor, $idestado, $idproducto, $idusuario)
    {
        if (!empty($parametro) && !empty($valor)) {
            $this->like($parametro, $valor);
        }

        if ($idestado > 0) {
            $this->where('idestado', $idestado);
        }

        if ($idproducto > 0) {
            $this->where('idproducto', $idproducto);
        }

        if ($idusuario > 0) {
            $this->where('idusuario', $idusuario);
        }

        return $this->countAllResults();
    }

    public function eliminarDeseo($iddeseo)
    {
        return $this->delete($iddeseo);
    }
}
