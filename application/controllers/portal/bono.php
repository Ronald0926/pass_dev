<?php
session_start();
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Bono extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        if ($this->session->userdata('entidad') == NULL) {
            redirect('/');
        }
    }
    public function verificarPerfilCo()
    {
        $rol = $this->session->userdata("rol");
        if (($rol != 45) and ($rol != 47)) {
            redirect('/portal/principal/pantalla');
        }
    }
    public function ingreso($pantalla = 0)
    {
        $this->verificarPerfilCo();
        $post = $this->input->post();
        $pkEntidad = $this->session->userdata('pkentidad');

        if ($post) {
            $sql = "BEGIN MODGENERI.GENPKGWEBSERVICE.PRCVALINGPORBONTRA(
                    :PARNITEMP, :PAREMPRES,
                    :PARTIPDOC, :PARDOCUME,
                    :PARCONTRA, :PARTIPVIN,
                    :PARRESPUE);
                    END;";
            $conn = $this->db->conn_id;
            $stmt = oci_parse($conn, $sql);
            $PARNITEMP = $post['nit'];
            oci_bind_by_name($stmt, ':PARNITEMP', $PARNITEMP, 32);
            $PAREMPRES = $pkEntidad;
            oci_bind_by_name($stmt, ':PAREMPRES', $PAREMPRES, 32);
            $PARTIPDOC = $post['tdocumento'];
            oci_bind_by_name($stmt, ':PARTIPDOC', $PARTIPDOC, 32);
            $PARDOCUME = $post['documento'];
            oci_bind_by_name($stmt, ':PARDOCUME', $PARDOCUME, 32);
            $PARCONTRA = $post['contrasena'];
            oci_bind_by_name($stmt, ':PARCONTRA', $PARCONTRA, 32);
            $PARTIPVIN = $post['rol'];
            oci_bind_by_name($stmt, ':PARTIPVIN', $PARTIPVIN, 32);

            oci_bind_by_name($stmt, ':PARRESPUE', $PARRESPUE, 32);
            if (!oci_execute($stmt)) {
                $e = oci_error($stmt);
                VAR_DUMP($e);
                exit;
            } else if ($PARRESPUE != 1) {

                if ($PARRESPUE == 100) {
                    $PARRESPUE = 'ERROR ' . $PARRESPUE . ' DATOS DE USUARIO INCORRECTO';
                } else if ($PARRESPUE == 101) {
                    $PARRESPUE = 'ERROR ' . $PARRESPUE . ' NO ESTA VINCULADO CON DICHA ENTIDAD';
                } else if ($PARRESPUE == 0) {
                    $PARRESPUE = 'ERROR ' . $PARRESPUE . ' NO TIENE ACTIVO SERVICIO PARA BONO TRANSPORTADOR';
                }
            } else if ($PARRESPUE == 1) {
                /* $this->load->view('portal/templates/headerBono', $data);
                $this->load->view('portal/bono/bonoPrincipal', $data);
                $this->load->view('portal/templates/footer', $data);*/
                redirect('/portal/bono/principal');
            }
        }
        $usuario = $this->session->userdata('usuario');
        //var_dump($PARRESPUE);
        $data['error'] = $PARRESPUE;
        $tipodocumento = $usuario['CLITBLTIPDOC_PK_TD_CODIGO'];
        $documento = $usuario['DOCUMENTO'];
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
            . " AND TIPVIN.PK_TIPVIN_CODIGO IN (45, 46,47)");
        $data['rol'] = $data['rol']->result_array;
        //var_dump($data['rol']);
        //exit();
        $tipodocumento = $this->db->query('SELECT PK_TD_CODIGO,ABREVIACION,NOMBRE FROM MODCLIUNI.CLITBLTIPDOC');
        $data['tipoDocumento'] = $tipodocumento->result_array;
        //DATOS DE SESION
        $empresa = $this->session->userdata("entidad");
        $data['empresa'] = $empresa['NOMBREEMPRESA'];
        $usuario = $this->session->userdata("usuario");
        $data['usuario'] = $usuario['NOMBRE'] . " " . $usuario['APELLIDO'];
        //
        $ultimaconexion = $this->session->userdata("ultimaconexion");
        $data['ultimaconexion'] = $ultimaconexion;
        $bonoTransportador = $this->db->query("SELECT pa.PK_PRODUCTO_CODIGO,pa.PARAMETRO
        FROM MODCOMERC.COMTBLCOTIZA co
        INNER JOIN MODCOMERC.COMTBLPROCES pr ON pr.PK_COTIZA_CODIGO = co.pk_cotiza_codigo
        INNER JOIN MODCOMERC.COMTBLPARAME pa ON pa.pk_proces_codigo = pr.pk_proces_codigo AND pa.pk_tippar_codigo IN (1,3)
        WHERE pr.pk_estado_codigo = 1
        AND  co.pk_estado_codigo = 1
        AND  co.PK_ENTIDA_CLIENTE = {$empresa['PK_ENT_CODIGO']}
        AND pa.PK_PRODUCTO_CODIGO=69");

        $data['bonoTrans'] = $bonoTransportador->result_array[0];
        $this->load->view('portal/templates/headerBono', $data);
        $this->load->view('portal/bono/ingreso', $data);
        $this->load->view('portal/templates/footer', $data);
    }
    public function bonoTarjetas($pantalla = 0)
    {
        $this->verificarPerfilCo();
        $pkEntidad = $this->session->userdata('pkentidad');
        $tarjetas = $this->db->query("     
            SELECT CASE WHEN tar.numero IS NULL THEN 'NO TIENE NUMERO DE TARJETA' ELSE '**** **** '||SUBSTR(tar.numero,-4) END NUMTAR,
            esttar.nombre ESTTAR,
            mov.pk_movimi_codigo MOV,
            tar.pk_tarjet_codigo  IDTAR
                FROM MODTARHAB.TARTBLCUENTA CUE
            JOIN MODTARHAB.tartbltarjet tar 
                on tar.pk_tartblcuenta_codigo = cue.pk_tartblcuenta_codigo
                AND cue.pk_ent_codigo_emp = {$pkEntidad}
                AND cue.pk_produc_codigo  = 68
            JOIN MODTARHAB.tartblesttar ESTTAR 
                ON esttar.pk_esttar_codigo = tar.pk_esttar_codigo
            left join MODBONTRA.bontblmovimi mov 
                on mov.pk_cuenta_codigo = cue.pk_tartblcuenta_codigo
                and mov.pk_ent_codigo_recibido != cue.pk_ent_codigo_emp");
        $data['tarjetas'] = $tarjetas->result_array;
        $estadoTarjetas = $this->db->query("     
            SELECT NOMBRE NOM, PK_ESTTAR_CODIGO COD
                FROM MODTARHAB.TARTBLESTTAR ");
        $data['estadoTarjetas'] = $estadoTarjetas->result_array;
        $data['menu'] = "tarjetas";
        //DATOS DE SESION
        $empresa = $this->session->userdata("entidad");
        $data['empresa'] = $empresa['NOMBREEMPRESA'];
        $usuario = $this->session->userdata("usuario");
        $data['usuario'] = $usuario['NOMBRE'] . " " . $usuario['APELLIDO'];
        $ultimaconexion = $this->session->userdata("ultimaconexion");
        $data['ultimaconexion'] = $ultimaconexion;
        //
        $this->load->view('portal/templates/headerBono2', $data);
        $this->load->view('portal/bono/Tarjetas', $data);
        $this->load->view('portal/templates/footer', $data);
    }
    public function bonoPortador($pantalla = 0)
    {
        $this->verificarPerfilCo();
        $pkEntidad = $this->session->userdata('pkentidad');
        $poractivo = $this->db->query("    
            select ent.nombre ||' '|| ent.apellido PORTAD,
            tipdoc.abreviacion ABR,
            ENT.DOCUMENTO DOC,
            tiplic.nombre TIPLIC,
            estpor.nombre ESTPOR,
        CASE tar.numero  WHEN NULL THEN
        'NO' ELSE 'SI' END ASI,
        tar.numero NUMTAR
        from 
            MODBONTRA.bontblvehrut vehrut
        join MODBONTRA.bontblportad por 
            on por.pk_portad_codigo = vehrut.pk_portad_codigo 
            and por.pk_entida_codigoemp = {$pkEntidad}
            and vehrut.pk_estvru_codigo = 1
        JOIN MODBONTRA.bontbltiplic TIPLIC 
            ON tiplic.pk_tiplic_codigo = por.pk_tiplic_codigo
        JOIN MODBONTRA.bontblestpor ESTPOR 
            ON estpor.pk_estpor_codigo = por.pk_estpor_codigo
        join MODCLIUNI.clitblentida ent 
            on ent.pk_ent_codigo = por.pk_entida_codigo
        JOIN MODCLIUNI.CLITBLTIPDOC TIPDOC 
            ON tipdoc.pk_td_codigo = ent.clitbltipdoc_pk_td_codigo
        join MODBONTRA.bontblvehicu veh 
            on veh.pk_vehicu_codigo = vehrut.pk_vehicu_codigo 
            and veh.pk_entida_codigo = {$pkEntidad}
        LEFT join MODBONTRA.bontblruta rut 
            on rut.pk_ruta_codigo = vehrut.pk_ruta_codigo 
            and rut.pk_entida_codigo = {$pkEntidad}
        LEFT join MODBONTRA.bontblestvru estvru 
            on estvru.pk_estvru_codigo = vehrut.pk_estvru_codigo
        LEFT join MODTARHAB.tartbltarjet tar 
            on tar.PK_TARTBLCUENTA_CODIGO = vehrut.PK_CUENTA_CODIGO
            and vehrut.pk_estvru_codigo = 1
        ");
        $data['error'] = $pantalla;
        $data['poractivo'] = $poractivo->result_array;
        $porinacti = $this->db->query("
            select 
                ENT.NOMBRE ||' '||ent.apellido PORTAD, 
                tipdoc.ABREVIACION ABR,
                ent.documento DOC,
                tiplic.nombre TIPLIC, 
                estpor.nombre ESTPOR
            from MODBONTRA.bontblportad por
            join MODCLIUNI.clitblentida ENT ON ent.pk_ent_codigo = por.pk_entida_codigo
            JOIN MODCLIUNI.clitbltipdoc TIPDOC ON tipdoc.pk_td_codigo = ent.clitbltipdoc_pk_td_codigo
            JOIN MODBONTRA.BONTBLTIPLIC TIPLIC ON tiplic.pk_tiplic_codigo = por.pk_tiplic_codigo
            JOIN MODBONTRA.BONTBLESTPOR ESTPOR ON ESTPOR.PK_ESTPOR_CODIGO = POR.PK_ESTPOR_CODIGO
            WHERE por.pk_portad_codigo NOT IN (
                select 
                    por.pk_portad_codigo
                from 
                    MODBONTRA.bontblportad por  
                join MODBONTRA.bontblvehrut vehrut  on por.pk_portad_codigo = vehrut.pk_portad_codigo 
                and por.pk_entida_codigoemp = {$pkEntidad}
                and vehrut.pk_estvru_codigo = 1)
            ");
        $data['porinacti'] = $porinacti->result_array;
        $data['menu'] = "portadores";
        //DATOS DE SESION
        $empresa = $this->session->userdata("entidad");
        $data['empresa'] = $empresa['NOMBREEMPRESA'];
        $usuario = $this->session->userdata("usuario");
        $data['usuario'] = $usuario['NOMBRE'] . " " . $usuario['APELLIDO'];
        //
        $this->load->view('portal/templates/headerBono2', $data);
        $this->load->view('portal/bono/portadores', $data);
        $this->load->view('portal/templates/footer', $data);
    }
    public function crearPortador($pantalla = 0)
    {
        $this->verificarPerfilCo();
        $post = $this->input->post();

        if ($post) {
            $pkEntidad = $this->session->userdata('pkentidad');
            $usuario = $this->session->userdata('usuario');
            $sql = "
                BEGIN 
                MODGENERI.GENPKGWEBSERVICE.prccreportador(
                    :PAREMPRES, :PARPRINOM,
                    :PARSEGNOM, :PARPRIAPE,
                    :PARSEGAPE, :PARESTPOR,
                    :PARDOCUME, :PARTIPDOC,
                    :PARCORREO, :PARTIPLIC,
                    :PARUSUCRE, :PARFECCRE,
                    :PARIP, :PARRESPUE);
                    END;";
            $conn = $this->db->conn_id;
            $stmt = oci_parse($conn, $sql);
            $PAREMPRES = $pkEntidad;
            oci_bind_by_name($stmt, ':PAREMPRES', $PAREMPRES, 32);
            $PARPRINOM = $post['nombre'];

            oci_bind_by_name($stmt, ':PARPRINOM', $PARPRINOM, 32);
            $PARSEGNOM;
            oci_bind_by_name($stmt, ':PARSEGNOM', $PARSEGNOM, 32);
            $PARPRIAPE = $post['apellido'];
            oci_bind_by_name($stmt, ':PARPRIAPE', $PARPRIAPE, 32);
            $PARSEGAPE;
            oci_bind_by_name($stmt, ':PARSEGAPE', $PARSEGAPE, 32);
            $PARESTPOR = $post['estado'];
            oci_bind_by_name($stmt, ':PARESTPOR', $PARESTPOR, 32);
            $PARDOCUME = $post['documento'];
            oci_bind_by_name($stmt, ':PARDOCUME', $PARDOCUME, 32);
            $PARTIPDOC = $post['tdocumento'];
            oci_bind_by_name($stmt, ':PARTIPDOC', $PARTIPDOC, 32);
            $PARCORREO = $post['correo'];
            oci_bind_by_name($stmt, ':PARCORREO', $PARCORREO, 32);
            $PARTIPLIC = $post['tlicencia'];
            oci_bind_by_name($stmt, ':PARTIPLIC', $PARTIPLIC, 32);
            $PARUSUCRE = $usuario['USUARIO_ACCESO'];
            oci_bind_by_name($stmt, ':PARUSUCRE', $PARUSUCRE, 32);
            $PARFECCRE = date_format(date_create(date("d/m/Y")), 'd-M-Y');;
            oci_bind_by_name($stmt, ':PARFECCRE', $PARFECCRE, 32);
            $PARIP = '0';
            oci_bind_by_name($stmt, ':PARIP', $PARIP, 32);
            $PARRESPUE;
            oci_bind_by_name($stmt, ':PARRESPUE', $PARRESPUE, 32);
            if (!oci_execute($stmt)) {
                $e = oci_error($stmt);
                VAR_DUMP($e);
                exit;
            } else if ($PARRESPUE != 1) {
                $PARRESPUE = 'ERROR ' . $PARRESPUE . ' NO SE PUEDE CREAR PORTADOR';
            } else if ($PARRESPUE == 1) {
                redirect('portal/bono/bonoPortador/' . $PARRESPUE);
            }
            $data['error'] = $PARRESPUE;
        }
        $tipoDocumento = $this->db->query('SELECT PK_TD_CODIGO,ABREVIACION,NOMBRE FROM MODCLIUNI.CLITBLTIPDOC');
        $data['tipoDocumento'] = $tipoDocumento->result_array;
        $tipoLicencia = $this->db->query('SELECT NOMBRE,pk_tiplic_codigo FROM MODBONTRA.BONTBLTIPLIC');
        $data['tipoLicencia'] = $tipoLicencia->result_array;
        $estadoPort = $this->db->query('SELECT NOMBRE,pk_estpor_codigo FROM MODBONTRA.BONTBLESTPOR');
        $data['estadoPort'] = $estadoPort->result_array;
        //DATOS DE SESION
        $empresa = $this->session->userdata("entidad");
        $data['empresa'] = $empresa['NOMBREEMPRESA'];
        $usuario = $this->session->userdata("usuario");
        $data['usuario'] = $usuario['NOMBRE'] . " " . $usuario['APELLIDO'];
        //
        $this->load->view('portal/templates/headerBono2', $data);
        $this->load->view('portal/bono/crearPortador', $data);
        $this->load->view('portal/templates/footer', $data);
    }
    public function crearVehiculo($pantalla = 0)
    {
        $this->verificarPerfilCo();
        $data['menu'] = 'vehiculos';
        //tipo de caga
        $post = $this->input->post();
        if ($post) {
            $pkEntidad = $this->session->userdata('pkentidad');
            $usuario = $this->session->userdata('usuario');
            $sql = "
                BEGIN 
                MODGENERI.GENPKGWEBSERVICE.PRCCREVEHICULO(
                    :PARPLAVEH, :PARMARVEH,
                    :PARMODVEH, :PARANIVEH,
                    :PARTIPCAR, :PARTIPVEH,
                    :PARESTADO, :PARENTIDA,
                    :PARUSUCRE, :PARRESPUE
                    );
                END;";
            $conn = $this->db->conn_id;
            $stmt = oci_parse($conn, $sql);
            $PARPLAVEH = $post['placa'];
            oci_bind_by_name($stmt, ':PARPLAVEH', $PARPLAVEH, 32);
            $PARMARVEH = $post['marca'];
            oci_bind_by_name($stmt, ':PARMARVEH', $PARMARVEH, 32);
            $PARMODVEH = $post['modelo'];
            oci_bind_by_name($stmt, ':PARMODVEH', $PARMODVEH, 32);
            $PARANIVEH = $post['anio'];
            oci_bind_by_name($stmt, ':PARANIVEH', $PARANIVEH, 32);
            $PARTIPCAR = $post['tcarga'];
            oci_bind_by_name($stmt, ':PARTIPCAR', $PARTIPCAR, 32);
            $PARTIPVEH = $post['tvehiculo'];
            oci_bind_by_name($stmt, ':PARTIPVEH', $PARTIPVEH, 32);
            $PARESTADO = $post['estado'];
            oci_bind_by_name($stmt, ':PARESTADO', $PARESTADO, 32);
            $PARENTIDA = $pkEntidad;
            oci_bind_by_name($stmt, ':PARENTIDA', $PARENTIDA, 32);
            $PARUSUCRE = $usuario['USUARIO_ACCESO'];
            oci_bind_by_name($stmt, ':PARUSUCRE', $PARUSUCRE, 32);
            $PARRESPUE;
            oci_bind_by_name($stmt, ':PARRESPUE', $PARRESPUE, 32);
            if (!oci_execute($stmt)) {
                $e = oci_error($stmt);
                VAR_DUMP($e);
                exit;
            } else if ($PARRESPUE != 1) {
                if ($PARRESPUE == 104) {
                    $PARRESPUE = 'ERROR ' . $PARRESPUE . ' EL VEHICULO YA FUE CREADO';
                } else {
                    $PARRESPUE = 'ERROR ' . $PARRESPUE . ' NO SE PUEDE CREAR PORTADOR';
                }
            }
            $data['error'] = $PARRESPUE;
        }


        $tipoCarga = $this->db->query('select nombre NOM, pk_tipcar_codigo PKY from modbontra.bontbltipcar');
        $data['tipoCarga'] = $tipoCarga->result_array;
        //tipo de vehiculo
        $tipoVehiculo = $this->db->query('select nombre NOM, pk_tipveh_codigo PKY from MODBONTRA.bontbltipveh');
        $data['tipoVehiculo'] = $tipoVehiculo->result_array;
        //estado
        $estado = $this->db->query('select NOMBRE NOM, pk_estveh_codigo PKY from MODBONTRA.bontblestveh');
        $data['estado'] = $estado->result_array;
        //DATOS DE SESION
        $empresa = $this->session->userdata("entidad");
        $data['empresa'] = $empresa['NOMBREEMPRESA'];
        $usuario = $this->session->userdata("usuario");
        $data['usuario'] = $usuario['NOMBRE'] . " " . $usuario['APELLIDO'];
        //
        $this->load->view('portal/templates/headerBono2', $data);
        $this->load->view('portal/bono/crearVehiculo', $data);
        $this->load->view('portal/templates/footer', $data);
    }
    public function bonoRuta($pantalla = 0)
    {
        $this->verificarPerfilCo();
        $pkEntidad = $this->session->userdata('pkentidad');
        $rutas = $this->db->query("
            SELECT 
                ruta.pk_ruta_codigo COD,
                (   SELECT NOMBRE 
                    FROM MODBONTRA.BONTBLPUNTOS PUN
                    JOIN MODBONTRA.BONTBLRUTPUN RUTPUN2 
                        ON RUTPUN2.PK_PUNTOS_CODIGO = PUN.PK_PUNTOS_CODIGO
                    WHERE RUTPUN2.PK_RUTPUN_CODIGO =  (
                        SELECT MIN(PK_RUTPUN_CODIGO) 
                        FROM MODBONTRA.BONTBLRUTPUN 
                        WHERE PK_RUTA_CODIGO = ruta.pk_ruta_codigo)
                ) PUNINI,
                
            
                (   SELECT NOMBRE 
                    FROM MODBONTRA.BONTBLPUNTOS PUN
                    JOIN MODBONTRA.BONTBLRUTPUN RUTPUN2 
                        ON RUTPUN2.PK_PUNTOS_CODIGO = PUN.PK_PUNTOS_CODIGO
                        
                    WHERE RUTPUN2.PK_RUTPUN_CODIGO = (
                        SELECT MAX(PK_RUTPUN_CODIGO) 
                        FROM MODBONTRA.BONTBLRUTPUN 
                        WHERE PK_RUTA_CODIGO = 
                      RUTA.pk_ruta_codigo)
                ) PUNFIN,
                ruta.costo_total COSTOT, 
                ruta.total_kilometros KILTOT, 
                ruta.gasolina_total GASTOT,
                ESTRUT.NOMBRE EST
                FROM MODBONTRA.BONTBLRUTA RUTA
                JOIN MODBONTRA.bontblestrut ESTRUT 
                ON estrut.pk_estrut_codigo = ruta.pk_estrut_codigo
                AND ruta.pk_entida_codigo =  {$pkEntidad}");

        $data['rutas'] = $rutas->result_array;
        //tipo de vehiculo
        $data['error'] = $pantalla;
        $data['menu'] = 'rutas';
        //DATOS DE SESION
        $empresa = $this->session->userdata("entidad");
        $data['empresa'] = $empresa['NOMBREEMPRESA'];
        $usuario = $this->session->userdata("usuario");
        $data['usuario'] = $usuario['NOMBRE'] . " " . $usuario['APELLIDO'];
        //
        $this->load->view('portal/templates/headerBono2', $data);
        $this->load->view('portal/bono/listaRuta', $data);
        $this->load->view('portal/templates/footer', $data);
    }
    public function crearRuta($pantalla = 0)
    {
        $this->verificarPerfilCo();
        $post = $this->input->post();
        if ($post) {
            $pkEntidad = $this->session->userdata('pkentidad');
            $usuario = $this->session->userdata('usuario');
            $sql = "
                BEGIN 
                    MODGENERI.GENPKGWEBSERVICE.PRCCRERUTA(
                        :PARNOMRUT, :PARCOSAPR,
                        :PARKILAPR, :PARCONGAS,
                        :PARPUNINI, :PARPUNFIN,
                        :PARESTRUT, :PAREMPRES,
                        :PARUSUCRE, :PARRESPUE);
                END;";
            $conn = $this->db->conn_id;
            $stmt = oci_parse($conn, $sql);
            $PARNOMRUT = $post['nomrut'];
            oci_bind_by_name($stmt, ':PARNOMRUT', $PARNOMRUT, 32);
            $PARCOSAPR = $post['cospro'];
            oci_bind_by_name($stmt, ':PARCOSAPR', $PARCOSAPR, 32);
            $PARKILAPR = $post['kilpro'];
            oci_bind_by_name($stmt, ':PARKILAPR', $PARKILAPR, 32);
            $PARCONGAS = $post['congas'];
            oci_bind_by_name($stmt, ':PARCONGAS', $PARCONGAS, 32);
            $PARPUNINI = $post['punini'];
            oci_bind_by_name($stmt, ':PARPUNINI', $PARPUNINI, 32);
            $PARPUNFIN = $post['punfin'];
            oci_bind_by_name($stmt, ':PARPUNFIN', $PARPUNFIN, 32);
            //var_dump($PARPUNINI);
            //var_dump($PARPUNFIN);
            //exit();
            $PARESTRUT = $post['estado'];
            oci_bind_by_name($stmt, ':PARESTRUT', $PARESTRUT, 32);
            $PAREMPRES = $pkEntidad;
            oci_bind_by_name($stmt, ':PAREMPRES', $PAREMPRES, 32);
            $PARUSUCRE = $usuario['USUARIO_ACCESO'];
            oci_bind_by_name($stmt, ':PARUSUCRE', $PARUSUCRE, 32);
            $PARRESPUE;
            oci_bind_by_name($stmt, ':PARRESPUE', $PARRESPUE, 32);
            if (!oci_execute($stmt)) {
                $e = oci_error($stmt);
                VAR_DUMP($e);
                exit;
            } else if ($PARRESPUE != 1) {
                if ($PARRESPUE == 106) {
                    $PARRESPUE = 'ERROR ' . $PARRESPUE . ' LA RUTA YA FUE CREADA ANTERIORMENTE';
                } else {
                    $PARRESPUE = 'ERROR ' . $PARRESPUE . ' NO SE PUEDE CREAR RUTA';
                }
            } elseif ($PARRESPUE == 1) {
                redirect('portal/bono/bonoRuta/' . $PARRESPUE);
            }
            $data['error'] = $PARRESPUE;
        }
        $estadoRuta = $this->db->query('SELECT NOMBRE NOM,pk_estrut_codigo PKY FROM MODBONTRA.BONTBLESTRUT');
        $data['estadoRuta'] = $estadoRuta->result_array;
        //DATOS DE SESION
        $empresa = $this->session->userdata("entidad");
        $data['empresa'] = $empresa['NOMBREEMPRESA'];
        $usuario = $this->session->userdata("usuario");
        $data['usuario'] = $usuario['NOMBRE'] . " " . $usuario['APELLIDO'];
        //
        $this->load->view('portal/templates/headerBono2', $data);
        $this->load->view('portal/bono/crearRuta', $data);
        $this->load->view('portal/templates/footer', $data);
    }
    public function gestionViaje($pantalla = 0)
    {
        $this->verificarPerfilCo();
        $pkEntidad = $this->session->userdata('pkentidad');
        $viajesact = $this->db->query("
            select 
                vehrut.pk_vehrut_codigo COD,
                ent.nombre ||' '|| ent.apellido PORTAD,
                veh.placa PLA,
                rut.nombre RUT,
                tar.numero NUMTAR,
                --vehrut.fecha_asignacion ASIGNA,
                vehrut.fecha_inicio INILAB,
                --vehrut.fecha_finalizacion FECFIN,
                estvru.nombre ESTADO
            from 
                MODBONTRA.bontblvehrut vehrut
            join MODBONTRA.bontblportad por 
                on por.pk_portad_codigo = vehrut.pk_portad_codigo 
                and por.pk_entida_codigoemp = {$pkEntidad}
            join MODCLIUNI.clitblentida ent 
                on ent.pk_ent_codigo = por.pk_entida_codigo
            join MODBONTRA.bontblvehicu veh 
                on veh.pk_vehicu_codigo = vehrut.pk_vehicu_codigo 
                and veh.pk_entida_codigo = {$pkEntidad}
            join MODBONTRA.bontblruta rut 
                on rut.pk_ruta_codigo = vehrut.pk_ruta_codigo 
                and rut.pk_entida_codigo = {$pkEntidad}
            join MODBONTRA.bontblestvru estvru 
                on estvru.pk_estvru_codigo = vehrut.pk_estvru_codigo
                and estvru.pk_estvru_codigo = 1
            join MODTARHAB.tartbltarjet tar 
                on tar.PK_TARTBLCUENTA_CODIGO = vehrut.PK_CUENTA_CODIGO    
        ");
        $data['viajesact'] = $viajesact->result_array;
        $viajesfin = $this->db->query("
            select
                vehrut.pk_vehrut_codigo COD,
                ent.nombre ||' '|| ent.apellido PORTAD,
                veh.placa PLA,
                rut.nombre RUT,
                tar.numero NUMTAR,
                --vehrut.fecha_asignacion ASIGNA,
                vehrut.fecha_inicio INILAB,
                --vehrut.fecha_finalizacion FECFIN,
                estvru.nombre ESTADO
            from 
                MODBONTRA.bontblvehrut vehrut
            join MODBONTRA.bontblportad por 
                on por.pk_portad_codigo = vehrut.pk_portad_codigo 
                and por.pk_entida_codigoemp = {$pkEntidad}
            join MODCLIUNI.clitblentida ent 
                on ent.pk_ent_codigo = por.pk_entida_codigo
            join MODBONTRA.bontblvehicu veh 
                on veh.pk_vehicu_codigo = vehrut.pk_vehicu_codigo 
                and veh.pk_entida_codigo = {$pkEntidad}
            join MODBONTRA.bontblruta rut 
                on rut.pk_ruta_codigo = vehrut.pk_ruta_codigo 
                and rut.pk_entida_codigo = {$pkEntidad}
            join MODBONTRA.bontblestvru estvru 
                on estvru.pk_estvru_codigo = vehrut.pk_estvru_codigo
                and estvru.pk_estvru_codigo = 2
            join MODTARHAB.tartbltarjet tar 
                on tar.PK_TARTBLCUENTA_CODIGO = vehrut.PK_CUENTA_CODIGO    
        ");
        //var_dump($viajesfin);
        //exit();
        $data['viajesfin'] = $viajesfin->result_array;
        $data['menu'] = 'viajes';
        $estadoRuta = $this->db->query('SELECT NOMBRE NOM, pk_estrut_codigo COD FROM MODBONTRA.bontblestrut');
        $data['estadoRuta'] = $estadoRuta->result_array;
        //DATOS DE SESION
        $empresa = $this->session->userdata("entidad");
        $data['empresa'] = $empresa['NOMBREEMPRESA'];
        $usuario = $this->session->userdata("usuario");
        $data['usuario'] = $usuario['NOMBRE'] . " " . $usuario['APELLIDO'];
        //
        $this->load->view('portal/templates/headerBono2', $data);
        $this->load->view('portal/bono/listaViajes', $data);
        $this->load->view('portal/templates/footer', $data);
    }
    public function verViaje($pantalla = 0)
    {
        $this->verificarPerfilCo();
        $post = $this->input->post();
        if ($post) {
            $pkEntidad = $this->session->userdata('pkentidad');
            $viaje = $this->db->query("
            select 
                ent.nombre ||' '|| ent.apellido PORTAD,
                veh.placa PLA,
                rut.nombre RUT,
                tar.numero NUMTAR,
                --vehrut.fecha_asignacion ASIGNA,
                vehrut.fecha_inicio INILAB,
                --vehrut.fecha_finalizacion FECFIN,
                estvru.nombre ESTADO
            from 
                MODBONTRA.bontblvehrut vehrut
            join MODBONTRA.bontblportad por 
                on por.pk_portad_codigo = vehrut.pk_portad_codigo 
                and por.pk_entida_codigoemp = {$pkEntidad}
                and vehrut.pk_vehrut_codigo = {$post['viaje']}
            join MODCLIUNI.clitblentida ent 
                on ent.pk_ent_codigo = por.pk_entida_codigo
            join MODBONTRA.bontblvehicu veh 
                on veh.pk_vehicu_codigo = vehrut.pk_vehicu_codigo 
                and veh.pk_entida_codigo = {$pkEntidad}
            join MODBONTRA.bontblruta rut 
                on rut.pk_ruta_codigo = vehrut.pk_ruta_codigo 
                and rut.pk_entida_codigo = {$pkEntidad}
            join MODBONTRA.bontblestvru estvru 
                on estvru.pk_estvru_codigo = vehrut.pk_estvru_codigo
            join MODTARHAB.tartbltarjet tar 
                on tar.PK_TARTBLCUENTA_CODIGO = vehrut.PK_CUENTA_CODIGO     
        ");
            $data['viaje'] = $viaje->result_array;
        }
        $data['menu'] = 'viajes';
        //DATOS DE SESION
        $empresa = $this->session->userdata("entidad");
        $data['empresa'] = $empresa['NOMBREEMPRESA'];
        $usuario = $this->session->userdata("usuario");
        $data['usuario'] = $usuario['NOMBRE'] . " " . $usuario['APELLIDO'];
        //
        $this->load->view('portal/templates/headerBono2', $data);
        $this->load->view('portal/bono/verViaje', $data);
        $this->load->view('portal/templates/footer', $data);
    }
    public function crearViaje($pantalla = 0)
    {
        $this->verificarPerfilCo();
        $post = $this->input->post();
        if ($post) {
            $pkEntidad = $this->session->userdata('pkentidad');
            $usuario = $this->session->userdata('usuario');
            $rol = $this->session->userdata('rol');
            $sql = "
                BEGIN 
                    MODGENERI.GENPKGWEBSERVICE.PRCCRERUTMOVTAR (
                        :PARPORTAD, :PARVEHICU,
                        :PARRUTVEH, :PARCUENTA,
                        :PARFECINI, :PAREMPRES,
                        :PARUSUCRE, :PARPKYUSU,
                        :PARROLUSU, :PARRESPUE
                        );
                END;";
            $conn = $this->db->conn_id;
            $stmt = oci_parse($conn, $sql);
            $PARPORTAD = $post['portador'];
            oci_bind_by_name($stmt, ':PARPORTAD', $PARPORTAD, 32);
            $PARVEHICU = $post['vehiculo'];
            oci_bind_by_name($stmt, ':PARVEHICU', $PARVEHICU, 32);
            $PARRUTVEH = $post['ruta'];
            oci_bind_by_name($stmt, ':PARRUTVEH', $PARRUTVEH, 32);
            $PARCUENTA = $post['tarjeta'];
            oci_bind_by_name($stmt, ':PARCUENTA', $PARCUENTA, 32);
            $PARFECINI = date_format(date_create($post['fecini']), 'd-M-Y');
            oci_bind_by_name($stmt, ':PARFECINI', $PARFECINI, 32);
            $PAREMPRES = $pkEntidad;
            oci_bind_by_name($stmt, ':PAREMPRES', $PAREMPRES, 32);
            $PARUSUCRE = $usuario['USUARIO_ACCESO'];
            oci_bind_by_name($stmt, ':PARUSUCRE', $PARUSUCRE, 32);
            $PARPKYUSU = $usuario['PK_ENT_CODIGO'];
            oci_bind_by_name($stmt, ':PARPKYUSU', $PARPKYUSU, 32);
            $PARROLUSU = $rol;
            oci_bind_by_name($stmt, ':PARROLUSU', $PARROLUSU, 32);
            $PARRESPUE;
            oci_bind_by_name($stmt, ':PARRESPUE', $PARRESPUE, 32);

            if (!oci_execute($stmt)) {
                $e = oci_error($stmt);
                VAR_DUMP($e);
                exit;
            } else if ($PARRESPUE != 1) {
                if ($PARRESPUE == 106) {
                    $PARRESPUE = 'ERROR ' . $PARRESPUE . ' LA RUTA YA FUE CREADA ANTERIORMENTE';
                } else {
                    $PARRESPUE = 'ERROR ' . $PARRESPUE . ' NO SE PUEDE CREAR RUTA';
                }
            } elseif ($PARRESPUE == 1) {
                redirect('portal/bono/gestionViaje/' . $PARRESPUE);
            }
            $data['error'] = $PARRESPUE;
        }

        $pkEntidad = $this->session->userdata('pkentidad');
        $portador = $this->db->query("
            select ent.nombre||' '|| ent.apellido||' - '||ent.documento PORTAD,
            por.PK_PORTAD_CODIGO COD 
            from MODBONTRA.bontblportad por
            join modcliuni.clitblentida ent on ent.pk_ent_codigo = por.pk_entida_codigo
            where por.pk_entida_codigoemp = {$pkEntidad} 
            and por.PK_PORTAD_CODIGO 
            not in (select vehrut2.pk_portad_codigo 
            from MODBONTRA.bontblvehrut vehrut2 where vehrut2.pk_vehrut_codigo = 
                some ( 
                    SELECT max(vehrut.pk_vehrut_codigo)  
                    FROM MODBONTRA.bontblvehrut vehrut  
                    group by  vehrut.pk_portad_codigo,vehrut.pk_ruta_codigo,
                    vehrut.pk_vehicu_codigo) 
            and vehrut2.pk_estvru_codigo != 2 --NO CUENTAN EN ESTADO FINALIZADO
            and vehrut2.pk_portad_codigo = por.pk_portad_codigo)
        ");
        $data['portador'] = $portador->result_array;
        $vehiculo = $this->db->query("
            SELECT VEH.PLACA ||' - '||tipveh.nombre ||' - '||tipcar.nombre VEHICU,
                veh.pk_vehicu_codigo COD
                FROM MODBONTRA.bontblvehicu  VEH 
            JOIN MODBONTRA.bontbltipveh TIPVEH 
                ON tipveh.pk_tipveh_codigo = veh.pk_tipveh_codigo
            join MODBONTRA.bontbltipcar tipcar 
                on tipcar.pk_tipcar_codigo = veh.pk_tipcar_codigo
            WHERE VEH.pk_entida_codigo = {$pkEntidad}  
            and veh.pk_estveh_codigo = 1
                and veh.PK_VEHICU_CODIGO not in
                (select vehrut2.PK_VEHICU_CODIGO 
                from MODBONTRA.bontblvehrut vehrut2 
                where vehrut2.pk_vehrut_codigo = some ( 
                SELECT max(vehrut.pk_vehrut_codigo)  
                FROM MODBONTRA.bontblvehrut vehrut  group by 
                vehrut.pk_portad_codigo,vehrut.pk_ruta_codigo,vehrut.pk_vehicu_codigo) 
                and vehrut2.pk_estvru_codigo != 2 
                and vehrut2.pk_vehicu_codigo = veh.PK_VEHICU_CODIGO) 
            ");
        $data['vehiculo'] = $vehiculo->result_array;
        $ruta = $this->db->query("
            SELECT NOMBRE NOM, 
            pk_ruta_codigo  COD
            FROM MODBONTRA.bontblruta 
            WHERE pk_entida_codigo = {$pkEntidad} 
            AND pk_estrut_codigo = 1
            ");
        $data['ruta'] = $ruta->result_array;
        $tarjeta = $this->db->query("
            SELECT 
                '**** **** **** '||SUBSTR(tar.numero,-4) NUMTAR,
                cue.pk_tartblcuenta_codigo COD
            FROM MODTARHAB.TARTBLCUENTA CUE
            join MODTARHAB.tartbltarjet tar 
                    on tar.pk_tartblcuenta_codigo = cue.pk_tartblcuenta_codigo
                    AND cue.pk_ent_codigo_emp = {$pkEntidad} 
                    AND cue.numero_tartblcuenta IS NOT NULL
                    AND tar.pk_esttar_codigo = 1
                    AND cue.PK_PRODUC_CODIGO = 68
                    AND cue.pk_tartblcuenta_codigo not in
            (
            SELECT vehrut.pk_cuenta_codigo 
            FROM MODBONTRA.bontblvehrut VEHRUT
            JOIN MODBONTRA.bontblruta RUT ON rut.pk_ruta_codigo = vehrut.pk_ruta_codigo
            AND vehrut.PK_ESTVRU_CODIGO != 2
            AND rut.pk_entida_codigo = {$pkEntidad} )
            ");
        $data['tarjeta'] = $tarjeta->result_array;
        $data['menu'] = 'viajes';
        //DATOS DE SESION
        $empresa = $this->session->userdata("entidad");
        $data['empresa'] = $empresa['NOMBREEMPRESA'];
        $usuario = $this->session->userdata("usuario");
        $data['usuario'] = $usuario['NOMBRE'] . " " . $usuario['APELLIDO'];
        //
        $this->load->view('portal/templates/headerBono2', $data);
        $this->load->view('portal/bono/crearViaje', $data);
        $this->load->view('portal/templates/footer', $data);
    }
    public function listaReverso($pantalla = 0)
    {
        $this->verificarPerfilCo();
        $post = $this->input->post();
        $pkEntidad = $this->session->userdata('pkentidad');

        if ($post) {
            unset($post['DataTables_Table_0_length']);
            foreach ($post as $key => $value) {
                redirect('portal/bono/realizarReverso/' . $key);
            }
        }
         
        $tarjetas = $this->db->query("SELECT
                ent.razon_social             razsoc,
                tipdoc.abreviacion           tipdoc,
                ent.documento                docume,
                '**** **** **** '
                || substr(tar.numero, - 4) numtar,
                tar.identificador            identi,
                cue.pk_tartblcuenta_codigo   cod
                FROM
                modtarhab.tartblcuenta   cue
                JOIN modtarhab.tartbltarjet   tar ON tar.pk_tartblcuenta_codigo = cue.pk_tartblcuenta_codigo
                                                AND cue.pk_ent_codigo_emp = {$pkEntidad}
                                                AND cue.numero_tartblcuenta IS NOT NULL
                                                AND tar.pk_esttar_codigo = 1
                                                AND cue.pk_produc_codigo = 68
                JOIN modcliuni.clitblentida   ent ON ent.pk_ent_codigo = cue.pk_ent_codigo_th
                JOIN modcliuni.clitbltipdoc   tipdoc ON tipdoc.pk_td_codigo = ent.clitbltipdoc_pk_td_codigo
                    ");
        // $tarjetas = $this->db->query("
        //     SELECT 
        //         ENT.RAZON_SOCIAL RAZSOC,
        //         TIPDOC.abreviacion TIPDOC,
        //         ent.documento DOCUME,
        //         '**** **** **** '||SUBSTR(tar.numero,-4) NUMTAR,
        //         tar.identificador IDENTI,
        //         cue.pk_tartblcuenta_codigo COD
        //     FROM MODTARHAB.TARTBLCUENTA CUE
        //     join MODTARHAB.tartbltarjet tar 
        //         on tar.pk_tartblcuenta_codigo = cue.pk_tartblcuenta_codigo
        //         AND cue.pk_ent_codigo_emp = {$pkEntidad}
        //         AND cue.numero_tartblcuenta IS NOT NULL
        //         AND tar.pk_esttar_codigo = 1
        //         AND cue.PK_PRODUC_CODIGO = 68
        //        /* AND cue.pk_tartblcuenta_codigo not in
        //             (
        //             SELECT vehrut.pk_cuenta_codigo 
        //             FROM MODBONTRA.bontblvehrut VEHRUT
        //             JOIN MODBONTRA.bontblruta RUT ON rut.pk_ruta_codigo = vehrut.pk_ruta_codigo
        //             AND vehrut.PK_ESTVRU_CODIGO != 2
        //             AND rut.pk_entida_codigo = {$pkEntidad})*/
        // /*    JOIN MODCLIUNI.CLITBLENTIDA ENT
        //         ON ent.pk_ent_codigo = cue.pk_ent_codigo_th
        //     JOIN MODCLIUNI.CLITBLTIPDOC TIPDOC 
        //         ON tipdoc.pk_td_codigo = ent.clitbltipdoc_pk_td_codigo
        //     ");
            
        $data['tarjetas']=$tarjetas->result_array;
        $data['menu'] = 'reverso-tarjetas';
        //DATOS DE SESION
        $empresa = $this->session->userdata("entidad");
        $data['empresa'] = $empresa['NOMBREEMPRESA'];
        $usuario = $this->session->userdata("usuario");
        $data['usuario'] = $usuario['NOMBRE'] . " " . $usuario['APELLIDO'];
        //DATOS DE SESION
        $empresa = $this->session->userdata("entidad");
        $data['empresa'] = $empresa['NOMBREEMPRESA'];
        $usuario = $this->session->userdata("usuario");
        $data['usuario'] = $usuario['NOMBRE'] . " " . $usuario['APELLIDO'];
        $ultimaconexion = $this->session->userdata("ultimaconexion");
        $data['ultimaconexion'] = $ultimaconexion;
        //
        $this->load->view('portal/templates/headerBono2', $data);
        $this->load->view('portal/bono/listaReversoTar', $data);
        $this->load->view('portal/templates/footer', $data);
    }
    public function realizarReverso($pantalla = 0)
    {
        $this->verificarPerfilCo();
        $pkEntidad = $this->session->userdata('pkentidad');
        $post = $this->input->post();
        if($post){
            $data['error'] = 0;
            $usuario = $this->session->userdata('usuario');
            unset($post['DataTables_Table_0_length']);
            foreach ($post as $key => $value) {
                //SE VALIDA LA CUENTA Y SE BUSCA EL IDENTIFICADOR
                $identi = $this->db->query("
                    SELECT nvl(TAR.ID_EMPRESA,tar.identificador) IDENTI
                    FROM MODTARHAB.TARTBLCUENTA CUE 
                    JOIN MODTARHAB.TARTBLTARJET TAR 
                        ON tar.pk_tartblcuenta_codigo = cue.pk_tartblcuenta_codigo
                        AND CUE.IDENTIFICADOR = TAR.IDENTIFICADOR
                    AND cue.pk_ent_codigo_emp = {$pkEntidad} 
                    AND cue.pk_ent_codigo_th = {$pkEntidad}
                    AND cue.pk_produc_codigo = 68
                    AND cue.pk_tartblcuenta_codigo = {$key}
                ");
                //   var_dump($identi);
                //   exit();
                $identi=$identi->result_array;
                
                foreach ($identi as $idenkey => $idenvalue) {
                    $sql = "
                           BEGIN 
                               MODGENERI.GENPKGWEBSERVICE.PRCGENREVTARBON(
                                   :PARCUENTA, :PAREMPRES,
                                   :PARVALREV, :PARIDENTI,
                                   :PARUSUCRE, :PARREVERS,
                                   :PARRESPUE);
                           END;";
                    
                       $conn = $this->db->conn_id;
                       $stmt = oci_parse($conn, $sql);
                       $PARCUENTA = $key;
                       var_dump($idenvalue['IDENTI']);
                       exit();
                       oci_bind_by_name($stmt, ':PARCUENTA', $PARCUENTA, 32);
                       $PAREMPRES = $pkEntidad;
                       oci_bind_by_name($stmt, ':PAREMPRES', $PAREMPRES, 32);
                       $PARVALREV = $value;
                       oci_bind_by_name($stmt, ':PARVALREV', $PARVALREV, 32);
                       $PARIDENTI = $idenvalue['IDENTI'];
                       oci_bind_by_name($stmt, ':PARIDENTI', $PARIDENTI, 32);
                       $PARUSUCRE = $usuario['USUARIO_ACCESO'];
                       oci_bind_by_name($stmt, ':PARUSUCRE', $PARUSUCRE, 32);
                       oci_bind_by_name($stmt, ':PARREVERS', $PARREVERS, 32); 
                       oci_bind_by_name($stmt, ':PARRESPUE', $PARRESPUE, 32);
                                var_dump($PARRESPUE);
                       if (!oci_execute($stmt)) {
                           $e = oci_error($stmt);
                           VAR_DUMP($e);
                           exit;
                       } else if ($PARRESPUE != 1) {
                           IF ($PARRESPUE == 30008) {
                              $PARRESPUE = 'ERROR '.$PARRESPUE.' NO SE PUEDE '
                                      . 'PROCESAR'; 
                           }ELSE{
                               $PARRESPUE = 'ERROR '.$PARRESPUE.'';
                           }
                       }//elseif ($PARRESPUE == 1) {
                          // redirect('portal/bono/gestionViaje/'.$PARRESPUE);
                       //}
                       $data['error'] = $PARRESPUE;
                       $data['movimiento'] = $PARREVERS;
                   }
                }
               
            
        }
        if($pantalla != 0){
                $tarjeta = $this->db->query("
                SELECT 
                    '**** **** **** '||SUBSTR(tar.numero,-4) NUMTAR,
                    tar.identificador IDENTI,
                    cue.pk_tartblcuenta_codigo COD
                FROM MODTARHAB.TARTBLCUENTA CUE
                join MODTARHAB.tartbltarjet tar 
                    on tar.pk_tartblcuenta_codigo = cue.pk_tartblcuenta_codigo
                    AND cue.pk_ent_codigo_emp = {$pkEntidad}
                    AND CUE.pk_tartblcuenta_codigo = {$pantalla}
                ");
                $data['tarjeta']=$tarjeta->result_array;
            } 
        $data['menu'] = 'reverso-tarjetas';
        //DATOS DE SESION
        $empresa = $this->session->userdata("entidad");
        $data['empresa'] = $empresa['NOMBREEMPRESA'];
        $usuario = $this->session->userdata("usuario");
        $data['usuario'] = $usuario['NOMBRE'] . " " . $usuario['APELLIDO'];
        //
        $this->load->view('portal/templates/headerBono2', $data);
        $this->load->view('portal/bono/reversoTar', $data);
        $this->load->view('portal/templates/footer', $data);
    }
    public function principal($pantalla = 0)
    {
        $this->verificarPerfilCo();
        //DATOS DE SESION
        $empresa = $this->session->userdata("entidad");
        $data['empresa'] = $empresa['NOMBREEMPRESA'];
        $usuario = $this->session->userdata("usuario");
        $data['usuario'] = $usuario['NOMBRE'] . " " . $usuario['APELLIDO'];
        //
        $this->load->view('portal/templates/headerBono', $data);
        $this->load->view('portal/bono/bonoPrincipal', $data);
        $this->load->view('portal/templates/footer', $data);
    }
    public function listaVehiculo()
    {
        $entidadCod = $this->session->userdata["entidad"]["PK_ENT_CODIGO"];
        $tipoCarga = $this->db->query('select * from modbontra.bontblVEHICU vehi where vehi.pk_entida_codigo = ' . $entidadCod . '');
        $data['vehiculos'] = $tipoCarga->result_array;
        $this->load->view('portal/templates/headerBono2', $data);
        $this->load->view('portal/bono/listaVehiculos', $data);
        $this->load->view('portal/templates/footer', $data);
    }
  
    public function updstatar($status, $id)
    {
        
        
        $sql = "BEGIN modtarhab.tarpkgfunciones.PRCUPDATEESTTAR(
            PARPKTARCODIGO=>:PARPKTARCODIGO,
            PARNUEESTADO=>:PARNUEESTADO,
            parrespue=>:parrespue);
        END;";
        
        $conn = $this->db->conn_id;
        $stmt = oci_parse($conn, $sql);
        $PARPKTARCODIGO = $id;
        $PARNUEESTADO = $status;
        oci_bind_by_name($stmt, ':PARPKTARCODIGO', $PARPKTARCODIGO, 32);
        oci_bind_by_name($stmt, ':PARNUEESTADO', $PARNUEESTADO, 32);
        oci_bind_by_name($stmt, ':parrespue', $parrespuesta, 32);
        if (!@oci_execute($stmt)) {
            $e = oci_error($stmt);
            var_dump($e);
        }
        if ($parrespuesta == 1) {
            $this->output->set_content_type('text/css');
            $this->output->set_output($parrespuesta);
        }
    }
}
