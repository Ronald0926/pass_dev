<?php

require_once  '../mpdf/vendor/autoload.php';

$fecha =$_POST['FECHA'];
$cliente=$_POST['CLIENTE'];
$datos= $_POST['LISTA'];
$documento= $_POST['DOCUMENTO'];
$consulta=$_POST['CONSULTA'];
$prioridad=$_POST['PRIORIDAD'];


$contenido='<div>
				<div>
					<h5>Bogot&aacute; D.C.,'.$fecha.'</h5>
				</div>
				<div>
					<h4> Consulta para:'.$cliente.'</h4>
					<h5> Documento:'.$documento.'</h5>
					<h5> '.$consulta.'</h5>
					<h5> '.$prioridad.'</h5>
					<table>
						<tr>
							<th>Listas <th>
						</tr>
                                                
						<tr>
							<td>'.$datos.'<td>
						</tr>
					</table>
				</div>
			</div>';
      
      
    $dir = '/var/www/html/uploads/';
    $date = date('Y-m-d');
    $random = rand(1000,9999);
    $name = strtolower($date.'-'.$random.'.pdf');
    $file_dir = $dir .$name;
    $url = 'http://'.$_SERVER['SERVER_ADDR'].':'.$_SERVER['SERVER_PORT'].'/uploads/'.$name;
    $nombre=$file_dir;
		
$mpdf = new \Mpdf\Mpdf(['tempDir' => '../mpdf/tmp']);
$html = mb_convert_encoding($contenido, 'UTF-8', 'UTF-8');
$mpdf->WriteHTML($html);
$mpdf->Output($nombre, 'F');
echo $url;
?>
