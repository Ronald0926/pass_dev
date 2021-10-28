<?php

ini_set("pcre.backtrack_limit", "5000000");
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class extracto extends CI_Controller {

    public function __construct() {
        parent::__construct();
    }

    public function __destruct() {
        $this->db->close();
    }

    public function crear() {

   
        require_once '/var/www/html/mpdf/vendor/autoload.php';
        //require_once $_SERVER["DOCUMENT_ROOT"] . "/mpdf/vendor/autoload.php";

        try {

            $urlpublica = $this->db->query("select VALOR_PARAMETRO from modgeneri.gentblpargen where pk_pargen_codigo =96");
            $urlpublica = $urlpublica->result_array[0];
            $sqlimghead = $this->db->query("SELECT URLIMG FROM MODGENERI.GENTBLIMGEXT WHERE PK_CODIGO_EXTRACTO = 1");
            $imghead = $sqlimghead->result_array[0];
            $sqlimgFooter = $this->db->query("SELECT URLIMG FROM MODGENERI.GENTBLIMGEXT WHERE PK_CODIGO_EXTRACTO = 2");
            $imgFooter = $sqlimgFooter->result_array[0];
            $data['imghead'] = $imghead;
            $data['imgFooter'] = $imgFooter;

        $codigo = $_POST['TARJETAZEUSID'];
        //echo  $codigo;
        //die();
        $fechafin = $_POST['FECHA'];
       
        //sumo 1 mes
        //echo date("d-m-Y",strtotime($fecha_actual."+ 1 month")); 
        //resto 1 mes
        
        $date=date_create($fechafin);
       
       $fechafin= date_format($date,"Ym");
       $partsfecha=explode("-",$_POST['FECHA']);

       $data['partsfecha']=$partsfecha;
              try{
            $sqlMovimientos="SELECT
                                                movtarjetaszeus.movimiento_id,
                                                movtarjetaszeus.pan_enmascarado,
                                                movtarjetaszeus.numero_transaccion,
                                                movtarjetaszeus.origen_transaccion,
                                                movtarjetaszeus.monto,
                                                movtarjetaszeus.iva,
                                                movtarjetaszeus.inc,
                                                movtarjetaszeus.propina,
                                                movtarjetaszeus.nombre_comercio,
                                                movtarjetaszeus.codigo_respuesta,
                                                movtarjetaszeus.numero_aprobacion,
                                                movtarjetaszeus.id_tipo_movimiento,
                                                movtarjetaszeus.tipo_movimiento,
                                             to_date(movtarjetaszeus.fecha_transaccion,'YYYY/MM/DD')as fecha_transaccion,
                                            to_char(to_date(movtarjetaszeus.hora_transaccion,'HH24MISS'),'HH24:mi:ss')as hora_transaccion,
                                                movtarjetaszeus.terminal,
                                                movtarjetaszeus.fk_estado_id,
                                                movtarjetaszeus.respuesta,
                                                movtarjetaszeus.transaccion_afectada,
                                                movtarjetaszeus.pais,
                                                movtarjetaszeus.ciudad,
                                                movtarjetaszeus.tipo_documento_id,
                                                movtarjetaszeus.numero_documento,
                                                movtarjetaszeus.codigo_tarjeta_zeus
                                             FROM
                                                 modtarhab.view_movtarj movtarjetaszeus                                        
                                       WHERE 
                                         movtarjetaszeus.codigo_tarjeta_zeus =$codigo 
                                     
                                                 AND to_char(to_date(MOVTARJETASZEUS.fecha_transaccion,'YYYYMMDD'),'YYYYMM') =".$fechafin."  and  (movtarjetaszeus.respuesta='Transacción Exitosa.' or
                                            movtarjetaszeus.respuesta is null)
 "; 
            //echo $sqlMovimientos;
              //die();
            

            $movimientosquery = $this->db->query($sqlMovimientos);
            $movimientos = $movimientosquery->result_array;
          
            $data['movimientos'] = $movimientos;
              } catch (Exception $exception){
                 return 'No se enviaron datos validos';                   
              }
            //pan enmascarado
            $movimientos2=$movimientosquery->row_array();
        
        $tdocumento="";
        if (!empty($movimientos2))
         {  //echo "llenos";
      //die();
            $data['pan_enmascarado'] = $movimientos2['PAN_ENMASCARADO'];
            $pk_estado = $movimientos2['PAN_ENMASCARADO'];
            //numero de c¿documento
            
            $documento = $movimientos2['NUMERO_DOCUMENTO'];

            $data['documento'] = $documento;

            //convierte el tipo de documento para poder consultar en Apolo
            $tdocumento = $movimientos2['TIPO_DOCUMENTO_ID'];
         }else{
      //echo "vacio";
      //die();
  $sqldata="SELECT
movtarjetaszeus.pan_enmascarado,
movtarjetaszeus.tipo_documento_id,
movtarjetaszeus.numero_documento,
movtarjetaszeus.codigo_tarjeta_zeus
FROM
modtarhab.view_movtarj movtarjetaszeus
WHERE
movtarjetaszeus.codigo_tarjeta_zeus =$codigo and  rownum <= 1";
$querydatosP=$this->db->query($sqldata);
$rowsarray=$querydatosP->row_array();

            $data['pan_enmascarado'] = $rowsarray['PAN_ENMASCARADO'];
            $pk_estado = $rowsarray['PAN_ENMASCARADO'];
            //numero de c¿documento
            
            $documento = $rowsarray['NUMERO_DOCUMENTO'];
       
            $data['documento'] = $documento;

            //convierte el tipo de documento para poder consultar en Apolo
            $tdocumento = $rowsarray['TIPO_DOCUMENTO_ID'];

         }   

           

      

               
                //echo  $movimientos->result_array[0]['TIPO_DOCUMENTO_ID'];

                //die();

            switch ($tdocumento) {
                case 0:
                    $tdocumento = '68';
                    break;
                case 2:
                    $tdocumento = '67';
                    break;
                case 1:
                    $tdocumento = '69';
                    break;
                case 3:
                    $tdocumento = '70';
                    break;
                case 7:
                    $tdocumento = '74';
                    break;


     

 
            }
  $data['TIPO_DOCUMENTO_ID']= $tdocumento;
        

           //echo $tdocumento;
           //die();
            if($tdocumento!= 6){
                $tcodigo=49;
            $sqLdatoscliente="select 
                                        PK_ENT_CODIGO
                                        from modcliuni.clitblentida 
                                        where DOCUMENTO = '$documento'
                                        and CLITBLTIPDOC_PK_TD_CODIGO = $tdocumento";

//echo $sqLdatoscliente;

 //die();
            $entidad = $this->db->query($sqLdatoscliente);
            $entidad = $entidad->result_array[0]['PK_ENT_CODIGO'];
        //var_dump($entidad); //---27940
            $sqldatosusser="select 
                                        PRIMER_NOMBRE||' '||SEGUNDO_NOMBRE||' '||PRIMER_APELLIDO||' '||SEGUNDO_APELLIDO As NOMBRE
                                        from modcliuni.clitbldatnat
                                        where CLITBLENTIDA_PK_ENT_CODIGO = $entidad order by PK_DATNAT_CODIGO desc" ;
            
            //echo $sqldatosusser;
            //die();
             
            $nombre = $this->db->query($sqldatosusser);
            $nombre = $nombre->result_array[0]['NOMBRE'];
            $data['nombre'] = $nombre;

  }
   if($tdocumento== 6){
   $tcodigo=48;
   $sqlempresa="select PK_TARTBLCUENTA_CODIGO from  modtarhab.TARTBLTARJET  where ID_EMPRESA='$documento'";
   $queryempresa=$this->db->query($sqlempresa)->row_array();
   $PK_TARTBLCUENTA_CODIGO= $queryempresa['PK_TARTBLCUENTA_CODIGO'];
   $sqltartblcuenta="select PK_ENT_CODIGO_TH from  modtarhab.TARTBLCUENTA where PK_TARTBLCUENTA_CODIGO='$PK_TARTBLCUENTA_CODIGO'";
   $queryempresacuenta=$this->db->query($sqltartblcuenta)->row_array(); 
 //print_r($queryempresacuenta);
   //die();
   $PK_ENT_CODIGO_TH=$queryempresacuenta['PK_ENT_CODIGO_TH'];

   $sqlentidad="select PK_ENT_CODIGO,NOMBRE  from MODCLIUNI.CLITBLENTIDA where PK_ENT_CODIGO='$PK_ENT_CODIGO_TH'";
    //echo $sqlentidad;
    //die();
   $queryentidad=$this->db->query($sqlentidad)->row_array();
    
   $entidad= $queryentidad['PK_ENT_CODIGO'];
   $data['nombre'] =$queryentidad['NOMBRE'];   
   }


            $direccion = $this->db->query("select
                                            max(DATO) As DATO
                                            from modcliuni.clitblcontac
                                            where CLITBLTIPCON_PK_TIPCON_CODIGO = $tcodigo
                                            and CLITBLENTIDA_PK_ENT_CODIGO = $entidad
                                            order by FECHA_CREACION asc");



            $direccion = $direccion->result_array[0];
            $direccion = $direccion['DATO'];
            $data['direccion'] = $direccion;
            //var_dump('asa',$direccion);
             
            $sqlpk_ciudad = $this->db->query("select
                                            max(CLITBLCIUDAD_PK_CIU_CODIGO) As CLITBLCIUDAD_PK_CIU_CODIGO
                                            from modcliuni.clitblcontac
                                            where CLITBLTIPCON_PK_TIPCON_CODIGO =$tcodigo
                                            and CLITBLENTIDA_PK_ENT_CODIGO = $entidad");
            
            //var_dump($sqlpk_ciudad);
            //die();
            $pk_ciudad = $sqlpk_ciudad->result_array[0];
            $pk_ciudad = $pk_ciudad['CLITBLCIUDAD_PK_CIU_CODIGO'];
            //die();
            $sqlciudad = $this->db->query("select
                                        NOMBRE
                                        FROM MODCLIUNI.CLITBLCIUDAD where PK_CIU_CODIGO = $pk_ciudad");
            $ciudad = $sqlciudad->result_array[0];
            $ciudad = $ciudad['NOMBRE'];
         
            $data['ciudad'] = $ciudad;

            $sqlcorreo = $this->db->query("select
                                            max(DATO) As DATO
                                            from modcliuni.clitblcontac
                                            where CLITBLTIPCON_PK_TIPCON_CODIGO = 45
                                            and CLITBLENTIDA_PK_ENT_CODIGO = $entidad");
            $correo = $sqlcorreo->result_array[0];
            //var_dump($correo['DATO']);
            //die();
            $correo = $correo['DATO'];
            if(!empty($correo)){
            $data['correo'] = $correo;     
            }else{
            $data['correo'] = $_POST['CORREO'];
               }
            
           if ($tdocumento!=6){

            $querysaldo="SELECT
                                    tarjeta.pk_tarjet_codigo   codigo_tarjeta,
                                    tarjetas_zeus.empresa,
                                    tarjetas_zeus.fk_tipo_documento_id tipo_documento,
                                    tarjetas_zeus.id_tarjeta_zeus codigo_Tarjeta_Zeus,
                                    tarjetas_zeus.numero_documento,
                                    tarjetas_zeus.pan_enmascarado,
                                    tarjetas_zeus.saldo,
                                    cuenta.pk_produc_codigo    codigo_producto,
                                    producto.nombre_producto producto,
                                    tarjetas_zeus.fk_estado_id estado_tarjeta,
                                    CASE
                                        WHEN fk_estado_id = 0
                                             AND motivo_bloqueo != 'BLOQUEO PREVENTIVO' THEN
                                            'BLOQUEADA'
                                        WHEN fk_estado_id = 1   THEN
                                            'ACTIVA'
                                        WHEN fk_estado_id = 4   THEN
                                            'PENDIENTE ACTIVACION'
                                        WHEN fk_estado_id = 0
                                             AND motivo_bloqueo = 'BLOQUEO PREVENTIVO' THEN
                                            'APAGADA'
                                        ELSE
                                            'DESCONOCIDO'
                                    END ESTADO,
                                    tarjetas_zeus.motivo_bloqueo
                                    ,producto.ecomerce
                                    ,tarjetas_zeus.fecha_inicio
                                    ,tarjetas_zeus.fecha_final
                                    ,tarjetas_zeus.ecommerce_saldo
                                    ,producto.monto_maximo_ecommerce
                                    ,producto.cantidad_maximo_ecommerce
                                    ,producto.DEFAULT_CANTIDAD_ECOMMERCE
                                    ,producto.DEFAULT_MONTO_ECOMMERCE
                                    ,tarjetas_zeus.ESTADO_ECOMMERCE
                                    ,nvl(tarjetas_zeus.MONTO_MAXIMO_DIARIO,0) MONTO_MAXIMO_DIARIO
                                    ,nvl(tarjetas_zeus.CANTIDAD_MAXIMO_DIARIO,0) CANTIDAD_MAXIMO_DIARIO
                                    ,tarjetas_zeus.fecha_vencimiento FECHA_VENCIMIENTO
                                FROM
                                    modcliuni.clitblentida   entida
                                    JOIN modtarhab.tartblcuenta   cuenta ON cuenta.pk_ent_codigo_th = entida.pk_ent_codigo
                                    JOIN modcliuni.clitbltipdoc   tipdoc ON entida.clitbltipdoc_pk_td_codigo = tipdoc.pk_td_codigo
                                    JOIN modtarhab.tartbltarjet   tarjeta ON cuenta.pk_tartblcuenta_codigo = tarjeta.pk_tartblcuenta_codigo
                                                                           AND tarjeta.pk_esttar_codigo NOT IN (15,16,17,6,7,8,18,19)
                                    JOIN modtarhab.view_listath   tarjetas_zeus ON tarjetas_zeus.fk_tipo_documento_id = 0
                                                                                 AND tarjetas_zeus.numero_documento = tarjeta.id_empresa
                                                                                 AND tarjetas_zeus.pan_enmascarado = tarjeta.numero
                                                                                 and tarjetas_zeus.id_tarjeta_zeus = $codigo
                                    JOIN modproduc.protblproduc producto ON cuenta.pk_produc_codigo=producto.pk_produc_codigo
                                    
                                WHERE
            



                                    tarjeta.id_empresa = $documento";
}

if($tdocumento==6){
$querysaldo="select
to_char(tar.fecha_creacion,'dd/mm/yyyy HH:MM:SS') as FechaSolicitud,
PED.DOCUMENTO_EMPRESA as NIT,
PED.NOMBRE_EMPRESA as NombreCliente,
PED.NOMBRE_CAMPANIA as Campaña,
tipdocth.nombre as TipoDocumento,
entth.documento NumeroDocumento,
nvl(entth.razon_social,entth.nombre ||' '||enttH.apellido) as TarjetaHabiente,
tar.identificador as Identificador,
tar.id_empresa as IdBanco,
pro.nombre_producto as Producto,
tar.numero as NumeroTarjeta,
PED.NOMBRE_ESTTAR as ESTADO,
TH.saldo as saldo

from MODALISTA.ALIVIEPEDIDO PED
JOIN MODTARHAB.TARTBLTARJET TAR ON tar.pk_detped_codigo = PED.PK_DETPED_CODIGO
JOIN MODTARHAB.tartblesttar ESTADOTAR ON tar.pk_esttar_codigo=estadotar.pk_esttar_codigo

JOIN MODPRODUC.PROTBLPRODUC PRO ON PRO.PK_PRODUC_CODIGO = PED.PK_PRODUCTO

JOIN MODCLIUNI.CLITBLENTIDA ENTCUS ON entcus.pk_ent_codigo = ped.PK_CUSTOD_CODIGO
JOIN MODCLIUNI.clitblentida ENTTH ON entth.pk_ent_codigo = PED.pk_tar_habiente
JOIN MODCLIUNI.CLITBLTIPDOC TIPDOCTH ON TIPDOCTH.pk_td_codigo = entth.clitbltipdoc_pk_td_codigo
LEFT JOIN MODALISTA.ALITBLDETENV DETENV ON detenv.pk_pedido = ped.pk_pedido_codigo
LEFT JOIN MODALISTA.alitblenvio ENV ON env.pk_envio_codigo = detenv.pk_envio
LEFT JOIN MODALISTA.alitblestenv ESTENV ON estenv.pk_estenv_codigo = env.pk_estado
left join  MODTARHAB.VIEW_LISTATH th  on  tar.id_empresa = th.NUMERO_DOCUMENTO
where tar.id_empresa='$documento'
 ";

}
//echo $querysaldo;
//die();
            $sqlsaldo = $this->db->query($querysaldo);
            
            

            $saldo = $sqlsaldo->result_array[0];
            $data['saldo'] = $saldo;
            $documento2=$_POST['DOCUMENTO2'];
            //$contenido = $this->load->view('wsonline2/extracto/pdfExtracto', $data, TRUE);
            $contenido = $this->load->view('wsonline2/extracto/pdfExtracto2',$data,TRUE);
            $dir = 'uploads/extracto/';
            $date = date('y-m-d');
            $random = rand(1000, 9999);
            $name = strtolower($movimientos['NUMERO_DOCUMENTO'] . '-' . $date . '-' . $random . '.pdf');
            $file_dir = $dir . $name;
            $url = $urlpublica['VALOR_PARAMETRO'] . '/' . $dir . $name;
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
                'default_font' => 'Poppins'
            ]);

            $html = mb_convert_encoding($contenido, 'UTF-8', 'UTF-8');
            $mpdf->SetProtection(array(), $documento2);
            $mpdf->defaultfooterline = false;
            $mpdf->setFooter('<div align="center">{PAGENO} </div>');
           
            $mpdf->WriteHTML($html);
            $mpdf->Output($nombre, 'F');
          
            //$mpdf->SetProtection(array($documento, '12345'), '', md5(time()), 128);
            //$this->output->set_content_type('text/css');
            //$this->output->set_output($url);
            //return $url;
        } catch (Exception $exc) {
            return 'No se puede generar el archivo';
        }
        //contenido del correo
        //$remitente = 'sebastian.ocampo@novabase.com.co';
        $destcorreo = $_POST['CORREO'];
        //$destusuario = 'Sebastian';
        $asunto = 'Extracto'; 
        //$mensaje = $this->load->view('wsonline2/extracto/pdfExtracto', $data, TRUE);

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
        $remitente = $sqlusername;
        $destusuario = $username;
        //use PHPMailer;
        //use Exception;
        require 'correo/Exception.php';
        require 'correo/PHPMailer.php';
        require 'correo/SMTP.php';

        $mail = new PHPMailer\PHPMailer\PHPMailer();                              // Passing `true` enables exceptions
        try {
            //Server settings
            //  $mail->SMTPDebug = SMTP::DEBUG_LOWLEVEL ( 4 );
            //$mail->SMTPDebug = 4; //Alternative to above constant
            //$mail->SMTPDebug = 2;                                 // Enable verbose debug output
            $mail->isSMTP();                                      // Set mailer to use SMTP
            $mail->Host = $host;  // Specify main and backup SMTP servers
            $mail->SMTPAuth = true;                               // Enable SMTP authentication
            $mail->Username = $username;                 // SMTP username
            $mail->Password = $password;                           // SMTP password
            $mail->SMTPSecure = $smtpsecure;                            // Enable TLS encryption, `ssl` also accepted
            $mail->Port = $port;                                    // TCP port to connect to
            //Recipients
            $mail->setFrom($username, $remitente);
            //$mail->setFrom('Soporte.cliente@peoplepass.com.co', $remitente);
            //echo 'destinatario'+$destinatari;
            $mail->addAddress($destcorreo, $destusuario);     // Add a recipient
            //($mail->addAddress($comercial, $comercialusuario);
            //$mail->AddCC($comercial,$comercialusuario);
            //$mail->addAddress('');               // Name is optional
            //$mail->addReplyTo($respcorreo, $respusuario);
            $mail->addCustomHeader('MIME-Version: 1.0');
            $mail->addCustomHeader('Content-Type: text/html; charset=ISO-8859-1');
            //$mail->addCC($destinatario);
            //$mail->addBCC($destinatario);
            //Attachments
            //$mail->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
            $mail->addAttachment($nombre, 'extracto.pdf');    // Optional name
            //Content
            $mail->isHTML(true);                                  // Set email format to HTML
            $mail->Subject = $asunto;
            $mail->Body = 'Cordial saludo, la contrase&ntilde;a del documento adjunto es su numero de documento.';

            //$mail->AltBody = 'This is the body in plain text for non-HTML mail clients';
            // echo 'FINNN';
            $envio = $mail->send();
            //echo ($envio);
            //var_dump($envio);
            //$variable =  json_encode($mail);
            //echo $SMTPDebug;
            //var_dump($mail);
            //echo $e->errorMessage();
            if ($envio) {
                echo 'El mensaje ha sido enviado';
                // var_dump($mail);
            } else {
                echo 'El mensaje no ha sido enviado';
                echo 'Message could not be sent. Mailer Error: ', $mail->ErrorInfo;
            }
        } catch (Exception $e) {
            echo 'Message could not be sent. Mailer Error: ', $mail->ErrorInfo;
        }
    }

}

                