<style type="text/css">
    .divContainer {
        height: 38px;  
    }

    .tableContainer {
        height:38px;  
    }
    .manu { 
        font-family: Arial,Helvetica Neue,Helvetica,sans-serif; 
        border-spacing:0;
        font-size: 14px;
    }
</style>
<html>
    <div> 
        <div style="margin:10px" class="">
            <table style="" class="table table-bordered manu" >

                <tr>
                    <td style="width: 38px"></td>
                    <td style="width: 38px"></td>
                    <td style="width: 38px"></td>
                    <td style="width: 38px"></td>
                    <td style="width: 38px"></td>
                    <td style="width: 38px"></td>
                    <td style="width: 38px"></td>
                    <td style="width: 38px"></td>
                    <td style="width: 38px"></td>
                    <td style="width: 38px"></td>
                    <td style="width: 38px"></td>
                    <td style="width: 38px"></td>
                    <td style="width: 38px"></td>
                    <td style="width: 38px"></td>
                    <td style="width: 38px"></td>
                    <td style="width: 38px"></td>
                    <td style="width: 38px"></td>
                    <td style="width: 38px"></td>
                    <td style="width: 38px"></td>
                    <td style="width: 38px"></td>
                    <td style="width: 38px"></td>
                    <td style="width: 38px"></td>
                    <td style="width: 38px"></td>
                    <td style="width: 38px"></td>
                    <td style="width: 38px"></td>
                    <td style="width: 38px"></td>
                    <td style="width: 38px"></td>
                    <td style="width: 38px"></td>
                </tr>
                <tr>
                    <td colspan="14" rowspan="4" style="border: 0px solid;
                        margin-left: 15px">
                        <img src="/static/img/portal/LogoInterno01.png" width="360px">                                             
                    </td>
                    <td colspan="14" rowspan="4" style="font-weight: bold;
                        text-align: right;
                        border: 0px solid;
                        font-size: 18px">
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
                            $TIPOTARJETA = "/static/img/wsonline2/extracto/tarjeta_premio_plus.png";
                        }
                        ?>
                       
                        
                        <img src="<?= $TIPOTARJETA ?>" width="360px">
                         
                    </td>
                </tr>
                <tr>
                    <td style="" colspan="28"></td>
                </tr>
                <tr>
                    <td style="" colspan="28"></td>
                </tr>
                <tr>
                    <td colspan="28" style=""></td>
                </tr>
                <tr>
                    <td colspan="14" style="font-weight: bold; border-left: 2px solid black; border-top: 2px solid black; border-top-left-radius: 10px; border-bottom: 2px solid black; border-bottom-left-radius: 10px;"> 
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        <p style="margin-left: 25px; margin-bottom: 10px; margin-top: 10px;">
                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Cliente: <?= $nombre ?>
                        </p>
                        <br>
                        <p style="margin-left: 25px;
                           margin-bottom: 10px;">
                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Ciudad: <?= $ciudad ?>
                        </p>
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    </td>
                    <td colspan="14" style="font-weight: bold; border-top: 2px solid black; border-bottom: 2px solid black; border-right: 2px solid black; border-bottom-right-radius: 10px; border-top-right-radius: 10px;">
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        <p style="margin-left: 25px; margin-bottom: 10px; margin-top: 10px;">
                            Dirección: <?= $direccion ?>
                        </p>
                        <br>
                        <p style="margin-left: 25px; margin-bottom: 10px;">
                            Correo: <?= $correo ?>
                        </p>
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    </td>
                </tr>
                <tr>
                    <td style="" colspan="28"></td>
                </tr>
                <tr>
                    <td style="" colspan="28"></td>
                </tr>
                <tr>
                    <td style="" colspan="28"></td>
                </tr>
                <tr>
                    <td style="" colspan="28"></td>
                </tr>
                <tr>
                    <td style="" colspan="28"></td>
                </tr>
                <tr>
                    <td style="" colspan="28"></td>
                </tr>
                <tr>
                    <td style="" colspan="28"></td>
                </tr>
                <tr>
                    <td style="" colspan="28"></td>
                </tr>
                <tr>
                    <td style="" colspan="28"></td>
                </tr>
                <tr>
                    <td colspan="19" rowspan="1" style="border: 1px solid black; border-radius: 2px;">
                        <p style="text-align: center; color: #FFFFFF; border: 1px solid gray; background-color: #0E3D64;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Informaci&oacute;n de la cuenta&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</p>
                        &nbsp;&nbsp;
                        <div style="background-color: #FFFFFF;">
                            <p style=" margin-left: 50px; margin-right: 0px;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;TARJETA&nbsp;<?= $saldo['PRODUCTO'] ?>
                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                <?= $movimientos[0]['PAN_ENMASCARADO'] ?>
                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                <?= $saldo['ESTADO'] ?> <?php
                                //if ($datosjuridicos['CLITBLTIPEMP_PK_TIPEMP_CODIGO'] == 45) echo '<img src="/static/img/portal/check.png" width="12px">';
