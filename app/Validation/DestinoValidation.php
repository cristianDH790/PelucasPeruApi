<?php

namespace App\Validation;

class DestinoValidation
{
    public static function destinoGuardarValidation(array $data): array
    {
        $errors = [];

        if (empty($data['estado']['idEstado'])) {
            $errors[] = ["campo" => "estado", "valor" => "Ingrese el estado."];
        }
        if (empty($data['ubigeo']['idUbigeo'])) {
            $errors[] = ["campo" => "estado", "valor" => "Ingrese el ubigeo."];
        }
        if (empty($data['usuario']['idUsuario'])) {
            $errors[] = ["campo" => "usuario", "valor" => "Ingrese el usuario."];
        }
        if (empty($data['pTipo']['idParametro'])) {
            $errors[] = ["campo" => "pTipo", "valor" => "Ingrese el tipo."];
        }
        if (empty($data['alias'])) {
            $errors[] = ["campo" => "alias", "valor" => "Ingrese el alias."];
        }





        return $errors;
    }

    public static function destinoActualizarValidation(array $data): array
    {
        $errors = [];


        if (empty($data['estado']['idEstado'])) {
            $errors[] = ["campo" => "estado", "valor" => "Ingrese el estado."];
        }
        if (empty($data['ubigeo']['idUbigeo'])) {
            $errors[] = ["campo" => "estado", "valor" => "Ingrese el ubigeo."];
        }
        if (empty($data['usuario']['idUsuario'])) {
            $errors[] = ["campo" => "usuario", "valor" => "Ingrese el usuario."];
        }
        if (empty($data['pTipo']['idParametro'])) {
            $errors[] = ["campo" => "pTipo", "valor" => "Ingrese el tipo."];
        }
        if (empty($data['alias'])) {
            $errors[] = ["campo" => "alias", "valor" => "Ingrese el alias."];
        }



        return $errors;
    }
}
