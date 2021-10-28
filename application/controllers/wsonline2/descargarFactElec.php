<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of descargarFactElec
 *
 * @author ronald.rosas
 */
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class descargarFactElec extends CI_Controller {

    public $iniciLog = '[INFO]::DESCARGA_FACTURA_COMFIAR:: INICIO_LOG::';
    public $logHeader = 'APOLOINFO:::DESCARGA_FACTURA_COMFIAR::: ';
    public $postData = 'POSTDATA:::DESCARGA_FACTURA_COMFIAR::: ';
    public $queryData = 'QUERYDATA:::DESCARGA_FACTURA_COMFIAR::: ';
    public $finFuncion = 'FIN PROCEDIMIENTO:::DESCARGA_FACTURA_COMFIAR::: ';
    public $finLog = '[INFO]::DESCARGA_FACTURA_COMFIAR:: FIN_LOG::';

    public function __construct() {
        parent::__construct();
        $this->load->helper('log4php');
        $this->load->library('facturacionelectronica');
    }

    public function __destruct() {
        $this->db->close();
    }

    //transmitir nota credito comfiar
    public function solicitud_pdf_comfiar() {
        require_once("application/controllers/wsonline2/facturaElectronica.php");

        $libfacturaElectronica = new FacturaElectronica();
        log_info($this->iniciLog);
        log_info($this->logHeader . ' Entro a solicitud_pdf_comfiar');

        $sqlfacturasinpdf = $this->db->query("SELECT pk_xml_codigo,xmlcom.url_pdf,xmlcom.pk_factura_codigo,xmlcom.id_transaccion_comfiar,fac.numero_factura
        FROM modfactur.factblxmlcomfiar xmlcom
        JOIN modfactur.factblfactur fac
        ON xmlcom.pk_factura_codigo = fac.pk_factur_codigo
        WHERE xmlcom.pk_tipo_xml_codigo =1
        and url_pdf is null  order by pk_xml_codigo asc");
        $facturas = $sqlfacturasinpdf->result_array;
        $total_sinpdf=count($facturas);
        log_info($this->logHeader . ' TOTAL FACTURAS SIN PDF = '.$total_sinpdf);
        
        if($total_sinpdf>0){
        //se consultan parametros consumo ws solo una vez para no 
        $cuitId = $this->facturacionelectronica->retornarValorConfiguracion(3); //Cuit, RUC o NIT del emisor del comprobante. 
        $puntoVentaId = $this->facturacionelectronica->retornarValorConfiguracion(5); //Número de punto de venta a procesar 01 factura
        $tipoComprobanteId = $this->facturacionelectronica->retornarValorConfiguracion(6); //Número del tipo de comprobante a procesar. Ejemplo 01:Factura
        $prefijoPeople = $this->facturacionelectronica->retornarValorConfiguracion(24); // Pefrijo factura ejemplo SETT
        }
        foreach ($facturas as $factura) {
            log_info($this->queryData . ' DATOS FACTURA: URL_PDF= ' . $factura['URL_PDF'] . ' PK_FACTURA_CODIGO= ' .
                    $factura['PK_FACTURA_CODIGO'] . ' ID_TRANSACCION_COMFIAR= ' . $factura['ID_TRANSACCION_COMFIAR'] . ' NÚMERO_FACTURA= ' . $factura['NUMERO_FACTURA']);

            $urlWsdl = $this->facturacionelectronica->retornarValorConfiguracion(30);
            $wsdl = $urlWsdl;
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
            $transaccionId = $factura['ID_TRANSACCION_COMFIAR'];
            $nroComprobante = $factura['NUMERO_FACTURA'];
            $respuesta_token = $libfacturaElectronica->verificar_token_comfiar();

            log_info($this->logHeader . '::::RESPUESTA_VERIFICAR_TOKEN::::' . $respuesta_token);

            $sessionId = $this->facturacionelectronica->retornarValorConfiguracion(32);
            $fechaVenc = $this->facturacionelectronica->retornarValorConfiguracion(33);
            $pk_xml_codigo = $factura['PK_XML_CODIGO'];

            // web service input params
            $request_param = array(
                "transaccionId" => $transaccionId, //274 id transaccion confiar
                "cuitId" => $cuitId,
                "puntoDeVentaId" => $puntoVentaId,
                "tipoComprobanteId" => $tipoComprobanteId, // tipo comprobante factura 01
                "numeroComprobante" => $nroComprobante, //418 Número de factura enviado SETT418
                "token" => array(
                    "SesionId" => $sessionId,
                    "FechaVencimiento" => $fechaVenc
                )
            );
            log_info($this->logHeader . $this->postData . 'Id_Tx_Comfiar: ' . $transaccionId .
                    ' cuitId: ' . $cuitId .
                    ' puntoDeVentaId: ' . $puntoVentaId .
                    ' tipoComprobanteId: ' . $tipoComprobanteId .
                    ' numeroComprobante: ' . $nroComprobante .
                    ' SesionId: ' . $sessionId .
                    ' FechaVencimiento: ' . $fechaVenc .
                    ' PK_xml_codigo: ' . $pk_xml_codigo);

            try {
                $responce_param = $client->DescargarPdf($request_param);
                log_info($this->postData . 'RESPUESTA COMSUMO DESCARGARPDF::' . json_encode($responce_param));
                if (isset($responce_param->DescargarPdfResult)) {


                    $b64 = $responce_param->DescargarPdfResult;
                    $data = base64_encode($b64);
                    $urlpublica = $this->db->query("select VALOR_PARAMETRO from modgeneri.gentblpargen where pk_pargen_codigo =96");
                    $urlpublica = $urlpublica->result_array[0];
                    //guarda y genera url factura pdf
                    $folderPath = "uploads/facturacomfiar/";
                    $date = date('Y-m-d');
                    $random = rand(1000, 9999);
                    $fact = strtolower($prefijoPeople) . '-' . $nroComprobante . '-';
                    $name = $fact . strtolower($date . '-' . $random . '.pdf');
                    $file_dir = $folderPath . $name;
                    $url = $urlpublica['VALOR_PARAMETRO'] . '/' . $folderPath . $name;
                    $pdf_decoded = base64_decode($data); //Write data back to pdf file
                    try {
                        $pdf = fopen($file_dir, 'w');
                        fwrite($pdf, $pdf_decoded);
                        //close output file
                        fclose($pdf);
                        $dataReturn = $url;
//                    echo $url . '+++' . $fact;
                    } catch (Exception $e) {
                        $response = 'Excepción capturada: ' . $e->getMessage();
                    }

                    log_info($this->logHeader . '::Consumo Correcto soap DescargarPdf::URL PDF COMFIAR::' . $dataReturn);

                    $response = $dataReturn;
                    //llamar procedimiento actualiza url factura transmitida comfiar
                    $sql = "BEGIN modfactur.facpkgdatacomfiar.prcactualizapdffacturacomfiar(
                            parpkxmlcodigo=>:parpkxmlcodigo,
                            parurlpdf=>:parurlpdf,
                            parrespuesta=>:parrespuesta);
                            END;";

                    $conn = $this->db->conn_id;
                    $stmt = oci_parse($conn, $sql);
                    oci_bind_by_name($stmt, ':parpkxmlcodigo', $pk_xml_codigo, 32);
                    oci_bind_by_name($stmt, ':parurlpdf', $dataReturn, 1000);
                    oci_bind_by_name($stmt, ':parrespuesta', $parrespuesta, 32);
                    if (!oci_execute($stmt)) {
                        $e = oci_error($stmt);
//                                VAR_DUMP($e);
                        log_info($this->finLog . ' ERROR ACTUALIZANDO FACTURA -prcactualizapdffacturacomfiar- '
                                . $e['message'] . '[*] parpkxmlcodigo=' . $pk_xml_codigo . '[*] parurlpdf=' . $dataReturn);
                    }
                } else {
                    $response = 'Error consumo Soap';
                }
            } catch (Exception $e) {
                log_info($this->logHeader . 'ERROR SOAP::' . $e->getMessage());

                $response = 'Error consumo DescargarPdf :' . $e->getMessage();
            }
            log_info($this->finLog . ' FACTURA NÚMERO= ' . $nroComprobante . ' PK_XML_CODIGO =' . $pk_xml_codigo . ' URL PDF ACTUALIZADA ::RESPONSE::' . $response);
        }
    }

}
