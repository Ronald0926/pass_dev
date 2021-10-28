<style>
    .btn-circle {
        background: transparent;
    }
    .item {
        width: 30px;
        height: 30px;
        /*margin-top: 30px;*/
        background-size: contain;
        /*margin: 50px 15px 0px 15px;*/
        -webkit-transition: all 0.4s ease;
        -moz-transition: all 0.4s ease;
        -o-transition: all 0.4s ease;
        -ms-transition: all 0.4s ease;
    }

    .item:hover {
        border-radius: 10px;
        -webkit-transform: scale(1.05);
        -moz-transform: scale(1.05);
        -ms-transform: scale(1.05);
        -o-transform: scale(1.05);
        transform: scale(1.05);
        width: 35px;
        height: 35px;
        margin: 0px 0px 0px 0px;
    }
    .facturar{
        background-image: url("/static/img/portal/prepedido/facturar.png");
    }
    .facturar:hover{
        background-image: url("/static/img/portal/prepedido/facturar-hover.png");
    }
    .descargar{
        background-image: url("/static/img/portal/prepedido/descarga.png");
    }
    .descargar:hover{
        background-image: url("/static/img/portal/prepedido/descarga-hover.png");
    }
    .editar{
        background-image: url("/static/img/portal/prepedido/editar.png");
    }
    .editar:hover{
        background-image: url("/static/img/portal/prepedido/editar-hover.png");
    }
    .eliminar{
        background-image: url("/static/img/portal/prepedido/eliminar.png");
    }
    .eliminar:hover{
        background-image: url("/static/img/portal/prepedido/eliminar-hover.png");
    }

</style>
<div class="loader" id="loader" hidden=""></div>
<div class="col-lg-2" ></div>
<div class="container col-lg-8" style=" margin-bottom: 200px; margin-top: -50px;">
    <h2 class="titulo-iz">Ordenes de pedido generadas</h2>
    <?php if ($_GET['res'] == 1) { ?>  
        <div class="alert alert-success">Orden creada exitosamente</div>
    <?php } ?>
    <?php if ($_GET['eli'] == 1) { ?>  
        <div class="alert alert-success">la Orden # <strong><?= $_GET['orden'] ?></strong> ha sido eliminada exitosamente.</div>
    <?php } ?>
    <div class="tab-content">

        <div class="container col-lg-12">
            <div class="grid" >
                <table class="table table-hover daos_datagrid">
                    <thead>
                        <tr>
                            <th> Número de orden </th>
                            <th> Facturar </th>
                            <th> Descargar </th>
                            <th> Editar </th>
                            <th> Eliminar </th>
                        </tr>
                    </thead>

                    <tbody>
                        <?php foreach ($ordenes as $value) { ?>
                            <tr>
                                <td><?= $value['ORDEN'] ?></td>
                                <td>
                                    <?php if ($value['ESTADO'] == 1) { ?>
                                        <a onclick="cargaModalFact(<?= $value['ORDEN'] ?>)" class="btn btn-circle item facturar"></a>
                                    <?php } else { ?>
                                        <span>ok</span>
                                    <?php } ?>
                                </td>
                                <td>
                                    <button  onclick="descargarpdf(<?= $value['ORDEN'] ?>)" class="btn btn-circle item descargar "  ></button>

                            <!--                                    <a href="/portal/solicitudGestion/editarPreorden/<?= $value['ORDEN'] ?>" class="btn btn-circle item descargar">
                                                                </a>-->
                                </td>
                                <td>
                                    <?php if ($value['ESTADO'] == 1) { ?>
                                        <a href="/portal/solicitudGestion/solicitudGes/<?= $value['ORDEN'] ?>" class="btn btn-circle item editar">
                                        </a>
                                    <?php } ?>
                                </td>
                                <td>
                                    <?php if ($value['ESTADO'] == 1) { ?>
                                        <button  onclick="cargaIdModalElimi(<?= $value['ORDEN'] ?>)" class="btn btn-circle item eliminar "  ></button>
                                    <?php } ?>
                                </td>
                            </tr>
                        <?php } ?>
                    </tbody>

                </table> 
                <div class="button  col-md-4 col-md-push-4">
                    <div class=" linkgenerico spacing">
                        <a href="/portal/solicitudGestion/solicitudGes">VOLVER</a>
                    </div>
                </div>
            </div>  
        </div>
    </div>
