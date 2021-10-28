<?php

session_start();
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Ajax extends CI_Controller {

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
    
     public function saldoLLavero($id_admin_llavero = NULL) {
          log_info($this->logHeader . 'INGRESO AJAX DATAUSER COLOMBIA'.$id_admin_llavero);
        if ($id_admin_llavero != NULL) {          
            $data_body['saldo_llavero'] = $this->returnsaldollaveroid($id_admin_llavero);
            $this->load->view('portal/llave/saldoLLavero', $data_body);
        }
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
    public function ciudad($id_admin_departamento = NULL) {
        if ($id_admin_departamento != NULL) {
            $data_body['ciudad'] = $this->db->query("SELECT PK_CIU_CODIGO, NOMBRE FROM MODCLIUNI.CLITBLCIUDAD WHERE CLITBLDEPPAI_PK_DEP_CODIGO=$id_admin_departamento. order by NOMBRE asc");
            $data_body['ciudad'] = $data_body['ciudad']->result_array;
            $this->load->view('portal/usuariosCreacion/ciudad', $data_body);
        }
    }

    public function dataUser($tipoDoc, $documento) {
        log_info($this->logHeader . 'INGRESO AJAX DATAUSER');
        log_info($this->postData . 'Tipodoc= ' . $tipoDoc . ' - Documento= ' . $documento);
        if ($tipoDoc != NULL && $documento != NULL) {
            //$empresa = $this->session->userdata("entidad");
            //$campana = $this->session->userdata("campana");
            $empresa = $_SESSION['entidad'];
            $campana = $_SESSION['campana'];
            $dataUser = $this->db->query("Select DISTINCT ent.pk_ent_codigo, ent.documento,ent.nombre,ent.apellido, ent.correo_electronico,ent.CLITBLCIUDAD_PK_CIU_CODIGO CIUDAD,ent.clitbltipdoc_pk_td_codigo,ciu.CLITBLDEPPAI_PK_DEP_CODIGO DEPARTAMENTO
            from modcliuni.clitblentida ent
            LEFT JOIN MODCLIUNI.CLITBLCIUDAD ciu ON ent.CLITBLCIUDAD_PK_CIU_CODIGO = ciu.PK_CIU_CODIGO
            where clitbltipdoc_pk_td_codigo='$tipoDoc' and documento='$documento'
            and ent.clitblestusu_pk_estusu_codigo=1");
            $data_body['data'] = $dataUser;

            if ($dataUser->num_rows == 0) {
                log_info($this->postData . 'ENTRA EMPTY dataUser ');
                $dataUser = $this->db->query("Select sol.primer_nombre || ' '||sol.segundo_nombre NOMBRE ,sol.primer_apellido|| ' '|| sol.segundo_apellido apellido,sol.documento, sol.correo_electronico,sol.CLITBLCIUDAD_PK_CIU_CODIGO CIUDAD,sol.clitbltipdoc_pk_td_codigo,ciu.CLITBLDEPPAI_PK_DEP_CODIGO DEPARTAMENTO
            from modprepedido.prepetbldetallesolicitud sol
            LEFT JOIN MODCLIUNI.CLITBLCIUDAD ciu ON sol.CLITBLCIUDAD_PK_CIU_CODIGO = ciu.PK_CIU_CODIGO
            where sol.clitbltipdoc_pk_td_codigo='$tipoDoc' and sol.documento='$documento'");
                $data_body['data'] = $dataUser;
            }
//            log_info($this->$postData . print_r($dataUser->result_array));
            $custio = $this->db->query("select ent.nombre ||' '|| ent.apellido || ' - '||ent.documento NOMBRE,
            ent.pk_ent_codigo CODIGOENTIDA
                        from modcliuni.clitblvincul vin 
                        join modcliuni.clitblentida ent on ent.pk_ent_codigo = vin.CLITBLENTIDA_PK_ENT_CODIGO
                        and vin.clitbltipvin_pk_tipvin_codigo = 46 
                        and vin.fecha_fin is null
                        and vin.clitblentida_pk_ent_codigo1={$empresa['PK_ENT_CODIGO']}
                        and vin.CLITBLCAMPAN_PK_CAMPAN_CODIGO=$campana");
            $data_body['custodios'] = $custio->result_array;
            $data_body['data'] = $data_body['data']->result_array;
            $tipodocumento = $this->db->query('SELECT PK_TD_CODIGO,ABREVIACION,NOMBRE FROM MODCLIUNI.CLITBLTIPDOC WHERE PK_TD_CODIGO IN (67,68,69,70,72) ');
            $data_body['tipoDocumento'] = $tipodocumento->result_array;
            $departamentos = $this->db->query("SELECT PK_DEP_CODIGO, NOMBRE FROM MODCLIUNI.CLITBLDEPPAI WHERE CLITBLPAIS_PK_PAIS_CODIGO=7");
            $data_body['departamentos'] = $departamentos->result_array;

            //enviar departamento 
            foreach ($departamentos->result_array as $value) {
                if ($value['PK_DEP_CODIGO'] == $dataUser->result_array[0]['DEPARTAMENTO']) {
                    $data_body['depentidad'] = $value['NOMBRE'];
                }
            }
            //enviar ciudad
            $ciudades = $this->db->query("SELECT PK_CIU_CODIGO, NOMBRE"
                    . " FROM MODCLIUNI.CLITBLCIUDAD"
                    . " WHERE CLITBLDEPPAI_PK_DEP_CODIGO='{$dataUser->result_array[0]['DEPARTAMENTO']}'");
            foreach ($ciudades->result_array as $value) {
                if ($value['PK_CIU_CODIGO'] == $dataUser->result_array[0]['CIUDAD']) {
                    $data_body['ciuentidad'] = $value['NOMBRE'];
                }
            }
            if (!empty($dataUser->result_array)) {
                $this->load->view('administrador/ajax/dataUsuarioTarjeta', $data_body);
            }
        }
    }

    public function onlineCampana($idempresa) {
        if ($idempresa != NULL) {
            $data_body['campana'] = $this->db->query(" SELECT DISTINCT pk_campan_codigo CODIGOCAMPANA,campan.nombre NOMBRECAMPANA
                 FROM modcliuni.clitblvincul vincul
                 JOIN MODCLIUNI.CLITBLCAMPAN campan
                 ON vincul.CLITBLCAMPAN_PK_CAMPAN_CODIGO=campan.pk_campan_codigo
                 WHERE clitblentida_pk_ent_codigo1=$idempresa
                 AND vincul.fecha_fin is null
                 and vincul.CLITBLTIPVIN_PK_TIPVIN_CODIGO in (45,46,47)");
            $data_body['campana'] = $data_body['campana']->result_array;
        }
        $this->load->view('administrador/ajax/onlineCampana', $data_body);
    }

    //Send message Client
    public function sendMessageClient($message) {
        //BUSCA SAC DISPONIBLE O EN LINEA
        //if ($this->session->userdata['dataChat'] == null) {
        if ($_SESSION['dataChat'] == null) {
            $asesordisponible = $this->db->query("            
            SELECT  to_char(fecha_creacion,'DD-MM-YYYY HH:MM:SS') fecha , estado, pk_conexion_sac, sac_user_id  FROM MODULCHAT.chatblconexionsac
            WHERE MODULCHAT.chatblconexionsac.estado = 1 and ROWNUM = 1 
            ");
            $asesordisponible = $asesordisponible->result_array[0];
            $this->session->set_userdata(array('dataChat' => $asesordisponible));
        } else {
            //$asesordisponible = $this->session->userdata['dataChat'];
            $asesordisponible = $_SESSION['dataChat'];
        }
        //CONDICION SI ESTA OCUPADO USUARIO SAC
        // if(count($asesordisponible) == 0){      
        //     var_dump($asesordisponible);      
        //     $asesorocupado= $this->db->query("            
        //     SELECT  to_char(fecha_creacion,'DD-MM-YYYY HH:MM:SS') fecha , estado, pk_conexion_sac, sac_user_id  FROM MODULCHAT.chatblconexionsac
        //     WHERE MODULCHAT.chatblconexionsac.estado = 2 and ROWNUM = 1 
        // ");
        //Inicia chat con SAC.  
        //if ($this->session->userdata['PK_CHAT'] == null) {
        if ($_SESSION['PK_CHAT'] == null) {
            $sql = "BEGIN MODULCHAT.CHATPKGACTUALIZACION.PRCCREARCHAT(parsacuserid=>:parsacuserid,
                    parclienteid=>:parclienteid,
                    parconexionid=>:parconexionid,
                    parestado=>:parestado,
                    parpkchat=>:parpkchat,
                    parrespuesta=>:parrespuesta);
                    END;";
            $conn = $this->db->conn_id;
            $stmt = oci_parse($conn, $sql);
            $parconexionid = $asesordisponible['PK_CONEXION_SAC'];
            $parsacuserid = $asesordisponible['SAC_USER_ID'];
            //$parclienteid = $this->session->userdata['usuario']['PK_ENT_CODIGO'];
            $parclienteid = $_SESSION['usuario']['PK_ENT_CODIGO'];
            $parestado = 1;
            oci_bind_by_name($stmt, ':parsacuserid', $parsacuserid, 32);
            oci_bind_by_name($stmt, ':parclienteid', $parclienteid, 32);
            oci_bind_by_name($stmt, ':parconexionid', $parconexionid, 32);
            oci_bind_by_name($stmt, ':parestado', $parestado, 32);
            oci_bind_by_name($stmt, ':parpkchat', $parpkchat, 32);
            oci_bind_by_name($stmt, ':parrespuesta', $parrespuesta, 32);
            if (!@oci_execute($stmt)) {
                $e = oci_error($stmt);
                var_dump($e);
            }
            if ($parrespuesta == 1) {
                $insert = $this->insertMessage($message, $parpkchat);
                if ($insert) {
                    $pk_details = $this->insertDetails($this->session->userdata['usuario']['PK_ENT_CODIGO'], 1);
                    if ($pk_details != null) {
                        $this->session->set_userdata('PK_CHAT', $parpkchat);
                        $this->session->set_userdata('PK_DETAILS', $pk_details);
                        $this->update_last_activity();
                    }
                }
            }
        } else {
            $this->insertMessage($message, $this->session->userdata['PK_CHAT']);
        }
    }

    public function sendMessageSac($message, $pk_chat, $to_user_id) {
        $sql = "BEGIN MODULCHAT.CHATPKGACTUALIZACION.prcinsertmessage(partouserid=>:partouserid,
        parfromuserid=>:parfromuserid,
        parmessage=>:parmessage,
        parpkchat=>:parpkchat,
        parestado=>:parestado,
        parrespuesta=>:parrespuesta);
        END;";
        $conn = $this->db->conn_id;
        $stmt = oci_parse($conn, $sql);
        $parusersac = $this->session->userdata['PK_ENT_CODIGO'];
        $parestado = 1;
        oci_bind_by_name($stmt, ':partouserid', $to_user_id, 32);
        oci_bind_by_name($stmt, ':parfromuserid', $parusersac, 32);
        oci_bind_by_name($stmt, ':parmessage', $message, 32);
        oci_bind_by_name($stmt, ':parpkchat', $pk_chat, 32);
        oci_bind_by_name($stmt, ':parestado', $parestado, 32);
        oci_bind_by_name($stmt, ':parrespuesta', $parrespuesta, 32);
        if (!@oci_execute($stmt)) {
            $e = oci_error($stmt);
            var_dump($e);
        }
        if ($parrespuesta == 1) {
            return true;
        }
        return false;
    }

    //insert message client
    function insertMessage($parmessage, $parpkchat) {
        $sql = "BEGIN MODULCHAT.CHATPKGACTUALIZACION.prcinsertmessage(partouserid=>:partouserid,
                    parfromuserid=>:parfromuserid,
                    parmessage=>:parmessage,
                    parpkchat=>:parpkchat,
                    parestado=>:parestado,
                    parrespuesta=>:parrespuesta);
                    END;";
        $conn = $this->db->conn_id;
        $stmt = oci_parse($conn, $sql);
        $partouserid = $this->session->userdata['dataChat']['SAC_USER_ID'];
        $parclienteid = $this->session->userdata['usuario']['PK_ENT_CODIGO'];
        $parestado = 1;
        oci_bind_by_name($stmt, ':partouserid', $partouserid, 32);
        oci_bind_by_name($stmt, ':parfromuserid', $parclienteid, 32);
        oci_bind_by_name($stmt, ':parmessage', $parmessage, 250);
        oci_bind_by_name($stmt, ':parpkchat', $parpkchat, 32);
        oci_bind_by_name($stmt, ':parestado', $parestado, 32);
        oci_bind_by_name($stmt, ':parrespuesta', $parrespuesta, 32);
        if (!@oci_execute($stmt)) {
            $e = oci_error($stmt);
            var_dump($e);
        }
        if ($parrespuesta == 1) {
            return true;
        }
        return false;
    }

    //INsert meesage usersac
    function insertMessageSac($parmessage, $parpkchat, $idClient) {
        $sql = "BEGIN MODULCHAT.CHATPKGACTUALIZACION.prcinsertmessage(partouserid=>:partouserid,
                    parfromuserid=>:parfromuserid,
                    parmessage=>:parmessage,
                    parpkchat=>:parpkchat,
                    parestado=>:parestado,
                    parrespuesta=>:parrespuesta);
                    END;";
        $conn = $this->db->conn_id;
        $stmt = oci_parse($conn, $sql);
        $parsacid = $this->session->userdata['PK_ENT_CODIGO'];
        $parestado = 1;
        oci_bind_by_name($stmt, ':partouserid', $idClient, 32);
        oci_bind_by_name($stmt, ':parfromuserid', $parsacid, 32);
        oci_bind_by_name($stmt, ':parmessage', $parmessage, 32);
        oci_bind_by_name($stmt, ':parpkchat', $parpkchat, 32);
        oci_bind_by_name($stmt, ':parestado', $parestado, 32);
        oci_bind_by_name($stmt, ':parrespuesta', $parrespuesta, 32);
        if (!@oci_execute($stmt)) {
            $e = oci_error($stmt);
            var_dump($e);
        }
        if ($parrespuesta == 1) {
            $output = $this->fetch_user_chat_history_sac($idClient, $parpkchat);
            $this->output->set_content_type('text/css');
            $this->output->set_output($output);
        }
        return false;
    }

    function insertDetails($userId, $parstatus = "") {
        $sql = "BEGIN MODULCHAT.CHATPKGACTUALIZACION.prcinsertdetails(
                    paruserid=>:paruserid,
                    parlastactivity=>:parlastactivity,
                    paristype=>:paristype,
                    parstatus=>:parstatus,
                    parpkdetails=>:parpkdetails,
                    parrespuesta=>:parrespuesta);
                    END;";
        $conn = $this->db->conn_id;
        $stmt = oci_parse($conn, $sql);
        $paristype = 1;
        oci_bind_by_name($stmt, ':paruserid', $userId, 32);
        oci_bind_by_name($stmt, ':parlastactivity', $parlastactivity, 32);
        oci_bind_by_name($stmt, ':paristype', $paristype, 32);
        oci_bind_by_name($stmt, ':parstatus', $parstatus, 32);
        oci_bind_by_name($stmt, ':parpkdetails', $parpkdetails, 32);
        oci_bind_by_name($stmt, ':parrespuesta', $parrespuesta, 32);
        if (!@oci_execute($stmt)) {
            $e = oci_error($stmt);
            var_dump($e);
        }
        if ($parrespuesta == 1) {
            return $parpkdetails;
        }
        return null;
    }

    function update_last_activity() {
        $sql = "BEGIN MODULCHAT.CHATPKGACTUALIZACION.prcupdatelastactivity(
                    parpkdetails=>:parpkdetails,
                    parlastactivity=>to_date(:parlastactivity, 'DD/MM/YYYY HH24:MI:SS'),
                    parrespuesta=>:parrespuesta);
                    END;";
        $conn = $this->db->conn_id;
        $stmt = oci_parse($conn, $sql);
        $datetime = new DateTime();
        $datetime->setTimezone(new DateTimeZone('America/Bogota'));
        $newdate = $datetime->format('d/m/Y H:i:s');
        $parlastactivity = $newdate;
        $parpkdetails = $this->session->userdata['PK_DETAILS'];
        oci_bind_by_name($stmt, ':parpkdetails', $parpkdetails, 32);
        oci_bind_by_name($stmt, ':parlastactivity', $parlastactivity, 32);
        oci_bind_by_name($stmt, ':parrespuesta', $parrespuesta, 32);
        if (!@oci_execute($stmt)) {
            $e = oci_error($stmt);
            var_dump($e);
        }
        if ($parrespuesta == 1) {
            return true;
        }
        return false;
    }

    // Service for CHAT-SAC
    function fetch_user() {
        $userSacId = $this->session->userdata['PK_ENT_CODIGO'];
        $listClientes = $this->db->query("            
                select ent.apellido, ent.correo_electronico, ent.documento, ent.nombre, ent.razon_social, chat.pk_chat, chat.cliente_id 
                from MODCLIUNI.clitblentida ent join 
                MODULCHAT.chatblchat chat on chat.cliente_id = ent.pk_ent_codigo
                where  chat.status = 1 and chat.sacuser_id = " . $userSacId . "
            ");
        $listClientes = $listClientes->result_array;
        $output = '
                <table class="table table-bordered table-striped">
                    <tr>
                        <th w8idth="70%">Usuario</td>
                        <th width="20%">Estado</td>
                        <th width="10%">Accion</td>
                    </tr>
                ';
        for ($i = 0; $i < count($listClientes); $i++) {
            $status = '';
            $current_timestamp = strtotime(date("Y-m-d H:i:s") . '- 7 second');
            $current_timestamp = date('Y-m-d H:i:s', $current_timestamp);
            $user_last_activity = $this->fetch_user_last_activity($listClientes[$i]['CLIENTE_ID']);
            $datetime = new DateTime();
            $datetime->setTimezone(new DateTimeZone('America/Bogota'));
            $datetime->modify('-10 second');
            $newdate = $datetime->format('d-m-Y H:i:s');
            if ($user_last_activity > $newdate) {
                $status = '<span class="label label-success">En linea</span>';
            } else {
                $status = '<span class="label label-danger">Offline</span>';
            }

            $clienteId = $listClientes[$i]['CLIENTE_ID'];
            // ' . $this->count_unseen_message($clienteId, $userSacId) . ' 
            $output .= '
                    <tr>
                        <td>' . $listClientes[$i]['NOMBRE'] . ' ' . $listClientes[$i]['APELLIDO'] . ' ' . $this->fetch_is_type_status($clienteId) . '</td>
                        <td>' . $status . '</td>
                        <td><button type="button" class="btn btn-success btn-xs start_chat" data-touserid="' . $listClientes[$i]['CLIENTE_ID'] . '" data-tousername="' . $listClientes[$i]['NOMBRE'] . '"data-pkchat="' . $listClientes[$i]['PK_CHAT'] . '">Iniciar Chat</button></td>
                    </tr>
                    ';
        }

        $output .= '</table>';
        $this->output->set_content_type('text/css');
        $this->output->set_output($output);
    }

    function fetch_user_last_activity($idClient) {
        $fetch_user_last_activity = $this->db->query("
                    SELECT to_char(chat.LAST_ACTIVITY,'yyyy/mm/dd hh24:mi:ss') LAST_ACTIVITY FROM (SELECT det.last_activity FROM modulchat.chatbldetalles det 
                    WHERE det.user_id = " . $idClient . "
                    ORDER BY det.last_activity DESC) chat WHERE rownum <= 1             
                ");
        // and chat.status_message = 1
        $fetch_user_last_activity = $fetch_user_last_activity->result_array;
        foreach ($fetch_user_last_activity as $row) {
            return $row['LAST_ACTIVITY'];
        }
    }

    function lastActivity($idChat) {

        $user_last_activity = $this->db->query("
                select to_char(chat.fecha_creacion,'DD-MM-YYYY HH:MM:SS') from MODULCHAT.chatblmessage chat
                where chat.pk_message = ( select max(cha.pk_message) from MODULCHAT.chatblmessage cha where cha.chatblchat_pk_chat =" . $idChat . ")
                and chat.status_message = 1
                ");
        $user_last_activity = $user_last_activity->result_array;
        var_dump($user_last_activity);
        return $user_last_activity;
    }

    function count_unseen_message($clienteId, $sacUserId) {
        $count_unseen_message = $this->db->query("
                select COUNT(chat.pk_message) from MODULCHAT.chatblmessage chat
                where chat.status_message = 1 and chat.from_user_id =" . $clienteId . " and chat.to_user_id =" . $sacUserId . "
                ");
        $count_unseen_message = $count_unseen_message->result_array;
        if (count($count_unseen_message) > 0) {
            $output = '<span class="label label-success">' . $count_unseen_message . '</span>';
        } else
            $output;
        return $output;
    }

    function update_message_notseen($clienteId, $sacUserId) {
        $update_message_notseen = $this->db->query("
        update MODULCHAT.chatblmessage chat
        set chat.status_message = 0
        where chat.status_message = 1 and chat.from_user_id = " . $clienteId . " and chat.to_user_id = " . $clienteId . "
        ");
        $update_message_notseen = $update_message_notseen->result_array;
        return $update_message_notseen;
    }

    function fetch_is_type_status($user_id) {
        $result = $this->db->query("
        SELECT * FROM MODULCHAT.chatbldetalles det
        WHERE det.user_id =  " . $user_id . "   
        and det.status = 1
        ORDER BY det.last_activity DESC        
        ");
        $result = $result->result_array;
        $output = '';
        foreach ($result as $row) {
            if ($row["is_type"] == '1') {
                $output = ' - <small><em><span class="text-muted">Typing...</span></em></small>';
            }
        }
        return $output;
    }

    // PINTA HISTORY DE CHAT 
    function fetch_user_chat_history() {
        $last_history_chat = $this->db->query("
        select * from (SELECT DBMS_LOB.SUBSTR(dit.message,4000), dit.fecha_creacion, dit.from_user_id, dit.chatblchat_pk_chat FROM MODULCHAT.chatblmessage dit
                WHERE (dit.from_user_id = " . $this->session->userdata['usuario']['PK_ENT_CODIGO'] . "
                AND dit.to_user_id = " . $this->session->userdata['dataChat']['SAC_USER_ID'] . ")
                OR (dit.from_user_id = " . $this->session->userdata['dataChat']['SAC_USER_ID'] . "
                AND dit.to_user_id = " . $this->session->userdata['usuario']['PK_ENT_CODIGO'] . ")                           
                ORDER BY dit.fecha_creacion asc) even where even.chatblchat_pk_chat =" . $this->session->userdata['PK_CHAT'] . "");
        $last_history_chat = $last_history_chat->result_array;
        $output = '';
        for ($i = 0; $i < count($last_history_chat); $i++) {
            $user_name = '';
            $fromMessage = $this->session->userdata['usuario']['PK_ENT_CODIGO'];
            $nameFromMessage = $this->session->userdata['usuario']['NOMBRE'];
            if ($last_history_chat[$i]["FROM_USER_ID"] == $fromMessage) {
                $user_name = '<b class="text-success">Tu</b>';
            } else {
                $user_name = '<b class="text-danger">' . $nameFromMessage . '</b>';
            }
            $message = $last_history_chat[$i]["DBMS_LOB.SUBSTR(DIT.MESSAGE,4000)"];
            if ($last_history_chat[$i]['FROM_USER_ID'] === $this->session->userdata['usuario']['PK_ENT_CODIGO']) {
                $output .= '<li>
                            <div class="msj-rta macro-rta">
                                <div class="text text-r">
                                    <p>' . urldecode($message) . '</p>
                                    <p><small>' . $last_history_chat[$i]['FECHA_CREACION'] . '</small></p>              
                                </div>
                            </div>
                    </li>
            ';
            } else {
                $output .= '<li>
                            <div class="msj macro">
                                <div class="text text-r">
                                    <p>' . urldecode($message) . '</p>
                                    <p><small>' . $last_history_chat[$i]['FECHA_CREACION'] . '</small></p>              
                                </div>
                            </div>
                    </li>
            ';
            }
        }
        $sql = "BEGIN MODULCHAT.CHATPKGACTUALIZACION.prcupdatestatusmessage(
            parfromuserid=>:parfromuserid,
            partouserid=>:partouserid,
            parrespuesta=>:parrespuesta);
        END;";
        $conn = $this->db->conn_id;
        $stmt = oci_parse($conn, $sql);
        $parfromuserid = $this->session->userdata['usuario']['PK_ENT_CODIGO'];
        $partouserid = $this->session->userdata['dataChat']['SAC_USER_ID'];
        oci_bind_by_name($stmt, ':parfromuserid', $parfromuserid, 32);
        oci_bind_by_name($stmt, ':partouserid', $partouserid, 32);
        oci_bind_by_name($stmt, ':parrespuesta', $parrespuesta, 32);
        if (!@oci_execute($stmt)) {
            $e = oci_error($stmt);
            var_dump($e);
        }
        if ($parrespuesta == 1) {
            $this->output->set_content_type('text/css');
            $this->output->set_output($output);
        }
    }

    // PINTA HISTORY DE CHAT 
    function fetch_user_chat_history_sac($idCliente, $pkchat) {

        $last_history_chat = $this->db->query("
        select * from (SELECT DBMS_LOB.SUBSTR(dit.message,4000), dit.fecha_creacion, dit.from_user_id, dit.chatblchat_pk_chat FROM MODULCHAT.chatblmessage dit
                WHERE (dit.from_user_id = " . $this->session->userdata['PK_ENT_CODIGO'] . "
                AND dit.to_user_id = " . $idCliente . ")
                OR (dit.from_user_id = " . $idCliente . "
                AND dit.to_user_id = " . $this->session->userdata['PK_ENT_CODIGO'] . ")                           
                ORDER BY dit.fecha_creacion asc) even where even.chatblchat_pk_chat =" . $pkchat . "");
        $last_history_chat = $last_history_chat->result_array;
        $output = '';
        for ($i = 0; $i < count($last_history_chat); $i++) {
            $user_name = '';
            $fromMessage = $this->session->userdata['PK_ENT_CODIGO'];
            $nameFromMessage = $this->session->userdata['NOMBRE'];
            if ($last_history_chat[$i]["FROM_USER_ID"] == $fromMessage) {
                $user_name = '<b class="text-success">Tu</b>';
            } else {
                $user_name = '<b class="text-danger">' . $nameFromMessage . '</b>';
            }
            $message = $last_history_chat[$i]["DBMS_LOB.SUBSTR(DIT.MESSAGE,4000)"];
            if ($last_history_chat[$i]['FROM_USER_ID'] === $this->session->userdata['PK_ENT_CODIGO']) {
                $output .= '<li style="list-style-type: none;">
                            <div class="msj-rta macro-rta">
                                <div class="text text-r">
                                    <p>' . urldecode($message) . '</p>
                                    <p><small>' . $last_history_chat[$i]['FECHA_CREACION'] . '</small></p>              
                                </div>
                            </div>
                    </li>
            ';
            } else {
                $output .= '<li style="list-style-type: none;">
                            <div class="msj macro">
                                <div class="text text-r">
                                    <p>' . urldecode($message) . '</p>
                                    <p><small>' . $last_history_chat[$i]['FECHA_CREACION'] . '</small></p>              
                                </div>
                            </div>
                    </li>
            ';
            }
        }

        $sql = "BEGIN MODULCHAT.CHATPKGACTUALIZACION.prcupdatestatusmessage(
            parfromuserid=>:parfromuserid,
            partouserid=>:partouserid,
            parrespuesta=>:parrespuesta);
        END;";
        $conn = $this->db->conn_id;
        $stmt = oci_parse($conn, $sql);
        $parfromuserid = $this->session->userdata['usuario']['PK_ENT_CODIGO'];
        $partouserid = $this->session->userdata['dataChat']['SAC_USER_ID'];
        oci_bind_by_name($stmt, ':parfromuserid', $parfromuserid, 32);
        oci_bind_by_name($stmt, ':partouserid', $partouserid, 32);
        oci_bind_by_name($stmt, ':parrespuesta', $parrespuesta, 32);
        if (!@oci_execute($stmt)) {
            $e = oci_error($stmt);
            var_dump($e);
        }
        if ($parrespuesta == 1) {
            $this->output->set_content_type('text/css');
            $this->output->set_output($output);
            return $output;
        }
    }

    public function notification($idEnt) {
        if ($idEnt != "") {
            // BUSCA notificaciones de la entidad idEnt
            $notifications = $this->db->query("select usu.fecha_creacion FECHA_CREACIÓN,usu.PK_USUARIO_ALERTA Alerta_No, ale.mensaje ,usu.fecha_visto FECHA_VISTO,
                usu.fecha_gestiono FECHA_GESTION,usu.fecha_finalizo FECHA_FINALIZACION, eus.nombre estado,pri.nombre PRIORIDAD
                from MODSEGCOM.segtblusuale usu 
                left join MODSEGCOM.SEGTBLALERTA ale on ale.PK_ALE_CODIGO = usu.FK_SEGTBLUSUALE_SEGTBLALERTA
                join MODSEGCOM.segtbleusale eus on eus.pk_eus_codigo = usu.fk_segtblusuale_segtbleusale
                join MODSEGCOM.segtblpriori pri on pri.pk_pri_codigo = usu.fk_segtblusuale_segtblpriori
                where  usu.fk_usuario_entidad = $idEnt and eus.pk_eus_codigo=1");

            $noti = $notifications->result_array;
            /* header('Content-Type: application/json');
              echo json_encode( $notifications->result_array );
              //return $notifications->result_array; */
            $output = '';
            foreach ($noti as $notification) {
                $output .= '<div id="' . $notification['ALERTA_NO'] . '" class="notificacion-interno" onclick="this.style.display=\'none\';changestatus(' . $notification['ALERTA_NO'] . ');">
                                               <div class="img-notificacion" >
                                            <div class="img-noti-envio"></div>
                                        </div>
                                        ' . $notification['MENSAJE'] . '
                                    </div>';
            } $output .= '<p id="numNotiback" hidden>' . count($noti) . '</p>';
        }
        $this->output->set_content_type('text/css');
        $this->output->set_output($output);
    }

    function update_is_type_status($is_type) {
        // $sql = "BEGIN MODULCHAT.CHATPKGACTUALIZACION.prcupdatelastactivity(
        //     parpkdetails=>:parpkdetails,
        //     parlastactivity=>:parlastactivity,
        //     parrespuesta=>:parrespuesta);
        //     END;";
        // $conn = $this->db->conn_id;
        // $stmt = oci_parse($conn, $sql);
        // var_dump($this->session->userdata['PK_DETAILS']);
        // $datetime = new DateTime();
        // $datetime->setTimezone(new DateTimeZone('America/Bogota'));
        // $newdate = $datetime->format('Y/m/d H:i:s');
        // $parlastactivity = $newdate;
        // var_dump($parlastactivity);
        // $parpkdetails = $this->session->userdata['PK_DETAILS'];
        // oci_bind_by_name($stmt, ':parpkdetails', $parpkdetails, 32);
        // oci_bind_by_name($stmt, ':parlastactivity', $parlastactivity, 32);
        // oci_bind_by_name($stmt, ':parrespuesta', $parrespuesta, 32);
        // if (!@oci_execute($stmt)) {
        //     $e = oci_error($stmt);
        //     var_dump($e);
        // }
        // if ($parrespuesta == 1) {
        //     return true;
        // }
        // return false;
    }

    public function returnDataEditarDetSol($codigoDetalle) {
        log_info($this->logHeader . 'INGRESO AJAX RETORNA DETALLE EDITAR SOLICITUD');
        log_info($this->postData . '$codigoDetalle= ' . $codigoDetalle);
        if ($codigoDetalle != null) {
            //$usuario = $this->session->userdata("usuario");
            $usuario = $_SESSION['usuario'];
            $coordinador = $usuario["PK_ENT_CODIGO"];
            //$campana = $this->session->userdata("campana");
            $campana = $_SESSION['campana'];
            //$pkEmpresa = $this->session->userdata("pkentidad");
            $pkEmpresa = $_SESSION['pkentidad'];
            $dataUser = $this->db->query("select pro.nombre_producto PRODUCTO,pro.pk_produc_codigo,tipdoc.NOMBRE TIPDOC,det.documento DOCUMENTO,
                                    det.pk_codigo_solicitud,det.pk_detalle_solicitud,nvl(det.pk_ent_custodio,0) pk_ent_custodio, 
                                    det.primer_nombre, det.segundo_nombre, det.primer_apellido ,det.segundo_apellido, det.correo_electronico,
                                    det.telefono,det.identificador_tarjeta 
                                    from MODPREPEDIDO.PREPETBLDETALLESOLICITUD det 
                                    join modproduc.PROTBLPRODUC pro on pro.pk_produc_codigo = det.pk_producto
                                    join MODPREPEDIDO.prepetblsolicitud sol ON det.pk_codigo_solicitud=sol.pk_codigo_solicitud
                                    JOIN MODCLIUNI.CLITBLTIPDOC TIPDOC  on tipdoc.pk_td_codigo= det.clitbltipdoc_pk_td_codigo
                                    where sol.pk_ent_solicitud = $coordinador and sol.pk_emp_solicitud = $pkEmpresa
                                    and sol.pk_campana_codigo = $campana
                                    and det.pk_detalle_solicitud = $codigoDetalle");
            $data_body['data'] = $dataUser->result_array;

            $productos = $this->db->query("SELECT  DISTINCT p.NOMBRE_PRODUCTO , p.pk_produc_codigo,
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
                AND  PK_ENTIDA_CLIENTE = $pkEmpresa
                AND co.pk_campana_codigo = {$campana}");
            $data_body['productos'] = $productos->result_array;
            $custodio = $this->db->query("select ent.nombre ||' '|| ent.apellido || ' - '||ent.documento NOMBRE,
            ent.pk_ent_codigo CODIGOENTIDA
                        from modcliuni.clitblvincul vin 
                        join modcliuni.clitblentida ent on ent.pk_ent_codigo = vin.CLITBLENTIDA_PK_ENT_CODIGO
                        and vin.clitbltipvin_pk_tipvin_codigo = 46 
                        and vin.fecha_fin is null
                        and vin.clitblentida_pk_ent_codigo1=$pkEmpresa
                        and vin.CLITBLCAMPAN_PK_CAMPAN_CODIGO=$campana");
            $data_body['custodios'] = $custodio->result_array;
        }
//        if(!empty($data_body['data']->result_array)){
        $this->load->view('administrador/ajax/dataDetalleEditar', $data_body);
//            }
    }

    public function returnDataEditarDetAbo($codDetalleAbono) {
        log_info($this->logHeader . 'INGRESO AJAX RETORNA DETALLE EDITAR ABONO');
        log_info($this->postData . 'CODDETALLE ABONO= ' . $codDetalleAbono);
        if ($codDetalleAbono != null) {
            //$usuario = $this->session->userdata("usuario");
            $usuario = $_SESSION['usuario'];
            $coordinador = $usuario["PK_ENT_CODIGO"];
            //$campana = $this->session->userdata("campana");
            $campana = $_SESSION['campana'];
            //$pkEmpresa = $this->session->userdata("pkentidad");
            $pkEmpresa = $_SESSION['pkentidad'];
            $dataAbonos = $this->db->query("select pro.nombre_producto PRODUCTO, 
                                    pro.PK_PRODUC_CODIGO PK_PRODUCTO_CODIGO,
                                    tipdoc.nombre TIPDOC,
                                    tipdoc.PK_TD_CODIGO,
                                    det.documento DOCUMENTO,
                                    det.identificador_tarjeta,
                                    det.monto_abono,
                                    to_char(det.fecha_dispersion,'YYYY-MM-DD') fecha_dispersion,
                                    det.pk_codigo_solicitud,det.pk_detalle_solicitud
                                    from MODPREPEDIDO.PREPETBLDETALLESOLICITUD det 
                                    join modproduc.PROTBLPRODUC pro on pro.pk_produc_codigo = det.pk_producto
                                    join MODPREPEDIDO.prepetblsolicitud sol ON det.pk_codigo_solicitud=sol.pk_codigo_solicitud
                                    left JOIN MODCLIUNI.CLITBLTIPDOC TIPDOC  on tipdoc.pk_td_codigo= det.clitbltipdoc_pk_td_codigo
                                    where sol.pk_ent_solicitud = $coordinador and sol.pk_emp_solicitud = $pkEmpresa
                                    and sol.pk_campana_codigo = $campana
                                    and det.pk_detalle_solicitud = $codDetalleAbono");
            $data_body['data'] = $dataAbonos->result_array;
            $this->load->view('administrador/ajax/dataDetalleEditarAbono', $data_body);
        }
    }

    public function returnDataFact($solicitudes, $pkorden = null) {
        log_info($this->logHeader . 'INGRESO AJAX RETORNA DETALLE FACTURA PREPEDIDO');
        log_info($this->postData . 'SOLICITUDES= ' . $solicitudes);
        if (!empty($pkorden))
            $data_body['pk_preorden_codigo'] = $pkorden;
        if ($solicitudes != null) {
            $data_body['sol'] = $solicitudes;
            $solicitudes = str_replace('-', ',', $solicitudes);
            log_info($this->postData . 'SOLICITUDES DESPUES= ' . $solicitudes);
            //$pkEmpresa = $this->session->userdata("pkentidad");
            $pkEmpresa = $_SESSION['pkentidad'];
            //$campana = $this->session->userdata("campana");
            $campana = $_SESSION['campana'];
            //Consulta costos abonos
            $dataAbonos = $this->db->query("select 'ABONO A PRODUCTO '||producto.nombre_producto producto
                ,count(detallesolicitud.pk_producto) cantidad
                ,detallesolicitud.PK_PRODUCTO pk_producto
                ,sum(detallesolicitud.MONTO_ABONO) valor_unitario 
                ,sum(detallesolicitud.MONTO_ABONO) valor_total
                from  MODPREPEDIDO.prepetblsolicitud solicitud
                join MODPREPEDIDO.prepetbldetallesolicitud detallesolicitud
                ON solicitud.pk_codigo_solicitud=detallesolicitud.pk_codigo_solicitud
                join modproduc.protblproduc producto
                ON detallesolicitud.pk_producto=producto.PK_PRODUC_CODIGO
                where solicitud.pk_codigo_solicitud in ($solicitudes)
                and solicitud.pk_tipsol_codigo in (3,4)
                and solicitud.pk_campana_codigo = {$campana}
                group by 'ABONO A PRODUCTO '||producto.nombre_producto, detallesolicitud.pk_producto");
            $data_body['dataAbonos'] = $dataAbonos->result_array;
//            Consulta costos administrativos
            $dataGastosAd = $this->db->query("select 'SERVICIO ADMINISTRACION ABONO A PRODUCTO '||producto.nombre_producto producto
                ,count(detallesolicitud.pk_producto) cantidad,
                detallesolicitud.PK_PRODUCTO pk_producto,
                CASE WHEN round(valor_tarjetas.tasa) <=100 THEN 
                sum(detallesolicitud.MONTO_ABONO*(valor_tarjetas.tasa/100))
                ELSE
                SUM(valor_tarjetas.tasa)
                END valor_unitario
                from  MODPREPEDIDO.prepetblsolicitud solicitud
                join MODPREPEDIDO.prepetbldetallesolicitud detallesolicitud
                ON solicitud.pk_codigo_solicitud=detallesolicitud.pk_codigo_solicitud
                join modcomerc.view_valor_tarjetas valor_tarjetas
                on valor_tarjetas.pk_entida_cliente=solicitud.pk_emp_solicitud
                and valor_tarjetas.pk_campana_codigo=solicitud.pk_campana_codigo
                and detallesolicitud.pk_producto=valor_tarjetas.pk_producto_codigo
                join modproduc.protblproduc producto
                ON detallesolicitud.pk_producto=producto.PK_PRODUC_CODIGO
                where solicitud.pk_codigo_solicitud in ($solicitudes)
                and solicitud.pk_tipsol_codigo in (3,4)
                and solicitud.pk_campana_codigo = {$campana}
                group by 'SERVICIO ADMINISTRACION ABONO A PRODUCTO '||producto.nombre_producto, detallesolicitud.pk_producto,valor_tarjetas.tasa");
            $data_body['dataAdmin'] = $dataGastosAd->result_array;
            // Consulta cobros de tarjetas
            $dataTarjetas = $this->db->query("select producto.nombre_producto producto
            ,count(pk_producto) cantidad
            ,PK_PRODUCTO
            ,valor_tarjetas.valor_unitario valor_unitario 
            ,(count(pk_producto)* valor_tarjetas.valor_unitario) valor_total
            from  MODPREPEDIDO.prepetblsolicitud solicitud
            join MODPREPEDIDO.prepetbldetallesolicitud detallesolicitud
            ON solicitud.pk_codigo_solicitud=detallesolicitud.pk_codigo_solicitud
            join modcomerc.view_valor_tarjetas valor_tarjetas
            on valor_tarjetas.pk_entida_cliente=solicitud.pk_emp_solicitud
            and valor_tarjetas.pk_campana_codigo=solicitud.pk_campana_codigo
            and detallesolicitud.pk_producto=valor_tarjetas.pk_producto_codigo
            join modproduc.protblproduc producto
            ON detallesolicitud.pk_producto=producto.PK_PRODUC_CODIGO
            where solicitud.pk_codigo_solicitud in ($solicitudes)
            and solicitud.pk_tipsol_codigo in (1,2)
            and solicitud.pk_campana_codigo = {$campana}
            group by producto.nombre_producto, pk_producto,valor_tarjetas.valor_unitario");
            $data_body['dataTarjetas'] = $dataTarjetas->result_array;

            $telefono = $this->db->query("SELECT DATO FROM MODCLIUNI.CLITBLCONTAC WHERE PK_CONTAC_CODIGO"
                    . "= MODCLIUNI.CLIPKGCONSULTAS.fncmaxpkcontacto({$pkEmpresa},47)");
            $data_body['telefono'] = $telefono->result_array[0];
            $direccion = $this->db->query("SELECT DATO FROM MODCLIUNI.CLITBLCONTAC WHERE PK_CONTAC_CODIGO"
                    . "= MODCLIUNI.CLIPKGCONSULTAS.fncmaxpkcontacto({$pkEmpresa},48)");
            $data_body['direccion'] = $direccion->result_array[0];
            $datosdir = explode('|', $data_body['direccion']['DATO']);
            $data_body['direccion'] = $datosdir[0];
            $ciudad = $this->db->query("SELECT pais.NOMBRE NOMBREPAIS, dep.NOMBRE NOMBREDEPARTAMENTO,ciu.nombre NOMBRECIUDAD
                FROM MODCLIUNI.CLITBLPAIS pais
                JOIN MODCLIUNI.CLITBLDEPPAI dep
                ON pais.pk_pais_codigo=dep.clitblpais_pk_pais_codigo
                JOIN MODCLIUNI.CLITBLCIUDAD ciu
                ON ciu.CLITBLDEPPAI_PK_DEP_CODIGO=dep.pk_dep_codigo
                JOIN MODCLIUNI.CLITBLENTIDA ent
                ON ent.clitblciudad_pk_ciu_codigo=ciu.pk_ciu_codigo
                WHERE ent.pk_ent_codigo={$pkEmpresa}");
            $data_body['ciudad'] = $ciudad->result_array[0];

            //total
            $totalTarje = 0;
            $totalAbono = 0;
            $totalAdm = 0;
            $tarjetas = $dataTarjetas->result_array;
            $abonos = $dataAbonos->result_array;
            $gasAdm = $dataGastosAd->result_array;
            if (!empty($tarjetas)) {
                foreach ($tarjetas as $value) {
                    $totalTarje += $value['VALOR_TOTAL'];
                }
            }
            if (!empty($abonos)) {
                foreach ($abonos as $value) {
                    $totalAbono += $value['VALOR_TOTAL'];
                }
            }
            if (!empty($gasAdm)) {
                foreach ($gasAdm as $value) {
                    $totalAdm += $value['VALOR_TOTAL'];
                }
            }
            $valorTotal = $totalTarje + $totalAbono + $totalAdm;
            $data_body['ValorTotal'] = $valorTotal;
            $valLetras = $this->numeros_letras($valorTotal);
//            CifrasEnLetras.convertirCifrasEnLetras($valorTotal);
            $data_body['ValorLetrasTotal'] = $valLetras;
            $this->load->view('administrador/ajax/dataDetallePrefac', $data_body);
        }
    }

    public function returnDataOrdenes($ordenes) {
        log_info($this->logHeader . 'INGRESO AJAX RETORNA DETALLE ORDENES PARA VISTA PAGO');
        log_info($this->postData . 'CODDETALLE ABONO= ' . $ordenes);
        if ($ordenes != null) {

            $ordenes = str_replace('-', ',', $ordenes);
            //impuestos
            $impuestos = $this->db->query("select 
						sum(fac.pma) INGRESOS_TERCEROS,
						sum(MODFACTUR.facpkgconsultas.fncconsultarimpuestofactura(parpkfactura=>FAC.pk_factur_codigo,parnombreimpuesto=>'IVA%')) IVA,
						sum(MODFACTUR.facpkgconsultas.fncconsultarimpuestofactura(parpkfactura=>FAC.pk_factur_codigo,parnombreimpuesto=>'RTE FTE%')) RTE_FUENTE,
						sum(MODFACTUR.facpkgconsultas.fncconsultarimpuestofactura(parpkfactura=>FAC.pk_factur_codigo,parnombreimpuesto=>'RTE ICA%')) RTE_ICA,
						sum(MODFACTUR.facpkgconsultas.fncconsultarimpuestofactura(parpkfactura=>FAC.pk_factur_codigo,parnombreimpuesto=>'RTE IVA%')) RTE_IVA,
						sum(FAC.PCO) INGRESOS_PROPIOS,
						sum(FAC.TOTAL) TOTAL
						from MODFACTUR.factblfacord ord join  MODFACTUR.factblfactur fac
                                                On ord.pk_factur_codigo = fac.pk_factur_codigo
						where ord.pk_ordcom_codigo = some($ordenes)");
            $valorimpuestos = $impuestos->result_array[0];
            $ingresos_propios = $valorimpuestos['INGRESOS_PROPIOS'];
            $ingresos_terceros = $valorimpuestos['INGRESOS_TERCEROS'];
            $iva = $valorimpuestos['IVA'];
            $rte_fuente = $valorimpuestos['RTE_FUENTE'];
            $rte_ica = $valorimpuestos['RTE_ICA'];
            $rte_iva = $valorimpuestos['RTE_IVA'];
            $subtotalPropios = ($ingresos_propios - $iva) + $rte_fuente + $rte_ica + $rte_iva;

            $data_body['SubTotalIngresosPropios'] = $subtotalPropios;
            $data_body['IngresosPropios'] = $ingresos_propios;
            $data_body['IngresosTerceros'] = $ingresos_terceros;
            $data_body['Iva'] = $iva;
            $data_body['rteFuente'] = $rte_fuente;
            $data_body['rteIca'] = $rte_ica;
            $data_body['rteIva'] = $rte_iva;
            $data_body['total'] = $valorimpuestos['TOTAL'];


            $this->load->view('administrador/ajax/dataDetalleOrden', $data_body);
        }
    }

    public function numeros_letras($valor = 0) {

        $sql = "begin 
             :varvalor:=modgeneri.genpkgutilidades.numero_a_letras($valor);
            end;";

        $conn = $this->db->conn_id;
        $stmt = oci_parse($conn, $sql);
        $valorletras = '';
        oci_bind_by_name($stmt, ':varvalor', $valorletras, 200);

        if (!oci_execute($stmt)) {
            $e = oci_error($stmt);
            $valorletras = '';
            VAR_DUMP($e);
            exit;
        }
        return $valorletras;
    }

}
