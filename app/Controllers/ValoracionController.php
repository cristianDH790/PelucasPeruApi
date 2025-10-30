<?php

namespace App\Controllers;

use App\Entities\Producto;
use App\Helpers\Paginator;
use App\Models\ValoracionModel;

class ValoracionController extends BaseController
{

    // public function valorarPublicacion()
    // {
    //     $json = $this->request->getJSON(true); // <- true para obtener array asociativo

    //     $idUsuario = $json["idUsuario"] ?? null;
    //     $idproducto = $json["idProducto"] ?? null;
    //     $valor = $json["valoracion"] ?? null;

    //     // Debug
    //     // var_dump($json, $idproducto); die();

    //     $val = new ValoracionModel();

    //     // Buscar si ya existe una valoración
    //     $valoracion = $val->buscarPor("", "", "", "", 425, 341, $idUsuario, 0, $idproducto, 0, 0);
    //     log_message('info', '🔍 Valoración encontrada: ' . json_encode($valoracion));

    //     if ($valoracion && count($valoracion) > 0) {
    //         $data = [
    //             "status" => "error",
    //             "mensaje" => "Ya ha valorado esta publicación"
    //         ];
    //     } else {
    //         $datos = [
    //             'idestado' => 425,
    //             'idrvaloracion' => null,
    //             'idclase' => 341,
    //             'idreferencia' => $idproducto,
    //             'idusuario' => $idUsuario,
    //             'valor' => $valor,
    //         ];

    //         $idvaloracion = $val->guardar($datos);

    //         $data = [
    //             "status" => "exito"
    //         ];
    //     }

    //     return $this->response->setJSON($data);
    // }
    public function valorarPublicacion()
    {
        $json = $this->request->getJSON(true);

        $idUsuario = $json["idUsuario"] ?? null;
        $idproducto = $json["idProducto"] ?? null;
        $valor = $json["valoracion"] ?? null;

        $val = new ValoracionModel();

        // Buscar si ya existe una valoración
        $valoracion = $val->buscarPor("", "", "", "", 425, 341, $idUsuario, 0, $idproducto, 0, 0);
        log_message('info', '🔍 Valoración encontrada: ' . json_encode($valoracion));

        if ($valoracion && count($valoracion) > 0) {
            // Si el modelo devuelve objetos
            $registro = is_array($valoracion) ? $valoracion[0] : $valoracion;
            $idvaloracion = is_object($registro) ? $registro->idvaloracion : $registro['idvaloracion'];

            $val->update($idvaloracion, [
                'valor' => $valor,
                'fecha' => date('Y-m-d H:i:s')
            ]);

            $data = [
                "status" => "actualizado",
                "mensaje" => "Valoración actualizada correctamente."
            ];
        } else {
            $datos = [
                'idestado' => 425,
                'idrvaloracion' => null,
                'idclase' => 341,
                'idreferencia' => $idproducto,
                'idusuario' => $idUsuario,
                'valor' => $valor,
                'fecha' => date('Y-m-d H:i:s')
            ];

            $val->insert($datos);

            $data = [
                "status" => "exito",
                "mensaje" => "Valoración registrada correctamente."
            ];
        }

        return $this->response->setJSON($data);
    }
}
