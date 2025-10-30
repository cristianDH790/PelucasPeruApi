<?php

namespace App\Validation;

class ContenidoWebValidation
{
    public static function ContenidoWebGuardarValidation(array $data): array
    {
        $errors = [];

        if (empty($data['estado']['idEstado'])) {
            $errors[] = ["campo" => "estado", "valor" => "Seleccione el estado."];
        }

        if (empty($data['contenidoWebCategoria']['idContenidoWebCategoria'])) {
            $errors[] = ["campo" => "contenidoWebCategoria", "valor" => "Seleccione la categoría."];
        }
        if (empty($data['pTipo']['idParametro'])) {
            $errors[] = ["campo" => "pTipo", "valor" => "Seleccione la categoría."];
        }

        if (empty($data['nombre'])) {
            $errors[] = ["campo" => "nombre", "valor" => "Ingrese el nombre."];
        }

        if ($data['pTipo']['idParametro'] != 418) {
            if (empty($data['urlAmigable'])) {
                $errors[] = ["campo" => "urlamigable", "valor" => "Ingrese la URL amigable."];
            }
        }






        return $errors;
    }

    public static function ContenidoWebActualizarValidation(array $data): array
    {
        $errors = [];
        if (empty($data['estado']['idEstado'])) {
            $errors[] = ["campo" => "estado", "valor" => "Seleccione el estado."];
        }

        if (empty($data['contenidoWebCategoria']['idContenidoWebCategoria'])) {
            $errors[] = ["campo" => "contenidoWebCategoria", "valor" => "Seleccione la categoría."];
        }
        if (empty($data['pTipo']['idParametro'])) {
            $errors[] = ["campo" => "pTipo", "valor" => "Seleccione la categoría."];
        }

        if (empty($data['nombre'])) {
            $errors[] = ["campo" => "nombre", "valor" => "Ingrese el nombre."];
        }

        if ($data['pTipo']['idParametro'] != 418) {
            if (empty($data['urlAmigable'])) {
                $errors[] = ["campo" => "urlamigable", "valor" => "Ingrese la URL amigable."];
            }
        }
        return $errors;
    }
}
