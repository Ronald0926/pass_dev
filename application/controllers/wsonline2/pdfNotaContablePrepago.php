<?php

ini_set("pcre.backtrack_limit", "5000000");
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class pdfNotaContablePrepago extends CI_Controller {

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

    public function crear($orden = 0) {
        log_info($this->iniciLog . 'INICIO CREAR PDF NOTA CONTABLE PREPAGO ORDEN=' . $this->postData . $orden);


        require_once 'mpdf/vendor/autoload.php';
        try {
            $post = $this->input->post();
            if (!empty($post['CODIGO_ORDEN'])) {
                $orden = $post['CODIGO_ORDEN'];
            }

            if ($orden != null) {

                log_info($this->postData . $orden);
                $urlpublica = $this->db->query("select VALOR_PARAMETRO from modgeneri.gentblpargen where pk_pargen_codigo =96");
                $urlpublica = $urlpublica->result_array[0]['VALOR_PARAMETRO'];

                $datosempresa = $this->db->query("select PK_CLIENTE FROM MODPROPAG.PPATBLORDCOM WHERE PK_ORDCOM_CODIGO=$orden");
                $pkEmpresa = $datosempresa->result_array[0]['PK_CLIENTE'];
                $empresa = $this->db->query("SELECT distinct ent.DOCUMENTO DOCUMENTO,
                    NVL(ent.RAZON_SOCIAL, ent.NOMBRE||' '||ent.APELLIDO) NOMBREEMPRESA
                        FROM MODCLIUNI.CLITBLENTIDA ent  
                        WHERE ent.PK_ENT_CODIGO=$pkEmpresa");
                $data_body['empresa'] = $empresa->result_array[0];


                $datosrecarga = $this->db->query("SELECT  lower(p.nombre_producto) PRODUCTO,
                                                            valor_unit  VALOR_UNITARIO, 
                                                             cantidad CANTIDAD,
                                                            cantidad*valor_unit TOTAL,
                                                            to_char(do.fecha_creacion,'dd/mm/yyyy') FECHA_CREACION
                                                FROM MODPROPAG.PPATBLDETORD do
                                                INNER JOIN  MODPRODUC.PROTBLPRODUC p
                                                ON p.PK_PRODUC_CODIGO = do.PK_PRODUCTO
                                                WHERE PK_ORDEN_COMPRA = $orden
                                                AND p.PK_TIPPRO_CODIGO=3
                                                AND do.pk_pedido IS NULL 
                                                AND do.pk_pedido_abono  IS NULL
                                                AND do.abono_llave_maestra=0
                                                ORDER BY 1 ASC");
                $data_body['productos'] = $datosrecarga->result_array;

                $datostotal = $this->db->query("SELECT
                                                nvl(sum(cantidad * valor_unit),0) TOTAL
                                            FROM
                                                     modpropag.ppatbldetord do
                                                INNER JOIN modproduc.protblproduc p ON p.pk_produc_codigo = do.pk_producto
                                            WHERE
                                                    pk_orden_compra = $orden
                                                AND p.pk_tippro_codigo = 3
                                                AND do.pk_pedido IS NULL
                                                AND do.pk_pedido_abono IS NULL
                                                AND do.abono_llave_maestra = 0");
                $data_body['total'] = $datostotal->result_array[0]['TOTAL'];
                $valor = $datostotal->result_array[0]['TOTAL'];

                $data_body['totalLetras'] = $this->numeros_letras($valor);
                $datosnotacontable = $this->db->query(" SELECT numero_nota_contable NUMERO_NOTA,
                                                        prefijo PREFIJO,
                                                        to_char(FECHA_CREACION,'dd/mm/yyyy hh24:mi:ss') FECHA_CREACION
                                                        FROM
                                                        modpropag.ppatblnotacontable
                                                        WHERE
                                                        pk_ordcom_codigo =$orden");

                $data_body['nota'] = $datosnotacontable->result_array[0];

                $telefono = $this->db->query("SELECT DATO FROM MODCLIUNI.CLITBLCONTAC WHERE PK_CONTAC_CODIGO"
                        . "= MODCLIUNI.CLIPKGCONSULTAS.fncmaxpkcontacto({$pkEmpresa},47)");
                $data_body['telefono'] = $telefono->result_array[0];

                $direccion = $this->db->query("SELECT DATO FROM MODCLIUNI.CLITBLCONTAC WHERE PK_CONTAC_CODIGO"
                        . "= MODCLIUNI.CLIPKGCONSULTAS.fncmaxpkcontacto({$pkEmpresa},48)");
                $data_body['direccion'] = $direccion->result_array[0];

                $datosdir = explode('|', $data_body['direccion']['DATO']);
                $data_body['direccion'] = $datosdir[0];

                $ciudad = $this->db->query("SELECT pais.NOMBRE NOMBREPAIS,
                dep.NOMBRE NOMBREDEPARTAMENTO,
                ciu.nombre NOMBRECIUDAD
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
                $nempresa = $empresa->result_array[0]['NOMBREEMPRESA'];
                $data_body['orden'] = $orden;


                $contenido = $this->load->view('/wsonline2/notaContablePrepago/pdfNotaContablePrepago', $data_body, TRUE);
                $dir = 'uploads/notacontableprepago/';
                $date = date('Y-m-d');
                $random = rand(1000, 9999);
                $name = strtolower($nempresa . '-' . $date . '-' . $random . '.pdf');
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
                log_info($this->iniciLog . 'FIN ' . $url);
                return $url;
            }
        } catch (Exception $exc) {
            log_info($this->iniciLog . 'FIN ' . $url);
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
