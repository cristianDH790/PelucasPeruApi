<?php

namespace App\Controllers;

use App\Entities\Mensaje;
use App\Entities\Usuario;
use App\Entities\UsuarioEntity;
use App\Models\EstadoModel;
use App\Models\MensajeModel;
use App\Models\ParametroModel;
use App\Models\PerfilModel;
use App\Models\UsuarioModel;

class UsuarioController extends BaseController
{

    private $email;
    private $estado;
    private $parametro;
    private $perfil;

    public function __construct()
    {
        date_default_timezone_set('America/Lima');

        $session = \Config\Services::session();
        $this->email = \Config\Services::email();
        helper('filesystem');


        $config['protocol'] = 'smtp';
        $config['charset']  = 'utf-8';
        $config['SMTPHost'] = getenv('SMTP_HOST');       // mail.superhostingdelta.com
        $config['SMTPUser'] = getenv('SMTP_USER');       // portal@superhostingdelta.com
        $config['SMTPPass'] = getenv('SMTP_PASS');       // x3Xa$yx(+[BNcCb2
        $config['SMTPPort'] = (int) getenv('SMTP_PORT');       // 587
        $config['SMTPTimeout'] = 30;                      // Opcional, timeout en segundos
        $config['mailType'] = 'html';
        $config['wordwrap'] = true;

        $this->parametro = new ParametroModel();
        $this->estado = new EstadoModel();
        $this->perfil = new PerfilModel();
        $this->email->initialize($config);
    }

    public function usuarioEditar()
    {
        if ($usuario = session()->get('usuarioSesion')) {
            $errores = [];

            $idusuario = $usuario->idusuario;
            $nombres = $this->request->getPost('nombres');
            $pApellido = $this->request->getPost('pApellido');
            $sApellido = $this->request->getPost('sApellido');
            $sexo = $this->request->getPost('sexo');
            $fechaNacimiento = $this->request->getPost('fechaNacimiento');
            $correo = $this->request->getPost('correo');
            $telefono = $this->request->getPost('telefono');
            $boletin = $this->request->getPost('boletin');

            /*Validaciones */

            if (empty($idusuario))
                array_push($errores, ['campo' => 'idUsuario', 'valor' => 'Complete.']);

            if (empty($nombres))
                array_push($errores, ['campo' => 'nombres', 'valor' => 'Complete.']);

            if (empty($pApellido))
                array_push($errores, ['campo' => 'pApellido', 'valor' => 'Complete.']);

            if (empty($sexo))
                array_push($errores, ['campo' => 'sexo', 'valor' => 'Seleccione.']);

            if (empty($fechaNacimiento))
                array_push($errores, ['campo' => 'fechaNacimiento', 'valor' => 'Complete.']);

            if (empty($telefono))
                array_push($errores, ['campo' => 'telefono', 'valor' => 'Complete.']);


            if (count($errores) > 0) {
                return $this->response->setJSON(["errors" => $errores, "status" => "error"]);
                return;
            }

            $datos = [
                'idusuario' => $idusuario,
                'nombres' => $nombres,
                'papellido' => $pApellido,
                'sapellido' => $sApellido,
                'idperfil' => 2,
                'sexo' => $sexo,
                'fechanacimiento' => $fechaNacimiento,
                'telefono' => $telefono,
                'boletin' => $boletin == "1" ? 1 : 0,
            ];

            $newUsuario = new UsuarioModel();
            $usuarioid = $newUsuario->guardar($datos);
            $usuario =   $newUsuario->obtenerPorId($usuarioid);


            $usuarioEntity = new UsuarioEntity($usuario);
            $usuarioEntity->estado = (object)$this->estado->obtenerPorId($usuario->idestado);
            $usuarioEntity->perfil = (object) $this->perfil->obtenerPorId($usuario->idperfil);
            $usuarioEntity->pdocumento = (object) $this->parametro->obtenerPorId($usuario->idpdocumento);


            if ($usuario) {
                $data = [
                    "status" => "exito",
                    "boletin" => $boletin,
                ];

                $informacion = "<ul>
                    <li>Nombres y apellidos: " . $usuario->nombres . " " . $usuario->papellido . " " . $usuario->sapellido . "</li>
                    <li>DNI: " . $usuario->documento  . "</li>
                    <li>Correo: " . $usuario->correo  . "</li>
                    <li>Teléfono: " . $usuario->telefono  . "</li>
                    <li>Sexo: " . $usuario->sexo  . "</li>
                    <li>Fecha de nacimiento: " . date('d-m-Y', strtotime($usuario->fechanacimiento)) . "</li>
                </ul>";

                $variables = array(
                    '1' => $usuario->nombres . ' ' . $usuario->papellido,
                    '2' => $usuario->login,
                    '3' => $informacion
                );

                $newMensaje = new MensajeModel();
                $mensaje = $newMensaje->obtenerPorId(11);
                $resultado = $mensaje->contenido;

                for ($i = 1; $i <= count($variables); $i++) {
                    $resultado = $this->reemplazo("{{" . $i . "}}", $variables[$i], $resultado);
                }


                $this->email->setFrom("milislens@pelucasperu.com", "PELUCAS PERU");
                $this->email->setTo($usuario->correo);
                $this->email->setMessage($resultado);
                $this->email->setSubject($mensaje->asunto);
                // $this->email->setTo("desarrollo1@onlinesolutions.com.pe");
                $this->email->send();

                session()->set('usuarioSesion', $usuarioEntity);

                return $this->response->setJSON($data);
            } else {
                $data = [
                    "status" => "error",
                ];
                return $this->response->setJSON($data);
            }
        }
    }


    public function reemplazo($valor1, $valor2, $cadena)
    {
        return str_replace($valor1, $valor2, $cadena);
    }
}
