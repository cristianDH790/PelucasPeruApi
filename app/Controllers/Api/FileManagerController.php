<?php

namespace App\Controllers\Api;

use CodeIgniter\API\ResponseTrait;

use Config\Storage;

use CodeIgniter\RESTful\ResourceController;


class FileManagerController extends ResourceController
{
    use ResponseTrait;

    protected $storage;

    public function __construct()
    {
        $this->storage = new Storage();
        helper('filesystem');
    }


    public function getCarpetasTodas($carpeta = null)
    {
        $nombreCarpeta = $this->request->getVar('nombreCarpeta');

        $basePath = rtrim($this->storage->archivosRoot, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR;
        if ($nombreCarpeta) {
            $basePath .= rtrim($nombreCarpeta, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR;
        }

        // $directories = $this->getAllDirectories($basePath, $basePath);
        $directories = $this->getAllDirectories($basePath, $basePath, $nombreCarpeta ?? '');


        $carpetas = [];

        foreach ($directories as $rutaRelativa) {
            $rutaCompleta = $basePath . str_replace($nombreCarpeta . DIRECTORY_SEPARATOR, '', $rutaRelativa);

            $partes = explode(DIRECTORY_SEPARATOR, $rutaRelativa);
            $nivel = count($partes);
            $nombre = end($partes);

            // Chequear si tiene subcarpetas
            $sub = scandir($rutaCompleta);
            $tieneHijos = false;
            foreach ($sub as $item) {
                if ($item === '.' || $item === '..') continue;
                if (is_dir($rutaCompleta . DIRECTORY_SEPARATOR . $item)) {
                    $tieneHijos = true;
                    break;
                }
            }

            if ($nivel > 1) {
                $padre = $partes[0];  // Primer segmento será la carpeta base, ej: 'curso'
            } else {
                $padre = '';
            }

            $data = [
                "nombre" => $nombre,
                "ruta" => str_replace(DIRECTORY_SEPARATOR, '/', $rutaRelativa),
                "nivel" => $nivel,
                "padre" => $padre,
                "subCarpeta" => $tieneHijos,
            ];

            $carpetas[] = $data;
        }

        return $this->respond(["carpetas" => $carpetas]);
    }




    private function getAllDirectories($path, $basePath, $nombreCarpeta = '')
    {
        $directories = [];

        return $this->respond(["ruta" => $path, "rutabase" => $basePath, "nombreCarpeta" => $nombreCarpeta]);
        $items = scandir($path);
        foreach ($items as $item) {
            if ($item === '.' || $item === '..') {
                continue;
            }
            $fullPath = $path . DIRECTORY_SEPARATOR . $item;
            if (is_dir($fullPath)) {
                // Obtener ruta relativa respecto a basePath
                $relativePath = substr($fullPath, strlen($basePath));
                $relativePath = ltrim($relativePath, DIRECTORY_SEPARATOR);

                // Si existe $nombreCarpeta, agrégalo al principio para que ruta completa incluya esa carpeta
                if ($nombreCarpeta !== '') {
                    $relativePath = $nombreCarpeta . DIRECTORY_SEPARATOR . $relativePath;
                }

                $directories[] = $relativePath;

                // Recursivamente obtener subdirectorios
                $subDirs = $this->getAllDirectories($fullPath, $basePath, $nombreCarpeta);
                $directories = array_merge($directories, $subDirs);
            }
        }

        return $directories;
    }



    public function getArchivos()
    {
        $ruta = $this->request->getVar('ruta');
        $path = $this->storage->archivosRoot . '/' . $ruta;


        if (!is_dir($path)) {
            return $this->failNotFound("Directorio no encontrado");
        }

        $files = [];
        $fileCollection = new \CodeIgniter\Files\FileCollection();
        $fileCollection->addDirectory($path);

        foreach ($fileCollection as $file) {
            $archivo = [
                "nombre" => $file->getFilename(),
                "size" => number_format($file->getSize() / 1024, 2, '.', ''),
                "lastModif" => date('d-m-Y', $file->getMTime()),
                "extension" => $file->getExtension()
            ];
            array_push($files, $archivo);
        }

        return $this->respond(["archivos" => $files, "carpeta" => $ruta]);
    }

    public function nuevoDirectorio()
    {
        $directorio = trim($this->request->getVar('directorio'));
        $ruta = trim($this->request->getVar('ruta'), '/');

        if (!$directorio) {
            return $this->failValidationError('Nombre de directorio no proporcionado');
        }

        $basePath = rtrim($this->storage->archivosRoot, '/');

        $fullPath = $basePath . '/' . ($ruta ? $ruta . '/' : '') . $directorio;

        // Verifica si ya existe
        if (is_dir($fullPath)) {
            return $this->fail('El directorio ya existe', 400);
        }

        // Intenta crear
        if (!mkdir($fullPath, 0755, true)) {
            return $this->fail("No es posible crear el directorio", 400);
        }

        return $this->respond([
            "mensaje" => "Directorio creado",
            "status" => "success"
        ]);
    }

    // public function nuevoDirectorio()
    // {
    //     $directorio = $this->request->getVar('directorio');
    //     $ruta = $this->request->getVar('ruta');
    //     $fullPath = $this->storage->archivosRoot . ($ruta ? $ruta . '/' : '') . $directorio;

    //     if (!mkdir($fullPath, 0755, true)) {
    //         return $this->fail("No es posible crear el directorio", 400);
    //     }

    //     return $this->respond([
    //         "mensaje" => "Directorio creado",
    //         "status" => "success"
    //     ]);
    // }


    public function archivoSubirImagen()
    {
        $file = $this->request->getFile('archivo');

        if (!$file || !$file->isValid()) {
            return $this->failValidationError('Debe seleccionar un archivo válido');
        }

        $ruta = $this->request->getVar('ruta');
        $extension = $file->getExtension();

        // Validar extensiones permitidas
        $extensionesInvalidas = ['php', 'js', 'html', 'htaccess'];
        if (in_array(strtolower($extension), $extensionesInvalidas)) {
            return $this->failValidationError('Tipo de archivo no permitido');
        }

        $newName = $file->getClientName();

        // Construir ruta completa segura
        $basePath = rtrim($this->storage->archivosRoot, DIRECTORY_SEPARATOR);
        $rutaFormateada = $ruta ? str_replace('/', DIRECTORY_SEPARATOR, $ruta) : '';
        $path = $basePath . ($rutaFormateada ? DIRECTORY_SEPARATOR . $rutaFormateada : '');

        // Verificar y crear carpeta si no existe
        if (!is_dir($path)) {
            if (!mkdir($path, 0777, true)) {
                return $this->failServerError('No se pudo crear la carpeta destino');
            }
        }

        // Mover archivo y verificar éxito
        if (!$file->hasMoved()) {
            if (!$file->move($path, $newName)) {
                return $this->failServerError('No se pudo mover el archivo');
            }
        } else {
            return $this->fail('El archivo ya fue movido anteriormente');
        }

        return $this->respondCreated([
            "mensaje" => "Archivo cargado con éxito",
            "nombre" => $newName,
            "ruta" => $ruta
        ]);
    }

    public function eliminarArchivoCarpeta()
    {
        $carpeta = $this->request->getVar('carpeta');
        $archivo = $this->request->getVar('archivo');

        // Eliminar archivo
        if ($archivo) {
            $path = $this->storage->archivosRoot . '/' . ($carpeta ? rtrim($carpeta, '/') . '/' : '') . $archivo;
            // 👇 Esto te ayudará a verificar qué ruta se está construyendo
            // return $this->respond(["debug_path" => $path]);
            if (!file_exists($path)) {
                return $this->failNotFound("Archivo no encontrado");
            }

            if (!unlink($path)) {
                return $this->fail("No se pudo eliminar el archivo", 400);
            }

            return $this->respond(["mensaje" => 'Archivo eliminado con éxito']);
        }

        // Eliminar carpeta
        if ($carpeta) {
            $path = $this->storage->archivosRoot . '/' . rtrim($carpeta, '/');
            // 👇 Esto te ayudará a verificar qué ruta se está construyendo
            // return $this->respond(["debug_path" => $path]);
            if (!is_dir($path)) {
                return $this->failNotFound("La carpeta no existe");
            }

            if (!$this->eliminarCarpetaRecursiva($path)) {
                return $this->fail("No se pudo eliminar la carpeta", 400);
            }

            return $this->respond(["mensaje" => 'Carpeta eliminada satisfactoriamente']);
        }

        return $this->fail("No se especificó archivo o carpeta a eliminar", 400);
    }

    /**
     * Función privada recursiva para eliminar una carpeta y todo su contenido
     */
    private function eliminarCarpetaRecursiva($path)
    {
        if (!is_dir($path)) {
            return false;
        }

        $items = array_diff(scandir($path), ['.', '..']);

        foreach ($items as $item) {
            $itemPath = $path . DIRECTORY_SEPARATOR . $item;
            if (is_dir($itemPath)) {
                if (!$this->eliminarCarpetaRecursiva($itemPath)) {
                    return false;
                }
            } else {
                if (!unlink($itemPath)) {
                    return false;
                }
            }
        }

        return rmdir($path);
    }

    public function descargarArchivo()
    {
        $carpeta = $this->request->getVar('carpeta');
        $archivo = $this->request->getVar('archivo');

        if (!$archivo) {
            return $this->fail("No se especificó archivo a descargar", 400);
        }
        $path = $this->storage->archivosRoot . '/' . ($carpeta ? $carpeta . '/' : '') . $archivo;
        //return $this->respond(["mensaje" => $path]);
        if (!file_exists($path)) {
            return $this->failNotFound("Archivo no encontrado");
        }

        return $this->response->download($path, null);
    }


    public function renombrarArchivo()
    {
        $nombreNuevo = $this->request->getVar('nombreNuevo');
        $nombreAnterior = $this->request->getVar('nombreAnterior');
        $ruta = $this->request->getVar('ruta');

        if (!$nombreAnterior || !$nombreNuevo) {
            return $this->fail("Nombres no válidos", 400);
        }

        $basePath = rtrim($this->storage->archivosRoot, DIRECTORY_SEPARATOR);

        $oldPath = $basePath . ($ruta ? DIRECTORY_SEPARATOR . trim($ruta, DIRECTORY_SEPARATOR) : '') . DIRECTORY_SEPARATOR . $nombreAnterior;
        $newPath = $basePath . ($ruta ? DIRECTORY_SEPARATOR . trim($ruta, DIRECTORY_SEPARATOR) : '') . DIRECTORY_SEPARATOR . $nombreNuevo;

        // Verificar que el archivo exista antes de renombrar
        if (!file_exists($oldPath)) {
            return $this->failNotFound("Archivo original no encontrado");
        }

        // Verificar que no exista ya el archivo nuevo
        if (file_exists($newPath)) {
            return $this->fail("Ya existe un archivo con el nombre nuevo", 400);
        }

        if (!rename($oldPath, $newPath)) {
            return $this->fail("No se pudo renombrar el archivo", 500);
        }

        return $this->respond(["mensaje" => 'Archivo renombrado con éxito']);
    }


    public function copiarArchivo()
    {
        $nombreNuevo = $this->request->getVar('nombreNuevo');
        $nombreAnterior = $this->request->getVar('nombreAnterior');
        $carpetaNueva = $this->request->getVar('carpetaNueva');
        $carpetaAnterior = $this->request->getVar('carpetaAnterior');

        if (!$nombreAnterior || !$nombreNuevo) {
            return $this->fail("Nombres no válidos", 400);
        }

        $basePath = rtrim($this->storage->archivosRoot, DIRECTORY_SEPARATOR);

        $source = $basePath . ($carpetaAnterior ? DIRECTORY_SEPARATOR . $carpetaAnterior : '') . DIRECTORY_SEPARATOR . $nombreAnterior;
        $destFolder = $basePath . ($carpetaNueva ? DIRECTORY_SEPARATOR . $carpetaNueva : '');
        $dest = $destFolder . DIRECTORY_SEPARATOR . $nombreNuevo;

        // Verificar que exista la carpeta destino, si no crearla
        if (!is_dir($destFolder)) {
            if (!mkdir($destFolder, 0777, true)) {
                return $this->failServerError("No se pudo crear la carpeta destino");
            }
        }

        // Verificar que el archivo origen exista
        if (!file_exists($source)) {
            return $this->failNotFound("Archivo origen no encontrado");
        }

        // Copiar archivo
        if (!copy($source, $dest)) {
            return $this->fail("No se pudo copiar el archivo", 400);
        }

        return $this->respond(["mensaje" => 'Archivo copiado con éxito']);
    }
}
