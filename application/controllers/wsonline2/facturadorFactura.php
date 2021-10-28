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
class FacturadorFactura extends CI_Controller {

    public $iniciLog = '[INFO] ';
    public $logHeader = 'FACTURADOR::::::::: ';
    public $postData = 'POSTDATA::::::::: ';
    public $queryData = 'QUERYDATA::::::: ';
    public $errorComfiar = 'ERROR_COMFIAR::::::: ';
    public $errorGeneral = 'ERROR_GENERAL::::::: ';
    public $finFuncion = ' FIN PROCEDIMIENTO::::::: ';

    public function __construct() {
        parent::__construct();
        $this->load->helper('log4php');
        $this->load->library('facturadorfacturacionelectronica');
    }

    public function transmitirFacturaComfiar() {
        if (!empty($_POST['PK_FACTURA'])) {
            $pk_factura_codigo = $_POST['PK_FACTURA'];
            log_info($this->logHeader . '::::FACTURA_COMFIAR::::INGRESO transmitirFacturaComfiar PK_FACTURA '.$pk_factura_codigo);
            $respuesta = $this->facturadorfacturacionelectronica->iniciar_sesion($pk_factura_codigo);
            if (isset($respuesta->IniciarSesionResult)) {
                $sessionId = $respuesta->IniciarSesionResult->SesionId;
                $fechaVenc = $respuesta->IniciarSesionResult->FechaVencimiento;

                $respuesta = $this->ejecutarFacturacionComfiar($pk_factura_codigo, $sessionId, $fechaVenc);
                log_info($this->logHeader .'-'. $this->finFuncion . ' RESPUESTA ejecutarFacturacionComfiar ' . $respuesta);
                 if ($respuesta == 1 ) {
                        //llamar procedimiento actualiza factura al trasnmitida acomfiar
                        $sql = "BEGIN MODFACTURADOR.PKGMODFACTURADORGENERAL.prcmodestadoenviofactura(
                    parpkfacturacodigo=>:parpkfacturacodigo,
                    parestadocomfiar=>:parestadocomfiar,
                    parrespuesta=>:parrespuesta);
                    END;";

                        $conn = $this->db->conn_id;
                        $stmt = oci_parse($conn, $sql);
                        $parestado = 1; // estado 1 trasnmitida
                        oci_bind_by_name($stmt, ':parpkfacturacodigo', $pk_factura_codigo, 32);
                        oci_bind_by_name($stmt, ':parestadocomfiar', $parestado, 32);
                        oci_bind_by_name($stmt, ':parrespuesta', $parrespuesta, 32);
                        if (!oci_execute($stmt)) {
                            $e = oci_error($stmt);
                            VAR_DUMP($e);
                            log_info($this->logHeader.'-'.$this->errorGeneral . ' ERROR ACTUALIZANDO FACTURA -prcactualizafactestadocomfiar- '
                                    . $e['message'] . '[*] parpkfacturacodigo=' . $pk_factura_codigo . '[*] parestadocomfiar=' . $parestado);
                        }
                        if ($parrespuesta == 1) {
                            log_info($this->logHeader.'-'.$this->finFuncion . '::::FACTURA_ELECTRONICA::::prcactualizafactestadocomfiar PK_FACTURA= ' . $pk_factura_codigo . ' - TRANSMITIDA= ' . $parrespuesta);
                        }
                    }
            }
        }
        log_info($this->logHeader.'-'.$this->finFuncion. '::::FIN TRANSMITIR FACTURA COMFIAR : '.$respuesta);

