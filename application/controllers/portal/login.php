<?php
session_start();
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Login extends CI_Controller {

    public $logHeader = 'APOLOINFO::::::::: ';
    public $postData = 'POSTDATA::::::::: ';
    public $queryData = 'QUERYDATA::::::: ';

    public function __construct() {
        parent::__construct();
        // error_reporting(3);
        $this->load->helper('log4php');
    }

    public function __destruct() {
        $this->db->close();
    }

    var $data;

    public function validar($ok = 0) {
        log_info($this->logHeader . 'Ingreso loging empresarial');

        $post = $this->input->post();

        if ($post) {
            log_info($this->logHeader . $this->postData . 'NIT: ' . $post['nit'] . ' Tipo Doc: ' . $post['tipoDocumento'] . ' Documento: ' . $post['documento']);
            $sqlurlPassOnline = $this->db->query("SELECT VALOR_PARAMETRO  FROM  MODGENERI.GENTBLPARGEN par WHERE PK_PARGEN_CODIGO=94");
            $sqlurlRestValida = $this->db->query("SELECT VALOR_PARAMETRO  FROM  MODGENERI.GENTBLPARGEN par WHERE PK_PARGEN_CODIGO=95");
            $urlPassOnline = $sqlurlPassOnline->result_array[0]['VALOR_PARAMETRO'];
            $urlRestValida = $sqlurlRestValida->result_array[0]['VALOR_PARAMETRO'];
            $nit = $post['nit'];
            $pkentida = "
                SELECT distinct
                    PK_ENT_CODIGO,
                    NVL(ent.RAZON_SOCIAL, ent.NOMBRE||' '||ent.APELLIDO) NOMBREEMPRESA,
                    tpdoc.NOMBRE,
                    DOCUMENTO,
                    ICONO
                 FROM
                    MODCLIUNI.CLITBLENTIDA ent
                    JOIN MODCLIUNI.CLITBLTIPDOC tpdoc
                    ON ent.CLITBLTIPDOC_PK_TD_CODIGO=tpdoc.PK_TD_CODIGO
                    JOIN MODCLIUNI.CLITBLVINCUL vinculaciones
                    ON vinculaciones.clitblentida_pk_ent_codigo1=ent.pk_ent_codigo
                WHERE
                    DOCUMENTO=TRIM('$nit')
                    AND ent.CLITBLESTENT_PK_EST_CODIGO in (3,6,23)";

            $entidad = $this->db->query($pkentida);


            $tipoDocumento = $post['tipoDocumento'];
            $documento = $post['documento'];

            $sql = "SELECT
                    entidad.pk_ent_codigo,
                    entidad.nombre,
                    entidad.apellido,
                    entidad.clitbltipdoc_pk_td_codigo,
                    entidad.documento,
                    entidad.usuario_acceso,
                    tipdoc.codigo_pasarela
                FROM
                    modcliuni.clitblentida   entidad
                    JOIN modcliuni.clitbltipdoc   tipdoc
                    ON entidad.clitbltipdoc_pk_td_codigo = tipdoc.pk_td_codigo
                WHERE
                    entidad.clitbltipdoc_pk_td_codigo = '$tipoDocumento'
                    AND entidad.documento = TRIM('$documento')
                    ";
            //var_dump($sql);
            //echo '<br/>';
            $usuario = $this->db->query($sql);

            if ($usuario->result_array[0] == NULL or $entidad->result_array[0] == NULL) {

                $curl = curl_init();
                $cedula_cliente = $documento; //11111114
                curl_setopt_array($curl, array(
                    CURLOPT_URL => $urlRestValida,
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_ENCODING => "",
                    CURLOPT_MAXREDIRS => 10,
                    CURLOPT_TIMEOUT => 0,
                    CURLOPT_FOLLOWLOCATION => true,
                    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                    CURLOPT_CUSTOMREQUEST => "POST",
                    CURLOPT_POSTFIELDS => "consulta=1&cedula=$cedula_cliente",
                    CURLOPT_SSL_VERIFYPEER => false,
                    CURLOPT_HTTPHEADER => array(
                        "Content-Type: application/x-www-form-urlencoded"
                    ),
                ));
                $response = curl_exec($curl);
                curl_close($curl);
                if ($response == 1) {
                    redirect($urlPassOnline);
                }

                $this->session->sess_destroy();
                redirect("/index.php/portal/login/validar/1");
            } else {
                $codigoentidad = $entidad->result_array[0]['PK_ENT_CODIGO'];
                $queryrol = $this->db->query("select ent.pk_ent_codigo 
                     FROM modcliuni.clitblentida ent 
                     JOIN MODCLIUNI.CLITBLVINCUL vin  
                     ON vin.clitblentida_pk_ent_codigo=ent.pk_ent_codigo 
                     where CLITBLTIPDOC_PK_TD_CODIGO= '$tipoDocumento'
                     AND DOCUMENTO= TRIM('$documento')
                     AND vin.clitbltipvin_pk_tipvin_codigo IN (45,46,47,56,58,59,60,61)  
                     AND ent.CLITBLESTUSU_PK_ESTUSU_CODIGO=1 
                     AND vin.clitblentida_pk_ent_codigo1= '$codigoentidad' 
                     AND vin.FECHA_FIN IS NULL");
                $queryrol = $queryrol->result_array[0];

                if (empty($queryrol)) {
                    log_info($this->logHeader . 'Usuario no existe');
                    $this->session->sess_destroy();
                    redirect("/index.php/portal/login/validar/3");
                }

                //var_dump($entidad);
                // exit();
                $_SESSION['usuario'] = $usuario->result_array[0];
                $_SESSION['pkentidad'] = $entidad->result_array[0]['PK_ENT_CODIGO'];
                $_SESSION['entidad'] = $entidad->result_array[0];
                
                /*
                  $this->session->set_userdata(
                  array(
                  'usuario' => $usuario->result_array[0],
                  'pkentidad' => $entidad->result_array[0]['PK_ENT_CODIGO'],
                  'entidad' => $entidad->result_array[0])); */
                redirect("/index.php/portal/login/validarCampana");
            }
        }

        $tipodocumento = $this->db->query("SELECT PK_TD_CODIGO,ABREVIACION,NOMBRE FROM MODCLIUNI.CLITBLTIPDOC
                                            WHERE PK_TD_CODIGO IN (67,68,69,70) ");

        $data['tipoDocumento'] = $tipodocumento->result_array;
        $data['ok'] = $ok;
        $this->load->view('portal/templates/headerlogin', $data);
        $this->load->view('portal/login/validar', $data);
        $this->load->view('portal/templates/footerlogin', $data);
    }

    function validarCaptcha() {
        try {
            $secretKey = '6Ldnk8QUAAAAAG2ji2CjHiA_jqoQJ18K6WKqH_jH';
            $verifyResponse = file_get_contents('https://www.google.com/recaptcha/api/siteverify?secret=' . $secretKey . '&response=' . $_POST['g-recaptcha-response']);
            $responseData = json_decode($verifyResponse);
            if ($responseData->success) {
                return true;
            } else {
                return false;
            }
        } catch (Exception $exc) {
            echo $exc->getTraceAsString();
        }
    }

    public function validarCampana() {
        $post = $this->input->post();
        //log_info($this->logHeader . ' Validar Campaña');
        
        if ($post) {
 

            $boolCaptcha = $this->validarCaptcha();
            /* if (!$boolCaptcha) {
              $this->session->set_userdata('errorCaptcha', "Catpcha Obligatorio o Invalido");
              redirect("/index.php/portal/login/validarCampana");
              } */
            unset($this->session->userdata['errorCaptcha']);
            unset($this->session->userdata['errorData']);
            //$usuario = $this->session->userdata("usuario");
            $usuario = $_SESSION['usuario'];
            $tipodocumento = $usuario['CLITBLTIPDOC_PK_TD_CODIGO'];
            $documento = $usuario['DOCUMENTO'];
            $pass = $post['pass'];
            //$pkentida = $this->session->userdata("entidad");
            $pkentida = $_SESSION['entidad'];
            
            log_info($this->logHeader . $this->postData . 'Campaña: ' . $post['campana'] . ' Rol: ' . $post['rol']);
            log_info($this->logHeader . ' INFO Entidad= ' . $pkentida['PK_ENT_CODIGO']);
            log_info($this->logHeader . ' INFO Usuario= ' . $usuario['PK_ENT_CODIGO']);
            $pkentida = $pkentida['PK_ENT_CODIGO'];
            log_info($this->logHeader . $this->postData . $pkentida);

            $sqlexitoso="select '1'"

            . " FROM modcliuni.clitblentida ent"
            . " JOIN MODCLIUNI.CLITBLVINCUL vin "
            . " ON vin.clitblentida_pk_ent_codigo=ent.pk_ent_codigo"
            . " where CLITBLTIPDOC_PK_TD_CODIGO='$tipodocumento' "
            . " AND DOCUMENTO='$documento'"
            . " AND PIN_ACCESO= modgeneri.genpkgvalidaciones.FNCCLIHASH('$pass') "
            . " AND vin.clitblcampan_pk_campan_codigo='{$post['campana']}'"
            . " AND vin.clitbltipvin_pk_tipvin_codigo='{$post['rol']}'"
            . " AND ent.CLITBLESTUSU_PK_ESTUSU_CODIGO=1"
            . " AND vin.clitblentida_pk_ent_codigo1= '$pkentida' 
                    AND vin.FECHA_FIN IS NULL ";

           
            $query = $this->db->query($sqlexitoso);

            $exitoso = $query->result_array[0];
            log_info($this->logHeader . $this->queryData . 'EXITOSO: ' . $exitoso);



            if (empty($usuario) || empty($exitoso)) {
                log_info($this->logHeader . 'Usuario no existe');
                $this->session->set_userdata('errorData', "Los datos Ingresados son Incorrectos");
                redirect("/index.php/portal/login/validarCampana");
            }

            //Se valida si usuario tiene rol 58 y no tiene activo anticipo se redeirecciona validar campana
            $pagoanticipoact = $this->db->query("SELECT 
                                        anticipo.pk_ent_codigo
                                        from modcomerc.comtblanticipo anticipo
                                         JOIN modcliuni.clitblentida entidad
                                         ON anticipo.pk_ent_codigo=entidad.pk_ent_codigo
                                         AND anticipo.solicitud_anticipo = 1
                                            AND anticipo.gestionado = 2
                                            and anticipo.aprobado=1
                                            and anticipo.pk_estant_codigo=1
                                            AND entidad.pk_ent_codigo= $pkentida");
            $pagoanticipoActivo = $pagoanticipoact->result_array[0];


            

            $pagoanticipo= 1;
            $activeAnticipo="";
            if (is_null($pagoanticipoActivo)) {
                $activeAnticipo = 0;
            } else {
                $activeAnticipo= 1;
            }
            $_SESSION['activeanticipo']=$activeAnticipo;
            //anticipo no esta activo
            if ($pagoanticipo == 0 && $post['rol'] == 58) {
                log_info($this->logHeader . 'El producto anticipo no esta activo.');
                $this->session->set_userdata('errorData', "El producto anticipo no esta activo.");
                redirect("/index.php/portal/login/validarCampana");
            }
            unset($this->session->userdata['errorData']);
            //$this->session->set_userdata(array('campana' => $post['campana'], 'rol' => $post['rol']));
            $_SESSION['campana'] = $post['campana'];
            $_SESSION['rol'] = $post['rol'];

            //$this->session->set_userdata(array('campana' => $post['campana'], 'rol' => $post['rol']));
            //$usuario = $this->session->userdata("usuario");
            $usuario = $_SESSION['usuario'];
            $parusuarioconexion = $usuario['USUARIO_ACCESO'];
            log_info($this->logHeader . $this->postData . $usuario['USUARIO_ACCESO']);
            try {
                $ultimaconexion = $this->db->query("SELECT to_char(MAX(FECHA_CREACION), 'mm/dd/yyyy HH12:mi am') FECHA
                                            FROM MODGENERI.GENTBLCONWEB WHERE USUARIO_CONEXION='$parusuarioconexion'");
                $ultimaconexion = $ultimaconexion->result_array[0];
                log_info($this->logHeader . $this->queryData . ' ultimaConexion :' . $ultimaconexion['FECHA']);
            } catch (Exception $ex) {
                log_info($this->logHeader . $queryData . ' ERROR CONSULTA ULTIMA CONEXION  ' . $ex->getMessage());
            }
            //$this->session->set_userdata(array('ultimaconexion' => $ultimaconexion['FECHA']));
            $_SESSION['ultimaconexion'] = $ultimaconexion['FECHA'];
            try {
                $tipoDocumento = $this->db->query("SELECT ABREVIACION "
                        . "from MODCLIUNI.CLITBLTIPDOC "
                        . "WHERE PK_TD_CODIGO = $tipodocumento");
                $abreviacion = $tipoDocumento->result_array[0];
            } catch (Exception $ex) {
                log_info($this->logHeader . ' ERROR CONSULTA TIPO DOCUMENTO ' . $ex->getMessage());
            }

            $sql = "BEGIN modgeneri.genpkgadmiweb.prcguardarconexion (
                parusuarioconexion =>:parusuarioconexion,
                parnavegador =>:parnavegador,
                paripconexion =>:paripconexion,
                parrespuesta  =>:parrespuesta ); END;";
            $conn = $this->db->conn_id;
            $stmt = oci_parse($conn, $sql);
            $parentidad = $abreviacion['ABREVIACION'] . $documento;
            $ip = $_SERVER['REMOTE_ADDR'];
            $parrespuest = '';
            $parnavegador = $_SERVER['HTTP_USER_AGENT'];
            //TIPO NUMBER INPUT
            oci_bind_by_name($stmt, ':parusuarioconexion', $parentidad, 32);
            //TIPO NUMBER INPUT
            oci_bind_by_name($stmt, ':parnavegador', $parnavegador, 1000);
            //TIPO NUMBER INPUT
            oci_bind_by_name($stmt, ':paripconexion', $ip, 32);
            //TIPO VARCHAR2 OUTPUT
            oci_bind_by_name($stmt, ':parrespuesta', $parrespuest, 100);
            try {
                if (!@oci_execute($stmt)) {
                    $e = oci_error($stmt);
                    var_dump($e);
                    $data['ok'] = 1;
                    var_dump($parentidad);
                    var_dump($correo);
                    var_dump($ip);
                    var_dump($parrespuest);
                    //exit();
                }
            } catch (Exception $ex) {
                log_info($this->logHeader . $this->queryData . ' ERROR GUARDAR CONEXION ' . $ex->getMessage());
            }
            //REVISION TERMINSO PENDIENTES
            //$usuario = $this->session->userdata("usuario");
            $usuario = $_SESSION['usuario'];
            $parpkentidad = $usuario['PK_ENT_CODIGO'];
            try {
                $terminos = $this->db->query("SELECT DISTINCT
                pol.pk_polpriv_codigo   codigo,
                pol.nombre_politica     nombre_politica,
                pol.obligatorio         obligatorio,
                pol.url				  	url
                FROM
                modgeneri.gentblpolpriv   pol
                LEFT JOIN modgeneri.gentblaceter    ace ON ace.pk_polpriv_codigo = pol.pk_polpriv_codigo
                                                        AND ace.pk_entida_codigo = $parpkentidad
                WHERE pol.pk_appws_codigo=3 
                AND ( ace.pk_aceter_codigo IS NULL 
                OR pol.fecha_actualizacion > (SELECT MAX(ace.fecha_creacion)  
                                                FROM modgeneri.gentblpolpriv   pol
                                                LEFT JOIN modgeneri.gentblaceter    ace 
                                                ON ace.pk_polpriv_codigo = pol.pk_polpriv_codigo
                                                AND ace.pk_entida_codigo = $parpkentidad)
        )");
                $terminos = $terminos->result_array;
            } catch (Exception $ex) {
                log_info($this->logHeader . $this->queryData . ' ERROR TERMINOS Y CONDICIONES ' . $ex->getMessage());
            }
            // se agrega datos de llave maestra si se encuentra activa para la entidad que se esta logueando
            //$pk_ent_codigo = $this->session->userdata("pkentidad");
            $pk_ent_codigo = $_SESSION['pkentidad'];
            try {
                $productos = $this->db->query("select producto1.PK_PRODUC_CODIGO CODIGO,producto1.NOMBRE_PRODUCTO ,producto1.PK_TIPPRO_CODIGO Codigo_Producto,
                                    NVL(producto1.PK_LINPRO_CODIGO,0) CODIGOL,
                                    NVL(PARAMETRO.CANTIDAD,0) CANTIDAD,
                                    NVL(PARAMETRO.ACTIVO,0) ACTIVO
                                    FROM MODPRODUC.protblproduc producto1
                                    LEFT JOIN
                                    (SELECT parametro.pk_producto_codigo CODIGO,'1' ACTIVO,parametro.CANTIDAD
                                           from MODCOMERC.comtblparame parametro 
                                     JOIN MODCOMERC.comtblproces proceso
                                    ON proceso.pk_proces_codigo=parametro.pk_proces_codigo
                                     JOIN MODCOMERC.comtblcotiza cotizacion
                                    on cotizacion.pk_cotiza_codigo=proceso.pk_cotiza_codigo
                                    JOIN MODPRODUC.PROTBLPRODUC producto 
                                    ON producto.pk_produc_codigo=parametro.pk_producto_codigo
                                    where  cotizacion.pk_entida_cliente=$pk_ent_codigo
                                    and cotizacion.pk_estado_codigo=1
                                    and proceso.pk_estado_codigo=1) PARAMETRO
                                    on producto1.pk_produc_codigo = parametro.CODIGO");
                $productos = $productos->result_array;
            } catch (Exception $ex) {
                log_info($this->logHeader . $this->queryData . ' ERROR CONSULTA PRODUCTO LLAVE MAESTRA ' . $ex->getMessage());
            }
            $sqlllavemaestra = $this->db->query("select llavemae.llavmae_codigo codllave from modllavemaestra.llavetblllavmae llavemae
                    where llavemae.pk_ent_codigo=$pk_ent_codigo and llavemae.pk_estado=1");
            $llavemaestra = $sqlllavemaestra->result_array[0];
         
            // buscar producto cuenta maestra con codigo 70 lo asigna a session
            if (!empty($llavemaestra['CODLLAVE'])) {
                for ($i = 0; $i < count($productos); $i++) {
                    if ($productos[$i]['ACTIVO'] == 1 && $productos[$i]['CODIGO'] == 70) {
                        //$this->session->set_userdata(array('NOMBRE_PRODUCTO' => $productos[$i]['NOMBRE_PRODUCTO'], 'CODIGO_PRODUCTO' => $productos[$i]['CODIGO'], 'TIPCODIGO_PRODUCTO' => $productos[$i]['CODIGO_PRODUCTO']));
                        
                        $_SESSION['PRODUCTOLLAVE']=array('NOMBRE_PRODUCTO' => $productos[$i]['NOMBRE_PRODUCTO'], 'CODIGO_PRODUCTO' => $productos[$i]['CODIGO'], 'TIPCODIGO_PRODUCTO' => $productos[$i]['CODIGO_PRODUCTO']);
                    }
                }
             
            }

            //Estado entidad para perfil pagador
            $sqlestado = $this->db->query("select estent.nombre ESTADO from
                modcliuni.clitblentida ent
                join modcliuni.clitblestent estent
                on ent.clitblestent_pk_est_codigo = estent.pk_est_codigo
                where ent.pk_ent_codigo= $pk_ent_codigo");
            $Estado = $sqlestado->result_array[0];
            //$this->session->set_userdata(array('ESTADO_ENTIDAD' => $Estado['ESTADO']));
            $_SESSION['ESTADO_ENTIDAD'] = $Estado['ESTADO'];
            if ($terminos) {
                //terminos
                redirect("/portal/login/validarTerminos");
            }
            redirect("/portal/principal/pantalla");
        }
        //$usuario = $this->session->userdata("usuario");
        $usuario = $_SESSION['usuario'];
        $tipodocumento = $usuario['CLITBLTIPDOC_PK_TD_CODIGO'];
        $documento = $usuario['DOCUMENTO'];
        //$pkEntidad = $this->session->userdata("pkentidad");
        $pkEntidad = $_SESSION['pkentidad'];
        log_info($this->logHeader . ' Validar pkentidad '.$pkEntidad);
        log_info($this->logHeader . ' Validar documento '.$documento);
        log_info($this->logHeader . ' Validar tipodocumento '.$tipodocumento);
        $data['campana'] = $this->db->query("SELECT DISTINCT CAM.PK_CAMPAN_CODIGO,CAM.NOMBRE  "
                . " FROM MODCLIUNI.CLITBLENTIDA ENT"
                . " JOIN MODCLIUNI.CLITBLVINCUL VINUSU"
                . " ON VINUSU.CLITBLENTIDA_PK_ENT_CODIGO = ENT.PK_ENT_CODIGO"
                . " JOIN MODCLIUNI.CLITBLENTIDA ENTEMP"
                . " ON ENTEMP.PK_ENT_CODIGO = VINUSU.CLITBLENTIDA_PK_ENT_CODIGO1"
                . " JOIN MODCLIUNI.CLITBLCAMPAN CAM"
                . " ON CAM.PK_CAMPAN_CODIGO  = VINUSU.CLITBLCAMPAN_PK_CAMPAN_CODIGO"
                . " WHERE ENT.CLITBLTIPDOC_PK_TD_CODIGO = '$tipodocumento'"
                . " AND ENT.DOCUMENTO =TRIM('$documento')"
                . " AND VINUSU.CLITBLENTIDA_PK_ENT_CODIGO1='$pkEntidad'");
        $data['campana'] = $data['campana']->result_array;
       // var_dump($data['campana']);
        log_info($this->logHeader . ' data[campana] '.$data['campana']->result_array );
        $data['rol'] = $this->db->query("SELECT DISTINCT PK_TIPVIN_CODIGO,TIPVIN.NOMBRE FROM MODCLIUNI.CLITBLENTIDA ENT"
                . " JOIN MODCLIUNI.CLITBLVINCUL VINUSU"
                . " ON VINUSU.CLITBLENTIDA_PK_ENT_CODIGO = ENT.PK_ENT_CODIGO"
                . " JOIN MODCLIUNI.CLITBLENTIDA ENTEMP "
                . " ON ENTEMP.PK_ENT_CODIGO = VINUSU.CLITBLENTIDA_PK_ENT_CODIGO1"
                . " JOIN MODCLIUNI.CLITBLTIPVIN TIPVIN"
                . " ON TIPVIN.PK_TIPVIN_CODIGO = VINUSU.CLITBLTIPVIN_PK_TIPVIN_CODIGO"
                . " WHERE ENT.CLITBLTIPDOC_PK_TD_CODIGO = '$tipodocumento'"
                . " AND ENT.DOCUMENTO =TRIM('$documento')"
                . " AND VINUSU.CLITBLENTIDA_PK_ENT_CODIGO1='$pkEntidad'"
                . " AND TIPVIN.PK_TIPVIN_CODIGO IN (45, 46,47,56,58,59,60,61)"
                . " AND FECHA_FIN IS NULL");
        $data['rol'] = $data['rol']->result_array;
        log_info($this->logHeader . ' data[rol] '.$data['rol']['PK_TIPVIN_CODIGO'] );
        //var_dump($data['rol']);
      //  exit();
        $this->load->view('portal/templates/headerlogin', $data);
        $this->load->view('portal/login/validarCampana', $data);
        $this->load->view('portal/templates/footerlogin', $data);
    }

    public function validarTerminos() {
        $post = $this->input->post();
        if ($post) {
            //$usuario = $this->session->userdata("usuario");
            $usuario = $_SESSION['usuario'];
            $parpkentidad = $usuario['PK_ENT_CODIGO'];
            $parusuarioconexion = $usuario['USUARIO_ACCESO'];
            $check = $post['check'];
            foreach ($check as $value) {
                $parrespuesta = '';
                try {
                    $sql = "BEGIN modgeneri.genpkgadmiweb.prcguardarterminos (
                    parpkentidad=>:parpkentidad,
                    parpkterminos=>:parpkterminos,
                    parusuarioconexion=>:parusuarioconexion,
                    parrespuesta=>:parrespuesta
                    ); END;";

                    $conn = $this->db->conn_id;
                    $stmt = oci_parse($conn, $sql);

                    //TIPO NUMBER INPUT
                    oci_bind_by_name($stmt, ':parpkentidad', $parpkentidad, 32);
                    //TIPO NUMBER INPUT
                    oci_bind_by_name($stmt, ':parpkterminos', $value, 32);
                    //TIPO NUMBER INPUT
                    oci_bind_by_name($stmt, ':parusuarioconexion', $parusuarioconexion, 32);
                    //TIPO VARCHAR2 OUTPUT
                    oci_bind_by_name($stmt, ':parrespuesta', $parrespuesta, 100);
                    if (!@oci_execute($stmt)) {
                        $e = oci_error($stmt);
                        var_dump($e);
                        $data['ok'] = 1;
                        var_dump($parentidad);
                        var_dump($correo);
                        var_dump($ip);
                        var_dump($parrespuest);
                        // exit();
                    }
                } catch (Exception $ex) {
                    log_info($this->logHeader . ' ERROR GUARDAR TERMINOS Y CONDICIONES ' . $ex->getMessage());
                }
            }
            $terminos = $this->db->query("select pol.pk_polpriv_codigo
            from MODGENERI.gentblpolpriv pol
            left join modgeneri.gentblaceter ace
            on ace.pk_polpriv_codigo = pol.pk_polpriv_codigo
            and ace.pk_entida_codigo = $parpkentidad
            where (ace.pk_aceter_codigo is null or pol.fecha_actualizacion > ace.fecha_creacion)
            AND pol.obligatorio = 2
            AND pol.pk_appws_codigo=3");

            $terminosaceptar = $terminos->result_array;

            foreach ($terminosaceptar as $value) {
                $parrespuesta = '';
                $sql = "BEGIN modgeneri.genpkgadmiweb.prcguardarterminos (
                    parpkentidad=>:parpkentidad,
                    parpkterminos=>:parpkterminos,
                    parusuarioconexion=>:parusuarioconexion,
                    parrespuesta=>:parrespuesta
                    ); END;";
                $conn = $this->db->conn_id;
                $stmt = oci_parse($conn, $sql);
                //TIPO NUMBER INPUT
                oci_bind_by_name($stmt, ':parpkentidad', $parpkentidad, 32);
                //TIPO NUMBER INPUT
                oci_bind_by_name($stmt, ':parpkterminos', $value['PK_POLPRIV_CODIGO'], 32);
                //TIPO NUMBER INPUT
                oci_bind_by_name($stmt, ':parusuarioconexion', $parusuarioconexion, 32);
                //TIPO VARCHAR2 OUTPUT
                oci_bind_by_name($stmt, ':parrespuesta', $parrespuesta, 100);
                if (!@oci_execute($stmt)) {
                    $e = oci_error($stmt);
                    var_dump($e);
                    $data['ok'] = 1;
                    exit();
                }
            }
            //$ultimaconexion = $this->session->userdata("ultimaconexion");
            $ultimaconexion = $_SESSION['ultimaconexion'];
            if ($ultimaconexion) {
                redirect("/portal/principal/pantalla");
            } else {
                redirect("/portal/ayuda/control");
            }
        }
        //$usuario = $this->session->userdata("usuario");
        $usuario = $_SESSION['usuario'];
        $parpkentidad = $usuario['PK_ENT_CODIGO'];
        $terminos = $this->db->query("SELECT DISTINCT
        pol.pk_polpriv_codigo   codigo,
        pol.nombre_politica     nombre_politica,
        pol.obligatorio         obligatorio,
        pol.url				  	url
        FROM
        modgeneri.gentblpolpriv   pol
        LEFT JOIN modgeneri.gentblaceter    ace ON ace.pk_polpriv_codigo = pol.pk_polpriv_codigo
                                                AND ace.pk_entida_codigo = $parpkentidad
        WHERE pol.pk_appws_codigo=3 
        AND ( ace.pk_aceter_codigo IS NULL 
        OR pol.fecha_actualizacion > (SELECT MAX(ace.fecha_creacion)  
                                                FROM modgeneri.gentblpolpriv   pol
                                                LEFT JOIN modgeneri.gentblaceter    ace 
                                                ON ace.pk_polpriv_codigo = pol.pk_polpriv_codigo
                                                AND ace.pk_entida_codigo = $parpkentidad)
        )");
        $data['terminos'] = $terminos->result_array;

        $this->load->view('portal/login/validarTerminos', $data);
    }

    public function olvido($ok = 0) {
        $post = $this->input->post();
        if ($post) {
            $tipoDocumento = $this->db->query("SELECT ABREVIACION "
                    . "from MODCLIUNI.CLITBLTIPDOC "
                    . "WHERE PK_TD_CODIGO = {$post['tipoDocumento']}");
            $abreviacion = $tipoDocumento->result_array[0];
            $sql = "BEGIN modgeneri.genpkgfunciones.PRCOLVIDECONTRA(
                    :parusuario,
                    :parcorreo,
                    :PARIP ,
                    :PARRESPUE );
                    END;";

            $conn = $this->db->conn_id;
            $stmt = oci_parse($conn, $sql);
            $parentidad = $abreviacion['ABREVIACION'] . $post['documento'];
            $correo = $post['correo'];
            $ip = 0; //$_SERVER['REMOTE_ADDR'];
            $parrespuest = '';

            //TIPO NUMBER INPUT
            oci_bind_by_name($stmt, ':parusuario', $parentidad, 32);
            //TIPO NUMBER INPUT
            oci_bind_by_name($stmt, ':parcorreo', $correo, 100);
            //TIPO NUMBER INPUT
            oci_bind_by_name($stmt, ':PARIP', $ip, 32);
            //TIPO VARCHAR2 OUTPUT
            oci_bind_by_name($stmt, ':PARRESPUE', $parrespuest, 100);
            if (!@oci_execute($stmt)) {
                $e = oci_error($stmt);
                var_dump($e);
                $data['ok'] = 1;
                var_dump($parentidad);
                var_dump($correo);
                var_dump($ip);
                var_dump($parrespuest);
                // exit();
            }
            // var_dump($parrespuest);
            //     exit();
            if ($parrespuest == 1) {
                redirect("portal/login/olvidoMensaje/2");
            } else {
                $ok = $parrespuest;
                $data['ok'] = $ok;
                redirect("portal/login/olvido/{$data['ok']}");
            }
        }

        $tipodocumento = $this->db->query('SELECT PK_TD_CODIGO,ABREVIACION,NOMBRE FROM MODCLIUNI.CLITBLTIPDOC WHERE PK_TD_CODIGO IN (67,68,69,70)');
        $data['tipoDocumento'] = $tipodocumento->result_array;
        $data['ok'] = $ok;

        $this->load->view('portal/templates/headerlogin', $data);
        $this->load->view('portal/login/olvido', $data);
        $this->load->view('portal/templates/footerlogin', $data);
    }

    public function olvidoMensaje($ok = 0) {
        $tipodocumento = $this->db->query('SELECT PK_TD_CODIGO,ABREVIACION,NOMBRE FROM MODCLIUNI.CLITBLTIPDOC WHERE PK_TD_CODIGO IN (67,68,69,70)');
        $data['tipoDocumento'] = $tipodocumento->result_array;
        $data['ok'] = $ok;

        redirect('portal/login/validar/' . $ok, $data);
        //$this->load->view('portal/login/olvidoMensaje', $data_header);
    }

    public function cambiarContrasena() {
        //$usuario = $this->session->userdata("usuario");
        $usuario = $_SESSION['usuario'];
        $post = $this->input->post();
        if ($post) {
            //$usuario = $this->session->userdata("usuario");
            $usuario = $_SESSION['usuario'];
            $sql = "BEGIN"
                    . " MODGENERI.GENPKGWEBSERVICE.PRCCAMBCONTRASE("
                    . " :parusuario,"
                    . " :parcontactu,"
                    . " :parcontnueva,"
                    . " :parverinueva,"
                    . " :PARIP,"
                    . " :PARRESPUES);"
                    . "EXCEPTION
                           WHEN no_data_found THEN
                           :PARRESPUES:='La informaciÃ³n ingresada no es valida';"
                    . " END;";

            $conn = $this->db->conn_id;
            $stmt = oci_parse($conn, $sql);
            $parentidad = $usuario['USUARIO_ACCESO'];
            $contrasenaActual = $post['contrasenaActual'];
            $contrasenaNueva = $post['contrasenaNueva'];
            $contrasenaVerifica = $post['contrasenaVerifica'];
            $ip = $_SERVER['REMOTE_ADDR'];
            $parrespuest = '';

            // var_dump($parentidad);
            // var_dump($contrasenaActual);
            // exit();
            //TIPO NUMBER INPUT
            oci_bind_by_name($stmt, ':parusuario', $parentidad, 32);
            //TIPO NUMBER INPUT
            oci_bind_by_name($stmt, ':parcontactu', $contrasenaActual, 32);
            //TIPO DATE INPUT
            oci_bind_by_name($stmt, ':parcontnueva', $contrasenaNueva, 32);
            //TIPO NUMBER INPUT
            oci_bind_by_name($stmt, ':parverinueva', $contrasenaVerifica, 32);
            //TIPO NUMBER INPUT
            oci_bind_by_name($stmt, ':PARIP', $ip, 32);
            //TIPO VARCHAR2 OUTPUT
            oci_bind_by_name($stmt, ':PARRESPUES', $parrespuest, 100);
            if (!@oci_execute($stmt)) {
                $e = oci_error($stmt);
                var_dump($e);
                $data['ok'] = 1;
            } else if ($parrespuest != 1) {
                //var_dump("IP $ip - $parrespuest - entidad $parentidad actual $contrasenaActual");
                $data['ok'] = 2;
                $data['codigo'] = $parrespuest;
            } else if ($parrespuest == 1) {
                // var_dump("IP $ip - $parrespuest - entidad $parentidad actual $contrasenaActual");
                $data['ok'] = 3;
            }
        }

        $empresa = $this->session->userdata("entidad");
        //´perfil super pagador
        $rol = $this->session->userdata("rol");
        if ($rol == 58) {
            $sqlAnticipo = $this->db->query("select distinct
                ent.pk_ent_codigo CODIGO_ENTIDAD,
                CASE
                    WHEN proc.aprobado = 1 THEN 'SI'
                    ELSE 'NO'
                END AS ANTICIPO,
                bol.DIAS_MAXIMO DIAS_ANTICIPO,
                to_char(MODGENERI.GENPKGCLAGEN.DECRYPT(bol.tope_maximo),'FML999G999G999G999G990D00') CUPO_ANTICIPO,
                to_char(MODGENERI.GENPKGCLAGEN.DECRYPT(bol.saldo),'FML999G999G999G999G990D00') CUPO_USADO,
                to_char((MODGENERI.GENPKGCLAGEN.DECRYPT(bol.monto_temporal) ),'FML999G999G999G999G990D00') EXTRACUPO
                ,to_char((MODGENERI.GENPKGCLAGEN.DECRYPT(bol.tope_maximo) + MODGENERI.GENPKGCLAGEN.DECRYPT(bol.saldo)+(MODGENERI.GENPKGCLAGEN.DECRYPT(bol.monto_temporal))),'FML999G999G999G999G990D00') CUPO_DISPONIBLE

            from 
                MODCLIUNI.clitblentida ent
                join modcliuni.clitblvincul vin on vin.clitblentida_pk_ent_codigo = ent.pk_ent_codigo
                join modcliuni.clitbltipent tipent on tipent.pk_tipent_codigo = ent.clitbltipent_pk_tipent_codigo
                join modcliuni.clitblestent estent on estent.pk_est_codigo = ent.clitblestent_pk_est_codigo
                join modcliuni.clitblestusu estusu on estusu.pk_estusu_codigo = ent.clitblestusu_pk_estusu_codigo
                                                    AND vin.clitbltipvin_pk_tipvin_codigo = 50
                left join modcomerc.comtblcotiza coti on coti.pk_entida_cliente = ent.pk_ent_codigo 
                                                    AND coti.pk_estado_codigo = 1
                left join MODCOMERC.comtblproces proc on proc.pk_cotiza_codigo = coti.pk_cotiza_codigo 
                                                    AND proc.pk_estado_codigo = 1
                join MODSALDOS.saltblbolsil bol on bol.pk_ent_codigo = ent.pk_ent_codigo 
                                                    AND bol.pk_tipbol_codigo = 3
                WHERE ent.pk_ent_codigo={$empresa['PK_ENT_CODIGO']}");
            $data['dataAnticipo'] = $sqlAnticipo->result_array[0];
        }
        $data['empresa'] = $empresa['NOMBREEMPRESA'];
        //$usuario = $this->session->userdata("usuario");
        $usuario = ['usuario'];
        $data['usuario'] = $usuario['NOMBRE'] . " " . $usuario['APELLIDO'];
        //$ultimaconexion = $this->session->userdata("ultimaconexion");
        $ultimaconexion = $_SESSION['ultimaconexion'];
        $data['ultimaconexion'] = $ultimaconexion;
        $this->load->view('portal/templates/header2', $data);
        $this->load->view('portal/login/cambiarContrasena', $data);

        $this->load->view('portal/templates/footer', $data);
    }

    public function cambioSesion() {
        //$usuario = $this->session->userdata("usuario");
        $usuario = $_SESSION['usuario'];
        $post = $this->input->post();
        if ($post) {
            $sql = "BEGIN modgeneri.GENPKGWEBSERVICE.PRCCAMBIOSESION(
                    :parpkempresa,
                    :parpkcampana,
                    :parpktipvin,
                    :parnitempresa,
                    :parpkentidad,
                    :parcontrasena,
                    :parresultul);
                    END;";
            $conn = $this->db->conn_id;
            $stmt = oci_parse($conn, $sql);
            $parpkempresa = $post['empresas'];
            $parpkcampana = $post['campana'];
            $parpktipvin = $post['perfil'];
            $parnitempresa = $post['nitEmpresa'];
            $parpkentidad = $usuario['PK_ENT_CODIGO'];
            $parcontrasena = $post['contrasena'];
            //$parresultul = '';
            //TIPO NUMBER INPUT
            oci_bind_by_name($stmt, ':parpkempresa', $parpkempresa, 32);
            //TIPO NUMBER INPUT
            oci_bind_by_name($stmt, ':parpkcampana', $parpkcampana, 32);
            //TIPO NUMBER INPUT
            oci_bind_by_name($stmt, ':parpktipvin', $parpktipvin, 32);
            //TIPO VARCHAR2 INPUT
            oci_bind_by_name($stmt, ':parnitempresa', $parnitempresa, 100);
            //TIPO NUMBER INPUT
            oci_bind_by_name($stmt, ':parpkentidad', $parpkentidad, 32);
            //TIPO NUMBER INPUT
            oci_bind_by_name($stmt, ':parcontrasena', $parcontrasena, 32);
            //TIPO NUMBER OUTPUT
            oci_bind_by_name($stmt, ':parresultul', $parresultul, 32);

            if (!@oci_execute($stmt)) {
                $e = oci_error($stmt);
                var_dump($e);
                $data['ok'] = 2;
            }
            //var_dump($parresultul);
            if ($parresultul == 1) {

                $pkentida = $this->db->query("SELECT PK_ENT_CODIGO"
                        . ",NVL(ent.RAZON_SOCIAL,ent.NOMBRE||' '||ent.APELLIDO) NOMBREEMPRESA,"
                        . " tpdoc.NOMBRE,DOCUMENTO"
                        . " FROM MODCLIUNI.CLITBLENTIDA ent"
                        . " JOIN MODCLIUNI.CLITBLTIPDOC tpdoc"
                        . " ON ent.CLITBLTIPDOC_PK_TD_CODIGO=tpdoc.PK_TD_CODIGO "
                        . " WHERE DOCUMENTO=TRIM('$parnitempresa') and ent.CLITBLTIPDOC_PK_TD_CODIGO=72");
                $entidad = $pkentida->result_array[0];

                /*$this->session->set_userdata(array(
                    'campana' => $post['campana'],
                    'rol' => $post['perfil'],
                    'pkentidad' => $entidad['PK_ENT_CODIGO'],
                    'entidad' => $entidad
                ));
                 */
                
                /*$this->session->set_userdata(array(
                    'campana' => $post['campana'],
                    'rol' => $post['perfil'],
                    'pkentidad' => $entidad['PK_ENT_CODIGO'],
                    'entidad' => $entidad
                ));*/
                $_SESSION['campana']= $post['campana'];
                $_SESSION['rol'] = $post['perfil'];
                $_SESSION['pkentidad']= $entidad['PK_ENT_CODIGO'];
                $_SESSION['entidad'] =$entidad;
                
                redirect('portal/principal/pantalla');
            } else {
                $data['ok'] = 1;
            }
        }
        $empresas = $this->db->query("SELECT DISTINCT PK_ENT_CODIGO CODIGOEMPRESA, NVL(RAZON_SOCIAL,NOMBRE||' '||APELLIDO) NOMBREEMPRESA
                 FROM modcliuni.clitblvincul vincul
                 JOIN MODCLIUNI.CLITBLENTIDA ent
                 ON vincul.clitblentida_pk_ent_codigo1=ent.pk_ent_codigo
                 WHERE clitblentida_pk_ent_codigo={$usuario['PK_ENT_CODIGO']}
                 AND vincul.fecha_fin is null
                 and vincul.CLITBLTIPVIN_PK_TIPVIN_CODIGO in (45,46,47)");

        $data['empresas'] = $empresas->result_array;

        $roles = $this->db->query("SELECT  PK_TIPVIN_CODIGO,NOMBRE FROM MODCLIUNI.CLITBLTIPVIN WHERE PK_TIPVIN_CODIGO IN (45,46,47)  ");

        $data['roles'] = $roles->result_array;

        //$empresa = $this->session->userdata("entidad");
        $empresa = $_SESSION['entidad'];


        $data['empresa'] = $empresa['NOMBREEMPRESA'];

        //´perfil super pagador
        //$rol = $this->session->userdata("rol");
        $rol = $_SESSION['rol'];
        if ($rol == 58) {
            $sqlAnticipo = $this->db->query("select distinct
                ent.pk_ent_codigo CODIGO_ENTIDAD,
                CASE
                    WHEN proc.aprobado = 1 THEN 'SI'
                    ELSE 'NO'
                END AS ANTICIPO,
                bol.DIAS_MAXIMO DIAS_ANTICIPO,
                to_char(MODGENERI.GENPKGCLAGEN.DECRYPT(bol.tope_maximo),'FML999G999G999G999G990D00') CUPO_ANTICIPO,
                to_char(MODGENERI.GENPKGCLAGEN.DECRYPT(bol.saldo),'FML999G999G999G999G990D00') CUPO_USADO,
                to_char((MODGENERI.GENPKGCLAGEN.DECRYPT(bol.monto_temporal) ),'FML999G999G999G999G990D00') EXTRACUPO
                ,to_char((MODGENERI.GENPKGCLAGEN.DECRYPT(bol.tope_maximo) + MODGENERI.GENPKGCLAGEN.DECRYPT(bol.saldo)+(MODGENERI.GENPKGCLAGEN.DECRYPT(bol.monto_temporal))),'FML999G999G999G999G990D00') CUPO_DISPONIBLE

            from 
                MODCLIUNI.clitblentida ent
                join modcliuni.clitblvincul vin on vin.clitblentida_pk_ent_codigo = ent.pk_ent_codigo
                join modcliuni.clitbltipent tipent on tipent.pk_tipent_codigo = ent.clitbltipent_pk_tipent_codigo
                join modcliuni.clitblestent estent on estent.pk_est_codigo = ent.clitblestent_pk_est_codigo
                join modcliuni.clitblestusu estusu on estusu.pk_estusu_codigo = ent.clitblestusu_pk_estusu_codigo
                                                    AND vin.clitbltipvin_pk_tipvin_codigo = 50
                left join modcomerc.comtblcotiza coti on coti.pk_entida_cliente = ent.pk_ent_codigo 
                                                    AND coti.pk_estado_codigo = 1
                left join MODCOMERC.comtblproces proc on proc.pk_cotiza_codigo = coti.pk_cotiza_codigo 
                                                    AND proc.pk_estado_codigo = 1
                join MODSALDOS.saltblbolsil bol on bol.pk_ent_codigo = ent.pk_ent_codigo 
                                                    AND bol.pk_tipbol_codigo = 3
                WHERE ent.pk_ent_codigo={$empresa['PK_ENT_CODIGO']}");
            $data['dataAnticipo'] = $sqlAnticipo->result_array[0];
        }
        //$usuario = $this->session->userdata("usuario");
        $usuario = $_SESSION['usuario'];
        $data['usuario'] = $usuario['NOMBRE'] . " " . $usuario['APELLIDO'];

        //$ultimaconexion = $this->session->userdata("ultimaconexion");
        $ultimaconexion = $_SESSION['ultimaconexion'];
        $data['ultimaconexion'] = $ultimaconexion;
        $this->load->view('portal/templates/header2', $data);
        $this->load->view('portal/login/cambioSesion', $data);
        $this->load->view('portal/templates/footer', $data);
    }

    public function cerrarSesion() {
        $sql = "BEGIN MODULCHAT.CHATPKGACTUALIZACION.prcupdatechatstatus(
            parpkchat=>:parpkchat,
             parrespuesta=>:parrespuesta);
            END;";
        $conn = $this->db->conn_id;
        $stmt = oci_parse($conn, $sql);
        //$parpkchat = $this->session->userdata['PK_CHAT'];
        $parpkchat = $_SESSION['PK_CHAT'];
        oci_bind_by_name($stmt, ':parpkchat', $parpkchat, 32);
        oci_bind_by_name($stmt, ':parrespuesta', $parrespuesta, 32);
        if (!@oci_execute($stmt)) {
            $e = oci_error($stmt);
            var_dump($e);
        }
        if ($parrespuesta == 1) {
            $this->session->sess_destroy();
            session_destroy();
            redirect('portal/login/validar');
        }
    }

}
