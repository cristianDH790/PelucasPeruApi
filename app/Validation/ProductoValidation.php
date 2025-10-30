<?php

namespace App\Validation;

class ProductoValidation
{
    public static function productoGuardarValidation(array $data): array
    {
        $errors = [];
        $productoModel = new \App\Models\ProductoModel();

        // Validaciones obligatorias
        if (empty($data['estado']['idEstado'])) {
            $errors[] = ["campo" => "estado", "valor" => "Ingrese el estado."];
        }
        if (empty($data['productoCategoria']['idProductoCategoria'])) {
            $errors[] = ["campo" => "productocategoria", "valor" => "Ingrese la categoría del producto."];
        }
        if (empty($data['pDestacado']['idParametro'])) {
            $errors[] = ["campo" => "pdestacado", "valor" => "Ingrese destacado."];
        }
        if (empty($data['pComplemento']['idParametro'])) {
            $errors[] = ["campo" => "pcomplemento", "valor" => "Ingrese el producto complemento."];
        }
        // if (empty($data['color']['idColor'])) {
        //     $errors[] = ["campo" => "color", "valor" => "Ingrese el color."];
        // }
        if (empty($data['nombre'])) {
            $errors[] = ["campo" => "nombre", "valor" => "Ingrese el nombre."];
        }
        if (empty($data['urlAmigable'])) {
            $errors[] = ["campo" => "urlamigable", "valor" => "Ingrese la URL amigable."];
        }
        if (empty($data['codigo'])) {
            $errors[] = ["campo" => "codigo", "valor" => "Ingrese el código del producto."];
        }
        if (empty($data['precioLista'])) {
            $errors[] = ["campo" => "preciolista", "valor" => "Ingrese el precio de lista."];
        }
        if (empty($data['precioVenta'])) {
            $errors[] = ["campo" => "precioventa", "valor" => "Ingrese el precio de venta."];
        }
        if (empty($data['fechaPublicacion'])) {
            $errors[] = ["campo" => "fechapublicacion", "valor" => "Ingrese la fecha de publicación."];
        }
        if (empty($data['stock'])) {
            $errors[] = ["campo" => "stock", "valor" => "Ingrese el stock."];
        }

        // Validar unicidad del nombre
        if (!empty($data['nombre'])) {
            $existeNombre = $productoModel->where('nombre', $data['nombre'])->first();
            if ($existeNombre) {
                $errors[] = ["campo" => "nombre", "valor" => "El nombre ya está registrado, elige otro."];
            }
        }

        // Validar unicidad de la URL amigable
        if (!empty($data['urlAmigable'])) {
            $existeUrl = $productoModel->where('urlamigable', $data['urlAmigable'])->first();
            if ($existeUrl) {
                $errors[] = ["campo" => "urlamigable", "valor" => "La URL amigable ya está registrada, cambia el nombre o URL."];
            }
        }

        return $errors;
    }

    public static function productoActualizarValidation(array $data): array
    {
        $errors = [];
        $productoModel = new \App\Models\ProductoModel();

        // Validaciones obligatorias
        if (empty($data['estado']['idEstado'])) {
            $errors[] = ["campo" => "estado", "valor" => "Ingrese el estado."];
        }
        if (empty($data['productoCategoria']['idProductoCategoria'])) {
            $errors[] = ["campo" => "productocategoria", "valor" => "Ingrese la categoría del producto."];
        }
        if (empty($data['pDestacado']['idParametro'])) {
            $errors[] = ["campo" => "pdestacado", "valor" => "Ingrese destacado."];
        }
        if (empty($data['pComplemento']['idParametro'])) {
            $errors[] = ["campo" => "pcomplemento", "valor" => "Ingrese el producto complemento."];
        }
        if (empty($data['nombre'])) {
            $errors[] = ["campo" => "nombre", "valor" => "Ingrese el nombre."];
        }
        if (empty($data['urlAmigable'])) {
            $errors[] = ["campo" => "urlamigable", "valor" => "Ingrese la URL amigable."];
        }
        if (empty($data['codigo'])) {
            $errors[] = ["campo" => "codigo", "valor" => "Ingrese el código del producto."];
        }
        if (empty($data['precioLista'])) {
            $errors[] = ["campo" => "preciolista", "valor" => "Ingrese el precio de lista."];
        }
        if (empty($data['precioVenta'])) {
            $errors[] = ["campo" => "precioventa", "valor" => "Ingrese el precio de venta."];
        }
        if (empty($data['fechaPublicacion'])) {
            $errors[] = ["campo" => "fechapublicacion", "valor" => "Ingrese la fecha de publicación."];
        }
        if (empty($data['stock'])) {
            $errors[] = ["campo" => "stock", "valor" => "Ingrese el stock."];
        }
        // Validar unicidad del nombre excluyendo el producto actual
        if (!empty($data['nombre'])) {
            $existeNombre = $productoModel
                ->where('nombre', $data['nombre'])
                ->where('idproducto !=', $data['idProducto'])
                ->first();
            if ($existeNombre) {
                $errors[] = ["campo" => "nombre", "valor" => "El nombre ya está registrado por otro producto."];
            }
        }

        // Validar unicidad de la URL amigable excluyendo el producto actual
        if (!empty($data['urlAmigable'])) {
            $existeUrl = $productoModel
                ->where('urlamigable', $data['urlAmigable'])
                ->where('idproducto !=', $data['idProducto'])
                ->first();
            if ($existeUrl) {
                $errors[] = ["campo" => "urlamigable", "valor" => "La URL amigable ya está registrada por otro producto."];
            }
        }

        return $errors;
    }
}
