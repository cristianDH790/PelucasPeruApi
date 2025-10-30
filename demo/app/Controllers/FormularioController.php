<?php

namespace App\Controllers;

use App\Entities\Configuracion;

use App\Entities\Mensaje;
use App\Entities\Suscripcion;
use App\Helpers\Util;

class FormularioController extends BaseController
{

    private $email;
    // Constructor
    public function __construct()
    {

        $session = \Config\Services::session();
        $this->email = \Config\Services::email();
        helper('filesystem');

        $config['protocol'] = 'smtp';
        $config['charset']  = 'utf-8';
        $config['SMTPHost'] = "mail.casalingenieros.com";
        $config['SMTPUser'] = "portal@casalingenieros.com";
        $config['SMTPPass'] = "dWwyVmJMon!0";
        $config['SMTPPort'] = 587;
        $config['mailType'] = "html";
        $config['wordwrap'] = TRUE;

        $this->email->initialize($config);
    }

    public function mailContacto()
    {

        $nombres = $this->request->getPost("nombres");
        $apellidos = $this->request->getPost("apellidos");
        $correo = $this->request->getPost("correo");
        $telefono = $this->request->getPost("telefono");
        $empresa = $this->request->getPost("empresa");
        $asunto = $this->request->getPost("asunto");
        $mensaje = $this->request->getPost("mensaje");
        // $terminos = $this->request->getPost("terminos");

        $captchacheck = $_SESSION['captcha_text'];
        $captcha = strtoupper($this->request->getPost("captcha"));

        $data = [];

        //validaciones
        if ($nombres == "" || $nombres == null)
            array_push($data, ['campo' => 'nombres', 'valor' => 'Complete']);

        if ($apellidos == "" || $apellidos == null)
            array_push($data, ['campo' => 'apellidos', 'valor' => 'Complete']);

        if ($correo == "" || $correo == null)
            array_push($data, ['campo' => 'correo', 'valor' => 'Complete']);

        if ($telefono == "" || $telefono == null)
            array_push($data, ['campo' => 'telefono', 'valor' => 'Complete']);

        if ($empresa == "" || $empresa == null)
            array_push($data, ['campo' => 'empresa', 'valor' => 'Complete']);

        if ($asunto == "" || $asunto == null)
            array_push($data, ['campo' => 'asunto', 'valor' => 'Complete']);

        if ($captcha == "" || $captcha == null)
            array_push($data, ['campo' => 'captcha', 'valor' => 'Complete el captcha']);
        elseif ($captcha != $captchacheck)
            array_push($data, ['campo' => 'captcha', 'valor' => 'Captcha incorrecto']);

        if (count($data) > 0)
            echo json_encode(["data" => $data, "status" => "error"]);

        else {
            $contenido = "";
            $contenido .= "<ul><p>Nombre y Apellidos: <strong>" . $nombres . " $apellidos </strong></p>";
            $contenido .= "<p>Correo: <strong>" . $correo  . " </strong></p>";
            $contenido .= "<p>Empresa: <strong>" . $empresa  . " </strong></p>";
            $contenido .= "<p>Teléfono: <strong>" . $telefono  . " </strong></p>";
            $contenido .= "<p>Asunto: <strong>" . $asunto . "</strong></p>";
            $mensaje ? $contenido .= "<p>Mensaje: <strong>" . $mensaje . "</strong></p>" : "";
            $contenido .= "<p>Fecha: <strong>" . date('d-m-Y H:i:s')  . " </strong></p></ul>";

            // $variables = array(
            //     '1' => $contenido,
            // );

            // $newMensaje = new Mensaje();
            // $mensaje = $newMensaje->obtenerById(1);
            // $resultado = $mensaje->contenido;
            // for ($i = 1; $i <= count($variables); $i++) {
            //     $resultado = $this->reemplazo("{{" . $i . "}}", $variables[$i], $resultado);
            // }

            $conf = new Configuracion();

            $configuracion = $conf->obtenerById(1);
            if ($configuracion) {
                $correoEnvio = explode(',', $configuracion->valor);
                foreach ($correoEnvio as $key => $value) {
                    $this->email->setFrom("portal@casalingenieros.com", "Casal");
                    $this->email->setTo($value);
                    $this->email->setSubject("Contáctenos - (Página web)");
                    $this->email->setMessage($contenido);
                    // $this->email->setMessage($contenido);
                    $this->email->send();
                }
                $data = [
                    "status" => "exito",
                ];
            }
            echo json_encode($data);
        }
    }

