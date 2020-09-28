<?php

class fileHandler {

    public static function SaveAsText($archivo ,$objetoRegistrar)
    {
        //TEXTO
        $archivoTexto = fopen( $archivo.".txt", "a+");
        $fwrite = fwrite($archivoTexto, $objetoRegistrar . PHP_EOL);
        fclose($archivoTexto);

        return $fwrite;
    }

    public static function SaveAsJson($nombreArchivo, $objetoRegistrar)
    {
       
        //JSON
        /*
        $archivoJson = fopen("users.json", "a+");
        $fwrite = fwrite($archivoJson, json_encode($objetoRegistrar));
        fclose($archivoJson);
        */
        $result = fileHandler::ReadJson($nombreArchivo);
        $result[] = $objetoRegistrar;
        return file_put_contents(__DIR__."/".$nombreArchivo, json_encode($result, JSON_PRETTY_PRINT) );

        //return file_put_contents(__DIR__. '/users.json', json_encode($objetoRegistrar, JSON_PRETTY_PRINT),FILE_APPEND );
        
    }
    public static function SaveSerialize($archivo, $objetoRegistrar)
    {
        //SERIALIZE
        $archivoSerilizar = fopen($archivo.".txt", "a+");
        $fwrite = fwrite($archivoSerilizar, serialize($objetoRegistrar));
        fclose($archivoSerilizar);

        return $fwrite;
    }

    public static function ReadText($archivo)
    {
        
    }

    public static function ReadJson($nombreArchivo)
    {
        
        /*miArchivo = fopen($archivo,"r");
        $size = filesize($archivo);
        //$listaObjetos = array();
        if($size > 0)
        {
            $fread = fread($miArchivo, $size);
        }
        if($fread != false)
        {
            echo "fread es falso";
            $listaObjetos = json_decode($fread);
        }
        var_dump($listaObjetos);
        return $listaObjetos;
        */
        //print_r($nombreArchivo);
        $users = json_decode(file_get_contents(__DIR__."/".$nombreArchivo, true));
        //var_dump($users);
        return $users;
    }
}