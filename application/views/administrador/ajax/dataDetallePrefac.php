<?php
//$entidad = $this->session->userdata("entidad");
$entidad = $_SESSION['entidad'];
$empresa = $entidad['NOMBREEMPRESA']
?>
<table>
    <tr>
        <td>
            <div class="widthcien" >
                <div style="width:35%;float:left; " >
                    <img width="98%" height="98%" src="/static/img/portal/logopas.png">                                             
                </div>
                <div  style="width:64%;border:2px solid black;border-radius:10px;float:right;">
                    <div style="width:100%;float:left;margin-left:5px;margin-top:5px">
                        Cliente: <span class="spanblue"><?= $empresa ?></span><br>
                        NIT: <span class="spanblue"><?= $entidad['DOCUMENTO'] ?></span><br>
                        Direcci&oacute;n: <span class="spanblue"><?= $direccion ?></span><br>
                    </div>
                    <div style="width:60%;float:left;margin-left:5px;">
                        Tel&eacute;fono: <span class="spanblue"><?= $telefono['DATO'] ?></span><br>
                        Ciudad: <span class="spanblue"><?= $ciudad['NOMBRECIUDAD'] . "," . $ciudad['NOMBREPAIS'] ?></span><br>
                        Forma de pago: <span class="spanblue">Contado</span><br>
                    </div>
                    <div style="width:30%;float:right;margin-top:5px;font-size:13px">
                        Orden de pedido<br>
                        <div style="border:2px solid black;border-radius:10px;margin-right:5px;text-align:center">
                            <span style="font-size:20px;Color:#39587f;text-align:center"> NRO- </span>
                        </div>
                    </div>

                </div>
                <div style="width:95%;text-align:right;float:right;margin-top:5px;margin-bottom:5px"><?php echo date('d/m/Y') ?></div>
            </div>
        </td>
    </tr>
    <tr>
        <td>
            <div class="widthcien" >

                <div  style="width:100%;border:2px solid black;border-top-left-radius:10px;border-top-right-radius:10px;border-bottom-left-radius:10px;border-bottom-right-radius:10px">
                    <table style="width:100%;border-collapse:collapse;" >

                        <tr>
                            <td style="text-align:center;border-right:2px solid black;border-bottom: 2px solid black;padding:0px;">CANT</td>
                            <td  style="text-align:center;border-right:2px solid black;border-bottom: 2px solid black;padding:0px;">DESCRIPCION</td>
                            <td  style="text-align:center;border-right:2px solid black;border-bottom: 2px solid black;padding:0px;">VALOR UNITARIO</td>
                            <td style="text-align:center;padding:0px;border-bottom: 2px solid black">TOTAL</td>
                        </tr>
