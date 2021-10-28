<?php
session_start();
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class CampanasCreacion extends CI_Controller {

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

    public function lista() {
        $this->verificarPerfilCo();
        //$pkEntidad = $this->session->userdata("pkentidad");
        $pkEntidad = $_SESSION['pkentidad'];
        $lista = $this->db->query("select ent.pk_ent_codigo CODIGOENT ,ent.nombre NOMBREENT , ent.apellido APELLIDO
        ,cam.pk_campan_codigo CODIGOCAMPANA, cam.nombre Campana, tipvin.nombre TIPVIN
        from MODCLIUNI.CLITBLVINCUL vin 
        join modcliuni.clitblcampan cam 
        on cam.pk_campan_codigo = vin.clitblcampan_pk_campan_codigo
        join modcliuni.clitblentida ent 
        on ent.pk_ent_codigo = vin.clitblentida_pk_ent_codigo
        join modcliuni.clitbltipvin tipvin
        ON tipvin.pk_tipvin_codigo=vin.clitbltipvin_pk_tipvin_codigo
        where vin.CLITBLENTIDA_PK_ENT_CODIGO1=$pkEntidad
        AND tipvin.pk_tipvin_codigo IN (45,46,47)
        and vin.fecha_fin is null");
        $lista = $lista->result_array;
        $data['lista'] = $lista;
        $campana = $this->db->query("select distinct cam.pk_campan_codigo CODIGOCAMPAN, cam.nombre
        from MODCLIUNI.CLITBLVINCUL vin 
        join modcliuni.clitblcampan cam 
        on cam.pk_campan_codigo = vin.clitblcampan_pk_campan_codigo
        join modcliuni.clitblentida ent 
        on ent.pk_ent_codigo = vin.clitblentida_pk_ent_codigo
        where vin.CLITBLENTIDA_PK_ENT_CODIGO1=$pkEntidad
        and vin.fecha_fin is null");
        $data['campanas'] = $campana->result_array;
        //$ultimaconexion = $this->session->userdata("ultimaconexion");
        $ultimaconexion = $_SESSION['ultimaconexion'];
        $data['ultimaconexion'] = $ultimaconexion;

        //$empresa = $this->session->userdata("entidad");
        $empresa = $_SESSION['entidad'];
        $data['empresa'] = $empresa['NOMBREEMPRESA'];
        //$usuario = $this->session->userdata("usuario");
        $usuario = $_SESSION['usuario'];
        $data['usuario'] = $usuario['NOMBRE'] . " " . $usuario['APELLIDO'];
        $this->load->view('portal/templates/header2', $data);
        $this->load->view('portal/campanasCreacion/lista', $data);
        $this->load->view('portal/templates/footer', $data);
    }

    public function crear() {
        $this->verificarPerfilCo();
        $post = $this->input->post();
        if ($post) {
            //print_r($post);
            
            
            //$pkEntidad = $this->session->userdata("pkentidad");
            $pkEntidad = $_SESSION['pkentidad'];
            $sql = " BEGIN MODGENERI.GENPKGWEBSERVICE.PRCCREARCAMPANA (:parempresa 
                           ,:parnombre
                           ,:paridcampana
                           ,:parusuario
                           ,:parrespuest); END;";

            $conn = $this->db->conn_id;
            $stmt = oci_parse($conn, $sql);

            $parempresa = $pkEntidad;
            $parnombre = $post['campana'];
            $parrespuesta = '';
            $paridcampana = '';
            //$parusuario = $this->session->userdata['usuario']['USUARIO_ACCESO'];
            $parusuario = $_SESSION['usuario']['USUARIO_ACCESO'];
            //TIPO NUMBER INPUT INPUT 
            oci_bind_by_name($stmt, ':parempresa', $parempresa, 100);
            //TIPO NUMBER INPUT INPUT 
            oci_bind_by_name($stmt, ':parusuario', $parusuario, 100);
            //TIPO VARCHAR2 INPUT
            oci_bind_by_name($stmt, ':parnombre', $parnombre, 100);
            //TIPO NUMBER OUTPUT
            oci_bind_by_name($stmt, ':paridcampana', $paridcampana, 100);
            //TIPO VARCHAR2 OUTPUT
            oci_bind_by_name($stmt, ':parrespuest', $parrespuesta, 100);
            if (!@oci_execute($stmt)) {
                $e = oci_error($stmt);
                var_dump("{$e['message']}");
                $data['error'] = 1;
                // $this->load->view('portal/usuariosCreacion/crear', $data);
            } else if ($parrespuesta != 1) {
                $data['error'] = 2;
            } else if ($parrespuesta == 1) {

                        /*vinculan el empleado   con  la  campaña*/
                /*foreach ($post['usuarios'] as $value) {
                    $sql = "BEGIN"
                            . " MODTARHAB.tarpkgfunciones.prccreavinculacion(:paraempleado"
                            . " , :parempresa "
                            . " , :parafechainicio "
                            . " , :partipvinc "
                            . " , :parcampana "
                            . " , :parmensaj );"
                            . "END;";
                    $conn = $this->db->conn_id;
                    $stmt = oci_parse($conn, $sql);
                    $fechavincul = $this->db->query("select to_char(sysdate,'dd-mm-yyyy') from dual");
                    $fechavincul = $fechavincul->result_array[0]['SYSDATE'];
                    $tipoVinculacion = $post[$value . "_roles"];// es eklrol del man 

                    //TIPO NUMBER INPUT
                    oci_bind_by_name($stmt, ':paraempleado', $value, 32);
                    //TIPO NUMBER INPUT
                    oci_bind_by_name($stmt, ':parempresa', $parempresa, 32);
                    //TIPO DATE INPUT
                    oci_bind_by_name($stmt, ':parafechainicio', $fechavincul, 32);
                    //TIPO NUMBER INPUT
                    oci_bind_by_name($stmt, ':partipvinc', $tipoVinculacion, 32);
                    //TIPO NUMBER INPUT
                    oci_bind_by_name($stmt, ':parcampana', $paridcampana, 32);
                    //TIPO VARCHAR2 OUTPUT
                    oci_bind_by_name($stmt, ':parmensaj', $parrespuesta, 32);
                    if (!@oci_execute($stmt)) {
                        $e = oci_error($stmt);
                        var_dump("{$e['message']}");
                        $data['error'] = 1;
                    }
                }*/
                if ($parrespuesta == 1) {
                    $_SESSION['success'] = 1;
                    //$['creaciocampana'] = $parnombre = $post['campana'];
                
                    redirect('portal/campanasCreacion/lista/');
                } else {
                    $_SESSION['success']='';
                    $data['error'] = 2;
                }
                // }
            }
        }
 

        //$pkEntidad = $this->session->userdata("pkentidad");
        /*$pkEntidad = $_SESSION['pkentidad'];

        $usuario = $this->db->query("select distinct ent.pk_ent_codigo CODIGOENT,ent.nombre NOMBREENT , ent.apellido APELLIDO
        from MODCLIUNI.CLITBLVINCUL vin 
        join modcliuni.clitblcampan cam 
        on cam.pk_campan_codigo = vin.clitblcampan_pk_campan_codigo
        join modcliuni.clitblentida ent 
        on ent.pk_ent_codigo = vin.clitblentida_pk_ent_codigo
        join modcliuni.clitbltipvin tipvin
        ON tipvin.pk_tipvin_codigo=vin.clitbltipvin_pk_tipvin_codigo
        where vin.CLITBLENTIDA_PK_ENT_CODIGO1=$pkEntidad
            and tipvin.pk_tipvin_codigo in(45,46,47)
        and vin.fecha_fin is null");
        $usuarios = $usuario->result_array;
        $data['usuarios'] = $usuarios;

        $roles = $this->db->query("select pk_tipvin_codigo CODIGO,nombre 
                from modcliuni.clitbltipvin 
                where pk_tipvin_codigo in (45,46,47) ");

        $data['roles'] = $roles->result_array;
        //$empresa = $this->session->userdata("entidad");
        $empresa = $_SESSION['entidad'];
        $data['empresa'] = $empresa['NOMBREEMPRESA'];
        //$usuario = $this->session->userdata("usuario");
        
      
     
       */
    
       $empresa = $_SESSION['entidad'];
        $data['empresa'] = $empresa['NOMBREEMPRESA'];
        $usuario = $_SESSION['usuario'];
        $ultimaconexion = $_SESSION['ultimaconexion'];
        $data['usuario'] = $usuario['NOMBRE'] . " " . $usuario['APELLIDO'];
        $data['ultimaconexion'] = $ultimaconexion;
        $this->load->view('portal/templates/header2', $data);
        $this->load->view('portal/campanasCreacion/crear', $data);
        $this->load->view('portal/templates/footer', $data);
    }

}
