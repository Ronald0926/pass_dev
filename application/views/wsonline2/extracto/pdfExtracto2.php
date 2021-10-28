<html>

      <head>
            <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
            <title>Orden de Servicio</title>
   <link rel="stylesheet" type="text/css" href="/static/assets/css/bootstrap.min.css'); ?> "/>
<style type="text/css">
    body{
    font-size:10px;
    font-family: 'Poppins-Regular' ;    
    }

    .row {
    margin-left: 0; 
    margin-right: 0; 
}

.row .col-xs-1, .row .col-xs-2, .row .col-xs-3, .row .col-xs-4, .row .col-xs-5, .row .col-xs-6, .row .col-xs-7, .row .col-xs-8, .row .col-xs-9, .row .col-xs-10, .row .col-xs-11, .row .col-xs-12 {
    padding-left: 0;
    padding-right: 0;
}


.responsive {
  width: 100%;
  height: auto;
}

​​​​​​​​​
</style>

     </head>

      <body>

<?php
if ($saldo['PRODUCTO'] == 'BIENESTAR') {
    $TIPOTARJETA = "/static/img/wsonline2/extracto/tarjeta_bienestar.png";
}
if ($saldo['PRODUCTO'] == 'COMBUSTIBLE') {
    $TIPOTARJETA = "/static/img/wsonline2/extracto/tarjeta_combustible.png";
}
if ($saldo['PRODUCTO'] == 'VESTUARIO') {
    $TIPOTARJETA = "/static/img/wsonline2/extracto/tarjeta_vestuario.png";
}
if ($saldo['PRODUCTO'] == 'ZAFIRO') {
    $TIPOTARJETA = "/static/img/wsonline2/extracto/tarjeta_zafiro.png";
}
if ($saldo['PRODUCTO'] == 'ZAFIRO PLUS') {
    $TIPOTARJETA = "/static/img/wsonline2/extracto/tarjeta_zafiro_plus.png";
}
if ($saldo['PRODUCTO'] == 'MARKET') {
    $TIPOTARJETA = "/static/img/wsonline2/extracto/tarjeta_market.png";
}
if ($saldo['PRODUCTO'] == 'BIENESTAR SALUD') {
    $TIPOTARJETA = "/static/img/wsonline2/extracto/tarjeta_bienestar_salud.png";
}
if ($saldo['PRODUCTO'] == 'BUSINESS CAR') {
    $TIPOTARJETA = "/static/img/wsonline2/extracto/tarjeta_business_car.png";
}
if ($saldo['PRODUCTO'] == 'CAJA MENOR') {
    $TIPOTARJETA = "/static/img/wsonline2/extracto/tarjeta_caja_menor.png";
}
if ($saldo['PRODUCTO'] == 'CANASTA') {
    $TIPOTARJETA = "/static/img/wsonline2/extracto/tarjeta_canasta.png";
}
if ($saldo['PRODUCTO'] == 'GASTOS CORPORATIVOS') {
    $TIPOTARJETA = "/static/img/wsonline2/extracto/tarjeta_gastos_corporativos.png";
}
if ($saldo['PRODUCTO'] == 'GASTOS DE REPRESENTACION') {
    $TIPOTARJETA = "/static/img/wsonline2/extracto/tarjeta_gastos_de_representacion.png";
}
if ($saldo['PRODUCTO'] == 'GASTOS DE VIAJE') {
    $TIPOTARJETA = "/static/img/wsonline2/extracto/tarjeta_gastos_de_viaje.png";
}
if ($saldo['PRODUCTO'] == 'MEDIOS DE TRANSPORTE') {
    $TIPOTARJETA = "/static/img/wsonline2/extracto/tarjeta_medios_de_transporte.png";
}
if ($saldo['PRODUCTO'] == 'MESADA') {
    $TIPOTARJETA = "/static/img/wsonline2/extracto/tarjeta_mesada.png";
}
if ($saldo['PRODUCTO'] == 'PREMIO') {
    $TIPOTARJETA = "/static/img/wsonline2/extracto/tarjeta_premio.png";
}
if ($saldo['PRODUCTO'] == 'PREMIO PLUS') {
    $TIPOTARJETA = "/static/img/wsonline2/extracto/tarjeta_premio_plus.png";
}

