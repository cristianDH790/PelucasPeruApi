<?php

namespace App\Validation;

class ContenidoWebCategoriaValidation
{
    public static function contenidoWebCategoriaGuardarValidation(array $data): array
    {
        $errors = [];

        if (empty($data['estado']['idEstado'])) {
            $errors[] = ["campo" => "estado", "valor" => "Seleccione el estado."];
        }

        if (empty($data['nombre'])) {
            $errors[] = ["campo" => "nombre", "valor" => "Ingrese el nombre."];
        }
        // if (empty($data['urlAmigable'])) {
        //     $errors[] = ["campo" => "urlamigable", "valor" => "Ingrese la urlamigable."];
        // }
        if (empty($data['orden'])) {
            $errors[] = ["campo" => "orden", "valor" => "Ingrese el orden."];
        }






        return $errors;
    }

    public static function contenidoWebCategoriaActualizarValidation(array $data): array
    {
        $errors = [];
        if (empty($data['estado']['idEstado'])) {
            $errors[] = ["campo" => "estado", "valor" => "Seleccione el estado."];
        }

        if (empty($data['nombre'])) {
            $errors[] = ["campo" => "nombre", "valor" => "Ingrese el nombre."];
        }
        if (empty($data['orden'])) {
            $errors[] = ["campo" => "orden", "valor" => "Ingrese el orden."];
        }
        // if (empty($data['urlAmigable'])) {
        //     $errors[] = ["campo" => "urlamigable", "valor" => "Ingrese la urlamigable."];
        // }

        return $errors;
    }
}
