<?php
class Obra
{
    public $codigo;
    public $foto_url;
    public $tipo;
    public $nombre;
    public $descripcion;
    public $pais;
    public $autor;

    public $personaje = array();
    protected static $ruta_global = __DIR__ . '/Data/obras/';

    public function __construct(array $data)
    {
        foreach ($data as $key => $value) {
            if (property_exists($this, $key)) {
                $this->$key = $value;
            }
        }
    }

    public static function cargar(): array
    {
        $ruta = self::$ruta_global;
        $data = [];

        if (is_dir($ruta)) {
            $archivos = scandir($ruta);
            foreach ($archivos as $archivo) {
                if ($archivo !== '.' && $archivo !== '..') {
                    $contenido = file_get_contents($ruta . $archivo);
                    $obra = json_decode($contenido, true);
                    if ($obra !== null) {
                        $data[] = $obra;
                    }
                }
            }
        } else {
            return $data = [];
        }

        return $data;
    }

    public static function guardar(Obra $obra): bool
    {
        $ruta = self::$ruta_global . $obra->codigo;
        $json = json_encode($obra);
        if (file_put_contents($ruta, $json) !== false) {
            return true;
        }
        return false;

    }

    public static function editar(Obra $obra): bool
    {
        $obras = self::cargar();

        $encontrado = false;
        foreach ($obras as &$existente) {
            if ($existente['codigo'] === $obra->codigo) {
                $existente = [
                    'codigo' => $obra->codigo,
                    'foto_url' => $obra->foto_url,
                    'tipo' => $obra->tipo,
                    'nombre' => $obra->nombre,
                    'descripcion' => $obra->descripcion,
                    'pais' => $obra->pais,
                    'autor' => $obra->autor,
                ];
                $encontrado = true;
                break;
            }
        }
        unset($existente);
        if ($encontrado) {
            self::guardar($obra);
        }
        return $encontrado;
    }


    public static function borrar($codigo)
    {
        $ruta = self::$ruta_global . $codigo;

        if (file_exists($ruta)) {
            return unlink($ruta);
        }

        return false;
    }

    public static function ObtenerporCodigo($codigo): Obra|null
    {
        $ruta = self::$ruta_global . $codigo;

        if (!file_exists($ruta)) {
            return null;
        }

        $contenido = file_get_contents($ruta);
        $datos = json_decode($contenido, true);

        if ($datos === null) {
            return null;
        }

        $obra = new Obra($datos); 

        /*
        $obra->codigo = $datos['codigo'];
        $obra->foto_url = $datos['foto_url'];
        $obra->tipo = $datos['tipo'];
        $obra->nombre = $datos['nombre'];
        $obra->descripcion = $datos['descripcion'];
        $obra->pais = $datos['pais'];
        $obra->autor = $datos['autor'];
        $obra->personaje = $datos['personaje'] ?? [];
        */
        return $obra;
    }




}
