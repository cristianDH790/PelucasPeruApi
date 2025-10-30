<?php

namespace App\Validation;

class SuscripcionValidation
{
    public static function suscripcionGuardarValidation(array $data): array
    {
        $errors = [];

        if (empty($data['correo'])) {
            $errors[] = ["campo" => "correo", "valor" => "Ingrese el correo."];
        }




        return $errors;
    }

    public static function suscripcionActualizarValidation(array $data): array
    {
        $errors = [];

    
        if (empty($data['correo'])) {
            $errors[] = ["campo" => "correo", "valor" => "Ingrese el correo."];
        }


        return $errors;
    }
}
