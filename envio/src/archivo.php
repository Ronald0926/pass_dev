<?php
/**
 * Created by PhpStorm.
 * User: edgar
 * Date: 11/09/18
 * Time: 08:53 PM
 */

    //var_dump($_FILES);
    //var_dump( $HTTP_POST_FILES);
    //var_dump($_POST);
    //var_dump($HTTP_GET_VARS);
    //ini_set("upload_max_filesize","10M");
    ini_set('post_max_size', '12M');
    ini_set('upload_max_filesize', '12M');
    header('Access-Control-Allow-Origin: *');
    date_default_timezone_set('America/Los_Angeles');

    $dir = '/var/www/html/uploads/';
    $date = date('Y-m-d-H-i-s');
    $random = rand(1000,9999);
    $split_name_file =  explode('.',basename($_FILES['file']['name']));
    $extention = end($split_name_file);
    $name = strtolower($date.'-'.$random.'.'.$extention);
    $file_dir = $dir .$name;//.basename($_FILES['file']['name']);
   // $url = 'http://'.$_SERVER['SERVER_ADDR'].':'.$_SERVER['SERVER_PORT'].'/uploads/'.$name;   
    $url = 'http://www.peoplepassonline.co:8090/uploads/'.$name;   


    $temp_file = $_FILES['file']['tmp_name'] ;
		echo 'Prueba='.$_FILES['file']['tmp_name'];
    //echo 'ruta ' .$file_dir;
    $result=move_uploaded_file($temp_file, $file_dir);
    var_dump($result);
    if($result) {
        $response ->url = $url;
        $response ->message = 'Se cargo el archivo exitosamente.';
        $response ->success = true;
        echo $url; // json_encode($response);//'Fichero cargado exitosamente.'. 'la hora es '. $timezone;
    }else {
        
        //$response ->message = 'Error al cargar el fichero'
        //$response ->success = false;
        echo     ' '.$temp_file;//json_encode($response) ;//$response;
    }
    
    //var_dump($_FILES['file']);

    //echo basename($_FILES['file']['name'];
    
    //$error =  $_FILES['fichero_usuario']["error"];
    //$message = $error;
    /*switch($error) {
      case 1: 
         $message = 'El fichero subido excede la directiva upload_max_filesize de php.ini.';
         break;
      case 2:
        $message = 'El fichero subido excede la directiva MAX_FILE_SIZE especificada en el formulario HTML';
        break;
      case 3:
        $message = 'El fichero fue s�lo parcialmente subido.';
        break;
      case 4:
        $message = 'No se subi� ning�n fichero';
        break;
      case 6:
        $message = 'Falta la carpeta temporal';
        break;
      case 7:
        $message = 'No se pudo escribir el fichero en el disco';
        break;
      default:
        $message =$_FILES;
        break;
    }*/

   // print_r($_FILES);

   // print "</pre>"


?>