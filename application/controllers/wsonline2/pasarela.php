<?php

ini_set("pcre.backtrack_limit", "5000000");
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Pasarela extends CI_Controller {

    public $iniciLog = '[INFO] ';
    public $logHeader = 'APOLOINFO::::::::: ';
    public $postData = 'POSTDATA::::::::: ';
    public $queryData = 'QUERYDATA::::::: ';
    public $finFuncion = ' FIN PROCEDIMIENTO::::::: ';

    public function __construct() {
        parent::__construct();
        $this->load->helper('log4php');
//        if ($this->session->userdata('entidad') == NULL) {
//            redirect('/');
//        }
    }

    public function __destruct() {
        $this->db->close();
    }

    public function retornoPago() {
        log_info($this->iniciLog.$this->logHeader . 'INGRESO RETORNO PAGO');
        $referenciaPago = $this->db->query("   SELECT LISTAGG(PK_REFERENCIA_PAGO,',') 
                                                WITHIN GROUP (ORDER BY PK_REFERENCIA_PAGO) REFERENCIAS
                                                FROM modpropag.ppatblrefpag  referencia_pago
                                                JOIN modpropag.ppatblordcom orden_compra 
                                                ON referencia_pago.pk_proceso=orden_compra.pk_ordcom_codigo
                                                WHERE referencia_pago.pk_estado = 3
                                                AND referencia_pago.PAGO_PASARELA=2
                                                AND referencia_pago.FECHA_PAGO IS NULL 
                                                AND referencia_pago.MEDIO_PAGO IS NULL
                                                AND orden_compra.pk_estado=4");
        $referenciaPago = $referenciaPago->result_array[0];
        $referenciaPago = explode(',', $referenciaPago['REFERENCIAS']);
        //var_dump($referenciaPago);

        if ($referenciaPago[0] != "") {
            //exit();
            $urlconsultapasarela = $this->db->query("select VALOR_PARAMETRO from modgeneri.gentblpargen where PK_PARGEN_CODIGO = 86"); // gentblpargen 86
            $urlconsultapasarela = $urlconsultapasarela->result_array[0];
            $codigoComercio = $this->db->query("select VALOR_PARAMETRO from modgeneri.gentblpargen where pk_pargen_codigo=78");
            $codigoComercio = $codigoComercio->result_array[0];

            //********Codigo consulta estado de transaccion multipay */
            $wsdl = $urlconsultapasarela['VALOR_PARAMETRO']; //"https://pagosvirtuales.multipay.com.co/WebServiceTransacciones/WebServicesTransacciones.asmx?wsdl";
            log_info($this->iniciLog.$this->logHeader . ' URL SOLICITUD '.$wsdl);
            $options = array(
                'cache_wsdl' => 0,
                'trace' => 1,
                'stream_context' => stream_context_create(array(
                    'ssl' => array(
                        'verify_peer' => false,
                        'verify_peer_name' => false,
                        'allow_self_signed' => true
                    )
                ))
            );
            $client = new SoapClient($wsdl, $options);
            $request_param = array();
            // web service input params
            for ($i = 0, $size = count($referenciaPago); $i < $size; ++$i) {
                 $codordenes='0';   
                $request_param = array(
                    "IdComercioElectronico" => $codigoComercio['VALOR_PARAMETRO'], // dato preferiblemente parametrizado
                    "Factura" => $referenciaPago[$i], //Codigo de referencia de pago
                );
                try {
                    $response_param = $client->ConsultarTransaccion($request_param);
                    $arrayres = json_decode(json_encode($response_param), True);
                    $respuesta = substr($arrayres["ConsultarTransaccionResult"], 0, 2);
                    //$respuesta = '00';
                    echo ' ' . $respuesta;
                    log_info($this->iniciLog.$this->logHeader . ' RESULTADO CONSUMO PSE '.$respuesta);
                    $this->guardarlog($referenciaPago[$i] . ' ' . $codigoComercio['VALOR_PARAMETRO'], $codordenes, $arrayres["ConsultarTransaccionResult"]);
                    if ('00' == $respuesta) {

                        $ordenes = $this->db->query("select pk_proceso ORDEN
                                     from modpropag.ppatblrefpag 
                                     where pk_referencia_pago=$referenciaPago[$i]");
                        $ordenes = $ordenes->result_array;
                        
                        log_info($this->iniciLog.$this->logHeader . ' RESULTADO ORDENES  '.$respuesta);
                        $this->guardarlog($referenciaPago[$i] . ' ' . $codigoComercio['VALOR_PARAMETRO'], $codordenes, $arrayres["ConsultarTransaccionResult"]);
                        $globales='';
                        foreach ($ordenes as $orden) {
                            $globales = $globales . $orden['ORDEN'] . ',';
                            log_info($this->iniciLog.$this->logHeader .$globales. ' ORDENES DE COMPRA SEGUN AL REFERENCIA DE PAGO   '.$referenciaPago[$i]);
                        }
                        log_info($this->iniciLog.$this->logHeader . ' CONSULTA INFORMACION REFERENCIA DE PAGO   '.$respuesta);
                        $codordenes = substr($globales, 0, strlen($globales) - 1);
                        log_info($this->iniciLog.$this->logHeader . ' PROCESAR APOLO  PAGAR PSE'.$resPagarPse);
                        $resPagarPse = $this->pagarPSE($codordenes, $referenciaPago[$i]);
                        //$this->guardarlog($referenciaPago[$i].' '.$codigoComercio['VALOR_PARAMETRO'], $codordenes, $arrayres["ConsultarTransaccionResult"]);
                        log_info($this->iniciLog.$this->logHeader . ' GUARDAR LOG A PROCESAR APOLO '.$resPagarPse);
                        $this->guardarlog($referenciaPago[$i] . ' ' . $codigoComercio['VALOR_PARAMETRO'], $codordenes, $arrayres["ConsultarTransaccionResult"]);
                        log_info($this->iniciLog.$this->logHeader . ' RESULTADO PAGAR PSE APOLO '.$resPagarPse);
                        echo $resPagarPse;
                    } else if ('NE' == $respuesta) {
                        echo "No se encontró la factura $referenciaPago[$i]";
                        $this->guardarlog($referenciaPago[$i] . ' ' . $codigoComercio['VALOR_PARAMETRO'], $codordenes, $arrayres["ConsultarTransaccionResult"]);
                        echo "<br>ok";
                    } else if ('98' == $respuesta) {
                        echo "Transaccion en proceso $referenciaPago[$i]";
                        $this->guardarlog($referenciaPago[$i] . ' ' . $codigoComercio['VALOR_PARAMETRO'], $codordenes, $arrayres["ConsultarTransaccionResult"]);
                    } else {
                        echo ("Transaccion rechazada $referenciaPago[$i]" );
                        $this->guardarlog($referenciaPago[$i] . ' ' . $codigoComercio['VALOR_PARAMETRO'], $codordenes, $arrayres["ConsultarTransaccionResult"]);
                    }
                } catch (Exception $e) {
                    echo "<h2>Exception Error!</h2>";
                    echo $e->getMessage();
                }
            }
            //****************************************************** */
        } else {
            echo 'Nada a procesar';
        }
    }

    public function pagarPSE($codordenes = null, $referenciapago = null) {
        if (!is_null($codordenes)) {
            $sql = "BEGIN modpropag.ppapkgactualizaciones.prcpacpagopse (
              :parreferenciapago,
              :parcodord,
              :parresmul ,
              :parespues
              );
              END;";

            $conn = $this->db->conn_id;
            $stmt = oci_parse($conn, $sql);
            $parcodord = $codordenes;
            $parreferenciapago = $referenciapago;
            $parresmul = 'Se pago exitosamente.';
            $parespues = '';
            //TIPO NUMBER INPUT
            oci_bind_by_name($stmt, ':parreferenciapago', $parreferenciapago, 32);
            //TIPO NUMBER INPUT
            oci_bind_by_name($stmt, ':parcodord', $parcodord, 1000);
            //TIPO NUMBER INPUT
            oci_bind_by_name($stmt, ':parresmul', $parresmul, 32);
            //TIPO NUMBER OUTPUT
            oci_bind_by_name($stmt, ':parespues', $parespues, 32);

            if (!@oci_execute($stmt)) {
                $e = oci_error($stmt);
                var_dump($e);
            }
            if ($parespues == 1) {
                array_push($pagas, $value);
            } else {
                array_push($fallopago, $value);
            }
        }
        if (is_null($fallopago)) {
            // $this->pago($fallopago);
            return 'Fallo pago ' . $parespues;
        } else {
            return 'Pago Ok';
        }
    }

    public function guardarlog($codigoReferencia = '0', $codigoOrdenes = '0', $respuestaServicio = '0') {
        // echo ' fallo';
        //if (!is_null($codordenes)) {
        $sql = "BEGIN 
                MODGENERI.GENPKGUTILIDADES.PRCLOGRESPUESTAPASARELA 
                (PARPAREMTROS =>:PARPAREMTROS
                ,PARRESPUESTA=>:PARRESPUESTA
                ,PARPROCEDIMIENTO=>:PARPROCEDIMIENTO);
              END;";

        $conn = $this->db->conn_id;
        $stmt = oci_parse($conn, $sql);
        $parcodord = $codordenes;
        $parreferenciapago = 'Codigos Referencia => ' . $codigoReferencia . ' Codigo de Ordenes => ' . $codigoOrdenes;
        $parresmul = 'Consumo wsdl multipay';
        //TIPO NUMBER INPUT
        oci_bind_by_name($stmt, ':PARPAREMTROS', $codigoReferencia, 1000);
        //TIPO NUMBER INPUT
        oci_bind_by_name($stmt, ':PARRESPUESTA', $respuestaServicio, 1000);
        //TIPO NUMBER INPUT
        oci_bind_by_name($stmt, ':PARPROCEDIMIENTO', $parresmul, 100);

        if (!@oci_execute($stmt)) {
            $e = oci_error($stmt);
            var_dump($e);

            echo ' fallo';
        } else {
            echo 'guardado';
            //  }
        }
    }

}
