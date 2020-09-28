<?php

class Materia{
    public $nombre;
    public $cuatrimestre;
    public $id;

    function __construct($nombre, $cuatrimestre, $id)
    {
        $this->nombre = $nombre;
        $this->cuatrimestre = $cuatrimestre;
        $this->id = $id;
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
        return $this->nombre.'*'.$this->cuatrimestre.'*'.$this->id;
     }
}