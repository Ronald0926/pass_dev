<?php

ini_set("pcre.backtrack_limit", "5000000");
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Sobreflex extends CI_Controller {
    public $iniciLog='[INFO] ';
    public $logHeader = 'APOLOINFO::::::::: ';
    public $postData = 'POSTDATA::::::::: ';
    public $queryData = 'QUERYDATA::::::: ';
    public $finFuncion=' FIN PROCEDIMIENTO::::::: ';
    public function __construct() {
        parent::__construct();
        $this->load->helper('log4php');
    }


    public function crear() {
        log_info($iniciLog.$logHeader);
           $post = $_POST['LINEAPRODUCTO'];
           log_info($iniciLog.$logHeader. ' SOBREFLEX LINEA DE PRODUCTO '.$post);
        //   ECHO $post;
      //     if ($post) {
        $varesttarina = 2;
        $varestcueina = 2;
        $varestcueini = 1;
        $varestenvnoe = 1;
        $VARSOLTARSOL = 1;
        require_once '/var/www/html/mpdf/vendor/autoload.php';
        $urlpublica = $this->db->query("select VALOR_PARAMETRO from modgeneri.gentblpargen where pk_pargen_codigo =99");
        $urlpublica=$urlpublica->result_array[0];
        
        $sobre = $this->db->query("
        select 
             nvl(entemp.razon_social,entemp.nombre||' '||entemp.apellido) nombre_empresa,
            CASE WHEN  pro.limitacion=0 THEN 
            tar.IDENTIFICADOR||' '|| 
            (case
            --when pro.pk_produc_codigo in (302,308) then 
            when pro.pk_produc_codigo in (324,325) then 
            '$'||TRIM(TO_CHAR((select monto
                        from  MODPROPAG.ppatbldetpab
                        where pk_detpab_codigo=(select max(pk_detpab_codigo)
                        from MODPROPAG.ppatbldetpab 
                        WHERE  pk_cuenta =CUE.PK_TARTBLCUENTA_CODIGO
                        and pk_producto =pro.pk_produc_codigo)),'999,999,999'))
            else 
            ''
            end)
            
            ELSE 
            NVL(UPPER(enttar.razon_social),UPPER(enttar.nombre)||' '||UPPER(enttar.apellido))
            END
             NOMBRE_TH,
            tar.id_empresa TARDOC,
            pro.nombre_producto NOMBRE_PRO,
            tar.identificador INDENTIFICADOR,
            LINPRO.PK_LINPRO_CODIGO LINPRO,
            SUBSTR(tar.numero,-4) NUMERO_TARJETA,
            CASE WHEN tar.TIPO_TARJETA =1 then ''
            when  tar.TIPO_TARJETA =2 THEN ' - Tarjeta R/X'
            end EMISION
            from MODALISTA.alitblenvio env
                join MODALISTA.alitbldetenv detenv 
                    on detenv.pk_envio = env.pk_envio_codigo
                join MODALISTA.alitblpedido ped 
                    on ped.PK_PEDIDO_CODIGO = detenv.pk_pedido  
                    and ped.alitblsoltar_pk_soltar_codigo = $VARSOLTARSOL
                join modalista.alitbldetped detped 
                    on detped.pk_pedido = ped.pk_pedido_codigo
                join MODALISTA.alitbldesdet desdet 
            on desdet.alitbldetped_pk_detped_codigo = detped.pk_detped_codigo 
            and desdet.pk_desdet_codigo = (select max(desdet2.pk_desdet_codigo) 
            from MODALISTA.alitbldesdet desdet2 
            where desdet2.alitbldetped_pk_detped_codigo = desdet.alitbldetped_pk_detped_codigo)
                join modcliuni.clitblentida entemp 
                    on entemp.PK_ENT_CODIGO = ped.pk_empresa
                join modcliuni.clitblentida entcus 
                    on entcus.PK_ENT_CODIGO = ped.pk_custodio
                join modcliuni.clitblentida enttar 
                    on enttar.PK_ENT_CODIGO = detped.pk_tar_habiente
                join modproduc.protblproduc pro 
                    on pro.pk_produc_codigo = detped.pk_producto
                JOIN MODPRODUC.PROTBLLINPRO LINPRO 
                    ON linpro.pk_linpro_codigo = pro.pk_linpro_codigo
                join MODTARHAB.tartbltarjet tar 
                    on tar.pk_detped_codigo = detped.pk_detped_codigo 
                    and tar.pk_esttar_codigo = $varesttarina 
                join MODTARHAB.tartblcuenta cue 
                    on cue.pk_tartblcuenta_codigo = tar.pk_tartblcuenta_codigo
                    and cue.PK_TARTBLESTADO_CODIGO in ($varestcueina,$varestcueini)
                where 
                    env.PK_ESTADO = $varestenvnoe 
                    AND LINPRO.PK_LINPRO_CODIGO = $post
                    ORDER BY nombre_empresa asc,SUBSTR(tar.numero,-4) asc"   );
                 //   ORDER BY linpro.pk_linpro_codigo,nombre_empresa,pro.nombre_producto");
        $data = $sobre->result_array;
       // var_dump($data);
        //exit();
        $dir = 'uploads/sobreflex/';
        $date = date('Y-m-d');
        $random = rand(1000, 9999);
        $name = strtolower($date . '-' . $random . '.pdf');
        $file_dir = $dir . $name;
        //$url = 'http://' . $_SERVER['SERVER_ADDR'] . ':' . $_SERVER['SERVER_PORT'] . '/uploads/' . $name;
        //$url = 'http://' . $_SERVER['SERVER_ADDR'] . ':' . $_SERVER['SERVER_PORT'] . '/'.$dir . $name;
          $url= $urlpublica['VALOR_PARAMETRO'].'/'.$dir.$name; 
        $nombre = $file_dir;
        $mpdf = new \Mpdf\Mpdf([
            'tempDir' => '/var/www/html/mpdf/tmp',
            'mode' => 'utf-8',
            'format' => 'A4-L',
            'default_font_size' => 8,
			'default_font'=>'Calibri'
        ]);
        $contadordata = 0;
        $detalles = null;
        foreach ($data as $key => $value) {
            $temp = null;
            if ($value['LINPRO'] == 2   ) {
                $temp = $value['TARDOC'];
            }
            elseif (($value['LINPRO'] == 3 && $value['LIMITACION']==0)) {
                    $temp = $value['TARDOC'];
            }
             else {
                $temp = ' *** *** ' . substr($value['TARDOC'],-4);
            }
            //var_dump($value['NOMBRE_EMPRESA']);
            //exit();
            $detalles[$contadordata] = $value['NOMBRE_EMPRESA'] . ';'
                    . $value['NOMBRE_TH'] .';'
                    . $temp .' - '.' *** *** ' .$value['NUMERO_TARJETA'] .';'
                    . 'Coomeva'.' - '.$value['NOMBRE_PRO'].$value['EMISION'];
            $contadordata = $contadordata +1;
        }
        $contador = 0;
        foreach ($detalles as $x => $x_value) {

            $dats = $x_value;
            log_info($iniciLog.$logHeader. ' SOBREFLEX IMPRESION '.$dats);
            $content = explode(";", $dats);

            $contador++;
            if ($contador == 1) {
                $data = '
              <table border="0" style="width: 100%; padding-left: 710px; padding-top: 5px;" >';
            } else {
                $data = '
              <table border="0" style="width: 100%; padding-left: 710px; margin-top: 327px;" >';
            }



            foreach ($content as $y => $y_value) {
                if ($y_value != "")
                    log_info($iniciLog.$logHeader. ' SOBREFLEX IMPRESION INICIO'.$y_value);
                    $data = $data . "
            <tr>
                <td>
                    - $y_value
                </td>
            </tr>
                ";
            
                log_info($iniciLog.$logHeader. ' SOBREFLEX IMPRESION FIN'.$y_value);
            }

            $data = $data . '
        </table>
        ';
            if ($contador == 1) {
                $mpdf->AddPage();
            } else {
                $contador = 0;
            }

            $mpdf->WriteHTML(mb_convert_encoding($data, 'UTF-8', 'UTF-8'));

            $data = "";
        }

        $mpdf->Output($nombre, 'F');
        echo $url . '';
/*
            } else {
                echo 'no post';
            }*/
    }
    
     public function crear_x_envio($numero_remesa=null) {
        log_info($iniciLog.$logHeader);
        
           $post = $_POST['LINEAPRODUCTO'];
           log_info($iniciLog.$logHeader. ' SOBREFLEX LINEA DE PRODUCTO '.$post);
        //   ECHO $post;ZZ
      //     if ($post) {
           if($numero_remesa===null){
           $codigo_envio=$_POST['NUMERO_REMESA'];
           }else{
           $codigo_envio=$numero_remesa;    
           }
        require_once '/var/www/html/mpdf/vendor/autoload.php';
        $urlpublica = $this->db->query("select VALOR_PARAMETRO from modgeneri.gentblpargen where pk_pargen_codigo =99");
        $urlpublica=$urlpublica->result_array[0];
        
        $sobre = $this->db->query("
        select 
             nvl(entemp.razon_social,entemp.nombre||' '||entemp.apellido) nombre_empresa,
            CASE WHEN  pro.limitacion=0 THEN 
            tar.IDENTIFICADOR||' '|| 
            (case
            --when pro.pk_produc_codigo in (302,308) then 
            when pro.pk_produc_codigo in (324,325) then 
            '$'||TRIM(TO_CHAR((select monto
                        from  MODPROPAG.ppatbldetpab
                        where pk_detpab_codigo=(select max(pk_detpab_codigo)
                        from MODPROPAG.ppatbldetpab 
                        WHERE  pk_cuenta =CUE.PK_TARTBLCUENTA_CODIGO
                        and pk_producto =pro.pk_produc_codigo)),'999,999,999'))
            else 
            ''
            end)
            
            ELSE 
            NVL(UPPER(enttar.razon_social),UPPER(enttar.nombre)||' '||UPPER(enttar.apellido))
            END
             NOMBRE_TH,
            tar.id_empresa TARDOC,
            pro.nombre_producto NOMBRE_PRO,
            tar.identificador INDENTIFICADOR,
            LINPRO.PK_LINPRO_CODIGO LINPRO,
            SUBSTR(tar.numero,-4) NUMERO_TARJETA,
            CASE WHEN tar.TIPO_TARJETA =1 then ''
            when  tar.TIPO_TARJETA =2 THEN ' - Tarjeta R/X'
            end EMISION
            ,pro.limitacion LIMITACION
            from MODALISTA.alitblenvio env
                join MODALISTA.alitbldetenv detenv 
                    on detenv.pk_envio = env.pk_envio_codigo
                join MODALISTA.alitblpedido ped 
                    on ped.PK_PEDIDO_CODIGO = detenv.pk_pedido  
                join modalista.alitbldetped detped 
                    on detped.pk_pedido = ped.pk_pedido_codigo
                join MODALISTA.alitbldesdet desdet 
            on desdet.alitbldetped_pk_detped_codigo = detped.pk_detped_codigo 
            and desdet.pk_desdet_codigo = (select max(desdet2.pk_desdet_codigo) 
            from MODALISTA.alitbldesdet desdet2 
            where desdet2.alitbldetped_pk_detped_codigo = desdet.alitbldetped_pk_detped_codigo)
                join modcliuni.clitblentida entemp 
                    on entemp.PK_ENT_CODIGO = ped.pk_empresa
                join modcliuni.clitblentida entcus 
                    on entcus.PK_ENT_CODIGO = ped.pk_custodio
                join modcliuni.clitblentida enttar 
                    on enttar.PK_ENT_CODIGO = detped.pk_tar_habiente
                join modproduc.protblproduc pro 
                    on pro.pk_produc_codigo = detped.pk_producto
                JOIN MODPRODUC.PROTBLLINPRO LINPRO 
                    ON linpro.pk_linpro_codigo = pro.pk_linpro_codigo
                join MODTARHAB.tartbltarjet tar 
                    on tar.pk_detped_codigo = detped.pk_detped_codigo 
                   
                join MODTARHAB.tartblcuenta cue 
                    on cue.pk_tartblcuenta_codigo = tar.pk_tartblcuenta_codigo
                where 
                   env.pk_envio_codigo = $codigo_envio 
                    ORDER BY nombre_empresa asc,SUBSTR(tar.numero,-4) asc"   );
                 //   ORDER BY linpro.pk_linpro_codigo,nombre_empresa,pro.nombre_producto");
        $data = $sobre->result_array;
       // var_dump($data);
        //exit();
        $dir = 'uploads/sobreflex/';
        $date = date('Y-m-d');
        $random = rand(1000, 9999);
        $name = strtolower($date . '-' . $random . '.pdf');
        $file_dir = $dir . $name;
        //$url = 'http://' . $_SERVER['SERVER_ADDR'] . ':' . $_SERVER['SERVER_PORT'] . '/uploads/' . $name;
        //$url = 'http://' . $_SERVER['SERVER_ADDR'] . ':' . $_SERVER['SERVER_PORT'] . '/'.$dir . $name;
          $url= $urlpublica['VALOR_PARAMETRO'].'/'.$dir.$name; 
        $nombre = $file_dir;
        $mpdf = new \Mpdf\Mpdf([
            'tempDir' => '/var/www/html/mpdf/tmp',
            'mode' => 'utf-8',
            'format' => 'A4-L',
            'default_font_size' => 8,
			'default_font'=>'Calibri'
        ]);
        $contadordata = 0;
        $detalles = null;
        foreach ($data as $key => $value) {
            $temp = null;
            if ($value['LINPRO'] == 2   ) {
                $temp = $value['TARDOC'];
            }
            elseif (($value['LINPRO'] == 3 && $value['LIMITACION']==0)) {
                    $temp = $value['TARDOC'];
            }
             else {
                $temp = ' *** *** ' . substr($value['TARDOC'],-4);
            }
            //var_dump($value['NOMBRE_EMPRESA']);
            //exit();
            $detalles[$contadordata] = $value['NOMBRE_EMPRESA'] . ';'
                    . $value['NOMBRE_TH'] .';'
                    . $temp .' - '.' *** *** ' .$value['NUMERO_TARJETA'] .';'
                    . 'Coomeva'.' - '.$value['NOMBRE_PRO'].$value['EMISION'];
            $contadordata = $contadordata +1;
        }
        $contador = 0;
        foreach ($detalles as $x => $x_value) {

            $dats = $x_value;
            log_info($iniciLog.$logHeader. ' SOBREFLEX IMPRESION '.$dats);
            $content = explode(";", $dats);

            $contador++;
            if ($contador == 1) {
                $data = '
              <table border="0" style="width: 100%; padding-left: 710px; padding-top: 5px;" >';
            } else {
                $data = '
              <table border="0" style="width: 100%; padding-left: 710px; margin-top: 327px;" >';
            }



            foreach ($content as $y => $y_value) {
                if ($y_value != "")
                    log_info($iniciLog.$logHeader. ' SOBREFLEX IMPRESION INICIO'.$y_value);
                    $data = $data . "
            <tr>
                <td>
                    - $y_value
                </td>
            </tr>
                ";
            
                log_info($iniciLog.$logHeader. ' SOBREFLEX IMPRESION FIN'.$y_value);
            }

            $data = $data . '
        </table>
        ';
            if ($contador == 1) {
                $mpdf->AddPage();
            } else {
                $contador = 0;
            }

            $mpdf->WriteHTML(mb_convert_encoding($data, 'UTF-8', 'UTF-8'));

            $data = "";
        }

        $mpdf->Output($nombre, 'F');
        echo $url . '';
/*
            } else {
                echo 'no post';
            }*/
    }
}
