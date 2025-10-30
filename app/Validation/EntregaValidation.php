<?php

namespace App\Validation;

class EntregaValidation
{
    public static function entregaGuardarValidation(array $data): array
    {
        $errors = [];


        if (empty($data['estado']['idEstado'])) {
            $errors[] = ["campo" => "estado", "valor" => "Ingrese el estado."];
        }
        if (empty($data['nombre'])) {
            $errors[] = ["campo" => "nombre", "valor" => "Ingrese el nombre."];
        }
        if (empty($data['diasHabiles'])) {
            $errors[] = ["campo" => "diasBloqueados", "valor" => "Ingrese lso dias habiles."];
        }
        if (empty($data['dias'])) {
            $errors[] = ["campo" => "dias", "valor" => "Ingrese los  dias de espera."];
        }
        if (empty($data['minimoGratis'])) {
            $errors[] = ["campo" => "minimogratis", "valor" => "Ingrese el importe minimo gratis."];
        }
        if (empty($data['importeMinimo'])) {
            $errors[] = ["campo" => "importeminimo", "valor" => "Ingrese el importe minimo."];
        }
        // if (empty($data['horaReferencia'])) {
        //     $errors[] = ["campo" => "horareferencia", "valor" => "Ingrese la horare ferencia."];
        // }
        // if (empty($data['pesoxCostoEnvio'])) {
        //     $errors[] = ["campo" => "pesoxcostoenvio", "valor" => "Ingrese el peso por costo de envio."];
        // }
        // if (empty($data['orden'])) {
        //     $errors[] = ["campo" => "orden", "valor" => "Ingrese el orden."];
        // }

        // if (empty($data['costoEnvio'])) {
        //     $errors[] = ["campo" => "costoenvio", "valor" => "Ingrese el costo de envio."];
        // }

        return $errors;
    }

    public static function entregaActualizarValidation(array $data): array
    {
        $errors = [];


        if (empty($data['estado']['idEstado'])) {
            $errors[] = ["campo" => "estado", "valor" => "Ingrese el estado."];
        }
        if (empty($data['nombre'])) {
            $errors[] = ["campo" => "nombre", "valor" => "Ingrese el nombre."];
        }
        if (empty($data['diasHabiles'])) {
            $errors[] = ["campo" => "diasBloqueados", "valor" => "Ingrese lso dias habiles."];
        }
        if (empty($data['dias'])) {
            $errors[] = ["campo" => "dias", "valor" => "Ingrese los  dias de espera."];
        }
        if (empty($data['minimoGratis'])) {
            $errors[] = ["campo" => "minimogratis", "valor" => "Ingrese el importe minimo gratis."];
        }
        if (empty($data['importeMinimo'])) {
            $errors[] = ["campo" => "importeminimo", "valor" => "Ingrese el importe minimo."];
        }
        // if (empty($data['horaReferencia'])) {
        //     $errors[] = ["campo" => "horareferencia", "valor" => "Ingrese la horare ferencia."];
        // }
        // if (empty($data['pesoxCostoEnvio'])) {
        //     $errors[] = ["campo" => "pesoxcostoenvio", "valor" => "Ingrese el peso por costo de envio."];
        // }
        // if (empty($data['orden'])) {
        //     $errors[] = ["campo" => "orden", "valor" => "Ingrese el orden."];
        // }
        // if (empty($data['costoEnvio'])) {
        //     $errors[] = ["campo" => "costoenvio", "valor" => "Ingrese el costo de envio."];
        // }

        return $errors;
    }
}