//else echo'<input type="checkbox">'; 
                                ?> 
                            </p>
                        </div>
                        <br>

                        <p style="background-color: #06568C; color: white; border: 1px solid gray; margin-top: 0px;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?= trim($saldo['PRODUCTO'])?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</p>
                        <br>
                        <p style="background-color: #0E3D64; color: #FFFFFF; border: 1px solid gray;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Resumen de movimientos&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</p>
                        <br>
                        <p style="margin-left: 10px;">
                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;+ Abonos&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                            <?php
                            $abonototal=0;
                            for ($i = 0; $i < count($movimientos); $i++) {
                                if ($movimientos[$i]['ID_TIPO_MOVIMIENTO'] == 8) {
                                    $abono = $movimientos[$i]['MONTO'];
                                    $abonototal += $abono;
                                } 
                                
                            }
                            ?>
                            $&nbsp;<?=$abonototal ?>
                        </p>
                        <br>
                        <p style="margin-left: 10px;">
                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;- Cargos&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                            <?php
                            for ($i = 0; $i < count($movimientos); $i++) {
                                if ($movimientos[$i]['ID_TIPO_MOVIMIENTO'] != 8) {
                                    $cargos = $movimientos[$i]['MONTO'];
                                    $cargostotal += $cargos;
                                } 
                            }
                            ?>
                            $&nbsp;<?= $cargostotal ?>
                        </p>
                        <br>
                        <p style="margin-left: 10px; color: #06568C;">
                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Saldo total&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                $&nbsp;<?= $saldo['SALDO'] ?>
                        </p>
                        <br>
                        <br>
                    </td>
                    <td style="" colspan="1"></td>
                    <td colspan="8" style="font-weight: bold;border: 1px solid gray;text-align: center;"><img src="<?= $imghead['URLIMG'] ?>" style="width: 301px; height: 251px;"></td>
                </tr>
                <tr>
                    <td colspan="28">

                    </td>
                </tr>
                <tr>
                    <td style="" colspan="28"></td>
                </tr>
                <tr>
                    <td style="" colspan="28"></td>
                </tr>
                <tr>
                    <td style="" colspan="28"></td>
                </tr>
                <tr>
                    <td style="" colspan="28"></td>
                </tr>
                <tr>
                    <td style="" colspan="28"></td>
                </tr>
                <tr>
                    <td style="" colspan="28"></td>
                </tr>
                <tr>
                    <td style="" colspan="28"></td>
                </tr>
                <tr>
                    <td style="" colspan="28"></td>
                </tr>
                <tr>
                    <td style="" colspan="28"></td>
                </tr>
                <tr>
                    <td style="" colspan="28"></td>
                </tr>
                <tr style="margin-top: 50px;">
                    <td colspan="3" style="font-weight: bold;border: 1px solid gray;text-align: center;color: #FFFFFF;background-color: #0E3D64;font-weight: 80;">Tarjeta</td>
                    <td colspan="3" style="font-weight: bold;border: 1px solid gray;text-align: center;color: #FFFFFF;background-color: #0E3D64;font-weight: 100;">Fecha</td>
                        <td colspan="3" style="font-weight: bold;border: 1px solid gray;text-align: center;color: #FFFFFF;background-color: #0E3D64;font-weight:100;">Hora</td>
                    <td colspan="6" style="font-weight: bold;border: 1px solid gray;text-align: center;color: #FFFFFF;background-color: #0E3D64;font-weight: 300;">Comercio</td>
                    <td colspan="5" style="font-weight: bold;border: 1px solid gray;text-align: center;color: #FFFFFF;background-color: #0E3D64;font-weight: 400;">Transacci&oacute;n</td>
                    <td colspan="5" style="font-weight: bold;border: 1px solid gray;text-align: center;color: #FFFFFF;background-color: #0E3D64;font-weight: 500;">Valor</td>
                    <td colspan="5" style="font-weight: bold;border: 1px solid gray;text-align: center;color: #FFFFFF;background-color: #0E3D64;font-weight: 600;">Respuesta</td>
                </tr>


                <?php
                for ($i = 0; $i < count($movimientos); $i++) {
                    ?>
                    <tr>
                        <td colspan="3" style="border: 1px solid gray; text-align: center;"><?= $movimientos[$i]['CODIGO_TARJETA_ZEUS'] ?></td>
                        <td colspan="3" style="border: 1px solid gray; text-align: center;"><?= $movimientos[$i]['FECHA_TRANSACCION'] ?></td>
                        <td colspan="3" style="border: 1px solid gray; text-align: center;"><?= $movimientos[$i]['HORA_TRANSACCION'] ?></td>
                        <td colspan="6" style="border: 1px solid gray; text-align: center;"><?= $movimientos[$i]['NOMBRE_COMERCIO'] ?></td>
                        <td colspan="5" style="border: 1px solid gray; text-align: center;"><?= $movimientos[$i]['TIPO_MOVIMIENTO'] ?></td>
                        <td colspan="5" style="border: 1px solid gray; text-align: center;">$<?= $movimientos[$i]['MONTO'] ?></td>
                        <?php if($movimientos[$i]['NOMBRE_COMERCIO']=='NOVEDADMONETARIA'){?>
                          
                            <td colspan="5" style="border: 1px solid gray; text-align: center;"><?php  echo 'Transacción Exitosa.' ?></td> 

                         <?php }else {?>
                        <td colspan="5" style="border: 1px solid gray; text-align: center;"><?php  echo $movimientos[$i]['RESPUESTA']; ?></td> 

                         <?php }?>
                        
                    </tr>
                <?php } ?>

                <tr>
                    <td colspan="28">

                    </td>
                </tr>
                <tr>
                    <td style="" colspan="28"></td>
                </tr>
                <tr>
                    <td style="" colspan="28"></td>
                </tr>
                <tr>
                    <td style="" colspan="28"></td>
                </tr>
                <tr>
                    <td style="" colspan="28"></td>
                </tr>
                <tr>
                    <td style="" colspan="28"></td>
                </tr>
                <tr>
                    <td style="" colspan="28"></td>
                </tr>
                <tr>
                    <td style="" colspan="28"></td>
                </tr>
                <tr>
                    <td style="" colspan="28"></td>
                </tr>
                <tr>
                    <td style="" colspan="28"></td>
                </tr>
                <tr>
                    <td style="" colspan="28"></td>
                </tr>
            </table>
            <img src="<?= $imgFooter['URLIMG'] ?>" width="1056px" height="401px">
        </div>
    </div>
</html>