<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class NotaContable extends CI_Controller {

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

    public function crearPdf() {
        log_info($this->iniciLog . $this->logHeader . ' CREAR PDF NOTA CONTABLE');
        $dominio = $this->db->query("select VALOR_PARAMETRO from modgeneri.gentblpargen where PK_PARGEN_CODIGO = 96");
        $dominio = $dominio->result_array[0];
        $dominio = $dominio['VALOR_PARAMETRO'];
        log_info($this->iniciLog . $this->logHeader . ' DOMINIO ' . $dominio);
        require_once 'mpdf/vendor/autoload.php';

        $data_body['numero_nota'] = $_POST['NUMNOTA'];
        $data_body['nombre_cliente'] = $_POST['NOMBRECLIENTE'];
        $data_body['nit_cliente'] = $_POST['NITCLIENTE'];
        $data_body['direccion_cliente'] = $_POST['DIRECCIONCLI'];
        $data_body['telefono_cliente'] = $_POST['TELEFONOCLI'];
        $data_body['fecha_nota'] = $_POST['FECHANOTA'];
        $data_body['ciudad_cliente'] = $_POST['CIUDADCLI'];
        $pknota = $_POST['PKNOTA'];
        $data_body['subtotal'] = $_POST['SUBTOTAL'];
        $data_body['costo_operativo'] = $_POST['COSTOOPER'];
        $data_body['iva_costo'] = $_POST['IVACOSTOOPER'];
        $total_nota = $_POST['TOTALNOTA'];
        $data_body['total_nota'] = $total_nota;
        $data_body['registro_contable'] = $_POST['REGCONTABLE'];
        $data_body['valor_4x1000'] = $_POST['VALOR4X1000'];

        $NOMBREPEOPLE = $_POST['NOMBREPEOPLE'];
        $NITPEOPLE = $_POST['NITPEOPLE'];
        //$DETALLE = $_POST['DETALLE'];
        log_info($this->iniciLog . $this->logHeader . ' CLIENTE ' . $NITCLIENTE);
        if (isset($_POST['PKNOTA'])) {

            $sqldetalle = $this->db->query("SELECT
                                        prod.NOMBRE_PRODUCTO PRODUCTO, 
                                        COUNT (1) CANTIDAD ,
                                        SUM (MONTO) VALOR
                                        FROM
                                            MODDISPER.DISTBLRENOTC rev
                                            JOIN MODPRODUC.PROTBLPRODUC prod
                                            on rev.PK_PRODUCTO_ORIGEN=prod.pk_produc_codigo
                                        WHERE
                                            PK_NOTA_CODIGO = $pknota
                                            group by prod.nombre_producto");
            $sqldetalle = $sqldetalle->result_array;
        }
        $data_body['detalles'] = $sqldetalle;
        $numero_letras = $this->numeros_letras($total_nota);
        $data_body['numero_letras'] = $numero_letras;
        $consulta4x1000= $this->db->query("SELECT sum(rev.valor_4x1000) VALOR
                                        FROM
                                            MODDISPER.DISTBLRENOTC rev
                                            JOIN MODPRODUC.PROTBLPRODUC prod
                                            on rev.PK_PRODUCTO_ORIGEN=prod.pk_produc_codigo
                                        WHERE
                                            PK_NOTA_CODIGO = $pknota");
        $data_body['VALOR_4x1000']=$consulta4x1000->result_array[0]['VALOR'];

        //$this->load->view('/wsonline2/notaContable/pdfNotaContable', $data_body);
        $contenido = $this->load->view('/wsonline2/notaContable/pdfNotaContable', $data_body, TRUE);
        
       
        
        log_info($this->iniciLog . $this->logHeader . ' CONTENIDO :::::::' . $contenido);
        $dir = 'uploads/notacontable/';
        $date = date('Y-m-d');
        $random = rand(1000, 9999);
        $name = strtolower($date . '-' . $random . '.pdf');
        $file_dir = $dir . $name;
        //$url = 'http://' . $_SERVER['SERVER_ADDR'] . ':' . $_SERVER['SERVER_PORT'] . '/uploads/' . $name;
        $url = $dominio . '/' . $dir . $name;
        $nombre = $file_dir;

        $mpdf = new \Mpdf\Mpdf(['tempDir' => 'mpdf/tmp']);
        try {
            //$mpdf->WriteHTML($css, \Mpdf\HTMLParserMode::HEADER_CSS);
            $html = mb_convert_encoding($contenido, 'UTF-8', 'UTF-8');
            $mpdf->WriteHTML($html);
            $mpdf->SetHTMLFooter('Transversal 55B # 115A 56 - Pbx: 743 47 00 soporte.cliente@peoplepass.com.co - www.peoplepass.com.co Bogot&aacute; D.C. ');
            $mpdf->Output($nombre, 'F');
            echo $url;
            log_info($this->iniciLog . $this->logHeader . ' CREADO CORRECTAMENTE ' . $url);
        } catch (Exception $ex) {
            log_error($this->logHeader . $ex->getMessage());
        }
    }

    public function numeros_letras($valor = 0) {
        log_info($this->iniciLog . $this->logHeader . ' VALOR ' . $valor);
        //$valor=number_format(replace($valor,'-',0), 0, ',', '.');
        $sql = "begin 
             :varvalor:=modgeneri.genpkgutilidades.numero_a_letras($valor);
            end;";

        $conn = $this->db->conn_id;
        $stmt = oci_parse($conn, $sql);
        $valorletras = '';
        oci_bind_by_name($stmt, ':varvalor', $valorletras, 200);

        if (!oci_execute($stmt)) {
            $e = oci_error($stmt);
            $valorletras = '';
            VAR_DUMP($e);
            exit;
        }
        return ucwords($valorletras);
    }

}