    public function mailServicio()
    {

        $servicio = $this->request->getPost("servicio");
        $nombres = $this->request->getPost("nombres");
        $apellidos = $this->request->getPost("apellidos");
        $empresa = $this->request->getPost("empresa");
        $telefono = $this->request->getPost("telefono");
        $correo = $this->request->getPost("correo");
        $asunto = $this->request->getPost("asunto");
        $mensaje = $this->request->getPost("mensaje");

        $data = [];

        //validaciones
        if ($servicio == "" || $servicio == null)
            array_push($data, ['campo' => 'servicio', 'valor' => 'Complete']);

        if ($nombres == "" || $nombres == null)
            array_push($data, ['campo' => 'nombres', 'valor' => 'Complete']);

        if ($apellidos == "" || $apellidos == null)
            array_push($data, ['campo' => 'apellidos', 'valor' => 'Complete']);

        if ($correo == "" || $correo == null)
            array_push($data, ['campo' => 'correo', 'valor' => 'Complete']);

        if ($telefono == "" || $telefono == null)
            array_push($data, ['campo' => 'telefono', 'valor' => 'Complete']);

        if ($empresa == "" || $empresa == null)
            array_push($data, ['campo' => 'empresa', 'valor' => 'Complete']);

        if ($asunto == "" || $asunto == null)
            array_push($data, ['campo' => 'asunto', 'valor' => 'Complete']);

        if (count($data) > 0)
            echo json_encode(["data" => $data, "status" => "error"]);

        else {
            $contenido = "";
            $contenido .= "<ul><h3>Servicio: <strong> $servicio </strong></h3>";
            $contenido .= "<p>Nombre y Apellidos: <strong>" . $nombres . " $apellidos </strong></p>";
            $contenido .= "<p>Correo: <strong>" . $correo  . " </strong></p>";
            $contenido .= "<p>Empresa: <strong>" . $empresa  . " </strong></p>";
            $contenido .= "<p>Teléfono: <strong>" . $telefono  . " </strong></p>";
            $contenido .= "<p>Asunto: <strong>" . $asunto . "</strong></p>";
            $mensaje ? $contenido .= "<p>Mensaje: <strong>" . $mensaje . "</strong></p>" : "";
            $contenido .= "<p>Fecha: <strong>" . date('d-m-Y H:i:s')  . " </strong></p></ul>";

            // $variables = array(
            //     '1' => $contenido,
            // );

            // $newMensaje = new Mensaje();
            // $mensaje = $newMensaje->obtenerById(2);
            // $resultado = $mensaje->contenido;
            // for ($i = 1; $i <= count($variables); $i++) {
            //     $resultado = $this->reemplazo("{{" . $i . "}}", $variables[$i], $resultado);
            // }

            $conf = new Configuracion();

            $configuracion = $conf->obtenerById(3);
            if ($configuracion) {
                $correoEnvio = explode(',', $configuracion->valor);
                foreach ($correoEnvio as $key => $value) {
                    $this->email->setFrom("portal@casalingenieros.com", "Casal");
                    $this->email->setTo($value);
                    $this->email->setSubject("Solicitar servicio - (Página web)");
                    $this->email->setMessage($contenido);
                    // $this->email->setMessage($contenido);
                    $this->email->send();
                }
                $data = [
                    "status" => "exito",
                ];
            }
            echo json_encode($data);
        }
    }


    public function reemplazo($valor1, $valor2, $cadena)
    {
        return str_replace($valor1, $valor2, $cadena);
    }
}
