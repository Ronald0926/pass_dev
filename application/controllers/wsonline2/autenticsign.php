<?php

ini_set("pcre.backtrack_limit", "5000000");
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Autenticsign extends CI_Controller {

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

        $server = $this->db->query("
	  select valor_parametro 

        from modgeneri.gentblpargen 
        where PK_PARGEN_CODIGO=34 ");

        $server = $server->result_array[0];
        $urlproceso = $server['VALOR_PARAMETRO'] . $parproceso;

        $plazoFecha = date('yy-m-d', strtotime('+2 months'));
        //var_dump($plazoFecha);
        //exit();
//        require_once '/var/www/html/mpdf/vendor/autoload.php';
//        $varpkcorreo = 45;
        $varpkdirecc = 48;
        $varpktelefomov = 46;
        $varpktelefofij = 47;
        $varpkotro = 49;
//        $varpktipprerepl = 45;
//        $varpktippreaccion = 47;
//
//
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
//
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
            ,TO_CHAR(FECHA_EXPEDICION,'DD/MM/YYYY') FECHA_EXPEDICION,CORREO_ELECTRONICO,tipdoc.NOMBRE TIPODOC,
            tipdoc.PK_TD_CODIGO PKTIPODOC,ciudad.nombre ciudad,departamento.nombre departamento,pais.nombre pais
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
//
//
//
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
//
//
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
//
//
//
            $consultaFijoRep = $this->db->query("
	SELECT DATO
       FROM (SELECT  dato
        FROM MODCLIUNI.clitblcontac 
        WHERE CLITBLTIPCON_PK_TIPCON_CODIGO = $varpktelefofij 
        AND CLITBLENTIDA_PK_ENT_CODIGO = $EntidadCliente 
        ORDER BY PK_CONTAC_CODIGO DESC )WHERE ROWNUM =1");
            $consultaFijoRep = $consultaFijoRep->result_array[0];
            $dataInfo['consultaFijoRep'] = $consultaFijoRep;

            $links = $this->db->query("
                SELECT
                    LINK_FUC,
                    LINK_COTIZACION,
                    LINK_CONTRATO
                FROM
                    modcomerc.comtblproces
                    where pk_proces_codigo = $parproceso");
            $links = $links->result_array[0];

            $contratodemediosdepago = file_get_contents($links["LINK_CONTRATO"]);
            $formulariounicodecliente = file_get_contents($links["LINK_FUC"]);
            $ofertacomercialPeoplePass = file_get_contents($links["LINK_COTIZACION"]);

            $contratodemediosdepago = base64_encode($contratodemediosdepago);
            $formulariounicodecliente = base64_encode($formulariounicodecliente);
            $ofertacomercialPeoplePass = base64_encode($ofertacomercialPeoplePass);


            $json = array(
                "sendCompletionNotification" => true,
                "emailForNotification" => "juanguillermogz@gmail.com",
                "env" => "QA",
                "processes" => array(
                    array(
                        "enterpriseId" => "900209956",
                        "senderEmail" => "patricia.ramirez@peopletech.com.co",
                        "senderIdentification" => "1033694366",
                        "signers" => array(
                            array(
                                "name" => $dataInfo['rep']['PRIMER_NOMBRE'],
                                "lastName" => $dataInfo['rep']['PRIMER_APELLIDO'],
                                "identification" => $consultaRepresentanteLegal['DOCUMENTO'],
                                "email" => $consultaRepresentanteLegal['CORREO_ELECTRONICO'],
                                "phone" => $dataInfo['consultaMovilRep']['DATO'],
                                "role" => "SIGNER",
                                "authMethods" => array(
                                    "OTP"
                                )
                            )
                        ),
                        "documents" => array(
                            array("content" => $contratodemediosdepago,
                                "fileName" => "Contratodemediosdepago.pdf"),
                            array("content" => $formulariounicodecliente,
                                "fileName" => "Formulariounicodecliente.pdf"),
                            array("content" => $ofertacomercialPeoplePass,
                                "fileName" => "OfertacomercialPeoplePass.pdf")
                        ),
                        "subject" => "Firma Documentos Vinculación Peoplepass Empresa - ".$dataInfo['empresa']['RAZON_SOCIAL'],
                        "message" => "Recuerde que para completar el proceso de vinculación, se debe realizar la firma de los documentos antes del tiempo establecido por AutenTIC Sign, saludo cordial.",
                        "order" => true,
                        "expirationDate" => $plazoFecha
                    )
                )
            );
            $json = json_encode($json);
            //var_dump($json);
            //exit();
            //llamadoWS
            $url = "https://mpl.autenticsign.com/v1/signingprocess/";
            $curl = curl_init();
            curl_setopt($curl, CURLOPT_POST, 1);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $json);
            curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
            curl_setopt($curl, CURLOPT_URL, $url);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);

            $result = curl_exec($curl);
            $result = json_decode($result, true);

            //var_dump($result);
            //exit();
            curl_close($curl);
            redirect($urlproceso);
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
