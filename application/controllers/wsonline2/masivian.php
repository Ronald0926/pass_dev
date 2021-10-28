
<?php

ini_set("pcre.backtrack_limit", "5000000");
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Masivian extends CI_Controller {

    public $iniciLog = '[INFO] ';
    public $logHeader = 'TALOSINFO::::::::: ';
    public $postData = 'POSTDATA::::::::: ';
    public $queryData = 'QUERYDATA::::::: ';
    public $finFuncion = ' FIN PROCEDIMIENTO::::::: ';

    public function __construct() {
        parent::__construct();
        $this->load->helper('log4php');
    }

    public function __destruct() {
        $this->db->close();
    }

    public function enviarOTP() {


        $post = $this->input->post();

        if ($post) {
            $action = $post['ACTION'];
            $username = $post['USERNAME'];
            $password = $post['PASSWORD'];
            $recipient = $post['RECIPIENT'];
            $messagedata = $post['MESSAGEDATA'];
            $url = $post['URL'];
            $url=$url . '?action=' . $action . '&username=' . $username . '&password=' . $password . '&recipient=' . $recipient . '&messagedata=' . $messagedata;
            $url=str_replace(' ', '%20', $url);
         //  VAR_DUMP($url);
            $curl = curl_init();

            curl_setopt_array($curl, array(
                CURLOPT_URL => $url ,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'POST',
                curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode(array()))
                //curl_setopt($ch,CURLOPT_RETURNTRANSFER, true)
            ));
          //  var_dump('listo para enviar');
            $response = curl_exec($curl);

            curl_close($curl);
           // var_dump($response);
           
           
            if ($response) {
           $this->output->set_content_type('text/css');
        $this->output->set_output('Envio Exitoso');
            //    echo 'Envio Exitoso echo';
            } else {
                
                $this->output->set_content_type('text/css');
        $this->output->set_output('No enviado 01');
       // echo 'No enviado 01 echo';
            }
        } else {
       
            $this->output->set_content_type('text/css');
        $this->output->set_output('NO enviado');
       //   echo 'NO enviado echo';
        }
    }

}
