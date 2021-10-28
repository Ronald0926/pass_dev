<?php

/**
 * Description of facturaElectronica
 *
 * @author ronald.rosas
 */
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class NotaCredito extends CI_Controller {

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
//        if ($this->session->userdata('entidad') == NULL) {
//            redirect('/');
//        }
    }

    //funcion recibe data post desde procedimiento bd
    public function crear($notaCredito = 0) {

        if (!empty($_POST['NUMERO_NOTA'])) {

            $notaCredito = $_POST['NUMERO_NOTA'];
            log_info($this->logHeader . '::::NOTA_CREDITO_COMFIAR::::INGRESO FUNCION  CREAR ::PK_NOTA_CREDITO::' . $notaCredito);
            //retorna url pdf si la transmision a COMFIAR fue correcta
            $respuestatxNC = $this->transmitirNotaCreditoComfiar($notaCredito);
            $codRespuesta = $respuestatxNC->CodRespuesta;
            $RespuestaMensaje = $respuestatxNC->Respuesta;
            log_info($this->logHeader . '::::RESPUESTA transmitirNotaCreditoComfiar:::: CODRESPUESTA::' . $codRespuesta . ' MsgRESPUESTA:: ' . $RespuestaMensaje);

            $sql = "BEGIN  MODARCCOR.arcpkgactualizaciones.prcpacactualizararchivo (
               pardirfisica=>:pardirfisica,
            partiparchiv=>:partiparchiv,
            parcodestado=>:parcodestado,
            paridpropiet=>:paridpropiet,
            pardatvigenc=>:pardatvigenc,
            parpropietar=>:parpropietar,
            parrespues  =>:parrespues , 
            parcodarch  =>:parcodarch  
            );
            END;";
            $conn = $this->db->conn_id;
            $stmt = oci_parse($conn, $sql);
            $partiparchiv = 15;
            $parcodestado = 5;
            $paridpropiet = 7;
            $pardatvigenc = null;
            $pardirfisica = null;
            $parpropietar = $notaCredito;
            //TIPO NUMBER INPUT
            oci_bind_by_name($stmt, ':pardirfisica', $pardirfisica, 32);
            oci_bind_by_name($stmt, ':partiparchiv', $partiparchiv, 32);
            oci_bind_by_name($stmt, ':parcodestado', $parcodestado, 32);
            oci_bind_by_name($stmt, ':paridpropiet', $paridpropiet, 1000);
            oci_bind_by_name($stmt, ':pardatvigenc', $pardatvigenc, 1000);
            oci_bind_by_name($stmt, ':parpropietar', $parpropietar, 1000);
            oci_bind_by_name($stmt, ':parrespues', $parrespues, 1000);
            oci_bind_by_name($stmt, ':parcodarch', $parcodarch, 1000);
            if (!@oci_execute($stmt)) {
                $e = oci_error($stmt);
                log_info($this->errorGeneral . '::::NOTA_CREDITO_COMFIAR::::error en prcpacactualizararchivo' . $e);
//                var_dump($e);
//                echo 0;
            } else {
                log_info($this->finFuncion . '::::NOTA_CREDITO_COMFIAR::::Comsumo prcpacactualizararchivo Correcto. ::parcodarch::' . $parcodarch);
            }

            $sql = "BEGIN  modarccor.arcpkgactualizaciones.prcpacactulizaubidigital(parcodarchi=>:parcodarchi
                                                         ,parorignomb=> :parorignomb
                                                         ,parurl=> :parurl
                                                         ,parproceso=>:parproceso
                                                         , parrespues=>:parrespues);
                        END;";
            $conn = $this->db->conn_id;
            $stmt = oci_parse($conn, $sql);
            $codigo_archivo = 15;
            $nombre_archivo = 'Nota credito ' . $notaCredito;
            $proceso = null;
            //TIPO NUMBER INPUT
            oci_bind_by_name($stmt, ':parcodarchi', $parcodarch, 32);
            oci_bind_by_name($stmt, ':parorignomb', $nombre_archivo, 50);
            oci_bind_by_name($stmt, ':parurl', $RespuestaMensaje, 500);
            oci_bind_by_name($stmt, ':parproceso', $proceso, 1000);
            oci_bind_by_name($stmt, ':parrespues', $parrespues, 150);
            if (!@oci_execute($stmt)) {
                $e = oci_error($stmt);
                log_info($this->errorGeneral . '::::NOTA_CREDITO_COMFIAR::::error en prcpacactulizaubidigital' . $e);
            } else {
//                echo $parrespues;
                log_info($this->finFuncion . '::::NOTA_CREDITO_COMFIAR::::Comsumo prcpacactulizaubidigital Correcto. ::parrespues::' . $parrespues . '::UrlPdf::' . $RespuestaMensaje);
            }
        } else {
            log_info($this->errorGeneral . '::::NOTA_CREDITO_COMFIAR::::PK_NOTA_CREDITO ES NULL.');
        }

        log_info($this->logHeader . '-' . $this->finFuncion . '::::FIN CREAR NOTA CREDITO : ' . $codRespuesta);
