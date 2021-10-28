<style>
    #hidden_div {
        display: none;
    }
</style>
<?php 
//$rol = $this->session->userdata("rol"); 
$rol = $_SESSION['rol']; 
?>
<div class="loader" hidden></div>
<div style="margin-top: 30px; margin-bottom: 40%;padding: 50px">
    <div style="padding-left:  1%">
        <h2 style="color: #1C5394; padding-left:  1%;">FACTURAS PARA PAGO</h2>
        <br/>
        <br/>
        <div id="checks" class="col-lg-3">
            <form action=""  id="formularioOrdenes" method="POST" >

                <table>
                    <tr>
                        <td>
                            FACTURAS A PAGAR: 
                            <?php foreach ($ordenes as $key => $value) { ?>
                                <input name="ordenes[]" type="hidden" value="<?= $value['CODIGOORDEN'] ?> ">
                                <span style="color: #1C5394;font-weight:bold;" ><?= $value['NUMERO_FACTURA'] ?></span>
                                <br>
                            <?php } ?>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <p><strong>Total CMA: </strong><span id="totalPMA" >0</span></p>
                            <p><strong>Total CCO: </strong><span id="totalPCO" >0</span></p>
                            <p><strong>Total: </strong> <span id="totalF" >0</span></p>
                        </td>
                    </tr>  
                </table>
                <h3>Total a pagar</h3>
                <div style="    font-size: 30px;color: #1c5394"id="totalF1">0</div>
                <br>
                <div class="select select" style="width: 55%">
                    <select class="select-online required"  name="tipoDocumento" onchange="showDiv(this)" >
                        <option value=""> Seleccione medio de pago</option>
                        <?php
                        foreach ($medioPago as $key => $value) {
                            //print_r($medioPago);
                            if ($value['PK_MEDPAG_CODIGO'] == 4   && $pagoanticipo == 1 && $rol==58) {
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
                <br>
                <div class="button">
                    <button type="button" id="PSE"style="width: 55%;display: none" onclick="
                            $('#formularioOrdenes').attr('action', '/portal/pagos/pagar');
                            $('#formularioOrdenes').submit();
                            ">PSE</button>
                </div>
                <br>

            </form>
            <div class="button " id="checks" >
                <button type="submit" id="ANTICIPO"style="width: 55%;display: none;" onclick="mymodal();">PAGAR</button>
            </div>
        </div>
        <div class="col-lg-9" style="margin-left: -12%;">          
            <div class="col-lg-3">
                <div class="container">
                    <div class="grid" style="margin: 2%;">
                        <div id="hidden_div">
                            <p style="color: #1c5394"><strong>Recuerde!<br>
                                    Para realizar transferencia debe tener el número de cuenta </strong></p>
                            <p style="color: #88898a"><strong> Cuenta #: <?= $cuenta[0] ["VALOR_PARAMETRO"] ?> </strong> </p>
                        </div>
                        <?php foreach ($ordenes as $key => $codordact) { ?>
                            <h3 class="subtitulo-iz">Número de factura: <?= $codordact['NUMERO_FACTURA'] ?></h3>
                            <div id="TAR<?= $codordact['CODIGOORDEN'] ?>" name="TAR<?= $codordact['CODIGOORDEN'] ?>"> 

                                <table class="table table-hover " >
                                    <thead>
                                        <tr>
                                            <th> Producto </th>
                                            <th> Valor Unitario</th>
                                            <th> Cantidad </th>
                                            <th> Sub Total </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php $total1 = 0; ?>
                                        <?php foreach ($dettar as $dettaract) { ?>
                                            <?php if ($dettaract['ORDCOM'] == $codordact['CODIGOORDEN']) {
                                                ?>
                                                <tr class="gradeC">
                                                    <td><?= $dettaract['PRODUCTO'] ?></td>  
                                                    <td>$ <?= number_format($dettaract['VALOR_UNITARIO']) ?></td>
                                                    <td><?= $dettaract['CANTIDAD'] ?></td>
                                                    <td>$ <?= number_format($dettaract['SUB_TOTAL']) ?><?php $total1 += $dettaract['SUB_TOTAL']; ?></td>
                                                </tr>
                                            <?php } ?>
                                        <?php } ?>

                                        <tr class="">
                                            <td><b>TOTAL</b></td>  
                                            <td></td>
                                            <td></td>
                                            <td>$ <?= number_format($total1) ?></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div ID="RES<?= $codordact['CODIGOORDEN'] ?>">
                                <p>RESUMEN</p>
                                <table class="table table-hover ">
                                    <thead>
                                        <tr>
                                            <th> Cantidad </th>
                                            <th> Producto </th>
                                            <th> Total </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php $total2 = 0; ?>
                                        <?php foreach ($detord as $key => $detordact) { ?>
                                            <?php if ($detordact['ORDCOM'] == $codordact['CODIGOORDEN']) {
                                                ?>
                                                <tr>
                                                    <td><?= $detordact['CANTID'] ?></td>
                                                    <td><?= $detordact['NOMPRO'] ?></td>
                                                    <td>$ <?= number_format($detordact['SUBTOT']) ?><?php $total2 += $detordact['SUBTOT']; ?></td>
                                                </tr> <?php } ?>
                                        <?php } ?>
                                        <tr>
                                            <td><b>TOTAL</b></td>
                                            <td></td>
                                            <td>$ <?= number_format($total2) ?></td>
                                        </tr>
                                    </tbody>
                                </table>                
                            </div>

                            <div ID="IMP<?= $codordact['CODIGOORDEN'] ?>" >
                                <p>Impuestos</p>
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th> Impuesto </th>
                                            <th> Valor </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php $total3 = 0; ?>
                                        <?php foreach ($impues as $key => $impuesact) { ?>
                                            <?php if ($impuesact['ORDCOM'] == $codordact['CODIGOORDEN']) {
                                                ?>
                                                <tr>
                                                    <td><?= $impuesact['NOMIMP'] ?></td>
                                                    <td>$ <?= number_format($impuesact['VALIMP']) ?><?php $total3 += $impuesact['VALIMP']; ?></td>
                                                </tr> <?php } ?>
                                        <?php } ?>
                                        <tr>
                                            <td><b>TOTAL</b></td>
                                            <td>$ <?= number_format($total3) ?></td>
                                        </tr> 
                                    </tbody>
                                </table>
                            </div>
                        <?php } ?>
                    </div>    
                </div> 
            </div>
        </div>
    </div>
</div>


<!-- Modal confirmacion pago con anticipo-->
<div class="modal fade" id="ModalPagoAnticipo" role="dialog" style="margin-top: 15%;"  data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content" style="border-radius:35px">

            <div class="modal-body" style="text-align: center;height: auto;">

                <label style="font-size: 18px;color: #366199;font-weight: bold;">¿Desea continuar?</label>

                <p style="font-size:18px;color:#888686;font-weight: bold">Recuerde por favor que si tienen una factura en mora no podrá utilizar el servicio de anticipo.</p>

                <div style=" margin-bottom: 3em">
                    <div class="button col-sm-6" >
                        <button type="button" name="ACEPTAR" value="1" class="btn btn-default"  onclick="
                                $('#formularioOrdenes').submit();" >A C E P T A R</button>
                    </div>
                    <div class="button col-sm-6" >
                        <button type="button" name="CANCELAR" class="btn btn-default" data-dismiss="modal">C A N C E L A R</button>
                    </div>
                </div>
                <br>
                <br>
            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function () {

        //debugger;
        var total = <?= round($valortotal) ?>;
        var pma = <?= round($pma) ?>;
        var pco =<?= round($pco) ?>;
        var totalPMA = formatter.format(pma);
        $('#totalPMA').text(totalPMA);
        var totalPCO = formatter.format(pco);
        $('#totalPCO').text(totalPCO);

        var total1 = formatter.format(total);
        $('#totalF').text(total1);
        $('#totalF1').text(total1);
        $('#formularioOrdenes').submit(function () {
            $(".loader").fadeIn();
        });
    });

    var formatter = new Intl.NumberFormat('en-CO', {
        style: 'currency',
        currency: 'USD',
        minimumFractionDigits: 0
    });

    function showDiv(element) {
        //        debugger;
        if (element.value == 2 || element.value == 3) {
            document.getElementById('hidden_div').style.display = 'block';
            document.getElementById('PSE').style.display = 'none';
            document.getElementById('ANTICIPO').style.display = 'none';
        } else if (element.value == 1) {
            document.getElementById('PSE').style.display = 'block';
            document.getElementById('hidden_div').style.display = 'none';
            document.getElementById('ANTICIPO').style.display = 'none';
        } else {
            document.getElementById('PSE').style.display = 'none';
            document.getElementById('hidden_div').style.display = 'none';
            document.getElementById('ANTICIPO').style.display = 'block';
        }
        //= element.value == 1 ? 'block' : 'none';
    }

    function mymodal() {

        $('#formularioOrdenes').attr('action', '/portal/pagos/pagarant');
        $('#ModalPagoAnticipo').modal('show');
        //        $('#formularioOrdenes').submit();
    }
</script>

