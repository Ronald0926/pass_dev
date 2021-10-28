<?php

ini_set("pcre.backtrack_limit", "5000000");
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Email extends CI_Controller {
    public $iniciLog='[INFO] ';
    public $logHeader = 'APOLOINFO::::::::: ';
    public $postData = 'POSTDATA::::::::: ';
    public $queryData = 'QUERYDATA::::::: ';
    public $finFuncion=' FIN PROCEDIMIENTO::::::: ';
    public function __construct() {
        parent::__construct();
         
        $this->load->helper('log4php');
    }

    public function __destruct() {
        $this->db->close();
    }

    public function enviar() {


        $post = $this->input->post();

        if ($post) {
            $remitente = $_POST['REMITENTE'];
            $destcorreo = $_POST['DESTCORREO'];
            $destusuario = $_POST['DESTUSUARIO'];
            $respcorreo = $_POST['RESPCORREO'];
            $respusuario = $_POST['RESPUSUARIO'];
            $asunto = $_POST['ASUNTO'];
            $mensaje = urldecode($_POST['MENSAJE']);
            $comercial = $_POST['COMERCIAL'];
            $comercialusuario = $_POST['NCOMERCIAL'];
            //CORREO
            $host = $_POST['HOST'];
            $username = $_POST['USERNAME'];
            $password = $_POST['PASSWORD'];
            $smtpsecure = $_POST['SMTPSECURE'];
            $port = $_POST['PORT'];
            // Load PHPMailer library
            $this->load->library('phpmailer_lib');
            // PHPMailer object
            $mail = $this->phpmailer_lib->load();
            // SMTP configuration
            $mail->isSMTP();
            $mail->Host =$host;// 'smtp.office365.com';
            $mail->SMTPAuth = true;
            $mail->Username = $username;//'notificaciones@peoplepass.com.co';
            $mail->Password = $password;//'P30pl32030*';
            $mail->SMTPSecure =$smtpsecure;// 'tls';
            $mail->Port = $port;//587;

            $mail->setFrom($username, $remitente);
            $mail->addReplyTo($respcorreo, $respusuario);

            // Add a recipient
            $mail->addAddress($destcorreo,  $destusuario);

            // Add cc or bcc 
            //$mail->addCC('cc@example.com');
            //$mail->addBCC('bcc@example.com');
            // Email subject
            $mail->Subject = $asunto;

            // Set email format to HTML
            $mail->isHTML(true);

            // Email body content
            $mailContent = $mensaje;
            $mail->Body = $mailContent;//$this->load->view('wsonline2/email/enviar', $data,TRUE);
            log_info('APOLOINFO::::::::: enviar correo a=> '.$destcorreo);
            log_info('APOLOINFO::::::::: asunto=> '.$asunto);
            log_info('APOLOINFO::::::::: mensaje=> '.$mailContent);
            
            // Send email
            if (!$mail->send()) {
               
               log_info('APOLOINFO::::::::: Error Mensaje no ha sido enviado');
               log_info('APOLOINFO::::::::: mail->error'. $mail->ErrorInfo);
               echo 'El mensaje no ha sido enviado'.$mail->ErrorInfo;
                
            } else {
                log_info('APOLOINFO::::::::: El mensaje ha sido enviado');
                echo 'El mensaje ha sido enviado';
                echo '<br/>';
            }
            
        }
       // $this->load->view('wsonline2/email/enviar', $data);
    }

}
