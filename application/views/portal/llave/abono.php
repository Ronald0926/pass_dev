<style>
    #masivoIconos td,
    th {
        padding: 30px;
    }
    .lblsaldoabono{
        margin-top: 2%;
        margin-left: 25%;
        width: 50%;
        text-align: center;
        background-color: #e3e3e3;
        font-size: 15px;
        font-weight: bold;   
        padding: 8px;
        color: #888;
        border: 1px solid;
        border-color: #979797;
        border-radius: 25px;
    }
    .tnotifi{
        color: red;
        padding-left:  15px;
        display: none;
    }
</style>
<div class="loader" id="loader" hidden=""></div>
<div class="col-lg-2"></div>
<div class="container col-lg-8" style=" margin-bottom: 200px; margin-top: -50px;">
    <hr style="border-top: 1px solid #eee0;">
    <h2 class="titulo-iz">Abono Tarjetas</h2>
    <?php if (isset($_GET['errordata'])) { ?>
        <div class="alert alert-info">
            <strong>No se ha seleccionado ningún producto</strong>
        </div>
    <?php } ?>
     <?php if (isset($_GET['errorAbono'])) { ?>
        <div class="alert alert-danger">
            <strong>Error abonando al producto.</strong>
        </div>
    <?php } ?>
    <ul class="nav nav-tabs">
        <li class="active"><a data-toggle="tab" href="#solicitudUno" style="/*border-top-left-radius:20px;border-top-right-radius:20px;background-color:#fdc500;color:#19548e;height:35px*/">Abono uno a uno</a></li>
        <li><a href="/portal/llaveMaestra/abonoMasivo">Abono Masivo</a></li>
    </ul>
    <div class="col-lg-3">
        <form action="/portal/llaveMaestra/abonoreturntarjellavero" method="POST">
            <div class="row"><label class="tnotifi"> Por favor seleccione un llavero.</label></div>
            <div class="select">
                <select name="pk_llavero" id="llavero"  required onchange="this.form.submit();">
                    <option value=""> Seleccione Llavero</option>
                    <?php foreach ($llaveros as $key => $value) { ?>
                        <option value="<?= $value['PK_LLAVERO_CODIGO'] ?>" <?php if ($value['PK_LLAVERO_CODIGO'] == $pk_llavero_codigo) echo 'selected'; ?>> <?= ucwords(strtolower($value['NOMBRE_LLAVERO'])) ?></option>
                    <?php } ?>
                </select>
                <div> <?php echo $nombrellaveroselect != "" ? $nombrellaveroselect : 'Seleccione Llavero*' ?></div>
            </div>
        </form>
        <div><label class="lblsaldoabono"><span>$ <?= number_format($saldo_llavero, 0, ',', '.'); ?></span></label></div>
    </div>
    <div class="tab-content">
        <div id="solicitudUno" class="tab-pane fade in active">
            <form method="POST" action="/portal/llaveMaestra/abonounoauno">
                <input type="text" name="pk_llavero_codigo" id='pk_llavero_codigo' value="<?= $pk_llavero_codigo ?>" hidden>
                <div class="container col-lg-12">
                    <div class="grid">
                        <table class="table table-hover daos_datagrid">
                            <thead>
                                <tr>
                                    <th>
                                        <div class="login-checkbox " onclick="onAllTh()" id="chkTodo">
                                            <input type="checkbox" id="chkMasivaAll">
                                            <span>
                                                <div class="">
                                                    <span class="login-checkbox-check">
                                                    </span>
                                                </div>
                                            </span>
                                        </div>
                                    </th>
                                    <th> Nombre </th>
                                    <th> T.D. </th>
                                    <th> No.Doc </th>
                                    <th> Producto </th>
                                    <th> Número Tarjeta </th>
                                    <th> Identificador </th>
                                    <th> Custodio </th>
                                    <th> Campaña </th>
                                    <th> Ciudad </th>
                                    <!--<th> Fecha de dispersión </th>-->
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $documento;
                                //$pedidoAbono = $this->session->userdata("pedidoAbono");
                                $pedidoAbono = $_SESSION['pedidoAbono'];
                                foreach ($tarjetallavero as $value) {
                                    ?>
                                    <tr class="gradeC">
                                        <td>
                                            <div class="login-checkbox" onclick="" style="padding-top: 5px">
                                                <input name="tarjetasabono[]" class="chkmasiva" value="<?= $value['PK_TARJET_CODIGO'] ?>" type="checkbox">
                                                <span>
                                                    <div class="">
                                                        <span class="login-checkbox-check spnmasiva">
                                                        </span>
                                                    </div>
                                                </span>
                                            </div>
                                        </td>
                                        <td><?= $value['NOMTAR'] ?></td>
                                        <td><?= $value['ABR'] ?></td>
                                        <td><?= $value['DOC'] ?></td>
                                        <td><?= $value['NOMPRO'] ?></td>
                                        <td><?= $value['NUMTAR'] ?></td>
                                        <td><?= $value['IDENTIFICADOR'] ?></td>
                                        <td><?= $value['NOMCUSTODIO'] ?></td>
                                        <td><?= $value['NOMCAMPANA'] ?></td>
                                        <td><?= $value['CIUDAD'] ?></td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>

                    </div>
                    <div class="button col-md-4 col-md-push-4">
                        <button type="submit" class="spacing">SIGUIENTE</button>
                    </div>
                </div>

            </form>
        </div>
    </div>
</div>

<?php
if (isset($_GET['abonoOK'])) {
//    $correodest = $this->session->userdata('CORREO_DES_ABONO');
    $correodest = $_SESSION["CORREO_DES_ABONO"];
    ?>
    <!-- Modal error-->
    <div class="modal fade" id="Modalcodauto" role="dialog" style="margin-top: 15%;"  data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog">

            <!-- Modal content-->
            <div class="modal-content" style="border-radius:35px">
                <button class="btn_cerrar_modal" data-dismiss="modal"></button>
                <div class="modal-body" style="text-align: center;height: 270px;">
                    <form  method="POST" action="/portal/llaveMaestra/verificar_codigo_abono" id="formverificarcodigo">
                        <div class="modal-header">
                            <h5 style="color: #366199;font-size: 20px;font-weight: bold; ">Código de confirmación</h5>
                        </div>
                        <p  style="font-size:15px;color:#888686;font-weight: bold;padding-top: 5px">Hemos enviado el código de confirmación a su correo electrónico <?php echo $correodest ?> <!--o como SMS -->
                        </p>
                        <?php if (isset($_GET['error'])) { ?>
                            <label style="color: #FF0000" class="oblique">Código incorrecto </label>
                        <?php } echo '<br>' ?>

                        <input type="text" name="codigoconfirmacion" style="width: 60%" placeholder="Digite código de confirmacón"  required>


                        <div style="">
                            <div class="button col-sm-6 col-sm-push-3" >
                                <button type="submit" name="CONFCARGA" value="1" class="btn btn-default spacing">ABONAR</button>
                            </div>
                        </div>
                        <br>
                    </form>
                </div>
            </div>
        </div>
    </div>
<?php } ?>
<?php if (isset($_GET['abosuccessful'])) { ?>
    <!-- Modal confirmacion recarga-->
    <div class="modal fade" id="ModalAbonoExitoso" role="dialog" style="margin-top: 15%;"  data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog">

            <!-- Modal content-->
            <div class="modal-content" style="border-radius:35px">

                <div class="modal-body" style="text-align: center;height: 200px;">

                    <p  style="font-size:24px;color:#888686;font-weight: bold;padding-top: 25px">El abono fue realizado exitosamente
                    </p>
                    <div style="">
                        <div class="button col-sm-6 col-sm-push-3" >
                            <button name="aceptar" data-dismiss="modal" class="btn btn-default spacing">ACEPTAR</button>
                        </div>
                    </div>
                    <br>
                </div>
            </div>
        </div>
    </div>
<?php } ?>

<?php if (isset($_GET['errorlimiteabono'])) { ?>
    <!-- Modal confirmacion recarga-->
    <div class="modal fade" id="ModalErrorlimite" role="dialog" style="margin-top: 15%;"  data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog">

            <!-- Modal content-->
            <div class="modal-content" style="border-radius:35px">

                <div class="modal-body" style="text-align: center;height: 200px;">

                    <p  style="font-size:24px;color:#888686;font-weight: bold;padding-top: 25px">El monto excede el valor limite de abonos diarios permitidos.
                    </p>
                    <div style="">
                        <div class="button col-sm-6 col-sm-push-3" >
                            <button name="aceptar" data-dismiss="modal" class="btn btn-default spacing">ACEPTAR</button>
                        </div>
                    </div>
                    <br>
                </div>
            </div>
        </div>
    </div>
<?php } ?>
<?php if (isset($_GET['errorsaldoinsu'])) { ?>
    <!-- Modal error-->
    <div class="modal fade" id="ModalerrorTX" role="dialog" style="margin-top: 15%;"  data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog">

            <!-- Modal content-->
            <div class="modal-content" style="border-radius:35px">

                <div class="modal-body" style="text-align: center;height: 200px;">
                    <div class="modal-header">
                        <h5 style="color: #366199;font-size: 20px;font-weight: bold; ">Fondos insuficientes</h5>
                    </div>
                    <p  style="font-size:18px;color:#888686;font-weight: bold;padding-top: 10px">
                        Saldo insuficiente para realizar la transacción
                    </p>
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

<script type="text/javascript">
    var errorlimite = <?php
if (isset($_GET['errorlimiteabono'])) {
    echo "1;";
} else {
    echo "0;";
}
?>
     var errorsaldoinsu = <?php if (isset($_GET['errorsaldoinsu'])) {
    echo "1;";
} else {
    echo "0;";
} ?>
    var abosuccessful = <?php
if (isset($_GET['abosuccessful'])) {
    echo "1;";
} else {
    echo "0;";
}
?>
    var abonook = <?php
if (isset($_GET['abonoOK'])) {
    echo "1;";
} else {
    echo "0;";
}
?>
    var x = <?php
if (isset($_GET['errorpkllavero'])) {
    echo "1;";
} else {
    echo "0;";
}
?>
    var errorpkcodigo = <?php
if (isset($errorpkllavero)) {
    echo "1;";
} else {
    echo "0;";
}
?>
    if (errorlimite == 1) {
        $('#ModalErrorlimite').modal('show');
    }
    if (abosuccessful == 1) {
        $('#ModalAbonoExitoso').modal('show');
    }
    if (abonook == 1) {
        $('#Modalcodauto').modal('show');
    }
    if (errorpkcodigo === 1 || x === 1) {
        $(".tnotifi").show();
    }
     if (errorsaldoinsu == 1) {
         $('#ModalerrorTX').modal('show');
     }
    if ($('#pk_llavero_codigo').val() != '') {
        $(".tnotifi").hide();
    }
    function onAllTh() {
        if (!$("#chkMasivaAll").prop("checked")) {
            $(".spnmasiva").each(function () {
                $(this).css("display", "inline");
            });
            $(".chkmasiva").each(function () {
                $(this).prop('checked', true);
            });
        } else {
            $(".spnmasiva").each(function () {
                $(this).css("display", "none");
            });
            $(".chkmasiva").each(function () {
                $(this).prop('checked', false);
            });
        }
    }
    ;

    $("#formverificarcodigo").submit(function () {
        $('#loader').modal('show');
    });
</script>