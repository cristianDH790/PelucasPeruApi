<?php

namespace App\Validation;

class SliderValidation
{
    public static function SliderGuardarValidation(array $data): array
    {
        $errors = [];

        if (empty($data['estado']['idEstado'])) {
            $errors[] = ["campo" => "estado", "valor" => "Seleccione el estado."];
        }
        if (empty($data['nombre'])) {
            $errors[] = ["campo" => "nombre", "valor" => "Ingrese el nombre."];
        }
        if (empty($data['orden'])) {
            $errors[] = ["campo" => "orden", "valor" => "Ingrese el orden."];
        }

        // if (empty($data['pCategoria']['idParametro'])) {
        //     $errors[] = ["campo" => "idpcategoria", "valor" => "Seleccione la la ubicacion."];
        // }
        if (empty($data['pRecurso']['idParametro'])) {
            $errors[] = ["campo" => "idptiporecurso", "valor" => "Seleccione el tipo recurso."];
        }
        if (empty($data['rProductoCategoria']['idProductoCategoria'])) {
            $errors[] = ["campo" => "productoCategoria", "valor" => "Seleccione el tipo de categoria."];
        }






        return $errors;
    }

    public static function SliderActualizarValidation(array $data): array
    {
        $errors = [];

        if (empty($data['estado']['idEstado'])) {
            $errors[] = ["campo" => "estado", "valor" => "Seleccione el estado."];
        }
        if (empty($data['nombre'])) {
            $errors[] = ["campo" => "nombre", "valor" => "Ingrese el nombre."];
        }
        if (empty($data['orden'])) {
            $errors[] = ["campo" => "orden", "valor" => "Ingrese el orden."];
        }

        // if (empty($data['pCategoria']['idParametro'])) {
        //     $errors[] = ["campo" => "idpcategoria", "valor" => "Seleccione la la ubicacion."];
        // }
        if (empty($data['pRecurso']['idParametro'])) {
            $errors[] = ["campo" => "idptiporecurso", "valor" => "Seleccione el tipo recurso."];
        }

        if (empty($data['rProductoCategoria']['idProductoCategoria'])) {
            $errors[] = ["campo" => "productoCategoria", "valor" => "Seleccione el tipo de categoria."];
        }

        return $errors;
    }
}