        echo $respuesta;
    }

    public function ejecutarFacturacionComfiar($pk_factura_codigo, $sessionId, $venciSesion) {

        log_info($this->logHeader . '::::FACTURA_COMFIAR::::INGRESO CONTROLADOR INICIOSESION FACTURAELECTRONICA');


        if (!empty($pk_factura_codigo)) {
            if (isset($sessionId) && isset($venciSesion)) {

                $sqlDataFactura= $this->db->query("SELECT NUMERO_FACTURA,PK_CLIENTE_CODIGO  FROM MODFACTURADOR.FACTURTBLFACTURACOMFIAR
		WHERE PK_FACTURA_CODIGO=$pk_factura_codigo");

                $numerofactura = $sqlDataFactura->result_array[0]['NUMERO_FACTURA'];
                $pk_cliente_codigo = $sqlDataFactura->result_array[0]['PK_CLIENTE_CODIGO'];


                $resultAutorizar = $this->facturadorfacturacionelectronica->autorizar_Comprobante($sessionId, $venciSesion, $pk_factura_codigo);
                if ($resultAutorizar->AutorizarComprobantesAsincronicoResult) {
                    $respuesta = new SimpleXMLElement($resultAutorizar->AutorizarComprobantesAsincronicoResult);

                    if (isset($respuesta->Transaccion->ID)) {
                        $transaccionId = $respuesta->Transaccion->ID;
                        $result_salida_transaccion = $this->facturadorfacturacionelectronica->salida_Transaccion($sessionId, $venciSesion, $transaccionId, $pk_factura_codigo);
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
                                $resultRespuestaCom = $this->facturadorfacturacionelectronica->respuesta_Comprobante($sessionId, $venciSesion, $numerofactura, $tipCom,$pk_factura_codigo);
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
                            $resultRespuestaCom = $this->descargaPdfComfiar((string) $tx_Id[0], $numerofactura, $sessionId, $venciSesion, $tipCom,$pk_factura_codigo);
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
                                        . '-parusuariocreacion= ' . $usuarioAcceso
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


                                    log_info($this->logHeader .$this->finFuncion. 'SE CARGO ARCHIVO FACTURACION XML A BD ' . $name);

                                    oci_free_statement($stmt);
                                    $blob->free();
                                } else {
                                    log_info($this->logHeader .$this->errorGeneral . '::::FACTURA_COMFIAR::::Error Procedimiento::prcguardarxmlfacturacomfiar::Respuesta Dian' . $estado_Salida_Transaccion);
                                    $respuesta = 100;
                                }

                                log_info($this->finFuncion . ':::::CUENTA FINAL::::' . $cuentaProc . '::::FACTURAS TRANSMITIDAS:::' . $facturasTX);
                            }
                            log_info($this->logHeader .$this->finFuncion . 'RESPUESTA PROCEDIMIENTO prcguardarxmlfacturacomfiar PARRESPUESTA::' . $parrespuesta . ' - PARMENSAJE:::' . $parmensajerespuesta);
                        }
                    } else {
                        $respuesta = 101;
                        log_info($this->logHeader.$this->errorComfiar . '::::FACTURA_COMFIAR::::Error al ejecutar soap autorizar_Comprobante: ' . $respuesta);
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
        return $respuesta;
    }
    //realiza llamado soap Comfiar retorna pdf para almacenarlo y crear url para almacenar en tabla FACTURTBLXMLCOMFIAR
     public function descargaPdfComfiar($idTxComfiar = null, $nroComprobante = null, $sessionId = null, $fechaVenc = null, $tipComp = null,$pk_factura_codigo=null) {
        log_info($this->logHeader . 'INGRESO CONTROLADOR FACTURA ELECTRONICA GENERAR PDF ID_tx_comfiar= '.$idTxComfiar.' No_comprobante = '.$nroComprobante.' Pk_factura = '.$pk_factura_codigo. ' Tipo_Comprobante = '.$tipComp);
        $codRespuesta = 0;
        $mensajeRespuesta;
        if (!empty($idTxComfiar) && !empty($nroComprobante) && !empty($sessionId) && !empty($fechaVenc) && !empty($tipComp) &&!empty($pk_factura_codigo)) {

            $resultRespuestaCom = $this->facturadorfacturacionelectronica->descarga_pdf($sessionId, $fechaVenc, $idTxComfiar, $nroComprobante, $tipComp, $pk_factura_codigo);
            $codRespuesta = $resultRespuestaCom->CodRespuesta;
            $mensajeRespuesta = $resultRespuestaCom->Respuesta;
            if ($codRespuesta == 0) {
                $mensajeRespuesta = 'Error consumiendo SOAP DescargarPdf comfiar';
                log_info($this->logHeader.$this->errorComfiar . 'Error consumiendo SOAP DescargarPdf comfiar, en funcion -descargaPdfComfiar-');
            }
        } else {
            $mensajeRespuesta = 'Datos incorrectos ';
            log_info($this->logHeader.$this->errorGeneral . ' Datos nulos  idTxComfiar, nroComprobante, sessionId , fechaVenc tipComp, en funcion -descargaPdfComfiar-');
        }
        $objectRespuesta = (object) [
                    'CodRespuesta' => $codRespuesta,
                    'Respuesta' => $mensajeRespuesta];
        return $objectRespuesta;
    }

}
