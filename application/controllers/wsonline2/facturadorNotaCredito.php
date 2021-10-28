<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of transmitirFacturaComfiar
 *
 * @author ronald.rosas
 */
class facturadorNotaCredito extends CI_Controller {

    public $iniciLog = '[INFO] ';
    public $logHeader = 'FACTURADOR:::::NC::::: ';
    public $postData = 'POSTDATA::::::::: ';
    public $queryData = 'QUERYDATA::::::: ';
    public $errorComfiar = 'ERROR_COMFIAR::::::: ';
    public $errorGeneral = 'ERROR_GENERAL::::::: ';
    public $finFuncion = ' FIN PROCEDIMIENTO::::::: ';

    public function __construct() {
        parent::__construct();
        $this->load->helper('log4php');
        $this->load->library('facturadorfacturacionelectronicanotacredito');
    }

    public function transmitirNcComfiar() {
        if (!empty($_POST['PK_NOTA'])) {
            $pk_nota_codigo = $_POST['PK_NOTA'];
            log_info($this->logHeader . '::::NOTA_CREDITO_COMFIAR::::INGRESO transmitirNcComfiar PK_NOTA ' . $pk_nota_codigo);
            $sqlData = $this->db->query("SELECT NOTA.PK_FACTURA_CODIGO,NOTA.CONSECUTIVO_NOTA_CREDITO ,NOTA.PK_EMPRESA_EMISORA,NOTA.PK_CLIENTE_CODIGO ,CONF.PK_CONF_CODIGO FROM MODFACTURADOR.FACTURTBLNOTACREDITO NOTA
                JOIN MODFACTURADOR.FACTURTBLEMPEMICONFIG CONF
                ON CONF.PK_EMPRESA_EMISORA = NOTA.PK_EMPRESA_EMISORA
		WHERE PK_NOTA_CODIGO=$pk_nota_codigo");
            $datanota = $sqlData->result_array[0];
            $pk_cliente_codigo = $datanota['PK_CLIENTE_CODIGO'];
            $pk_empresa_emisora = $datanota['PK_EMPRESA_EMISORA'];
            $pk_conf_codigo = $datanota['PK_CONF_CODIGO'];
            $consecutivo_nota = $datanota['CONSECUTIVO_NOTA_CREDITO'];
            $pk_factura_codigo = $datanota['PK_FACTURA_CODIGO'];
            log_info($this->logHeader . '::::NOTA_CREDITO_COMFIAR:::: PK_CLIENTE_CODIGO: ' . $pk_cliente_codigo . ' PK_EMPRESA_EMISORA: ' . $pk_empresa_emisora . ' PK_CONF_CODIGO: ' . $pk_conf_codigo . ' CONSECUTIVO_NOTA_CREDITO: ' . $consecutivo_nota);
            $respuesta = $this->facturadorfacturacionelectronicanotacredito->iniciar_sesion($pk_conf_codigo);
            if (isset($respuesta->IniciarSesionResult)) {
                $sessionId = $respuesta->IniciarSesionResult->SesionId;
                $fechaVenc = $respuesta->IniciarSesionResult->FechaVencimiento;

                $respuesta = $this->ejecutarNCComfiar($pk_factura_codigo, $pk_conf_codigo, $consecutivo_nota, $pk_nota_codigo, $pk_cliente_codigo, $pk_empresa_emisora, $sessionId, $fechaVenc);
                log_info($this->logHeader . '-' . $this->finFuncion . ' RESPUESTA ejecutarNCComfiar ' . $respuesta);
                if ($respuesta === 1) {
                    //llamar procedimiento actualiza nota credito a trasnmitida a comfiar
                    $sql = "BEGIN MODFACTURADOR.PKGMODFACTURADORGENERAL.prcmodestadoenvionotacredito(
                    parpknotacodigo=>:parpknotacodigo,
                    parestadocomfiar=>:parestadocomfiar,
                    parrespuesta=>:parrespuesta);
                    END;";

                    $conn = $this->db->conn_id;
                    $stmt = oci_parse($conn, $sql);
                    $parestado = 1; // estado 1 trasnmitida
                    oci_bind_by_name($stmt, ':parpknotacodigo', $pk_nota_codigo, 32);
                    oci_bind_by_name($stmt, ':parestadocomfiar', $parestado, 32);
                    oci_bind_by_name($stmt, ':parrespuesta', $parrespuesta, 32);
                    if (!oci_execute($stmt)) {
                        $e = oci_error($stmt);
                        VAR_DUMP($e);
                        log_info($this->logHeader . '-' . $this->errorGeneral . ' ERROR ACTUALIZANDO NOTA_CREDITO -prcmodestadoenvionotacredito- '
                                . $e['message'] . '[*] parpknotacodigo=' . $pk_nota_codigo . '[*] parestadocomfiar=' . $parestado);
                    }
                    if ($parrespuesta == 1) {
                        log_info($this->logHeader . '-' . $this->finFuncion . '::::NOTA_CREDITO::::prcmodestadoenvionotacredito PK_NOTA_CODIGO= ' . $pk_nota_codigo . ' - TRANSMITIDA= ' . $parrespuesta);
                    }
                }
            }
        }
        log_info($this->logHeader . '-' . $this->finFuncion . '::::FIN TRANSMITIR NOTA_CREDITO COMFIAR : ' . $respuesta);

        echo $respuesta;
    }

