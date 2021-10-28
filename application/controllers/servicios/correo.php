<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Correo extends CI_Controller {

    public function __construct() {
        parent::__construct();
    }

    public function sendMail() {
       $remitente = $_POST['REMITENTE'];
        $destcorreo = $_POST['DESTCORREO'];
        $destusuario = $_POST['DESTUSUARIO'];
        $respcorreo = $_POST['RESPCORREO'];
        $respusuario = $_POST['RESPUSUARIO'];
        $asunto = $_POST['ASUNTO'];
        $mensaje = $_POST['MENSAJE'];
        $comercial = $_POST['COMERCIAL'];
        $comercialusuario = $_POST['NCOMERCIAL'];




        //CORREO
        $host = $_POST['HOST'];
        $username = $_POST['USERNAME'];
        $password = $_POST['PASSWORD'];
        $smtpsecure = $_POST['SMTPSECURE'];
        $port = $_POST['PORT'];
       // use PHPMailer;
       // use Exception;
        require 'Exception.php';
        require 'PHPMailer.php';
        require 'SMTP.php';
        $mail = new PHPMailer\PHPMailer\PHPMailer();                              // Passing `true` enables exceptions
        try {
            //Server settings
            //  $mail->SMTPDebug = SMTP::DEBUG_LOWLEVEL ( 4 );
            //$mail->SMTPDebug = 4; //Alternative to above constant
            $mail->SMTPDebug = 2;                                 // Enable verbose debug output
            $mail->isSMTP();                                      // Set mailer to use SMTP
            $mail->Host = $host;  // Specify main and backup SMTP servers
            $mail->SMTPAuth = true;                               // Enable SMTP authentication
            $mail->Username = $username;                 // SMTP username
            $mail->Password = $password;                           // SMTP password
            $mail->SMTPSecure = $smtpsecure;                            // Enable TLS encryption, `ssl` also accepted
            $mail->Port = $port;                                    // TCP port to connect to
            //Recipients
            $mail->setFrom($username, $remitente);
            //$mail->setFrom('Soporte.cliente@peoplepass.com.co', $remitente);
            //echo 'destinatario'+$destinatari;
            $mail->addAddress($destcorreo, $destusuario);     // Add a recipient
            $mail->addAddress($comercial, $comercialusuario);
            //$mail->AddCC($comercial,$comercialusuario);
            //$mail->addAddress('');               // Name is optional
            $mail->addReplyTo($respcorreo, $respusuario);
            $mail->addCustomHeader('MIME-Version: 1.0');
            $mail->addCustomHeader('Content-Type: text/html; charset=ISO-8859-1');
            //$mail->addCC($destinatario);
            //$mail->addBCC($destinatario);
            //Attachments
            //$mail->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
            //$mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name
            //Content
            $mail->isHTML(true);                                  // Set email format to HTML
            $mail->Subject = $asunto;
            $mail->Body = $mensaje;

            //$mail->AltBody = 'This is the body in plain text for non-HTML mail clients';
            // echo 'FINNN';
            $envio = $mail->send();
            //echo ($envio);
            //var_dump($envio);
            //$variable =  json_encode($mail);
            //echo $SMTPDebug;
            //var_dump($mail);
            //echo $e->errorMessage();
            if ($envio) {
                echo 'El mensaje ha sido enviado';
                // var_dump($mail);
            } else {
                echo 'El mensaje no ha sido enviado';
                echo 'Message could not be sent. Mailer Error: ', $mail->ErrorInfo;
            }
        } catch (Exception $e) {
            echo 'Message could not be sent. Mailer Error: ', $mail->ErrorInfo;
        }
    }

}
