<?php

namespace App\Validation;

class ProductoColorValidation
{
    public static function productoColorGuardarValidation(array $data): array
    {
        $errors = [];

        if (empty($data['estado']['idEstado'])) {
            $errors[] = ["campo" => "estado", "valor" => "Ingrese el estado."];
        }

        if (empty($data['producto']['idProducto'])) {
            $errors[] = ["campo" => "producto", "valor" => "Ingrese el producto."];
        }

        if (empty($data['color']['idColor'])) {
            $errors[] = ["campo" => "color", "valor" => "Ingrese el color."];
        }

        if (empty($data['nombre'])) {
            $errors[] = ["campo" => "nombre", "valor" => "Ingrese el nombre del producto color."];
        }
        if (empty($data['stock'])) {
            $errors[] = ["campo" => "stock", "valor" => "Ingrese el stock"];
        }

        return $errors;
    }

    public static function productoColorActualizarValidation(array $data): array
    {
        $errors = [];

        if (empty($data['estado']['idEstado'])) {
            $errors[] = ["campo" => "estado", "valor" => "Ingrese el estado."];
        }

        if (empty($data['producto']['idProducto'])) {
            $errors[] = ["campo" => "producto", "valor" => "Ingrese el producto."];
        }

        if (empty($data['color']['idColor'])) {
            $errors[] = ["campo" => "color", "valor" => "Ingrese el color."];
        }

        if (empty($data['nombre'])) {
            $errors[] = ["campo" => "nombre", "valor" => "Ingrese el nombre del producto color."];
        }
        if (empty($data['stock'])) {
            $errors[] = ["campo" => "stock", "valor" => "Ingrese el stock"];
        }
        return $errors;
    }
}
