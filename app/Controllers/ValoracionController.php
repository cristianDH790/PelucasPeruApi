<?php

namespace App\Controllers;

use App\Entities\Producto;
use App\Helpers\Paginator;
use App\Models\ValoracionModel;

class ValoracionController extends BaseController
{

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

    // ✅ Obtener resumen de valoraciones por idreferencia
    public function resumen($idreferencia = null)
    {
        if ($idreferencia === null) {
            return $this->fail('Debe especificar un idreferencia.');
        }

        $model = new ValoracionModel();
        $data = $model->obtenerResumenValoraciones($idreferencia);

        return $this->response->setJSON($data);
    }


    public function obtenerValoracionUsuario()
    {
        $json = $this->request->getJSON(true);
        $idProducto = $json['idProducto'] ?? 0;
        $idUsuario = $json['idUsuario'] ?? 0;
        $ValoracionModel = new ValoracionModel();
        $valoracion = $ValoracionModel
            ->where('idreferencia', $idProducto)
            ->where('idusuario', $idUsuario)
            ->first();

        if ($valoracion) {
            return $this->response->setJSON([
                'status' => 'exito',
                'valoracion' => $valoracion['valor'] // 👈 asegúrate que el campo se llame 'valor' o 'valoracion' según tu tabla
            ]);
        } else {
            return $this->response->setJSON([
                'status' => 'sin_valoracion'
            ]);
        }
    }
}
