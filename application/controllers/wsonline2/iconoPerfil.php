<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class IconoPerfil extends CI_Controller {
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

    public function guardar() {
       // $post = $this->input->post();
        $appingreso = $_POST['app_ingreso'];
        $tipodedocumento = $_POST['tipo_documento'];
        $documento = $_POST['documento'];
        $img = 'data:image/png;base64,'.$_POST['imagen'];
        log_info($this->iniciLog.$this->logHeader. $this->encabezados.' $appingreso '.$appingreso);
        log_info($this->iniciLog.$this->logHeader. $this->encabezados.' $tipodedocumento '.$tipodedocumento);
        log_info($this->iniciLog.$this->logHeader. $this->encabezados.' $documento '.$documento);
        log_info($this->iniciLog.$this->logHeader. $this->encabezados.' $img '.$img);
        $app_ingreso=$this->db->query("
                                        SELECT PK_APPWS_CODIGO CODIGO
                                        FROM MODGENERI.GENTBLAPPWS
                                        WHERE UPPER(USUARIO_APP)=UPPER('{$appingreso}')");
        $app_ingreso=$app_ingreso->result_array;
        
        if ($app_ingreso[0]['CODIGO']==1 || $app_ingreso[0]['CODIGO']==2 ) {
            
        } else {
            $arrayJson = array("codigo_respuesta" => '2',
                "mensaje_respuesta" => 'La appingreso no se encuentra registrado');

            $JSON = json_encode($arrayJson, JSON_UNESCAPED_UNICODE);
            echo $JSON;
            return;
        }

        if ($tipodedocumento) {
            
        } else {
            $arrayJson = array("codigo_respuesta" => '3',
                "mensaje_respuesta" => 'No diligenció el Tipo de Documento');

            $JSON = json_encode($arrayJson, JSON_UNESCAPED_UNICODE);
            echo $JSON;
            return;
        }
        if ($documento) {
            
        } else {
            $arrayJson = array("codigo_respuesta" => '4',
                "mensaje_respuesta" => 'No diligenció el Número Documento');

            $JSON = json_encode($arrayJson, JSON_UNESCAPED_UNICODE);
            echo $JSON;
            return;
        }
        if ($img) {
            
        } else {
            $arrayJson = array("codigo_respuesta" => '5',
                "mensaje_respuesta" => 'Imagen no encontrada');

            $JSON = json_encode($arrayJson, JSON_UNESCAPED_UNICODE);
            echo $JSON;
            return;
        }
        
        $usuario_ingreso=$this->db->query("
                                        SELECT PK_ENT_CODIGO CODIGO
                                        FROM MODCLIUNI.CLITBLENTIDA
                                        WHERE UPPER(DOCUMENTO)=UPPER('{$documento}')
                                        AND CLITBLTIPDOC_PK_TD_CODIGO={$tipodedocumento}");
        $usuario_ingreso= $usuario_ingreso->result_array;   
        $codigousuario=$usuario_ingreso[0]['CODIGO'];
        if($codigousuario!=NULL){
        
            
            if ($img) {    
                $urlpublica = $this->db->query("select VALOR_PARAMETRO from modgeneri.gentblpargen where pk_pargen_codigo =99");
                $urlpublica=$urlpublica->result_array[0];        
               
                //$dir = '/var/www/html/uploads/';
                $folderPath = "uploads/perfil/";
                $image_parts = explode(";base64,", $img);
                $image_type_aux = explode("uploads/", $image_parts[0]);
                $image_base64 = base64_decode($image_parts[1]);
                $name = uniqid() . '.png';
                $file = $folderPath . $name;
                file_put_contents($file, $image_base64);
                $ubidig="/" . $file;
                $url= $urlpublica['VALOR_PARAMETRO'].'/'.$folderPath.$name; 
                $guardar= $this->actualizarIcono($tipodedocumento ,$documento ,$url ,$codigousuario );
                
                if ($guardar==1){
                $server=$urlpublica['VALOR_PARAMETRO'];
                $arrayJson = array("imagen" => $url,
                    "codigo_respuesta" => '1',
                    "mensaje_respuesta" => 'Consumo Correcto');
                }
                else{
                    $arrayJson = array(
                    "codigo_respuesta" => $guardar,
                    "mensaje_espuesta" => 'No se puede guardar la Imagen');
                }
                $JSON = json_encode($arrayJson, JSON_UNESCAPED_UNICODE);
                echo $JSON;
                
            }
        }else{
            $arrayJson = array("codigo_respuesta" => '6',
                "mensaje_respuesta" => 'El usuario no existe');

            $JSON = json_encode($arrayJson, JSON_UNESCAPED_UNICODE);
            echo $JSON;
            return;
        }
    }
    
    public function actualizarIcono($tipodocumento=null,$documento=null,$ubidigicono=null,$codigoentida=null){
        $sql = "BEGIN modwsonline2.wspkgactualizaciones.actualizaricono(
                    tipodocumento =>:tipodocumento,
                    pardocumento=>:pardocumento,
                    ubidigicono=>:ubidigicono,
                    pkentida=>:pkentida,
                    parrespuesta=>:parrespuesta);
                    END;";
            $conn = $this->db->conn_id;
            $stmt = oci_parse($conn, $sql);
        //TIPO NUMBER INPUT
        oci_bind_by_name($stmt, ':tipodocumento', $tipodocumento, 32);
        oci_bind_by_name($stmt, ':pardocumento', $documento, 32);
        oci_bind_by_name($stmt, ':ubidigicono', $ubidigicono, 150);
        oci_bind_by_name($stmt, ':pkentida', $codigoentida, 32);
        oci_bind_by_name($stmt, ':parrespuesta', $parrespuesta, 32);
        
        if (!@oci_execute($stmt)) {
            $e = oci_error($stmt);
            var_dump($e);
            return 3;
        }
        if($parrespuesta==1){
            return 1;
        }else {
            return 0;
        }
            
    }
    
}

