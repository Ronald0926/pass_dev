<?php

/**
 * Description of facturaElectronica
 *
 * @author ronald.rosas
 */
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class FacturaElectronica extends CI_Controller {

    public $iniciLog = '[INFO] ';
    public $logHeader = 'APOLOINFO::::::::: ';
    public $postData = 'POSTDATA::::::::: ';
    public $queryData = 'QUERYDATA::::::: ';
    public $errorComfiar = 'ERROR_COMFIAR::::::: ';
    public $errorGeneral = 'ERROR_GENERAL::::::: ';
    public $finFuncion = ' FIN PROCEDIMIENTO::::::: ';

    public function __construct() {

        parent::__construct();
        $this->load->helper('log4php');
        $this->load->library('facturacionelectronica');
    }

    //se llama desde JOB oracle para trasnmitir facturas, si exitoso marcar factura con 1
    public function transmitirComfiar() {
        log_info($this->logHeader . '::::FACTURA_COMFIAR::::INGRESO CONTROLADOR  FACTURAELECTRONICA TRANSMITIRCOMFIAR ');


        //iniciar sesion Comfiar
//        $respuesta = $this->facturacionelectronica->iniciar_sesion();
//        if (isset($respuesta->IniciarSesionResult)) {
//            $sessionId = $respuesta->IniciarSesionResult->SesionId;
//            $fechaVenc = $respuesta->IniciarSesionResult->FechaVencimiento;

        $parrespuesta = 0;
        $ultima_hora_sesion = $this->facturacionelectronica->retornarValorConfiguracion(31);
        $token_comfiar = $this->facturacionelectronica->retornarValorConfiguracion(33);
        log_info($this->logHeader . '::::FACTURA_COMFIAR::::TRANSMITIRCOMFIAR ULTIMA_HORA_SESION_COMFIAR= ' . $ultima_hora_sesion . ' TOKEN_COMFIAR= ' . $token_comfiar);
        $EstadoCanalTX = $this->facturacionelectronica->retornarValorConfiguracion(29);
        if (intval($EstadoCanalTX) == 1) {
            $sqlFacturas = $this->db->query("select *
              from(select pk_factur_codigo,numero_factura from  MODFACTUR.FACTBLFACTUR   where  ENVIO_COMFIAR=0 and error_tx_factura=0  order by pk_factur_codigo asc) where rownum=1");
            $facturas = $sqlFacturas->result_array;
            log_info($this->logHeader . '::::FACTURA_COMFIAR::::TRANSMITIRCOMFIAR Cantidad Facturas= ' . count($facturas));
            if (count($facturas) > 0) {

                $respuesta_token = $this->verificar_token_comfiar();

                log_info($this->logHeader . '::::RESPUESTA_VERIFICAR_TOKEN::::' . $respuesta_token);

                $sessionId = $this->facturacionelectronica->retornarValorConfiguracion(32);
                $fechaVenc = $this->facturacionelectronica->retornarValorConfiguracion(33);
                if (!empty($sessionId) && !empty($fechaVenc) && $respuesta_token === 1) {
                    log_info($this->logHeader . '::::FACTURA_COMFIAR::::DATOS_SESION: ' . $sessionId . ' - ' . $fechaVenc);
                    foreach ($facturas as $fact) {
                        log_info($this->logHeader . '::::FACTURA_COMFIAR::::TRANSMITIRCOMFIAR data facturas= ' . $fact['PK_FACTUR_CODIGO']);

                        $pk_factur_codigo = $fact['PK_FACTUR_CODIGO'];

                        //llamar procedimiento actualiza factura al trasnmitida acomfiar
                        /* comenta Ronald 10 noviembre 
                          $sql = "BEGIN modfactur.facpkgdatacomfiar.prcactualizafactestadocomfiar(
                          parpkfacturacodigo=>:parpkfacturacodigo,
                          parestadocomfiar=>:parestadocomfiar,
                          parrespuesta=>:parrespuesta);
                          END;";

                          $conn = $this->db->conn_id;
                          $stmt = oci_parse($conn, $sql);
                          $parestado = 3; // estado 3 transmitiendo
                          oci_bind_by_name($stmt, ':parpkfacturacodigo', $pk_factur_codigo, 32);
                          oci_bind_by_name($stmt, ':parestadocomfiar', $parestado, 32);
                          oci_bind_by_name($stmt, ':parrespuesta', $parrespuesta, 32);
                          if (!oci_execute($stmt)) {
                          $e = oci_error($stmt);
                          VAR_DUMP($e);
                          log_info($this->errorGeneral . ' ERROR ACTUALIZANDO FACTURA -prcactualizafactestadocomfiar- '
                          . $e['message'] . '[*] parpkfacturacodigo=' . $pk_factur_codigo . '[*] parestadocomfiar=' . $parestado);
                          }
                          if ($parrespuesta == 1) {
                          log_info($this->finFuncion . '::::FACTURA_ELECTRONICA::::prcactualizafactestadocomfiar PK_FACTURA= ' . $pk_factur_codigo . ' - TRANSMITITIENDO= ' . $parrespuesta);
                          } */

                        $respuesta = $this->ejecutarFacturacionComfiar($pk_factur_codigo, $sessionId, $fechaVenc);

                        $sqlFacturaTxComfar = $this->db->query("select nvl(count(pk_xml_codigo),0) cantidad from
                                            modfactur.factblxmlcomfiar 
                                            where pk_factura_codigo = $pk_factur_codigo 
                                            and pk_tipo_xml_codigo =1
                                            and estado_transaccion='Transacción Exitosa'");
                        $cantidadTx = $sqlFacturaTxComfar->result_array[0]['CANTIDAD'];

                        log_info($this->logHeader . '::::FACTURA_COMFIAR::::FACTURAELECTRONICA TRANSMITIRCOMFIAR RESPUESTA ejecutarFacturacionComfiar ' . $respuesta);

                        if ($respuesta == 1 || $cantidadTx != 0) {
                            //llamar procedimiento actualiza factura al trasnmitida acomfiar
                            $sql = "BEGIN modfactur.facpkgdatacomfiar.prcactualizafactestadocomfiar(
                    parpkfacturacodigo=>:parpkfacturacodigo,
                    parestadocomfiar=>:parestadocomfiar,
                    parrespuesta=>:parrespuesta);
                    END;";

                            $conn = $this->db->conn_id;
                            $stmt = oci_parse($conn, $sql);
                            $parestado = 1; // estado 1 trasnmitida
                            oci_bind_by_name($stmt, ':parpkfacturacodigo', $pk_factur_codigo, 32);
                            oci_bind_by_name($stmt, ':parestadocomfiar', $parestado, 32);
                            oci_bind_by_name($stmt, ':parrespuesta', $parrespuesta, 32);
                            if (!oci_execute($stmt)) {
                                $e = oci_error($stmt);
                                VAR_DUMP($e);
                                log_info($this->errorGeneral . ' ERROR ACTUALIZANDO FACTURA -prcactualizafactestadocomfiar- '
                                        . $e['message'] . '[*] parpkfacturacodigo=' . $pk_factur_codigo . '[*] parestadocomfiar=' . $parestado);
                            }
                            if ($parrespuesta == 1) {
                                log_info($this->finFuncion . '::::FACTURA_ELECTRONICA::::prcactualizafactestadocomfiar PK_FACTURA= ' . $pk_factur_codigo . ' - TRANSMITIDA= ' . $parrespuesta);
                            }
                        }
                    }
                } else {
                    log_info($this->finFuncion . 'DATOS SESSION COMFIAR ERRADOS = ' . $sessionId . ' - ' . $fechaVenc . ' - respuesta_token = ' . $respuesta_token);
                }
            } else {
                log_info($this->finFuncion . 'NO EXISTEN FACTURAS PENDIENTES POR TRANSMITIR CANTIDAD = ' . count($facturas));
            }
        } else {
            log_info($this->errorGeneral . ' ERROR ESTADO CANAL DE TRANSMISION ESTADO= ' . $EstadoCanalTX);
        }
//        }


        return $parrespuesta;
    }

    //transmitir nota credito comfiar
    public function transmitirNotaCreditoComfiar($pk_nota_codigo) {
        log_info($this->logHeader . '::::NOTA_CREDITO_COMFIAR::::INGRESO FUNCION  transmitirNotaCreditoComfiar');

        if (!empty($pk_nota_codigo)) {
            $respuesta = $this->facturacionelectronica->iniciar_sesion();
            if (isset($respuesta->IniciarSesionResult)) {
                $sessionId = $respuesta->IniciarSesionResult->SesionId;
                $fechaVenc = $respuesta->IniciarSesionResult->FechaVencimiento;

                $respuestaNota = $this->ejecutarNcComfiar($pk_nota_codigo, $sessionId, $fechaVenc);
            }
        } else {
            log_info($this->errorGeneral . ' ERROR -transmitirNotaCreditoComfiar- ::PK_NOTA_CREDITO ES NULL::');
        }
    }

    //transmitir nota credito comfiar prueba navegador

    public function transmitirFacturaPostman($pk_factura = null) {
        $EstadoCanalTX = $this->facturacionelectronica->retornarValorConfiguracion(29);

        $post = $this->input->post();
        if ($post) {
            log_info($this->logHeader . '::::ENTRO POST::::');
            $pk_factura = $post['PK_FACTURA'];
        }
        $respuestaservicio = 0;
        if (intval($EstadoCanalTX) == 1) {

            log_info($this->logHeader . '::::FACTURA_COMFIAR::::TRANSMITIRCOMFIAR POSTMAN = ' . $pk_factura);
            if (!empty($pk_factura)) {

                $respuesta_token = $this->verificar_token_comfiar();

                log_info($this->logHeader . '::::RESPUESTA_VERIFICAR_TOKEN::::' . $respuesta_token);

                $sessionId = $this->facturacionelectronica->retornarValorConfiguracion(32);
                $fechaVenc = $this->facturacionelectronica->retornarValorConfiguracion(33);
                if (!empty($sessionId) && !empty($fechaVenc) && $respuesta_token === 1) {
                    log_info($this->logHeader . '::::FACTURA_COMFIAR::::DATOS_SESION: ' . $sessionId . ' - ' . $fechaVenc);

                    $pk_factur_codigo = $pk_factura;
                    $respuesta = $this->ejecutarFacturacionComfiar($pk_factur_codigo, $sessionId, $fechaVenc);
                    log_info($this->logHeader . '::::RESPUESTA_ejecutarFacturacionComfiar:::: ' . $respuesta);

                    $respuestaservicio = $respuesta;
                    $sqlFacturaTxComfar = $this->db->query("select nvl(count(pk_xml_codigo),0) cantidad from
                                            modfactur.factblxmlcomfiar 
                                            where pk_factura_codigo = $pk_factur_codigo 
                                            and pk_tipo_xml_codigo =1
                                            and estado_transaccion='Transacción Exitosa'");
                    $cantidadTx = $sqlFacturaTxComfar->result_array[0]['CANTIDAD'];

                    log_info($this->logHeader . '::::FACTURA_COMFIAR::::FACTURAELECTRONICA TRANSMITIRCOMFIAR RESPUESTA ejecutarFacturacionComfiar ' . $respuesta);

                    if ($respuesta == 1 || $cantidadTx != 0) {
                        log_info($this->logHeader . '::::FACTURA_COMFIAR::::ENTRA ACTUALIZAR ESTADO TRASMITIDA '.$cantidadTx. ' - '. $respuesta);
                        //llamar procedimiento actualiza factura al trasnmitida acomfiar
                        $sql = "BEGIN modfactur.facpkgdatacomfiar.prcactualizafactestadocomfiar(
                    parpkfacturacodigo=>:parpkfacturacodigo,
                    parestadocomfiar=>:parestadocomfiar,
                    parrespuesta=>:parrespuesta);
                    END;";

                        $conn = $this->db->conn_id;
                        $stmt = oci_parse($conn, $sql);
                        $parestado = 1; // estado 1 trasnmitida
                        oci_bind_by_name($stmt, ':parpkfacturacodigo', $pk_factur_codigo, 32);
                        oci_bind_by_name($stmt, ':parestadocomfiar', $parestado, 32);
                        oci_bind_by_name($stmt, ':parrespuesta', $parrespuesta, 32);
                        if (!oci_execute($stmt)) {
                            $respuestaservicio=404;
                            $e = oci_error($stmt);
                            VAR_DUMP($e);
                            log_info($this->errorGeneral . ' ERROR ACTUALIZANDO FACTURA -prcactualizafactestadocomfiar- '
                                    . $e['message'] . '[*] parpkfacturacodigo=' . $pk_factur_codigo . '[*] parestadocomfiar=' . $parestado);
                        }
                        if ($parrespuesta == 1) {
                            $respuestaservicio =1;
                            log_info($this->finFuncion . '::::FACTURA_ELECTRONICA::::prcactualizafactestadocomfiar PK_FACTURA= ' . $pk_factur_codigo . ' - TRANSMITIDA= ' . $parrespuesta);
                        }
                    }
                } else {
                    $respuestaservicio=401;
                    log_info($this->finFuncion . 'DATOS SESSION COMFIAR ERRADOS = ' . $sessionId . ' - ' . $fechaVenc . ' - respuesta_token = ' . $respuesta_token);
                }
            } else {
                $respuestaservicio=402;
                log_info($this->finFuncion . 'PK_FACTURA ENTRADA ES NULL ');
            }
        } else {
            $respuestaservicio=404;
            log_info($this->errorGeneral . ' ERROR ESTADO CANAL DE TRANSMISION ESTADO= ' . $EstadoCanalTX);
        }
        
        $objectRespuesta = (object) [
                    'PK_FACTURA' => $pk_factur_codigo,
                    'CODIGO_RESPUESTA' => $respuestaservicio];
        $myJSON = json_encode($objectRespuesta);
        echo  $myJSON;
//        echo 'RESPUESTA TRANMISION MANUAL PK_FACTURA : ' . $pk_factur_codigo . ' CODIGO_RESPUESTA:' .  $parrespuesta;
    }

    public function ejecutarFacturacionComfiar($pk_factura_codigo, $sessionId, $venciSesion) {

        log_info($this->logHeader . '::::FACTURA_COMFIAR::::INGRESO CONTROLADOR ejecutarFacturacionComfiar');

        $EstadoCanalTX = $this->facturacionelectronica->retornarValorConfiguracion(29);
        if (intval($EstadoCanalTX) == 1) {
            if (!empty($pk_factura_codigo)) {
//        $this->verificarPerfilCo();
                //cargo la librería facturacion
                $this->load->library('facturacionelectronica');
//            $respuesta = $this->facturacionelectronica->iniciar_sesion();
                if (isset($sessionId) && isset($venciSesion)) {
                    $sessionId = $sessionId;
                    $fechaVenc = $venciSesion;
                    $idFact = $pk_factura_codigo;
                    $ClienteFactura = $this->db->query("
		select NUMERO_FACTURA,PK_ENT_CODIGO,CLIENTE_NUMERO_DOCUMENTO 
                , CLIENTE_TIPO_DOCUMENTO FROM MODFACTUR.FACTBLFACTUR
		WHERE PK_FACTUR_CODIGO=$idFact");

                    $numerofactura = $ClienteFactura->result_array[0]['NUMERO_FACTURA'];
                    $pkEntCodigo = $ClienteFactura->result_array[0]['PK_ENT_CODIGO'];
                    $EntTd = $ClienteFactura->result_array[0]['CLIENTE_TIPO_DOCUMENTO'];
                    $EntDocumento = $ClienteFactura->result_array[0]['CLIENTE_NUMERO_DOCUMENTO'];

                    $resultAutorizar = $this->facturacionelectronica->autorizar_Comprobante($sessionId, $fechaVenc, $idFact);

                    if ($resultAutorizar->AutorizarComprobantesAsincronicoResult) {
//                var_dump($resultAutorizar->AutorizarComprobantesAsincronicoResult->comprobantes);
//                echo "RESPUESTA -1:::<br />";
                        // var_dump(gettype($resultAutorizar->AutorizarComprobantesAsincronicoResult));
//                  $idstr=simplexml_load_string($resultAutorizar->AutorizarComprobantesAsincronicoResult);
                        $respuesta = new SimpleXMLElement($resultAutorizar->AutorizarComprobantesAsincronicoResult);

                        if (isset($respuesta->Transaccion->ID)) {
                            $transaccionId = $respuesta->Transaccion->ID;
                            $result_salida_transaccion = $this->facturacionelectronica->salida_Transaccion($sessionId, $fechaVenc, $transaccionId);

//                    $respuesta_salida_transaccion = new SimpleXMLElement($result_salida_transaccion);
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
                                $comprobante = $respuesta_Salida_transaccion->comprobantes->Comprobante->informacionOrganismo->ComprobanteProcesado;
                                $tiempoEspera = $this->facturacionelectronica->retornarValorConfiguracion(28);
                                log_info($this->logHeader . '::::FACTURA_COMFIAR:::: TIEMPO ESPERA TRANSMITIR = ' . $tiempoEspera . ' segundos');
                                $CodAutorizacion = 0;
                                while ($intentos <= 2 && $estado_Salida_Transaccion != 'Transacción Exitosa') {
                                    sleep(intval($tiempoEspera));

                                    $resultRespuestaCom = $this->facturacionelectronica->respuesta_Comprobante($sessionId, $fechaVenc, $numerofactura, $tipCom);
                                    $respuestaComprobanteresult = new SimpleXMLElement($resultRespuestaCom->RespuestaComprobanteResult);
//                            var_dump($respuestaComprobanteresult->Transaccion->Estado);
                                    log_info($this->logHeader . '::::FACTURA_COMFIAR:::: ID Transaccion: ' . $tx_Id . ' Intento No:' . $intentos . ' - ' . 'Estado transacción DIAN: ' . $respuestaComprobanteresult->Transaccion->Estado);


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
//                                  
                                        $respuestaDian = $respuestaComprobanteresult->Comprobante->RespuestaDIAN;
                                        $ObjRespDian = get_object_vars($respuestaDian[0]);

//                                $objRespuestaDian= get_object_vars($respuestaDian);
                                        $stringXmlRespDian = $ObjRespDian[0];
                                        log_info($this->logHeader . '::::FACTURA_COMFIAR::::Respuesta xml dian ' . $stringXmlRespDian);
                                        //
                                        $xml = preg_replace('/(<\?xml[^?]+?)utf-16/i', '$1utf-8', $stringXmlRespDian);
                                        $xmlRespuestaDian = simplexml_load_string($xml);
//                               //permite saber error en xml
//                                libxml_use_internal_errors(true);
//                                $sxe = simplexml_load_string($xml);
//                                if ($sxe === false) {
//                                    echo "Error cargando XML\n";
//                                    foreach (libxml_get_errors() as $error) {
//                                        echo "\t", $error->message;
//                                    }
//                                }
                                        $cufe = $xmlRespuestaDian->Version;
                                        $comments = $xmlRespuestaDian->Comments;
                                        $tx_Id = $xmlRespuestaDian->TransaccionId;
                                        $CodAutorizacion = $xmlRespuestaDian->CodAutorizacion;
                                        log_info($this->logHeader . '::::FACTURA_COMFIAR::::Respuesta dian CUFE= ' . (string) $cufe[0] . ' - Comments= ' . (string) $comments[0] . ' - IdTransaccionComfiar= ' . (string) $tx_Id[0] . ' -CodAutorizacion= ' . (string) $CodAutorizacion[0]);

                                        break;
                                    } elseif ($respuestaComprobanteresult->Transaccion->Estado == 'Comprobantes Erróneos') {

                                        $estadoInfComfiar = $respuestaComprobanteresult->Comprobante->informacionComfiar->Estado;
                                        $mensajesErrorDIAN = $respuestaComprobanteresult->Comprobante->informacionComfiar->mensajes->mensaje;


                                        //se recorren errores encontrados para formar string y guardarlo en BD
                                        $msjErrorDian = '';
                                        foreach ($mensajesErrorDIAN as $msj) {
//                                            echo $msj->identificador, ' interpretado por ', $msj->mensaje, PHP_EOL;
                                            $msjErrorDian = $msjErrorDian . ' ID ERROR: ' . $msj->identificador . ': ' . $msj->mensaje;
                                        }


                                        $msgInfComfiar = $respuestaComprobanteresult->Comprobante->informacionComfiar->mensajes->mensaje->mensaje;
                                        $idMsgComfiar = $respuestaComprobanteresult->Comprobante->informacionComfiar->mensajes->mensaje->identificador;

//                                        $mensajeError = $estadoInfComfiar . ' -Número Factura: ' . $numerofactura . ' -ID Transaccion: ' . (string)$tx_Id[0] . ' -ID Error ' . (string) $idMsgComfiar[0] . '-Mensaje: ' . (string) $msgInfComfiar[0];
                                        $mensajeError = $estadoInfComfiar . ' -Número Factura: ' . $numerofactura . ' -ID Transaccion: ' . (string) $tx_Id[0] . ' ::::';

                                        log_info($this->logHeader . $this->errorComfiar . 'COMPROBANTES ERRONEOS:: ' . $mensajeError);

                                        //aca se actualizaria factura a estado error por estructura errada xml
                                        $sql = "BEGIN modfactur.facpkgdatacomfiar.prcactulizaerrortxfactura(
                                        parpkfactura=>:parpkfactura, 
                                        parmsgerror=>:parmsgerror,
                                        parrespuesta=>:parrespuesta
                                        );
                                        END;";
                                        $conn = $this->db->conn_id;
                                        $stmt = oci_parse($conn, $sql);
                                        $msjErrorDian = $mensajeError . $msjErrorDian;
                                        oci_bind_by_name($stmt, ':parpkfactura', $idFact, 32);
                                        oci_bind_by_name($stmt, ':parmsgerror', $msjErrorDian, 1024);
                                        oci_bind_by_name($stmt, ':parrespuesta', $parrespuestaupdate, 32);
                                        if (!oci_execute($stmt)) {
                                            $e = oci_error($stmt);
                                            VAR_DUMP($e);
                                            log_info($this->logHeader . ' ERROR prcactulizaerrortxfactura::: ' . $e['message']);
                                        } if ($parrespuestaupdate == 1) {
                                            log_info($this->logHeader . '-' . $this->finFuncion . ' Se actualizo factura en prcactulizaerrortxfactura con el msg_error_tx_factura= ' . $mensajeError);
                                        }
                                        $codRespuesta = 99;
                                        break;
                                    }
                                    $intentos++;

                                    if ($intentos == 2 && $CodAutorizacion[0] == '510') {
                                        log_info($this->logHeader . '-' . $this->errorComfiar . '::::Error de conexión con la DIAN NO INTENTOS:::' . $intentos . ' CODIGO_ERROR= ' . (string) $CodAutorizacion[0]);
                                        //aca se actualizaria factura a estado error por estructura errada xml
                                        $sql = "BEGIN modfactur.facpkgdatacomfiar.prcactualizaconfcomfiar(
                                        parpkconfcodigo=>:parpkconfcodigo, 
                                        parvalor=>:parvalor,
                                        parrespuesta=>:parrespuesta
                                        );
                                        END;";
                                        $parcodConfig = 29; //PK_CONF_CODIGO de la tabla MODFACTUR.FACTBLCONFCOMFIAR 
                                        $parvalorcanal = 2; //Estado del canal de transmision 
                                        $conn = $this->db->conn_id;
                                        $stmt = oci_parse($conn, $sql);
                                        oci_bind_by_name($stmt, ':parpkconfcodigo', $parcodConfig, 32);
                                        oci_bind_by_name($stmt, ':parvalor', $parvalorcanal, 200);
                                        oci_bind_by_name($stmt, ':parrespuesta', $parrespuestaestado, 32);
                                        if (!oci_execute($stmt)) {
                                            $e = oci_error($stmt);
                                            VAR_DUMP($e);
                                            log_info($this->logHeader . ' ERROR prcactulizaerrortxDIAN::: ' . $e['message']);
                                        } if ($parrespuestaestado == 1) {
                                            log_info($this->logHeader . '-' . $this->finFuncion . ' Se actualizo ESTADO CANAL  en prcactualizaconfcomfiar Nuevo Valor= ' . $parvalorcanal . ' PK_CONF_CODIGO= ' . $parcodConfig);
                                        }
                                        break;
                                    }
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
                                $resultRespuestaCom = $this->descargaPdfComfiar((string) $tx_Id[0], $numerofactura, $sessionId, $fechaVenc, $tipCom);
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
                                    $facturasTX = '';
                                    $sql = "BEGIN modfactur.facpkgdatacomfiar.prcguardarxmlcomfiar(
                        partipoxml=>:partipoxml, 
                        parpkfactura=>:parpkfactura, 
                        parpkentidad=>:parpkentidad,
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
                                    $usuarioAcceso = $EntTd . $EntDocumento;
                                    $xmlResp = '';
                                    $issueDate = (string) $issueDate;
                                    $idComprobante = (string) $idComprobante;
//                                $xmlResp = (string) $comprobante[0];
                                    $pk_entidad = $pkEntCodigo;
                                    $idFactura = $idFact;
                                    $cufe = (string) $cufe[0];
                                    $estadoDian = (string) $comments[0];
                                    log_info($this->logHeader . 'INGRESO prcguardarxmlcomfiar');
                                    log_info($this->postData . ''
                                            . '-partipoxml= ' . $tipoXml
                                            . '-parpkfactura= ' . $idFactura
                                            . '-parpkentidad= ' . $pk_entidad
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
                                    oci_bind_by_name($stmt, ':parpkentidad', $pk_entidad, 32);
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
                                        $facturasTX = $facturasTX . ' - ' . $idFactura;
                                        $cuentaProc++;
                                        $mensajeRespuesta = $parmensajerespuesta;
                                        $codRespuesta = $parrespuesta;

                                        $xmlRespBLOB = (string) $comprobante[0];
                                        $query = "UPDATE MODFACTUR.FACTBLXMLCOMFIAR
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


                                        log_info($this->logHeader . 'SE CARGO ARCHIVO FACTURACION XML A BD ' . $name);

                                        oci_free_statement($stmt);
                                        $blob->free();
                                    } else {
                                        $mensajeRespuesta = '::::FACTURA_COMFIAR::::Error Procedimiento::prcguardarxmlcomfiar::Respuesta Dian' . $estado_Salida_Transaccion;
                                        $codRespuesta = 1001;
                                    }

                                    log_info($this->finFuncion . ':::::CUENTA FINAL::::' . $cuentaProc . '::::FACTURAS TRANSMITIDAS:::' . $facturasTX);
                                }
                                log_info($this->logHeader . 'RESPUESTA PROCEDIMIENTO prcguardarxmlcomfiar PARRESPUESTA::' . $parrespuesta . ' - PARMENSAJE:::' . $parmensajerespuesta);
                            }

                            $respuestaComfiar = 'CODIGORESPUESTA:' . $codRespuesta . ',ESTADO:' . $estado_Salida_Transaccion;
                            log_info($this->finFuncion . '::::FACTURA_COMFIAR:::: Al parecer todo salió bien: ' . $respuestaComfiar);

//                     var_dump($respuestaComfiar);
                        } else {
                            //error autorizando comprobante
                            $codRespuesta = 101;
                            log_info($this->errorComfiar . '::::FACTURA_COMFIAR::::Error al ejecutar soap autorizar_Comprobante: ' . $respuesta);
                        }
                    } else {
                        $codRespuesta = 102;
                        log_info($this->errorComfiar . '::::FACTURA_COMFIAR::::Error al ejecutar soap autorizar_Comprobante: ' . $resultAutorizar);
                        $errorArmandoXML = 'Error en la estructura del xml ::ERROR::' . $resultAutorizar;
                        //aca se actualizaria factura a estado error por estructura errada xml
                        $sql = "BEGIN modfactur.facpkgdatacomfiar.prcactulizaerrortxfactura(
                        parpkfactura=>:parpkfactura, 
                        parmsgerror=>:parmsgerror,
                        parrespuesta=>:parrespuesta
                        );
                        END;";
                        $conn = $this->db->conn_id;
                        $stmt = oci_parse($conn, $sql);
                        oci_bind_by_name($stmt, ':parpkfactura', $idFact, 32);
                        oci_bind_by_name($stmt, ':parmsgerror', $errorArmandoXML, 200);
                        oci_bind_by_name($stmt, ':parrespuesta', $parrespuestaupdate, 32);
                        if (!oci_execute($stmt)) {
                            $e = oci_error($stmt);
                            VAR_DUMP($e);
                            log_info($this->logHeader . ' ERROR prcactulizaerrortxfactura::: ' . $e['message']);
                        } if ($parrespuestaupdate == 1) {
                            log_info($this->logHeader . '-' . $this->finFuncion . ' Se actualizo factura en prcactulizaerrortxfactura con el msg_error_tx_factura= ' . $errorArmandoXML);
                        }
                    }
                } else {
                    $codRespuesta = 103;
                    log_info($this->errorComfiar . '::::FACTURA_COMFIAR::::Error al ejecutar soap iniciar_sesion: ' . $respuesta);
                }
            } else {
                $codRespuesta = 104;
                log_info($this->errorComfiar . '::::FACTURA_COMFIAR::::PK_FACTURA es null: ' . $respuesta);
            }
        } else {
            //error en canal de transmision 
            $codRespuesta = 105;
            log_info($this->errorComfiar . '::::FACTURA_COMFIAR::::POR FAVOR ACTUALIZAR ESTADO CANAL DE TRANSMISION A 1 EN MODFACTUR.FACTBLCONFCOMFIAR: ' . $codRespuesta . ' ESTADO ACTUAL CANAL = ' . $EstadoCanalTX);
        }
