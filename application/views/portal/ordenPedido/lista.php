<div style="margin-top: 30px; margin-bottom: 40%;padding: 50px">
    <div style="padding-left:  1%">
        <h2 style="color: #1C5394; padding-left:  1%;" class="titulo">Facturas para Pago</h2>
        <br/>
        <br/>
        <?php if ($error == 1) { ?>
            <div class="alert alert-success">Solicitud Exitosa</div>
        <?php } ?>
        <?php if ($error == 2) { ?>
            <div class="alert alert-danger">No selecciono una orden a pagar</div>
        <?php } ?>
        <?php if ($anuladas == 1) { ?>
            <div class="alert alert-success">
                <strong>El pago se realizado con exito!</strong>
            </div>
        <?php } ?>
        <?php if ($anuladas == 6040) { ?>
            <div class="alert alert-danger">
                <strong>No tiene Saldo Disponible para Pagar la Orden!</strong>
            </div>
        <?php } ?>    
        <?php if ($anuladas == 6043) { ?>
            <div class="alert alert-danger">
                <strong>Tiene un Bloqueo por mora!</strong>
            </div>
        <?php } ?>  
        <?php if ($anuladas == 6042) { ?>
            <div class="alert alert-danger">
                <strong><?= $anuladas ?>: El pago con anticipo no esta disponible!</strong>
            </div>
        <?php } ?> 
        <?php if ($anuladas == 7013) { ?>
            <div class="alert alert-danger">
                <strong><?= $anuladas ?>: Empresa no habilitada para anticipo!</strong>
            </div>
        <?php } ?> 

        <?php if (isset($_GET['OrderError'])) { ?>
            <div class="alert alert-danger ">
                <strong>Error,</strong> Debe seleccionar una orden para generar la factura.
            </div>
        <?php } ?>
        <?php ?>

        <?php
        $facturas = $_SESSION['facturas'];
        if ($facturas != null) {

            foreach ($facturas as $key => $value) {
                ?>
                <script>
                    window.open('<?= $value ?>', '_blank');
                </script>
                <?php
            }
            //$this->session->set_userdata(array('facturas' => null));
            $_SESSION['facturas'] = null;
        }
        ?>

        <form action="" id="formularioOrdenes" method="POST" >
            <div id="checks" class="col-lg-3">
                <table>
                    <?php foreach ($ordenes as $key => $value) { ?>
                        <tr>
                            <td style="padding-bottom: 10px;">
                                <div  class="login-checkbox" onclick="">
                                    <input style="height: 1.5em;" name ="ordenes[]" total="<?= $value['VALOR'] ?>" id="<?= $value['CODIGOORDEN'] ?>" type="checkbox" value="<?= $value['CODIGOORDEN'] ?>" > 
                                    <span id ="<?= $value['CODIGOORDEN'] ?>">
                                        <div class="">
                                            <span  class="login-checkbox-check" > 
                                            </span>
                                        </div>
                                    </span>
                                </div>
                            </td>
                            <td style="padding-bottom:5px;font-weight: bold;color: #366199;font-size: 18px;">
                                <?= $value['NUMERO_FACTURA'] ?>
                            </td>
                        </tr>
                    <?php } ?>
                </table>
                <h3>Total a pagar</h3>
                <div style="  font-weight: bold;  font-size: 35px;color: #1c5394"id="totalF">0</div>
                <?php if ($modelopago['DATO'] == 'PREPAGO') { ?> 
                    <div class="button col-sm-12" > 
                        <button class="spacing" type="button" data-toggle="modal" data-target="#myModal">ANULAR  ORDEN</button>
                    </div>
                <?php } ?>  
                <br>
                <br>                
                <?php if ($modelopago['DATO'] == 'POSPAGO') { ?>  
                    <div class="button col-sm-12" >
                        <button class="spacing" name="ORDEN" value="2" type="button" onclick="
                                $('#formularioOrdenes').attr('action', '/portal/ordenPedido/crearfactura');
                                $('#formularioOrdenes').submit();
                                ">DESCARGAR FACTURA</button>
                    </div>
                <?php } ?> 
                <br>
                <br>
                <div class="button col-sm-12" >
                    <button class="spacing" name="PAGAR" value="2" type="button" onclick="
                            $('#formularioOrdenes').attr('action', '/portal/pagos/pago');
                            $('#formularioOrdenes').submit();
                            ">PAGAR</button>
                </div>

                <div class="container" style="margin-top: 15%;">
                    <!-- Modal -->
                    <div class="modal fade" id="myModal" role="dialog" style="    margin-top: 15%;">
                        <div class="modal-dialog">

                            <!-- Modal content-->
                            <div class="modal-content" style="border-radius:35px">
                                <!--  <div class="modal-header">
                                      <button type="button" class="close" data-dismiss="modal">&times;</button>
                                  </div>-->
                                <div class="modal-body" style="text-align: center;height: 230px;">

                                    <p  style="font-size:18px;color:#888686;font-weight: bold">Si procede a anular la orden ya no podrá
                                        <br>editarla ni pagarla
                                    </p>
                                    <br>
                                    <p  style="font-size:18px;color:#888686;font-weight: bold">¿Desea continuar la anulación?</p>
                                    <br>
                                    <br>
                                    <div style="">
                                        <div class="button col-sm-6" >
                                            <button type="button" name="ACEPTAR" value="1" class="btn btn-default" onclick="
                                                    $('#formularioOrdenes').attr('action', '/portal/ordenPedido/anularOrden');
                                                    $('#formularioOrdenes').submit();
                                                    " >A C E P T A R</button>
                                        </div>
                                        <div class="button col-sm-6" >
                                            <button type="button" name="CANCELAR" class="btn btn-default" data-dismiss="modal">C A N C E L A R</button>
                                        </div>
                                    </div>
                                    <br>
                                </div>
                                <!--   <div class="modal-footer">
                                      <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                  </div>-->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
        <div class="col-lg-3">
            <div class="container">
                <div class="grid" style="margin: 2%;margin-bottom: 15%">
                    <!--nueva presentacion-->
                    <div id="detalleOrd">

                    </div>
                    <!--fin nueva pre-->
                </div>   


            </div> 
        </div>
    </div>

