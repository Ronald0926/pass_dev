<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class UpdateFileLoaded extends CI_Controller {

    public $iniciLog = '[INFO] ';
    public $logHeader = 'APOLOINFO::::::::: ';
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

    public function actualizar() {
        log_info($this->iniciLog . $this->logHeader);
        $dominio = $this->db->query("select VALOR_PARAMETRO from modulosac.gentblpargen where PK_PARGEN_CODIGO = 1");
        $dominio = $dominio->result_array[0];
        $dominio = $dominio['VALOR_PARAMETRO'];

        ini_set('post_max_size', '12M');
        ini_set('upload_max_filesize', '12M');
        header('Access-Control-Allow-Origin: *');
        date_default_timezone_set('America/Los_Angeles');

        $dir = 'uploads/modulosac/';
        $date = date('Y-m-d-H-i-s');
        $random = rand(1000, 9999);
        $split_name_file = explode('.', basename($_FILES['file']['name']));
        $extention = end($split_name_file);
        $name = strtolower($date . '-' . $random . '.' . $extention);
        $file_dir = $dir . $name; //.basename($_FILES['file']['name']);
        // $url = 'http://'.$_SERVER['SERVER_ADDR'].':'.$_SERVER['SERVER_PORT'].'/uploads/'.$name;   
        // $url = 'http://www.peoplepassonline.co:8090/uploads/' . $name;
        $url = $dominio . '/' . $dir . $name;

        $temp_file = $_FILES['file']['tmp_name'];

        $result = move_uploaded_file($temp_file, $file_dir);
        log_info($this->iniciLog . $this->logHeader . ' Resultado Cargue archivo ' . $result);
        // var_dump($result);
        if ($result) {
            $response->url = $url;
            $response->message = 'Se cargo el archivo exitosamente.';
            $response->success = true;
            echo $url;
            if (true) {
                $ticket = $_POST['ticket'];
                $nombre = $split_name_file[0];//$_POST['nombre'];
                $usuario = $_POST['usuario'];

                log_info($this->logHeader . ' DATOS PROCEDIMIENTO PROCEDIMIENTO ADJUNTO ticket '
                        . $ticket . ' $nombre ' . $nombre . ' usuario ' . $usuario);
                $sql = "BEGIN 
                modulosac.SACPKGEMPRESARIAL.prcadjunto(
                                    particket =>:particket,
                                    parnombre =>:parnombre,
                                    parurl =>:parurl,
                                    parusuario =>:parusuario,
                                    parrespues =>:parrespues
                                         );
                        END;";
                $conn = $this->db->conn_id;
                $stmt = oci_parse($conn, $sql);
                //TIPO NUMBER INPUT
                oci_bind_by_name($stmt, ':particket', $ticket, 32);
                oci_bind_by_name($stmt, ':parnombre', $nombre, 32);
                oci_bind_by_name($stmt, ':parurl', $url, 500);
                oci_bind_by_name($stmt, ':parusuario', $usuario, 1000);
                oci_bind_by_name($stmt, ':parrespues', $parrespues, 150);


                if (!@oci_execute($stmt)) {
                    // $e = oci_error($stmt);
                    //var_dump($e);
                    log_info($this->iniciLog . $this->logHeader . $e);
                    echo 0;
                } else {
                    log_info($this->iniciLog . $this->logHeader . ' parrespues ' . $parrespues);
                    echo $parrespues;
                }
            } else {
                echo 'sin dato';
            }
        } else {
            echo ' ' . $temp_file; //json_encode($response) ;//$response;
        }
    }

}
