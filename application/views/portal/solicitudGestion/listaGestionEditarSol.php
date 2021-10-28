<style>
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
</style>

<div class="loader" id="loader" hidden=""></div>
<div style=" margin-bottom: 200px; margin-top: -50px;">
    <div class="container">
        <hr style="border-top: 1px solid #eee0;">
        <h2 class="titulo-iz">Editar solicitud tarjetas</h2>
        <?php if ($actualizacion == 1) { ?>  
            <div class="alert alert-success">Actualización Exitosa</div>
        <?php } ?>
        <?php if (isset($_GET['OK']) == 1) { ?>  
            <div class="alert alert-success">Registro eliminado exitosamente</div>
        <?php } ?>
        <?php if (isset($_GET['error']) == 504) { ?>  
            <div class="alert alert-danger">Error al intentar eliminar registro</div>
        <?php } ?>
        <?php if (isset($_GET['add']) == 'OK') { ?>  
            <div class="alert alert-success">Tarjeta adicionada a la solicitud correctamente</div>
        <?php } ?>
        <div class="tab-content">
            <div class="container">
                <div class="grid" style="margin: 2%;">
                    <table class="table table-hover daos_datagrid">
                        <thead>
                            <tr>
                                <th> Producto </th>
                                <th> T.D </th>
                                <th> Documento </th>
                                <th> Primer nombre </th>
                                <th> Segundo nombre </th>
                                <th> Primer apellido </th>
                                <th> Segundo apellido </th>
                                <th> Email </th>
                                <th> Teléfono</th>
                                <th> Identificador</th>
                                <th> Editar</th>
                                <th> Eliminar</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($solicitudes as $value) { ?>
                                <tr>
                                    <td><?= $value['PRODUCTO'] ?></td>
                                    <td><?= $value['TIPDOC'] ?></td>
                                    <td><?= $value['DOCUMENTO'] ?></td>
                                    <td><?= $value['PRIMER_NOMBRE'] ?></td>
                                    <td><?= $value['SEGUNDO_NOMBRE'] ?></td>
                                    <td><?= $value['PRIMER_APELLIDO'] ?></td>
                                    <td><?= $value['SEGUNDO_APELLIDO'] ?></td>
                                    <td><?= $value['CORREO_ELECTRONICO'] ?></td>
                                    <td><?= $value['TELEFONO'] ?></td>
                                    <td><?= $value['IDENTIFICADOR_TARJETA'] ?></td>
                                    <td align="center"> 
                                        <?php if (empty($value['IDENTIFICADOR_TARJETA'])) { ?>
                                            <div onclick="cargarModal(<?= $value['PK_DETALLE_SOLICITUD'] ?>)" class="btn btn-circle item editar">
                                               
                                            </div>
                                        <?php } ?>
                                    </td>
                                    <td align="center">
    <!--                                        <a href="/portal/solicitudGestion/eliminarSolicitud/<?= $value['PK_CODIGO_SOLICITUD'] ?>">
                                            <img src="/static/img/portal/prepedido/eliminar.png" style="width: 20px;height: 20px;">
                                        </a>-->
                                        <button  onclick="cargaIdModal(<?= $value['PK_DETALLE_SOLICITUD'] ?>)"   class="btn btn-circle item eliminar">
                                            <!--<img src="/static/img/portal/prepedido/eliminar.png" style="width: 20px;height: 20px;">-->
                                        </button>
                                    </td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>  
            </div> 
            <div class="button  col-md-4 col-md-push-4">
                <div class=" linkgenerico spacing">
                    <a href="/portal/solicitudTarjetas/solicitud/?sol=<?php echo $codSolicitud ?>"> ADICIONAR OTRA TARJETA</a>
                </div>
                <br><br>
                <div class=" linkgenerico spacing">
                    <a href="/portal/solicitudGestion/solicitudGes">VOLVER</a>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Modal Editar detalle solicitud-->
<div class="modal fade" id="ModalEditar" role="dialog" style="margin-top: 5%;"  data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content" style="border-radius:35px;">
            <button class="btn_cerrar_modal" data-dismiss="modal"></button>
            <div class="modal-body" style="text-align: center;height: auto;">
                <form  method="POST" action="/portal/solicitudGestion/editarSolicitud/<?php echo $codSolicitud ?>" id="FormEnvioEditar">
                    <div class="modal-header" style="padding:0px">
                        <h5 style="color: #366199;font-size: 18px;font-weight: bold; ">Editar información</h5>
                    </div>

                    <div id="editDet" style="width: 60%; margin-left: auto;  margin-right: auto;">

                    </div>

                    <div class="button col-sm-6 col-sm-push-3" >
                        <button type="submit" name="CONFEDIT" value="1" class="btn btn-default spacing">EDITAR</button>
                    </div>
                    <br>
                    <br>
                    <br>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- Modal confirmar eliminar registro-->
<div class="modal fade" id="ModalConfEliminar" role="dialog" style="margin-top: 5%;"  data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content" style="border-radius:35px;">
            <button class="btn_cerrar_modal" data-dismiss="modal"></button>
            <div class="modal-body" style="text-align: center;height: auto;">
                <form  method="POST" action="/portal/solicitudGestion/eliminarSolicitud" id="FormEliminar">
                    <div class="modal-header" style="padding:0px">
                        <h5 style="color: #366199;font-size: 24px;font-weight: bold; ">Eliminar registro</h5>
                    </div>
                    <p  style="font-size:20px;color:#888686;font-weight: bold;padding-top: 25px">
                        Va a eliminar un registro, ¿Desea continuar?, una vez eliminado un registro no se podrá recuperar.
                    </p>
                    <div  style="width: 60%; margin-left: auto;  margin-right: auto;">
                        <input value="" name="pkdetalleSolElimi" id="pkdetalleSolElimi" hidden>
                        <input value="<?= $codSolicitud ?>" name="pkCodSol" id="pkcodSolicitud"  hidden>
                    </div>

                    <div class="button col-sm-6 col-sm-push-3" >
                        <button type="submit" name="CONFEDIT" value="1" class="btn btn-default spacing">CONFIRMAR</button>
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
    function cargarModal(codigosol) {
        $('#ModalEditar').modal('show');
        $.ajax({
            url: "/portal/ajax/returnDataEditarDetSol/" + codigosol
        })
                .done(function (msg) {
                    if (msg.length > 0) {
                        $('#editDet').html(msg)
                    }
                });
    }
    function cargaIdModal(codDetalle) {
        if (codDetalle !== '') {
            $("#pkdetalleSolElimi").val(codDetalle);
            $("#ModalConfEliminar").modal('show');
        }
    }
    $("#FormEnvioEditar").submit(function () {
        $('#loader').modal('show');
    });
    $("#FormEliminar").submit(function () {
        $('#loader').modal('show');
    });
</script>