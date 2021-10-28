<?php

ini_set("pcre.backtrack_limit", "5000000");
if (!defined('BASEPATH'))
	exit('No direct script access allowed');

class NotaCredito extends CI_Controller
{

	public function __construct()
	{
		parent::__construct();
	}

	public function __destruct()
	{
		$this->db->close();
	}
	public function crear($notaCredito = 0)
	{
		require_once 'mpdf/vendor/autoload.php';		
		if ($_POST['NUMERO_NOTA']) {			
			$notaCredito = $_POST['NUMERO_NOTA'];
		}
		
		$Clientenota = $this->db->query("SELECT NOMBRE_CLIENTE,
                NIT_CLIENTE,
                replace(DIRECCION_CLIENTE,'||',' '),
                TELEFONO,
                FECHA,
                NUMERO_FACTURA,
                CIUDAD,
                modfactur.facpkgactualizaciones.fncvalortotalnota($notaCredito) TOTAL
                FROM MODFACTUR.FACTBLNOTA nota
                JOIN MODFACTUR.FACTBLFACTUR factur
                ON nota.pk_factur_codigo=factur.pk_factur_codigo
                and nota.pk_nota_codigo=$notaCredito");


		$Clientenota = $Clientenota->result_array[0];
		//var_dump($ClienteFactura);
		
		$detallenota = $this->db->query("select   cantidad CANTIDAD,
                connot.nombre ||' '||lower(detalle) DESCRIPCION,
                valor_total VALORES
                from MODFACTUR.factbldetnot detnot
                JOIN MODFACTUR.factblconnot connot
                ON detnot.pk_concepto_codigo=connot.pk_connot_codigo
                where pk_nota_codigo = $notaCredito
                UNION ALL
                select 1 CANTIDAD,
                'Anulaci&oacute;n cobro de '|| lower(IMPUESTO) ||' '||PROCENTAJE,
                SUM(VALOR_DEVUELTO) VALORES
                from modfactur.factblimpnotcre impuesto
                JOIN modfactur.factbldetnot detnot
                ON impuesto.pk_detnot_codigo=detnot.pk_detnot_codigo
                where detnot.pk_nota_codigo=$notaCredito
                GROUP BY 1,'Anulaci&oacute;n cobro de '|| lower(IMPUESTO) ||' '||PROCENTAJE");
		$detallenota = $detallenota->result_array;


		$numdetalles = 0;
		//var_dump($detallePropios);
		$total_nc = 0;
		foreach ($detallenota as $value) {
			$descripcion = $descripcion . '<tr class="spanblue">
                        <td style="border-right:2px solid black;" class="spanblue">' . number_format($value['CANTIDAD']) . '</td>
                        <td style="text-align:left;border-right:2px solid black;" class="spanblue">' . ucfirst($value['DESCRIPCION']) . '</td>
                        <td style="text-align:center; solid black;" class="spanblue">' . number_format($value['VALORES']) . '</td>
                        </tr> ';
			$numdetalles = $numdetalles + 1;
			$total_nc +=$value['VALORES'];
		}
		$valorletras = $this->numeros_letras($total_nc);
		if ($numdetalles < 20) {
			for ($i = 0; $i < 20 - $numdetalles; $i++) {
				$descripcion = $descripcion . '<tr >
                        <td style="border-right:2px solid black;" class="spanwhite">27/09/2019</td>
                        <td style="text-align:center;border-right:2px solid black;" class="spanwhite"></td>
                        <td style="text-align:left;solid black;" class="spanwhite">T</td>
                        </tr> ';
			}
		}
		


	$css = "
		body {

			background-size: 50;
			background-position: bottom left;
			background-repeat: no-repeat;
			background-image-resize: 4;
			background-image-resolution: from-image;
			text-align: justify;
			font-size: 13px;
			font-family: Arial;
		}
		.div-principal{
			width: 842px;
			height: 1191px;
		}
		.widthcien{
			width:100%;
		}	
		.tabla-principal{
			margin:5px;
			width:100%;
			border-radius:10px;
			border:2px solid;
		}
		.spanblue{
			color:#39587f;
			font-size: 15px;
		}
		.spanwhite{
			color:white;
			font-size: 15px;
		}
		td{
			padding-left:5px;
			padding-right:5px;
			padding-top:5px;
			padding-bottom:5px;
		}
		.codeqr{
			-webkit-print-color-adjust: exact;
			background-color:#ceb6b6;
		}
		.impuestos{
			position: absolute;
			margin-bottom:0%;
			left: 0;
			bottom: 0;
			width: 100%;
			text-align: center;

		}
		";

	$header = '
				<div class="widthcien" >
					<div style="width:65%;position:absolute;float:left;margin-bottom:0px" >
						<img width="100%" height="100px" src="static/img/wsonline2/logo-nit2.png">                                             
					</div>
					<div  style="width:30%;position:absolute;float:right;">
						<div style="width:30%;position:absolute;float:right;margin-top:5px">
							
							<div style="text-align:center">NOTA CREDITO<br>
								<span style="font-size:20px;Color:#39587f;text-align:center">' . $notaCredito . '</span>
							</div>
						</div>
					</div>
                    <br>
                    <div  style="width:100%;position:absolute;border:2px solid black;border-radius:10px;">
						<div style="width:50%;position:absolute;float:left;margin-left:5px;margin-top:5px">
							Cliente: <span class="spanblue">' . $Clientenota['NOMBRE_CLIENTE'] . '</span><br>
							NIT: <span class="spanblue">' . $Clientenota['NIT_CLIENTE'] . '</span><br>
							Direcci&oacute;n: <span class="spanblue">' . $Clientenota['DIRECCION_CLIENTE'] . '</span><br>
                                                        Tel&eacute;fono: <span class="spanblue">' . $Clientenota['TELEFONO'] . '</span><br>
						</div>
						<div style="width:49%;position:absolute;float:right;margin-left:5px;">
							Fecha: <span class="spanblue">' . $Clientenota['FECHA'] . '</span><br>
							No. Factura: <span class="spanblue">' . $Clientenota['NUMERO_FACTURA'] . '</span><br>
							Ciudad: <span class="spanblue">' . $Clientenota['CIUDAD'] . '</span><br>
						</div>
					</div><br>
					<span>Documento que Modifica</span>
					<br><br>
					<div  style="width:100%; border:2px solid black;float:right;margin-top:-2px;border-radius:10px;">
						<table style="width:100%;border-collapse:collapse;">
							<tr>
								<td style="border-right:2px solid black;solid black;width:50%">Factura de venta</td>
								<td style="text-align:right;width:40%" > No. Factura: <span class="spanblue">' . $Clientenota['NUMERO_FACTURA']  . '</span></td>
							</tr>
						</table>
					</div>					
				</div>
				';

		$contenido = '
		<div class="widthcien" style="padding-top:18%">	
			<div  style="width:100%;position:absolute;border:2px solid black;border-top-left-radius:10px;border-top-right-radius:10px;">
				<table style="width:100%;border-collapse:collapse;" >
					<tr style="text-align: center;background-color:#37567E;color:white;font-weight:bold;padding:0px;">						
						<td style="text-align:center;border-right:2px solid black;padding:0px;">CANTIDAD</td>
						<td style="text-align:center;border-right:2px solid black;padding:0px;">DESCRIPCI&Oacute;N</td>
						<td style="text-align:center; solid black;padding:0px;">VALORES</td>
						
					</tr>
					' . $descripcion . '					
				</table>
			</div>
			<div  style="width:100%; position:absolute;border:2px solid black;float:right;margin-top:-2px;border-bottom-right-radius:10px;border-bottom-left-radius:10px;">
				<table style="width:100%;border-collapse:collapse;">
					<tr>
						<td style="border-right:2px solid black;solid black;width:50%">Valor en letras: ' . ucfirst($valorletras) . ' Pesos M/cte</td>
						<td style="width:10%">TOTAL: </td>
						<td style="text-align:right;width:40%">' . number_format($total_nc) . '</td>
					</tr>
				</table>
			</div>
		</div>';

		$footer = '
				<div style="width:100%">
					<table style="width:100%;text-align:center;">
							<tr>
								<td>ELABORO</td>
								<td>PROBADO</td>
								<td>ACEPTADO</td>
							</tr>
					</table>
					<span>Transversal 55B # 115A 56 - Pbx: 743 47 00 soporte.cliente@peoplepass.com.co - www.peoplepass.com.co Bogot&aacute; D.C.</span> 
				</div> ';

		//$dir = '/var/www/html/uploads/';
		
		$dir = 'uploads/';
		$date = date('Y-m-d');
		$random = rand(1000, 9999);
		$name = strtolower($date . '-' . $random . '.pdf');
		$file_dir = $dir . $name;
		$url = 'http://' . $_SERVER['SERVER_ADDR'] . ':' . $_SERVER['SERVER_PORT'] . '/uploads/' . $name;
		//$url = 'http://localhost:' . $_SERVER['SERVER_PORT'] . '/uploads/' . $name;
		$nombre = $file_dir;
		
		$mpdf = new \Mpdf\Mpdf([
			'tempDir' => 'mpdf/tmp',
			'mode' => 'utf-8',
			'format' => 'A4',
			'margin_header' => 10,
			'margin_footer' => 10,
			'margin_top' => 52,
			'margin_bottom' => 5,
			'margin_left' => 10,
			'margin_right' => 10,
			'default_font' => 'Calibri'
		]);
		
		$html = mb_convert_encoding($contenido, 'UTF-8', 'UTF-8');

		$mpdf->SetHTMLHeader($header);
		$mpdf->SetHTMLFooter($footer);

		$mpdf->AddPage();
		$mpdf->WriteHTML($css, \Mpdf\HTMLParserMode::HEADER_CSS);
		$mpdf->WriteHTML($html);
		$mpdf->Output($nombre, 'F');
		$this->output->set_content_type('text/css');
        $this->output->set_output($url);
	}

	public function numeros_letras($valor)
	{

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
			/*VAR_DUMP($e);*/
			/*exit;*/
		}
		return $valorletras;
	}
}
