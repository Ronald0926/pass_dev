<?php

 $remitente=$_POST['REMITENTE'];
 $destcorreo=$_POST['DESTCORREO'];
 $destusuario=$_POST['DESTUSUARIO'];
 $respcorreo=$_POST['RESPCORREO'];
 $respusuario=$_POST['RESPUSUARIO'];
 $asunto=$_POST['ASUNTO'];
 $mensaje=$_POST['MENSAJE'];
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

//$variable =  json_encode($mail);
//echo $variable;
    if ( $envio ) {
	echo 'El mensaje ha sido enviado';
  }
    else {
	echo 'El mensaje no ha sido enviado' ;
   echo 'Message could not be sent. Mailer Error: ', $mail->ErrorInfo;
   }
} catch (Exception $e) {
    echo 'Message could not be sent. Mailer Error: ', $mail->ErrorInfo;
}
 ?>
