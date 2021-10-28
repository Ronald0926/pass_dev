<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Login extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
    }
    public function __destruct()
    {
        $this->db->close();
    }
    var $data;
    
    public function loginChat()
    {
        $tipodocumento = $this->db->query('SELECT PK_TD_CODIGO,ABREVIACION,NOMBRE FROM MODCLIUNI.CLITBLTIPDOC');
        $data['tipoDocumento'] = $tipodocumento->result_array;
        $this->load->view('chat/login/loginchat', $data);
    }

    public function loginController()
    {
        $post = $this->input->post();
        if ($post) {
            $tipodocumento = $post['tipoDocumento'];
            $documento = $post['documento'];
            $pass = $post['pass'];
            //Consulta login          
            $dataUser = $this->db->query("SELECT ent.nombre, ent.documento, ent.apellido, ent.correo_electronico, ent.pk_ent_codigo"
                . " FROM modcliuni.clitblentida ent"
                . " JOIN MODCLIUNI.CLITBLVINCUL vin "
                . "ON vin.clitblentida_pk_ent_codigo=ent.pk_ent_codigo "
                . "JOIN MODGENERI.gentblentare entarea "
                . "ON ent.pk_ent_codigo=entarea.pk_entida_codigo "
                . "JOIN MODGENERI.GENTBLAREA area "
                . "ON entarea.pk_area_codigo=area.pk_are_codigo "
                . "WHERE CLITBLTIPDOC_PK_TD_CODIGO='$tipodocumento' "
                . "AND DOCUMENTO='$documento' "
                . "AND PIN_ACCESO= modgeneri.genpkgvalidaciones.FNCCLIHASH('$pass') "
                . "AND vin.clitbltipvin_pk_tipvin_codigo= 48 "
                . "AND ent.CLITBLESTUSU_PK_ESTUSU_CODIGO= 1 "
                . "AND area.pk_are_codigo in (26,27) "
                . "AND vin.FECHA_FIN is   null "
                . "AND entarea.pk_estent_codigo=1");

            $exitoso = $dataUser->result_array[0];
            if (empty($exitoso)) {
                $this->session->set_userdata('errorData', "Los datos Ingresados son Incorrectos");
                redirect("/index.php/chat/login/loginchat");
            }
            $this->session->set_userdata($exitoso);
            $usuario = $this->session->userdata("usuario");
            // ACTUALIZACION DE ESTADO
            $sql = "BEGIN MODULCHAT.CHATPKGACTUALIZACION.PRCCREARCONEXIONSAC(parsacuserid=>:parsacuserid,
                                                                         parestado=>:parestado,
                                                                          parpkconexionsac=>:parpkconexionsac,
                                                                           parrespuesta=>:parrespuesta);            
                    END;";
            $conn = $this->db->conn_id;
            $stmt = oci_parse($conn, $sql);
            $parsacuserid = $exitoso['PK_ENT_CODIGO'];
            $parestado = 1; // estado activo
            oci_bind_by_name($stmt, ':parsacuserid', $parsacuserid, 32);
            oci_bind_by_name($stmt, ':parestado', $parestado, 32);
            oci_bind_by_name($stmt, ':parpkconexionsac', $parpkconexionsac, 32);
            oci_bind_by_name($stmt, ':parrespuesta', $parrespuesta, 32);
            if (!@oci_execute($stmt)) {
                $e = oci_error($stmt);
                var_dump($e);
            }
            if ($parrespuesta == 1) {
                $this->session->set_userdata('CONEXION_CHAT', $parpkconexionsac);                
                redirect("/chat/principal/pantalla");
            }
        } else {
            $message = '<label>Contraseña o usuario incorrecto</label>';
        }
        // $tipodocumento = $this->db->query('SELECT PK_TD_CODIGO,ABREVIACION,NOMBRE FROM MODCLIUNI.CLITBLTIPDOC');
        // $data['tipoDocumento'] = $tipodocumento->result_array;
    }

    public function cerrarSesion()
    {
        $sql = "BEGIN MODULCHAT.CHATPKGACTUALIZACION.prcupdateconexionsac (parpkconexionsac=>:parpkconexionsac,
        parestado=>:parestado,
        parrespuesta=>:parrespuesta);
        END;";
        $conn = $this->db->conn_id;
        $stmt = oci_parse($conn, $sql);
        $parpkconexionsac = $this->session->userdata['CONEXION_CHAT'];
        $parestado = 0; // estado inactivo
        //TIPO NUMBER INPUT
        oci_bind_by_name($stmt, ':parpkconexionsac', $parpkconexionsac, 32);
        //TIPO NUMBER INPUT
        oci_bind_by_name($stmt, ':parestado', $parestado, 32);
        //TIPO NUMBER OUTPUT
        oci_bind_by_name($stmt, ':parrespuesta', $parrespuesta, 32);
        if (!@oci_execute($stmt)) {
            $e = oci_error($stmt);
            var_dump($e);
        }
        if ($parrespuesta == 1) {
            $this->session->sess_destroy();
            redirect('chat/login/loginchat');
        }
        
    }
}
