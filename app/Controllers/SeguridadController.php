<?php

namespace App\Controllers;

use App\Entities\Configuracion;
use App\Entities\ListaDeseo;
use App\Entities\Mensaje;
use App\Entities\Usuario;
use App\Entities\UsuarioEntity;
use App\Models\ConfiguracionModel;
use App\Models\EstadoModel;
use App\Models\MensajeModel;
use App\Models\ParametroModel;
use App\Models\PerfilModel;
use App\Models\UsuarioModel;
use CodeIgniter\Session\Session;

class SeguridadController extends BaseController
{
    private $email;
    protected $usuario;
    protected $mensaje;
    protected $configuracion;
    protected $session;
    protected $estado;
    protected $parametro;
    protected $perfil;

    public function __construct()
    {
        helper('session');

        //$this->session = \Config\Services::session(); // ✅ Correcto


        date_default_timezone_set('America/Lima');


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


        $this->email->initialize($config);

        $this->usuario = new UsuarioModel();
        $this->mensaje = new MensajeModel();
        $this->configuracion = new ConfiguracionModel();
        $this->estado = new EstadoModel();
        $this->perfil = new PerfilModel();
        $this->parametro = new ParametroModel();
    }

    public function usuarioGuardar()
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

            if (empty($correo))
                array_push($errores, ['campo' => 'correo', 'valor' => 'Complete.']);

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
                'sexo' => $sexo,
                'fechanacimiento' => $fechaNacimiento,
                'correo' => $correo,
                'telefono' => $telefono,

            ];


            $idusuario = $this->usuario->guardar($datos);

            $data = [
                "status" => "exito",
                "idUsuario" => $idusuario,
            ];
            return $this->response->setJSON($data);
        }
    }

    public function registrarUsuario()
    {
        $errores = [];

        $correo = $this->request->getPost('correo');
        $nombres = $this->request->getPost('nombres');
        $pApellido = $this->request->getPost('pApellido');
        $sApellido = $this->request->getPost('sApellido');
        $ptipodoc = $this->request->getPost('ptipodoc');
        $documento = $this->request->getPost('documento');
        $telefono = $this->request->getPost('telefono');
        $sexo = $this->request->getPost('sexo');
        $fechaNacimiento = $this->request->getPost('fechaNacimiento');
        $terminos = $this->request->getPost('terminos');
        $boletin = $this->request->getPost('boletin');

        /*Validaciones */

        if (empty($correo))
            array_push($errores, ['campo' => 'correo', 'valor' => 'Complete.']);
        else {
            $existe = $this->usuario->obtenerPorCorreo($correo, 0);
            if ($existe)
                array_push($errores, ['campo' => 'correo', 'valor' => 'Correo ya registrado.']);
        }

        if (empty($nombres))
            array_push($errores, ['campo' => 'nombres', 'valor' => 'Complete.']);

        if (empty($pApellido))
            array_push($errores, ['campo' => 'pApellido', 'valor' => 'Seleccione.']);

        if (empty($ptipodoc))
            array_push($errores, ['campo' => 'ptipodoc', 'valor' => 'Complete.']);

        if (empty($documento))
            array_push($errores, ['campo' => 'documento', 'valor' => 'Complete.']);
        else {
            $existe = $this->usuario->obtenerPorDocumento($documento, 0);
            if ($existe)
                array_push($errores, ['campo' => 'documento', 'valor' => 'El usuario ya se encuentra registrado.']);
        }

        if (empty($telefono))
            array_push($errores, ['campo' => 'telefono', 'valor' => 'Complete.']);

        // if (empty($sexo))
        //     array_push($errores, ['campo' => 'sexo', 'valor' => 'Complete.']);

        if (empty($fechaNacimiento))
            array_push($errores, ['campo' => 'fechaNacimiento', 'valor' => 'Complete.']);

        if (empty($terminos))
            array_push($errores, ['campo' => 'terminos', 'valor' => 'Complete.']);

        if (count($errores) > 0)
            return $this->response->setJSON(["errors" => $errores, "status" => "error"]);

        $datos = [
            'idusuario' => null,
            'idestado' => 110,
            'idperfil' => 2,
            'nombres' => $nombres,
            'papellido' => $pApellido,
            'sapellido' => $sApellido,
            'idpdocumento' => $ptipodoc,
            'documento' => $documento,
            'telefono' => $telefono,
            'correo' => $correo,
            'sexo' => $sexo,
            'fechanacimiento' => $fechaNacimiento,
            'boletin' => $boletin == "on" ? 1 : 0,
            'login' => $documento,
            'password' => md5($documento),
        ];

        $idusuario = $this->usuario->guardar($datos);
        $usuario = $this->usuario->obtenerPorId($idusuario);
        $usuarioEntity = new UsuarioEntity($usuario);
        $usuarioEntity->estado = (object)$this->estado->obtenerPorId($usuario->idestado);
        $usuarioEntity->perfil = (object) $this->perfil->obtenerPorId($usuario->idperfil);
        $usuarioEntity->pdocumento = (object) $this->parametro->obtenerPorId($usuario->idpdocumento);


        if ($usuario) {
            session()->set('usuarioSesion', $usuarioEntity);
            $data = [
                "status" => "exito",
                "usuario" => $usuario,
                "sss" => $boletin
            ];
            $contenido = "";
            $contenido .= "<p>login: <strong>" . $documento  . " </strong></p>";
            $contenido .= "<p>password: <strong>" . $documento  . " </strong></p>";

            $variables = array(
                '1' => '' . $nombres . ' ' . $pApellido . ' ' . $sApellido,
                '2' => $contenido,
            );


            $mensaje = $this->mensaje->obtenerPorId(7);
            $resultado = $mensaje->contenido;
            for ($i = 1; $i <= count($variables); $i++) {
                $resultado = $this->reemplazo("{{" . $i . "}}", $variables[$i], $resultado);
            }


            $configuracion = $this->configuracion->obtenerPorId(34);
            if ($configuracion) {


                $correoEnvio = explode(',', $configuracion->valor);
                foreach ($correoEnvio as $key => $value) {
                    $this->email->setFrom("milislens@pelucasperu.com", "PELUCAS PERU");
                    $this->email->setTo($correo);
                    // $this->email->setBcc($value);
                    $this->email->setBcc($value);
                    $this->email->setSubject("Registro de Cliente - PELUCAS PERU");
                    $this->email->setMessage($resultado);
                    $this->email->send();
                }

                return $this->response->setJSON(["status" => "exito", 'usuario' => $usuario]);
            }



            // log_message('error', 'Session set usuarioSesion: ' . print_r($this->session->get('usuarioSesion'), true));
        } else {
            return $this->response->setJSON(["status" => "error", 'mensaje' => "Error al registrar usuario"]);
        }
        return $this->response->setJSON($data);
    }

    public function login()
    {
        $errores = [];

        $loginUsuario = $this->request->getPost('login-usuario');

        /*Validaciones */
        if (empty($loginUsuario))
            array_push($errores, ['campo' => 'login-usuario', 'valor' => 'Complete']);
        /*========= */

        if (count($errores) > 0)
            return $this->response->setJSON(["errors" => $errores, "status" => "error"]);


        $usuario = $this->usuario->login($loginUsuario, md5($loginUsuario));
        if (!$usuario) {
            array_push($errores, ['campo' => 'login-usuario', 'valor' => 'Usuario no encontrado.']);
            return $this->response->setJSON(["errors" => $errores, "status" => "error"]);
        } else {
            $usuarioEntity = new UsuarioEntity($usuario);
            $usuarioEntity->estado = (object) $this->estado->obtenerPorId($usuario->idestado);
            $usuarioEntity->perfil =  (object)$this->perfil->obtenerPorId($usuario->idperfil);
            $usuarioEntity->pdocumento =  (object)$this->parametro->obtenerPorId($usuario->idpdocumento);
            session()->set('usuarioSesion',  $usuarioEntity);

            return $this->response->setJSON(["usuario" => $usuario, "status" => "exito"]);
        }
    }

    public function cerrarSesion()
    {

        session()->destroy();

        return $this->response->setJSON(['status' => 'exito']);
    }

    public function reemplazo($valor1, $valor2, $cadena)
    {
        return str_replace($valor1, $valor2, $cadena);
    }
}
