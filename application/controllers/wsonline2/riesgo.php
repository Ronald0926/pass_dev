<?php

ini_set("pcre.backtrack_limit", "5000000");
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Riesgo extends CI_Controller {

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
        require_once '/var/www/html/mpdf/vendor/autoload.php';
        $urlpublica = $this->db->query("select VALOR_PARAMETRO from modgeneri.gentblpargen where pk_pargen_codigo =96");
        $urlpublica = $urlpublica->result_array[0];
        $fecha = $_POST['FECHA'];
        $cliente = $_POST['CLIENTE'];
        $datos = $_POST['LISTA'];
        $documento = $_POST['DOCUMENTO'];
        $consulta = $_POST['CONSULTA'];
        $prioridad = $_POST['PRIORIDAD'];


        $conn = $this->db->conn_id;
        $sqlxml3 = ("SELECT dbms_xmlgen.getxml(       
            'SELECT
            DETALLE
            FROM
            MODCLIUNI.CLITBLHISEVA
            WHERE
            PK_HISEVA_CODIGO=$datos')xml
      from dual");

        $s = oci_parse($conn, $sqlxml3);
        oci_execute($s, OCI_DEFAULT);
        $r = oci_fetch_array($s, OCI_RETURN_NULLS + OCI_RETURN_LOBS);
        $resultXML = ($r["XML"]);
//var_dump($resultXML);
        $xml = simplexml_load_string($resultXML);
//        var_dump($xml);
        $contLista = "";
        foreach ($xml->ROW->DETALLE->RESPUESTA->REGISTRO as $REGISTRO) {
            //var_dump($REGISTRO[1]);

            if (count($REGISTRO->FILA) === 7) {
                foreach ($REGISTRO->FILA as $fila1) {


                    $Confi = 'tipo lista:';
                    $coincidencia = strpos($fila1, $Confi);
                    if ($coincidencia !== false) {
                        //busca posision inicia signo :
                        $pos = strrpos($fila1, ':', 0);
                        //saca en contenido despues de los ':'
                        $dat = substr($fila1, $pos + 2, strlen($fila1));
                    }
                }

                if ($dat == '1') {
                    $contLista = $contLista . '<tr  bgcolor="#900020">';
                } elseif ($dat == '2') {
                    $contLista = $contLista . '<tr  bgcolor="#ff0000">';
                } elseif ($dat == '3') {
                    $contLista = $contLista . '<tr  bgcolor="#ff8c00">';
                } elseif ($dat == '4') {
                    $contLista = $contLista . '<tr  bgcolor="#ffff00">';
                } elseif ($dat == '6') {
                    $contLista = $contLista . '<tr bgcolor="#008000">';
                } elseif ($dat == '-99') {
                    $contLista = $contLista . '<tr bgcolor="#5585c3" style="color:#fff;">';
                } else {
                    $contLista = $contLista . '<tr>';
                }
            } else {
                $buscaReVacio = 'No existen registros asociados a los parámetros de consulta.';
                $filaregistronull = $REGISTRO->FILA;
                $coincidenciaBR = strpos($filaregistronull, $buscaReVacio);
                if ($coincidenciaBR !== false) {
                    $posf = strrpos($filaregistronull, '#', 0);
                    $dat = substr($filaregistronull, $posf + 1, strlen($filaregistronull));
                    $contLista = $contLista . '<tr><td colspan="7">' . $dat . '</td></tr>';
                }
            }
            foreach ($REGISTRO->FILA as $fila) {

                $CantC = 'Cantidad de coincidencias:';
                $NumC = 'Número de consulta:';
                $coincidencia1 = strpos($fila, $CantC);
                $coincidencia2 = strpos($fila, $NumC);
                if ($coincidencia1 === false && $coincidencia2 === false) {
                    //busca posision inicia signo :
                    $possi = strrpos($fila, ':', 0);
                    //saca en contenido despues de los ':'
                    $dato = substr($fila, $possi + 2, strlen($fila));
//                    echo '-'.$fila, PHP_EOL;
                    $contLista = $contLista . '<td>' . $dato . '</td>';
                }
            }
            if (count($REGISTRO->FILA) === 7) {
                $contLista = $contLista . "</tr>";
            }
        }

        /*
         * CSS TABLA
         */

        $css = " 
          #data {
            font-family: 'Montserrat', sans-serif;
            border-collapse: collapse;
            width: 100%;
            margin-top:5%;
          }

          #data td, #data th {
            border: 1px solid #ddd;
            padding: 4px;
            text-align: center;
          }
          #data th {
          background-color:#3f51b5;
          color:white;
          font-weight: bold;
          text-align: center;
          padding: 5px;
          }
          .body{
          font-family: 'Montserrat', sans-serif;
          }
          .subtitulo-iz {
            text-align: center;
            font-size: 18px;
            color: #17202A;
            padding-bottom: 20px;
        }
        .datos {
            font-size: 18px;
            color: #366199;
            margin: 0px;
        }

        .span {
            letter-spacing: -0.5pt;
            font-size: 18px;
            color: #366199;
        }
           ";
