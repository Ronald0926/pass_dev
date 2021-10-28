<?php

session_start();
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Abonos extends CI_Controller {

    public $iniciLog = '[INFO] ';
    public $logHeader = 'APOLOINFO::::::::: ';
    public $postData = 'POSTDATA::::::::: ';
    public $queryData = 'QUERYDATA::::::: ';
    public $finFuncion = ' FIN PROCEDIMIENTO::::::: ';

    public function __construct() {
        parent::__construct();

        try {
            $this->load->helper('log4php');
        } catch (Exception $ex) {
            
        }
        //if ($this->session->userdata('entidad') == NULL) {
        if ($_SESSION['entidad'] == NULL) {
            redirect('/');
        }
    }

    public function __destruct() {
        $this->db->close();
    }

    public function verificarPerfilCo() {
        //$rol = $this->session->userdata("rol");
        $rol = $_SESSION['rol'];
        if (($rol != 45) and ( $rol != 47)) {
            redirect('/portal/principal/pantalla');
        }
    }

    public function unoAUno($pantalla = 0, $pedidoAbono = 0, $error = 0) {
        log_info($this->logHeader . 'INGRESO UNO A UNO');
        $this->verificarPerfilCo();
        //$empresa = $this->session->userdata("entidad");
        $empresa = $_SESSION['entidad'];
        $data['empresa'] = $empresa['NOMBREEMPRESA'];
        //$usuario = $this->session->userdata("usuario");
        $usuario = $_SESSION['usuario'];
        $data['usuario'] = $usuario['NOMBRE'] . " " . $usuario['APELLIDO'];
        $post = $this->input->post();
        //$pedidoAbono = $this->session->userdata("pedidoAbono");
        $pedidoAbono = $_SESSION['pedidoAbono'];
        //$llavesTemp = $this->session->userdata("llavesTemp");
        $llavesTemp = $_SESSION['llavesTemp'];

        //unset($post['DataTables_Table_0_length']);
        if ($post) {
            log_info($this->logHeader . 'INGRESO UNO A UNO POST');
            //var_dump($post);

            if ($post['referidosestado']) {
                $llavesTemp = $post['referidosestado'];
            } else {
                $llavesTemp = "";
            }
            $pedidoAbono = explode(',', $llavesTemp);
            $inicio = substr($llavesTemp, 0, 1);
            if ($inicio == ',') {
                $llavesTemp = substr($llavesTemp, 1);
            }
//            $this->session->set_userdata(array("pedidoAbono" => $pedidoAbono));
            $this->session->set_userdata(array("llavesTemp" => $llavesTemp));
            $this->session->set_userdata(array("llavesTemp2" => $pedidoAbono));

            IF ($llavesTemp) {
                $tarjetaHabiente = $this->db->query("
            select NVL(ent.razon_social,ent.nombre ||' '||ent.apellido) NOMTAR,
            ENT.PK_ENT_CODIGO ENTTAH,
            tipdoc.abreviacion ABR, 
            ent.documento DOC, 
            pro.nombre_producto NOMPRO,pro.pk_produc_codigo,
            cue.pk_tartblcuenta_codigo CUENTA,
            cue.IDENTIFICADOR
            from  modtarhab.tartblcuenta cue
            JOIN modcliuni.clitblentida ent
            on  cue.PK_ENT_CODIGO_TH=ent.pk_ent_codigo
            and cue.pk_tartblcuenta_codigo = some ({$llavesTemp})
            join modcliuni.clitbltipdoc tipdoc on tipdoc.pk_td_codigo = ent.clitbltipdoc_pk_td_codigo
            join modproduc.protblproduc pro on pro.pk_produc_codigo = cue.pk_produc_codigo
            order by 1,4 asc");
            }
            if (isset($post['pksolicitudPrepepdido'])) {
                $data['pkcodSol'] = $post['pksolicitudPrepepdido'];
            }
        } else {
//            $pedidoAbono = null;
//            $this->session->set_userdata(array("llavesTemp2" => $pedidoAbono)); 
        }
        $data['tarjetaHabiente'] = $tarjetaHabiente->result_array;
        //$ultimaconexion = $this->session->userdata("ultimaconexion");
        $ultimaconexion = $_SESSION['ultimaconexion'];
        $data['ultimaconexion'] = $ultimaconexion;
        $data['menu'] = "abonos";
        $data['error'] = $error;
        $this->load->view('portal/templates/header2', $data);
        $this->load->view('portal/abonos/unoAUno', $data);
        $this->load->view('portal/templates/footer', $data);
    }

    public function insertUnoAUno($pantalla = 0) {
        log_info($this->logHeader . 'INGRESO INSERT UNO A UNO');
        $this->verificarPerfilCo();
        $post = $this->input->post();
        //$empresa = $this->session->userdata("entidad");
        //$pedidoAbono = $this->session->userdata("pedidoAbono");
        $empresa = $_SESSION['entidad'];
        $pedidoAbono = $_SESSION['pedidoAbono'];
        unset($post['DataTables_Table_0_length']);
        //  var_dump($post);
        // exit();
        //unset($post['DataTables_Table_0_length']);
        if ($post && isset($post['solabono']) == 'solabono') {
            $pedidoAbono = null;
        } elseif ($post) {
            unset($post['DataTables_Table_0_length']);
            log_info($this->logHeader . 'INSERT UNO A UNO VALIDACION DE CAMPAÑA ACTIVA');
            //$parcampana = $this->session->userdata("campana");
            //$parempresa = $this->session->userdata("pkentidad");
            $parcampana = $_SESSION['campana'];
            $parempresa = $_SESSION['pkentidad'];
            $sqlcotizacion = $this->db->query("Select co.pk_cotiza_codigo
                FROM MODCOMERC.COMTBLCOTIZA co
                INNER JOIN MODCOMERC.COMTBLPROCES pr
                    ON pr.PK_COTIZA_CODIGO = co.pk_cotiza_codigo
                INNER JOIN MODCOMERC.COMTBLPARAME pa
                    ON pa.pk_proces_codigo = pr.pk_proces_codigo
            WHERE pr.pk_estado_codigo=1
                AND co.pk_estado_codigo=1
                AND  PK_ENTIDA_CLIENTE =  $parempresa
                AND co.pk_campana_codigo=$parcampana");

            $cotizacion = $sqlcotizacion->result_array;
            


            if (!empty($cotizacion)) {
                log_info($this->logHeader . 'LA COTIZACION ESTA ACTIVA SE PROCESA LA SOLICITUD DE ABONOS');
                if (empty($post['pksolicitudPrepepdido'])) {
                    //CREA LA solicitud DEL PREPEDIDO
                    $sql = "BEGIN MODPREPEDIDO.MODPREPEDIDOPKGFUNCIONES.prccrearactusolicitud(
                    parnombresolicitud =>:parnombresolicitud,
                    parpkpreorden =>:parpkpreorden,
                    parpktiposolicitud=>:parpktiposolicitud,
                    parpkempresa=>:parpkempresa,
                    parpkcoordinador=>:parpkcoordinador,
                    parcampana=>:parcampana,
                    parusuariocreacion=>:parusuariocreacion,
                    parpkcodigosolicitud=>:parpkcodigosolicitud,
                    parmensajerespuesta=>:parmensajerespuesta,
                    parrespuesta=>:parrespuesta);
                    END;";

                    $conn = $this->db->conn_id;
                    $stmt = oci_parse($conn, $sql);
                    //$usuario = $this->session->userdata("usuario");
                    $usuario = $_SESSION['usuario'];
                    $parusuario = $usuario['USUARIO_ACCESO'];
                    $coordinador = $usuario["PK_ENT_CODIGO"];
//                    $pkEmpresa = $this->session->userdata("pkentidad");
//                    $parcampana = $this->session->userdata("campana");
                    $pkEmpresa = $_SESSION['pkentidad'];
                    $parcampana = $_SESSION['campana'];
                    $parnombresolicitud;
                    $parpkpreorden;
                    $parpktiposolicitud = 3; //solicitud abono uno a uno
                    oci_bind_by_name($stmt, ':parnombresolicitud', $parnombresolicitud, 32);
                    oci_bind_by_name($stmt, ':parpkpreorden', $parpkpreorden, 32);
                    oci_bind_by_name($stmt, ':parpktiposolicitud', $parpktiposolicitud, 32);
                    oci_bind_by_name($stmt, ':parpkempresa', $pkEmpresa, 32);
                    oci_bind_by_name($stmt, ':parpkcoordinador', $coordinador, 32);
                    oci_bind_by_name($stmt, ':parcampana', $parcampana, 32);
                    oci_bind_by_name($stmt, ':parusuariocreacion', $parusuario, 32);
                    oci_bind_by_name($stmt, ':parpkcodigosolicitud', $parpkcodigosolicitud, 32);
                    oci_bind_by_name($stmt, ':parmensajerespuesta', $parmensajerespuesta, 500);
                    oci_bind_by_name($stmt, ':parrespuesta', $parrespuesta, 32);

                    if (!oci_execute($stmt)) {
                        $e = oci_error($stmt);
                        VAR_DUMP($e);
                        log_info($this->logHeader . 'ERROR PROCESANDO LA SOLICITUD DE ABONOS PREPEDIDO ' . $e['message']);
                    } else if ($parrespuesta != 1) {
                        log_info($this->logHeader . 'ERROR PROCESANDO LA SOLICITUD DE ABONOS PREPEDIDO ' . $parrespuesta . 'MENSAJE' . $parmensajerespuesta);

                        var_dump($parrespuesta);
                        echo '<br/>';
                        var_dump($pkEmpresa);
                        exit();
                        //lo saca y monta la vista con el error
                    }
                    if ($parrespuesta == 1) {
                        log_info($this->logHeader . ' RESPUESTA CREAR SOLICITUD ABONO UNO A UNO '
                                . ' parrespuest ' . $parrespuesta
                                . ' parmensajerespuesta ' . $parmensajerespuesta
                                . ' parpkcodigosolicitud ' . $parpkcodigosolicitud);
                        $parpkcodigosolicitud = $parpkcodigosolicitud;
                    }
                } else {
                    log_info($this->logHeader . 'INGRESA UNA PKCODSOLICITUD ADICIONAR ABONO A SOLICITUD'
                            . ' parpkcodigosolicitud ' . $post['pksolicitudPrepepdido']);
                    $parpkcodigosolicitud = $post['pksolicitudPrepepdido'];
                }

                foreach ($post as $key => $value) {
                    //DIVIDE EL REGISTRO

                    $regdivido = explode("/", $key, 4);
                    //$regdivido2 = explode("/", $regdivido[2],2);
                    
                    //$enttar = $regdivido2[0];
                    //CREA UN REGISTRO DE CADA UNO
                    $datosth = array(
                        "ENTTAR" => $regdivido[2],
                        "MONTO" => "",
                        "FECDIS" => "",
                        "CUE" => $regdivido[1]
                    );
                    $llave = $regdivido[2] . $regdivido[1] . $regdivido[3];
                    //var_dump($regdivido[3]);
                    if ($regdivido[0] == 'monto' || $regdivido[0] == 'fecha') {
                        
                        if (!array_key_exists($llave, $contenido)) {
                            
                            if ($regdivido[0] == 'monto') {
                                $porciones = explode(".", $value);
                                $monto = $this->dejarSoloCaracteresDeseados($porciones[0], "0123456789");
                                $datosth['MONTO'] = $monto;
                            } elseif ($regdivido[0] == 'fecha') {
                                $datosth['FECDIS'] = $value;
                            }
                            $contenido[$llave] = $datosth;
                        } else {
                            if ($regdivido[0] == 'monto') {
                                $porciones = explode(".", $value);
                                $monto = $this->dejarSoloCaracteresDeseados($porciones[0], "0123456789");
                                $contenido[$llave]['MONTO'] = $monto;
                            } elseif ($regdivido[0] == 'fecha') {
                                $contenido[$llave]['FECDIS'] = $value;
                            }
                        }
                    }
                }
                //var_dump($contenido);
                //die();
                
                foreach ($contenido as $key => $value) {
                    //llamada procedimiento crear detalle pedido abono
                    $sql = "BEGIN MODPREPEDIDO.MODPREPEDIDOPKGFUNCIONES.PRCREGISTRODETSOLABONO(
                    parpktarhab=>:parpktarhab,
                    parpkempresa=>:parpkempresa,
                    parpkcodigosolicitud=>:parpkcodigosolicitud,
                    parmonto=>:parmonto,
                    parcuentatar=>:parcuentatar,
                    parfechadispersar=>:parfechadispersar,
                    parmensajerespuesta=>:parmensajerespuesta,
                    parcodrespuesta=>:parcodrespuesta);
                    END;";

                    $conn = $this->db->conn_id;
                    $stmt = oci_parse($conn, $sql);
                    $partarhab = $value['ENTTAR'];
                    oci_bind_by_name($stmt, ':parpktarhab', $partarhab, 32);
                    //$pkEmpresa = $this->session->userdata("pkentidad");
                    $pkEmpresa = $_SESSION['pkentidad'];
                    oci_bind_by_name($stmt, ':parpkempresa', $pkEmpresa, 32);
                    $parcodsol = $parpkcodigosolicitud;
                    oci_bind_by_name($stmt, ':parpkcodigosolicitud', $parcodsol, 32);
                    $parmonto = $value['MONTO'];
                    oci_bind_by_name($stmt, ':parmonto', $parmonto, 32);
                    $parcuenta = $value['CUE'];
                    oci_bind_by_name($stmt, ':parcuentatar', $parcuenta, 32);
                    $parfechadispersar = date_format(date_create($value['FECDIS']), 'd-M-Y');
                    oci_bind_by_name($stmt, ':parfechadispersar', $parfechadispersar, 32);
                    oci_bind_by_name($stmt, ':parmensajerespuesta', $parmensajerespuesta, 500);
                    oci_bind_by_name($stmt, ':parcodrespuesta', $parcodrespuesta, 32);
                    log_info($this->logHeader . 'CREANCION DETALLE PREPEDIDO ABONO UNO A UNO '
                            . ' partarhab ' . $partarhab
                            . ' parpkempresa ' . $pkEmpresa
                            . ' parpkcodigosolicitud ' . $parcodsol
                            . ' parmonto ' . $parmonto
                            . ' parcuentatar ' . $parcuenta
                            . ' parfechadispersar ' . $parfechadispersar);
                    if (!oci_execute($stmt)) {
                        $e = oci_error($stmt);
                        VAR_DUMP($e);
                        log_info($this->logHeader . 'ERROR PROCESANDO LA SOLICITUD DE REGISTRO DE ABONO INDIVIDUAL ' . $e['message']);
                        //exit;
                    } else if ($parcodrespuesta != 1) {
                        $PARRESPUE = 'ERROR ' . $parcodrespuesta;
                        //lo saca y monta la vista con el error
                    }
                }

                $parcodpedido = $parcodsol;
                if ($parcodrespuesta != 1) {
                    $parpkcodigosolicitud = null;
                    $this->session->set_userdata(array("pedidoAbono" => $parpkcodigosolicitud));
                    $parrespue = 'ERROR ' . $parrespue;
                    //lo saca y monta la vista con el error
                } else {
                    log_info($this->logHeader . ' RESPUESTA CREANCION DETALLE PREPEDIDO ABONO PRCREGISTRODETSOLABONO'
                            . ' parrespuesta ' . $parcodrespuesta
                            . ' parmensajerespuesta ' . $parmensajerespuesta);
                    log_info($this->logHeader . 'PREPEDIDO DE ABONO UNO A UNO PROCESADO ');
                    $data['pkcodsolicitud'] = $parpkcodigosolicitud;
                    $data['solAbonoOK'] = $parcodrespuesta;

                    $parpkcodigosolicitud = null;
                    $this->session->set_userdata(array("pedidoAbono" => $parpkcodigosolicitud));
                    $this->session->set_userdata(array("llavesTemp" => $parpkcodigosolicitud));


                    if (!empty($post['pksolicitudPrepepdido'])) {
                        $pkcodsolicitud = $post['pksolicitudPrepepdido'];
                        redirect("portal/solicitudGestion/editarAbono/$pkcodsolicitud?add=OK");
                    }
//                    redirect("portal/abonos/unoAUno/$parpkcodigosolicitud/1");
//                    redirect('portal/ordenPedido/lista/0/1');
                }
                //para colocar nombre solicitud
                $data['codord'] = $parcodpedido;
            }
            //echo($llavesTemp.' hola2');
            //    exit();

            $data['error'] = $parrespue;
            $pedidoAbono = $parcodpedido;
            $this->session->set_userdata(array("pedidoAbono" => $pedidoAbono));

            if ($pedidoAbono) {
                foreach ($pedidoAbono as $key => $value) {
                    $llavesTemp = $llavesTemp . "'" . $value . "'" . ',';
                }
                $llavesTemp = substr($llavesTemp, 0, -1);
            } else {
                $llavesTemp = "'0'";
            }
        } else {
            $data['errocotizacion'] = 1;
            redirect('/portal/abonos/unoAUno?error');
            //retornar vista anterior con error cotizacion no activa
        }


        //$ultimaconexion = $this->session->userdata("ultimaconexion");
        $ultimaconexion = $_SESSION['ultimaconexion'];
        $data['ultimaconexion'] = $ultimaconexion;
        $data['menu'] = "abonos";

        $this->load->view('portal/templates/header2', $data);
        $this->load->view('portal/abonos/unoAUno', $data);
        $this->load->view('portal/templates/footer', $data);
    }

    public function nombraOrden($pantalla = 0) {
        $this->verificarPerfilCo();
        $post = $this->input->post();

        if ($post) {
            $sql = "BEGIN
                        MODGENERI.GENPKGWEBSERVICE.PRCACTUALIZANOMBREORDEN (
                            :parcodigoorden,
                            :parnombre,
                            :parresultado
                        );
                    END;";
            $conn = $this->db->conn_id;
            $stmt = oci_parse($conn, $sql);
            $parcodigoorden = $post['codord'];
            //var_dump('codigo pedido '.$PARCODPEDIDO);
            oci_bind_by_name($stmt, ':parcodigoorden', $parcodigoorden, 32);
            $parnombre = $post['nomord'];
            //var_dump('respuesta '.$PARRESPUESTA);
            oci_bind_by_name($stmt, ':parnombre', $parnombre, 32);
            $parresultado;
            //var_dump('cliente '.$PARCLIENTE);
            oci_bind_by_name($stmt, ':parresultado', $parresultado, 32);

            if (!oci_execute($stmt)) {

                $e = oci_error($stmt);
                $mensaje = explode(":", $e['message']);
                var_dump($mensaje);
                // exit;
            } else if ($parresultado != 1) {
                $parresultado = 'ERROR ' . $parresultado;
                //lo saca y monta la vista con el error
                $this->load->view('portal/templates/header2', $data);
                $this->load->view('portal/abonos/unoAUno', $data);
                $this->load->view('portal/templates/footer', $data);
                return;
            }
        }


        if ($post['solabono']) {
            $pedidoAbono = null;
            $this->session->set_userdata(array("pedidoAbono" => $pedidoAbono));
            redirect('/portal/abonos/unoAUno');
        } else if ($post['solorden']) {
            $pedidoAbono = null;
            $this->session->set_userdata(array("pedidoAbono" => $pedidoAbono));
            redirect('/portal/ordenPedido/lista');
        }
    }

    public function abonoMasivo($pantalla = 0) {
        $this->verificarPerfilCo();
        $post = $this->input->post();
        log_info($this->logHeader . ' INGRESA A METODO ABONO MASIVO  ');
        if ($post) {
            log_info($this->logHeader . 'SE ENVIO POST ABONO MASIVO ');
        }
        log_info($this->logHeader . ' SE CARGA LA VENTANA DE ABONO MASIVOS PREVIOS ');
//        $empresa = $this->session->userdata("entidad");
//        $campana = $this->session->userdata("campana");
        $empresa = $_SESSION['entidad'];
        $campana = $_SESSION['campana'];
        $abonosMasivos = $this->db->query("SELECT NOMBRE_PEDIDO,FECHA_CREACION,URL_PLANTILLA,PK_PEDABON_CODIGO 
                                            FROM MODPROPAG.PPATBLPEDABON
                                            WHERE URL_PLANTILLA IS NOT NULL
                                             AND PK_CLIENTE={$empresa['PK_ENT_CODIGO']}"
                . "AND PK_CAMPAN_CODIGO={$campana}");
        $data['empresa'] = $empresa['NOMBREEMPRESA'];
        //$usuario = $this->session->userdata("usuario");
        $usuario = $_SESSION['usuario'];
        $data['usuario'] = $usuario['NOMBRE'] . " " . $usuario['APELLIDO'];
        $data['abonos'] = $abonosMasivos->result_array;
        $data['menu'] = "abonos";
        $this->load->view('portal/templates/header2', $data);
        $this->load->view('portal/abonos/abonoMasivo', $data);
        $this->load->view('portal/templates/footer', $data);
    }

    public function abonoPlantilla($pantalla = 0) {
        log_info($this->logHeader . 'INGRESA A ABONO POR PLANTILLA, CARGA EL DOCUMENTO ');
        $filePath = 'uploads/pedidosabono/';
        $this->verificarPerfilCo();
        $post = $this->input->post();
        if ($post || $_FILES) {
            log_info($this->logHeader . ' SE RECIVE EL ARCHIVO ');
            //$parcampana = $this->session->userdata("campana");
            //$parempresa = $this->session->userdata("pkentidad");
            $parcampana = $_SESSION['campana'];
            $parempresa = $_SESSION['pkentidad'];
            log_info($this->logHeader . ' SE VALIDA LA EXISTENCIA DE LA COTIZACION ACTIVA');
            $sqlcotizacion = $this->db->query("Select co.pk_cotiza_codigo
                FROM MODCOMERC.COMTBLCOTIZA co
                INNER JOIN MODCOMERC.COMTBLPROCES pr
                    ON pr.PK_COTIZA_CODIGO = co.pk_cotiza_codigo
                INNER JOIN MODCOMERC.COMTBLPARAME pa
                    ON pa.pk_proces_codigo = pr.pk_proces_codigo
            WHERE pr.pk_estado_codigo=1
                AND co.pk_estado_codigo=1
                AND  PK_ENTIDA_CLIENTE =  $parempresa
                AND co.pk_campana_codigo=$parcampana");
            $cotizacion = $sqlcotizacion->result_array;

            log_info($this->logHeader . ' VALOR DE LA COTIZACION ' . $cotizacion[0]);
            if (!empty($cotizacion)) {
                $url = "";
                log_info($this->logHeader . ' SE VA A CARGA EL ARCHIVO DE ABONO ' . $cotizacion[0]);
                if ($_FILES['file']['name'] != "") {
                    ini_set('post_max_size', '12M');
                    ini_set('upload_max_filesize', '12M');
                    header('Access-Control-Allow-Origin: *');
                    date_default_timezone_set('America/Los_Angeles');

                    $dir = 'uploads/pedidosAbono/';
                    $date = date('Y-m-d-H-i-s');
                    $random = rand(1000, 9999);
                    $split_name_file = explode('.', basename($_FILES['file']['name']));
                    $extention = end($split_name_file);
                    $name = strtolower($date . '-' . $random . '.' . $extention);
                    $file_dir = $dir . $name;
                    $url = $dominio . '/' . $dir . $name;
                    $BLOB_CONTENT = file_get_contents($_FILES['file']['tmp_name']);
                    $temp_file = $_FILES['file']['tmp_name'];
                    $result = move_uploaded_file($temp_file, $file_dir);
                    log_info($this->logHeader . ' RESULTADO CARGUE DE ARCHIVO ' . $result);
                    if ($result === 1) {
                        log_info($this->logHeader . ' NO SE CARGO ARCHIVO DE ABONOS A UPLOADS');
                        redirect("/portal/abonos/abonoMasivo?error");
                    } else {
                        $url = '/' . $filePath . $file['file_name'];
                        log_info($this->logHeader . ' SE CARGO ARCHIVO DE ABONOS A UPLOADS' . $url);
                    }
                }
                /*                 * *******Inicia Carga de Archivo************ */
                log_info($this->logHeader . ' INICIA CARGO DE ARCHIVO A BD PARA PROCESAMIENTO');
                $sql = "INSERT INTO FLOWS_FILES.WWV_FLOW_FILE_OBJECTS$ (FLOW_ID, NAME,BLOB_CONTENT, DELETED_AS_OF) 
                     VALUES(102,'{$name}', empty_blob(),sysdate+5)"
                        . " RETURNING BLOB_CONTENT INTO :BLOB_CONTENT";
                $connection = $this->db->conn_id;
                $stmt = oci_parse($connection, $sql);
                $blob = oci_new_descriptor($connection, OCI_D_LOB);
                oci_bind_by_name($stmt, ":BLOB_CONTENT", $blob, -1, OCI_B_BLOB);
                if (!@oci_execute($stmt, OCI_NO_AUTO_COMMIT)) {
                    $e = oci_error($stmt);
                    $mensaje = explode(":", $e['message']);
                    var_dump($mensaje);
                    $data['error'] = 4;
                    $data['mensaje'] = substr($mensaje[2], 0, 44);
                    echo $sql;
                    echo $name;
                    log_info($this->logHeader . ' NO SE CARGO ARCHIVO DE ABONOS A BD ' . $name . $mensaje);
                }

                // oci_execute($result, OCI_DEFAULT) or die("Unable to execute query");
                if (!$blob->save($BLOB_CONTENT)) {
                    oci_rollback($connection);
                    log_info($this->logHeader . 'ROLLBACK CARGO ARCHIVO DE ABONOS A BD ' . $name);
                } else {
                    oci_commit($connection);
                    log_info($this->logHeader . 'SE CARGO ARCHIVO DE ABONOS A BD ' . $name);
                }
                oci_free_statement($stmt);
                $blob->free();
                /*                 * IDENTIFICADOR DE ABONO MENSUAL* */
                //$parempresa = $this->session->userdata("pkentidad");
                $parempresa = $_SESSION['pkentidad'];
                $abono = $this->db->query("SELECT DISTINCT p.NOMBRE_PRODUCTO
                                          ,pa.PK_PRODUCTO_CODIGO 
                                  FROM 
                                  MODCOMERC.COMTBLCOTIZA co
                        INNER JOIN MODCOMERC.COMTBLPROCES pr ON pr.PK_COTIZA_CODIGO = co.pk_cotiza_codigo
                        INNER JOIN MODCOMERC.COMTBLESTADO esp ON pr.pk_estado_codigo = esp.pk_estado_codigo
                        INNER JOIN MODCOMERC.COMTBLESTADO esc ON co.pk_estado_codigo = esc.pk_estado_codigo
                        INNER JOIN MODCOMERC.COMTBLPARAME pa ON pa.pk_proces_codigo = pr.pk_proces_codigo
                        INNER JOIN MODPRODUC.PROTBLPRODUC p ON p.PK_PRODUC_CODIGO = pa.PK_PRODUCTO_CODIGO 
                        INNER JOIN MODPRODUC.protbltippro tp 
                            ON p.pk_tippro_codigo = tp.pk_tippro_codigo 
                            AND tp.pk_tippro_codigo = 2
                                WHERE 
                                    esp.pk_estado_codigo  = 1
                                    AND  esc.pk_estado_codigo  = 1
                                    AND  PK_ENTIDA_CLIENTE =$parempresa");
                $codabono = $abono->result_array[0]['PK_PRODUCTO_CODIGO'];

                /*                 * ********Termina carga de archivo para recorrido*********** */
                log_info($this->logHeader . 'INICIA PROCESAMIENTO DE ARCHIVO ABONO' . $name);
                /* $sql = "BEGIN MODGENERI.genpkgfunciones.prccargapedidoabono(
                  :PARNOMARCH ,
                  :PARENTIDAD ,
                  :PARUSUARIO ,
                  :PARCODABON ,
                  :PARCODORD,
                  :PARCAMPAN,
                  :PARRESP,
                  :PARMENSAJE,
                  :PARURL,
                  :PARNOMBREPEDIDO,
                  :PARFECHADIS,
                  :PARCOORDINADOR);END;"; */
                $sql = "BEGIN 
                        	MODPREPEDIDO.MODPREPEDIDOPKGFUNCIONES.PRCCARGAPEDIDOABONO(
                                PARNOMARCH 		=>:PARNOMARCH 								
                                ,PARENTIDAD 		=>:PARENTIDAD 								
                                ,PARUSUARIO 		=>:PARUSUARIO 								
                                ,PARCODIGOSOLICITUD     =>:PARCODIGOSOLICITUD 						
                                ,PARCAMPAN 		=>:PARCAMPAN 								
                                ,PARRESPUESTA 		=>:PARRESPUESTA 							
                                ,PARURL 		=>:PARURL 									
                                ,PARNOMBREPEDIDO 	=>:PARNOMBREPEDIDO 							
                                ,PARFECHADIS 		=>:PARFECHADIS 								
                                ,PARCOORDINADOR 	=>:PARCOORDINADOR);
                            END;";
                $conn = $this->db->conn_id;
                $stmt = oci_parse($conn, $sql);

                $pararchivo = $name; //$file['file_name'];
                //$usuario = $this->session->userdata("usuario");
                $usuario = $_SESSION['usuario'];
                $PARCOORDINADOR = $usuario['PK_ENT_CODIGO'];
                $parusuario = $usuario['USUARIO_ACCESO'];
                //$parcampana = $this->session->userdata("campana");
                $parcampana = $_SESSION['campana'];
                $parurl = $url;

                $parnombrepedido = $post['nombrePedido'];
                //TIPO NUMBER INPUT INPUT
                oci_bind_by_name($stmt, ':PARNOMARCH', $pararchivo, 100);
                //TIPO VARCHAR2 INPUT
                oci_bind_by_name($stmt, ':PARENTIDAD', $parempresa, 100);
                //TIPO VARCHAR2 INPUT
                oci_bind_by_name($stmt, ':PARUSUARIO', $parusuario, 100);
                // TIPO NUMBER CODIGO TIPO ABONO 
                oci_bind_by_name($stmt, ':PARCODIGOSOLICITUD', $parcodigosolicitud, 100);
                // TIPO NUMBER CODIGO TIPO ABONO 
                oci_bind_by_name($stmt, ':PARCAMPAN', $parcampana, 100);
                // TIPO NUMBER OUT RESPUESA
                oci_bind_by_name($stmt, ':PARRESPUESTA', $parrespuesta, 100);
                // TIPO NUMBER OUT MENSAJE  
                oci_bind_by_name($stmt, ':PARURL', $parurl, 100);
                // TIPO NUMBER IN PARNOMBREPEDIDO 
                oci_bind_by_name($stmt, ':PARNOMBREPEDIDO', $parnombrepedido, 100);
                // TIPO NUMBER IN FECHA DISPERSION
                oci_bind_by_name($stmt, ':PARFECHADIS', $fechadispersion, 100);
                oci_bind_by_name($stmt, ':PARCOORDINADOR', $PARCOORDINADOR, 100);


                if (!oci_execute($stmt)) {
                    $e = oci_error($stmt);
                    VAR_DUMP($e);
                    log_info($this->logHeader . ' ERROR PROCESANDO EL ARCHIVO DE ABONOS ' . $e['message']);
                }

                if ($parrespuesta == 1) {
                    log_info($this->logHeader . ' ARCHIVO PROCESADO CORRECTAMENTE ');
                    redirect("/portal/abonos/abonoMasivo?ok&c=$parcodigosolicitud");
                } else {
                    log_info($this->logHeader . ' SE PROCESO EL ARCHIVO, SE ENCONTRARON ERRORES ' . $parrespuesta);
                    $this->mensajeError($pararchivo);

//                redirect("portal/abonos/mensajeError/$parrespuesta/$pararchivo");
                    // exit();
                    //redirect("/portal/abonos/abonoMasivo?error");
                }
            } else {
                log_info($this->logHeader . ' COTIZACION VACIA ' . $parmensaje);
                redirect('portal/abonos/abonoMasivo?2');
            }
        } else {
            log_info($this->logHeader . ' POST NULL  FILES NULL' . $parmensaje);
            redirect('portal/abonos/abonoMasivo?3');
        }
    }

    public function listaUnoAUno($pantalla = 0) {
        $this->verificarPerfilCo();
        //$empresa = $this->session->userdata("entidad");
        //$parcampana = $this->session->userdata("campana");
        $empresa = $_SESSION['entidad'];
        $parcampana = $_SESSION['campana'];


        /*echo "
        select 
          nvl(ent.razon_social,ent.nombre ||' '||ent.apellido) NOMTAR,
          tipdoc.abreviacion ABR, 
          ent.documento DOC, 
          pro.nombre_producto NOMPRO,
          cue.pk_tartblcuenta_codigo CUENTA,
          cue.identificador
          from modcliuni.clitblentida ent
          join modcliuni.clitblvincul vin 
          on vin.clitblentida_pk_ent_codigo = ent.pk_ent_codigo
           and (vin.fecha_fin is null or vin.fecha_fin <= sysdate)
           and vin.clitblentida_pk_ent_codigo1 = {$empresa['PK_ENT_CODIGO']}
           and vin.clitbltipvin_pk_tipvin_codigo = 48
          join modcliuni.clitbltipdoc tipdoc on tipdoc.pk_td_codigo = ent.clitbltipdoc_pk_td_codigo
          join modtarhab.tartblcuenta cue on cue.pk_ent_codigo_th = ent.pk_ent_codigo
         
          join modproduc.protblproduc pro on pro.pk_produc_codigo = cue.pk_produc_codigo
           
          UNION
          select 
          nvl(ent.razon_social,ent.nombre ||' '||ent.apellido) NOMTAR,
          tipdoc.abreviacion ABR, 
          ent.documento DOC, 
          pro.nombre_producto NOMPRO,
          cue.pk_tartblcuenta_codigo CUENTA,
          cue.identificador
          from modcliuni.clitblentida ent
          join modcliuni.clitbltipdoc tipdoc on tipdoc.pk_td_codigo = ent.clitbltipdoc_pk_td_codigo
          join modtarhab.tartblcuenta cue on cue.pk_ent_codigo_th = ent.pk_ent_codigo
          join modproduc.protblproduc pro on pro.pk_produc_codigo = cue.pk_produc_codigo
          join modtarhab.tartblcompartirtarjeta compar on compar.pk_entidad_th=ent.pk_ent_codigo  
          join modcomerc.comtblcotiza cotizacion on cotizacion.pk_entida_cliente=compar.pk_entidad_destino
          join modcomerc.comtblproces proceso ON proceso.pk_cotiza_codigo = cotizacion.pk_cotiza_codigo
          and proceso.pk_estado_codigo = 1
          and cotizacion.pk_estado_codigo = 1 
          join modcomerc.comtblparame parametro 
          ON parametro.pk_proces_codigo = proceso.pk_proces_codigo 
          and parametro.PK_PRODUCTO_CODIGO =pro.pk_produc_codigo 
          and pro.pk_tippro_codigo=1
          where  compar.pk_entidad_destino = {$empresa['PK_ENT_CODIGO']}
          and cotizacion.pk_campana_codigo= $parcampana
          and compar.fecha_fin_compartir is null order by 1,4 asc";
          //die();*/

        $tarjetaHabiente = $this->db->query("
          select 
            nvl(ent.razon_social,ent.nombre ||' '||ent.apellido) NOMTAR,
            tipdoc.abreviacion ABR, 
            ent.documento DOC, 
            pro.nombre_producto NOMPRO,
            cue.pk_tartblcuenta_codigo CUENTA,
            cue.identificador
            from modcliuni.clitblentida ent
            join modcliuni.clitblvincul vin 
            on vin.clitblentida_pk_ent_codigo = ent.pk_ent_codigo
             and (vin.fecha_fin is null or vin.fecha_fin <= sysdate)
             and vin.clitblentida_pk_ent_codigo1 = {$empresa['PK_ENT_CODIGO']}
             and vin.clitbltipvin_pk_tipvin_codigo = 48
            join modcliuni.clitbltipdoc tipdoc on tipdoc.pk_td_codigo = ent.clitbltipdoc_pk_td_codigo
            join modtarhab.tartblcuenta cue on cue.pk_ent_codigo_th = ent.pk_ent_codigo
           
            join modproduc.protblproduc pro on pro.pk_produc_codigo = cue.pk_produc_codigo
             
            UNION
            select 
            nvl(ent.razon_social,ent.nombre ||' '||ent.apellido) NOMTAR,
            tipdoc.abreviacion ABR, 
            ent.documento DOC, 
            pro.nombre_producto NOMPRO,
            cue.pk_tartblcuenta_codigo CUENTA,
            cue.identificador
            from modcliuni.clitblentida ent
            join modcliuni.clitbltipdoc tipdoc on tipdoc.pk_td_codigo = ent.clitbltipdoc_pk_td_codigo
            join modtarhab.tartblcuenta cue on cue.pk_ent_codigo_th = ent.pk_ent_codigo
            join modproduc.protblproduc pro on pro.pk_produc_codigo = cue.pk_produc_codigo
            join modtarhab.tartblcompartirtarjeta compar on compar.pk_entidad_th=ent.pk_ent_codigo  
            join modcomerc.comtblcotiza cotizacion on cotizacion.pk_entida_cliente=compar.pk_entidad_destino
            join modcomerc.comtblproces proceso ON proceso.pk_cotiza_codigo = cotizacion.pk_cotiza_codigo
            and proceso.pk_estado_codigo = 1
            and cotizacion.pk_estado_codigo = 1 
            join modcomerc.comtblparame parametro 
            ON parametro.pk_proces_codigo = proceso.pk_proces_codigo 
            and parametro.PK_PRODUCTO_CODIGO =pro.pk_produc_codigo 
            and pro.pk_tippro_codigo=1
            where  compar.pk_entidad_destino = {$empresa['PK_ENT_CODIGO']}
            and cotizacion.pk_campana_codigo= $parcampana
            and compar.fecha_fin_compartir is null order by 1,4 asc");

        $data['tarjetaHabiente'] = $tarjetaHabiente->result_array;

        //$empresa = $this->session->userdata("entidad");
        $empresa = $_SESSION['entidad'];
        $data['empresa'] = $empresa['NOMBREEMPRESA'];
        //$usuario = $this->session->userdata("usuario");
        $usuario = $_SESSION['usuario'];
        $data['usuario'] = $usuario['NOMBRE'] . " " . $usuario['APELLIDO'];
        $data['menu'] = "abonos";
        //$ultimaconexion = $this->session->userdata("ultimaconexion");
        $ultimaconexion = $_SESSION['ultimaconexion'];
        $data['ultimaconexion'] = $ultimaconexion;
        $this->load->view('portal/templates/header2', $data);
        $this->load->view('portal/abonos/listaUnoAUno', $data);
        $this->load->view('portal/templates/footer', $data);
    }

    public function descargarPlantilla() {
        $this->verificarPerfilCo();
        $path = '/uploads/ARCHIVO-DISPERSION-MASIVA.xlsx';
        header("Location:" . $path);
    }

    public function mensajeError($nombrearchivo) {
        $this->verificarPerfilCo();
        //$empresa = $this->session->userdata("entidad");
        $empresa = $_SESSION['entidad'];
        $data['empresa'] = $empresa['NOMBREEMPRESA'];
        //$usuario = $this->session->userdata("usuario");
        $usuario = $_SESSION['usuario'];
        $data['usuario'] = $usuario['NOMBRE'] . " " . $usuario['APELLIDO'];

        $errores = $this->db->query("SELECT LINEA_ARCHIVO,DATO,DESCRIPCION 
                                FROM MODGENERI.gentblerrcar 
                                WHERE ARCHIVO = '$nombrearchivo'
                                order by LINEA_ARCHIVO");
        $data['errores'] = $errores->result_array;
        $data['menu'] = "abonos";
        $this->load->view('portal/templates/header2', $data);
        $this->load->view('portal/abonos/mensajeError', $data);
        $this->load->view('portal/templates/footer', $data);
    }

    public function abonoSeleccionado($pantalla = 0) {
        $this->verificarPerfilCo();
        $post = $this->input->post();
        if ($post) {
            //$_FILES['file']['name'] != ""

            if ($post['check1']) {

                $str = $post['check1'];
                $nombre = explode('-', $str, 2);
                $parnombrepedido = $nombre[0];
                $file = $nombre[1];

                $BLOB_CONTENT = file_get_contents('.' . $file);
                $sql = "INSERT INTO FLOWS_FILES.WWV_FLOW_FILE_OBJECTS$ (FLOW_ID, NAME,BLOB_CONTENT, DELETED_AS_OF) 
                     VALUES(102,'$file', empty_blob(),sysdate+5) RETURNING BLOB_CONTENT INTO :BLOB_CONTENT";
                $connection = $this->db->conn_id;
                $stmt = oci_parse($connection, $sql);
                $blob = oci_new_descriptor($connection, OCI_D_LOB);
                oci_bind_by_name($stmt, ":BLOB_CONTENT", $blob, -1, OCI_B_BLOB);
                if (!@oci_execute($stmt, OCI_NO_AUTO_COMMIT)) {
                    $e = oci_error($stmt);
                    $mensaje = explode(":", $e['message']);
                    var_dump($mensaje);
                    $data['error'] = 4;
                    $data['mensaje'] = substr($mensaje[2], 0, 44);
                    echo $sql;
                    echo $name;
                }
                // oci_execute($result, OCI_DEFAULT) or die("Unable to execute query");
                if (!$blob->save($BLOB_CONTENT)) {
                    oci_rollback($connection);
                } else {
                    oci_commit($connection);
                }
                oci_free_statement($stmt);
                $blob->free();

                /*                 * IDENTIFICADOR DE ABONO MENSUAL* */
                //$parempresa = $this->session->userdata("pkentidad");
                $parempresa = $_SESSION['pkentidad'];
                $abono = $this->db->query("SELECT DISTINCT p.NOMBRE_PRODUCTO
                                          ,pa.PK_PRODUCTO_CODIGO 
                                  FROM 
                                  MODCOMERC.COMTBLCOTIZA co
                        INNER JOIN MODCOMERC.COMTBLPROCES pr ON pr.PK_COTIZA_CODIGO = co.pk_cotiza_codigo
                        INNER JOIN MODCOMERC.COMTBLESTADO esp ON pr.pk_estado_codigo = esp.pk_estado_codigo
                        INNER JOIN MODCOMERC.COMTBLESTADO esc ON co.pk_estado_codigo = esc.pk_estado_codigo
                        INNER JOIN MODCOMERC.COMTBLPARAME pa ON pa.pk_proces_codigo = pr.pk_proces_codigo
                        INNER JOIN MODPRODUC.PROTBLPRODUC p ON p.PK_PRODUC_CODIGO = pa.PK_PRODUCTO_CODIGO 
                        INNER JOIN MODPRODUC.protbltippro tp 
                            ON p.pk_tippro_codigo = tp.pk_tippro_codigo 
                            AND tp.pk_tippro_codigo = 2
                                WHERE 
                                    esp.pk_estado_codigo  = 1
                                    AND  esc.pk_estado_codigo  = 1
                                    AND  PK_ENTIDA_CLIENTE =$parempresa");
                $codabono = $abono->result_array[0]['PK_PRODUCTO_CODIGO'];

                /*                 * ********Termina carga de archivo para recorrido*********** */

                $sql = "BEGIN MODGENERI.genpkgfunciones.prccargapedidoabono(
                    :PARNOMARCH , 
                    :PARENTIDAD ,
                    :PARUSUARIO ,
                    :PARCODABON ,
                    :PARCODORD,
                    :PARCAMPAN,
                    :PARRESP,
                    :PARMENSAJE,
                    :PARURL,
                    :PARNOMBREPEDIDO,
                    :PARFECHADIS);END;";

                $conn = $this->db->conn_id;
                $stmt = oci_parse($conn, $sql);

                $pararchivo = $file;
                //$usuario = $this->session->userdata("usuario");
                $usuario = $_SESSION['usuario'];
                $parusuario = $usuario['USUARIO_ACCESO'];
                //$parcampana = $this->session->userdata("campana");
                $parcampana = $_SESSION['campana'];
                $parurl = $file;
                $fechadispersion = $post['fechaDispersion'];
                $fechadispersion = date_format(date_create($fechadispersion), 'd-M-Y');
                //TIPO NUMBER INPUT INPUT
                oci_bind_by_name($stmt, ':PARNOMARCH', $pararchivo, 100);
                //TIPO VARCHAR2 INPUT
                oci_bind_by_name($stmt, ':PARENTIDAD', $parempresa, 100);
                //TIPO VARCHAR2 INPUT
                oci_bind_by_name($stmt, ':PARUSUARIO', $parusuario, 100);
                // TIPO NUMBER CODIGO TIPO ABONO 
                oci_bind_by_name($stmt, ':PARCODABON', $codabono, 100);
                // TIPO NUMBER CODIGO TIPO ABONO 
                oci_bind_by_name($stmt, ':PARCODORD', $parcodorden, 100);
                // TIPO NUMBER CODIGO TIPO ABONO 
                oci_bind_by_name($stmt, ':PARCAMPAN', $parcampana, 100);
                // TIPO NUMBER OUT RESPUESA
                oci_bind_by_name($stmt, ':PARRESP', $parrespuesta, 100);
                // TIPO NUMBER OUT MENSAJE  
                oci_bind_by_name($stmt, ':PARMENSAJE', $parmensaje, 100);
                // TIPO NUMBER IN URL ARCHIVO BASE 
                oci_bind_by_name($stmt, ':PARURL', $parurl, 100);
                // TIPO NUMBER IN PARNOMBREPEDIDO 
                oci_bind_by_name($stmt, ':PARNOMBREPEDIDO', $parnombrepedido, 100);
                // TIPO NUMBER IN FECHA DISPERSION
                oci_bind_by_name($stmt, ':PARFECHADIS', $fechadispersion, 100);

                if (!oci_execute($stmt)) {
                    $e = oci_error($stmt);
                    VAR_DUMP($e);
                    exit;
                }
            }

            if ($parmensaje == 1) {
                var_dump($parcodorden);
                redirect("/portal/abonos/abonoMasivo?ok&c=$parcodorden");
            } else {
                $this->mensajeError($pararchivo);
            }
        } else {
            redirect('portal/abonos/abonoMasivo?errorp');
        }
    }

    public function nombreOrden() {
        $this->verificarPerfilCo();
        $post = $this->input->post();
        if ($post) {
            // var_dump($post);
            //exit();
            if ($post['nombreorden']) {
                $sql = "BEGIN MODGENERI.GENPKGWEBSERVICE.prcactualizanombreorden(:parcodigoorden
              ,:parnombre
              ,:parresultado);
              END;";
            }
            $conn = $this->db->conn_id;
            $stmt = oci_parse($conn, $sql);
            $parcodigoorden = $post['codigo'];
            $parnombre = $post['nombreorden'];
            //TIPO VARCHAR2 INPUT
            oci_bind_by_name($stmt, ':parcodigoorden', $parcodigoorden, 100);
            //TIPO VARCHAR2 INPUT
            oci_bind_by_name($stmt, ':parnombre', $parnombre, 100);
            //TIPO VARCHAR2 OUTPUT
            oci_bind_by_name($stmt, ':parresultado', $parrespuesta, 100);

            if (!@oci_execute($stmt)) {
                $e = oci_error($stmt);
                $mensaje = explode(":", $e['message']);
                var_dump($mensaje);
                $data['error'] = 1;
            } elseif ($parrespuesta == 1) {
                if ($post['SOLICITUD']) {
                    //redirect('portal/SolicitudTarjeta/solicitud/$data/$var2'); 
                    redirect('/portal/abonos/unoAUno');
                    //var_dump('SOLICITUD');
                } else {
                    redirect('/portal/ordenPedido/lista');
                }
            }
        }
    }

    /*
      Borra todos los caracteres del texto que no sea alguno de los caracteres deseados.
      Ejemplos:
      dejarSoloCaracteresDeseados("89.500.400","0123456789") --> "89500400"
      dejarSoloCaracteresDeseados("ABC-000-123-X-456","0123456789") --> "000123456"
     */

    private static function dejarSoloCaracteresDeseados($texto, $caracteresDeseados) {
        $resultado = array();
        for ($indice = 0; $indice < strlen($texto); $indice++) {
            $caracter = $texto[$indice];
            if (strpos($caracteresDeseados, $caracter) !== false)
                $resultado[] = $caracter;
        }
        return implode('', $resultado);
    }

    public function finalizarPrePedidoAbo() {
        log_info($this->logHeader . 'INGRESO CONTROLADOR FINALIZARPREPEDIDOABO UNO A UNO');
        $this->verificarPerfilCo();
        $post = $this->input->post();
        //$ultimaconexion = $this->session->userdata("ultimaconexion");
        $ultimaconexion = $_SESSION['ultimaconexion'];
        $data['ultimaconexion'] = $ultimaconexion;
        //$empresa = $this->session->userdata("entidad");
        $empresa = $_SESSION['entidad'];
        $data['empresa'] = $empresa['NOMBREEMPRESA'];
        //$usuario = $this->session->userdata("usuario");
        $usuario = $_SESSION['usuario'];
        $data['usuario'] = $usuario['NOMBRE'] . " " . $usuario['APELLIDO'];
        if ($post) {
            log_info($this->logHeader . ' DATOS POST '
                    . ' nombresolicitud ' . $post['nombreorden']
                    . ' pksolicitud ' . $post['pedido']);
            $pksolicitud = $post['pedido'];
            $NombreSolicitud = $post['nombreorden'];
            //se actualiza solicitud 
            $sql = "BEGIN MODPREPEDIDO.MODPREPEDIDOPKGFUNCIONES.prccrearactusolicitud(
                    parnombresolicitud =>:parnombresolicitud,
                    parpkpreorden =>:parpkpreorden,
                    parpktiposolicitud=>:parpktiposolicitud,
                    parpkempresa=>:parpkempresa,
                    parpkcoordinador=>:parpkcoordinador,
                    parcampana=>:parcampana,
                    parusuariocreacion=>:parusuariocreacion,
                    parpkcodigosolicitud=>:parpkcodigosolicitud,
                    parmensajerespuesta=>:parmensajerespuesta,
                    parrespuesta=>:parrespuesta);
                    END;";

            $conn = $this->db->conn_id;
            $stmt = oci_parse($conn, $sql);
            $coordinador;
            $pkEmpresa;
            $parnombresolicitud = $NombreSolicitud;
            $parcampana;
            $parpkpreorden;
            $parpktiposolicitud; //solicitud uno a uno
            $parpkcodigosolicitud = $pksolicitud;
            $parusuario = $usuario['USUARIO_ACCESO'];
            oci_bind_by_name($stmt, ':parnombresolicitud', $parnombresolicitud, 32);
            oci_bind_by_name($stmt, ':parpkpreorden', $parpkpreorden, 32);
            oci_bind_by_name($stmt, ':parpktiposolicitud', $parpktiposolicitud, 32);
            oci_bind_by_name($stmt, ':parpkempresa', $pkEmpresa, 32);
            oci_bind_by_name($stmt, ':parpkcoordinador', $coordinador, 32);
            oci_bind_by_name($stmt, ':parcampana', $parcampana, 32);
            oci_bind_by_name($stmt, ':parusuariocreacion', $parusuario, 32);
            oci_bind_by_name($stmt, ':parpkcodigosolicitud', $parpkcodigosolicitud, 32);
            oci_bind_by_name($stmt, ':parmensajerespuesta', $parmensajerespuesta, 500);
            oci_bind_by_name($stmt, ':parrespuesta', $parrespuesta, 32);

            if (!oci_execute($stmt)) {
                $e = oci_error($stmt);
                VAR_DUMP($e);
                $data['error'] = 4;
                $data['respues'] = 'No se puede actualizar la solicitud en estos momentos. ';
            }
            if ($parrespuesta == 1) {
                log_info($logHeader . ' RESPUESTA ACTUALIZAR SOLICITUD ABONO'
                        . ' parrespuest ' . $parrespuesta
                        . ' parmensajerespuesta ' . $parmensajerespuesta);
                $data['nomSolicitud'] = $parnombresolicitud;
                $data['ok'] = 1;
            }

            $this->load->view('portal/templates/header2', $data);
            if (isset($post['AbonoMasi']) && !empty($post['AbonoMasi']) == 1) {
                $this->load->view('portal/abonos/abonoMasivo', $data);
            } else if (isset($post['SoliTarMasi']) && !empty($post['SoliTarMasi']) == 1) {
                $this->load->view('portal/solicitudTarjetas/solicitudTarjetasMasivo', $data);
            } else {
                $this->load->view('portal/abonos/unoAUno', $data);
            }
            $this->load->view('portal/templates/footer', $data);
        }
    }

}
