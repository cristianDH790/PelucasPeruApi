<?php

namespace App\Validation;

class ColorValidation
{
    public static function colorGuardarValidation(array $data): array
    {
        $errors = [];

        if (empty($data['estado']['idEstado'])) {
            $errors[] = ["campo" => "estado", "valor" => "Ingrese el estado."];
        }

        if (empty($data['nombre'])) {
            $errors[] = ["campo" => "nombre", "valor" => "Ingrese el nombre del color."];
        }

        if (empty($data['codigo'])) {
            $errors[] = ["campo" => "codigo", "valor" => "Ingrese el código del color."];
        }

        return $errors;
    }

    public static function colorActualizarValidation(array $data): array
    {
        $errors = [];

        if (empty($data['estado']['idEstado'])) {
            $errors[] = ["campo" => "estado", "valor" => "Ingrese el estado."];
        }

        if (empty($data['nombre'])) {
            $errors[] = ["campo" => "nombre", "valor" => "Ingrese el nombre del color."];
        }

        if (empty($data['codigo'])) {
            $errors[] = ["campo" => "codigo", "valor" => "Ingrese el código del color."];
        }

        return $errors;
    }
}
