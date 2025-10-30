<?php

namespace App\Validation;

class ProductoImagenValidation
{
    public static function productoImagenGuardarValidation(array $data): array
    {
        $errors = [];

        if (empty($data['estado']['idEstado'])) {
            $errors[] = ["campo" => "estado", "valor" => "Ingrese el estado."];
        }

        if (empty($data['pDestacado']['idParametro'])) {
            $errors[] = ["campo" => "ptipo", "valor" => "Ingrese el tipo."];
        }
        if (empty($data['nombre'])) {
            $errors[] = ["campo" => "nombre", "valor" => "Ingrese el nombre."];
        }
        if (empty($data['urlImagen'])) {
            $errors[] = ["campo" => "urlImagen", "valor" => "Ingresa imagen."];
        }
        // if (empty($data['productoColor']['idProductoColor'])) {
        //     $errors[] = ["campo" => "productocolor", "valor" => "Ingresa imagen."];
        // }





        return $errors;
    }

    public static function productoImagenActualizarValidation(array $data): array
    {
        $errors = [];

        if (empty($data['estado']['idEstado'])) {
            $errors[] = ["campo" => "estado", "valor" => "Ingrese el estado."];
        }

        if (empty($data['pDestacado']['idParametro'])) {
            $errors[] = ["campo" => "ptipo", "valor" => "Ingrese el tipo."];
        }
        if (empty($data['nombre'])) {
            $errors[] = ["campo" => "nombre", "valor" => "Ingrese el nombre."];
        }
        if (empty($data['urlImagen'])) {
            $errors[] = ["campo" => "urlImagen", "valor" => "Ingresa imagen."];
        }
        // if (empty($data['productoColor']['idProductoColor'])) {
        //     $errors[] = ["campo" => "productocolor", "valor" => "Ingresa imagen."];
        // }

        return $errors;
    }
}
