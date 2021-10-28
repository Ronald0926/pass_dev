<?php

ini_set("pcre.backtrack_limit", "5000000");
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Contrato extends CI_Controller {

    public function __construct() {
        parent::__construct();
    }

    public function __destruct() {
        $this->db->close();
    }

    public function crear($parproceso = null, $tipo_contrato = null) {

        if ($_POST['PROCESO'] != NULL) {
            $parproceso = $_POST['PROCESO'];
        }
        $server = $this->db->query("
	  select valor_parametro 

        from modgeneri.gentblpargen 
        where PK_PARGEN_CODIGO=34 ");

        $server = $server->result_array[0];

        $serverweb = $this->db->query("
	  select valor_parametro 

        from modgeneri.gentblpargen 
        where PK_PARGEN_CODIGO=96 ");

        $serverweb = $serverweb->result_array[0];

        $urlweb = $serverweb['VALOR_PARAMETRO'];
        $urlproceso = $server['VALOR_PARAMETRO'] . $parproceso;
//        var_dump($tipo_contrato);
//        die();
        if ($tipo_contrato != 2 and $tipo_contrato != 3) {
            redirect($urlproceso);
        };
        //var_dump($urlweb);
        //exit();

        require_once '/var/www/html/mpdf/vendor/autoload.php';
        $varpkcorreo = 45;
        $varpkdirecc = 48;
        $varpktelefomov = 46;
        $varpktelefofij = 47;
        $varpkotro = 49;
        $varpktipprerepl = 45;
        $varpktippreaccion = 47;


        try {

            $urlpublica = $this->db->query("select VALOR_PARAMETRO from modgeneri.gentblpargen where pk_pargen_codigo =96");
            $urlpublica = $urlpublica->result_array[0];
//Entidad del cliente
            $EntidadCliente = $this->db->query("
	SELECT pk_entida_cliente ,comercial.NOMBRE||' '||comercial.apellido COMERCIAL 
        FROM MODCOMERC.comtblcotiza co  
        join MODCOMERC.COMTBLPROCES  pr 
        on co.pk_cotiza_codigo = pr.pk_cotiza_codigo 
        join modcliuni.clitblentida comercial
        ON comercial.PK_ENT_CODIGO=co.PK_ENTIDAD_ASESOR
        where pr.pk_proces_codigo = $parproceso ");

            $EntidadCliente = $EntidadCliente->result_array[0];

            $dataInfo['comercial'] = $EntidadCliente['COMERCIAL'];
            $EntidadCliente = $EntidadCliente['PK_ENTIDA_CLIENTE'];
            $dataInfo['proceso'] = $parproceso;

            $dataInfo['entidadCliente'] = $EntidadCliente;
            $hoy = getdate();
            $dataInfo['dia'] = $hoy['mday'];
            $dataInfo['mes'] = $hoy['mon'];
            $dataInfo['ano'] = $hoy['year'];

            /*
             * Consulta regimen de la entidad ronald 27/02/2020
             */
            $sqlRegimen = $this->db->query("SELECT UPPER(TIPREG.NOMBRE),PK_TIPREG_CODIGO
            FROM MODCLIUNI.CLITBLDATJUR DATJUR
                 JOIN MODCLIUNI.clitbltipreg TIPREG ON tipreg.pk_tipreg_codigo = datjur.clitbltipreg_pk_tipreg_codigo
             WHERE datjur.clitblentida_pk_ent_codigo = $EntidadCliente
                AND datjur.usuario_actualizacion IS NULL
                AND datjur.fecha_actualizacion IS NULL");
            $InfoRegimen = $sqlRegimen->result_array[0];
            $dataInfo['regimen'] = $InfoRegimen;

            $InfoEntidad = $this->db->query("
	SELECT 
                RAZON_SOCIAL ,
                DOCUMENTO ,
                FECHA_NAC_CREA ,
                CORREO_ELECTRONICO 
            FROM  MODCLIUNI.clitblentida 
            where pk_ent_codigo = $EntidadCliente ");
            $InfoEntidad = $InfoEntidad->result_array[0];
            $dataInfo['empresa'] = $InfoEntidad;

            $consultaDireccion = $this->db->query("
	 select dato,ciudad,departamento
             from (  SELECT dato,
             ciudad.nombre ciudad,
             departamento.nombre departamento
        FROM MODCLIUNI.clitblcontac contac 
        join MODCLIUNI.clitblciudad ciudad
        on contac.CLITBLCIUDAD_PK_CIU_CODIGO=ciudad.PK_CIU_CODIGO
        join MODCLIUNI.CLITBLDEPPAI departamento
        on departamento.PK_DEP_CODIGO = ciudad.CLITBLDEPPAI_PK_DEP_CODIGO
        where 
          CLITBLENTIDA_PK_ENT_CODIGO=$EntidadCliente
          AND contac.USUARIO_ACTUALIZACION IS NULL
          AND contac.FECHA_ACTUALIZACION IS NULL
          AND CLITBLTIPCON_PK_TIPCON_CODIGO=$varpkdirecc order by PK_CONTAC_CODIGO desc)where  rownum =1 ");
            $consultaDireccion = $consultaDireccion->result_array[0];
            $dataInfo['consultaDireccion'] = $consultaDireccion;

            $consultaMovil = $this->db->query("
	select dato
        from ( SELECT dato
        FROM MODCLIUNI.clitblcontac 
        WHERE CLITBLTIPCON_PK_TIPCON_CODIGO = $varpktelefomov 
            AND CLITBLENTIDA_PK_ENT_CODIGO = $EntidadCliente 
            AND USUARIO_ACTUALIZACION IS NULL 
            AND fecha_Actualizacion IS NULL order by PK_CONTAC_CODIGO desc ) where rownum=1 ");
            $consultaMovil = $consultaMovil->result_array[0];

            $consultaFijo = $this->db->query("
	select dato 
        from (SELECT dato
        FROM  MODCLIUNI.clitblcontac 
        WHERE CLITBLTIPCON_PK_TIPCON_CODIGO = $varpktelefofij 
        AND CLITBLENTIDA_PK_ENT_CODIGO = $EntidadCliente 
        AND USUARIO_ACTUALIZACION IS NULL 
        AND fecha_Actualizacion IS NULL order by PK_CONTAC_CODIGO desc) where rownum=1 ");
            $consultaFijo = $consultaFijo->result_array[0];
            $dataInfo['telefonooficina'] = $consultaMovil;

            $consultaDatosJuridicos = $this->db->query("
	SELECT
            PK_DATJUR_CODIGO,CLITBLTIPEMP_PK_TIPEMP_CODIGO,CLITBLTIPSOC_PK_TIPSOC_CODIGO,INGRESO_MENSUAL,OTROS_INGRESOS_M,TOTAL_INGRESOS,EGRESOS_MENSUALES,TOTAL_ACTIVOS,
            TOTAL_PASIVOS,TOTAL_PATRIMONIO,CONCEPTO_INGRESOS,CLITBLTIPREG_PK_TIPREG_CODIGO,MODCLIUNI.CLITBLTIPSOC.NOMBRE TIPO_SOC,MODCLIUNI.CLITBLTIPEMP.NOMBRE TIP_EMP,
            ingreso_a_anterior,
            otro_ingreso_a_anterior,
            total_ingreso_a_anterior,
            total_activos_a_anterior,
            total_pasivos_a_anterior,
            total_patrimonio_a_anterior,
            egresos_a_anterior,
            concepto_ingresos_a_anterior,
            periodos_declarados
         FROM 
             MODCLIUNI.CLITBLDATJUR 
             INNER JOIN MODCLIUNI.CLITBLTIPSOC ON MODCLIUNI.CLITBLDATJUR.CLITBLTIPSOC_PK_TIPSOC_CODIGO = MODCLIUNI.CLITBLTIPSOC.PK_TIPSOC_CODIGO
             INNER JOIN MODCLIUNI.CLITBLTIPEMP ON MODCLIUNI.CLITBLDATJUR.CLITBLTIPEMP_PK_TIPEMP_CODIGO = MODCLIUNI.CLITBLTIPEMP.PK_TIPEMP_CODIGO
            WHERE CLITBLENTIDA_PK_ENT_CODIGO = $EntidadCliente
            AND MODCLIUNI.CLITBLDATJUR.USUARIO_ACTUALIZACION IS NULL AND
            MODCLIUNI.CLITBLDATJUR.FECHA_ACTUALIZACION IS NULL ");
            $consultaDatosJuridicos = $consultaDatosJuridicos->result_array[0];
            $dataInfo['datosjuridicos'] = $consultaDatosJuridicos;

            //CIIU
            $consultaActividadEconomica = $this->db->query("
	SELECT 
        CODIGO_CIIU,
        acteco.NOMBRE ACTIVIDAD_ECONIMICA
        FROM MODCLIUNI.CLITBLDJUAEC djuaec 
        JOIN MODCLIUNI.CLITBLACTECO acteco
        ON djuaec.CLITBLACTECO_PK_AECO_CODIGO=acteco.PK_AECO_CODIGO
        where CLITBLDATJUR_PK_DATJUR_CODIGO = {$consultaDatosJuridicos['PK_DATJUR_CODIGO']} 
        AND djuaec.USUARIO_ACTUALIZACION IS NULL 
        AND djuaec.fecha_Actualizacion IS NULL
        AND PRINCIPAL_SECUN = 'P'    ");
            $consultaActividadEconomica = $consultaActividadEconomica->result_array[0];
            $dataInfo['ciiu'] = $consultaActividadEconomica;

            //conculta entidad Representante Legal
            $consultaPKRepresentanteLegal = $this->db->query("
	 SELECT clitblentida_pk_ent_codigo
            FROM  MODCLIUNI.clitblvincul 
            where clitblentida_pk_ent_codigo1 = $EntidadCliente     
            AND clitbltipvin_pk_tipvin_codigo = $varpkotro
            AND USUARIO_ACTUALIZACION IS NULL 
            AND fecha_Actualizacion IS NULL ");

            $consultaPKRepresentanteLegal = $consultaPKRepresentanteLegal->result_array[0];



            $consultaRepresentanteLegal = $this->db->query("
	 select DOCUMENTO,ciudaddos.nombre lugarexp
            ,TO_CHAR(FECHA_EXPEDICION,'DD/MM/YYYY') FECHA_EXPEDICION,CORREO_ELECTRONICO,tipdoc.NOMBRE TIPODOC,tipdoc.PK_TD_CODIGO PKTIPODOC,ciudad.nombre ciudad,departamento.nombre departamento,pais.nombre pais
            FROM  MODCLIUNI.clitblentida entidad
            join MODCLIUNI.clitblciudad ciudad
            ON entidad.CLITBLCIUDAD_PK_CIU_CODIGO=ciudad.pk_ciu_codigo
            JOIN MODCLIUNI.CLITBLTIPDOC tipdoc
            ON tipdoc.PK_TD_CODIGO=CLITBLTIPDOC_PK_TD_CODIGO
        left join MODCLIUNI.clitblciudad ciudaddos
            ON entidad.CLITBLCIUDAD_PK_CIU_CODIGO1=ciudaddos.pk_ciu_codigo
          left  join MODCLIUNI.CLITBLDEPPAI departamento
        on departamento.PK_DEP_CODIGO = ciudaddos.CLITBLDEPPAI_PK_DEP_CODIGO
        left join MODCLIUNI.CLITBLPAIS pais
        on pais.PK_PAIS_CODIGO = departamento.CLITBLPAIS_PK_PAIS_CODIGO
            WHERE pk_ent_codigo = {$consultaPKRepresentanteLegal['CLITBLENTIDA_PK_ENT_CODIGO']} ");
            $consultaRepresentanteLegal = $consultaRepresentanteLegal->result_array[0];

            $fechaDoc = $consultaRepresentanteLegal['FECHA_EXPEDICION'];
//        $fechaDoc = explode('/', $fechaDoc);
//        $dataInfo['diarep']=$fechaDoc[0];
//        $dataInfo['mesrep']=$fechaDoc[1];
//        $dataInfo['anorep']=$fechaDoc[2];
            $dataInfo['fechaDoc'] = $fechaDoc;
            $dataInfo['rep2'] = $consultaRepresentanteLegal;



            $consultaPersonaNatural = $this->db->query(" 
        SELECT  PK_DATNAT_CODIGO,
            PRIMER_APELLIDO,SEGUNDO_APELLIDO,PRIMER_NOMBRE,SEGUNDO_NOMBRE,NACIONALIDAD,genero.NOMBRE

        FROM modcliuni.clitbldatnat datnat
        LEFT JOIN modcliuni.clitblgenero genero
        ON datnat.CLITBLGENERO_PK_GEN_CODIGO=genero.PK_GEN_CODIGO
        WHERE CLITBLENTIDA_PK_ENT_CODIGO = {$consultaPKRepresentanteLegal['CLITBLENTIDA_PK_ENT_CODIGO']} 
        AND datnat.USUARIO_ACTUALIZACION IS NULL AND 
        datnat.fecha_Actualizacion IS NULL ");
            $consultaPersonaNatural = $consultaPersonaNatural->result_array[0];
            $dataInfo['rep'] = $consultaPersonaNatural;


            $consultaDireccionRep = $this->db->query("
	 
        SELECT DATO 
    FROM 
        (SELECT  dato
    FROM MODCLIUNI.clitblcontac 
    WHERE CLITBLTIPCON_PK_TIPCON_CODIGO = $varpkdirecc
    AND CLITBLENTIDA_PK_ENT_CODIGO = {$consultaPKRepresentanteLegal['CLITBLENTIDA_PK_ENT_CODIGO']}  
    AND USUARIO_ACTUALIZACION IS NULL AND fecha_Actualizacion IS NULL ORDER BY 1 DESC) WHERE ROWNUM =1 ");
            $consultaDireccionRep = $consultaDireccionRep->result_array[0];
            $dataInfo['consultaDireccionRep'] = $consultaDireccionRep;


            $consultaMovilRep = $this->db->query("
	SELECT  dato
        FROM (
        SELECT DATO 
        FROM MODCLIUNI.clitblcontac 
        WHERE CLITBLTIPCON_PK_TIPCON_CODIGO = $varpktelefomov 
        AND CLITBLENTIDA_PK_ENT_CODIGO = {$consultaPKRepresentanteLegal['CLITBLENTIDA_PK_ENT_CODIGO']}   
        ORDER BY PK_CONTAC_CODIGO DESC
        ) WHERE ROWNUM=1");
            $consultaMovilRep = $consultaMovilRep->result_array[0];
            $dataInfo['consultaMovilRep'] = $consultaMovilRep;



            $consultaFijoRep = $this->db->query("
	SELECT DATO
       FROM (SELECT  dato
        FROM MODCLIUNI.clitblcontac 
        WHERE CLITBLTIPCON_PK_TIPCON_CODIGO = $varpktelefofij 
        AND CLITBLENTIDA_PK_ENT_CODIGO = $EntidadCliente 
        ORDER BY PK_CONTAC_CODIGO DESC )WHERE ROWNUM =1");
            $consultaFijoRep = $consultaFijoRep->result_array[0];
            $dataInfo['consultaFijoRep'] = $consultaFijoRep;


            $preguntasRep = $this->db->query(" 
	SELECT 
            ROW_NUMBER() OVER(
        ORDER BY pre.pregunta DESC
    ) row_num,pre.pregunta,
            case res.respuesta 
                when 'N' then 'NO'
                ELSE 'SI' END RESPUESTA,
                res.indicar INDICAR,res.CLITBLPREVER_PK_PREGVER_CODIGO CODIGOPREG
        FROM modcliuni.clitbldnapre res 
        join modcliuni.CLITBLPREVER pre  on res.clitblprever_pk_pregver_codigo = pre.pk_pregver_codigo
        where
        CLITBLDATNAT_PK_DATNAT_CODIGO = {$consultaPersonaNatural['PK_DATNAT_CODIGO']}
         AND pre.CLITBLTIPPRE_PK_TPREG_CODIGO = $varpktipprerepl  ");
            $preguntasRep = $preguntasRep->result_array;
            $dataInfo['preguntasRep'] = $preguntasRep;



            $rolesPlataforma = $rolesPlataforma->result_array;
            $dataInfo['rolesPlataforma'] = $rolesPlataforma;


            if ($tipo_contrato == 2) {
                $contenido = $this->load->view('/wsonline2/contrato/visualn', $dataInfo, TRUE);
                $header = $this->load->view('/wsonline2/contrato/headern', $dataInfo, TRUE);
            }
            if ($tipo_contrato == 3) {
                $contenido = $this->load->view('/wsonline2/contrato/visual', $dataInfo, TRUE);
                $header = $this->load->view('/wsonline2/contrato/header', $dataInfo, TRUE);
            }
            $footer = $this->load->view('/wsonline2/contrato/footer', $dataInfo, TRUE);

            $dir = 'uploads/contrato/';
            $date = date('Y-m-d');
            $random = rand(1000, 9999);
            $name = strtolower($empresa['DOCUMENTO'] . '-' . $date . '-' . $random . '.pdf');
            $file_dir = $dir . $name;
            //$url = 'http://' . $_SERVER['SERVER_ADDR'] . ':' . $_SERVER['SERVER_PORT'] . '/uploads/' . $name;
            $url = $urlpublica['VALOR_PARAMETRO'] . '/' . $dir . $name;
            //$url = 'http://localhost:' . $_SERVER['SERVER_PORT'] . '/uploads/' . $name;
            $nombre = $file_dir;

            $mpdf = new \Mpdf\Mpdf([
                'tempDir' => 'mpdf/tmp',
                'mode' => 'utf-8',
                'format' => 'A4',
                'margin_header' => 0,
                'margin_footer' => 0,
                'margin_top' => 12,
                'margin_bottom' => 12,
                'margin_left' => -1,
                'margin_right' => -1,
                'default_font' => 'Arial'
            ]);
            $html = mb_convert_encoding($contenido, 'UTF-8', 'UTF-8');
            $header = mb_convert_encoding($header, 'UTF-8', 'UTF-8');
            $footer = mb_convert_encoding($footer, 'UTF-8', 'UTF-8');

            $mpdf->SetHTMLHeader($header);
            $mpdf->SetHTMLFooter($footer);
            $mpdf->AddPage();
            //$mpdf->WriteHTML($css, \Mpdf\HTMLParserMode::HEADER_CSS);

            $mpdf->WriteHTML($html);
            $mpdf->Output($nombre, 'F');

            $sql = " 
                    BEGIN MODCOMERC.compkgfunciones.prcinsertarlinkcontrato(
                    linkcontrato=>:linkcontrato,
                    proceso=>:proceso);
                    END;";
            $conn = $this->db->conn_id;
            $stmt = oci_parse($conn, $sql);

            oci_bind_by_name($stmt, ':linkcontrato', $url, 80);
            oci_bind_by_name($stmt, ':proceso', $parproceso, 32);
            if (!@oci_execute($stmt)) {
                $e = oci_error($stmt);
                var_dump($e);
            }

            $sql = " 
                    BEGIN MODCOMERC.compkgfunciones.prccambiarestadoproceso(
                    estado=>:estado,
                    proceso=>:proceso);
                    END;";
            $conn = $this->db->conn_id;
            $stmt = oci_parse($conn, $sql);

            oci_bind_by_name($stmt, ':estado', $tipo_contrato, 80);
            oci_bind_by_name($stmt, ':proceso', $parproceso, 32);
            if (!@oci_execute($stmt)) {
                $e = oci_error($stmt);
                var_dump($e);
            }

            // echo $url;
            //$this->output->set_content_type('text/css');
            //$this->output->set_output($url);
            //return $url;
            if ($tipo_contrato == 2) {
                redirect($urlproceso);
            }
            if ($tipo_contrato == 3) {
                redirect('/wsonline2/autenticsign/crear/' . $parproceso);
            }
            //var_dump($urlproceso);
        } catch (Exception $exc) {
            return 'No se puede generar el archivo';
        }
    }

}
