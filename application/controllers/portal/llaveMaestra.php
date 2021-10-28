<?php

session_start();
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class LlaveMaestra extends CI_Controller {

    public $dataLlave = 'DATA LLAVE MAESTRA::::::::: ';
    public $errorLlave = 'ERROR LLAVE MAESTRA::::::::: ';

    public function __construct() {
        parent::__construct();
//        if ($this->session->userdata('entidad') == NULL) {
        if ($_SESSION['entidad'] == NULL) {
            redirect('/');
        }
        $this->load->helper('log4php');
    }

    public function ingreso($pantalla = 0) {
        //$empresa = $this->session->userdata("entidad");
        $empresa = $_SESSION['entidad'];
        $data['empresa'] = $empresa['NOMBREEMPRESA'];
        //$usuario = $this->session->userdata("usuario");
        $usuario = $_SESSION['usuario'];
        $data['usuario'] = $usuario['NOMBRE'] . " " . $usuario['APELLIDO'];
        //$ultimaconexion = $this->session->userdata("ultimaconexion");
        $ultimaconexion = $_SESSION['ultimaconexion'];
        /*
         * 
         * consumo ejemplo
         */

//insert message client
//        $sql = "BEGIN MODLLAVEMAESTRA.LLAVMAEPKGGENERAL.asociarllavemaestra(
//                    parsaldollave=>:parsaldollave,
//                    parperiodofacturacion=>:parperiodofacturacion,
//                    parfechacreacion=>:parfechacreacion,
//                    parusuariocreacion=>:parusuariocreacion,
//                    parlimitereverso=>:parlimitereverso,
//                    parlimiteabono=>:parlimiteabono,
//                    parcupomaximo=>:parcupomaximo,
//                    parentcodigo=>:parentcodigo,
//                    parrespuesta=>:parrespuesta);
//                    END;";
//        $conn = $this->db->conn_id;
//        $stmt = oci_parse($conn, $sql);

        $sql2 = "BEGIN MODLLAVEMAESTRA.LLAVMAEPKGGENERAL.ejemplo(
                    parejemplo=>:parejemplo,
                    parrespuesta=>:parrespuesta);
                    END;";
        $conn2 = $this->db->conn_id;
        $stmt = oci_parse($conn2, $sql2);
        //$partouserid = $this->session->userdata['dataChat']['SAC_USER_ID'];
        $partouserid = $_SESSION['dataChat']['SAC_USER_ID'];
        //$parclienteid = $this->session->userdata['usuario']['PK_ENT_CODIGO'];
        $parclienteid = $_SESSION['usuario']['PK_ENT_CODIGO'];
        $parestado = 1;
        $parprueba = 1;
        oci_bind_by_name($stmt, ':parejemplo', $parprueba, 32);
//        oci_bind_by_name($stmt, ':parsaldollave', $parprueba, 32);
//        oci_bind_by_name($stmt, ':parperiodofacturacion', $parprueba, 32);
//        oci_bind_by_name($stmt, ':parfechacreacion', $parprueba, 32);
//        oci_bind_by_name($stmt, ':parusuariocreacion',$parprueba, 32);
//        oci_bind_by_name($stmt, ':parlimitereverso', $parprueba, 32);
//        oci_bind_by_name($stmt, ':parlimiteabono', $parprueba, 32);
//        oci_bind_by_name($stmt, ':parcupomaximo', $parprueba, 32);
//        oci_bind_by_name($stmt, ':parentcodigo', $parprueba, 32);
        oci_bind_by_name($stmt, ':parrespuesta', $parrespuesta, 32);
        if (!@oci_execute($stmt)) {
            $e = oci_error($stmt);
            var_dump($e);
        }
        if ($parrespuesta == 1) {
            var_dump($parrespuesta);
        }
        var_dump($parrespuesta);

        $data['saldo'] = 25000000;
        $data['llaveMaestra'] = 1;
        $data['mostrar'] = 'si';

        $tipodocumento = $this->db->query('SELECT PK_TD_CODIGO,ABREVIACION,NOMBRE FROM MODCLIUNI.CLITBLTIPDOC');
        $data['tipoDocumento'] = $tipodocumento->result_array;

        $this->load->view('portal/templates/headerllave', $data);
        $this->load->view('portal/llave/ingreso', $data);
        $this->load->view('portal/templates/footer', $data);
    }

    public function principal($pantalla = 0, $modificar = '') {
        //$this->session->set_userdata(array("pedidoAbono" => null));
        //$this->session->set_userdata(array("llavesTemp" => null));
        $_SESSION['pedidoAbono'] = null;
        $_SESSION['llavesTemp'] = null;
        $rol = $_SESSION['rol'];
        $producto = $_SESSION['PRODUCTOLLAVE']['CODIGO_PRODUCTO'];
        if (($rol == 59 || $rol == 60 ||$rol == 61) && $producto == 70) {
            //$empresa = $this->session->userdata("entidad");
            $empresa = $_SESSION['entidad'];
            $data['empresa'] = $empresa['NOMBREEMPRESA'];
            $pk_ent_codigo = $empresa['PK_ENT_CODIGO'];
//            var_dump($this->session->userdata);
//            print_r('pkentidad=' . $pkEntidad . ', documento=' . $documento . ', tipo=' . $tipodocumento . ',ROL=' . $rol = $this->session->userdata("rol"));
//            MODGENERI.GENPKGCLAGEN.DECRYPT(varencsaldo)
            $sql = "BEGIN MODLLAVEMAESTRA.LLAVMAEPKGGENERAL.saldollavemaestra(
                    parentcodigo =>:parentcodigo,
                    parsaldo =>:parsaldo,
                    parrespuesta=>:parrespuesta);
                    END;";

            $conn = $this->db->conn_id;
            $stmt = oci_parse($conn, $sql);
            $parpk_entidad = $pk_ent_codigo;
            oci_bind_by_name($stmt, ':parentcodigo', $parpk_entidad, 32);
            oci_bind_by_name($stmt, ':parsaldo', $parsaldo, 32);
            oci_bind_by_name($stmt, ':parrespuesta', $parrespuesta, 32);
            if (!oci_execute($stmt)) {
                $e = oci_error($stmt);
                VAR_DUMP($e);
            }
            if ($parrespuesta == 1) {
                $data['saldo'] = $parsaldo;
                $data['saldocanje'] = 0;
            }
            $activo = $this->db->query("SELECT llavmae_codigo pk_llave_maestra FROM MODLLAVEMAESTRA.LLAVETBLLLAVMAE llavemae WHERE llavemae.pk_ent_codigo= $pk_ent_codigo");
            $pk_llave_maestra = $activo->result_array[0];
            //$this->session->set_userdata(array('PK_LLAVE_MAESTRA' => $pk_llave_maestra['PK_LLAVE_MAESTRA']));
            $_SESSION['PK_LLAVE_MAESTRA'] = $pk_llave_maestra['PK_LLAVE_MAESTRA'];
            //$usuario = $this->session->userdata("usuario");
            $usuario = $_SESSION['usuario'];
            $data['usuario'] = $usuario['NOMBRE'] . " " . $usuario['APELLIDO'];
            //$ultimaconexion = $this->session->userdata("ultimaconexion");
            $ultimaconexion = $_SESSION['ultimaconexion'];
            $data['ultimaconexion'] = $ultimaconexion;
            $data['llaveMaestra'] = 1;
            $this->load->view('portal/templates/headerllave', $data);
            $this->load->view('portal/llave/principal', $data);
            $this->load->view('portal/templates/footer', $data);
        } else {
            redirect("/portal/principal/pantalla");
        }
    }

//Asocia tarjeta-habiente en un llavero
    public function asociacion($pantalla = 0) {
        $this->verificarllaveMestraNuevosPerfiles();
        //$empresa = $this->session->userdata("entidad");
        $empresa = $_SESSION['entidad'];
        $pk_ent_codigo = $empresa['PK_ENT_CODIGO'];
        if ($pantalla == 1) {
            $post = $this->input->post();
            //$usuario = $this->session->userdata("usuario");
            $usuario = $_SESSION['usuario'];
            $usuarioactualizacion = $usuario['USUARIO_ACCESO'];
            foreach ($post['usuarios'] as $key => $value) {
                $sql = "BEGIN MODLLAVEMAESTRA.LLAVMAEPKGGENERAL.prcasociartarjetath(
                        :parproductocodigo, 
                        :parllaverocodigo, 
                        :parentidadcodigo,
                        :parusuariocreacion,
                        :parpktarjetacodigo,
                        :parrespuesta
                        );
                        END;";

                $dataUserTh = explode(",", $value);
                $conn = $this->db->conn_id;
                $stmt = oci_parse($conn, $sql);
                $parproductocodigo = $dataUserTh[0];
                $parllaverocodigoc = $post['pk_llavero_codigo'];
                $parentidadcodigo = $dataUserTh[1];
                $numeroTar = $dataUserTh[2];
                oci_bind_by_name($stmt, ':parusuariocreacion', $usuarioactualizacion, 32);
                oci_bind_by_name($stmt, ':parproductocodigo', $parproductocodigo, 32);
                oci_bind_by_name($stmt, ':parllaverocodigo', $parllaverocodigoc, 32);
                oci_bind_by_name($stmt, ':parentidadcodigo', $parentidadcodigo, 32);
                oci_bind_by_name($stmt, ':parpktarjetacodigo', $numeroTar, 32);
                oci_bind_by_name($stmt, ':parrespuesta', $parrespuesta, 32);
                if (!oci_execute($stmt)) {
                    $e = oci_error($stmt);
                    VAR_DUMP($e);
//exit;
                } else if ($parrespuesta != 1) {
                    $PARRESPUE = 'ERROR ' . $parrespuesta;
//lo saca y monta la vista con el error
                } elseif ($parrespuesta == 1) {
                    $data['accionOut'] = '1';
                }
            }
        }
//        $post = $this->input->post();
//        if ($post && !empty($post['pk_llavero'])) {
//            $pk_llavero = $post['pk_llavero'];
//            $empresa = $this->session->userdata("entidad");
//            $tarjetaHabiente = $this->db->query(" select vista2.* from(select vista.nomtar, vista.abr, vista.doc, vista.NOMPRO, vista.CODPROD, vista.codth, vista.NUMTAR, vista.codtar,asot.* from (SELECT ent.documento DOC,
//            ent.pk_ent_codigo CODTH,
//            tipdoc.abreviacion abr, 
//            NVL(TO_CHAR(tar.fecha_creacion,'DD/MM/YYYY'),'PENDIENTE') FEC,
//            ent.nombre ||' '||ent.apellido nomtar, TAR.NUMERO NUMTAR,
//            tar.pk_tarjet_codigo codtar,tar.pk_esttar_codigo,
//            PRO.NOMBRE_PRODUCTO NOMPRO,
//            pro.pk_produc_codigo codprod
//            FROM MODTARHAB.tartbltarjet tar 
//            join MODTARHAB.TARTBLCUENTA CUE 
//            ON cue.pk_tartblcuenta_codigo = tar.pk_tartblcuenta_codigo 
//            AND cue.PK_ENT_CODIGO_EMP = {$empresa['PK_ENT_CODIGO']}
//            JOIN MODCLIUNI.CLITBLENTIDA ENT 
//            ON ent.pk_ent_codigo = cue.pk_ent_codigo_th 
//            JOIN MODCLIUNI.CLITBLTIPDOC TIPDOC 
//            ON tipdoc.pk_td_codigo = ent.clitbltipdoc_pk_td_codigo 
//            JOIN MODPRODUC.PROTBLPRODUC PRO 
//            ON pro.pk_produc_codigo = cue.pk_produc_codigo 
//            JOIN MODALISTA.ALITBLDETPED DETPED 
//            ON detped.pk_detped_codigo = tar.pk_detped_codigo 
//            JOIN MODALISTA.ALITBLPEDIDO PED ON ped.pk_pedido_codigo = detped.pk_pedido 
//            JOIN MODCLIUNI.CLITBLENTIDA ENTCUS ON entcus.pk_ent_codigo = ped.pk_custodio
//            JOIN MODCLIUNI.CLITBLCAMPAN CAM ON cam.pk_campan_codigo = ped.pk_campan_codigo 
//            LEFT JOIN MODPROPAG.PPATBLDETORD DETORD ON detord.pk_pedido = detped.pk_detped_Codigo
//            left JOIN MODFACTUR.FACTBLFACORD FACORD ON facord.pk_ordcom_codigo=detord.pk_orden_compra
//            left JOIN MODPROPAG.PPATBLORDCOM ORDCOM ON facord.pk_ordcom_codigo=ordcom.pk_ordcom_codigo
//            LEFT JOIN MODFACTUR.FACTBLFACTUR factur ON facord.pk_factur_codigo=factur.pk_factur_codigo 
//            JOIN MODTARHAB.tartblesttar ESTTAR 
//            ON esttar.pk_esttar_codigo = tar.pk_esttar_codigo  
//            and  esttar.pk_esttar_codigo=1 and TAR.NUMERO is not null
//            order BY tar.fecha_creacion asc) vista 
//            left join MODLLAVEMAESTRA.llavetblasotar asot on vista.codprod = asot.pk_produc_codigo and asot.fecha_desasociacion IS NULL 
//            and vista.codth = asot.pk_ent_codigo and vista.codtar = asot.pk_tarjeta_codigo) vista2 
//            where not vista2.pk_llavero_codigo = $pk_llavero or vista2.asotar_codigo is null");
//            $data['tarjetaHabiente'] = $tarjetaHabiente->result_array;
//            
//            $llaveros = $this->returnarrayllaveros();
//                foreach ($llaveros as $value) {
//                    if ($value['PK_LLAVERO_CODIGO'] == $pk_llavero) {
//                        $nombrellaveroselect = $value['NOMBRE_LLAVERO'];
//                    }
//                }
//                $data['nombrellaveroselect'] = $nombrellaveroselect;
//                $data['pk_llavero_codigo'] = $post['pk_llavero'];
//        } else {
//            $data['errorpkllavero'] = 1;
//        }
        $data['llaveros'] = $this->returnarrayllaveros();
        $data['saldo'] = $this->saldollavemaestra();
        //$empresa = $this->session->userdata("entidad");
        $empresa = $_SESSION['entidad'];
        $data['empresa'] = $empresa['NOMBREEMPRESA'];
        //$usuario = $this->session->userdata("usuario");
        $usuario = $_SESSION['usuario'];
        $data['usuario'] = $usuario['NOMBRE'] . " " . $usuario['APELLIDO'];
        //$ultimaconexion = $this->session->userdata("ultimaconexion");
        $ultimaconexion = $_SESSION['ultimaconexion'];
        $data['ultimaconexion'] = $ultimaconexion;
        $data['llaveMaestra'] = 1;
        $data['menu'] = "asociacion";
        $this->load->view('portal/templates/header2llave', $data);
        $this->load->view('portal/llave/asociacion', $data);
        $this->load->view('portal/templates/footer', $data);
    }

    public function returnTarjetasAsociacion($pantalla=null) {
        $this->verificarllaveMestraNuevosPerfiles();
        //$empresa = $this->session->userdata("entidad");
        $empresa = $_SESSION['entidad'];
        //$parcampana = $this->session->userdata("campana");
        $parcampana = $_SESSION['campana'];
        $post = $this->input->post();
        if ($post && !empty($post['pk_llavero'])) {
            $pk_llavero = $post['pk_llavero'];
            //$empresa = $this->session->userdata("entidad");
            $empresa = $_SESSION['entidad'];


            $tarjetaHabiente = $this->db->query("select vista.nomtar, vista.abr, vista.doc, vista.NOMPRO, vista.CODPROD, vista.codth, vista.NUMTAR, vista.codtar, vista.IDENTIFICADOR,vista.pk_esttar_codigo,vista.estadotar,llave.* 
            from (SELECT distinct  
            ent.documento DOC,
            ent.pk_ent_codigo CODTH,
            tipdoc.abreviacion abr, 
            NVL(TO_CHAR(tar.fecha_creacion,'DD/MM/YYYY'),'PENDIENTE') FEC,
            ent.nombre ||' '||ent.apellido nomtar, 
            TAR.NUMERO NUMTAR,
            tar.pk_tarjet_codigo codtar,
            tar.pk_esttar_codigo,
            PRO.NOMBRE_PRODUCTO NOMPRO,
            pro.pk_produc_codigo codprod,
            NVL(tar.identificador ,'-')IDENTIFICADOR,
            ESTTAR.nombre estadotar,
            cue.pk_tartblcuenta_codigo CUENTA
            FROM MODTARHAB.tartbltarjet tar 
            join MODTARHAB.TARTBLCUENTA CUE 
            ON cue.pk_tartblcuenta_codigo = tar.pk_tartblcuenta_codigo 
            AND cue.PK_ENT_CODIGO_EMP = {$empresa['PK_ENT_CODIGO']}
            JOIN MODCLIUNI.CLITBLENTIDA ENT 
            ON ent.pk_ent_codigo = cue.pk_ent_codigo_th 
            JOIN MODCLIUNI.CLITBLTIPDOC TIPDOC 
            ON tipdoc.pk_td_codigo = ent.clitbltipdoc_pk_td_codigo 
            JOIN MODPRODUC.PROTBLPRODUC PRO 
            ON pro.pk_produc_codigo = cue.pk_produc_codigo 
            JOIN MODALISTA.ALITBLDETPED DETPED 
            ON detped.pk_detped_codigo = tar.pk_detped_codigo 
            JOIN MODALISTA.ALITBLPEDIDO PED ON ped.pk_pedido_codigo = detped.pk_pedido 
            JOIN MODCLIUNI.CLITBLENTIDA ENTCUS ON entcus.pk_ent_codigo = ped.pk_custodio
            JOIN MODCLIUNI.CLITBLCAMPAN CAM ON cam.pk_campan_codigo = ped.pk_campan_codigo 
            JOIN MODPROPAG.PPATBLDETORD DETORD ON detord.pk_pedido = detped.pk_detped_Codigo
            JOIN MODFACTUR.FACTBLFACORD FACORD ON facord.pk_ordcom_codigo=detord.pk_orden_compra
            JOIN MODPROPAG.PPATBLORDCOM ORDCOM ON facord.pk_ordcom_codigo=ordcom.pk_ordcom_codigo
            JOIN MODFACTUR.FACTBLFACTUR factur ON facord.pk_factur_codigo=factur.pk_factur_codigo 
            JOIN MODALISTA.ALITBLDESDET esdet ON esdet.ALITBLDETPED_PK_DETPED_CODIGO=tar.PK_DETPED_CODIGO  
                                        AND trunc(esdet.FECHA_CREACION)=trunc(tar.fecha_creacion)
                                        AND esdet.ALITBLESTDET_PK_ESTPED_CODIGO in (9)
            JOIN MODTARHAB.tartblesttar ESTTAR 
            ON esttar.pk_esttar_codigo = tar.pk_esttar_codigo
            and  esttar.pk_esttar_codigo not in(6,7,8,15,16,17,18,19,20) and TAR.NUMERO is not null 
            --union compartir tarjetas
            UNION ALL
            select distinct 
            ent.documento DOC, 
            ent.pk_ent_codigo CODTH,
            tipdoc.abreviacion ABR, 
            NVL(TO_CHAR(tar.fecha_creacion,'DD/MM/YYYY'),'PENDIENTE') FEC,
            nvl(ent.razon_social,ent.nombre ||' '||ent.apellido) NOMTAR,
            TAR.NUMERO NUMTAR,
            tar.pk_tarjet_codigo codtar,
            tar.pk_esttar_codigo,
            pro.nombre_producto NOMPRO,
            pro.pk_produc_codigo codprod,
            NVL(tar.identificador ,'-')IDENTIFICADOR,	
            ESTTAR.nombre estadotar,
            cue.pk_tartblcuenta_codigo CUENTA
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
            and parametro.PK_PRODUCTO_CODIGO = pro.pk_produc_codigo 
            and pro.pk_tippro_codigo=1
            JOIN MODTARHAB.tartbltarjet tar
            ON cue.pk_tartblcuenta_codigo = tar.pk_tartblcuenta_codigo 
            JOIN MODALISTA.ALITBLDESDET esdet ON esdet.ALITBLDETPED_PK_DETPED_CODIGO=tar.PK_DETPED_CODIGO  
                                        AND trunc(esdet.FECHA_CREACION)=trunc(tar.fecha_creacion)
                                        AND esdet.ALITBLESTDET_PK_ESTPED_CODIGO in (9)
            JOIN MODTARHAB.tartblesttar ESTTAR	
            ON ESTTAR.pk_esttar_codigo = tar.pk_esttar_codigo
            where  compar.pk_entidad_destino = {$empresa['PK_ENT_CODIGO']}
            and cotizacion.pk_campana_codigo = $parcampana
            and TAR.NUMERO is not null
            and compar.fecha_fin_compartir is null and ESTTAR.pk_esttar_codigo not in(6,7,8,15,16,17,18,19,20)) vista
            left join 
            (select asotar.pk_tarjeta_codigo
                    from MODLLAVEMAESTRA.llavetblllavero llavero
                    join modllavemaestra.llavetblasotar asotar on
                    llavero.llavero_codigo=asotar.pk_llavero_codigo
                    and asotar.fecha_desasociacion is null and
                    llavero.llavero_codigo=$pk_llavero) llave 
                    on vista.codtar=llave.pk_tarjeta_codigo
                    where llave.pk_tarjeta_codigo is null");

            $data['tarjetaHabiente'] = $tarjetaHabiente->result_array;

            $llaveros = $this->returnarrayllaveros();
            foreach ($llaveros as $value) {
                if ($value['PK_LLAVERO_CODIGO'] == $pk_llavero) {
                    $nombrellaveroselect = $value['NOMBRE_LLAVERO'];
                }
            }
            $data['nombrellaveroselect'] = $nombrellaveroselect;
            $data['pk_llavero_codigo'] = $post['pk_llavero'];
        } else {
            $data['errorpkllavero'] = 1;
        }

        if ($pantalla == 1) {
            $data['llaveros'] = $this->returnarrayllaveros();
            $data['saldo'] = $this->saldollavemaestra();
            //$empresa = $this->session->userdata("entidad");
            $empresa = $_SESSION['entidad'];
            $data['empresa'] = $empresa['NOMBREEMPRESA'];
            //$usuario = $this->session->userdata("usuario");
            $usuario = $_SESSION['usuario'];
            $data['usuario'] = $usuario['NOMBRE'] . " " . $usuario['APELLIDO'];
            //$ultimaconexion = $this->session->userdata("ultimaconexion");
            $ultimaconexion = $_SESSION['ultimaconexion'];
            $data['ultimaconexion'] = $ultimaconexion;
            $data['llaveMaestra'] = 1;
            $data['menu'] = "asociacion";
            $this->load->view('portal/templates/header2llave', $data);
            $this->load->view('portal/llave/asociacion', $data);
            $this->load->view('portal/templates/footer', $data);
        } elseif ($pantalla == 2) {
            $data['llaveros'] = $this->returnarrayllaveros();
            $data['saldo'] = $this->saldollavemaestra();
            //$empresa = $this->session->userdata("entidad");
            $empresa = $_SESSION['entidad'];
            $data['empresa'] = $empresa['NOMBREEMPRESA'];
            //$usuario = $this->session->userdata("usuario");
        $usuario = $_SESSION['usuario'];
            $data['usuario'] = $usuario['NOMBRE'] . " " . $usuario['APELLIDO'];
            //$ultimaconexion = $this->session->userdata("ultimaconexion");
            $ultimaconexion = $_SESSION['ultimaconexion'];
            $data['ultimaconexion'] = $ultimaconexion;
            $data['llaveMaestra'] = 1;
            $data['menu'] = "asociacion";
            $this->load->view('portal/templates/header2llave', $data);
            $this->load->view('portal/llave/asociacionMasiva', $data);
            $this->load->view('portal/templates/footer', $data);
        }elseif ($pantalla == 0 || empty($pantalla)) {
            redirect("portal/llaveMaestra/asociacion");
        }
    }

//Asociación masica de tarjeta-habientes
    public function solicitudMasiva() {

        $this->verificarllaveMestraNuevosPerfiles();
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
                redirect("portal/solicitudTarjetas/mensajeError/$parrespuesta/$pararchivo");
            } elseif ($parrespuesta == 1) {
//
                $codigo = $parcodigoorden;
                redirect("portal/solicitudTarjetas/solicitudTarjetasMasivo/$parrespuesta/$codigo");
            }
        }
    }

    public function gestion_llaveros() {
        $this->verificarllaveMestraAdminLLaveros();
        //$empresa = $this->session->userdata("entidad");
        $empresa = $_SESSION['entidad'];
        $data['empresa'] = $empresa['NOMBREEMPRESA'];
        $pk_ent_codigo = $empresa['PK_ENT_CODIGO'];       
        //$usuario = $this->session->userdata("usuario");
        $usuario = $_SESSION['usuario'];
        $data['usuario'] = $usuario['NOMBRE'] . " " . $usuario['APELLIDO'];
        $pk_entidad_coor = $usuario['PK_ENT_CODIGO'];
        //$ultimaconexion = $this->session->userdata("ultimaconexion");
        $ultimaconexion = $_SESSION['ultimaconexion'];
        $data['ultimaconexion'] = $ultimaconexion;
        $data['saldo'] = $this->saldollavemaestra();
        $data['llaveMaestra'] = 1;
        $data['menu'] = "bolsillo";
        //$pk_llavemae = $this->session->userdata("PK_LLAVE_MAESTRA");
        $pk_llavemae = $_SESSION['PK_LLAVE_MAESTRA'];
        //$rol = $this->session->userdata("rol");
        $rol = $_SESSION['rol'];

        $sql = "BEGIN MODLLAVEMAESTRA.LLAVMAEPKGGENERAL.retornallaveros(
                    parentcodigo =>:parentcodigo,
                    llaveros =>:llaveros,
                    parrespuesta=>:parrespuesta);
                    END;";
        $conn = $this->db->conn_id;
        $stmt = oci_parse($conn, $sql);

        $curs = oci_new_cursor($conn);//trae todos los llaveros
        oci_bind_by_name($stmt, ":llaveros", $curs, -1, OCI_B_CURSOR);
        oci_bind_by_name($stmt, ':parentcodigo', $pk_ent_codigo, 32);
        oci_bind_by_name($stmt, ':parrespuesta', $parrespuesta, 32);

        if (!oci_execute($stmt)) {
            $e = oci_error($stmt);
            VAR_DUMP($e);
            exit;
        }
        if ($parrespuesta == 1) {
            $llaveros = array();
            $llaverosInac = array();
            oci_execute($curs);  // Ejecutar el REF CURSOR como un ide de sentencia normal
            while (($row = oci_fetch_array($curs, OCI_ASSOC + OCI_RETURN_NULLS)) != false) {
                if ($row['ESTADO'] == 1 && ($rol == 61)) {
                    array_push($llaveros, array(
                        'PK_LLAVERO_CODIGO' => $row['PK_LLAVERO_CODIGO'], 'NOMBRE_LLAVERO' => $row['NOMBRE_LLAVERO'], 'NOMBRE_COOR_RES' => $row['NOMBRE_COOR_RES'], 'NOMBRE_ADM_PAGO' => $row['NOMBRE_ADM_PAGO'], 'SALDO' => $row['SALDO'], 'ESTADO' => $row['ESTADO']
                    ));
                } elseif ($row['ESTADO'] == 2 && is_null($row['FECHA_DESVINCULACION'])) {
                    array_push($llaverosInac, array(
                        'PK_LLAVERO_CODIGO' => $row['PK_LLAVERO_CODIGO'], 'NOMBRE_LLAVERO' => $row['NOMBRE_LLAVERO'], 'NOMBRE_COOR_RES' => $row['NOMBRE_COOR_RES'], 'NOMBRE_ADM_PAGO' => $row['NOMBRE_ADM_PAGO'], 'SALDO' => $row['SALDO'], 'ESTADO' => $row['ESTADO']
                    ));
                } elseif (($rol == 60) && ($row['ESTADO'] == 1 && $row['PK_ENT_CODIGO_COOR'] == $pk_entidad_coor)) {
                    array_push($llaveros, array(
                        'PK_LLAVERO_CODIGO' => $row['PK_LLAVERO_CODIGO'], 'NOMBRE_LLAVERO' => $row['NOMBRE_LLAVERO'], 'NOMBRE_COOR_RES' => $row['NOMBRE_COOR_RES'], 'NOMBRE_ADM_PAGO' => $row['NOMBRE_ADM_PAGO'], 'SALDO' => $row['SALDO'], 'ESTADO' => $row['ESTADO']
                    ));
                }
            }

            $data['llaveros'] = $llaveros;
            $data['llaverosInac'] = $llaverosInac;
        }


        $this->load->view('portal/templates/header2llave', $data);
        $this->load->view('portal/llave/gestionllavero', $data);
        $this->load->view('portal/templates/footer', $data);
    }

    public function accionGestionllavero($val = 0, $accion = '') {
        $this->verificarllaveMestraPerfilGestor();
       if($accion!=''){
        if ($accion == 'desactivar' || $accion == 'activar') {
            if ($accion == 'desactivar') {
                $estado_llavero = 2;
                $accionOut = 'desactivado';
            } elseif ($accion == 'activar') {
                $estado_llavero = 1;
                $accionOut = 'activado';
            }

            $sql = "BEGIN MODLLAVEMAESTRA.LLAVMAEPKGGENERAL.prcupdatellaveroestado(
                    parpkllaverocodigo =>:parpkllaverocodigo,
                    parestado =>:parestado,
                    parusuarioactualiza =>:parusuarioactualiza,
                    parrespuesta=>:parrespuesta);
                    END;";

            $conn = $this->db->conn_id;
            $stmt = oci_parse($conn, $sql);
            $parpk_cod_llavero = $val;
            oci_bind_by_name($stmt, ':parpkllaverocodigo', $parpk_cod_llavero, 32);
            oci_bind_by_name($stmt, ':parestado', $estado_llavero, 32);
            //$usuario = $this->session->userdata("usuario");
            $usuario = $_SESSION['usuario'];
            $usuarioactualizacion = $usuario['USUARIO_ACCESO'];
            oci_bind_by_name($stmt, ':parusuarioactualiza', $usuarioactualizacion, 32);
            oci_bind_by_name($stmt, ':parrespuesta', $parrespuesta, 32);
            if (!oci_execute($stmt)) {
                $e = oci_error($stmt);
                VAR_DUMP($e);
                exit;
            }
            if ($parrespuesta == 1) {
                redirect("/portal/llaveMaestra/gestion_llaveros?ok&acc=$accionOut");
            }
        } elseif ($accion == 'eliminar' && $val != '') {
//se valida si el llavero tiene tarjetas asociadas no se deja eliminar
            $parpk_cod_llavero = $val;
            $sqlcantllaveros = $this->db->query("select count(llavero.llavero_codigo) cantidad from MODLLAVEMAESTRA.llavetblllavero llavero
                                join modllavemaestra.llavetblasotar asotar 
                                ON llavero.llavero_codigo=asotar.pk_llavero_codigo
                                and asotar.fecha_desasociacion is null and
                                llavero.llavero_codigo={$parpk_cod_llavero}");
            $cantidadtarjetasasoc = $sqlcantllaveros->result_array[0];
            $cantidadtarje = $cantidadtarjetasasoc['CANTIDAD'];
//se valida si el llavero contiene saldo no se deja eliminar hasta que retorne saldo a llave maestra
            $saldollavero = $this->returnsaldollaveroid($parpk_cod_llavero);
            if ($saldollavero == 0 && $cantidadtarje == 0) {
                $sql = "BEGIN MODLLAVEMAESTRA.LLAVMAEPKGGENERAL.prcupdatellaveroeliminar(
                    parpkllaverocodigo =>:parpkllaverocodigo,
                    parusuarioactualiza =>:parusuarioactualiza,
                    parrespuesta=>:parrespuesta);
                    END;";

                $conn = $this->db->conn_id;
                $stmt = oci_parse($conn, $sql);
                oci_bind_by_name($stmt, ':parpkllaverocodigo', $parpk_cod_llavero, 32);
                //$usuario = $this->session->userdata("usuario");
                $usuario = $_SESSION['usuario'];
                $usuarioactualizacion = $usuario['USUARIO_ACCESO'];
                oci_bind_by_name($stmt, ':parusuarioactualiza', $usuarioactualizacion, 32);
                oci_bind_by_name($stmt, ':parrespuesta', $parrespuesta, 32);
                if (!oci_execute($stmt)) {
                    $e = oci_error($stmt);
                    VAR_DUMP($e);
                    exit;
                }
                if ($parrespuesta == 1) {
                    $accionOut = "eliminado";
                    redirect("/portal/llaveMaestra/gestion_llaveros?ok&acc=$accionOut");
                }
            } else {
                if ($saldollavero != 0) {
                    redirect("/portal/llaveMaestra/gestion_llaveros?ErrorElim&acc=1");
                } elseif ($cantidadtarje != 0) {
                    redirect("/portal/llaveMaestra/gestion_llaveros?ErrorElim&acc=2");
                }
            }
        }
    }else{
        redirect("/portal/llaveMaestra/gestion_llaveros");
    }
    }

  
    /*
   para realizar la asociociacion masiva mediante plantilla
     * */
   
        public function descargarPlantillaAsociacion() {
        $this->verificarllaveMestraNuevosPerfiles();
        $path = '/uploads/ARCHIVO-PEDIDO-MASIVO-ASOCIACION-DE-TARJETAS.xlsx';
        header("Location:" . $path);
    }
      public function asociacionMasiva($pantalla = 0,$sucessc=0) { 
        $this->verificarllaveMestraNuevosPerfiles();
        //$empresa = $this->session->userdata("entidad");
        $empresa = $_SESSION['entidad"'];
        $pk_ent_codigo = $empresa['PK_ENT_CODIGO'];
        $data['llaveros'] = $this->returnarrayllaveros();
        $empresa = $_SESSION['entidad'];
        $data['empresa'] = $empresa['NOMBREEMPRESA'];
        //$usuario = $this->session->userdata("usuario");
        $usuario = $_SESSION['usuario'];
        $data['usuario'] = $usuario['NOMBRE'] . " " . $usuario['APELLIDO'];
        //$ultimaconexion = $this->session->userdata("ultimaconexion");
        $ultimaconexion = $_SESSION['ultimaconexion'];
        $data['saldo'] = $this->saldollavemaestra();
        $data['ultimaconexion'] = $ultimaconexion;
        $data['llaveMaestra'] = 1;
        $data['menu'] = "asociacion";
        if($sucessc==0){
         $data['sucess']=0;   
        }            
        elseif($sucessc==1){
        $data['sucess']=$sucessc;
        }elseif($sucessc==200){
        $data['sucesse']=$sucessc;
        }elseif($sucessc==500){
        $data['sucesse']=300;
        }elseif($sucessc==600){
        $data['sucesse']=600;
        }
        $this->load->view('portal/templates/header2llave', $data);
        $this->load->view('portal/llave/asociacionMasivaPlantilla', $data);
        $this->load->view('portal/templates/footer', $data);
    }
   
    
    public function solicitudAsocMasivaPlantilla($pantalla = 0) { 
      log_info($this->logHeader . ' INGRESO SOLICITUD DE MASIVA PROCESAMIENTO DE ARCHIVO');
        log_info($this->iniciLog . $this->logHeader . $this->session->userdata("usuario"));
        log_info($this->logHeader . ' MI PERFIL SERA CUAL ' . $_SESSION['PRODUCTOLLAVE']['CODIGO_PRODUCTO'].'y rol que tiene es'.$rol);
        $this->verificarllaveMestraNuevosPerfiles();
        $post = $this->input->post();
        if($pantalla==1){
       
       
      
        if (!empty($post['pk_llavero'])){
                if(!empty($_FILES["file"]["name"])) {
                   log_info('PARA QUE TE TRAJE MMM->'. $_FILES["file"]["name"]);
            $pk_llavero = $post['pk_llavero'];
            log_info('MI LLAVERO ES->'.$pk_llavero); 
              $date = date('Y_m_d');
            $random = rand(1000, 9999);
            $name = strtolower($date . '_' . $random);
            $tmp_name = $name;
           log_info(' INSERTA ARCHIVOS $NAME'.$tmp_name);
            log_info($this->logHeader . ' INSERTA EN FLOW FILES');
            $BLOB_CONTENT = file_get_contents($_FILES['file']['tmp_name']);
            $sql = "INSERT INTO modllavemaestra.WWV_FLOW_FILE_OBJECTS$ (FLOW_ID, NAME,BLOB_CONTENT, DELETED_AS_OF) 
                     VALUES(102,'$tmp_name', empty_blob(),sysdate+5) RETURNING BLOB_CONTENT INTO :BLOB_CONTENT";
            $connection = $this->db->conn_id;
            $stmt = oci_parse($connection, $sql);
            $blob = oci_new_descriptor($connection, OCI_D_LOB);
             log_info($this->logHeader . ' UN CLOB QUE NO SE PUEDE VISUALIZAR'.$BLOB);
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


            $sql = "BEGIN modllavemaestra.LLAVMAEPKGGENERAL.prccargarmasivoasociaciont(:parnomarch
                                                                    ,:parentidad
                                                                    ,:parusuario
                                                                    ,:parcoordinador
                                                                    ,:parcampana
                                                                    ,:parllavero
                                                                    ,:parrespuesta);
                        END;";

               
            $conn = $this->db->conn_id;
            $stmt = oci_parse($conn, $sql);
            $pararchivo = $tmp_name;
            $parllavero=$pk_llavero;
             log_info('OBTENGO EL LLAVERO->'.$parllavero);
            //$parempresa = $this->session->userdata("pkentidad");
            $parempresa = $_SESSION['pkentidad'];
            //$usuario = $this->session->userdata("usuario");
            $usuario = $_SESSION['usuario'];
            $parusuario = $usuario['USUARIO_ACCESO'];
            //$parcampana = $this->session->userdata("campana");
            $parcampana = $_SESSION['campana'];
            $parcoordinador = $usuario['PK_ENT_CODIGO'];
            log_info($this->logHeader . 'ahi vamos con la campana'
                        . $parcampana);

            //TIPO NUMBER INPUT INPUT
            oci_bind_by_name($stmt, ':parnomarch', $pararchivo, 100);
            //TIPO VARCHAR2 INPUT
            oci_bind_by_name($stmt, ':parentidad', $parempresa, 100);
            //TIPO NUMBER INPUT
            oci_bind_by_name($stmt, ':parusuario', $parusuario, 100);
            //TIPO VARCHAR2 INPUT
            oci_bind_by_name($stmt, ':parcoordinador', $parcoordinador, 100);
            //TIPO VARCHAR2 INPUT
            oci_bind_by_name($stmt, ':parcampana', $parcampana, 100);
            //TIPO VARCHAR2 INPUT
            oci_bind_by_name($stmt, ':parllavero', $parllavero, 100);
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
                $sucessc=200;
                redirect("portal/llaveMaestra/asociacionMasiva/".$sucessc);
            } elseif ($parrespuesta != 1) {
                log_info($this->logHeader . ' SE PROCESA EL ARCHIVO CON ERRORES ');
               redirect("portal/llaveMaestra/mensajeError/$parrespuesta/$pararchivo");
            } elseif ($parrespuesta == 1) {
                //
                $codigo = 'OK';
                $sucessc=1;
                log_info($this->logHeader . ' ARCHIVO PROCESADO RESPUESTA' . $parrespuesta . ' CODIGO SOLICITUD ' . $codigo);
                redirect("portal/llaveMaestra/asociacionMasiva/0/".$sucessc);
                
            }
                }else{
                    $sucessc=600;
                   redirect("portal/llaveMaestra/asociacionMasiva/0/$sucessc"); 
                }
        }else{
             $sucessc=500;
            redirect("portal/llaveMaestra/asociacionMasiva/0/".$sucessc);
        }  
  
        }else{
           redirect("portal/llaveMaestra/asociacionMasiva/");
        }
    }
    
   
      public function mensajeError($error=0, $nombrearchivo=0) {
        $this->verificarllaveMestraNuevosPerfiles();
        //$empresa = $this->session->userdata("entidad");
        if($error>0){
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
        $data['saldo'] = $this->saldollavemaestra();
        //$empresa = $this->session->userdata("entidad");
        $empresa = $_SESSION['entidad'];
        $data['empresa'] = $empresa['NOMBREEMPRESA'];
        //$usuario = $this->session->userdata("usuario");
        $usuario = $_SESSION['usuario'];
        $data['usuario'] = $usuario['NOMBRE'] . " " . $usuario['APELLIDO'];
        
        log_info('DATA ERROR PERO QUE PANA'.'USUARIO VALIDO'.$data['usuario'].'EMPRESA'.$data['empresa']);
        //$ultimaconexion = $this->session->userdata("ultimaconexion");
        $ultimaconexion = $_SESSION['ultimaconexion'];
        $data['ultimaconexion'] = $ultimaconexion;
        $this->load->view('portal/templates/header2llave', $data);
        $this->load->view('portal//llave/mensajeError', $data);
        $this->load->view('portal/templates/footer', $error);
        }else{
             redirect("portal/llaveMaestra/asociacionMasivaPlantilla");
        }
    }
    
     /*
     * termina metodo asociacion masiva tarjeta mediante plantilla
     */
    /*
    public function asociacionMasiva($pantalla = 0) { 
        $this->verificarllaveMestraNuevosPerfiles();
        //$empresa = $this->session->userdata("entidad");
        $empresa = $_SESSION['entidad"'];
        $pk_ent_codigo = $empresa['PK_ENT_CODIGO'];
        if ($pantalla == 1) {
            $post = $this->input->post();
            //$usuario = $this->session->userdata("usuario");
            $usuario = $_SESSION['usuario'];
            $usuarioactualizacion = $usuario['USUARIO_ACCESO'];
            foreach ($post['usuarios'] as $key => $value) {
                $sql = "BEGIN MODLLAVEMAESTRA.LLAVMAEPKGGENERAL.prcasociartarjetath(
                    :parproductocodigo, 
                    :parllaverocodigo, 
                    :parentidadcodigo,
                    :parusuariocreacion,
                    :parpktarjetacodigo,
                    :parrespuesta
                    );
                    END;";

                $dataUserTh = explode(",", $value);
                $conn = $this->db->conn_id;
                $stmt = oci_parse($conn, $sql);
                $parproductocodigo = $dataUserTh[0];
                $parllaverocodigoc = $post['pk_llavero_codigo'];
                $parentidadcodigo = $dataUserTh[1];
                $numeroTar = $dataUserTh[2];
                oci_bind_by_name($stmt, ':parusuariocreacion', $usuarioactualizacion, 32);
                oci_bind_by_name($stmt, ':parproductocodigo', $parproductocodigo, 32);
                oci_bind_by_name($stmt, ':parllaverocodigo', $parllaverocodigoc, 32);
                oci_bind_by_name($stmt, ':parentidadcodigo', $parentidadcodigo, 32);
                oci_bind_by_name($stmt, ':parpktarjetacodigo', $numeroTar, 32);
                oci_bind_by_name($stmt, ':parrespuesta', $parrespuesta, 32);
                if (!oci_execute($stmt)) {
                    $e = oci_error($stmt);
                    VAR_DUMP($e);
//exit;
                } else if ($parrespuesta != 1) {
                    $PARRESPUE = 'ERROR ' . $parrespuesta;
//lo saca y monta la vista con el error
                } else {
                    $data['accionOut'] = '1';
                }
            }
        }
        $data['llaveros'] = $this->returnarrayllaveros();
//        $empresa = $this->session->userdata("entidad");
//        $tarjetaHabiente = $this->db->query("
//        select vista.nomtar, vista.abr, vista.doc, vista.NOMPRO, vista.CODPROD, vista.codth, vista.NUMTAR, vista.codtar from (SELECT ent.documento DOC,
//            ent.pk_ent_codigo CODTH,
//            tipdoc.abreviacion abr, 
//            NVL(TO_CHAR(tar.fecha_creacion,'DD/MM/YYYY'),'PENDIENTE') FEC,
//            ent.nombre ||' '||ent.apellido nomtar, TAR.NUMERO NUMTAR,
//            tar.pk_tarjet_codigo codtar,tar.pk_esttar_codigo,
//            PRO.NOMBRE_PRODUCTO NOMPRO,
//            pro.pk_produc_codigo codprod
//            FROM MODTARHAB.tartbltarjet tar 
//            join MODTARHAB.TARTBLCUENTA CUE 
//            ON cue.pk_tartblcuenta_codigo = tar.pk_tartblcuenta_codigo 
//            AND cue.PK_ENT_CODIGO_EMP = {$empresa['PK_ENT_CODIGO']}
//            JOIN MODCLIUNI.CLITBLENTIDA ENT 
//            ON ent.pk_ent_codigo = cue.pk_ent_codigo_th 
//            JOIN MODCLIUNI.CLITBLTIPDOC TIPDOC 
//            ON tipdoc.pk_td_codigo = ent.clitbltipdoc_pk_td_codigo 
//            JOIN MODPRODUC.PROTBLPRODUC PRO 
//            ON pro.pk_produc_codigo = cue.pk_produc_codigo 
//            JOIN MODALISTA.ALITBLDETPED DETPED 
//            ON detped.pk_detped_codigo = tar.pk_detped_codigo 
//            JOIN MODALISTA.ALITBLPEDIDO PED ON ped.pk_pedido_codigo = detped.pk_pedido 
//            JOIN MODCLIUNI.CLITBLENTIDA ENTCUS ON entcus.pk_ent_codigo = ped.pk_custodio
//            JOIN MODCLIUNI.CLITBLCAMPAN CAM ON cam.pk_campan_codigo = ped.pk_campan_codigo 
//            LEFT JOIN MODPROPAG.PPATBLDETORD DETORD ON detord.pk_pedido = detped.pk_detped_Codigo
//            left JOIN MODFACTUR.FACTBLFACORD FACORD ON facord.pk_ordcom_codigo=detord.pk_orden_compra
//            left JOIN MODPROPAG.PPATBLORDCOM ORDCOM ON facord.pk_ordcom_codigo=ordcom.pk_ordcom_codigo
//            LEFT JOIN MODFACTUR.FACTBLFACTUR factur ON facord.pk_factur_codigo=factur.pk_factur_codigo 
//            JOIN MODTARHAB.tartblesttar ESTTAR 
//            ON esttar.pk_esttar_codigo = tar.pk_esttar_codigo            
//            order BY tar.fecha_creacion asc) vista 
//            left join MODLLAVEMAESTRA.llavetblasotar asot on vista.codprod = asot.pk_produc_codigo and asot.fecha_desasociacion IS NULL
//            and vista.codth = asot.pk_ent_codigo and vista.codtar = asot.pk_tarjeta_codigo where asot.pk_ent_codigo  IS NULL
//            and asot.pk_produc_codigo IS NULL and vista.numtar IS NOT NULL AND
//            vista.pk_esttar_codigo=1
//            order by vista.NOMTAR asc");
//        $data['tarjetaHabiente'] = $tarjetaHabiente->result_array;
        //$empresa = $this->session->userdata("entidad");
        $empresa = $_SESSION['entidad'];
        $data['empresa'] = $empresa['NOMBREEMPRESA'];
        //$usuario = $this->session->userdata("usuario");
        $usuario = $_SESSION['usuario'];
        $data['usuario'] = $usuario['NOMBRE'] . " " . $usuario['APELLIDO'];
        //$ultimaconexion = $this->session->userdata("ultimaconexion");
        $ultimaconexion = $_SESSION['ultimaconexion'];
        $data['saldo'] = $this->saldollavemaestra();
        $data['ultimaconexion'] = $ultimaconexion;
        $data['llaveMaestra'] = 1;
        $data['menu'] = "asociacion";
        $this->load->view('portal/templates/header2llave', $data);
        $this->load->view('portal/llave/asociacionMasiva', $data);
        $this->load->view('portal/templates/footer', $data);
    }
*/
    public function desasociacion($pantalla = 0) {
        $this->verificarllaveMestraNuevosPerfiles();
        //$empresa = $this->session->userdata("entidad");
        $empresa = $_SESSION['entidad'];
        $pk_ent_codigo = $empresa['PK_ENT_CODIGO'];
        $post = $this->input->post();
        if ($pantalla == 1) {
            if ($post && !empty($post['pk_llavero'])) {
                $pk_codigo_llavero = $post['pk_llavero'];
                $tarjetasllavero = $this->db->query("select vista.nomtar, vista.abr, vista.doc, vista.NOMPRO, vista.CODPROD, vista.IDENTIFICADOR, vista.codth, vista.NUMTAR,vista.PK_TARJET_CODIGO,vista.ciudad,vista.nomcampana, asot.asotar_codigo PKTAR,vista.nomcustodio from (SELECT ent.documento DOC,
                                        ent.pk_ent_codigo CODTH,CIUD.nombre ciudad,CAM.nombre nomcampana,
                                        tipdoc.abreviacion abr, 
                                        NVL(TO_CHAR(tar.fecha_creacion,'DD/MM/YYYY'),'PENDIENTE') FEC,
                                        ent.nombre ||' '||ent.apellido nomtar, TAR.NUMERO NUMTAR,TAR.PK_TARJET_CODIGO,
                                        PRO.NOMBRE_PRODUCTO NOMPRO,
                                        pro.pk_produc_codigo codprod,
                                        NVL(tar.identificador ,'-')IDENTIFICADOR,
                                        tar.pk_tarjet_codigo codtar,
                                        ENTCUS.nombre ||' '||ENTCUS.apellido nomcustodio, entcus.pk_ent_codigo custoid
                                        FROM MODTARHAB.tartbltarjet tar 
                                        join MODTARHAB.TARTBLCUENTA CUE 
                                        ON cue.pk_tartblcuenta_codigo = tar.pk_tartblcuenta_codigo 
                                        AND cue.PK_ENT_CODIGO_EMP = $pk_ent_codigo
                                        JOIN MODCLIUNI.CLITBLENTIDA ENT 
                                        ON ent.pk_ent_codigo = cue.pk_ent_codigo_th
                                        JOIN MODCLIUNI.CLITBLCIUDAD CIUD ON ent.CLITBLCIUDAD_PK_CIU_CODIGO = CIUD.PK_CIU_CODIGO
                                        JOIN MODCLIUNI.CLITBLTIPDOC TIPDOC 
                                        ON tipdoc.pk_td_codigo = ent.clitbltipdoc_pk_td_codigo 
                                        JOIN MODPRODUC.PROTBLPRODUC PRO 
                                        ON pro.pk_produc_codigo = cue.pk_produc_codigo
                                        JOIN MODALISTA.ALITBLDETPED DETPED 
                                        ON detped.pk_detped_codigo = tar.pk_detped_codigo 
                                        JOIN MODALISTA.ALITBLPEDIDO PED ON ped.pk_pedido_codigo = detped.pk_pedido 
                                        JOIN MODCLIUNI.CLITBLENTIDA ENTCUS ON entcus.pk_ent_codigo = ped.pk_custodio
                                        JOIN MODCLIUNI.CLITBLCAMPAN CAM ON cam.pk_campan_codigo = ped.pk_campan_codigo 
                                        LEFT JOIN MODPROPAG.PPATBLDETORD DETORD ON detord.pk_pedido = detped.pk_detped_Codigo
                                        left JOIN MODFACTUR.FACTBLFACORD FACORD ON facord.pk_ordcom_codigo=detord.pk_orden_compra
                                        left JOIN MODPROPAG.PPATBLORDCOM ORDCOM ON facord.pk_ordcom_codigo=ordcom.pk_ordcom_codigo
                                        LEFT JOIN MODFACTUR.FACTBLFACTUR factur ON facord.pk_factur_codigo=factur.pk_factur_codigo 
                                        JOIN MODTARHAB.tartblesttar ESTTAR 
                                        ON esttar.pk_esttar_codigo = tar.pk_esttar_codigo            
                                        order BY tar.fecha_creacion asc) vista 
                                        left join MODLLAVEMAESTRA.llavetblasotar asot on vista.codprod = asot.pk_produc_codigo
                                        and vista.codth = asot.pk_ent_codigo and vista.codtar = asot.pk_tarjeta_codigo 
                                        and asot.fecha_desasociacion IS NULL where
                                        asot.pk_llavero_codigo =$pk_codigo_llavero and vista.numtar IS NOT NULL");
                $data['tarjetallavero'] = $tarjetasllavero->result_array;
                $llaveros = $this->returnarrayllaveros();
                foreach ($llaveros as $value) {
                    if ($value['PK_LLAVERO_CODIGO'] == $pk_codigo_llavero) {
                        $nombrellaveroselect = $value['NOMBRE_LLAVERO'];
                    }
                }
                $data['nombrellaveroselect'] = $nombrellaveroselect;
                $data['pk_llavero_codigo'] = $post['pk_llavero'];
                $data['saldo_llavero'] = $this->returnsaldollaveroid($pk_codigo_llavero);
            } else {
                $data['errorpkllavero'] = 1;
            }
        }

        $data['llaveros'] = $this->returnarrayllaveros();
        //$empresa = $this->session->userdata("entidad");
        $empresa = $_SESSION['entidad'];
        $data['empresa'] = $empresa['NOMBREEMPRESA'];
        //$usuario = $this->session->userdata("usuario");
        $usuario = $_SESSION['usuario'];
        $data['usuario'] = $usuario['NOMBRE'] . " " . $usuario['APELLIDO'];
        //$ultimaconexion = $this->session->userdata("ultimaconexion");
        $ultimaconexion = $_SESSION['ultimaconexion'];
        $data['saldo'] = $this->saldollavemaestra();
        $data['ultimaconexion'] = $ultimaconexion;
        $data['llaveMaestra'] = 1;
        $data['menu'] = "asociacion";
        $this->load->view('portal/templates/header2llave', $data);
        $this->load->view('portal/llave/desasociacion', $data);
        $this->load->view('portal/templates/footer', $data);
    }

    public function desasociaciontarj() {
        $post = $this->input->post();
        if ($post) {
            if (!empty($post['data'])) {
                foreach ($post['data'] as $key => $value) {
                    //$usuario = $this->session->userdata("usuario");
                    $usuario = $_SESSION['usuario'];
                    $usuariodesasocia = $usuario['USUARIO_ACCESO'];
                    $dataTar = explode(",", $value);
                    $sql = "BEGIN MODLLAVEMAESTRA.LLAVMAEPKGGENERAL.desasociartarjetallavero(
                parpkasotarcodigo =>:parpkasotarcodigo,
                parpktarjeta=>:parpktarjeta,
                parusuariodesasocia =>:parusuariodesasocia,
                parpkllaverocodigo=>:parpkllaverocodigo,
                parmensajerespuesta=>:parmensajerespuesta,
                parrespuesta=>:parrespuesta);
                END;";
                    $conn = $this->db->conn_id;
                    $stmt = oci_parse($conn, $sql);
                    $parpk_asotar_codigo = $dataTar[1];
                    oci_bind_by_name($stmt, ':parpkasotarcodigo', $parpk_asotar_codigo, 32);
                    $parpk_tarjeta = $dataTar[0];
                    oci_bind_by_name($stmt, ':parpktarjeta', $parpk_tarjeta, 32);
                    oci_bind_by_name($stmt, ":parusuariodesasocia", $usuariodesasocia, 32);
                    $parpk_llavero = $dataTar[2];
                    oci_bind_by_name($stmt, ':parpkllaverocodigo', $parpk_llavero, 32);
                    oci_bind_by_name($stmt, ':parmensajerespuesta', $parmensajerespuesta, 32);
                    oci_bind_by_name($stmt, ':parrespuesta', $parrespuesta, 32);

                    if (!oci_execute($stmt)) {
                        $e = oci_error($stmt);
                        VAR_DUMP($e);
                        exit;
                    }
                    if ($parrespuesta != 1) {
                        var_dump($parmensajerespuesta);
                        exit();
                    }
                }
                redirect("/portal/llaveMaestra/desasociacion?ok");
            } else {
                redirect("/portal/llaveMaestra/desasociacion?errordata");
            }
        } else {
            var_dump('entrooo');
            redirect("/portal/llaveMaestra/desasociacion?errordata");
        }
    }

    public function carga($pantalla = 0) {
        $parcodigoproduco = 999995;
        $this->verificarllaveMestraAdmin();
        //$empresa = $this->session->userdata("entidad");
        $empresa = $_SESSION['entidad'];
        //$usuario = $this->session->userdata('usuario');
        $usuario = $_SESSION['usuario'];
        $pk_ent_codigo = $empresa['PK_ENT_CODIGO'];
        $post = $this->input->post();
        if (!empty($post['recarga'])) {
//var_dump($post);
//exit();
            $sql = "BEGIN 
                    modpropag.ppapkgactualizaciones.creaordencargacuentamestr (
                    parcodcli =>:parcodcli,
                    parcodord =>:parcodord,
                    parcodpro =>:parcodpro,
                    parmonto  =>:parmonto,
                    parmediopago  =>:parmediopago,
                    parpedido =>:parpedido,
                    parrespue =>:parrespue
                  );
                  END;
                  ";

            $conn = $this->db->conn_id;
            $stmt = oci_parse($conn, $sql);
            $porciones = explode(".", $post['recarga']);
            $parmonto = $this->dejarSoloCaracteresDeseados($porciones[0], "0123456789");
            $parmediopago = 1;
            oci_bind_by_name($stmt, ':parcodcli', $pk_ent_codigo, 32);
// abono tarjeta
            oci_bind_by_name($stmt, ':parcodord', $parordcompra, 32);
            oci_bind_by_name($stmt, ':parcodpro', $parcodigoproduco, 32);
            oci_bind_by_name($stmt, ':parmonto', $parmonto, 32);
            oci_bind_by_name($stmt, ':parmediopago', $parmediopago, 32);
            oci_bind_by_name($stmt, ':parpedido', $parpedido, 32);
            oci_bind_by_name($stmt, ':parrespue', $parrespuesta, 32);
            if (!oci_execute($stmt)) {
                $e = oci_error($stmt);
                VAR_DUMP($e);
                exit;
            }
            if ($parrespuesta == 1) {
                $pse = 1;
                $referenciapago = $this->generarreferenciapago($parordcompra, $pse);
            }


            if ($referenciapago !== 0) {
                try {
                    $porciones = explode(".", $post['recarga']);
                    $totalPago = $this->dejarSoloCaracteresDeseados($porciones[0], "0123456789");

                    //Consultar la informacion del cliente
                    $dataUser = $this->db->query("select
                                        entida.NOMBRE NOMBRE,
                                        entida.APELLIDO APELLIDO,
                                        entida.DOCUMENTO DOCUMENTO,
                                        entida.CORREO_ELECTRONICO CORREO_ELECTRONICO, 
                                        tipdoc.CODIGO_PASARELA TIPODOCUMENTO 
                                        from MODCLIUNI.CLITBLENTIDA entida 
                                        JOIN MODCLIUNI.CLITBLTIPDOC tipdoc 
                                        ON entida.clitbltipdoc_pk_td_codigo=tipdoc.pk_td_codigo
                                        where pk_ent_codigo={$usuario['PK_ENT_CODIGO']}");

                    $data = $dataUser->result_array[0];

                    $apiKey = $this->db->query("SELECT VALOR_PARAMETRO FROM MODGENERI.GENTBLPARGEN WHERE pk_pargen_codigo=77");
                    $apiKey = $apiKey->result_array[0];
                    $urlRetorno = $this->db->query("SELECT VALOR_PARAMETRO FROM MODGENERI.GENTBLPARGEN WHERE pk_pargen_codigo=79");
                    $urlRetorno = $urlRetorno->result_array[0];
                    $codigoComercio = $this->db->query("SELECT VALOR_PARAMETRO FROM MODGENERI.GENTBLPARGEN WHERE pk_pargen_codigo=78");
                    $codigoComercio = $codigoComercio->result_array[0];
                    $urlpasarela = $this->db->query("SELECT VALOR_PARAMETRO FROM MODGENERI.GENTBLPARGEN WHERE pk_pargen_codigo=80");
                    $urlpasarela = $urlpasarela->result_array[0];
                    $referenciaComercio = $this->db->query("SELECT VALOR_PARAMETRO FROM MODGENERI.GENTBLPARGEN WHERE pk_pargen_codigo=90");
                    $referenciaComercio = $referenciaComercio->result_array[0];

                    $htmlPay = '                
                        <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
                        <form class="hidden" method="post" action="' . $urlpasarela['VALOR_PARAMETRO'] . '" target="_blank" >
                                    <input type="hidden" name="Id" id="Id" value="' . $codigoComercio['VALOR_PARAMETRO'] . '"/>
                                    <input type="hidden" name="Clave" id="Clave" value="' . $apiKey['VALOR_PARAMETRO'] . '"/>
                                    <input type="hidden" name="CompraNeta" id="CompraNeta" value="' . $totalPago . '"/>
                                    <input type="hidden" name="Iva" id="Iva" value="0"/>
                                    <input type="hidden" name="BaseDevolucion" id="BaseDevolucion" value="0"/>
                                    <input type="hidden" name="NombreProducto" id="NombreProducto" value="' . $referenciapago . '"/>
                                    <input type="hidden" name="Referencia" id="Referencia" value="' . $referenciaComercio['VALOR_PARAMETRO'] . '"/>
                                    <input type="hidden" name="ValorTotal" id="ValorTotal" value="' . $totalPago . '"/>
                                    <input type="hidden" name="Factura" id="Factura" value="' . $referenciapago . '"/>
                                    <input type="hidden" name="IdCiudad" id="IdCiudad" value="9"/>
                                    <input type="hidden" name="IdPais" id="IdPais" value="170"/>
                                    <input type="hidden" name="Franquicia" id="Franquicia" value=""/>
                                    <input TYPE="hidden" name="PrimerNombre"  value="' . $data['NOMBRE'] . '"><br>
                                    <input TYPE="hidden" name="PrimerApellido"  value="' . $data['APELLIDO'] . '"><br>   
                                    <input TYPE="hidden" required name="Correo" id="Correo" value="' . $data['CORREO_ELECTRONICO'] . '"/>
                                    <input TYPE="hidden" required name="NumeroDocumento" id="NumeroDocumento" value="' . $data['DOCUMENTO'] . '"/>
                                    <input  name="Submit" type="submit" value="" id="SendPeoplePay" hidden>
                        </form>            
                        <script>
                            $( document ).ready(function() {
                                document.getElementById("SendPeoplePay").click();
                            });
                        </script>
                    ';
                    $data['htmlPay'] = $htmlPay;
                    $data['totalPago'] = $totalPago;
                    $data['referenciapago'] = $referenciapago;
//DATOS DE SESION
                    //$empresa = $this->session->userdata("entidad");
                    $empresa = $_SESSION['entidad'];
                    $data['empresa'] = $empresa['NOMBREEMPRESA'];
                    //$usuario = $this->session->userdata("usuario");
                    $usuario = $_SESSION['usuario'];
                    $data['usuario'] = $usuario['NOMBRE'] . " " . $usuario['APELLIDO'];
                    //$ultimaconexion = $this->session->userdata("ultimaconexion");
                    $ultimaconexion = $_SESSION['ultimaconexion'];
                    $data['ultimaconexion'] = $ultimaconexion;
//    
                    $this->load->view('portal/templates/header2llave', $data);
                    $this->load->view('portal/llave/procesandorecarga', $data);
                    $this->load->view('portal/templates/footer', $data);
                    return;
                } catch (Exception $e) {
                    var_dump($e);
                    $this->load->view('portal/templates/header2llave', $data);
                    $this->load->view('portal/llave/carga', $data);
                    $this->load->view('portal/templates/footer', $data);
                }
            }
        }



        $cuenta = $this->db->query("SELECT VALOR_PARAMETRO FROM MODGENERI.CUENTA_BANCO_PEOPLE WHERE PK_PARGEN_CODIGO=74");
        $data['cuenta'] = $cuenta->result_array;

        /* consulta el ultimo dato de direccion almacenado para esa entidad */
        $direccion = $this->db->query("SELECT DATO FROM MODCLIUNI.CLITBLCONTAC WHERE PK_CONTAC_CODIGO"
                . "= MODCLIUNI.CLIPKGCONSULTAS.fncmaxpkcontacto({$pk_ent_codigo},48)");
        $data['direccion'] = $direccion->result_array[0];
        $datosdir = explode('|', $data['direccion']['DATO']);
        $data['direccion'] = $datosdir[0];
        $ciudad = $this->db->query("SELECT pais.NOMBRE NOMBREPAIS, dep.NOMBRE NOMBREDEPARTAMENTO,ciu.nombre NOMBRECIUDAD
                FROM MODCLIUNI.CLITBLPAIS pais
                JOIN MODCLIUNI.CLITBLDEPPAI dep
                ON pais.pk_pais_codigo=dep.clitblpais_pk_pais_codigo
                JOIN MODCLIUNI.CLITBLCIUDAD ciu
                ON ciu.CLITBLDEPPAI_PK_DEP_CODIGO=dep.pk_dep_codigo
                JOIN MODCLIUNI.CLITBLENTIDA ent
                ON ent.clitblciudad_pk_ciu_codigo=ciu.pk_ciu_codigo
                WHERE ent.pk_ent_codigo={$pk_ent_codigo}");
        $data['ciudad'] = $ciudad->result_array[0];
        $telefono = $this->db->query("SELECT DATO FROM MODCLIUNI.CLITBLCONTAC WHERE PK_CONTAC_CODIGO"
                . "= MODCLIUNI.CLIPKGCONSULTAS.fncmaxpkcontacto({$pk_ent_codigo},47)");
        $data['telefono'] = $telefono->result_array[0];

        $medio = $this->db->query("SELECT PK_MEDPAG_CODIGO, NOMBRE FROM MODPROPAG.PPATBLMEDPAG WHERE PK_MEDPAG_CODIGO=1");
        $data['medioPago'] = $medio->result_array;
        $data['saldo'] = $this->saldollavemaestra();
        //$empresa = $this->session->userdata("entidad");
        $empresa = $_SESSION['entidad'];
        $data['entidad'] = $empresa;
        $data['empresa'] = $empresa['NOMBREEMPRESA'];
        //$usuario = $this->session->userdata("usuario");
        $usuario = $_SESSION['usuario'];
        $data['usuario'] = $usuario['NOMBRE'] . " " . $usuario['APELLIDO'];
        //$ultimaconexion = $this->session->userdata("ultimaconexion");
        $ultimaconexion = $_SESSION['ultimaconexion'];
        $data['ultimaconexion'] = $ultimaconexion;
        $data['llaveMaestra'] = 1;
        $data['menu'] = "carga";
        $this->load->view('portal/templates/header2llave', $data);
        $this->load->view('portal/llave/carga', $data);
        $this->load->view('portal/templates/footer', $data);
    }

    public function abono($pantalla = 0) {
        $rol=$_SESSION['rol'];
        //if (($this->session->userdata("rol") == 45 || $this->session->userdata("rol") == 47) && $this->session->userdata("CODIGO_PRODUCTO") == 70) {
        if (($rol == 60 || $rol == 61) && $_SESSION['PRODUCTOLLAVE']['CODIGO_PRODUCTO'] == 70) {
            
        } else {
            redirect("/portal/principal/pantalla");
        }
        //$empresa = $this->session->userdata("entidad");
        $empresa = $_SESSION['entidad'];
        $data['empresa'] = $empresa['NOMBREEMPRESA'];
        //$usuario = $this->session->userdata("usuario");
        $usuario = $_SESSION['usuario'];
        $data['llaveros'] = $this->returnarrayllaveros();
        $data['saldo'] = $this->saldollavemaestra();
        //$usuario = $this->session->userdata("usuario");
        $usuario = $_SESSION['usuario'];
        $data['usuario'] = $usuario['NOMBRE'] . " " . $usuario['APELLIDO'];
        //$ultimaconexion = $this->session->userdata("ultimaconexion");
        $ultimaconexion = $_SESSION['ultimaconexion'];
        $data['ultimaconexion'] = $ultimaconexion;
        $data['llaveMaestra'] = 1;
        $data['menu'] = "abono";
        $this->load->view('portal/templates/header2llave', $data);
        $this->load->view('portal/llave/abono', $data);
        $this->load->view('portal/templates/footer', $data);
    }

    public function abonoreturntarjellavero() {
        $this->verificarllaveMestraNuevosPerfiles();
        //$empresa = $this->session->userdata("entidad");
        $empresa = $_SESSION['entidad'];
        $pk_ent_codigo = $empresa['PK_ENT_CODIGO'];
        $post = $this->input->post();
        //$campana = $this->session->userdata("campana");
        $campana = $_SESSION['campana'];

        if ($post) {
            if (!empty($post['pk_llavero'])) {
                $pk_codigo_llavero = $post['pk_llavero'];
                $tarjetasllavero = $this->db->query("select vista.nomtar, vista.abr, vista.doc, vista.NOMPRO, vista.CODPROD, vista.codth, vista.NUMTAR,vista.PK_TARJET_CODIGO,vista.ciudad,vista.nomcampana,vista.nomcustodio, vista.IDENTIFICADOR,vista.pk_esttar_codigo,vista.estadotar from (
                                        SELECT distinct ent.documento DOC,
                                        ent.pk_ent_codigo CODTH,
                                        CIUD.nombre ciudad,
                                        CAM.nombre nomcampana,
                                        tipdoc.abreviacion abr, 
                                        NVL(TO_CHAR(tar.fecha_creacion,'DD/MM/YYYY'),'PENDIENTE') FEC,
                                        ent.nombre ||' '||ent.apellido nomtar,
                                        TAR.NUMERO NUMTAR,
                                        TAR.PK_TARJET_CODIGO,
                                        PRO.NOMBRE_PRODUCTO NOMPRO,
                                        pro.pk_produc_codigo codprod,
                                        ENTCUS.nombre ||' '||ENTCUS.apellido nomcustodio,
                                        entcus.pk_ent_codigo custoid,
                                        NVL(tar.identificador ,'-')IDENTIFICADOR,
                                        esttar.pk_esttar_codigo,	
                                        esttar.nombre estadotar
                                        FROM MODTARHAB.tartbltarjet tar 
                                        join MODTARHAB.TARTBLCUENTA CUE 
                                        ON cue.pk_tartblcuenta_codigo = tar.pk_tartblcuenta_codigo 
                                        AND cue.PK_ENT_CODIGO_EMP = $pk_ent_codigo
                                        JOIN MODCLIUNI.CLITBLENTIDA ENT 
                                        ON ent.pk_ent_codigo = cue.pk_ent_codigo_th
                                        JOIN MODCLIUNI.CLITBLCIUDAD CIUD ON ent.CLITBLCIUDAD_PK_CIU_CODIGO = CIUD.PK_CIU_CODIGO
                                        JOIN MODCLIUNI.CLITBLTIPDOC TIPDOC 
                                        ON tipdoc.pk_td_codigo = ent.clitbltipdoc_pk_td_codigo 
                                        JOIN MODPRODUC.PROTBLPRODUC PRO 
                                        ON pro.pk_produc_codigo = cue.pk_produc_codigo
                                        JOIN MODALISTA.ALITBLDETPED DETPED 
                                        ON detped.pk_detped_codigo = tar.pk_detped_codigo 
                                        JOIN MODALISTA.ALITBLPEDIDO PED ON ped.pk_pedido_codigo = detped.pk_pedido 
                                        JOIN MODCLIUNI.CLITBLENTIDA ENTCUS ON entcus.pk_ent_codigo = ped.pk_custodio
                                        JOIN MODCLIUNI.CLITBLCAMPAN CAM ON cam.pk_campan_codigo = ped.pk_campan_codigo 
                                        JOIN MODPROPAG.PPATBLDETORD DETORD ON detord.pk_pedido = detped.pk_detped_Codigo
                                        JOIN MODFACTUR.FACTBLFACORD FACORD ON facord.pk_ordcom_codigo=detord.pk_orden_compra
                                        JOIN MODPROPAG.PPATBLORDCOM ORDCOM ON facord.pk_ordcom_codigo=ordcom.pk_ordcom_codigo
                                        JOIN MODFACTUR.FACTBLFACTUR factur ON facord.pk_factur_codigo=factur.pk_factur_codigo 
                                        JOIN MODALISTA.ALITBLDESDET esdet ON esdet.ALITBLDETPED_PK_DETPED_CODIGO=tar.PK_DETPED_CODIGO  
                                        AND trunc(esdet.FECHA_CREACION)=trunc(tar.fecha_creacion)
                                        AND esdet.ALITBLESTDET_PK_ESTPED_CODIGO in (9)
                                        JOIN MODTARHAB.tartblesttar ESTTAR 
                                        ON esttar.pk_esttar_codigo = tar.pk_esttar_codigo            
                                        --order BY tar.fecha_creacion asc
                                         --union compartir tarjetas
                                        UNION 
                                        select distinct
                                        ent.documento DOC, 
                                        ent.pk_ent_codigo CODTH,
                                        CIUD.nombre ciudad,
                                        CAM.nombre nomcampana,
                                        tipdoc.abreviacion ABR, 
                                        NVL(TO_CHAR(tar.fecha_creacion,'DD/MM/YYYY'),'PENDIENTE') FEC,
                                        nvl(ent.razon_social,ent.nombre ||' '||ent.apellido) NOMTAR,
                                        TAR.NUMERO NUMTAR,
                                        tar.pk_tarjet_codigo ,
                                        pro.nombre_producto NOMPRO,
                                        pro.pk_produc_codigo codprod,
                                        ENTCUS.nombre ||' '||ENTCUS.apellido nomcustodio,
                                        entcus.pk_ent_codigo custoid,
                                        NVL(tar.identificador ,'-')IDENTIFICADOR,
                                        est.pk_esttar_codigo,	
                                        est.nombre estadotar
                                        from modcliuni.clitblentida ent
                                        JOIN MODCLIUNI.CLITBLCIUDAD CIUD ON ent.CLITBLCIUDAD_PK_CIU_CODIGO = CIUD.PK_CIU_CODIGO
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
                                        and parametro.PK_PRODUCTO_CODIGO = pro.pk_produc_codigo 
                                        and pro.pk_tippro_codigo=1
                                        JOIN MODTARHAB.tartbltarjet tar
                                        ON cue.pk_tartblcuenta_codigo = tar.pk_tartblcuenta_codigo 
                                        JOIN MODALISTA.ALITBLDESDET esdet ON esdet.ALITBLDETPED_PK_DETPED_CODIGO=tar.PK_DETPED_CODIGO  
                                        AND trunc(esdet.FECHA_CREACION)=trunc(tar.fecha_creacion)
                                        AND esdet.ALITBLESTDET_PK_ESTPED_CODIGO in (9)
                                        JOIN MODTARHAB.tartblesttar est	
                                        ON est.pk_esttar_codigo = tar.pk_esttar_codigo
                                        JOIN MODALISTA.ALITBLDETPED DETPED 
                                        ON detped.pk_detped_codigo = tar.pk_detped_codigo 
                                        JOIN MODALISTA.ALITBLPEDIDO PED ON ped.pk_pedido_codigo = detped.pk_pedido 
                                        JOIN MODCLIUNI.CLITBLENTIDA ENTCUS ON entcus.pk_ent_codigo = ped.pk_custodio
                                        JOIN MODCLIUNI.CLITBLCAMPAN CAM ON cam.pk_campan_codigo = ped.pk_campan_codigo 
                                        where  compar.pk_entidad_destino = $pk_ent_codigo
                                        and cotizacion.pk_campana_codigo = $campana
                                        and TAR.NUMERO is not null
                                        and compar.fecha_fin_compartir is null 
                                        ) vista 
                                        left join MODLLAVEMAESTRA.llavetblasotar asot on vista.codprod = asot.pk_produc_codigo and asot.fecha_desasociacion IS NULL
                                        and vista.codth = asot.pk_ent_codigo and vista.pk_tarjet_codigo = asot.pk_tarjeta_codigo where
                                        asot.pk_llavero_codigo =$pk_codigo_llavero and vista.numtar IS NOT NULL and vista.pk_esttar_codigo not in(6,7,8,15,16,17,18,19,20)");
                $data['tarjetallavero'] = $tarjetasllavero->result_array;
                $llaveros = $this->returnarrayllaveros();
                foreach ($llaveros as $value) {
                    if ($value['PK_LLAVERO_CODIGO'] == $pk_codigo_llavero) {
                        $nombrellaveroselect = $value['NOMBRE_LLAVERO'];
                    }
                }
                $data['nombrellaveroselect'] = $nombrellaveroselect;
                $data['pk_llavero_codigo'] = $post['pk_llavero'];
                $data['saldo_llavero'] = $this->returnsaldollaveroid($pk_codigo_llavero);
            } else {
                $data['errorpkllavero'] = 1;
            }
        }
        $data['empresa'] = $empresa['NOMBREEMPRESA'];
        $data['llaveros'] = $this->returnarrayllaveros();
        $data['saldo'] = $this->saldollavemaestra();
        //$usuario = $this->session->userdata("usuario");
        $usuario = $_SESSION['usuario'];
        $data['usuario'] = $usuario['NOMBRE'] . " " . $usuario['APELLIDO'];
        //$ultimaconexion = $this->session->userdata("ultimaconexion");
        $ultimaconexion = $_SESSION['ultimaconexion'];
        $data['ultimaconexion'] = $ultimaconexion;
        $data['llaveMaestra'] = 1;
        $data['menu'] = "abono";
        $this->load->view('portal/templates/header2llave', $data);
        $this->load->view('portal/llave/abono', $data);
        $this->load->view('portal/templates/footer', $data);
    }

    public function abonomasivoreturntarjellavero() {
        $this->verificarllaveMestraNuevosPerfiles();
        //$empresa = $this->session->userdata("entidad");
        $empresa = $_SESSION['entidad'];
        $pk_ent_codigo = $empresa['PK_ENT_CODIGO'];
        $post = $this->input->post();
        //$campana = $this->session->userdata("campana");
        $campana = $_SESSION['campana'];

        if ($post) {
            if (!empty($post['pk_llavero'])) {
                $pk_codigo_llavero = $post['pk_llavero'];
                $tarjetasllavero = $this->db->query("select vista.nomtar, vista.abr, vista.doc, vista.NOMPRO, vista.CODPROD, vista.codth, vista.NUMTAR,vista.PK_TARJET_CODIGO,vista.ciudad,vista.nomcampana,vista.nomcustodio, vista.IDENTIFICADOR,vista.pk_esttar_codigo,vista.estadotar from (
                                        SELECT distinct ent.documento DOC,
                                        ent.pk_ent_codigo CODTH,
                                        CIUD.nombre ciudad,
                                        CAM.nombre nomcampana,
                                        tipdoc.abreviacion abr, 
                                        NVL(TO_CHAR(tar.fecha_creacion,'DD/MM/YYYY'),'PENDIENTE') FEC,
                                        ent.nombre ||' '||ent.apellido nomtar,
                                        TAR.NUMERO NUMTAR,
                                        TAR.PK_TARJET_CODIGO,
                                        PRO.NOMBRE_PRODUCTO NOMPRO,
                                        pro.pk_produc_codigo codprod,
                                        ENTCUS.nombre ||' '||ENTCUS.apellido nomcustodio,
                                        entcus.pk_ent_codigo custoid,
                                        NVL(tar.identificador ,'-')IDENTIFICADOR,
                                        esttar.pk_esttar_codigo,	
                                        esttar.nombre estadotar
                                        FROM MODTARHAB.tartbltarjet tar 
                                        join MODTARHAB.TARTBLCUENTA CUE 
                                        ON cue.pk_tartblcuenta_codigo = tar.pk_tartblcuenta_codigo 
                                        AND cue.PK_ENT_CODIGO_EMP = $pk_ent_codigo
                                        JOIN MODCLIUNI.CLITBLENTIDA ENT 
                                        ON ent.pk_ent_codigo = cue.pk_ent_codigo_th
                                        JOIN MODCLIUNI.CLITBLCIUDAD CIUD ON ent.CLITBLCIUDAD_PK_CIU_CODIGO = CIUD.PK_CIU_CODIGO
                                        JOIN MODCLIUNI.CLITBLTIPDOC TIPDOC 
                                        ON tipdoc.pk_td_codigo = ent.clitbltipdoc_pk_td_codigo 
                                        JOIN MODPRODUC.PROTBLPRODUC PRO 
                                        ON pro.pk_produc_codigo = cue.pk_produc_codigo
                                        JOIN MODALISTA.ALITBLDETPED DETPED 
                                        ON detped.pk_detped_codigo = tar.pk_detped_codigo 
                                        JOIN MODALISTA.ALITBLPEDIDO PED ON ped.pk_pedido_codigo = detped.pk_pedido 
                                        JOIN MODCLIUNI.CLITBLENTIDA ENTCUS ON entcus.pk_ent_codigo = ped.pk_custodio
                                        JOIN MODCLIUNI.CLITBLCAMPAN CAM ON cam.pk_campan_codigo = ped.pk_campan_codigo 
                                        JOIN MODPROPAG.PPATBLDETORD DETORD ON detord.pk_pedido = detped.pk_detped_Codigo
                                        JOIN MODFACTUR.FACTBLFACORD FACORD ON facord.pk_ordcom_codigo=detord.pk_orden_compra
                                        JOIN MODPROPAG.PPATBLORDCOM ORDCOM ON facord.pk_ordcom_codigo=ordcom.pk_ordcom_codigo
                                        JOIN MODFACTUR.FACTBLFACTUR factur ON facord.pk_factur_codigo=factur.pk_factur_codigo 
                                        JOIN MODALISTA.ALITBLDESDET esdet ON esdet.ALITBLDETPED_PK_DETPED_CODIGO=tar.PK_DETPED_CODIGO  
                                        AND trunc(esdet.FECHA_CREACION)=trunc(tar.fecha_creacion)
                                        AND esdet.ALITBLESTDET_PK_ESTPED_CODIGO in (9)
                                        JOIN MODTARHAB.tartblesttar ESTTAR 
                                        ON esttar.pk_esttar_codigo = tar.pk_esttar_codigo
                                        and ESTTAR.pk_esttar_codigo not in(6,7,8,15,16,17,18,19,20) 
                                        --order BY tar.fecha_creacion asc
                                         --union compartir tarjetas
                                        UNION 
                                        select distinct
                                        ent.documento DOC, 
                                        ent.pk_ent_codigo CODTH,
                                        CIUD.nombre ciudad,
                                        CAM.nombre nomcampana,
                                        tipdoc.abreviacion ABR, 
                                        NVL(TO_CHAR(tar.fecha_creacion,'DD/MM/YYYY'),'PENDIENTE') FEC,
                                        nvl(ent.razon_social,ent.nombre ||' '||ent.apellido) NOMTAR,
                                        TAR.NUMERO NUMTAR,
                                        tar.pk_tarjet_codigo ,
                                        pro.nombre_producto NOMPRO,
                                        pro.pk_produc_codigo codprod,
                                        ENTCUS.nombre ||' '||ENTCUS.apellido nomcustodio,
                                        entcus.pk_ent_codigo custoid,
                                        NVL(tar.identificador ,'-')IDENTIFICADOR,
                                        est.pk_esttar_codigo,	
                                        est.nombre estadotar
                                        from modcliuni.clitblentida ent
                                        JOIN MODCLIUNI.CLITBLCIUDAD CIUD ON ent.CLITBLCIUDAD_PK_CIU_CODIGO = CIUD.PK_CIU_CODIGO
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
                                        and parametro.PK_PRODUCTO_CODIGO = pro.pk_produc_codigo 
                                        and pro.pk_tippro_codigo=1
                                        JOIN MODTARHAB.tartbltarjet tar
                                        ON cue.pk_tartblcuenta_codigo = tar.pk_tartblcuenta_codigo 
                                        JOIN MODALISTA.ALITBLDESDET esdet ON esdet.ALITBLDETPED_PK_DETPED_CODIGO=tar.PK_DETPED_CODIGO  
                                        AND trunc(esdet.FECHA_CREACION)=trunc(tar.fecha_creacion)
                                        AND esdet.ALITBLESTDET_PK_ESTPED_CODIGO in (9)
                                        JOIN MODTARHAB.tartblesttar est	
                                        ON est.pk_esttar_codigo = tar.pk_esttar_codigo
                                        JOIN MODALISTA.ALITBLDETPED DETPED 
                                        ON detped.pk_detped_codigo = tar.pk_detped_codigo 
                                        JOIN MODALISTA.ALITBLPEDIDO PED ON ped.pk_pedido_codigo = detped.pk_pedido 
                                        JOIN MODCLIUNI.CLITBLENTIDA ENTCUS ON entcus.pk_ent_codigo = ped.pk_custodio
                                        JOIN MODCLIUNI.CLITBLCAMPAN CAM ON cam.pk_campan_codigo = ped.pk_campan_codigo 
                                        where  compar.pk_entidad_destino = $pk_ent_codigo
                                        and cotizacion.pk_campana_codigo = $campana
                                        and TAR.NUMERO is not null
                                        and compar.fecha_fin_compartir is null 
                                        and est.pk_esttar_codigo not in(6,7,8,15,16,17,18,19,20)
                                        ) vista 
                                        left join MODLLAVEMAESTRA.llavetblasotar asot on vista.codprod = asot.pk_produc_codigo and asot.fecha_desasociacion IS NULL
                                        and vista.codth = asot.pk_ent_codigo and vista.pk_tarjet_codigo = asot.pk_tarjeta_codigo where
                                        asot.pk_llavero_codigo =$pk_codigo_llavero and vista.numtar IS NOT NULL");
                $data['tarjetallavero'] = $tarjetasllavero->result_array;
                $llaveros = $this->returnarrayllaveros();
                foreach ($llaveros as $value) {
                    if ($value['PK_LLAVERO_CODIGO'] == $pk_codigo_llavero) {
                        $nombrellaveroselect = $value['NOMBRE_LLAVERO'];
                    }
                }
                $data['nombrellaveroselect'] = $nombrellaveroselect;
                $data['pk_llavero_codigo'] = $post['pk_llavero'];
                $data['saldo_llavero'] = $this->returnsaldollaveroid($pk_codigo_llavero);
            } else {
                $data['errorpkllavero'] = 1;
            }
        }

        $data['llaveros'] = $this->returnarrayllaveros();
        $data['saldo'] = $this->saldollavemaestra();
        //$usuario = $this->session->userdata("usuario");
        $usuario = $_SESSION['usuario'];
        $data['usuario'] = $usuario['NOMBRE'] . " " . $usuario['APELLIDO'];
        //$ultimaconexion = $this->session->userdata("ultimaconexion");
        $ultimaconexion = $_SESSION['ultimaconexion'];
        $data['ultimaconexion'] = $ultimaconexion;
        $data['llaveMaestra'] = 1;
        $data['menu'] = "abono";
        $this->load->view('portal/templates/header2llave', $data);
        $this->load->view('portal/llave/abonoMasivo', $data);
        $this->load->view('portal/templates/footer', $data);
    }

    public function abonounoauno() {
        //$empresa = $this->session->userdata("entidad");
        $empresa = $_SESSION['entidad'];
        $pk_ent_codigo = $empresa['PK_ENT_CODIGO'];
        $post = $this->input->post();
        //$campana = $this->session->userdata("campana");
        $campana = $_SESSION['campana'];
        if (empty($post['pk_llavero_codigo'])) {
            $data['errorpkllavero'] = 1;
            redirect("/portal/llaveMaestra/abono?errorpkllavero");
        }
        if ($post) {
            if (!empty($post['tarjetasabono'])) {
                $pk_codigo_llavero = $post['pk_llavero_codigo'];
                $llaveros = $this->returnarrayllaveros();
                foreach ($llaveros as $value) {
                    if ($value['PK_LLAVERO_CODIGO'] == $pk_codigo_llavero) {
                        $nombrellaveroselect = $value['NOMBRE_LLAVERO'];
                    }
                }

                $lista = '';
// pk_llaveros para abonar
                foreach ($post['tarjetasabono'] as $key => $value) {
                    $lista = $value . ',' . $lista;
                }
                if (is_null($lista)) {
                    $lista = '0';
                } else {
                    $lista = substr($lista, 0, -1);
                }

                $tarjetasllavero = $this->db->query("select vista.nomtar, vista.abr, vista.doc, vista.NOMPRO, vista.CODPROD, vista.codth, vista.NUMTAR,vista.PK_TARJET_CODIGO,vista.ciudad,vista.nomcampana,vista.nomcustodio, vista.IDENTIFICADOR, asot.asotar_codigo PKTAR from (	
                                        SELECT distinct ent.documento DOC,	
                                        ent.pk_ent_codigo CODTH,	
                                        CIUD.nombre ciudad,	
                                        CAM.nombre nomcampana,	
                                        tipdoc.abreviacion abr, 	
                                        NVL(TO_CHAR(tar.fecha_creacion,'DD/MM/YYYY'),'PENDIENTE') FEC,	
                                        ent.nombre ||' '||ent.apellido nomtar,	
                                        TAR.NUMERO NUMTAR,	
                                        TAR.PK_TARJET_CODIGO,	
                                        PRO.NOMBRE_PRODUCTO NOMPRO,	
                                        pro.pk_produc_codigo codprod,	
                                        ENTCUS.nombre ||' '||ENTCUS.apellido nomcustodio,	
                                        entcus.pk_ent_codigo custoid,	
                                        NVL(tar.identificador ,'-')IDENTIFICADOR	
                                        FROM MODTARHAB.tartbltarjet tar 	
                                        join MODTARHAB.TARTBLCUENTA CUE 	
                                        ON cue.pk_tartblcuenta_codigo = tar.pk_tartblcuenta_codigo 	
                                        AND cue.PK_ENT_CODIGO_EMP = $pk_ent_codigo	
                                        JOIN MODCLIUNI.CLITBLENTIDA ENT 	
                                        ON ent.pk_ent_codigo = cue.pk_ent_codigo_th	
                                        JOIN MODCLIUNI.CLITBLCIUDAD CIUD ON ent.CLITBLCIUDAD_PK_CIU_CODIGO = CIUD.PK_CIU_CODIGO	
                                        JOIN MODCLIUNI.CLITBLTIPDOC TIPDOC 	
                                        ON tipdoc.pk_td_codigo = ent.clitbltipdoc_pk_td_codigo 	
                                        JOIN MODPRODUC.PROTBLPRODUC PRO 	
                                        ON pro.pk_produc_codigo = cue.pk_produc_codigo	
                                        JOIN MODALISTA.ALITBLDETPED DETPED 	
                                        ON detped.pk_detped_codigo = tar.pk_detped_codigo 	
                                        JOIN MODALISTA.ALITBLPEDIDO PED ON ped.pk_pedido_codigo = detped.pk_pedido 	
                                        JOIN MODCLIUNI.CLITBLENTIDA ENTCUS ON entcus.pk_ent_codigo = ped.pk_custodio	
                                        JOIN MODCLIUNI.CLITBLCAMPAN CAM ON cam.pk_campan_codigo = ped.pk_campan_codigo 	
                                        JOIN MODPROPAG.PPATBLDETORD DETORD ON detord.pk_pedido = detped.pk_detped_Codigo	
                                        JOIN MODFACTUR.FACTBLFACORD FACORD ON facord.pk_ordcom_codigo=detord.pk_orden_compra	
                                        JOIN MODPROPAG.PPATBLORDCOM ORDCOM ON facord.pk_ordcom_codigo=ordcom.pk_ordcom_codigo	
                                        JOIN MODFACTUR.FACTBLFACTUR factur ON facord.pk_factur_codigo=factur.pk_factur_codigo 
                                        JOIN MODALISTA.ALITBLDESDET esdet ON esdet.ALITBLDETPED_PK_DETPED_CODIGO=tar.PK_DETPED_CODIGO  
                                        AND trunc(esdet.FECHA_CREACION)=trunc(tar.fecha_creacion)
                                        AND esdet.ALITBLESTDET_PK_ESTPED_CODIGO in (9)
                                        JOIN MODTARHAB.tartblesttar ESTTAR 	
                                        ON esttar.pk_esttar_codigo = tar.pk_esttar_codigo	
                                        and ESTTAR.pk_esttar_codigo not in(6,7,8,15,16,17,18,19,20) 	
                                        --order BY tar.fecha_creacion asc	
                                         --union compartir tarjetas	
                                        UNION 	
                                        select distinct
                                        ent.documento DOC, 	
                                        ent.pk_ent_codigo CODTH,	
                                        CIUD.nombre ciudad,	
                                        CAM.nombre nomcampana,	
                                        tipdoc.abreviacion ABR, 	
                                        NVL(TO_CHAR(tar.fecha_creacion,'DD/MM/YYYY'),'PENDIENTE') FEC,	
                                        nvl(ent.razon_social,ent.nombre ||' '||ent.apellido) NOMTAR,	
                                        TAR.NUMERO NUMTAR,	
                                        tar.pk_tarjet_codigo ,	
                                        pro.nombre_producto NOMPRO,	
                                        pro.pk_produc_codigo codprod,	
                                        ENTCUS.nombre ||' '||ENTCUS.apellido nomcustodio,	
                                        entcus.pk_ent_codigo custoid,	
                                        NVL(tar.identificador ,'-')IDENTIFICADOR	
                                        from modcliuni.clitblentida ent	
                                        JOIN MODCLIUNI.CLITBLCIUDAD CIUD ON ent.CLITBLCIUDAD_PK_CIU_CODIGO = CIUD.PK_CIU_CODIGO	
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
                                        and parametro.PK_PRODUCTO_CODIGO = pro.pk_produc_codigo 	
                                        and pro.pk_tippro_codigo=1	
                                        JOIN MODTARHAB.tartbltarjet tar	
                                        ON cue.pk_tartblcuenta_codigo = tar.pk_tartblcuenta_codigo
                                        JOIN MODALISTA.ALITBLDESDET esdet ON esdet.ALITBLDETPED_PK_DETPED_CODIGO=tar.PK_DETPED_CODIGO  
                                        AND trunc(esdet.FECHA_CREACION)=trunc(tar.fecha_creacion)
                                        AND esdet.ALITBLESTDET_PK_ESTPED_CODIGO in (9)
                                        JOIN MODTARHAB.tartblesttar est	
                                        ON est.pk_esttar_codigo = tar.pk_esttar_codigo	       
                                        JOIN MODALISTA.ALITBLDETPED DETPED 	
                                        ON detped.pk_detped_codigo = tar.pk_detped_codigo 	
                                        JOIN MODALISTA.ALITBLPEDIDO PED ON ped.pk_pedido_codigo = detped.pk_pedido 	
                                        JOIN MODCLIUNI.CLITBLENTIDA ENTCUS ON entcus.pk_ent_codigo = ped.pk_custodio	
                                        JOIN MODCLIUNI.CLITBLCAMPAN CAM ON cam.pk_campan_codigo = ped.pk_campan_codigo 	
                                        where  compar.pk_entidad_destino = $pk_ent_codigo	
                                        and cotizacion.pk_campana_codigo = $campana	
                                        and TAR.NUMERO is not null	
                                        and compar.fecha_fin_compartir is null 	
                                        and est.pk_esttar_codigo not in(6,7,8,15,16,17,18,19,20) 	
                                        ) vista 	
                                        left join MODLLAVEMAESTRA.llavetblasotar asot on vista.codprod = asot.pk_produc_codigo and asot.fecha_desasociacion IS NULL	
                                        and vista.codth = asot.pk_ent_codigo and vista.pk_tarjet_codigo = asot.pk_tarjeta_codigo where	
                                        asot.pk_llavero_codigo =$pk_codigo_llavero and vista.numtar IS NOT NULL and asot.pk_tarjeta_codigo IN ($lista)");

                $data['tarjetallavero'] = $tarjetasllavero->result_array;

                $conceptos = $this->db->query("SELECT PK_CONCEPTO_CODIGO, NOMBRE "
                        . "FROM MODLLAVEMAESTRA.LLAVETBLCONCEPTOMOV ORDER BY PK_CONCEPTO_CODIGO");
                $data['conceptos'] = $conceptos->result_array;

                $data['nombrellaveroselect'] = $nombrellaveroselect;
                $data['pk_llavero_codigo'] = $post['pk_llavero_codigo'];
                $saldollavero = $this->returnsaldollaveroid($pk_codigo_llavero);
                $data['saldo_llavero'] = $saldollavero;
                $_SESSION['saldo_llavero'] = $saldollavero;
                $_SESSION['pk_llavero_codigo'] = $pk_codigo_llavero;
//                $this->session->set_userdata(array('pk_llavero_codigo' => $pk_codigo_llavero, 'saldollavero' => $saldollavero));
            } else {
                redirect("/portal/llaveMaestra/abono?errordata");
            }
        }
        $data['llaveros'] = $this->returnarrayllaveros();
        $data['saldo'] = $this->saldollavemaestra();
        //$usuario = $this->session->userdata("usuario");
        $usuario = $_SESSION['usuario'];
        $data['usuario'] = $usuario['NOMBRE'] . " " . $usuario['APELLIDO'];
        //$ultimaconexion = $this->session->userdata("ultimaconexion");
        $ultimaconexion = $_SESSION['ultimaconexion'];
        $data['ultimaconexion'] = $ultimaconexion;
        $data['llaveMaestra'] = 1;
        $data['menu'] = "abono";
        $this->load->view('portal/templates/header2llave', $data);
        $this->load->view('portal/llave/abono2', $data);
        $this->load->view('portal/templates/footer', $data);
    }

    public function abonounoaunofin() {


        //$empresa = $this->session->userdata("entidad");
        $empresa = $_SESSION['entidad'];
        $pk_ent_codigo_empresa = $empresa['PK_ENT_CODIGO'];
        //$usuario = $this->session->userdata("usuario");
        $usuario = $_SESSION['usuario'];
        $pk_ent_codigo = $usuario['PK_ENT_CODIGO']; //entidad a la que se le envia correo pin confirmacion
        $usuariocreacion = $usuario['USUARIO_ACCESO'];
        $post = $this->input->post();

        if ($post) {
//            var_dump($post['pk_llavero_codigo']);
//            unset($post['DataTables_Table_0_length']);
            foreach ($post as $key => $value) {
//DIVIDE EL REGISTRO

                $regdivido = explode("/", $key, 5);
//CREA UN REGISTRO DE CADA UNO
                $datosth = array(
                    "CODTH" => $regdivido[1],
                    "CODPROD" => $regdivido[2],
                    "PK_TARJET_CODIGO" => $regdivido[3],
                    "PKTAR" => $regdivido[4],
                    "CONCEPTO" => "",
                    "MONTO" => "",
                    "COMISION" => "",
                    "PORCCOMISION" => ""
                );
                $llave = $regdivido[1] . $regdivido[2] . $regdivido[3] . $regdivido[4];

                if (!array_key_exists($llave, $contenido)) {

                    if ($regdivido[0] == 'monto') {
                        $porciones = explode(".", $value);
                        $monto_abono = $this->dejarSoloCaracteresDeseados($porciones[0], "0123456789");
                        $datosth['MONTO'] = $monto_abono;
                    } elseif ($regdivido[0] == 'concepto') {
                        $datosth['CONCEPTO'] = $value;
                    }
                    $contenido[$llave] = $datosth;
                } else {
                    if ($regdivido[0] == 'monto') {
                        $porciones = explode(".", $value);
                        $monto_abono = $this->dejarSoloCaracteresDeseados($porciones[0], "0123456789");
                        $contenido[$llave]['MONTO'] = $monto_abono;
                    } elseif ($regdivido[0] == 'concepto') {
                        $contenido[$llave]['CONCEPTO'] = $value;
                    }
                }
            }
            $total_abono = 0;
            $total_comision = 0;
            foreach ($contenido as $key1 => $value1) {
//                var_dump($value1['MONTO']);
                $total_abono += $value1['MONTO'];
                //calculo impuestos para cada  abono
                $pk_prod_codigo = $value1['CODPROD'];
                $monto_abon = $value1['MONTO'];
                //$campana = $this->session->userdata("campana");
                $campana = $_SESSION['campana'];
                $sqlcomision = $this->db->query("select NVL(modpropag.ppapkgconsultas.fncconvalorabono($pk_prod_codigo,$pk_ent_codigo_empresa,$monto_abon,$campana),0) COMISION from dual");
                $Comision = $sqlcomision->result_array[0]['COMISION'];
                $contenido[$key1]['COMISION'] = $Comision;

                //consulta tasa por producto
                $sqltasa = $this->db->query("SELECT parametro.tasa
                    FROM MODCOMERC.COMTBLCOTIZA cotizacion
                    INNER JOIN MODCOMERC.COMTBLPROCES proceso
                        ON proceso.PK_COTIZA_CODIGO = cotizacion.pk_cotiza_codigo
                    INNER JOIN MODCOMERC.COMTBLPARAME parametro
                        ON parametro.pk_proces_codigo = proceso.pk_proces_codigo
                    WHERE  proceso.pk_estado_codigo=1 and cotizacion.pk_estado_codigo=1
                    AND  cotizacion.PK_ENTIDA_CLIENTE =  $pk_ent_codigo_empresa
                    AND parametro.pk_producto_codigo = $pk_prod_codigo
                    AND cotizacion.pk_campana_codigo=$campana");
                $tasa = $sqltasa->result_array[0]['TASA'];

                $contenido[$key1]['PORCCOMISION'] = isset($tasa) ? $tasa : 0;
                log_info($this->dataLlave . ' pk_producto_codigo=' . $pk_prod_codigo . ' pk_ent_th=' . $pk_ent_codigo_empresa . ' monto_abono=' . $monto_abon . ' campana=' . $campana . ' Comision=' . $Comision . ' Tasa=' . $tasa);

                $total_comision += $Comision;
            }


            //valida si se exceden los montos diarios permitidos
            //validar moto maximo abonos por dia establecido en la creacion llave maestra
            $sql = "BEGIN MODLLAVEMAESTRA.LLAVMAEPKGGENERAL.returntotalpktipomovdia(
                parpkentidad =>:parpkentidad,
                parpktipmovllavero=>:parpktipmovllavero,
                parpkcoorllavero=>:parpkcoorllavero,
                parmensajerespuesta =>:parmensajerespuesta,
                partotalmov=>:partotalmov,
                parvalorlimite=>:parvalorlimite,
                parrespuesta=>:parrespuesta);
                END;";
            $conn = $this->db->conn_id;
            $stmt = oci_parse($conn, $sql);
            oci_bind_by_name($stmt, ':parpkentidad', $pk_ent_codigo_empresa, 32);
            $parpktipomov = 4; // abono tarjeta
            oci_bind_by_name($stmt, ':parpktipmovllavero', $parpktipomov, 32);
            oci_bind_by_name($stmt, ':parpkcoorllavero', $pk_ent_codigo, 32);
            oci_bind_by_name($stmt, ':parmensajerespuesta', $parmensajerespuesta, 32);
            oci_bind_by_name($stmt, ':partotalmov', $partotalmov, 32);
            oci_bind_by_name($stmt, ':parvalorlimite', $parvalorlimite, 32);
            oci_bind_by_name($stmt, ':parrespuesta', $parrespuesta, 32);
            if (!oci_execute($stmt)) {
                $e = oci_error($stmt);
                VAR_DUMP($e);
            }
            if ($parrespuesta == 1) {
                $totalmovimientos = $partotalmov + $total_abono;
                $limiteabonollave = (int) $parvalorlimite;
                log_info($this->dataLlave . ' Total movimientos=' . $totalmovimientos . 'Limite abonos =' . $limiteabonollave);
            }


            if ($totalmovimientos > $limiteabonollave) {
                redirect("/portal/llaveMaestra/abono?errorlimiteabono");
            } else {

                // log_info($this->dataLlave . 'COMISION TOTAL='.$total_comision);

                $pk_llavero_codigo = $_SESSION["pk_llavero_codigo"]; //$this->session->userdata("pk_llavero_codigo");
                $saldo_llavero = $_SESSION["saldo_llavero"]; //$this->session->userdata("saldollavero");
//            var_dump("total abono=" . $total_abono . "-saldo llavero=" . $saldo_llavero . "-pkllavero=" . $pk_llavero_codigo);
                //se averigua costos de impuestos para el total del abono
                $valor_impuestos = 0;
                //se busca la llave maestra asociada a la entidad 
                $sqlllavemae = $this->db->query("SELECT llavmae_codigo pk_llave_maestra FROM MODLLAVEMAESTRA.LLAVETBLLLAVMAE llavemae WHERE llavemae.pk_ent_codigo= $pk_ent_codigo_empresa");
                $pk_llave_maestra = $sqlllavemae->result_array[0]['PK_LLAVE_MAESTRA'];
                
                //se llama procedimiento validar si los abonos mas comisiones son inferiores a saldo llavero 
                $sql = "BEGIN MODLLAVEMAESTRA.llavmaepkgfacturacion.prcpacimplementarimpuestos(
                parllavecodigo =>:parllavecodigo,
                parmontoAbono=>:parmontoAbono,
                parmontoComision=>:parmontoComision,
                parsaldollavero=>:parsaldollavero,
                parestadocomi=>:parestadocomi,
                paraprobacion=>:paraprobacion,
                parvalorimpuestos=>:parvalorimpuestos
                );
                END;";
                $conn = $this->db->conn_id;
                $stmt = oci_parse($conn, $sql);
                oci_bind_by_name($stmt, ':parllavecodigo', $pk_llave_maestra, 32);
                oci_bind_by_name($stmt, ':parmontoAbono', $total_abono, 32);
                oci_bind_by_name($stmt, ':parmontoComision', $total_comision, 32);
                oci_bind_by_name($stmt, ':parsaldollavero', $saldo_llavero, 32);
                $parestadocomi=1;
                oci_bind_by_name($stmt, ':parestadocomi', $parestadocomi, 32);
                oci_bind_by_name($stmt, ':paraprobacion', $paraprobacion, 32);
                oci_bind_by_name($stmt, ':parvalorimpuestos', $parvalorimpuestos, 32);
                if (!oci_execute($stmt)) {
                    $e = oci_error($stmt);
                    VAR_DUMP($e);
                    log_info($this->dataLlave . 'IMPAAA RESPUESTA prcpacimplementarimpuestos error= ' .$e);
                }
                if ($paraprobacion == 1) {
                    log_info($this->dataLlave . 'RESPUESTA prcpacimplementarimpuestos = ' . $paraprobacion);
                    $valor_impuestos = $parvalorimpuestos;

                    // Calculo valor a descontar llavero
                    $impuestos = $total_comision + $valor_impuestos;

                    $difsaldoabono = $saldo_llavero - $total_abono - $impuestos;
                    log_info('AALLAVERO paraprobacion= ' . $paraprobacion . ' Saldo llavero= ' . $saldo_llavero . 'Total Impuestos = ' . $impuestos . ' Total abono= ' . $total_abono . ' Total comision = ' . $total_comision . ' Impuestos= ' . $valor_impuestos.' diferenciasaldo='.$difsaldoabono);
                    //valida que el saldo llavero sea inferior al total del movimineto mas impuestos
                    if ($difsaldoabono < 0) {
                        redirect("/portal/llaveMaestra/abono?errorsaldoinsu");
                    }

                    log_info($this->dataLlave . ' Nuevo saldo llavero=' . $saldo_llavero - $total_abono - $impuestos);
//llamado procedimiento creacion movimiento llavero para obtener pk_mov_llavero, para enviarlo en llamado procedimiento abono tarjetas (prcabonotarjetallavemaestra)
//
                    $sql = "BEGIN MODLLAVEMAESTRA.LLAVMAEPKGGENERAL.prccrearmovimientollaveroabono(
                        parpkentidad =>:parpkentidad,
                        parpkllavero=>:parpkllavero,
                        parnuevosaldollavero =>:parnuevosaldollavero,
                        parsaldoantiguollavero=>:parsaldoantiguollavero,
                        parmontomov=>:parmontomov,
                        parusuario =>:parusuario,
                        partipomovllaverocodigo =>:partipomovllaverocodigo,                        
                        parmovllaverocodigo=>:parmovllaverocodigo,
                        pinconfirmacion=>:pinconfirmacion,
                        correoenvio =>:correoenvio,
                        parmensajerespuesta=>:parmensajerespuesta,
                        parrespuesta=>:parrespuesta);
                        END;";
                    $conn = $this->db->conn_id;
                    $stmt = oci_parse($conn, $sql);
                    $parpk_endidad_codigo = $pk_ent_codigo;
                    oci_bind_by_name($stmt, ':parpkentidad', $parpk_endidad_codigo, 32);
                    oci_bind_by_name($stmt, ':parpkllavero', $pk_llavero_codigo, 32);
                    $parnuevosaldollavero = $saldo_llavero - $total_abono - $impuestos;
                    oci_bind_by_name($stmt, ':parnuevosaldollavero', $parnuevosaldollavero, 32);
                    oci_bind_by_name($stmt, ':parsaldoantiguollavero', $saldo_llavero, 32);
                    oci_bind_by_name($stmt, ':parmontomov', $total_abono, 32);
                    oci_bind_by_name($stmt, ':parusuario', $usuariocreacion, 32);
                    $partipomovllavero = 4; //abono tarjeta
                    oci_bind_by_name($stmt, ':partipomovllaverocodigo', $partipomovllavero, 32);
                    oci_bind_by_name($stmt, ':parmovllaverocodigo', $parmovllaverocodigo, 32);
                    oci_bind_by_name($stmt, ':pinconfirmacion', $pinconfirmacion, 32);
                    oci_bind_by_name($stmt, ':correoenvio', $correoenvio, 50);
                    oci_bind_by_name($stmt, ':parmensajerespuesta', $parmensajerespuesta, 32);
                    oci_bind_by_name($stmt, ':parrespuesta', $parrespuesta, 32);
                    if (!oci_execute($stmt)) {
                        $e = oci_error($stmt);
                        VAR_DUMP($e);
                    }

                    if ($parrespuesta == 1) {

                        
                      //eliminar impuestos luego de generar movimientos
                      $sql = "BEGIN MODLLAVEMAESTRA.LLAVMAEPKGGENERAL.prceliminarimpuestostotalabon(
                        parllavema_codigo =>:parllavema_codigo,
                        parrespuestae=>:parrespuestae);
                        END;";
                    $conn = $this->db->conn_id;
                    $stmt = oci_parse($conn, $sql);
                    $parpk_endidad_codigo = $pk_ent_codigo;
                    oci_bind_by_name($stmt, ':parllavema_codigo', $pk_llave_maestra, 32);
                    oci_bind_by_name($stmt, ':parrespuestae', $parrespuestae, 32);
                    if (!oci_execute($stmt)) {
                        $e = oci_error($stmt);
                        VAR_DUMP($e);
                         log_info($this->dataLlave . 'Error al eliminar impuestos error= ' . $e);
                    }
                      //termina eliminar impuestos
//Array con data tarjetas 
                    if($parrespuestae==1){
                        $abonodata = array();
                        $parmovllaverocod = $parmovllaverocodigo;
                        $pinconfirmaabono = $pinconfirmacion;
                        $correodestino = $correoenvio;
                        $obj = array();
                        foreach ($contenido as $key => $value) {
                            $obj = (object) [
                                        'pk_tarjeta_codigo' => $value['PK_TARJET_CODIGO'], //codigo tarjeta
                                        'codigo_th' => $value['CODTH'], //codigo tarjetahabiente
                                        'producto_codigo' => $value['CODPROD'], //codigo producto
                                        'monto_abono' => $value['MONTO'], //Monto abono
                                        'pk_asotar_codigo' => $value['PKTAR'], //pk_asotar_codigo
                                        'pk_concepto_abono' => $value['CONCEPTO'], //pk_concepto_abono 
                                        'valor_comision' => $value['COMISION'], //valor comision 
                                        'porc_comision' => $value['PORCCOMISION'] //porcentaje comision  
                            ];

                            array_push($abonodata, (array) $obj);
                            log_info($this->dataLlave . ' PK_COD_LLAVERO= ' . $pk_llavero_codigo
                                    . ' CorreoDestino= ' . $correodestino
                                    . ' parmovllaverocod ' . $parmovllaverocod
                                    . ' pk_tarjeta_codigo= ' . $value['PK_TARJET_CODIGO']
                                    . ' codigo_th= ' . $value['CODTH']
                                    . ' producto_codigo= ' . $value['CODPROD']
                                    . ' monto_abono= ' . $value['MONTO']
                                    . ' pk_asotar_codigo= ' . $value['PKTAR']
                                    . ' pk_concepto_abono= ' . $value['CONCEPTO']
                                    . ' valor_comision= ' . $value['COMISION']
                                    , ' porc_comision= ' . $value['PORCCOMISION']);
                        }

                        $_SESSION['datatarjabono'] = $abonodata;
                        $confirmarre = 200;
                        $correoen = 'resgistrado';
                        if ($this->mask_email($correodestino)) {
                            $correoen = $this->mask_email($correodestino);
                        }
//                    $this->session->set_userdata(array('CORREO_DES_ABONO' => $correoen, 'PAR_MOVLLAVERO_CODIGO_ABONO' => $parmovllaverocod, 'PK_COD_LLAVERO' => $pk_llavero_codigo));
                        $_SESSION['CORREO_DES_ABONO'] = $correoen;
                        $_SESSION['PAR_MOVLLAVERO_CODIGO_ABONO'] = $parmovllaverocod;
                        $_SESSION['PK_COD_LLAVERO'] = $pk_llavero_codigo;
                        redirect("/portal/llaveMaestra/abono?abonoOK&cre=$pk_llavero_codigo");
                    }
//                   
                    }
                }
                // fin parrespuesta impuestos 
                else {
                    redirect("/portal/llaveMaestra/abono?errorsaldoinsu");
                }
            }
        }
    }
/*inicia abono masivo plantilla*/
    public function abonoMasivo($sucessc=0) {
        //$empresa = $this->session->userdata("entidad");
        $this->verificarllaveMestraNuevosPerfiles();
        $empresa = $_SESSION['entidad'];
        $pk_ent_codigo = $empresa['PK_ENT_CODIGO'];
        $this->verificarllaveMestraNuevosPerfiles();
        $data['llaveros'] = $this->returnarrayllaveros();
        $data['saldo'] = $this->saldollavemaestra();
        //$empresa = $this->session->userdata("entidad");
        $empresa = $_SESSION['entidad'];
        $data['empresa'] = $empresa['NOMBREEMPRESA'];
        //$usuario = $this->session->userdata("usuario");
        $usuario = $_SESSION['usuario'];
        $data['usuario'] = $usuario['NOMBRE'] . " " . $usuario['APELLIDO'];
        //$ultimaconexion = $this->session->userdata("ultimaconexion");
        $ultimaconexion = $_SESSION['ultimaconexion'];
        $data['ultimaconexion'] = $ultimaconexion;
        $data['llaveMaestra'] = 1;
        $data['menu'] = "abono";
         if($sucessc==0){
         $data['sucess']=0;   
        }            
        elseif($sucessc==1){
        $data['sucess']=$sucessc;
        }elseif($sucessc==200){
        $data['sucesse']=$sucessc;
        }elseif($sucessc==500){
        $data['sucesse']=300;
        }elseif($sucessc==600){
        $data['sucesse']=600;
        }
        $this->load->view('portal/templates/header2llave', $data);
        $this->load->view('portal/llave/abonoMasivoPlantilla', $data);
        $this->load->view('portal/templates/footer', $data);
    }
     public function descargarAbonoPlantilla() {
        $this->verificarllaveMestraNuevosPerfiles();
        $path = '/uploads/ARCHIVO-DISPERSION-MASIVA-ABONO-LL.xlsx';
        header("Location:" . $path);
    }
     public function solicitudAbonMasivaPlantilla($pantalla = 0) { 
      log_info($this->logHeader . ' INGRESO SOLICITUD DE MASIVA PROCESAMIENTO DE ARCHIVO');
        log_info($this->iniciLog . $this->logHeader . $this->session->userdata("usuario"));
        log_info($this->logHeader . ' MI PERFIL SERA CUAL ' . $_SESSION['PRODUCTOLLAVE']['CODIGO_PRODUCTO'].'y rol que tiene es'.$rol);
        $this->verificarllaveMestraNuevosPerfiles();
        $post = $this->input->post();
        if($pantalla==1){
                
        if (!empty($post['pk_llavero'])){
                if(!empty($_FILES["file"]["name"])) {
                  
                $pk_llavero = $post['pk_llavero'];
            $saldo_llavero=$this->returnsaldollaveroid($pk_llavero);
             $nombre_pedido=$post['nombrePedido'];
              log_info(' SALDO LLAVERO VALIDADO ANDRES'.$saldo_llavero);
             log_info(' SALDO LLAVERO'.$saldo_llavero);
            log_info('MI LLAVERO ES->'.$pk_llavero); 
              $date = date('Y_m_d');
            $random = rand(1000, 9999);
            $name = strtolower($date . '_' . $random);
            $tmp_name = $name;
           log_info(' INSERTA ARCHIVOS $NAME'.$tmp_name);
            log_info($this->logHeader . ' INSERTA EN FLOW FILES');
            $BLOB_CONTENT = file_get_contents($_FILES['file']['tmp_name']);
            $sql = "INSERT INTO modllavemaestra.WWV_FLOW_FILE_OBJECTSABLL$(FLOW_ID, NAME,BLOB_CONTENT, DELETED_AS_OF) 
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
                log_info($this->logHeader . ' EL ARCHIVO DE ABONOS NO SE CARGO A LA BASE DE DATOS' . $e['message']);
            } else {
                log_info($this->logHeader . ' SE CARGO EL ARCHIVO DE ABONOS A LA BASE DE DATOS');
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


            $sql = "BEGIN modllavemaestra.LLAVMAEPKGGENERAL.prccargarmasabontarjetall(:parnomarch
                                                                    ,:parentidad
                                                                    ,:parusuario
                                                                    ,:parcoordinador
                                                                    ,:parcampana
                                                                    ,:parllavero
                                                                    ,:parsaldollavero
                                                                    ,:parnombrepedido
                                                                    ,:parmovllaverocodi
                                                                    ,:pinconfirmacion
                                                                    ,:correoenvio
                                                                    ,:parmensajerespuesta
                                                                    ,:parrespuesta);
                        END;";

               
            $conn = $this->db->conn_id;
            $stmt = oci_parse($conn, $sql);
            $pararchivo = $tmp_name;
            $parllavero=$pk_llavero;
            $parsaldollavero=$saldo_llavero;
            $parnombrepedido=$nombre_pedido;
             log_info('OBTENGO EL LLAVERO->'.$parllavero);
            //$parempresa = $this->session->userdata("pkentidad");
            $parempresa = $_SESSION['pkentidad'];
            //$usuario = $this->session->userdata("usuario");
            $usuario = $_SESSION['usuario'];
            $parusuario = $usuario['USUARIO_ACCESO'];
            //$parcampana = $this->session->userdata("campana");
            $parcampana = $_SESSION['campana'];
            $parcoordinador = $usuario['PK_ENT_CODIGO'];
            log_info($this->logHeader . 'ahi vamos con la campana'
                        . $parcampana);

            //TIPO NUMBER INPUT INPUT
            oci_bind_by_name($stmt, ':parnomarch', $pararchivo, 100);
            //TIPO VARCHAR2 INPUT
            oci_bind_by_name($stmt, ':parentidad', $parempresa, 100);
            //TIPO NUMBER INPUT
            oci_bind_by_name($stmt, ':parusuario', $parusuario, 100);
            //TIPO VARCHAR2 INPUT
            oci_bind_by_name($stmt, ':parcoordinador', $parcoordinador, 100);
            //TIPO VARCHAR2 INPUT
            oci_bind_by_name($stmt, ':parcampana', $parcampana, 100);
            //TIPO VARCHAR2 INPUT
            oci_bind_by_name($stmt, ':parllavero', $parllavero, 100);
            //TIPO VARCHAR2 INPUT
            oci_bind_by_name($stmt, ':parsaldollavero', $parsaldollavero, 100);
             //TIPO VARCHAR2 INPUT
            oci_bind_by_name($stmt, ':parnombrepedido', $parnombrepedido, 100);  
            //TIPO VARCHAR2 OUTPUT
            oci_bind_by_name($stmt, ':parmovllaverocodi', $parmovllaverocodi, 100);
             //TIPO VARCHAR2 OUTPUT
            oci_bind_by_name($stmt, ':pinconfirmacion', $pinconfirmacion, 100);
             //TIPO VARCHAR2 OUTPUT
            oci_bind_by_name($stmt, ':correoenvio', $correoenvio, 100);
             //TIPO VARCHAR2 OUTPUT
             oci_bind_by_name($stmt, ':parmensajerespuesta', $parmensajerespuesta, 100);
            //TIPO VARCHAR2 OUTPUT
             oci_bind_by_name($stmt, ':parrespuesta', $parrespuesta, 100);
            if (!@oci_execute($stmt)) {
                $e = oci_error($stmt);
                $mensaje = explode(":", $e['message']);
                var_dump($mensaje);
                //$data['error'] = 1;
                $parrespuesta = 0;
                log_info($this->logHeader . ' ERROR PROCESANDO EL ARCHIVO DE ABONOS EN APOLO '
                        . $e['message'] . ' EMPRESA ' . $parempresa);
                $sucessc=200;
                redirect("portal/llaveMaestra/abonoMasivo/".$sucessc);
            } elseif ($parrespuesta != 1) {
                if($parrespuesta==51){
                   redirect("portal/llaveMaestra/abonoMasivo?errorsaldoinsu");
                }elseif($parrespuesta==50){
                 redirect("portal/llaveMaestra/abonoMasivo?errorlimiteabono");   
                }elseif($parrespuesta==53){
                     redirect("/portal/llaveMaestra/abonoMasivo?errorsaldoinsu");
                }                
                else{
                log_info($this->logHeader . ' SE PROCESA EL ARCHIVO DE ABONOS CON ERRORES ');
               redirect("portal/llaveMaestra/mensajeErrorAbon/$parrespuesta/$pararchivo");
                }
            } elseif ($parrespuesta == 1) {
                $codigo = 'OK';
                $sucessc=1;
                 $tarjetasabonos = $this->returnarraytarjetasabonar($parllavero);
                 
                    $abonodata = array();
                        $parmovllaverocod = $parmovllaverocodi;
                        $pinconfirmaabono = $pinconfirmacion;
                        $correodestino = $correoenvio;
                        
                        $obj = array();
                    foreach ($tarjetasabonos as $value) {
                    
                              $obj = (object) [
                                        'pk_tarjeta_codigo' => $value['CODTAR'], //codigo tarjeta
                                        'codigo_th' => $value['CODTH'], //codigo tarjetahabiente
                                        'producto_codigo' => $value['PK_PRODUCTO'], //codigo producto
                                        'monto_abono' => $value['MONTO_ABONO'], //Monto abono
                                        'pk_asotar_codigo' => $value['PKTAR'], //pk_asotar_codigo
                                        'pk_concepto_abono' => $value['CONCEPTO'], //pk_concepto_abono 
                                        'valor_comision' => $value['COMISION'], //valor comision 
                                        'porc_comision' => $value['PORCOMISION'] //porcentaje comision
                            ];
                            array_push($abonodata, (array) $obj);
                              
                            log_info(' TARJETAS ABONAR PRUEBAS VERIFICACION CODTAR->'.$value['CODTAR'].' PK_PRODUCTO'.$value['PK_PRODUCTO'].' CONCEPTO'.$value['CONCEPTO'].' CONCEPTO'.$value['PORCOMISION'].'movllaverocod'.$parmovllaverocod);
                     
                    }
                        $_SESSION['datatarjabono'] = $abonodata;
                        $confirmarre = 200;
                        $correoen = 'resgistrado';
                        if ($this->mask_email($correodestino)) {
                            $correoen = $this->mask_email($correodestino);
                        }
//                    $this->session->set_userdata(array('CORREO_DES_ABONO' => $correoen, 'PAR_MOVLLAVERO_CODIGO_ABONO' => $parmovllaverocod, 'PK_COD_LLAVERO' => $pk_llavero_codigo));
                        $_SESSION['CORREO_DES_ABONO'] = $correoen;
                        $_SESSION['PAR_MOVLLAVERO_CODIGO_ABONO'] = $parmovllaverocod;
                        $_SESSION['PK_COD_LLAVERO'] = $pk_llavero_codigo;
                        redirect("/portal/llaveMaestra/abonoMasivo?abonoOK&cre=$parllavero");
                log_info($this->logHeader . ' ARCHIVO PROCESADO RESPUESTA' . $parrespuesta . ' CODIGO SOLICITUD ' . $codigo);
              //  redirect("portal/llaveMaestra/abonoMasivoPlantilla/".$sucessc);
                
            }
                }else{
                    $sucessc=600;
                   redirect("portal/llaveMaestra/abonoMasivo/".$sucessc); 
                }
        }else{
             $sucessc=500;
            redirect("portal/llaveMaestra/abonoMasivo/".$sucessc);
        }  
  
        }else{
           redirect("portal/llaveMaestra/abonoMasivo/");
        }
    }
    //retorna tarjetas abonar
    public function returnarraytarjetasabonar($parllavero) {
        //$empresa = $this->session->userdata("entidad");

        $sql = "BEGIN MODLLAVEMAESTRA.LLAVMAEPKGGENERAL.prcretornatarjetasabonar(
                parllaverocodigo =>:parllaverocodigo,
                tarjetasabonar =>:tarjetasabonar,
                parrespuesta=>:parrespuesta);
                END;";
        $conn = $this->db->conn_id;
        $stmt = oci_parse($conn, $sql);
        $curs = oci_new_cursor($conn);
        oci_bind_by_name($stmt, ":tarjetasabonar", $curs, -1, OCI_B_CURSOR);
        oci_bind_by_name($stmt, ':parllaverocodigo', $parllavero, 32);
        oci_bind_by_name($stmt, ':parrespuesta', $parrespuesta, 32);
        if (!oci_execute($stmt)) {
            $e = oci_error($stmt);
            VAR_DUMP($e);
            exit;
        }
        $tarjetasabonos = array();
        if ($parrespuesta == 1) {
            oci_execute($curs);  // Ejecutar el REF CURSOR como un ide de sentencia normal
            while (($row = oci_fetch_array($curs, OCI_ASSOC + OCI_RETURN_NULLS)) != false) {

                    array_push($tarjetasabonos, array(
                        'CODTAR' => $row['CODTAR'], 'CODTH' => $row['CODTH'], 'PK_PRODUCTO' => $row['PK_PRODUCTO'], 'MONTO_ABONO' => $row['MONTO_ABONO'], 'COMISION' => $row['COMISION'], 'PORCOMISION' => $row['PORCOMISION'], 'PKTAR' => $row['PKTAR'], 'CONCEPTO' => $row['CONCEPTO']
                    ));

            }
//            $data['llaveros'] = $llaveros;
        }
        return $tarjetasabonos;
    }
    
    public function mensajeErrorAbon($error=0, $nombrearchivo=0) {
        $this->verificarllaveMestraNuevosPerfiles();
        //$empresa = $this->session->userdata("entidad");
        if($error>0){
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
        $data['saldo'] = $this->saldollavemaestra();
        //$empresa = $this->session->userdata("entidad");
        $empresa = $_SESSION['entidad'];
        $data['empresa'] = $empresa['NOMBREEMPRESA'];
        //$usuario = $this->session->userdata("usuario");
        $usuario = $_SESSION['usuario'];
        $data['usuario'] = $usuario['NOMBRE'] . " " . $usuario['APELLIDO'];
        
        log_info('DATA ERROR PERO QUE PANA'.'USUARIO VALIDO'.$data['usuario'].'EMPRESA'.$data['empresa']);
        //$ultimaconexion = $this->session->userdata("ultimaconexion");
        $ultimaconexion = $_SESSION['ultimaconexion'];
        $data['ultimaconexion'] = $ultimaconexion;
        $this->load->view('portal/templates/header2llave', $data);
        $this->load->view('portal//llave/mensajeErrorAbon', $data);
        $this->load->view('portal/templates/footer', $error);
        }else{
             redirect("portal/llaveMaestra/abonoMasivo");
        }
    }
    
/* termina abono masivo plantilla  */
    /*
    public function abonoMasivo() {
        //$empresa = $this->session->userdata("entidad");
        $empresa = $_SESSION['entidad'];
        $pk_ent_codigo = $empresa['PK_ENT_CODIGO'];
        $this->verificarllaveMestraNuevosPerfiles();
        $data['llaveros'] = $this->returnarrayllaveros();
        $data['saldo'] = $this->saldollavemaestra();
        //$empresa = $this->session->userdata("entidad");
        $empresa = $_SESSION['entidad'];
        $data['empresa'] = $empresa['NOMBREEMPRESA'];
        //$usuario = $this->session->userdata("usuario");
        $usuario = $_SESSION['usuario'];
        $data['usuario'] = $usuario['NOMBRE'] . " " . $usuario['APELLIDO'];
        //$ultimaconexion = $this->session->userdata("ultimaconexion");
        $ultimaconexion = $_SESSION['ultimaconexion'];
        $data['ultimaconexion'] = $ultimaconexion;
        $data['llaveMaestra'] = 1;
        $data['menu'] = "abono";
        $this->load->view('portal/templates/header2llave', $data);
        $this->load->view('portal/llave/abonoMasivo', $data);
        $this->load->view('portal/templates/footer', $data);
    }

    public function abonomasivodatath() {
        $this->verificarllaveMestra();
        //$empresa = $this->session->userdata("entidad");
        $empresa = $_SESSION['entidad'];
        $pk_ent_codigo = $empresa['PK_ENT_CODIGO'];
        $post = $this->input->post();
        //$campana = $this->session->userdata("campana");
        $campana = $_SESSION['campana'];

        if ($post) {
            if (!empty($post['pk_llavero_codigo'])) {
                if (!empty($post['tarjetasabono'])) {
                    $pk_codigo_llavero = $post['pk_llavero_codigo'];
                    $llaveros = $this->returnarrayllaveros();
                    foreach ($llaveros as $value) {
                        if ($value['PK_LLAVERO_CODIGO'] == $pk_codigo_llavero) {
                            $nombrellaveroselect = $value['NOMBRE_LLAVERO'];
                        }
                    }
                    $lista = '';
                    $cantth = (count($post['tarjetasabono']));
                    $data['cantfilas'] = $cantth;
// pk_llaveros para abonar
                    foreach ($post['tarjetasabono'] as $key => $value) {
                        $lista = $value . ',' . $lista;
                    }
                    if (is_null($lista)) {
                        $lista = '0';
                    } else {
                        $lista = substr($lista, 0, -1);
                    }

                    $tarjetasllavero = $this->db->query("select vista.nomtar, vista.abr, vista.doc, vista.NOMPRO, vista.CODPROD, vista.codth, vista.NUMTAR,vista.PK_TARJET_CODIGO,vista.ciudad,vista.nomcampana,vista.nomcustodio, vista.IDENTIFICADOR, asot.asotar_codigo PKTAR from (	
                                        SELECT distinct ent.documento DOC,	
                                        ent.pk_ent_codigo CODTH,	
                                        CIUD.nombre ciudad,	
                                        CAM.nombre nomcampana,	
                                        tipdoc.abreviacion abr, 	
                                        NVL(TO_CHAR(tar.fecha_creacion,'DD/MM/YYYY'),'PENDIENTE') FEC,	
                                        ent.nombre ||' '||ent.apellido nomtar,	
                                        TAR.NUMERO NUMTAR,	
                                        TAR.PK_TARJET_CODIGO,	
                                        PRO.NOMBRE_PRODUCTO NOMPRO,	
                                        pro.pk_produc_codigo codprod,	
                                        ENTCUS.nombre ||' '||ENTCUS.apellido nomcustodio,	
                                        entcus.pk_ent_codigo custoid,	
                                        NVL(tar.identificador ,'-')IDENTIFICADOR	
                                        FROM MODTARHAB.tartbltarjet tar 	
                                        join MODTARHAB.TARTBLCUENTA CUE 	
                                        ON cue.pk_tartblcuenta_codigo = tar.pk_tartblcuenta_codigo 	
                                        AND cue.PK_ENT_CODIGO_EMP = $pk_ent_codigo	
                                        JOIN MODCLIUNI.CLITBLENTIDA ENT 	
                                        ON ent.pk_ent_codigo = cue.pk_ent_codigo_th	
                                        JOIN MODCLIUNI.CLITBLCIUDAD CIUD ON ent.CLITBLCIUDAD_PK_CIU_CODIGO = CIUD.PK_CIU_CODIGO	
                                        JOIN MODCLIUNI.CLITBLTIPDOC TIPDOC 	
                                        ON tipdoc.pk_td_codigo = ent.clitbltipdoc_pk_td_codigo 	
                                        JOIN MODPRODUC.PROTBLPRODUC PRO 	
                                        ON pro.pk_produc_codigo = cue.pk_produc_codigo	
                                        JOIN MODALISTA.ALITBLDETPED DETPED 	
                                        ON detped.pk_detped_codigo = tar.pk_detped_codigo 	
                                        JOIN MODALISTA.ALITBLPEDIDO PED ON ped.pk_pedido_codigo = detped.pk_pedido 	
                                        JOIN MODCLIUNI.CLITBLENTIDA ENTCUS ON entcus.pk_ent_codigo = ped.pk_custodio	
                                        JOIN MODCLIUNI.CLITBLCAMPAN CAM ON cam.pk_campan_codigo = ped.pk_campan_codigo 	
                                        JOIN MODPROPAG.PPATBLDETORD DETORD ON detord.pk_pedido = detped.pk_detped_Codigo	
                                        JOIN MODFACTUR.FACTBLFACORD FACORD ON facord.pk_ordcom_codigo=detord.pk_orden_compra	
                                        JOIN MODPROPAG.PPATBLORDCOM ORDCOM ON facord.pk_ordcom_codigo=ordcom.pk_ordcom_codigo	
                                        JOIN MODFACTUR.FACTBLFACTUR factur ON facord.pk_factur_codigo=factur.pk_factur_codigo 
                                        JOIN MODALISTA.ALITBLDESDET esdet ON esdet.ALITBLDETPED_PK_DETPED_CODIGO=tar.PK_DETPED_CODIGO  
                                        AND trunc(esdet.FECHA_CREACION)=trunc(tar.fecha_creacion)
                                        AND esdet.ALITBLESTDET_PK_ESTPED_CODIGO in (9)
                                        JOIN MODTARHAB.tartblesttar ESTTAR 	
                                        ON esttar.pk_esttar_codigo = tar.pk_esttar_codigo 	
                                        and ESTTAR.pk_esttar_codigo not in(6,7,8,15,16,17,18,19,20) 	
                                        --order BY tar.fecha_creacion asc	
                                         --union compartir tarjetas	
                                        UNION 	
                                        select distinct	
                                        ent.documento DOC, 	
                                        ent.pk_ent_codigo CODTH,	
                                        CIUD.nombre ciudad,	
                                        CAM.nombre nomcampana,	
                                        tipdoc.abreviacion ABR, 	
                                        NVL(TO_CHAR(tar.fecha_creacion,'DD/MM/YYYY'),'PENDIENTE') FEC,	
                                        nvl(ent.razon_social,ent.nombre ||' '||ent.apellido) NOMTAR,	
                                        TAR.NUMERO NUMTAR,	
                                        tar.pk_tarjet_codigo ,	
                                        pro.nombre_producto NOMPRO,	
                                        pro.pk_produc_codigo codprod,	
                                        ENTCUS.nombre ||' '||ENTCUS.apellido nomcustodio,	
                                        entcus.pk_ent_codigo custoid,	
                                        NVL(tar.identificador ,'-')IDENTIFICADOR	
                                        from modcliuni.clitblentida ent	
                                        JOIN MODCLIUNI.CLITBLCIUDAD CIUD ON ent.CLITBLCIUDAD_PK_CIU_CODIGO = CIUD.PK_CIU_CODIGO	
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
                                        and parametro.PK_PRODUCTO_CODIGO = pro.pk_produc_codigo 	
                                        and pro.pk_tippro_codigo=1	
                                        JOIN MODTARHAB.tartbltarjet tar	
                                        ON cue.pk_tartblcuenta_codigo = tar.pk_tartblcuenta_codigo
                                        JOIN MODALISTA.ALITBLDESDET esdet ON esdet.ALITBLDETPED_PK_DETPED_CODIGO=tar.PK_DETPED_CODIGO  
                                        AND trunc(esdet.FECHA_CREACION)=trunc(tar.fecha_creacion)
                                        AND esdet.ALITBLESTDET_PK_ESTPED_CODIGO in (9)
                                        JOIN MODTARHAB.tartblesttar est	
                                        ON est.pk_esttar_codigo = tar.pk_esttar_codigo	
                                        JOIN MODALISTA.ALITBLDETPED DETPED 	
                                        ON detped.pk_detped_codigo = tar.pk_detped_codigo 	
                                        JOIN MODALISTA.ALITBLPEDIDO PED ON ped.pk_pedido_codigo = detped.pk_pedido 	
                                        JOIN MODCLIUNI.CLITBLENTIDA ENTCUS ON entcus.pk_ent_codigo = ped.pk_custodio	
                                        JOIN MODCLIUNI.CLITBLCAMPAN CAM ON cam.pk_campan_codigo = ped.pk_campan_codigo 	
                                        where  compar.pk_entidad_destino = $pk_ent_codigo	
                                        and cotizacion.pk_campana_codigo = $campana	
                                        and TAR.NUMERO is not null	
                                        and compar.fecha_fin_compartir is null 	
                                        and est.pk_esttar_codigo not in(6,7,8,15,16,17,18,19,20) 	
                                        ) vista 	
                                        left join MODLLAVEMAESTRA.llavetblasotar asot on vista.codprod = asot.pk_produc_codigo and asot.fecha_desasociacion IS NULL	
                                        and vista.codth = asot.pk_ent_codigo and vista.pk_tarjet_codigo = asot.pk_tarjeta_codigo where	
                                        asot.pk_llavero_codigo =$pk_codigo_llavero and vista.numtar IS NOT NULL and asot.pk_tarjeta_codigo IN ($lista)");

                    $data['tarjetallavero'] = $tarjetasllavero->result_array;

                    $conceptos = $this->db->query("SELECT PK_CONCEPTO_CODIGO, NOMBRE "
                            . "FROM MODLLAVEMAESTRA.LLAVETBLCONCEPTOMOV ORDER BY PK_CONCEPTO_CODIGO");
                    $data['conceptos'] = $conceptos->result_array;

                    $data['nombrellaveroselect'] = $nombrellaveroselect;
                    $data['pk_llavero_codigo'] = $post['pk_llavero_codigo'];
                    $saldollavero = $this->returnsaldollaveroid($pk_codigo_llavero);
                    $data['saldo_llavero'] = $saldollavero;
//redondea al valor anterior
                    $saldoporth = floor($saldollavero / $cantth);
                   // log_info('VALIDANDO MONTOS->saldoth'.$saldoporth.' $cantth'.$cantth.' $saldollavero'.$saldollavero);
                  
                 log_info('QUIERO VALIDAR EL SALDOPORTTH'.$saldoporth);
//                    $saldoporth = round($saldollavero / $cantth, 0, PHP_ROUND_HALF_EVEN);
//                    var_dump($saldollavero / $cantth);
//                    var_dump($saldoporth);
//                    $saldoporth='$'.number_format($saldoporth, 2, '.', ',');
                    $data['saldoporth'] = '$' . number_format($saldoporth, 2, '.', ',');
                    
                    $sumatoriaAbono = $saldoporth * $cantth;
                    $data['sumatoriaabono'] = $sumatoriaAbono;
                    $data['difabono'] = $saldollavero - $sumatoriaAbono;
                    $_SESSION['saldo_llavero'] = $saldollavero;
                    $_SESSION['pk_llavero_codigo'] = $pk_codigo_llavero;
                } else {
                    redirect("/portal/llaveMaestra/abonoMasivo?errordata");
                }
            } else {
                redirect("/portal/llaveMaestra/abonoMasivo?errorpk");
            }
        }
        $this->verificarllaveMestra();
        $data['llaveros'] = $this->returnarrayllaveros();
        $data['saldo'] = $this->saldollavemaestra();
        //$empresa = $this->session->userdata("entidad");
        $empresa = $_SESSION['entidad'];
        $data['empresa'] = $empresa['NOMBREEMPRESA'];
        //$usuario = $this->session->userdata("usuario");
        $usuario = $_SESSION['usuario'];
        $data['usuario'] = $usuario['NOMBRE'] . " " . $usuario['APELLIDO'];
        //$ultimaconexion = $this->session->userdata("ultimaconexion");
        $ultimaconexion = $_SESSION['ultimaconexion'];
        $data['ultimaconexion'] = $ultimaconexion;
        $data['llaveMaestra'] = 1;
        $data['menu'] = "abono";
        $this->load->view('portal/templates/header2llave', $data);
        $this->load->view('portal/llave/abonoMasivo2', $data);
        $this->load->view('portal/templates/footer', $data);
    }

    public function abonomasivofin() {


        //$empresa = $this->session->userdata("entidad");
        $empresa = $_SESSION['entidad'];
        $pk_ent_codigo_empresa = $empresa['PK_ENT_CODIGO'];
        //$usuario = $this->session->userdata("usuario");
        $usuario = $_SESSION['usuario'];
        $pk_ent_codigo = $usuario['PK_ENT_CODIGO'];
        $usuariocreacion = $usuario['USUARIO_ACCESO'];
        $post = $this->input->post();

        if ($post) {
//            var_dump($post['pk_llavero_codigo']);
//            unset($post['DataTables_Table_0_length']);
            foreach ($post as $key => $value) {
//DIVIDE EL REGISTRO

                $regdivido = explode("/", $key, 5);
//CREA UN REGISTRO DE CADA UNO
                $datosth = array(
                    "CODTH" => $regdivido[1],
                    "CODPROD" => $regdivido[2],
                    "PK_TARJET_CODIGO" => $regdivido[3],
                    "PKTAR" => $regdivido[4],
                    "CONCEPTO" => "",
                    "MONTO" => "",
                    "COMISION" => "",
                    "PORCCOMISION" => ""
                );
                $llave = $regdivido[1] . $regdivido[2] . $regdivido[3] . $regdivido[4];

                if (!array_key_exists($llave, $contenido)) {

                    if ($regdivido[0] == 'monto') {
                        $porciones = explode(".", $value);
                        $monto_abono = $this->dejarSoloCaracteresDeseados($porciones[0], "0123456789");
                        $datosth['MONTO'] = $monto_abono;
                    } elseif ($regdivido[0] == 'concepto') {
                        $datosth['CONCEPTO'] = $value;
                    }
                    $contenido[$llave] = $datosth;
                    log_info('DATOS CONTENIDO'. var_dump(print_r($contenido[$llave])));
                } else {
                    if ($regdivido[0] == 'monto') {
                        $porciones = explode(".", $value);
                        $monto_abono = $this->dejarSoloCaracteresDeseados($porciones[0], "0123456789");
                        $contenido[$llave]['MONTO'] = $monto_abono;
                    } elseif ($regdivido[0] == 'concepto') {
                        $contenido[$llave]['CONCEPTO'] = $value;
                    }
                }
            }
            $total_abono = 0;
            $total_comision = 0;
            foreach ($contenido as $key1 => $value1) {
//                var_dump($value1['MONTO']);
                $total_abono += $value1['MONTO'];
                //calculo impuestos para cada  abono
                $pk_prod_codigo = $value1['CODPROD'];
                $monto_abon = $value1['MONTO'];
                //$campana = $this->session->userdata("campana");
                $campana = $_SESSION['campana'];
                $sqlcomision = $this->db->query("select modpropag.ppapkgconsultas.fncconvalorabono($pk_prod_codigo,$pk_ent_codigo_empresa,$monto_abon,$campana) COMISION from dual");
                $Comision = $sqlcomision->result_array[0]['COMISION'];
                $contenido[$key1]['COMISION'] = $Comision;

                //consulta tasa por producto
                $sqltasa = $this->db->query("SELECT parametro.tasa
                    FROM MODCOMERC.COMTBLCOTIZA cotizacion
                    INNER JOIN MODCOMERC.COMTBLPROCES proceso
                        ON proceso.PK_COTIZA_CODIGO = cotizacion.pk_cotiza_codigo
                    INNER JOIN MODCOMERC.COMTBLPARAME parametro
                        ON parametro.pk_proces_codigo = proceso.pk_proces_codigo
                    WHERE  proceso.pk_estado_codigo=1 and cotizacion.pk_estado_codigo=1
                    AND  cotizacion.PK_ENTIDA_CLIENTE =  $pk_ent_codigo_empresa
                    AND parametro.pk_producto_codigo = $pk_prod_codigo
                    AND cotizacion.pk_campana_codigo = $campana");
                $tasa = $sqltasa->result_array[0]['TASA'];
                $contenido[$key1]['PORCCOMISION'] = $tasa;

                $total_comision += $Comision;
            }

            $pk_llavero_codigo = $_SESSION["pk_llavero_codigo"]; //$this->session->userdata("pk_llavero_codigo");
            $saldo_llavero = $_SESSION["saldo_llavero"]; //$this->session->userdata("saldollavero");
            //
           //validar moto maximo abonos por dia establecido en la creacion llave maestra
            $sql = "BEGIN MODLLAVEMAESTRA.LLAVMAEPKGGENERAL.returntotalpktipomovdia(
                parpkentidad =>:parpkentidad,
                parpktipmovllavero=>:parpktipmovllavero,
                parpkcoorllavero=>:parpkcoorllavero,
                parmensajerespuesta =>:parmensajerespuesta,
                partotalmov=>:partotalmov,
                parvalorlimite=>:parvalorlimite,
                parrespuesta=>:parrespuesta);
                END;";
            $conn = $this->db->conn_id;
            $stmt = oci_parse($conn, $sql);
            oci_bind_by_name($stmt, ':parpkentidad', $pk_ent_codigo_empresa, 32);
            $parpktipomov = 4; // abono tarjeta
            oci_bind_by_name($stmt, ':parpktipmovllavero', $parpktipomov, 32);
            oci_bind_by_name($stmt, ':parpkcoorllavero', $pk_ent_codigo, 32);
            oci_bind_by_name($stmt, ':parmensajerespuesta', $parmensajerespuesta, 32);
            oci_bind_by_name($stmt, ':partotalmov', $partotalmov, 32);
            oci_bind_by_name($stmt, ':parvalorlimite', $parvalorlimite, 32);
            oci_bind_by_name($stmt, ':parrespuesta', $parrespuesta, 32);
            if (!oci_execute($stmt)) {
                $e = oci_error($stmt);
                VAR_DUMP($e);
            }
            if ($parrespuesta == 1) {
                $totalmovimientos = $partotalmov + $total_abono;
                $limiteabonollave = (int) $parvalorlimite;
            }
            if ($totalmovimientos > $limiteabonollave) {
                redirect("/portal/llaveMaestra/abonoMasivo?errorlimiteabono");
            } else {

                //se averigua costos de impuestos para el total del abono
                $valor_impuestos = 0;
                //se busca la llave maestra asociada a la entidad 
                $sqlllavemae = $this->db->query("SELECT llavmae_codigo pk_llave_maestra FROM MODLLAVEMAESTRA.LLAVETBLLLAVMAE llavemae WHERE llavemae.pk_ent_codigo= $pk_ent_codigo_empresa");
                $pk_llave_maestra = $sqlllavemae->result_array[0]['PK_LLAVE_MAESTRA'];

                //se llama procedimiento validar si los abonos mas comisiones son inferiores a saldo llavero 
                $sql = "BEGIN MODLLAVEMAESTRA.llavmaepkgfacturacion.prcpacimplementarimpuestos(
                parllavecodigo =>:parllavecodigo,
                parmontoAbono=>:parmontoAbono,
                parmontoComision=>:parmontoComision,
                parsaldollavero=>:parsaldollavero,
                paraprobacion=>:paraprobacion,
                parvalorimpuestos=>:parvalorimpuestos
                );
                END;";
                $conn = $this->db->conn_id;
                $stmt = oci_parse($conn, $sql);
                oci_bind_by_name($stmt, ':parllavecodigo', $pk_llave_maestra, 32);
                oci_bind_by_name($stmt, ':parmontoAbono', $total_abono, 32);
                oci_bind_by_name($stmt, ':parmontoComision', $total_comision, 32);
                oci_bind_by_name($stmt, ':parsaldollavero', $saldo_llavero, 32);
                oci_bind_by_name($stmt, ':paraprobacion', $paraprobacion, 32);
                oci_bind_by_name($stmt, ':parvalorimpuestos', $parvalorimpuestos, 32);
                if (!oci_execute($stmt)) {
                    $e = oci_error($stmt);
                    VAR_DUMP($e);
                    log_info($this->dataLlave . 'RESPUESTA prcpacimplementarimpuestos error= ' . $e);
                }
                if ($paraprobacion == 1) {
                    log_info($this->dataLlave . 'RESPUESTA prcpacimplementarimpuestos = ' . $paraprobacion);
                    $valor_impuestos = $parvalorimpuestos;

                    // Calculo valor a descontar llavero
                    $impuestos = $total_comision + $valor_impuestos;

                    $difsaldoabono = $saldo_llavero - $total_abono - $impuestos;

                    if ($difsaldoabono < 0) {
                        redirect("/portal/llaveMaestra/abonoMasivo?errorsaldoinsu");
                    }


//llamado procedimiento creacion movimiento llavero luego se llama procedimiento abono tarjetas (prcabonotarjetallavemaestra)
//
                    $sql = "BEGIN MODLLAVEMAESTRA.LLAVMAEPKGGENERAL.prccrearmovimientollaveroabono(
                        parpkentidad =>:parpkentidad,
                        parpkllavero=>:parpkllavero,
                        parnuevosaldollavero =>:parnuevosaldollavero,
                        parsaldoantiguollavero=>:parsaldoantiguollavero,
                        parmontomov=>:parmontomov,
                        parusuario =>:parusuario,
                        partipomovllaverocodigo =>:partipomovllaverocodigo,                        
                        parmovllaverocodigo=>:parmovllaverocodigo,
                        pinconfirmacion=>:pinconfirmacion,
                        correoenvio =>:correoenvio,
                        parmensajerespuesta=>:parmensajerespuesta,
                        parrespuesta=>:parrespuesta);
                        END;";
                    $conn = $this->db->conn_id;
                    $stmt = oci_parse($conn, $sql);
                    $parpk_endidad_codigo = $pk_ent_codigo;
                    oci_bind_by_name($stmt, ':parpkentidad', $parpk_endidad_codigo, 32);
                    oci_bind_by_name($stmt, ':parpkllavero', $pk_llavero_codigo, 32);
                    $parnuevosaldollavero = $saldo_llavero - $total_abono - $impuestos;
                    oci_bind_by_name($stmt, ':parnuevosaldollavero', $parnuevosaldollavero, 55);
                    oci_bind_by_name($stmt, ':parsaldoantiguollavero', $saldo_llavero, 55);
                    oci_bind_by_name($stmt, ':parmontomov', $total_abono, 32);
                    oci_bind_by_name($stmt, ':parusuario', $usuariocreacion, 32);
                    $partipomovllavero = 4; //abono tarjeta
                    oci_bind_by_name($stmt, ':partipomovllaverocodigo', $partipomovllavero, 32);
                    oci_bind_by_name($stmt, ':parmovllaverocodigo', $parmovllaverocodigo, 32);
                    oci_bind_by_name($stmt, ':pinconfirmacion', $pinconfirmacion, 32);
                    oci_bind_by_name($stmt, ':correoenvio', $correoenvio, 50);
                    oci_bind_by_name($stmt, ':parmensajerespuesta', $parmensajerespuesta, 32);
                    oci_bind_by_name($stmt, ':parrespuesta', $parrespuesta, 32);
                    if (!oci_execute($stmt)) {
                        $e = oci_error($stmt);
                        VAR_DUMP($e);
                        exit;
                    }

                    if ($parrespuesta == 1) {

//Array con data tarjetas 
                        $abonodata = array();
                        $parmovllaverocod = $parmovllaverocodigo;
                        $pinconfirmaabono = $pinconfirmacion;
                        $correodestino = $correoenvio;
                        $obj = array();
                        foreach ($contenido as $key => $value) {
                            $obj = (object) [
                                        'pk_tarjeta_codigo' => $value['PK_TARJET_CODIGO'], //codigo tarjeta
                                        'codigo_th' => $value['CODTH'], //codigo tarjetahabiente
                                        'producto_codigo' => $value['CODPROD'], //codigo producto
                                        'monto_abono' => $value['MONTO'], //Monto abono
                                        'pk_asotar_codigo' => $value['PKTAR'], //pk_asotar_codigo
                                        'pk_concepto_abono' => $value['CONCEPTO'], //pk_concepto_abono 
                                        'valor_comision' => $value['COMISION'], //valor comision 
                                        'porc_comision' => $value['PORCCOMISION'] //porcentaje comision
                            ];

                            array_push($abonodata, (array) $obj);
                        }

                        $_SESSION['datatarjabono'] = $abonodata;
                        $confirmarre = 200;
                        $correoen = 'resgistrado';
                        if ($this->mask_email($correodestino)) {
                            $correoen = $this->mask_email($correodestino);
                        }
//                    $this->session->set_userdata(array('CORREO_DES_ABONO' => $correoen, 'PAR_MOVLLAVERO_CODIGO_ABONO' => $parmovllaverocod, 'PK_COD_LLAVERO' => $pk_llavero_codigo));
                        $_SESSION['CORREO_DES_ABONO'] = $correoen;
                        $_SESSION['PAR_MOVLLAVERO_CODIGO_ABONO'] = $parmovllaverocod;
                        $_SESSION['PK_COD_LLAVERO'] = $pk_llavero_codigo;
                        redirect("/portal/llaveMaestra/abonoMasivo?abonoOK&cre=$pk_llavero_codigo");
//                   
                    }
                }//fin procedimiento valida cobro impuestos
                else {
                    redirect("/portal/llaveMaestra/abonoMasivo?errorsaldoinsu");
                }
            }
        }
    }
    */
    public function estado($pantalla = 0) {
        $this->verificarllaveMestraEstado();
        //$empresa = $this->session->userdata("entidad");
        $empresa = $_SESSION['entidad'];
        $pk_ent_codigo = $empresa['PK_ENT_CODIGO'];
        //$rol = $this->session->userdata("rol");
        $rol = $_SESSION['rol'];
        if (($rol == 61))  {
            $sql = "BEGIN MODLLAVEMAESTRA.LLAVMAEPKGGENERAL.prcretornamovllavemaestra(
                parentcodigo =>:parentcodigo,
                parnumrows=>:parnumrows,
                movimientos =>:movimientos,
                parrespuesta=>:parrespuesta);
                END;";
            $conn = $this->db->conn_id;
            $stmt = oci_parse($conn, $sql);
            $curs = oci_new_cursor($conn);
            oci_bind_by_name($stmt, ':parentcodigo', $pk_ent_codigo, 32);
            $numrows = 5; //configurar cantidad filas a retornar
            oci_bind_by_name($stmt, ':parnumrows', $numrows, 32);
            oci_bind_by_name($stmt, ":movimientos", $curs, -1, OCI_B_CURSOR);
            oci_bind_by_name($stmt, ':parrespuesta', $parrespuesta, 32);
            if (!oci_execute($stmt)) {
                $e = oci_error($stmt);
                VAR_DUMP($e);
                exit;
            }
            $movllavemaestra = array();
            if ($parrespuesta == 1) {
                oci_execute($curs);  // Ejecutar el REF CURSOR como un ide de sentencia normal
                while (($row = oci_fetch_array($curs, OCI_ASSOC + OCI_RETURN_NULLS)) != false) {

                    array_push($movllavemaestra, array(
                        'FECHA' => $row['FECHA'], 'NOMBRE_MOV' => $row['NOMBRE'], 'MONTO_MOV' => $row['MONTO_MOV'], 'SALDO_ANT_MOV' => $row['SALDO_ANT_MOV']
                    ));
                }
                $data['movllavemaestra'] = $movllavemaestra;
            }
        }

        $pk_tipomovabono = 4; //pk tipo movimiento abonos 
        $pk_tipomovreverso = 5; //pk tipo movimiento reverso 5 
        $pk_tipomovdevolucion = 6; //pk tipo movimiento devolucion saldo llavero a llave maestr6 5 

        if ($rol == 61) {
            $data['totalabonos'] = $this->returntotalpktipomovllavero($pk_tipomovabono, '', '');
            $data['totalreversos'] = $this->returntotalpktipomovllavero($pk_tipomovreverso, '', '');
            $data['totaldevoluciones'] = $this->returntotalpktipomovllavero($pk_tipomovdevolucion, '', '');
            $data['saldo'] = $this->saldollavemaestra();
        } elseif (($rol == 60)) {
            $data['totalabonos'] = 0;
            $data['totalreversos'] = 0;
            $data['totaldevoluciones'] = 0;
        }


        $llaveros = $this->returnarrayllaveros();
        $data['llaveros'] = $llaveros;

        $data['empresa'] = $empresa['NOMBREEMPRESA'];
        //$usuario = $this->session->userdata("usuario");
        $usuario = $_SESSION['usuario'];
        $data['usuario'] = $usuario['NOMBRE'] . " " . $usuario['APELLIDO'];
        //$ultimaconexion = $this->session->userdata("ultimaconexion");
        $ultimaconexion = $_SESSION['ultimaconexion'];
        $data['ultimaconexion'] = $ultimaconexion;
        $data['llaveMaestra'] = 1;
        $data['menu'] = "estado";
        $this->load->view('portal/templates/header2llave', $data);
        $this->load->view('portal/llave/estadoLLaveMaestra', $data);
        $this->load->view('portal/templates/footer', $data);
    }

    public function estadoTarjetas($pantalla = 0) {
        $this->verificarllaveMestraNuevosPerfiles();
        //$empresa = $this->session->userdata("entidad");
        $empresa = $_SESSION['entidad'];
        //$usuario = $this->session->userdata("usuario");
        $usuario = $_SESSION['usuario'];
        $pk_entidad_coor = $usuario['PK_ENT_CODIGO'];
        //$rol = $this->session->userdata("rol");
        $rol = $_SESSION['rol'];
        if ($rol == 61) {
            $tarjetasEntidad = $this->db->query("select vista.nomtar,
vista.abr,
vista.doc, 
vista.NOMPRO, 
vista.CODPROD,
vista.codth,
vista.NUMTAR,
vista.PK_TARJET_CODIGO,
vista.pk_linpro_codigo,
vista.identificador, 
asot.asotar_codigo PKTAR,
llavero.nombre_llavero 
,vista.saldo
from (SELECT ent.documento DOC,
ent.pk_ent_codigo CODTH,
tipdoc.abreviacion abr, 
NVL(TO_CHAR(tar.fecha_creacion,'DD/MM/YYYY'),'PENDIENTE') FEC,
ent.nombre ||' '||ent.apellido nomtar,
TAR.NUMERO NUMTAR,TAR.PK_TARJET_CODIGO,
PRO.NOMBRE_PRODUCTO NOMPRO,
pro.pk_produc_codigo codprod,
pro.pk_linpro_codigo,
tar.pk_tarjet_codigo codtar,
NVL(tar.identificador, '-') IDENTIFICADOR
,case when pro.limitacion!=1 then saldos.saldo else 0 end saldo
FROM MODTARHAB.tartbltarjet tar 
join MODTARHAB.TARTBLCUENTA CUE  ON cue.pk_tartblcuenta_codigo = tar.pk_tartblcuenta_codigo 
AND cue.PK_ENT_CODIGO_EMP ={$empresa['PK_ENT_CODIGO']}
JOIN MODCLIUNI.CLITBLENTIDA ENT ON ent.pk_ent_codigo = cue.pk_ent_codigo_th 
JOIN MODCLIUNI.CLITBLTIPDOC TIPDOC 
ON tipdoc.pk_td_codigo = ent.clitbltipdoc_pk_td_codigo 
JOIN MODPRODUC.PROTBLPRODUC PRO ON pro.pk_produc_codigo = cue.pk_produc_codigo and pro.pk_linpro_codigo in (1,2,3)
JOIN MODALISTA.ALITBLDETPED DETPED ON detped.pk_detped_codigo = tar.pk_detped_codigo 
 JOIN MODALISTA.ALITBLPEDIDO PED ON ped.pk_pedido_codigo = detped.pk_pedido 
 JOIN MODCLIUNI.CLITBLENTIDA ENTCUS ON entcus.pk_ent_codigo = ped.pk_custodio
 JOIN MODCLIUNI.CLITBLCAMPAN CAM ON cam.pk_campan_codigo = ped.pk_campan_codigo 
JOIN MODTARHAB.tartblesttar ESTTAR ON esttar.pk_esttar_codigo = tar.pk_esttar_codigo 
JOIN modtarhab.view_listath saldos ON saldos.numero_documento = tar.id_empresa
AND saldos.pan_enmascarado = tar.numero
order BY tar.fecha_creacion asc) vista 
left join MODLLAVEMAESTRA.llavetblasotar asot on vista.codprod = asot.pk_produc_codigo
join MODLLAVEMAESTRA.llavetblllavero llavero on asot.pk_llavero_codigo = llavero.llavero_codigo
and vista.codth = asot.pk_ent_codigo and vista.codtar = asot.pk_tarjeta_codigo
where asot.pk_ent_codigo IS NOT NULL
and asot.pk_produc_codigo IS NOT NULL and vista.numtar IS NOT NULL
order by vista.NOMTAR asc");
            $data['tarjetaEntidad'] = $tarjetasEntidad->result_array;
        } elseif ($rol == 60) {
            $tarjetascoordinador = $this->db->query("
SELECT
    vista.nomtar,
    vista.abr,
    vista.doc,
    vista.nompro,
    vista.codprod,
    vista.codth,
    vista.numtar,
    vista.pk_tarjet_codigo,
    vista.pk_linpro_codigo,
    vista.identificador,
    asot.asotar_codigo pktar,
    llavero.nombre_llavero,
    vista.saldo
FROM
    (
        SELECT
            ent.documento doc,
            ent.pk_ent_codigo codth,
            tipdoc.abreviacion abr,
            nvl(to_char(tar.fecha_creacion, 'DD/MM/YYYY'), 'PENDIENTE') fec,
            ent.nombre || ' ' || ent.apellido nomtar,
            tar.numero numtar,
            tar.pk_tarjet_codigo,
            pro.nombre_producto nompro,
            pro.pk_produc_codigo codprod,
            pro.pk_linpro_codigo,
            tar.pk_tarjet_codigo codtar,
            nvl(tar.identificador, '-') identificador,
            case when pro.limitacion=2 then saldos.saldo else 0 end saldo
        FROM
             modtarhab.tartbltarjet tar
            JOIN modtarhab.tartblcuenta    cue ON cue.pk_tartblcuenta_codigo = tar.pk_tartblcuenta_codigo
            AND cue.pk_ent_codigo_emp =  {$empresa['PK_ENT_CODIGO']}
            JOIN modcliuni.clitblentida    ent ON ent.pk_ent_codigo = cue.pk_ent_codigo_th
            JOIN modcliuni.clitbltipdoc    tipdoc ON tipdoc.pk_td_codigo = ent.clitbltipdoc_pk_td_codigo
            JOIN modproduc.protblproduc    pro ON pro.pk_produc_codigo = cue.pk_produc_codigo
            AND pro.pk_linpro_codigo IN (1,2,3)
            JOIN modalista.alitbldetped    detped ON detped.pk_detped_codigo = tar.pk_detped_codigo
            JOIN modalista.alitblpedido    ped ON ped.pk_pedido_codigo = detped.pk_pedido
            JOIN modcliuni.clitblentida    entcus ON entcus.pk_ent_codigo = ped.pk_custodio
            JOIN modcliuni.clitblcampan    cam ON cam.pk_campan_codigo = ped.pk_campan_codigo
            JOIN modtarhab.tartblesttar    esttar ON esttar.pk_esttar_codigo = tar.pk_esttar_codigo
            JOIN modtarhab.view_listath    saldos ON saldos.numero_documento = tar.id_empresa
            AND saldos.pan_enmascarado = tar.numero
        ORDER BY tar.fecha_creacion ASC    ) vista
      LEFT JOIN modllavemaestra.llavetblasotar     asot ON vista.codprod = asot.pk_produc_codigo
     JOIN modllavemaestra.llavetblllavero    llavero ON asot.pk_llavero_codigo = llavero.llavero_codigo
     AND vista.codth = asot.pk_ent_codigo
     AND vista.codtar = asot.pk_tarjeta_codigo
WHERE  asot.pk_ent_codigo IS NOT NULL
    AND asot.pk_produc_codigo IS NOT NULL
    AND vista.numtar IS NOT NULL
and llavero.pk_ent_codigo_coor= {$pk_entidad_coor}
ORDER BY
    vista.nomtar ASC");
            $data['tarjetaEntidad'] = $tarjetascoordinador->result_array;
        }

        $data['saldo'] = $this->saldollavemaestra();
        $data['empresa'] = $empresa['NOMBREEMPRESA'];

        $data['usuario'] = $usuario['NOMBRE'] . " " . $usuario['APELLIDO'];
        //$ultimaconexion = $this->session->userdata("ultimaconexion");
        $ultimaconexion = $_SESSION['ultimaconexion'];
        $data['ultimaconexion'] = $ultimaconexion;
        $data['llaveMaestra'] = 1;
        $data['menu'] = "estado";
        $this->load->view('portal/templates/header2llave', $data);
        $this->load->view('portal/llave/estadoTarjetas', $data);
        $this->load->view('portal/templates/footer', $data);
    }

    public function estadocuentadetalletarjeta($id = 0, $pk_tarj_cod = 0) {
        $this->verificarllaveMestra();
        //$empresa = $this->session->userdata("entidad");
        $empresa = $_SESSION['entidad'];
        if ($id != '' && $pk_tarj_cod != '') {
            $tarjetasEntidad = $this->db->query("select vista.nomtar, vista.abr, vista.doc, vista.NOMPRO, vista.CODPROD, vista.codth, vista.NUMTAR,vista.id_empresa,vista.PK_TARJET_CODIGO,vista.pk_linpro_codigo, asot.asotar_codigo PKTAR from (SELECT ent.documento DOC,
                                            ent.pk_ent_codigo CODTH,
                                            tipdoc.abreviacion abr, 
                                            NVL(TO_CHAR(tar.fecha_creacion,'DD/MM/YYYY'),'PENDIENTE') FEC,
                                            ent.nombre ||' '||ent.apellido nomtar, TAR.NUMERO NUMTAR,TAR.PK_TARJET_CODIGO,
                                            PRO.NOMBRE_PRODUCTO NOMPRO,
                                            pro.pk_produc_codigo codprod,
                                            pro.pk_linpro_codigo,
                                            tar.pk_tarjet_codigo codtar,
                                            tar.id_empresa
                                            FROM MODTARHAB.tartbltarjet tar 
                                            join MODTARHAB.TARTBLCUENTA CUE 
                                            ON cue.pk_tartblcuenta_codigo = tar.pk_tartblcuenta_codigo 
                                            AND cue.PK_ENT_CODIGO_EMP = {$empresa['PK_ENT_CODIGO']}
                                            JOIN MODCLIUNI.CLITBLENTIDA ENT 
                                            ON ent.pk_ent_codigo = cue.pk_ent_codigo_th 
                                            JOIN MODCLIUNI.CLITBLTIPDOC TIPDOC 
                                            ON tipdoc.pk_td_codigo = ent.clitbltipdoc_pk_td_codigo 
                                            JOIN MODPRODUC.PROTBLPRODUC PRO 
                                            ON pro.pk_produc_codigo = cue.pk_produc_codigo and pro.pk_linpro_codigo in (1,2,3)
                                            JOIN MODALISTA.ALITBLDETPED DETPED 
                                            ON detped.pk_detped_codigo = tar.pk_detped_codigo 
                                            JOIN MODALISTA.ALITBLPEDIDO PED ON ped.pk_pedido_codigo = detped.pk_pedido 
                                            JOIN MODCLIUNI.CLITBLENTIDA ENTCUS ON entcus.pk_ent_codigo = ped.pk_custodio
                                            JOIN MODCLIUNI.CLITBLCAMPAN CAM ON cam.pk_campan_codigo = ped.pk_campan_codigo 
                                            LEFT JOIN MODPROPAG.PPATBLDETORD DETORD ON detord.pk_pedido = detped.pk_detped_Codigo
                                            left JOIN MODFACTUR.FACTBLFACORD FACORD ON facord.pk_ordcom_codigo=detord.pk_orden_compra
                                            left JOIN MODPROPAG.PPATBLORDCOM ORDCOM ON facord.pk_ordcom_codigo=ordcom.pk_ordcom_codigo
                                            LEFT JOIN MODFACTUR.FACTBLFACTUR factur ON facord.pk_factur_codigo=factur.pk_factur_codigo 
                                            JOIN MODTARHAB.tartblesttar ESTTAR 
                                            ON esttar.pk_esttar_codigo = tar.pk_esttar_codigo            
                                            order BY tar.fecha_creacion asc) vista 
                                            left join MODLLAVEMAESTRA.llavetblasotar asot on vista.codprod = asot.pk_produc_codigo
                                            and vista.codth = asot.pk_ent_codigo and vista.codtar = asot.pk_tarjeta_codigo where asot.pk_ent_codigo IS NOT NULL
                                            and asot.pk_produc_codigo IS NOT NULL and vista.numtar IS NOT NULL
                                            order by vista.NOMTAR asc");

            foreach ($tarjetasEntidad->result_array as $value) {
                if ($value['DOC'] == $id && $value['PK_TARJET_CODIGO'] == $pk_tarj_cod) {
                    $numtarjeta = $value['NUMTAR'];
                    $id_empresa = $value['ID_EMPRESA'];
                }
            }

//            var_dump($id_empresa);
//            var_dump($numtarjeta);
//         administradores ligados a la empresa  
            $sqlmovtarjeta = $this->db->query("select vmovtar.fecha_transaccion,vmovtar.nombre_comercio,vmovtar.tipo_movimiento,vmovtar.id_tipo_movimiento,vmovtar.monto from  MODTARHAB.view_movtarj vmovtar
                                        JOIN MODTARHAB.TARTBLTARJET tarjeta 
                                        ON tarjeta.ID_EMPRESA=vmovtar.NUMERO_DOCUMENTO
                                        AND  vmovtar.pan_enmascarado='$numtarjeta'
                                        AND tarjeta.ID_EMPRESA=$id_empresa");
            $movtarjeta = $sqlmovtarjeta->result_array;
            $data['movtarjeta'] = $movtarjeta;

            $sqlsaldodisponible = $this->db->query("SELECT
                                    tarjeta.pk_tarjet_codigo   codigotarjeta,
                                    tarjetas_zeus.empresa,
                                    tarjetas_zeus.fk_tipo_documento_id,
                                    tarjetas_zeus.id_tarjeta_zeus,
                                    tarjetas_zeus.numero_documento,
                                    tarjetas_zeus.pan_enmascarado,
                                    tarjetas_zeus.saldo,
                                    cuenta.pk_produc_codigo    codigoproducto,
                                    tarjetas_zeus.producto,
                                    tarjetas_zeus.fk_estado_id,
                                    CASE
                                        WHEN fk_estado_id = 0
                                             AND motivo_bloqueo != 'BLOQUEO PREVENTIVO' THEN
                                            'BLOQUEADA'
                                        WHEN fk_estado_id = 1   THEN
                                            'ACTIVA'
                                        WHEN fk_estado_id = 4   THEN
                                            'PENDIENTE ACTIVACION'
                                        WHEN fk_estado_id = 0
                                             AND motivo_bloqueo = 'BLOQUEO PREVENTIVO' THEN
                                            'APAGADA'
                                        ELSE
                                            'DESCONOCIDO'
                                    END ESTADO,
                                    tarjetas_zeus.motivo_bloqueo
                                    FROM
                                    modcliuni.clitblentida   entida
                                    JOIN modtarhab.tartblcuenta   cuenta ON cuenta.pk_ent_codigo_th = entida.pk_ent_codigo
                                    JOIN modcliuni.clitbltipdoc   tipdoc ON entida.clitbltipdoc_pk_td_codigo = tipdoc.pk_td_codigo
                                    JOIN modtarhab.tartbltarjet   tarjeta ON cuenta.pk_tartblcuenta_codigo = tarjeta.pk_tartblcuenta_codigo
                                    AND tarjeta.pk_esttar_codigo NOT IN (15,16,17)
                                    JOIN modtarhab.view_listath   tarjetas_zeus 
                                    ON tarjetas_zeus.fk_tipo_documento_id =  CASE
                                        WHEN ( entida.clitbltipdoc_pk_td_codigo = 68 ) THEN
                                             0--CEDULA
                                        WHEN ( entida.clitbltipdoc_pk_td_codigo = 67 ) THEN
                                           2
                                        WHEN ( entida.clitbltipdoc_pk_td_codigo = 69 ) THEN
                                             1
                                        WHEN ( entida.clitbltipdoc_pk_td_codigo = 70 ) THEN
                                            3
                                        WHEN ( entida.clitbltipdoc_pk_td_codigo = 72 OR entida.clitbltipdoc_pk_td_codigo = 73 ) THEN
                                             6
                                             END 
                                             AND tarjetas_zeus.numero_documento = tarjeta.id_empresa
                                            AND tarjetas_zeus.pan_enmascarado = tarjeta.numero
                                    WHERE  tarjeta.pk_tarjet_codigo = {$pk_tarj_cod}");
            $saldotarjeta = $sqlsaldodisponible->result_array[0];
            $data['saldotarjeta'] = $saldotarjeta['SALDO'];
            $data['producto'] = $saldotarjeta['PRODUCTO'];
            $data['numtar'] = $saldotarjeta['PAN_ENMASCARADO'];
        }


        $data['saldo'] = $this->saldollavemaestra();
        //$empresa = $this->session->userdata("entidad");
        $empresa = $_SESSION['entidad'];
        $data['empresa'] = $empresa['NOMBREEMPRESA'];
        //$usuario = $this->session->userdata("usuario");
        $usuario = $_SESSION['usuario'];
        $data['usuario'] = $usuario['NOMBRE'] . " " . $usuario['APELLIDO'];
        //$ultimaconexion = $this->session->userdata("ultimaconexion");
        $ultimaconexion = $_SESSION['ultimaconexion'];
        $data['ultimaconexion'] = $ultimaconexion;
        $data['llaveMaestra'] = 1;
        $data['menu'] = "estado";
        $this->load->view('portal/templates/header2llave', $data);
        $this->load->view('portal/llave/estadocuenta', $data);
        $this->load->view('portal/templates/footer', $data);
    }

    public function legalizacionAbono($pk_abono = 0, $pk_tarj_cod = 0, $montoAbono, $pk_llavero = null) {
        $this->verificarllaveMestra();
        if (!empty($pk_abono) && !empty($pk_tarj_cod)) {
            $sqlsaldodisponible = $this->db->query("SELECT
                                    tarjeta.pk_tarjet_codigo   codigotarjeta,
                                    tarjetas_zeus.empresa,
                                    tarjetas_zeus.id_tarjeta_zeus,
                                    tarjetas_zeus.numero_documento,
                                    tarjetas_zeus.pan_enmascarado,
                                    tarjetas_zeus.saldo,
                                    cuenta.pk_produc_codigo    codigoproducto,
                                    tarjetas_zeus.producto,
                                    tarjetas_zeus.fk_estado_id,
                                    pro.pk_linpro_codigo ,
                                    CASE
                                        WHEN fk_estado_id = 0
                                             AND motivo_bloqueo != 'BLOQUEO PREVENTIVO' THEN
                                            'BLOQUEADA'
                                        WHEN fk_estado_id = 1   THEN
                                            'ACTIVA'
                                        WHEN fk_estado_id = 4   THEN
                                            'PENDIENTE ACTIVACION'
                                        WHEN fk_estado_id = 0
                                             AND motivo_bloqueo = 'BLOQUEO PREVENTIVO' THEN
                                            'APAGADA'
                                        ELSE
                                            'DESCONOCIDO'
                                    END ESTADO
                                    FROM
                                    modcliuni.clitblentida   entida
                                    JOIN modtarhab.tartblcuenta   cuenta ON cuenta.pk_ent_codigo_th = entida.pk_ent_codigo
                                    JOIN modcliuni.clitbltipdoc   tipdoc ON entida.clitbltipdoc_pk_td_codigo = tipdoc.pk_td_codigo
                                    JOIN modtarhab.tartbltarjet   tarjeta ON cuenta.pk_tartblcuenta_codigo = tarjeta.pk_tartblcuenta_codigo
                                    AND tarjeta.pk_esttar_codigo NOT IN (15,16,17)
                                    JOIN modtarhab.view_listath   tarjetas_zeus 
                                    ON  tarjetas_zeus.numero_documento = tarjeta.id_empresa
                                            AND tarjetas_zeus.pan_enmascarado = tarjeta.numero
                                    JOIN modproduc.protblproduc pro on pro.pk_produc_codigo = cuenta.pk_produc_codigo
                                    WHERE  tarjeta.pk_tarjet_codigo = {$pk_tarj_cod}");
            $saldotarjeta = $sqlsaldodisponible->result_array[0];
            if (!empty($pk_llavero)) {
                $sqlLlavero = $this->db->query("select nombre_llavero from modllavemaestra.llavetblllavero 
                                        where llavero_codigo = $pk_llavero");
                $nomLlavero = $sqlLlavero->result_array[0]['NOMBRE_LLAVERO'];
                $data['nombre_llavero'] = $nomLlavero;
                $data['pk_llavero'] = $pk_llavero;
            }

            $data['pk_abono'] = $pk_abono;
            $data['montoabono'] = $montoAbono;

            $data['saldotarjeta'] = $saldotarjeta['SALDO'];
            $data['producto'] = $saldotarjeta['PRODUCTO'];
            $data['numtar'] = $saldotarjeta['PAN_ENMASCARADO'];
            $data['pk_linpro_codigo'] = $saldotarjeta['PK_LINPRO_CODIGO'];

            $sqllegalizacionabono = $this->db->query("select tbllegal.pk_legalizacion_codigo,tbllegal.fecha_creacion,tbllegal.monto_legalizado,tbllegal.monto_pen_legalizar,tbllegal.url_soporte,estleg.nombre estado,estleg.estlegali_codigo pk_lega 
                        from modllavemaestra.llavetbllegalizaciongastos tbllegal
                        JOIN modllavemaestra.llavetblestlegali estleg ON tbllegal.pk_estado_legalizacion = estleg.estlegali_codigo
                        where tbllegal.pk_abotar_codigo={$pk_abono}");

            $legalizacionesabono = $sqllegalizacionabono->result_array;
            $data['legalizacionesabono'] = $legalizacionesabono;
            $urlhost = $_SERVER["SERVER_ADDR"];
            $data['urlhost'] = $urlhost;
        }
        $post = $this->input->post();
        if ($post) {
            if (!empty($post['codigos'])) {
                $varcount = 0;
                foreach ($post['codigos'] as $key => $value) {
                    $dataUserTh = explode(",", $value);
                    $pk_legalizacion = $dataUserTh[0];
                    $pk_codigo_abono = $dataUserTh[1];

                    $sql = "BEGIN MODLLAVEMAESTRA.LLAVMAEPKGGENERAL.prccambioestadolegalizacion(
                    parpklegalizacion=>:parpklegalizacion,
                    parpkabonocodigo=>:parpkabonocodigo,
                    parpkestadolega=>:parpkestadolega,
                    parmensajerespuesta=>:parmensajerespuesta,
                    parrespuesta=>:parrespuesta);
                    END;";

                    $conn = $this->db->conn_id;
                    $stmt = oci_parse($conn, $sql);
                    oci_bind_by_name($stmt, ':parpklegalizacion', $pk_legalizacion, 32);
                    oci_bind_by_name($stmt, ':parpkabonocodigo', $pk_codigo_abono, 32);
                    $pk_estado_legali = 1; //aprobado
                    oci_bind_by_name($stmt, ':parpkestadolega', $pk_estado_legali, 32);
                    oci_bind_by_name($stmt, ':parmensajerespuesta', $parmensajerespuesta, 250);
                    oci_bind_by_name($stmt, ':parrespuesta', $parrespuesta, 32);
                    if (!oci_execute($stmt)) {
                        $e = oci_error($stmt);
                        VAR_DUMP($e);
                        exit;
                    }
                    if ($parrespuesta == 1) {
                        $varcount++;
                    }
                }
                if ($varcount == count($post['codigos'])) {
                    redirect("/portal/llaveMaestra/informeAbonos?aprobOk");
                } else {
                    $data['excedeAbono'] = 1;
                }
            } else {
                redirect("/portal/llaveMaestra/legalizacionAbono?errordata");
            }
        }

        $data['saldo'] = $this->saldollavemaestra();
        //$empresa = $this->session->userdata("entidad");
        $empresa = $_SESSION['entidad'];
        $data['empresa'] = $empresa['NOMBREEMPRESA'];
        //$usuario = $this->session->userdata("usuario");
        $usuario = $_SESSION['usuario'];
        $data['usuario'] = $usuario['NOMBRE'] . " " . $usuario['APELLIDO'];
        //$ultimaconexion = $this->session->userdata("ultimaconexion");
        $ultimaconexion = $_SESSION['ultimaconexion'];
        $data['ultimaconexion'] = $ultimaconexion;
        $data['llaveMaestra'] = 1;
        $data['menu'] = "estado";
        $this->load->view('portal/templates/header2llave', $data);
        $this->load->view('portal/llave/legalizacionAbono', $data);
        $this->load->view('portal/templates/footer', $data);
    }

    public function informeAbonos($pantalla = 0) {
        $this->verificarllaveMestraNuevosPerfiles();
//        if (($this->session->userdata("rol") == 45 || $this->session->userdata("rol") == 47) && $this->session->userdata("CODIGO_PRODUCTO") == 70) {
//            
//        } else {
//            redirect("/portal/principal/pantalla");
//        }


        $data['saldo'] = $this->saldollavemaestra();
        $llaveros = $this->returnarrayllaveros();
        $data['llaveros'] = $llaveros;
        //$empresa = $this->session->userdata("entidad");
        $empresa = $_SESSION['entidad'];
        $data['empresa'] = $empresa['NOMBREEMPRESA'];
        //$usuario = $this->session->userdata("usuario");
        $usuario = $_SESSION['usuario'];
        $data['usuario'] = $usuario['NOMBRE'] . " " . $usuario['APELLIDO'];
        //$ultimaconexion = $this->session->userdata("ultimaconexion");
        $ultimaconexion = $_SESSION['ultimaconexion'];
        $data['ultimaconexion'] = $ultimaconexion;
        $data['llaveMaestra'] = 1;
        $data['menu'] = "estado";
        $this->load->view('portal/templates/header2llave', $data);
        $this->load->view('portal/llave/informeAbonos', $data);
        $this->load->view('portal/templates/footer', $data);
    }

    public function informeGrafico($pantalla = 0) {
        $rol=$_SESSION['rol'];
        //if (($this->session->userdata("rol") == 45 || $this->session->userdata("rol") == 47) && $this->session->userdata("CODIGO_PRODUCTO") == 70) {
        if (($rol == 60 || $rol == 61) 
                && $_SESSION['PRODUCTOLLAVE']['CODIGO_PRODUCTO'] == 70) {     
        } else {
            redirect("/portal/principal/pantalla");
        }
        $var_cod_gastos_representacion = 305;
        $var_cod_caja_menor = 306;
        $var_bussines_car = 307;
        $var_medios_transporte = 301;
        $var_gastos_viaje = 308;

        //$empresa = $this->session->userdata("entidad");
        $empresa = $_SESSION['entidad'];
        $sqlcantabonos = $this->db->query("SELECT COUNT(vista.fecha_creacion) abonos FROM (select asotar.fecha_creacion from MODLLAVEMAESTRA.llavetblabotar abotar 
                JOIN MODLLAVEMAESTRA.llavetblasotar asotar
                ON asotar.asotar_codigo= abotar.pk_asotar_codigo
                JOIN MODLLAVEMAESTRA.llavetblmovllavero movllavero
                ON movllavero.movllavero_codigo= abotar.pk_movllavero_codigo
                JOIN MODLLAVEMAESTRA.LLAVETBLLLAVERO llavero
                ON llavero.llavero_codigo=movllavero.fk_llavero_codigo
                JOIN MODLLAVEMAESTRA.LLAVETBLLLAVMAE LLAVEMAE
                ON llavemae.llavmae_codigo=llavero.llavemaestra_codigo
                JOIN MODTARHAB.tartbltarjet tar 
                ON tar.pk_tarjet_codigo = asotar.pk_tarjeta_codigo
                join MODTARHAB.TARTBLCUENTA CUE 
                ON cue.pk_tartblcuenta_codigo = tar.pk_tartblcuenta_codigo 
                JOIN MODPRODUC.PROTBLPRODUC PRO 
                ON pro.pk_produc_codigo = cue.pk_produc_codigo and pro.pk_linpro_codigo in (2)
                where movllavero.tipmovllavero_codigo=4 and
                movllavero.estmov_codigo=1 and
                llavemae.pk_ent_codigo={$empresa['PK_ENT_CODIGO']})vista
                WHERE vista.fecha_creacion BETWEEN  trunc(sysdate, 'MM') AND current_date");
        $cantabonos = $sqlcantabonos->result_array;
        $canttotalabonos = $cantabonos[0]['ABONOS'];
        $data['canttotalabonos'] = $canttotalabonos;

        $cant_abonos_gastrepre = $this->returncantabonopkcodprodcuto($var_cod_gastos_representacion);
        $cant_abonos_cajamenor = $this->returncantabonopkcodprodcuto($var_cod_caja_menor);
        $cant_abonos_bussinescar = $this->returncantabonopkcodprodcuto($var_bussines_car);
        $cant_abonos_mediotrans = $this->returncantabonopkcodprodcuto($var_medios_transporte);
        $cant_abonos_gastosviaje = $this->returncantabonopkcodprodcuto($var_gastos_viaje);
        $data['cantabono_gastosrepre'] = $cant_abonos_gastrepre;
        $data['cantabono_cajamenor'] = $cant_abonos_cajamenor;
        $data['cantabono_bussinescar'] = $cant_abonos_bussinescar;
        $data['cantabono_mediostrans'] = $cant_abonos_mediotrans;
        $data['cantabono_gastosviaje'] = $cant_abonos_gastosviaje;

        $sqlcantreversos = $this->db->query("SELECT COUNT(vista.fecha_creacion) reversos FROM (select asotar.fecha_creacion from MODLLAVEMAESTRA.llavetblrevtar revtar 
                JOIN MODLLAVEMAESTRA.llavetblasotar asotar
                ON asotar.asotar_codigo= revtar.pk_asotar_codigo
                JOIN MODLLAVEMAESTRA.llavetblmovllavero movllavero
                ON movllavero.movllavero_codigo= revtar.pk_movllavero_codigo
                JOIN MODLLAVEMAESTRA.LLAVETBLLLAVERO llavero
                ON llavero.llavero_codigo=movllavero.fk_llavero_codigo
                JOIN MODLLAVEMAESTRA.LLAVETBLLLAVMAE LLAVEMAE
                ON llavemae.llavmae_codigo=llavero.llavemaestra_codigo
                JOIN MODTARHAB.tartbltarjet tar 
                ON tar.pk_tarjet_codigo = asotar.pk_tarjeta_codigo
                join MODTARHAB.TARTBLCUENTA CUE 
                ON cue.pk_tartblcuenta_codigo = tar.pk_tartblcuenta_codigo 
                JOIN MODPRODUC.PROTBLPRODUC PRO 
                ON pro.pk_produc_codigo = cue.pk_produc_codigo and pro.pk_linpro_codigo in (2)
                where movllavero.tipmovllavero_codigo=5 and
                movllavero.estmov_codigo=1 and
                llavemae.pk_ent_codigo={$empresa['PK_ENT_CODIGO']})vista
                WHERE vista.fecha_creacion BETWEEN  trunc(sysdate, 'MM') AND current_date");
        $canreversos = $sqlcantreversos->result_array;
        $canttotalrev = $canreversos[0]['REVERSOS'];
        $data['cantreversos'] = $canreversos[0]['REVERSOS'];
//        $totalgrafico=$canttotalabonos+$cant_abonos_gastrepre+$cant_abonos_cajamenor+$cant_abonos_bussinescar+$cant_abonos_bussinescar+$cant_abonos_mediotrans+$cant_abonos_gastosviaje+$canttotalrev;

        $data['saldo'] = $this->saldollavemaestra();
        $data['empresa'] = $empresa['NOMBREEMPRESA'];
        //$usuario = $this->session->userdata("usuario");
        $usuario = $_SESSION['usuario'];
        $data['usuario'] = $usuario['NOMBRE'] . " " . $usuario['APELLIDO'];
        //$ultimaconexion = $this->session->userdata("ultimaconexion");
        $ultimaconexion = $_SESSION['ultimaconexion'];
        $data['ultimaconexion'] = $ultimaconexion;
        $data['llaveMaestra'] = 1;
        $data['menu'] = "estado";
        $this->load->view('portal/templates/header2llave', $data);
        $this->load->view('portal/llave/informeGrafico', $data);
        $this->load->view('portal/templates/footer', $data);
    }

    public function estadocuenta($pantalla = 0) {
        $this->verificarllaveMestra();
        $rol=$_SESSION['rol'];
        if (($rol == 45 || $rol == 47) && $_SESSION['PRODUCTOLLAVE']['CODIGO_PRODUCTO'] == 70) {
        //if (($this->session->userdata("rol") == 45 || $this->session->userdata("rol") == 47) && $this->session->userdata("CODIGO_PRODUCTO") == 70) {
            
        } else {
            redirect("/portal/principal/pantalla");
        }
        //$empresa = $this->session->userdata("entidad");
        $empresa = $_SESSION['entidad'];
        $data['empresa'] = $empresa['NOMBREEMPRESA'];
        //$usuario = $this->session->userdata("usuario");
        $usuario = $_SESSION['usuario'];
        $data['usuario'] = $usuario['NOMBRE'] . " " . $usuario['APELLIDO'];
        //$ultimaconexion = $this->session->userdata("ultimaconexion");
        $ultimaconexion = $_SESSION['ultimaconexion'];
        $data['ultimaconexion'] = $ultimaconexion;
        $data['llaveMaestra'] = 1;
        $data['menu'] = "estado";
        $this->load->view('portal/templates/header2llave', $data);
        $this->load->view('portal/llave/estadocuenta', $data);
        $this->load->view('portal/templates/footer', $data);
    }

//Devuelve saldo de las tarjetas asociados hacia los llaveros asociados a llave maestra
    public function reverso($pantalla = 0) {
//        var_dump($this->session->userdata);
        $this->verificarllaveMestraNuevosPerfiles();
        $this->verificarllaveMestraAdmin();
        //$empresa = $this->session->userdata("entidad");
        $empresa = $_SESSION['entidad'];
        $post = $this->input->post();
        if (!$post) {

            $data['empresa'] = $empresa['NOMBREEMPRESA'];
            //$usuario = $this->session->userdata("usuario");
            $usuario = $_SESSION['usuario'];
            $data['usuario'] = $usuario['NOMBRE'] . " " . $usuario['APELLIDO'];
            //$ultimaconexion = $this->session->userdata("ultimaconexion");
            $ultimaconexion = $_SESSION['ultimaconexion'];
            $data['ultimaconexion'] = $ultimaconexion;
            $llaveros = $this->returnarrayllaveros();
            $data['llaveros'] = $llaveros;
            $data['llaveMaestra'] = 1;
            $data['menu'] = "reverso";
            $data['saldo'] = $this->saldollavemaestra();
            $this->load->view('portal/templates/header2llave', $data);
            $this->load->view('portal/llave/reverso', $data);
            $this->load->view('portal/templates/footer', $data);
        } else {
            if (empty($post['pk_llavero_codigo'])) {
                redirect("/portal/llaveMaestra/reverso?errorpkllavero");
            }
            if (!empty($post['tarjetasreverso'])) {
                $pk_codigo_llavero = $post['pk_llavero_codigo'];
                $llaveros = $this->returnarrayllaveros();
                foreach ($llaveros as $value) {
                    if ($value['PK_LLAVERO_CODIGO'] == $pk_codigo_llavero) {
                        $nombrellaveroselect = $value['NOMBRE_LLAVERO'];
                    }
                }

                $lista = '';
// pk_llaveros para abonar
                foreach ($post['tarjetasreverso'] as $key => $value) {
                    $lista = $value . ',' . $lista;
                }
                if (is_null($lista)) {
                    $lista = '0';
                } else {
                    $lista = substr($lista, 0, -1);
                }
            } else {
                redirect("/portal/llaveMaestra/reverso?errordata");
            }

            $tarjetasEntidad = $this->db->query("
   SELECT
    vista.nomtar,
    vista.abr,
    vista.doc,
    vista.nompro,
    vista.codprod,
    vista.codth,
    vista.numtar,
    vista.id_empresa,
    vista.pk_tarjet_codigo,
    asot.asotar_codigo        pktar,
    llavero.nombre_llavero,
    llavero.llavero_codigo    pk_llavero,
    abotar.monto_abono,
    abotar.abotar_codigo,
    vista.ciudad,
    vista.nomcampana,
    vista.nomcustodio,
    vista.identificador,
    vista.saldo
FROM
    ( SELECT
      ent.documento doc,
      ent.pk_ent_codigo codth,
      ciud.nombre ciudad,
      cam.nombre nomcampana,
      tipdoc.abreviacion abr,
      nvl(to_char(tar.fecha_creacion, 'DD/MM/YYYY'), 'PENDIENTE') fec,
      ent.nombre  || ' ' || ent.apellido nomtar,
      tar.numero numtar,
      tar.pk_tarjet_codigo,
      pro.nombre_producto nompro,
      pro.pk_produc_codigo codprod,
      tar.pk_tarjet_codigo codtar,
      entcus.nombre || ' ' || entcus.apellido nomcustodio,
      tar.id_empresa,
      tar.identificador identificador,
      saldos.saldo
  FROM
      modtarhab.tartbltarjet tar
      JOIN modtarhab.tartblcuenta    cue ON cue.pk_tartblcuenta_codigo = tar.pk_tartblcuenta_codigo
                                         AND cue.pk_ent_codigo_emp = {$empresa['PK_ENT_CODIGO']}
      JOIN modcliuni.clitblentida    ent ON ent.pk_ent_codigo = cue.pk_ent_codigo_th
      JOIN modcliuni.clitblciudad    ciud ON ent.clitblciudad_pk_ciu_codigo = ciud.pk_ciu_codigo
      JOIN modcliuni.clitbltipdoc    tipdoc ON tipdoc.pk_td_codigo = ent.clitbltipdoc_pk_td_codigo
      JOIN modproduc.protblproduc    pro ON pro.pk_produc_codigo = cue.pk_produc_codigo
      AND pro.pk_linpro_codigo IN (2)
      JOIN modalista.alitbldetped    detped ON detped.pk_detped_codigo = tar.pk_detped_codigo
      JOIN modalista.alitblpedido    ped ON ped.pk_pedido_codigo = detped.pk_pedido
      JOIN modcliuni.clitblentida    entcus ON entcus.pk_ent_codigo = ped.pk_custodio
      JOIN modcliuni.clitblcampan    cam ON cam.pk_campan_codigo = ped.pk_campan_codigo
      JOIN modtarhab.tartblesttar esttar
       ON esttar.pk_esttar_codigo = tar.pk_esttar_codigo 
      JOIN modtarhab.view_listath    saldos ON saldos.numero_documento = tar.id_empresa
      AND saldos.pan_enmascarado = tar.numero
ORDER BY tar.fecha_creacion ASC) vista
LEFT JOIN modllavemaestra.llavetblasotar asot ON vista.codprod = asot.pk_produc_codigo
AND asot.fecha_desasociacion IS NULL
JOIN modllavemaestra.llavetblllavero llavero ON asot.pk_llavero_codigo = llavero.llavero_codigo
AND llavero.estado_codigo = 1
JOIN modllavemaestra.llavetblabotar abotar ON abotar.pk_asotar_codigo = asot.asotar_codigo
JOIN modllavemaestra.llavetblmovllavero movllavero ON abotar.pk_movllavero_codigo = movllavero.movllavero_codigo
JOIN modllavemaestra.llavetblconceptomov conceptomov ON abotar.pk_concepto_codigo = conceptomov.pk_concepto_codigo
AND vista.codth = asot.pk_ent_codigo
AND vista.codtar = asot.pk_tarjeta_codigo 
where asot.pk_ent_codigo IS NOT NULL
AND movllavero.estmov_codigo=1
and asot.pk_produc_codigo IS NOT NULL and
abotar.abotar_codigo IN ($lista) order by abotar.fecha_creacion DESC");

            $data['tarjetaEntidad'] = $tarjetasEntidad->result_array;
            $_SESSION["PK_LLAVERO_DES_REVERSO"] = $pk_codigo_llavero;
            $data['menu'] = "reverso";
            $data['saldo'] = $this->saldollavemaestra();
            $this->load->view('portal/templates/header2llave', $data);
            $this->load->view('portal/llave/reverso2', $data);
            $this->load->view('portal/templates/footer', $data);
        }
    }

    public function reversosaldotarjeta() {
        $this->verificarllaveMestra();
        $this->verificarllaveMestraAdmin();
        //$empresa = $this->session->userdata("entidad");
        $empresa = $_SESSION['entidad'];
        $pk_ent_codigo_empresa = $empresa['PK_ENT_CODIGO'];
        //$usuario = $this->session->userdata("usuario");
        $usuario = $_SESSION['usuario'];
        $pk_ent_codigo = $usuario['PK_ENT_CODIGO'];

        $post = $this->input->post();
        if ($post) {

            foreach ($post as $key => $value) {
//DIVIDE EL REGISTRO

                $regdivido = explode("/", $key, 6);
//CREA UN REGISTRO DE CADA UNO
                $datosth = array(
                    "CODTH" => $regdivido[1],
                    "CODPROD" => $regdivido[2],
                    "PK_TARJET_CODIGO" => $regdivido[3],
                    "PKTAR" => $regdivido[4],
                    "ABOTAR_CODIGO" => $regdivido[5],
                    "MONTO" => ""
                );
                $llave = $regdivido[1] . $regdivido[2] . $regdivido[3] . $regdivido[4] . $regdivido[5];

                if (!array_key_exists($llave, $contenido)) {

                    if ($regdivido[0] == 'monto') {
                        $porciones = explode(".", $value);
                        $monto_reverso = $this->dejarSoloCaracteresDeseados($porciones[0], "0123456789");
                        $datosth['MONTO'] = $monto_reverso;
                    }
                    $contenido[$llave] = $datosth;
                } else {
                    if ($regdivido[0] == 'monto') {
                        $porciones = explode(".", $value);
                        $monto_reverso = $this->dejarSoloCaracteresDeseados($porciones[0], "0123456789");
                        $contenido[$llave]['MONTO'] = $monto_reverso;
                    }
                }
            }
//validar cada tarjeta contenga saldo disponible o retornar error
            $costomov = 0;
            $valorComi = 0;
            $cantRev = 0;
            foreach ($contenido as $key1 => $value1) {
                $total_reverso_tarj = $value1['MONTO'];
                $total_mov += $value1['MONTO'];
                $pk_tarjeta = $value1['PK_TARJET_CODIGO'];
                $saldoactutarjeta = $this->returnsaldotarjetaid($pk_tarjeta);
                $pk_abono = $value1['ABOTAR_CODIGO'];
                $difsaldostarjrev = $saldoactutarjeta - $total_reverso_tarj;
                $qvarabono = $this->db->query("select abotar.monto_abono from MODLLAVEMAESTRA.LLAVETBLABOTAR abotar
                    where abotar.abotar_codigo=$pk_abono");
                $valor_abono = $qvarabono->result_array[0];
                $difsaldotarabon = $valor_abono['MONTO_ABONO'] - $total_reverso_tarj;

                //Consulta costo movimiento  reverso cod 108
                $sqlcostomov = $this->db->query("select valor_parametro from modgeneri.gentblpargen where 
                       pk_pargen_codigo = 108");
                $valorComi = $sqlcostomov->result_array[0]['VALOR_PARAMETRO'];
                $costomov += $valorComi;
                $cantRev++;
                if ($difsaldotarabon < 0) {
                    redirect("/portal/llaveMaestra/reverso?errordifsalabono");
                }
                if ($difsaldostarjrev < 0) {
                    redirect("/portal/llaveMaestra/reverso?errorsalta");
                }
            }

//validar moto maximo reverso por dia establecido en la creacion llave maestra
            $sql = "BEGIN MODLLAVEMAESTRA.LLAVMAEPKGGENERAL.returntotalpktipomovdia(
                parpkentidad =>:parpkentidad,
                parpktipmovllavero=>:parpktipmovllavero,
                parpkcoorllavero=>:parpkcoorllavero,
                parmensajerespuesta =>:parmensajerespuesta,
                partotalmov=>:partotalmov,
                parvalorlimite=>:parvalorlimite,
                parrespuesta=>:parrespuesta);
                END;";
            $conn = $this->db->conn_id;
            $stmt = oci_parse($conn, $sql);
            oci_bind_by_name($stmt, ':parpkentidad', $pk_ent_codigo_empresa, 32);
            $parpktipomov = 5; // reverso tarjeta
            oci_bind_by_name($stmt, ':parpktipmovllavero', $parpktipomov, 32);
            oci_bind_by_name($stmt, ':parpkcoorllavero', $pk_ent_codigo, 32);
            oci_bind_by_name($stmt, ':parmensajerespuesta', $parmensajerespuesta, 32);
            oci_bind_by_name($stmt, ':partotalmov', $partotalmov, 32);
            oci_bind_by_name($stmt, ':parvalorlimite', $parvalorlimite, 32);
            oci_bind_by_name($stmt, ':parrespuesta', $parrespuesta, 32);
            if (!oci_execute($stmt)) {
                $e = oci_error($stmt);
                VAR_DUMP($e);
                exit;
            }
            if ($parrespuesta == 1) {
                $totalmovimientos = $partotalmov + $total_mov + $costomov;
                $limitereversollave = (int) $parvalorlimite;
            }

            if ($totalmovimientos > $limitereversollave) {
                redirect("/portal/llaveMaestra/reverso?errorlimitereverso");
            } else {

                //$empresa = $this->session->userdata("entidad");
                $empresa = $_SESSION['entidad'];
//                    $pk_ent_codigo = $empresa['PK_ENT_CODIGO']; // si se quiere enviar correo a la entidad empresa
                //$usuario = $this->session->userdata("usuario");
                $usuario = $_SESSION['usuario'];
                $usuariocreacion = $usuario['USUARIO_ACCESO'];
                $usuario_pk_entidad = $usuario['PK_ENT_CODIGO']; //usuario coordinador al que se le envia correo con pin de verificacion
//llamado procedimiento creacion movimiento llavero luego se llama procedimiento abono tarjetas (prcabonotarjetallavemaestra)
//

                $sql = "BEGIN MODLLAVEMAESTRA.LLAVMAEPKGGENERAL.prccrearmovimientollaveroabono(
                        parpkentidad =>:parpkentidad,
                        parpkllavero=>:parpkllavero,
                        parnuevosaldollavero =>:parnuevosaldollavero,
                        parsaldoantiguollavero=>:parsaldoantiguollavero,
                        parmontomov=>:parmontomov,
                        parusuario =>:parusuario,
                        partipomovllaverocodigo =>:partipomovllaverocodigo,                        
                        parmovllaverocodigo=>:parmovllaverocodigo,
                        pinconfirmacion=>:pinconfirmacion,
                        correoenvio =>:correoenvio,
                        parmensajerespuesta=>:parmensajerespuesta,
                        parrespuesta=>:parrespuesta);
                        END;";
                $conn = $this->db->conn_id;
                $stmt = oci_parse($conn, $sql);
                $parpk_endidad_codigo = $usuario_pk_entidad;
                oci_bind_by_name($stmt, ':parpkentidad', $parpk_endidad_codigo, 32);
                $pk_llavero_codigo = $_SESSION["PK_LLAVERO_DES_REVERSO"];
                oci_bind_by_name($stmt, ':parpkllavero', $pk_llavero_codigo, 32);
                $saldo_llavero = $this->returnsaldollaveroid($pk_llavero_codigo);
                $monto_mov = $total_mov;
                //$this->session->set_userdata('MONTO_REVERSO', $monto_mov);
                $_SESSION['MONTO_REVERSO']= $monto_mov;
                //se resta costomov costo que lleva hacer reverso 
                $parnuevosaldollavero = $saldo_llavero + $monto_mov - $costomov;
                log_info($this->dataLlave . '::::REVERSO SALDO TARJETA A LLAVERO:::: -PK_LLAVERO: ' . $pk_llavero_codigo . ' -SALDO ACTUAL LLAVERO: ' . $saldo_llavero . ' -MONTO MOVIMIENTO: ' . $monto_mov . ' -NUEVO SALDO:' . $parnuevosaldollavero . '- COSTO MOVIMIENTO: ' . $costomov . '- CANTIDAD REVERSOS: ' . $cantRev);

                oci_bind_by_name($stmt, ':parnuevosaldollavero', $parnuevosaldollavero, 32);
                oci_bind_by_name($stmt, ':parsaldoantiguollavero', $saldo_llavero, 32);
                oci_bind_by_name($stmt, ':parmontomov', $monto_mov, 32);
                oci_bind_by_name($stmt, ':parusuario', $usuariocreacion, 32);
                $partipomovllavero = 5; //Reverso saldo tarjeta a llavero 
                oci_bind_by_name($stmt, ':partipomovllaverocodigo', $partipomovllavero, 32);
                oci_bind_by_name($stmt, ':parmovllaverocodigo', $parmovllaverocodigo, 32);
                oci_bind_by_name($stmt, ':pinconfirmacion', $pinconfirmacion, 32);
                oci_bind_by_name($stmt, ':correoenvio', $correoenvio, 50);
                oci_bind_by_name($stmt, ':parmensajerespuesta', $parmensajerespuesta, 32);
                oci_bind_by_name($stmt, ':parrespuesta', $parrespuesta, 32);
                if (!oci_execute($stmt)) {
                    $e = oci_error($stmt);
                    VAR_DUMP($e);
                    exit;
                }

                if ($parrespuesta == 1) {
//Array con data tarjetas 
                    $reversodata = array();
                    $correodestino = $correoenvio;
                    $obj = array();
                    foreach ($contenido as $key => $value) {
                        $obj = (object) [
                                    'pk_tarjeta_codigo_reverso' => $value['PK_TARJET_CODIGO'], //codigo tarjeta
                                    'codigo_th_reverso' => $value['CODTH'], //codigo tarjetahabiente
                                    'producto_codigo_reverso' => $value['CODPROD'], //codigo producto
                                    'monto_reverso' => $value['MONTO'], //Monto reverso
                                    'pk_abotar_codigo_reverso' => $value['ABOTAR_CODIGO'], //pk_abotar_codigo
                                    'pk_asotar_codigo_reverso' => $value['PKTAR'] //pk_asotar_codigo
                        ];

                        array_push($reversodata, (array) $obj);
                    }

                    $_SESSION['datatarjreverso'] = $reversodata;
                    $correoen = 'resgistrado';
                    if ($this->mask_email($correodestino)) {
                        $correoen = $this->mask_email($correodestino);
                    }
//                        $this->session->set_userdata(array('CORREO_DES_REVERSO' => $correoen, 'PAR_MOVLLAVERO_CODIGO_REV' => $parmovllaverocodigo));
                    $_SESSION["CORREO_DES_REVERSO"] = $correoen;
                    $_SESSION["PAR_MOVLLAVERO_CODIGO_REV"] = $parmovllaverocodigo;
                    $confirmarre = 200;
                    redirect("/portal/llaveMaestra/reverso?revOK&rev=$confirmarre");
                } else {
                    redirect("/portal/llaveMaestra/reverso?error_rev");
                }
            }
        }
    }

    public function llavero($pk_llavero = 0, $modificar = '', $cargar = '') {
        $this->verificarllaveMestraPerfilGestor();
        $this->verificarllaveMestraAdmin();
        //$empresa = $this->session->userdata("entidad");
        $empresa = $_SESSION['entidad'];
        //$campana = $this->session->userdata("campana");
        $campana = $_SESSION['campana'];
        $pk_ent_codigo = $empresa['PK_ENT_CODIGO'];
//         administradores ligados a la empresa  
/*        
        $sqladministradores = $this->db->query("select ent.nombre ||' '||ent.apellido NOMBRE,ent.pk_ent_codigo, ent.documento
            from modcliuni.clitblentida ent
            join modcliuni.clitblvincul vincu on vincu.clitblentida_pk_ent_codigo 
             = ent.pk_ent_codigo
             and vincu.clitblentida_pk_ent_codigo1 = $pk_ent_codigo
             and vincu.clitbltipvin_pk_tipvin_codigo = 47 
             and vincu.clitblcampan_pk_campan_codigo=$campana and vincu.fecha_fin is null");
        $administradores = $sqladministradores->result_array;
 */

//        coordinadores ligados a la empresa
        $sqlcoordinadores = $this->db->query("select ent.nombre ||' '||ent.apellido NOMBRE,ent.pk_ent_codigo, ent.documento,ent.correo_electronico
            from modcliuni.clitblentida ent
            join modcliuni.clitblvincul vincu on vincu.clitblentida_pk_ent_codigo 
             = ent.pk_ent_codigo
             and vincu.clitblentida_pk_ent_codigo1 = $pk_ent_codigo
             and vincu.clitbltipvin_pk_tipvin_codigo = 60
             and vincu.clitblcampan_pk_campan_codigo=$campana "
                . "and vincu.fecha_fin is null");
        $coordinadores = $sqlcoordinadores->result_array;
        if ($cargar == 'ok' && $pk_llavero != 0) {
            $sql = "BEGIN MODLLAVEMAESTRA.LLAVMAEPKGGENERAL.prcsaldollavero(
                    parcodigollavero =>:parcodigollavero,
                    parsaldo =>:parsaldo,
                    parrespuesta=>:parrespuesta);
                    END;";

            $conn = $this->db->conn_id;
            $stmt = oci_parse($conn, $sql);
            $parpk_llavero = $pk_llavero;
            oci_bind_by_name($stmt, ':parcodigollavero', $parpk_llavero, 32);
            oci_bind_by_name($stmt, ':parsaldo', $parsaldo, 32);
            oci_bind_by_name($stmt, ':parrespuesta', $parrespuesta, 32);
            if (!oci_execute($stmt)) {
                $e = oci_error($stmt);
                VAR_DUMP($e);
                exit;
            }
            if ($parrespuesta == 1) {
                $saldoactullavero = $parsaldo;
                $data['saldo_llavero'] = $parsaldo;
                $data['carga_llavero'] = 1;
            }
        }
        if ($modificar == 'modificar') {
            $sqlllavero = $this->db->query("select llavero.llavero_codigo pk_llavero, llavero.nombre_llavero NOMBRE,llavero.pk_tipnot_llavero , llavero.pk_ent_codigo_coor,llavero.pk_ent_codigo_adm_pago
            from modllavemaestra.llavetblllavero llavero
             WHERE llavero.llavero_codigo  = $pk_llavero
             ");
            $llavero = $sqlllavero->result_array[0];

            foreach ($coordinadores as $value) {
                if ($value['PK_ENT_CODIGO'] == $llavero['PK_ENT_CODIGO_COOR']) {
                    $data['nomcoordinador'] = $value['NOMBRE'];
                }
            }
            /*
            foreach ($administradores as $value) {
                if ($value['PK_ENT_CODIGO'] == $llavero['PK_ENT_CODIGO_ADM_PAGO']) {
                    $data['nomadmpago'] = $value['NOMBRE'];
                }
            }*/
            if ($llavero['PK_TIPNOT_LLAVERO'] == 1) {
                $data['valchcorreo'] = 'on';
            } else if ($llavero['PK_TIPNOT_LLAVERO'] == 2) {
                $data['valchsms'] = 'on';
            } else if ($llavero['PK_TIPNOT_LLAVERO'] == 3) {
                $data['valchcorreo'] = 'on';
                $data['valchsms'] = 'on';
            }
            $data['pk_llavero'] = $llavero['PK_LLAVERO'];
            $data['coordinador'] = $llavero['PK_ENT_CODIGO_COOR'];
           // $data['adminpagos'] = $llavero['PK_ENT_CODIGO_ADM_PAGO'];
            $data['nomllavero'] = $llavero['NOMBRE'];
        }


        $post = $this->input->post();
        if ($post) {
          //if ((empty($post['ckcorreo']) && empty($post['cksms'])) || empty($post['nombllavero']) || empty($post['coordinador']) || empty($post['admpagos'])) {
          if ((empty($post['ckcorreo']) && empty($post['cksms'])) || empty($post['nombllavero']) || empty($post['coordinador'])) {
                foreach ($coordinadores as $value) {
                    if ($value['PK_ENT_CODIGO'] == $post['coordinador']) {
                        $data['nomcoordinador'] = $value['NOMBRE'];
                    }
                }
                /*
                foreach ($administradores as $value) {
                    if ($value['PK_ENT_CODIGO'] == $post['admpagos']) {
                        $data['nomadmpago'] = $value['NOMBRE'];
                    }
                }                
                 */
                $data['coordinador'] = $post['coordinador'];
               // $data['adminpagos'] = $post['admpagos'];
                $data['valchcorreo'] = $post['ckcorreo'];
                $data['valchsms'] = $post['cksms'];
                $data['error'] = 1;
                $data['nomllavero'] = $post['nombllavero'];
                
              
                
            } else {

                if ($post['ckcorreo'] == 'on' && is_null($post['cksms'])) {
                    $pk_tipo_not = 1;
                } else if ($post['cksms'] == 'on' && is_null($post['ckcorreo'])) {
                    $pk_tipo_not = 2;
                } else if ($post['ckcorreo'] == 'on' && $post['cksms'] == 'on') {
                    $pk_tipo_not = 3;
                }


                if ($post['pk_llavero'] != 0 || $post['pk_llavero'] != '') {
                    $sql = "BEGIN MODLLAVEMAESTRA.LLAVMAEPKGGENERAL.prcupdatellavero(
                    parpkllaverocodigo =>:parpkllaverocodigo,
                    parnombre =>:parnombre,
                    partiponot=>:partiponot,
                    parcodcoor=>:parcodcoor,
                    parcodadmpago=>:parcodadmpago,
                    parusuarioactualiza=>:parusuarioactualiza,
                    parrespuesta=>:parrespuesta);
                    END;";

                    $conn = $this->db->conn_id;
                    $stmt = oci_parse($conn, $sql);
                    //$usuario = $this->session->userdata("usuario");
                    $usuario = $_SESSION['usuario'];
                    $usuarioactualizacion = $usuario['USUARIO_ACCESO'];
                    $parpk_llavero = $post['pk_llavero'];
                    oci_bind_by_name($stmt, ':parpkllaverocodigo', $parpk_llavero, 32);
                    $parnom_llavero = $post['nombllavero'];
                    oci_bind_by_name($stmt, ':parnombre', $parnom_llavero, 250);
                    $par_tipo_not = $pk_tipo_not;
                    oci_bind_by_name($stmt, ':partiponot', $par_tipo_not, 32);
                    $par_coordinador = $post['coordinador'];
                    oci_bind_by_name($stmt, ':parcodcoor', $par_coordinador, 32);
                    //$par_adm_pago = $post['admpagos'];
                    $par_adm_pago=0;
                    oci_bind_by_name($stmt, ':parcodadmpago', $par_adm_pago, 32);
                    oci_bind_by_name($stmt, ':parusuarioactualiza', $usuarioactualizacion, 32);
                    oci_bind_by_name($stmt, ':parrespuesta', $parrespuesta, 32);
                    if (!oci_execute($stmt)) {
                        $e = oci_error($stmt);
                        VAR_DUMP($e);
                        exit;
                    } else if ($parrespuesta != 1) {
                        if ($parrespuesta == 6001) {
                            $errorResppuesta = 'ERROR ' . $parrespuesta . ' el parpkllaverocodigo viene null';
                        } else if ($parrespuesta == 6500) {
                            $errorResppuesta = 'ERROR ' . $parrespuesta . ' Ya existe un llavero con el nombre ' . $parnom_llavero;
                        }
                        $data['ErrorCreando'] = $errorResppuesta;
                    } else if ($parrespuesta == 1) {
                        $data['success'] = 2;
                    }
                } else {
                    $parnom_llavero = $post['nombllavero'];

                    //$empresa = $this->session->userdata("entidad");
                    $empresa = $_SESSION['entidad'];
                    $pk_ent_codigo = $empresa['PK_ENT_CODIGO'];
//se busca la llave maestra asociada a la entidad 
                    $activo = $this->db->query("SELECT llavmae_codigo pk_llave_maestra FROM MODLLAVEMAESTRA.LLAVETBLLLAVMAE llavemae WHERE llavemae.pk_ent_codigo= $pk_ent_codigo");
                    $pk_llave_maestra = $activo->result_array[0];

                    log_info('::::SI ESTA CREANDO EL LLAVERO: '.$parnom_llavero );
                    $sql = "BEGIN MODLLAVEMAESTRA.LLAVMAEPKGGENERAL.asociarllavero(
                    parnombre =>:parnombre,
                    parmontoasignado =>:parmontoasignado,
                    parcodigollavemaestra=>:parcodigollavemaestra,
                    parestado=>:parestado,
                    partiponot=>:partiponot,
                    parcodcoor=>:parcodcoor,
                    parcodadmpago=>:parcodadmpago,
                    parusuariocreacion=>:parusuariocreacion,
                    parpkllavero=>:parpkllavero,
                    parrespuesta=>:parrespuesta);
                    END;";

                    $conn = $this->db->conn_id;
                    $stmt = oci_parse($conn, $sql);
                    //$usuario = $this->session->userdata("usuario");
                    $usuario = $_SESSION['usuario'];
                    $usuariocreacion = $usuario['USUARIO_ACCESO'];


                    oci_bind_by_name($stmt, ':parnombre', $parnom_llavero, 32);
                    $par_monto = 0; /* $post['monto']; */
                    oci_bind_by_name($stmt, ':parmontoasignado', $par_monto, 32);
                    $parcodigollavemaestra = $pk_llave_maestra['PK_LLAVE_MAESTRA']; //buscar llave asociada
                    oci_bind_by_name($stmt, ':parcodigollavemaestra', $parcodigollavemaestra, 32);
                    oci_bind_by_name($stmt, ':parestado', $par_estado_llavero, 32);
                    $par_tipo_not = $pk_tipo_not;
                    oci_bind_by_name($stmt, ':partiponot', $par_tipo_not, 32);
                    $par_coordinador = $post['coordinador'];
                    oci_bind_by_name($stmt, ':parcodcoor', $par_coordinador, 32);
                    $par_adm_pago =0;
                    // $par_adm_pago = $post['admpagos'];
                    oci_bind_by_name($stmt, ':parcodadmpago', $par_adm_pago, 32);
                    oci_bind_by_name($stmt, ':parusuariocreacion', $usuariocreacion, 32);
                    oci_bind_by_name($stmt, ':parpkllavero', $parpkllavero, 32);
                    oci_bind_by_name($stmt, ':parrespuesta', $parrespuesta, 32);

                    if (!oci_execute($stmt)) {
                        $e = oci_error($stmt);
                        VAR_DUMP($e);
                        exit;
                    } else if ($parrespuesta != 1) {

                        if ($parrespuesta == 6001 || $parrespuesta == 6002) {
                            $errorResppuesta = 'ERROR ' . $parrespuesta . ' DATOS DE USUARIO INCORRECTOS';
                        } else if ($parrespuesta == 6009) {
                            $errorResppuesta = 'ERROR ' . $parrespuesta . ' NO ESTA VINCULADO CON DICHA ENTIDAD';
                        } else if ($parrespuesta == 0) {
                            $errorResppuesta = 'ERROR ' . $parrespuesta . ' NO TIENE ACTIVO SERVICIO PARA BONO TRANSPORTADOR';
                        } else if ($parrespuesta == 6500) {
                            $errorResppuesta = 'ERROR ' . $parrespuesta . ' Ya existe un llavero con el nombre ' . $parnom_llavero;
                        }
                        $data['ErrorCreando'] = $errorResppuesta;
                    } else if ($parrespuesta == 1) {
                        $data['success'] = 1;
                    }
                }
            }
        }
//        




        //$data['administradores'] = $administradores;
        $data['coordinadores'] = $coordinadores;
        $data['saldo'] = $this->saldollavemaestra();
        $tipodocumento = $this->db->query('SELECT PK_TD_CODIGO,ABREVIACION,NOMBRE FROM MODCLIUNI.CLITBLTIPDOC');
        $data['tipoDocumento'] = $tipodocumento->result_array;
        $data['empresa'] = $empresa['NOMBREEMPRESA'];
        //$usuario = $this->session->userdata("usuario");
        $usuario = $_SESSION['usuario'];
        $data['usuario'] = $usuario['NOMBRE'] . " " . $usuario['APELLIDO'];
        //$ultimaconexion = $this->session->userdata("ultimaconexion");
        $ultimaconexion = $_SESSION['ultimaconexion'];
        $data['ultimaconexion'] = $ultimaconexion;
        $data['llaveMaestra'] = 1;
        $data['menu'] = "bolsillo";
        $data['llavero'] = 1;
        $this->load->view('portal/templates/header2llave', $data);
        $this->load->view('portal/llave/llavero', $data);
        $this->load->view('portal/templates/footer', $data);
    }

// devolucion saldo llavero a llavemaestra
    public function devolucion() {
        $this->verificarllaveMestraNuevosPerfiles();
        //$rol = $this->session->userdata("rol");
        $rol = $_SESSION['rol'];

        $data['llaveros'] = $this->returnarrayllaveros();
        $data['menu'] = "reverso";
        //$empresa = $this->session->userdata("entidad");
        $empresa = $_SESSION['entidad'];
        $pk_ent_codigo = $empresa['PK_ENT_CODIGO'];
        $data['empresa'] = $empresa['NOMBREEMPRESA'];
        //$usuario = $this->session->userdata("usuario");
        $usuario = $_SESSION['usuario'];
        $data['usuario'] = $usuario['NOMBRE'] . " " . $usuario['APELLIDO'];
        //$ultimaconexion = $this->session->userdata("ultimaconexion");
        $ultimaconexion = $_SESSION['ultimaconexion'];
        $data['ultimaconexion'] = $ultimaconexion;
        $data['saldo'] = $this->saldollavemaestra();
        $this->load->view('portal/templates/header2llave', $data);
        $this->load->view('portal/llave/devolucion', $data);
        $this->load->view('portal/templates/footer', $data);
    }

    public function solicitarDevolucion() {
        $this->verificarllaveMestraNuevosPerfiles();
        $post = $this->input->post();
        //$empresa = $this->session->userdata("entidad");
        $empresa = $_SESSION['entidad'];
        if ($post) {
            if (empty($post['pk_llavero_codigo'])) {
                redirect("/portal/llaveMaestra/solicitarDevolucion?errorpkllavero");
            }
            if (!empty($post['datath'])) {
                $totalsol = 0;

                foreach ($post['datath'] as $key => $value) {

                    $dataUserTh = explode(",", $value);
                    $cod_ent_th = $dataUserTh[0];
                    $pk_codigo_abono = $dataUserTh[1];

                    $sql = "BEGIN MODLLAVEMAESTRA.LLAVMAEPKGGENERAL.prcsolicitardevolucion(
                            parpkentidath =>:parpkentidath,
                            parpkabonocodigo =>:parpkabonocodigo,
                            parmensajerespuesta =>:parmensajerespuesta,
                            parrespuesta =>:parrespuesta);
                            END;";

                    $conn = $this->db->conn_id;
                    $stmt = oci_parse($conn, $sql);
                    oci_bind_by_name($stmt, ':parpkentidath', $cod_ent_th, 32);
                    oci_bind_by_name($stmt, ':parpkabonocodigo', $pk_codigo_abono, 32);
                    oci_bind_by_name($stmt, ':parmensajerespuesta', $parmensajerespuesta, 500);
                    oci_bind_by_name($stmt, ':parrespuesta', $parrespuesta, 32);

                    if (!oci_execute($stmt)) {
                        $e = oci_error($stmt);
                        VAR_DUMP($e);
                        exit;
                    }
                    if ($parrespuesta == 1) {
                        $totalsol++;
//                        var_dump($dataUserTh);
//                        var_dump($totalsol);
                    }
                }
                if ($totalsol == count($post['datath'])) {
                    $data['solDevol'] = 'ok';
                }
            } else {
                redirect("/portal/llaveMaestra/solicitarDevolucion?errordata");
//                redirect("/portal/llaveMaestra/abono?errorpkllavero");
            }
        }

//        $sqlabonollavero = $this->db->query(" select vista.nomtar, vista.abr, vista.doc, vista.NOMPRO, vista.CODPROD, vista.codth, vista.NUMTAR,vista.id_empresa,vista.PK_TARJET_CODIGO,vista.ciudad,abotar.monto_abono, asot.asotar_codigo PKTAR,llavero.nombre_llavero,llavero.llavero_codigo pk_llavero,vista.nomcustodio from (SELECT ent.documento DOC,
//                                        ent.pk_ent_codigo CODTH,CIUD.nombre ciudad,
//                                        tipdoc.abreviacion abr, 
//                                        NVL(TO_CHAR(tar.fecha_creacion,'DD/MM/YYYY'),'PENDIENTE') FEC,
//                                        ent.nombre ||' '||ent.apellido nomtar, TAR.NUMERO NUMTAR,TAR.PK_TARJET_CODIGO,
//                                        PRO.NOMBRE_PRODUCTO NOMPRO,
//                                        pro.pk_produc_codigo codprod,
//                                        tar.pk_tarjet_codigo codtar,ENTCUS.nombre ||' '||ENTCUS.apellido nomcustodio,
//                                        tar.id_empresa
//                                        FROM MODTARHAB.tartbltarjet tar 
//                                        join MODTARHAB.TARTBLCUENTA CUE 
//                                        ON cue.pk_tartblcuenta_codigo = tar.pk_tartblcuenta_codigo 
//                                        AND cue.PK_ENT_CODIGO_EMP = {$empresa['PK_ENT_CODIGO']}
//                                        JOIN MODCLIUNI.CLITBLENTIDA ENT 
//                                        ON ent.pk_ent_codigo = cue.pk_ent_codigo_th 
//                                        JOIN MODCLIUNI.CLITBLCIUDAD CIUD ON ent.CLITBLCIUDAD_PK_CIU_CODIGO = CIUD.PK_CIU_CODIGO
//                                        JOIN MODCLIUNI.CLITBLTIPDOC TIPDOC 
//                                        ON tipdoc.pk_td_codigo = ent.clitbltipdoc_pk_td_codigo 
//                                        JOIN MODPRODUC.PROTBLPRODUC PRO 
//                                        ON pro.pk_produc_codigo = cue.pk_produc_codigo and pro.pk_linpro_codigo in (2)
//                                        JOIN MODALISTA.ALITBLDETPED DETPED 
//                                        ON detped.pk_detped_codigo = tar.pk_detped_codigo 
//                                        JOIN MODALISTA.ALITBLPEDIDO PED ON ped.pk_pedido_codigo = detped.pk_pedido 
//                                        JOIN MODCLIUNI.CLITBLENTIDA ENTCUS ON entcus.pk_ent_codigo = ped.pk_custodio
//                                        JOIN MODCLIUNI.CLITBLCAMPAN CAM ON cam.pk_campan_codigo = ped.pk_campan_codigo 
//                                        LEFT JOIN MODPROPAG.PPATBLDETORD DETORD ON detord.pk_pedido = detped.pk_detped_Codigo
//                                        left JOIN MODFACTUR.FACTBLFACORD FACORD ON facord.pk_ordcom_codigo=detord.pk_orden_compra
//                                        left JOIN MODPROPAG.PPATBLORDCOM ORDCOM ON facord.pk_ordcom_codigo=ordcom.pk_ordcom_codigo
//                                        LEFT JOIN MODFACTUR.FACTBLFACTUR factur ON facord.pk_factur_codigo=factur.pk_factur_codigo 
//                                        JOIN MODTARHAB.tartblesttar ESTTAR 
//                                        ON esttar.pk_esttar_codigo = tar.pk_esttar_codigo            
//                                        order BY tar.fecha_creacion asc) vista 
//                                        left join MODLLAVEMAESTRA.llavetblasotar asot on vista.codprod = asot.pk_produc_codigo
//                                        Join MODLLAVEMAESTRA.llavetblabotar abotar on asot.asotar_codigo = abotar.pk_asotar_codigo 
//                                        join MODLLAVEMAESTRA.llavetblllavero llavero on asot.pk_llavero_codigo = llavero.llavero_codigo
//                                        and vista.codth = asot.pk_ent_codigo and vista.codtar = asot.pk_tarjeta_codigo where asot.pk_ent_codigo IS NOT NULL
//                                        and asot.pk_produc_codigo IS NOT NULL --AND
//                                        --llavero.llavero_codigo=14
//                                        order by vista.NOMTAR asc ");
//        $data['abonollaveros'] = $sqlabonollavero->result_array;


        $data['llaveros'] = $this->returnarrayllaveros();
        $data['saldo'] = $this->saldollavemaestra();
        //$usuario = $this->session->userdata("usuario");
        $usuario = $_SESSION['usuario'];
        $data['usuario'] = $usuario['NOMBRE'] . " " . $usuario['APELLIDO'];
        //$ultimaconexion = $this->session->userdata("ultimaconexion");
        $ultimaconexion = $_SESSION['ultimaconexion'];
        $data['ultimaconexion'] = $ultimaconexion;
        $data['llaveMaestra'] = 1;
        $data['menu'] = "reverso";
        $this->load->view('portal/templates/header2llave', $data);
        $this->load->view('portal/llave/solicitarDevolucion', $data);
        $this->load->view('portal/templates/footer', $data);
    }

    public function devoluciondatallavero() {
        $this->verificarllaveMestraNuevosPerfiles();

        $post = $this->input->post();
        if ($post) {
            if ($post['pk_llavero'] == '') {
                $data['error_devolucion'] = 1;
                $data['errorpkllavero'] = 1;
            } else {
                $pk_codigo_llavero = $post['pk_llavero'];
                $llaveros = $this->returnarrayllaveros();
                foreach ($llaveros as $value) {
                    if ($value['PK_LLAVERO_CODIGO'] == $pk_codigo_llavero) {
                        $nombrellaveroselect = $value['NOMBRE_LLAVERO'];
                        $saldo_llavero = $value['SALDO'];
                    }
                }
                $data['saldo_llavero'] = $saldo_llavero;
                $data['nombrellaveroselect'] = $nombrellaveroselect;
                $data['pk_llavero_codigo'] = $post['pk_llavero'];
            }
        }

        $data['llaveros'] = $this->returnarrayllaveros();
        //$empresa = $this->session->userdata("entidad");
        $empresa = $_SESSION['entidad'];
        $pk_ent_codigo = $empresa['PK_ENT_CODIGO'];
        $data['empresa'] = $empresa['NOMBREEMPRESA'];
        //$usuario = $this->session->userdata("usuario");
        $usuario = $_SESSION['usuario'];
        $data['usuario'] = $usuario['NOMBRE'] . " " . $usuario['APELLIDO'];
        //$ultimaconexion = $this->session->userdata("ultimaconexion");
        $ultimaconexion = $_SESSION['ultimaconexion'];
        $data['ultimaconexion'] = $ultimaconexion;
        $data['menu'] = "reverso";
        $data['saldo'] = $this->saldollavemaestra();
        $this->load->view('portal/templates/header2llave', $data);
        $this->load->view('portal/llave/devolucion', $data);
        $this->load->view('portal/templates/footer', $data);
    }

    public function devolucionsaldollavero() {
        $post = $this->input->post();
        if ($post) {

            if (empty($post['monto_devolucion'])) {
                $data['montovacio'] = 'Por favor ingrese un monto valido ';
            }
            if (!empty($post['pk_llavero_codigo']) && !empty($post['monto_devolucion'])) {
                $porciones = explode(".", $post['monto_devolucion']);
                $monto_devolucion = $this->dejarSoloCaracteresDeseados($porciones[0], "0123456789");
                $pk_codigo_llavero = $post['pk_llavero_codigo'];
                $saldoactual_lavero = $this->returnsaldollaveroid($pk_codigo_llavero);
                $difsaldollavero = $saldoactual_lavero - $monto_devolucion;

                if ($difsaldollavero < 0) {
                    $data['montovacio'] = 'Por favor ingrese un monto valido ';
                } else {
                    //Consulta costo movimiento  reverso cod 108
                    $sqlcostomov = $this->db->query("select valor_parametro  from modgeneri.gentblpargen where 
                       pk_pargen_codigo = 109");
                    $costomov = $sqlcostomov->result_array[0]['VALOR_PARAMETRO'];
                    //$usuario = $this->session->userdata("usuario");
                    $usuario = $_SESSION['usuario'];
                    $usuario_pk_entidad = $usuario['PK_ENT_CODIGO']; //usuario coordinador al que se le envia correo con pin de verificacion
                    $usuarioregistro = $usuario['USUARIO_ACCESO'];
                    $parpk_llavero = $pk_codigo_llavero;
                    //$pk_llavemae = $this->session->userdata("PK_LLAVE_MAESTRA");
                    $pk_llavemae = $_SESSION['PK_LLAVE_MAESTRA'];
                    $monto_movimiento = $monto_devolucion;
                    $saldoactualllavemae = $this->saldollavemaestra();
                    // se descuenta costomovimiento devolucion
                    $varnuevosaldollavemae = $saldoactualllavemae + $monto_movimiento - $costomov;

                    log_info($this->dataLlave . '::::DEVOLUCION SALDO LLAVERO A LLAVE MAESTRA:::: -PK_LLAVE_MAESTRA: ' . $pk_llavemae . ' -PK_LLAVERO: ' . $parpk_llavero . ' -SALDO ACTUAL LLAVE: ' . $saldoactualllavemae . ' -MONTO MOVIMIENTO: ' . $monto_movimiento . ' -NUEVO SALDO:' . $varnuevosaldollavemae);

//llama proceminiento que realiza el guardado del intento de movimiento y envia correo con codigo de confirmacion
                    $sql = "BEGIN MODLLAVEMAESTRA.LLAVMAEPKGGENERAL.prcrearmovllaverollave(
                            parpkentidad =>:parpkentidad,
                            parpkllavero =>:parpkllavero,
                            parpkllavemaestra =>:parpkllavemaestra,
                            parnuevosaldollavemae =>:parnuevosaldollavemae,
                            parsaldoantiguollavemae=>:parsaldoantiguollavemae,
                            parnuevosaldollavero =>:parnuevosaldollavero,
                            parsaldoantiguollavero=>:parsaldoantiguollavero,
                            parmontomov =>:parmontomov,
                            parpktipomov=>:parpktipomov,
                            parusuario =>:parusuario,
                            parcodmovllave=>:parcodmovllave,
                            parcodmovllavero=>:parcodmovllavero,
                            correoenvio =>:correoenvio,
                            codigorespuesta =>:codigorespuesta,
                            mensajerespuesta =>:mensajerespuesta,
                            pinconfirmacion=>:pinconfirmacion);
                            END;";

                    $conn = $this->db->conn_id;
                    $stmt = oci_parse($conn, $sql);
                    $parpk_entidad = $usuario_pk_entidad;
                    oci_bind_by_name($stmt, ':parpkentidad', $parpk_entidad, 32);
                    oci_bind_by_name($stmt, ':parpkllavero', $parpk_llavero, 32);
                    oci_bind_by_name($stmt, ':parpkllavemaestra', $pk_llavemae, 32);
                    oci_bind_by_name($stmt, ':parnuevosaldollavemae', $varnuevosaldollavemae, 32);
                    oci_bind_by_name($stmt, ':parsaldoantiguollavemae', $saldoactualllavemae, 32);
                    oci_bind_by_name($stmt, ':parnuevosaldollavero', $difsaldollavero, 32);
                    oci_bind_by_name($stmt, ':parsaldoantiguollavero', $saldoactual_lavero, 32);
                    oci_bind_by_name($stmt, ':parmontomov', $monto_movimiento, 32);
                    $parpktipomovimiento = 6; //devolucion saldo llavero a llave maestra
                    oci_bind_by_name($stmt, ':parpktipomov', $parpktipomovimiento, 32);
                    oci_bind_by_name($stmt, ':parusuario', $usuarioregistro, 32);
                    oci_bind_by_name($stmt, ':parcodmovllave', $parcodmovllave, 32);
                    oci_bind_by_name($stmt, ':parcodmovllavero', $parcodmovllavero, 32);
                    oci_bind_by_name($stmt, ':correoenvio', $correoenvio, 50);
                    oci_bind_by_name($stmt, ':codigorespuesta', $codigorespuesta, 32);
                    oci_bind_by_name($stmt, ':mensajerespuesta', $mensajerespuesta, 32);
                    oci_bind_by_name($stmt, ':pinconfirmacion', $pinconfirmacion, 32);

                    if (!oci_execute($stmt)) {
                        $e = oci_error($stmt);
                        VAR_DUMP($e);
                        exit;
                    }
                    if ($codigorespuesta == 1) {

                        $confirmadev = 200;
                        $correoen = 'resgistrado';
                        if ($this->mask_email($correoenvio)) {
                            $correoen = $this->mask_email($correoenvio);
                        }
                        //$this->session->set_userdata(array('CORREO_DES_DEVO_LLAVERO' => $correoen, 'COD_MOV_LLAVE_DEV' => $parcodmovllave, 'COD_MOV_LLAVERO_DEV' => $parcodmovllavero, 'PK_COD_LLAVERO' => $parpk_llavero));
                        $_SESSION['CORREO_DES_DEVO_LLAVERO'] = $correoen;
                        $_SESSION['COD_MOV_LLAVE_DEV'] = $parcodmovllave;
                        $_SESSION['COD_MOV_LLAVERO_DEV'] = $parcodmovllavero;
                        $_SESSION['PK_COD_LLAVERO'] = $parpk_llavero;
                        $_SESSION["CORREO_DES_DEVO_LLAVERO"] = $correoen;
                        $_SESSION["COD_MOV_LLAVE_DEV"] = $parcodmovllave;
                        $_SESSION["COD_MOV_LLAVERO_DEV"] = $parcodmovllavero;
                        $_SESSION["PK_COD_LLAVERO"] = $parpk_llavero;
                        redirect("/portal/llaveMaestra/devolucion?devolucionOK&cre=$confirmadev");
                    }
//                        else{
//                            var_dump($mensajerespuesta);
//                            exit();
//                        }
                }
            }
        }
        $data['llaveros'] = $this->returnarrayllaveros();
        //$empresa = $this->session->userdata("entidad");
        $empresa = $_SESSION['entidad'];
        $pk_ent_codigo = $empresa['PK_ENT_CODIGO'];
        $data['empresa'] = $empresa['NOMBREEMPRESA'];
        //$usuario = $this->session->userdata("usuario");
        $usuario = $_SESSION['usuario'];
        $data['usuario'] = $usuario['NOMBRE'] . " " . $usuario['APELLIDO'];
        //$ultimaconexion = $this->session->userdata("ultimaconexion");
        $ultimaconexion = $_SESSION['ultimaconexion'];
        $data['ultimaconexion'] = $ultimaconexion;
        $data['saldo'] = $this->saldollavemaestra();
        $this->load->view('portal/templates/header2llave', $data);
        $this->load->view('portal/llave/devolucion', $data);
        $this->load->view('portal/templates/footer', $data);
    }

    public function cerrarSesion($pantalla = 0) {
        redirect('portal/principal/pantalla');
    }

    public function verificarllaveMestra() {
        //$rol = $this->session->userdata("rol");
        $rol = $_SESSION['rol'];
        //if (($rol != 45) || ($rol != 47) and ( $this->session->userdata("CODIGO_PRODUCTO") != 70)) {
        if(( $_SESSION['PRODUCTOLLAVE']['CODIGO_PRODUCTO'] == 70)){
        if (($rol != 59)&&($rol != 60) && ($rol != 61)) {
            redirect('/portal/principal/pantalla');
        }
        }
    }
       public function verificarllaveMestraNuevosPerfiles() {
        //$rol = $this->session->userdata("rol");
        $rol = $_SESSION['rol'];
        
        //log_info('VAMOS PUES PERFIL SAL DE AHI ' . $_SESSION['PRODUCTOLLAVE']['CODIGO_PRODUCTO'].'y rol que tiene es'.$rol);
        //if (($rol != 45) || ($rol != 47) and ( $this->session->userdata("CODIGO_PRODUCTO") != 70)) {
        if(( $_SESSION['PRODUCTOLLAVE']['CODIGO_PRODUCTO'] == 70)){
        if (($rol != 60) && ($rol != 61)) {
            redirect('/portal/principal/pantalla');
        }
        }
    }

     public function verificarllaveMestraPerfilGestor() {
        //$rol = $this->session->userdata("rol");
        $rol = $_SESSION['rol'];
        //if (($rol != 45) || ($rol != 47) and ( $this->session->userdata("CODIGO_PRODUCTO") != 70)) {
        if(( $_SESSION['PRODUCTOLLAVE']['CODIGO_PRODUCTO'] == 70)){
        if (($rol != 61)) {
            redirect('/portal/principal/pantalla');
        }
        }
    }
    public function verificarllaveMestraEstado() {
        //$rol = $this->session->userdata("rol");
        $rol = $_SESSION['rol'];
        //if (($rol != 45) || ($rol != 47) and ( $this->session->userdata("CODIGO_PRODUCTO") != 70)) {
        if(( $_SESSION['PRODUCTOLLAVE']['CODIGO_PRODUCTO'] == 70)){
        if (($rol != 60) && ($rol != 61)) {
            redirect('/portal/principal/pantalla');
        }
        }
    }
    
    public function verificarllaveMestraAdmin() {
        //$rol = $this->session->userdata("rol");
        $rol = $_SESSION['rol'];
        //$llave_maestra_activa = $this->session->userdata("CODIGO_PRODUCTO");
        $llave_maestra_activa = $_SESSION['PRODUCTOLLAVE']['CODIGO_PRODUCTO'];
        if ($llave_maestra_activa == 70) {
            if (($rol != 59) && ($rol != 60) && ($rol != 61)) {
                redirect('/portal/llaveMaestra/principal/');
            }
        }
    }
    
        public function verificarllaveMestraAdminLLaveros() {
        //$rol = $this->session->userdata("rol");
        $rol = $_SESSION['rol'];
        //$llave_maestra_activa = $this->session->userdata("CODIGO_PRODUCTO");
        $llave_maestra_activa = $_SESSION['PRODUCTOLLAVE']['CODIGO_PRODUCTO'];
        if ($llave_maestra_activa == 70) {
            if (($rol != 61)) {
                redirect('/portal/llaveMaestra/principal/');
            }
        }
    }

    public function recargallavero($pk_llavero = 0, $cargar = '') {
        $this->verificarllaveMestraPerfilGestor();
        $this->verificarllaveMestraAdmin();
        //$empresa = $this->session->userdata("entidad");
        $empresa = $_SESSION['entidad'];
        $pk_ent_codigo = $empresa['PK_ENT_CODIGO'];
         log_info('::::::: EMPRESARIANDO'.$pk_ent_codigo);
//         administradores ligados a la empresa  
      /*
         $sqladministradores = $this->db->query("select ent.nombre ||' '||ent.apellido NOMBRE,ent.pk_ent_codigo, ent.documento
            from modcliuni.clitblentida ent
            join modcliuni.clitblvincul vincu on vincu.clitblentida_pk_ent_codigo 
             = ent.pk_ent_codigo
             and vincu.clitblentida_pk_ent_codigo1 = $pk_ent_codigo
             and vincu.clitbltipvin_pk_tipvin_codigo = 47 and vincu.fecha_fin is null");
        $administradores = $sqladministradores->result_array;
        */
//        coordinadores ligados a la empresa
        $sqlcoordinadores = $this->db->query("select ent.nombre ||' '||ent.apellido NOMBRE,ent.pk_ent_codigo, ent.documento
            from modcliuni.clitblentida ent
            join modcliuni.clitblvincul vincu on vincu.clitblentida_pk_ent_codigo 
             = ent.pk_ent_codigo
             and vincu.clitblentida_pk_ent_codigo1 = $pk_ent_codigo
             and vincu.clitbltipvin_pk_tipvin_codigo = 60 and vincu.fecha_fin is null");
        $coordinadores = $sqlcoordinadores->result_array;
        $sql = "BEGIN MODLLAVEMAESTRA.LLAVMAEPKGGENERAL.prcsaldollavero(
                    parcodigollavero =>:parcodigollavero,
                    parsaldo =>:parsaldo,
                    parrespuesta=>:parrespuesta);
                    END;";

        $conn = $this->db->conn_id;
        $stmt = oci_parse($conn, $sql);
        $parpk_llavero = $pk_llavero;
        oci_bind_by_name($stmt, ':parcodigollavero', $parpk_llavero, 32);
        oci_bind_by_name($stmt, ':parsaldo', $parsaldo, 32);
        oci_bind_by_name($stmt, ':parrespuesta', $parrespuesta, 32);
        if (!oci_execute($stmt)) {
            $e = oci_error($stmt);
            VAR_DUMP($e);
            exit;
        }
        if ($parrespuesta == 1) {
            $saldoactullavero = $parsaldo;
            $data['saldo_llavero'] = $parsaldo;
        }
        $sqlllavero = $this->db->query("select llavero.llavero_codigo pk_llavero, llavero.nombre_llavero NOMBRE,llavero.pk_tipnot_llavero , llavero.pk_ent_codigo_coor,llavero.pk_ent_codigo_adm_pago
            from modllavemaestra.llavetblllavero llavero
             WHERE llavero.llavero_codigo  = $pk_llavero
             ");
        $llavero = $sqlllavero->result_array[0];
         log_info('::::::: probando llaveros $llavero[PK_ENT_CODIGO_COOR]'.$llavero['PK_ENT_CODIGO_COOR']);
        foreach ($coordinadores as $value) {
            log_info('::::::: $value[PK_ENT_CODIGO] == $llavero[PK_ENT_CODIGO_COOR]'.$value['PK_ENT_CODIGO'].' == '.$llavero['PK_ENT_CODIGO_COOR']);
            if (trim($value['PK_ENT_CODIGO']) == trim($llavero['PK_ENT_CODIGO_COOR'])) {
                $data['nomcoordinador'] = $value['NOMBRE'];
                log_info('::::::: CLICK probando llaveros $llavero[PK_ENT_CODIGO_COOR]'.$value['PK_ENT_CODIGO'].'EMPRENDIENDO'.$value['NOMBRE']);
            }
        }
     /*
        foreach ($administradores as $value) {
            if ($value['PK_ENT_CODIGO'] == $llavero['PK_ENT_CODIGO_ADM_PAGO']) {
                $data['nomadmpago'] = $value['NOMBRE'];
            }
        }
        */
        if ($llavero['PK_TIPNOT_LLAVERO'] == 1) {
            $data['valchcorreo'] = 'on';
        } else if ($llavero['PK_TIPNOT_LLAVERO'] == 2) {
            $data['valchsms'] = 'on';
        } else if ($llavero['PK_TIPNOT_LLAVERO'] == 3) {
            $data['valchcorreo'] = 'on';
            $data['valchsms'] = 'on';
        }
        $data['pk_llavero'] = $llavero['PK_LLAVERO'];
        $data['coordinador'] = $llavero['PK_ENT_CODIGO_COOR'];
        //$data['adminpagos'] = $llavero['PK_ENT_CODIGO_ADM_PAGO'];
        $data['nomllavero'] = $llavero['NOMBRE'];
        $post = $this->input->post();
        if ($post) {
            if ($post['valorCarga'] == '') {
                $data['error_carga'] = 1;
            } else {
                $porciones = explode(".", $post['valorCarga']);
              //  log_info($this->dataLlave . '::::PORCIONES VALOR VARGA $porciones'.$porciones);
                $valorcarga = $this->dejarSoloCaracteresDeseados($porciones[0], "0123456789");
//                $valorcarga = $post['valorCarga'];
                //$pk_llavemae = $this->session->userdata("PK_LLAVE_MAESTRA");
                $pk_llavemae = $_SESSION['PK_LLAVE_MAESTRA'];
                //$empresa = $this->session->userdata("entidad");
                $empresa = $_SESSION['entidad'];
                $pk_ent_codigo = $empresa['PK_ENT_CODIGO'];
                $sql = "BEGIN MODLLAVEMAESTRA.LLAVMAEPKGGENERAL.saldollavemaestra(
                    parentcodigo =>:parentcodigo,
                    parsaldo =>:parsaldo,
                    parrespuesta=>:parrespuesta);
                    END;";

                $conn = $this->db->conn_id;
                $stmt = oci_parse($conn, $sql);
                $parpk_entidad = $pk_ent_codigo;
                oci_bind_by_name($stmt, ':parentcodigo', $parpk_entidad, 32);
                oci_bind_by_name($stmt, ':parsaldo', $parsaldo_llavemae, 32);
                oci_bind_by_name($stmt, ':parrespuesta', $parrespuesta, 32);
                if (!oci_execute($stmt)) {
                    $e = oci_error($stmt);
                    VAR_DUMP($e);
                    exit;
                }
                if ($parrespuesta == 1) {
                    $result = $parsaldo_llavemae - $valorcarga;
                    if ($result < 0) {
                        $data['error_tx'] = 'Saldo insuficiente para realizar la transacción';
                    } else {


                        //$usuario = $this->session->userdata("usuario");
                        $usuario = $_SESSION['usuario'];
                        $pk_ent_usuario = $usuario['PK_ENT_CODIGO'];
                        $usuarioregistro = $usuario['USUARIO_ACCESO'];
                        $varnuevosaldollavemae = $parsaldo_llavemae - $valorcarga;
                        //$pk_llavemae = $this->session->userdata("PK_LLAVE_MAESTRA");
                        $pk_llavemae = $_SESSION['PK_LLAVE_MAESTRA'];
//saldo actual llavero
                        $nuevosaldollavero = $saldoactullavero + $valorcarga;

//llama proceminiento que realiza el guardado del intento de movimiento y envia correo con codigo de confirmacion
                        $sql = "BEGIN MODLLAVEMAESTRA.LLAVMAEPKGGENERAL.prcrearmovllaverollave(
                            parpkentidad =>:parpkentidad,
                            parpkllavero =>:parpkllavero,
                            parpkllavemaestra =>:parpkllavemaestra,
                            parnuevosaldollavemae =>:parnuevosaldollavemae,
                            parsaldoantiguollavemae=>:parsaldoantiguollavemae,
                            parnuevosaldollavero =>:parnuevosaldollavero,
                            parsaldoantiguollavero=>:parsaldoantiguollavero,
                            parmontomov =>:parmontomov,
                            parpktipomov=>:parpktipomov,
                            parusuario =>:parusuario,
                            parcodmovllave=>:parcodmovllave,
                            parcodmovllavero=>:parcodmovllavero,
                            correoenvio =>:correoenvio,
                            codigorespuesta =>:codigorespuesta,
                            mensajerespuesta =>:mensajerespuesta,
                            pinconfirmacion=>:pinconfirmacion);
                            END;";

                        $conn = $this->db->conn_id;
                        $stmt = oci_parse($conn, $sql);

                        oci_bind_by_name($stmt, ':parpkentidad', $pk_ent_usuario, 32);
                        oci_bind_by_name($stmt, ':parpkllavero', $parpk_llavero, 32);
                        oci_bind_by_name($stmt, ':parpkllavemaestra', $pk_llavemae, 32);
                        oci_bind_by_name($stmt, ':parnuevosaldollavemae', $varnuevosaldollavemae, 32);
                        oci_bind_by_name($stmt, ':parsaldoantiguollavemae', $parsaldo_llavemae, 32);
                        oci_bind_by_name($stmt, ':parnuevosaldollavero', $nuevosaldollavero, 32);
                        oci_bind_by_name($stmt, ':parsaldoantiguollavero', $saldoactullavero, 32);
                        oci_bind_by_name($stmt, ':parmontomov', $valorcarga, 32);
                        $parpktipomovimiento = 3; //Recarga llavero
                        oci_bind_by_name($stmt, ':parpktipomov', $parpktipomovimiento, 32);
                        oci_bind_by_name($stmt, ':parusuario', $usuarioregistro, 32);
                        oci_bind_by_name($stmt, ':parcodmovllave', $parcodmovllave, 32);
                        oci_bind_by_name($stmt, ':parcodmovllavero', $parcodmovllavero, 32);
                        oci_bind_by_name($stmt, ':correoenvio', $correoenvio, 50);
                        oci_bind_by_name($stmt, ':codigorespuesta', $codigorespuesta, 32);
                        oci_bind_by_name($stmt, ':mensajerespuesta', $mensajerespuesta, 32);
                        oci_bind_by_name($stmt, ':pinconfirmacion', $pinconfirmacion, 32);

                        if (!oci_execute($stmt)) {
                            $e = oci_error($stmt);
                            VAR_DUMP($e);
                            exit;
                        }
                        if ($codigorespuesta == 1) {

                            $confirmarre = 200;
                            $correoen = 'resgistrado';
                            if ($this->mask_email($correoenvio)) {
                                $correoen = $this->mask_email($correoenvio);
                            }
//                            $this->session->set_userdata(array('CORREO_DES_CLLAVERO' => $correoen, 'COD_MOV_LLAVE' => $parcodmovllave, 'COD_MOV_LLAVERO' => $parcodmovllavero, 'PK_COD_LLAVERO' => $parpk_llavero));
                            $_SESSION["CORREO_DES_CLLAVERO"] = $correoen;
                            $_SESSION["COD_MOV_LLAVE"] = $parcodmovllave;
                            $_SESSION["COD_MOV_LLAVERO"] = $parcodmovllavero;
                            $_SESSION["PK_COD_LLAVERO"] = $parpk_llavero;
                            redirect("/portal/llaveMaestra/gestion_llaveros?recargaok&cre=$confirmarre");
                        } else {
                            redirect("/portal/llaveMaestra/recargallavero/$parpk_llavero/cargar");
                        }
                    }
                }
            }
        }
        //$empresa = $this->session->userdata("entidad");
        $empresa = $_SESSION['entidad'];
        $data['empresa'] = $empresa['NOMBREEMPRESA'];
        //$usuario = $this->session->userdata("usuario");
        $usuario = $_SESSION['usuario'];
        $data['usuario'] = $usuario['NOMBRE'] . " " . $usuario['APELLIDO'];
        $data['saldo'] = $this->saldollavemaestra();
        $data['menu'] = "bolsillo";
        $this->load->view('portal/templates/header2llave', $data);
        $this->load->view('portal/llave/cargarllavero', $data);
        $this->load->view('portal/templates/footer', $data);
    }

    public function verificar_codigo_recarga_llavero() {
        $post = $this->input->post();
        if ($post) {
            $codconfpost = $post['codigoconfirmacion'];
            $parcod_movllavero = $_SESSION["COD_MOV_LLAVERO"]; //$this->session->userdata('COD_MOV_LLAVERO');
            $sql = "BEGIN MODLLAVEMAESTRA.LLAVMAEPKGGENERAL.returncodigoverificacio(
                            parpkmovllaverocodigo =>:parpkmovllaverocodigo,
                            parpinconfirmacion =>:parpinconfirmacion,
                            parmensajerespuesta =>:parmensajerespuesta,
                            parrespuesta =>:parrespuesta);
                            END;";

            $conn = $this->db->conn_id;
            $stmt = oci_parse($conn, $sql);
            oci_bind_by_name($stmt, ':parpkmovllaverocodigo', $parcod_movllavero, 32);
            oci_bind_by_name($stmt, ':parpinconfirmacion', $parpinconfirmacion, 32);
            oci_bind_by_name($stmt, ':parmensajerespuesta', $parmensajerespuesta, 500);
            oci_bind_by_name($stmt, ':parrespuesta', $parrespuesta, 32);

            if (!oci_execute($stmt)) {
                $e = oci_error($stmt);
                VAR_DUMP($e);
                exit;
            }
            if ($parrespuesta == 1) {
                $pinconfirmacion = $parpinconfirmacion;
            }
            if ($codconfpost == $pinconfirmacion) {
                $sql = "BEGIN MODLLAVEMAESTRA.LLAVMAEPKGGENERAL.prcupdatesaldosmovllavellavero(
                            parpkllaverocodigo =>:parpkllaverocodigo,
                            parpkllavemaestra =>:parpkllavemaestra,
                            parpkmovllave =>:parpkmovllave,
                            parpkmovllavero =>:parpkmovllavero,
                            parrespuesta=>:parrespuesta);
                            END;";

                $conn = $this->db->conn_id;
                $stmt = oci_parse($conn, $sql);
                $parpk_llaverocodigo = $_SESSION["PK_COD_LLAVERO"]; //$this->session->userdata('PK_COD_LLAVERO');
                oci_bind_by_name($stmt, ':parpkllaverocodigo', $parpk_llaverocodigo, 32);
                //$pk_llavemae = $this->session->userdata("PK_LLAVE_MAESTRA");
                $pk_llavemae = $_SESSION['PK_LLAVE_MAESTRA'];
                oci_bind_by_name($stmt, ':parpkllavemaestra', $pk_llavemae, 32);
                $parcodigo_movllave = $_SESSION["COD_MOV_LLAVE"]; //$this->session->userdata('COD_MOV_LLAVE');
                oci_bind_by_name($stmt, ':parpkmovllave', $parcodigo_movllave, 32);
                oci_bind_by_name($stmt, ':parpkmovllavero', $parcod_movllavero, 32);
                oci_bind_by_name($stmt, ':parrespuesta', $parrespuesta, 32);

                if (!oci_execute($stmt)) {
                    $e = oci_error($stmt);
                    VAR_DUMP($e);
                    exit;
                }
                if ($parrespuesta == 1) {
                    unset($_SESSION["CORREO_DES_CLLAVERO"]);
                    unset($_SESSION["COD_MOV_LLAVE"]);
                    unset($_SESSION["COD_MOV_LLAVERO"]);
                    unset($_SESSION['PK_COD_LLAVERO']);
                    redirect("/portal/llaveMaestra/gestion_llaveros?rsuccessful");
                }
            } else {
                redirect("/portal/llaveMaestra/gestion_llaveros?recargaok&cre=200&error");
            }
        }
    }

    public function verificar_codigo_abono() {

//        var_dump($_SESSION['datatarjabono']);
//        var_dump($this->session->userdata);
        //$empresa = $this->session->userdata("entidad");
        $empresa = $_SESSION['entidad'];
        $pk_ent_codigo = $empresa['PK_ENT_CODIGO'];
        //$usuario = $this->session->userdata("usuario");
        $usuario = $_SESSION['usuario'];
        $usuariocreacion = $usuario['USUARIO_ACCESO'];
        $post = $this->input->post();
        if ($post) {
            $abonosuccess = 0;
            $codconfpost = $post['codigoconfirmacion'];
            $parcod_movllavero = $_SESSION["PAR_MOVLLAVERO_CODIGO_ABONO"]; //$this->session->userdata('PAR_MOVLLAVERO_CODIGO_ABONO');
            log_info($this->dataLlave . '  _SESSION["PAR_MOVLLAVERO_CODIGO_ABONO = ' . $parcod_movllavero);
            $sql = "BEGIN MODLLAVEMAESTRA.LLAVMAEPKGGENERAL.returncodigoverificacio(
                            parpkmovllaverocodigo =>:parpkmovllaverocodigo,
                            parpinconfirmacion =>:parpinconfirmacion,
                            parmensajerespuesta =>:parmensajerespuesta,
                            parrespuesta =>:parrespuesta);
                            END;";

            $conn = $this->db->conn_id;
            $stmt = oci_parse($conn, $sql);
            //$parpinconfirmacion=$codconfpost;
            oci_bind_by_name($stmt, ':parpkmovllaverocodigo', $parcod_movllavero, 32);
            oci_bind_by_name($stmt, ':parpinconfirmacion', $parpinconfirmacion, 32);
            oci_bind_by_name($stmt, ':parmensajerespuesta', $parmensajerespuesta, 500);
            oci_bind_by_name($stmt, ':parrespuesta', $parrespuesta, 32);

            if (!oci_execute($stmt)) {
                $e = oci_error($stmt);
                VAR_DUMP($e);
                log_info($this->errorLlave . ' Error abonando tarjeta ' . $e['message'] . ' PARRESPUESTA:' . $parrespuesta);
            }
            if ($parrespuesta == 1) {
                $pinconfirmacion = $parpinconfirmacion;
            }

            if ($codconfpost == $pinconfirmacion) {

                $data_abono_tarjeta = $_SESSION['datatarjabono'];

                for ($i = 0; $i < count($data_abono_tarjeta); $i++) {
                    $pk_tarjeta_codigo = $data_abono_tarjeta[$i]['pk_tarjeta_codigo'];
                    $codigo_th = $data_abono_tarjeta[$i]['codigo_th']; //codigo tarjetahabiente
                    $producto_codigo = $data_abono_tarjeta[$i]['producto_codigo']; //codigo producto
                    $monto_abono = $data_abono_tarjeta[$i]['monto_abono']; //Monto abono
                    $pk_asotar_codigo = $data_abono_tarjeta[$i]['pk_asotar_codigo']; //codigo asociacion tarjeta llavero
                    $pk_concepto_abono = $data_abono_tarjeta[$i]['pk_concepto_abono']; //Concepto carga tarjeta
                    $monto_comision = $data_abono_tarjeta[$i]['valor_comision']; //Concepto carga tarjeta
                    $porc_comision = $data_abono_tarjeta[$i]['porc_comision']; //Concepto carga tarjeta
//                    $par_abono  = $this->session->userdata("valor_abono"); /* $array['valor_abono']; */
//                    var_dump("pk_tarjeta_codigo=" . $pk_tarjeta_codigo .
//                            "--codigo_th=" . $codigo_th .
//                            "-producto_codigo=" . $producto_codigo .
//                            "--monto_abono=" . $monto_abono .
//                            "--pk_asotar_codigo=" . $pk_asotar_codigo .
//                            "--pk_concepto_abono=" . $pk_concepto_abono . "<br>");
                    $parmovllaverocod = $_SESSION["PAR_MOVLLAVERO_CODIGO_ABONO"];
                    log_info($this->dataLlave . 'AAB VERIFICAR_CODIGO_ABONO::: pk_tar=' . $pk_tarjeta_codigo
                            . ' ,codigo_th=' . $codigo_th
                            . ' ,Cod_producto=' . $producto_codigo
                            . ' ,monto_abono=' . $monto_abono
                            . ' ,pk_asotar_codigo=' . $pk_asotar_codigo
                            . ' ,pk_concepto=' . $pk_concepto_abono
                            . ' ,monto_comision=' . $monto_comision
                            . ' ,porcentaje_comision=' . $porc_comision
                            . ' ,parmovllaverocod= ' . $parmovllaverocod
                    );

                    $sqldatostarjeta = $this->db->query("SELECT PK_TARTBLCUENTA_CODIGO ,IDENTIFICADOR FROM MODTARHAB.TARTBLTARJET WHERE PK_TARJET_CODIGO= $pk_tarjeta_codigo");
                    $datostarjeta = $sqldatostarjeta->result_array[0];
                    $pk_tartblcuenta_codigo = $datostarjeta['PK_TARTBLCUENTA_CODIGO']; //codigo cuenta de la tarjeta
                    $identificadortarjeta = $datostarjeta['IDENTIFICADOR'];

//                    var_dump("pk_tartblcuenta_codigo=" . $pk_tartblcuenta_codigo . "--identificadortarjeta" . $identificadortarjeta . "<br>");
//
//                    var_dump("Valor parametro=" . $parametro);
//                    var_dump("Valor par_cantidad=" . $par_cantidad);
//                    var_dump("Valor par_abono=" . $par_abono);
//                    var_dump("Valor PK_TIPPAR_PARCODIGO=" . $PK_TIPPAR_PARCODIGO);
//                    var_dump("Valor pk_producto_codigo=" . $pk_producto_codigo);
//                    var_dump("Valor par_new_proceso=" . $par_new_proceso);
//prcagregarparametrosycostos
                    $sql = "BEGIN MODLLAVEMAESTRA.LLAVMAEPKGGENERAL.prcabonotarjetallavemaestra(
                        parentcodigo =>:parentcodigo,
                        parentth=>:parentth,
                        parproductocodigo =>:parproductocodigo,
                        parpktarjetacuenta=>:parpktarjetacuenta,
                        partaridentificador=>:partaridentificador,
                        parvalorabono =>:parvalorabono,
                        parusuariocreacion=>:parusuariocreacion,
                        parpkasotarcodigo=>:parpkasotarcodigo,
                        parpkconceptocodigo =>:parpkconceptocodigo,
                        parmovllaverocodigo =>:parmovllaverocodigo,
                        parporccomision =>:parporccomision,
                        parmontocomision =>:parmontocomision,
                        parmensajerespuesta=>:parmensajerespuesta,
                        parrespuesta=>:parrespuesta);
                        END;";
                    $conn = $this->db->conn_id;
                    $stmt = oci_parse($conn, $sql);
                    oci_bind_by_name($stmt, ':parentcodigo', $pk_ent_codigo, 32);
//                        $parentth = $value['CODTH']; //codigo tarjetahabiente
                    oci_bind_by_name($stmt, ':parentth', $codigo_th, 32);
//                        $parproductocodigo = $value['CODPROD']; //codigo producto
                    oci_bind_by_name($stmt, ":parproductocodigo", $producto_codigo, 32);
                    oci_bind_by_name($stmt, ":parpktarjetacuenta", $pk_tartblcuenta_codigo, 32);
                    oci_bind_by_name($stmt, ':partaridentificador', $identificadortarjeta, 32);
//                        $parvalorabono = $value['MONTO']; //Monto abono
                    oci_bind_by_name($stmt, ":parvalorabono", $monto_abono, 32);
                    oci_bind_by_name($stmt, ":parusuariocreacion", $usuariocreacion, 32);
//                        $parpk_asotar_codigo = $value['PKTAR']; //codigo asociacion tarjeta llavero
                    oci_bind_by_name($stmt, ':parpkasotarcodigo', $pk_asotar_codigo, 32);
//                        $parpkconcepto_abono = $value['CONCEPTO']; //codigo asociacion tarjeta llavero
                    oci_bind_by_name($stmt, ':parpkconceptocodigo', $pk_concepto_abono, 32);
                    oci_bind_by_name($stmt, ':parmovllaverocodigo', $parmovllaverocod, 32);
                    oci_bind_by_name($stmt, ':parporccomision', $porc_comision, 32);
                    oci_bind_by_name($stmt, ':parmontocomision', $monto_comision, 32);
                    oci_bind_by_name($stmt, ':parmensajerespuesta', $parmensajerespuesta, 4000);
                    oci_bind_by_name($stmt, ':parrespuesta', $parrespuesta, 32);
                    if (!oci_execute($stmt)) {
                        $e = oci_error($stmt);
//                        VAR_DUMP($e);
                        log_info($this->errorLlave . ' Error abonando tarjeta ' . $e['message'] . ' PARRESPUESTA:' . $parrespuesta);
                    }

                    if ($parrespuesta == 1) {
//                        var_dump($parmensajerespuesta);
                        $abonosuccess++;
                    } else {
//                        var_dump($parmensajerespuesta);
                        log_info($this->errorLlave . ' Error abonando tarjeta ' . $e['message'] . ' PARRESPUESTA:' . $parrespuesta);
                        redirect("/portal/llaveMaestra/abono?errorAbono&product=$producto_codigo");
                    }
                }
                if ($abonosuccess == count($data_abono_tarjeta)) {
                    /* $sql = "BEGIN MODLLAVEMAESTRA.LLAVMAEPKGGENERAL.prcupdatesaldollaveromovabono(
                      parpkllaverocodigo =>:parpkllaverocodigo,
                      parpkmovllaverocodigo=>:parpkmovllaverocodigo,
                      parpinconfirmacion=>:parpinconfirmacion,
                      paresabono=>:paresabono,
                      parmensajerespuesta =>:parmensajerespuesta,
                      parrespuesta=>:parrespuesta);
                      END;";
                      $conn = $this->db->conn_id;
                      $stmt = oci_parse($conn, $sql);
                      $curs = oci_new_cursor($conn);
                      $paresabono = 1;
                      $par_pk_llavero_codigo = $_SESSION['PK_COD_LLAVERO']; //$this->session->userdata('PK_COD_LLAVERO');
                      $parmovllaverocod = $_SESSION["PAR_MOVLLAVERO_CODIGO_ABONO"]; //$this->session->userdata('PAR_MOVLLAVERO_CODIGO_ABONO');
                      log_info($this->dataLlave . ' par_pk_llavero_codigo= ' . $par_pk_llavero_codigo
                      . ' parmovllaverocod= ' . $parmovllaverocod
                      . ' codConfirmacion= ' . $codconfpost);
                      oci_bind_by_name($stmt, ':parpkllaverocodigo', $par_pk_llavero_codigo, 32);
                      oci_bind_by_name($stmt, ':parpkmovllaverocodigo', $parmovllaverocod, 32);
                      oci_bind_by_name($stmt, ':parpinconfirmacion', $codconfpost, 32);
                      oci_bind_by_name($stmt, ':paresabono', $paresabono, 32);
                      $parmensajerespuesta = '';
                      $parrespuesta = '';
                      oci_bind_by_name($stmt, ":parmensajerespuesta", $parmensajerespuesta, 200);
                      oci_bind_by_name($stmt, ':parrespuesta', $parrespuesta, 32);
                      if (!oci_execute($stmt)) {
                      $e = oci_error($stmt);
                      //VAR_DUMP($e);
                      log_info($this->errorLlave . ' Error abonando tarjeta: ' . $e['message'] . ' PARMENSJAERESPUESTA: ' . $parmensajerespuesta);
                      } */

                    if ($parrespuesta == 1) {
                        unset($_SESSION["pk_llavero_codigo"]);
                        unset($_SESSION["saldo_llavero"]);
                        unset($_SESSION["datatarjabono"]);
                        unset($_SESSION['CORREO_DES_ABONO']);
                        unset($_SESSION["PAR_MOVLLAVERO_CODIGO_ABONO"]);
                        unset($_SESSION['PK_COD_LLAVERO']);
                        //unset($this->session->userdata['pk_llavero_codigo']);
                        //unset($this->session->userdata['saldollavero']);
                        unset($_SESSION['pk_llavero_codigo']);
                        unset($_SESSION['saldollavero']);
                        redirect("/portal/llaveMaestra/abono?abosuccessful");
                    } else {
//                        var_dump($parmensajerespuesta);
                        log_info($this->errorLlave . ' ::::prcupdatesaldollaveromovabono::::: Error actualizando saldo llavero' . $parmensajerespuesta);
                        redirect("/portal/llaveMaestra/abono?errorUpdateLlavero");
                    }
                }
//                var_dump("Cantidad insert==" . $abonosuccess);
            } else {
                redirect("/portal/llaveMaestra/abono?abonoOK&cre=200&error");
            }
        }
    }
    //cargue masivotarjetas plantilla
     public function verificar_codigo_masivoplant() {

        //$empresa = $this->session->userdata("entidad");
        $empresa = $_SESSION['entidad'];
        $pk_ent_codigo = $empresa['PK_ENT_CODIGO'];
        //$usuario = $this->session->userdata("usuario");
        $usuario = $_SESSION['usuario'];
        $usuariocreacion = $usuario['USUARIO_ACCESO'];
        $post = $this->input->post();
        if ($post) {
            $abonosuccess = 0;
            $codconfpost = $post['codigoconfirmacion'];
            $parcod_movllavero = $_SESSION["PAR_MOVLLAVERO_CODIGO_ABONO"]; //$this->session->userdata('PAR_MOVLLAVERO_CODIGO_ABONO');
            $sql = "BEGIN MODLLAVEMAESTRA.LLAVMAEPKGGENERAL.returncodigoverificacio(
                            parpkmovllaverocodigo =>:parpkmovllaverocodigo,
                            parpinconfirmacion =>:parpinconfirmacion,
                            parmensajerespuesta =>:parmensajerespuesta,
                            parrespuesta =>:parrespuesta);
                            END;";

            $conn = $this->db->conn_id;
            $stmt = oci_parse($conn, $sql);
            oci_bind_by_name($stmt, ':parpkmovllaverocodigo', $parcod_movllavero, 32);
            oci_bind_by_name($stmt, ':parpinconfirmacion', $parpinconfirmacion, 32);
            oci_bind_by_name($stmt, ':parmensajerespuesta', $parmensajerespuesta, 500);
            oci_bind_by_name($stmt, ':parrespuesta', $parrespuesta, 32);

            if (!oci_execute($stmt)) {
                $e = oci_error($stmt);
                log_info(' ERROR PROCESANDO  ABONOS ' . $e['message']);
                redirect("/portal/llaveMaestra/abonoMasivo?abonoOK&cre=200&error");
            }

            if ($parrespuesta == 1) {
                $pinconfirmacion = $parpinconfirmacion;
            }

            if ($codconfpost == $pinconfirmacion) {

                $data_abono_tarjeta = $_SESSION['datatarjabono'];

                for ($i = 0; $i < count($data_abono_tarjeta); $i++) {
                    $pk_tarjeta_codigo = $data_abono_tarjeta[$i]['pk_tarjeta_codigo'];
                    $codigo_th = $data_abono_tarjeta[$i]['codigo_th']; //codigo tarjetahabiente
                    $producto_codigo = $data_abono_tarjeta[$i]['producto_codigo']; //codigo producto
                    $monto_abono = $data_abono_tarjeta[$i]['monto_abono']; //Monto abono
                    $pk_asotar_codigo = $data_abono_tarjeta[$i]['pk_asotar_codigo']; //codigo asociacion tarjeta llavero
                    $pk_concepto_abono = $data_abono_tarjeta[$i]['pk_concepto_abono']; //Concepto carga tarjeta
                    $monto_comision = $data_abono_tarjeta[$i]['valor_comision']; //Concepto carga tarjeta
                    $porc_comision = $data_abono_tarjeta[$i]['porc_comision']; //Concepto carga tarjeta
                    log_info($this->dataLlave . 'Masivo INTERESANTE:: pk_tar=' . $pk_tarjeta_codigo
                            . ' codigo_th=' . $codigo_th
                            . ' Cod_producto=' . $producto_codigo
                            . ' monto_abono=' . $monto_abono
                            . ' pk_asotar_codigo=' . $pk_asotar_codigo
                            . ' pk_concepto=' . $pk_concepto_abono
                            . ' monto_comision=' . $monto_comision
                            . ' porcentaje_comision=' . $porc_comision
                            . ' parmovllaverocodigo=' . $_SESSION["PAR_MOVLLAVERO_CODIGO_ABONO"]
                    );

                    $sqldatostarjeta = $this->db->query("SELECT PK_TARTBLCUENTA_CODIGO ,IDENTIFICADOR FROM MODTARHAB.TARTBLTARJET WHERE PK_TARJET_CODIGO= $pk_tarjeta_codigo");
                    $datostarjeta = $sqldatostarjeta->result_array[0];
                    $pk_tartblcuenta_codigo = $datostarjeta['PK_TARTBLCUENTA_CODIGO']; //codigo cuenta de la tarjeta
                    $identificadortarjeta = $datostarjeta['IDENTIFICADOR'];

                    $sql = "BEGIN MODLLAVEMAESTRA.LLAVMAEPKGGENERAL.prcabonotarjetallavemaestra(
                        parentcodigo =>:parentcodigo,
                        parentth=>:parentth,
                        parproductocodigo =>:parproductocodigo,
                        parpktarjetacuenta=>:parpktarjetacuenta,
                        partaridentificador=>:partaridentificador,
                        parvalorabono =>:parvalorabono,
                        parusuariocreacion=>:parusuariocreacion,
                        parpkasotarcodigo=>:parpkasotarcodigo,
                        parpkconceptocodigo =>:parpkconceptocodigo,
                        parmovllaverocodigo =>:parmovllaverocodigo,
                        parporccomision =>:parporccomision,
                        parmontocomision =>:parmontocomision,
                        parmensajerespuesta=>:parmensajerespuesta,
                        parrespuesta=>:parrespuesta);
                        END;";
                    $conn = $this->db->conn_id;
                    $stmt = oci_parse($conn, $sql);
                    oci_bind_by_name($stmt, ':parentcodigo', $pk_ent_codigo, 32);
//                        $parentth = $value['CODTH']; //codigo tarjetahabiente
                    oci_bind_by_name($stmt, ':parentth', $codigo_th, 32);
//                        $parproductocodigo = $value['CODPROD']; //codigo producto
                    oci_bind_by_name($stmt, ":parproductocodigo", $producto_codigo, 32);
                    oci_bind_by_name($stmt, ":parpktarjetacuenta", $pk_tartblcuenta_codigo, 32);
                    oci_bind_by_name($stmt, ':partaridentificador', $identificadortarjeta, 32);
//                        $parvalorabono = $value['MONTO']; //Monto abono
                    oci_bind_by_name($stmt, ":parvalorabono", $monto_abono, 32);
                    oci_bind_by_name($stmt, ":parusuariocreacion", $usuariocreacion, 32);
//                        $parpk_asotar_codigo = $value['PKTAR']; //codigo asociacion tarjeta llavero
                    oci_bind_by_name($stmt, ':parpkasotarcodigo', $pk_asotar_codigo, 32);
//                        $parpkconcepto_abono = $value['CONCEPTO']; //codigo asociacion tarjeta llavero
                    oci_bind_by_name($stmt, ':parpkconceptocodigo', $pk_concepto_abono, 32);
                    $parmovllaverocod = $_SESSION["PAR_MOVLLAVERO_CODIGO_ABONO"]; //$this->session->userdata('PAR_MOVLLAVERO_CODIGO_ABONO');
                    oci_bind_by_name($stmt, ':parmovllaverocodigo', $parmovllaverocod, 32);
                    oci_bind_by_name($stmt, ':parporccomision', $porc_comision, 32);
                    oci_bind_by_name($stmt, ':parmontocomision', $monto_comision, 32);
                    oci_bind_by_name($stmt, ':parmensajerespuesta', $parmensajerespuesta, 4000);
                    oci_bind_by_name($stmt, ':parrespuesta', $parrespuesta, 32);
                    if (!oci_execute($stmt)) {
                        $e = oci_error($stmt);
                        VAR_DUMP($e);
                        //  exit;
                    }

                    if ($parrespuesta == 1) {
//                        var_dump($parmensajerespuesta);
                        $abonosuccess++;
                    }
                }
                if ($abonosuccess == count($data_abono_tarjeta)) {
                    /*  $sql = "BEGIN MODLLAVEMAESTRA.LLAVMAEPKGGENERAL.prcupdatesaldollaveromovabono(
                      parpkllaverocodigo =>:parpkllaverocodigo,
                      parpkmovllaverocodigo=>:parpkmovllaverocodigo,
                      parpinconfirmacion=>:parpinconfirmacion,
                      paresabono=>:paresabono,
                      parmensajerespuesta =>:parmensajerespuesta,
                      parrespuesta=>:parrespuesta);
                      END;";
                      $conn = $this->db->conn_id;
                      $stmt = oci_parse($conn, $sql);
                      $curs = oci_new_cursor($conn);
                      $paresabono = 1;
                      $par_pk_llavero_codigo = $_SESSION['PK_COD_LLAVERO']; //$this->session->userdata('PK_COD_LLAVERO');
                      oci_bind_by_name($stmt, ':parpkllaverocodigo', $par_pk_llavero_codigo, 32);
                      $parmovllaverocod = $_SESSION["PAR_MOVLLAVERO_CODIGO_ABONO"]; //$this->session->userdata('PAR_MOVLLAVERO_CODIGO_ABONO');
                      oci_bind_by_name($stmt, ':parpkmovllaverocodigo', $parmovllaverocod, 32);
                      oci_bind_by_name($stmt, ':parpinconfirmacion', $codconfpost, 32);
                      oci_bind_by_name($stmt, ':paresabono', $paresabono, 32);
                      oci_bind_by_name($stmt, ":parmensajerespuesta", $parmensajerespuesta, 2000);
                      oci_bind_by_name($stmt, ':parrespuesta', $parrespuesta, 4000);
                      if (!oci_execute($stmt)) {
                      $e = oci_error($stmt);
                      VAR_DUMP($e);
                      //  exit;
                      redirect("/portal/llaveMaestra/abonoMasivo?abonoOK&cre=200&error");
                      } */

                    if ($parrespuesta == 1) {
                        unset($_SESSION["pk_llavero_codigo"]);
                        unset($_SESSION["saldo_llavero"]);
                        unset($_SESSION["datatarjabono"]);
                        unset($_SESSION['CORREO_DES_ABONO']);
                        unset($_SESSION["PAR_MOVLLAVERO_CODIGO_ABONO"]);
                        unset($_SESSION['PK_COD_LLAVERO']);
                        //unset($this->session->userdata['pk_llavero_codigo']);
                        //unset($this->session->userdata['saldollavero']);
                        unset($_SESSION['pk_llavero_codigo']);
                        unset($_SESSION['saldollavero']);
                        redirect("/portal/llaveMaestra/abonoMasivo?abosuccessful");
                    }
                }
//                var_dump("Cantidad insert==" . $abonosuccess);
            } else {
                redirect("/portal/llaveMaestra/abonoMasivo?abonoOK&cre=200&error");
            }
        }
        redirect("/portal/llaveMaestra/abonoMasivo?abosuccessful");
    }

    public function verificar_codigo_abono_masivo() {

        //$empresa = $this->session->userdata("entidad");
        $empresa = $_SESSION['entidad'];
        $pk_ent_codigo = $empresa['PK_ENT_CODIGO'];
        //$usuario = $this->session->userdata("usuario");
        $usuario = $_SESSION['usuario'];
        $usuariocreacion = $usuario['USUARIO_ACCESO'];
        $post = $this->input->post();
        if ($post) {
            $abonosuccess = 0;
            $codconfpost = $post['codigoconfirmacion'];
            $parcod_movllavero = $_SESSION["PAR_MOVLLAVERO_CODIGO_ABONO"]; //$this->session->userdata('PAR_MOVLLAVERO_CODIGO_ABONO');
            $sql = "BEGIN MODLLAVEMAESTRA.LLAVMAEPKGGENERAL.returncodigoverificacio(
                            parpkmovllaverocodigo =>:parpkmovllaverocodigo,
                            parpinconfirmacion =>:parpinconfirmacion,
                            parmensajerespuesta =>:parmensajerespuesta,
                            parrespuesta =>:parrespuesta);
                            END;";

            $conn = $this->db->conn_id;
            $stmt = oci_parse($conn, $sql);
            oci_bind_by_name($stmt, ':parpkmovllaverocodigo', $parcod_movllavero, 32);
            oci_bind_by_name($stmt, ':parpinconfirmacion', $parpinconfirmacion, 32);
            oci_bind_by_name($stmt, ':parmensajerespuesta', $parmensajerespuesta, 500);
            oci_bind_by_name($stmt, ':parrespuesta', $parrespuesta, 32);

            if (!oci_execute($stmt)) {
                $e = oci_error($stmt);
                log_info(' ERROR PROCESANDO  ABONOS ' . $e['message']);
                redirect("/portal/llaveMaestra/abonoMasivo?abonoOK&cre=200&error");
            }

            if ($parrespuesta == 1) {
                $pinconfirmacion = $parpinconfirmacion;
            }

            if ($codconfpost == $pinconfirmacion) {

                $data_abono_tarjeta = $_SESSION['datatarjabono'];

                for ($i = 0; $i < count($data_abono_tarjeta); $i++) {
                    $pk_tarjeta_codigo = $data_abono_tarjeta[$i]['pk_tarjeta_codigo'];
                    $codigo_th = $data_abono_tarjeta[$i]['codigo_th']; //codigo tarjetahabiente
                    $producto_codigo = $data_abono_tarjeta[$i]['producto_codigo']; //codigo producto
                    $monto_abono = $data_abono_tarjeta[$i]['monto_abono']; //Monto abono
                    $pk_asotar_codigo = $data_abono_tarjeta[$i]['pk_asotar_codigo']; //codigo asociacion tarjeta llavero
                    $pk_concepto_abono = $data_abono_tarjeta[$i]['pk_concepto_abono']; //Concepto carga tarjeta
                    $monto_comision = $data_abono_tarjeta[$i]['valor_comision']; //Concepto carga tarjeta
                    $porc_comision = $data_abono_tarjeta[$i]['porc_comision']; //Concepto carga tarjeta
                    log_info($this->dataLlave . 'Masivo:: pk_tar=' . $pk_tarjeta_codigo
                            . ' codigo_th=' . $codigo_th
                            . ' Cod_producto=' . $producto_codigo
                            . ' monto_abono=' . $monto_abono
                            . ' pk_asotar_codigo=' . $pk_asotar_codigo
                            . ' pk_concepto=' . $pk_concepto_abono
                            . ' monto_concepto=' . $monto_comision
                            . ' porcentaje_comision=' . $porc_comision
                    );

                    $sqldatostarjeta = $this->db->query("SELECT PK_TARTBLCUENTA_CODIGO ,IDENTIFICADOR FROM MODTARHAB.TARTBLTARJET WHERE PK_TARJET_CODIGO= $pk_tarjeta_codigo");
                    $datostarjeta = $sqldatostarjeta->result_array[0];
                    $pk_tartblcuenta_codigo = $datostarjeta['PK_TARTBLCUENTA_CODIGO']; //codigo cuenta de la tarjeta
                    $identificadortarjeta = $datostarjeta['IDENTIFICADOR'];

                    $sql = "BEGIN MODLLAVEMAESTRA.LLAVMAEPKGGENERAL.prcabonotarjetallavemaestra(
                        parentcodigo =>:parentcodigo,
                        parentth=>:parentth,
                        parproductocodigo =>:parproductocodigo,
                        parpktarjetacuenta=>:parpktarjetacuenta,
                        partaridentificador=>:partaridentificador,
                        parvalorabono =>:parvalorabono,
                        parusuariocreacion=>:parusuariocreacion,
                        parpkasotarcodigo=>:parpkasotarcodigo,
                        parpkconceptocodigo =>:parpkconceptocodigo,
                        parmovllaverocodigo =>:parmovllaverocodigo,
                        parporccomision =>:parporccomision,
                        parmontocomision =>:parmontocomision,
                        parmensajerespuesta=>:parmensajerespuesta,
                        parrespuesta=>:parrespuesta);
                        END;";
                    $conn = $this->db->conn_id;
                    $stmt = oci_parse($conn, $sql);
                    oci_bind_by_name($stmt, ':parentcodigo', $pk_ent_codigo, 32);
//                        $parentth = $value['CODTH']; //codigo tarjetahabiente
                    oci_bind_by_name($stmt, ':parentth', $codigo_th, 32);
//                        $parproductocodigo = $value['CODPROD']; //codigo producto
                    oci_bind_by_name($stmt, ":parproductocodigo", $producto_codigo, 32);
                    oci_bind_by_name($stmt, ":parpktarjetacuenta", $pk_tartblcuenta_codigo, 32);
                    oci_bind_by_name($stmt, ':partaridentificador', $identificadortarjeta, 32);
//                        $parvalorabono = $value['MONTO']; //Monto abono
                    oci_bind_by_name($stmt, ":parvalorabono", $monto_abono, 32);
                    oci_bind_by_name($stmt, ":parusuariocreacion", $usuariocreacion, 32);
//                        $parpk_asotar_codigo = $value['PKTAR']; //codigo asociacion tarjeta llavero
                    oci_bind_by_name($stmt, ':parpkasotarcodigo', $pk_asotar_codigo, 32);
//                        $parpkconcepto_abono = $value['CONCEPTO']; //codigo asociacion tarjeta llavero
                    oci_bind_by_name($stmt, ':parpkconceptocodigo', $pk_concepto_abono, 32);
                    $parmovllaverocod = $_SESSION["PAR_MOVLLAVERO_CODIGO_ABONO"]; //$this->session->userdata('PAR_MOVLLAVERO_CODIGO_ABONO');
                    oci_bind_by_name($stmt, ':parmovllaverocodigo', $parmovllaverocod, 32);
                    oci_bind_by_name($stmt, ':parporccomision', $porc_comision, 32);
                    oci_bind_by_name($stmt, ':parmontocomision', $monto_comision, 32);
                    oci_bind_by_name($stmt, ':parmensajerespuesta', $parmensajerespuesta, 4000);
                    oci_bind_by_name($stmt, ':parrespuesta', $parrespuesta, 32);
                    if (!oci_execute($stmt)) {
                        $e = oci_error($stmt);
                        VAR_DUMP($e);
                        //  exit;
                    }

                    if ($parrespuesta == 1) {
//                        var_dump($parmensajerespuesta);
                        $abonosuccess++;
                    }
                }
                if ($abonosuccess == count($data_abono_tarjeta)) {
                    /*  $sql = "BEGIN MODLLAVEMAESTRA.LLAVMAEPKGGENERAL.prcupdatesaldollaveromovabono(
                      parpkllaverocodigo =>:parpkllaverocodigo,
                      parpkmovllaverocodigo=>:parpkmovllaverocodigo,
                      parpinconfirmacion=>:parpinconfirmacion,
                      paresabono=>:paresabono,
                      parmensajerespuesta =>:parmensajerespuesta,
                      parrespuesta=>:parrespuesta);
                      END;";
                      $conn = $this->db->conn_id;
                      $stmt = oci_parse($conn, $sql);
                      $curs = oci_new_cursor($conn);
                      $paresabono = 1;
                      $par_pk_llavero_codigo = $_SESSION['PK_COD_LLAVERO']; //$this->session->userdata('PK_COD_LLAVERO');
                      oci_bind_by_name($stmt, ':parpkllaverocodigo', $par_pk_llavero_codigo, 32);
                      $parmovllaverocod = $_SESSION["PAR_MOVLLAVERO_CODIGO_ABONO"]; //$this->session->userdata('PAR_MOVLLAVERO_CODIGO_ABONO');
                      oci_bind_by_name($stmt, ':parpkmovllaverocodigo', $parmovllaverocod, 32);
                      oci_bind_by_name($stmt, ':parpinconfirmacion', $codconfpost, 32);
                      oci_bind_by_name($stmt, ':paresabono', $paresabono, 32);
                      oci_bind_by_name($stmt, ":parmensajerespuesta", $parmensajerespuesta, 2000);
                      oci_bind_by_name($stmt, ':parrespuesta', $parrespuesta, 4000);
                      if (!oci_execute($stmt)) {
                      $e = oci_error($stmt);
                      VAR_DUMP($e);
                      //  exit;
                      redirect("/portal/llaveMaestra/abonoMasivo?abonoOK&cre=200&error");
                      } */

                    if ($parrespuesta == 1) {
                        unset($_SESSION["pk_llavero_codigo"]);
                        unset($_SESSION["saldo_llavero"]);
                        unset($_SESSION["datatarjabono"]);
                        unset($_SESSION['CORREO_DES_ABONO']);
                        unset($_SESSION["PAR_MOVLLAVERO_CODIGO_ABONO"]);
                        unset($_SESSION['PK_COD_LLAVERO']);
                        //unset($this->session->userdata['pk_llavero_codigo']);
                        //unset($this->session->userdata['saldollavero']);
                        unset($_SESSION['pk_llavero_codigo']);
                        unset($_SESSION['saldollavero']);
                        redirect("/portal/llaveMaestra/abonoMasivo?abosuccessful");
                    }
                }
//                var_dump("Cantidad insert==" . $abonosuccess);
            } else {
                redirect("/portal/llaveMaestra/abonoMasivo?abonoOK&cre=200&error");
            }
        }
        redirect("/portal/llaveMaestra/abonoMasivo?abosuccessful");
    }

    public function verificar_codigo_devolucion() {
        $post = $this->input->post();
        if ($post) {
            $codconfpost = $post['codigoconfirmacion'];
            $parcod_movllavero = $_SESSION["COD_MOV_LLAVERO_DEV"]; //$this->session->userdata('COD_MOV_LLAVERO_DEV');
            $sql = "BEGIN MODLLAVEMAESTRA.LLAVMAEPKGGENERAL.returncodigoverificacio(
                            parpkmovllaverocodigo =>:parpkmovllaverocodigo,
                            parpinconfirmacion =>:parpinconfirmacion,
                            parmensajerespuesta =>:parmensajerespuesta,
                            parrespuesta =>:parrespuesta);
                            END;";

            $conn = $this->db->conn_id;
            $stmt = oci_parse($conn, $sql);
            oci_bind_by_name($stmt, ':parpkmovllaverocodigo', $parcod_movllavero, 32);
            oci_bind_by_name($stmt, ':parpinconfirmacion', $parpinconfirmacion, 32);
            oci_bind_by_name($stmt, ':parmensajerespuesta', $parmensajerespuesta, 500);
            oci_bind_by_name($stmt, ':parrespuesta', $parrespuesta, 32);

            if (!oci_execute($stmt)) {
                $e = oci_error($stmt);
                VAR_DUMP($e);
                exit;
            }
            if ($parrespuesta == 1) {
                $pinconfirmacion = $parpinconfirmacion;
            }
            if ($codconfpost == $pinconfirmacion) {
                $sql = "BEGIN MODLLAVEMAESTRA.LLAVMAEPKGGENERAL.prcupdatesaldosmovllavellavero(
                            parpkllaverocodigo =>:parpkllaverocodigo,
                            parpkllavemaestra =>:parpkllavemaestra,
                            parpkmovllave =>:parpkmovllave,
                            parpkmovllavero =>:parpkmovllavero,
                            parrespuesta=>:parrespuesta);
                            END;";

                $conn = $this->db->conn_id;
                $stmt = oci_parse($conn, $sql);
                $parpk_llaverocodigo = $_SESSION["PK_COD_LLAVERO"]; //$this->session->userdata('PK_COD_LLAVERO');
                oci_bind_by_name($stmt, ':parpkllaverocodigo', $parpk_llaverocodigo, 32);
                //$pk_llavemae = $this->session->userdata("PK_LLAVE_MAESTRA");
                $pk_llavemae = $_SESSION['PK_LLAVE_MAESTRA'];
                oci_bind_by_name($stmt, ':parpkllavemaestra', $pk_llavemae, 32);
                $parcodigo_movllave = $_SESSION["COD_MOV_LLAVE_DEV"]; //$this->session->userdata('COD_MOV_LLAVE_DEV');
                oci_bind_by_name($stmt, ':parpkmovllave', $parcodigo_movllave, 32);
                $parcodigo_movllavero = $_SESSION["COD_MOV_LLAVERO_DEV"]; //$this->session->userdata('COD_MOV_LLAVERO_DEV');
                oci_bind_by_name($stmt, ':parpkmovllavero', $parcodigo_movllavero, 32);
                oci_bind_by_name($stmt, ':parrespuesta', $parrespuesta, 32);

                if (!oci_execute($stmt)) {
                    $e = oci_error($stmt);
                    VAR_DUMP($e);
                    exit;
                }
                if ($parrespuesta == 1) {
                    unset($_SESSION["CORREO_DES_DEVO_LLAVERO"]);
                    unset($_SESSION["COD_MOV_LLAVE_DEV"]);
                    unset($_SESSION["COD_MOV_LLAVERO_DEV"]);
                    unset($_SESSION["PK_COD_LLAVERO"]);
                    redirect("/portal/llaveMaestra/devolucion?devosuccessful");
                }
            } else {
                redirect("/portal/llaveMaestra/devolucion?devolucionOK&cre=200&error");
            }
        }
    }

    public function verificar_codigo_reverso() {
        //var_dump($this->session->userdata);
        $post = $this->input->post();
        if ($post) {
            $reversosOK = 0;
            $codconfpost = $post['codigoconfirmacion'];
            $parcod_movllavero = $_SESSION["PAR_MOVLLAVERO_CODIGO_REV"]; //$this->session->userdata('PAR_MOVLLAVERO_CODIGO_REV');
            $sql = "BEGIN MODLLAVEMAESTRA.LLAVMAEPKGGENERAL.returncodigoverificacio(
                            parpkmovllaverocodigo =>:parpkmovllaverocodigo,
                            parpinconfirmacion =>:parpinconfirmacion,
                            parmensajerespuesta =>:parmensajerespuesta,
                            parrespuesta =>:parrespuesta);
                            END;";

            $conn = $this->db->conn_id;
            $stmt = oci_parse($conn, $sql);
            oci_bind_by_name($stmt, ':parpkmovllaverocodigo', $parcod_movllavero, 32);
            oci_bind_by_name($stmt, ':parpinconfirmacion', $parpinconfirmacion, 32);
            oci_bind_by_name($stmt, ':parmensajerespuesta', $parmensajerespuesta, 500);
            oci_bind_by_name($stmt, ':parrespuesta', $parrespuesta, 32);

            if (!oci_execute($stmt)) {
                $e = oci_error($stmt);
                VAR_DUMP($e);
                exit;
            }
            if ($parrespuesta == 1) {
                $pinconfirmacion = $parpinconfirmacion;
            }
            if ($codconfpost == $pinconfirmacion) {

                $data_reverso_tarjeta = $_SESSION['datatarjreverso'];

                foreach ($data_reverso_tarjeta as $key => $value) {

                    $pk_tarjeta_codigo_reverso = $value['pk_tarjeta_codigo_reverso']; //codigo tarjeta
                    $codigo_th_reverso = $value['codigo_th_reverso']; //codigo tarjetahabiente
                    $producto_codigo_reverso = $value['producto_codigo_reverso']; //codigo producto
                    $monto_reverso = $value['monto_reverso']; //Monto reverso
                    $pk_abotar_codigo_reverso = $value['pk_abotar_codigo_reverso']; //pk_abotar_codigo
                    $pk_asotar_codigo_reverso = $value['pk_asotar_codigo_reverso']; //pk_asotar_codigo

                    $sqldatostarjeta = $this->db->query("SELECT ID_EMPRESA  FROM MODTARHAB.TARTBLTARJET WHERE PK_TARJET_CODIGO= $pk_tarjeta_codigo_reverso");
                    $datostarjeta = $sqldatostarjeta->result_array[0];
                    $parnumeroTar = $datostarjeta['ID_EMPRESA']; // identificador de la tarjeta
//                    $parnumeroTar = $value['numero_tarjeta_reverso']; //numero tarjeta de la tarjeta
                    //$usuario = $this->session->userdata("usuario");
                    $usuario = $_SESSION['usuario'];
                    $usuariocreacion = $usuario['USUARIO_ACCESO'];
                    //$empresa = $this->session->userdata("entidad");
                    $empresa = $_SESSION['entidad'];
                    $parpk_ent_codigo = $empresa['PK_ENT_CODIGO'];
                    $saldo_act_tarjeta = $this->returnsaldotarjetaid($pk_tarjeta_codigo_reverso);
                    $saldo_nuevo_tarjeta = $saldo_act_tarjeta - $monto_reverso;

                    $sql = " BEGIN MODLLAVEMAESTRA.LLAVMAEPKGGENERAL.prcreversollavemaestra( 
                                                             parPKEMPRESA=>:parPKEMPRESA ,
                                                             parPKPRODUCTO=>:parPKPRODUCTO,
                                                             parPK_ENTIDA_TH=>:parPK_ENTIDA_TH,
                                                             parPARVALOR=>:parPARVALOR,
                                                             parPARIDENTARJETA=>:parPARIDENTARJETA,
                                                             parPK_TARJET_CODIGO=>:parPK_TARJET_CODIGO,
                                                             parpkasotarcodigo=>:parpkasotarcodigo,
                                                             parmovllaverocodigo=>:parmovllaverocodigo,
                                                             parsaldotarjantiguo=>:parsaldotarjantiguo,
                                                             parsaldotarjnuevo=>:parsaldotarjnuevo,
                                                             parusuariocreacion=>:parusuariocreacion,
                                                             parmensajerespuesta=>:parmensajerespuesta,
                                                             parrespuesta=>:parrespuesta);           
                     END;";

                    $conn = $this->db->conn_id;
                    $stmt = oci_parse($conn, $sql);
                    oci_bind_by_name($stmt, ':parPKEMPRESA', $parpk_ent_codigo, 32);
                    oci_bind_by_name($stmt, ':parPKPRODUCTO', $producto_codigo_reverso, 32);
                    oci_bind_by_name($stmt, ':parPK_ENTIDA_TH', $codigo_th_reverso, 32);
                    oci_bind_by_name($stmt, ':parPARVALOR', $monto_reverso, 32);
                    oci_bind_by_name($stmt, ':parPARIDENTARJETA', $parnumeroTar, 32);
                    oci_bind_by_name($stmt, ':parPK_TARJET_CODIGO', $pk_tarjeta_codigo_reverso, 32);
                    oci_bind_by_name($stmt, ':parpkasotarcodigo', $pk_asotar_codigo_reverso, 32);
                    oci_bind_by_name($stmt, ':parmovllaverocodigo', $parcod_movllavero, 32);
                    oci_bind_by_name($stmt, ':parsaldotarjantiguo', $saldo_act_tarjeta, 32);
                    oci_bind_by_name($stmt, ':parsaldotarjnuevo', $saldo_nuevo_tarjeta, 32);
                    oci_bind_by_name($stmt, ':parusuariocreacion', $usuariocreacion, 32);
                    oci_bind_by_name($stmt, ':parmensajerespuesta', $parmensajerespuesta, 2000);
                    oci_bind_by_name($stmt, ':parrespuesta', $parrespuesta, 32);
                    if (!oci_execute($stmt)) {
                        $e = oci_error($stmt);
                        VAR_DUMP($e);
//exit;
                    } else if ($parrespuesta == 1) {
                        $reversosOK++;
                    } else {
                        $PARRESPUE = 'ERROR ' . $parrespuesta . 'MENSAERROR ' . $parmensajerespuesta;
                        log_info($this->errorLlave . '::::ERROR prcreversollavemaestra = ' . $PARRESPUE);

                        redirect("/portal/llaveMaestra/reverso?error_rev_tar");
                    }
                }
                // if ($reversosOK == count($data_reverso_tarjeta)) {
//Si realiza en reverso se llama procedimiento realizar abono llavero con nuevo saldo
                /* $sql = "BEGIN MODLLAVEMAESTRA.LLAVMAEPKGGENERAL.prcupdatesaldollaveromovabono(
                  parpkllaverocodigo =>:parpkllaverocodigo,
                  parpkmovllaverocodigo=>:parpkmovllaverocodigo,
                  parpinconfirmacion=>:parpinconfirmacion,
                  parmensajerespuesta =>:parmensajerespuesta,
                  paresabono=>:paresabono,
                  parrespuesta=>:parrespuesta);
                  END;";
                  $conn = $this->db->conn_id;
                  $stmt = oci_parse($conn, $sql);
                  $curs = oci_new_cursor($conn);
                  $paresabono = 0;
                  $par_pk_llavero_codigo = $_SESSION["PK_LLAVERO_DES_REVERSO"];
                  oci_bind_by_name($stmt, ':parpkllaverocodigo', $par_pk_llavero_codigo, 32);
                  oci_bind_by_name($stmt, ':parpkmovllaverocodigo', $parcod_movllavero, 32);
                  oci_bind_by_name($stmt, ':parpinconfirmacion', $pinconfirmacion, 32);
                  oci_bind_by_name($stmt, ':paresabono', $paresabono, 32);
                  oci_bind_by_name($stmt, ":parmensajerespuesta", $parmensajerespuesta, 200);
                  oci_bind_by_name($stmt, ':parrespuesta', $parrespuesta, 32);
                  if (!oci_execute($stmt)) {
                  $e = oci_error($stmt);
                  VAR_DUMP($e);
                  exit;
                  } */

                if ($parrespuesta == 1) {
                    unset($_SESSION["PK_LLAVERO_DES_REVERSO"]);
                    unset($_SESSION["PAR_MOVLLAVERO_CODIGO_REV"]);
                    unset($_SESSION["CORREO_DES_REVERSO"]);
                    redirect("/portal/llaveMaestra/reverso?revsuccessful");
                } else {
                    $error = 'ERROR' . $parrespuesta . 'MENSAJE' . $parmensajerespuesta;
                    log_info($this->errorLlave . '::::ERROR prcupdatesaldollaveromovabono = ' . $error);

                    redirect("/portal/llaveMaestra/reverso?error_rev_tar");
                }
                //}
            } else {
                redirect("/portal/llaveMaestra/reverso?revOK&rev=200&error");
            }
        }
    }

    public function returnabonosllavero() {
        $this->verificarllaveMestraNuevosPerfiles();
        //$empresa = $this->session->userdata("entidad");
        $empresa = $_SESSION['entidad'];
        $post = $this->input->post();
        if ($post) {
            if ($post['llavero'] != '') {
                $pk_llavero = $post['llavero'];
                $data['pk_llavero_codigo'] = $pk_llavero;
                $sqlabonollavero = $this->db->query("select vista2.*, abot.abotar_codigo,abot.monto_abono,abot.fecha_creacion FECHA_ABONO,conceptomov.nombre nom_concepto from (select vista.nomtar, vista.abr, vista.doc, vista.NOMPRO, vista.CODPROD, vista.identificador, vista.codth, vista.NUMTAR,vista.PK_TARJET_CODIGO, asot.asotar_codigo PKTAR,vista.nomcustodio,vista.nomcampana from (SELECT ent.documento DOC,
                                        ent.pk_ent_codigo CODTH,
                                        CAM.nombre nomcampana,
                                        tipdoc.abreviacion abr, 
                                        NVL(TO_CHAR(tar.fecha_creacion,'DD/MM/YYYY'),'PENDIENTE') FEC,
                                        ent.nombre ||' '||ent.apellido nomtar, TAR.NUMERO NUMTAR,TAR.PK_TARJET_CODIGO,
                                        PRO.NOMBRE_PRODUCTO NOMPRO,
                                        pro.pk_produc_codigo codprod,
                                        tar.pk_tarjet_codigo codtar,
                                        NVL(tar.identificador,'-') IDENTIFICADOR,
                                        ENTCUS.nombre ||' '||ENTCUS.apellido nomcustodio
                                        FROM MODTARHAB.tartbltarjet tar 
                                        join MODTARHAB.TARTBLCUENTA CUE 
                                        ON cue.pk_tartblcuenta_codigo = tar.pk_tartblcuenta_codigo 
                                        AND cue.PK_ENT_CODIGO_EMP = {$empresa['PK_ENT_CODIGO']}
                                        JOIN MODCLIUNI.CLITBLENTIDA ENT 
                                        ON ent.pk_ent_codigo = cue.pk_ent_codigo_th 
                                        JOIN MODCLIUNI.CLITBLTIPDOC TIPDOC 
                                        ON tipdoc.pk_td_codigo = ent.clitbltipdoc_pk_td_codigo 
                                        JOIN MODPRODUC.PROTBLPRODUC PRO 
                                        ON pro.pk_produc_codigo = cue.pk_produc_codigo 
                                        JOIN MODALISTA.ALITBLDETPED DETPED 
                                        ON detped.pk_detped_codigo = tar.pk_detped_codigo 
                                        JOIN MODALISTA.ALITBLPEDIDO PED ON ped.pk_pedido_codigo = detped.pk_pedido 
                                        JOIN MODCLIUNI.CLITBLENTIDA ENTCUS ON entcus.pk_ent_codigo = ped.pk_custodio
                                        JOIN MODCLIUNI.CLITBLCAMPAN CAM ON cam.pk_campan_codigo = ped.pk_campan_codigo 
                                        LEFT JOIN MODPROPAG.PPATBLDETORD DETORD ON detord.pk_pedido = detped.pk_detped_Codigo
                                        left JOIN MODFACTUR.FACTBLFACORD FACORD ON facord.pk_ordcom_codigo=detord.pk_orden_compra
                                        left JOIN MODPROPAG.PPATBLORDCOM ORDCOM ON facord.pk_ordcom_codigo=ordcom.pk_ordcom_codigo
                                        LEFT JOIN MODFACTUR.FACTBLFACTUR factur ON facord.pk_factur_codigo=factur.pk_factur_codigo 
                                        JOIN MODTARHAB.tartblesttar ESTTAR 
                                        ON esttar.pk_esttar_codigo = tar.pk_esttar_codigo ) vista 
                                        left join MODLLAVEMAESTRA.llavetblasotar asot on vista.codprod = asot.pk_produc_codigo and asot.pk_llavero_codigo = $pk_llavero
                                        and vista.codth = asot.pk_ent_codigo and vista.codtar = asot.pk_tarjeta_codigo where asot.pk_ent_codigo IS NOT NULL
                                        and asot.pk_produc_codigo IS NOT NULL
                                        order by vista.NOMTAR asc) vista2
                                        left join modllaveMAESTRA.LLAVETBLABOTAR abot ON vista2.pktar = abot.pk_asotar_codigo 
                                        JOIN MODLLAVEMAESTRA.llavetblmovllavero movllavero ON abot.pk_movllavero_codigo=movllavero.movllavero_codigo
                                        join MODLLAVEMAESTRA.llavetblconceptomov  conceptomov ON abot.pk_concepto_codigo = conceptomov.pk_concepto_codigo
                                        where abot.abotar_codigo IS NOT NULL AND movllavero.estmov_codigo=1 order by abot.fecha_creacion DESC");
                $data['abonollaveros'] = $sqlabonollavero->result_array;

                $llaveros = $this->returnarrayllaveros();
                foreach ($llaveros as $value) {
                    if ($value['PK_LLAVERO_CODIGO'] == $pk_llavero) {
                        $nombrellaveroselect = $value['NOMBRE_LLAVERO'];
                    }
                }
                $data['nombrellaveroselect'] = $nombrellaveroselect;
            }
        }

        $data['saldo'] = $this->saldollavemaestra();
        $llaveros = $this->returnarrayllaveros();
        $data['llaveros'] = $llaveros;
        //$empresa = $this->session->userdata("entidad");
        $empresa = $_SESSION['entidad'];
        $data['empresa'] = $empresa['NOMBREEMPRESA'];
        //$usuario = $this->session->userdata("usuario");
        $usuario = $_SESSION['usuario'];
        $data['usuario'] = $usuario['NOMBRE'] . " " . $usuario['APELLIDO'];
        //$ultimaconexion = $this->session->userdata("ultimaconexion");
        $ultimaconexion = $_SESSION['ultimaconexion'];
        $data['ultimaconexion'] = $ultimaconexion;
        $data['menu'] = "estado";
        $this->load->view('portal/templates/header2llave', $data);
        $this->load->view('portal/llave/informeAbonos', $data);
        $this->load->view('portal/templates/footer', $data);
    }

//solicita devolucion a tarjetas no corporativas 
    public function returnAbonosLegalizacion() {
        $this->verificarllaveMestraNuevosPerfiles();
        //$empresa = $this->session->userdata("entidad");
        $empresa = $_SESSION['entidad'];
        $post = $this->input->post();
        if ($post) {
            if ($post['pk_llavero'] != '') {
                $pk_llavero = $post['pk_llavero'];
                $data['pk_llavero_codigo'] = $pk_llavero;
                $sqlabonollavero = $this->db->query(" select vista.nomtar, vista.abr, vista.doc, vista.NOMPRO, vista.CODPROD, vista.codth, vista.NUMTAR,vista.id_empresa,vista.PK_TARJET_CODIGO,vista.ciudad,abotar.monto_abono,abotar.abotar_codigo, asot.asotar_codigo PKTAR,llavero.nombre_llavero,llavero.llavero_codigo pk_llavero,vista.nomcustodio from (SELECT ent.documento DOC,
                                        ent.pk_ent_codigo CODTH,CIUD.nombre ciudad,
                                        tipdoc.abreviacion abr, 
                                        NVL(TO_CHAR(tar.fecha_creacion,'DD/MM/YYYY'),'PENDIENTE') FEC,
                                        ent.nombre ||' '||ent.apellido nomtar, TAR.NUMERO NUMTAR,TAR.PK_TARJET_CODIGO,
                                        PRO.NOMBRE_PRODUCTO NOMPRO,
                                        pro.pk_produc_codigo codprod,
                                        tar.pk_tarjet_codigo codtar,ENTCUS.nombre ||' '||ENTCUS.apellido nomcustodio,
                                        tar.id_empresa
                                        ,tar.identificador IDENTIFICADOR
                                        FROM MODTARHAB.tartbltarjet tar 
                                        join MODTARHAB.TARTBLCUENTA CUE 
                                        ON cue.pk_tartblcuenta_codigo = tar.pk_tartblcuenta_codigo 
                                        AND cue.PK_ENT_CODIGO_EMP = {$empresa['PK_ENT_CODIGO']}
                                        JOIN MODCLIUNI.CLITBLENTIDA ENT 
                                        ON ent.pk_ent_codigo = cue.pk_ent_codigo_th 
                                        JOIN MODCLIUNI.CLITBLCIUDAD CIUD ON ent.CLITBLCIUDAD_PK_CIU_CODIGO = CIUD.PK_CIU_CODIGO
                                        JOIN MODCLIUNI.CLITBLTIPDOC TIPDOC 
                                        ON tipdoc.pk_td_codigo = ent.clitbltipdoc_pk_td_codigo 
                                        JOIN MODPRODUC.PROTBLPRODUC PRO 
                                        ON pro.pk_produc_codigo = cue.pk_produc_codigo and pro.pk_linpro_codigo in (1,3)--beneflex pasarela
                                        JOIN MODALISTA.ALITBLDETPED DETPED 
                                        ON detped.pk_detped_codigo = tar.pk_detped_codigo 
                                        JOIN MODALISTA.ALITBLPEDIDO PED ON ped.pk_pedido_codigo = detped.pk_pedido 
                                        JOIN MODCLIUNI.CLITBLENTIDA ENTCUS ON entcus.pk_ent_codigo = ped.pk_custodio
                                        JOIN MODCLIUNI.CLITBLCAMPAN CAM ON cam.pk_campan_codigo = ped.pk_campan_codigo 
                                        LEFT JOIN MODPROPAG.PPATBLDETORD DETORD ON detord.pk_pedido = detped.pk_detped_Codigo
                                        left JOIN MODFACTUR.FACTBLFACORD FACORD ON facord.pk_ordcom_codigo=detord.pk_orden_compra
                                        left JOIN MODPROPAG.PPATBLORDCOM ORDCOM ON facord.pk_ordcom_codigo=ordcom.pk_ordcom_codigo
                                        LEFT JOIN MODFACTUR.FACTBLFACTUR factur ON facord.pk_factur_codigo=factur.pk_factur_codigo 
                                        JOIN MODTARHAB.tartblesttar ESTTAR 
                                        ON esttar.pk_esttar_codigo = tar.pk_esttar_codigo            
                                        order BY tar.fecha_creacion asc) vista 
                                        left join MODLLAVEMAESTRA.llavetblasotar asot on vista.codprod = asot.pk_produc_codigo
                                        Join MODLLAVEMAESTRA.llavetblabotar abotar on asot.asotar_codigo = abotar.pk_asotar_codigo 
                                        join MODLLAVEMAESTRA.llavetblllavero llavero on asot.pk_llavero_codigo = llavero.llavero_codigo
                                        and vista.codth = asot.pk_ent_codigo and vista.codtar = asot.pk_tarjeta_codigo 
                                        where asot.pk_ent_codigo IS NOT NULL
                                        and asot.pk_produc_codigo IS NOT NULL AND
                                        llavero.llavero_codigo=$pk_llavero
                                        order by vista.NOMTAR asc ");
                $data['abonollaveros'] = $sqlabonollavero->result_array;

                $llaveros = $this->returnarrayllaveros();
                foreach ($llaveros as $value) {
                    if ($value['PK_LLAVERO_CODIGO'] == $pk_llavero) {
                        $nombrellaveroselect = $value['NOMBRE_LLAVERO'];
                    }
                }
                $data['nombrellaveroselect'] = $nombrellaveroselect;
            } else {
                $data['errorpkllavero'] = 1;
            }
        }
        $data['saldo'] = $this->saldollavemaestra();
        $llaveros = $this->returnarrayllaveros();
        $data['llaveros'] = $llaveros;
        //$empresa = $this->session->userdata("entidad");
        $empresa = $_SESSION['entidad'];
        $data['empresa'] = $empresa['NOMBREEMPRESA'];
        //$usuario = $this->session->userdata("usuario");
        $usuario = $_SESSION['usuario'];
        $data['usuario'] = $usuario['NOMBRE'] . " " . $usuario['APELLIDO'];
        //$ultimaconexion = $this->session->userdata("ultimaconexion");
        $ultimaconexion = $_SESSION['ultimaconexion'];
        $data['ultimaconexion'] = $ultimaconexion;
        $data['menu'] = "reverso";
        $this->load->view('portal/templates/header2llave', $data);
        $this->load->view('portal/llave/solicitarDevolucion', $data);
        $this->load->view('portal/templates/footer', $data);
    }

    public function returnReversoTar() {
        $this->verificarllaveMestraNuevosPerfiles();
        //$empresa = $this->session->userdata("entidad");
        $empresa = $_SESSION['entidad'];
        //$usuario = $this->session->userdata("usuario");
        $usuario = $_SESSION['usuario'];
        $post = $this->input->post();
        if ($post) {
            if (!empty($post['pk_llavero'])) {
                $pk_llavero = $post['pk_llavero'];
                $tarjetasEntidad = $this->db->query("
                select vista.nomtar,
                vista.abr,
                vista.doc,
                vista.NOMPRO,
                vista.CODPROD,
                vista.codth,
                vista.NUMTAR,
                vista.id_empresa,
                vista.PK_TARJET_CODIGO,
                asot.asotar_codigo PKTAR,
                llavero.nombre_llavero,
                llavero.llavero_codigo pk_llavero,
                abotar.monto_abono,
                abotar.abotar_codigo,
                conceptomov.nombre nom_concepto,
                vista.ciudad,
                vista.nomcampana
                ,vista.IDENTIFICADOR
                from (SELECT ent.documento DOC,
                                        ent.pk_ent_codigo CODTH,CIUD.nombre ciudad,CAM.nombre nomcampana,
                                        tipdoc.abreviacion abr, 
                                        NVL(TO_CHAR(tar.fecha_creacion,'DD/MM/YYYY'),'PENDIENTE') FEC,
                                        ent.nombre ||' '||ent.apellido nomtar, TAR.NUMERO NUMTAR,TAR.PK_TARJET_CODIGO,
                                        PRO.NOMBRE_PRODUCTO NOMPRO,
                                        pro.pk_produc_codigo codprod,
                                        tar.pk_tarjet_codigo codtar,
                                        tar.id_empresa
                                        ,tar.identificador IDENTIFICADOR
                                        FROM MODTARHAB.tartbltarjet tar 
                                        join MODTARHAB.TARTBLCUENTA CUE 
                                        ON cue.pk_tartblcuenta_codigo = tar.pk_tartblcuenta_codigo 
                                        AND cue.PK_ENT_CODIGO_EMP = {$empresa['PK_ENT_CODIGO']}
                                        JOIN MODCLIUNI.CLITBLENTIDA ENT 
                                        ON ent.pk_ent_codigo = cue.pk_ent_codigo_th 
                                        JOIN MODCLIUNI.CLITBLCIUDAD CIUD ON ent.CLITBLCIUDAD_PK_CIU_CODIGO = CIUD.PK_CIU_CODIGO
                                        JOIN MODCLIUNI.CLITBLTIPDOC TIPDOC 
                                        ON tipdoc.pk_td_codigo = ent.clitbltipdoc_pk_td_codigo 
                                        JOIN MODPRODUC.PROTBLPRODUC PRO 
                                        ON pro.pk_produc_codigo = cue.pk_produc_codigo and pro.pk_linpro_codigo in (2)
                                        JOIN MODALISTA.ALITBLDETPED DETPED 
                                        ON detped.pk_detped_codigo = tar.pk_detped_codigo 
                                        JOIN MODALISTA.ALITBLPEDIDO PED ON ped.pk_pedido_codigo = detped.pk_pedido 
                                        JOIN MODCLIUNI.CLITBLENTIDA ENTCUS ON entcus.pk_ent_codigo = ped.pk_custodio
                                        JOIN MODCLIUNI.CLITBLCAMPAN CAM ON cam.pk_campan_codigo = ped.pk_campan_codigo 
                                        LEFT JOIN MODPROPAG.PPATBLDETORD DETORD ON detord.pk_pedido = detped.pk_detped_Codigo
                                        left JOIN MODFACTUR.FACTBLFACORD FACORD ON facord.pk_ordcom_codigo=detord.pk_orden_compra
                                        left JOIN MODPROPAG.PPATBLORDCOM ORDCOM ON facord.pk_ordcom_codigo=ordcom.pk_ordcom_codigo
                                        LEFT JOIN MODFACTUR.FACTBLFACTUR factur ON facord.pk_factur_codigo=factur.pk_factur_codigo 
                                        JOIN MODTARHAB.tartblesttar ESTTAR 
                                        ON esttar.pk_esttar_codigo = tar.pk_esttar_codigo            
                                        order BY tar.fecha_creacion asc) vista 
                                        left join MODLLAVEMAESTRA.llavetblasotar asot on vista.codprod = asot.pk_produc_codigo and asot.fecha_desasociacion IS NULL
                                        join MODLLAVEMAESTRA.llavetblllavero llavero on asot.pk_llavero_codigo = llavero.llavero_codigo and llavero.estado_codigo=1
                                        JOIN MODLLAVEMAESTRA.llavetblabotar abotar on abotar.pk_asotar_codigo =asot.asotar_codigo
                                        JOIN MODLLAVEMAESTRA.llavetblmovllavero movllavero ON abotar.pk_movllavero_codigo=movllavero.movllavero_codigo
                                        join MODLLAVEMAESTRA.llavetblconceptomov  conceptomov ON abotar.pk_concepto_codigo = conceptomov.pk_concepto_codigo
                                        and vista.codth = asot.pk_ent_codigo and vista.codtar = asot.pk_tarjeta_codigo where asot.pk_ent_codigo IS NOT NULL
                                        AND movllavero.estmov_codigo=1
                                        and asot.pk_produc_codigo IS NOT NULL AND
                                        llavero.llavero_codigo=$pk_llavero
                                        order by abotar.fecha_creacion DESC");
                $data['tarjetaEntidad'] = $tarjetasEntidad->result_array;
                $llaveros = $this->returnarrayllaveros();
                foreach ($llaveros as $value) {
                    if ($value['PK_LLAVERO_CODIGO'] == $pk_llavero) {
                        $nombrellaveroselect = $value['NOMBRE_LLAVERO'];
                    }
                }
                $data['nombrellaveroselect'] = $nombrellaveroselect;
                $data['pk_llavero_codigo'] = $post['pk_llavero'];
            }
        }
        $data['usuario'] = $usuario['NOMBRE'] . " " . $usuario['APELLIDO'];
        //$ultimaconexion = $this->session->userdata("ultimaconexion");
        $ultimaconexion = $_SESSION['ultimaconexion'];
        $data['ultimaconexion'] = $ultimaconexion;
        $llaveros = $this->returnarrayllaveros();
        $data['llaveros'] = $llaveros;
        $data['llaveMaestra'] = 1;
        $data['menu'] = "reverso";
        $data['saldo'] = $this->saldollavemaestra();
        $this->load->view('portal/templates/header2llave', $data);
        $this->load->view('portal/llave/reverso', $data);
        $this->load->view('portal/templates/footer', $data);
    }

    public function mask_email($email) {
        $char_shown = 3;
        $mail_parts = explode("@", $email);
        $username = $mail_parts[0];
        $len = strlen($username);
        if ($len <= $char_shown) {
            return implode("@", $mail_parts);
        } //Logic: show asterisk in middle, but also show the last character before @ 
        $mail_parts[0] = substr($username, 0, $char_shown) . str_repeat("*", $len - $char_shown - 1) . substr($username, $len - $char_shown + 2, 1);
        return implode("@", $mail_parts);
    }

    public function returnmovllavero() {
        $this->verificarllaveMestraNuevosPerfiles();
        //$empresa = $this->session->userdata("entidad");
        $empresa = $_SESSION['entidad'];
        $pk_ent_codigo = $empresa['PK_ENT_CODIGO'];
        $post = $this->input->post();
        if ($post) {

            if ($post['llavero'] != '') {

                $sql = "BEGIN MODLLAVEMAESTRA.LLAVMAEPKGGENERAL.prcretornamovllavero(
                parentcodigo =>:parentcodigo,
                parllaverocodigo=>:parllaverocodigo,
                parnumrows=>:parnumrows,
                movimientosllevero =>:movimientosllevero,
                parrespuesta=>:parrespuesta);
                END;";
                $conn = $this->db->conn_id;
                $stmt = oci_parse($conn, $sql);
                $curs = oci_new_cursor($conn);

                oci_bind_by_name($stmt, ':parentcodigo', $pk_ent_codigo, 32);
                $pk_llaverocodigo = $post['llavero'];
                oci_bind_by_name($stmt, ':parllaverocodigo', $pk_llaverocodigo, 32);
                $numrows = 50; //configurar cantidad filas a retornar
                oci_bind_by_name($stmt, ':parnumrows', $numrows, 32);
                oci_bind_by_name($stmt, ":movimientosllevero", $curs, -1, OCI_B_CURSOR);
                oci_bind_by_name($stmt, ':parrespuesta', $parrespuesta, 32);
                if (!oci_execute($stmt)) {
                    $e = oci_error($stmt);
                    VAR_DUMP($e);
                    exit;
                }
                $movllaveros = array();
                if ($parrespuesta == 1) {
                    oci_execute($curs);  // Ejecutar el REF CURSOR como un ide de sentencia normal
                    while (($row = oci_fetch_array($curs, OCI_ASSOC + OCI_RETURN_NULLS)) != false) {
                        array_push($movllaveros, array(
                            'FECHA' => $row['FECHA'], 'PK_TIPMOV' => $row['TIPMOV_CODIGO'], 'NOMBRE_MOV' => $row['NOMBRE'], 'MONTO_MOV' => $row['MONTO_MOV'], 'SALDO_ANT_MOV' => $row['SALDO_ANT_MOV']
                        ));
                    }
                    $data['movllaveros'] = $movllaveros;
                    $data['consultallaveros'] = 1;
                }
            } elseif ($post['llavero'] == '') {
                //$rol = $this->session->userdata("rol");
                $rol = $_SESSION['rol'];
                if ($rol == 47) {
                    $sql = "BEGIN MODLLAVEMAESTRA.LLAVMAEPKGGENERAL.prcretornamovllavemaestra(
                parentcodigo =>:parentcodigo,
                parnumrows=>:parnumrows,
                movimientos =>:movimientos,
                parrespuesta=>:parrespuesta);
                END;";
                    $conn = $this->db->conn_id;
                    $stmt = oci_parse($conn, $sql);
                    $curs = oci_new_cursor($conn);
                    oci_bind_by_name($stmt, ':parentcodigo', $pk_ent_codigo, 32);
                    $numrows = 5; //configurar cantidad filas a retornar
                    oci_bind_by_name($stmt, ':parnumrows', $numrows, 32);
                    oci_bind_by_name($stmt, ":movimientos", $curs, -1, OCI_B_CURSOR);
                    oci_bind_by_name($stmt, ':parrespuesta', $parrespuesta, 32);
                    if (!oci_execute($stmt)) {
                        $e = oci_error($stmt);
                        VAR_DUMP($e);
                        exit;
                    }
                    $movllavemaestra = array();
                    if ($parrespuesta == 1) {
                        oci_execute($curs);  // Ejecutar el REF CURSOR como un ide de sentencia normal
                        while (($row = oci_fetch_array($curs, OCI_ASSOC + OCI_RETURN_NULLS)) != false) {

                            array_push($movllavemaestra, array(
                                'FECHA' => $row['FECHA'], 'NOMBRE_MOV' => $row['NOMBRE'], 'MONTO_MOV' => $row['MONTO_MOV'], 'SALDO_ANT_MOV' => $row['SALDO_ANT_MOV']
                            ));
                        }
                        $data['movllavemaestra'] = $movllavemaestra;
                    }
                }
            }
//            var_dump($movllaveros);
            $llaveros = $this->returnarrayllaveros();
            foreach ($llaveros as $value) {
                if ($value['PK_LLAVERO_CODIGO'] == $post['llavero']) {
                    $nombrellaveroselect = $value['NOMBRE_LLAVERO'];
                }
            }
            $data['nombrellaveroselect'] = $nombrellaveroselect;
            $data['pk_llavero_codigo'] = $post['llavero'];
        }
        //$rol = $this->session->userdata("rol");
        $rol = $_SESSION['rol'];
        $pk_tipomovabono = 4; //pk tipo movimiento abonos 
        $pk_tipomovreverso = 5; //pk tipo movimiento reverso 5 
        $pk_tipomovdevolucion = 6; //pk tipo movimiento reverso 5 
        if ($rol == 47) {
            $data['totalabonos'] = $this->returntotalpktipomovllavero($pk_tipomovabono, '', '');
            $data['totalreversos'] = $this->returntotalpktipomovllavero($pk_tipomovreverso, '', '');
            $data['totaldevoluciones'] = $this->returntotalpktipomovllavero($pk_tipomovdevolucion, '', '');
            $data['saldo'] = $this->saldollavemaestra();
        } elseif ($rol == 45) {
            //$usuario = $this->session->userdata("usuario");
            $usuario = $_SESSION['usuario'];
            $pk_entidad_coor = $usuario['PK_ENT_CODIGO'];
            $data['totalabonos'] = $this->returntotalpktipomovllavero($pk_tipomovabono, '', $pk_llaverocodigo);
            $data['totalreversos'] = $this->returntotalpktipomovllavero($pk_tipomovreverso, '', $pk_llaverocodigo);
            $data['totaldevoluciones'] = $this->returntotalpktipomovllavero($pk_tipomovdevolucion, '', $pk_llaverocodigo);
            if (!empty($post['llavero'])) {
                $data['saldo'] = $this->returnsaldollaveroid($post['llavero']);
            }
        }


        
        $llaveros = $this->returnarrayllaveros();
        $data['llaveros'] = $llaveros;
        $data['saldo'] = $this->saldollavemaestra();
        $data['empresa'] = $empresa['NOMBREEMPRESA'];
        //$usuario = $this->session->userdata("usuario");
        $usuario = $_SESSION['usuario'];
        $data['usuario'] = $usuario['NOMBRE'] . " " . $usuario['APELLIDO'];
        //$ultimaconexion = $this->session->userdata("ultimaconexion");
        $ultimaconexion = $_SESSION['ultimaconexion'];
        $data['ultimaconexion'] = $ultimaconexion;
        $data['llaveMaestra'] = 1;
        $data['menu'] = "estado";
        $this->load->view('portal/templates/header2llave', $data);
        $this->load->view('portal/llave/estadoLLaveMaestra', $data);
        $this->load->view('portal/templates/footer', $data);
    }

    public function saldollavemaestra() {
        //$empresa = $this->session->userdata("entidad");
        $empresa = $_SESSION['entidad'];
        $pk_ent_codigo = $empresa['PK_ENT_CODIGO'];
        $sql = "BEGIN MODLLAVEMAESTRA.LLAVMAEPKGGENERAL.saldollavemaestra(
                    parentcodigo =>:parentcodigo,
                    parsaldo =>:parsaldo,
                    parrespuesta=>:parrespuesta);
                    END;";

        $conn = $this->db->conn_id;
        $stmt = oci_parse($conn, $sql);
        $parpk_entidad = $pk_ent_codigo;
        oci_bind_by_name($stmt, ':parentcodigo', $parpk_entidad, 32);
        oci_bind_by_name($stmt, ':parsaldo', $parsaldo, 32);
        oci_bind_by_name($stmt, ':parrespuesta', $parrespuesta, 32);
        if (!oci_execute($stmt)) {
            $e = oci_error($stmt);
            VAR_DUMP($e);
            exit;
        }
        if ($parrespuesta == 1) {
            $saldollavemaestra = $parsaldo;
        }
        return $saldollavemaestra;
    }

    public function returnarrayllaveros() {
        //$empresa = $this->session->userdata("entidad");
        $empresa = $_SESSION['entidad'];
        $pk_ent_codigo = $empresa['PK_ENT_CODIGO'];
        //$usuario = $this->session->userdata("usuario");
        $usuario = $_SESSION['usuario'];
        $pk_entidad = $usuario['PK_ENT_CODIGO'];
        //$rol = $this->session->userdata("rol");
        $rol = $_SESSION['rol'];

        $sql = "BEGIN MODLLAVEMAESTRA.LLAVMAEPKGGENERAL.retornallaveros(
                parentcodigo =>:parentcodigo,
                llaveros =>:llaveros,
                parrespuesta=>:parrespuesta);
                END;";
        $conn = $this->db->conn_id;
        $stmt = oci_parse($conn, $sql);
        $curs = oci_new_cursor($conn);
        oci_bind_by_name($stmt, ":llaveros", $curs, -1, OCI_B_CURSOR);
        oci_bind_by_name($stmt, ':parentcodigo', $pk_ent_codigo, 32);
        oci_bind_by_name($stmt, ':parrespuesta', $parrespuesta, 32);
        if (!oci_execute($stmt)) {
            $e = oci_error($stmt);
            VAR_DUMP($e);
            exit;
        }
        $llaveros = array();
        if ($parrespuesta == 1) {
            oci_execute($curs);  // Ejecutar el REF CURSOR como un ide de sentencia normal
            while (($row = oci_fetch_array($curs, OCI_ASSOC + OCI_RETURN_NULLS)) != false) {
                if ($row['ESTADO'] == 1 && ($rol == 61)) {
                    array_push($llaveros, array(
                        'PK_LLAVERO_CODIGO' => $row['PK_LLAVERO_CODIGO'], 'NOMBRE_LLAVERO' => $row['NOMBRE_LLAVERO'], 'NOMBRE_COOR_RES' => $row['NOMBRE_COOR_RES'], 'NOMBRE_ADM_PAGO' => $row['NOMBRE_ADM_PAGO'], 'SALDO' => $row['SALDO'], 'ESTADO' => $row['ESTADO']
                    ));
                } elseif (($rol == 60) && ($row['ESTADO'] == 1 && $row['PK_ENT_CODIGO_COOR'] == $pk_entidad)) {
                    array_push($llaveros, array(
                        'PK_LLAVERO_CODIGO' => $row['PK_LLAVERO_CODIGO'], 'NOMBRE_LLAVERO' => $row['NOMBRE_LLAVERO'], 'NOMBRE_COOR_RES' => $row['NOMBRE_COOR_RES'], 'NOMBRE_ADM_PAGO' => $row['NOMBRE_ADM_PAGO'], 'SALDO' => $row['SALDO'], 'ESTADO' => $row['ESTADO']
                    ));
                }
            }
//            $data['llaveros'] = $llaveros;
        }
        return $llaveros;
    }

    public function returnsaldollaveroid($idllavero) {

        $sql = "BEGIN MODLLAVEMAESTRA.LLAVMAEPKGGENERAL.prcsaldollavero(
                    parcodigollavero =>:parcodigollavero,
                    parsaldo =>:parsaldo,
                    parrespuesta=>:parrespuesta);
                    END;";

        $conn = $this->db->conn_id;
        $stmt = oci_parse($conn, $sql);
        $parpk_llavero = $pk_llavero;
        oci_bind_by_name($stmt, ':parcodigollavero', $idllavero, 32);
        oci_bind_by_name($stmt, ':parsaldo', $parsaldo, 32);
        oci_bind_by_name($stmt, ':parrespuesta', $parrespuesta, 32);
        if (!oci_execute($stmt)) {
            $e = oci_error($stmt);
            VAR_DUMP($e);
            exit;
        }
        if ($parrespuesta == 1) {
            $saldoactullavero = $parsaldo;
        } else {
            $saldoactullavero = 404;
        }
        return $saldoactullavero;
    }

    /* Retorna saldo tarjeta
     * Recibe como parametro el pk_tarjeta_codigo
     */

    public function returnsaldotarjetaid($pk_tarjeta_codigo) {
        if (!empty($pk_tarjeta_codigo)) {
            $sqlsaldodisponible = $this->db->query("SELECT
                                    tarjeta.pk_tarjet_codigo   codigotarjeta,
                                    tarjetas_zeus.empresa,
                                    tarjetas_zeus.fk_tipo_documento_id,
                                    tarjetas_zeus.id_tarjeta_zeus,
                                    tarjetas_zeus.numero_documento,
                                    tarjetas_zeus.pan_enmascarado,
                                    tarjetas_zeus.saldo,
                                    cuenta.pk_produc_codigo    codigoproducto,
                                    tarjetas_zeus.producto,
                                    tarjetas_zeus.fk_estado_id,
                                    CASE
                                        WHEN fk_estado_id = 0
                                             AND motivo_bloqueo != 'BLOQUEO PREVENTIVO' THEN
                                            'BLOQUEADA'
                                        WHEN fk_estado_id = 1   THEN
                                            'ACTIVA'
                                        WHEN fk_estado_id = 4   THEN
                                            'PENDIENTE ACTIVACION'
                                        WHEN fk_estado_id = 0
                                             AND motivo_bloqueo = 'BLOQUEO PREVENTIVO' THEN
                                            'APAGADA'
                                        ELSE
                                            'DESCONOCIDO'
                                    END ESTADO,
                                    tarjetas_zeus.motivo_bloqueo
                                    FROM
                                    modcliuni.clitblentida   entida
                                    JOIN modtarhab.tartblcuenta   cuenta ON cuenta.pk_ent_codigo_th = entida.pk_ent_codigo
                                    JOIN modcliuni.clitbltipdoc   tipdoc ON entida.clitbltipdoc_pk_td_codigo = tipdoc.pk_td_codigo
                                    JOIN modtarhab.tartbltarjet   tarjeta ON cuenta.pk_tartblcuenta_codigo = tarjeta.pk_tartblcuenta_codigo
                                    AND tarjeta.pk_esttar_codigo NOT IN (15,16,17)
                                    JOIN modtarhab.view_listath   tarjetas_zeus 
                                    ON tarjetas_zeus.fk_tipo_documento_id =  CASE
                                        WHEN ( entida.clitbltipdoc_pk_td_codigo = 68 ) THEN
                                             0--CEDULA
                                        WHEN ( entida.clitbltipdoc_pk_td_codigo = 67 ) THEN
                                           2
                                        WHEN ( entida.clitbltipdoc_pk_td_codigo = 69 ) THEN
                                             1
                                        WHEN ( entida.clitbltipdoc_pk_td_codigo = 70 ) THEN
                                            3
                                        WHEN ( entida.clitbltipdoc_pk_td_codigo = 72 OR entida.clitbltipdoc_pk_td_codigo = 73 ) THEN
                                             6
                                             END 
                                             AND tarjetas_zeus.numero_documento = tarjeta.id_empresa
                                            AND tarjetas_zeus.pan_enmascarado = tarjeta.numero
                                    WHERE  tarjeta.pk_tarjet_codigo = $pk_tarjeta_codigo");
            $saldotarjeta = $sqlsaldodisponible->result_array[0];
            $saldo_tarj_return = $saldotarjeta['SALDO'];
        }
        return $saldo_tarj_return;
    }

    public function returntotalpktipomovllavero($pk_tipo_movllavero, $pk_coor_llavero, $pk_llavero_codigo) {
        //$empresa = $this->session->userdata("entidad");
        $empresa = $_SESSION['entidad'];
        $pk_ent_codigo = $empresa['PK_ENT_CODIGO'];
        if (!empty($pk_tipo_movllavero)) {
            $sql = "BEGIN MODLLAVEMAESTRA.LLAVMAEPKGGENERAL.returntotalmovllaveropktipomov(
                parpkentidad =>:parpkentidad,
                parpktipmovllavero =>:parpktipmovllavero,
                parpkcoorllavero=>:parpkcoorllavero,
                parpkllavero=>:parpkllavero,
                parmensajerespuesta=>:parmensajerespuesta,
                partotal=>:partotal,
                parrespuesta=>:parrespuesta);
                END;";
            $conn = $this->db->conn_id;
            $stmt = oci_parse($conn, $sql);
            $curs = oci_new_cursor($conn);
            oci_bind_by_name($stmt, ":parpkentidad", $pk_ent_codigo, 32);
            oci_bind_by_name($stmt, ':parpktipmovllavero', $pk_tipo_movllavero, 32);
            oci_bind_by_name($stmt, ':parpkcoorllavero', $pk_coor_llavero, 32);
            oci_bind_by_name($stmt, ':parpkllavero', $pk_llavero_codigo, 32);
            oci_bind_by_name($stmt, ':parmensajerespuesta', $parmensajerespuesta, 32);
            oci_bind_by_name($stmt, ':partotal', $partotal, 32);
            oci_bind_by_name($stmt, ':parrespuesta', $parrespuesta, 32);
            if (!oci_execute($stmt)) {
                $e = oci_error($stmt);
                VAR_DUMP($e);
                exit;
            }
            $total = 0;
            if ($parrespuesta == 1) {
                $total = $partotal;
            }
        }
        return $total;
    }

    public function returncantabonopkcodprodcuto($codigo_producto) {
        //$empresa = $this->session->userdata("entidad");
        $empresa = $_SESSION['entidad'];
        $pk_entida = $empresa['PK_ENT_CODIGO'];
        $cantabonos_pkprod = 0;
        if (!empty($codigo_producto)) {
            $sqlcantabonos = $this->db->query("SELECT COUNT(vista.fecha_creacion) abonos FROM (select asotar.fecha_creacion from MODLLAVEMAESTRA.llavetblabotar abotar 
                JOIN MODLLAVEMAESTRA.llavetblasotar asotar
                ON asotar.asotar_codigo= abotar.pk_asotar_codigo
                JOIN MODLLAVEMAESTRA.llavetblmovllavero movllavero
                ON movllavero.movllavero_codigo= abotar.pk_movllavero_codigo
                JOIN MODLLAVEMAESTRA.LLAVETBLLLAVERO llavero
                ON llavero.llavero_codigo=movllavero.fk_llavero_codigo
                JOIN MODLLAVEMAESTRA.LLAVETBLLLAVMAE LLAVEMAE
                ON llavemae.llavmae_codigo=llavero.llavemaestra_codigo
                JOIN MODTARHAB.tartbltarjet tar 
                ON tar.pk_tarjet_codigo = asotar.pk_tarjeta_codigo
                join MODTARHAB.TARTBLCUENTA CUE 
                ON cue.pk_tartblcuenta_codigo = tar.pk_tartblcuenta_codigo 
                JOIN MODPRODUC.PROTBLPRODUC PRO 
                ON pro.pk_produc_codigo = cue.pk_produc_codigo and pro.pk_linpro_codigo in (2)
                where movllavero.tipmovllavero_codigo=4 and
                movllavero.estmov_codigo=1 and
                llavemae.pk_ent_codigo=$pk_entida and
                PRO.pk_produc_codigo=$codigo_producto)vista
                WHERE vista.fecha_creacion BETWEEN  trunc(sysdate, 'MM') AND current_date");
            $cantabonos = $sqlcantabonos->result_array;
            $cantabonos_pkprod = $cantabonos[0]['ABONOS'];
        }
        return $cantabonos_pkprod;
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

    /* recibe por parametro las ordenes de pago para generar la referencia de pago */

    public function generarreferenciapago($ordenes, $pse = 0) {
        $sql = " BEGIN 
            modpropag.ppapkgactualizaciones.prcconsumirreferenciapago
            (parreferenciapago=>:parreferenciapago
             ,parordenes=>:parordenes 
             ,paractivarpse=>:paractivarpse); END;";

        //$ci =& get_instance();
        //$conn = $ci->db->conn_id;
        $conn = $this->db->conn_id;
        $stmt = oci_parse($conn, $sql);
        $parordenes = $ordenes;

        if ($pse = 1) {
            $paractivarpse = $pse;
        } else {
            $paractivarpse = 0;
        }

//TIPO NUMBER INPUT
        oci_bind_by_name($stmt, ':parreferenciapago', $parreferenciapago, 100);
//TIPO NUMBER INPUT
        oci_bind_by_name($stmt, ':parordenes', $parordenes, 4000);
//TIPO VARCHAR2 INTPUT
        oci_bind_by_name($stmt, ':paractivarpse', $paractivarpse, 4000);
        if (!@oci_execute($stmt)) {
            $e = oci_error($stmt);
            var_dump("{$e['message']}");
//exit();
            return 0;
        } else {
            return $parreferenciapago;
        }
    }

    public function consultaNotasContables() {
        $this->verificarllaveMestra();
        //$empresa = $this->session->userdata("entidad");
        $empresa = $_SESSION['entidad'];
        //$usuario = $this->session->userdata("usuario");
        $usuario = $_SESSION['usuario'];
        $pk_entidad_coor = $usuario['PK_ENT_CODIGO'];
        //$rol = $this->session->userdata("rol");
        $rol = $_SESSION['rol'];
        if (($rol == 59) || ($rol == 60) || ($rol == 61)) {
            $notaContables = $this->db->query(" 
            select entidad.documento NIT, 
            NVL(entidad.razon_social,entidad.nombre||' '||entidad.apellido) RAZON_SOCIAL,
            notacontable.pk_notacontable_codigo ID_NOTA_CONTABLE
            ,notacontable.numero_nota_contable NUMERO_NOTA
            ,notacontable.PREFIJO
            ,orden_compra.pk_ordcom_codigo NUMERO_ORDEN
            ,(SELECT SUM(MONTO) FROM MODPROPAG.ppatbldetord WHERE pk_orden_compra=orden_compra.pk_ordcom_codigo) MONTO
            ,estado_orden.nombre ESTADO
            ,to_char(notacontable.fecha_creacion,'dd/mm/yyyy') FECHA_CREACION
            ,to_char(orden_compra.fecha_pago,'dd/mm/yyyy') FECHA_PAGO
            ,orden_compra.medio_pago MEDIO_PAGO
            from 
            MODPROPAG.ppatblordcom orden_compra
            JOIN MODPROPAG.ppatblestoco estado_orden 
            ON estado_orden.pk_estoco_codigo=orden_compra.pk_estado
            JOIN MODPROPAG.ppatblnotacontable notacontable
            ON notacontable.pk_ordcom_codigo=orden_compra.pk_ordcom_codigo
            JOIN MODCLIUNI.CLITBLENTIDA entidad
            ON entidad.pk_ent_codigo=orden_compra.pk_cliente
            WHERE entidad.pk_ent_codigo={$empresa['PK_ENT_CODIGO']}
            order by notacontable.pk_notacontable_codigo desc");
            $data['notaContables'] = $notaContables->result_array;
        }

        $data['saldo'] = $this->saldollavemaestra();
        $data['empresa'] = $empresa['NOMBREEMPRESA'];

        $data['usuario'] = $usuario['NOMBRE'] . " " . $usuario['APELLIDO'];
        //$ultimaconexion = $this->session->userdata("ultimaconexion");
        $ultimaconexion = $_SESSION['ultimaconexion'];
        $data['ultimaconexion'] = $ultimaconexion;
        $data['llaveMaestra'] = 1;
        $data['menu'] = "estado";
        $this->load->view('portal/templates/header2llave', $data);
        $this->load->view('portal/llave/notasContables', $data);
        $this->load->view('portal/templates/footer', $data);
    }

    public function consultaFacturas() {
        $this->verificarllaveMestraNuevosPerfiles();
        //$empresa = $this->session->userdata("entidad");
        $empresa = $_SESSION['entidad'];
        //$usuario = $this->session->userdata("usuario");
        $usuario = $_SESSION['usuario'];
        $data['saldo'] = $this->saldollavemaestra();
        $data['empresa'] = $empresa['NOMBREEMPRESA'];
        $data['usuario'] = $usuario['NOMBRE'] . " " . $usuario['APELLIDO'];
        //$ultimaconexion = $this->session->userdata("ultimaconexion");
        $ultimaconexion = $_SESSION['ultimaconexion'];
        $data['ultimaconexion'] = $ultimaconexion;
        $data['menu'] = "estado";


        //$rol = $this->session->userdata("rol");
        $rol = $_SESSION['rol'];
        if (($rol == 60) || ($rol == 61)) {
            $sqlFacturas = $this->db->query("Select  entidad.documento NIT,
            NVL(entidad.razon_social,entidad.nombre||' '||entidad.apellido) RAZON_SOCIAL,
            fac.numero_factura,
            fac.pk_factur_codigo fac,
            estf.nombre ESTADO,
            fac.total MONTO,
            fac.fecha_creacion,
            fac.fecha_pago
            from
            MODFACTUR.factblfacord facord
            join modllavemaestra.llavetblprocesofacturacion prfac
            ON facord.pk_ordcom_codigo = prfac.pk_ordcom_codigo
            join MODFACTUR.factblfactur fac
            ON fac.pk_factur_codigo = facord.pk_factur_codigo
            join modllavemaestra.llavetblllavmae llavemae
            on prfac.fk_llavemae_codigo = llavemae.llavmae_codigo
            Join modfactur.factblestfac estf
            on estf.pk_estfac_codigo = fac.pk_estado
            JOIN MODCLIUNI.CLITBLENTIDA entidad
            ON entidad.pk_ent_codigo=llavemae.pk_ent_codigo
            and entidad.pk_ent_codigo = {$empresa['PK_ENT_CODIGO']}
            and fac.es_factura_llave =1");
            $data['Facturas'] = $sqlFacturas->result_array;
        }
        $this->load->view('portal/templates/header2llave', $data);
        $this->load->view('portal/llave/facturas', $data);
        $this->load->view('portal/templates/footer', $data);
    }

    public function pagarNota($pk_nota_contable = null) {

        if (!empty($pk_nota_contable)) {

            $dataUser = $this->db->query("select
                                        entida.NOMBRE NOMBRE,
                                        entida.APELLIDO APELLIDO,
                                        entida.DOCUMENTO DOCUMENTO,
                                        entida.CORREO_ELECTRONICO CORREO_ELECTRONICO, 
                                        tipdoc.CODIGO_PASARELA TIPODOCUMENTO ,
                                        notacontable.pk_ordcom_codigo,
                                        modpropag.ppapkgconsultas.fncconmontoorden( notacontable.pk_ordcom_codigo) Valor_total
                                        from MODPROPAG.ppatblordcom orden_compra
                                        JOIN MODCLIUNI.CLITBLENTIDA entida
                                        ON entida.pk_ent_codigo=orden_compra.pk_cliente
                                        JOIN MODCLIUNI.CLITBLTIPDOC tipdoc 
                                        ON entida.clitbltipdoc_pk_td_codigo=tipdoc.pk_td_codigo
                                        JOIN MODPROPAG.ppatblnotacontable notacontable
                                        ON notacontable.pk_ordcom_codigo=orden_compra.pk_ordcom_codigo
                                        where notacontable.pk_notacontable_codigo=$pk_nota_contable");

            $data = $dataUser->result_array[0];
            $parordcompra = $data['PK_ORDCOM_CODIGO'];
            $totalPago = $data['VALOR_TOTAL'];
            $apiKey = $this->db->query("SELECT VALOR_PARAMETRO FROM MODGENERI.GENTBLPARGEN WHERE pk_pargen_codigo=77");
            $apiKey = $apiKey->result_array[0];
            $urlRetorno = $this->db->query("SELECT VALOR_PARAMETRO FROM MODGENERI.GENTBLPARGEN WHERE pk_pargen_codigo=79");
            $urlRetorno = $urlRetorno->result_array[0];
            $codigoComercio = $this->db->query("SELECT VALOR_PARAMETRO FROM MODGENERI.GENTBLPARGEN WHERE pk_pargen_codigo=78");
            $codigoComercio = $codigoComercio->result_array[0];
            $urlpasarela = $this->db->query("SELECT VALOR_PARAMETRO FROM MODGENERI.GENTBLPARGEN WHERE pk_pargen_codigo=80");
            $urlpasarela = $urlpasarela->result_array[0];
            $referenciaComercio = $this->db->query("SELECT VALOR_PARAMETRO FROM MODGENERI.GENTBLPARGEN WHERE pk_pargen_codigo=90");
            $referenciaComercio = $referenciaComercio->result_array[0];

            if (!empty($parordcompra)) {
                $pse = 1;
                $referenciapago = $this->generarreferenciapago($parordcompra, $pse);
            }
            $htmlPay = '                
                        <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
                        <form class="hidden" method="post" action="' . $urlpasarela['VALOR_PARAMETRO'] . '" target="_blank" >
                                    <input type="hidden" name="Id" id="Id" value="' . $codigoComercio['VALOR_PARAMETRO'] . '"/>
                                    <input type="hidden" name="Clave" id="Clave" value="' . $apiKey['VALOR_PARAMETRO'] . '"/>
                                    <input type="hidden" name="CompraNeta" id="CompraNeta" value="' . $totalPago . '"/>
                                    <input type="hidden" name="Iva" id="Iva" value="0"/>
                                    <input type="hidden" name="BaseDevolucion" id="BaseDevolucion" value="0"/>
                                    <input type="hidden" name="NombreProducto" id="NombreProducto" value="' . $referenciapago . '"/>
                                    <input type="hidden" name="Referencia" id="Referencia" value="' . $referenciaComercio['VALOR_PARAMETRO'] . '"/>
                                    <input type="hidden" name="ValorTotal" id="ValorTotal" value="' . $totalPago . '"/>
                                    <input type="hidden" name="Factura" id="Factura" value="' . $referenciapago . '"/>
                                    <input type="hidden" name="IdCiudad" id="IdCiudad" value="9"/>
                                    <input type="hidden" name="IdPais" id="IdPais" value="170"/>
                                    <input type="hidden" name="Franquicia" id="Franquicia" value=""/>
                                    <input TYPE="hidden" name="PrimerNombre"  value="' . $data['NOMBRE'] . '"><br>
                                    <input TYPE="hidden" name="PrimerApellido"  value="' . $data['APELLIDO'] . '"><br>   
                                    <input TYPE="hidden" required name="Correo" id="Correo" value="' . $data['CORREO_ELECTRONICO'] . '"/>
                                    <input TYPE="hidden" required name="NumeroDocumento" id="NumeroDocumento" value="' . $data['DOCUMENTO'] . '"/>
                                    <input  name="Submit" type="submit" value="" id="SendPeoplePay" hidden>
                        </form>            
                        <script>
                            $( document ).ready(function() {
                                document.getElementById("SendPeoplePay").click();
                            });
                        </script>
                    ';
            $data['htmlPay'] = $htmlPay;
            $data['totalPago'] = $totalPago;
            $data['referenciapago'] = $referenciapago;
//DATOS DE SESION
            //$empresa = $this->session->userdata("entidad");
            $empresa = $_SESSION['entidad'];
            $data['empresa'] = $empresa['NOMBREEMPRESA'];
            //$usuario = $this->session->userdata("usuario");
            $usuario = $_SESSION['usuario'];
            $data['usuario'] = $usuario['NOMBRE'] . " " . $usuario['APELLIDO'];
            //$ultimaconexion = $this->session->userdata("ultimaconexion");
            $ultimaconexion = $_SESSION['ultimaconexion'];
            $data['ultimaconexion'] = $ultimaconexion;
//    
            $this->load->view('portal/templates/header2llave', $data);
            $this->load->view('portal/llave/procesandorecarga', $data);
            $this->load->view('portal/templates/footer', $data);
            return;
        }
    }

}