    public function ejecutarNCComfiar($pk_factura_codigo, $pk_conf_codigo, $consecutivo_nota, $pk_nota_codigo, $pk_cliente_codigo, $pk_empresa_emisora, $sessionId, $venciSesion) {

        log_info($this->logHeader . '::::NOTA_CREDITO_COMFIAR::::INGRESO FUNCION ejecutarNCComfiar ::PK_NOTA_CODIGO = ' . $pk_nota_codigo . ' PK_CLIENTE_CODIGO = ' . $pk_cliente_codigo . ' PK_EMPRESA_EMISORA = ' . $pk_empresa_emisora);


        if (!empty($pk_factura_codigo) && !empty($pk_nota_codigo) && !empty($pk_cliente_codigo) && !empty($pk_empresa_emisora) && !empty($consecutivo_nota)) {
            if (isset($sessionId) && isset($venciSesion)) {



                $resultAutorizar = $this->facturadorfacturacionelectronicanotacredito->autorizar_Comprobante($sessionId, $venciSesion, $pk_conf_codigo, $pk_cliente_codigo, $pk_empresa_emisora, $pk_nota_codigo);
                if ($resultAutorizar->AutorizarComprobantesAsincronicoResult) {
                    $respuesta = new SimpleXMLElement($resultAutorizar->AutorizarComprobantesAsincronicoResult);

                    if (isset($respuesta->Transaccion->ID)) {
                        $transaccionId = $respuesta->Transaccion->ID;
                        $result_salida_transaccion = $this->facturadorfacturacionelectronicanotacredito->salida_Transaccion($sessionId, $venciSesion, $transaccionId, $pk_conf_codigo);
                        if ($result_salida_transaccion->SalidaTransaccionResult) {
                            $respuesta_Salida_transaccion = new SimpleXMLElement($result_salida_transaccion->SalidaTransaccionResult);
                            $estado_Salida_Transaccion = '0';
                            $estado_Salida_Transaccion = $respuesta_Salida_transaccion->Estado;
                            $codRespuesta = 0;
                            $intentos = 0;
                            $tipCom = 1; //1-factura 2-nota credito
                            $tx_Id = $respuesta_Salida_transaccion->TransaccionId;
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
                                sleep(5);
                                $resultRespuestaCom = $this->facturadorfacturacionelectronicanotacredito->respuesta_Comprobante($sessionId, $venciSesion, $consecutivo_nota, $pk_conf_codigo);
                                $respuestaComprobanteresult = new SimpleXMLElement($resultRespuestaCom->RespuestaComprobanteResult);
                                log_info($this->logHeader . '::::NOTA_CREDITO_COMFIAR:::: ID Transaccion: ' . $transaccionId . ' Comprobante ID: '.$consecutivo_nota.' Intento No:' . $intentos . ' - ' . 'Estado Comprobante: ' . $respuestaComprobanteresult->Transaccion->Estado);
                                


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
                                }

                                $intentos++;
                            }
                            log_info($this->logHeader . '::::NOTA_CREDITO_COMFIAR::::ESTADO FINAL::: Id transacción= ' . $tx_Id . ' Estado= ' . $estado_Salida_Transaccion . ' NO INTENTO:::' . $intentos);
                        }
                        if ($estado_Salida_Transaccion == 'Transacción Exitosa') {

                            log_info($this->logHeader . '::::NOTA_CREDITO_COMFIAR::::INGRESO EJECUTAR prcguardarxmlcomfiar: ' . $estado_Salida_Transaccion);
                            $codRespuesta = 1;

                            //ejecutar funcion para generar pdf nota credito y retornar url pdf para guardar en Bd
                            $resultRespuestaCom = $this->descargaPdfComfiar((string) $tx_Id[0], $consecutivo_nota, $sessionId, $venciSesion, $pk_conf_codigo);
                            $codRespuestaPdf = $resultRespuestaCom->CodRespuesta;
                            $RespuestaPdf = $resultRespuestaCom->Respuesta;
                            log_info($this->logHeader . '::::NOTA_CREDITO_COMFIAR::::RESPUESTA descargaPdfComfiar: codRespuestaPdf::' . $codRespuestaPdf . '::RespuestaPdf::' . $RespuestaPdf);

                            if ($codRespuestaPdf == 1) {
                                $urlPdfNota = $resultRespuestaCom->Respuesta;
                            } else {
                                $urlPdfNota = '';
                                log_info($this->errorGeneral . '::::NOTA CREDITO_COMFIAR::::Error EJECUTAR descargaPdfComfiar  : ' . $codRespuestaPdf . '-' . $RespuestaPdf);
                            }
                            log_info($this->logHeader . '::::FACTURA_COMFIAR::::COMPROBANTE TAMAÑO:' . strlen((string) $comprobante[0]) . ' - ' . 'Estado transacción DIAN: ' . $estado_Salida_Transaccion);

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
                                        log_info($this->logHeader . ' NO SE CARGO ARCHIVO XML NOTA_CREDITO'  . $mensaje);
                                    }
                                    log_info($this->logHeader . $this->finFuncion . 'SE CARGO ARCHIVO XML NOTA CREDITO XML A BD ' );

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
        return $respuesta;
    }

    //realiza llamado soap Comfiar retorna pdf para almacenarlo y crear url para almacenar en tabla FACTURTBLXMLCOMFIAR
    public function descargaPdfComfiar($idTxComfiar = null, $nroComprobante = null, $sessionId = null, $fechaVenc = null, $pk_conf_codigo = null) {
        log_info($this->logHeader . 'INGRESO CONTROLADOR FACTURA ELECTRONICA GENERAR PDF ID_tx_comfiar= ' . $idTxComfiar . ' No_comprobante = ' . $nroComprobante . ' Pk_conf_codigo = ' . $pk_conf_codigo);
        $codRespuesta = 0;
        $mensajeRespuesta;
        if (!empty($idTxComfiar) && !empty($nroComprobante) && !empty($sessionId) && !empty($fechaVenc) && !empty($pk_conf_codigo)) {

            $resultRespuestaCom = $this->facturadorfacturacionelectronicanotacredito->descarga_pdf($sessionId, $fechaVenc, $idTxComfiar, $nroComprobante, $pk_conf_codigo);
            $codRespuesta = $resultRespuestaCom->CodRespuesta;
            $mensajeRespuesta = $resultRespuestaCom->Respuesta;
            if ($codRespuesta == 0) {
                $mensajeRespuesta = 'Error consumiendo SOAP DescargarPdf comfiar';
                log_info($this->logHeader . $this->errorComfiar . 'Error consumiendo SOAP DescargarPdf comfiar, en funcion -descargaPdfComfiar-');
            }
        } else {
            $mensajeRespuesta = 'Datos incorrectos ';
            log_info($this->logHeader . $this->errorGeneral . ' Datos nulos  idTxComfiar, nroComprobante, sessionId , fechaVenc , pk_conf_codigo en funcion -descargaPdfComfiar-');
        }
        $objectRespuesta = (object) [
                    'CodRespuesta' => $codRespuesta,
                    'Respuesta' => $mensajeRespuesta];
        return $objectRespuesta;
    }

}
