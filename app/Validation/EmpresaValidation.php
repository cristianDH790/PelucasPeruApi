<?php

namespace App\Validation;

class EmpresaValidation
{
    public static function empresaGuardarValidation(array $data): array
    {
        $errors = [];

        if (empty($data['estado']['idEstado'])) {
            $errors[] = ["campo" => "estado", "valor" => "Ingrese el estado."];
        }
        if (empty($data['nombre'])) {
            $errors[] = ["campo" => "nombre", "valor" => "Ingrese el nombre."];
        }
        if (empty($data['razonSocial'])) {
            $errors[] = ["campo" => "razonsocial", "valor" => "Ingrese la razon social."];
        }
        if (empty($data['ruc'])) {
            $errors[] = ["campo" => "ruc", "valor" => "Ingrese el ruc."];
        }





        return $errors;
    }

    public static function empresaActualizarValidation(array $data): array
    {
        $errors = [];


        if (empty($data['estado']['idEstado'])) {
            $errors[] = ["campo" => "estado", "valor" => "Ingrese el estado."];
        }
        if (empty($data['nombre'])) {
            $errors[] = ["campo" => "nombre", "valor" => "Ingrese el nombre."];
        }
        if (empty($data['razonSocial'])) {
            $errors[] = ["campo" => "razonsocial", "valor" => "Ingrese la razon social."];
        }
        if (empty($data['ruc'])) {
            $errors[] = ["campo" => "ruc", "valor" => "Ingrese el ruc."];
        }


        return $errors;
    }
}
