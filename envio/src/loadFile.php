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
    $secuencia=$_POST['secuencia'];
    ini_set('post_max_size', '12M');
    ini_set('upload_max_filesize', '12M');
    header('Access-Control-Allow-Origin: *');
    date_default_timezone_set('America/Los_Angeles');

    $dir = '/var/www/html/uploads/';
    $date = date('Y-m-d-H-i-s');
    $random = rand(1000,9999);
    $split_name_file =  explode('.',basename($_FILES['file']['name']));
    $extention = end($split_name_file);
    $name = strtolower('ayuda'.$secuencia.'.'.$extention);
    $file_dir = $dir .$name;//.basename($_FILES['file']['name']);
    $url = '/uploads/'.$name; 


    $temp_file = $_FILES['file']['tmp_name'] ;

    if( move_uploaded_file($temp_file, $file_dir)) {
        $response ->url = $url;
        $response ->message = 'Se cargo el archivo exitosamente.';
        $response ->success = true;
        echo $url; // json_encode($response);//'Fichero cargado exitosamente.'. 'la hora es '. $timezone;
    }else {
        echo     'Error ';//json_encode($response) ;//$response;
    }
    
  
?>