if ($saldo['PRODUCTO'] == 'BUSINESS CAR') {
    $TIPOTARJETA = "/static/img/wsonline2/extracto/tarjeta_business_car.png";
}

$abonototal = 0;
for ($i = 0; $i < count($movimientos); $i++) {
    if ($movimientos[$i]['ID_TIPO_MOVIMIENTO'] == 8) {
        $abono = $movimientos[$i]['MONTO'];
        $abonototal += $abono;
    }
    
}

$cargostotal = 0;
for ($i = 0; $i < count($movimientos); $i++) {
    if ($movimientos[$i]['ID_TIPO_MOVIMIENTO'] != 8) {
        $cargos = $movimientos[$i]['MONTO'];
        $cargostotal += $cargos;
    }
}

?>



<table class="table">
     <tr>
          

<td  colspan="14"  style="border: 0px solid;
                        margin-left: 15px">

 <img src="/static/img/portal/LogoInterno01.png" width="360px" >                                            
    
 </td> 
 <td colspan="25"></td>                  

                      
                        <td colspan="14"  style="font-weight: bold;
                        text-align: right;
                        border: 0px solid;
                        font-size: 18px">
                        <img src="<?= $TIPOTARJETA ?>" width="360px">
                        </td>
                    </tr>

 </table>


 



 <div class="panel panel-default" style="border-color:black; font-weight: bold;   ">
  
  <div class="panel-body">
  

<table class="table"cellpadding="0" cellspacing="0">
    <tr>
   <td colspan="20">Cliente:</td>
   <td colspan="20">&nbsp;&nbsp;&nbsp;<?= $nombre ?></td>
   <td colspan="20">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
   <td colspan="20">Dirección:</td>
   <td colspan="20">&nbsp;&nbsp;&nbsp;<?= $direccion ?></td>
    <td colspan="20">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
    <td colspan="20">Año:</td>
   <td colspan="20"><?= $partsfecha[0]?></td>
    </tr>
    <tr> <td colspan="20">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td></tr>
      
      <tr>
    <td colspan="20"> Correo:</td>
   <td colspan="20">&nbsp;&nbsp;&nbsp;<?= $correo ?></td>
   <td colspan="20">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
   <td colspan="20">Ciudad:</td>
   <td colspan="20">&nbsp;&nbsp;&nbsp;<?= ucfirst($ciudad) ?></td>
    <td colspan="20">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
    <td colspan="20"> Mes:</td>
   <?php $mesesL=array('01'=>"Enero","02"=>"Febrero","03"=>"Marzo","04"=>"Abril","05"=>"Mayo","06"=>"Junio","07"=>"Julio", "08"=>"Agosto","09"=>"Septiembre","10"=>"Octubre","11"=>"Nombiembre","12"=>"Diciembre" );  ?>

   <td colspan="20">&nbsp;&nbsp;&nbsp;<?= $mesesL["".$partsfecha[1].""]  ?></td>
   
 </tr>
 <tr>  

 
   <td colspan="20">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
   <td colspan="20">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
   <td colspan="20">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
    <td colspan="20">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
  
   
 </tr>
    </table>    
