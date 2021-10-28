<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of facturaMinero
 *
 * @author ronald.rosas
 */
class facturaMinero extends CI_Controller {

    public $logHeader = '[INFO] APOLO_TALOS_INFO::::::::: ';
    public $postData = 'POSTDATA::::::::: ';
    public $queryData = 'QUERYDATA::::::: ';
    public $errorComfiar = 'ERROR_COMFIAR::::::: ';
    public $errorGeneral = 'ERROR_GENERAL::::::: ';
    public $finFuncion = ' FIN PROCEDIMIENTO::::::: ';

    public function __construct() {

        parent::__construct();
        $this->load->helper('log4php');
        $this->load->library('facturacionelectronicaminero');
    }

    //se llama desde JOB oracle para trasnmitir facturas, si exitoso marcar factura con 1
    public function transmitirComfiar($pk_factura_talos = null) {
        log_info($this->logHeader . '::::FACTURA_MINERO::::INGRESO CONTROLADOR  FACTURAELECTRONICA TRANSMITIRCOMFIAR ');


        $parrespuesta = 0;
        $mensajeRespuesta = 'Empty';

        $EstadoCanalTX = $this->facturacionelectronicaminero->retornarValorConfiguracion($pk_factura_talos, 'ESTADO_CANAL_TX');
        log_info($this->logHeader . '::::FACTURA_COMFIAR::::TRANSMITIRCOMFIAR ESTADO CANAL TX= ' . $EstadoCanalTX);

        if (intval($EstadoCanalTX) == 1 && !empty($pk_factura_talos)) {
            $sqlFacturas = $this->db->query("SELECT fac.envio_comfiar,facxml.url_pdf from modfacturador.facturtblfacturacomfiar fac
                        LEFT JOIN modfacturador.facturtblxmlcomfiar facxml
                        ON fac.pk_factura_codigo = facxml.pk_factura_codigo where fac.pk_factura_codigo = $pk_factura_talos and facxml.pk_tipo_xml_codigo=1");
            $Envio_comfiar = $sqlFacturas->result_array[0]['ENVIO_COMFIAR'];
            $url_pdf = $sqlFacturas->result_array[0]['URL_PDF'];
            log_info($this->logHeader . '::::FACTURA_MINERO::::TRANSMITIRCOMFIAR PK_FACTURA= ' . $pk_factura_talos . ' ESTADO_ENVIOCOMFIAR= ' . $Envio_comfiar);
            if ($Envio_comfiar == 0) {

                $respuesta_token = $this->verificar_token_comfiar($pk_factura_talos);

                log_info($this->logHeader . '::::RESPUESTA_VERIFICAR_TOKEN::::' . $respuesta_token);

                $sessionId = $this->facturacionelectronicaminero->retornarValorConfiguracion($pk_factura_talos, 'SESIONID_COMFIAR');
                $fechaVenc = $this->facturacionelectronicaminero->retornarValorConfiguracion($pk_factura_talos, 'VENCIMIENTO_TOKEN_COMFIAR');
                if (!empty($sessionId) && !empty($fechaVenc) && $respuesta_token === 1) {
                    log_info($this->logHeader . '::::FACTURA_COMFIAR::::DATOS_SESION: ' . $sessionId . ' - ' . $fechaVenc);

                    log_info($this->logHeader . '::::FACTURA_COMFIAR::::TRANSMITIRCOMFIAR data facturas= ' . $pk_factura_talos);
                    $resultRespuestaFac = $this->ejecutarFacturacionComfiar($pk_factura_talos, $sessionId, $fechaVenc);
                    $respuesta = $resultRespuestaFac->CodRespuesta;
                    $mensajeRespuesta = $resultRespuestaFac->Respuesta;
                    $IdTxComfiar = $resultRespuestaFac->IdTxComfiar;
                    log_info($this->logHeader . '::::FACTURA_COMFIAR::::FACTURAELECTRONICA TRANSMITIRCOMFIAR RESPUESTA ejecutarFacturacionComfiar ' . $respuesta . ' UrlPdf: ' . $mensajeRespuesta . ' IdTxComfiar: ' . $IdTxComfiar);

                    if ($respuesta == 1) {
                        //llamar procedimiento actualiza factura al trasnmitida acomfiar
                        $sql = "BEGIN MODFACTURADOR.PKGMODFACTURADORGENERAL.prcmodestadoenviofactura(
                    parpkfacturacodigo=>:parpkfacturacodigo,
                    parestadocomfiar=>:parestadocomfiar,
                    parrespuesta=>:parrespuesta);
                    END;";

                        $conn = $this->db->conn_id;
                        $stmt = oci_parse($conn, $sql);
                        $parestado = 1; // estado 1 trasnmitida
                        oci_bind_by_name($stmt, ':parpkfacturacodigo', $pk_factura_talos, 32);
                        oci_bind_by_name($stmt, ':parestadocomfiar', $parestado, 32);
                        oci_bind_by_name($stmt, ':parrespuesta', $parrespuesta, 32);
                        if (!oci_execute($stmt)) {
                            $e = oci_error($stmt);
                            VAR_DUMP($e);
                            log_info($this->logHeader . '-' . $this->errorGeneral . ' ERROR ACTUALIZANDO FACTURA -prcactualizafactestadocomfiar- '
                                    . $e['message'] . '[*] parpkfacturacodigo=' . $pk_factura_talos . '[*] parestadocomfiar=' . $parestado);
                        }
                        if ($parrespuesta == 1) {
                            log_info($this->logHeader . '-' . $this->finFuncion . '::::FACTURA_ELECTRONICA::::prcactualizafactestadocomfiar PK_FACTURA= ' . $pk_factura_talos . ' - TRANSMITIDA= ' . $parrespuesta);
                        }
                    } else {
                        // el estado que retorno  ejecutarFacturacionComfiar diferente de 1
                        $parrespuesta = $respuesta;
                        $mensajeRespuesta = $mensajeRespuesta;
                        log_info($this->finFuncion . 'Error transmitiendo factura TALOS_MINERO= ' . $pk_factura_talos . ' CodigoRespouesta: ' . $parrespuesta . ' MensajeRespuesta: ' . $mensajeRespuesta);
                    }
                } else {
                    $parrespuesta = 501;
                    $mensajeRespuesta = 'Datos sesion comfiar erroneos:::' . $sessionId . ' - ' . $fechaVenc . ' - ' . $respuesta_token;
                    log_info($this->finFuncion . 'DATOS SESSION COMFIAR ERRADOS = ' . $sessionId . ' - ' . $fechaVenc . ' - respuesta_token = ' . $respuesta_token);
                }
            } else {
                $parrespuesta = 1;
                $mensajeRespuesta = $url_pdf;
                log_info($this->finFuncion . 'FACTURA = ' . $pk_factura_talos . ' Estado envio_comfiar = ' . $Envio_comfiar);
                //falta respuesta factura ya tiene estado 1 envio comfiar
            }
        } else {
            $parrespuesta = 500;
            $mensajeRespuesta = ' Estado Canal de tx Comfiar: ' . $EstadoCanalTX . ' Pk_factura_talos: ' . $pk_factura_talos;
            log_info($this->errorGeneral . ' ERROR ESTADO CANAL DE TRANSMISION ESTADO= ' . $EstadoCanalTX . ' PK_FACTURA_TALOS= ' . $pk_factura_talos);
            //falta respuesta de canal de transmision en estado 2
        }
//        }

        $objectRespuesta = (object) [
                    'CodRespuesta' => $parrespuesta,
                    'UrlPdf' => $mensajeRespuesta,
                    'IdTxComfiar' => $IdTxComfiar];
        return $objectRespuesta;
//        return $parrespuesta;
    }

