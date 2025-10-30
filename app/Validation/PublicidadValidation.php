<?php

namespace App\Validation;

class PublicidadValidation
{
    public static function publicidadGuardarValidation(array $data): array
    {
        $errors = [];

        if (empty($data['estado']['idEstado'])) {
            $errors[] = ["campo" => "estado", "valor" => "Ingrese el estado."];
        }
        if (empty($data['destino']['idParametro'])) {
            $errors[] = ["campo" => "destino", "valor" => "Ingrese el estado."];
        }

        if (empty($data['nombre'])) {
            $errors[] = ["campo" => "nombre", "valor" => "Ingrese el nombre."];
        }
        if (empty($data['titulo'])) {
            $errors[] = ["campo" => "titulo", "valor" => "Ingrese el titulo."];
        }







        return $errors;
    }

    public static function publicidadActualizarValidation(array $data): array
    {
        $errors = [];


        if (empty($data['estado']['idEstado'])) {
            $errors[] = ["campo" => "estado", "valor" => "Ingrese el estado."];
        }
        if (empty($data['destino']['idParametro'])) {
            $errors[] = ["campo" => "destino", "valor" => "Ingrese el estado."];
        }

        if (empty($data['nombre'])) {
            $errors[] = ["campo" => "nombre", "valor" => "Ingrese el nombre."];
        }
        if (empty($data['titulo'])) {
            $errors[] = ["campo" => "titulo", "valor" => "Ingrese el titulo."];
        }

        return $errors;
    }
}
