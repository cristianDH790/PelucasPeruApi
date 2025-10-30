<?php

namespace App\Validation;

class SedeValidation
{
    public static function sedeGuardarValidation(array $data): array
    {
        $errors = [];

        if (empty($data['estado']['idEstado'])) {
            $errors[] = ["campo" => "estado", "valor" => "Ingrese el estado."];
        }
        if (empty($data['empresa']['idEmpresa'])) {
            $errors[] = ["campo" => "empresa", "valor" => "Ingrese la empresa."];
        }
        if (empty($data['ubigeo'])) {
            $errors[] = ["campo" => "ubigeo", "valor" => "Ingrese el ubigeo."];
        }
        if (empty($data['nombre'])) {
            $errors[] = ["campo" => "nombre", "valor" => "Ingrese el nombre."];
        }
        if (empty($data['urlCabecera'])) {
            $errors[] = ["campo" => "urlcabecera", "valor" => "Ingrese la url cabecera."];
        }




        return $errors;
    }

    public static function sedeActualizarValidation(array $data): array
    {
        $errors = [];


        if (empty($data['estado']['idEstado'])) {
            $errors[] = ["campo" => "estado", "valor" => "Ingrese el estado."];
        }
        if (empty($data['empresa']['idEmpresa'])) {
            $errors[] = ["campo" => "empresa", "valor" => "Ingrese la empresa."];
        }
        if (empty($data['ubigeo'])) {
            $errors[] = ["campo" => "ubigeo", "valor" => "Ingrese el ubigeo."];
        }
        if (empty($data['nombre'])) {
            $errors[] = ["campo" => "nombre", "valor" => "Ingrese el nombre."];
        }
        if (empty($data['urlCabecera'])) {
            $errors[] = ["campo" => "urlcabecera", "valor" => "Ingrese la url cabecera."];
        }

        return $errors;
    }
}
