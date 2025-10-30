<?php

namespace App\Validation;

class UbigeoValidation
{
    public static function ubigeoGuardarValidation(array $data): array
    {
        $errors = [];

        if (empty($data['correo'])) {
            $errors[] = ["campo" => "correo", "valor" => "Ingrese el correo."];
        }
        if (empty($data['estado']['idEstado'])) {
            $errors[] = ["campo" => "estado", "valor" => "Ingrese el estado."];
        }
        
        return $errors;
    }

    public static function ubigeoActualizarValidation(array $data): array
    {
        $errors = [];

        if (empty($data['correo'])) {
            $errors[] = ["campo" => "correo", "valor" => "Ingrese el correo."];
        }
        if (empty($data['estado']['idEstado'])) {
            $errors[] = ["campo" => "estado", "valor" => "Ingrese el estado."];
        }

        return $errors;
    }
}
