<?php
session_start();
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class UsuariosCreacion extends CI_Controller {

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
        if (($rol != 45) && ($rol != 47) && ($rol != 61)) {
            redirect('/portal/principal/pantalla');
        }
    }

    public function lista() {
        $this->verificarPerfilCo();
        //$pkEntidad = $this->session->userdata("pkentidad");
        $pkEntidad = $_SESSION['pkentidad'];
        log_info($this->iniciLog . $this->logHeader . ' Entidad a buscar ' . $pkEntidad);
        //$campana = $this->session->userdata("campana");
        $campana = $_SESSION['campana'];
        $rol = $_SESSION['rol'];
        if($rol==61){
        $sqlusu="SELECT pk_ent_codigo"
        . " , ent.nombre||' '||APELLIDO USUARIO"
        . " , ent.documento DOCUMENTO "
        . " , vin.clitbltipvin_pk_tipvin_codigo CODIGO "
        . " , tipvin.nombre ROL "
        . " , vin.CLITBLCAMPAN_PK_CAMPAN_CODIGO IDCAMPANA"
        . " , campana.nombre NOMBRECAMPANA"
        . " , estusu.nombre ESTADO "
        . " , vper.PERMISOS "
        . " , LIMITE_GASTO "
        . " , vin.PK_VINCUL_CODIGO"
        . "  FROM modcliuni.clitblentida ent  "
        . "   JOIN MODCLIUNI.CLITBLVINCUL vin  "
        . "  ON vin.clitblentida_pk_ent_codigo = ent.pk_ent_codigo  "
        . "   JOIN MODCLIUNI.CLITBLTIPVIN tipvin  "
        . "  ON vin.clitbltipvin_pk_tipvin_codigo = tipvin.pk_tipvin_codigo  "
        . "  JOIN MODCLIUNI.CLITBLESTUSU estusu "
        . "   ON ent.CLITBLESTUSU_PK_ESTUSU_CODIGO=estusu.PK_ESTUSU_CODIGO "
        . "   LEFT JOIN MODCLIUNI.CLIVIWVINPEM vper "
        . "  ON vper.pk_vincul_codigo=vin.pk_vincul_codigo"
        . " JOIN MODCLIUNI.CLITBLCAMPAN campana"
        . " ON vin.CLITBLCAMPAN_PK_CAMPAN_CODIGO=campana.PK_CAMPAN_CODIGO"
        . " WHERE vin.clitblentida_pk_ent_codigo1 ='$pkEntidad' "
        . " AND vin.clitbltipvin_pk_tipvin_codigo in (59,60)
            and vin.fecha_fin is null
            and campana.PK_CAMPAN_CODIGO = {$campana}";
         
        }else{
         $sqlusu="SELECT pk_ent_codigo"
        . " , ent.nombre||' '||APELLIDO USUARIO"
        . " , ent.documento DOCUMENTO "
        . " , vin.clitbltipvin_pk_tipvin_codigo CODIGO "
        . " , tipvin.nombre ROL "
        . " , vin.CLITBLCAMPAN_PK_CAMPAN_CODIGO IDCAMPANA"
        . " , campana.nombre NOMBRECAMPANA"
        . " , estusu.nombre ESTADO "
        . " , vper.PERMISOS "
        . " , LIMITE_GASTO "
        . " , vin.PK_VINCUL_CODIGO"
        . "  FROM modcliuni.clitblentida ent  "
        . "   JOIN MODCLIUNI.CLITBLVINCUL vin  "
        . "  ON vin.clitblentida_pk_ent_codigo = ent.pk_ent_codigo  "
        . "   JOIN MODCLIUNI.CLITBLTIPVIN tipvin  "
        . "  ON vin.clitbltipvin_pk_tipvin_codigo = tipvin.pk_tipvin_codigo  "
        . "  JOIN MODCLIUNI.CLITBLESTUSU estusu "
        . "   ON ent.CLITBLESTUSU_PK_ESTUSU_CODIGO=estusu.PK_ESTUSU_CODIGO "
        . "   LEFT JOIN MODCLIUNI.CLIVIWVINPEM vper "
        . "  ON vper.pk_vincul_codigo=vin.pk_vincul_codigo"
        . " JOIN MODCLIUNI.CLITBLCAMPAN campana"
        . " ON vin.CLITBLCAMPAN_PK_CAMPAN_CODIGO=campana.PK_CAMPAN_CODIGO"
        . " WHERE vin.clitblentida_pk_ent_codigo1 ='$pkEntidad' "
        . " AND vin.clitbltipvin_pk_tipvin_codigo in (45, 46, 47,56)
            and vin.fecha_fin is null
            and campana.PK_CAMPAN_CODIGO = {$campana}";
         
        
        }
         $usuarios = $this->db->query($sqlusu);  
         $data['usuarios'] = $usuarios->result_array;
        log_info($this->iniciLog . $this->logHeader . ' Entidad a buscar ' . $pkEntidad);
        //$empresa = $this->session->userdata("entidad");
        $empresa = $_SESSION['entidad'];
        $data['empresa'] = $empresa['NOMBREEMPRESA'];
        //$usuario = $this->session->userdata("usuario");
        $usuario = $_SESSION['usuario'];
        $data['usuario'] = $usuario['NOMBRE'] . " " . $usuario['APELLIDO'];
        //$ultimaconexion = $this->session->userdata("ultimaconexion");
        $ultimaconexion = $_SESSION['ultimaconexion'];
        $data['ultimaconexion'] = $ultimaconexion;
        // var_dump($data);
        $this->load->view('portal/templates/header2', $data);
        $this->load->view('portal/usuariosCreacion/lista', $data);
        $this->load->view('portal/templates/footer', $data);
    }

    public function actualizarUsuarios($pk_ent_codigo, $idcampana, $idrol, $idvinculacion) {
        $this->verificarPerfilCo();
        if ($pk_ent_codigo == null) {
            redirect('index.php/portal/usuariosCreacion/lista');
        }

        $post = $this->input->post();
        if ($post) {
            //$usuario = $this->session->userdata("usuario");
            $usuario = $_SESSION['usuario'];
            $usuarioactual = $usuario['USUARIO_ACCESO'];
            // var_dump($post['primerNombre']);
            // var_dump($post['ciudad']);
            //exit;
            /* actualizar tabla entidad */
            $resulact = $this->db->query("UPDATE modcliuni.clitblentida "
                    . "SET NOMBRE=UPPER('{$post['primerNombre']}')"
                    . ", APELLIDO =UPPER('{$post['primerApellido']}')"
                    . ", CLITBLCIUDAD_PK_CIU_CODIGO='{$post['ciudad']}'"
                    . ", CORREO_ELECTRONICO=UPPER('{$post['email']}')"
                    . ", USUARIO_ACTUALIZACION='$usuarioactual'"
                    . ", FECHA_ACTUALIZACION=SYSDATE"
                    . " WHERE PK_ENT_CODIGO='$pk_ent_codigo'");

            /* actualizar los datos de limite de gasto y limite de tarjeta */
            $resulaact = $this->db->query("UPDATE MODCLIUNI.CLITBLVINCUL"
                    . " SET LIMITE_GASTO='{$post['maxmonto']}'"
                    . " ,LIMITE_TARJETAS='{$post['maxtarjetas']}'"
                    . " WHERE PK_VINCUL_CODIGO='$idvinculacion'");

            /* consulta el ultimo dato de direccion almacenando para esa entidad */
            $iddireccion = $this->db->query("select MODCLIUNI.CLIPKGCONSULTAS.fncmaxpkcontacto($pk_ent_codigo,48) CODIGO from dual");
            $iddirec = $iddireccion->result_array[0];

            /* actualizacion de los datos de direccion */
            $result = $this->db->query("UPDATE MODCLIUNI.CLITBLCONTAC  SET DATO='{$post['direccion']}'"
                    . " WHERE PK_CONTAC_CODIGO={$iddirec['CODIGO']}");

            /* consulta el ultimo dato de telefono almacenando para esa entidad */
            $idtelefono = $this->db->query("select MODCLIUNI.CLIPKGCONSULTAS.fncmaxpkcontacto($pk_ent_codigo,47) CODIGO from dual");
            $idtelec = $idtelefono->result_array[0];
            //var_dump($idtelec['CODIGO']);
            //exit;

            if ($idtelec['CODIGO'] == null) {

                $inserta2 = $this->db->query("INSERT INTO modcliuni.clitblcontac (
                    dato,
                    clitbltipcon_pk_tipcon_codigo,
                    clitblentida_pk_ent_codigo,
                    clitblciudad_pk_ciu_codigo
                ) VALUES (
                    '{$post['telefono']}',
                    47,
                    $pk_ent_codigo,
                    298
                )");
            } else {
                $result = $this->db->query("UPDATE MODCLIUNI.CLITBLCONTAC  SET DATO='{$post['telefono']}'"
                        . " WHERE PK_CONTAC_CODIGO={$idtelec['CODIGO']}");
            }

            /* consulta el ultimo dato de telefono almacenando para esa entidad */
            $idtelefonocelular = $this->db->query("select MODCLIUNI.CLIPKGCONSULTAS.fncmaxpkcontacto($pk_ent_codigo,46) CODIGO from dual");
            $idtelefonocelular = $idtelefonocelular->result_array[0];

            if ($idtelefonocelular['CODIGO'] == null) {

                $inserta2 = $this->db->query("INSERT INTO modcliuni.clitblcontac (
                    dato,
                    clitbltipcon_pk_tipcon_codigo,
                    clitblentida_pk_ent_codigo,
                    clitblciudad_pk_ciu_codigo
                ) VALUES (
                    '{$post['telefono']}',
                    46,
                    $pk_ent_codigo,
                    298
                )");
            } else {
                $result = $this->db->query("UPDATE MODCLIUNI.CLITBLCONTAC  SET DATO='{$post['telefono']}'"
                        . " WHERE PK_CONTAC_CODIGO={$idtelefonocelular['CODIGO']}");
            }

            /* actualizacion de los datos de telefono */


            $borraprivi = $this->db->query("DELETE FROM MODCLIUNI.CLITBLVINPEM "
                    . "WHERE PK_VINCUL_CODIGO='$idvinculacion'");
            foreach ($post['privilegios'] as $key => $value) {
                $inserta = $this->db->query("insert into MODCLIUNI.CLITBLVINPEM (PK_VINCUL_CODIGO, PK_PEREMP_CODIGO, USUARIO_CREACION)"
                        . " VALUES($idvinculacion,$value,'$usuarioactual')");
            }

            redirect('portal/usuariosCreacion/lista');
        }

        $data['usuario'] = $this->db->query("
            Select 
            ent.nombre NOMBRE,
            ent.apellido APELLIDO,
            ent.CORREO_ELECTRONICO,
            ent.CLITBLCIUDAD_PK_CIU_CODIGO CIUDAD,
            ciudad.CLITBLDEPPAI_PK_DEP_CODIGO DEPARTAMENTO
            FROM modcliuni.clitblentida ent 
            left JOIN modcliuni.clitblciudad ciudad
            ON ent.CLITBLCIUDAD_PK_CIU_CODIGO=ciudad.PK_CIU_CODIGO
            where pk_ent_codigo = $pk_ent_codigo
                 ");
        $data['usuarioa'] = $data['usuario']->result_array[0];

        $departamentos = $this->db->query("SELECT PK_DEP_CODIGO, NOMBRE "
                . "FROM MODCLIUNI.CLITBLDEPPAI order by NOMBRE asc");
        $data['departamentos'] = $departamentos->result_array;

        $direccion = $this->db->query("SELECT DATO FROM MODCLIUNI.CLITBLCONTAC WHERE PK_CONTAC_CODIGO"
                . "= MODCLIUNI.CLIPKGCONSULTAS.fncmaxpkcontacto($pk_ent_codigo,48)");
        $data['direccion'] = $direccion->result_array[0];

        $telefono = $this->db->query("SELECT DATO FROM MODCLIUNI.CLITBLCONTAC WHERE PK_CONTAC_CODIGO"
                . "= MODCLIUNI.CLIPKGCONSULTAS.fncmaxpkcontacto($pk_ent_codigo,47)");
        $data['telefono'] = $telefono->result_array[0];

        $maxmonto = $this->db->query("SELECT LIMITE_GASTO, LIMITE_TARJETAS "
                . "  FROM modcliuni.clitblentida ent  "
                . "  JOIN MODCLIUNI.CLITBLVINCUL vin  "
                . "  ON vin.clitblentida_pk_ent_codigo = ent.pk_ent_codigo "
                . " where ent.pk_ent_codigo= '$pk_ent_codigo' "
                . " AND vin.CLITBLCAMPAN_PK_CAMPAN_CODIGO='$idcampana'"
                . " AND vin.clitbltipvin_pk_tipvin_codigo='$idrol'"
                . " AND vin.fecha_fin is null");
        $data['maxmonto'] = $maxmonto->result_array[0];

        $permisos = $this->db->query("select peremp.pk_peremp_codigo,nombre"
                . " from MODCLIUNI.CLITBLPEREMP peremp");
        $data['permisos'] = $permisos->result_array;

        $permisosrol = $this->db->query("select peremp.pk_peremp_codigo,nombre"
                . " from MODCLIUNI.CLITBLPEREMP peremp"
                . " JOIN MODCLIUNI.CLITBLVINPEM vinpem"
                . " ON peremp.PK_PEREMP_CODIGO=vinpem.PK_PEREMP_CODIGO"
                . " where vinpem.pk_vincul_codigo= '$idvinculacion'");
        $data['permisosrol'] = $permisosrol->result_array;

        //$empresa = $this->session->userdata("entidad");
        $empresa = $_SESSION['entidad'];
        $data['empresa'] = $empresa['NOMBREEMPRESA'];
        //$usuario = $this->session->userdata("usuario");
        $usuario = $_SESSION['usuario'];
        $data['usuario'] = $usuario['NOMBRE'] . " " . $usuario['APELLIDO'];
        //$ultimaconexion = $this->session->userdata("ultimaconexion");
        $ultimaconexion = $_SESSION['ultimaconexion'];
        $data['ultimaconexion'] = $ultimaconexion;
        //enviar departamento 
        foreach ($departamentos->result_array as $value) {
            if ($value['PK_DEP_CODIGO'] == $data['usuarioa']['DEPARTAMENTO']) {
                $data['depentidad'] = $value['NOMBRE'];
            }
        }
        //enviar ciudad
        $ciudades = $this->db->query("SELECT PK_CIU_CODIGO, NOMBRE"
                . " FROM MODCLIUNI.CLITBLCIUDAD"
                . " WHERE CLITBLDEPPAI_PK_DEP_CODIGO='{$data['usuarioa']['DEPARTAMENTO']}'");
        foreach ($ciudades->result_array as $value) {
            if ($value['PK_CIU_CODIGO'] == $data['usuarioa']['CIUDAD']) {
                $data['ciuentidad'] = $value['NOMBRE'];
            }
        }

        $this->load->view('portal/templates/header2', $data);
        $this->load->view('portal/usuariosCreacion/actualizar', $data);
        $this->load->view('portal/templates/footer', $data);
    }

    public function crear() {
        $this->verificarPerfilCo();
        log_info($this->iniciLog . $this->logHeader . ' Ingresa Crear Usuario ');
        $post = $this->input->post();
        if ($post) {
     ///print_r();
      //die();
            //$pkEmpresa = $this->session->userdata("pkentidad");
            $pkEmpresa = $_SESSION['pkentidad'];
            //$usuario = $this->session->userdata("usuario");
            $usuario = $_SESSION['usuario'];
            $usuarioactual = $usuario['USUARIO_ACCESO'];

            $sql = " BEGIN "
                    . " MODTARHAB.tarpkgfunciones.prcvalicreath(:parentidad,"
                    . " :partipodocum," . " :pardocumento ," . " :parprimnombre ,"
                    . " :parsegunombre ," . " :parprimapellido ," . " :parseguapellido,"
                    . " :parcorreoprinci," . " :parciudad," . " :parfechnacimien,"
                    . " :parnacionalidad," . " :paringreso," . " :parotroingreso,"
                    . " :partotalingres," . " :paregresos," . " :paractivos ,"
                    . " :parpasivos ," . " :parpatrimonio," . " :parconcepto,"
                    . " :pargenero ," . " :parestadocivil," . " :pardireccion,"
                    . " :parcelular ," . " :partelefonofijo," . " :parempresa,"
                    . " :parrespuest);"
                    . " END;";
            $conn = $this->db->conn_id;
            $stmt = oci_parse($conn, $sql);
            $tipodocumento = $post['tipoDocumento'];
            $documento = $post['documento'];
            $primerNombre = strtoupper($post['primerNombre']);
            $segundoNombre = strtoupper($post['segundoNombre']);
            $primerApellido = strtoupper($post['primerApellido']);
            $segundoApellido = strtoupper($post['segundoApellido']);
            $correo = strtoupper($post['correo']);
            $ciudad = $post['ciudad'];
            $fechaNacimiento = $post['fechaNacimiento'];
            $nacionalidad = strtoupper($post['nacionalidad']);
            $genero = $post['genero'];
            $estadoCivil = $post['estadoCivil'];
            $direccion = $post['direccion'];
            $celular = $post['telefono']; // $post['celular'];
            $telefono = $post['telefono'];
            $fechavincul = $this->db->query("select to_char(sysdate,'dd-mm-yyyy') from dual");
            $fechavincul = $fechavincul->result_array[0]['SYSDATE'];
            $tipoVinculacion = $post['tipoVinculacion'];
            //$campana = $this->session->userdata("campana");
            //
            $rol = $_SESSION['rol'];
            echo $rol;
            if($rol == 47){
                $campana = $post['campana'];
                ///echo $campana;
            }else{
                $campana = $_SESSION['campana'];
                //echo $campana;
            }
           
            $fechavincul = date_format(date_create($fechavincul), 'd-M-Y');
            $fechaNacimiento = date_format(date_create($fechaNacimiento), 'd-M-Y');            //TIPO NUMBER INPUT OUTPUT 
            oci_bind_by_name($stmt, ':parentidad', $parentidad, 32);
            //TIPO NUMBER INPUT
            oci_bind_by_name($stmt, ':partipodocum', $tipodocumento, 32);
            //TIPO VARCHAR2 INPUT
            oci_bind_by_name($stmt, ':pardocumento', $documento, 32);
            //TIPO VARCHAR2 INPUT
            oci_bind_by_name($stmt, ':parprimnombre', $primerNombre, 32);
            //TIPO VARCHAR2 INPUT
            oci_bind_by_name($stmt, ':parsegunombre', $segundoNombre, 32);
            //TIPO VARCHAR2 INPUT
            oci_bind_by_name($stmt, ':parprimapellido', $primerApellido, 32);
            //TIPO VARCHAR2 INPUT
            oci_bind_by_name($stmt, ':parseguapellido', $segundoApellido, 32);
            //TIPO VARCHAR2 INPUT
            oci_bind_by_name($stmt, ':parcorreoprinci', $correo, 150);
            //TIPO VARCHAR2 INPUT
            oci_bind_by_name($stmt, ':parciudad', $ciudad, 32);
            //TIPO DATE INPUT
            oci_bind_by_name($stmt, ':parfechnacimien', $fechaNacimiento, 32);
            //TIPO VARCHAR2 INPUT
            oci_bind_by_name($stmt, ':parnacionalidad', $nacionalidad, 32);
            //TIPO NUMBER INPUT
            oci_bind_by_name($stmt, ':paringreso', $ingreso, 32);
            //TIPO NUMBER INPUT
            oci_bind_by_name($stmt, ':parotroingreso', $otroingreso, 32);
            //TIPO NUMBER INPUT
            oci_bind_by_name($stmt, ':partotalingres', $totalingreso, 32);
            //TIPO NUMBER INPUT
            oci_bind_by_name($stmt, ':paregresos', $egreso, 32);
            //TIPO NUMBER INPUT
            oci_bind_by_name($stmt, ':paractivos', $activos, 32);
            //TIPO NUMBER INPUT
            oci_bind_by_name($stmt, ':parpasivos', $pasivos, 32);
            //TIPO NUMBER INPUT
            oci_bind_by_name($stmt, ':parpatrimonio', $patrimonio, 32);
            //TIPO NUMBER INPUT
            oci_bind_by_name($stmt, ':parconcepto', $concepto, 32);
            //TIPO NUMBER INPUT
            oci_bind_by_name($stmt, ':pargenero', $genero, 32);
            //TIPO NUMBER INPUT
            oci_bind_by_name($stmt, ':parestadocivil', $estadoCivil, 32);
            //TIPO VARCHAR2 INPUT
            oci_bind_by_name($stmt, ':pardireccion', $direccion, 60);
            //TIPO VARCHAR2 INPUT
            oci_bind_by_name($stmt, ':parcelular', $celular, 32);
            //TIPO NUMBER INPUT
            oci_bind_by_name($stmt, ':partelefonofijo', $telefono, 32);
            //TIPO NUMBER INPUT
            oci_bind_by_name($stmt, ':parempleado', $parentidad, 32);
            //TIPO NUMBER INPUT
            oci_bind_by_name($stmt, ':parempresa', $pkEmpresa, 32);
            //TIPO DATE INPUT
            oci_bind_by_name($stmt, ':parafechainicio', $fechavincul, 32);
            //TIPO NUMBER INPUT
            oci_bind_by_name($stmt, ':partipvinc', $tipoVinculacion, 32);
            //TIPO NUMBER INPUT
            oci_bind_by_name($stmt, ':parcampana', $campana, 32);
            //TIPO VARCHAR2 OUTPUT
            oci_bind_by_name($stmt, ':parmensaj', $parmenrespuest, 32);
            //TIPO VARCHAR2 OUTPUT
            oci_bind_by_name($stmt, ':parrespuest', $parrespuest, 32);
            log_info($this->iniciLog . $this->logHeader . ' Ingresa Crear Usuario ');
            if (!@oci_execute($stmt)) {
                $e = oci_error($stmt);
                var_dump("{$e['message']}");
                $data['error'] = 1;
                // $this->load->view('portal/usuariosCreacion/crear', $data);
            }
            log_info($this->iniciLog . $this->logHeader . ' RESPUESTA CREACION USUARIO ' . $parrespuest);
            //buscar si el th ya tiene una vinculacion identica a la que se intenta crear
            //Se puede validar y retornar error y omitir el envio de nueva contraseña
            $sql = "BEGIN MODTARHAB.tarpkgfunciones.PRCVALIDAVINCULACIONTH(
                    parpkempleado=>:parpkempleado,
                    parpkempresa=>:parpkempresa,
                    partipvinc=>:partipvinc,
                    parcampana=>:parcampana,
                    parmensajerespuesta=>:parmensajerespuesta,
                    parrespuesta=>:parrespuesta);
                    END;"; 
                    

            $conn = $this->db->conn_id;
            $stmt = oci_parse($conn, $sql);
            oci_bind_by_name($stmt, ':parpkempleado', $parentidad, 32);
            oci_bind_by_name($stmt, ':parpkempresa', $pkEmpresa, 32);
            oci_bind_by_name($stmt, ':partipvinc', $tipoVinculacion, 32);
            oci_bind_by_name($stmt, ':parcampana', $campana, 32);
            oci_bind_by_name($stmt, ':parmensajerespuesta', $parmensajerespuesta, 200);
            oci_bind_by_name($stmt, ':parrespuesta', $parrespuesta, 32);

            if (!oci_execute($stmt)) {
                $e = oci_error($stmt);
                VAR_DUMP($e);
                exit;
            }
            log_info($this->iniciLog . $this->logHeader . ' RESPUESTA  VINCULACION' . $tipoVinculacion . ' ' . $parrespuest);
            //no existe la vinculacion
            if ($parrespuesta == 1) {
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


                //TIPO NUMBER INPUT
                oci_bind_by_name($stmt, ':paraempleado', $parentidad, 32);
                //TIPO NUMBER INPUT
                oci_bind_by_name($stmt, ':parempresa', $pkEmpresa, 32);
                //TIPO DATE INPUT
                oci_bind_by_name($stmt, ':parafechainicio', $fechavincul, 32);
                //TIPO NUMBER INPUT
                oci_bind_by_name($stmt, ':partipvinc', $tipoVinculacion, 32);
                //TIPO NUMBER INPUT
                oci_bind_by_name($stmt, ':parcampana', $campana, 32);
                //TIPO VARCHAR2 OUTPUT
                oci_bind_by_name($stmt, ':parmensaj', $parrespuest, 32);


                if (!@oci_execute($stmt)) {
                    $e = oci_error($stmt);
                    var_dump("{$e['message']}");
                    exit();
                    $data['error'] = 1;
                    // $this->load->view('portal/usuariosCreacion/crear', $data);
                }
                log_info($this->iniciLog . $this->logHeader . ' RESPUESTA VINCULACION' . $tipoVinculacion . ' ' . $parrespuest);

                if ($parrespuest == 1) {

                    //se valida si la entidad ya tiene alguna vinculacion y asi no enviar correo con contraseña
                    $sqlVincul = $this->db->query("SELECT vin.PK_VINCUL_CODIGO
                        FROM modcliuni.clitblentida ent  
                        JOIN MODCLIUNI.CLITBLVINCUL vin  
                        ON vin.clitblentida_pk_ent_codigo = ent.pk_ent_codigo  
                        JOIN MODCLIUNI.CLITBLTIPVIN tipvin  
                        ON vin.clitbltipvin_pk_tipvin_codigo = tipvin.pk_tipvin_codigo  
                        JOIN MODCLIUNI.CLITBLESTUSU estusu 
                        ON ent.CLITBLESTUSU_PK_ESTUSU_CODIGO=estusu.PK_ESTUSU_CODIGO 
                        JOIN MODCLIUNI.CLITBLCAMPAN campana
                        ON vin.CLITBLCAMPAN_PK_CAMPAN_CODIGO=campana.PK_CAMPAN_CODIGO
                        WHERE vin.clitblentida_pk_ent_codigo1 =$pkEmpresa
                        AND ent.pk_ent_codigo =$parentidad
                        and vin.fecha_fin is null");
                    $sqlVincul->result_array;
                    log_info($this->iniciLog . $this->logHeader . ' RESPUESTA VINCULACION NUMERO REGISTROS $sqlVincul->num_rows ' . $sqlVincul->num_rows . ' ' . $parrespuest);

                    if ($sqlVincul->num_rows == 1) {

                        $sql = "BEGIN 
                        MODCLIUNI.CLIPKGACTUMODI.NOTIFICARCONTRASENA  (parpkentida =>:parpkentida
                                                                    ,parrespuesta=>:parrespuesta);
                        END;";
                        $conn = $this->db->conn_id;
                        $stmt = oci_parse($conn, $sql);
                        $parrespuest = null;
                        //TIPO NUMBER INPUT
                        oci_bind_by_name($stmt, ':parpkentida', $parentidad, 32);
                        //TIPO NUMBER INPUT
                        oci_bind_by_name($stmt, ':parrespuesta', $parrespuest, 32);



                        if (!@oci_execute($stmt)) {
                            $e = oci_error($stmt);
                            var_dump("{$e['message']}");
                            exit();
                            $data['error'] = 1;
                            // $this->load->view('portal/usuariosCreacion/crear', $data);
                        }
                        redirect('portal/usuariosCreacion/lista?ok');
                    }
                    log_info($this->iniciLog . $this->logHeader . ' RESPUESTA NOTIFICACION $parrespuest ' . $sqlVincul->num_rows . ' ' . $parrespuest);


                    //if ($parrespuest == 1) {
                    redirect('portal/usuariosCreacion/lista?ActOk');
                    //}
                } else {
                    //el usuario puede tener activa las misma vinculacion que se esta intentando 
                    $data['error'] = 99;
//                $this->load->view('portal/usuariosCreacion/crear', $data);
                }
            } else {
                $data['resVinculExiste'] = $parrespuesta;
                $data['msgVinculExiste'] = $parmensajerespuesta;
            }
        }

        //$entidad = $this->session->userdata("entidad");
        $entidad = $_SESSION['entidad'];
        $pk_ent_codigo = $entidad['PK_ENT_CODIGO'];

        $campana = $this->db->query("select cam.pk_campan_codigo PK_CAMPAN_CODIGO,cam.nombre NOMBRE "
                . " FROM MODCLIUNI.clitblcampan cam "
                . " WHERE cam.clitblgrupo_pk_gru_codigo = some("
                . " SELECT gruent.clitblgrupo_pk_gru_codigo"
                . " FROM MODCLIUNI.clitblgruent gruent"
                . " JOIN MODCLIUNI.clitblgrupo gru "
                . " ON gru.pk_gru_codigo = gruent.clitblgrupo_pk_gru_codigo "
                . " AND GRU.USUARIO_ACTUALIZACION IS NULL "
                . " AND GRU.FECHA_ACTUALIZACION IS NULL"
                . " WHERE"
                . " gruent.clitblentida_pk_ent_codigo ='$pk_ent_codigo'"
                . " AND GRUENT.USUARIO_ACTUALIZACION IS NULL "
                . " AND GRUENT.FECHA_ACTUALIZACION IS NULL)"
                . " AND CAM.USUARIO_aCTUALIZACION IS NULL"
                . " AND CAM.FECHA_ACTUALIZACION IS NULL");

        $data['campana'] = $campana->result_array;
        //$rol = $this->session->userdata("rol");
        $rol = $_SESSION['rol'];
        //echo $rol;
      
        if ($rol == 47) {
            $tipoVinculacion = $this->db->query("SELECT PK_TIPVIN_CODIGO, NOMBRE "
                    . " FROM MODCLIUNI.CLITBLTIPVIN"
                    . " WHERE PK_TIPVIN_CODIGO IN (45,46,56) ");
            $data['tipoVinculacion'] = $tipoVinculacion->result_array;
        }

        if ($rol == 46) {
            #   $tipoVinculacion = $this->db->query("SELECT PK_TIPVIN_CODIGO, NOMBRE "
            #           . " FROM MODCLIUNI.CLITBLTIPVIN"
            #           . " WHERE PK_TIPVIN_CODIGO IN (46) ");
            $data['tipoVinculacion'] = $tipoVinculacion->result_array;
        }

        if ($rol == 45) {
            $tipoVinculacion = $this->db->query("SELECT PK_TIPVIN_CODIGO, NOMBRE "
                    . " FROM MODCLIUNI.CLITBLTIPVIN"
                    . " WHERE PK_TIPVIN_CODIGO IN (46) ");
            $data['tipoVinculacion'] = $tipoVinculacion->result_array;
        }
        if ($rol == 61) {
            $tipoVinculacion = $this->db->query("SELECT PK_TIPVIN_CODIGO, NOMBRE "
                    . " FROM MODCLIUNI.CLITBLTIPVIN"
                    . " WHERE PK_TIPVIN_CODIGO IN (59,60) ");
            $data['tipoVinculacion'] = $tipoVinculacion->result_array;
        }

        $tipodocumento = $this->db->query("SELECT PK_TD_CODIGO, NOMBRE "
                . "FROM MODCLIUNI.CLITBLTIPDOC WHERE PK_TD_CODIGO IN (67,68,69,70) ");
        $data['tipoDocumento'] = $tipodocumento->result_array;
        $data['rol']=$_SESSION['rol'];
        $departamentos = $this->db->query("SELECT PK_DEP_CODIGO, NOMBRE "
                . "FROM MODCLIUNI.CLITBLDEPPAI "
                . "WHERE CLITBLPAIS_PK_PAIS_CODIGO=7  ORDER BY NOMBRE");
        $data['departamentos'] = $departamentos->result_array;

        $generos = $this->db->query("SELECT PK_GEN_CODIGO, NOMBRE "
                . "FROM MODCLIUNI.CLITBLGENERO");
        $data['generos'] = $generos->result_array;

        $estadoCivil = $this->db->query("SELECT PK_ESTCIV_CODIGO, NOMBRE "
                . "FROM MODCLIUNI.CLITBLESTCIV");
        $data['estadoCivil'] = $estadoCivil->result_array;

        $permisos = $this->db->query("select peremp.pk_peremp_codigo,nombre"
                . " from MODCLIUNI.CLITBLPEREMP peremp");
        $data['permisos'] = $permisos->result_array;

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
        $this->load->view('portal/usuariosCreacion/crear', $data);
        $this->load->view('portal/templates/footer', $data);
    }

    public function desvinculuser($PK_ENT_CODIGO, $IDCAMPANA, $CODIGO, $PK_VINCUL_CODIGO) {

        //$entidad = $this->session->userdata["entidad"]['PK_ENT_CODIGO'];
        $entidad = $_SESSION['entidad']['PK_ENT_CODIGO'];

        $sql = "BEGIN MODtarhab.tarpkgfunciones.prcdesvinuser(
            parpkempleado=>:parpkempleado,
            parpkempresa=>:parpkempresa,
            parpkvincul=>:parpkvincul,
            parpkcampana=>:parpkcampana,
            parpkvinculcodigo=>:parpkvinculcodigo,
            parrespue=>:parrespue);
            END;";
        $conn = $this->db->conn_id;
        $stmt = oci_parse($conn, $sql);
        $parpkempleado = $PK_ENT_CODIGO;
        $parpkvincul = $CODIGO;
        $parpkempresa = $entidad;
        $parpkcampana = $IDCAMPANA;
        $parpkvinculcodigo = $PK_VINCUL_CODIGO;
        oci_bind_by_name($stmt, ':parpkempleado', $parpkempleado, 32);
        oci_bind_by_name($stmt, ':parpkempresa', $parpkempresa, 32);
        oci_bind_by_name($stmt, ':parpkvincul', $parpkvincul, 32);
        oci_bind_by_name($stmt, ':parpkcampana', $parpkcampana, 32);
        oci_bind_by_name($stmt, ':parpkvinculcodigo', $parpkvinculcodigo, 32);
        oci_bind_by_name($stmt, ':parrespue', $parrespue, 32);
        if (!@oci_execute($stmt)) {
            $e = oci_error($stmt);
            var_dump($e);
        }
        if ($parrespue == 1) {
            redirect('/portal/usuariosCreacion/lista');
        }
        return false;
    }

}
