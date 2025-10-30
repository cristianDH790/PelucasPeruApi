<?php

namespace App\Controllers;

use App\Entities\Configuracion;

use App\Entities\Mensaje;
use App\Entities\Suscripcion;
use App\Entities\Usuario;
use App\Helpers\Util;
use App\Models\ConfiguracionModel;
use App\Models\MensajeModel;
use App\Models\SuscripcionModel;
use App\Models\UsuarioModel;

class FormularioController extends BaseController
{

    private $email;
    private $usuario;
    protected $suscripcion;
    protected $session;
    // Constructor
    public function __construct()
    {
        date_default_timezone_set('America/Lima');

        $this->session = \Config\Services::session();
        $this->email = \Config\Services::email();
        helper('filesystem');


        $config['protocol'] = 'smtp';
        $config['charset']  = 'utf-8';
        $config['SMTPHost'] = getenv('SMTP_HOST');
        $config['SMTPUser'] = getenv('SMTP_USER');
        $config['SMTPPass'] = getenv('SMTP_PASS');
        $config['SMTPPort'] = (int) getenv('SMTP_PORT');
        $config['SMTPTimeout'] = 30;
        $config['mailType'] = 'html';
        $config['wordwrap'] = true;


        $this->email->initialize($config);

        $this->suscripcion = new SuscripcionModel();
        $this->usuario = new UsuarioModel();
    }

    public function mailContacto()
    {

        $nombres = $this->request->getPost("nombres");
        $correo = $this->request->getPost("correo");
        $telefono = $this->request->getPost("telefono");
        $asunto = $this->request->getPost("asunto");
        $mensaje = $this->request->getPost("mensaje");
        // $terminos = $this->request->getPost("terminos");

        $captchacheck = $_SESSION['captcha_text'];
        $captcha = strtoupper($this->request->getPost("captcha"));

        $data = [];

        //validaciones
        if ($nombres == "" || $nombres == null)
            array_push($data, ['campo' => 'nombres', 'valor' => 'Complete']);

        if ($correo == "" || $correo == null)
            array_push($data, ['campo' => 'correo', 'valor' => 'Complete']);

        if ($telefono == "" || $telefono == null)
            array_push($data, ['campo' => 'telefono', 'valor' => 'Complete']);

        if ($asunto == "" || $asunto == null)
            array_push($data, ['campo' => 'asunto', 'valor' => 'Complete']);

        if ($captcha == "" || $captcha == null)
            array_push($data, ['campo' => 'captcha', 'valor' => 'Complete el captcha']);
        elseif ($captcha != $captchacheck)
            array_push($data, ['campo' => 'captcha', 'valor' => 'Captcha incorrecto']);

        if (count($data) > 0)
            return $this->response->setJSON(["errors" => $data, "status" => "error"]);

        else {
            $contenido = "";
            $contenido .= "<ul><p>Nombre y Apellidos: <strong>" . $nombres . " </strong></p>";
            $contenido .= "<p>Correo: <strong>" . $correo  . " </strong></p>";
            $contenido .= "<p>Teléfono: <strong>" . $telefono  . " </strong></p>";
            $contenido .= "<p>Asunto: <strong>" . $asunto . "</strong></p>";
            $mensaje ? $contenido .= "<p>Mensaje: <strong>" . $mensaje . "</strong></p>" : "";
            $contenido .= "<p>Fecha: <strong>" . date('d-m-Y H:i:s')  . " </strong></p></ul>";

            $variables = array(
                '1' => $contenido,
            );

            $newMensaje = new MensajeModel();
            $mensaje = $newMensaje->obtenerPorId(2);
            $resultado = $mensaje->contenido;
            for ($i = 1; $i <= count($variables); $i++) {
                $resultado = $this->reemplazo("{{" . $i . "}}", $variables[$i], $resultado);
            }

            $conf = new ConfiguracionModel();

            $configuracion = $conf->obtenerPorId(35);
            if ($configuracion) {
                $correoEnvio = explode(',', $configuracion->valor);
                foreach ($correoEnvio as $key => $value) {
                    $this->email->setFrom("milislens@pelucasperu.com", "PELUCAS PERU");
                    $this->email->setTo($value);
                    $this->email->setSubject($mensaje->asunto);
                    $this->email->setMessage($resultado);
                    //$this->email->setTo("desarrollo1@onlinesolutions.com.pe");
                    // $this->email->setMessage($contenido);
                    $this->email->send();
                }
            }
            $data = [
                "status" => "exito",
            ];
            return $this->response->setJSON($data);
        }
    }