<!--                                <tr>
                            <td colspan="5">Exento de impuetos debido a que son <br>ingresos para terceros</td>
                        </tr>-->
                        <tr>
                            <td colspan=4 style="text-align: center;background-color:#37567E;color:white;font-weight:bold;padding:0px;">
                                <span>Exento de impuesto debido a que son ingresos para terceros</span>
                            </td>
                        </tr>
                        <?php foreach ($dataAbonos as $value) { ?>
                            <tr>
                                <td style="padding-bottom: 5px;text-align:center;border-right:2px solid black;font-size: 14px;" class="datapago"><?= $value['CANTIDAD'] ?></td>
                                <td style="padding-bottom: 5px;text-align:left;border-right:2px solid black;font-size: 14px;" class="datapago"><?= $value['PRODUCTO'] ?></td>
                                <td style="padding-bottom: 5px;text-align:right;border-right:2px solid black;font-size: 14px;" class="datapago">$ <?= number_format($value['VALOR_UNITARIO'], 0, ',', '.') ?></td>
                                <td style="padding-bottom: 5px;text-align:right" class="datapago" id="motocarga">$ <?= number_format($value['VALOR_TOTAL'], 0, ',', '.') ?></td>
                            </tr>
                        <?php } ?>
                        <tr>
                            <td style="padding-bottom: 60px;border-right:2px solid black;" ></td>
                            <td style="padding-bottom: 60px;border-right:2px solid black; font-size: 2.2em; color: #c0c0c0" >Exento de impuetos debido a que son <br>ingresos para terceros</td>
                            <td style="padding-bottom: 60px;border-right:2px solid black;" ></td>
                            <td style="padding-bottom: 60px;" ></td>
                        </tr>
                        <tr style="background-color:#37567E;">
                            <td colspan=4 style="text-align: center;background-color:#37567E;color:white;font-weight:bold;padding:0px;">
                                <span>Ingresos propios</span>
                            </td>
                        </tr>
                        <?php foreach ($dataTarjetas as $value) { ?>
                            <tr>
                                <td style="padding-bottom: 5px;text-align:center;border-right:2px solid black;font-size: 14px;" class="datapago"><?= $value['CANTIDAD'] ?></td>
                                <td style="padding-bottom: 5px;text-align:left;border-right:2px solid black;font-size: 14px;" class="datapago"><?= $value['PRODUCTO'] ?></td>
                                <td style="padding-bottom: 5px;text-align:right;border-right:2px solid black;font-size: 14px;" class="datapago">$ <?= number_format($value['VALOR_UNITARIO'], 0, ',', '.') ?></td>
                                <td style="padding-bottom: 5px;text-align:right;border-right:2px solid black;font-size: 14px;" class="datapago" id="motocarga">$ <?= number_format($value['VALOR_TOTAL'], 0, ',', '.') ?></td>
                            </tr>
                        <?php } ?>
                        <?php foreach ($dataAdmin as $value) { ?>
                            <tr>
                                <td style="padding-bottom: 5px;text-align:center;border-right:2px solid black;font-size: 14px;" class="datapago"><?= $value['CANTIDAD'] ?></td>
                                <td style="padding-bottom: 5px;text-align:left;border-right:2px solid black;font-size: 14px;" class="datapago"><?= $value['PRODUCTO'] ?></td>
                                <td style="padding-bottom: 5px;text-align:right;border-right:2px solid black;font-size: 14px;" class="datapago">$ <?= number_format($value['VALOR_UNITARIO'], 0, ',', '.') ?></td>
                                <td style="padding-bottom: 5px;text-align:right;font-size: 14px;" class="datapago" id="motocarga">$ <?= number_format($value['VALOR_UNITARIO'], 0, ',', '.') ?></td>
                            </tr>
                        <?php } ?>
                        <tr>
                            <td colspan=4 style="border-top:2px solid black;border-right:2px solid black ;text-transform: uppercase;text-align: center" id="valorletras">
                                <?= $ValorLetrasTotal ?>
                            </td>
                        </tr>
                        <tr>
                            <td style="border-top:2px solid black;"></td>
                            <td></td>
                            <td  style="    font-weight: bold;border-top:2px solid black;border-left:2px solid black;border-right:2px solid black ;text-align:right"> Total antes de impuestos $</td>
                            <td style="border-top:2px solid black;text-align:right;" id="tdtotal">$ <?= number_format($ValorTotal, 0, ',', '.') ?></td>
                        </tr>
                    </table>
                </div>

            </div>
        </td>
    </tr>
    <tr>

        <td colspan="5">
            <div style="margin-bottom: 2%"></div>
        </td>   
    </tr>

    <tr >
        <td>
            <div class="widthcien impuestos" >
                <div style="width:53%;float:left;border:2px solid black;border-radius:10px">
                    <table style="width:100%;background-color: #ceb6b6">
                        <tr>
                            <td style="border-right:2px solid black;height: 80px; padding-left: 15px">TOTAL A PAGAR</td>
                            <td style="text-align:center;font-size: 15px;font-weight: bold;" id="totalre">$ <?= number_format($ValorTotal, 0, ',', '.') ?></td>
                        </tr>
                    </table>
                </div>
                <!--                        <div  style="width:45%; border:2px solid black;float:right;margin-top:-2px;border-bottom-right-radius:10px;border-bottom-left-radius:10px;">
                                            <table style="width:100%;border-collapse:collapse;">
                                                <tr>
                                                    <td style="border-right:2px solid black">TOTAL A PAGAR</td>
                                                    <td style="text-align:right;" id="totalre">$0</td>
                                                </tr>
                                            </table>
                
                                        </div> -->
            </div>
        </td>
    </tr>
    <tr>
        <td>
            <div style="width:100%;text-align:center;">
                Transversal 55B # 115A 56 - Pbx: 743 47 00 soporte.cliente@peoplepass.com.co - www.peoplepass.com.co Bogot&aacute; D.C. 
            </div>
        </td>
    </tr>
</table>
<div class="col-md-12">
    <div class="button col-sm-3 " >
        <div class="row linkgenerico" >
            <a  href="/portal/solicitudGestion/generarOrden/<?= $sol ?><?= !empty($pk_preorden_codigo)?'/'.$pk_preorden_codigo:'' ?>" class="spacing">GENERAR</a>
        </div>
    </div>
    <div class="col-sm-1"></div>
    <div class="button col-sm-3 " >
        <div class="row linkgenerico">
            <a  href="/portal/solicitudGestion/generarOrden/<?= $sol ?><?= !empty($pk_preorden_codigo)?'/'.$pk_preorden_codigo:null ?>/101" class="spacing">DESCARGAR</a>
        </div>
    </div>
</div>