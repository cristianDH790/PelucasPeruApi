<?php

namespace App\Controllers;

use App\Entities\Producto;
use App\Helpers\Paginator;
use App\Models\ComentarioModel;
use App\Models\ValoracionModel;

class ComentarioController extends BaseController
{
    protected $session;
    public function __construct()
    {
        $this->session = \Config\Services::session();
    }
    public function guardarComentario()
    {
        $request = $this->request;

        $idcomentario   = $request->getPost('idcomentario') ?? 0;
        $idrcomentario  = $request->getPost('idrcomentario') ?: null;
        $idestado       = $request->getPost('idestado') ?? 427;
        $idusuario      = $request->getPost('idusuario');
        $idclase        = $request->getPost('idclase');
        $idreferencia   = $request->getPost('idreferencia');
        $contenido      = trim($request->getPost('comentario')); // ← coincide con el name="comentario" de tu formulario

        $captchacheck = $_SESSION['captcha_text'];
        $captcha = strtoupper($this->request->getPost("captcha"));

        $data = [];

        $data = [];

        // Validaciones
        if (!$idusuario) {
            $data[] = ['campo' => 'usuario', 'valor' => 'Debe iniciar sesión para comentar.'];
        }

        if (empty($contenido)) {
            $data[] = ['campo' => 'comentario', 'valor' => 'Ingrese un comentario.'];
        }

        if ($captcha == "" || $captcha == null)
            array_push($data, ['campo' => 'captcha', 'valor' => 'Complete el captcha']);
        elseif ($captcha != $captchacheck)
            array_push($data, ['campo' => 'captcha', 'valor' => 'Captcha incorrecto']);

        if (count($data) > 0) {
            return $this->response->setJSON(["data" => $data, "status" => "error"]);
        }

        // Guardar comentario
        $comentarioModel = new ComentarioModel();

        $datos = [
            'idestado'      => $idestado,
            'idusuario'     => $idusuario,
            'idrcomentario' => $idrcomentario,
            'idclase'       => $idclase,
            'idreferencia'  => $idreferencia,
            'contenido'     => $contenido,
        ];

        // Si edita comentario
        if ($idcomentario != 0) {
            $datos['idcomentario'] = $idcomentario;
        }

        $comentarioModel->guardar($datos);

        return $this->response->setJSON(["status" => "exito"]);
    }


    // public function eliminarComentario($idcomentario)
    // {
    //     $comentarioModel = new ComentarioModel();
    //     $comentario = $comentarioModel->obtenerById($idcomentario);

    //     if (!$comentario) {
    //         return $this->response->setJSON(["status" => "error", "mensaje" => "Comentario no encontrado."]);
    //     }

    //     // Buscar respuestas del comentario y eliminarlas en cascada
    //     $respuestas = $comentarioModel->buscarPor("", "", "", "", 0, 0, 0, $comentario->idcomentario, 0, 0, 0);

    //     if ($respuestas) {
    //         foreach ($respuestas as $respuesta) {
    //             $subRespuestas = $comentarioModel->buscarPor("", "", "", "", 0, 0, 0, $respuesta->idcomentario, 0, 0, 0);

    //             if ($subRespuestas) {
    //                 foreach ($subRespuestas as $sub) {
    //                     $comentarioModel->eliminar($sub->idcomentario);
    //                 }
    //             }
    //             $comentarioModel->eliminar($respuesta->idcomentario);
    //         }
    //     }

    //     $comentarioModel->eliminar($comentario->idcomentario);

    //     return $this->response->setJSON(["status" => "exito"]);
    // }
    public function eliminarComentario()
    {
        $idcomentario = $this->request->getPost('idcomentario');

        if (!$idcomentario) {
            return $this->response->setJSON([
                "status" => "error",
                "mensaje" => "ID de comentario no proporcionado."
            ]);
        }

        $comentarioModel = new ComentarioModel();
        $comentario = $comentarioModel->obtenerById($idcomentario);

        if (!$comentario) {
            return $this->response->setJSON([
                "status" => "error",
                "mensaje" => "Comentario no encontrado."
            ]);
        }

        // ✅ Soporte para array u objeto
        $idComentarioActual = is_array($comentario) ? $comentario['idcomentario'] : $comentario->idcomentario;

        // Buscar respuestas del comentario (por idrcomentario)
        $respuestas = $comentarioModel->buscarPor("", "", "", "", 0, 0, 0, $idComentarioActual, 0, 0, 0);

        if ($respuestas) {
            foreach ($respuestas as $respuesta) {
                $idRespuesta = is_array($respuesta) ? $respuesta['idcomentario'] : $respuesta->idcomentario;

                // Subrespuestas
                $subRespuestas = $comentarioModel->buscarPor("", "", "", "", 0, 0, 0, $idRespuesta, 0, 0, 0);

                if ($subRespuestas) {
                    foreach ($subRespuestas as $sub) {
                        $idSub = is_array($sub) ? $sub['idcomentario'] : $sub->idcomentario;
                        $comentarioModel->eliminar($idSub);
                    }
                }

                $comentarioModel->eliminar($idRespuesta);
            }
        }

        // Eliminar comentario principal
        $comentarioModel->eliminar($idComentarioActual);

        return $this->response->setJSON([
            "status" => "exito",
            "mensaje" => "Comentario eliminado correctamente."
        ]);
    }



    public function comentarios()
    {
        $request = $this->request;

        // Convierte a enteros los valores numéricos
        $idclase        = (int) ($request->getPost('idclase') ?? 0);
        $idreferencia   = (int) ($request->getPost('idreferencia') ?? 0);
        $idusuario      = (int) ($request->getPost('idusuario') ?? 0);
        $inicio         = (int) ($request->getPost('inicio') ?? 0);
        $registros      = (int) ($request->getPost('registros') ?? 10);

        $parametro      = trim($request->getPost('parametro') ?? "");
        $valor          = trim($request->getPost('valor') ?? "");
        $ordencriterio  = $request->getPost('ordencriterio') ?? "comentario.fecha";
        $ordentipo      = $request->getPost('ordentipo') ?? "DESC";
        $idestado       = 427;
        $idrcomentario  = 0;

        $comentarioModel = new ComentarioModel();

        // Total de comentarios
        $total = $comentarioModel->buscarTotalPor(
            $parametro,
            $valor,
            $idestado,
            $idusuario,
            $idclase,
            $idrcomentario,
            $idreferencia
        );

        // Lista de comentarios
        $comentarios = $comentarioModel->buscarPor(
            $ordencriterio,
            $ordentipo,
            $parametro,
            $valor,
            $idestado,
            $idusuario,
            $idclase,
            $idrcomentario,
            $idreferencia,
            $inicio,
            $registros
        );

        $data = [];
        foreach ($comentarios as $row) {
            $data[] = [
                'idcomentario' => $row['idcomentario'] ?? null,
                'usuario'      => trim(($row['miembronombre'] ?? '') . ' ' . ($row['miembropapellido'] ?? '')),
                'fecha'        => date('d M Y', strtotime($row['fecha'] ?? date('Y-m-d'))),
                'contenido'    => $row['contenido'] ?? '',
                'idusuario'    => $row['idusuario'] ?? null,
                'estado'       => $row['estado'] ?? '',
            ];
        }

        return $this->response->setJSON([
            'status' => 'exito',
            'total'  => $total,
            'data'   => $data
        ]);
    }
}
