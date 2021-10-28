<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class LegalizarGastosLlaveMaestra extends CI_Controller {

    public function __construct() {
        parent::__construct();
    }

    public function __destruct() {
        $this->db->close();
    }

    public function legalizarAbono() {
        $post = $this->input->post();
        $appingreso = $_POST['app_ingreso'];
        $tipodedocumento = $_POST['tipo_documento'];
        $documento = $_POST['documento'];
        $pkabonocodigo = $_POST['abono_codigo'];
        $imgSoporte = 'data:image/png;base64,'.$_POST['imagen'];
        $montoAlegalizar = $_POST["monto"];
        $observacion=$_POST["observacion"];
        $pkmovimiento = $_POST["codigo_movimiento"];
        $app_ingreso = $this->db->query("SELECT PK_APPWS_CODIGO CODIGO
                                        FROM MODGENERI.GENTBLAPPWS
                                        WHERE UPPER(USUARIO_APP)=UPPER('{$appingreso}')");
        $app_ingreso = $app_ingreso->result_array;
        $urlpublica = $this->db->query("select VALOR_PARAMETRO from modgeneri.gentblpargen where pk_pargen_codigo =96");
                $urlpublica = $urlpublica->result_array[0]['VALOR_PARAMETRO'];

        if ($app_ingreso[0]['CODIGO'] !== "1") {
            $arrayJson = array(
                "codigo_respuesta" => '2',
                "mensaje_respuesta" => 'La appingreso no se encuentra registrado'
            );
            $JSON = json_encode($arrayJson, JSON_UNESCAPED_UNICODE);
            echo $JSON;
            return;
        }

        if (empty($tipodedocumento)) {
            $arrayJson = array(
                "codigo_respuesta" => '3',
                "mensaje_respuesta" => 'No diligenció el Tipo de Documento'
            );

            $JSON = json_encode($arrayJson, JSON_UNESCAPED_UNICODE);
            echo $JSON;
            return;
        } elseif (empty($documento)) {
            $arrayJson = array(
                "codigo_respuesta" => '4',
                "mensaje_respuesta" => 'No diligenció el Número Documento'
            );
            $JSON = json_encode($arrayJson, JSON_UNESCAPED_UNICODE);
            echo $JSON;
            return;
        } elseif (empty($pkabonocodigo)) {
            $arrayJson = array(
                "codigo_respuesta" => '8',
                "mensaje_respuesta" => 'No se ingreso codigo del abono a legalizar'
            );
            $JSON = json_encode($arrayJson, JSON_UNESCAPED_UNICODE);
            echo $JSON;
            return;
        }
        $usuario_ingreso = $this->db->query("
                                        SELECT PK_ENT_CODIGO CODIGO
                                        FROM MODCLIUNI.CLITBLENTIDA
                                        WHERE UPPER(DOCUMENTO)=UPPER('{$documento}')
                                        AND CLITBLTIPDOC_PK_TD_CODIGO={$tipodedocumento}");
        $usuario_ingreso = $usuario_ingreso->result_array;
        $codigousuario = $usuario_ingreso[0]['CODIGO'];
        if (empty($codigousuario) || $codigousuario == NULL) {
            $arrayJson = array("codigo_respuesta" => '6', "mensajerespuesta" => 'El usuario no existe');
            $JSON = json_encode($arrayJson, JSON_UNESCAPED_UNICODE);
            echo $JSON;
            return;
        }
        $totalLegalizaciones = null;
        $parrespuesta = "";
        $parmensajerespuesta = "";
        if (empty($imgSoporte)) {
            $arrayJson = array(
                "codigo_respuesta" => '5',
                "mensaje_respuesta" => 'No selecciono la imagen de soporte'
            );
            $JSON = json_encode($arrayJson, JSON_UNESCAPED_UNICODE);
            echo $JSON;
            return;
        } elseif (empty($montoAlegalizar)) {
            $arrayJson = array(
                "codigo_respuesta" => '7',
                "mensaje_respuesta" => 'No se ingreso monto a legalizar'
            );
            $JSON = json_encode($arrayJson, JSON_UNESCAPED_UNICODE);
            echo $JSON;
            return;
        }


        $folderPath = "uploads/soportesLegalizacion/";
        $image_parts = explode(";base64,", $imgSoporte);
        $image_type_aux = explode("uploads/", $image_parts[0]);
        $image_base64 = base64_decode($image_parts[1]);
        $name = uniqid() . '.png';
        $file = $folderPath . $name;
        file_put_contents($file, $image_base64);
        $ubidig = "/" . $file;
        $url = $urlpublica . $ubidig;

        $sql = "BEGIN modllavemaestra.LLAVMAEPKGGENERAL.prclegalizaciongastos(
                        parmontolegalizar =>:parmontolegalizar,
                        parpkmovimiento=>:parpkmovimiento,
                        parurlsoporte=>:parurlsoporte,
                        parobservacion=>:parobservacion,
                        parpkabonocodigo=>:parpkabonocodigo,
                        parmensajerespuesta=>:parmensajerespuesta,
                        parrespuesta=>:parrespuesta);
                        END;";
        $conn = $this->db->conn_id;
        $stmt = oci_parse($conn, $sql);
        //TIPO NUMBER INPUT
        oci_bind_by_name($stmt, ':parmontolegalizar', $montoAlegalizar, 32);
        oci_bind_by_name($stmt, ':parpkmovimiento', $pkmovimiento, 32);
        oci_bind_by_name($stmt, ':parurlsoporte', $url, 500);
        oci_bind_by_name($stmt, ':parobservacion', $observacion, 1000);
        oci_bind_by_name($stmt, ':parpkabonocodigo', $pkabonocodigo, 150);
        oci_bind_by_name($stmt, ':parmensajerespuesta', $parmensajerespuesta, 250);
        oci_bind_by_name($stmt, ':parrespuesta', $parrespuesta, 32);

        if (!@oci_execute($stmt)) {
            $e = oci_error($stmt);
            var_dump($e);
        }
        if ($parrespuesta == 1) {
            $totalLegalizaciones++;
            $arrayJson = array(
                "codigo_respuesta" => $parrespuesta,
                "mensajerespuesta" => $parmensajerespuesta
            );
        } else {
            $arrayJson = array(
                "codigo_respuesta" => $parrespuesta,
                "mensaje_respuesta" => $parmensajerespuesta
            );
        }


        $arrayJson = array(
            "codigo_respuesta" => $parrespuesta,
            "mensaje_respuesta" => $parmensajerespuesta
                //"totalLegalizaciones" => $totalLegalizaciones.' de '.count($dataPost["abonoslegalizar"])
        );
        $JSON = json_encode($arrayJson, JSON_UNESCAPED_UNICODE);
        echo $JSON;
    }

}