    public function ejecutarFacturacionComfiar($pk_factura_codigo, $sessionId, $venciSesion) {

        log_info($this->logHeader . '::::FACTURA_COMFIAR::::INGRESO CONTROLADOR INICIOSESION FACTURAELECTRONICA');


        if (!empty($pk_factura_codigo)) {
            if (isset($sessionId) && isset($venciSesion)) {

                $sqlDataFactura = $this->db->query("SELECT NUMERO_FACTURA,PK_CLIENTE_CODIGO  FROM MODFACTURADOR.FACTURTBLFACTURACOMFIAR 
		WHERE PK_FACTURA_CODIGO=$pk_factura_codigo");

                $numerofactura = $sqlDataFactura->result_array[0]['NUMERO_FACTURA'];
                $pk_cliente_codigo = $sqlDataFactura->result_array[0]['PK_CLIENTE_CODIGO'];

                $tx_Id = 000;
                $resultAutorizar = $this->facturacionelectronicaminero->autorizar_Comprobante($sessionId, $venciSesion, $pk_factura_codigo);
                if ($resultAutorizar->AutorizarComprobantesAsincronicoResult) {
                    $respuesta = new SimpleXMLElement($resultAutorizar->AutorizarComprobantesAsincronicoResult);
                    $urlPdfFactura = null;
                    if (isset($respuesta->Transaccion->ID)) {
                        $transaccionId = $respuesta->Transaccion->ID;
                        $result_salida_transaccion = $this->facturacionelectronicaminero->salida_Transaccion($sessionId, $venciSesion, $transaccionId, $pk_factura_codigo);
                        if ($result_salida_transaccion->SalidaTransaccionResult) {
                            $respuesta_Salida_transaccion = new SimpleXMLElement($result_salida_transaccion->SalidaTransaccionResult);
                            $estado_Salida_Transaccion = '0';
                            $estado_Salida_Transaccion = $respuesta_Salida_transaccion->Estado;
                            $codRespuesta = 0;
                            $intentos = 0;
                            $tipCom = 1; //1-factura 2-nota credito
                            $tx_Id = isset($respuesta_Salida_transaccion->TransaccionId) ? $respuesta_Salida_transaccion->TransaccionId : $respuesta_Salida_transaccion->Transaccion->ID;

                            //validacion tipo respuesta
                            if ($estado_Salida_Transaccion == 'CargandoComprobantes') {
                                log_info($this->logHeader . '::::FACTURA_COMFIAR:::: idEstado:' . 3 . ' Estado= ' . $estado_Salida_Transaccion);
                            } else if ($estado_Salida_Transaccion == 'AProcesar') {
                                log_info($this->logHeader . '::::FACTURA_COMFIAR:::: idEstado:' . 4 . ' Estado= ' . $estado_Salida_Transaccion);
                            } else if ($estado_Salida_Transaccion == 'ProcesandoOrganismoFiscal') {
                                log_info($this->logHeader . '::::FACTURA_COMFIAR:::: idEstado:' . 5 . ' Estado= ' . $estado_Salida_Transaccion);
                            } else if ($estado_Salida_Transaccion == 'GuardandoResultado') {
                                log_info($this->logHeader . '::::FACTURA_COMFIAR:::: idEstado:' . 6 . ' Estado= ' . $estado_Salida_Transaccion);
                            } else if ($estado_Salida_Transaccion == 'ProcesandoAFIP') {
                                log_info($this->logHeader . '::::FACTURA_COMFIAR:::: idEstado:' . 7 . ' Estado= ' . $estado_Salida_Transaccion);
                            } else if ($estado_Salida_Transaccion == 'ProcesandoAFIP') {
                                log_info($this->logHeader . '::::FACTURA_COMFIAR:::: idEstado:' . 8 . ' Estado= ' . $estado_Salida_Transaccion);
                            }
                            log_info($this->logHeader . '::::FACTURA_COMFIAR::::RESPUESTA SALIDA TRANSACCION::: Id transacción= ' . $tx_Id . ' Estado= ' . $estado_Salida_Transaccion);
                            $estado_Salida_Transaccion = isset($respuesta_Salida_transaccion->Transaccion->Estado) ? $respuesta_Salida_transaccion->Transaccion->Estado : $estado_Salida_Transaccion;
                            log_info($this->logHeader . '::::FACTURA_COMFIAR:::: Estado Antes de llamar respuesta_Comprobante = ' . $tx_Id . ' Estado= ' . $estado_Salida_Transaccion);
                            while ($intentos <= 2 && $estado_Salida_Transaccion != 'Transacción Exitosa') {
                                sleep(5);
                                $resultRespuestaCom = $this->facturacionelectronicaminero->respuesta_Comprobante($sessionId, $venciSesion, $numerofactura, $tipCom, $pk_factura_codigo);
                                $respuestaComprobanteresult = new SimpleXMLElement($resultRespuestaCom->RespuestaComprobanteResult);

                                if ($respuestaComprobanteresult->Transaccion->Estado == 'Transacción Exitosa') {

                                    $estado_Salida_Transaccion = $respuestaComprobanteresult->Transaccion->Estado;
                                    log_info($this->logHeader . '::::FACTURA_COMFIAR::::Intento:' . $intentos . ' - ' . 'Estado transacción DIAN: ' . $estado_Salida_Transaccion);

                                    $comprobante = $respuestaComprobanteresult->Comprobante->informacionOrganismo->ComprobanteProcesado;
                                    $xmlRespuestaComprobante = new SimpleXMLElement(str_replace('&', '&amp;', $comprobante[0]));
                                    $nameSpace = $xmlRespuestaComprobante->getNamespaces(true);
                                    $arrayChNameSpace = $xmlRespuestaComprobante->children($nameSpace["cbc"]);
                                    $idComprobante = $arrayChNameSpace->ID;
                                    $issueDate = $arrayChNameSpace->IssueDate;
                                    log_info($this->logHeader . '::::FACTURA_COMFIAR::::ID_COMPROBANTE:::' . $idComprobante . ' - ' . 'ISSUEDATE: ' . $issueDate);

                                    $respuestaDian = $respuestaComprobanteresult->Comprobante->RespuestaDIAN;
                                    $ObjRespDian = get_object_vars($respuestaDian[0]);
                                    $stringXmlRespDian = $ObjRespDian[0];
                                    log_info($this->logHeader . '::::FACTURA_COMFIAR::::Respuesta xml dian ' . $stringXmlRespDian);
                                    //
                                    $xml = preg_replace('/(<\?xml[^?]+?)utf-16/i', '$1utf-8', $stringXmlRespDian);
                                    $xmlRespuestaDian = simplexml_load_string($xml);
                                    $cufe = $xmlRespuestaDian->Version;
                                    $comments = $xmlRespuestaDian->Comments;
                                    $tx_Id = $xmlRespuestaDian->TransaccionId;
                                    log_info($this->logHeader . '::::FACTURA_COMFIAR::::Respuesta dian CUFE= ' . (string) $cufe[0] . ' - Comments= ' . (string) $comments[0] . ' - IdTransaccionComfiar' . (string) $tx_Id[0]);

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

//                                    $msgInfComfiar = $respuestaComprobanteresult->Comprobante->informacionComfiar->mensajes->mensaje->mensaje;
//                                    $$msgInfComfiaridMsgComfiar = $respuestaComprobanteresult->Comprobante->informacionComfiar->mensajes->mensaje->identificador;
//                                        $mensajeError = $estadoInfComfiar . ' -Número Factura: ' . $numerofactura . ' -ID Transaccion: ' . (string)$tx_Id[0] . ' -ID Error ' . (string) $idMsgComfiar[0] . '-Mensaje: ' . (string) $msgInfComfiar[0];
                                    $mensajeError = $estadoInfComfiar . ' -Número Factura: ' . $numerofactura . ' -ID Transaccion: ' . (string) $tx_Id[0] . ' ::::';

                                    log_info($this->logHeader . $this->errorComfiar . 'COMPROBANTES ERRONEOS:: ' . $mensajeError);

                                    //aca se actualizaria factura a estado error por estructura errada xml
                                    $sql = "BEGIN MODFACTURADOR.PKGMODFACTURADORTALOS.prcactulizaerrortxfactura(
                                        parpkfactura=>:parpkfactura, 
                                        parmsgerror=>:parmsgerror,
                                        parrespuesta=>:parrespuesta
                                        );
                                        END;";
                                    $conn = $this->db->conn_id;
                                    $stmt = oci_parse($conn, $sql);
                                    $msjErrorDian = $mensajeError . $msjErrorDian;
                                    oci_bind_by_name($stmt, ':parpkfactura', $pk_factura_codigo, 32);
                                    oci_bind_by_name($stmt, ':parmsgerror', $msjErrorDian, 1024);
                                    oci_bind_by_name($stmt, ':parrespuesta', $parrespuestaupdate, 32);
                                    if (!oci_execute($stmt)) {
                                        $e = oci_error($stmt);
                                        VAR_DUMP($e);
                                        log_info($this->logHeader . ' ERROR prcactulizaerrortxfactura::: ' . $e['message']);
                                    } if ($parrespuestaupdate == 1) {
                                        log_info($this->logHeader . '-' . $this->finFuncion . ' Se actualizo factura en prcactulizaerrortxfactura con el msg_error_tx_factura= ' . $mensajeError);
                                    }
                                    $respuesta = 99;
                                    break;
                                }

                                $intentos++;
                            }
                            log_info($this->logHeader . '::::FACTURA_COMFIAR::::ESTADO FINAL::: Id transacción= ' . $tx_Id . ' Estado= ' . $estado_Salida_Transaccion . ' NO INTENTO:::' . $intentos);

                            if (isset($respuesta_Salida_transaccion->comprobantes->Comprobante->informacionComfiar)) {
                                $error = $respuesta_Salida_transaccion->comprobantes->Comprobante->informacionComfiar->Estado;
                                $mensaje = $respuesta_Salida_transaccion->comprobantes->Comprobante->informacionComfiar->mensajes[0]->mensaje->mensaje;
                                $estado_Salida_Transaccion = $estado_Salida_Transaccion . '-' . $error . '-' . $mensaje;
                            }
                        }
                        if ($estado_Salida_Transaccion == 'Transacción Exitosa') {

                            log_info($this->logHeader . '::::FACTURA_COMFIAR::::INGRESO EJECUTAR prcguardarxmlcomfiar: ' . strlen($comprobante));
                            $codRespuesta = 1;

                            //ejecutar funcion para generar pdf factura y retornar url pdf para guardar en Bd
                            $resultRespuestaCom = $this->descargaPdfComfiar((string) $tx_Id[0], $numerofactura, $sessionId, $venciSesion, $tipCom, $pk_factura_codigo);
                            $codRespuestaPdf = $resultRespuestaCom->CodRespuesta;
                            $RespuestaPdf = $resultRespuestaCom->Respuesta;
                            log_info($this->logHeader . '::::FACTURA_COMFIAR::::RESPUESTA descargaPdfFactura: codRespuestaPdf::' . $codRespuestaPdf . '::RespuestaPdf::' . $RespuestaPdf);

                            if ($codRespuestaPdf == 1) {
                                $urlPdfFactura = $resultRespuestaCom->Respuesta;
                            } else {
                                $urlPdfFactura = '';
                                log_info($this->errorGeneral . '::::FACTURA_COMFIAR::::Error EJECUTAR descargaPdfFactura: ' . $codRespuestaPdf . '-' . $RespuestaPdf);
                            }
                            log_info($this->logHeader . '::::FACTURA_COMFIAR::::COMPROBANTE TAMAÑO:' . strlen((string) $comprobante[0]) . ' - ' . 'Estado transacción DIAN: ' . $estado_Salida_Transaccion);

                            //se guarda informacion base de datos
                            if (!empty($comprobante)) {
                                $cuentaProc = 0;
                                $facturasTX = 0;
                                $sql = "BEGIN modfacturador.FCPKGFACTURACION.prcguardarxmlfacturacomfiar(
                                partipoxml=>:partipoxml, 
                                parpkfactura=>:parpkfactura, 
                                parpkcliente=>:parpkcliente,
                                parxml=>:parxml,
                                paridtxcomfiar=>:paridtxcomfiar,
                                parestadotx=>:parestadotx,
                                parcufe=>:parcufe,
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
                                $tipoXml = 1; //tipo factura

                                $xmlResp = '';
                                $issueDate = (string) $issueDate;
                                $idComprobante = (string) $idComprobante;
//                                $xmlResp = (string) $comprobante[0];
                                $pk_cliente = $pk_cliente_codigo;
                                $idFactura = $pk_factura_codigo;
                                $cufe = (string) $cufe[0];
                                $estadoDian = (string) $comments[0];
                                log_info($this->logHeader . 'INGRESO prcguardarxmlcomfiar');
                                log_info($this->postData . ''
                                        . '-partipoxml= ' . $tipoXml
                                        . '-parpkfactura= ' . $idFactura
                                        . '-parpkentidad= ' . $pk_cliente
                                        . '-parxml= ' . gettype($xmlResp)
                                        . '-paridtxcomfiar= ' . $paridComfiar
                                        . '-parestadotx= ' . $parestadotx
                                        . '-parcufe= ' . $cufe
                                        . '-parestadodian= ' . $estadoDian
                                        . '-parurlpdf= ' . $urlPdfFactura
                                        . '-parissuedate= ' . (string) $issueDate
                                        . '-paridcomprobante= ' . (string) $idComprobante
                                );

                                oci_bind_by_name($stmt, ':partipoxml', $tipoXml, 32);
                                oci_bind_by_name($stmt, ':parpkfactura', $idFactura, 32);
                                oci_bind_by_name($stmt, ':parpkcliente', $pk_cliente, 32);
                                oci_bind_by_name($stmt, ':parxml', $xmlResp, 50000);
                                oci_bind_by_name($stmt, ':paridtxcomfiar', $paridComfiar, 32);
                                oci_bind_by_name($stmt, ':parestadotx', $parestadotx, 200);
                                oci_bind_by_name($stmt, ':parcufe', $cufe, 300);
                                oci_bind_by_name($stmt, ':parestadodian', $estadoDian, 200);
                                oci_bind_by_name($stmt, ':parurlpdf', $urlPdfFactura, 500);
                                oci_bind_by_name($stmt, ':parissuedate', $issueDate, 50);
                                oci_bind_by_name($stmt, ':paridcomprobante', $idComprobante, 32);
                                oci_bind_by_name($stmt, ':parpkxmlcodigo', $pk_xml_codigo, 32);
                                oci_bind_by_name($stmt, ':parrespuesta', $parrespuesta, 32);
                                oci_bind_by_name($stmt, ':parmensajerespuesta', $parmensajerespuesta, 200);
                                if (!oci_execute($stmt)) {
                                    $e = oci_error($stmt);
                                    VAR_DUMP($e);
                                    log_info($this->logHeader . ' ERROR PRCGUARDARXMLCOMFIAR::: ' . $e['message']);
                                } if ($parrespuesta == 1) {
                                    log_info($this->logHeader . 'pk_xml_codigo retorna PRCGUARDARXMLCOMFIAR:: ' . $pk_xml_codigo . ' parrespuesta=' . $parrespuesta . '  parmensajerespuesta=' . $parmensajerespuesta);
                                    $facturasTX = $paridComfiar . ' - ' . $idFactura;
                                    $cuentaProc++;
                                    $respuesta = $parrespuesta;

                                    $xmlRespBLOB = (string) $comprobante[0];
                                    $query = "UPDATE MODFACTURADOR.FACTURTBLXMLCOMFIAR
                                                SET
                                                xml_enviado =:BLOB_CONTENT
                                                WHERE  pk_xml_codigo=$pk_xml_codigo";
                                    $connection = $this->db->conn_id;
                                    $stmt = oci_parse($connection, $query);
                                    $blob = oci_new_descriptor($connection, OCI_D_LOB);
                                    oci_bind_by_name($stmt, ":BLOB_CONTENT", $blob, -1, OCI_B_CLOB);
                                    $blob->WriteTemporary($xmlRespBLOB, OCI_TEMP_CLOB);
                                    if (!@oci_execute($stmt, OCI_DEFAULT)) {
                                        $e = oci_error($stmt);
                                        $mensaje = explode(":", $e['message']);
                                        var_dump($mensaje);
                                        $data['error'] = 4;
                                        $data['mensaje'] = substr($mensaje[2], 0, 44);
                                        echo $sql;
                                        echo $name;
                                        log_info($this->logHeader . ' NO SE CARGO ARCHIVO de FACTURACION XML' . $name . $mensaje);
                                    }


                                    log_info($this->logHeader . $this->finFuncion . 'SE CARGO ARCHIVO FACTURACION XML A BD ' );

                                    oci_free_statement($stmt);
                                    $blob->free();
                                } else {
                                    log_info($this->logHeader . $this->errorGeneral . '::::FACTURA_COMFIAR::::Error Procedimiento::prcguardarxmlfacturacomfiar::Respuesta Dian' . $estado_Salida_Transaccion);
                                    $respuesta = 100;
                                }

                                log_info($this->finFuncion . ':::::CUENTA FINAL::::' . $cuentaProc . '::::FACTURAS TRANSMITIDAS:::' . $facturasTX);
                            }
                            log_info($this->logHeader . $this->finFuncion . 'RESPUESTA PROCEDIMIENTO prcguardarxmlfacturacomfiar PARRESPUESTA::' . $parrespuesta . ' - PARMENSAJE:::' . $parmensajerespuesta);
                        } else {
                            $respuesta = 111;
                            $urlPdfFactura = (string)$estado_Salida_Transaccion[0];
                            log_info($this->logHeader . $this->errorComfiar . '::::FACTURA_COMFIAR::::Error Transmision XML ' . $respuesta . ' Estado comprobante : ' . $estado_Salida_Transaccion . ' Intentos transmision: ' . $intentos);
                        }
                    } else {
                        $respuesta = 101;
                        log_info($this->logHeader . $this->errorComfiar . '::::FACTURA_COMFIAR::::Error al ejecutar soap autorizar_Comprobante: ' . $respuesta);
                    }
                } else {
                    $respuesta = 102;
                    log_info($this->logHeader . $this->errorComfiar . '::::FACTURA_COMFIAR::::Error al ejecutar soap autorizar_Comprobante: ' . $respuesta);
                }
            } else {
                $respuesta = 103;
                log_info($this->logHeader . $this->errorGeneral . ' DATOS SESION COMFIAR NULOS: ' . $respuesta);
            }
        } else {
            $respuesta = 104;
            log_info($this->logHeader . $this->errorGeneral . ' PK_FACTURA es null: ' . $respuesta);
        }
        $objectRespuesta = (object) [
                    'CodRespuesta' => $respuesta,
                    'Respuesta' => $urlPdfFactura,
                    'IdTxComfiar' => (string)$tx_Id[0]];
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

    function update_data_sesion_comfiar($pk_factura = null, $valor_parametro = null, $item = null) {
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

}
