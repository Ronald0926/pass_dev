@@ -0,0 +1,148 @@
<html>
    <head>
        <style>
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
                font-size: 15px;
            }
            .spanblue2{
                font-size: 12px;
            }
            .spanwhite{
                color:white;
                font-size: 15px;
            }
            td{
                padding-left:5px;
                padding-right:5px;
                padding-top:5px;
                padding-bottom:5px;
            }
            .codeqr{
                -webkit-print-color-adjust: exact;
                background-color:#ceb6b6;
            }
            .impuestos{
                position: absolute;
                margin-bottom:0%;
                left: 0;
                bottom: 0;
                width: 90%;
                text-align: center;
                margin-right: 34px;
                margin-left: 38px;
            }
        </style>
    </head>
    <body>
        <div class="widthcien" >
            <div style="width:35%;position:absolute;float:left;margin-bottom:0px" >
                <img width="260" height="100" src="static/img/wsonline2/logo-nit.png">                                             
            </div>
            <div  style="width:64%;position:absolute;border:2px solid black;border-radius:10px;float:right;">
                <div style="width:100%;float:left;margin-left:5px;margin-top:5px">
                    Cliente: <span class="spanblue"><?= $nombre_cliente ?></span><br>
                    NIT: <span class="spanblue"><?= $nit_cliente ?></span><br>
                    Direcci&oacute;n: <span class="spanblue"><?= strtoupper($direccion_cliente) ?></span><br>
                </div>
                <div style="width:60%;float:left;margin-left:5px;">
                    Tel&eacute;fono: <span class="spanblue"><?= $telefono_cliente ?></span><br>
                    Ciudad: <span class="spanblue"><?= $ciudad_cliente ?></span><br>
                    <br>
                </div>
                <div style="width:30%;float:right;margin-top:5px;font-size:13px">
                    Nota contable<br>
                    <div style="border:2px solid black;border-radius:10px;margin-right:5px;text-align:center">
                        <span style="font-size:20px;Color:#39587f;text-align:center">  <?= 'NTC' . '-' . $numero_nota ?></span>
                    </div>
                </div>

            </div>
            <div style="width:95%;text-align:right;float:right;margin-top:5px;margin-bottom:5px">Fecha creaci&oacute;n <?= $fecha_nota ?></div>
        </div>
        <div class="widthcien" >

            <div  style="width:100%;border:2px solid black;border-top-left-radius:10px;border-top-right-radius:10px;border-bottom-left-radius:10px;border-bottom-right-radius:10px">
                <table style="width:100%;border-collapse:collapse;" >

                    <tr>
                        <td  style="text-align:center;border-right:2px solid black;border-bottom: 2px solid black;padding:0px;">DESCRIPCION</td>
                        <td  style="text-align:center;border-right:2px solid black;border-bottom: 2px solid black;padding:0px;">CANTIDAD</td>
                        <td style="text-align:center;padding:0px;border-bottom: 2px solid black">TOTAL</td>
                    </tr>
                    <?php foreach ($detalles as $value) { ?>
                        <tr>
                            <td style="padding-bottom: 5px;text-align:left;border-right:2px solid black;" class="spanblue2"><?= ucfirst($value['PRODUCTO']) ?></td>
                            <td style="padding-bottom: 5px;text-align:right;border-right:2px solid black;" class="spanblue2"> <?= ucfirst($value['CANTIDAD']) ?></td>
                            <td style="padding-bottom: 5px;text-align:right" class="spanblue2" id="motocarga">$ <?= number_format($value['VALOR'], 0, ',', '.') ?></td>
                        </tr>
                    <?php } ?>
                    <tr>
						<td style="padding-bottom: 5px;text-align:left;border-right:2px solid black;" class="spanblue2"><?= ucfirst('COBRO 4X1000/REVERSO') ?></td>
                        <td style="padding-bottom: 5px;text-align:right;border-right:2px solid black;" class="spanblue2"> <?= ucfirst('1') ?></td>
                        <td style="padding-bottom: 5px;text-align:right;border-right:2px solid black;" class="spanblue2">$<?= number_format($VALOR_4x1000, 0, ',', '.') ?></td>
                    </tr>
                    <?php for ($i = 0; $i <= 5; $i++) { ?>
                        <tr>
                            <td style="padding-bottom: 5px;text-align:left;border-right:2px solid black;color: #FFF" class="spanblue2">ok</td>
                            <td style="padding-bottom: 5px;text-align:right;border-right:2px solid black;color: #FFF" class="spanblue2"> ok</td>
                            <td style="padding-bottom: 5px;text-align:right;color: #FFF" class="spanblue2" id="motocarga">ok</td>
                        </tr>
                    <?php } ?>           
                </table>
            </div>
        </div>
        <div class="widthcien" >
            <div style="width:53%;float:left;margin-top:15px;border:2px solid black;border-radius:10px">
                <table style="width:100%;background-color: #ceb6b6">
                    <tr>
                        <td style="border-right:2px solid black;height: 80px; padding-left: 15px">TOTAL  NOTA <?= strtoupper($numero_letras) ?></td>
                    </tr>
                </table>
            </div>
            <div style="width:40%;float:right;border:2px solid black;border-radius:10px">
                <table style="width:100%;">
                    <tr>
                        <td style="border-right:2px solid black; padding-left: 15px" >SUBTOTAL</td>
                        <td style="text-align:center;font-size: 15px;" id="totalre">$ <?= number_format($subtotal-$VALOR_4x1000, 0, ',', '.') ?></td>
                    </tr>
                    <tr>
                        <td style="border-right:2px solid black;padding-left: 15px">COSTO OPERATIVO</td>
                        <td style="text-align:center;font-size: 15px;" id="totalre">$ <?= number_format($costo_operativo, 0, ',', '.') ?></td>
                    </tr>
                    <tr>
                        <td style="border-right:2px solid black;  padding-left: 15px">IVA COSTO OPERATIVO</td>
                        <td style="text-align:center;font-size: 15px;" id="totalre">$ <?= number_format($iva_costo, 0, ',', '.') ?></td>
                    </tr>
                    <tr>
                        <td style="border-right:2px solid black; padding-left: 15px">TOTAL</td>
                        <td style="text-align:center;font-size: 15px;" id="totalre">$ <?= number_format($total_nota, 0, ',', '.') ?></td>
                    </tr>
                </table>
            </div>
        </div>
    </body>
</html>