<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of ProcesamientoTalos
 *
 * @author ronald.rosas
 */
class ProcesamientoFacturador extends CI_Controller {

    public $iniciLog = '[INFO] ';
    public $logHeader = 'APOLO_TALOS_INFO::::::::: ';
    public $postData = 'POSTDATA::::::::: ';
    public $queryData = 'QUERYDATA::::::: ';
    public $errorGeneral = 'ERROR_GENERAL::::::: ';
    public $finFuncion = ' FIN PROCEDIMIENTO::::::: ';

    public function __construct() {
        parent::__construct();
        $this->load->helper('log4php');
        $this->load->library('facturacionelectronicaminero');
    }

    public function crear_factura() {
        $class = get_class($this);
        require_once("application/controllers/wstalosfacturador/facturaMinero.php");
        log_info($this->iniciLog . $this->logHeader . ' CREAR FACTURA MINERO::::');
        $libEjecutarFacturaMinero = new facturaMinero();

        /* Pasos
         * 1- Captura pk_factura post
         * 2- Llamar funcion transmitir factura comfiar facturaMinero/transmitirComfiar
         * 3- Retornar respuesta a facturador MINERO
         */
        log_info($this->iniciLog . $this->logHeader . 'TIPO_DOC = ' . $tipoDocumento . ' DOCUMENTO = ' . $Documento);
        if (!empty($_POST['PK_FACTURA'])) {
            $pk_factura_codigo = $_POST['PK_FACTURA'];
            log_info($this->iniciLog . $this->logHeader . 'DATA RECIBIDA = ' . $pk_factura_codigo);

            //se llama controlador encargado de transmitir factura a comfiar
            $respuestaTxfacturaComfiar = $libEjecutarFacturaMinero->transmitirComfiar($pk_factura_codigo);
            $codigoRespuesta = $respuestaTxfacturaComfiar->CodRespuesta;
            $urlPdf = $respuestaTxfacturaComfiar->UrlPdf;
            $IdTxComfiar = $respuestaTxfacturaComfiar->IdTxComfiar;
            log_info($this->iniciLog . '-' . $this->logHeader . ' respuestaTxfacturaComfiar = ' . $codigoRespuesta . ' UrlPdf: ' . $urlPdf . ' IdTxComfiar: ' . $IdTxComfiar);
            $parrespuesta = $codigoRespuesta;
            if ($codigoRespuesta == 1) {
                $parmensajerespuesta = 'PK_FACTURA_CODIGO: ' . $pk_factura_codigo . ' transmitida correctamente';

                $host = $this->facturacionelectronicaminero->retornarValorConfiguracion($pk_factura_codigo, 'HOST_RESPUESTA');
                $path_ws_crear_factura_talos = '/almacenarFacturaManual';
                //se envia factura creada manualmente a talos 
                $url_ws_factura_facturador = $host . $path_ws_crear_factura_talos;
                log_info($this->iniciLog . $class . $this->logHeader . ' URL API ALMACENAR FACTURA_MANUAL TALOS ' . $url_ws_factura_facturador);

                $sql_factura = $this->db->query("SELECT 
                FAC.NUMERO_FACTURA,
                CLI.PK_TIPDOC_CODIGO,
                CLI.IDENTIFICACION,
                FAC.CONTRATO,
                FAC.SUBTOTAL MONTO_TOTAL,
                FAC.PAGO_MINIMO,-- igual pago total
                FAC.ENVIO_COMFIAR,
                FAC.FECHA_LIMITE_PAGO FECHA_VENCIMIENTO,
                FAC.FECHA_CREACION
                FROM modfacturador.facturtblfacturacomfiar fac
                JOIN modfacturador.facturtblclienteempresa cli
                ON fac.pk_cliente_codigo = cli.pk_cliente_codigo
                where fac.pk_factura_codigo = $pk_factura_codigo");
                $factura = $sql_factura->result_array[0];



                //se arma json enviar talos
                $grouped_array = new stdClass();
                $facturaObject = new stdClass();
                $facturaObject->pk_factura_codigo = $pk_factura_codigo;
                $facturaObject->numero_factura = $factura['NUMERO_FACTURA'];
                $facturaObject->tipo_documento = $factura['PK_TIPDOC_CODIGO'];
                $facturaObject->numero_documento = $factura['IDENTIFICACION'];
                $facturaObject->numero_contrato = $factura['CONTRATO'];
                $facturaObject->monto_total = $factura['MONTO_TOTAL'];
                $facturaObject->pago_minimo = $factura['PAGO_MINIMO'];
                $facturaObject->pago_total = $factura['PAGO_MINIMO'];
                $facturaObject->envio_comfiar = $factura['ENVIO_COMFIAR'];
                $facturaObject->fecha_vencimiento = $factura['FECHA_VENCIMIENTO'];
                $facturaObject->url_pdf = $urlPdf;
                $facturaObject->fecha_creacion = $factura['FECHA_CREACION'];
                $facturaObject->id_tx_comfiar = $IdTxComfiar;

                $grouped_array->Factura = $facturaObject;

                $json = json_encode($grouped_array);
//                        echo json_encode($grouped_array, JSON_PRETTY_PRINT);
                log_info($this->logHeader . 'JSON VIAJA A TALOS ' . $class . ' JSON = ' . $json . ' WS_TALOS= ' . $url_ws_factura_facturador);

                $ch = curl_init($url_ws_factura_facturador);
                curl_setopt_array($ch, array(
                    CURLOPT_CUSTOMREQUEST => "POST",
                    CURLOPT_POSTFIELDS => $json,
                    CURLOPT_HTTPHEADER => array(
                        'Content-Type: application/json',
                        'Content-Length: ' . strlen($json), // Abajo podríamos agregar más encabezados
                    ),
                    # indicar que regrese los datos, no que los imprima directamente
                    CURLOPT_RETURNTRANSFER => true,
                ));
                $resultadoWsFactura = curl_exec($ch);
                $codigoRespuesta = curl_getinfo($ch, CURLINFO_HTTP_CODE);
                log_info($this->logHeader . ' codigoRespuestaWsGuardarFacturaTalos :' . $codigoRespuesta);
                log_info($this->logHeader . ' Json Response Ws CrearFacturaTalos :' . $resultadoWsFactura);
                if ($codigoRespuesta === 200) {
                    $respuestaDecodificada = json_decode($resultadoWsFactura);
                    $cod_respuesws = $respuestaDecodificada->CodRespuesta;
                    $mensajeRespuesta = $respuestaDecodificada->Respuesta;
                    log_info($this->logHeader . ' RESPUESTA TALOS ws:'.$url_ws_factura_facturador .' Cod_respuesta: '. $cod_respuesws.' msg_respuesta: '.$mensajeRespuesta);
                }
            } else {
                $parmensajerespuesta = 'PK_FACTURA_CODIGO: ' . $pk_factura_codigo . ' ERROR: ' . $urlPdf;
            }
        } else {
            $parrespuesta = 26;
            $parmensajerespuesta = 'Datos tarjetahabiente vacios.';
        }
        log_info($this->finFuncion . $this->logHeader . ' RESPUESTA CREAR_FACTURA CODIGO_RESPUESTA: ' . $parrespuesta . ' MENSAJE_RESPUESTA: ' . $parmensajerespuesta);

        echo $parrespuesta;
    }
    
    public function crear_nota() {
        
        
        $class = get_class($this);
        require_once("application/controllers/wstalosfacturador/notaMinero.php");
        log_info($this->iniciLog . $this->logHeader . ' TRANSMITIR NOTA MINERO::::');
        $libEjecutarNotaMinero = new notaMinero();

        /* Pasos
         * 1- Captura pk_nota post
         * 2- Llamar funcion transmitir nota comfiar notaMinero/transmitirComfiar
         * 3- Retornar respuesta a facturador MINERO
         */
        log_info($this->iniciLog . $this->logHeader . 'PK_NOTA_POST = ' . $_POST['PK_NOTA']  );
        if (!empty($_POST['PK_NOTA'])) {
            $pk_nota_codigo = $_POST['PK_NOTA'];
            log_info($this->iniciLog . $this->logHeader . 'DATA RECIBIDA = ' . $pk_nota_codigo);

            //se llama controlador encargado de transmitir factura a comfiar
            $respuestaTxnotaComfiar = $libEjecutarNotaMinero->transmitirComfiar($pk_nota_codigo);
            $codigoRespuesta = $respuestaTxnotaComfiar->CodRespuesta;
            $urlPdf = $respuestaTxnotaComfiar->UrlPdf;
            $IdTxComfiar = $respuestaTxnotaComfiar->IdTxComfiar;
            log_info($this->iniciLog . '-' . $this->logHeader . ' respuestaTxfacturaComfiar = ' . $codigoRespuesta . ' UrlPdf: ' . $urlPdf . ' IdTxComfiar: ' . $IdTxComfiar);
            $parrespuesta = $codigoRespuesta;
           if($codigoRespuesta ==1){
               $parmensajerespuesta = 'Nota MINERO: ' . $pk_nota_codigo . ' transmitida correctamente';
           }
        } else {
            $parrespuesta = 26;
            $parmensajerespuesta = 'Datos tarjetahabiente vacios.';
        }
        log_info($this->finFuncion . $this->logHeader . ' RESPUESTA CREAR_NOTA_CREDIO MINERO CODIGO_RESPUESTA: ' . $parrespuesta . ' MENSAJE_RESPUESTA: ' . $parmensajerespuesta);

        echo $parrespuesta;
    }
    
}
