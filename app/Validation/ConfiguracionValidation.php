<?php

namespace App\Validation;

class ConfiguracionValidation
{
    public static function ConfiguracionGuardarValidation(array $data): array
    {
        $errors = [];

        if (empty($data['pTipo']['idParametro'])) {
            $errors[] = ["campo" => "ptipo", "valor" => "Seleccione el tipo."];
        }

        if (empty($data['nombre'])) {
            $errors[] = ["campo" => "nombre", "valor" => "Ingrese el nombre."];
        }

        if (empty($data['pRecurso']['idParametro'])) {
            $errors[] = ["campo" => "precurso", "valor" => "Seleccione el tipo."];
        } else {
            if ($data['pRecurso']['idParametro'] == 570) {
                if (empty($data['urlImagen'])) {
                    $errors[] = ["campo" => "urlImagen", "valor" => "Seleccione la imagen."];
                }
                // $errors[] = ["campo" => "urlImagen", "valor" => "Seleccione la imagen."];
            } else {
                if (empty($data['valor'])) {
                    $errors[] = ["campo" => "valor", "valor" => "Ingrese el valor."];
                }
            }
        }


        // if (empty($data['valor'])) {
        //     $errors[] = ["campo" => "valor", "valor" => "Ingrese el valor."];
        // }



        return $errors;
    }

    public static function ConfiguracionActualizarValidation(array $data): array
    {
        $errors = [];

        if (empty($data['pTipo']['idParametro'])) {
            $errors[] = ["campo" => "ptipo", "valor" => "Seleccione el tipo."];
        }

        if (empty($data['nombre'])) {
            $errors[] = ["campo" => "nombre", "valor" => "Ingrese el nombre."];
        }
        if (empty($data['pRecurso']['idParametro'])) {
            $errors[] = ["campo" => "precurso", "valor" => "Seleccione el tipo."];
        } else {
            if ($data['pRecurso']['idParametro'] == 570) {
                if (empty($data['urlImagen'])) {
                    $errors[] = ["campo" => "urlImagen", "valor" => "Seleccione la imagen."];
                }
                // $errors[] = ["campo" => "urlImagen", "valor" => "Seleccione la imagen."];
            } else {
                if (empty($data['valor'])) {
                    $errors[] = ["campo" => "valor", "valor" => "Ingrese el valor."];
                }
            }
        }

        // if (empty($data['valor'])) {
        //     $errors[] = ["campo" => "valor", "valor" => "Ingrese el valor."];
        // }

        return $errors;
    }
}