</div>
</div>



  <div class="container"style="margin-left:80px;">
    <div class="row">
        <div class="col-xs-8 col-sm-10 ">
          <div class="panel  panel-primary class"style="border-color:black;font-weight: bold;">
    <div class="panel-heading" style="background-color:#1f4d75; color:#fff;text-align: left;font-size:12 ;">Información de la cuenta</div>
    <div class="panel-body">
    <table class="table">
    <tr>
    <td colspan="20">TARJETA:&nbsp;<?= $saldo['PRODUCTO'] ?> </td> 
   <td colspan="20" >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td> 
  
   <td colspan="20" >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?= $pan_enmascarado ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td> 
   <td colspan="20" >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td> 
   <td colspan="20" >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?= $saldo['ESTADO'] ?></td> 
    </tr>
        
    </table>    

    </div>
  </div>

           <div class="panel  panel-primary class"style="border-color:black;font-weight: bold;">
    <div class="panel-heading" style="background-color:#1f4d75; color:#fff;text-align: left;font-size:12 ;">Resumen de movimientos</div>
    <div class="panel-body">
    <table class="table"cellpadding="0" cellspacing="0">
    <tr>
   <td colspan="20">+Abonos:</td>
   <td colspan="20">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
   <td colspan="20">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
    <td colspan="20">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
     <td colspan="20">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
    <td colspan="20">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
  
   <td colspan="20">&nbsp;$<?= number_format($abonototal)?></td>
    </tr>
    <tr> <td colspan="20">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td></tr>
      <tr>
   <td colspan="20">- Cargos</td>
   <td colspan="20">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
   <td colspan="20">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
   <td colspan="20">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
   <td colspan="20">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
    <td colspan="20">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
  
   <td colspan="20">&nbsp;$<?=number_format( $cargostotal) ?></td>
    </tr>
   
    </table>    

    </div>
  </div>
        </div>
      
      
 <div class="col-xs-2  col-sm-10 " style="width:32% ; height:24%; float:right;" >
          
     <img src="<?= $imghead['URLIMG'] ?>" style="width:100%;height:95%;">
        

    </div>
  </div>

          
        </div>
  

<table class="table">
 <thead style="background-color:#1f4d75; color:#fff;">
  <tr>
<th style="background-color:#1f4d75; color:#fff; border: 1px solid black ;font-size:12 px;text-align: center;"> Tarjeta</th>
<th style="background-color:#1f4d75; color:#fff;border: 1px solid black ;font-size:12 px;text-align: center;" > Fecha</th>
<th style="background-color:#1f4d75; color:#fff;border: 1px solid black ;font-size:12 px;text-align: center;" > Hora</th>
<th style="background-color:#1f4d75; color:#fff;border: 1px solid black ;font-size:12 px;text-align: center;"> Comercio</th>
<th style="background-color:#1f4d75; color:#fff;border: 1px solid black ;font-size:12 px;text-align: center;"> Transacci&oacute;n</th>
<th style="background-color:#1f4d75; color:#fff;border: 1px solid black ;font-size:12 px;text-align: center;"> Valor</th>
<th style="background-color:#1f4d75; color:#fff;border: 1px solid black ;font-size:12 px;text-align: center;"> Respuesta</th>
  </tr>  
 </thead>
 <tbody>


 <?php

for ($i = 0; $i < count($movimientos); $i++) {

?>



                   <tr>
                        <td  style="font-size:11 px; border: 1px solid black;">&nbsp;<?= $movimientos[$i]['CODIGO_TARJETA_ZEUS'] ?></td>
                        <td  style="font-size:11 px; border: 1px solid black;">&nbsp;<?=  $movimientos[$i]['FECHA_TRANSACCION'] ?></td>
                        <td  style="font-size:11 px; border: 1px solid black;">&nbsp;<?= $movimientos[$i]['HORA_TRANSACCION'] ?></td>
                        <td  style="font-size:11 px; border: 1px solid black;">&nbsp;<?= $movimientos[$i]['NOMBRE_COMERCIO'] ?></td>
                        <td  style="font-size:11 px; border: 1px solid black;">&nbsp;<?= $movimientos[$i]['TIPO_MOVIMIENTO'] ?></td>
                        <td  style="font-size:11 px; border: 1px solid black;">&nbsp;$<?= number_format($movimientos[$i]['MONTO']) ?></td>
                        <?php
    if ($movimientos[$i]['NOMBRE_COMERCIO'] == 'NOVEDADMONETARIA') {
?>
                         
    <td  style="text-align: center;font-size:11 px; border: 1px solid black;"><?php
        echo 'Transacción Exitosa.';?></td> 

                         <?php
    } else {
?>
                       <td  style="text-align: center;font-size:11 px; border: 1px solid black;"><?php
        echo $movimientos[$i]['RESPUESTA'];
?></td> 

                         <?php
    }
?>
                       
                    </tr>
                      <?php
}
?>

 </tbody>


</table>





        <div class="col-md-6 col-md-offset-6">
 <img src="<?= $imgFooter['URLIMG'] ?>"  class="img-responsive"width="400" height="300" aling=center>
</div>
</div>
</div>

      </body>
    </html>