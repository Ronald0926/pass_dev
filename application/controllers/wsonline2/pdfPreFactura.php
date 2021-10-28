<?php

ini_set("pcre.backtrack_limit", "5000000");
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class PdfPreFactura extends CI_Controller {
    public $iniciLog='[INFO] ';
    public $logHeader = 'APOLOINFO::::::::: ';
    public $postData = 'POSTDATA::::::::: ';
    public $queryData = 'QUERYDATA::::::: ';
    public $finFuncion=' FIN PROCEDIMIENTO::::::: ';

    public function __construct() {
        parent::__construct();
        $this->load->helper('log4php');
//        if ($this->session->userdata('entidad') == NULL) {
//            redirect('/');
//        }
    }

    public function __destruct() {
        $this->db->close();
    }

    public function crear($orden = 0) {
        log_info($this->iniciLog. 'INICIO CREAR PDF PREFACTURA ORDEN='.$this->postData.$orden);
        

        require_once 'mpdf/vendor/autoload.php';
        try {
            if ($orden != null) {
                log_info($this->postData.$orden);
                $urlpublica = $this->db->query("select VALOR_PARAMETRO from modgeneri.gentblpargen where pk_pargen_codigo =96");
                $urlpublica = $urlpublica->result_array[0]['VALOR_PARAMETRO'];
                $sqlcampana = $this->db->query("select DISTINCT(pk_campana_codigo) from modprepedido.prepetblsolicitud 
                                where pk_preorden_codigo =$orden");
                $campana = $sqlcampana->result_array[0]['PK_CAMPANA_CODIGO'];

                $EntidadCliente = $this->db->query("Select pk_emp_orden from MODPREPEDIDO.prepetblpreorden 
                        where pk_preorden_codigo = $orden ");

                $EntidadCliente = $EntidadCliente->result_array[0];
                $pkEmpresa = $EntidadCliente['PK_EMP_ORDEN'];
                $Empresa = $this->db->query("SELECT distinct ent.DOCUMENTO,NVL(ent.RAZON_SOCIAL, ent.NOMBRE||' '||ent.APELLIDO) NOMBREEMPRESA
                        FROM
                            MODCLIUNI.CLITBLENTIDA ent  WHERE
                    ent.PK_ENT_CODIGO=$pkEmpresa");

                $data_body['empresa'] = $Empresa->result_array[0];
               
                
                //Consulta costos abonos
                $dataAbonos = $this->db->query("select 'ABONO A PRODUCTO '||producto.nombre_producto producto
                ,count(detallesolicitud.pk_producto) cantidad
                ,detallesolicitud.PK_PRODUCTO pk_producto
                ,sum(detallesolicitud.MONTO_ABONO) valor_unitario 
                ,sum(detallesolicitud.MONTO_ABONO) valor_total
                from  MODPREPEDIDO.prepetblsolicitud solicitud
                join MODPREPEDIDO.prepetbldetallesolicitud detallesolicitud
                ON solicitud.pk_codigo_solicitud=detallesolicitud.pk_codigo_solicitud
                join modproduc.protblproduc producto
                ON detallesolicitud.pk_producto=producto.PK_PRODUC_CODIGO
                JOIN MODPREPEDIDO.prepetblpreorden preord 
                ON preord.pk_preorden_codigo = solicitud.pk_preorden_codigo
                where preord.pk_preorden_codigo = $orden
                and solicitud.pk_tipsol_codigo in (3,4)
                and solicitud.pk_campana_codigo = {$campana}
                group by 'ABONO A PRODUCTO '||producto.nombre_producto, detallesolicitud.pk_producto");
                $data_body['dataAbonos'] = $dataAbonos->result_array;
//            Consulta costos administrativos
                $dataGastosAd = $this->db->query("select 'SERVICIO ADMINISTRACION ABONO A PRODUCTO '||producto.nombre_producto producto
                ,count(detallesolicitud.pk_producto) cantidad
                ,detallesolicitud.PK_PRODUCTO pk_producto,
                --,sum(detallesolicitud.MONTO_ABONO*(valor_tarjetas.tasa/100)) valor_unitario 
                --,sum(detallesolicitud.MONTO_ABONO* (valor_tarjetas.tasa/100)) valor_total
                CASE WHEN round(valor_tarjetas.tasa) <=100 THEN 
                sum(detallesolicitud.MONTO_ABONO*(valor_tarjetas.tasa/100))
                ELSE
                SUM(valor_tarjetas.tasa)
                END valor_unitario
                from  MODPREPEDIDO.prepetblsolicitud solicitud
                join MODPREPEDIDO.prepetbldetallesolicitud detallesolicitud
                ON solicitud.pk_codigo_solicitud=detallesolicitud.pk_codigo_solicitud
                join modcomerc.view_valor_tarjetas valor_tarjetas
                on valor_tarjetas.pk_entida_cliente=solicitud.pk_emp_solicitud
                and solicitud.pk_campana_codigo=solicitud.pk_campana_codigo
                and detallesolicitud.pk_producto=valor_tarjetas.pk_producto_codigo
                join modproduc.protblproduc producto
                ON detallesolicitud.pk_producto=producto.PK_PRODUC_CODIGO
                JOIN MODPREPEDIDO.prepetblpreorden preord 
                ON preord.pk_preorden_codigo = solicitud.pk_preorden_codigo
                where preord.pk_preorden_codigo = $orden
                and solicitud.pk_tipsol_codigo in (3,4)
                and solicitud.pk_campana_codigo = {$campana}
                and valor_tarjetas.pk_campana_codigo=solicitud.pk_campana_codigo
                group by 'SERVICIO ADMINISTRACION ABONO A PRODUCTO '||producto.nombre_producto, detallesolicitud.pk_producto, valor_tarjetas.tasa");
                $data_body['dataAdmin'] = $dataGastosAd->result_array;
                // Consulta cobros de tarjetas
                $dataTarjetas = $this->db->query("select producto.nombre_producto producto
                ,count(pk_producto) cantidad
                ,PK_PRODUCTO
                ,valor_tarjetas.valor_unitario valor_unitario 
                ,(count(pk_producto)* valor_tarjetas.valor_unitario) valor_total
                from  MODPREPEDIDO.prepetblsolicitud solicitud
                join MODPREPEDIDO.prepetbldetallesolicitud detallesolicitud
                ON solicitud.pk_codigo_solicitud=detallesolicitud.pk_codigo_solicitud
                join modcomerc.view_valor_tarjetas valor_tarjetas
                on valor_tarjetas.pk_entida_cliente=solicitud.pk_emp_solicitud
                and solicitud.pk_campana_codigo=solicitud.pk_campana_codigo
                and detallesolicitud.pk_producto=valor_tarjetas.pk_producto_codigo
                join modproduc.protblproduc producto
                ON detallesolicitud.pk_producto=producto.PK_PRODUC_CODIGO
                JOIN MODPREPEDIDO.prepetblpreorden preord 
                ON preord.pk_preorden_codigo = solicitud.pk_preorden_codigo
                where preord.pk_preorden_codigo = $orden
                and solicitud.pk_tipsol_codigo in (1,2)
                and solicitud.pk_campana_codigo = {$campana}
                and valor_tarjetas.pk_campana_codigo=solicitud.pk_campana_codigo    
                group by producto.nombre_producto, pk_producto,valor_tarjetas.valor_unitario");
                    $data_body['dataTarjetas'] = $dataTarjetas->result_array;

                $telefono = $this->db->query("SELECT DATO FROM MODCLIUNI.CLITBLCONTAC WHERE PK_CONTAC_CODIGO"
                        . "= MODCLIUNI.CLIPKGCONSULTAS.fncmaxpkcontacto({$pkEmpresa},47)");
                $data_body['telefono'] = $telefono->result_array[0];
                $direccion = $this->db->query("SELECT DATO FROM MODCLIUNI.CLITBLCONTAC WHERE PK_CONTAC_CODIGO"
                        . "= MODCLIUNI.CLIPKGCONSULTAS.fncmaxpkcontacto({$pkEmpresa},48)");
                $data_body['direccion'] = $direccion->result_array[0];
                $datosdir = explode('|', $data_body['direccion']['DATO']);
                $data_body['direccion'] = $datosdir[0];
                $ciudad = $this->db->query("SELECT pais.NOMBRE NOMBREPAIS, dep.NOMBRE NOMBREDEPARTAMENTO,ciu.nombre NOMBRECIUDAD
                FROM MODCLIUNI.CLITBLPAIS pais
                JOIN MODCLIUNI.CLITBLDEPPAI dep
                ON pais.pk_pais_codigo=dep.clitblpais_pk_pais_codigo
                JOIN MODCLIUNI.CLITBLCIUDAD ciu
                ON ciu.CLITBLDEPPAI_PK_DEP_CODIGO=dep.pk_dep_codigo
                JOIN MODCLIUNI.CLITBLENTIDA ent
                ON ent.clitblciudad_pk_ciu_codigo=ciu.pk_ciu_codigo
                WHERE ent.pk_ent_codigo={$pkEmpresa}");
                $data_body['ciudad'] = $ciudad->result_array[0];
                $entidad = $this->session->userdata("entidad");
                $empresa = $Empresa->result_array[0]['NOMBREEMPRESA'];
                $data_body['orden']=$orden;

                //total
                $totalTarje = 0;
                $totalAbono = 0;
                $totalAdm = 0;
                $tarjetas = $dataTarjetas->result_array;
                $abonos = $dataAbonos->result_array;
                $gasAdm = $dataGastosAd->result_array;
                if (!empty($tarjetas)) {
                    foreach ($tarjetas as $value) {
                        $totalTarje += $value['VALOR_TOTAL'];
                    }
                }
                if (!empty($abonos)) {
                    foreach ($abonos as $value) {
                        $totalAbono += $value['VALOR_TOTAL'];
                    }
                }
                if (!empty($gasAdm)) {
                    foreach ($gasAdm as $value) {
                        $totalAdm += $value['VALOR_TOTAL'];
                    }
                }
                $valorTotal = $totalTarje + $totalAbono + $totalAdm;
                $data_body['ValorTotal'] = $valorTotal;
                $valLetras = $this->numeros_letras($valorTotal);
//            CifrasEnLetras.convertirCifrasEnLetras($valorTotal);
                $data_body['ValorLetrasTotal'] = $valLetras;
//            $this->load->view('administrador/ajax/dataDetallePrefac', $data_body);
            }
            $contenido = $this->load->view('/portal/solicitudGestion/pdfPreFact', $data_body, TRUE);
            $dir = 'uploads/preOrden/';
            $date = date('Y-m-d');
            $random = rand(1000, 9999);
            $name = strtolower($empresa . '-' . $date . '-' . $random . '.pdf');
            $file_dir = $dir . $name;
            $url = $urlpublica . '/' . $dir . $name;
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
                'default_font' => 'Calibri'
            ]);
            $html = mb_convert_encoding($contenido, 'UTF-8', 'UTF-8');


            $mpdf->WriteHTML($html);
            $mpdf->Output($nombre, 'F');

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
        return ucwords($valorletras);
    }

}
