<?php

require_once '../mpdf/vendor/autoload.php';


$detalles = explode(";", $_POST['TEXTO']);

$dir = '/var/www/html/uploads/';
$date = date('Y-m-d');
$random = rand(1000, 9999);
$name = strtolower($date . '-' . $random . '.pdf');
$file_dir = $dir . $name;
$url = 'http://' . $_SERVER['SERVER_ADDR'] . ':' . $_SERVER['SERVER_PORT'] . '/uploads/' . $name;
$nombre = $file_dir;


//$mpdf = new \Mpdf\Mpdf(['tempDir' => '../mpdf/tmp']);
$mpdf = new \Mpdf\Mpdf(['tempDir' => '../mpdf/tmp', 'mode' => 'utf-8', 'format' => 'A4-L']);

$contador = 0;
foreach ($detalles as $x => $x_value) {

    $dats = $x_value;
    $content = explode(",", $dats);

    $contador++;
    if ($contador == 1) {
        $data = '
            <table border="0" style="width: 100%; padding-left: 500px" >
        ';
    } else {
        $data = '
            <table border="0" style="width: 100%; padding-left: 500px; margin-top: 50px" >
        ';
    }



    foreach ($content as $y => $y_value) {
        if ($y_value != "")
            $data = $data . "
            <tr>
                <td>
                    - $y_value
                </td>
            </tr>
                ";
    }

    $data = $data . '
        </table>
        ';
    if ($contador == 1) {
        $mpdf->AddPage();
    } else {
        $contador = 0;
    }

    $mpdf->WriteHTML(mb_convert_encoding($data, 'UTF-8', 'UTF-8'));

    $data = "";
}

$mpdf->Output($nombre, 'F');
echo $url . '';

