<?php
session_start();
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class SolicitudTarjetas extends CI_Controller {

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

    public function solicitud($respuesta = null, $codigo = null) {
        //$this->session->set_userdata(array("pedidoAbono" => null));
        $_SESSION[array("pedidoAbono" => null)];
        //$this->session->set_userdata(array("llavesTemp" => null));
        $_SESSION[array("llavesTemp" => null)];
        $this->verificarPerfilCo();
        $data['error'] = $respuesta;
        $post = $this->input->post();
        log_info($this->iniciLog . $this->logHeader . $this->session->userdata("usuario"));
        log_info($this->logHeader . 'INGRESO SOLICITUD DE TARJETAS');
        if ($post) {
            log_info($this->logHeader . $this->postData . $post);
            $productos = $post['productos'];
            //$pkEmpresa = $this->session->userdata("pkentidad");
            $pkEmpresa = $_SESSION['pkentidad'];

            if (!empty($productos[0])) {
                if (empty($post['pksolicitudPrepepdido'])) {
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
                    $coordinador = $usuario["PK_ENT_CODIGO"];

                    //$parcampana = $this->session->userdata("campana");
                    $parcampana = $_SESSION['campana'];
                    $usuarioactual = $usuario['USUARIO_ACCESO'];
                    $parnombresolicitud="";
                    $parpkpreorden="";
                    $parpktiposolicitud = 1; //solicitud uno a uno
                    oci_bind_by_name($stmt, ':parnombresolicitud', $parnombresolicitud, 32);
                    oci_bind_by_name($stmt, ':parpkpreorden', $parpkpreorden, 32);
                    oci_bind_by_name($stmt, ':parpktiposolicitud', $parpktiposolicitud, 32);
                    oci_bind_by_name($stmt, ':parpkempresa', $pkEmpresa, 32);
                    oci_bind_by_name($stmt, ':parpkcoordinador', $coordinador, 32);
                    oci_bind_by_name($stmt, ':parcampana', $parcampana, 32);
                    oci_bind_by_name($stmt, ':parusuariocreacion', $usuarioactual, 32);
                    oci_bind_by_name($stmt, ':parpkcodigosolicitud', $parpkcodigosolicitud, 32);
                    oci_bind_by_name($stmt, ':parmensajerespuesta', $parmensajerespuesta, 500);
                    oci_bind_by_name($stmt, ':parrespuesta', $parrespuesta, 32);
                    if (!oci_execute($stmt)) {
                        $e = oci_error($stmt);
                        VAR_DUMP($e);
                        $data['error'] = 4;
                        $data['respues'] = 'No se puede crear el Pedido en estos momentos. ';
                    }
                    if ($parrespuesta == 1) {
                        $parpkcodigosolicitud = $parpkcodigosolicitud;
                    }
                } else {
                    $parpkcodigosolicitud = $post['pksolicitudPrepepdido'];
                }
                //se valida que codigo solicitud no sea null
                if (!empty($parpkcodigosolicitud)) {
                    log_info($this->logHeader . ' RESPUESTA CREAR SOLICITUD '
                            . ' parrespuest ' . $parrespuesta
                            . ' parmensajerespuesta ' . $parmensajerespuesta
                            . ' parpkcodigosolicitud ' . $parpkcodigosolicitud);
                    $producto = explode('_', $productos[0]);

                    $parpkproducto = $producto[0];
                    $parpkbinproducto = $producto[1];
                    //se crea el detalle solicitud
                    $sql = " BEGIN MODPREPEDIDO.MODPREPEDIDOPKGFUNCIONES.prccreardetsolicitud(
                            :parpkproducto,
                            :parpktdcodigo,
                            :pardocumento,
                            :parprimernombre,
                            :parsegundonombre,
                            :parprimerapellido,
                            :parsegundoapellido,
                            :parcorreo,
                            :partelefono,
                            :paridentificador,
                            :parpktdcustodio,
                            :pardocumentocustodio,
                            :parpkcodigosolicitud,
                            :parpkciudad,
                            :parpkempresa,
                            :parpkcustodio,
                            :parmensajerespuesta,
                            :parrespuesta); END;";

                    $conn = $this->db->conn_id;
                    $stmt = oci_parse($conn, $sql);

                    $tipodocumento = $post['tipoDocumento'];
                    $documento = $post['documento'];
                    $tipoVinculacion = '48'; // tipo de vinculacion empleado o Tarjeta habiente

                    //$usuario = $this->session->userdata("usuario");
                    $usuario = $_SESSION['usuario'];
                    $usuarioactual = $usuario['USUARIO_ACCESO'];
                    $primerNombre = strtoupper($post['primerNombre']);
                    $segundoNombre = strtoupper($post['segundoNombre']);
                    $primerApellido = strtoupper($post['primerApellido']);
                    $segundoApellido = strtoupper($post['segundoApellido']);
                    $correo = strtoupper($post['correo']);
                    $ciudad = $post['ciudad'];
                    $telefono = $post['telefono'];
                    $tdcustodio = '';
                    $docCustodio = '';
                    $custodio = $post['custodio'];
                    if (!empty($post['identificador'])) {
                        //$empresa = $this->session->userdata("entidad");
                        $empresa = $_SESSION['entidad'];
                        $primerNombre = $empresa['NOMBREEMPRESA'];
                        $documento = $empresa['DOCUMENTO'];
                        $tipodocumento = null;
                        $segundoNombre = null;
                        $primerApellido = null;
                        $segundoApellido = null;
                        $correo = null;
                        $telefono = null;
                        $ciudad = null;
                    }
                    $paridentificador = $post['identificador'];

                    //TIPO NUMBER INPUT
                    log_info($this->logHeader . 'CREANCION DETALLE PREPEDIDO'
                            . ' parpkproducto ' . $parpkproducto
                            . ' parpktdcodigo ' . $tipodocumento
                            . ' pardocumento ' . $documento
                            . ' parprimernombre ' . $primerNombre
                            . ' parsegundonombre ' . $segundoNombre
                            . ' parprimerapellido ' . $primerApellido
                            . ' parsegundoapellido ' . $segundoApellido
                            . ' parcorreo ' . $correo
                            . ' partelefono ' . $telefono
                            . ' paridentificador ' . $paridentificador
                            . ' parpktdcustodio ' . $tdcustodio
                            . ' pardocumentocustodio ' . $docCustodio
                            . ' parpkcodigosolicitud ' . $parpkcodigosolicitud
                            . ' parpkciudad ' . $ciudad
                            . ' parpkempresa ' . $pkEmpresa
                            . ' parpkcustodio ' . $custodio);
                    oci_bind_by_name($stmt, ':parpkproducto', $parpkproducto, 32);
                    oci_bind_by_name($stmt, ':parpktdcodigo', $tipodocumento, 32);
                    oci_bind_by_name($stmt, ':pardocumento', $documento, 32);
                    oci_bind_by_name($stmt, ':parprimernombre', $primerNombre, 32);
                    oci_bind_by_name($stmt, ':parsegundonombre', $segundoNombre, 32);
                    oci_bind_by_name($stmt, ':parprimerapellido', $primerApellido, 32);
                    oci_bind_by_name($stmt, ':parsegundoapellido', $segundoApellido, 32);
                    oci_bind_by_name($stmt, ':parcorreo', $correo, 32);
                    oci_bind_by_name($stmt, ':partelefono', $telefono, 32);
                    oci_bind_by_name($stmt, ':paridentificador', $paridentificador, 32);
                    oci_bind_by_name($stmt, ':parpktdcustodio', $tdcustodio, 32);
                    oci_bind_by_name($stmt, ':pardocumentocustodio', $docCustodio, 32);
                    oci_bind_by_name($stmt, ':parpkcodigosolicitud', $parpkcodigosolicitud, 32);
                    oci_bind_by_name($stmt, ':parpkciudad', $ciudad, 32);
                    oci_bind_by_name($stmt, ':parpkempresa', $pkEmpresa, 32);
                    oci_bind_by_name($stmt, ':parpkcustodio', $custodio, 32);
                    oci_bind_by_name($stmt, ':parmensajerespuesta', $parmensajerespuest, 200);
                    oci_bind_by_name($stmt, ':parrespuesta', $parrespuest, 32);
                    try {
                        if (!@oci_execute($stmt)) {
                            $e = oci_error($stmt);
                            var_dump("{$e['message']}");
                            $data['error'] = 1;
                            // $this->load->view('portal/usuariosCreacion/crear', $data);
                        }
                    } catch (Exception $ex) {
                        log_info($this->logHeader . 'ERROR ' . $ex->getMessage());
                    }
                    log_info($this->logHeader . ' RESPUESTA CREANCION DETALLE PREPEDIDO '
                            . ' parrespuest ' . $parrespuest
                            . ' mensajeRespuesta ' . $parmensajerespuest);
                    if ($parrespuest == 1) {
                        $data['error'] = 1;
                        $data['respues'] = $parrespuest;
                        if (empty($post['pksolicitudPrepepdido'])) {
                            redirect("portal/solicitudTarjetas/lista/$parpkcodigosolicitud/1");
                        } else {
                            redirect("portal/solicitudGestion/editarSolicitud/$parpkcodigosolicitud?add=OK");
                        }

                        //redirect("portal/solicitudTarjetas/solicitud/{$data['error']}/{$data['respues']}");
                    }
                }
            } else {
                // post viene null
                $parrespuest = 11405;
            }


            // var_dump(!is_null($productos));
            //   exit(); 
            if ($parrespuest != 1) {
                if ($parrespuest == 10006 || $parrespuest == 10005) {
                    $data['error'] = '5';
                    $data['respues'] = 'Ocurrio el Codigo de Error: La tarjeta ya fue expedida ';
                } elseif ($parrespuest == 10004 || $parrespuest == 1004) {
                    $data['error'] = '5';
                    $data['respues'] = 'Ocurrio el Codigo de Error: La tarjeta ya fue solicitada en otro pedido ';
                } elseif ($parrespuest == 1007) {
                    $data['error'] = '5';
                    $data['respues'] = 'Ocurrio el Codigo de Error: Se debe agregar un identificador';
                } elseif ($parrespuest == 10006) {
                    $data['error'] = '5';
                    $data['respues'] = 'Ocurrio el Codigo de Error: Se debe verificar el estado del pedido';
                } elseif ($parrespuest == 10003) {
                    $data['error'] = '5';
                    $data['respues'] = 'Ocurrio el Codigo de Error: La tarjeta ya fue expedida';
                } elseif ($parrespuest == 1010) {
                    $data['error'] = '5';
                    $data['respues'] = 'Ocurrio el Codigo de Error: No puede solicitar una tarjeta de la linea businnes a un TH ';
                } elseif ($parrespuest == 10010) {
                    $data['error'] = '5';
                    $data['respues'] = 'Ocurrio el Codigo de Error: El producto seleccionado no esta disponible en su cotizaci&oacute;n ';
                } elseif ($parrespuest == 4021) {
                    $data['error'] = '5';
                    $data['respues'] = 'La tarjeta ya fue expedida';
                } elseif ($parrespuest == 4018) {
                    $data['error'] = '5';
                    $data['respues'] = 'Identificador ya esta en uso';
                } elseif ($parrespuest == 11405) {
                    $data['error'] = '5';
                    $data['respues'] = 'No se ha seleccionado ningun producto';
                } elseif ($parrespuest == 4013) {
                    $data['error'] = '5';
                    $data['respues'] = 'Identificador debe ser nulo para productos personales';
                } else {
                    $data['error'] = '5';
                    $data['respues'] = 'Ocurrio el Codigo de Error: No se ha seleccionado ningun producto!';
                }
            }
        }
        //15/07/2020 solicitudes incompletas
        //$usuarioSol = $this->session->userdata("usuario");
        $usuarioSol = $_SESSION['usuario'];
        $coordinadorSol = $usuarioSol["PK_ENT_CODIGO"];
        //$parcampana = $this->session->userdata("campana");
        $parcampana = $_SESSION['campana'];
        $sqlSolPendientes = $this->db->query("Select sol.pk_codigo_solicitud SOLICITUD,sol.fecha_creacion from modprepedido.prepetblsolicitud sol
                    where sol.nombre_solicitud is null
                    and sol.pk_ent_solicitud = $coordinadorSol and sol.pk_campana_codigo = $parcampana
                    and pk_tipsol_codigo=1");
        $data['solicitudespend'] = $sqlSolPendientes->result_array;
        //$empresa = $this->session->userdata("entidad");
        $empresa = $_SESSION['entidad'];
        $data['empresa'] = $empresa['NOMBREEMPRESA'];
        //$usuario = $this->session->userdata("usuario");
        $usuario = $_SESSION['usuario'];
        $data['usuario'] = $usuario['NOMBRE'] . " " . $usuario['APELLIDO'];
        //$campana = $this->session->userdata("campana");
        $campana = $_SESSION['campana'];

        $productos = $this->db->query("SELECT  DISTINCT p.NOMBRE_PRODUCTO ,
            pa.PK_PRODUCTO_CODIGO||'_'||pa.PK_BIN_PRODUCTO_CODIGO||'_'||p.limitacion AS PK_PRODUCTO_CODIGO
                FROM MODCOMERC.COMTBLCOTIZA co
                INNER JOIN MODCOMERC.COMTBLPROCES pr ON pr.PK_COTIZA_CODIGO = co.pk_cotiza_codigo
                INNER JOIN MODCOMERC.COMTBLESTADO esp ON pr.pk_estado_codigo = esp.pk_estado_codigo
                INNER JOIN MODCOMERC.COMTBLESTADO esc ON co.pk_estado_codigo = esc.pk_estado_codigo
                INNER JOIN MODCOMERC.COMTBLPARAME pa ON pa.pk_proces_codigo = pr.pk_proces_codigo
                INNER JOIN MODPRODUC.PROTBLPRODUC p ON p.PK_PRODUC_CODIGO = pa.PK_PRODUCTO_CODIGO 
                INNER JOIN MODPRODUC.protbltippro tp ON p.pk_tippro_codigo = tp.pk_tippro_codigo
                    AND tp.nombre LIKE '%TARJETA%'
                WHERE esp.NOMBRE  LIKE 'ACTIVO'
                AND  esc.nombre  LIKE 'ACTIVO'
                AND  PK_ENTIDA_CLIENTE = {$empresa['PK_ENT_CODIGO']}
                AND co.pk_campana_codigo = {$campana}");
        $data['productos'] = $productos->result_array;
        $custio = $this->db->query("select ent.nombre ||' '|| ent.apellido || ' - '||ent.documento NOMBRE,
            ent.pk_ent_codigo CODIGOENTIDA
                        from modcliuni.clitblvincul vin 
                        join modcliuni.clitblentida ent on ent.pk_ent_codigo = vin.CLITBLENTIDA_PK_ENT_CODIGO
                        and vin.clitbltipvin_pk_tipvin_codigo = 46 
                        and vin.fecha_fin is null
                        and vin.clitblentida_pk_ent_codigo1={$empresa['PK_ENT_CODIGO']}
                        and vin.CLITBLCAMPAN_PK_CAMPAN_CODIGO=$campana");
        $data['custodios'] = $custio->result_array;
        $tipodocumento = $this->db->query('SELECT PK_TD_CODIGO,ABREVIACION,NOMBRE FROM MODCLIUNI.CLITBLTIPDOC WHERE PK_TD_CODIGO IN (67,68,69,70,72) ');
        $data['tipoDocumento'] = $tipodocumento->result_array;
        $departamentos = $this->db->query("SELECT PK_DEP_CODIGO, NOMBRE FROM MODCLIUNI.CLITBLDEPPAI WHERE CLITBLPAIS_PK_PAIS_CODIGO=7");
        $data['departamentos'] = $departamentos->result_array;
        $genero = $this->db->query("SELECT PK_GEN_CODIGO,NOMBRE FROM MODCLIUNI.CLITBLGENERO");
        $data['generos'] = $genero->result_array;
        if ($codigo != null) {
            $data['codigosolicitud'] = $codigo;
        }
        //$ultimaconexion = $this->session->userdata("ultimaconexion");
        $ultimaconexion = $_SESSION['ultimaconexion'];
        $data['ultimaconexion'] = $ultimaconexion;
        $data['menu'] = "solicitud";
        log_info($this->iniciLog . $this->logHeader . $this->finFuncion);
        $this->load->view('portal/templates/header2', $data);
        $this->load->view('portal/solicitudTarjetas/solicitudTarjetas', $data);
        $this->load->view('portal/templates/footer', $data);
    }

    public function solicitudTarjetasMasivo($respuesta = null, $codigo = null) {
        
        $this->verificarPerfilCo();
        $empresa = $_SESSION['entidad'];
        $data['empresa'] = $empresa['NOMBREEMPRESA'];
        //$usuario = $this->session->userdata("usuario");
        $usuario = $_SESSION['usuario'];
        //$post = $this->input->post();
        log_info($this->logHeader . ' INGRESO SOLICITUD  DE TARJETAS MASIVA APOLO');
        log_info($this->logHeader . ' INGRESO SOLICITUD DE TARJETAS MASIVA ' . $empresa['NOMBREEMPRESA']);
        //$empresa = $this->session->userdata("entidad");
        $empresa = $_SESSION['entidad'];
        $data['empresa'] = $empresa['NOMBREEMPRESA'];
        //$usuario = $this->session->userdata("usuario");
        $usuario = $_SESSION['usuario'];
        log_info($this->iniciLog . $this->logHeader . '  ' . $usuario);
        $data['usuario'] = $usuario['NOMBRE'] . " " . $usuario['APELLIDO'];
        //$campana = $this->session->userdata("campana");
        $campana = $_SESSION['campana'];

        //$ultimaconexion = $this->session->userdata("ultimaconexion");
        $ultimaconexion = $_SESSION['ultimaconexion'];
        $data['ultimaconexion'] = $ultimaconexion;
        $data['menu'] = "solicitud";
        $data['error'] = $respuesta;
        $parempresa = $empresa['PK_ENT_CODIGO'];
        $modelopago = $this->db->query("select clipar.dato
                                    from modcliuni.clitblclipar clipar
                                    join modcliuni.clitblparame par 
                                    on par.pk_parame_codigo = clipar.pk_parame_codigo 
                                    where clipar.pk_ent_codigo =$parempresa
                                    and clipar.pk_parame_codigo = 1");
        $modelopago = $modelopago->result_array[0];
        $data['modelopago'] = $modelopago;
        log_info($this->iniciLog . $this->logHeader . $this->finFuncion);
        $this->load->view('portal/templates/header2', $data);
        $this->load->view('portal/solicitudTarjetas/solicitudTarjetasMasivo', $data);
        $this->load->view('portal/templates/footer', $data);
    }

    public function solicitudTarjetasMasivouau($respuesta = null, $codigo = null) {
        $this->verificarPerfilCo();
        $empresa = $_SESSION['entidad'];
        $data['empresa'] = $empresa['NOMBREEMPRESA'];
        //$usuario = $this->session->userdata("usuario");
        $usuario = $_SESSION['usuario'];
        //$post = $this->input->post();
        log_info($this->logHeader . ' INGRESO SOLICITUD  DE TARJETAS MASIVA APOLO');
        log_info($this->logHeader . ' INGRESO SOLICITUD DE TARJETAS MASIVA ' .$empresa['NOMBREEMPRESA']);
        //$empresa = $this->session->userdata("entidad");
       
        log_info($this->iniciLog . $this->logHeader . '  ' . $usuario);
        $data['usuario'] = $usuario['NOMBRE'] . " " . $usuario['APELLIDO'];
        //$campana = $this->session->userdata("campana");
        $campana = $_SESSION['campana'];

        //$ultimaconexion = $this->session->userdata("ultimaconexion");
        $ultimaconexion = $_SESSION['ultimaconexion'];
        $data['ultimaconexion'] = $ultimaconexion;
        $data['menu'] = "solicitud";
        $data['error'] = $respuesta;
        $parempresa = $empresa['PK_ENT_CODIGO'];
        $modelopago = $this->db->query("select clipar.dato
                                    from modcliuni.clitblclipar clipar
                                    join modcliuni.clitblparame par 
                                    on par.pk_parame_codigo = clipar.pk_parame_codigo 
                                    where clipar.pk_ent_codigo =$parempresa
                                    and clipar.pk_parame_codigo = 1");
        $modelopago = $modelopago->result_array[0];
        $data['modelopago'] = $modelopago;
        log_info($this->iniciLog . $this->logHeader . $this->finFuncion);
        $this->load->view('portal/templates/header2', $data);
        $this->load->view('portal/solicitudTarjetas/solicitudTarjetasMasivoUnoAUno', $data);
        $this->load->view('portal/templates/footer', $data);
    }

    public function lista($pedido = 0, $error = 0) {
        $this->verificarPerfilCo();
        //$empresa = $this->session->userdata("entidad");
        $empresa = $_SESSION['entidad'];
        $data['empresa'] = $empresa['NOMBREEMPRESA'];
        //$usuario = $this->session->userdata("usuario");
        $usuario = $_SESSION['usuario'];
        $data['usuario'] = $usuario['NOMBRE'] . " " . $usuario['APELLIDO'];
        $coordinador = $usuario["PK_ENT_CODIGO"];
        //$pkEmpresa = $this->session->userdata("pkentidad");
        $pkEmpresa = $_SESSION['pkentidad'];
        //$campana = $this->session->userdata("campana");
        $campana = $_SESSION['campana'];
        $pedidos = $this->db->query("select det.pk_codigo_solicitud PK_TARHAB,det.pk_detalle_solicitud,det.primer_nombre||' ' ||det.primer_apellido TARJETA_HABIENTE,
                                    det.documento DOCUMENTO,pro.nombre_producto PRODUCTOS
                                    from MODPREPEDIDO.PREPETBLDETALLESOLICITUD det 
                                    join modproduc.PROTBLPRODUC pro on pro.pk_produc_codigo = det.pk_producto
                                    join MODPREPEDIDO.prepetblsolicitud sol ON det.pk_codigo_solicitud=sol.pk_codigo_solicitud
                                    where sol.pk_ent_solicitud = $coordinador and sol.pk_emp_solicitud = $pkEmpresa
                                    and sol.pk_campana_codigo = $campana
                                    and det.pk_codigo_solicitud = $pedido");
//        $pedidos = $this->db->query("select det.pk_tar_habiente PK_TARHAB,det.PK_DETPED_CODIGO,ent.nombre||' ' ||ent.apellido TARJETA_HABIENTE,
//                                    ent.documento DOCUMENTO,pro.nombre_producto PRODUCTOS
//                                    from MODALISTA.alitbldetped det 
//                                    join modcliuni.clitblentida ent on ent.pk_ent_codigo = det.pk_tar_habiente
//                                    join modproduc.PROTBLPRODUC pro on pro.pk_produc_codigo = det.pk_producto
//                                    where pk_pedido = $pedido");
        $data['pedidos'] = $pedidos->result_array;
        //$data['TH']=array_push ($data['TH'],$pedidos->result_array[0],$pedidos->result_array[1]);
        $data['pedidoActual'] = $pedido;
        //$parempresa = $this->session->userdata("pkentidad");
        $parempresa = $_SESSION['pkentidad'];
        $modelopago = $this->db->query("select clipar.dato
                                    from modcliuni.clitblclipar clipar
                                    join modcliuni.clitblparame par 
                                    on par.pk_parame_codigo = clipar.pk_parame_codigo 
                                    where clipar.pk_ent_codigo =$parempresa
                                    and clipar.pk_parame_codigo = 1");

        $modelopago = $modelopago->result_array[0];
        $data['modelopago'] = $modelopago;
        $data['error'] = $error;
        $data['menu'] = "solicitud";
        //$ultimaconexion = $this->session->userdata("ultimaconexion");
        $ultimaconexion = $_SESSION['ultimaconexion'];
        $data['ultimaconexion'] = $ultimaconexion;
        log_info($this->iniciLog . $this->finFuncion);
        $this->load->view('portal/templates/header2', $data);
        $this->load->view('portal/solicitudTarjetas/lista', $data);
        $this->load->view('portal/templates/footer', $data);
    }

    public function descargarPlantilla() {
        $this->verificarPerfilCo();
        $path = '/uploads/ARCHIVO-PEDIDO-MASIVO-DE-TARJETAS.xlsx';
        header("Location:" . $path);
    }

    public function descargarPlantillaUnoAUno() {
        $this->verificarPerfilCo();
        $path = '/uploads/ARCHIVO-PEDIDO-MASIVO-DE-TARJETAS-UNO-A-UNO.xlsx';
        header("Location:" . $path);
    }

    public function solicitudMasivaUnoAUno() {
        $empresa = $_SESSION['entidad'];
        $data['empresa'] = $empresa['NOMBREEMPRESA'];
        //$usuario = $this->session->userdata("usuario");
        $usuario = $_SESSION['usuario'];
        log_info($this->logHeader . ' INGRESO SOLICITUD DE MASIVA PROCESAMIENTO DE ARCHIVO UNO A UNO');
        log_info($this->iniciLog . $this->logHeader . $this->session->userdata("usuario"));
        log_info($this->logHeader . ' INGRESO SOLICITUD DE MASIVA ' . $empresa['NOMBREEMPRESA'] . 'UNO A UNO');
        $this->verificarPerfilCo();
        $post = $this->input->post();
        if ($post || $_FILES) {
            $date = date('Y_m_d');
            $random = rand(1000, 9999);
            $name = strtolower($date . '_' . $random);
            $tmp_name = $name;
            log_info($this->logHeader . ' INSERTA EN FLOW FILES');
            $BLOB_CONTENT = file_get_contents($_FILES['fileunoauno']['tmp_name']);
            $sql = "INSERT INTO FLOWS_FILES.WWV_FLOW_FILE_OBJECTS$ (FLOW_ID, NAME,BLOB_CONTENT, DELETED_AS_OF) 
                     VALUES(102,'$tmp_name', empty_blob(),sysdate+5) RETURNING BLOB_CONTENT INTO :BLOB_CONTENT";
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
                log_info($this->logHeader . ' EL ARCHIVO NO SE CARGO A LA BASE DE DATOS' . $e['message']);
            } else {
                log_info($this->logHeader . ' SE CARGO EL ARCHIVO A LA BASE DE DATOS');
            }
            // oci_execute($result, OCI_DEFAULT) or die("Unable to execute query");

            if ($blob->save($BLOB_CONTENT)) {
                oci_commit($connection);
                //oci_rollback($connection);
            } else {
                oci_rollback($connection);
            }

            oci_free_statement($stmt);
            $blob->free();

            /* $sql = "BEGIN MODGENERI.GENPKGWEBSERVICE.cargar_masivo(:parnomarch
              ,:parentidad
              ,:parusuario
              ,:parcoordinador
              ,:parcustodio
              ,:parcampana
              ,:parordcon
              ,:parrespuesta);
              END;"; */
            $sql = "BEGIN MODPREPEDIDO.MODPREPEDIDOPKGFUNCIONESUAU.prccargarmasivounoauno(:parnomarch
                                                                    ,:parentidad
                                                                    ,:parusuario
                                                                    ,:parcoordinador
                                                                    ,:parcampana
                                                                    ,:parcodigosolicitud
                                                                    ,:parrespuesta);
                        END;";


            $conn = $this->db->conn_id;
            $stmt = oci_parse($conn, $sql);
            $pararchivo = $tmp_name;
            //$parempresa = $this->session->userdata("pkentidad");
            $parempresa = $_SESSION['pkentidad'];
            //$usuario = $this->session->userdata("usuario");
            $usuario = $_SESSION['usuario'];
            $parusuario = $usuario['USUARIO_ACCESO'];
            //$parcampana = $this->session->userdata("campana");
            $parcampana = $_SESSION['campana'];
            $parcoordinador = $usuario['PK_ENT_CODIGO'];

            $parcustodio = $post['custodio'];

            //TIPO NUMBER INPUT INPUT
            oci_bind_by_name($stmt, ':parnomarch', $pararchivo, 100);
            //TIPO VARCHAR2 INPUT
            oci_bind_by_name($stmt, ':parentidad', $parempresa, 100);
            //TIPO NUMBER INPUT
            oci_bind_by_name($stmt, ':parusuario', $parusuario, 100);
            //TIPO VARCHAR2 INPUT
            oci_bind_by_name($stmt, ':parcoordinador', $parcoordinador, 100);
            //TIPO VARCHAR2 INPUT
            //  oci_bind_by_name($stmt, ':parcustodio', $parcustodio, 100);
            //TIPO VARCHAR2 INPUT
            oci_bind_by_name($stmt, ':parcampana', $parcampana, 100);
            //TIPO VARCHAR2 INPUT
            oci_bind_by_name($stmt, ':parcodigosolicitud', $parcodigoorden, 100);
            //TIPO VARCHAR2 OUTPUT
            oci_bind_by_name($stmt, ':parrespuesta', $parrespuesta, 100);


            if (!oci_execute($stmt)) {
                $e = oci_error($stmt);
                $mensaje = explode(":", $e['message']);
                //var_dump($mensaje);
                //exit();
                //$data['error'] = 1;
                //$parrespuesta = 0;
                log_info($this->logHeader . ' ERROR PROCESANDO EL ARCHIVO APOLO '
                        . $e['message'] . ' EMPRESA ' . $parempresa);
                redirect("portal/solicitudTarjetas/mensajeError/$parrespuesta/$pararchivo");
            } elseif ($parrespuesta != 1) {
                log_info($this->logHeader . ' SE PROCESA EL ARCHIVO CON ERRORES ');
                redirect("portal/solicitudTarjetas/mensajeError/$parrespuesta/$pararchivo");
            } elseif ($parrespuesta == 1) {
                //
                $codigo = $parcodigoorden;
                log_info($this->logHeader . ' ARCHIVO PROCESADO RESPUESTA' . $parrespuesta . ' CODIGO SOLICITUD ' . $codigo);
                redirect("portal/solicitudTarjetas/solicitudTarjetasMasivo/2");
            }
        }
    }  
  //

    public function solicitudMasiva() {
        $empresa = $_SESSION['entidad'];
        $data['empresa'] = $empresa['NOMBREEMPRESA'];
        //$usuario = $this->session->userdata("usuario");
        $usuario = $_SESSION['usuario'];
        log_info($this->logHeader . ' INGRESO SOLICITUD DE MASIVA PROCESAMIENTO DE ARCHIVO');
        log_info($this->iniciLog . $this->logHeader . $this->session->userdata("usuario"));
        log_info($this->logHeader . ' INGRESO SOLICITUD DE MASIVA ' . $empresa['NOMBREEMPRESA']);
        $this->verificarPerfilCo();
        $post = $this->input->post();
        if ($post || $_FILES) {
            $date = date('Y_m_d');
            $random = rand(1000, 9999);
            $name = strtolower($date . '_' . $random);
            $tmp_name = $name;
            log_info($this->logHeader . ' INSERTA ARCHIVOS $NAME'.$tmp_name);
            log_info($this->logHeader . ' INSERTA EN FLOW FILES');
            $BLOB_CONTENT = file_get_contents($_FILES['file']['tmp_name']);
            $sql = "INSERT INTO FLOWS_FILES.WWV_FLOW_FILE_OBJECTS$ (FLOW_ID, NAME,BLOB_CONTENT, DELETED_AS_OF) 
                     VALUES(102,'$tmp_name', empty_blob(),sysdate+5) RETURNING BLOB_CONTENT INTO :BLOB_CONTENT";
            $connection = $this->db->conn_id;
            $stmt = oci_parse($connection, $sql);
            $blob = oci_new_descriptor($connection, OCI_D_LOB);
             log_info($this->logHeader . ' UN CLOB QUE NO SE PUEDE VISUALIZAR'.$tmp_name);
            oci_bind_by_name($stmt, ":BLOB_CONTENT", $blob, -1, OCI_B_BLOB);
            if (!@oci_execute($stmt, OCI_NO_AUTO_COMMIT)) {
                $e = oci_error($stmt);
                $mensaje = explode(":", $e['message']);
                var_dump($mensaje);
                $data['error'] = 4;
                $data['mensaje'] = substr($mensaje[2], 0, 44);
                log_info($this->logHeader . ' EL ARCHIVO NO SE CARGO A LA BASE DE DATOS' . $e['message']);
            } else {
                log_info($this->logHeader . ' SE CARGO EL ARCHIVO A LA BASE DE DATOS');
            }
            // oci_execute($result, OCI_DEFAULT) or die("Unable to execute query");

            if ($blob->save($BLOB_CONTENT)) {
                oci_commit($connection);
                //oci_rollback($connection);
            } else {
                oci_rollback($connection);
            }

            oci_free_statement($stmt);
            $blob->free();

            /* $sql = "BEGIN MODGENERI.GENPKGWEBSERVICE.cargar_masivo(:parnomarch
              ,:parentidad
              ,:parusuario
              ,:parcoordinador
              ,:parcustodio
              ,:parcampana
              ,:parordcon
              ,:parrespuesta);
              END;"; */
            $sql = "BEGIN MODPREPEDIDO.MODPREPEDIDOPKGFUNCIONES.prccargarmasivo(:parnomarch
                                                                    ,:parentidad
                                                                    ,:parusuario
                                                                    ,:parcoordinador
                                                                    ,:parcampana
                                                                    ,:parcodigosolicitud
                                                                    ,:parrespuesta);
                        END;";


            $conn = $this->db->conn_id;
            $stmt = oci_parse($conn, $sql);
            $pararchivo = $tmp_name;
            //$parempresa = $this->session->userdata("pkentidad");
            $parempresa = $_SESSION['pkentidad'];
            //$usuario = $this->session->userdata("usuario");
            $usuario = $_SESSION['usuario'];
            $parusuario = $usuario['USUARIO_ACCESO'];
            //$parcampana = $this->session->userdata("campana");
            $parcampana = $_SESSION['campana'];
            $parcoordinador = $usuario['PK_ENT_CODIGO'];

            $parcustodio = $post['custodio'];

            //TIPO NUMBER INPUT INPUT
            oci_bind_by_name($stmt, ':parnomarch', $pararchivo, 100);
            //TIPO VARCHAR2 INPUT
            oci_bind_by_name($stmt, ':parentidad', $parempresa, 100);
            //TIPO NUMBER INPUT
            oci_bind_by_name($stmt, ':parusuario', $parusuario, 100);
            //TIPO VARCHAR2 INPUT
            oci_bind_by_name($stmt, ':parcoordinador', $parcoordinador, 100);
            //TIPO VARCHAR2 INPUT
            //  oci_bind_by_name($stmt, ':parcustodio', $parcustodio, 100);
            //TIPO VARCHAR2 INPUT
            oci_bind_by_name($stmt, ':parcampana', $parcampana, 100);
            //TIPO VARCHAR2 INPUT
            oci_bind_by_name($stmt, ':parcodigosolicitud', $parcodigoorden, 100);
            //TIPO VARCHAR2 OUTPUT
            oci_bind_by_name($stmt, ':parrespuesta', $parrespuesta, 100);
            if (!@oci_execute($stmt)) {
                $e = oci_error($stmt);
                $mensaje = explode(":", $e['message']);
                var_dump($mensaje);
                //$data['error'] = 1;
                $parrespuesta = 0;
                log_info($this->logHeader . ' ERROR PROCESANDO EL ARCHIVO APOLO '
                        . $e['message'] . ' EMPRESA ' . $parempresa);
                redirect("portal/solicitudTarjetas/mensajeError/$parrespuesta/$pararchivo");
            } elseif ($parrespuesta != 1) {
                log_info($this->logHeader . ' SE PROCESA EL ARCHIVO CON ERRORES ');
                redirect("portal/solicitudTarjetas/mensajeError/$parrespuesta/$pararchivo");
            } elseif ($parrespuesta == 1) {
                //
                $codigo = $parcodigoorden;
                log_info($this->logHeader . ' ARCHIVO PROCESADO RESPUESTA' . $parrespuesta . ' CODIGO SOLICITUD ' . $codigo);
                redirect("portal/solicitudTarjetas/solicitudTarjetasMasivo/$parrespuesta?c=$codigo");
            }
        }
    }

    public function mensajeError($error, $nombrearchivo) {
        $this->verificarPerfilCo();
        //$empresa = $this->session->userdata("entidad");
        $empresa = $_SESSION['entidad'];
        $data['empresa'] = $empresa['NOMBREEMPRESA'];
        //$usuario = $this->session->userdata("usuario");
        $usuario = $_SESSION['usuario'];
        $data['usuario'] = $usuario['NOMBRE'] . " " . $usuario['APELLIDO'];
        //$campana = $this->session->userdata("campana");
        $campana = $_SESSION['campana'];
        //var_dump($nombrearchivo);

        $errores = $this->db->query("SELECT LINEA_ARCHIVO,DATO,DESCRIPCION 
                                FROM MODGENERI.gentblerrcar 
                                WHERE ARCHIVO = '$nombrearchivo'
                                order by LINEA_ARCHIVO");
        $data['errores'] = $errores->result_array;
        $data['error'] = $error;
        //$empresa = $this->session->userdata("entidad");
        $empresa = $_SESSION['entidad'];
        $data['empresa'] = $empresa['NOMBREEMPRESA'];
        //$usuario = $this->session->userdata("usuario");
        $usuario = $_SESSION['usuario'];
        $data['usuario'] = $usuario['NOMBRE'] . " " . $usuario['APELLIDO'];
        //$ultimaconexion = $this->session->userdata("ultimaconexion");
        $ultimaconexion = $_SESSION['ultimaconexion'];
        $data['ultimaconexion'] = $ultimaconexion;
        $this->load->view('portal/templates/header2', $data);
        $this->load->view('portal/solicitudTarjetas/mensajeError', $data);
        $this->load->view('portal/templates/footer', $error);
    }

    public function nombreOrden() {
        $this->verificarPerfilCo();
        $post = $this->input->post();
        log_info($this->logHeader . 'INGRESO NOMBRE ORDEN');
        if ($post) {
            log_info($this->logHeader . $this->postData . $post);
            $varestadopagonopa = 1; // no se ha pagado la orden
            $varnosolicitarjet = 2; // no se han solicitado tarjetas 

            if ($post['ORDEN'] == 1) {
                $varcrearorden = 0; // 1 SI ES SOLICITAR ABONO Y 0 SI ES SOLICITAR ORDEN COMPRA
            } else if ($post['SOLICITUD'] == 2 || $post['FINALIZAR'] == 2) {
                $varcrearorden = 1;
            }

            $sql = " BEGIN modgeneri.genpkgfunciones.prcfinalizarmasivotarjetas (
                varestadopagonopa=>:varestadopagonopa,
                varnosolicitarjet=>:varnosolicitarjet,
                parempresa=>:parempresa,
                parcoordinador=>:parcoordinador,
                parcampana=>:parcampana,
                parordcon=>:parordcon,
                parusuario=>:parusuario,
                parcrearordcon=>:varcrearorden,
                parrespuesta=>:parrespuesta
                ); END; ";

            //$parempresa = $this->session->userdata("pkentidad");
            $parempresa = $_SESSION['pkentidad'];
            //$usuario = $this->session->userdata("usuario");
            $usuario = $_SESSION['usuario'];
            $parusuario = $usuario['USUARIO_ACCESO'];
            //$parcampana = $this->session->userdata("campana");
            $parcampana = $_SESSION['campana'];
            $parcoordinador = $usuario['PK_ENT_CODIGO'];

            $conn = $this->db->conn_id;
            $stmt = oci_parse($conn, $sql);

            //TIPO VARCHAR2 INPUT
            oci_bind_by_name($stmt, ':varestadopagonopa', $varestadopagonopa, 100);
            //TIPO VARCHAR2 INPUT
            oci_bind_by_name($stmt, ':varnosolicitarjet', $varnosolicitarjet, 100);
            //TIPO VARCHAR2 input
            oci_bind_by_name($stmt, ':parempresa', $parempresa, 100);
            //TIPO VARCHAR2 input
            oci_bind_by_name($stmt, ':parcoordinador', $parcoordinador, 100);
            //TIPO VARCHAR2 input
            oci_bind_by_name($stmt, ':parcampana', $parcampana, 100);
            //TIPO VARCHAR2 input
            oci_bind_by_name($stmt, ':parordcon', $parordcon, 100);
            //TIPO VARCHAR2 input
            oci_bind_by_name($stmt, ':parusuario', $parusuario, 100);
            //TIPO VARCHAR2 input
            oci_bind_by_name($stmt, ':varcrearorden', $varcrearorden, 100);
            //TIPO VARCHAR2 input
            oci_bind_by_name($stmt, ':parrespuesta', $parrespuesta, 100);

            try {

                if (!@oci_execute($stmt)) {
                    $e = oci_error($stmt);
                    $mensaje = explode(":", $e['message']);
                    var_dump($mensaje);
                    var_dump('Error finalizando la solicitud del excel');
                    $data['error'] = 10;
                }
            } catch (Exception $ex) {
                log_info($this->logHeader . ' ERROR ' . $ex->getMessage());
            }
            log_info($this->logHeader . ' PROCEDURE prcfinalizarmasivotarjetas parrespuesta ' . $parrespuesta);
            if ($parrespuesta == 1) {

                if ($post['nombreorden']) {

                    $sql = "BEGIN MODGENERI.GENPKGWEBSERVICE.prcactualizanombreorden(:parcodigoorden
                ,:parnombre
                ,:parresultado);
                END;";

                    $conn = $this->db->conn_id;
                    $stmt = oci_parse($conn, $sql);
                    $parcodigoorden = $post['codigo'];
                    if ($parcodigoorden === null || $parcodigoorden === '') {
                        $parcodigoorden = $parordcon;
                    }
                    $parnombre = $post['nombreorden'];
                    //TIPO VARCHAR2 INPUT
                    oci_bind_by_name($stmt, ':parcodigoorden', $parcodigoorden, 100);
                    //TIPO VARCHAR2 INPUT
                    oci_bind_by_name($stmt, ':parnombre', $parnombre, 100);
                    //TIPO VARCHAR2 OUTPUT
                    oci_bind_by_name($stmt, ':parresultado', $parrespuesta, 100);

                    if (!@oci_execute($stmt)) {
                        $e = oci_error($stmt);
                        $data['error'] = 10;
                    } else if ($parrespuesta == 1) {
                        $modelopago = $this->db->query("select clipar.dato
                                    from modcliuni.clitblclipar clipar
                                    join modcliuni.clitblparame par 
                                    on par.pk_parame_codigo = clipar.pk_parame_codigo 
                                    where clipar.pk_ent_codigo =$parempresa
                                    and clipar.pk_parame_codigo = 1");

                        $modelopago = $modelopago->result_array[0];

                        if ($post['SOLICITUD']) {
                            //redirect('portal/SolicitudTarjeta/solicitud/$data/$var2'); 
                            redirect('/portal/abonos/unoAUno');
                            //var_dump('SOLICITUD');
                        } elseif ($post['FINALIZAR']) {
                            log_info($this->logHeader . ' CLICK EN BOTON FINALIZAR   ' . $parrespuesta);
                            redirect('/portal/solicitudTarjetas/solicitud/1');
                        } else {
                            redirect('/portal/ordenPedido/lista');
                        }
                    }
                } else {
                    log_info($this->logHeader . ' NO ENVIO NOMBRE DE LA ORDEN  ');
                    redirect('portal/solicitudTarjetas/solicitud/1');
                }
            } else {
                log_info($this->logHeader . ' FINALIZAR PEDIDO  PARRESPUESTA  ' . $parrespuesta);
                redirect('portal/solicitudTarjetas/solicitud/1');
            }
        }
    }

    public function finalizarPedido() {
        $this->verificarPerfilCo();
        $post = $this->input->post();
        //$ultimaconexion = $this->session->userdata("ultimaconexion");
        $ultimaconexion = $_SESSION['ultimaconexion'];
        $data['ultimaconexion'] = $ultimaconexion;
        log_info($this->logHeader . ' FINALIZAR PEDIDO  ' . $ultimaconexion);
        if ($post) {
            log_info($this->logHeader . $this->postData . $post);
            $pedido = $post['pedido'];
            //$empresa = $this->session->userdata("entidad");
            $empresa = $_SESSION['entidad'];
            $empresa = $empresa['PK_ENT_CODIGO'];

            $pkorden = $this->db->query("select 1 from modalista.alitblpedido
                                       where pk_pedido_codigo=$pedido
                                       and pk_empresa=$empresa");
            $pkorden = $pkorden->result_array[0];
            if ($pkorden['1'] == 1) {
                $sql = "BEGIN MODGENERI.GENPKGWEBSERVICE.PRCFINALIZARPEDIDOTARJETAS(
                    :parpedido
                    ,:pardetalleresp
                    ,:parrespuest); END;";

                $conn = $this->db->conn_id;
                $stmt = oci_parse($conn, $sql);

                //TIPO VARCHAR2 INPUT
                oci_bind_by_name($stmt, ':parpedido', $pedido, 100);
                //TIPO VARCHAR2 INPUT
                oci_bind_by_name($stmt, ':pardetalleresp', $pardetalleresp, 100);
                //TIPO VARCHAR2 OUTPUT
                oci_bind_by_name($stmt, ':parrespuest', $parrespuesta, 100);

                if (!@oci_execute($stmt)) {
                    $e = oci_error($stmt);
                    $mensaje = explode(":", $e['message']);

                    $data['pedidoActual'] = $pedido;
                    $data['error'] = $parrespuesta;
                    $data['mensajeError'] = $pardetalleresp;
                    $this->load->view('portal/templates/header2', $data);
                    $this->load->view('portal/solicitudTarjetas/lista', $data);
                    $this->load->view('portal/templates/footer', $data);
                    //redirect('/portal/solicitudTarjetas/lista/' . $pedido . '/' . $parrespuesta);
                } else if ($parrespuesta == 1) {
                    //var_dump($parrespuesta);

                    //$parempresa = $this->session->userdata("pkentidad");
                    $parempresa = $_SESSION['pkentidad'];
                    $modelopago = $this->db->query("select clipar.dato
                                    from modcliuni.clitblclipar clipar
                                    join modcliuni.clitblparame par 
                                    on par.pk_parame_codigo = clipar.pk_parame_codigo 
                                    where clipar.pk_ent_codigo =$parempresa
                                    and clipar.pk_parame_codigo = 1");

                    $modelopago = $modelopago->result_array[0];
                    $data['modelopago'] = $modelopago;


                    if ($modelopago == 'PREPAGO') {

                        redirect('/portal/ordenPedido/lista/1');
                    } else {
                        $data['error'] = $parrespuesta;
                        redirect('/portal/solicitudTarjetas/solicitud/' . $parrespuesta);
                        /* $data['pedidoActual'] = $pedido;

                          $data['mensajeError'] = $pardetalleresp;
                          $this->load->view('portal/templates/header2', $data);
                          $this->load->view('portal/solicitudTarjetas/solicitudTarjetasMasivo', $data);
                          $this->load->view('portal/templates/footer', $data); */
                    }
                } else {
                    var_dump($parrespuesta);
                }
            }
        }
    }

    public function finalizarPrePedido() {
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
        log_info($this->logHeader . ' NOMBRE SOLICITUD  ' . $ultimaconexion);
        if ($post) {
            log_info($this->logHeader . $this->postData . $post);
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
            $coordinador="";
            $pkEmpresa="";
            $parnombresolicitud = $NombreSolicitud;
            $parcampana="";
            $parpkpreorden="";
            $parpktiposolicitud=""; //solicitud uno a uno
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
                log_info($this->logHeader . ' RESPUESTA ACTUALIZAR SOLICITUD '
                        . ' parrespuest ' . $parrespuesta
                        . ' parmensajerespuesta ' . $parmensajerespuesta
                        . ' parrespuesta ' . $parrespuesta);
                $data['nomSolicitud'] = $parnombresolicitud;
                $data['ok'] = 1;
            }

            $this->load->view('portal/templates/header2', $data);
            $this->load->view('portal/solicitudTarjetas/lista', $data);
            $this->load->view('portal/templates/footer', $data);
        }
    }

}
