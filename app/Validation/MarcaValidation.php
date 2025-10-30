<?php

namespace App\Validation;

class MarcaValidation
{
    public static function marcaGuardarValidation(array $data): array
    {
        $errors = [];

        if (empty($data['estado']['idEstado'])) {
            $errors[] = ["campo" => "estado", "valor" => "Ingrese el estado."];
        }

        if (empty($data['nombre'])) {
            $errors[] = ["campo" => "nombre", "valor" => "Ingrese el nombre."];
        }
       
      





        return $errors;
    }

    public static function marcaActualizarValidation(array $data): array
    {
        $errors = [];

        if (empty($data['estado']['idEstado'])) {
            $errors[] = ["campo" => "estado", "valor" => "Ingrese el estado."];
        }

        if (empty($data['nombre'])) {
            $errors[] = ["campo" => "nombre", "valor" => "Ingrese el nombre."];
        }
       
      

        return $errors;
    }
}
