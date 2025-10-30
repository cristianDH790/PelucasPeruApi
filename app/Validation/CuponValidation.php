<?php

namespace App\Validation;

use App\Models\CuponModel;

class CuponValidation
{
    public static function cuponGuardarValidation(array $data, CuponModel $CuponModel): array
    {
        $errors = [];

        if (empty($data['estado']['idEstado'])) {
            $errors[] = ["campo" => "estado", "valor" => "Ingrese el estado."];
        }

        if (empty($data['pTipo']['idParametro'])) {
            $errors[] = ["campo" => "ptipos", "valor" => "Ingrese el tipo cupo."];
        }
        if (empty($data['nombre'])) {
            $errors[] = ["campo" => "nombre", "valor" => "Ingrese el nombre."];
        }
        // if (empty($data['codigo'])) {
        //     $errors[] = ["campo" => "codigo", "valor" => "Ingrese el codigo."];
        // }
        if (empty($data['codigo'])) {
            $errors[] = ["campo" => "codigo", "valor" => "Ingrese el codigo."];
        } else {
            // Validar unicidad nombre para creación
            $existe = $CuponModel->where('codigo', $data['codigo'])->first();
            if ($existe) {
                $errors[] = ["campo" => "codigo", "valor" => "El codigo ya existe."];
            }
        }
        if (empty($data['limite'])) {
            $errors[] = ["campo" => "limite", "valor" => "Ingrese el limite."];
        }
        if (empty($data['descuento'])) {
            $errors[] = ["campo" => "descuento", "valor" => "Ingrese el descuento."];
        }
        if (empty($data['inicio'])) {
            $errors[] = ["campo" => "inicio", "valor" => "Ingrese el inicio."];
        }
        if (empty($data['termino'])) {
            $errors[] = ["campo" => "termino", "valor" => "Ingrese el termino."];
        }





        return $errors;
    }

    public static function cuponActualizarValidation(array $data, CuponModel $CuponModel): array
    {
        $errors = [];

        if (empty($data['estado']['idEstado'])) {
            $errors[] = ["campo" => "estado", "valor" => "Ingrese el estado."];
        }

        if (empty($data['pTipo']['idParametro'])) {
            $errors[] = ["campo" => "ptipos", "valor" => "Ingrese el tipo cupo."];
        }
        if (empty($data['nombre'])) {
            $errors[] = ["campo" => "nombre", "valor" => "Ingrese el nombre."];
        }
        // if (empty($data['codigo'])) {
        //     $errors[] = ["campo" => "codigo", "valor" => "Ingrese el codigo."];
        // }



        if (empty($data['codigo'])) {
            $errors[] = ["campo" => "codigo", "valor" => "Ingrese el codigo."];
        } else {
            $idCupon = $data['idCupon'] ?? null;


            // Validar unicidad nombre para edición, excluyendo el actual registro
            $existe = $CuponModel->where('codigo', $data['codigo'])
                ->where('idCupon !=', $idCupon)
                ->first();

            if ($existe) {
                $errors[] = ["campo" => "codigo", "valor" => "El codigo ya existe en otro registro."];
            }
        }

        if (empty($data['limite'])) {
            $errors[] = ["campo" => "limite", "valor" => "Ingrese el limite."];
        }
        if (empty($data['descuento'])) {
            $errors[] = ["campo" => "descuento", "valor" => "Ingrese el descuento."];
        }
        if (empty($data['inicio'])) {
            $errors[] = ["campo" => "inicio", "valor" => "Ingrese el inicio."];
        }
        if (empty($data['termino'])) {
            $errors[] = ["campo" => "termino", "valor" => "Ingrese el termino."];
        }




        return $errors;
    }
}
