<?php

require_once  '../mpdf/vendor/autoload.php';

$NOMBREPEOPLE=$_POST['NOMBREPEOPLE'];
$NITPEOPLE=$_POST['NITPEOPLE'];
$NUMNOTA=$_POST['NUMNOTA'];
$NOMBRECLIENTE=$_POST['NOMBRECLIENTE'];
$NITCLIENTE=$_POST['NITCLIENTE'];
$DIRECIONCLI=$_POST['DIRECCIONCLI'];
$TELEFONOCLI=$_POST['TELEFONOCLI'];
$FECHANOTA=$_POST['FECHANOTA'];
$CIUDADCLI=$_POST['CIUDADCLI'];
$DETALLE=$_POST['DETALLE'];
$SUBTOTAL=$POST['SUBTOTAL'];
$COSTOOPER=$_POST['COSTOOPER'];
$IVACOSTOOPER=$_POST['IVACOSTOOPER'];
$TOTALNOTA=$_POST['TOTALNOTA'];
$REGCONTABLE=$POST['REGCONTABLE'];

$detalles=explode(";",$DETALLE);

foreach ($detalles as $x => $x_value){
$dats= $x_value;
$content = explode(",", $dats);
  $data = $data.'<tr>';
  foreach($content as $y => $y_value ) {
    $data= $data." <td>". number_format($y_value)."</td>";
  }
  $data = $data.'</tr>';

}


$contenido='<h1>'.$NOMBREPEOPLE.'</h1>
<table style="width:100%; border:1px;">
  <tr>
    <td>NIT'.$NITPEOPLE.'</td>
    <td></td>
    <td style="text-align: right;""> NOTA CREDITO N: '.$NUMNOTA.'</td>
  </tr>
</table>
<table style="width:100%; border: 1px solid black;">
  <tr>
    <td>NIT'.$NITPEOPLE.'</td>
    <td></td>
    <td style="text-align: right;""> NOTA CREDITO N: '.$NUMNOTA.'</td>
  </tr>
</table>
<table style="width:100%; border: 1px solid black; text-align: left;">
  <tr >
    <td>CLIENTE:'.$NOMBRECLIENTE.'</td>
    <td>FECHA</td>
    <td>'.$FECHANOTA.'</td>
  </tr>
  <tr>
    <td>NIT '.$NITCLIENTE.'</td>
    <td>ANT</td>
    <td> </td>
  </tr>
  <tr>
    <td>DIRECCION '.$DIRECIONCLI.' </td>
    <td>N� FACTURA CMA</td>
    <td> </td>
  </tr>
  <tr>
    <td>TELEFONO '.$TELEFONOCLI.'</td>
    <td>CIUDAD</td>
    <td>'.$CIUDADCLI.'</td>
  </tr>

</table>
<table style="width:100%; height:50%; border: 1px solid black; text-align: left;">
  <tr style="background:white; height:20px" >
    <td > </td>
    <td> </td>
    <td> </td>
  </tr>
  <tr>
    <th>DETALLE</th>
    <th>CANTIDA</th>
    <th>VALOR</th>
  </tr>
  '.$data.'
</table>

    <table style="width:100%; border: 1px solid black; text-align: center;">
      <tr style="background:white; height:20px" >
        <td > </td>
        <td> </td>
        <td> </td>
      </tr>
      <tr>
        <td>valor en letras</td>
        <td>
          <table style="width:100%; text-align: center;">
          <tr>
            <td>SUBTOTAL NOTA</td>
          </tr>
          <tr>
           <td> Costo Operativo</td>
          </tr>
          <tr>
           <td> Iva Costo Operativo</td>
          </tr>
          <tr style=" height:30px">
           <td>  </td>
          </tr>
          <tr>
           <td> VALOR TOTAL</td>
          </tr>
        </table>
      </td>
        <td><table style="width:100%; text-align: center;">
        <tr>
          <td>'.str_replace(',', '.', $SUBTOTAL).'</td>
        </tr>
        <tr>
         <td> '.$COSTOOPER.'</td>
        </tr>
        <tr>
         <td> '.$IVACOSTOOPER.'</td>
        </tr>
        <tr style=" height:30px">
         <td>  </td>
        </tr>
        <tr>
         <td>  '.$TOTALNOTA.'</td>
        </tr>
      </table></td>
      </tr>
    </table>

<table style="width:100%; border: 1px solid black; text-align: center;">
  <tr style=" height:30px">
    <td>'.$REGCONTABLE.'</td>
    <td></td>
    <td></td>
  </tr>
  <tr style=" height:30px">
    <td> </td>
    <td></td>
    <td></td>
  </tr>
<tr style=" height:30px">
  <td>Registro Contabilidad</td>
  <td>Aprobo</td>
  <td>Elaboro</td>
</tr>
</table>';

$dir = '/var/www/html/uploads/';
    $date = date('Y-m-d');
    $random = rand(1000,9999);
    $name = strtolower($date.'-'.$random.'.pdf');
    $file_dir = $dir .$name;
    $url = 'http://'.$_SERVER['SERVER_ADDR'].':'.$_SERVER['SERVER_PORT'].'/uploads/'.$name;
    $nombre=$file_dir;

$mpdf = new \Mpdf\Mpdf(['tempDir' => '../mpdf/tmp']);
$mpdf->WriteHTML(utf8_encode($contenido));
$mpdf->Output($nombre, 'F');
echo $url;
