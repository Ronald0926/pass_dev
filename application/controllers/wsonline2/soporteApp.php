<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
class SoporteApp extends CI_Controller{

private function soporte(
        $PARTIPCAT,
        $PARSUBCAT,
        $PARENTIDA,
        $PARUSUSOL,
        $PARROLUSU,
        $PARCAMPAN,
        $PARTELCON,
        $PARCORCON,
        $PARDESCRI,
        $PARTIPDOC,
        $PARDOCUME,
        $PARPRODUC,
        $PARNUMTAR,
        $PARVALNOTCRE,
        $PARCOMPAR,
        $PARNUMFAC,
        $PARVALANT,
        $PARCOMPAG,
        $PARNUMGUI,
        $PARNUMPED,
        $PARCANTAR,
        $PARURL,
        $PARNOMBREARC
        ) {
        $PARRESPUE = '';
    
        $sql = "BEGIN MODULOSAC.SACPKGEMPRESARIAL.PRCSOPORTEPAGEMP(
                    :PARTIPCAT, :PARSUBCAT,
                    :PARENTIDA, :PARUSUSOL,
                    :PARROLUSU, :PARCAMPAN,
                    :PARTELCON, :PARCORCON,
                    :PARDESCRI, :PARTIPDOC,
                    :PARDOCUME, :PARPRODUC,
                    :PARNUMTAR, :PARVALNOTCRE,
                    :PARCOMPAR, :PARNUMFAC,
                    :PARVALANT, :PARCOMPAG,
                    :PARNUMGUI, :PARNUMPED,
                    :PARCANTAR, :PARURL,
                    :PARNOMBREARC,:PARTICKET,
                    :PARRESPUE);
                    END;";
        $conn = $this->db->conn_id;
        $stmt = oci_parse($conn, $sql);

        oci_bind_by_name($stmt, ':PARTIPCAT', $PARTIPCAT, 32);
        oci_bind_by_name($stmt, ':PARSUBCAT', $PARSUBCAT, 32);
        oci_bind_by_name($stmt, ':PARENTIDA', $PARENTIDA, 32);
        oci_bind_by_name($stmt, ':PARUSUSOL', $PARUSUSOL, 32);
        oci_bind_by_name($stmt, ':PARROLUSU', $PARROLUSU, 32);
        oci_bind_by_name($stmt, ':PARCAMPAN', $PARCAMPAN, 32);
        oci_bind_by_name($stmt, ':PARTELCON', $PARTELCON, 32);
        oci_bind_by_name($stmt, ':PARCORCON', $PARCORCON, 32);
        oci_bind_by_name($stmt, ':PARDESCRI', $PARDESCRI, 3500);
        oci_bind_by_name($stmt, ':PARTIPDOC', $PARTIPDOC, 32);
        oci_bind_by_name($stmt, ':PARDOCUME', $PARDOCUME, 32);
        oci_bind_by_name($stmt, ':PARPRODUC', $PARPRODUC, 32);
        oci_bind_by_name($stmt, ':PARNUMTAR', $PARNUMTAR, 32);
        oci_bind_by_name($stmt, ':PARVALNOTCRE', $PARVALNOTCRE, 32);
        oci_bind_by_name($stmt, ':PARCOMPAR', $PARCOMPAR, 32);
        oci_bind_by_name($stmt, ':PARNUMFAC', $PARNUMFAC, 32);
        oci_bind_by_name($stmt, ':PARVALANT', $PARVALANT, 32);
        oci_bind_by_name($stmt, ':PARCOMPAG', $PARCOMPAG, 32);
        oci_bind_by_name($stmt, ':PARNUMGUI', $PARNUMGUI, 32);
        oci_bind_by_name($stmt, ':PARNUMPED', $PARNUMPED, 32);
        oci_bind_by_name($stmt, ':PARCANTAR', $PARCANTAR, 32);
        oci_bind_by_name($stmt, ':PARURL', $PARURL, 100);
        oci_bind_by_name($stmt, ':PARNOMBREARC', $PARNOMBREARC, 100);
        oci_bind_by_name($stmt, ':PARTICKET', $PARTICKET, 100);
        oci_bind_by_name($stmt, ':PARRESPUE', $PARRESPUE, 32);

        if (!oci_execute($stmt)) {
            $e = oci_error($stmt);
             VAR_DUMP($e);
            exit;
        } else if ($PARRESPUE != 1) {
            $PARRESPUE='0';
        }

