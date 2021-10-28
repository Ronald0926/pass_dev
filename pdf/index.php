<?php

require_once  '../mpdf/vendor/autoload.php';

$vartexto =$_POST['TEXTO'];

$detalles=explode(";",$vartexto);

foreach ($detalles as $x => $x_value){
$dats= $x_value;
$content = explode(",", $dats);
  $data = $data.'<table border="1"><tr>';
  foreach($content as $y => $y_value ) {
    $data= $data." <td>". $y_value."</td>";
    
  }
  $data = $data.'</tr></table>';
}
$contenido='<div>
				<div>
				<h5>REMESA</h5>
				</div>
				<div>
          '.$data.'
        </div>
			</div>';
 
    $dir = '/var/www/html/uploads/';
    $date = date('Y-m-d');
    $random = rand(1000,9999);
    $name = strtolower($date.'-'.$random.'.pdf');
    $file_dir = $dir .$name;//.basename($_FILES['file']['name']);
    $url = 'http://'.$_SERVER['SERVER_ADDR'].':'.$_SERVER['SERVER_PORT'].'/uploads/'.$name;
    $nombre=$file_dir;
    
$mpdf = new \Mpdf\Mpdf(['tempDir' => '../mpdf/tmp']);
$html = mb_convert_encoding($contenido, 'UTF-8', 'UTF-8');
$mpdf->WriteHTML($html);
$mpdf->Output($nombre, 'F');
echo $url.'';
?>
