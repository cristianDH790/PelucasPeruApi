<?php

namespace App\Validation;

class NoticiaValidation
{
    public static function NoticiaGuardarValidation(array $data): array
    {
        $errors = [];

        if (empty($data['estado']['idEstado'])) {
            $errors[] = ["campo" => "estado", "valor" => "Seleccione el estado."];
        }


        if (empty($data['noticiaCategoria']['idNoticiaCategoria'])) {
            $errors[] = ["campo" => "noticiacategoria", "valor" => "Seleccione la categoría."];
        }
        if (empty($data['pDestacado']['idParametro'])) {
            $errors[] = ["campo" => "pdestacado", "valor" => "Seleccione el destacado."];
        }

        if (empty($data['nombre'])) {
            $errors[] = ["campo" => "nombre", "valor" => "Ingrese el nombre."];
        }
        if (empty($data['orden'])) {
            $errors[] = ["campo" => "orden", "valor" => "Ingrese el orden."];
        }

        if (empty($data['urlAmigable'])) {
            $errors[] = ["campo" => "urlamigable", "valor" => "Ingrese la URL amigable."];
        }


        if (empty($data['fechaPublicacion'])) {
            $errors[] = ["campo" => "fechapublicacion", "valor" => "Ingrese la fecha de publicación."];
        }

        return $errors;
    }

    public static function NoticiaActualizarValidation(array $data): array
    {
        $errors = [];

        if (empty($data['estado']['idEstado'])) {
            $errors[] = ["campo" => "estado", "valor" => "Seleccione el estado."];
        }


        if (empty($data['noticiaCategoria']['idNoticiaCategoria'])) {
            $errors[] = ["campo" => "noticiacategoria", "valor" => "Seleccione la categoría."];
        }
        if (empty($data['pDestacado']['idParametro'])) {
            $errors[] = ["campo" => "pdestacado", "valor" => "Seleccione el destacado."];
        }

        if (empty($data['nombre'])) {
            $errors[] = ["campo" => "nombre", "valor" => "Ingrese el nombre."];
        }
        if (empty($data['orden'])) {
            $errors[] = ["campo" => "orden", "valor" => "Ingrese el orden."];
        }

        if (empty($data['urlAmigable'])) {
            $errors[] = ["campo" => "urlamigable", "valor" => "Ingrese la URL amigable."];
        }


        if (empty($data['fechaPublicacion'])) {
            $errors[] = ["campo" => "fechapublicacion", "valor" => "Ingrese la fecha de publicación."];
        }

        return $errors;
    }
}
