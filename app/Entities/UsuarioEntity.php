<?php

namespace App\Entities;


class UsuarioEntity
{
    public $idusuario;
    public $idestado;
    public $idperfil;
    public $idpdocumento;
    public $documento;
    public $nombres;
    public $papellido;
    public $sapellido;
    public $fechanacimiento;
    public $sexo;
    public $correo;
    public $telefono;
    public $boletin;
    public $login;
    public $password;
    public $fecha;
    public $pedidos;
    public $importetotal;



    public $estado;
    public $pdocumento;
    public $perfil;
    public $empresa;
    public $authorities;

    public function __construct($data = null)
    {
        if ($data) {
            if (is_array($data)) {
                $this->idusuario = $data['idusuario'] ?? null;
                $this->idestado = $data['idestado'] ?? null;
                $this->idperfil = $data['idperfil'] ?? null;
                $this->idpdocumento = $data['idpdocumento'] ?? null;
                $this->documento = $data['documento'] ?? null;
                $this->nombres = $data['nombres'] ?? null;
                $this->papellido = $data['papellido'] ?? null;
                $this->sapellido = $data['sapellido'] ?? null;
                $this->fechanacimiento = $data['fechanacimiento'] ?? null;
                $this->sexo = $data['sexo'] ?? null;
                $this->telefono = $data['telefono'] ?? null;
                $this->correo = $data['correo'] ?? null;

                $this->boletin = $data['boletin'] ?? null;
                $this->login = $data['login'] ?? null;
                $this->password = $data['password'] ?? null;
                $this->fecha = $data['fecha'] ?? null;
            } elseif (is_object($data)) {
                $this->idusuario = $data->idusuario ?? null;
                $this->idestado = $data->idestado ?? null;
                $this->idperfil = $data->idperfil ?? null;
                $this->idpdocumento = $data->idpdocumento ?? null;
                $this->documento = $data->documento ?? null;
                $this->nombres = $data->nombres ?? null;
                $this->papellido = $data->papellido ?? null;
                $this->sapellido = $data->sapellido ?? null;
                $this->fechanacimiento = $data->fechanacimiento ?? null;
                $this->sexo = $data->sexo ?? null;
                $this->telefono = $data->telefono ?? null;
                $this->correo = $data->correo ?? null;

                $this->boletin = $data->boletin ?? null;
                $this->login = $data->login ?? null;
                $this->password = $data->password ?? null;
                $this->fecha = $data->fecha ?? null;
            }
        }
    }

    /**
     * Convierte la entidad a array, incluyendo relaciones si existen
     * @return array
     */
    public function toArray(): array
    {

        $data = [
            'idUsuario' => $this->idusuario,
            'idEstado' => $this->idestado,
            'idPerfil' => $this->idperfil,
            'idpDocumento' => $this->idpdocumento,
            'documento' => $this->documento,
            'nombres' => $this->nombres,
            'pApellido' => $this->papellido,
            'sApellido' => $this->sapellido,
            'fechaNacimiento' => $this->fechanacimiento,
            'sexo' => $this->sexo,
            'correo' => $this->correo,
            'telefono' => $this->telefono,
            'boletin' => $this->boletin,
            'login' => $this->login,
            'clave' => $this->password,
            'fecha' => $this->fecha,
            'pedidos' => $this->pedidos ?? null,
            'importeTotal' => $this->importetotal ?? null,
        ];

        // Relaciones (solo si están cargadas)
        if ($this->estado !== null) {
            $data['estado'] = $this->estado->toArray();
        }

        if ($this->perfil !== null) {
            $data['perfil'] = $this->perfil->toArray();
        }
        if ($this->pdocumento !== null) {
            $data['pDocumento'] = $this->pdocumento->toArray();
        }
        if ($this->empresa !== null) {
            $data['empresa'] = $this->empresa->toArray();
        }
        // if ($this->authorities !== null) {
        //     //$data['authorities'] = $this->authorities->toArray();
        //     $data['authorities'] = $this->authorities;
        // }
        if ($this->authorities !== null) {
            if (is_array($this->authorities)) {
                // Ya es un array simple, lo agregamos directo
                $data['authorities'] = $this->authorities;
            } elseif (is_object($this->authorities) && method_exists($this->authorities, 'toArray')) {
                $data['authorities'] = $this->authorities->toArray();
            } else {
                // fallback si es otro tipo
                $data['authorities'] = $this->authorities;
            }
        }




        return $data;
    }
}
