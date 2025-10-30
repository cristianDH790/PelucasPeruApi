<?php

namespace App\Validation;

class NoticiaCategoriaValidation
{
    public static function NoticiaCategoriaGuardarValidation(array $data): array
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

        if (empty($data['urlAmigable'])) {
            $errors[] = ["campo" => "urlamigable", "valor" => "Ingrese la URL amigable."];
        }



        return $errors;
    }

    public static function NoticiaCategoriaActualizarValidation(array $data): array
    {
        $errors = [];

        if (empty($data['estado']['idEstado'])) {
            $errors[] = ["campo" => "estado", "valor" => "Seleccione el estado."];
        }
        if (empty($data['orden'])) {
            $errors[] = ["campo" => "orden", "valor" => "Ingrese el orden."];
        }
        if (empty($data['nombre'])) {
            $errors[] = ["campo" => "nombre", "valor" => "Ingrese el nombre."];
        }

        if (empty($data['urlAmigable'])) {
            $errors[] = ["campo" => "urlamigable", "valor" => "Ingrese la URL amigable."];
        }
        return $errors;
    }
}
