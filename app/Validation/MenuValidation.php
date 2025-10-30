<?php

namespace App\Validation;

class MenuValidation
{
    public static function menuGuardarValidation(array $data): array
    {
        $errors = [];

        if (empty($data['estado']['idEstado'])) {
            $errors[] = ["campo" => "estado", "valor" => "Ingrese el estado."];
        }
        if (empty($data['pDestino']['idParametro'])) {
            $errors[] = ["campo" => "pdestino", "valor" => "Ingrese el destino."];
        }
        if (empty($data['pUbicacion']['idParametro'])) {
            $errors[] = ["campo" => "pubicacion", "valor" => "Ingrese la ubicacion."];
        }
        if (empty($data['pTipo']['idParametro'])) {
            $errors[] = ["campo" => "ptipo", "valor" => "Ingrese el tipo."];
        }


        if (empty($data['destino'])) {
            $errors[] = ["campo" => "destino", "valor" => "Ingrese una url de redireccion."];
        }
        if (empty($data['nombre'])) {
            $errors[] = ["campo" => "nombre", "valor" => "Ingrese el nombre."];
        }







        return $errors;
    }

    public static function menuActualizarValidation(array $data): array
    {
        $errors = [];

        if (empty($data['estado']['idEstado'])) {
            $errors[] = ["campo" => "estado", "valor" => "Ingrese el estado."];
        }
        if (empty($data['pDestino']['idParametro'])) {
            $errors[] = ["campo" => "pdestino", "valor" => "Ingrese el destino."];
        }
        if (empty($data['pUbicacion']['idParametro'])) {
            $errors[] = ["campo" => "pubicacion", "valor" => "Ingrese la ubicacion."];
        }
        if (empty($data['pTipo']['idParametro'])) {
            $errors[] = ["campo" => "ptipo", "valor" => "Ingrese el tipo."];
        }


        if (empty($data['destino'])) {
            $errors[] = ["campo" => "destino", "valor" => "Ingrese una url de redireccion."];
        }
        if (empty($data['nombre'])) {
            $errors[] = ["campo" => "nombre", "valor" => "Ingrese el nombre."];
        }

        return $errors;
    }
}
