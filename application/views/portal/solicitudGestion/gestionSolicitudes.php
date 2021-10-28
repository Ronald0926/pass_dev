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
<div class="container col-lg-12" style=" margin-bottom: 200px; margin-top: -50px;">

    <hr style="border-top: 1px solid #eee0;">
    <h2 class="titulo-iz">Gestión solicitudes</h2>
    <div class="col-md-5 col-sm-12" style="">
        <?php if (isset($_GET['eliminar'])) { ?>  
            <div class="alert alert-success">La solicitud # <strong><?= $_GET['sol'] ?></strong> ha sido eliminada exitosamente.</div>
        <?php } ?>

        <h3 class="subtitulo-iz">Solicitud de tarjetas</h3>
        <div class="grid" >
            <table class="table table-hover dataSel" id="tblSolicitud" style="margin-top: 5px;width: 100%;margin-right: auto; margin-left: auto;">
                <thead>
                    <tr style="font-weight: bold">
                        <td></td>
                        <td>Nombre de la solicitud</td>
                        <td>Editar</td>
                        <td>Eliminar</td>
                    </tr>
                </thead>
                <tbody class="tblbody">
                    <?php foreach ($solicitudes as $value) { ?>
                        <?php if ($value['PK_TIPSOL_CODIGO'] == 1 || $value['PK_TIPSOL_CODIGO'] == 2) { ?>
                            <?php if (empty($value['PK_PREORDEN_CODIGO']) || $value['PK_PREORDEN_CODIGO'] == $pk_preorden_codigo) { ?>
                                <tr class="checks">
                                    <td style="padding-bottom: 8px;">
                                        <input id="checks" type="checkbox"  <?php if (!empty($pk_preorden_codigo) && $value['PK_PREORDEN_CODIGO'] == $pk_preorden_codigo) { ?>
                                                   checked 
                                               <?php } ?>   name="solicitudes[]" value="<?= $value['PK_CODIGO_SOLICITUD'] ?>" style="width: 15px;height: 15px;">
                                    </td>
                                    <td> <?= $value['NOMBRE_SOLICITUD'] ?></td>
                                    <td> 
                                        <a href="/portal/solicitudGestion/editarSolicitud/<?= $value['PK_CODIGO_SOLICITUD'] ?>" class="btn btn-circle item editar">
                                        </a>
                                    </td>
                                    <td>
                                        <button  onclick="cargaIdModalElimi(<?= $value['PK_CODIGO_SOLICITUD'] ?>)"   class="btn btn-circle item eliminar">
                                        </button>
            <!--                                        <a href="/portal/solicitudGestion/eliminarSolicitud/<?= $value['PK_CODIGO_SOLICITUD'] ?>" class="btn btn-circle item eliminar">
                                        </a>-->
                                    </td>
                                </tr>
                                <?php
                            }
                        }
                    }
                    ?>
                </tbody>
            </table>
        </div>

        <!--Inicio tabla abonos-->
        <h3 class="subtitulo-iz">Solicitud de abonos</h3>
        <div class="grid" >
            <table class="table table-hover dataSel" id="tblAbono" style="margin-top: 5px;width: 100%;margin-right: auto; margin-left: auto;">
                <thead>
                    <tr style="font-weight: bold">
                        <td></td>
                        <td>Nombre de la solicitud</td>
                        <td>Editar</td>
                        <td>Eliminar</td>
                    </tr>
                </thead>
                <tbody class="tblbody">
                    <?php foreach ($solicitudes as $value) { ?>
                        <?php if ($value['PK_TIPSOL_CODIGO'] == 3 || $value['PK_TIPSOL_CODIGO'] == 4) { ?>
                            <?php if (empty($value['PK_PREORDEN_CODIGO']) || $value['PK_PREORDEN_CODIGO'] == $pk_preorden_codigo) { ?>
                                <tr class="gradeC">
                                    <td style="padding-bottom: 8px;">
                                        <input id="checks" type="checkbox" <?php if (!empty($pk_preorden_codigo) && $value['PK_PREORDEN_CODIGO'] == $pk_preorden_codigo) { ?>
                                                   checked 
                                               <?php } ?> name="solicitudes[]" value="<?= $value['PK_CODIGO_SOLICITUD'] ?>" style="width: 15px;height: 15px;">
                                    </td>
                                    <td> <?= $value['NOMBRE_SOLICITUD'] ?></td>
                                    <td> 
                                        <a href="/portal/solicitudGestion/editarAbono/<?= $value['PK_CODIGO_SOLICITUD'] ?>" class="btn btn-circle item editar">
                                        </a>
                                    </td>
                                    <td>
                                        <button  onclick="cargaIdModalElimi(<?= $value['PK_CODIGO_SOLICITUD'] ?>)"   class="btn btn-circle item eliminar">
                                        </button>
            <!--                                    <a href="/portal/solicitudGestion/eliminarSolicitud/<?= $value['PK_CODIGO_SOLICITUD'] ?>" class="btn btn-circle item eliminar">
                                    </a>-->
                                    </td>
                                </tr>
                                <?php
                            }
                        }
                    }
                    ?>
                </tbody>
            </table>
        </div>
        <!--fin tabla abonos-->
        <div class="button  col-sm-12 ">
            <div class=" linkgenerico spacing">
                <a href="/portal/solicitudGestion/generarOrden">Ver ordenes generadas</a>
            </div>
        </div>
    </div>

    <div class="col-md-7 col-sm-12 " style="padding-left: 5%;">

        <div id="detalleFact">
            <label style="font-size:25px;color:#888686;font-weight: bold;margin-top: 15%;"> No ha seleccionado ninguna solicitud</label>
        </div>
    </div>
