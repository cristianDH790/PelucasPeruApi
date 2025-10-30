<?php

namespace App\Validation;

class UsuarioValidation
{
    public static function UsuarioGuardarValidation(array $data): array
    {
        $errors = [];

        if (empty($data['estado']['idEstado'])) {
            $errors[] = ["campo" => "estado", "valor" => "Seleccione el estado."];
        }

        if (empty($data['perfil']['idPerfil'])) {
            $errors[] = ["campo" => "perfil", "valor" => "Seleccione el perfil."];
        }
       

        if (empty($data['pDocumento']['idParametro'])) {
            $errors[] = ["campo" => "pDocumento", "valor" => "Seleccione el documento."];
        }

        if (empty($data['login'])) {
            $errors[] = ["campo" => "login", "valor" => "Ingrese el login."];
        }

        if (empty($data['clave'])) {
            $errors[] = ["campo" => "clave", "valor" => "Ingrese la clave."];
        } elseif (strlen($data['clave']) < 4) {
            $errors[] = ["campo" => "clave", "valor" => "La clave debe tener al menos 6 caracteres."];
        }

        if (empty($data['nombres'])) {
            $errors[] = ["campo" => "nombres", "valor" => "Ingrese los nombres."];
        }

        if (empty($data['pApellido'])) {
            $errors[] = ["campo" => "papellido", "valor" => "Ingrese el primer apellido."];
        }

        if (empty($data['sApellido'])) {
            $errors[] = ["campo" => "sapellido", "valor" => "Ingrese el primer apellido."];
        }

        // sapellido es permit_empty, no se valida si está vacío

        if (empty($data['documento'])) {
            $errors[] = ["campo" => "documento", "valor" => "Ingrese el docuemento."];
        }

        // sexo es permit_empty, opcional pero si viene validar valores permitidos
        if (empty($data['sexo'])) {
            $errors[] = ["campo" => "sexo", "valor" => "Ingrese el sexo."];
        }

        // fechanacimiento es permit_empty, pero si viene validar formato fecha (opcional)
        if (empty($data['fechaNacimiento'])) {
            $errors[] = ["campo" => "fechanacimiento", "valor" => "Formato de fecha inválido."];
        }
        if (empty($data['clave'])) {
            $errors[] = ["campo" => "clave", "valor" => "Ingrese la clave."];
        }

        if (empty($data['correo'])) {
            $errors[] = ["campo" => "correo", "valor" => "Ingrese el correo."];
        } elseif (!filter_var($data['correo'], FILTER_VALIDATE_EMAIL)) {
            $errors[] = ["campo" => "correo", "valor" => "Correo electrónico inválido."];
        }

        // telefono es permit_empty, no validamos si está vacío

        return $errors;
    }

    public static function UsuarioActualizarValidation(array $data): array
    {
        $errors = [];


        if (empty($data['estado']['idEstado'])) {
            $errors[] = ["campo" => "estado", "valor" => "Seleccione el estado."];
        }

        if (empty($data['perfil']['idPerfil'])) {
            $errors[] = ["campo" => "perfil", "valor" => "Seleccione el perfil."];
        }
      
        if (empty($data['pDocumento']['idParametro'])) {
            $errors[] = ["campo" => "pDocumento", "valor" => "Seleccione el documento."];
        }

        if (empty($data['login'])) {
            $errors[] = ["campo" => "login", "valor" => "Ingrese el login."];
        }

        if (empty($data['clave'])) {
            $errors[] = ["campo" => "clave", "valor" => "Ingrese la clave."];
        } elseif (strlen($data['clave']) < 4) {
            $errors[] = ["campo" => "clave", "valor" => "La clave debe tener al menos 6 caracteres."];
        }

        if (empty($data['nombres'])) {
            $errors[] = ["campo" => "nombres", "valor" => "Ingrese los nombres."];
        }

        if (empty($data['pApellido'])) {
            $errors[] = ["campo" => "papellido", "valor" => "Ingrese el primer apellido."];
        }

        if (empty($data['sApellido'])) {
            $errors[] = ["campo" => "sapellido", "valor" => "Ingrese el primer apellido."];
        }

        // sapellido es permit_empty, no se valida si está vacío

        if (empty($data['documento'])) {
            $errors[] = ["campo" => "documento", "valor" => "Ingrese el docuemento."];
        }

        // sexo es permit_empty, opcional pero si viene validar valores permitidos
        if (empty($data['sexo'])) {
            $errors[] = ["campo" => "sexo", "valor" => "Ingrese el sexo."];
        }

        // fechanacimiento es permit_empty, pero si viene validar formato fecha (opcional)
        if (empty($data['fechaNacimiento'])) {
            $errors[] = ["campo" => "fechanacimiento", "valor" => "Formato de fecha inválido."];
        }
        if (empty($data['clave'])) {
            $errors[] = ["campo" => "clave", "valor" => "Ingrese la clave."];
        }

        if (empty($data['correo'])) {
            $errors[] = ["campo" => "correo", "valor" => "Ingrese el correo."];
        } elseif (!filter_var($data['correo'], FILTER_VALIDATE_EMAIL)) {
            $errors[] = ["campo" => "correo", "valor" => "Correo electrónico inválido."];
        }

        // telefono es permit_empty, no validamos si está vacío

        return $errors;
    }
}