//        $this->output->set_output($respuestatxNC);
        echo $codRespuesta;
    }

    //transmitir nota credito comfiar
    public function transmitirNotaCreditoComfiar($pk_nota_codigo) {
        log_info($this->logHeader . '::::NOTA_CREDITO_COMFIAR::::INGRESO FUNCION  transmitirNotaCreditoComfiar pk_nota_credito= ' . $pk_nota_codigo);
        $respuestaNota = 0;
        if (!empty($pk_nota_codigo)) {
            $respuesta = $this->facturacionelectronica->iniciar_sesion();
            if (isset($respuesta->IniciarSesionResult)) {
                $sessionId = $respuesta->IniciarSesionResult->SesionId;
                $fechaVenc = $respuesta->IniciarSesionResult->FechaVencimiento;

                $respuestaNota = $this->ejecutarNcComfiar($pk_nota_codigo, $sessionId, $fechaVenc);

                log_info($this->finFuncion . '::RESPUESTA ejecutarNcComfiar EN transmitirNotaCreditoComfiar:: ' . $respuestaNota->CodRespuesta . '--' . $respuestaNota->Respuesta);
            }
        } else {
            log_info($this->errorGeneral . ' ERROR -transmitirNotaCreditoComfiar- ::PK_NOTA_CREDITO ES NULL::');
        }
        return $respuestaNota;
    }

    public function ejecutarNcComfiar($pk_nota_codigo, $sessionId, $venciSesion) {
        log_info($this->logHeader . 'INGRESO ejecutarNcComfiar:: pk_nota_credito= ' . $pk_nota_codigo);

        //cargo la librería f
        //acturacion
        $this->load->library('facturacionelectronica');
        if (!empty($pk_nota_codigo) && isset($sessionId) && isset($venciSesion)) {
            $codRespuesta = 0;
            $mensajeRespuesta = '';

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
                    while ($intentos <= 3 && $estado_Salida_Transaccion != 'Transacción Exitosa') {
                        sleep(20);
                        $tipCom = 2; //1-factura, 2 Nota credito
                        $resultRespuestaCom = $this->facturacionelectronica->respuesta_Comprobante($sessionId, $venciSesion, $pk_nota_codigo, $tipCom);
                        $respuestaComprobanteresult = new SimpleXMLElement($resultRespuestaCom->RespuestaComprobanteResult);
                        log_info($this->logHeader . 'NOTA_CREDITO::respuestaComprobanteresult::' . json_encode($respuestaComprobanteresult));
                        log_info($this->logHeader . '::::NOTA_CREDITO_COMFIAR:::: ID Transaccion: ' . $IdtxComfiar . ' Intento No:' . $intentos . ' - ' . 'Estado transacción DIAN: ' . $respuestaComprobanteresult->Transaccion->Estado);


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

                                $mensajeRespuesta = $urlPdfNota;
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
                    $codRespuesta = 1002;
                    $mensajeRespuesta = 'Error autorizando comprobante';
                    log_info($this->errorComfiar . 'Error autorizando comprobante');

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
            $mensajeRespuesta = 'Error datos  nullos (pk_nota_codigo, sessionId, venciSesion)';
            $codRespuesta = $error;
        }
        $objectRespuesta = (object) [
                    'CodRespuesta' => $codRespuesta,
                    'Respuesta' => $mensajeRespuesta];
        return $objectRespuesta;
    }

    public function descargaPdfComfiar($idTxComfiar = null, $nroComprobante = null, $sessionId = null, $fechaVenc = null, $tipComp = null) {
        log_info($this->logHeader . 'INGRESO CONTROLADOR NOTA CREDITO GENERAR PDF');
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

}