//        echo $contLista;
//        exit();
        $contenido = '<div class="body">
            <div class="img"> 
            <img src="/static/img/portal/LogoInterno01.png" width="250px">   
            <h5 class="subtitulo-iz">REPORTE CONSULTA LISTAS RESTRICTIVAS</h5>
                </div>
				<div>
					<h5 class="datos">Bogot&aacute; D.C.,' . $fecha . '</h5>
				</div>
				<div>
					<p class="datos"> Consulta para:<strong> ' . $cliente . '</strong> </p>
					<p class="datos"> Documento: <strong> ' . $documento . '</strong> </p>
					<p class="datos"> ' . $consulta . '</p>
					<table id="data">
						<tr>
							<th>No </th>
                                                        <th>Prioridad </th>
                                                        <th>Tipo documento </th>
                                                        <th>Número documento</th>
                                                        <th>Nombre </th>
                                                        <th>Número tipo lista </th>
                                                        <th>Lista </th>
						</tr>
							' . $contLista . '
					</table>
				</div>
			</div>';
//
//        echo $contenido;
        $dir = 'uploads/riesgo/';

        $date = date('Y-m-d');
        $random = rand(1000, 9999);
        $name = strtolower($date . '-' . $random . '.pdf');
        $file_dir = $dir . $name;
        //$url = 'http://' . $_SERVER['SERVER_ADDR'] . ':' . $_SERVER['SERVER_PORT'] . '/'.$dir . $name;
        //$url = 'http://' . $_SERVER['SERVER_ADDR'] . ':' . $_SERVER['SERVER_PORT'] . '/'.$dir . $name;
        $url = $urlpublica['VALOR_PARAMETRO'] . '/' . $dir . $name;
        $nombre = $file_dir;

        $mpdf = new \Mpdf\Mpdf(['tempDir' => '/var/www/html/mpdf/tmp']);
