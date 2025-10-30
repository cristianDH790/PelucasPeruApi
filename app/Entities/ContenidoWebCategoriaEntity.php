<?php

namespace App\Entities;

use CodeIgniter\Entity\Entity;

class ContenidoWebCategoriaEntity
{

    // Propiedades principales (campos directos de la tabla)
    public $idcontenidowebcategoria;
    public $idestado;
    public $nombre;
    public $orden;
    public $urlamigable;
    public $descripcionseo;


    public $idrcontenidowebcategoria;
    public $miniatura;
    public $banner;
    public $fecha;


    // Relaciones (deben cargarse externamente)
    public $rcontenidowebcategoria;
    public $estado;





    /**
     * Constructor - Hidrata la entidad desde un array
     */
    public function __construct($data = null)
    {
        if ($data) {
            if (is_array($data)) {
                $this->idcontenidowebcategoria = $data['idcontenidowebcategoria'] ?? null;
                $this->idestado = $data['idestado'] ?? null;
                $this->nombre = $data['nombre'] ?? null;
                $this->orden = $data['orden'] ?? null;
                $this->urlamigable = $data['urlamigable'] ?? null;
                $this->descripcionseo = $data['descripcionseo'] ?? null;

                $this->idrcontenidowebcategoria = $data['idrcontenidowebcategoria'] ?? null;
                $this->miniatura = $data['miniatura'] ?? null;
                $this->banner = $data['banner'] ?? null;
                $this->fecha = $data['fecha'] ?? null;
            } elseif (is_object($data)) {
                $this->idcontenidowebcategoria = $data->idcontenidowebcategoria ?? null;
                $this->idestado = $data->idestado ?? null;
                $this->banner = $data->banner ?? null;
                $this->nombre = $data->nombre ?? null;
                $this->urlamigable = $data->urlamigable ?? null;
                $this->descripcionseo = $data->descripcionseo ?? null;

                $this->idrcontenidowebcategoria = $data->idrcontenidowebcategoria ?? null;
                $this->miniatura = $data->miniatura ?? null;
                $this->orden = $data->orden ?? null;
                $this->fecha = $data->fecha ?? null;
            }
        }
    }

    /**
     * Convierte la entidad a array, incluyendo relaciones si existen
     * @return array
     */
    public function toArray(): array
    {
        // Datos base del asociado
        $data = [
            'idContenidoWebCategoria' => $this->idcontenidowebcategoria,
            'idEstado' => $this->estado,
            'nombre' => $this->nombre,
            'orden' => $this->orden,
            'urlAmigable' => $this->urlamigable,
            'descripcionSeo' => $this->descripcionseo,


            'idrContenidoWebCategoria' => $this->idrcontenidowebcategoria,
            'miniatura' => $this->miniatura,
            'banner' => $this->banner,
            'fecha' => $this->fecha,
        ];

        // Relaciones (solo si están cargadas)
        if ($this->rcontenidowebcategoria !== null) {
            $data['rContenidoWebCategoria'] = $this->rcontenidowebcategoria->toArray();
        }
        if ($this->estado !== null) {
            $data['estado'] = $this->estado->toArray();
        }
        return $data;
    }
}
