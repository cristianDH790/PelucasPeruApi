<?php

namespace App\Validation;

class FormaPagoValidation
{
    public static function formaPagoGuardarValidation(array $data): array
    {
        $errors = [];

        if (empty($data['estado']['idEstado'])) {
            $errors[] = ["campo" => "estado", "valor" => "Ingrese el estado."];
        }
        // if (empty($data['pTipo']['idParametro'])) {
        //     $errors[] = ["campo" => "pTipo", "valor" => "Ingrese el estado."];
        // }
        // if (empty($data['empresa']['idEmpresa'])) {
        //     $errors[] = ["campo" => "empresa", "valor" => "Ingrese la empresa."];
        // }
        if (empty($data['nombre'])) {
            $errors[] = ["campo" => "nombre", "valor" => "Ingrese el nombre."];
        }
        if (empty($data['abr'])) {
            $errors[] = ["campo" => "abr", "valor" => "Ingresa la abreviatura."];
        }

        // if (empty($data['comision'])) {
        //     $errors[] = ["campo" => "comision", "valor" => "Ingresa la comision."];
        // }



        return $errors;
    }

    public static function formaPagoActualizarValidation(array $data): array
    {
        $errors = [];


        if (empty($data['estado']['idEstado'])) {
            $errors[] = ["campo" => "estado", "valor" => "Ingrese el estado."];
        }
        // if (empty($data['pTipo']['idParametro'])) {
        //     $errors[] = ["campo" => "pTipo", "valor" => "Ingrese el estado."];
        // }
        // if (empty($data['empresa']['idEmpresa'])) {
        //     $errors[] = ["campo" => "empresa", "valor" => "Ingrese la empresa."];
        // }
        if (empty($data['nombre'])) {
            $errors[] = ["campo" => "nombre", "valor" => "Ingrese el nombre."];
        }
        if (empty($data['abr'])) {
            $errors[] = ["campo" => "abr", "valor" => "Ingresa la abreviatura."];
        }
        // if (empty($data['comision'])) {
        //     $errors[] = ["campo" => "comision", "valor" => "Ingresa la comision."];
        // }


        return $errors;
    }
}