//        $mpdf = new \Mpdf\Mpdf([
//            'tempDir' => '/var/www/html/mpdf/tmp',
//            'mode' => 'utf-8',
//            'format' => 'A4-L',
//            'default_font_size' => 8,
//            'default_font' => 'Calibri'
//        ]);
        $html = mb_convert_encoding($contenido, 'UTF-8', 'UTF-8');
        $mpdf->WriteHTML($css, \Mpdf\HTMLParserMode::HEADER_CSS);
        $mpdf->WriteHTML($html);
        $mpdf->Output($nombre, 'F');
        echo $url;
    }

    public function info() {
        phpinfo();
    }

    public function consultarListas() {
         
        $entidades = $this->db->query("SELECT pk_ent_codigo, documento, CLITBLTIPDOC_PK_TD_CODIGO,NOMBRE FROM(
select DISTINCT ent.pk_ent_codigo, ent.documento, ent.CLITBLTIPDOC_PK_TD_CODIGO,nvl(ent.razon_social,ent.nombre||' '||ent.apellido) NOMBRE
                                    FROM 
                                    MODCLIUNI.CLITBLENTIDA ent
                                    left JOIN MODCLIUNI.CLITBLEVARIE eva
                                    ON ent.pk_ent_codigo= eva.CLITBLENTIDA_PK_ENT_CODIGO
                                    WHERE eva.CLITBLENTIDA_PK_ENT_CODIGO IS NULL
                                     AND ent.documento is not null
                                     AND ent.CLITBLTIPDOC_PK_TD_CODIGO is not null)
                                     WHERE ROWNUM<100");
        $entidades = $entidades->result_array;

        $url = $this->db->query("select valor_parametro PARAMETRO from modgeneri.gentblpargen where PK_PARGEN_CODIGO = 72 ");
        $url = $url->result_array[0]['PARAMETRO'];
        $pass = $this->db->query("     select MODGENERI.GENPKGCLAGEN.DECRYPT(valor_parametro) PARAMETRO from modgeneri.gentblpargen where PK_PARGEN_CODIGO = 73");
        $pass = $pass->result_array[0]['PARAMETRO'];

        foreach ($entidades as $entidad) {
            log_info('APOLO_ERROR::::::::: CONSULTA ENTIDAD EVALUAR:' . $entidad['DOCUMENTO'].' '.$entidad['NOMBRE']);
            //  $url = $auxurl . '/LoadWSInspektor?Numeiden=' . $entidad['DOCUMENTO'] . '&Nombre=' . $entidad['NOMBRE'] . '&Password=' . $pass;
            // $url = str_replace(' ', '%20', $url);

            $options = array(
                'cache_wsdl' => 0,
                'trace' => 1,
                'stream_context' => stream_context_create(array(
                    'ssl' => array(
                        'verify_peer' => false,
                        'verify_peer_name' => false,
                        'allow_self_signed' => true
                    )
                ))
            );
            $client = new SoapClient($url . '?wsdl', $options);
            $request_param = array();
            $request_param = array(
                "Numeiden" => $entidad['DOCUMENTO'], // dato preferiblemente parametrizado
                "Nombre" => $entidad['NOMBRE'],
                "Password" => $pass,
            );
            try {
                $response_param = $client->LoadWSInspektor($request_param);
                $arrayres = json_decode(json_encode($response_param), True);
                $respuesta = $arrayres["LoadWSInspektorResult"];
                log_info('APOLO_ERROR::::::::: RESULTADO ENTIDAD EVALUAR:' . $respuesta);
                $sql = "BEGIN
                    MODCLIUNI.CLIPKGGENERAL.PRCEVALUARRIESGOAUTOMATICO(PARPKENTIDA=>:PARPKENTIDA
                                     , PARDOCUMENTO=>:PARDOCUMENTO
                                     , PARCLITBLTIPDOC_PK_TD_CODIGO=>:PARCLITBLTIPDOC_PK_TD_CODIGO
                                     , PARXMLRESULTADO=>:PARXMLRESULTADO
                                     ,PARRESULTADO=>:PARRESULTADO
                                     ,PARRESSERVICE=>:PARRESSERVICE
                                     ,PARRESCORRREO=>:PARRESCORRREO);
                    END;";
                $pkentidad = $entidad['PK_ENT_CODIGO'];
                $documento = $entidad['DOCUMENTO'];
                $tipodocumento = $entidad['CLITBLTIPDOC_PK_TD_CODIGO'];
                $conn = $this->db->conn_id;
                $stmt = oci_parse($conn, $sql);
                //TIPO NUMBER INPUT
                oci_bind_by_name($stmt, ':PARPKENTIDA', $pkentidad, 32);
                //TIPO NUMBER INPUT
                oci_bind_by_name($stmt, ':PARDOCUMENTO', $documento, 32);
                //TIPO NUMBER OUTPUT
                oci_bind_by_name($stmt, ':PARCLITBLTIPDOC_PK_TD_CODIGO', $tipodocumento, 32);
                oci_bind_by_name($stmt, ':PARXMLRESULTADO', $respuesta, 100000);
                oci_bind_by_name($stmt, ':PARRESULTADO', $parresultado, 4000);
                oci_bind_by_name($stmt, ':PARRESSERVICE', $parrespuestaservicio, 4000);
                oci_bind_by_name($stmt, ':PARRESCORRREO', $parrespuestacorreo, 4000);

                if (!@oci_execute($stmt)) {
                    $e = oci_error($stmt);
                    var_dump($e);
                }
                echo 'FIN';
            } catch (Exception $e) {
                echo "<h2>Exception Error!</h2>";
                echo $e->getMessage();
            }
        }
    }

}
