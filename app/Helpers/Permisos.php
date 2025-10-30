<?php

namespace App\Helpers;  // o App\Services

use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use App\Models\OpcionModel;

class Permisos
{
    public function obtenerPermisosDesdeToken(string $token): array
    {
        if (!$token) {
            return ['error' => 'Token no proporcionado'];
        }

        try {
            $key = getenv('JWT_SECRET');
            $decoded = JWT::decode($token, new Key($key, 'HS256'));
            $userId = $decoded->sub;

            $opcionModel = new OpcionModel();
            $permisos = $opcionModel->listarCodigosPorUsuario($userId);

            return ['authorities' => $permisos];
        } catch (\Exception $e) {
            return ['error' => 'Token inválido: ' . $e->getMessage()];
        }
    }
    
}
