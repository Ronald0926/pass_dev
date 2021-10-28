<?php

ini_set("pcre.backtrack_limit", "5000000");
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Cotizacion extends CI_Controller {

    public function __construct() {
        parent::__construct();
    }

    public function __destruct() {
        $this->db->close();
    }

    public function crearpdf($parpkproceso = null) {

        if ($_POST['PROCESO'] != NULL) {
            $parpkproceso = $_POST['PROCESO'];
            $datos = $_POST['DETALLE'];
            $detalles = explode(";", $datos);
        }
        try {
            require_once '/var/www/html/mpdf/vendor/autoload.php';

            $urlpublica = $this->db->query("select VALOR_PARAMETRO from modgeneri.gentblpargen where pk_pargen_codigo =99");
            $urlpublica = $urlpublica->result_array[0];
            //Entidad del cliente
            $EntidadCliente = $this->db->query("
	SELECT pk_entida_cliente ,comercial.NOMBRE||' '||comercial.apellido COMERCIAL 
        FROM MODCOMERC.comtblcotiza co  
        join MODCOMERC.COMTBLPROCES  pr 
        on co.pk_cotiza_codigo = pr.pk_cotiza_codigo 
        join modcliuni.clitblentida comercial
        ON comercial.PK_ENT_CODIGO=co.PK_ENTIDAD_ASESOR
        where pr.pk_proces_codigo = $parpkproceso ");
            $EntidadCliente = $EntidadCliente->result_array[0];
            $dataInfo['comercial'] = $EntidadCliente['COMERCIAL'];
            $EntidadCliente = $EntidadCliente['PK_ENTIDA_CLIENTE'];
            $dataInfo['proceso'] = $parpkproceso;
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

            $EntidadCliente = $EntidadCliente->result_array[0];
            $dataInfo['entidadCliente'] = $EntidadCliente;


            $sqldataCotizacion = $this->db->query("select 
            par.PK_PARAME_CODIGO PK_PARAME_CODIGO,
            par.PARAMETRO PARAMETRO,
            bancos.NOMBRE_BANCO,
            bines.BIN,
            marcas.NOMBRE_MARCA MARCA,
            par.PK_TIPPAR_CODIGO PK_TIPPAR_CODIGO,
            tp.NOMBRE TIPO_PRODUCTO,
            par.CANTIDAD CANTIDAD,
            val.PK_VALORES_CODIGO PK_VALORES_CODIGO,
            nvl(val.VALOR,0) VALOR,
            val.PK_TIPCOS_CODIGO PK_TIPCOS_CODIGO,
            tc.nombre tipo_costo,
            par.PK_PRODUCTO_CODIGO,
            par.CANTIDAD*nvl(val.VALOR,0) total,
            par.CANTIDAD*nvl(val.VALOR,0) *0.19 IVA,
            par.valor_abono,
            par.TASA,
            case when tasa > 100 then 
            par.TASA
            else
            round(par.VALOR_ABONO*(par.TASA/100)) 
            end Costo_administracion
            --par.VALOR_ABONO*nvl(par.TASA,0) Costo_administracion
            from  MODCOMERC.COMTBLPARAME par 
            left join  MODCOMERC.comtblvalores val on par.PK_PARAME_CODIGO= val.PK_PARAME_CODIGO 
            left join MODPRODUC.PROTBLTIPPRO tp on tp.pk_tippro_codigo=par.pk_tippar_codigo 
            left join MODPRODUC.PROTBLTIPCOS tc  on  val.PK_TIPCOS_CODIGO=tc.pk_tipco_codigo
            LEFT JOIN MODPRODUC.protblbinproducto binpro ON par.pk_bin_producto_codigo=binpro.pk_bin_producto_codigo 
            LEFT JOIN modproduc.protblbines bines ON binpro.pk_bines_codigo=bines.PK_BINES_CODIGO
            LEFT JOIN modproduc.protblbancos  bancos ON bines.pk_banco_codigo=bancos.PK_BANCO_CODIGO
            LEFT JOIN modproduc.protblmarcastarjetas marcas on bines.PK_MARCASTARJETAS_CODIGO=marcas.PK_MARCASTARJETAS_CODIGO
            where  par.PK_PROCES_CODIGO =$parpkproceso 
            and tp.pk_tippro_codigo=1");

            $dataInfo['dataCotizacion'] = $sqldataCotizacion->result_array;
            
            //consulta servicios 
               $sqldataServicios = $this->db->query("select 
                par.PARAMETRO PARAMETRO,
                par.CANTIDAD CANTIDAD,
                nvl(val.VALOR,0) VALOR,
                par.CANTIDAD*nvl(val.VALOR,0) *0.19 IVA,
                par.CANTIDAD*nvl(val.VALOR,0) total,
                par.PK_TIPPAR_CODIGO PK_TIPPAR_CODIGO,
                par.PK_PRODUCTO_CODIGO
                from  MODCOMERC.COMTBLPARAME par left join  MODCOMERC.comtblvalores val 
                on par.PK_PARAME_CODIGO= val.PK_PARAME_CODIGO 
                left join MODPRODUC.PROTBLTIPPRO tp on tp.pk_tippro_codigo=par.pk_tippar_codigo 
                left join MODPRODUC.PROTBLTIPCOS tc  on  val.PK_TIPCOS_CODIGO=tc.pk_tipco_codigo
                where  par.PK_PROCES_CODIGO =$parpkproceso 
                and tp.pk_tippro_codigo=3 and par.pk_producto_codigo in(70,999994,999993)
                ORDER BY par.PARAMETRO desc");
            $dataServicios=$sqldataServicios->result_array;
            $dataInfo['servicios']=$dataServicios;
            $valorReexpide = $this->db->query("select valor_parametro from modgeneri.gentblpargen where pk_pargen_codigo=71 ");
            
            $valorReexpide=$valorReexpide->result_array;
            $dataInfo['valorReexpedicion']=$valorReexpide;
            $contenido = $this->load->view('/wsonline2/cotizacion/pdfCotizacion', $dataInfo, TRUE);

            $dir = 'uploads/cotizacion/';
            $date = date('Y-m-d');
            $random = rand(1000, 9999);
            $name = strtolower($InfoEntidad['DOCUMENTO'] . '-' . $date . '-' . $random . '.pdf');
            $file_dir = $dir . $name;
            //$url = 'http://' . $_SERVER['SERVER_ADDR'] . ':' . $_SERVER['SERVER_PORT'] . '/uploads/' . $name;
            //$url = 'http://localhost:' . $_SERVER['SERVER_PORT'] . '/uploads/' . $name;
            $url = $urlpublica['VALOR_PARAMETRO'] . '/' . $dir . $name;
            $nombre = $file_dir;

            $mpdf = new \Mpdf\Mpdf([
                'tempDir' => 'mpdf/tmp',
                'mode' => 'utf-8',
                'format' => 'A4',
                'margin_header' => 5,
                'margin_top' => 25,
                'margin_bottom' => 20,
                'margin_left' => 30,
                'margin_right' => 20,
                'default_font' => 'arial,sans-serif,serif'
            ]);
            $html = mb_convert_encoding($contenido, 'UTF-8', 'UTF-8');
            $mpdf->SetHTMLHeader("
            <div style='text-align: right;'>
            <img src='../pdf/Imagenes/logo.png' width='250' >
            </div>
            ");

            $mpdf->SetHTMLFooter('
            <table width="100%" style="margin-bottom: 20px;">
                <tr>
                    <td width="50%" style="text-align: left;font-size: 11px; color: #B4B4B4;">FO-GC-03/ 28/07/20/ V6</td>
                     <td width="50%" style="text-align: right;font-size: 11px; color: #B4B4B4;">{PAGENO}</td>
                </tr>
            </table>');

            // $mpdf->SetHTMLHeader($header);
            // $mpdf->SetHTMLFooter($footer);
            //$mpdf->AddPage();
            //$mpdf->WriteHTML($css, \Mpdf\HTMLParserMode::HEADER_CSS);
            
            $mpdf->WriteHTML($html);
            $mpdf->Output($nombre, 'F');
            
            $sql = " 
                    BEGIN MODCOMERC.compkgfunciones.prcinsertarlinkcotizacion(
                    linkcotizacion=>:linkcotizacion,
                    proceso=>:proceso);
                    END;";
            $conn = $this->db->conn_id;
            $stmt = oci_parse($conn, $sql);
            
            oci_bind_by_name($stmt, ':linkcotizacion', $url, 80);
            oci_bind_by_name($stmt, ':proceso', $parpkproceso, 32);
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

}
