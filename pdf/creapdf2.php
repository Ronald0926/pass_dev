<?php

require_once '../mpdf/vendor/autoload.php';



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
	font-size: 10px;
 }
 td{
	padding-left:5px;
	padding-right:5px;
	padding-top:5px;
	padding-bottom:5px;
 }
";

$contenido2 = '
<div class="div-principal" >


</div>';

$contenido = '
<div class="widthcien" >
	<div style="width:35%;position:absolute;float:left;margin-bottom:30px" >
		<img width="260" height="100" src="Imagenes/logo-nit.png">
	</div>
    <div  style="width:64%;position:absolute;border:2px solid black;border-radius:10px;float:right;">
		<div style="width:64%;position:absolute;float:left;margin:5px;">
			Cliente: <span class="spanblue">EMPRESA COLOMBIA S.A.S</span><br>
			NIT: <span class="spanblue">6969696969-1</span><br>
			Dirección: <span class="spanblue">CRA 123 # 13-45</span><br>
			Teléfono: <span class="spanblue">1234567</span><br>
			Ciudad: <span class="spanblue">BOGOTÁ</span><br>
		</div>
		<div style="width:30%;position:absolute;float:right;margin-top:35px">
			FACTURA DE VENTA<br>
			<div style="border:2px solid black;border-radius:10px;margin-right:5px;text-align:center">
				<span style="font-size:20px;Color:#39587f;text-align:center">2035</span>
			</div>
		</div>

    </div>
    <div  style="width:100%;position:absolute;border:2px solid black;border-top-left-radius:10px;border-top-right-radius:10px;border-bottom-left-radius:10px">
		<table style="width:100%;border-collapse:collapse;" >
			<tr>
				<td style="text-align:center;border-right:2px solid black;padding:0px;">FECHA</td>
				<td style="text-align:center;border-right:2px solid black;padding:0px;">CANT</td>
				<td style="text-align:center;border-right:2px solid black;padding:0px;">DESCRIPCION</td>
				<td style="text-align:center;border-right:2px solid black;padding:0px;">VALOR UNITARIO</td>
				<td style="text-align:center;padding:0px;">TOTAL</td>
			</tr>
			<tr>
				<td colspan=5 style="text-align: center;background-color:#37567E;color:white;font-weight:bold;padding:0px;">
					<span>Exento de impuesto debido a que son ingresos para terceros</span>
				</td>
			</tr>
			<tr>
				<td style="border-right:2px solid black;"></td>
				<td style="text-align:center;border-right:2px solid black;">1</td>
				<td style="text-align:left;border-right:2px solid black;">Abono</td>
				<td style="text-align:right;border-right:2px solid black;">579.715.182</td>
				<td style="text-align:right">579.715.182</td>
			</tr>
			<tr>
				<td style="height:230px;border-right:2px solid black;"></td>
				<td style="height:230px;border-right:2px solid black;"></td>
				<td style="height:230px;border-right:2px solid black;"></td>
				<td style="height:230px;border-right:2px solid black;"></td>
				<td style="height:230px;"></td>
			</tr>
			<tr style="background-color:#37567E;">
				<td></td><td></td>
				<td style="color:white;font-weight:bold;padding:0px;">
					<span>Ingresos Propios</span>
				</td>
				<td></td><td></td>
			</tr>
			<tr>
				<td style="border-right:2px solid black;"></td>
				<td style="text-align:center;border-right:2px solid black;">1</td>
				<td style="text-align:left;border-right:2px solid black;">Servicio de administraci&oacute;n</td>
				<td style="text-align:right;border-right:2px solid black;">20.255.085</td>
				<td style="text-align:right">20.255.085</td>
			</tr>
			<tr>
				<td style="border-right:2px solid black;"></td>
				<td style="text-align:center;border-right:2px solid black;">78</td>
				<td style="text-align:left;border-right:2px solid black;">Tarjetas zafiro plus</td>
				<td style="text-align:right;border-right:2px solid black;">3.700</td>
				<td style="text-align:right">288.600</td>
			</tr>
			<tr>
				<td style="border-right:2px solid black;"></td>
				<td style="text-align:center;border-right:2px solid black;">3</td>
				<td style="text-align:left;border-right:2px solid black;">Correos</td>
				<td style="text-align:right;border-right:2px solid black;">10.000</td>
				<td style="text-align:right">30.000</td>
			</tr>
			<tr>
				<td style="height:230px;border-right:2px solid black;"></td>
				<td style="height:230px;border-right:2px solid black;"></td>
				<td style="height:230px;border-right:2px solid black;"></td>
				<td style="height:230px;border-right:2px solid black;"></td>
				<td style="height:230px;"></td>
			</tr>
			<tr>
				<td colspan=5 style="border-top:2px solid black;font-size:7px">* Esta factura de venta se asimila en todos sus efectos a una letra de cambio (arts. 772 a 774CCo.) Despu&eacute;s de su vencimiento y en caso de no pago
					de las obligaciones aqu&iacute; contenidas, se causar&aacute;n intereses moratorios<br> en los t&eacute;rminos permitidos por la Superintendencia Financiera de Colombia, hasta el d$iacute;aque se verifique su pago.*
				</td>
			</tr>
		</table>
    </div>
	<div style="width:53%;float:left;">
		<div style="width:100%;position:absolute;border:2px solid black;margin-bottom:10px;margin-top:10px;border-radius:10px" >
			<div style="width:50%;font-size:10px;border-right:2px solid black;padding:10px">
			(R&eacute;gimen Com&uacute;n-Responsable de IVA, Actividad econ&oacute;mica 6311Tarifa Ica 9.66X1.000 No somos Grandes Contribuyentes.
							No somos Autorretenedores. Representaci&oacute;n graca de la factura electr&oacute;nica seg&uacute;n par&aacute;grafo 1, articulo 3 decreto 2242 de 2015 Factura Electr&oacute;nica Autorizaci&oacute;n xxxxxx de xx/xx/2019
							vigencia xx meses desde xx hasta xx
			</div>
			<div style="width:50%">
			</div>

		</div>
		<div style="width:100%;position:absolute;border:2px solid black;border-radius:10px;padding:15px;" >

			SON:

		</div>
	</div>
    <div  style="width:45%;position:absolute;border:2px solid black;float:right;margin-top:-2px;border-bottom-right-radius:10px;border-bottom-left-radius:10px;">
		<table style="width:100%;border-collapse:collapse;">
			<tr>
				<td style="border-right:2px solid black;border-bottom:2px solid black;width:50%">SUBTOTAL INGRESOS<BR>PROPIOS</td>
				<td style="border-bottom:2px solid black;text-align:right;width:50%">20.573.685</td>
			</tr>
			<tr>
				<td style="border-right:2px solid black;border-bottom:2px solid black">IVA</td>
				<td style="border-bottom:2px solid black;text-align:right;">3.909.000</td>
			</tr>
			<tr>
				<td style="border-right:2px solid black;border-bottom:2px solid black">RETEFUENTE</td>
				<td style="border-bottom:2px solid black;text-align:right;">818.618</td>
			</tr>
			<tr>
				<td style="border-right:2px solid black;border-bottom:2px solid black">RETEICA</td>
				<td style="border-bottom:2px solid black;text-align:right;">195.755</td>
			</tr>
			<tr>
				<td style="border-right:2px solid black;border-bottom:2px solid black">RETEIVA</td>
				<td style="border-bottom:2px solid black;text-align:right;">586.350</td>
			</tr>
			<tr>
				<td style="border-right:2px solid black;border-bottom:2px solid black">TOTAL INGRESOS PROPIOS</td>
				<td style="border-bottom:2px solid black;text-align:right;">22.881.961</td>
			</tr>
			<tr>
				<td style="border-right:2px solid black;border-bottom:2px solid black">TOTAL INGRESOS PARA<BR>TERCEROS</td>
				<td style="border-bottom:2px solid black;text-align:right;">579.715.182</td>
			</tr>
			<tr>
				<td style="border-right:2px solid black">TOTAL A PAGAR</td>
				<td style="text-align:right;">602.597.143</td>
			</tr>
		</table>

    </div>
	<div style="width:100%;text-align:center;position:absolute;margin-top:10px">
		Transversal 55B # 115A 56 - Pbx: 743 47 00 soporte.cliente@peoplepass.com.co - www.peoplepass.com.co Bogotá D.C.
	</div>
</div>

';

 $dir = '/var/www/html/uploads/';
    $date = date('Y-m-d');
    $random = rand(1000,9999);
    $name = strtolower($date.'-'.$random.'.pdf');
    $file_dir = $dir .$name;
    $url = 'http://'.$_SERVER['SERVER_ADDR'].':'.$_SERVER['SERVER_PORT'].'/uploads/'.$name;
    $nombre=$file_dir;

$mpdf = new \Mpdf\Mpdf([
	'tempDir' => __DIR__ . '/tmp',
	'mode' => 'utf-8',
	'format' => 'A4',
	'margin_header' => 5,
	'margin_top' => 10,
	'margin_bottom' => 0,
	'margin_left' => 10,
	'margin_right' => 10,
  'default_font' => 'Calibri'
	]);
$html = mb_convert_encoding($contenido, 'UTF-8', 'UTF-8');

$mpdf->SetHTMLHeader ('

');

$mpdf->AddPage();
$mpdf->WriteHTML($css,\Mpdf\HTMLParserMode::HEADER_CSS);
$mpdf->WriteHTML($html);
$mpdf->Output($nombre, 'F');
echo $url;
