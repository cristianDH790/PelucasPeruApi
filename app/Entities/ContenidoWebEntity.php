<?php

namespace App\Entities;


class ContenidoWebEntity
{
    // Propiedades principales (campos directos de la tabla)
    public $idcontenidoweb;
    public $idestado;
    public $idcontenidowebcategoria;
    public $idptipo;
    public $nombre;
    public $urlamigable;
    public $urlimagen;
    public $resumen;
    public $contenido;
    public $seccion;
    public $urlbanner;
    public $orden;
    public $tituloseo;
    public $descripcionseo;
    public $palabrasclaveseo;
    public $fecha;

    // Relaciones (deben cargarse externamente)
    public $categoria;
    public $ptipo;
    public $estado;

    /**
     * Constructor - Hidrata la entidad desde un array
     */
    public function __construct($data = null)
    {
        if ($data) {
            if (is_array($data)) {
                $this->idcontenidoweb = $data['idcontenidoweb'] ?? null;
                $this->idestado = $data['idestado'] ?? null;
                $this->idcontenidowebcategoria = $data['idcontenidowebcategoria'] ?? null;
                $this->idptipo = $data['idptipo'] ?? null;
                $this->nombre = $data['nombre'] ?? null;
                $this->urlamigable = $data['urlamigable'] ?? null;
                $this->urlimagen = $data['urlimagen'] ?? null;
                $this->resumen = $data['resumen'] ?? null;
                $this->contenido = $data['contenido'] ?? null;
                $this->seccion = $data['seccion'] ?? null;
                $this->urlbanner = $data['urlbanner'] ?? null;
                $this->orden = $data['orden'] ?? null;
                $this->tituloseo = $data['tituloSeo'] ?? null;
                $this->descripcionseo = $data['descripcionseo'] ?? null;
                $this->palabrasclaveseo = $data['palabrasclaveseo'] ?? null;
                $this->fecha = $data['fecha'] ?? null;
            } elseif (is_object($data)) {
                $this->idcontenidoweb = $data->idcontenidoweb ?? null;
                $this->idestado = $data->idestado ?? null;
                $this->idcontenidowebcategoria = $data->idcontenidowebcategoria ?? null;
                $this->idptipo = $data->idptipo ?? null;
                $this->nombre = $data->nombre ?? null;
                $this->urlamigable = $data->urlamigable ?? null;
                $this->urlimagen = $data->urlimagen ?? null;
                $this->resumen = $data->resumen ?? null;
                $this->contenido = $data->contenido ?? null;
                $this->seccion = $data->seccion ?? null;
                $this->urlbanner = $data->urlbanner ?? null;
                $this->orden = $data->orden ?? null;
                $this->tituloseo = $data->tituloseo ?? null;
                $this->descripcionseo = $data->descripcionseo ?? null;
                $this->palabrasclaveseo = $data->palabrasclaveseo ?? null;
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
        $data = [
            'idContenidoWeb' => $this->idcontenidoweb,
            'idEstado' => $this->idestado,
            'idContenidoWebCategoria' => $this->idcontenidowebcategoria,
            'idPtipo' => $this->idptipo,
            'nombre' => $this->nombre,
            'urlAmigable' => $this->urlamigable,
            'urlImagen' => $this->urlimagen,
            'resumen' => $this->resumen,
            'contenido' => $this->contenido,
            'seccion' => $this->seccion,
            'urlBanner' => $this->urlbanner,
            'orden' => $this->orden,
            'tituloSeo' => $this->tituloseo,
            'descripcionSeo' => $this->descripcionseo,
            'palabrasClaveSeo' => $this->palabrasclaveseo,
            'fecha' => $this->fecha,
        ];

        // Relaciones (solo si están cargadas)
        if ($this->categoria !== null) {
            $data['contenidoWebCategoria'] = $this->categoria->toArray();
        }
        if ($this->estado !== null) {
            $data['estado'] = $this->estado->toArray();
        }
        if ($this->ptipo !== null) {
            $data['pTipo'] = $this->ptipo->toArray();
        }

        return $data;
    }
}
