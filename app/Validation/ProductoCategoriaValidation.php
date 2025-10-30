<?php

namespace App\Validation;

class ProductoCategoriaValidation
{
    public static function productoCategoriaGuardarValidation(array $data): array
    {
        $errors = [];
        $categoriaModel = new \App\Models\ProductoCategoriaModel();

        if (empty($data['estado']['idEstado'])) {
            $errors[] = ["campo" => "estado", "valor" => "Seleccione el estado."];
        }

        if (empty($data['nombre'])) {
            $errors[] = ["campo" => "nombre", "valor" => "Ingrese el nombre."];
        } else {
            $existeNombre = $categoriaModel->where('nombre', $data['nombre'])->first();
            if ($existeNombre) {
                $errors[] = ["campo" => "nombre", "valor" => "El nombre ya está registrado, elija otro."];
            }
        }

        if (empty($data['urlAmigable'])) {
            $errors[] = ["campo" => "urlAmigable", "valor" => "Ingrese la URL amigable."];
        } else {
            $existeUrl = $categoriaModel->where('urlAmigable', $data['urlAmigable'])->first();
            if ($existeUrl) {
                $errors[] = ["campo" => "urlAmigable", "valor" => "La URL amigable ya está registrada, cambie el nombre o URL."];
            }
        }

        return $errors;
    }

    public static function productoCategoriaActualizarValidation(array $data): array
    {
        $errors = [];
        $categoriaModel = new \App\Models\ProductoCategoriaModel();

        if (empty($data['estado']['idEstado'])) {
            $errors[] = ["campo" => "estado", "valor" => "Seleccione el estado."];
        }

        if (empty($data['nombre'])) {
            $errors[] = ["campo" => "nombre", "valor" => "Ingrese el nombre."];
        } else {
            $existeNombre = $categoriaModel
                ->where('nombre', $data['nombre'])
                ->where('idProductoCategoria !=', $data['idProductoCategoria'])
                ->first();
            if ($existeNombre) {
                $errors[] = ["campo" => "nombre", "valor" => "El nombre ya está registrado por otra categoría."];
            }
        }

        if (empty($data['urlAmigable'])) {
            $errors[] = ["campo" => "urlAmigable", "valor" => "Ingrese la URL amigable."];
        } else {
            $existeUrl = $categoriaModel
                ->where('urlAmigable', $data['urlAmigable'])
                ->where('idProductoCategoria !=', $data['idProductoCategoria'])
                ->first();
            if ($existeUrl) {
                $errors[] = ["campo" => "urlAmigable", "valor" => "La URL amigable ya está registrada por otra categoría."];
            }
        }

        return $errors;
    }
}
