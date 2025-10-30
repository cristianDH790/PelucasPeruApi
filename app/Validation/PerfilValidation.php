<?php

namespace App\Validation;

class PerfilValidation
{
    public static function perfilGuardarValidation(array $data): array
    {
        $errors = [];

        if (empty($data['estado']['idestado'])) {
            $errors[] = ["campo" => "estado", "valor" => "Ingrese el estado."];
        }
        if (empty($data['nombre'])) {
            $errors[] = ["campo" => "nombre", "valor" => "Ingrese el nombre."];
        }
        if (empty($data['abr'])) {
            $errors[] = ["campo" => "abr", "valor" => "Ingrese la abreviatura."];
        }




        return $errors;
    }

    public static function perfilActualizarValidation(array $data): array
    {
        $errors = [];


        if (empty($data['estado']['idestado'])) {
            $errors[] = ["campo" => "estado", "valor" => "Ingrese el estado."];
        }
        if (empty($data['nombre'])) {
            $errors[] = ["campo" => "nombre", "valor" => "Ingrese el nombre."];
        }
        if (empty($data['abr'])) {
            $errors[] = ["campo" => "abr", "valor" => "Ingrese la abreviatura."];
        }


        return $errors;
    }
}
