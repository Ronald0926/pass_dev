<?php
session_start();
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Entidad extends CI_Controller {

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
        if (( $rol != 45 ) and ( $rol != 47) and ( $rol != 58)) {
            redirect('/portal/principal/pantalla');
        }
    }

    public function actualizar() {
        $this->verificarPerfilCo();

        $post = $this->input->post();
        if ($post) {

            //carga del logo
            $url = "";
            if ($_FILES['logoEntidad']['name'] != "") {
                $config['upload_path'] = './static/files/entidad/';
                $config['allowed_types'] = 'gif|jpg|png|jpeg';
                $config['max_size'] = '1040';
                $config['max_width'] = '300';
                $config['max_height'] = '300';
                $config['encrypt_name'] = TRUE;

                $this->load->library('upload', $config);

                if (!$this->upload->do_upload('logoEntidad')) {
                    redirect("/portal/entidad/actualizar?error");
                    exit();
                } else {
                    $file = $this->upload->data();
                    $url = $_SERVER['name_server'] . '/static/files/entidad/' . $file['file_name'];
                }
            } else {
                //$icono = $this->session->userdata('entidad');
                $icono = $_SESSION['entidad'];
                $url = $icono['ICONO'];
            }

            //$usuario = $this->session->userdata("usuario");
            $usuario = $_SESSION['usuario'];
            $usuarioactual = $usuario['USUARIO_ACCESO'];

            //$entidad = $this->session->userdata("entidad");
            $entidad = $_SESSION['entidad'];
            $data['entidad'] = $entidad;
            $pk_ent_codigo = $entidad['PK_ENT_CODIGO'];

            /* actualizar tabla entidad */
            $resulact = $this->db->query("
                UPDATE 
                    modcliuni.clitblentida
                SET   
                    CORREO_ELECTRONICO=UPPER('{$post['correo']}'),
                    USUARIO_ACTUALIZACION='$usuarioactual',
                    CLITBLCIUDAD_PK_CIU_CODIGO='{$post['ciudad']}',
                    ICONO='$url',
                    FECHA_ACTUALIZACION=SYSDATE
                WHERE 
                    PK_ENT_CODIGO='$pk_ent_codigo'
                ");


            /* consulta el ultimo dato de direccion almacenado para esa entidad */
            $iddireccion = $this->db->query("select MODCLIUNI.CLIPKGCONSULTAS.fncmaxpkcontacto($pk_ent_codigo,48)"
                    . " CODIGO from dual");
            $iddirec = $iddireccion->result_array[0];

            /* actualizacion de los datos de direccion */
            $result = $this->db->query("UPDATE MODCLIUNI.CLITBLCONTAC  SET DATO='{$post['direccion']}|{$post['piso']}|{$post['edificio']}|{$post['barrio']}'"
                    . " WHERE PK_CONTAC_CODIGO={$iddirec['CODIGO']}");


            /* consulta el ultimo dato de direccion almacenado para esa entidad */
            $idtelefono = $this->db->query("select MODCLIUNI.CLIPKGCONSULTAS.fncmaxpkcontacto($pk_ent_codigo,47)"
                    . " CODIGO from dual");
            $idtel = $idtelefono->result_array[0];

            /* actualizacion de los datos de direccion */
            $result = $this->db->query("UPDATE MODCLIUNI.CLITBLCONTAC  SET DATO='{$post['telefono']}'"
                    . " WHERE PK_CONTAC_CODIGO={$idtel['CODIGO']}");

            //actuliza la entidad en la session
            //$entidad = $this->session->userdata("entidad");
            $entidad = $_SESSION['entidad'];
            $entidad['ICONO'] = $url;
            $this->session->set_userdata(
                    array(
                        "entidad" => $entidad
                    )
            );

            redirect('/portal/entidad/actualizar?ok');
            exit();
        }
        //$empresa = $this->session->userdata("entidad");
        $empresa = $_SESSION['entidad'];
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


        $data['empresa'] = $empresa['NOMBREEMPRESA'];
        //$usuario = $this->session->userdata("usuario");
        $usuario = $_SESSION['usuario'];
        $data['usuario'] = $usuario['NOMBRE'] . " " . $usuario['APELLIDO'];

        $departamentos = $this->db->query("SELECT PK_DEP_CODIGO, NOMBRE "
                . "FROM MODCLIUNI.CLITBLDEPPAI ORDER BY NOMBRE ASC");
        $data['departamentos'] = $departamentos->result_array;

        //$entidad = $this->session->userdata("entidad");
        $entidad = $_SESSION['entidad'];
        $data['entidad'] = $entidad;

        $infoentidad = $this->db->query("select CORREO_ELECTRONICO,CLITBLCIUDAD_PK_CIU_CODIGO CIUDAD"
                . ", CLITBLDEPPAI_PK_DEP_CODIGO DEPARTAMENTO"
                . " FROM MODCLIUNI.CLITBLENTIDA "
                . " JOIN MODCLIUNI.CLITBLCIUDAD "
                . "ON CLITBLCIUDAD_PK_CIU_CODIGO = PK_CIU_CODIGO"
                . " WHERE PK_ENT_CODIGO='{$entidad['PK_ENT_CODIGO']}'");

        $data['infoentida'] = $infoentidad->result_array[0];

        $telefono = $this->db->query("SELECT DATO FROM MODCLIUNI.CLITBLCONTAC WHERE PK_CONTAC_CODIGO"
                . "= MODCLIUNI.CLIPKGCONSULTAS.fncmaxpkcontacto({$entidad['PK_ENT_CODIGO']},47)");
        $data['telefono'] = $telefono->result_array[0];

        $direccion = $this->db->query("SELECT DATO FROM MODCLIUNI.CLITBLCONTAC WHERE PK_CONTAC_CODIGO"
                . "= MODCLIUNI.CLIPKGCONSULTAS.fncmaxpkcontacto({$entidad['PK_ENT_CODIGO']},48)");
        $data['direccion'] = $direccion->result_array[0];

        $datosdir = explode('|', $data['direccion']['DATO']);
        $data['direccion'] = $datosdir[0];
        $data['piso'] = $datosdir[1];
        $data['edificio'] = $datosdir[2];
        $data['barrio'] = $datosdir[3];

        $departamentos = $this->db->query("SELECT PK_DEP_CODIGO, NOMBRE "
                . "FROM MODCLIUNI.CLITBLDEPPAI ORDER BY NOMBRE");
        $data['departamentos'] = $departamentos->result_array;
        //enviar departamento 
        foreach ($departamentos->result_array as $value) {
            if ($value['PK_DEP_CODIGO'] == $infoentidad->result_array[0]['DEPARTAMENTO']) {
                $data['depentidad'] = $value['NOMBRE'];
            }
        }
        //enviar ciudad
        $ciudades = $this->db->query("SELECT PK_CIU_CODIGO, NOMBRE"
                . " FROM MODCLIUNI.CLITBLCIUDAD"
                . " WHERE CLITBLDEPPAI_PK_DEP_CODIGO='{$infoentidad->result_array[0]['DEPARTAMENTO']}'");
        foreach ($ciudades->result_array as $value) {
            if ($value['PK_CIU_CODIGO'] == $infoentidad->result_array[0]['CIUDAD']) {
                $data['ciuentidad'] = $value['NOMBRE'];
            }
        }

        $sqlipserver="select VALOR_PARAMETRO from modgeneri.gentblpargen  where pk_pargen_codigo=34";
        $queryipserver=$this->db->query($sqlipserver)->row();
        
        $ipserver= $queryipserver->VALOR_PARAMETRO;
        
        $pk_entidad=$_SESSION['pkentidad'];
        $cpana=$_SESSION['campana'];
        $sqlnumerocotizacion="select PK_PROCES_CODIGO
         from MODCOMERC.comtblcotiza cotizacion
         LEFT JOIN  MODCOMERC.COMTBLPROCES proceso on cotizacion.pk_cotiza_codigo  = proceso.pk_cotiza_codigo
         LEFT JOIN MODCOMERC.COMTBLDISPON DIS on  DIS.PK_DISPON_CODIGO = proceso.PK_DISPON_CODIGO
         LEFT JOIN MODCLIUNI.CLITBLCAMPAN campana on campana.pk_campan_codigo=cotizacion.pk_campana_codigo
         where cotizacion.PK_ENTIDA_CLIENTE= $pk_entidad
         and  proceso.PK_ESTADO_CODIGO=1
         and cotizacion.PK_CAMPANA_CODIGO=$cpana
         order by proceso.PK_PROCES_CODIGO desc";
          
        $querynumeroctizacion=$this->db->query($sqlnumerocotizacion)->row();
        $numerocotizacion= $querynumeroctizacion->PK_PROCES_CODIGO;
        $SqlCotiza = $this->db->query("SELECT MODCLIUNI.PKG_FUC.FNENCRYPTPHP('$numerocotizacion') NUM_COT from dual");
        $pk_cotiza = $SqlCotiza->result_array[0]['NUM_COT'];
        $data['numerocotizacion']=$pk_cotiza;
        //$ultimaconexion = $this->session->userdata("ultimaconexion");
        $ultimaconexion = $_SESSION['ultimaconexion'];
        $data['ultimaconexion'] = $ultimaconexion;
        $this->load->view('portal/templates/header2', $data);
        $this->load->view('portal/entidad/actualizar', $data);
        $this->load->view('portal/templates/footer', $data);
    }

    public function datosEmpresa() {
        $this->verificarPerfilCo();
        //$empresa = $this->session->userdata("entidad");
        $empresa = $_SESSION['entidad'];
        $data['empresa'] = $empresa['NOMBREEMPRESA'];
        //$usuario = $this->session->userdata("usuario");
        $usuario = $_SESSION['usuario'];
        $data['usuario'] = $usuario['NOMBRE'] . " " . $usuario['APELLIDO'];

        $departamentos = $this->db->query("SELECT PK_DEP_CODIGO, NOMBRE "
                . "FROM MODCLIUNI.CLITBLDEPPAI ORDER BY NOMBRE");
        $data['departamentos'] = $departamentos->result_array;

        //$entidad = $this->session->userdata("entidad");
        $entidad = $_SESSION['entidad'];
        $data['entidad'] = $entidad;

        $infoentidad = $this->db->query("select CORREO_ELECTRONICO,CLITBLCIUDAD_PK_CIU_CODIGO CIUDAD"
                . ", CLITBLDEPPAI_PK_DEP_CODIGO DEPARTAMENTO"
                . " FROM MODCLIUNI.CLITBLENTIDA "
                . " JOIN MODCLIUNI.CLITBLCIUDAD "
                . "ON CLITBLCIUDAD_PK_CIU_CODIGO = PK_CIU_CODIGO"
                . " WHERE PK_ENT_CODIGO='{$entidad['PK_ENT_CODIGO']}'");

        $data['infoentida'] = $infoentidad->result_array[0];

        $telefono = $this->db->query("SELECT DATO FROM MODCLIUNI.CLITBLCONTAC WHERE PK_CONTAC_CODIGO"
                . "= MODCLIUNI.CLIPKGCONSULTAS.fncmaxpkcontacto({$entidad['PK_ENT_CODIGO']},47)");
        $data['telefono'] = $telefono->result_array[0];

        $direccion = $this->db->query("SELECT DATO FROM MODCLIUNI.CLITBLCONTAC WHERE PK_CONTAC_CODIGO"
                . "= MODCLIUNI.CLIPKGCONSULTAS.fncmaxpkcontacto({$entidad['PK_ENT_CODIGO']},48)");
        $data['direccion'] = $direccion->result_array[0];

        $datosdir = explode('|', $data['direccion']['DATO']);
        $data['direccion'] = $datosdir[0];
        $data['piso'] = $datosdir[1];
        $data['edificio'] = $datosdir[2];
        $data['barrio'] = $datosdir[3];

        $ciudad = $this->db->query("SELECT pais.NOMBRE NOMBREPAIS, dep.NOMBRE NOMBREDEPARTAMENTO,ciu.nombre NOMBRECIUDAD
                FROM MODCLIUNI.CLITBLPAIS pais
                JOIN MODCLIUNI.CLITBLDEPPAI dep
                ON pais.pk_pais_codigo=dep.clitblpais_pk_pais_codigo
                JOIN MODCLIUNI.CLITBLCIUDAD ciu
                ON ciu.CLITBLDEPPAI_PK_DEP_CODIGO=dep.pk_dep_codigo
                JOIN MODCLIUNI.CLITBLENTIDA ent
                ON ent.clitblciudad_pk_ciu_codigo=ciu.pk_ciu_codigo
                WHERE ent.pk_ent_codigo={$entidad['PK_ENT_CODIGO']}");
        $data['ciudad'] = $ciudad->result_array[0];
        //$ultimaconexion = $this->session->userdata("ultimaconexion");
        $ultimaconexion = $_SESSION['ultimaconexion'];
        $data['ultimaconexion'] = $ultimaconexion;

        $this->load->view('portal/templates/header2', $data);
        $this->load->view('portal/entidad/datosEmpresa', $data);
        $this->load->view('portal/templates/footer', $data);
    }
    public  function  redirecionafuc(){
   
        $sqlipserver="select VALOR_PARAMETRO from modgeneri.gentblpargen  where pk_pargen_codigo=34";
        $queryipserver=$this->db->query($sqlipserver)->row();
        
        $ipserver= $queryipserver->VALOR_PARAMETRO;
        
        $pk_entidad=$_SESSION['pkentidad'];
        $cpana=$_SESSION['campana'];
        $sqlnumerocotizacion="select PK_PROCES_CODIGO
         from MODCOMERC.comtblcotiza cotizacion
         LEFT JOIN  MODCOMERC.COMTBLPROCES proceso on cotizacion.pk_cotiza_codigo  = proceso.pk_cotiza_codigo
         LEFT JOIN MODCOMERC.COMTBLDISPON DIS on  DIS.PK_DISPON_CODIGO = proceso.PK_DISPON_CODIGO
         LEFT JOIN MODCLIUNI.CLITBLCAMPAN campana on campana.pk_campan_codigo=cotizacion.pk_campana_codigo
         where cotizacion.PK_ENTIDA_CLIENTE= $pk_entidad
         and  proceso.PK_ESTADO_CODIGO=1
         and cotizacion.PK_CAMPANA_CODIGO=$cpana
         order by proceso.PK_PROCES_CODIGO desc";
          
        $querynumeroctizacion=$this->db->query($sqlnumerocotizacion)->row();
        $numerocotizacion= $querynumeroctizacion->PK_PROCES_CODIGO;
        //echo $numerocotizacion;
        //$sqlencryptncotizacion="SELECT MODGENERI.GENPKGCLAGEN.ENCRYPT('1135') NUM_COT from dual";
        
        //$querynumercotizacion=$this->db->query($sqlencryptncotizacion);
        
        //$nmcotizacion= $querynumercotizacion->result_array[0]['NUM_COT'];
        
        
        
        $SqlCotiza = $this->db->query("SELECT MODCLIUNI.PKG_FUC.FNENCRYPTPHP('$numerocotizacion') NUM_COT from dual");
        $pk_cotiza = $SqlCotiza->result_array[0]['NUM_COT'];
        
        //log_info($this->logHeader . 'COTIZACION  ' . urldecode($pk_cotiza));
        
        //echo $nmcotizacion;
        $urltotal=$ipserver.'?CODE='.$pk_cotiza;
        $pk_entidad=$_SESSION['pkentidad'];
        
        $sqldelete = " BEGIN MODCLIUNI.PKG_FUC.BORRAR_RESPUESTASRIESGO (:ENTCODIGO ); END;";
        $conn = $this->db->conn_id;
        $stmt = oci_parse($conn, $sqldelete);
       
       
        oci_bind_by_name($stmt, ':ENTCODIGO', $pk_entidad, 100);
        $entidad=$_SESSION['pkentidad'];
       $sqlestadofuc="UPDATE MODCLIUNI.CLITBLENTIDA SET ACTULIZAFUC = '1' WHERE PK_ENT_CODIGO= $entidad ";
        //echo $sqlestadoprospecto;
       $this->db->query($sqlestadofuc);
       
        if (!@oci_execute($stmt)) {
           $e = oci_error($stmt);
           var_dump("{$e['message']}");
           
           
       }

        echo $urltotal;
        
         }


 public   function datamayorano(){
  //print_r($_SESSION);


   $pkentidad = $_SESSION['usuario']['PK_ENT_CODIGO'];
   $documento=$_SESSION['usuario']['DOCUMENTO'];
  // $pk_entidad=$_SESSION['pkentidad'];
    //echo $pk_entidad;
    //$sql="select RAZON_SOCIAL,CORREO_ELECTRONICO, FECHA_ACTUALIZACION from modcliuni.clitblentida  where  ((trunc(sysdate) - trunc (FECHA_ACTUALIZACION)) / 30)>=12  and PK_ENT_CODIGO='".$pk_entidad."' ";
   $sqldata="   SELECT   CORREO_ELECTRONICO,
   ENTIDAD2 AS RAZON_SOCIAL,PK_VINCUL_CODIGO FROM modcliuni.viewentidadadministradores  WHERE  DOCUMENTO1='".$documento."' AND FECHA_FIN IS NULL  AND  ((trunc(sysdate) - trunc (FECHA_INICIO)) / 30)>= 12";


    


$datosentidad=$this->db->query($sqldata);
$rows= $datosentidad->result_array();


//print_r($rows);
   //die();
    echo json_encode( $rows);

    /*$dataentidades=array('razonsocial'=> $dataentidades->RAZON_SOCIAL,'email'=>$dataentidades->CORREO_ELECTRONICO);
    --echo json_encode($dataentidades);*/
}

public  function enviomail(){
 $razon=$this->input->post('razon');
 $correo=$this->input->post('correo'); 

 $sqlipserver="select VALOR_PARAMETRO from modgeneri.gentblpargen  where pk_pargen_codigo=34";
$queryipserver=$this->db->query($sqlipserver)->row();

$ipserver= $queryipserver->VALOR_PARAMETRO;

$pk_entidad=$_SESSION['pkentidad'];
$cpana=$_SESSION['campana'];
$sqlnumerocotizacion="select PK_PROCES_CODIGO
 from MODCOMERC.comtblcotiza cotizacion
 LEFT JOIN  MODCOMERC.COMTBLPROCES proceso on cotizacion.pk_cotiza_codigo  = proceso.pk_cotiza_codigo
 LEFT JOIN MODCOMERC.COMTBLDISPON DIS on  DIS.PK_DISPON_CODIGO = proceso.PK_DISPON_CODIGO
 LEFT JOIN MODCLIUNI.CLITBLCAMPAN campana on campana.pk_campan_codigo=cotizacion.pk_campana_codigo
 where cotizacion.PK_ENTIDA_CLIENTE= $pk_entidad
 and  proceso.PK_ESTADO_CODIGO=1
 and cotizacion.PK_CAMPANA_CODIGO=$cpana
 order by proceso.PK_PROCES_CODIGO desc";
  


     $querynumeroctizacion=$this->db->query($sqlnumerocotizacion)->row();
        $numerocotizacion= $querynumeroctizacion->PK_PROCES_CODIGO;
        //echo $numerocotizacion;
        //$sqlencryptncotizacion="SELECT MODGENERI.GENPKGCLAGEN.ENCRYPT('1135') NUM_COT from dual";
        
        //$querynumercotizacion=$this->db->query($sqlencryptncotizacion);
        
        //$nmcotizacion= $querynumercotizacion->result_array[0]['NUM_COT'];
        
        
        
        $SqlCotiza = $this->db->query("SELECT MODCLIUNI.PKG_FUC.FNENCRYPTPHP('$numerocotizacion') NUM_COT from dual");
        $pk_cotiza = $SqlCotiza->result_array[0]['NUM_COT'];
        
        //log_info($this->logHeader . 'COTIZACION  ' . urldecode($pk_cotiza));
        
        //echo $nmcotizacion;
        $urltotal=$ipserver.'?CODE='.$pk_cotiza;


    $sqlHost = $this->db->query("SELECT VALOR_PARAMETRO FROM MODGENERI.GENTBLPARGEN WHERE PK_PARGEN_CODIGO = 28");
    $sqlHost = $sqlHost->result_array[0]['VALOR_PARAMETRO'];
    $sqlusername = $this->db->query("SELECT VALOR_PARAMETRO FROM MODGENERI.GENTBLPARGEN WHERE PK_PARGEN_CODIGO = 29");
    $sqlusername = $sqlusername->result_array[0]['VALOR_PARAMETRO'];
    $sqlpassword = $this->db->query("select MODGENERI.GENPKGCLAGEN.DECRYPT(valor_parametro) As VALOR_PARAMETRO from modgeneri.gentblpargen where PK_PARGEN_CODIGO = 30");
    $sqlpassword = $sqlpassword->result_array[0]['VALOR_PARAMETRO'];
    $sqlSMTP = $this->db->query("SELECT VALOR_PARAMETRO FROM MODGENERI.GENTBLPARGEN WHERE PK_PARGEN_CODIGO = 31");
    $sqlSMTP = $sqlSMTP->result_array[0]['VALOR_PARAMETRO'];
    $sqlport = $this->db->query("SELECT VALOR_PARAMETRO FROM MODGENERI.GENTBLPARGEN WHERE PK_PARGEN_CODIGO = 32");
    $sqlport = $sqlport->result_array[0]['VALOR_PARAMETRO'];
    //CORREO
    $host = $sqlHost;
    $username = $sqlusername;
    $password = $sqlpassword;
    $smtpsecure = $sqlSMTP; //STARTTLS
    $port = $sqlport; //'587'
    
        $link='<a href="'.$urltotal.'" target="blank">CLICK AQUI <a>';   
       $mensaje = "Se&ntilde;ores <br> $razon<br>
       por favor actualice los datos del formulario &uacute;nico de cliente (FUC) ingresando al siguiente enlace ".$link.""; 
       $this->load->library('phpmailer_lib');
       // PHPMailer object
       $mail = $this->phpmailer_lib->load();
       // SMTP configuration
       $mail->isSMTP();
       $mail->Host =$host;// 'smtp.office365.com';
       $mail->SMTPAuth = true;
       $mail->Username = $username;//'notificaciones@peoplepass.com.co';
       $mail->Password = $password;//'P30pl32030*';
       $mail->SMTPSecure =$smtpsecure;// 'tls';
       $mail->Port = $port;//587;

       $mail->setFrom($username);
       //$mail->addReplyTo($respcorreo, $respusuario);

       // Add a recipient
       $mail->addAddress($correo);

       // Add cc or bcc 
       //$mail->addCC('cc@example.com');
       //$mail->addBCC('bcc@example.com');
       // Email subject
       $mail->Subject = "actualizacion fuc";

       // Set email format to HTML
       $mail->isHTML(true);  

       // Email body content
       $mailContent =$mensaje;
       $mail->Body = $mailContent;//$this->load->view('wsonline2/email/enviar', $data,TRUE);
     

       
       // Send email
       if (!$mail->send()) {
          
          log_info('APOLOINFO::::::::: Error Mensaje no ha sido enviado');
          log_info('APOLOINFO::::::::: mail->error'. $mail->ErrorInfo);
          echo 'El mensaje no ha sido enviado'.$mail->ErrorInfo;
           
       } else {
           log_info('APOLOINFO::::::::: El mensaje ha sido enviado');
           echo 'El mensaje ha sido enviado';
           echo '<br/>';
           $pk_entidad=$_SESSION['pkentidad'];
           $sqldelete = " BEGIN MODCLIUNI.PKG_FUC.BORRAR_RESPUESTASRIESGO (:ENTCODIGO ); END;";
           $conn = $this->db->conn_id;
           $stmt = oci_parse($conn, $sqldelete);
          
          
           oci_bind_by_name($stmt, ':ENTCODIGO', $pk_entidad, 100);
           $entidad=$_SESSION['pkentidad'];
          $sqlestadofuc="UPDATE MODCLIUNI.CLITBLENTIDA SET  ACTULIZAFUC = '1' WHERE PK_ENT_CODIGO= $entidad ";
           //echo $sqlestadoprospecto;
          $this->db->query($sqlestadofuc);
          
           if (!@oci_execute($stmt)) {
              $e = oci_error($stmt);
              var_dump("{$e['message']}");
              
              
          }

       }
       


} 

public function enviardoc() {
$data = json_decode(file_get_contents("php://input"), true);

    $post = $data;
  

        $sql = "BEGIN  modcliuni.pkg_FUC.PRO_CARGAPDF(
                    NUMCOT=>:NUMCOT,
                    TIPARCHIVO=>:TIPARCHIVO,
                    PARCODARCHI=>:PARCODARCHI); 
                    END;";

        $conn = $this->db->conn_id;

        $stmt = oci_parse($conn, $sql);

        $numero_cotizacion = $post['numero_cotizacion'];
        $sqlcotizacion = "SELECT MODGENERI.GENPKGCLAGEN.DECRYPT('$numero_cotizacion') NUM_COT from dual";
                $numcotizacion = $this->db->query($sqlcotizacion);
                $proceso = $numcotizacion->result_array[0]['NUM_COT'];
        $TIPARCHIVO = $post['tipo_archivo'];
        $PARCODARCHI = ''; //id archivo retornado para usar en eotro procedimiento

        //log_info($this->logHeader . $this->postData . ':::NUMERO COTIZACION :' . $numero_cotizacion);
        oci_bind_by_name($stmt, ':NUMCOT', $proceso, 38);
        oci_bind_by_name($stmt, ':TIPARCHIVO', $TIPARCHIVO, 38);
        oci_bind_by_name($stmt, ':PARCODARCHI', $PARCODARCHI, 38);

        $dominio = $this->db->query("select VALOR_PARAMETRO from modgeneri.gentblpargen where PK_PARGEN_CODIGO = 96")->row();
        $Vdominio = $dominio->VALOR_PARAMETRO;
        if (!@oci_execute($stmt)) {
            $e = oci_error($stmt);
            
            log_info($this->errorGeneral . '::::ERROR LLAMANDO PROCEDIMIENTO MODCLIUNI.PKG_FUC.PRO_CARGAPDF :' . $e);
        }if (!empty($PARCODARCHI)) {
            $datos = array($PARCODARCHI, $Vdominio);
            echo json_encode($datos);
        }
    
}


  public   function Downloadfuc(){
   $numero_cotizacion= $this->input->post('numero_cotizacion');
   $sqldownload='SELECT link_fuc, link_cotizacion, link_contrato FROM modcomerc.comtblproces where PK_PROCES_CODIGO='.$numero_cotizacion.'';
  // echo  $sqldownload;
   $querydownload=$this->db->query($sqldownload)->row();
   echo $querydownload->LINK_FUC;

  }

  public  function confirmarfuc(){

   $pkvinculcodigo=$this->input->post('pkvinculcode');
   
   //confirmardatafuc
   $sql = "BEGIN  modcliuni.pkg_FUC.confirmardatafuc(
    vinculcodigo=>:vinculcodigo); 
    END;";

    
  //$sql="UPDATE MODCLIUNI.CLITBLVINCUL SET FECHA_INICIO =sysdate  WHERE PK_VINCUL_CODIGO='".$pkvinculcodigo."' ";
  $conn = $this->db->conn_id;
  //$lob = oci_new_descriptor($conn, OCI_D_LOB);
  $stid = oci_parse($conn,$sql);
  //blob = oci_new_descriptor($conn, OCI_D_LOB);
  oci_bind_by_name($stid, ':vinculcodigo', $pkvinculcodigo, 100);
  if (!@oci_execute($stid)) {
    $e = oci_error($stid);
    var_dump($e);
}


  //echo $sql;
  //die();
  //if($this->db->query($sql)){
//echo "ok";

//}
  
}


}

