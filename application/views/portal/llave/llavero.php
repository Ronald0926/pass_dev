<link href="https://gitcdn.github.io/bootstrap-toggle/2.2.2/css/bootstrap-toggle.min.css" rel="stylesheet">
<script src="https://gitcdn.github.io/bootstrap-toggle/2.2.2/js/bootstrap-toggle.min.js"></script>
<style>
    .tnotifi{
        color: red;
        padding-left:  15px;
        display: none;
    }
    .lblnoticarga{
        color: red;
        padding-left:  15px;
        padding-bottom: 0px;
        display: none;
    }
    .label_carga{
        font-size: 15px;
        font-weight: bold;   
        padding: 8px;
        color: #366199;
        border: 1px solid;
        border-color: #366199;
        border-radius: 25px;
    }
    .nom_llavero_carga{
        text-align: center ;
        font-size: 25px;
        font-weight: bold; 
        color: #366199;
        padding-bottom: 35px;
    }
    .label_saldo_in{
        background-color: #e3e3e3;
        font-size: 15px;
        font-weight: bold;   
        padding: 8px;
        color: #888;
        border: 1px solid;
        border-color: #979797;
        border-radius: 25px;
    }
</style>
<div class='row'>
    <div class="col-4"></div>
    <?php
//$rol = $this->session->userdata("rol");
$rol = $_SESSION['rol'];
?>
    <?php if (isset($carga_llavero) == 1) {
        ?> 
        <div class='col-4' style="margin-bottom: 5%">
            <h1 class="titulo">Cargar  llavero</h1><!--action="/portal/llaveMaestra/cargallavero" -->

            <form  method="POST" id="formcargarllavero">
                <input type="number" name="pk_llavero" value="<?= $pk_llavero ?>" hidden>
                <input name="carga_llavero" value="1" hidden>
                <input type="text" style="padding-left:10px" name="nombllavero" id="nombllavero" placeholder="Nombre llavero*" value="<?= $nomllavero ?>" hidden><br>
                <div style="text-align: center">
                    <label class="nom_llavero_carga"><?= $nomllavero ?></label>
                </div>

                <div>
                    <label style="margin-top: 10px; font-size: 15px;padding-left:  8px">Coordinador responsable</label>
                    <div class="label_carga">
                        <?php echo $nomcoordinador != "" ? $nomcoordinador : 'Error' ?>
                    </div>
                    <label style="margin-top: 10px;font-size: 15px;padding-left:  8px">Administrador de pagos</label>
                    <div class="label_carga">
                        <?php echo $nomadmpago != "" ? $nomadmpago : 'Error' ?>
                    </div>
                    <div class="label_saldo_in" style="text-align: center;margin-top: 20px" > 
                        <label style=""> $ <span  id="saldoanterior"></span></label>
                    </div>
                    <label style="margin-top: 10px;font-size: 12px;color:#366199;padding-left:8px;">Valor Carga</label>
                    <div class="row" style="padding-left:8px;"><label class="lblnoticarga"> Por favor ingrese valor.</label></div>
                    <input class="label_carga numPat" name="valorCarga" style="text-align: center" id="valorCarga" placeholder="Ingrese valor a cargar*" required>

                </div>
                <br>
        <!--<input class="numPat" type="text" style="padding-left:10px" name="monto" placeholder="Monto asignado*" value="" hidden>-->

            </form>
            <div class="button  col-md-6 col-md-push-3">
                <button class="spacing" data-toggle="modal" data-target="#ModalConfCarga">
                    CARGAR
                </button>
                <br><br>
                <div class=" linkgenerico spacing">
                    <a href="/portal/llaveMaestra/gestion_llaveros">VOLVER</a>
                </div>
            </div>

        </div>
        <!-- Modal confirmacion-->
        <div class="modal fade" id="ModalConfCarga" role="dialog" style="margin-top: 15%;"  data-backdrop="static" data-keyboard="false">
            <div class="modal-dialog">

                <!-- Modal content-->
                <div class="modal-content" style="border-radius:35px">

                    <div class="modal-body" style="text-align: center;height: 230px;">

                        <p  style="font-size:24px;color:#888686;font-weight: bold;padding-top: 25px">¿Esta seguro de realizar esta recarga?
                        </p>
                        <label id="nomllavero" style="font-size: 18px;color: #366199;font-weight: bold;"></label>

                        <div style="">
                            <div class="button col-sm-6" >
                                <button type="button" name="ACEPTAR" value="1" class="btn btn-default"  onclick="
                                            $('#formcargarllavero').submit();" >A C E P T A R</button>
                            </div>
                            <div class="button col-sm-6" >
                                <button type="button" name="CANCELAR" class="btn btn-default" data-dismiss="modal">C A N C E L A R</button>
                            </div>
                        </div>
                        <br>
                    </div>
                </div>
            </div>
        </div>
        <?php if (isset($error_tx)) { ?>
            <!-- Modal error-->
            <div class="modal fade" id="ModalerrorTX" role="dialog" style="margin-top: 15%;"  data-backdrop="static" data-keyboard="false">
                <div class="modal-dialog">

                    <!-- Modal content-->
                    <div class="modal-content" style="border-radius:35px">

                        <div class="modal-body" style="text-align: center;height: 230px;">
                            <div class="modal-header">
                                <h5 style="color: #366199;font-size: 20px;font-weight: bold; ">Fondos insuficientes</h5>
                            </div>
                            <p  style="font-size:24px;color:#888686;font-weight: bold;padding-top: 25px"><?php echo $error_tx ?>
                            </p>
                            <label id="nomllavero" style="font-size: 18px;color: #366199;font-weight: bold;"></label>

                            <div style="">
                                <div class="button col-sm-6 col-sm-push-3" >
                                    <button type="button" name="CANCELAR" class="btn btn-default spacing" data-dismiss="modal">ACEPTAR</button>
                                </div>
                            </div>
                            <br>
                        </div>
                    </div>
                </div>
            </div>
        <?php } ?>
        <?php if (isset($cod_con)) { ?>
            <!-- Modal error-->
            <div class="modal fade" id="Modalcodauto" role="dialog" style="margin-top: 15%;"  data-backdrop="static" data-keyboard="false">
                <div class="modal-dialog">

                    <!-- Modal content-->
                    <div class="modal-content" style="border-radius:35px">

                        <div class="modal-body" style="text-align: center;height: 270px;">
                            <form  method="POST" >
                                <div class="modal-header">
                                    <h5 style="color: #366199;font-size: 20px;font-weight: bold; ">Código de confirmacón</h5>
                                </div>
                                <p  style="font-size:15px;color:#888686;font-weight: bold;padding-top: 5px">Hemos enviado el código de confirmación a su correo electrónico <?php echo $correodest ?> o como SMS
                                </p>
                                <input type="hidden" name="cod_mov_llave" value="<?= $cod_mov_llave ?>">
                                <input type="hidden" name="cod_mov_llavero" value="<?= $cod_mov_llavero ?>">
                                <input type="text" name="codigoconfirmacion" style="width: 60%" placeholder="Digite código de confirmacón"  required>


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
        <?php } ?>
    <?php } else { ?>
        <div class='col-4'>
            <?php if ($error == 1) { ?>
                <div class="alert alert-danger">
                    <strong>Por favor complete los campos !</strong>
                </div>
            <?php } if (isset($ErrorCreando)) { ?>
                <div class="alert alert-danger">
                    <strong> <?= $ErrorCreando ?></strong>
                </div>
            <?php } ?>
            <h1 class="titulo"><?= isset($pk_llavero) ? 'Modificar' : 'Crear' ?>  llavero</h1>
            <form method="POST" id="formllavero">
                <input type="number" name="pk_llavero" value="<?= $pk_llavero ?>" hidden>
                <input type="text" style="padding-left:10px" name="nombllavero" id="nombllavero" placeholder="Nombre llavero*" value="<?= $nomllavero ?>" required><br>

                <div class="portal-paddin-0" style="padding-left: 0px;">
                    <label style="margin-top: 10px">Legalizador responsable</label>
                    <div class="select " style="margin-top: -1px">
                        <select name='coordinador'   required onchange="getval(this);">
                            <option value="">Seleccione el Legalizador*</option>
                            <?php foreach ($coordinadores as $key => $value) { ?>
                                <option value="<?= $value['PK_ENT_CODIGO'] ?>" <?php if ($value['PK_ENT_CODIGO'] == $coordinador) echo 'selected'; ?>> <?= ucfirst(strtolower($value['NOMBRE'])) ?> </option>
                            <?php } ?>
                        </select>
                        
                        <div>
                            <?php echo $nomcoordinador != "" ? $nomcoordinador : 'Seleccione el Legalizador*' ?>
                        </div>
                    </div>
                                            
                    <h5 style="font-size: 16px;color: #366199;margin-top: 5px;padding: 0px;"><a style="font-weight: bold;" href="/portal/usuariosCreacion/crear">                              
                            Crear nuevo Legalizador</a> </h5>
                </div>
                                
           <!--
                <div class="portal-paddin-0" style="padding-left: 0px;">
                    <label style="margin-top: 10px">Administrador de pagos</label>
                    <div class="select " style="margin-top: -1px">
                        <select name='admpagos' required >
                            <option value="">Seleccione el administrador*</option>
                            <?php foreach ($administradores as $key => $value) { ?>
                                <option value="<?= $value['PK_ENT_CODIGO'] ?>" <?php if ($value['PK_ENT_CODIGO'] == $adminpagos) echo 'selected'; ?>> <?= ucfirst(strtolower($value['NOMBRE'])) ?></option>
                            <?php } ?>
                        </select>
                        <div>
                            <?php echo $nomadmpago != "" ? $nomadmpago : 'Seleccione el administrador*' ?>
                        </div>
                    </div>
                </div>
                -->
                               
               <!--<input class="numPat" type="text" style="padding-left:10px" name="monto" placeholder="Monto asignado*" value="" hidden>-->
                <h4>Tipo de notificación * </h4>
                <div class="row"><label class="tnotifi"> Por favor elija una opción de notificatión.</label></div>
                <div style="float:left">
                    <label>Correo</label><br>
                    <input type="checkbox" <?php if ($valchcorreo == 'on') echo 'checked'; ?> name="ckcorreo"  id="ckcorreo" data-toggle="toggle">
                    <h5 style="font-size: 16px;color: #366199;margin-top: 5px;padding: 0px;" id="correoDest"></h5>
                </div>

                <div style="float:left;margin-left: 10%" hidden>
                    <label>SMS</label><br>
                    <input type="checkbox" name="cksms" <?php if ($valchsms == 'on') echo 'checked'; ?>  id="cksms" data-toggle="toggle">
                </div>
            </form>
            <div class="form-inline col-md-12">
                <div class="button  col-md-6 col-md-push-3">
                    <button class="spacing" data-toggle="modal" data-target="#Modalconf" id="validate">
                        <?= isset($pk_llavero) ? 'MODIFICAR' : 'CREAR' ?>
                    </button>
                    <br>
                    <br>
                    <div class=" linkgenerico spacing">
                        <a href="/portal/llaveMaestra/gestion_llaveros">VOLVER</a>
                    </div>
                </div>
            </div>
        </div>
    <?php } ?>
    <div class="col-4"></div>