</div>
<div class="col-lg-2" ></div>

<!-- Modal confirmar eliminar registro-->
<div class="modal fade" id="ModalConfEliminar" role="dialog" style="margin-top: 5%;"  data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content" style="border-radius:35px;">
            <button class="btn_cerrar_modal" data-dismiss="modal"></button>
            <div class="modal-body" style="text-align: center;height: auto;">
                <form  method="POST" action="/portal/solicitudGestion/eliminarPreorden/" id="FormEliminar">
                    <div class="modal-header" style="padding:0px">
                        <h5 style="color: #366199;font-size: 24px;font-weight: bold; ">Eliminar registro</h5>
                    </div>
                    <p  style="font-size:20px;color:#888686;font-weight: bold;padding-top: 25px">
                        Va a eliminar un registro, ¿Desea continuar?, una vez eliminado no se podrá recuperar.
                    </p>
                    <div class="button col-sm-6 col-sm-push-3" >
                        <button type="submit"  value="1" class="btn btn-default spacing">CONFIRMAR</button>
                    </div>
                    <br>
                    <br>
                    <br>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- Modal confirmar generar pdf prefactura-->
<div class="modal fade" id="ModalConfFact" role="dialog" style="margin-top: 5%;"  data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content" style="border-radius:35px;">
            <button class="btn_cerrar_modal" data-dismiss="modal"></button>
            <div class="modal-body" style="text-align: center;height: auto;padding-bottom: 35px">
                <form  method="POST" action="/portal/solicitudGestion/facturarPreorden/" id="FormFact" onsubmit="return checkForm(this);">
                    <div class="modal-header" style="padding:0px">
                        <h5 style="color: #366199;font-size: 24px;font-weight: bold; ">FACTURAR</h5>
                    </div>
                    <p  style="font-size:20px;color:#888686;font-weight: bold;padding-top: 25px">
                        ¿Al generar la factura no podrá ser anulada, si
                        la factura contiene pedidos de tarjetas, se iniciará inmediatamente la fabricación de las
                        mismas y el proceso no podrá detenerse, desea continuar? 
                    </p>
                    <input type="text" class="textPatObserFactura" maxlength="250" placeholder="Observación orden" style="width: 80%" name="observacion">
                    <div class="button col-sm-6 col-sm-push-3">
                        <button type="submit" name="btnFacturar" value="1" class="btn btn-default spacing">CONFIRMAR</button>
                    </div>
                    <br>
                    <br>
                    <br>
                </form>
            </div>
        </div>
    </div>
</div>


<script type="text/javascript">

    function checkForm(form)
    {
        //
        // validate form fields
        //
        form.btnFacturar.disabled = true;
        $("#loader").modal('show');
        return true;
    }

</script>
<script type="text/javascript">
    function cargaModalFact(codOrd) {
        if (codOrd !== '') {
            $('#FormFact').attr('action', '/portal/solicitudGestion/facturarPreorden/' + codOrd);
            $("#ModalConfFact").modal('show');
        }
    }
    $("#FormEliminar").submit(function () {
        $('#loader').modal('show');
    });
    $("#FormFact").submit(function () {
        $('#loader').modal('show');
    });

    $(document).ready(function () {
        var devsucc = <?= isset($_GET['descarga']) ? $_GET['descarga'] : 0 ?>;
        if (devsucc == 101) {
            descargarpdf(<?= $_GET['orden'] ?>);
        }


    });
    function cargaIdModalElimi(codOrd) {
        if (codOrd !== '') {
            $('#FormEliminar').attr('action', '/portal/solicitudGestion/eliminarPreorden/' + codOrd);
            $("#ModalConfEliminar").modal('show');
        }
    }
    $("#FormEliminar").submit(function () {
        $('#loader').modal('show');
    });
    function descargarpdf(codOrd) {
        $.ajax({
            url: "/wsonline2/pdfPreFactura/crear/" + codOrd
        })
                .done(function (msg) {
                    if (msg.length > 0) {
//                        $('#detalleFact').html(msg)
                        window.open(encodeURI(msg));
                    }
                    console.log(msg);
                });
    }
</script>