    public function mailLibroReclamaciones()
    {

        $tipoDocumento = $this->request->getPost("tipodoc");
        $documento = $this->request->getPost("documento");
        $nombres = $this->request->getPost("nombres");
        $apellidos = $this->request->getPost("apellidos");
        $tipoDireccion = $this->request->getPost("tipodir");
        $direccion = $this->request->getPost("direccion");
        $correo = $this->request->getPost("correo");
        $telefono = $this->request->getPost("telefono");
        $motivo = $this->request->getPost("motivo");
        $detalle = $this->request->getPost("detalle");
        $terminos = $this->request->getPost("terminos");

        $captchacheck = $_SESSION['captcha_text'];
        $captcha = strtoupper($this->request->getPost("captcha"));


        $data = [];

        //validaciones
        if ($tipoDocumento == "" || $tipoDocumento == null)
            array_push($data, ['campo' => 'tipodoc', 'valor' => 'Seleccione']);

        if ($documento == "" || $documento == null)
            array_push($data, ['campo' => 'documento', 'valor' => 'Complete']);

        if ($nombres == "" || $nombres == null)
            array_push($data, ['campo' => 'nombres', 'valor' => 'Complete']);

        if ($apellidos == "" || $apellidos == null)
            array_push($data, ['campo' => 'apellidos', 'valor' => 'Complete']);

        if ($tipoDireccion == "" || $tipoDireccion == null)
            array_push($data, ['campo' => 'tipodir', 'valor' => 'Seleccione']);

        if ($direccion == "" || $direccion == null)
            array_push($data, ['campo' => 'direccion', 'valor' => 'Complete']);

        if ($correo == "" || $correo == null)
            array_push($data, ['campo' => 'correo', 'valor' => 'Complete']);

        if ($telefono == "" || $telefono == null)
            array_push($data, ['campo' => 'telefono', 'valor' => 'Complete']);

        if ($motivo == "" || $motivo == null)
            array_push($data, ['campo' => 'motivo', 'valor' => 'Seleccione']);

        if ($detalle == "" || $detalle == null)
            array_push($data, ['campo' => 'detalle', 'valor' => 'Complete']);

        // if ($terminos == "" || $terminos == null || $terminos == false)
        //     array_push($data, ['campo' => 'terminos', 'valor' => 'Acepte los términos y condiciones.']);

        if ($captcha == "" || $captcha == null) {
            array_push($data, ['campo' => 'captcha', 'valor' => 'Complete el captcha']);
        } else {
            if ($captcha != $captchacheck)
                array_push($data, ['campo' => 'captcha', 'valor' => 'Captcha incorrecto']);
        }

        if (count($data) > 0)
            return $this->response->setJSON(["errors" => $data, "status" => "error"]);

        else {
            $contenido = "";
            $contenido .= "<ul><p>Nombre y Apellidos: <strong>" . $nombres . " " . $apellidos . " </strong></p>";
            $contenido .= "<p>" . $tipoDocumento . ": <strong>" . $documento  . " </strong></p>";
            $contenido .= "<p>Correo: <strong>" . $correo  . " </strong></p>";
            $contenido .= "<p>Teléfono: <strong>" . $telefono  . " </strong></p>";
            $contenido .= "<p>Dirección: <strong>" . $tipoDireccion  . " - " . $direccion . " </strong></p>";
            $contenido .= "<p>Motivo: <strong>" . $motivo  . " </strong></p>";
            $contenido .= "<p>Fecha: <strong>" . date('d-m-Y H:i:s')  . " </strong></p></ul>";

            $variables = array(
                '1' => $contenido,
                '2' => $detalle,
            );

            $newMensaje = new MensajeModel();
            $mensaje = $newMensaje->obtenerPorId(8);
            $resultado = $mensaje->contenido;
            for ($i = 1; $i <= count($variables); $i++) {
                $resultado = $this->reemplazo("{{" . $i . "}}", $variables[$i], $resultado);
            }

            $conf = new ConfiguracionModel();
            $configuracion = $conf->obtenerPorId(36);
            if ($configuracion) {
                $correoEnvio = explode(',', $configuracion->valor);
                foreach ($correoEnvio as $key => $value) {
                    $this->email->setFrom($correo, $nombres);
                    $this->email->setFrom("milislens@pelucasperu.com", "PELUCAS PERU");
                    $this->email->setTo($value);
                    //$this->email->setTo("desarrollo1@onlinesolutions.com.pe");
                    $this->email->setSubject("(Libro de reclamaciones) - Página web");
                    $this->email->setMessage($resultado);
                    $this->email->send();
                }
            }
            $data = [
                "status" => "exito",
                "correo" => $correoEnvio
            ];
            return $this->response->setJSON($data);
        }
    }

