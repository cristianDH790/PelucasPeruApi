<?php

namespace App\Models;

use App\Entities\UsuarioEntity;
use CodeIgniter\Model;

class UsuarioModel extends Model
{
    protected $table            = 'usuario';
    protected $primaryKey       = 'idusuario';
    protected $useAutoIncrement = true;
    protected $returnType       = UsuarioEntity::class;
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'idestado',
        'idperfil',
        'idpdocumento',
        'documento',
        'nombres',
        'papellido',
        'sapellido',
        'fechanacimiento',
        'sexo',
        'correo',
        'telefono',
        'login',
        'password',
        'fecha',
    ];

    protected bool $allowEmptyInserts = false;
    protected bool $updateOnlyChanged = true;

    protected array $casts = [];
    protected array $castHandlers = [];

    // Dates
    protected $useTimestamps = false;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'fecha';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    // Validation
    protected $validationRules      = [];
    protected $validationMessages   = [];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert   = [];
    protected $afterInsert    = [];
    protected $beforeUpdate   = [];
    protected $afterUpdate    = [];
    protected $beforeFind     = [];
    protected $afterFind      = [];
    protected $beforeDelete   = [];
    protected $afterDelete    = [];

    public function login($login, $clave)
    {
        $usuario = new UsuarioModel();
        $usuario->where('login', $login);
        // $usuario->where('password', $clave);

        $usuario->select("usuario.*, (select perfil.nombre from perfil where perfil.idperfil=usuario.idperfil limit 1) as perfil,
        (select parametro.nombre from parametro where parametro.idparametro=usuario.idpdocumento limit 1) as pdocumento");

        return $usuario->first();
    }
    public function autenticar($usuario)
    {
        return $this->where('login', $usuario)
            ->whereIn('idperfil', [1, 2])
            ->first();
    }
    public function obtenerPorEmail($correo)
    {
        return $this->where('correo', $correo)->first();
    }

    public function obtenerPorCorreo($correo, $idusuario)
    {
        $usuario = new UsuarioModel();
        $usuario->where('correo', $correo);
        if ($idusuario > 0)
            $usuario->where('idusuario', $idusuario);

        return $usuario->first();
    }
    public  function obtenerPorDocumento($documento, $idusuario)
    {
        $usuario = new UsuarioModel();
        $usuario->where('documento', $documento);
        if ($idusuario > 0)
            $usuario->where('idusuario', $idusuario);

        return $usuario->first();
    }

    public function obtenerPorId($idusuario)
    {
        return $this->find($idusuario);
    }

    public function eliminar($idusuario): bool
    {
        $this->db->transStart();
        try {
            if (!$this->where('idusuario', $idusuario)->first()) {
                return false;
            }

            $resultado = $this->delete($idusuario);

            $this->db->transComplete();
            return $resultado;
        } catch (\Throwable $e) {
            $this->db->transRollback();
            log_message('error', 'Eliminar usuario falló: ' . $e->getMessage());
            return false;
        }
    }

    public function guardar($data): int
    {
        $this->db->transStart();
        try {
            if (empty($data['idusuario'])) {
                $this->insert($data);
                $id = $this->getInsertID();
            } else {
                $this->update($data['idusuario'], $data);
                $id = $data['idusuario'];
            }

            // Verifica si ya existe relación usuario-rol
            // Conexión DB para el builder
            $db = \Config\Database::connect();
            $builder = $db->table('usuario_rol');
            $existe = $builder->where('idusuario', $id)->get()->getRow();

            $datosRol = [
                'idusuario' => $id,
                'idrol'     => $data['idperfil']
            ];

            if ($existe) {
                // Si ya tiene un rol, actualiza
                $builder->where('idusuario', $id)->update($datosRol);
            } else {
                // Si no tiene, lo inserta
                $builder->insert($datosRol);
            }

            $this->db->transComplete();
            return $id;
        } catch (\Throwable $e) {
            $this->db->transRollback();
            log_message('error', 'Error en guardar: ' . $e->getMessage());
            throw $e;
        }
    }



    public function buscarPor($ordencriterio, $ordentipo, $parametro, $valor, $idestado, $idperfil, $inicio, $registros)
    {
        $builder = $this->db->table($this->table);
        $builder->select('*');

        // Filtro por búsqueda
        if (!empty($parametro) && !empty($valor)) {
            $builder->like($parametro, trim($valor), 'both');
        }
        // Filtros por ID
        if ($idestado > 0) $builder->where('idestado', $idestado);
        // if ($idperfil > 0) $builder->where('idperfil', $idperfil);


        if ($idperfil > 0) {
            $builder->where('idperfil', $idperfil);
        }
        if ($idperfil == -100) {
            $builder->where('idperfil !=', 3); // Excluir perfil 3
        }
        // if ($fecharango) {
        //     $arr = explode(" - ", $fecharango);
        //     if (count($arr) == 2) {
        //         $builder->where('fecha >=', date("Y-m-d", strtotime($arr[0])));
        //         $builder->where('fecha <=', date("Y-m-d", strtotime($arr[1])));
        //     }
        // }

        // Ordenamiento
        if (!empty($ordencriterio) && !empty($ordentipo)) $builder->orderBy($ordencriterio, $ordentipo);


        if ($registros > 0) {
            $builder->limit($registros, $inicio);
        }

        return $builder->get()->getResult();
    }
    public function buscarPorTotal($parametro, $valor,  $idestado, $idperfil)
    {
        $builder = $this->db->table($this->table);

        // Filtro por búsqueda
        if (!empty($parametro) && !empty($valor)) {
            $builder->like($parametro, trim($valor), 'both');
        }

        //Filtros por ID
        if ($idestado > 0)  $builder->where('idestado', $idestado);
        if ($idperfil > 0) $builder->where('idperfil', $idperfil);
        if ($idperfil == -100) {
            $builder->where('idperfil !=', 6); // Excluir perfil 6
        }
        // if ($fecharango) {
        //     $arr = explode(" - ", $fecharango);
        //     if (count($arr) == 2) {
        //         $builder->where('fecha >=', date("Y-m-d", strtotime($arr[0])));
        //         $builder->where('fecha <=', date("Y-m-d", strtotime($arr[1])));
        //     }
        // }

        return $builder->countAllResults();
    }

    public function obtenerImporteTotal($idUsuario)
    {
        $pedidoModel = new \App\Models\PedidoModel();
        // Accede directamente al resultado de la consulta y devuelve el valor de 'totalImporte'
        return (float) $pedidoModel->where('idusuario', $idUsuario)
            ->selectSum('total', 'totalImporte')
            ->first()->totalImporte ?? 0;
    }





    public function obtenerPedidos($idUsuario)
    {
        $pedidoModel = new \App\Models\PedidoModel();

        // Traemos todos los pedidos de este usuario
        return $pedidoModel->where('idusuario', $idUsuario)
            ->countAllResults();
    }
}
