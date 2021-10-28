<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class updateFileLoaded extends CI_Controller {

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

        $dominio = $this->db->query("select VALOR_PARAMETRO from modgeneri.gentblpargen where PK_PARGEN_CODIGO = 96");
        $dominio = $dominio->result_array[0];
        $dominio = $dominio['VALOR_PARAMETRO'];

        ini_set('post_max_size', '12M');
        ini_set('upload_max_filesize', '12M');
        header('Access-Control-Allow-Origin: *');
        date_default_timezone_set('America/Los_Angeles');

        $dir = 'uploads/fuc/';
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
        //var_dump($result);
        log_info($this->iniciLog . $this->logHeader . ' RESULT : ' . $result);

        if ($result) {
            
            $response->url = $url;
            $response->message = 'Se cargo el archivo exitosamente.';
            $response->success = true;
            log_info($this->iniciLog . $this->logHeader . ' URL' . $url);
            //echo $url;
            if (true) {
                $codigo_archivo = $_POST['codigo_archivo'];
                $nombre_archivo = $split_name_file[0]; //$_POST['nombre_archivo'];
                //$url = $_POST['url'];
                $numero_cot_encript = $_POST['proceso'];
                $sqlcotizacion = "SELECT MODGENERI.GENPKGCLAGEN.DECRYPT('$numero_cot_encript') NUM_COT from dual";
                $numcotizacion = $this->db->query($sqlcotizacion);
                $proceso = $numcotizacion->result_array[0]['NUM_COT'];

//                $proceso = $_POST['proceso'];

                $sql = "BEGIN 
            modarccor.arcpkgactualizaciones.prcpacactulizaubidigital(parcodarchi=>:parcodarchi
                                                         ,parorignomb=> :parorignomb
                                                         ,parurl=> :parurl
                                                         ,parproceso=>:parproceso
                                                         , parrespues=> :parrespues);
                        END;";
                $conn = $this->db->conn_id;
                $stmt = oci_parse($conn, $sql);
                //TIPO NUMBER INPUT
                oci_bind_by_name($stmt, ':parcodarchi', $codigo_archivo, 32);
                oci_bind_by_name($stmt, ':parorignomb', $nombre_archivo, 32);
                oci_bind_by_name($stmt, ':parurl', $url, 500);
                oci_bind_by_name($stmt, ':parproceso', $proceso, 1000);
                oci_bind_by_name($stmt, ':parrespues', $parrespues, 150);


                if (!@oci_execute($stmt)) {
                    // $e = oci_error($stmt);
                    //var_dump($e);
                    echo 0;
                } else {
                    echo $parrespues;
                }
            } else {
                echo 'sin dato';
            }
        } else {
            echo ' ' . $temp_file; //json_encode($response) ;//$response;
        }
    }

    public function uploadsApolo() {
        log_info($this->iniciLog . $this->logHeader);
        $dominio = $this->db->query("select VALOR_PARAMETRO from modgeneri.gentblpargen where PK_PARGEN_CODIGO = 96");
        $dominio = $dominio->result_array[0];
        $dominio = $dominio['VALOR_PARAMETRO'];

        ini_set('post_max_size', '12M');
        ini_set('upload_max_filesize', '12M');
        header('Access-Control-Allow-Origin: *');
        date_default_timezone_set('America/Los_Angeles');

        $dir = 'uploads/archivo/';
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
                $codigo_archivo = $_POST['codigo_archivo'];
                $nombre_archivo = $split_name_file[0]; //$_POST['nombre_archivo'];
                //$url = $_POST['url'];
                $proceso = $_POST['proceso'];
                if ($proceso = 'undefined') {
                    $proceso = 0;
                }
                log_info($this->logHeader . ' DATOS PROCEDIMIENTO UBICACION DIGITAL codigo_archivo '
                        . $codigo_archivo . ' $proceso ' . $proceso . ' nombre_archivo ' . $nombre_archivo);
                $sql = "BEGIN 
            modarccor.arcpkgactualizaciones.prcpacactulizaubidigital(parcodarchi=>:parcodarchi
                                                         ,parorignomb=> :parorignomb
                                                         ,parurl=> :parurl
                                                         ,parproceso=>:parproceso
                                                         , parrespues=> :parrespues);
                        END;";
                $conn = $this->db->conn_id;
                $stmt = oci_parse($conn, $sql);
                //TIPO NUMBER INPUT
                oci_bind_by_name($stmt, ':parcodarchi', $codigo_archivo, 32);
                oci_bind_by_name($stmt, ':parorignomb', $nombre_archivo, 32);
                oci_bind_by_name($stmt, ':parurl', $url, 500);
                oci_bind_by_name($stmt, ':parproceso', $proceso, 1000);
                oci_bind_by_name($stmt, ':parrespues', $parrespues, 150);
                log_info($this->iniciLog . $this->logHeader . ' DATOS PROCEDIMIENTO '
                        . ' :parcodarchi ' . $codigo_archivo
                        . ':parorignomb ' . $nombre_archivo
                        . ':parurl ' . $url
                        . ':parproceso ' . $proceso);

                if (!@oci_execute($stmt)) {
                    $e = oci_error($stmt);
                    var_dump($e);
                    log_info($this->iniciLog . $this->logHeader . $e);
                    echo 0;
                } else {
                    $pkdebidadil = $_POST['debidadeil'];
                    if ($pkdebidadil = 'undefined') {
                        $pkdebidadil = 0;
                    }
                    if ($pkdebidadil != 0) {
                        $sql = "BEGIN 
                            modarccor.arcpkgactualizaciones.prcfinalizadebidadil(PARURLINFORMEIN=>:parurl,
                                   PARPKDEBDIL_CODIGO=>:parpkdebida 
                                   ,PARRESPUEST=>:parrespuesta); END;";
                        $conn = $this->db->conn_id;
                        $stmt = oci_parse($conn, $sql);
                        //TIPO NUMBER INPUT
                        oci_bind_by_name($stmt, ':parurl', $url, 500);
                        oci_bind_by_name($stmt, ':parpkdebida', $url, 500);
                        oci_bind_by_name($stmt, ':parrespuesta', $respuesta1, 500);
                        if (!@oci_execute($stmt)) {
                            $e = oci_error($stmt);
                            var_dump($e);
                            log_info($this->iniciLog . $this->logHeader . $e);
                            echo 0;
                        } else {
                            log_info($this->logHeader . ' ACTUALIZACION DE DEBIDA DILIGENCIA ' . $respuesta1);
                        }
                    }
                    echo $parrespues;
                    log_info($this->logHeader . ' RESULTADO UBICACION DIGITAL ' . $parrespues);
                }
            } else {
                echo 'sin dato';
            }
        } else {
            echo ' ' . $temp_file; //json_encode($response) ;//$response;
        }
    }

    public function uploadsPoliticas() {
        log_info($this->iniciLog . $this->logHeader);
        $dominio = $this->db->query("select VALOR_PARAMETRO from modgeneri.gentblpargen where PK_PARGEN_CODIGO = 96");
        $dominio = $dominio->result_array[0];
        $dominio = $dominio['VALOR_PARAMETRO'];

        ini_set('post_max_size', '12M');
        ini_set('upload_max_filesize', '12M');
        header('Access-Control-Allow-Origin: *');
        date_default_timezone_set('America/Los_Angeles');

        $dir = 'uploads/politicas/';
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

        if ($result) {
            $response->url = $url;
            $response->message = 'Se cargo el archivo exitosamente.';
            $response->success = true;
            echo $url;
            if (true) {
                $codigo_archivo = $_POST['codigo_politica'];
                $nombre_archivo = $split_name_file[0]; //$_POST['nombre_archivo'];
                //$url = $_POST['url'];

                log_info($this->logHeader . ' DATOS PROCEDIMIENTO UBICACION DIGITAL codigo_archivo ' . $codigo_archivo
                        . ' nombre_archivo ' . $nombre_archivo);
                $sql = "BEGIN 
                    modgeneri.genpkgadmiweb.prcactualizarurlpoliticas(parid_politica =>:parid_politica,
                                    parnombre_archivo =>:parnombre_archivo,
                                    parurl     =>:parurl,
                                    parrespuesta=>:parrespuesta);
                        END;";
                $conn = $this->db->conn_id;
                $stmt = oci_parse($conn, $sql);
                //TIPO NUMBER INPUT
                oci_bind_by_name($stmt, ':parid_politica', $codigo_archivo, 10);
                oci_bind_by_name($stmt, ':parnombre_archivo', $nombre_archivo, 100);
                oci_bind_by_name($stmt, ':parurl', $url, 1000);
                oci_bind_by_name($stmt, ':parrespuesta', $proceso, 32);

                log_info($this->iniciLog . $this->logHeader . ' DATOS PROCEDIMIENTO '
                        . ' :parid_politica ' . $codigo_archivo
                        . ':parnombre_archivo ' . $nombre_archivo
                        . ':parurl ' . $url
                        . ':parrespuesta ' . $proceso);

                if (!@oci_execute($stmt)) {
                    $e = oci_error($stmt);
                    var_dump($e);
                    log_info($this->iniciLog . $this->logHeader . $e);
                    echo 0;
                }
            } else {
                echo 'sin datoS';
            }
        } else {
            echo ' ' . $temp_file; //json_encode($response) ;//$response;
        }
    }


  public function uploadsExtracto() {
        log_info($this->iniciLog . $this->logHeader);
        $dominio = $this->db->query("select VALOR_PARAMETRO from modgeneri.gentblpargen where PK_PARGEN_CODIGO = 96");
        $dominio = $dominio->result_array[0];
        $dominio = $dominio['VALOR_PARAMETRO'];

        ini_set('post_max_size', '12M');
        ini_set('upload_max_filesize', '12M');
        header('Access-Control-Allow-Origin: *');
        date_default_timezone_set('America/Los_Angeles');

        $dir = 'uploads/extracto/';
        $date = date('Y-m-d-H-i-s');
        $random = rand(1000, 9999);
        $split_name_file = explode('.', basename($_FILES['file']['name']));
        $extention = end($split_name_file);
        $name = strtolower($date . '-' . $random . '.' . $extention);
        $file_dir = $dir . $name;
        $url = $dominio . '/' . $dir . $name;

        $temp_file = $_FILES['file']['tmp_name'];

        $result = move_uploaded_file($temp_file, $file_dir);
        log_info($this->iniciLog . $this->logHeader . ' Resultado Cargue archivo: ' . $result);

        if ($result) {
            $response->url = $url;
            $response->message = 'Se cargo el archivo exitosamente.';
            $response->success = true;
            echo $url;
            if (true) {
                $codigo_archivo = $_POST['CODIGO_IMGEXTRACTO']; //esto de donde viene?
                //$descripcion = $_POST['DESCRIPCION'];
                //$tipo_archivo = $_POST['TIPO_ARCHIVO'];
                $nombre_archivo = $split_name_file[0];
                log_info($this->logHeader . ' DATOS PROCEDIMIENTO UBICACION DIGITAL codigo_archivo ' . $codigo_archivo
                        . ' nombre_archivo ' . $nombre_archivo);
                $sql = "BEGIN 
                    modgeneri.genpkgadmiweb.prcactualizarimgextracto(parid_extracto=>:parid_extracto,
                                    parurl=>:parurl,
                                    parrespuesta=>:parrespuesta);
                        END;";
                $conn = $this->db->conn_id;
                $stmt = oci_parse($conn, $sql);
                //TIPO NUMBER INPUT
                oci_bind_by_name($stmt, ':parid_extracto', $codigo_archivo, 10);
                oci_bind_by_name($stmt, ':parurl', $url, 1000);
                oci_bind_by_name($stmt, ':parrespuesta', $proceso, 32);

                log_info($this->iniciLog . $this->logHeader . ' DATOS PROCEDIMIENTO IMAGENES EXTRACTO'
                        . ' :parid_extracto ' . $codigo_archivo
                        . ':parnombre_archivo ' . $nombre_archivo
                        . ':pardescripcion' . $descripcion
                        . ':parurl ' . $url
                        . ':partiparc ' . $tipo_archivo
                        . ':parrespuesta ' . $proceso);

                if (!@oci_execute($stmt)) {
                    $e = oci_error($stmt);
                    var_dump($e);
                    log_info($this->iniciLog . $this->logHeader . $e);
                    echo 0;
                }
            } else {
                echo 'sin datos';
            }
        } else {
            echo ' ' . $temp_file; //json_encode($response) ;//$response;
        }
    }

}
