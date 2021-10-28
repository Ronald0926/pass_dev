<?php
session_start();
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Principal extends CI_Controller {

    public function __construct() {
        parent::__construct();

         try{
        $this->load->helper('log4php');
         } catch (Exception $ex){
             
         }
         // if ($this->session->userdata('entidad') == NULL) {
          if ($_SESSION['entidad'] == NULL) {
            redirect('/');
        }
    }

    public function __destruct() {
        $this->db->close();
    }

    public function pantalla($pantalla = 0) {

        //$empresa = $this->session->userdata("entidad");
        $empresa = $_SESSION['entidad'];
        $data['empresa'] = $empresa['NOMBREEMPRESA'];
        //$usuario = $this->session->userdata("usuario");
        $usuario = $_SESSION['usuario'];
        $data['usuario'] = $usuario['NOMBRE'] . " " . $usuario['APELLIDO'];
        //$ultimaconexion = $this->session->userdata("ultimaconexion");
        $ultimaconexion = $_SESSION['ultimaconexion'];
        $data['ultimaconexion'] = $ultimaconexion;
        //$rol = $this->session->userdata("rol");
        $rol = $_SESSION['rol'];
        $campana = $_SESSION['campana'];
        //var_dump($rol);
        //var_dump($campana);
        //var_dump($empresa);
        if ($rol == 58 || $rol == 56) {
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

        $bonoTransportador = $this->db->query("SELECT pa.PK_PRODUCTO_CODIGO,pa.PARAMETRO
        FROM MODCOMERC.COMTBLCOTIZA co
        INNER JOIN MODCOMERC.COMTBLPROCES pr ON pr.PK_COTIZA_CODIGO = co.pk_cotiza_codigo
        INNER JOIN MODCOMERC.COMTBLPARAME pa ON pa.pk_proces_codigo = pr.pk_proces_codigo AND pa.pk_tippar_codigo IN (1,3)
        WHERE pr.pk_estado_codigo = 1
        AND  co.pk_estado_codigo = 1
        AND  co.PK_ENTIDA_CLIENTE = {$empresa['PK_ENT_CODIGO']}
        AND pa.PK_PRODUCTO_CODIGO=69");
       
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
       $urltotal="";
       
       if(!empty($numerocotizacion)){
       
        $SqlCotiza = $this->db->query("SELECT MODCLIUNI.PKG_FUC.FNENCRYPTPHP('$numerocotizacion') NUM_COT from dual");
         $pk_cotiza = $SqlCotiza->result_array[0]['NUM_COT'];
         $urltotal=$ipserver.'?CODE='.$pk_cotiza;
      
       }
      
       
       //log_info($this->logHeader . 'COTIZACION  ' . urldecode($pk_cotiza));
       
       //echo $nmcotizacion;
      
       //$urltotal=$ipserver.$numerocotizacion;
    
    /*$dataentidades=array('razonsocial'=> $dataentidades->RAZON_SOCIAL,'email'=>$dataentidades->CORREO_ELECTRONICO);
    --echo json_encode($dataentidades);*/

        $data['bonoTrans'] = $bonoTransportador->result_array[0];
        $data['llaveMaestra'] = 1;
        $data['empresa'] = $empresa['PK_ENT_CODIGO'];
        $data['empresa'] = $empresa['NOMBREEMPRESA'];
        $data['urltotal']=$urltotal; 
        //$data['razonsocial']= $datosentidad->RAZON_SOCIAL;
        //$data['correo']=$datosentidad->CORREO_ELECTRONICO;


//        var_dump($pedidos);
//        die();
        $this->load->view('portal/templates/header', $data);
        $this->load->view('portal/principal/pantalla', $data);
        $this->load->view('portal/templates/footer', $data);
    }

    public function validacionModal() {
        $empresa = $_SESSION['entidad'];
        $campana = $_SESSION['campana'];

        $pedidos = $this->db->query("select PK_PEDIDO_CODIGO NUMEROPEDIDO,
        cantidad.cantidad CANTIDADTARJETAS,
        nvl(cantactivas.cantactivas,0) CANTTARACTIVAS,
        pedidos.fecha_creacion FECHASOLICITUD,
        envios.fechaentregado FECHARECIBIDO,
        nvl(envios.nomestado,estped.nombre) ESTADOPEDIDO
        ,envios.estado CODESTADOENVIO
        ,envios.pk_envio_codigo CODIGOENVIO
        from modalista.alitblpedido PEDIDOS
        join modalista.alitblestped estped
        ON pedidos.ALITBLESTPED_PK_ESTPED_CODIGO=estped.pk_estped_codigo
        join (select pedid.pk_pedido_codigo pedido,count(1) cantidad
            from MODALISTA.alitblpedido pedid
            join MODALISTA.alitbldetped detped
            on detped.pk_pedido=pedid.pk_pedido_codigo
            group by pedid.pk_pedido_codigo) cantidad
        ON cantidad.pedido=pedidos.pk_pedido_codigo
         left join (select pedido.pk_pedido_codigo pedactiva, count(1) cantactivas from MODTARHAB.TARTBLTARJET tarjeta
             JOIN MODALISTA.ALITBLDETPED detped 
              ON tarjeta.pk_detped_codigo=detped.pk_detped_codigo
            JOIN MODALISTA.ALITBLPEDIDO pedido 
              ON detped.pk_pedido= pedido.pk_pedido_codigo
                where PK_PEDIDO_CODIGO=PK_PEDIDO_CODIGO
                and tarjeta.pk_esttar_codigo=1
         group by pedido.pk_pedido_codigo) cantactivas
        ON cantactivas.pedactiva =pedidos.pk_pedido_codigo
        left join (
            SELECT PK_PEDIDO PEDIDO, PK_ESTADO ESTADO,
                estenv.nombre nomestado,
                env.fecha_actualizacion actualizacion,
                env.fecha_entregado fechaentregado,
                env.pk_envio_codigo pk_envio_codigo
            FROM modalista.alitbldetenv detenv
            JOIN modalista.alitblenvio env
            ON  detenv.pk_envio=env.pk_envio_codigo
            JOIN modalista.alitblestenv estenv
            ON env.pk_estado=estenv.pk_estenv_codigo ) envios
        ON pedidos.pk_pedido_codigo=envios.pedido
                WHERE PK_EMPRESA={$empresa ['PK_ENT_CODIGO']}
                 and pedidos.pk_campan_codigo={$campana}
                 and envios.estado = 3
                 order by PK_PEDIDO_CODIGO desc");
        $data = $pedidos->result_array;
        $valida = array();
        foreach($data as $pedidos){
            //$pedidos['CANTIDADTARJETAS'];
            //$pedidos['CANTTARACTIVAS']; 
            
            if($pedidos['CANTIDADTARJETAS']!=$pedidos['CANTTARACTIVAS']){
                $valida[] = $pedidos['CANTIDADTARJETAS'];
                
            }
        }
        echo json_encode($valida);
    }

    public function pantalla2($pantalla = 0) {

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
        $this->load->view('portal/principal/pantalla', $data);
        $this->load->view('portal/templates/footer', $data);
    }

    public function grid($pantalla = 0) {
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
        $this->load->view('portal/principal/grid', $data);
        $this->load->view('portal/templates/footer', $data);
    }


public  function validamodalcorreos(){

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
    //echo $pk_entidad;
    //echo $cpana;

 $sqlmodal=" select ent.razon_social, ent.CORREO_ELECTRONICO,vincul.FECHA_INICIO,vincul.FECHA_FIN,vincul.FECHA_ACTUALIZACION
 from  MODCLIUNI.CLITBLENTIDA ent  inner join MODCLIUNI.CLITBLVINCUL vincul 
 on  ent.PK_ENT_CODIGO= vincul.CLITBLENTIDA_PK_ENT_CODIGO1 
 inner JOIN MODCLIUNI.CLITBLTIPVIN ON vincul.CLITBLTIPVIN_PK_TIPVIN_CODIGO=PK_TIPVIN_CODIGO  
 where  PK_TIPVIN_CODIGO=47 and ent.PK_ENT_CODIGO=$pk_entidad and vincul.FECHA_ACTUALIZACION is null and ((trunc(sysdate) - trunc (vincul.FECHA_INICIO)) / 30)>=12
 ";

 
   
 
    $datosentidad=$this->db->query($sqlmodal)->row_array();
    //$datosvalida[]=array('RAZON_SOCIAL'=>$datosentidad['RAZON_SOCIAL'],'CORREO_ELECTRONICO'=>$datosentidad['CORREO_ELECTRONICO'] );
     $datosvalida[]=$datosentidad;
    echo json_encode($datosvalida);
   

  

}




}
