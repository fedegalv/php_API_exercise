<?php
class Asignacion{
    public $legajoProfesor;
    public $idMateria;
    public $turno;
    function __construct($legajoProfesor, $idMateria, $turno)
    {
        $this->legajoProfesor = $legajoProfesor;
        $this->idMateria = $idMateria;
        $this->turno = $turno;
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
        return $this->legajoProfesor.'*'.$this->idMateria.'*'.$this->turno;
    }
}