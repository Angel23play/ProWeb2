<?php
/*
🧍‍♂️Módulo 2: Registro de Personajes
Cada obra puede tener múltiples personajes. Para cada personaje se deben solicitar los siguientes campos:

Campo	Descripción
cédula	Identificador único del personaje
foto_url	Imagen del personaje
nombre	Nombre del personaje
apellido	Apellido del personaje
fecha_nacimiento	Fecha de nacimiento
sexo	Masculino o Femenino
habilidades	Lista de habilidades separadas por comas
comida_favorita	Texto libre
✅ Cada personaje debe estar relacionado a una obra existente (por su código).
*/

class Personaje
{
    public $cedula;
    public $foto_url;
    public $nombre;
    public $apellido;
    public $fecha_nacimiento;
    public $sexo;
    public $habilidades;
    public $comida_favorita;
    public $codigo_obra;
    protected static $ruta_global = __DIR__ . '/Data/personajes/';

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
                    $personaje = json_decode($contenido, true);
                    if ($personaje !== null) {
                        $data[] = $personaje;
                    }
                }
            }
        } else {
            return $data = [];
        }

        return $data;
    }

    public static function guardar(Personaje $personaje): bool
    {
        $ruta = self::$ruta_global . $personaje->codigo;
        $json = json_encode($personaje);
        if (file_put_contents($ruta, $json) !== false) {
            return true;
        }
        return false;

    }


    public static function editar(Personaje $personaje): void
    {
        $personajes = self::cargar();

        $encontrado = false;
        foreach ($personajes as &$existente) {
            if ($existente['codigo'] === $personaje->codigo) {
                $existente = [
                    'codigo' => $personaje->codigo,
                    'foto_url' => $personaje->foto_url,
                    'tipo' => $personaje->tipo,
                    'nombre' => $personaje->nombre,
                    'descripcion' => $personaje->descripcion,
                    'pais' => $personaje->pais,
                    'autor' => $personaje->autor,
                ];
                $encontrado = true;
                break;
            }
        }
        unset($existente);
        if ($encontrado) {
            self::guardar($personaje);
        }
    }



public static function borrar($codigo)
    {
         $ruta = self::$ruta_global . $codigo;

    if (file_exists($ruta)) {
        return unlink($ruta); // retorna true si se eliminó correctamente
    }

    return false;
    }
}