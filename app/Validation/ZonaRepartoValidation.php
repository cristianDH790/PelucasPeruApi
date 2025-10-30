<?php

namespace App\Validation;

class ZonaRepartoValidation
{
    public static function zonaRepartoGuardarValidation(array $data): array
    {
        $errors = [];

        if (empty($data['estado']['idEstado'])) {
            $errors[] = ["campo" => "estado", "valor" => "Ingrese el estado."];
        }

        if (empty($data['nombre'])) {
            $errors[] = ["campo" => "nombre", "valor" => "Ingrese el nombre."];
        }
        if (empty($data['costo'])) {
            $errors[] = ["campo" => "costo", "valor" => "Ingrese el costo."];
        }




        return $errors;
    }

    public static function zonaRepartoActualizarValidation(array $data): array
    {
        $errors = [];


        if (empty($data['estado']['idEstado'])) {
            $errors[] = ["campo" => "estado", "valor" => "Ingrese el estado."];
        }

        if (empty($data['nombre'])) {
            $errors[] = ["campo" => "nombre", "valor" => "Ingrese el nombre."];
        }
        if (empty($data['costo'])) {
            $errors[] = ["campo" => "costo", "valor" => "Ingrese el costo."];
        }



        return $errors;
    }
}