</div>

<?php if ($error == 1) { ?>
    <!-- Modal confirmacion recarga-->
    <div class="modal fade" id="ModalOpOk" role="dialog" style="margin-top: 15%;"  data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog">

            <!-- Modal content-->
            <div class="modal-content" style="border-radius:35px">

                <div class="modal-body" style="text-align: center;height: 200px;">

                    <p  style="font-size:24px;color:#888686;font-weight: bold;padding-top: 25px">La solicitud fue realizada exitosamente
                    </p>
                    <hr>
                    <div style="">
                        <div class="button col-sm-6 col-sm-push-3" >
                            <button type="button" class="btn btn-default spacing" data-dismiss="modal">ACPETAR</button>
                        </div>
                    </div>
                    <br>
                </div>
            </div>
        </div>
    </div>
<?php } ?>

<?php if (isset($_GET['sinFact'])) { ?>
    <div class="container" style="margin-top: 15%;">
        <!-- Modal -->
        <div class="modal fade" id="ModalSinFact" role="dialog" style="    margin-top: 15%;">
            <div class="modal-dialog">

                <!-- Modal content-->
                <div class="modal-content" style="border-radius:35px">
                    <button class="btn_cerrar_modal" data-dismiss="modal"></button>
                    <div class="modal-body" style="text-align: center;height: 270px;">
                        <div class="modal-header">
                            <h5 style="color: #366199;font-size: 20px;font-weight: bold; ">Factura no disponible</h5>
                        </div>
                        <p  style="font-size:18px;color:#888686;font-weight: bold;padding-top: 5px">
                            <span  class="glyphicon glyphicon-exclamation-sign"></span>Nuestra facturación ahora es electronicamente, por tal motivo tu factura estará disponible en breve, por favor intenta más tarde.
                        </p>
                        <div style="">
                            <div class="button col-sm-6 col-sm-push-3" >
                                <button type="button" class="btn btn-default" data-dismiss="modal">ACEPTAR</button>
                            </div>
                        </div>
                        <br>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php } ?>

<script>
    var sinFact =<?= isset($_GET['sinFact']) ? 1 : 0 ?>;
    var OpOk =<?= isset($error) == 1 ? 1 : 0 ?>;
    if (OpOk == 1) {
        $('#ModalOpOk').modal('show');
    }

    if (sinFact == 1) {
        $('#ModalSinFact').modal('show');
    }
    $(document).ready(function () {
        var total = 0;
//        $('body').on('click', '#checks span', function () {
//            var x = $(this).attr('id');


//            if (x != undefined) {
//
//                var checkBox = document.getElementById(x);
//                var subtotal = $(checkBox).attr('total');
//                if (checkBox.checked == true) {
//
//                    $('#TAR' + x).show();
//                    $('#RES' + x).show();
//                    $('#IMP' + x).show();
//                    total = parseInt(total) + parseInt(subtotal);
//                } else {
//                    $('#TAR' + x).hide();
//                    $('#RES' + x).hide();
//                    $('#IMP' + x).hide();
//                    total = parseInt(total) - parseInt(subtotal);
//                }
//                var total1 = formatter.format(total);
//                $('#totalF').text(total1);
//            }
        $('body').on('click', '#checks span', function () {
            var x = $(this).attr('id');
            if (x != undefined) {
                var checkBox = document.getElementById(x);
                var subtotal = $(checkBox).attr('total');
                if (checkBox.checked == true) {
                    total = parseInt(total) + parseInt(subtotal);
                } else {
                    total = parseInt(total) - parseInt(subtotal);
                }
                var total1 = formatter.format(total);
                $('#totalF').text(total1);
            }
            var arr = $('[name="ordenes[]"]:checked').map(function () {
                return this.value;
            }).get();

            var str = arr.join('-');

            cargarDetalle(str);

        });
    });

//    var checkboxes = document.getElementsByTagName("INPUT");
//    for (var x = 0; x < checkboxes.length; x++)
//    {
//        if (checkboxes[x].type == "checkbox")
//        {
//            checkboxes[x].checked = false;
//        }
//        }
//    }
//    );
    var formatter = new Intl.NumberFormat('en-CO', {
        style: 'currency',
        currency: 'USD',
        minimumFractionDigits: 0
    });
    function cargarDetalle(ordenes) {
        if (ordenes !== '') {
            $.ajax({
                url: "/portal/ajax/returnDataOrdenes/" + ordenes
            })
                    .done(function (msg) {
                        if (msg.length > 0) {
                            $('#detalleOrd').html(msg)
                        }
                    });
            $('#detalleOrd').show();
        } else {
            $('#detalleOrd').hide();
        }
    }


</script>

