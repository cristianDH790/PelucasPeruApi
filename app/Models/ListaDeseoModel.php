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
        'idproducto',
        'idusuario',
        'fecha'
    ];

    public function obtenerById($idlistadeseo)
    {
        return $this->find($idlistadeseo);
    }

    public function obtenerByIdProductoAndIdUsuario($idproducto, $idusuario, $idestado = 0)
    {
        $this->where('idproducto', $idproducto)
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
       estado.nombre AS estado,
       producto.idestado AS producto_idestado,
       producto.preciolista AS producto_precioLista,
       producto.precioventa AS producto_precioVenta,
       producto.nombre AS producto_nombre,
       productocategoria.nombre AS producto_categoria,
       productocategoria.urlamigable AS productocategoria_urlAmigable,
       (SELECT urlimagen FROM productoimagen WHERE idproducto = listadeseo.idproducto AND idestado = 346 ORDER BY orden ASC LIMIT 1) AS producto_urlImagen,
       producto.urlamigable AS producto_urlAmigable')
            ->join('estado', 'estado.idestado = listadeseo.idestado', 'left')
            ->join('producto', 'producto.idproducto = listadeseo.idproducto', 'left')
            ->join('productocategoria', 'productocategoria.idproductocategoria = producto.idproductocategoria', 'left');

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