        $soporte=array('ticket'=>$PARTICKET,
                        'respuesta'=>$PARRESPUE);
        return $soporte;
    }
    
    public function ayuda($pantalla = 0)
    {
        $post = $this->input->post();
        
        $appingreso=$post['app_ingreso'];
        $tipodedocumento = $_POST['tipo_documento'];
        $documento = $_POST['documento'];
        $telefono=$_POST['numero_celular'];
        $correo=$_POST['correo_electronico'];
        $asunto=$_POST['asunto'];
        $descripcion=$_POST['mensaje'];
        $soporte=$_POST['soporte']; // debe ingresar en base64
        
        
        $app_ingreso=$this->db->query("
                                        SELECT PK_APPWS_CODIGO CODIGO
                                        FROM MODGENERI.GENTBLAPPWS
                                        WHERE UPPER(USUARIO_APP)=UPPER('{$appingreso}')");
        $app_ingreso=$app_ingreso->result_array;
        
        if ($app_ingreso[0]['CODIGO']==1 || $app_ingreso[0]['CODIGO']==2 ) {
            
        } else {
            $arrayJson = array("codigorespuesta" => '2',
                "mensajerespuesta" => 'La appingreso no se encuentra registrado');

            $JSON = json_encode($arrayJson, JSON_UNESCAPED_UNICODE);
            echo $JSON;
            return;
        }

        if ($tipodedocumento) {
            
        } else {
            $arrayJson = array("codigorespuesta" => '3',
                "mensajerespuesta" => 'No diligenció el Tipo de Documento');

            $JSON = json_encode($arrayJson, JSON_UNESCAPED_UNICODE);
            echo $JSON;
            return;
        }
        if ($documento) {
            
        } else {
            $arrayJson = array("codigorespuesta" => '4',
                "mensajerespuesta" => 'No diligenció el Número Documento');

            $JSON = json_encode($arrayJson, JSON_UNESCAPED_UNICODE);
            echo $JSON;
            return;
        }
        if ($telefono) {
            
        } else {
            $arrayJson = array("codigorespuesta" => '5',
                "mensajerespuesta" => 'No diligenció el telefono para solicitud');

            $JSON = json_encode($arrayJson, JSON_UNESCAPED_UNICODE);
            echo $JSON;
            return;
        }
         
        if ($correo) {
            
        } else {
            $arrayJson = array("codigorespuesta" => '6',
                "mensajerespuesta" => 'No diligenció el Correo electrónico para solicitud');

            $JSON = json_encode($arrayJson, JSON_UNESCAPED_UNICODE);
            echo $JSON;
            return;
        }
        
        if ($asunto) {
            
        } else {
            $arrayJson = array("codigorespuesta" => '7',
                "mensajerespuesta" => 'No diligenció el Asunto para solicitud');

            $JSON = json_encode($arrayJson, JSON_UNESCAPED_UNICODE);
            echo $JSON;
            return;
        }
        
        if ($descripcion) {
            $descripcion=$asunto.' '.$descripcion;
        } else {
            $arrayJson = array("codigorespuesta" => '8',
                "mensajerespuesta" => 'No diligenció el descripción para solicitud');

            $JSON = json_encode($arrayJson, JSON_UNESCAPED_UNICODE);
            echo $JSON;
            return;
        }
        
       /* if ($soporte) {
            
        } else {
            $arrayJson = array("codigorespuesta" => '7',
                "mensajerespuesta" => 'No diligenció el soporte para solicitud');

            $JSON = json_encode($arrayJson, JSON_UNESCAPED_UNICODE);
            echo $JSON;
            return;
        }*/
        
        
       $usuario_ingreso=$this->db->query("
                                        SELECT PK_ENT_CODIGO CODIGO,USUARIO_ACCESO
                                        FROM MODCLIUNI.CLITBLENTIDA
                                        WHERE UPPER(DOCUMENTO)=UPPER('{$documento}')
                                        AND CLITBLTIPDOC_PK_TD_CODIGO={$tipodedocumento}");
        $usuario_ingreso= $usuario_ingreso->result_array;   
        $codigousuario=$usuario_ingreso[0]['CODIGO'];
        $usuario_acceso=$usuario_ingreso[0]['USUARIO_ACCESO'];
        
       if ($soporte) {
                
                
                $folderPath = "uploads/soporteapp/";
                $image_parts = explode(";base64,", $soporte);
                $image_type_aux = explode("uploads/", $image_parts[0]);
                $image_base64 = base64_decode($image_parts[1]);
                $name = uniqid() . '.png';
                $file = $folderPath . $name;
                file_put_contents($file, $image_base64);
                $ubidig="/" . $file;
                $url = 'http://' . $_SERVER['SERVER_ADDR'] . ':' . $_SERVER['SERVER_PORT'].$ubidig ;
       
       }
        
            $respuesta = $this->soporte(
                20, //$PARTIPCAT,
                30, //$PARSUBCAT,
                $codigousuario, //$PARENTIDA,
                $usuario_acceso, //$PARUSUSOL,rol
                null, //$PARROLUSU,
                null, //$PARCAMPAN,
                $telefono, //$PARTELCON,
                $correo, //$PARCORCON,
                $descripcion, //$PARDESCRI,
                $tipodedocumento, //$PARTIPDOC,
                $documento, //$PARDOCUME,
                null, //$PARPRODUC,
                null, //$PARNUMTAR,
                null, //$PARVALNOTCRE,
                null, //$PARCOMPAR,
                null, //$PARNUMFAC,
                null, //$PARVALANT,
                null, //$PARCOMPAG,
                null, //$PARNUMGUI,
                null, //$PARNUMPED,
                null, //$PARCANTAR
                $url, //$PARURL
                'Imagen '.uniqid() //$PARNOMBREARC
            );
           
            
          
           
            
            if ($respuesta['respuesta'] == 1) {
               $arrayJson = array("ticket"=>$respuesta['ticket'],
                   "codigorespuesta" => '1',
                "mensajerespuesta" => 'Consumo correcto');

            $JSON = json_encode($arrayJson, JSON_UNESCAPED_UNICODE);
            echo $JSON;
            } else {
				unlink($file);
                $arrayJson = array( 
                   "codigorespuesta" => '8',
                "mensajerespuesta" => 'No se puede crear la solicitud en estos momentos');

            $JSON = json_encode($arrayJson, JSON_UNESCAPED_UNICODE);
            echo $JSON;
            }
        }
        
}