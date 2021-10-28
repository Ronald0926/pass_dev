<?php

   header("Content-Type: application/json; charset=UTF-8");
   header("Access-Control-Allow-Methods: POST");
 
  
  
  $json_str = file_get_contents('php://input');
  $data = json_decode($json_str);
  //$dato_uno = $data->DESTCORREO;
  
  //print_r($data);
  
   $response = array();
   $remitente=$data->REMITENTE; 
   $destcorreo=$data->DESTCORREO;
   $destusuario=$data->DESTUSUARIO;
   $respcorreo=$data->RESPCORREO;
   $respusuario=$data->RESPUSUARIO;
   $asunto=$data->ASUNTO;
   $mensaje=$data->MENSAJE;
   
   //$response["mensaje"] = "No se envio el correo.";
   
  
 use PHPMailer;
 use Exception;
 require 'Exception.php';
 require 'PHPMailer.php';
 require 'SMTP.php';
 $mail = new PHPMailer\PHPMailer\PHPMailer();                              // Passing `true` enables exceptions
 try {
    //Server settings
    //$mail->SMTPDebug = 2;                                 // Enable verbose debug output
    $mail->isSMTP();                                      // Set mailer to use SMTP
    $mail->Host = 'smtp.ip-zone.com';  // Specify main and backup SMTP servers
    $mail->SMTPAuth = true;                               // Enable SMTP authentication
    $mail->Username = 'nationalhealthp';                 // SMTP username
    $mail->Password = '7f3ba211';                           // SMTP password
    $mail->SMTPSecure = 'ssl';                            // Enable TLS encryption, `ssl` also accepted
    $mail->Port = 465;                                    // TCP port to connect to

    //Recipients
    $mail->setFrom('team@nationalhealthp.com', $remitente);
    //echo 'destinatario'+$destinatari;
    $mail->addAddress($destcorreo,  $destusuario);     // Add a recipient
    //$mail->addAddress('');               // Name is optional
    $mail->addReplyTo($respcorreo, $respusuario);
    //$mail->addCC($destinatario);
    //$mail->addBCC($destinatario);

    //Attachments
    //$mail->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
    //$mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name

    //Content
    $mail->isHTML(true);                                  // Set email format to HTML
    $mail->Subject =  $asunto;
    $mail->Body    = $mensaje;
    //$mail->AltBody = 'This is the body in plain text for non-HTML mail clients';
    
   $envio = $mail->send();
   
   $response["envio"] = $mail;
   
    if ( $envio ) {
     $response["RESPUESTA"] = "Correo enviado satisfactoriamente.";
     $response["CODIGO"] = 200 ;
    
    //echo json_encode($response);
    }
    else {
      $response["RESPUESTA"] = "No se envio el correo.";
     $response["CODIGO"] = 404 ;
    //echo json_encode($response);
    }
} catch (Exception $e) {
     $response["mensaje"] = "No se envio el correo.";
     $response["codigo"] = 500 ;
    
    echo json_encode($response);
}

  /* $response["data"] = $data;
   $response["remitente"] =$remitente;
   $response["destcorreo"] =$destcorreo;
   $response["destusuario"] =$destusuario;
   $response["respcorreo"] =$respcorreo;
   $response["respusuario"] =$respusuario;
   $response["asunto"] =$asunto;
   $response["mensaje"] =$mensaje;*/
   echo json_encode( $response);
 ?>