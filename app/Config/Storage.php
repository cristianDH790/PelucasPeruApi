<?php

namespace Config;

class Storage
{
    public string $archivosRoot;
    public string $archivosUrl;
    public string $visibility = 'public';

    public function __construct()
    {
        // Asegúrate de que estas variables estén definidas en .env
        $this->archivosRoot = FCPATH . getenv('URL_IMAGE'); // ruta/archivos
        $this->archivosUrl  = getenv('FRONT_SITE') . 'archivos/'; //https://apetac.superhostingdelta.com/archivos/
    }
}