//        var_dump($codRespuesta);
        return $codRespuesta;
//         print_r($respuesta);
    }

    public function ejecutarNcComfiar($pk_nota_codigo, $sessionId, $venciSesion) {
        log_info($this->logHeader . 'INGRESO CONTROLADOR INICIOSESION FACTURAELECTRONICA');

        //cargo la librería facturacion
        $this->load->library('facturacionelectronica');
        if (!empty($pk_nota_codigo) && isset($sessionId) && isset($venciSesion)) {
            $resultAutorizar = $this->facturacionelectronica->autorizar_ComprobanteNC($sessionId, $venciSesion, $pk_nota_codigo);
            if ($resultAutorizar->AutorizarComprobantesAsincronicoResult) {
                // var_dump(gettype($resultAutorizar->AutorizarComprobantesAsincronicoResult));
//                  $idstr=simplexml_load_string($resultAutorizar->AutorizarComprobantesAsincronicoResult);
                $respuesta = new SimpleXMLElement($resultAutorizar->AutorizarComprobantesAsincronicoResult);

//                  var_dump($respuesta->Transaccion->ID);
                if (isset($respuesta->Transaccion->ID)) {
                    $transaccionId = $respuesta->Transaccion->ID;
                    $result_salida_transaccion = $this->facturacionelectronica->salida_Transaccion($sessionId, $venciSesion, $transaccionId);

                    $respuesta_Salida_transaccion = new SimpleXMLElement($result_salida_transaccion->SalidaTransaccionResult);

                    $estado_Salida_Transaccion = $respuesta_Salida_transaccion->Estado;
                    $IdtxComfiar = $respuesta_Salida_transaccion->TransaccionId;
                    log_info($this->logHeader . '::::NOTA_CREDITO_COMFIAR:::: IdTxComfiar: ' . $IdtxComfiar . ' Estado= ' . $estado_Salida_Transaccion);
                    $intentos = 0;
                    $tiempoEspera = $this->facturacionelectronica->retornarValorConfiguracion(28);
                    while ($intentos <= 2 && $estado_Salida_Transaccion != 'Transacción Exitosa') {
                        sleep(intval($tiempoEspera));
                        $tipCom = 2; //a-factura, 2 Nota credito
                        $resultRespuestaCom = $this->facturacionelectronica->respuesta_Comprobante($sessionId, $venciSesion, $pk_nota_codigo, $tipCom);
                        $respuestaComprobanteresult = new SimpleXMLElement($resultRespuestaCom->RespuestaComprobanteResult);
                        log_info($this->logHeader . '::::NOTA_CREDITO_COMFIAR:::: ID Transaccion: ' . $IdtxComfiar . ' Intento No:' . $intentos . ' - ' . 'Estado Comprobante: ' . $respuestaComprobanteresult->Transaccion->Estado);


                        if ($respuestaComprobanteresult->Transaccion->Estado == 'Transacción Exitosa') {

                            $estado_Salida_Transaccion = $respuestaComprobanteresult->Transaccion->Estado;
                            log_info($this->logHeader . '::::NOTA_CREDITO_COMFIAR::::Intento:' . $intentos . ' - ' . 'Estado transacción DIAN: ' . $estado_Salida_Transaccion);

                            $comprobante = $respuestaComprobanteresult->Comprobante->informacionOrganismo->ComprobanteProcesado;
                            $xmlRespuestaComprobante = new SimpleXMLElement($comprobante[0]);
                            $nameSpace = $xmlRespuestaComprobante->getNamespaces(true);
                            $arrayChNameSpace = $xmlRespuestaComprobante->children($nameSpace["cbc"]);
                            $idComprobante = $arrayChNameSpace->ID;
                            $issueDate = $arrayChNameSpace->IssueDate;
                            log_info($this->logHeader . '::::NOTA_CREDITO_COMFIAR::::ID_COMPROBANTE:::' . $idComprobante . ' - ' . 'ISSUEDATE: ' . $issueDate);
//                                  
                            $respuestaDian = $respuestaComprobanteresult->Comprobante->RespuestaDIAN;
                            $ObjRespDian = get_object_vars($respuestaDian[0]);

//                                $objRespuestaDian= get_object_vars($respuestaDian);
                            $stringXmlRespDian = $ObjRespDian[0];
                            log_info($this->logHeader . '::::NOTA_CREDITO_COMFIAR::::Respuesta xml dian ' . $stringXmlRespDian);
                            //
                            $xml = preg_replace('/(<\?xml[^?]+?)utf-16/i', '$1utf-8', $stringXmlRespDian);
                            $xmlRespuestaDian = simplexml_load_string($xml);
//                               //permite saber error en xml
//                                libxml_use_internal_errors(true);
//                                $sxe = simplexml_load_string($xml);
//                                if ($sxe === false) {
//                                    echo "Error cargando XML\n";
//                                    foreach (libxml_get_errors() as $error) {
//                                        echo "\t", $error->message;
//                                    }
//                                }
                            $cude = $xmlRespuestaDian->Version;
                            $comments = $xmlRespuestaDian->Comments;
                            $tx_Id = $xmlRespuestaDian->TransaccionId;
                            log_info($this->logHeader . '::::NOTA_CREDITO_COMFIAR::::Respuesta dian CUDE= ' . (string) $cude[0] . ' - Comments= ' . (string) $comments[0] . ' - IdTransaccionComfiar' . (string) $tx_Id[0]);

                            break;
                        }
                        $intentos++;
                    }

                    if ($estado_Salida_Transaccion == 'Transacción Exitosa') {

                        log_info($this->logHeader . '::::NOTA_CREDITO_COMFIAR::::INGRESO EJECUTAR prcguardarxmlcomfiar: ' . strlen($comprobante));
                        $codRespuesta = 1;

                        $tipoCom = 2; //nota credito
                        //ejecutar funcion para generar pdf nota credito y retornar url pdf para guardar en Bd
                        $resultRespuestaCom = $this->descargaPdfComfiar((string) $tx_Id[0], $pk_nota_codigo, $sessionId, $venciSesion, $tipoCom);
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
                            $ClienteNota = $this->db->query("SELECT nota.pk_ent_codigo, nota.pk_factur_codigo
                                            FROM MODFACTUR.factblnota nota
                                            WHERE nota.pk_nota_codigo=$pk_nota_codigo");

                            $pkEntCodigo = $ClienteNota->result_array[0]['PK_ENT_CODIGO'];
                            $pkfacturcod = $ClienteNota->result_array[0]['PK_FACTUR_CODIGO'];

                            $sql = "BEGIN modfactur.facpkgdatacomfiar.prcguardarxmlnotacomfiar(
                        partipoxml=>:partipoxml, 
                        parpkfactura=>:parpkfactura, 
                        parpknota=>:parpknota,
                        parpkentidad=>:parpkentidad,
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
                            $pk_entidad = $pkEntCodigo;
                            $idNota = $pk_nota_codigo;
                            $cude = (string) $cude[0];
                            $estadoDian = (string) $comments[0];
                            log_info($this->logHeader . 'INGRESO prcguardarxmlcomfiar');
                            log_info($this->postData . ''
                                    . '-partipoxml= ' . $tipoXml
                                    . '-parpknota= ' . $pk_nota_codigo
                                    . '-parpkentidad= ' . $pk_entidad
                                    . '-parxml= ' . gettype($xmlResp)
                                    . '-paridtxcomfiar= ' . $paridComfiar
                                    . '-parestadotx= ' . $parestadotx
                                    . '-parcufe= ' . $cude
                                    . '-parestadodian= ' . $estadoDian
                                    . '-parurlpdf= ' . $urlPdfNota
                                    . '-parissuedate= ' . (string) $issueDate
                                    . '-paridcomprobante= ' . (string) $idComprobante
                            );

                            oci_bind_by_name($stmt, ':partipoxml', $tipoXml, 32);
                            oci_bind_by_name($stmt, ':parpkfactura', $pkfacturcod, 32);
                            oci_bind_by_name($stmt, ':parpknota', $idNota, 32);
                            oci_bind_by_name($stmt, ':parpkentidad', $pk_entidad, 32);
                            oci_bind_by_name($stmt, ':paridtxcomfiar', $paridComfiar, 32);
                            oci_bind_by_name($stmt, ':parestadotx', $parestadotx, 200);
                            oci_bind_by_name($stmt, ':parcude', $cude, 300);
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
                                log_info($this->logHeader . ' ERROR PRCGUARDARXMLCOMFIAR::: ' . $e['message']);
                            } if ($parrespuesta == 1) {
                                log_info($this->logHeader . ':::PK_XML_CODIGO:::' . $pk_xml_codigo);
                                $NotaTX = ' - ' . $pk_nota_codigo;
                                $mensajeRespuesta = $parmensajerespuesta;
                                $codRespuesta = $parrespuesta;
                                $xmlRespBLOB = (string) $comprobante[0];
                                var_dump($xmlRespBLOB);
//                                    exit();
                                $query = "UPDATE MODFACTUR.FACTBLXMLCOMFIAR
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
                                    log_info($this->logHeader . ' NO SE CARGO ARCHIVO DE NOTA CREDITO XML' . $name . $mensaje);
                                }


                                log_info($this->logHeader . 'SE CARGO ARCHIVO FACTURACION XML A BD ');

                                oci_free_statement($stmt);
                                $blob->free();
                            } else {
                                $mensajeRespuesta = '::::NOTA_CREDITO_COMFIAR::::Error Procedimiento::prcguardarxmlcomfiar::Respuesta Dian' . $estado_Salida_Transaccion;
                                $codRespuesta = 1001;
                            }

                            log_info($this->finFuncion . ':::::::::NOTA_CREDITO TRANSMITIDA:::' . $NotaTX);
                        }
                        log_info($this->logHeader . 'RESPUESTA PROCEDIMIENTO prcguardarxmlcomfiar PARRESPUESTA::' . $parrespuesta . ' - PARMENSAJE:::' . $parmensajerespuesta);
                    }
                } else {
                    //error autorizando comprobante
                }
                // var_dump('id='.$id);
                // var_dump('SALIDA.......'.$idstr);
            }

