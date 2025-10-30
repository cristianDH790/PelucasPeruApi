<?php

namespace App\Validation;

class PromocionValidation
{
    public static function promocionGuardarValidation(array $data): array
    {
        $errors = [];

        if (empty($data['estado']['idEstado'])) {
            $errors[] = ["campo" => "estado", "valor" => "Ingrese el estado."];
        }
        if (empty($data['nombre'])) {
            $errors[] = ["campo" => "nombre", "valor" => "Ingrese el nombre."];
        }
        if (empty($data['urlAmigable'])) {
            $errors[] = ["campo" => "urlAmigable", "valor" => "Ingrese el url amigable."];
        }





        return $errors;
    }

    public static function promocionActualizarValidation(array $data): array
    {
        $errors = [];


        if (empty($data['estado']['idEstado'])) {
            $errors[] = ["campo" => "estado", "valor" => "Ingrese el estado."];
        }
        if (empty($data['nombre'])) {
            $errors[] = ["campo" => "nombre", "valor" => "Ingrese el nombre."];
        }
        if (empty($data['urlAmigable'])) {
            $errors[] = ["campo" => "urlAmigable", "valor" => "Ingrese el url amigable."];
        }



        return $errors;
    }
}
