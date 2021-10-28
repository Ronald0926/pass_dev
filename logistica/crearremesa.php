<?php
function unique_multidim_array($array, $key) { 
    $temp_array = array(); 
    $i = 0; 
    $key_array = array(); 
    
    foreach($array as $val) { 
        if (!in_array($val[$key], $key_array)) { 
            $key_array[$i] = $val[$key]; 
            $temp_array[$i] = $val; 
        } 
        $i++; 
    } 
    return $temp_array; 
} 

require_once '../mpdf/vendor/autoload.php';

$detalles = explode(";", $_POST['TEXTO']);

$encabezado = explode(',', $detalles[0]);

$encabezados = array();
$recorrido = 0;

foreach ($detalles as $x => $x_value) {
    $content = explode(",", $x_value);
    $encabezados[$recorrido] = array(
		"codigo"=>$content[0],
		"nombre"=>$content[1].' '.$content[2],
		"correo"=>$content[6],
		"telefono"=>$content[4],
		"ciudad"=>$content[5]
	);
	$recorrido++;
}
$encabezados = unique_multidim_array($encabezados,'codigo');
sort($encabezados);


$dir = '/var/www/html/uploads/';
$date = date('Y-m-d');
$random = rand(1000, 9999);
$name = strtolower($date . '-' . $random . '.pdf');
$file_dir = $dir . $name; //.basename($_FILES['file']['name']);
$url = 'http://' . $_SERVER['SERVER_ADDR'] . ':' . $_SERVER['SERVER_PORT'] . '/uploads/' . $name;
$nombre = $file_dir;

$mpdf = new \Mpdf\Mpdf([
	'tempDir' => '../mpdf/tmp', 
	'mode' => 'utf-8', 
	'format' => 'A4', 
	'margin_header' => 10,
	'margin_top' => 100,
	'margin_bottom' => 40
	]);




foreach($encabezados as $remesa){
	
	$contenido = "";
	
	$contenido = $contenido."<table style='width:100%; font-size: 14px;'>
        <tr>
            <td style='width: 100%; vertical-align: top; height: 200px'>
				<img src='header.png' width='100%' />
                <table style='width: 100%;'>
                    <tr>
                        <td>
                            <strong>Señores:</strong>
                        </td>
                        <td style='text-align: right;'> <strong> RMPP-{$remesa['codigo']} </strong> </td>
                    </tr>
                </table>
                <br/>
                <strong>{$remesa['nombre']}</strong>
                <br/>
                {$remesa['correo']}
                <br/>
                {$remesa['telefono']}
                <br/>
                {$remesa['ciudad']}
                <br/>
                <strong>Respetados Señores:</strong>
                <br/>
                <br/>
                Estamos haciendo entrega de las siguientes tarjetas segun su amable solicitud
                <br/>
                <br/>";
					$contenido = $contenido."</td>
            
        </tr>

    </table>";
				
	$contenido2 = '<table width="100%" border="1" cellspacing="0" style="font-size: 12px;">
        <tr>
            <td>
                <strong>Reg</strong>
            </td>
            <td>
                <strong>Nit</strong>
            </td>
            <td>
                <strong>Nombres</strong>
            </td>
            <td>
                <strong>Apellidos</strong>
            </td>
            <td>
                <strong>Identificacion</strong>
            </td>
            <td>
                <strong>Tarjeta</strong>
            </td>
            <td> 
                <strong>Producto</strong>
            </td>
        </tr> 
    ';
	$y = 0;
	foreach ($detalles as $x => $x_value) {
		
		$content = explode(",", $x_value);
		if($remesa['codigo'] == $content[0]){
			$contenido2 = $contenido2."
				<tr>
					<td>".($y + 1)."</td>
					<td>{$content[2]}</td>
					<td>{$content[7]}</td>
					<td>{$content[8]}</td>
					<td>{$content[11]}</td>
					<td>{$content[10]}</td>
					<td>{$content[9]}</td>
				</tr>
				";
			$y++;
		}
	}
	
	$contenido2 = $contenido2."</table>";
	
	$html = mb_convert_encoding($contenido, 'UTF-8', 'UTF-8');
	$mpdf->SetHTMLHeader ($html);
	$mpdf->SetHTMLFooter ("<img src='footer.png' width='100%' >");
	$mpdf->AddPage();
	$mpdf->SetHTMLHeader ("");
    $html2 = mb_convert_encoding($contenido2, 'UTF-8', 'UTF-8');
	$mpdf->WriteHTML($html2);

}

$mpdf->Output($nombre, 'F');
echo $url . '';