    public function envioCorreoVerificacion()
    {

        $codigo = $this->request->getPost("codigo");
        $correo = $this->request->getPost("correo");

        $data = [];

        //validaciones
        if ($codigo == "" || $codigo == null)
            array_push($data, ['campo' => 'codigo', 'valor' => 'Complete']);

        if ($correo == "" || $correo == null)
            array_push($data, ['campo' => 'correo', 'valor' => 'Complete']);

        if (count($data) > 0)
            return $this->response->setJSON(["data" => $data, "status" => "error"]);

        //Set codigo
        $_SESSION['codigo_editarMisDatos'] = $codigo;

        $variables = array(
            '1' => $codigo,
        );

        $newMensaje = new MensajeModel();
        $mensaje = $newMensaje->obtenerPorId(10);
        $resultado = $mensaje->contenido;

        for ($i = 1; $i <= count($variables); $i++) {
            $resultado = $this->reemplazo("{{" . $i . "}}", $variables[$i], $resultado);
        }


        $this->email->setFrom("milislens@pelucasperu.com", "PELUCAS PERU");
        $this->email->setTo($correo);
        $this->email->setMessage($resultado);
        $this->email->setSubject($mensaje->asunto);
        // $this->email->setTo("desarrollo1@onlinesolutions.com.pe");
        $this->email->send();

        $data = [
            "status" => "exito",
        ];
        return $this->response->setJSON($data);
    }

    public function suscripcion()
    {
        $errors = [];

        $correo = $this->request->getPost("correo");

        if ($correo == "" || $correo == null)
            array_push($errors, ['campo' => 'correo', 'valor' => 'Complete']);
        elseif (!filter_var($correo, FILTER_VALIDATE_EMAIL))
            array_push($errors, ['campo' => 'correo', 'valor' => 'Correo inválido']);
        else {
            $existe = $this->suscripcion->suscripcionPorCorreo($correo, '');
            if ($existe)
                array_push($errors, ['campo' => 'correo', 'valor' => 'El correo ya se encuentra suscrito']);
        }

        if (count($errors) > 0)
            return $this->response->setJSON(["errors" => $errors, "status" => "error"]);

        $datos = [
            'idsuscripcion' => null,
            'idestado' => 412,
            'correo' => $correo,
            'telefono' => null,
        ];
        $suscripcion = $this->suscripcion->guardar($datos);

        if ($suscripcion) {
            $contenido = "";
            $contenido .= "<p>Correo: <strong>" . $correo . " </strong></p>";
            $contenido .= "<p>Gracias por tu sucripción.</p>";

            $variables = array(
                '1' => '' . $correo,
                '2' => $contenido,
            );

            $newMensaje = new MensajeModel();
            $mensaje = $newMensaje->obtenerPorId(6);
            $resultado = $mensaje->contenido;
            for ($i = 1; $i <= count($variables); $i++) {
                $resultado = $this->reemplazo("{{" . $i . "}}", $variables[$i], $resultado);
            }

            $conf = new ConfiguracionModel();
            $configuracion = $conf->obtenerPorId(27);
            if ($configuracion) {
                $correoEnvio = explode(',', $configuracion->valor);
                foreach ($correoEnvio as $key => $value) {
                    $this->email->setFrom("milislens@pelucasperu.com", "PELUCAS PERU");
                    $this->email->setTo($correo);
                    // $this->email->setBcc($value);
                    $this->email->setBcc($value);
                    $this->email->setSubject("Suscripcíon - Página web");
                    $this->email->setMessage($resultado);
                    $this->email->send();
                }
                return $this->response->setJSON(["status" => "exito", 'suscripcion' => $suscripcion]);
            }
        }
    }



