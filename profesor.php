<?php
class Profesor{
    public $nombre;
    public $legajo;
    
    public function __construct($nombre, $legajo)
    {
        $this->nombre = $nombre;
        $this->legajo = $legajo;
    }
    public function __set($name, $value)
    {
        $this->$name = $value;
    }

    public function __get($name)
    {
        return $this->$name;
    }
    public function __toString(){
        return $this->nombre.'*'.$this->legajo.'*';
     }

     public static function VerificarLegajo($listaProfesores, $legajo)
    {
        foreach ($listaProfesores as $profesor) {
            if ($legajo == $profesor->legajo) {
                throw new Exception("LEGAJO REPETIDO!");
                break;
            }
        }
        return true;
    }
}