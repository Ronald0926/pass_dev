<style>
    .hiddenFileInput > input{
        height: 100%;
        width: 100%;
        opacity: 0;
        cursor: pointer;
    }
    .hiddenFileInput{
        border: none;
        max-width: 245px;
        width: 245px;
        height: 33px;
        display: inline-block;
        overflow: hidden;
        margin-left: -5%;
        /*for the background, optional*/
        background: center center no-repeat;
        background-image:  url(/static/img/portal/solicitudTar/cargar-archivo-new.png);
    }
/*    .hiddenFileInput:hover{
        border: none;
        width: 120%;
        height: 33px;
        display: inline-block;
        overflow: hidden;
        margin-left: -5%;
        for the background, optional
        background: center center no-repeat;
        background-image:  url(/static/img/portal/abonos/adjuntar-hover.png);
    }*/


    .hiddenFileDownload > a{
        height: 100%;
        width: 100%;
        opacity: 0;
        cursor: pointer;
    }
    .hiddenFileDownload{
        border: none;
        max-width: 245px;
        width: 245px;
        height: 33px;
        display: inline-block;
        overflow: hidden;
        margin-left: -5%;
        /*for the background, optional*/
        background: center center no-repeat;
        background-image:  url(/static/img/portal/solicitudTar/descargar-plantilla-new.png);
    }
/*    .hiddenFileDownload:hover{
        border: none;
        width: 120%;
        height: 33px;
        display: inline-block;
        overflow: hidden;
        margin-left: -5%;
        for the background, optional
        background: center center no-repeat;
        background-image:  url(/static/img/portal/solicitudTar/descargar-plantilla-hover.png);
    }*/
    .lblnoticarga{
        color: red;
        padding-left:  15px;
        padding-bottom: 0px;
        display: none;
    }
