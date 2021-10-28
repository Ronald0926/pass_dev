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
        /*background-color:#ceb6b6;*/
        background-color: #c0c0c0;
    }
    .datapago{
        color: #366199;
        font-size: 15px;
    }
    .impuestos{
        /*        position: absolute;*/
        margin-bottom:0%;
        left: 0;
        bottom: 0;
        width: 100%;
        text-align: center;

    }
    #hidden_div {
        display: none;
    }
</style>

<div class=" col-sm-1" > </div>
<div class="container col-lg-10" style=" margin-bottom: 200px; margin-top: -50px;">
    <hr style="border-top: 1px solid #eee0;">
    <h2 class="titulo-iz">Carga Maestra</h2>
    <div class="col-md-3 col-sm-12" >
        <form method="POST">
            <input type="text" placeholder="Ingrese monto a recargar" name="recarga" data-type="currency" id="recarga" style="font-size: 20px;">
            <label style="padding-top: 10px;color:#757575;font-weight: 500;" class="oblique">1. Aqui se realizará la carga de su llave maestra, con la que usted realizara las cargas a sus llaveros.</label>
            <label style="padding-top: 10px;color:#757575;font-weight: 500;" class="oblique">2. A este valor se le recargara el valor negociado para su llave maestra.</label>
            <br>
            <!--            <div class="select" style="font-size: 18px;">
                            <select name="medioPago" id="medioPago" required>
                                <option value="">Seleccionar medio de pago</option>
                                <option value="1">PSE</option>
                                <option value="2">Transferencia electronica</option>
                                <option value="3">Consignación</option>
                                <option value="4">Anticipo</option>
                            </select>
                            <div>Seleccione medio de pago</div>
                        </div>-->
            <div class="select select" style="">
                <select class="select-online required"  name="tipoDocumento" onchange="showDiv(this)" >
                    <option value=""> Seleccione medio de pago</option>
                    
                    <?php
                    foreach ($medioPago as $key => $value) {
                        if ($value['PK_MEDPAG_CODIGO'] == 4 and $pagoanticipo == 1) {
                            ?>
                            <option value="<?= $value['PK_MEDPAG_CODIGO'] ?>"> <?= $value['NOMBRE'] ?></option>
                            <?php
                        } else if ($value['PK_MEDPAG_CODIGO'] != 4) {
                            ?>
                            <option value="<?= $value['PK_MEDPAG_CODIGO'] ?>"> <?= $value['NOMBRE'] ?></option>
                            <?php
                            
                        }
                    }
                    ?>
                </select>
                <div style="margin-right: 2px">
                    Seleccione medio de pago
                </div>
            </div>

            <div class="button col-sm-6 col-sm-push-3">
                <img id="imgpse" style="display:none" width="100%" height="100%" src="/static/img/portal/iconos/pse.png"> 
                <button type="submit" id="PSE"style="display: none" class="spacing" >PAGAR</button>
            </div>
            <br> 
            <div id="hidden_div">
                <label style="text-align: center;color: #095E8F;font-size: 1.2em;font-weight: 500;">Para realizar la transferencia electrónica recuerde que debe consignar a la  cuenta <br> Número : <?= $cuenta[0] ["VALOR_PARAMETRO"] ?></label>           
            </div>
            <br>
        </form>
    </div>
    <div class="col-md-9 col-sm-12 " style="padding-left: 5%">
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
                                NOTA CONTABLE PREPAGO<br>
                                <div style="border:2px solid black;border-radius:10px;margin-right:5px;text-align:center">
                                    <span style="font-size:20px;Color:#39587f;text-align:center"> NTC- </span>
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
                                    <td style="text-align:center;border-right:2px solid black;border-bottom: 2px solid black;padding:0px;">FECHA</td>
                                    <td style="text-align:center;border-right:2px solid black;border-bottom: 2px solid black;padding:0px;">CANT</td>
                                    <td colspan='2' style="text-align:center;border-right:2px solid black;border-bottom: 2px solid black;padding:0px;">DESCRIPCION</td>
                                    <td style="text-align:center;padding:0px;border-bottom: 2px solid black">TOTAL</td>
                                </tr>
                                <!--<tr>
                                    <td colspan=5 style="text-align: center;background-color:#37567E;color:white;font-weight:bold;padding:0px;">
                                        <span>Exento de impuesto debido a que son ingresos para terceros</span>
                                    </td>
                                </tr>-->
                                <tr>
                                    <td style="padding-bottom: 60px;border-right:2px solid black;" class="datapago"><?php echo date('d/m/Y') ?></td>
                                    <td style="padding-bottom: 60px;text-align:center;border-right:2px solid black;" class="datapago">1</td>
                                    <td colspan='2' style="padding-bottom: 60px;text-align:left;border-right:2px solid black;font-size: 20px;" class="datapago">Recarga cuenta maestra</td>
                                    <td style="padding-bottom: 60px;text-align:right" class="datapago" id="motocarga">$0</td>
                                </tr>
                                <tr>
                                    <td style="padding-bottom: 60px;border-right:2px solid black;" ></td>
                                    <td style="padding-bottom: 60px;border-right:2px solid black;" ></td>
                                    <td colspan='2' style="padding-bottom: 60px;border-right:2px solid black; font-size: 2.2em; color: #c0c0c0" >Exento de impuetos debido a que son <br>ingresos para terceros</td>
                                    <td style="padding-bottom: 60px;" ></td>
                                </tr>
                                <!--<tr style="background-color:#37567E;">
                                    <td></td><td></td>
                                    <td style="color:white;font-weight:bold;padding:0px;">
                                        <span>Ingresos Propios</span>
                                    </td>
                                    <td></td><td></td>
                                </tr>-->
                                <tr>
                                    <td colspan=4 style="border-top:2px solid black;border-right:2px solid black ;text-transform: uppercase;" id="valorletras">
                                    </td>
                                </tr>
                                <tr>
                                    <td style="border-top:2px solid black;"></td>
                                    <td></td>
                                    <td></td>
                                    <td  style="    font-weight: bold;border-top:2px solid black;border-left:2px solid black;border-right:2px solid black ;text-align:right"> Total $</td>
                                    <td style="border-top:2px solid black;text-align:right;" id="tdtotal">$0</td>
                                </tr>
                            </table>
                        </div>

                    </div>
                </td>
            </tr>
            <tr>


            </tr>
            <tr>
                <td>
                    <div class="widthcien impuestos" >
                        <div style="width:53%;float:left;border:2px solid black;border-radius:10px">
                            <table style="width:100%;background-color: #ceb6b6">
                                <tr>
                                    <td style="border-right:2px solid black;height: 80px;">TOTAL A PAGAR</td>
                                    <td style="text-align:right;" id="totalre">$0</td>
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
    </div>

</div>
</div><div class="col-sm-1" > </div>
<script>

    function showDiv(element) {
        if (element.value == 2 || element.value == 3) {
            document.getElementById('hidden_div').style.display = 'block';
            document.getElementById('PSE').style.display = 'none';
            document.getElementById('imgpse').style.display = 'none';
        } else if (element.value == 1) {
            document.getElementById('PSE').style.display = 'block';
            document.getElementById('imgpse').style.display = 'block';
            document.getElementById('hidden_div').style.display = 'none';
        } else {
            document.getElementById('PSE').style.display = 'none';
            document.getElementById('hidden_div').style.display = 'none';
            document.getElementById('imgpse').style.display = 'none';
        }
    }
    $('#recarga').on('blur', function () {
        var t = $("#recarga").val();
        $("#motocarga").html(t);
        $("#tdtotal").html(t);
        $("#totalre").html(t);
        var nfor = CifrasEnLetras.dejarSoloCaracteresDeseados(t, "0123456789");
        var nuletras = CifrasEnLetras.convertirCifrasEnLetras(nfor)+' M/CTE';
        $("#valorletras").html(nuletras);
    });


</script>