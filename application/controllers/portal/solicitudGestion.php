<?php
session_start();
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class SolicitudGestion extends CI_Controller {

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

    public function solicitudGes($codOrden = null) {

        $this->verificarPerfilCo();
        $data['pk_preorden_codigo'] = $codOrden;

        //log_info($this->iniciLog . $this->session->userdata("usuario"));
        log_info($this->iniciLog . $_SESSION['usuario']);
        log_info($this->logHeader . 'INGRESO GESTION SOLICITUDES');



        //$empresa = $this->session->userdata("entidad");
        $empresa = $_SESSION['entidad'];
        $data['empresa'] = $empresa['NOMBREEMPRESA'];
        //$usuario = $this->session->userdata("usuario");
        $usuario = $_SESSION['usuario'];
        //$ultimaconexion = $this->session->userdata("ultimaconexion");
        $ultimaconexion = $_SESSION['ultimaconexion'];
        $data['ultimaconexion'] = $ultimaconexion;
        //$usuario = $this->session->userdata("usuario");
        $usuario = $_SESSION['usuario'];
        $data['usuario'] = $usuario['NOMBRE'] . " " . $usuario['APELLIDO'];
        $coordinador = $usuario["PK_ENT_CODIGO"];
        //$campana = $this->session->userdata("campana");
        $campana = $_SESSION['campana'];
        log_info($logHeader . 'CONSULTA  SOLICITUDES '
                . ' Coordinador ' . $coordinador
                . ' Empresa ' . $empresa['PK_ENT_CODIGO']
                . ' pkcampaña ' . $campana);
        if (!empty($codOrden)) {
            log_info($this->logHeader . 'INGRESO GESTION SOLICITUDES EDITAR PK_PREORDEN = ' . $codOrden);
            // se envian las solicitudes con la pk orden para validar en vista y colocar el check en true
            $solicitudes = $this->db->query("select sol.pk_codigo_solicitud, nvl(sol.nombre_solicitud,'SIN FINALIZAR'), tpo.nombre_tiposol ,
                                    tpo.pk_tipsol_codigo,
                                    sol.pk_preorden_codigo
                                    from MODPREPEDIDO.prepetblsolicitud sol
                                    join modprepedido.prepetbltiposolicitud tpo on sol.pk_tipsol_codigo = tpo.pk_tipsol_codigo
                                    where sol.pk_ent_solicitud = {$coordinador}
                                    and sol.pk_emp_solicitud = {$empresa['PK_ENT_CODIGO']}
                                    and sol.pk_campana_codigo = {$campana}
                                    
                                    ");
                                    //and sol.nombre_solicitud is not null
        } else {
            $solicitudes = $this->db->query("select sol.pk_codigo_solicitud,nvl(sol.nombre_solicitud,'SIN FINALIZAR')nombre_solicitud, tpo.nombre_tiposol ,
                                    tpo.pk_tipsol_codigo,
                                    sol.pk_preorden_codigo
                                    from MODPREPEDIDO.prepetblsolicitud sol
                                    join modprepedido.prepetbltiposolicitud tpo on sol.pk_tipsol_codigo = tpo.pk_tipsol_codigo
                                    where sol.pk_ent_solicitud = {$coordinador}
                                    and sol.pk_emp_solicitud = {$empresa['PK_ENT_CODIGO']}
                                    and sol.pk_campana_codigo = {$campana}
                                   
                                    and sol.pk_preorden_codigo is null");
                                     //and sol.nombre_solicitud is not null
        }

        $data['solicitudes'] = $solicitudes->result_array;
        $data['menu'] = "gestion";
        $this->load->view('portal/templates/header2', $data);
        $this->load->view('portal/solicitudGestion/gestionSolicitudes', $data);
        $this->load->view('portal/templates/footer', $data);
    }

    public function editarSolicitud($codSolicitud) {
        $this->verificarPerfilCo();
        log_info($logHeader . 'INGRESO EDITAR SOLICITUD PREPEDIDO');
        log_info($logHeader . ' DATA ENTRADA EDITAR SOLICITUD PREPEDIDO'
                . ' codSolicitud ' . $codSolicitud);
        //$usuario = $this->session->userdata("usuario");
        $usuario = $_SESSION['usuario'];
        //$empresa = $this->session->userdata("entidad");
        $empresa = $_SESSION['entidad'];
        log_info($this->iniciLog . $this->logHeader . $_SESSION['usuario']);

        $post = $this->input->post();
        if ($post) {
            log_info('POSTDATA:::::::::' . 'producto=' . $post['producto']
                    . '-Pk_codigo_solicitud=' . $post['pksolicitud']
                    . '-Pk_detalle_solicitud=' . $post['pkdetalle']
                    . '-Primer_nombre=' . $post['primerNombre']
                    . '-Segundo_nombre=' . $post['segundoNombre']
                    . '-Primer_apellido=' . $post['primerApellido']
                    . '-Segundo_apellido=' . $post['segundoApellido']
                    . '-parcorreo=' . $post['correo']
                    . '-partelefono=' . $post['telefono']
                    . '-parpkcustodio=' . $post['custodio']);
            $producto = explode('_', $post['producto']);
            $parpkproducto = $producto[0];
            $parpkbinproducto = $producto[1];
            $sql = "BEGIN MODPREPEDIDO.MODPREPEDIDOPKGFUNCIONES.PRCACTUALIZADETALLESOL(
                    parpkproducto=>:parpkproducto,
                    parbinproducto =>:parbinproducto,
                    parprimernombre=>:parprimernombre,
                    parsegundonombre=>:parsegundonombre,
                    parprimerapellido=>:parprimerapellido,
                    parsegundoapellido=>:parsegundoapellido,
                    parcorreo=>:parcorreo,
                    partelefono=>:partelefono,
                    parpkdetallesolicitud=>:parpkdetallesolicitud,
                    parpkcustodio=>:parpkcustodio,
                    parmensajerespuesta=>:parmensajerespuesta,
                    parcodrespuesta=>:parcodrespuesta);
                    END;";

            $conn = $this->db->conn_id;
            $stmt = oci_parse($conn, $sql);
            oci_bind_by_name($stmt, ':parpkproducto', $parpkproducto, 32);
            oci_bind_by_name($stmt, ':parbinproducto', $parpkbinproducto, 32);
            $parprimerNombre = strtoupper($post['primerNombre']);
            oci_bind_by_name($stmt, ':parprimernombre', $parprimerNombre, 32);
            $parsegundoNombre = strtoupper($post['segundoNombre']);
            oci_bind_by_name($stmt, ':parsegundonombre', $parsegundoNombre, 32);
            $parprimerApe = strtoupper($post['primerApellido']);
            oci_bind_by_name($stmt, ':parprimerapellido', $parprimerApe, 32);
            $parsegundoApe = strtoupper($post['segundoApellido']);
            oci_bind_by_name($stmt, ':parsegundoapellido', $parsegundoApe, 32);
            $parcorreo = strtoupper($post['correo']);
            oci_bind_by_name($stmt, ':parcorreo', $parcorreo, 32);
            $partelefono = $post['telefono'];
            oci_bind_by_name($stmt, ':partelefono', $partelefono, 32);
            $parpkdetalleSol = $post['pkdetalle'];
            oci_bind_by_name($stmt, ':parpkdetallesolicitud', $parpkdetalleSol, 32);
            $parpkCustodio = $post['custodio'];
            oci_bind_by_name($stmt, ':parpkcustodio', $parpkCustodio, 32);
            oci_bind_by_name($stmt, ':parmensajerespuesta', $parmensajerespuesta, 500);
            oci_bind_by_name($stmt, ':parcodrespuesta', $parcodrespuesta, 32);
            if (!oci_execute($stmt)) {
                $e = oci_error($stmt);
                VAR_DUMP($e);
                log_info($this->logHeader . ' ERROR EDITANDO DETALLESOLICITUD '
                        . $e['message'] . ' PARDETALLESOLICITUD ' . $parpkdetalleSol);
                $data['error'] = 4;
                $data['respues'] = 'No se puede editar la información en estos momentos. ';
            }
            if ($parcodrespuesta == 1) {
                $data['actualizacion'] = 1;
            }
        }

        if (!empty($codSolicitud)) {
            $coordinador = $usuario["PK_ENT_CODIGO"];
            //$campana = $this->session->userdata("campana");
            $campana = $_SESSION['campana'];
            //$pkEmpresa = $this->session->userdata("pkentidad");
            $pkEmpresa = $_SESSION['pkentidad'];
            $detalleSolicitudes = $this->db->query("select pro.nombre_producto PRODUCTO, 
                                    tipdoc.abreviacion TIPDOC,det.documento DOCUMENTO,
                                    det.pk_codigo_solicitud,det.pk_detalle_solicitud,
                                    det.primer_nombre, det.segundo_nombre, det.primer_apellido ,
                                    det.segundo_apellido, 
                                    det.correo_electronico,
                                    det.telefono,det.identificador_tarjeta 
                                    from MODPREPEDIDO.PREPETBLDETALLESOLICITUD det 
                                    join modproduc.PROTBLPRODUC pro on pro.pk_produc_codigo = det.pk_producto
                                    join MODPREPEDIDO.prepetblsolicitud sol ON det.pk_codigo_solicitud=sol.pk_codigo_solicitud
                                    left JOIN MODCLIUNI.CLITBLTIPDOC TIPDOC  on tipdoc.pk_td_codigo= det.clitbltipdoc_pk_td_codigo
                                    where sol.pk_ent_solicitud = $coordinador and sol.pk_emp_solicitud = $pkEmpresa
                                    and sol.pk_campana_codigo = $campana
                                    and det.pk_codigo_solicitud = $codSolicitud");
            $data['solicitudes'] = $detalleSolicitudes->result_array;
        }

        $data['codSolicitud'] = $codSolicitud;
        $data['empresa'] = $empresa['NOMBREEMPRESA'];
        $usuario = $this->session->userdata("usuario");
        $data['usuario'] = $usuario['NOMBRE'] . " " . $usuario['APELLIDO'];
        $ultimaconexion = $this->session->userdata("ultimaconexion");
        $data['ultimaconexion'] = $ultimaconexion;
        $data['menu'] = "gestion";
        $this->load->view('portal/templates/header2', $data);
        $this->load->view('portal/solicitudGestion/listaGestionEditarSol', $data);
        $this->load->view('portal/templates/footer', $data);
    }

    public function eliminarSolicitud($codSolicitud = null) {
        $this->verificarPerfilCo();
        log_info($this->iniciLog . $this->logHeader . $_SESSION['usuario']);
        log_info($this->logHeader . 'INGRESO ELIMINAR REGISTRO DETALLE SOLICITUD');
        $post = $this->input->post();
        if ($post) {
            if (!empty($post['pkdetalleSolElimi']) && !empty($post['pkCodSol'])) {
                $pksolpost = $post['pkCodSol'];
                log_info($this->postData .
                        '[*] pk_codigo_solicitud=' . $post['pkCodSol']
                        . '[*] Pk_detalle_solicitud=' . $post['pkdetalleSolElimi']);
                $sql = "BEGIN MODPREPEDIDO.MODPREPEDIDOPKGFUNCIONES.PRCELIMINARDETALLESOL(
                    parpksolicitud=>:parpksolicitud,
                    parpkdetallesolicitud=>:parpkdetallesolicitud,
                    parmensajerespuesta=>:parmensajerespuesta,
                    parcodrespuesta=>:parcodrespuesta);
                    END;";

                $conn = $this->db->conn_id;
                $stmt = oci_parse($conn, $sql);
                //Se envia pksolicitud null para que elimine solo el registro detalle solicitud
                $parpkSol;
                oci_bind_by_name($stmt, ':parpksolicitud', $parpkSol, 32);
                $parpkdetalleSol = $post['pkdetalleSolElimi'];
                oci_bind_by_name($stmt, ':parpkdetallesolicitud', $parpkdetalleSol, 32);
                oci_bind_by_name($stmt, ':parmensajerespuesta', $parmensajerespuesta, 500);
                oci_bind_by_name($stmt, ':parcodrespuesta', $parcodrespuesta, 32);
                if (!oci_execute($stmt)) {
                    $e = oci_error($stmt);
                    VAR_DUMP($e);
                    log_info($this->logHeader . ' ERROR ELIMINANDO DETALLESOLICITUD -PRCELIMINARDETALLESOL- '
                            . $e['message'] . '[*] parpkdetallesolicitud=' . $parpkdetalleSol . '[*] parpksolicitud=' . $parpkSol);
                }
                if ($parcodrespuesta == 1) {
                    log_info($this->logHeader . ' RESPUESTA PRCELIMINARDETALLESOL'
                            . '[*] parmensajerespuesta=' . $parmensajerespuesta . '[*] parcodrespuesta=' . $parcodrespuesta);
                    if (isset($post['abonoEliminar']) == 1) {
                        redirect("/portal/solicitudGestion/editarAbono/$pksolpost?eliminar&OK=$parcodrespuesta");
                    } else {
                        redirect("/portal/solicitudGestion/editarSolicitud/$pksolpost?eliminar&OK=$parcodrespuesta");
                    }
                }
            } else {
                redirect("/portal/solicitudGestion/editarSolicitud/$pksolpost?eliminar&error=504");
            }
        } else if (!empty($codSolicitud)) {
            log_info($this->postData .
                    '[*] pk_codigo_solicitud=' . $post['pkCodSol']
                    . '[*] Pk_detalle_solicitud=' . $post['pkdetalleSolElimi']);
            $sql = "BEGIN MODPREPEDIDO.MODPREPEDIDOPKGFUNCIONES.PRCELIMINARDETALLESOL(
                    parpksolicitud=>:parpksolicitud,
                    parpkdetallesolicitud=>:parpkdetallesolicitud,
                    parmensajerespuesta=>:parmensajerespuesta,
                    parcodrespuesta=>:parcodrespuesta);
                    END;";

            $conn = $this->db->conn_id;
            $stmt = oci_parse($conn, $sql);
            //Se envia pksolicitud para eliminar detalle solicitud  asociadas y luego aliminar solicitud
            $parpkSol = $codSolicitud;
            oci_bind_by_name($stmt, ':parpksolicitud', $parpkSol, 32);
            // Se envia null
            $parpkdetalleSol;
            oci_bind_by_name($stmt, ':parpkdetallesolicitud', $parpkdetalleSol, 32);
            oci_bind_by_name($stmt, ':parmensajerespuesta', $parmensajerespuesta, 500);
            oci_bind_by_name($stmt, ':parcodrespuesta', $parcodrespuesta, 32);
            if (!oci_execute($stmt)) {
                $e = oci_error($stmt);
                VAR_DUMP($e);
                log_info($this->logHeader . ' ERROR ELIMINANDO DETALLESOLICITUD -PRCELIMINARDETALLESOL- '
                        . $e['message'] . '[*] parpkdetallesolicitud=' . $parpkdetalleSol . '[*] parpksolicitud=' . $parpkSol);
            }
            if ($parcodrespuesta == 1) {
                log_info($this->finFuncion . 'FIN ELIMINAR DETALLESOLICITUD' . 'parcodrespuesta =' . $parcodrespuesta . ' mensajerespuesta=' . $parmensajerespuesta);
                redirect("/portal/solicitudGestion/solicitudGes?sol=$parpkSol&eliminar&OK=$parcodrespuesta");
            }
        }
    }

    public function editarAbono($codSolicitud) {
        $this->verificarPerfilCo();
        log_info($logHeader . 'INGRESO EDITAR ABONO PREPEDIDO');
        log_info($logHeader . ' DATA ENTRADA EDITAR ABONO PREPEDIDO'
                . ' codSolicitud ' . $codSolicitud);
        //$usuario = $this->session->userdata("usuario");
        $usuario = $_SESSION['usuario'];
        //$empresa = $this->session->userdata("entidad");
        $empresa = $_SESSION['entidad'];
        log_info($this->iniciLog . $this->logHeader . $_SESSION['usuario']);

        $post = $this->input->post();
        if ($post) {
            log_info($logHeader . 'INGRESO POST EDITAR ABONO PREPEDIDO');
            log_info('POSTDATA:::::::::'
                    . '-Pk_codigo_solicitud= ' . $post['pksolicitud']
                    . '-Pk_detalle_solicitud= ' . $post['pkdetalle']
                    . '-Tipo_doc= ' . $post['tipoDoc']
                    . '-Documento= ' . $post['Documento']
                    . '-Producto= ' . $post['producto']
                    . '-PKProducto= ' . $post['pkproducto']
                    . '-Monto_abono= ' . $post['valorAbono']
                    . '-Fecha_dispersion= ' . $post['fechaDisp']
                    . '-Identificador= ' . $post['identificador']);
            $parpkproducto = $post['pkproducto'];
            $sql = "BEGIN MODPREPEDIDO.MODPREPEDIDOPKGFUNCIONES.prccreardetsolicitudabono(
                    parpkproducto=>:parpkproducto,
                    parpktdcodigo =>:parpktdcodigo,
                    pardocumento=>:pardocumento,
                    parmonto=>:parmonto,
                    parfechadispersion=>:parfechadispersion,
                    paridentificador=>:paridentificador,
                    parpkcodigosolicitud=>:parpkcodigosolicitud,
                    parpkdetallesol=>:parpkdetallesol,
                    parpkempresa=>:parpkempresa,
                    parmensajerespuesta=>:parmensajerespuesta,
                    parrespuesta=>:parrespuesta);
                    END;";

            $parpktd = $post['tipoDoc'];
            $pardocumento = $post['Documento'];
            //Convertir solo numeros
            $parmonto = $this->dejarSoloCaracteresDeseados($post['valorAbono'], "0123456789");
            $parfechaDisp = date_format(date_create($post['fechaDisp']), 'd-M-Y');
            $paridentificador = strtoupper($post['identificador']);
            $parpkcodsolicitud = $post['pksolicitud'];
            $parpkdetallesol = $post['pkdetalle'];
            //$pkEmpresa = $this->session->userdata("pkentidad");
            $pkEmpresa = $_SESSION['pkentidad'];

            $conn = $this->db->conn_id;
            $stmt = oci_parse($conn, $sql);
            oci_bind_by_name($stmt, ':parpkproducto', $parpkproducto, 32);
            oci_bind_by_name($stmt, ':parpktdcodigo', $parpktd, 32);
            oci_bind_by_name($stmt, ':pardocumento', $pardocumento, 32);
            oci_bind_by_name($stmt, ':parmonto', $parmonto, 32);
            oci_bind_by_name($stmt, ':parfechadispersion', $parfechaDisp, 32);
            oci_bind_by_name($stmt, ':paridentificador', $paridentificador, 32);
            oci_bind_by_name($stmt, ':parpkcodigosolicitud', $parpkcodsolicitud, 32);
            oci_bind_by_name($stmt, ':parpkdetallesol', $parpkdetallesol, 32);
            oci_bind_by_name($stmt, ':parpkempresa', $pkEmpresa, 32);
            oci_bind_by_name($stmt, ':parmensajerespuesta', $parmensajerespuesta, 500);
            oci_bind_by_name($stmt, ':parrespuesta', $parcodrespuesta, 32);
            if (!oci_execute($stmt)) {
                $e = oci_error($stmt);
                VAR_DUMP($e);
                log_info($this->logHeader . ' ERROR EDITANDO DETALLESOLICITUD '
                        . $e['message'] . ' PARDETALLESOLICITUD ' . $parpkdetalleSol);
                $data['error'] = 4;
                $data['respues'] = 'No se puede editar la información en estos momentos. ';
            }
            if ($parcodrespuesta == 1) {
                $data['actualizacion'] = 1;
            } else {
                $data['errorS'] = $parcodrespuesta;
                $data['msgError'] = $parmensajerespuesta;
            }
        }

        if (!empty($codSolicitud)) {
            $coordinador = $usuario["PK_ENT_CODIGO"];
            //$campana = $this->session->userdata("campana");
            $campana = $_SESSION['campana'];
            //$pkEmpresa = $this->session->userdata("pkentidad");
            $pkEmpresa = $_SESSION['pkentidad'];
            $detalleSolicitudes = $this->db->query("select 
                                    tipdoc.abreviacion TIPDOC,det.documento DOCUMENTO,
                                    pro.nombre_producto PRODUCTO, 
                                    det.pk_codigo_solicitud,det.pk_detalle_solicitud,
                                    det.identificador_tarjeta,
                                    det.monto_abono,
                                    det.fecha_dispersion
                                    from MODPREPEDIDO.PREPETBLDETALLESOLICITUD det 
                                    join modproduc.PROTBLPRODUC pro on pro.pk_produc_codigo = det.pk_producto
                                    join MODPREPEDIDO.prepetblsolicitud sol ON det.pk_codigo_solicitud=sol.pk_codigo_solicitud
                                    left JOIN MODCLIUNI.CLITBLTIPDOC TIPDOC  on tipdoc.pk_td_codigo= det.clitbltipdoc_pk_td_codigo
                                    where sol.pk_ent_solicitud = $coordinador and sol.pk_emp_solicitud = $pkEmpresa
                                    and sol.pk_campana_codigo = $campana
                                    and det.pk_codigo_solicitud = $codSolicitud");
            $data['abonos'] = $detalleSolicitudes->result_array;
        }

        $data['codSolicitud'] = $codSolicitud;
        $data['empresa'] = $empresa['NOMBREEMPRESA'];
        //$usuario = $this->session->userdata("usuario");
        $usuario = $_SESSION['usuario'];
        $data['usuario'] = $usuario['NOMBRE'] . " " . $usuario['APELLIDO'];
        //$ultimaconexion = $this->session->userdata("ultimaconexion");
        $ultimaconexion = $_SESSION['ultimaconexion'];
        $data['ultimaconexion'] = $ultimaconexion;
        $data['menu'] = "gestion";
        $this->load->view('portal/templates/header2', $data);
        $this->load->view('portal/solicitudGestion/listaGestionEditarAbo', $data);
        $this->load->view('portal/templates/footer', $data);
    }

    public function listaGestion($pedido = 0, $error = 0) {
        $this->verificarPerfilCo();
        //$empresa = $this->session->userdata("entidad");
        $empresa = $_SESSION['entidad'];
        $data['empresa'] = $empresa['NOMBREEMPRESA'];
        //$usuario = $this->session->userdata("usuario");
        $usuario = $_SESSION['usuario'];
        $data['usuario'] = $usuario['NOMBRE'] . " " . $usuario['APELLIDO'];

        $pedidos = $this->db->query("select det.pk_tar_habiente PK_TARHAB,det.PK_DETPED_CODIGO,ent.nombre||' ' ||ent.apellido TARJETA_HABIENTE,
                                    ent.documento DOCUMENTO,pro.nombre_producto PRODUCTOS
                                    from MODALISTA.alitbldetped det 
                                    join modcliuni.clitblentida ent on ent.pk_ent_codigo = det.pk_tar_habiente
                                    join modproduc.PROTBLPRODUC pro on pro.pk_produc_codigo = det.pk_producto
                                    where pk_pedido = $pedido");
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

        //$ultimaconexion = $this->session->userdata("ultimaconexion");
        $ultimaconexion = $_SESSION['ultimaconexion'];
        $data['ultimaconexion'] = $ultimaconexion;
        log_info($iniciLog . $finFuncion);
        $data['menu'] = "gestion";
        $this->load->view('portal/templates/header2', $data);
        $this->load->view('portal/solicitudGestion/listaGestion', $data);
        $this->load->view('portal/templates/footer', $data);
    }

    public function finalizarOrden() {
        $this->verificarPerfilCo();
        $post = $this->input->post();
        log_info($logHeader . ' FINALIZAR PEDIDO  ' . $ultimaconexion);
        if ($post) {
            $nomOrden = $post['nombreOrden'];
            //aqui se retornara la respuesta de crear nombre orden 
            $parrespuesta = 1;
            redirect("/portal/solicitudGestion/listaGestion/?res=$parrespuesta&nom=$nomOrden");
        }
    }

    public function descargarPlantilla() {
        $this->verificarPerfilCo();
        $path = '/uploads/ARCHIVO-PEDIDO-MASIVO-DE-TARJETAS.xlsx';
        header("Location:" . $path);
    }

    public function solicitudTarjetasMasivo($respuesta = null, $codigo = null) {
        $this->verificarPerfilCo();
        //$post = $this->input->post();

        //$empresa = $this->session->userdata("entidad");
        $empresa = $_SESSION['entidad'];
        $data['empresa'] = $empresa['NOMBREEMPRESA'];
        //$usuario = $this->session->userdata("usuario");
        $usuario = $_SESSION['usuario'];
        log_info($iniciLog . '  ' . $usuario);
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
        log_info($iniciLog . $finFuncion);
        $data['menu'] = "gestion";
        $this->load->view('portal/templates/header2', $data);
        $this->load->view('portal/solicitudGestion/solicitudTarjetasMasivo', $data);
        $this->load->view('portal/templates/footer', $data);
    }

    public function solicitudMasiva() {

        $this->verificarPerfilCo();
        $post = $this->input->post();
        if ($post || $_FILES) {
            $date = date('Y_m_d');
            $random = rand(1000, 9999);
            $name = strtolower($date . '_' . $random);
            $tmp_name = $name;
            $BLOB_CONTENT = file_get_contents($_FILES['file']['tmp_name']);
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
                echo $sql;
                echo $name;
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

            $sql = "BEGIN MODGENERI.GENPKGWEBSERVICE.cargar_masivo(:parnomarch
              ,:parentidad
              ,:parusuario
              ,:parcoordinador
              ,:parcustodio
              ,:parcampana
              ,:parordcon
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
            $parcampana = $this->session->userdata("campana");
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
            oci_bind_by_name($stmt, ':parcustodio', $parcustodio, 100);
            //TIPO VARCHAR2 INPUT
            oci_bind_by_name($stmt, ':parcampana', $parcampana, 100);
            //TIPO VARCHAR2 INPUT
            oci_bind_by_name($stmt, ':parordcon', $parcodigoorden, 100);
            //TIPO VARCHAR2 OUTPUT
            oci_bind_by_name($stmt, ':parrespuesta', $parrespuesta, 100);



            if (!@oci_execute($stmt)) {
                $e = oci_error($stmt);
                $mensaje = explode(":", $e['message']);
                var_dump($mensaje);
                $data['error'] = 1;
            } elseif ($parrespuesta != 1) {
                redirect("portal/solicitudGestion/mensajeError/$parrespuesta/$pararchivo");
            } elseif ($parrespuesta == 1) {
                //
                $codigo = $parcodigoorden;
                redirect("portal/solicitudGestion/solicitudTarjetasMasivo/$parrespuesta/$codigo");
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
        $this->load->view('portal/solicitudGestion/mensajeError', $data);
        $this->load->view('portal/templates/footer', $error);
    }

    public function generarOrden($solicitudes = null, $pkorden = null, $descarga = null) {
        $this->verificarPerfilCo();
        //$empresa = $this->session->userdata("entidad");
        $empresa = $_SESSION['entidad'];
        log_info($this->logHeader . ' INGRESO CREACION PREORDEN Y ASIGANCION SOLICITUDES');
        log_info($this->iniciLog . $this->logHeader . $this->session->userdata("usuario"));
        log_info($this->logHeader . ' INGRESO CREACION PREORDEN Y ASIGANCION SOLICITUDES  ' . $empresa['NOMBREEMPRESA']);
        //$usuario = $this->session->userdata("usuario");
        $usuario = $_SESSION['usuario'];
        if (!empty($solicitudes)) {
            log_info($this->postData . ' SOLICITUDES  ' . $solicitudes);

            $solicitudes = str_replace('-', ',', $solicitudes);
            if (empty($pkorden) || $pkorden == 'null') {

                $sql = "BEGIN MODPREPEDIDO.MODPREPEDIDOPKGFUNCIONES.PRCCREARACTUPREORDEN(
                     :parpkempresa
                    ,:parpkestadopreorden
                    ,:parusuariocreacion
                    ,:parpkpreordencodigo
                    ,:parmensajerespuesta
                    ,:parcodrespuesta
                    ); END;";

                $conn = $this->db->conn_id;
                $stmt = oci_parse($conn, $sql);
                $estadoPreorden = 1; //generado
                //$parempresa = $this->session->userdata("pkentidad");
                $parempresa = $_SESSION['pkentidad'];
                $pkpreorden;
                $parusuario = $usuario['USUARIO_ACCESO'];
                oci_bind_by_name($stmt, ':parpkempresa', $parempresa, 100);
                oci_bind_by_name($stmt, ':parpkestadopreorden', $estadoPreorden, 100);
                oci_bind_by_name($stmt, ':parusuariocreacion', $parusuario, 100);
                oci_bind_by_name($stmt, ':parpkpreordencodigo', $pkpreorden, 100);
                oci_bind_by_name($stmt, ':parmensajerespuesta', $parmensajerespuesta, 100);
                oci_bind_by_name($stmt, ':parcodrespuesta', $parcodrespuesta, 100);
                log_info($this->postData . 'parpkempresa =' . $parempresa
                        . ' estadoPreorden =' . $estadoPreorden
                        . ' parusuariocreacion =' . $parusuariocreacion);
                if (!@oci_execute($stmt)) {
                    $e = oci_error($stmt);
                    $mensaje = explode(":", $e['message']);
                    log_info($this->logHeader . 'Error crear generarorden =' . $mensaje);
                } else if ($parcodrespuesta == 1) {
                    log_info($this->finFuncion . ' RESPUESTA CREACION PREORDEN '
                            . ' parmensajerespuesta ' . $parmensajerespuesta
                            . ' parrespuesta ' . $parcodrespuesta
                            . ' pkpreorden ' . $pkpreorden);
                    $pkpreorden = $pkpreorden;
                }
            } else {
                $pkpreorden = $pkorden;
            }

            if (!empty($pkpreorden)) {
                $porciones = explode(",", $solicitudes);
                log_info($this->logHeader . ' INGRESO ACTUALIZAR PRCACTUALIZASOLORDNULL PKORDEN ' . $pkpreorden);

                //actualizar solicitudes vinculadas a la orden 
                $sql = "BEGIN MODPREPEDIDO.MODPREPEDIDOPKGFUNCIONES.PRCACTUALIZASOLORDNULL(
                     :parpkpreordencodigo
                    ,:parmensajerespuesta
                    ,:parcodrespuesta
                    ); END;";

                $conn = $this->db->conn_id;
                $stmt = oci_parse($conn, $sql);
                oci_bind_by_name($stmt, ':parpkpreordencodigo', $pkpreorden, 100);
                oci_bind_by_name($stmt, ':parmensajerespuesta', $parmensajerespuesta, 100);
                oci_bind_by_name($stmt, ':parcodrespuesta', $parcodrespuestaAct, 100);
                if (!@oci_execute($stmt)) {
                    $e = oci_error($stmt);
                    $mensaje = explode(":", $e['message']);
                    log_info($this->logHeader . ' ERROR PRCACTUALIZASOLORDNULL MENSAJE ' . $e['message']);
                } else if ($parcodrespuestaAct == 1) {
                    log_info($this->finFuncion . ' RESPUESTA PRCACTUALIZASOLORDNULL ACTUALIZAR SOLICITUDORDEN NULL  '
                            . ' parmensajerespuesta ' . $parmensajerespuesta
                            . ' parrespuesta ' . $parcodrespuestaAct
                            . ' pkpreorden ' . $pkpreorden);
                }

                if ($parcodrespuestaAct == 1) {

                    foreach ($porciones as $value) {
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
                        $parnombresolicitud;
                        $parcampana;
                        $parpkpreorden = $pkpreorden;
                        $parpktiposolicitud;
                        $parpkcodigosolicitud = $value;
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
                            log_info($this->logHeader . ' RESPUESTA ACTUALIZAR SOLICITUD CREACION PREORDEN '
                                    . ' parmensajerespuesta ' . $parmensajerespuesta
                                    . ' parrespuesta ' . $parrespuesta);
//                        $data['ok'] = 1;
                        }
                    }
                }
                if (!empty($descarga)) {
                    redirect("/portal/solicitudGestion/generarOrden?res=$parrespuesta&orden=$pkpreorden&descarga=$descarga");
                } else {
                    redirect("/portal/solicitudGestion/generarOrden?res=$parrespuesta&orden=$pkpreorden");
                }
//                redirect("/portal/solicitudGestion/generarOrden?res=$parrespuesta&orden=$pkpreorden");
            }//termina if si pkorden no es null
        }

        //$usuario = $this->session->userdata("usuario");
        $usuario = $_SESSION['usuario'];
        $coordinador = $usuario["PK_ENT_CODIGO"];
        //$campana = $this->session->userdata("campana");
        $campana = $_SESSION['campana'];
        $ordenes = $this->db->query("select DISTINCT( ord.pk_preorden_codigo) orden, ord.pk_estado_preorden estado
            from modprepedido.prepetblpreorden ord
            left join modprepedido.prepetblsolicitud sol ON ord.pk_preorden_codigo=sol.pk_preorden_codigo
            where sol.pk_campana_codigo = $campana
            and sol.pk_ent_solicitud=$coordinador
            order by 1 desc");
        $data['ordenes'] = $ordenes->result_array;
        //$empresa = $this->session->userdata("entidad");
        $empresa = $_SESSION['entidad'];
        $data['empresa'] = $empresa['NOMBREEMPRESA'];
        $data['menu'] = "gestion";
        $data['usuario'] = $usuario['NOMBRE'] . " " . $usuario['APELLIDO'];
        //$ultimaconexion = $this->session->userdata("ultimaconexion");
        $ultimaconexion = $_SESSION['ultimaconexion'];
        $data['ultimaconexion'] = $ultimaconexion;
        $this->load->view('portal/templates/header2', $data);
        $this->load->view('portal/solicitudGestion/ordenGenerada', $data);
        $this->load->view('portal/templates/footer', $data);
    }

    public function eliminarPreorden($orden = null) {
        $this->verificarPerfilCo();
        //$empresa = $this->session->userdata("entidad");
        $empresa = $_SESSION['entidad'];
        log_info($this->logHeader . ' INGRESO ELIMINAR ORDEN' . $empresa['NOMBREEMPRESA']);
        log_info($this->iniciLog . $this->logHeader . $this->session->userdata("usuario"));
        if (!empty($orden)) {
            $sql = "BEGIN MODPREPEDIDO.MODPREPEDIDOPKGFUNCIONES.PRCELIMINARPREORDENSOL(
                    :parpkpreordencodigo
                    ,:parmensajerespuesta
                    ,:parcodrespuesta
                    ); END;";

            $conn = $this->db->conn_id;
            $stmt = oci_parse($conn, $sql);
            $pkpreorden = $orden;
            oci_bind_by_name($stmt, ':parpkpreordencodigo', $pkpreorden, 100);
            oci_bind_by_name($stmt, ':parmensajerespuesta', $parmensajerespuesta, 100);
            oci_bind_by_name($stmt, ':parcodrespuesta', $parcodrespuesta, 100);

            if (!@oci_execute($stmt)) {
                $e = oci_error($stmt);
                $mensaje = explode(":", $e['message']);
            } else if ($parcodrespuesta == 1) {
                log_info($logHeader . ' RESPUESTA ELIMINAR PREORDEN '
                        . ' parmensajerespuesta ' . $parmensajerespuesta
                        . ' parrespuesta ' . $parcodrespuesta
                        . ' pkpreorden ' . $pkpreorden);
                redirect("/portal/solicitudGestion/generarOrden?eli=$parcodrespuesta&orden=$pkpreorden");
            }
        }
    }

    public function facturarPreorden($preorden = null) {
        log_info($this->logHeader . 'FACTURAR PREORDEN ' . $preorden);
        $this->verificarPerfilCo();
        //$empresa = $this->session->userdata("entidad");
        $empresa = $_SESSION['entidad'];
        $data['empresa'] = $empresa['NOMBREEMPRESA'];
        //$usuario = $this->session->userdata("usuario");
        $usuario = $_SESSION['usuario'];
        $data['usuario'] = $usuario['NOMBRE'] . " " . $usuario['APELLIDO'];
        $data['tarjetaHabiente'] = $tarjetaHabiente->result_array;
        //$ultimaconexion = $this->session->userdata("ultimaconexion");
        $ultimaconexion = $_SESSION['ultimaconexion'];
        $data['ultimaconexion'] = $ultimaconexion;
        $post = $this->input->post();
        $observ = $post['observacion'];

        if ($preorden != null) {
            $parpkpreorden = $preorden;
            if (!empty($observ)) {
                $sql = "BEGIN modprepedido.MODPREPEDIDOPKGFUNCIONES.PRCACTUALIZAOBSERPREORDEN ( 
                            parpkpreordencodigo=>:parpkpreordencodigo
                            ,parobservacion=>:parobservacion
                            ,parcodrespuesta=>:parcodrespuesta);
                    END;";

                $conn = $this->db->conn_id;
                $stmt = oci_parse($conn, $sql);
                oci_bind_by_name($stmt, ':parpkpreordencodigo', $parpkpreorden, 32);
                oci_bind_by_name($stmt, ':parobservacion', $observ, 250);
                oci_bind_by_name($stmt, ':parcodrespuesta', $parcodrespuesta, 32);
                if (!oci_execute($stmt)) {
                    $e = oci_error($stmt);
                    VAR_DUMP($e);
                    log_info($this->logHeader . 'ERROR ACTUALIZANDO OBSERVACION DE PREORDEN ' . $preorden . ' ' . $e['message']);
                } else if ($parcodrespuesta == 1) {
                    log_info($this->logHeader . ' SE ACTUALIZO LA OBSERVACION EN LA PREORDEN ' . $preorden . ' OBSERVACION= ' . $observ);
                }
            }
            $sql = "BEGIN modprepedido.pkgactualizaciones.prcfacturarprepedido ( 
                            parpkpreorden =>:parpkpreorden
                            ,parusuario   =>:parusuario
                            ,parpkentida  =>:parpkentida
                            ,parpkcampana =>:parpkcampana
                            ,parcoordinador =>:parcoordinador
                            ,parcodigorespuesta =>:parcodigorespuesta
                            ,parmensajerespuesta => :parmensajerespuesta);
                    END;";

            $conn = $this->db->conn_id;
            $stmt = oci_parse($conn, $sql);

            //$usuario = $this->session->userdata("usuario");
            $usuario = $_SESSION['usuario'];
            $parusuario = $usuario['USUARIO_ACCESO'];
            $coordinador = $usuario["PK_ENT_CODIGO"];
            //$pkEmpresa = $this->session->userdata("pkentidad");
            $pkEmpresa = $_SESSION['pkentidad'];
            //$parcampana = $this->session->userdata("campana");
            $parcampana = $_SESSION['campana'];


            oci_bind_by_name($stmt, ':parpkpreorden', $parpkpreorden, 32);
            oci_bind_by_name($stmt, ':parpkentida', $pkEmpresa, 32);
            oci_bind_by_name($stmt, ':parcoordinador', $coordinador, 32);
            oci_bind_by_name($stmt, ':parpkcampana', $parcampana, 32);
            oci_bind_by_name($stmt, ':parusuario', $parusuario, 32);
            oci_bind_by_name($stmt, ':parcodigorespuesta', $parrespuesta, 32);
            oci_bind_by_name($stmt, ':parmensajerespuesta', $parmensajerespuesta, 4000);
            if (!oci_execute($stmt)) {
                $e = oci_error($stmt);
                VAR_DUMP($e);
                log_info($this->logHeader . 'ERROR PROCESANDO LA FACTURACION DE PREORDEN ' . $preorden . ' ' . $e['message']);
                $parrespuesta = 0;
                $parmensajerespuesta = 'No se pudo facturar la orden, Codigo de sistema: ' . $e['code'];
            } else if ($parrespuesta != 1) {
                log_info($this->logHeader . 'ERROR PROCESANDO LA FACTURACION DE PREORDEN ' . $preorden . ' ' . $parrespuesta . 'MENSAJE' . $parmensajerespuesta);
                // var_dump($parrespuesta);
                //  echo '<br/>';
                //  var_dump($pkEmpresa);
                // exit();
                $data['codigoMensaje'] = $parrespuesta;
                $data['mensajeSistema'] = $parmensajerespuesta;
                $errores = $this->db->query("SELECT LINEA_ARCHIVO,DATO,DESCRIPCION 
                                FROM MODGENERI.gentblerrcar 
                                WHERE ARCHIVO = trim($parpkpreorden)
                                order by LINEA_ARCHIVO");
                $data['errores'] = $errores->result_array;
                //lo saca y monta la vista con el error
            }
            // $parrespuesta = 1;
            //$parmensajerespuesta = 'Facturado correctamente';
            if ($parrespuesta == 1) {
                log_info($this->logHeader . 'SOLICITUD DE FACTURACION CORRECTA '
                        . ' parrespuest ' . $parrespuesta
                        . ' parmensajerespuesta ' . $parmensajerespuesta
                        . ' parpkcodigosolicitud ' . $parpkcodigosolicitud);
                $parpkcodigosolicitud = $parpkcodigosolicitud;
                $data['codigoMensaje'] = $parrespuesta;
                $data['mensajeSistema'] = $parmensajerespuesta;
            } else {
                $data['codigoMensaje'] = $parrespuesta;
                $data['mensajeSistema'] = $parmensajerespuesta;
            }
        } else {
            $data['codigoMensaje'] = 0;
            $data['mensajeSistema'] = 'No seleccionó ninguna orden a facturar';
            echo 'no factura';
            //exit();
        }
        $data['menu'] = "gestion";
        $this->load->view('portal/templates/header2', $data);
        $this->load->view('portal/solicitudGestion/facturarPreorden', $data);
        $this->load->view('portal/templates/footer', $data);
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

}
