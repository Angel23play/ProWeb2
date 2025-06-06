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


require('obra.php');
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

    protected static $ruta_global = __DIR__ . '/Data/obras/';



    public function __construct(array $data)
    {
        foreach ($data as $key => $value) {
            if (property_exists($this, $key)) {
                $this->$key = $value;
            }
        }
    }
    


    public function obtenerporcedula(){
        return $this->cedula;
    }

    public static function guardar(Personaje $personaje, $codigo_obra): bool
    {
        $Obra = Obra::ObtenerporCodigo($codigo_obra);

        if (!$Obra) {
            return false;
        }

        array_push($Obra->personaje, $personaje);

        return Obra::guardar($Obra);

    }


    public static function editar($codigo, Personaje $personajeEditar): bool
    {
        $obra = Obra::ObtenerporCodigo($codigo);
        if (!$obra) {
            return false;
        }

       foreach ($obra->personaje as $index => $personaje) {
            if ($personaje['cedula'] === $personajeEditar->cedula) {
                
                $obra->personaje[$index] = (array) $personajeEditar;
                Obra::guardar($obra); // Guarda la obra actualizada
                return true; 
            }
        }
        return false;
    }



    public static function borrar($codigo, $cedula): bool
    {
        $obra = Obra::ObtenerporCodigo($codigo);

        if (!$obra) {
            return false;
        }

        foreach ($obra->personaje as $index => $personaje) {
            if ($personaje['cedula'] === $cedula) {
                unset($obra->personaje[$index]); // Elimina ese personaje

                $obra->personaje = array_values($obra->personaje);
                return Obra::guardar($obra); // Guarda la obra actualizada
            }
        }
        return true;
    }
}