<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of notaMinero
 *
 * @author ronald.rosas
 */
class notaMinero extends CI_Controller {

    public $logHeader = '[INFO] APOLO_TALOS_INFO::::::::: ';
    public $postData = 'POSTDATA::::::::: ';
    public $queryData = 'QUERYDATA::::::: ';
    public $errorComfiar = 'ERROR_COMFIAR::::::: ';
    public $errorGeneral = 'ERROR_GENERAL::::::: ';
    public $finFuncion = ' FIN PROCEDIMIENTO::::::: ';
    //tiempo espera antes de ejecutar servicio salida transaccion comfiar
    public $tiempo2 = 10;

    public function __construct() {

        parent::__construct();
        $this->load->helper('log4php');
        $this->load->library('facturacionelectronicaminero');
    }

    public function transmitirComfiar($pk_nota_talos = null) {


        log_info($this->logHeader . '::::NOTA_CREDITO_COMFIAR::::INGRESO transmitirComfiar PK_NOTA ' . $pk_nota_talos);

        $sqlData = $this->db->query("SELECT NOTA.PK_FACTURA_CODIGO,NOTA.CONSECUTIVO_NOTA_CREDITO ,NOTA.PK_EMPRESA_EMISORA,NOTA.PK_CLIENTE_CODIGO ,CONF.PK_CONF_CODIGO FROM MODFACTURADOR.FACTURTBLNOTACREDITO NOTA
                JOIN MODFACTURADOR.FACTURTBLEMPEMICONFIG CONF
                ON CONF.PK_EMPRESA_EMISORA = NOTA.PK_EMPRESA_EMISORA
		WHERE PK_NOTA_CODIGO=$pk_nota_talos");
        $datanota = $sqlData->result_array[0];
        $pk_cliente_codigo = $datanota['PK_CLIENTE_CODIGO'];
        $pk_empresa_emisora = $datanota['PK_EMPRESA_EMISORA'];
        $consecutivo_nota = $datanota['CONSECUTIVO_NOTA_CREDITO'];
        $pk_factura_talos = $datanota['PK_FACTURA_CODIGO'];

        $parrespuesta = 0;
        $mensajeRespuesta = 'Empty';

        $EstadoCanalTX = $this->facturacionelectronicaminero->retornarValorConfiguracion($pk_factura_talos, 'ESTADO_CANAL_TX');

        if (intval($EstadoCanalTX) == 1 && !empty($pk_nota_talos)) {
            $sqlNota = $this->db->query("SELECT nvl(nota.envio_comfiar,0) ENVIO_COMFIAR,notaxml.url_pdf from modfacturador.facturtblnotacredito nota
                        LEFT JOIN modfacturador.facturtblxmlcomfiar notaxml
                        ON nota.pk_nota_codigo = notaxml.pk_nota_codigo where nota.pk_nota_codigo = $pk_nota_talos and notaxml.pk_tipo_xml_codigo=2");
            $Envio_comfiar = $sqlNota->result_array[0]['ENVIO_COMFIAR'];
            $url_pdf = $sqlNota->result_array[0]['URL_PDF'];

            if ($Envio_comfiar == 0) {
                //Se envia pk asiganada a la nota para tomar parametros configuracion empresa emisora factura
                $respuesta_token = $this->verificar_token_comfiar($pk_factura_talos);

                log_info($this->logHeader . '::::RESPUESTA_VERIFICAR_TOKEN::::' . $respuesta_token);

                $sessionId = $this->facturacionelectronicaminero->retornarValorConfiguracion($pk_factura_talos, 'SESIONID_COMFIAR');
                $fechaVenc = $this->facturacionelectronicaminero->retornarValorConfiguracion($pk_factura_talos, 'VENCIMIENTO_TOKEN_COMFIAR');
                if (!empty($sessionId) && !empty($fechaVenc) && $respuesta_token === 1) {
                    $respuestaTxNc = $this->ejecutarNcMineroComfiar($pk_factura_talos, $consecutivo_nota, $pk_nota_talos, $pk_cliente_codigo, $pk_empresa_emisora, $sessionId, $fechaVenc);
                    $respuesta = $respuestaTxNc->CodRespuesta;
                    $mensajeRespuesta = $respuestaTxNc->Respuesta;
                    $IdTxComfiar = $respuestaTxNc->IdTxComfiar;
                    log_info($this->logHeader . '::::NOTA_CREDITO_COMFIAR:::: RESPUESTA ejecutarNcMineroComfiar ' . $respuesta . ' UrlPdf: ' . $mensajeRespuesta . ' IdTxComfiar: ' . $IdTxComfiar);

                    if ($respuesta == 1) {
                        //llamar procedimiento actualiza nota credito a trasnmitida a comfiar
                        $sql = "BEGIN MODFACTURADOR.PKGMODFACTURADORGENERAL.prcmodestadoenvionotacredito(
                    parpknotacodigo=>:parpknotacodigo,
                    parestadocomfiar=>:parestadocomfiar,
                    parrespuesta=>:parrespuesta);
                    END;";

                        $conn = $this->db->conn_id;
                        $stmt = oci_parse($conn, $sql);
                        $parestado = 1; // estado 1 trasnmitida
                        oci_bind_by_name($stmt, ':parpknotacodigo', $pk_nota_talos, 32);
                        oci_bind_by_name($stmt, ':parestadocomfiar', $parestado, 32);
                        oci_bind_by_name($stmt, ':parrespuesta', $parrespuesta, 32);
                        if (!oci_execute($stmt)) {
                            $e = oci_error($stmt);
                            VAR_DUMP($e);
                            log_info($this->logHeader . '-' . $this->errorGeneral . ' ERROR ACTUALIZANDO NOTA_CREDITO -prcmodestadoenvionotacredito- '
                                    . $e['message'] . '[*] parpknotacodigo=' . $pk_nota_talos . '[*] parestadocomfiar=' . $parestado);
                        }
                        if ($parrespuesta == 1) {
                            log_info($this->logHeader . '-' . $this->finFuncion . '::::NOTA_CREDITO::::prcmodestadoenvionotacredito PK_NOTA_CODIGO= ' . $pk_nota_talos . ' - TRANSMITIDA= ' . $parrespuesta);
                        }
                    } else {
                        // el estado que retorno  ejecutarFacturacionComfiar diferente de 1
                        $parrespuesta = $respuesta;
                        $mensajeRespuesta = $mensajeRespuesta;
                        log_info($this->finFuncion . 'Error transmitiendo NOTA_TALOS_MINERO= ' . $pk_nota_talos . ' CodigoRespouesta: ' . $parrespuesta . ' MensajeRespuesta: ' . $mensajeRespuesta);
                    }
                } else {
                    $parrespuesta = 501;
                    $mensajeRespuesta = 'Datos sesion comfiar erroneos:::' . $sessionId . ' - ' . $fechaVenc . ' - ' . $respuesta_token;
                    log_info($this->finFuncion . 'DATOS SESSION COMFIAR ERRADOS = ' . $sessionId . ' - ' . $fechaVenc . ' - respuesta_token = ' . $respuesta_token);
                }
            } else {
                $parrespuesta = 1;
                $mensajeRespuesta = $url_pdf;
                log_info($this->finFuncion . 'NOTA_TALOS = ' . $pk_nota_talos . ' Estado envio_comfiar = ' . $Envio_comfiar);
                //falta respuesta factura ya tiene estado 1 envio comfiar
            }
        } else {
            $parrespuesta = 500;
            $mensajeRespuesta = ' Estado Canal de tx Comfiar: ' . $EstadoCanalTX . ' NOTA_TALOS: ' . $pk_nota_talos;
            log_info($this->errorGeneral . ' ERROR ESTADO CANAL DE TRANSMISION ESTADO= ' . $EstadoCanalTX . ' PK_FACTURA_TALOS= ' . $pk_factura_talos);
            //falta respuesta de canal de transmision en estado 2
        }

        $objectRespuesta = (object) [
                    'CodRespuesta' => $parrespuesta,
                    'UrlPdf' => $mensajeRespuesta,
                    'IdTxComfiar' => $IdTxComfiar];
        return $objectRespuesta;
    }

    public function ejecutarNcMineroComfiar($pk_factura_codigo, $consecutivo_nota, $pk_nota_codigo, $pk_cliente_codigo, $pk_empresa_emisora, $sessionId, $venciSesion) {

        log_info($this->logHeader . '::::NOTA_CREDITO_COMFIAR::::INGRESO FUNCION ejecutarNCComfiar ::PK_NOTA_CODIGO = ' . $pk_nota_codigo . ' PK_CLIENTE_CODIGO = ' . $pk_cliente_codigo . ' PK_EMPRESA_EMISORA = ' . $pk_empresa_emisora);


        if (!empty($pk_factura_codigo) && !empty($pk_nota_codigo) && !empty($pk_cliente_codigo) && !empty($pk_empresa_emisora) && !empty($consecutivo_nota)) {
            if (isset($sessionId) && isset($venciSesion)) {

                $tx_Id = 000;
                $resultAutorizar = $this->facturacionelectronicaminero->autorizar_Comprobante_Nc_Minero($sessionId, $venciSesion, $pk_factura_codigo, $pk_nota_codigo);
                if ($resultAutorizar->AutorizarComprobantesAsincronicoResult) {
                    $respuesta = new SimpleXMLElement($resultAutorizar->AutorizarComprobantesAsincronicoResult);

                    if (isset($respuesta->Transaccion->ID)) {
                        $transaccionId = $respuesta->Transaccion->ID;
                        //Si la DIAN tiene retrasos en respuesta ajustar este tiempo en base de datos
                        //tiempo espera antes de ejecutar servicio salida transaccion comfiar

                        $tiempo1 = $this->facturacionelectronicaminero->retornarValorConfiguracion($pk_factura_codigo, 'TIEMPO_ESPERA_1');
                        log_info($this->logHeader . ':::salida_Transaccion:::TIEMPO_ESPERA_1 =  ' . $tiempo1);
                        sleep($tiempo1);
                        $result_salida_transaccion = $this->facturacionelectronicaminero->salida_Transaccion($sessionId, $venciSesion, $transaccionId, $pk_factura_codigo);

                        if ($result_salida_transaccion->SalidaTransaccionResult) {
                            $respuesta_Salida_transaccion = new SimpleXMLElement($result_salida_transaccion->SalidaTransaccionResult);
                            $estado_Salida_Transaccion = '0';
                            $estado_Salida_Transaccion = $respuesta_Salida_transaccion->Estado;
                            $codRespuesta = 0;
                            $intentos = 0;
                            $tipCom = 2; //1-factura 2-nota credito
                            $tx_Id = isset($respuesta_Salida_transaccion->TransaccionId) ? $respuesta_Salida_transaccion->TransaccionId : $respuesta_Salida_transaccion->Transaccion->ID;
                            //validacion tipo respuesta
                            if ($estado_Salida_Transaccion == 'CargandoComprobantes') {
                                log_info($this->logHeader . '::::NOTA_CREDITO_COMFIAR:::: idEstado:' . 3 . ' Estado= ' . $estado_Salida_Transaccion);
                            } else if ($estado_Salida_Transaccion == 'AProcesar') {
                                log_info($this->logHeader . '::::NOTA_CREDITO_COMFIAR:::: idEstado:' . 4 . ' Estado= ' . $estado_Salida_Transaccion);
                            } else if ($estado_Salida_Transaccion == 'ProcesandoOrganismoFiscal') {
                                log_info($this->logHeader . '::::NOTA_CREDITO_COMFIAR:::: idEstado:' . 5 . ' Estado= ' . $estado_Salida_Transaccion);
                            } else if ($estado_Salida_Transaccion == 'GuardandoResultado') {
                                log_info($this->logHeader . '::::NOTA_CREDITO_COMFIAR:::: idEstado:' . 6 . ' Estado= ' . $estado_Salida_Transaccion);
                            } else if ($estado_Salida_Transaccion == 'ProcesandoAFIP') {
                                log_info($this->logHeader . '::::NOTA_CREDITO_COMFIAR:::: idEstado:' . 7 . ' Estado= ' . $estado_Salida_Transaccion);
                            } else if ($estado_Salida_Transaccion == 'ProcesandoAFIP') {
                                log_info($this->logHeader . '::::NOTA_CREDITO_COMFIAR:::: idEstado:' . 8 . ' Estado= ' . $estado_Salida_Transaccion);
                            }
                            log_info($this->logHeader . '::::NOTA_CREDITO_COMFIAR::::RESPUESTA SALIDA TRANSACCION::: Id transacción= ' . $tx_Id . ' Estado= ' . $estado_Salida_Transaccion);
                            $estado_Salida_Transaccion = isset($respuesta_Salida_transaccion->Transaccion->Estado) ? $respuesta_Salida_transaccion->Transaccion->Estado : $estado_Salida_Transaccion;
                            log_info($this->logHeader . '::::NOTA_CREDITO_COMFIAR:::: Estado Antes de llamar respuesta_Comprobante = ' . $tx_Id . ' Estado= ' . $estado_Salida_Transaccion);
                            while ($intentos <= 2 && $estado_Salida_Transaccion != 'Transacción Exitosa') {
                                $tiempo2 = $this->facturacionelectronicaminero->retornarValorConfiguracion($pk_factura_codigo, 'TIEMPO_ESPERA_2');
                                log_info($this->logHeader . ':::respuesta_Comprobante:::TIEMPO_ESPERA_2 =  ' . $tiempo2);
                                sleep($tiempo2);
                                $resultRespuestaCom = $this->facturacionelectronicaminero->respuesta_Comprobante($sessionId, $venciSesion, $consecutivo_nota, $tipCom, $pk_factura_codigo);
                                $respuestaComprobanteresult = new SimpleXMLElement($resultRespuestaCom->RespuestaComprobanteResult);
                                log_info($this->logHeader . '::::NOTA_CREDITO_COMFIAR:::: ID Transaccion: ' . $transaccionId . ' Comprobante ID: ' . $consecutivo_nota . ' Intento No:' . $intentos . ' - ' . 'Estado Comprobante: ' . $respuestaComprobanteresult->Transaccion->Estado);



                                if ($respuestaComprobanteresult->Transaccion->Estado == 'Transacción Exitosa') {

                                    $estado_Salida_Transaccion = $respuestaComprobanteresult->Transaccion->Estado;
                                    log_info($this->logHeader . '::::NOTA_CREDITO_COMFIAR::::Intento:' . $intentos . ' - ' . 'Estado transacción DIAN: ' . $estado_Salida_Transaccion);

                                    $comprobante = $respuestaComprobanteresult->Comprobante->informacionOrganismo->ComprobanteProcesado;
                                    $xmlRespuestaComprobante = new SimpleXMLElement(str_replace('&', '&amp;', $comprobante[0]));
                                    $nameSpace = $xmlRespuestaComprobante->getNamespaces(true);
                                    $arrayChNameSpace = $xmlRespuestaComprobante->children($nameSpace["cbc"]);
                                    $idComprobante = $arrayChNameSpace->ID;
                                    $issueDate = $arrayChNameSpace->IssueDate;
                                    log_info($this->logHeader . '::::NOTA_CREDITO_COMFIAR::::ID_COMPROBANTE:::' . $idComprobante . ' - ' . 'ISSUEDATE: ' . $issueDate);

                                    $respuestaDian = $respuestaComprobanteresult->Comprobante->RespuestaDIAN;
                                    $ObjRespDian = get_object_vars($respuestaDian[0]);
                                    $stringXmlRespDian = $ObjRespDian[0];
                                    log_info($this->logHeader . '::::NOTA_CREDITO_COMFIAR::::Respuesta xml dian ' . $stringXmlRespDian);
                                    //
                                    $xml = preg_replace('/(<\?xml[^?]+?)utf-16/i', '$1utf-8', $stringXmlRespDian);
                                    $xmlRespuestaDian = simplexml_load_string($xml);
                                    $cude = $xmlRespuestaDian->Version;
                                    $comments = $xmlRespuestaDian->Comments;
                                    $tx_Id = $xmlRespuestaDian->TransaccionId;
                                    log_info($this->logHeader . '::::NOTA_CREDITO_COMFIAR::::Respuesta dian CUDE = ' . (string) $cude[0] . ' - Comments = ' . (string) $comments[0] . ' - IdTransaccionComfiar = ' . (string) $tx_Id[0]);

                                    break;
                                } elseif ($respuestaComprobanteresult->Transaccion->Estado == 'Comprobantes Erróneos') {

                                    $estadoInfComfiar = $respuestaComprobanteresult->Comprobante->informacionComfiar->Estado;
                                    $mensajesErrorDIAN = $respuestaComprobanteresult->Comprobante->informacionComfiar->mensajes->mensaje;
                                    $estado_Salida_Transaccion = $respuestaComprobanteresult->Transaccion->Estado;

                                    //se recorren errores encontrados para formar string y guardarlo en BD
                                    $msjErrorDian = '';
                                    foreach ($mensajesErrorDIAN as $msj) {
//                                            echo $msj->identificador, ' interpretado por ', $msj->mensaje, PHP_EOL;
                                        $msjErrorDian = $msjErrorDian . ' ID ERROR: ' . $msj->identificador . ': ' . $msj->mensaje;
                                    }

                                    $mensajeError = $estadoInfComfiar . ' -Número Nota: ' . $consecutivo_nota . ' -ID Transaccion: ' . (string) $tx_Id[0] . ' ::::';

                                    log_info($this->logHeader . $this->errorComfiar . 'COMPROBANTES ERRONEOS:: ' . $mensajeError);

                                    //aca se actualizaria factura a estado error por estructura errada xml
                                    $sql = "BEGIN MODFACTURADOR.PKGMODFACTURADORTALOS.prcactulizaerrortxnota(
                                        parpknota=>:parpknota, 
                                        parmsgerror=>:parmsgerror,
                                        parrespuesta=>:parrespuesta
                                        );
                                        END;";
                                    $conn = $this->db->conn_id;
                                    $stmt = oci_parse($conn, $sql);
                                    $msjErrorDian = $mensajeError . $msjErrorDian;
                                    oci_bind_by_name($stmt, ':parpknota', $pk_nota_codigo, 32);
                                    oci_bind_by_name($stmt, ':parmsgerror', $msjErrorDian, 1024);
                                    oci_bind_by_name($stmt, ':parrespuesta', $parrespuestaupdate, 32);
                                    if (!oci_execute($stmt)) {
                                        $e = oci_error($stmt);
                                        VAR_DUMP($e);
                                        log_info($this->logHeader . ' ERROR prcactulizaerrortxfactura::: ' . $e['message']);
                                    } if ($parrespuestaupdate == 1) {
                                        log_info($this->logHeader . '-' . $this->finFuncion . ' Se actualizo nota Credito en prcactulizaerrortxnota con el msg_error_tx_factura= ' . $msjErrorDian);
                                    }
                                    $respuesta = 99;
                                    break;
                                }

                                $intentos++;
                            }

                            //si da exitoso de un solo intento
                            if ($respuesta_Salida_transaccion->Transaccion->Estado== 'Transacción Exitosa' && $intentos == 0) {
                                log_info($this->logHeader . '::::EXITOSO CERO INTENTOS::::' . $intentos . ' Tx_ID = ' . $respuesta_Salida_transaccion->Transaccion->ID . ' Estado Comprobante = ' . $estado_Salida_Transaccion);
                                $comprobante = $respuesta_Salida_transaccion->Comprobante->informacionOrganismo->ComprobanteProcesado;
                                $xmlRespuestaComprobante = new SimpleXMLElement(str_replace('&', '&amp;', $comprobante[0]));
                                $nameSpace = $xmlRespuestaComprobante->getNamespaces(true);
                                $arrayChNameSpace = $xmlRespuestaComprobante->children($nameSpace["cbc"]);
                                $idComprobante = $arrayChNameSpace->ID;
                                $issueDate = $arrayChNameSpace->IssueDate;
                                log_info($this->logHeader . '::::NOTA_CREDITO_COMFIAR::::ID_COMPROBANTE:::' . $idComprobante . ' - ' . 'ISSUEDATE: ' . $issueDate);

                                $respuestaDian = $respuesta_Salida_transaccion->Comprobante->RespuestaDIAN;
                                $ObjRespDian = get_object_vars($respuestaDian[0]);
                                $stringXmlRespDian = $ObjRespDian[0];
                                log_info($this->logHeader . '::::NOTA_CREDITO_COMFIAR::::Respuesta xml dian Cero Intentos' . $stringXmlRespDian);
                                //
                                $xml = preg_replace('/(<\?xml[^?]+?)utf-16/i', '$1utf-8', $stringXmlRespDian);
                                $xmlRespuestaDian = simplexml_load_string($xml);
                                $cude = $xmlRespuestaDian->Version;
                                $comments = $xmlRespuestaDian->Comments;
                                $tx_Id = $xmlRespuestaDian->TransaccionId;
                                log_info($this->logHeader . '::::NOTA_CREDITO_COMFIAR::::Respuesta dian CUDE = ' . (string) $cude[0] . ' - Comments = ' . (string) $comments[0] . ' - IdTransaccionComfiar = ' . (string) $tx_Id[0]);
                            }
                            //$respuesta_Salida_transaccion
//                            $comprobante = $respuestaComprobanteresult->Comprobante->informacionOrganismo->ComprobanteProcesado;
                            log_info($this->logHeader . '::::NOTA_CREDITO_COMFIAR::::ESTADO FINAL ANTES DE ENTRAR A GUARDAR XML BD::: Id transacción= ' . $tx_Id . ' Estado= ' . $estado_Salida_Transaccion . ' NO INTENTO:::' . $intentos);
                        }
                        if ($estado_Salida_Transaccion == 'Transacción Exitosa') {

                            log_info($this->logHeader . '::::NOTA_CREDITO_COMFIAR::::INGRESO EJECUTAR prcguardarxmlcomfiar: ' . $estado_Salida_Transaccion);
                            $codRespuesta = 1;

                            //ejecutar funcion para generar pdf nota credito y retornar url pdf para guardar en Bd
                            $resultRespuestaCom = $this->descargaPdfComfiar((string) $tx_Id[0], $consecutivo_nota, $sessionId, $venciSesion, $tipCom, $pk_factura_codigo);
                            $codRespuestaPdf = $resultRespuestaCom->CodRespuesta;
                            $RespuestaPdf = $resultRespuestaCom->Respuesta;
                            log_info($this->logHeader . '::::NOTA_CREDITO_COMFIAR::::RESPUESTA descargaPdfComfiar: codRespuestaPdf::' . $codRespuestaPdf . '::RespuestaPdf::' . $RespuestaPdf);

                            if ($codRespuestaPdf == 1) {
                                $urlPdfNota = $resultRespuestaCom->Respuesta;
                            } else {
                                $urlPdfNota = '';
                                log_info($this->errorGeneral . '::::NOTA_CREDITO_COMFIAR::::Error EJECUTAR descargaPdfComfiar  : ' . $codRespuestaPdf . '-' . $RespuestaPdf);
                            }
                            log_info($this->logHeader . '::::NOTA_CREDITO_COMFIAR::::COMPROBANTE TAMAÑO:' . strlen((string) $comprobante[0]) . ' - ' . 'Estado transacción DIAN: ' . $estado_Salida_Transaccion);

                            //se guarda informacion base de datos
                            if (!empty($comprobante)) {


                                $cuentaProc = 0;
                                $facturasTX = 0;
                                $sql = "BEGIN  MODFACTURADOR.FCPKGFACTURACION.prcguardarxmlnotacrecomfiar(
                                partipoxml=>:partipoxml, 
                                parpkfactura=>:parpkfactura, 
                                parpknota=>:parpknota,
                                parpkcliente=>:parpkcliente,
                                paridtxcomfiar=>:paridtxcomfiar,
                                parestadotx=>:parestadotx,
                                parcude=>:parcude,
                                parestadodian=>:parestadodian,
                                parurlpdf=>:parurlpdf,
                                parissuedate=>:parissuedate,
                                paridcomprobante=>:paridcomprobante,
                                parpkxmlcodigo=>:parpkxmlcodigo,
                                parrespuesta=>:parrespuesta,
                                parmensajerespuesta=>:parmensajerespuesta
                                );
                                END;";


                                $conn = $this->db->conn_id;
                                $stmt = oci_parse($conn, $sql);
                                $parestadotx = (string) $estado_Salida_Transaccion[0];
                                $paridComfiar = (string) $tx_Id[0];
                                $tipoXml = 2; //nota credito

                                $xmlResp = '';
                                $issueDate = (string) $issueDate;
                                $idComprobante = (string) $idComprobante;
//                                $xmlResp = (string) $comprobante[0];
                                $pk_cliente = $pk_cliente_codigo;
                                $idNota = $pk_nota_codigo;
                                $cude = (string) $cude[0];
                                $estadoDian = (string) $comments[0];
                                log_info($this->logHeader . 'INGRESO prcguardarxmlnotacrecomfiar');
                                log_info($this->postData . ''
                                        . '-partipoxml= ' . $tipoXml
                                        . '-parpkfactura= ' . $pk_factura_codigo
                                        . '-parpknota= ' . $idNota
                                        . '-parpkcliente= ' . $pk_cliente
                                        . '-paridtxcomfiar= ' . $paridComfiar
                                        . '-parestadotx= ' . $parestadotx
                                        . '-parcude= ' . $cude
                                        . '-parestadodian= ' . $estadoDian
                                        . '-parurlpdf= ' . $urlPdfNota
                                        . '-parissuedate= ' . (string) $issueDate
                                        . '-paridcomprobante= ' . (string) $idComprobante
                                );

                                oci_bind_by_name($stmt, ':partipoxml', $tipoXml, 32);
                                oci_bind_by_name($stmt, ':parpkfactura', $pk_factura_codigo, 32);
                                oci_bind_by_name($stmt, ':parpknota', $idNota, 32);
                                oci_bind_by_name($stmt, ':parpkcliente', $pk_cliente, 32);
                                oci_bind_by_name($stmt, ':paridtxcomfiar', $paridComfiar, 32);
                                oci_bind_by_name($stmt, ':parestadotx', $parestadotx, 200);
                                oci_bind_by_name($stmt, ':parcude', $cude, 500);
                                oci_bind_by_name($stmt, ':parestadodian', $estadoDian, 200);
                                oci_bind_by_name($stmt, ':parurlpdf', $urlPdfNota, 500);
                                oci_bind_by_name($stmt, ':parissuedate', $issueDate, 50);
                                oci_bind_by_name($stmt, ':paridcomprobante', $idComprobante, 32);
                                oci_bind_by_name($stmt, ':parpkxmlcodigo', $pk_xml_codigo, 32);
                                oci_bind_by_name($stmt, ':parrespuesta', $parrespuesta, 32);
                                oci_bind_by_name($stmt, ':parmensajerespuesta', $parmensajerespuesta, 200);
                                if (!oci_execute($stmt)) {
                                    $e = oci_error($stmt);
                                    VAR_DUMP($e);
                                    log_info($this->logHeader . ' ERROR prcguardarxmlnotacrecomfiar::: ' . $e['message']);
                                } if ($parrespuesta == 1) {
                                    log_info($this->logHeader . 'pk_xml_codigo retorna prcguardarxmlnotacrecomfiar:: ' . $pk_xml_codigo . ' parrespuesta=' . $parrespuesta . '  parmensajerespuesta=' . $parmensajerespuesta);
                                    $NotaTX = $paridComfiar . ' - ' . $idNota;
                                    $cuentaProc++;
                                    $respuesta = $parrespuesta;

                                    $xmlRespBLOB = (string) $comprobante[0];
                                    $query = "UPDATE MODFACTURADOR.FACTURTBLXMLCOMFIAR
                                                SET  xml_enviado =:BLOB_CONTENT
                                                WHERE  pk_xml_codigo=$pk_xml_codigo";
                                    $connection = $this->db->conn_id;
                                    $stmt = oci_parse($connection, $query);
                                    $blob = oci_new_descriptor($connection, OCI_D_LOB);
                                    oci_bind_by_name($stmt, ":BLOB_CONTENT", $blob, -1, OCI_B_CLOB);
                                    $blob->WriteTemporary($xmlRespBLOB, OCI_TEMP_CLOB);
                                    if (!@oci_execute($stmt, OCI_DEFAULT)) {
                                        $e = oci_error($stmt);
                                        $mensaje = explode(":", $e['message']);
                                        $data['error'] = 4;
                                        $data['mensaje'] = substr($mensaje[2], 0, 44);
                                        log_info($this->logHeader . ' NO SE CARGO ARCHIVO XML NOTA_CREDITO' . $mensaje);
                                    }
                                    log_info($this->logHeader . $this->finFuncion . 'SE CARGO ARCHIVO XML NOTA CREDITO XML A BD ');

                                    oci_free_statement($stmt);
                                    $blob->free();
                                } else {
                                    log_info($this->logHeader . $this->errorGeneral . '::::NOTA_CREDITO_COMFIAR::::Error Procedimiento::prcguardarxmlfacturacomfiar::Respuesta Dian' . $estado_Salida_Transaccion);
                                    $respuesta = 100;
                                }

                                log_info($this->finFuncion . ':::::CUENTA FINAL::::' . $cuentaProc . '::::NOTA CREDITO TRANSMITIDA:::' . $NotaTX);
                            }
                            log_info($this->logHeader . $this->finFuncion . 'RESPUESTA PROCEDIMIENTO prcguardarxmlnotacrecomfiar PARRESPUESTA::' . $parrespuesta . ' - PARMENSAJE:::' . $parmensajerespuesta);
                        }
                    } else {
                        $respuesta = 101;
                        log_info($this->logHeader . $this->errorComfiar . '::::NOTA_CREDITO_COMFIAR::::Error al ejecutar soap autorizar_Comprobante: ' . $respuesta);
                    }
                } else {
                    $respuesta = 102;
                    log_info($this->logHeader . $this->errorComfiar . '::::NOTA_CREDITO_COMFIAR::::Error al ejecutar soap autorizar_Comprobante: ' . $respuesta);
                }
            } else {
                $respuesta = 103;
                log_info($this->logHeader . $this->errorGeneral . ' DATOS INICIO SESION COMFIAR NULOS: ' . $respuesta);
            }
        } else {
            $respuesta = 104;
            log_info($this->logHeader . $this->errorGeneral . ' DATA NOTA NULL: ' . $respuesta);
        }
        log_info($this->logHeader . $this->finFuncion . ' RESPUESTA -ejecutarNcMineroComfiar- : ' . $respuesta);
        $objectRespuesta = (object) [
                    'CodRespuesta' => $respuesta,
                    'Respuesta' => $urlPdfNota,
                    'IdTxComfiar' => (string) $tx_Id[0]];
        return $objectRespuesta;
//        return $respuesta;
    }

    function verificar_token_comfiar($pk_factura) {
        $venci_ant = $this->facturacionelectronicaminero->retornarValorConfiguracion($pk_factura, 'VENCIMIENTO_TOKEN_COMFIAR');
        log_info($this->logHeader . 'ENTRO A VERIFICAR TOKEN COMFIAR FECHA_VENCIMIENTO = ' . $venci_ant);
        $respuesta = 0;
        if (!empty($venci_ant)) {
            $trozos_hora = explode("T", $venci_ant);
            log_info($this->logHeader . 'TROZOS ' . $trozos_hora[0] . ' - ' . $trozos_hora[1]);

            $trozo_hora = explode(".", $trozos_hora[1]);
            $hora_vencimiento_token = $trozo_hora[0];
            $hora_actual = date("H:i:s");
            $to_time = strtotime($hora_actual);
            $from_time = strtotime($hora_vencimiento_token);
            $minutes = round(abs($to_time - $from_time) / 60, 2);
            log_info($this->logHeader . 'HORA VECIMIENTO COMFIAR= ' . $trozo_hora[0] . ' - ' . $trozo_hora[1]);
            log_info($this->logHeader . 'HORA VECIMIENTO COMFIAR= ' . $trozo_hora[0] . ' -  HORA ACTUAL= ' . $hora_actual . ' - DIFERENCIA DE = ' . $minutes . ' Minutos');

            $fecha_actual = strtotime(date("d-m-Y H:i:00", time()));
            $fecha_entrada = strtotime($venci_ant);

            $hoy = date("Y-m-d H:i:s");
            $fecha_ven_tok = $trozos_hora[0] . ' ' . $trozo_hora[0];
            $fecha1 = new DateTime($fecha_ven_tok); //fecha inicial
            $fecha2 = new DateTime($hoy); //fecha de cierre
            $intervalo = $fecha1->diff($fecha2);
            $tiempo_transcurrido = $intervalo->format('%Y años %m meses %d days %H horas %i minutos %s segundos');

            log_info($this->logHeader . 'HORA = ' . $fecha_ven_tok . ' - ' . $hoy . ' interval = ' . $tiempo_transcurrido);

            //  if ($hora_actual >= $hora_vencimiento_token || $dias_diferencia>0 || $horas_diferencia>0 ) {
            //  if ($fecha_actual > $fecha_entrada) {
            // if ($hora_actual >= $hora_vencimiento_token) {
            if ($fecha_actual >= $fecha_entrada) {
                $nueva_hora = date("H:i:s");
                log_info($this->logHeader . '::ENTRO IF :::' . $hora_vencimiento_token . '>=' . $hora_actual);
                $respuestasesion = $this->facturacionelectronicaminero->iniciar_sesion($pk_factura);
                if (isset($respuestasesion->IniciarSesionResult)) {
                    $sessionId = $respuestasesion->IniciarSesionResult->SesionId;
                    $fechaVenc = $respuestasesion->IniciarSesionResult->FechaVencimiento;
                    $resp_act_hora = $this->update_data_sesion_comfiar($pk_factura, $nueva_hora, 'HORA');
                    if ($resp_act_hora != 1)
                        log_info($this->logHeader . $this->errorGeneral . 'Error actualizando hora sesion comfiar::' . $nueva_hora);
                    $resp_act_sesion_id = $this->update_data_sesion_comfiar($pk_factura, $sessionId, 'SESIONID');
                    if ($resp_act_sesion_id != 1)
                        log_info($this->logHeader . $this->errorGeneral . 'Error actualizando sesionID comfiar::' . $sessionId);
                    $resp_act_fecha_ven = $this->update_data_sesion_comfiar($pk_factura, $fechaVenc, 'VENCIMIENTO_TOKEN');
                    if ($resp_act_fecha_ven != 1)
                        log_info($this->logHeader . $this->errorGeneral . 'Error actualizando fecha_vencimiento comfiar::' . $resp_act_fecha_ven);
//                    log_info($this->logHeader . 'SE ACTUALIZO TOKEN COMFIAR::' . $fechaVenc);
                }
                log_info($this->logHeader . 'MINUTOS DIFERENCIA::' . $minutes);
                log_info($this->logHeader . 'NUEVA HORA INICIO SESSION ::' . $nueva_hora);
            } else {
                log_info($this->logHeader . '::::TOKEN VALIDO:::TOKEN VENCE A LAS = ' . $hora_vencimiento_token . ' HORA ACTUAL = ' . $hora_actual . ' FALTAN = ' . $minutes . ' MINUTOS');
            }
            $respuesta = 1;
        } else {
            log_info($this->logHeader . $this->errorGeneral . 'FECHA VENCIMIENTO ES NULL EN verificar_token_comfiar.');
            $respuesta = 2;
        }

        return $respuesta;
    }

    //realiza llamado soap Comfiar retorna pdf para almacenarlo y crear url para almacenar en tabla FACTURTBLXMLCOMFIAR
    public function descargaPdfComfiar($idTxComfiar = null, $nroComprobante = null, $sessionId = null, $fechaVenc = null, $tipComp = null, $pk_factura_codigo = null) {
        log_info($this->logHeader . 'INGRESO CONTROLADOR FACTURA ELECTRONICA GENERAR PDF ID_tx_comfiar= ' . $idTxComfiar . ' No_comprobante = ' . $nroComprobante . ' Pk_factura = ' . $pk_factura_codigo . ' Tipo_Comprobante = ' . $tipComp);
        $codRespuesta = 0;
        $mensajeRespuesta;
        if (!empty($idTxComfiar) && !empty($nroComprobante) && !empty($sessionId) && !empty($fechaVenc) && !empty($tipComp) && !empty($pk_factura_codigo)) {

            $resultRespuestaCom = $this->facturacionelectronicaminero->descarga_pdf($sessionId, $fechaVenc, $idTxComfiar, $nroComprobante, $tipComp, $pk_factura_codigo);
            $codRespuesta = $resultRespuestaCom->CodRespuesta;
            $mensajeRespuesta = $resultRespuestaCom->Respuesta;
            if ($codRespuesta == 0) {
                $mensajeRespuesta = 'Error consumiendo SOAP DescargarPdf comfiar';
                log_info($this->logHeader . $this->errorComfiar . 'Error consumiendo SOAP DescargarPdf comfiar, en funcion -descargaPdfComfiar-');
            }
        } else {
            $mensajeRespuesta = 'Datos incorrectos ';
            log_info($this->logHeader . $this->errorGeneral . ' Datos nulos  idTxComfiar, nroComprobante, sessionId , fechaVenc tipComp, en funcion -descargaPdfComfiar-');
        }
        $objectRespuesta = (object) [
                    'CodRespuesta' => $codRespuesta,
                    'Respuesta' => $mensajeRespuesta];
        return $objectRespuesta;
    }

    function update_data_sesion_comfiar($pk_factura = null, $valor_parametro = null, $item = null) {
        log_info($this->logHeader . ' ACTUALIZAR DATOS SESION NOTA CREDITO MINERO: Pk_factura= ' . $pk_factura . '  asociada a la nota credito.');
        $parrespuesta = 0;
        if (!empty($pk_factura) && !empty($valor_parametro) && !empty($item)) {
            $sql = "BEGIN MODFACTURADOR.PKGMODFACTURADORTALOS.prcactualizatokencomfiar(
                            parpkfactura=>:parpkfactura,
                            parvalor=>:parvalor,
                            parparametro=>:parparametro,
                            parrespuesta=>:parrespuesta);
                            END;";

            $conn = $this->db->conn_id;
            $stmt = oci_parse($conn, $sql);
            oci_bind_by_name($stmt, ':parpkfactura', $pk_factura, 32);
            oci_bind_by_name($stmt, ':parvalor', $valor_parametro, 300);
            oci_bind_by_name($stmt, ':parparametro', $item, 100);
            oci_bind_by_name($stmt, ':parrespuesta', $parrespuesta, 32);

            if (!oci_execute($stmt)) {
                $e = oci_error($stmt);
                log_info($this->logHeader . $this->errorGeneral . ' prcactualizaconfcomfiar' . $e['message']);
            }
            if ($parrespuesta == 1) {
                log_info($this->logHeader . $this->finFuncion . 'Consumo -update_data_sesion_comfiar- correcto !!' . $item . ' = ' . $valor_parametro);
            }
        } else {
            $parrespuesta = 405;
            log_info($this->logHeader . $this->errorGeneral . 'DATOS NULL EN  update_data_sesion_comfiar:: pk_conf_cod  o valor_parametro');
        }
        return $parrespuesta;
    }

}