//            var_dump($respuesta);
//            var_dump($respuesta->IniciarSesionResult->SesionId);
//            $xml = $this->facturacionelectronica->generarXML();
            //var_dump($resultAutorizar);
        } else {
            $error = 404;
            $this->output->set_output($error);
        }
        $$this->output->set_output($urlPdfNota);
    }

    public function descargaPdfComfiar($idTxComfiar = null, $nroComprobante = null, $sessionId = null, $fechaVenc = null, $tipComp = null) {
        log_info($this->logHeader . 'INGRESO CONTROLADOR FACTURA ELECTRONICA GENERAR PDF');
        $codRespuesta = 0;
        $mensajeRespuesta;
        //cargo la librería facturacion
        $this->load->library('facturacionelectronica');
        if (!empty($idTxComfiar) && !empty($nroComprobante) && !empty($sessionId) && !empty($fechaVenc) && !empty($tipComp)) {

            $resultRespuestaCom = $this->facturacionelectronica->descarga_pdf($sessionId, $fechaVenc, $idTxComfiar, $nroComprobante, $tipComp);
            $codRespuesta = $resultRespuestaCom->CodRespuesta;
            $mensajeRespuesta = $resultRespuestaCom->Respuesta;
            if ($codRespuesta == 0) {
                $mensajeRespuesta = 'Error consumiendo SOAP DescargarPdf comfiar';
                log_info($this->errorComfiar . 'Error consumiendo SOAP DescargarPdf comfiar, en funcion -descargaPdfComfiar-');
            }
        } else {
            $mensajeRespuesta = 'Datos incorrectos ';
            log_info($this->errorGeneral . ' Datos nulos  idTxComfiar, nroComprobante, sessionId , fechaVenc tipComp, en funcion -descargaPdfComfiar-');
        }
        $objectRespuesta = (object) [
                    'CodRespuesta' => $codRespuesta,
                    'Respuesta' => $mensajeRespuesta];
        return $objectRespuesta;
    }

    public function descargaPdf($idfactura = null) {
        require_once("application/controllers/wsonline2/factura.php");
        $libfactura = new Factura();
        if (!empty($idfactura)) {
            $sqlfacturapdf = $this->db->query("select url_pdf from MODFACTUR.factblxmlcomfiar
                                where pk_factura_codigo = $idfactura
                                    and pk_tipo_xml_codigo=1");
            $urlFact = $sqlfacturapdf->result_array[0]['URL_PDF'];

            if (empty($urlFact)) {
                //desarrollo y QA
//                $sqlfacturappo = $this->db->query("SELECT COUNT(numero_factura) CANTIDAD FROM modfactur.factblfactur
//                                where pk_factur_codigo = $idfactura
//                                and prefijo_factura ='FV' ");
                //produccion
                $sqlfacturappo = $this->db->query("SELECT COUNT(numero_factura) CANTIDAD FROM modfactur.factblfactur
                                where pk_factur_codigo = $idfactura
                                and prefijo_factura ='PPO'");
                $esFactAnterior = $sqlfacturappo->result_array[0]['CANTIDAD'];
                if ($esFactAnterior != 0) {
                    $urlFact = $libfactura->crear($idfactura);
                } else {
                    $urlFact = 0;
                }
                $this->output->set_output($urlFact);
                return $urlFact;
            } else {
                $this->output->set_output($urlFact);
                return $urlFact;
            }
        } else {
            //factura null
            $urlFact = 404;
//                    $urlFact = $libfactura->crear($idfactura);
            $this->output->set_output($urlFact);
            return $urlFact;
        }
    }

    public function tiempo_inicio_sesion() {

        $ultima_hora_inicio_se = $this->facturacionelectronica->retornarValorConfiguracion(31);

        $hora_actual = date("H:i:s");
        $to_time = strtotime($hora_actual);
        $from_time = strtotime($ultima_hora_inicio_se);
        $minutes = round(abs($to_time - $from_time) / 60, 2);

        log_info($this->logHeader . 'TIEMPO_INICIO_SESION ::  HORA_ANT_INICIO_SE: ' . $ultima_hora_inicio_se . ' HORA_ACTUAl : ' . $hora_actual . '  MINUTOS_DIFERENCIA: ' . $minutes);

//        echo("The difference in minutes is: $minutes minutes.");
        if ($minutes >= 9) {
            $nueva_hora = date("H:i:s");
            $respuesta = $this->facturacionelectronica->iniciar_sesion();
            if (isset($respuesta->IniciarSesionResult)) {
                $sessionId = $respuesta->IniciarSesionResult->SesionId;
                $fechaVenc = $respuesta->IniciarSesionResult->FechaVencimiento;
                $resp_act_hora = $this->update_data_sesion_comfiar(31, $nueva_hora);
                if ($resp_act_hora != 1)
                    log_info($this->logHeader . $this->errorGeneral . 'Error actualizando hora sesion comfiar::' . $nueva_hora);
                $resp_act_sesion_id = $this->update_data_sesion_comfiar(32, $sessionId);
                if ($resp_act_sesion_id != 1)
                    log_info($this->logHeader . $this->errorGeneral . 'Error actualizando sesionID comfiar::' . $sessionId);
                $resp_act_fecha_ven = $this->update_data_sesion_comfiar(33, $fechaVenc);
                if ($resp_act_sesion_id != 1)
                    log_info($this->logHeader . $this->errorGeneral . 'Error actualizando fecha_vencimiento comfiar::' . $resp_act_fecha_ven);
            }
            log_info($this->logHeader . 'MINUTOS ::' . $minutes);
            log_info($this->logHeader . 'NUEVA HORA SESSION ::' . $nueva_hora);
        }
    }

    function update_data_sesion_comfiar($pk_conf_cod = null, $valor_parametro = null) {
        $parrespuesta = 0;
        if (!empty($pk_conf_cod) && !empty($valor_parametro)) {
            $sql = "BEGIN modfactur.facpkgdatacomfiar.prcactualizaconfcomfiar(
                            parpkconfcodigo =>:parpkconfcodigo,
                            parvalor =>:parvalor,
                            parrespuesta =>:parrespuesta);
                            END;";

            $conn = $this->db->conn_id;
            $stmt = oci_parse($conn, $sql);
            oci_bind_by_name($stmt, ':parpkconfcodigo', $pk_conf_cod, 32);
            oci_bind_by_name($stmt, ':parvalor', $valor_parametro, 300);
            oci_bind_by_name($stmt, ':parrespuesta', $parrespuesta, 32);

            if (!oci_execute($stmt)) {
                $e = oci_error($stmt);
                log_info($this->logHeader . $this->errorGeneral . ' prcactualizaconfcomfiar' . e);
            }
            if ($parrespuesta == 1) {
                log_info($this->logHeader . $this->finFuncion . 'Consumo -update_data_sesion_comfiar- correcto !!');
            }
        } else {
            $parrespuesta = 405;
            log_info($this->logHeader . $this->errorGeneral . 'DATOS NULL EN  update_data_sesion_comfiar:: pk_conf_cod  o valor_parametro');
        }
        return $parrespuesta;
    }

    function verificar_token_comfiar() {
        $venci_ant = $this->facturacionelectronica->retornarValorConfiguracion(33);
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
            $dias_diferencia = $intervalo->format('%d');
            $horas_diferencia = $intervalo->format('%H');
            log_info($this->logHeader . 'HORA = ' . $fecha_ven_tok . ' - ' . $hoy . ' interval = ' . $tiempo_transcurrido);

            //  if ($hora_actual >= $hora_vencimiento_token || $dias_diferencia>0 || $horas_diferencia>0 ) {
            //  if ($fecha_actual > $fecha_entrada) {
            // if ($hora_actual >= $hora_vencimiento_token) {
            if ($fecha_actual >= $fecha_entrada) {
                $nueva_hora = date("H:i:s");
                log_info($this->logHeader . '::ENTRO IF :::' . $hora_vencimiento_token . '>=' . $hora_actual);
                $respuestasesion = $this->facturacionelectronica->iniciar_sesion();
                if (isset($respuestasesion->IniciarSesionResult)) {
                    $sessionId = $respuestasesion->IniciarSesionResult->SesionId;
                    $fechaVenc = $respuestasesion->IniciarSesionResult->FechaVencimiento;
                    $resp_act_hora = $this->update_data_sesion_comfiar(31, $nueva_hora);
                    if ($resp_act_hora != 1)
                        log_info($this->logHeader . $this->errorGeneral . 'Error actualizando hora sesion comfiar::' . $nueva_hora);
                    $resp_act_sesion_id = $this->update_data_sesion_comfiar(32, $sessionId);
                    if ($resp_act_sesion_id != 1)
                        log_info($this->logHeader . $this->errorGeneral . 'Error actualizando sesionID comfiar::' . $sessionId);
                    $resp_act_fecha_ven = $this->update_data_sesion_comfiar(33, $fechaVenc);
                    if ($resp_act_fecha_ven != 1)
                        log_info($this->logHeader . $this->errorGeneral . 'Error actualizando fecha_vencimiento comfiar::' . $resp_act_fecha_ven);
                    log_info($this->logHeader . 'SE ACTUALIZO TOKEN COMFIAR::' . $fechaVenc);
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

}