    public function registro()
    {
        $errors = [];

        $nombre = $this->request->getPost("nombres");
        $apellidoPaterno = $this->request->getPost("apellido-paterno");
        $apellidoMaterno = $this->request->getPost("apellido-materno");
        $documento = $this->request->getPost("documento");
        $pdocumento = $this->request->getPost("pdocumento");
        $correo = $this->request->getPost("correo");
        $telefono = $this->request->getPost("telefono");
        $sexo = $this->request->getPost("sexo");
        $termino = $this->request->getPost("terminos");
        $captchacheck = $_SESSION['captcha_text'];
        $captcha = strtoupper($this->request->getPost("captcha"));



        if ($correo == "" || $correo == null)
            array_push($errors, ['campo' => 'correo', 'valor' => 'Complete']);
        else {
            $existe = $this->usuario->obtenerPorCorreo($correo, '');
            if ($existe)
                array_push($errors, ['campo' => 'correo', 'valor' => 'El Correo ya se encuentra registrado']);
        }

        if ($documento == "" || $documento == null)
            array_push($errors, ['campo' => 'documento', 'valor' => 'Complete']);
        else {
            $existe = $this->usuario->obtenerPorDocumento($documento, '');
            if ($existe)
                array_push($errors, ['campo' => 'documento', 'valor' => 'El Documento ya se encuentra registrado']);
        }

        if ($pdocumento == "" || $pdocumento == null)
            array_push($errors, ['campo' => 'tipo-documento', 'valor' => 'Complete']);

        if ($sexo == "" || $sexo == null)
            array_push($errors, ['campo' => 'sexo', 'valor' => 'Complete']);

        if ($telefono == "" || $telefono == null)
            array_push($errors, ['campo' => 'telefono', 'valor' => 'Complete']);


        if ($nombre == "" || $nombre == null)
            array_push($errors, ['campo' => 'nombres', 'valor' => 'Complete']);


        if ($apellidoPaterno == "" || $apellidoPaterno == null)
            array_push($errors, ['campo' => 'apellido-paterno', 'valor' => 'Complete']);

        if ($captcha == "" || $captcha == null)
            array_push($errors, ['campo' => 'captcha', 'valor' => 'Complete el captcha']);
        elseif ($captcha != $captchacheck)
            array_push($errors, ['campo' => 'captcha', 'valor' => 'Captcha incorrecto']);

        if ($apellidoMaterno == "" || $apellidoMaterno == null)
            array_push($errors, ['campo' => 'apellido-materno', 'valor' => 'Complete']);

        if (!$termino)
            array_push($errors, ['campo' => 'terminos', 'valor' => 'Acepte términos y condiciones']);





        if (count($errors) > 0)
            return $this->response->setJSON(["errors" => $errors, "status" => "error"]);

        $datos = [
            //'idsuscripcion' => null,
            'idestado' => 110,
            'correo' => $correo,
            'idpdocumento' => $pdocumento,
            'documento' => $documento,
            'nombres' => $nombre,
            'idperfil' => 2,
            'papellido' => $apellidoPaterno,
            'sapellido' => $apellidoMaterno,
            'telefono' => $telefono,
            'login' => $documento,
            'sexo' => $sexo,
            'password' => md5($documento),
        ];
        $usuario = $this->usuario->guardar($datos);


        $contenido = "";
        $contenido .= "<p>login: <strong>" . $documento  . " </strong></p>";
        $contenido .= "<p>password: <strong>" . $documento  . " </strong></p>";

        $variables = array(
            '1' => '' . $nombre . ' ' . $apellidoPaterno . ' ' . $apellidoMaterno,
            '2' => $contenido,
        );

        $newMensaje = new MensajeModel();
        $mensaje = $newMensaje->obtenerPorId(7);
        $resultado = $mensaje->contenido;
        for ($i = 1; $i <= count($variables); $i++) {
            $resultado = $this->reemplazo("{{" . $i . "}}", $variables[$i], $resultado);
        }

        $conf = new ConfiguracionModel();
        $configuracion = $conf->obtenerPorId(34);
        if ($configuracion) {
            $correoEnvio = explode(',', $configuracion->valor);
            foreach ($correoEnvio as $key => $value) {
                $this->email->setFrom("milislens@pelucasperu.com", "PELUCAS PERU");
                $this->email->setTo($correo);
                // $this->email->setBcc($value);
                $this->email->setBcc($value);
                $this->email->setSubject("Registro de Cliente - Página web");
                $this->email->setMessage($resultado);
                $this->email->send();
            }

            return $this->response->setJSON(["status" => "exito", 'usuario' => $usuario]);
        }
    }

    public function reemplazo($valor1, $valor2, $cadena)
    {
        return str_replace($valor1, $valor2, $cadena);
    }
}
