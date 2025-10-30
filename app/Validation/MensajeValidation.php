<?php

namespace App\Validation;

class MensajeValidation
{
   public static function MensajeGuardarValidation(array $data): array
    {
        $errors = [];

        if (empty($data['estado']['idEstado'])) {
            $errors[] = ["campo" => "estado", "valor" => "Seleccione el estado."];
        }

        if (empty($data['clase']['idClase'])) {
            $errors[] = ["campo" => "clase", "valor" => "Seleccione la clase."];
        }

        if (empty($data['nombre'])) {
            $errors[] = ["campo" => "nombre", "valor" => "Ingrese el nombre."];
        }

        if (empty($data['asunto'])) {
            $errors[] = ["campo" => "asunto", "valor" => "Ingrese el asunto."];
        }

        if (empty($data['contenido'])) {
            $errors[] = ["campo" => "contenido", "valor" => "Ingrese el contenido."];
        }

        return $errors;
    }

    public static function MensajeActualizarValidation(array $data): array
    {
        $errors = [];


        if (empty($data['estado']['idEstado'])) {
            $errors[] = ["campo" => "estado", "valor" => "Seleccione el estado."];
        }

        if (empty($data['clase']['idClase'])) {
            $errors[] = ["campo" => "clase", "valor" => "Seleccione la clase."];
        }

        if (empty($data['nombre'])) {
            $errors[] = ["campo" => "nombre", "valor" => "Ingrese el nombre."];
        }

        if (empty($data['asunto'])) {
            $errors[] = ["campo" => "asunto", "valor" => "Ingrese el asunto."];
        }
        if (empty($data['contenido'])) {
            $errors[] = ["campo" => "contenido", "valor" => "Ingrese el contenido."];
        }


        return $errors;
    }
}