</div>

<!-- Modal confirmacion-->
<div class="modal fade" id="Modalconf" role="dialog" style="margin-top: 15%;"  data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content" style="border-radius:35px">
            <div class="modal-body" style="text-align: center;height: 230px;">

                <p  style="font-size:24px;color:#888686;font-weight: bold;padding-top: 25px">¿Desea <?= isset($pk_llavero) ? 'actualizar' : 'crear' ?> este llavero?
                </p>
                <label id="nomllavero" style="font-size: 18px;color: #366199;font-weight: bold;"></label>

                <div style="">
                    <div class="button col-sm-6" >
                        <button type="button" name="ACEPTAR" value="1" class="btn btn-default"  onclick="
                                $('#formllavero').submit();" >A C E P T A R</button>
                    </div>
                    <div class="button col-sm-6" >
                        <button type="button" name="CANCELAR" class="btn btn-default" data-dismiss="modal">C A N C E L A R</button>
                    </div>
                </div>
                <br>
            </div>
        </div>
    </div>
</div>
<!-- Modal creacion exitosa-->
<div class="modal fade" id="modalsuccess" role="dialog" style="    margin-top: 15%;">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content" style="border-radius:35px">

            <div class="modal-body" style="text-align: center;height: 200px;">

                <p  style="font-size:24px;color:#888686;font-weight: bold;padding-top: 25px">El llavero fue <?= $success == 2 ? 'actualizado' : 'creado' ?> correctamente
                </p>

                <div style="padding-top: 25px">
                    <form method="post" action="/portal/llaveMaestra/gestion_llaveros">
                        <div class="button col-sm-6 col-md-push-3" >
                            <button type="submit" name="ACEPTAR" value="1" class="btn btn-default" >A C E P T A R</button>
                        </div>
                    </form>
                </div>
                <br>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    var success = <?php
    if (isset($success)) {
        echo "1;";
    } else {
        echo "0;";
    }
    ?>
    var carga_llavero =<?php
    if (isset($carga_llavero) == 1) {
        echo "1;";
    } else {
        echo "0;";
    }
    ?>
    if (carga_llavero != 1) {
        $(document).ready(function () {

            var error = '<?= $error ?>';
            if (error != null && error != '') {
            }
            if (success == 1) {
                $('#modalsuccess').show();
                $('#modalsuccess').modal({backdrop: 'static', keyboard: false});
            }

            var boton = document.getElementById("validate");
            boton.onclick = function (e) {
                document.getElementById('nomllavero').innerHTML = $("#nombllavero").val();
            };
            $('#ckcorreo').change(function () {
                if (!$(this).is(":checked") && !$('#cksms').is(":checked")) {
                    $(".tnotifi").show();
                } else {
                    $(".tnotifi").hide();
                }
            });
            $('#cksms').change(function () {
                if (!$(this).is(":checked") && !$('#ckcorreo').is(":checked")) {
                    $(".tnotifi").show();
                } else {
                    $(".tnotifi").hide();
                }
            });
            if ($('input:checkbox[name=ckcorreo]:checked').val() != 'on' && $('input:checkbox[name=cksms]:checked').val() != 'on') {
                $(".tnotifi").show();
            }

        });
    }//fin if carga llavero
    else {
        var errortx = <?php
    if (isset($error_tx)) {
        echo "1;";
    } else {
        echo "0;";
    }
    ?>
        var confcargallavero = <?php
    if (isset($cod_con)) {
        echo "1;";
    } else {
        echo "0;";
    }
    ?>
        var error_carga = '<?= $error_carga ?>';
        if (error_carga != null && error_carga != '') {
            $(".lblnoticarga").show();
        }
    }

</script>
<script>
    var valorantllavero =<?php echo isset($saldo_llavero) ? $saldo_llavero : '0'; ?>;
    $(document).ready(function () {
        var total1 = currencyFormat(valorantllavero);
        $('#saldoanterior').text(total1);

    });
    function currencyFormat(num) {
        return  parseFloat(num).toFixed(0).replace('.', ',').replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1.');
    }
    if (errortx === 1) {
        $("#ModalerrorTX").modal("show");
    }
    if (confcargallavero === 1) {
        $("#Modalcodauto").modal("show");
    }
    function getval(sel)
    {
        var ent = parseInt(sel.value);
<?php foreach ($coordinadores as $value) { ?>
            if (<?= $value['PK_ENT_CODIGO'] ?> === ent) {
                var correo = 'Las notificaciones serán enviadas al correo:' + '<br>' + ('<?= $value['CORREO_ELECTRONICO'] ?>').toLowerCase();
                $('#correoDest').html(correo);
            }
<?php } ?>
    }
</script>