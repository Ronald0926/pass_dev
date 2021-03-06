<?php

ini_set("pcre.backtrack_limit", "5000000");
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Remesa extends CI_Controller {

    public $iniciLog = '[INFO] ';
    public $logHeader = 'APOLOINFO::::::::: ';
    public $postData = 'POSTDATA::::::::: ';
    public $queryData = 'QUERYDATA::::::: ';
    public $finFuncion = ' FIN PROCEDIMIENTO::::::: ';

    public function __construct() {
        parent::__construct();
        $this->load->helper('log4php');
    }

    public function __destruct() {
        $this->db->close();
    }

    public function crear() {
        $varesttarina = 2;
        $VARSOLTARSOL = 1;
        $varestenvnoe = 1;
        $varestcueina = 2;
        $VARESTTIPCONTEL = 46;
        $VARESTTIPCONDIR = 48;

        function unique_multidim_array($array, $key) {
            $temp_array = array();
            $i = 0;
            $key_array = array();

            foreach ($array as $val) {
                if (!in_array($val[$key], $key_array)) {
                    $key_array[$i] = $val[$key];
                    $temp_array[$i] = $val;
                }
                $i++;
            }
            return $temp_array;
        }

        require_once '/var/www/html/mpdf/vendor/autoload.php';

        $urlpublica = $this->db->query("select VALOR_PARAMETRO from modgeneri.gentblpargen where pk_pargen_codigo =99");
        $urlpublica = $urlpublica->result_array[0];

        // require_once 'C:\xampp\htdocs\mpdf\vendor\autoload.php';
        $remesa = $this->db->query("
           select env.pk_envio_codigo PK_ENVIO,
            ped.pk_empresa PK_EMPRESA,
            entemp.DOCUMENTO NIT_EMPRESA,
            nvl(entemp.razon_social,entemp.nombre||' '||entemp.apellido) NOMBRE_EMPRESA,
            ped.pk_custodio CUSTODIO,
            entcus.CORREO_ELECTRONICO CORREO_CUSTODIO,
            modcliuni.clipkgconsultas.fncdatocontacto(NVL(modcliuni.clipkgconsultas.fncmaxpkcontacto(ped.pk_custodio, 46),modcliuni.clipkgconsultas.fncmaxpkcontacto(ped.pk_custodio, 47))) TELEFONO_CUSTODIO,
            modcliuni.clipkgconsultas.fncdatocontacto(NVL(modcliuni.clipkgconsultas.fncmaxpkcontacto(ped.pk_custodio, 48),modcliuni.clipkgconsultas.fncmaxpkcontacto(ped.pk_custodio, 49))) DIRECCIO_CUSTODIO, 
            CIU.NOMBRE CIUDAD_CUSTODIO,
            UPPER(enttar.nombre) NOMBRE_TH,
            CASE WHEN  pro.limitacion=0 THEN 
            tar.IDENTIFICADOR||' '|| 
            (case
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
            NVL(UPPER(enttar.razon_social),UPPER(enttar.apellido))
            END
            APELLIDO_TH,
            --UPPER(enttar.apellido) APELLIDO_TH,
            (CASE WHEN  LINPRO.PK_LINPRO_CODIGO= 2  OR (LINPRO.PK_LINPRO_CODIGO =3 AND pro.limitacion=0) 
            THEN ' '||tar.id_empresa||' ' 
            ELSE '*** *** '||SUBSTR(tar.id_empresa,-4) END) TAR_CEDULA,
            UPPER(entcus.nombre)||' '||UPPER(entcus.apellido) NOMBRE_CUSTODIO,
            detped.PK_PRODUCTO PKEYPRODUCTO,
            pro.nombre_producto NOMBRE_PRODUCTO,
	    LINPRO.PK_LINPRO_CODIGO LINPRO,
            (CASE LINPRO.PK_LINPRO_CODIGO 
            WHEN 2 THEN ' '||SUBSTR(tar.numero,-4)||' '
            ELSE  SUBSTR(tar.numero,-4) END)NUMERO_TARJETA
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
        JOIN MODCLIUNI.CLITBLCIUDAD CIU 
            ON ciu.pk_ciu_codigo = entcus.clitblciudad_pk_ciu_codigo
        join MODTARHAB.tartbltarjet tar 
            on tar.pk_detped_codigo = detped.pk_detped_codigo 
        join MODTARHAB.tartblcuenta cue 
            on cue.pk_tartblcuenta_codigo = tar.pk_tartblcuenta_codigo
        where env.PK_ESTADO = $varestenvnoe
         ORDER BY env.pk_envio_codigo,nombre_empresa asc,SUBSTR(tar.numero,-4) asc");
        $data = $remesa->result_array;

        $encabezados = array();
        $recorrido = 0;

        foreach ($data as $key => $value) {

            $encabezados[$recorrido] = array(
                "codigo" => $value['PK_ENVIO'],
                "NOM_EMPRESA" => $value['NOMBRE_EMPRESA'],
                "nombre" => $value['NOMBRE_CUSTODIO'],
                "correo" => $value['CORREO_CUSTODIO'],
                "telefono" => $value['TELEFONO_CUSTODIO'],
                "direccion" => $value['DIRECCIO_CUSTODIO'],
                "ciudad" => $value['CIUDAD_CUSTODIO']
            );
            log_info($this->iniciLog . $this->logHeader . $this->encabezados);
            $temp = null;
            if ($value['LINPRO'] != 2) {
                $temp = ' *** ' . $value['NUMERO_TARJETA'];
            } else {
                $temp = ' *** ' . $value['NUMERO_TARJETA'];
            }
            $detalles[$recorrido] = array(
                "codigo" => $value['PK_ENVIO'],
                "NIT_EMPRESA" => $value['NIT_EMPRESA'],
                "NOMBRE_TH" => $value['NOMBRE_TH'],
                "APELLIDO_TH" => $value['APELLIDO_TH'],
                "DOCUMENTO" => $value['TAR_CEDULA'],
                "TARJETA" => $temp,
                "PRODUCTO" => $value['NOMBRE_PRODUCTO']
            );
            log_info($this->iniciLog . $this->logHeader . $detalles);
            $recorrido++;
            log_info($this->iniciLog . $this->logHeader . ' FIN REMESA ');
        }
        $encabezados = unique_multidim_array($encabezados, 'codigo');
        sort($encabezados);
        $dir = 'uploads/remesa/';
        // $dir = 'C:\xampp\htdocs\uploads';
        $date = date('Y-m-d');
        $random = rand(1000, 9999);
        $name = strtolower($date . '-' . $random . '.pdf');
        $file_dir = $dir . $name; //.basename($_FILES['file']['name']);
        //$url = 'http://' . $_SERVER['SERVER_ADDR'] . ':' . $_SERVER['SERVER_PORT'] . '/uploads/' . $name;
        //$url = 'http://' . $_SERVER['SERVER_ADDR'] . ':' . $_SERVER['SERVER_PORT'] . '/' .$dir. $name;
        $url = $urlpublica['VALOR_PARAMETRO'] . '/' . $dir . $name;
        $nombre = $file_dir;

        $mpdf = new \Mpdf\Mpdf([
            'tempDir' => '/var/www/html/mpdf/tmp',
            //    'tempDir' => 'C:\xampp\htdocs\mpdf\tmp',
            'mode' => 'utf-8',
            'format' => 'A4',
            'margin_header' => 10,
            'margin_top' => 55,
            'margin_bottom' => 40,
            'default_font' => 'calibri'
        ]);

        foreach ($encabezados as $remesa) {

            $contenido = "";
            $clasenombr = "";
                $caracteresnombre = strlen($remesa['NOM_EMPRESA']);
                if($caracteresnombre>60){
                    $clasenombr="style='font-size: 12px;'";
                }
            $contenido = $contenido . "<table style='width:100%; font-size: 14px;'>
        <tr>
            <td style='width: 100%; vertical-align: top; height: 200px'>
			<img src='/var/www/html/static/img/remesawsonline/header.png' width='100%' />
                <table style='width: 100%;'>
                    <tr>
                        <td style='width: 75%;'>
                            <strong >Se&ntilde;ores:</strong> 
                        </td>
                        <td> <strong> RMPP-{$remesa['codigo']} </strong> </td>
                    </tr>
                    <tr>
                        <td style='width: 75%;'>
                        <strong $clasenombr>{$remesa['NOM_EMPRESA']}</strong>
                        </td>
                        <td> <strong> " . "Fecha: " . date("d/m/Y") . " </strong> </td>
                    </tr>
                    <tr>
                        <td style='width: 75%;'>
                        {$remesa['nombre']}
                        </td>
                        <td> <strong> " . "Hora: " . date("H:i:s") . " </strong> </td>
                    </tr>
                    <tr>
                        <td>
                          {$remesa['direccion']}
                        </td>
                    </tr>
                    <tr>
                        <td>
                           {$remesa['telefono']}
                         </td>
                    </tr>
                    <tr>
                        <td>
                           {$remesa['ciudad']}
                         </td>
                    </tr>
                    <tr>
                         <td>
                            <strong>Respetados Se&ntilde;ores:</strong>
                          </td>
                    </tr>
                    <tr>
                    <td>
                    Estamos haciendo entrega de las siguientes tarjetas segun su amable solicitud
                    </tr>
                </table>
                ";
            $contenido = $contenido . "</td>
            
        </tr>

    </table>";

            $contenido2 = '
<br/>
<br/>
<br/>
<br/>
<br/>
<br/>
<br/>
<br/>
<br/>
<style>
.tabla1,#tdheader{
  border-collapse: collapse;
  border: 1px solid black;
  font-size:10px;
  background-color:#FFFFFF;
}
#tdfilas{
border-right: 1px solid black;
}
.tabla1 tr:nth-child(odd) {
	background-color:#BEBEBE;
}
.tabla1 tr:nth-child(even) {
    background-color:#FFFFFF; 
}
</style>

<table style="font-size: 10px;" class="tabla1" width="100%" cellspacing="0">';
            $contenido2cab = '
        <tr>
            <td id="tdheader">
                <strong>Reg</strong>
            </td>
            <td id="tdheader">
                <strong>Nit</strong>
            </td>
            <td id="tdheader">
                <strong>Nombres</strong>
            </td>
            <td id="tdheader">
                <strong>Apellidos</strong>
            </td>
            <td id="tdheader">
                <strong>Identificacion</strong>
            </td>
            <td id="tdheader">
                <strong>Tarjeta</strong>
            </td>
            <td id="tdheader">
                <strong>Banco</strong>
            </td>
            <td id="tdheader"> 
                <strong>Producto</strong>
            </td>
        </tr> 
    ';
            $contenido2 = $contenido2 . $contenido2cab;
            $y = 0;
            $temp = 53;
            $numcol = 39;
            foreach ($detalles as $x => $x_value) {

                if ($x_value["APELLIDO_TH"] == $x_value["NOMBRE_TH"]) {
                    $x_value["APELLIDO_TH"] = "";
                }
                
                $clasenombr = "";
                $caracteresnombre = strlen($x_value["NOMBRE_TH"]);
                $caracteresapellido = strlen($x_value["APELLIDO_TH"]);
                $totalcaracteres = $caracteresnombre+$caracteresapellido;
                if($totalcaracteres>45){
                    $clasenombr="style='font-size: 8px;'";
                }
                if($caracteresnombre>37){
                    $x_value["NOMBRE_TH"]= substr($x_value["NOMBRE_TH"], 0,37);
                }
                if($caracteresnombre>50){
                    $x_value["NOMBRE_TH"] = substr($x_value["NOMBRE_TH"],0,50);
                }
                $columnas = "<tr>
                        <td id='tdfilas' >" . ($y + 1) . "</td>
                        <td id='tdfilas' >{$x_value["NIT_EMPRESA"]}</td>
                        <td id='tdfilas' $clasenombr>{$x_value["NOMBRE_TH"]}</td>
                        <td id='tdfilas' $clasenombr>{$x_value["APELLIDO_TH"]}</td>
                        <td id='tdfilas' >{$x_value["DOCUMENTO"]}</td>
                        <td id='tdfilas' >{$x_value["TARJETA"]}</td>
                        <td id='tdfilas' >Coomeva</td>
                        <td id='tdfilas' >{$x_value["PRODUCTO"]}</td>
                </tr>";
                //    switch ($y) {
                //case 32:
                //  $contenido2 = $contenido2 . $columnas."</table>";
                //	break;
                /* case 33:
                  $contenido2 = $contenido2 .'<table class="tabla1" width="100%" cellspacing="0">'. $contenido2cab. $columnas;
                  break; */
                //   default :
                if ($remesa["codigo"] == $x_value["codigo"]) {
                    if ($y > 31) {
                        if ($numcol == ($y - 1)) {
                            $contenido2 = $contenido2 . "</table>";
                            $numcol = $numcol + $temp;
                            $contenido2 = $contenido2 . '<table class="tabla1" width="100%" cellspacing="0" >' . $contenido2cab . $columnas;
                        } else {
                            $contenido2 = $contenido2 . $columnas . '<p>' . $numcol . 'X' . $y . '</p>';
                        }
                    } else {
                        $contenido2 = $contenido2 . $columnas;
                    }
                } else {
                    $y = -1;
                }
                //       break;
                //  }
                $y++;
            }

            $contenido2 = $contenido2 . "</table>";
            /* IF($remesa["codigo"] == 181){
              var_dump($contenido2);
              exit();
              } */
            $html = mb_convert_encoding($contenido, 'UTF-8', 'UTF-8');
            $mpdf->SetHTMLHeader($html);
            $mpdf->SetHTMLFooter("
            <img src='/var/www/html/static/img/remesawsonline/footer.png' width='100%' >");
            $mpdf->AddPage();
            $mpdf->SetHTMLHeader("<tr>
            <td style='width: 100%; vertical-align: top; height: 200px'>
                                <img src='/var/www/html/static/img/remesawsonline/header.png' width='100%' />
            </td>
            </tr>");
            $html2 = mb_convert_encoding($contenido2, 'UTF-8', 'UTF-8');
            $mpdf->WriteHTML($html2);
        }

        $mpdf->Output($nombre, 'F');
        echo $url . '';
    }

    public function crear_x_remesa($numero_remesa=null) {
            
        function unique_multidim_array_x($array, $key) {
            $temp_array = array();
            $i = 0;
            $key_array = array();

            foreach ($array as $val) {
                if (!in_array($val[$key], $key_array)) {
                    $key_array[$i] = $val[$key];
                    $temp_array[$i] = $val;
                }
                $i++;
            }
            return $temp_array;
        } 
       
        if($numero_remesa ===null) {
        $codigo_envio = $_POST['NUMERO_REMESA'];
        }else{
         $codigo_envio=$numero_remesa;
        }

        require_once '/var/www/html/mpdf/vendor/autoload.php';

        $urlpublica = $this->db->query("select VALOR_PARAMETRO from modgeneri.gentblpargen where pk_pargen_codigo =99");
        $urlpublica = $urlpublica->result_array[0];

        // require_once 'C:\xampp\htdocs\mpdf\vendor\autoload.php';
        $remesa = $this->db->query("
           select env.pk_envio_codigo PK_ENVIO,
            ped.pk_empresa PK_EMPRESA,
            entemp.DOCUMENTO NIT_EMPRESA,
            nvl(entemp.razon_social,entemp.nombre||' '||entemp.apellido) NOMBRE_EMPRESA,
            ped.pk_custodio CUSTODIO,
            entcus.CORREO_ELECTRONICO CORREO_CUSTODIO,
            modcliuni.clipkgconsultas.fncdatocontacto(modcliuni.clipkgconsultas.fncmaxpkcontacto(ped.pk_custodio , 46)) TELEFONO_CUSTODIO,
            NVL(modcliuni.clipkgconsultas.fncdatocontacto(modcliuni.clipkgconsultas.fncmaxpkcontacto(ped.pk_custodio, 48)),
                modcliuni.clipkgconsultas.fncdatocontacto(modcliuni.clipkgconsultas.fncmaxpkcontacto(ped.pk_custodio, 49)))DIRECCIO_CUSTODIO,
            CIU.NOMBRE CIUDAD_CUSTODIO,
            UPPER(enttar.nombre) NOMBRE_TH,
            CASE WHEN  pro.limitacion=0 THEN 
            tar.IDENTIFICADOR||' '|| 
            (case
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
            NVL(UPPER(enttar.razon_social),UPPER(enttar.apellido))
            END
            APELLIDO_TH,
            --UPPER(enttar.apellido) APELLIDO_TH,
            (CASE WHEN  LINPRO.PK_LINPRO_CODIGO= 2  OR (LINPRO.PK_LINPRO_CODIGO =3 AND pro.limitacion=0) 
            THEN ' '||tar.id_empresa||' ' 
            ELSE '*** *** '||SUBSTR(tar.id_empresa,-4) END) TAR_CEDULA,
            UPPER(entcus.nombre)||' '||UPPER(entcus.apellido) NOMBRE_CUSTODIO,
            detped.PK_PRODUCTO PKEYPRODUCTO,
            pro.nombre_producto NOMBRE_PRODUCTO,
	    LINPRO.PK_LINPRO_CODIGO LINPRO,
            (CASE LINPRO.PK_LINPRO_CODIGO 
            WHEN 2 THEN ' '||SUBSTR(tar.numero,-4)||' '
            ELSE  SUBSTR(tar.numero,-4) END)NUMERO_TARJETA
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
        JOIN MODCLIUNI.CLITBLCIUDAD CIU 
            ON ciu.pk_ciu_codigo = entcus.clitblciudad_pk_ciu_codigo
        join MODTARHAB.tartbltarjet tar 
            on tar.pk_detped_codigo = detped.pk_detped_codigo 
        join MODTARHAB.tartblcuenta cue 
            on cue.pk_tartblcuenta_codigo = tar.pk_tartblcuenta_codigo
        where env.pk_envio_codigo = $codigo_envio  
         ORDER BY env.pk_envio_codigo,nombre_empresa asc,SUBSTR(tar.numero,-4) asc");
        //ORDER BY env.pk_envio_codigo,linpro.pk_linpro_codigo,nombre_empresa,pro.nombre_producto desc");
        $data = $remesa->result_array;


        // $detalles = explode(";", $_POST['TEXTO']);
        //$encabezado = explode(',', $detalles[0]);

        $encabezados = array();
        $recorrido = 0;

        /* foreach ($detalles as $x => $x_value) {
          $content = explode(",", $x_value);
          $encabezados[$recorrido] = array(
          "codigo" => $content[0],
          "nombre" => if ()$content[1] . ' ' . $content[2],
          "correo" => $content[6],
          "telefono" => $content[4],
          "ciudad" => $content[5]
          );
          $recorrido++;
          } */

        foreach ($data as $key => $value) {

            $encabezados[$recorrido] = array(
                "codigo" => $value['PK_ENVIO'],
                "NOM_EMPRESA" => $value['NOMBRE_EMPRESA'],
                "nombre" => $value['NOMBRE_CUSTODIO'],
                "correo" => $value['CORREO_CUSTODIO'],
                "telefono" => $value['TELEFONO_CUSTODIO'],
                "direccion" => $value['DIRECCIO_CUSTODIO'],
                "ciudad" => $value['CIUDAD_CUSTODIO']
            );
            log_info($this->iniciLog . $this->logHeader . $this->encabezados);
            $temp = null;
            if ($value['LINPRO'] != 2) {
                $temp = ' *** ' . $value['NUMERO_TARJETA'];
            } else {
                $temp = ' *** ' . $value['NUMERO_TARJETA'];
            }
            $detalles[$recorrido] = array(
                "codigo" => $value['PK_ENVIO'],
                "NIT_EMPRESA" => $value['NIT_EMPRESA'],
                "NOMBRE_TH" => $value['NOMBRE_TH'],
                "APELLIDO_TH" => $value['APELLIDO_TH'],
                "DOCUMENTO" => $value['TAR_CEDULA'],
                "TARJETA" => $temp,
                "PRODUCTO" => $value['NOMBRE_PRODUCTO']
            );
            log_info($this->iniciLog . $this->logHeader . $detalles);
            $recorrido++;
            log_info($this->iniciLog . $this->logHeader . ' FIN REMESA ');
        }
        $encabezados = unique_multidim_array_x($encabezados, 'codigo');
        sort($encabezados);
        $dir = 'uploads/remesa/';
        // $dir = 'C:\xampp\htdocs\uploads';
        $date = date('Y-m-d');
        $random = rand(1000, 9999);
        $name = strtolower($date . '-' . $random . '.pdf');
        $file_dir = $dir . $name; //.basename($_FILES['file']['name']);
        //$url = 'http://' . $_SERVER['SERVER_ADDR'] . ':' . $_SERVER['SERVER_PORT'] . '/uploads/' . $name;
        //$url = 'http://' . $_SERVER['SERVER_ADDR'] . ':' . $_SERVER['SERVER_PORT'] . '/' .$dir. $name;
        $url = $urlpublica['VALOR_PARAMETRO'] . '/' . $dir . $name;
        $nombre = $file_dir;

        $mpdf = new \Mpdf\Mpdf([
            'tempDir' => '/var/www/html/mpdf/tmp',
            //    'tempDir' => 'C:\xampp\htdocs\mpdf\tmp',
            'mode' => 'utf-8',
            'format' => 'A4',
            'margin_header' => 10,
            'margin_top' => 55,
            'margin_bottom' => 40,
            'default_font' => 'calibri'
        ]);

        foreach ($encabezados as $remesa) {

            $contenido = "";

            $contenido = $contenido . "<table style='width:100%; font-size: 14px;'>
        <tr>
            <td style='width: 100%; vertical-align: top; height: 200px'>
			<img src='/var/www/html/static/img/remesawsonline/header.png' width='100%' />
                <table style='width: 100%;'>
                    <tr>
                        <td style='width: 75%;'>
                            <strong>Se&ntilde;ores:</strong> 
                        </td>
                        <td> <strong> RMPP-{$remesa['codigo']} </strong> </td>
                    </tr>
                    <tr>
                        <td style='width: 75%;'>
                        <strong>{$remesa['NOM_EMPRESA']}</strong>
                        </td>
                        <td> <strong> " . "Fecha: " . date("d/m/Y") . " </strong> </td>
                    </tr>
                    <tr>
                        <td style='width: 75%;'>
                        {$remesa['nombre']}
                        </td>
                        <td> <strong> " . "Hora: " . date("H:i:s") . " </strong> </td>
                    </tr>
                    <tr>
                        <td>
                          {$remesa['direccion']}
                        </td>
                    </tr>
                    <tr>
                        <td>
                           {$remesa['telefono']}
                         </td>
                    </tr>
                    <tr>
                        <td>
                           {$remesa['ciudad']}
                         </td>
                    </tr>
                    <tr>
                         <td>
                            <strong>Respetados Se&ntilde;ores:</strong>
                          </td>
                    </tr>
                    <tr>
                    <td>
                    Estamos haciendo entrega de las siguientes tarjetas segun su amable solicitud
                    </tr>
                </table>
                ";
            $contenido = $contenido . "</td>
            
        </tr>

    </table>";

            $contenido2 = '
<br/>
<br/>
<br/>
<br/>
<br/>
<br/>
<br/>
<br/>
<br/>
<style>
.tabla1,#tdheader{
  border-collapse: collapse;
  border: 1px solid black;
  font-size:10px;
  background-color:#FFFFFF;
}
#tdfilas{
border-right: 1px solid black;
}
.tabla1 tr:nth-child(odd) {
	background-color:#BEBEBE;
}
.tabla1 tr:nth-child(even) {
    background-color:#FFFFFF; 
}
</style>

<table style="font-size: 10px;" class="tabla1" width="100%" cellspacing="0">';
            $contenido2cab = '
        <tr>
            <td id="tdheader">
                <strong>Reg</strong>
            </td>
            <td id="tdheader">
                <strong>Nit</strong>
            </td>
            <td id="tdheader">
                <strong>Nombres</strong>
            </td>
            <td id="tdheader">
                <strong>Apellidos</strong>
            </td>
            <td id="tdheader">
                <strong>Identificacion</strong>
            </td>
            <td id="tdheader">
                <strong>Tarjeta</strong>
            </td>
            <td id="tdheader">
                <strong>Banco</strong>
            </td>
            <td id="tdheader"> 
                <strong>Producto</strong>
            </td>
        </tr> 
    ';
            $contenido2 = $contenido2 . $contenido2cab;
            $y = 0;
            $temp = 53;
            $numcol = 39;
            foreach ($detalles as $x => $x_value) {

                if ($x_value["APELLIDO_TH"] == $x_value["NOMBRE_TH"]) {
                    $x_value["APELLIDO_TH"] = "";
                }
                $clasenombr = "";
                $caracteresnombre = strlen($x_value["NOMBRE_TH"]);
                $caracteresapellido = strlen($x_value["APELLIDO_TH"]);
                $totalcaracteres = $caracteresnombre+$caracteresapellido;
                if($totalcaracteres>45){
                    $clasenombr="style='font-size: 8px;'";
                }
                $columnas = "<tr>
                        <td id='tdfilas' >" . ($y + 1) . "</td>
                        <td id='tdfilas' >{$x_value["NIT_EMPRESA"]}</td>
                        <td id='tdfilas' $totalcaracteres>{$x_value["NOMBRE_TH"]}</td>
                        <td id='tdfilas' $totalcaracteres>{$x_value["APELLIDO_TH"]}</td>
                        <td id='tdfilas' >{$x_value["DOCUMENTO"]}</td>
                        <td id='tdfilas' >{$x_value["TARJETA"]}</td>
                        <td id='tdfilas' >Coomeva</td>
                        <td id='tdfilas' >{$x_value["PRODUCTO"]}</td>
                </tr>";
                //    switch ($y) {
                //case 32:
                //  $contenido2 = $contenido2 . $columnas."</table>";
                //	break;
                /* case 33:
                  $contenido2 = $contenido2 .'<table class="tabla1" width="100%" cellspacing="0">'. $contenido2cab. $columnas;
                  break; */
                //   default :
                if ($remesa["codigo"] == $x_value["codigo"]) {
                    if ($y > 31) {
                        if ($numcol == ($y - 1)) {
                            $contenido2 = $contenido2 . "</table>";
                            $numcol = $numcol + $temp;
                            $contenido2 = $contenido2 . '<table class="tabla1" width="100%" cellspacing="0" >' . $contenido2cab . $columnas;
                        } else {
                            $contenido2 = $contenido2 . $columnas . '<p>' . $numcol . 'X' . $y . '</p>';
                        }
                    } else {
                        $contenido2 = $contenido2 . $columnas;
                    }
                } else {
                    $y = -1;
                }
                //       break;
                //  }
                $y++;
            }

            $contenido2 = $contenido2 . "</table>";
            /* IF($remesa["codigo"] == 181){
              var_dump($contenido2);
              exit();
              } */
            $html = mb_convert_encoding($contenido, 'UTF-8', 'UTF-8');
            $mpdf->SetHTMLHeader($html);
            $mpdf->SetHTMLFooter("
            <img src='/var/www/html/static/img/remesawsonline/footer.png' width='100%' >");
            $mpdf->AddPage();
            $mpdf->SetHTMLHeader("<tr>
            <td style='width: 100%; vertical-align: top; height: 200px'>
                                <img src='/var/www/html/static/img/remesawsonline/header.png' width='100%' />
            </td>
            </tr>");
            $html2 = mb_convert_encoding($contenido2, 'UTF-8', 'UTF-8');
            $mpdf->WriteHTML($html2);
        }

        $mpdf->Output($nombre, 'F');
        echo $url . '';
    }

}
