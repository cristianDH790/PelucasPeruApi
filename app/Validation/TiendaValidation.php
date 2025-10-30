<?php

namespace App\Validation;

class TiendaValidation
{
    public static function tiendaGuardarValidation(array $data): array
    {
        $errors = [];

        if (empty($data['nombre'])) {
            $errors[] = ["campo" => "nombre", "valor" => "Complete."];
        }

        if (empty($data['estado']) || ($data['estado']['idEstado'] ?? 0) == 0) {
            $errors[] = ["campo" => "estado", "valor" => "Seleccione."];
        }

        if (empty($data['ubigeo']) || ($data['ubigeo']['idUbigeo'] ?? 0) == 0) {
            $errors[] = ["campo" => "ubigeo", "valor" => "Seleccione."];
        }

        if (empty($data['latitud'])) {
            $errors[] = ["campo" => "latitud", "valor" => "Complete."];
        }

        if (empty($data['longitud'])) {
            $errors[] = ["campo" => "longitud", "valor" => "Complete."];
        }

        if (!isset($data['orden']) || !is_numeric($data['orden'])) {
            $errors[] = ["campo" => "orden", "valor" => "Valor no válido."];
        }

        if (empty($data['telefono'])) {
            $errors[] = ["campo" => "telefono", "valor" => "Complete."];
        }

        if (empty($data['horaInicio'])) {
            $errors[] = ["campo" => "horaInicio", "valor" => "Complete."];
        }

        if (empty($data['horaTermino'])) {
            $errors[] = ["campo" => "horaTermino", "valor" => "Complete."];
        }

        return $errors;
    }

    public static function tiendaActualizarValidation(array $data): array
    {
        $errors = [];

        if (empty($data['nombre'])) {
            $errors[] = ["campo" => "nombre", "valor" => "Complete."];
        }

        if (empty($data['estado']) || ($data['estado']['idEstado'] ?? 0) == 0) {
            $errors[] = ["campo" => "estado", "valor" => "Seleccione."];
        }

        if (empty($data['ubigeo']) || ($data['ubigeo']['idUbigeo'] ?? 0) == 0) {
            $errors[] = ["campo" => "ubigeo", "valor" => "Seleccione."];
        }

        if (empty($data['latitud'])) {
            $errors[] = ["campo" => "latitud", "valor" => "Complete."];
        }

        if (empty($data['longitud'])) {
            $errors[] = ["campo" => "longitud", "valor" => "Complete."];
        }

        if (!isset($data['orden']) || !is_numeric($data['orden'])) {
            $errors[] = ["campo" => "orden", "valor" => "Valor no válido."];
        }

        if (empty($data['telefono'])) {
            $errors[] = ["campo" => "telefono", "valor" => "Complete."];
        }

        if (empty($data['horaInicio'])) {
            $errors[] = ["campo" => "horaInicio", "valor" => "Complete."];
        }

        if (empty($data['horaTermino'])) {
            $errors[] = ["campo" => "horaTermino", "valor" => "Complete."];
        }

        return $errors;
    }
}
