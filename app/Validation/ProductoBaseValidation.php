<?php

namespace App\Validation;

class ProductoBaseValidation
{
    public static function productoBaseGuardarValidation(array $data): array
    {
        $errors = [];

        if (empty($data['estado']['idEstado'])) {
            $errors[] = ["campo" => "estado", "valor" => "Ingrese el estado."];
        }
        if (empty($data['productoCategoria']['idProductoCategoria'])) {
            $errors[] = ["campo" => "productocategoria", "valor" => "Ingrese el producto categoria."];
        }
        if (empty($data['pPromocion']['idParametro'])) {
            $errors[] = ["campo" => "ppromocion", "valor" => "Ingrese la promocion."];
        }
        if (empty($data['pDestacado']['idParametro'])) {
            $errors[] = ["campo" => "pdestacado", "valor" => "Ingrese destacado."];
        }

        if (empty($data['nombre'])) {
            $errors[] = ["campo" => "nombre", "valor" => "Ingrese el nombre."];
        }
        if (empty($data['urlAmigable'])) {
            $errors[] = ["campo" => "urlamigable", "valor" => "Ingrese la url cabecera."];
        }
        if (empty($data['marca']['idMarca'])) {
            $errors[] = ["campo" => "marca", "valor" => "Ingrese la marca."];
        }
        if (empty($data['codigo'])) {
            $errors[] = ["campo" => "codigo", "valor" => "Ingrese el codigo del producto."];
        }
        if (empty($data['precioLista'])) {
            $errors[] = ["campo" => "preciolista", "valor" => "Ingrese el precio lista."];
        }
        if (empty($data['precioVenta'])) {
            $errors[] = ["campo" => "precioventa", "valor" => "Ingrese el precio venta."];
        }
        if (empty($data['peso'])) {
            $errors[] = ["campo" => "peso", "valor" => "Ingrese el peso."];
        }
        // if (empty($data['empresa']['idEmpresa'])) {
        //     $errors[] = ["campo" => "empresa", "valor" => "Ingrese la empresa."];
        // }
        if (empty($data['fechaPublicacion'])) {
            $errors[] = ["campo" => "fechapublicacion", "valor" => "Ingrese la fecha de publicacion."];
        }

        // if (empty($data['stock'])) {
        //     $errors[] = ["campo" => "stock", "valor" => "Ingrese el stock."];
        // }


        return $errors;
    }

    public static function productoBaseActualizarValidation(array $data): array
    {
        $errors = [];

        if (empty($data['estado']['idEstado'])) {
            $errors[] = ["campo" => "estado", "valor" => "Ingrese el estado."];
        }
        if (empty($data['productoCategoria']['idProductoCategoria'])) {
            $errors[] = ["campo" => "productocategoria", "valor" => "Ingrese el producto categoria."];
        }
        if (empty($data['pPromocion']['idParametro'])) {
            $errors[] = ["campo" => "ppromocion", "valor" => "Ingrese la promocion."];
        }
        if (empty($data['pDestacado']['idParametro'])) {
            $errors[] = ["campo" => "pdestacado", "valor" => "Ingrese destacado."];
        }

        if (empty($data['nombre'])) {
            $errors[] = ["campo" => "nombre", "valor" => "Ingrese el nombre."];
        }
        if (empty($data['urlAmigable'])) {
            $errors[] = ["campo" => "urlamigable", "valor" => "Ingrese la url cabecera."];
        }
        if (empty($data['marca']['idMarca'])) {
            $errors[] = ["campo" => "marca", "valor" => "Ingrese la marca."];
        }
        if (empty($data['codigo'])) {
            $errors[] = ["campo" => "codigo", "valor" => "Ingrese el codigo del producto."];
        }
        if (empty($data['precioLista'])) {
            $errors[] = ["campo" => "preciolista", "valor" => "Ingrese el precio lista."];
        }
        if (empty($data['precioVenta'])) {
            $errors[] = ["campo" => "precioventa", "valor" => "Ingrese el precio venta."];
        }
        if (empty($data['peso'])) {
            $errors[] = ["campo" => "peso", "valor" => "Ingrese el peso."];
        }
        // if (empty($data['empresa']['idEmpresa'])) {
        //     $errors[] = ["campo" => "empresa", "valor" => "Ingrese la empresa."];
        // }
        if (empty($data['fechaPublicacion'])) {
            $errors[] = ["campo" => "fechapublicacion", "valor" => "Ingrese la fecha de publicacion."];
        }
        // if (empty($data['stock'])) {
        //     $errors[] = ["campo" => "stock", "valor" => "Ingrese el stock."];
        // }




        return $errors;
    }
}