</div>


<?php ?>
<div class="container" style="margin-top: 15%;">
    <!-- Modal -->
    <div class="modal fade" id="myModal" role="dialog" style="    margin-top: 15%;">
        <div class="modal-dialog">

            <!-- Modal content-->
            <div class="modal-content" style="border-radius:35px">
                <div class="modal-body" style="text-align: center;height: 270px;">
                    <form  method="POST" action="/portal/solicitudGestion/finalizarOrden" id="enviocodigoconf">
                        <div class="modal-header">
                            <h5 style="color: #366199;font-size: 20px;font-weight: bold; ">Finalizar solicitud</h5>
                        </div>
                        <p  style="font-size:15px;color:#888686;font-weight: bold;padding-top: 5px">
                            Por favor asigne un nombre a la orden
                        </p>
                        <input type="text" name="nombreOrden" style="width: 60%" placeholder="Digite nombre para la orden"  required>
                        <div style="">
                            <div class="button col-sm-6 col-sm-push-3" >
                                <button type="submit" name="CONFCARGA" value="1" class="btn btn-default spacing">CARGAR</button>
                            </div>
                        </div>
                        <br>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<?php ?>

<?php if (isset($_GET['res']) == 1) { ?>
    <div class="container" style="margin-top: 15%;">
        <!-- Modal -->
        <div class="modal fade" id="ModalInfo" role="dialog" style="    margin-top: 15%;">
            <div class="modal-dialog">

                <!-- Modal content-->
                <div class="modal-content" style="border-radius:35px">
                    <div class="modal-body" style="text-align: center;height: 270px;">
                        <div class="modal-header">
                            <h5 style="color: #366199;font-size: 20px;font-weight: bold; ">Inicio su solicitud</h5>
                        </div>
                        <p  style="font-size:15px;color:#888686;font-weight: bold;padding-top: 5px">
                            Su solicitud <span style="color: #366199;font-weight: bold;"><?php echo $_GET['nom'] ?></span> ha iniciado, por
                            favor tenga presente que las tarjetas no iniciarán su proceso de fabricación y envío de tarjetas hasta
                            que realice la respectiva factura, para ello puede dirigirse al módulo - Gestión de solicitudes y ordenes
                            -, generar la orden y factura correspondiente.
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
<!-- Modal confirmar eliminar registro-->
<div class="modal fade" id="ModalConfEliminar" role="dialog" style="margin-top: 5%;"  data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content" style="border-radius:35px;">
            <button class="btn_cerrar_modal" data-dismiss="modal"></button>
            <div class="modal-body" style="text-align: center;height: auto;">
                <form  method="POST" action="/portal/solicitudGestion/eliminarSolicitud/" id="FormEliminar">
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

<script type="text/javascript">
    $(document).ready(function () {

        //se ejecuta para cargar vista detalle cuando es editar
        var arr = $('[name="solicitudes[]"]:checked').map(function () {
            return this.value;
        }).get();

        var str = arr.join('-');

        cargarFact(str);

        $('[name="solicitudes[]"]').click(function () {

            var arr = $('[name="solicitudes[]"]:checked').map(function () {
                return this.value;
            }).get();

            var str = arr.join('-');

            cargarFact(str);

        });

    });

    function cargarFact(solicitudes) {
        var pkorden =<?= !empty($pk_preorden_codigo) ? $pk_preorden_codigo : 'null' ?>;

//        var re = /,/g;
//        var dataSol = solicitudes.replace(re, '-');
        var dataSol = solicitudes;
//alert(dataSol);
        $.ajax({
            url: "/portal/ajax/returnDataFact/" + dataSol + "/" + pkorden
        })
                .done(function (msg) {
                    if (msg.length > 0) {
                        $('#detalleFact').html(msg)
                    }
                });
    }
    function cargaIdModalElimi(codSol) {
        if (codSol !== '') {
            $('#FormEliminar').attr('action', '/portal/solicitudGestion/eliminarSolicitud/' + codSol);
            $("#ModalConfEliminar").modal('show');
        }
    }
    $("#FormEliminar").submit(function () {
        $('#loader').modal('show');
    });
</script>