</style>
<div class="loader" id="loader" hidden></div> 
<div style=" margin-bottom: 200px; margin-top: -50px;">
    <div class="container">
        <hr style="border-top: 1px solid #eee0;">
        <h2 class="titulo-iz">Solicitud de Abonos</h2>
        <ul class="nav nav-tabs">
            <li><a href="/portal/abonos/unoAUno">Solicitud Individual</a></li>
            <li class="active"><a data-toggle="tab" href="#solicitudMasiva">Solicitud Masiva</a></li>
        </ul>
        <div class="tab-content">
            <div id="solicitudMasiva" class="tab-pane fade in active">
                <h3>Solicitud masiva de abonos a tarjetas</h3>
                <p>Aqu&iacute;, podr&aacute;s solicitar la realizaci&oacute;n de un abono a las tarjetas, descargando la plantilla, diligenciando los datos solicitados del producto de los colaboradores a quienes deseas realizar el abono, el valor que deseas abonar y la fecha de dispersi&oacute;n, guardando los cambios de la plantilla y subi&eacute;ndola y envi&aacute;ndola nuevamente desde la plataforma.</p>
                
                <?php if (isset($_GET['error'])) { ?>

                    <div class="col-sm-6 alert alert-danger ">
                        Error, no se puede cargar el archivo.
                    </div>
                <?php } ?>
                <?php if (isset($_GET['errorp'])) { ?>

                    <div class="col-sm-6 alert alert-danger ">
                        No se diligenciaron los campos necesarios.
                    </div>
                <?php } ?>
                <?php if (isset($_GET['2'])) { ?>

                    <div class="col-sm-6 alert alert-danger ">
                        No tiene Producto activos.
                    </div>
                <?php } ?>
                <?php if (isset($_GET['3'])) { ?>

                    <div class="col-sm-6 alert alert-danger ">
                        No tcargo ningun documento.
                    </div>
                <?php } ?>

                <div class="row col-sm-9">
                    <br>

                    <form action="abonoPlantilla" method="post" enctype="multipart/form-data" id="formAbonoMasivo">
                        <div class="col-sm-3">
                            <input name="nombrePedido" id="nombrePedido" class="textPat" type="text" placeholder="Nombre Plantilla" required>
                        </div>
                        <div class="button col-sm-4" > 
                            <span class="hiddenFileInput">
                                <input type="file" name="file" id="file" accept="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet" required/>
                            </span>
                            <label class="lblnoticarga"> Por favor seleccionar un archivo.</label>
                        </div> 
                        <div class="button col-sm-3" > 
                            <button class="" type="submit" id="btnSubmitAbonoMa"> Envar plantilla subida </button>
                        </div> 
                    </form>
                    <div class="col-sm-2" > 
                        <a href="/portal/abonos/descargarPlantilla" ><span class="hiddenFileDownload"></span></a>
                    </div>  
                </div>
                <form method="post" enctype="multipart/form-data" action="abonoSeleccionado">
                    <div class="row col-sm-10">
                        <?php if (isset($_GET['ok'])) { ?>            
                            <div class="alert alert-success">Solicitud Exitosa</div>
                        <?php } ?>
                        <h3>Historial de Plantillas</h3>
                        <br>
                        <div class="grid">
                            <table class="table table-hover daos_datagrid">
                                <thead>
                                    <tr>
                                        <th>Nombre</th>
                                        <th>Ãšltima ModificacÃ­on</th>
                                        <th>Descargar archivo</th>
                                        <th hidden>Seleccionar archivo</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($abonos as $value) { ?>
                                        <tr class="gradeC">        
                                            <td><?= $value['NOMBRE_PEDIDO'] ?></td>
                                            <td> <?= $value['FECHA_CREACION'] ?></td>
                                            <td></div><a href="<?= '' . $value['URL_PLANTILLA'] ?>">Descargar </a> </td>
                                            <td hidden> 
                                                <input class="only-one" type="checkbox" name="check1"  value="<?= $value['NOMBRE_PEDIDO'] ?>-<?= $value['URL_PLANTILLA'] ?>"/> 
                                                <!--  <div class="login-checkbox">
                                                    <div class="">
                                                         <span class="login-checkbox-check">
                                                         </span>
                                                     </div>
                                                 </div> -->
                                            </td>
                                        </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <br>
                    <br>
                    <br>
                    <br>
                    <br>
                    <br>
                    <br>
                    <br>
                    <br>
                    <br>
                    <div class="button col-sm-2" hidden>
                        <input type="date" required="" class="textPat"  name="fechaDispersion" alt="Selecciona Fecha de DispersiÃ³n">
                        <br>
                        <br>
                        <button type="submit" value="" > APLICAR ABONO</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php if (isset($_GET['ok'])) { ?>
   <!-- Modal -->
<div class="modal fade" id="myModalFinalizar" role="dialog" style="    margin-top: 15%;" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content" style="border-radius:35px">
            <div class="modal-body" style="text-align: center;height: auto;">
                <div class="modal-header" style="padding:0px">
                    <h5 style="color: #366199;font-size: 24px;font-weight: bold; ">Nombrar orden</h5>
                </div>
                <div>
                    <br>
                    <form method="post" action="/portal/abonos/finalizarPrePedidoAbo" id="formNombreOrden" >
                        <input type="hidden" name="AbonoMasi" value="1"> 
                        <input type="hidden" name="pedido" value="<?= $_GET['c'] ?>"> 
                        <p style="font-size:18px;color:#888686;">Por favor asigne un nombre a la orden:</p>

                        <input type="text" class="textPat"  name="nombreorden" style="width: 60%" placeholder="Ingrese un nombre para la orden" required>
                        <br><br>
                        <div class="button col-sm-6 col-sm-push-3" >
                            <button type="submit" name="ACEPTAR" value="1" class="btn btn-default spacing" >CONFIRMAR</button>
                        </div>
                    </form>
                    <br><br><br><br>
                </div>
            </div>
        </div>
    </div>
</div>
<?php } ?>

<!-- Modal confirmacion abono masivo-->
<div class="modal fade" id="ModalConfAboMasi" role="dialog" style="margin-top: 5%;"  data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content" style="border-radius:35px">

            <div class="modal-body" style="text-align: center;height: auto;">
                <div class="modal-header" style="padding:0px">
                    <h5 style="color: #366199;font-size: 24px;font-weight: bold; ">Confirmar solicitud</h5>
                </div>
                <br>
                <p  style="font-size:17px;color:#333;padding-top: 5px;text-align: center;"><span  class="glyphicon glyphicon-exclamation-sign"></span>
                    Desea continuar con la operaciÃ³n.
                </p>
                <div style=" margin-bottom: 4em">
                    <div class="button col-sm-6" >
                        <button type="button" name="ACEPTAR" value="1" class="btn btn-default spacing"  onclick="
                                $('#formAbonoMasivo').submit();" >SI</button>
                    </div>
                    <div class="button col-sm-6" >
                        <button type="button" name="CANCELAR" class="btn btn-default spacing" data-dismiss="modal">NO</button>
                    </div>
                </div>
                <br>
            </div>
        </div>
    </div>
</div>
<?php if ($ok == 1) { ?>
    <div class="container" style="margin-top: 15%;">
        <div class="modal fade" id="myModalok" role="dialog" style="    margin-top: 15%;">
            <div class="modal-dialog">
                <div class="modal-content" style="border-radius:35px">
                    <button class="btn_cerrar_modal" data-dismiss="modal"></button>
                    <div class="modal-body" style="text-align: center;height: auto;">
                        <div class="modal-header" style="padding:0px">
                            <h5 style="color: #366199;font-size: 24px;font-weight: bold; ">Â¡La informaciÃ³n se guardo correctamente!</h5>
                        </div>
                        <br>
                        <div>
                            <p  style="font-size:18px;color:#333;font-weight: bold;padding-top: 5px;text-align: justify;padding-left: 4%; padding-right: 4%"><span  class="glyphicon glyphicon-exclamation-sign"></span>
                                Su solicitud "<strong style="color:#0c385e"><?php echo $nomSolicitud ?> </strong>" ha iniciado, por favor tenga en cuenta que las tarjetas no iniciarÃ¡n  su proceso de generaciÃ³n y envÃ­o
                                hasta que realice la respectiva factura, para ello puede dirigirse al modulo "GestiÃ³n de solicitudes y ordenes", generar la orden y factura correspondiente.
                            </p>
                            <br>
                            <div class="button col-sm-6 col-sm-push-3" >
                                <div class="row linkgenerico" style="/*padding-bottom: 100px; padding-left: 100px;*/">
                                    <a  href="/portal/abonos/abonoMasivo" class="spacing">ACEPTAR</a>
                                </div>
                            </div>
                            <br>
                            <br>
                            <br>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php } ?>  

<script type="text/javascript">
    var x = "<?= $ok ?>";
    if (x == "1") {
        $('#myModalok').modal('show');
    }
    $('.add').click(function () {
        $('.block:last').before('<div class="form-group"><div class="block col-sm-6 "> <select name="productos"><option value=""></option><?php foreach ($productos as $value) { ?><option><?= $value['NOMBRE_PRODUCTO'] ?></option><?php } ?></select></div> <a class="remove btn btn-danger"> X </a></div>');
    });
    $('.optionBox').on('click', '.remove', function () {
        $(this).parent().remove();
    });
</script>
<script type="text/javascript">
    $(document).ready(function () {
        $('#inputDepartamento').change(function () {

            $.ajax({
                url: "/portal/ajax/ciudad/" + $('#inputDepartamento').val()
            })
                    .done(function (msg) {
                        $('#inputCiudad').html(msg)
                    });
        });
    });


    let Checked = null;
    //The class name can vary
    for (let CheckBox of document.getElementsByClassName('only-one')) {
        CheckBox.onclick = function () {
            if (Checked != null) {
                Checked.checked = false;
                Checked = CheckBox;
            }
            Checked = CheckBox;
        }
    }

    var x = <?php
if (isset($_GET['ok'])) {
    echo "1;";
} else {
    echo "0;";
}
?>
    if (x == 1) {
        $('#myModalFinalizar').modal({backdrop: 'static', keyboard: false});
        $('#myModalFinalizar').modal('show');
    }

    var boton = document.getElementById("btnSubmitAbonoMa");
    boton.onclick = function (e) {
        var validado = $("#formAbonoMasivo").valid();
        if (validado) {
            e.preventDefault();
            $('#ModalConfAboMasi').modal('show');
        } else {
            e.preventDefault();
            $(".lblnoticarga").show();
            $('#loader').modal('hide');
        }
    };
    $("#formAbonoMasivo").submit(function () {
        $('#loader').modal('show');
    });
    $("#formNombreOrden").submit(function () {
        $('#loader').modal('show');
    });
</script>

