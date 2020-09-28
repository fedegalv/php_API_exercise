<?php
require_once 'usuario.php';
require_once 'materia.php';
require_once 'profesor.php';
require_once 'asignacion.php';
require_once 'fileHandler.php';


require __DIR__ . '/vendor/autoload.php';

use \Firebase\JWT\JWT;

/*TOKEN*/

$key = "pro3-parcial";
$payload = array(
    "email" => "",
);

/*global*/
$jwt = null;

$method = $_SERVER['REQUEST_METHOD'];
$path = $_SERVER['PATH_INFO'] ?? 0;
//echo 'Inicia programa';
switch ($path) {
    case '/usuario':
        if ($method == 'POST') {
            //echo $_POST['email'].' y '.$_POST['clave'].'FROM POST';
            $email = $_POST['email'] ?? '';
            $clave = $_POST['clave'] ?? '';
            //echo $email.' y '.$email. 'VARS';
            $usuario = new Usuario($email, $clave);
            //fileHandler::SaveAsText("users",$usuario);
            if (fileHandler::SaveAsJson("users.json", $usuario)) {
                echo 'Usuario regitrado';
            } else {
                echo 'No se pudo guardar';
            }
            //fileHandler::SaveSerialize("usersSerialized",$Usuario);


        }

        break;
    case '/login':
        if ($method == 'POST') {
            $email = $_POST['email'] ?? '';
            $clave = $_POST['clave'] ?? '';
            $found = false;
            $listaUsuarios = fileHandler::ReadJson("users.json");
            //echo "<pre>";
            //var_dump($listaUsuarios);
            foreach ($listaUsuarios as $usuario) {
                if (($email == $usuario->email) && ($clave == $usuario->clave)) {
                    $found = true;
                }
            }
            if ($found) {
                echo "Usuario encontrado<br>Tu JWT es:<br>";
                $payload["email"] = $email;
                $jwt = JWT::encode($payload, $key);
                print_r($jwt);
            } else {
                echo "Usuario no encontrado<br>";
                $jwt = null;
            }
            //echo "</pre>";

        }

        break;
        //POST MATERIA
    case '/materia':
        if ($method == 'POST') {
            $token = $_SERVER['HTTP_TOKEN'];
            try {
                $decoded = JWT::decode($token, $key, array('HS256'));
                echo "TOKEN VALIDO <br>";
                $nombre = $_POST['nombre'] ?? '';
                $cuatrimestre = $_POST['cuatrimestre'] ?? '';
                // GENERAR ID, LECTURA DE ARCHIVO SI LO HAY Y BUSCAR ID MAS GRANDE
                // Y SUMAR 1
                $listaMateria = fileHandler::ReadJson("materias.json");
                if (is_array($listaMateria) == true && sizeof($listaMateria) != 0) {
                    //ENCUENTRA EL ID MAS GRANDE EN EL ARRAY
                    $idMax = max(array_column($listaMateria, 'id'));
                    $materia = new Materia($nombre, $cuatrimestre, $idMax + 1);
                } else {
                    $materia = new Materia($nombre, $cuatrimestre, 1);
                }
                //fileHandler::SaveAsText("materias",$materia);
                fileHandler::SaveAsJson("materias.json", $materia);
                echo "MATERIA GUARDADA";
                //fileHandler::SaveSerialize("materiasSerialized",$materia);
            } catch (Exception $e) {
                echo "ERROR AL AUNTENTIFICAR: TOKEN INCORRECTO O INVALIDO";
            }
        }
        else if($method == "GET")
        {
            try{
                if ($_SERVER['HTTP_TOKEN'] != null) {
                    $token = $_SERVER['HTTP_TOKEN'];
                } else {
                    throw new Exception("FALTA TOKEN O TOKEN INVALIDO!");
                }
                $decoded = JWT::decode($token, $key, array('HS256'));
                echo "TOKEN VALIDO <br>";

                echo 'Lista de Materias: <br>';
                $listaMaterias = fileHandler::ReadJson("materias.json");
                foreach($listaMaterias as $materia)
                {
                    echo 'Id:'.$materia->id.' - '.$materia->nombre.' - Cuatrimestre: '.$materia->cuatrimestre.'<br>';
                }
            }
            catch(Exception $e)
            {
                echo 'ERROR: '.$e->getMessage();
            }
        }
        break;

        ///POST PROFESOR
    case '/profesor':
        if ($method == 'POST') {

            try {
                if ($_SERVER['HTTP_TOKEN'] != null) {
                    $token = $_SERVER['HTTP_TOKEN'];
                } else {
                    throw new Exception("FALTA TOKEN!");
                }

                $decoded = JWT::decode($token, $key, array('HS256'));
                echo "TOKEN VALIDO <br>";
                $nombre = $_POST['nombre'];
                $legajo = $_POST['legajo'];
                $profesorNuevo = new Profesor($nombre, $legajo);
                $listaProfesores = fileHandler::ReadJson("profesores.json");

                //REVISAR SI LISTA NO ES VACIA, SI ES VACIA GUARDAR DIRECTAMENTE
                if (is_array($listaProfesores) == true && count($listaProfesores) != 0) {
                    //BUSCAR SI SE REPITE, SI LO HACE TIRA EXCEPCION
                    Profesor::VerificarLegajo($listaProfesores, $legajo);
                }

                //SI LLEGA A ESTE PUNTO ES QUE HAY LEGAJO VALIDO O LA LISTA ESTA VACIA
                fileHandler::SaveAsJson("profesores.json", $profesorNuevo);
                echo "PROFESOR GUARDADO";

            } catch (Exception $e) {
                echo "ERROR: " . $e->getMessage();
            }
            //VERIFICAR QUE LEGAJO SEA UNICO

        }
        else if($method == "GET")
        {
            try{
                if ($_SERVER['HTTP_TOKEN'] != null) {
                    $token = $_SERVER['HTTP_TOKEN'];
                } else {
                    throw new Exception("FALTA TOKEN O TOKEN INVALIDO!");
                }
                $decoded = JWT::decode($token, $key, array('HS256'));
                echo "TOKEN VALIDO <br>";

                echo 'Lista de Profesores: <br>';
                $listaProfesores = fileHandler::ReadJson("profesores.json");
                foreach($listaProfesores as $profesor)
                {
                    echo $profesor->nombre.' - Legajo: '.$profesor->legajo.'<br>';
                }
            }
            catch(Exception $e)
            {
                echo 'ERROR: '.$e->getMessage();
            }
        }
        break;
    case '/asignacion':
        if($method == 'POST')
        {
            try{
                if ($_SERVER['HTTP_TOKEN'] != null) {
                    $token = $_SERVER['HTTP_TOKEN'];
                } else {
                    throw new Exception("FALTA TOKEN!");
                }
                $decoded = JWT::decode($token, $key, array('HS256'));
                echo "TOKEN VALIDO <br>";

                $legajoProfesor = $_POST['legajoProfesor'];
                $idMateria = $_POST['idMateria'];
                $turno = $_POST['turno'];

                $listaAsignaciones = fileHandler::ReadJson("materias-profesores.json");
                if (is_array($listaProfesores) == true && sizeof($listaAsignaciones) != 0) {
                    foreach($listaAsignaciones as $asignacion)
                    {
                        if( ($asignacion->legajoProfesor == $legajoProfesor) && ( $asignacion->idMateria == $idMateria && $asignacion->turno == $turno))
                        {
                            throw new Exception("NO SE PUEDE ASIGNAR MISMO LEGAJO A MISMO TURNO Y MATERIA");
                        }
                    }
                }

                $asignacionNueva = new Asignacion($legajoProfesor, $idMateria, $turno);                
                fileHandler::SaveAsJson("materias-profesores.json", $asignacionNueva);
                echo 'ASIGNACION CREADA!';
            }
            catch(Exception $e)
            {
                echo 'ERROR: '.$e->getMessage();
            }
        }
        else if($method == "GET")
        {
            try{
                if ($_SERVER['HTTP_TOKEN'] != null) {
                    $token = $_SERVER['HTTP_TOKEN'];
                } else {
                    throw new Exception("FALTA TOKEN O TOKEN INVALIDO!");
                }
                $decoded = JWT::decode($token, $key, array('HS256'));
                echo "TOKEN VALIDO <br>";

                echo 'Lista de asignaciones: <br>';
                $listaAsignaciones = fileHandler::ReadJson("materias-profesores.json");
                foreach($listaAsignaciones as $asignacion)
                {
                    echo 'Legajo profesor: '.$asignacion->legajoProfesor.' - idMateria: '.$asignacion->idMateria.' - Turno: '.$asignacion->turno.'<br>';
                }
            }
            catch(Exception $e)
            {
                echo 'ERROR: '.$e->getMessage();
            }
        }
        break;
    default:
        echo 'RUTA INVALIDA';
        break;
}
