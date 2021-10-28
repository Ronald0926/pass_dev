<?php 
  
    header("Access-Control-Allow-Origin: *");
    header("Content-Type: application/json; charset=UTF-8");
    header("Access-Control-Allow-Methods: POST");
    header("Access-Control-Max-Age: 3600");
    header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
    
    $json_str = file_get_contents('php://input');
    $json_obj = json_decode($json_str);
    $from = $json_obj["from"];
    
    $response = array();
    
    $response["message"] = "Acceso correcto";
    $response["data"] =  $from;
    
    echo json_encode($response);
?>
