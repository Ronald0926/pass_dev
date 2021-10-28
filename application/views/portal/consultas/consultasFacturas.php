<style>
    .tnotifi{
        /*color: red;*/
        padding-top: 20px;
        padding-bottom:10px;
        display: none;
    }
    .label.badge-pill {
        font-size: 1.25em;
        border-radius:1em;
        /*margin:0 0.1em;*/
    }
</style>

<div class="loader" hidden></div> 
<div style=" margin-bottom: 300px; margin-top: -50px;">
    <div style=" margin-bottom: 200px; margin-top: -50px;">
        <div class="container">
            <hr style="border-top: 1px solid #eee0;">
            <h2 class="titulo-iz">Consultas</h2>
            <ul class="nav nav-tabs">
                <li><a href="/portal/consultas/consultasAbonos">Abonos</a></li>
                <li class="active"><a href="/portal/consultas/consultasFacturas">Facturas</a></li>
                <li><a href="/portal/consultas/consultasTarjetas">Tarjetas</a></li>
                <li><a href="/portal/consultas/consultasBussines">Business</a></li>
                <li><a href="/portal/consultas/consultasPedidosTarjeta">Pedidos Tarjetas</a></li>
            </ul>


            <div id="facturas" class="tab-pane fade in active">
                <div class="grid" style="margin: 2%;">
                    <table class="table table-hover daos_datagrid">
                        <thead>
                            <tr>
                                <th> Descargar </th>
                                <th> Fecha </th>
                                <th> No. Factura </th>
                                <th> Valor Abonos </th>
                                <th> Valor Cobros </th>
                                <th> Total Factura </th>
                                <th> No. Orden </th>
                                <th> Estado </th>
                                <th> Medio de pago </th>
                                <th> Fecha de pago </th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($factura as $value) { ?>
                                <tr class="gradeC">
                                    <td>
                                        <div class="login-checkbox" onclick="" style="padding-top: 5px">
                                            <span id="<?= $value['FAC'] ?>" data-estado="<?= $value['FAC'] ?>">
                                                <div class="">
                                                    <span class="login-checkbox-check noncheck" id="<?= $value['FAC'] ?>"></span>
                                                </div>
                                            </span>
                                        </div>
                                        <br>
                                    </td>
                                    <td><?= $value['FECCRE'] ?></td>
                                    <td><?= $value['NUMERO_FACTURA'] ?></td>
                                    <td><?= "$ " . number_format($value['PMA'], 0) ?></td>
                                    <td><?= "$ " . number_format($value['PCO'], 0) ?></td>
                                    <td><?= "$ " . number_format($value['TOTAL'], 0) ?></td>
                                    <td><?= $value['ORDEN'] ?></td>
                                    <td><?= $value['ESTADO'] ?></td>
                                    <td><?= $value['MEDIO_PAGO'] ?></td>
                                    <td><?= $value['FECHA_PAGO'] ?></td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                    <div class="row">
                        <!--    <div class="button col-sm-2">
                                <button type="submit">
                                    Descargar EXCEL
                                </button>
                            </div>-->
                        <div class="button col-sm-2">
                            <div class=" tnotifi">
                                <span class="label label-danger badge-pill">Seleccioné una factura </span>
                            </div>
                            <input type="hidden" value="" id="formFact">
                            <button id="createFactura" onclick="createPdf()">
                                Descargar PDF
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
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
<style>   


    .loader {
        position: fixed;
        left: 0px;
        top: 0px;
        width: 100%;
        height: 100%;
        z-index: 9999;
        background: url('/static/img/wsonline2/carga-loader.gif') 50% 50% no-repeat rgb(249, 249, 249);
        opacity: .8;
    }
</style>
<script>
    $('body').on('click', '.gradeC span', function () {
        var id = $(this).attr('id');

        if (id != undefined) {
            var status = $(this).data('estado');
            var y = $('.noncheck');
            for (i = 0; i < y.length; i++) {
                if (y[i].id != id) {
                    y[i].style.display = "none";
                }
            }
            $("#formFact").attr("value", "/wsonline2/facturaElectronica/descargaPdf/" + status);

        }
    });

    function createPdf() {

        if ($("#formFact").val() != "") {
            $(".tnotifi").hide();
            $(".loader").fadeIn();
            $.ajax({
                url: $("#formFact").val(),
            })
                    .done(function (data) {
                        if (parseInt(data) === 0) {
                            $('.loader').hide();
                            $('#ModalSinFact').modal('show');
                        } else if (parseInt(data) === 404) {
                            $('.loader').hide();
                            $(".tnotifi").show();
                        } else {
                            $(".loader").fadeOut("slow");
                            window.open(data);
                        }
                    });

        } else {
            $(".tnotifi").show();
        }
    }


</script>