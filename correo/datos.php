<?php
require("_lib/class.phpmailer.php");
$mail = new PHPMailer();
$mail->isSMTP();
$mail->SMTPAuth = true;
$mail->SMTPSECURE = "ssl";
$mail->Host = "smtp.ip-zone.com";
$mail->Username = "nationalhealthp";
$mail->Password = "7f3ba211";
$mail->Port = 465;
$mail->From = "team@nationalhealthp.com";
$mail->FromName = "Team";
$mail->AddAddress("fredy.mendoza@tecnolet.com");
$mail->IsHTML(true);
$mail->Subject = "Titulo";
$body = "Hola mundo. Esta es la primer linea ";
$body .= "Aqu� continuamos el mensaje"; $mail->Body = $body;
$exito = $mail->Send();
if($exito){
echo "El correo fue enviado correctamente.";
}else{
echo "Hubo un problema. Contacta a un administrador."; }
?>
<!DOCTYPE html>
  <html>
    <head>
      <title>Mi quinta p�gina con php</title>
    </head>
    <body>

  </body>
  </html>
