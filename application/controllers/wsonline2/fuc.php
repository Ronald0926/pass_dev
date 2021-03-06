<?php

ini_set("pcre.backtrack_limit", "5000000");
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Fuc extends CI_Controller {

    public function __construct() {
        parent::__construct();
    }

    public function __destruct() {
        $this->db->close();
    }

    public function crear($parproceso = null) {

        if ($_POST['PROCESO'] != NULL) {
            $parproceso = $_POST['PROCESO'];
        }


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
            $urlpublica=$urlpublica->result_array[0];
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
            $sqlRegimen=$this->db->query("SELECT UPPER(TIPREG.NOMBRE),PK_TIPREG_CODIGO
            FROM MODCLIUNI.CLITBLDATJUR DATJUR
                 JOIN MODCLIUNI.clitbltipreg TIPREG ON tipreg.pk_tipreg_codigo = datjur.clitbltipreg_pk_tipreg_codigo
             WHERE datjur.clitblentida_pk_ent_codigo = $EntidadCliente
                AND datjur.usuario_actualizacion IS NULL
                AND datjur.fecha_actualizacion IS NULL");
          $InfoRegimen= $sqlRegimen->result_array[0];
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
            periodos_declarados,TIPEMP_OTRO,TIPSOC_OTRO
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


            //Juan camilo Fonseca
            $consultaDeAlgo = $this->db->query(" 
	        select 
                dir.NOMBRE,
                dir.SEGUNDO_NOMBRE,
               dir.PRIMER_APELLIDO,
               dir.SEGUNDO_APELLIDO ,
               dir.CARGO ,
               depdir.NOMBRE 
               ,dir.INDICATIVO 
               ,dir.TELEFONO 
               ,dir.EXTENSION 
               ,dir.CORREO_ECTRONICO 
               ,dir.FECHA_NACIMIENTO 
            from MODCLIUNI.CLITBLDIRECT dir 
            left join MODCLIUNI.CLITBLDEPDIR depdir 
            on dir.CLITBLDEPDIR_PK_DEPDIR_CODIGO = depdir.PK_DEPDIR_CODIGO 
            where dir.CLITBLENTIDA_PK_ENT_CODIGO = $EntidadCliente
            AND DIR.PK_PROCES_CODIGO = $parproceso  ");
            $consultaDeAlgo = $consultaDeAlgo->result_array[0];

            //
            $preguntasOperacionesInt = $this->db->query(" 
        select 
                ROW_NUMBER() OVER(
                ORDER BY pre.pregunta DESC
            ) row_num,pre.pregunta
                ,res.respuesta
                ,res.indicar,pre.PK_PREGVER_CODIGO
                from MODCLIUNI.clitbldjupre res 
                join modcliuni.CLITBLPREVER pre  on res.clitblprever_pk_pregver_codigo = pre.pk_pregver_codigo
                where
         CLITBLDATJUR_PK_DATJUR_CODIGO = {$consultaDatosJuridicos['PK_DATJUR_CODIGO']}  ");
            $preguntasOperacionesInt = $preguntasOperacionesInt->result_array;
            $dataInfo['preguntasOperacionesInt'] = $preguntasOperacionesInt;


            $consultaAccionistas = $this->db->query("  
            SELECT 
	     ENT.PK_ENT_CODIGO , 
        vin.pk_vincul_codigo CODIGO, 
        DATNAT.PRIMER_NOMBRE||' '||DATNAT.SEGUNDO_NOMBRE NOMBRE, 
        DATNAT.PRIMER_APELLIDO||' '||DATNAT.SEGUNDO_APELLIDO APELLIDOS,  
        TIPDOC.ABREVIACION TIPODOCUMENTO, 
        ENT.DOCUMENTO DOCUMENTO,
        datnat.nacionalidad NACIONALIDAD,
        DATNAT.PK_DATNAT_CODIGO,
        VIN.PORCENTAJE_PARTICIPACION
        FROM MODCLIUNI.CLITBLVINCUL VIN 
        JOIN MODCLIUNI.CLITBLENTIDA ENT ON ent.pk_ent_codigo = vin.clitblentida_pk_ent_codigo
            AND vin.clitbltipvin_pk_tipvin_codigo = 51
            AND vin.clitblentida_pk_ent_codigo1 = $EntidadCliente
            AND vin.usuario_actualizacion IS NULL
            AND vin.fecha_actualizacion IS NULL
        JOIN MODCLIUNI.CLITBLTIPDOC TIPDOC ON TIPDOC.PK_TD_CODIGO = ent.clitbltipdoc_pk_td_codigo
        JOIN MODCLIUNI.CLITBLDATNAT DATNAT ON datnat.clitblentida_pk_ent_codigo = ent.pk_ent_codigo
            AND datnat.usuario_actualizacion IS NULL
            AND datnat.fecha_actualizacion IS NULL
        WHERE VIN.USUARIO_aCTUALIZACION IS NULL
            AND VIN.FECHA_ACTUALIZACION IS NULL  ");
            $consultaAccionistas = $consultaAccionistas->result_array;
            $dataInfo['consultaAccionistas'] = $consultaAccionistas;

            $idsaccionistas = "";
            foreach ($consultaAccionistas as $key => $consultaAccionistasItem) {
                if ($key == 0) {
                    $idsaccionistas = "" . $consultaAccionistasItem['PK_DATNAT_CODIGO'];
                } else {
                    $idsaccionistas = $idsaccionistas . "," . $consultaAccionistasItem['PK_DATNAT_CODIGO'];
                }
            }

            $consultaPreguntasAccionistas = $this->db->query("  
             select 
        ROW_NUMBER() OVER(
        ORDER BY pre.pregunta DESC
    ) row_num,pre.pregunta
        ,res.respuesta
        ,res.indicar,pre.PK_PREGVER_CODIGO,CLITBLDATNAT_PK_DATNAT_CODIGO
        from MODCLIUNI.clitbldnapre res 
        join modcliuni.CLITBLPREVER pre  on res.clitblprever_pk_pregver_codigo = pre.pk_pregver_codigo
        where
        CLITBLDATNAT_PK_DATNAT_CODIGO in ($idsaccionistas) AND pre.CLITBLTIPPRE_PK_TPREG_CODIGO = $varpktippreaccion ");
            $consultaPreguntasAccionistas = $consultaPreguntasAccionistas->result_array;
            $dataInfo['consultaPreguntasAccionistas'] = $consultaPreguntasAccionistas;


            //Ref Comercial
            $referenciaComercial = $this->db->query("  
            select 
            CLITBLDATJUR_PK_DATJUR_CODIGO, 
                RAZON_SOCIAL RAZONSOCIAL 
                ,NIT NIT 
                ,TELEFONO TELEFONO 
                ,DIRECCION DIRECCION 
                ,CONTACTO CONTACTO 
            from modcliuni.CLITBLREFCOM
            where CLITBLDATJUR_PK_DATJUR_CODIGO = {$consultaDatosJuridicos['PK_DATJUR_CODIGO']} 
                and 
                usuario_Actualizacion is null
                and fecha_Actualizacion is null  ");
            $referenciaComercial = $referenciaComercial->result_array;
            $dataInfo['referenciaComercial'] = $referenciaComercial;

            //Ref Bancarias
            $referenciaBancaria = $this->db->query("  
                SELECT   BANCO BANCO 
            ,TIPO_CUENTA TIPO_CUENTA 
            , NUMERO_CUENTA NUMERO_CUENTA 
            , NOMBRE_CUENTA
            FROM MODCLIUNI.clitblreban
               WHERE CLITBLDATJUR_PK_DATJUR_CODIGO = ({$consultaDatosJuridicos['PK_DATJUR_CODIGO']}) ");
            $referenciaBancaria = $referenciaBancaria->result_array;
            $dataInfo['referenciaBancaria'] = $referenciaBancaria;


            $perfilesplataforma = $this->db->query("select distinct
        CAM.PK_CAMPAN_cODIGO ||' - '||CAM.NOMBRE CAMPANA,
         datnat.pk_datnat_codigo, 
         datnat.PRIMER_APELLIDO PRIMER_APELLIDO,
         datnat.SEGUNDO_APELLIDO SEGUNDO_APELLIDO,
         datnat.PRIMER_NOMBRE PRIMER_NOMBRE,
         datnat.SEGUNDO_NOMBRE SEGUNDO_NOMBRE,
         DATNAT.NACIONALIDAD,
         DATNAT.clitblgenero_pk_gen_codigo,
         DATNAT.clitblestciv_pk_estciv_codigo,
         ent.pk_ent_codigo,
         ENT.FECHA_NAC_CREA FECHA_NACIMIENTO,
         ENT.CORREO_ELECTRONICO,
         TIPDOC.NOMBRE TIPO_DOCUMENTO,
        TIPDOC.PK_TD_CODIGO TIPDOC,
         ENT.DOCUMENTO DOCUMENTO,
         GEN.NOMBRE GENERO,
         CONDIR.DATO DIRECCION,
         CONTEL.DATO TELEFONO,
         PAI.NOMBRE PAIS,
         DEPPAI.NOMBRE DEPARTAMENTO,
         CIU.NOMBRE CIUDAD
        from modcliuni.clitbldatnat datnat 
        join modcliuni.clitblentida ent 
            on ent.pk_ent_codigo = datnat.clitblentida_pk_ent_codigo
            and datnat.usuario_actualizacion is null 
            and datnat.fecha_actualizacion is null
        JOIN MODCLIUNI.CLITBLTIPDOC TIPDOC 
            ON TIPDOC.PK_TD_CODIGO = ENT.CLITBLTIPDOC_PK_TD_CODIGO
        JOIN MODCLIUNI.CLITBLGENERO GEN ON GEN.PK_GEN_CODIGO = DATNAT.CLITBLGENERO_PK_GEN_CODIGO
        join modcliuni.clitblvincul vin 
            on vin.clitblentida_pk_ent_codigo = ent.pk_ent_codigo
            and vin.usuario_Actualizacion is null 
            and vin.fecha_actualizacion is null
            and vin.clitblentida_pk_ent_codigo1 = $EntidadCliente
        LEFT JOIN MODCLIUNI.CLITBLCAMPAN CAM ON CAM.PK_CAMPAN_CODIGO = VIN.CLITBLCAMPAN_PK_CAMPAN_CODIGO
        join modcliuni.clitbltipvin tipvin 
            on tipvin.pk_tipvin_codigo = vin.clitbltipvin_pk_tipvin_codigo
            and tipvin.pk_tipvin_codigo = some(45,46,47,58)

        JOIN MODCLIUNI.clitblcontac CONDIR ON CONDIR.CLITBLENTIDA_PK_ENT_CODIGO = ENT.PK_ENT_CODIGO 
            AND CONDIR.USUARIO_aCTUALIZACION IS NULL
            AND CONDIR.FECHA_ACTUALIZACION IS NULL --DIRECCION
            AND CONDIR.CLITBLTIPCON_PK_TIPCON_CODIGO = 48
        JOIN MODCLIUNI.clitblcontac CONTEL ON CONTEL.CLITBLENTIDA_PK_ENT_CODIGO = ENT.PK_ENT_CODIGO 
            AND CONTEL.USUARIO_aCTUALIZACION IS NULL
            AND CONTEL.FECHA_ACTUALIZACION IS NULL --TELEFONO
            AND CONTEL.CLITBLTIPCON_PK_TIPCON_CODIGO = 46
        JOIN MODCLIUNI.CLITBLCIUDAD CIU ON CIU.PK_CIU_CODIGO = ENT.CLITBLCIUDAD_PK_CIU_CODIGO
        JOIN MODCLIUNI.CLITBLDEPPAI DEPPAI ON DEPPAI.PK_DEP_CODIGO = CIU.CLITBLDEPPAI_PK_DEP_CODIGO
        JOIN MODCLIUNI.CLITBLPAIS PAI ON PAI.PK_PAIS_CODIGO = DEPPAI.CLITBLPAIS_PK_PAIS_CODIGO");
            $perfilesplataforma = $perfilesplataforma->result_array;
            $dataInfo['perfilesplataforma'] = $perfilesplataforma;
            
            $rolesPlataforma = $this->db->query("
                select 
        tipvin.nombre ROL,
        tipvin.PK_TIPVIN_CODIGO ROLID,
         ENT.DOCUMENTO DOCUMENTO
        from modcliuni.clitbldatnat datnat 
        join modcliuni.clitblentida ent 
            on ent.pk_ent_codigo = datnat.clitblentida_pk_ent_codigo
            and datnat.usuario_actualizacion is null 
            and datnat.fecha_actualizacion is null
        join modcliuni.clitblvincul vin 
            on vin.clitblentida_pk_ent_codigo = ent.pk_ent_codigo
            and vin.usuario_Actualizacion is null 
            and vin.fecha_actualizacion is null
            and vin.clitblentida_pk_ent_codigo1 = $EntidadCliente
        join modcliuni.clitbltipvin tipvin 
            on tipvin.pk_tipvin_codigo = vin.clitbltipvin_pk_tipvin_codigo
            and tipvin.pk_tipvin_codigo = some(45,46,47,56)

            ");
            $rolesPlataforma = $rolesPlataforma->result_array;
            $dataInfo['rolesPlataforma'] = $rolesPlataforma;

            $contenido = $this->load->view('/wsonline2/fuc/lista', $dataInfo, TRUE);

            $dir = 'uploads/fuc/';
            $date = date('Y-m-d');
            $random = rand(1000, 9999);
            $name = strtolower($empresa['DOCUMENTO']. '-' .$date . '-' . $random . '.pdf');
            $file_dir = $dir . $name;
            //$url = 'http://' . $_SERVER['SERVER_ADDR'] . ':' . $_SERVER['SERVER_PORT'] . '/uploads/' . $name;
            $url= $urlpublica['VALOR_PARAMETRO'].'/'.$dir.$name;         
            //$url = 'http://localhost:' . $_SERVER['SERVER_PORT'] . '/uploads/' . $name;
            $nombre = $file_dir;

            $mpdf = new \Mpdf\Mpdf([
                'tempDir' => 'mpdf/tmp',
                'mode' => 'utf-8',
                'format' => 'A4',
                'margin_header' => 10,
                'margin_footer' => 10,
                'margin_top' => 10,
                'margin_bottom' => 10,
                'margin_left' => 5,
                'margin_right' => 5,
                'default_font' => 'Arial'
            ]);
            $html = mb_convert_encoding($contenido, 'UTF-8', 'UTF-8');

            // $mpdf->SetHTMLHeader($header);
            // $mpdf->SetHTMLFooter($footer);
            //$mpdf->AddPage();
            //$mpdf->WriteHTML($css, \Mpdf\HTMLParserMode::HEADER_CSS);

            $mpdf->WriteHTML($html);
            $mpdf->Output($nombre, 'F');
            
            $sql = " 
                    BEGIN MODCOMERC.compkgfunciones.prcinsertarlinkfuck(
                    linkfuc=>:linkfuc,
                    proceso=>:proceso);
                    END;";
            $conn = $this->db->conn_id;
            $stmt = oci_parse($conn, $sql);
            
            oci_bind_by_name($stmt, ':linkfuc', $url, 70);
            oci_bind_by_name($stmt, ':proceso', $parproceso, 32);
            if (!@oci_execute($stmt)) {
                $e = oci_error($stmt);
                var_dump($e);
            }
            
            // echo $url;
            $this->output->set_content_type('text/css');
            $this->output->set_output($url);
            return $url;
        } catch (Exception $exc) {
            return 'No se puede generar el archivo';
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
