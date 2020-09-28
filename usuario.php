<?php
class Usuario{
    public $email;
    public $clave;

    function __construct($email, $clave)
    {
        $this->email = $email;
        $this->clave = $clave;
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
        return $this->email.'*'.$this->clave;
     }
     /*
    public function Registrar()
    {
        //JSON
        $archivoJson = fopen("users.json", "a+");
        fwrite($archivoJson, json_encode($this));
        fclose($archivoJson);
        //SERIALIZE
        $archivoSerilizar = fopen("usersSerialized.txt", "a+");
        fwrite($archivoSerilizar, serialize($this));
        fclose($archivoSerilizar);

        //TEXTO
        $archivoTexto = fopen("users.txt", "a+");
        fwrite($archivoTexto, $this . PHP_EOL);
        fclose($archivoTexto);

    }
    */
    
}