<?php

require_once  '../mpdf/vendor/autoload.php';

//datos entidad
$idproceso =$_POST['PROCESO'];
$razonsocial=$_POST['RAZONSOCIAL'];
$documento=$_POST['DOCUMENTO'];
$fecha=$_POST['FECHA'];
$correoempresa=$_POST['CORREOEMPRESA'];
$dirempresa=$_POST['DIREMPRESA'];
$ciudadempresa=$_POST['CIUDADEMPRESA'];
$telmovempresa=$_POST['TELMOVEMPRESA'];
$telfijoempresa=$_POST['TELFIJEMPRESA'];
$fechacreacion=$_POST['FECHACREACION'];
//datos juridicos
$tipoempresa=$_POST['TIPOEMPRESA'];
$tiposociedad=$_POST['TIPOSOCIEDAD'];
$ingreso=$_POST['INGRESO'];
$otroingreso=$_POST['OTROINGRESO'];
$concepto=$_POST['CONCEPTO'];
$totalingreso=$_POST['TOTALINGRESO'];
$egreso=$_POST['EGRESO'];
$activo=$_POST['ACTIVO'];
$totalpasivo=$_POST['PASIVO'];
$patrimonio=$_POST['PATRIMONIO'];
$tiporegimen=$_POST['TIPOREGIMEN'];
$actividadeconomica=$_POST['ACTIVIDAECONOMICA'];
//informacion representante legal
$documentorl=$_POST['DOCUMENTORL'];
$fechanacrl=$_POST['FECHANACRL'];
$correorl=$_POST['CORREORL'];
$direccionrl=$_POST['DIRECCIONRL'];
$ciudad=$_POST['CIUDAD'];
$primernombre=$_POST['PRIMERNOMBRE'];
$segundonombre=$_POST['SEGUNDONOMBRE'];
$primerapellido=$_POST['PRIMERAPELLIDO'];
$segundoapellido=$_POST['SEGUNDOAPELLIDO'];
$nacionalidad=$_POST['NACIONALIDAD'];
$genero=$_POST['GENERO'];
$telmovrl=$_POST['TELMOVRL'];
 
$contenido='<table>
				<tr>DATOS DE LA ENTIDAD</tr>
				<tr>
					<td>Fecha: '.$fecha.'</td>
					<td>Raz&oacute;n Social: '.$razonsocial.' </td>
					<td>Documento: '.$documento.'</td>
					<td>&nbsp;</td>
					<td>Correo Electr&oacute;nico: '.$correoempresa.'</td>
					<td>Direcci&oacute;n : '.$dirempresa.'</td>
					<td>Ciudad: '.$ciudadempresa.'</td>
				</tr>
				<tr>
					<td> Tel&eacute;fono M&oacute;vil: '.$telmovempresa.'</td>
					<td> Tel&eacute;fono Fijo: '.$telfijoempresa.'</td>
					<td> Fecha Creaci&oacute;n: '.$fechacreacion.'</td>
					<td>&nbsp;</td>
					<td> Tipo empresa: '.$tipoempresa.'</td>
					<td> Tipo Sociedad: '.$tiposociedad.'</td>
					<td> R&eacute;gimen: '.$tiporegimen.'</td>
				</tr>
				<tr>
					<td>INFORMACI&OACUTE;N FINANCIERA</td>
				</tr>
				<tr>
					<td>Ingresos: '.$ingreso.'</td>
					<td>Otros Ingresos: '.$otroingreso.'</td>
					<td>Total Ingresos: '.$totalingreso.'</td>
					<td>&nbsp;</td>
					<td>Concepto de Otros Ingresos: '.$concepto.'</td>
					<td>&nbsp;</>
					<td>&nbsp;</>
				</tr>
				<tr>
					<td>Egresos: '.$egreso.'</td>
					<td>Activos: '.$activo.'</td>
					<td>Pasivos: '.$totalpasivo.'</td>
					<td>&nbsp;</>
					<td>Patrimonio: '.$patrimonio.'</>
					<td>&nbsp;</>
					<td>&nbsp;</>
				</tr>
				<tr>
					<td>DATOS DEL REPRESENTANTE LEGAL</td>
				</tr>
				<tr>
					<td>Primer Nombre: '.$primernombre.'</td>
					<td>Segundo Nombre: '.$segundonombre.'</td>
					<td>Primer Apellido: '.$primerapellido.'</td>
					<td>&nbsp;</>
					<td>Segundo Apellido: '.$segundoapellido.'</td>
					<td>Documento: '.$documentorl.'</td>
					<td>Correo Electr&oacute: '.$correorl.'</td>
				</tr>
				<tr>
					<td>Fecha de Nacimiento:'.$fechanacrl.'</td>
					<td>Nacionalidad :'.$nacionalidad.'</td>
					<td>Genero'.$genero.'</td>
				</tr>
			</table>';



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
	'margin_top' => 25,
	'margin_bottom' => 40,
	'margin_left' => 30,
	'margin_right' => 20
	]);
	
$html = mb_convert_encoding($contenido, 'UTF-8', 'UTF-8');



$mpdf->AddPage();
$mpdf->WriteHTML($html);
$mpdf->Output($nombre, 'F');
echo $url;
?>
