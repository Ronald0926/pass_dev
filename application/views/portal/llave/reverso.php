<style>
    .tnotifi{
        color: red;
        padding-left:  15px;
        display: none;
    }
</style>
<?php
//$rol = $this->session->userdata("rol");
$rol = $_SESSION['rol'];
?>
<div class="loader" id="loader" hidden=""></div>
<div class="col-lg-2"></div>
<div class="container col-lg-8" style=" margin-bottom: 200px; margin-top: -50px;">
    <hr style="border-top: 1px solid #eee0;">
    <h2 class="titulo-iz">Reverso tarjetas</h2>
     <?php if (isset($_GET['errordata'])) { ?>
        <div class="alert alert-info">
            <strong>No se ha seleccionado ningún producto</strong>
        </div>
    <?php } ?>
     <?php if (isset($_GET['error_rev_tar'])) { ?>
        <div class="alert alert-info">
            <strong>Error al reversar producto seleccionado.</strong>
        </div>
    <?php } ?>
    <ul class="nav nav-tabs">
        <?php if (($rol == 60) || ($rol == 61)) { ?>
        <li><a href="/portal/llaveMaestra/devolucion">Devolución</a></li>
        <?php } ?>
        <?php if (($rol == 60) || ($rol == 61)) { ?>
        <li><a href="/portal/llaveMaestra/solicitarDevolucion" >Solicitar devolución</a></li>
         <?php } ?>
        <?php if (($rol == 60) || ($rol == 61)) { ?>
            <li class="active"><a data-toggle="tab" href="#reversotar" >Reverso</a></li>
        <?php } ?>
    </ul>
    <div class="col-lg-3">
         <!--<p style=" text-align: left;font-size: 25px;color: #366199;font-weight: bold;">Seleccione la tarjeta a reversar:</p>-->
        <form action="/portal/llaveMaestra/returnReversoTar" method="POST">
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
    </div>
    <form method="POST" action="/portal/llaveMaestra/reverso">
        <div class="container col-lg-12">
            <input type="text" name="pk_llavero_codigo" id='pk_llavero_codigo' value="<?= $pk_llavero_codigo ?>" hidden>
            <div class="grid" id="reversotar">
                <table id="tblreverso" class="table table-hover daos_datagrid">
                    <thead>
                        <tr>
                            <th> <div class="login-checkbox " onclick="onAllTh()" id="chkTodo">
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
                            <th> Numero Tarjeta </th>
                            <th> Identificador </th>
                            <th> Concepto</th>
                            <th> Abono</th>
                            
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        foreach ($tarjetaEntidad as $value) {
                            ?>
                            <tr class="gradeC" >
                                <td>
                                    <div class="login-checkbox"  style="padding-top: 5px">
                                        <input name="tarjetasreverso[]" class="chkmasiva" value="<?= $value['ABOTAR_CODIGO'] ?>" type="checkbox" hidden>
                                        <span>
                                            <div class="">
                                                <span class="login-checkbox-check spnmasiva">
                                                </span>
                                            </div>
                                        </span>
                                    </div>
                                    <br>
                                </td>
                                <td><?= $value['NOMTAR'] ?></td>
                                <td><?= $value['ABR'] ?></td>
                                <td><?= $value['DOC'] ?></td>
                                <td><?= $value['NOMPRO'] ?></td>
                                <td><?= $value['NUMTAR'] ?></td>
                                <td><?= $value['IDENTIFICADOR']?></td>
                                <td><?= $value['NOM_CONCEPTO'] ?></td>
                                <td>$ <?= number_format($value['MONTO_ABONO'], 0, ',', '.'); ?></td>
                                <?php // } else { ?>
    <!--                                    <td>
                                    <div class="login-checkbox" onclick="" style="padding-top: 5px">
                                        <input name="usuarios[]" value="<?= $value['CODPROD'] ?>, <?= $value['CODTH'] ?>" type="checkbox">
                                        <input name="PK_TAR" value="<?= $value['CODPROD'] ?>" type="checkbox">
                                        <span id="<?= $value['CODPROD'] ?>">
                                            <div class="">
                                                <span class="login-checkbox-check " id="<?= $value['CODIGOORDEN'] ?>">
                                                </span>
                                            </div>
                                        </span>
                                    </div>
                                </td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td><?= $value['NOMPRO'] ?></td>
                                <td><?= $value['NUMTAR'] ?></td>
                                <td><?= $value['NOMBRE_LLAVERO'] ?></td>-->
                                <?php // } ?>


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
<div class="col-lg-2"></div>
<?php
if (isset($_GET['revOK'])) {
//    $correodest = $this->session->userdata('CORREO_DES_REVERSO');
    $correodest = $_SESSION["CORREO_DES_REVERSO"];
    ?>
    <!-- Modal error-->
    <div class="modal fade" id="Modalcodauto" role="dialog" style="margin-top: 15%;"  data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog">

            <!-- Modal content-->
            <div class="modal-content" style="border-radius:35px">
            <button class="btn_cerrar_modal" data-dismiss="modal"></button>
                <div class="modal-body" style="text-align: center;height: 270px;">
                    <form  method="POST" action="/portal/llaveMaestra/verificar_codigo_reverso" id="enviocodigoconf">
                        <div class="modal-header">
                            <h5 style="color: #366199;font-size: 20px;font-weight: bold; ">Código de confirmación</h5>
                        </div>
                        <p  style="font-size:15px;color:#888686;font-weight: bold;padding-top: 5px">Hemos enviado el código de confirmación a su correo electrónico <?php echo $correodest ?> <!-- o como SMS -->
                        </p>
                        <?php if (isset($_GET['error'])) { ?>
                            <label style="color: #FF0000" class="oblique">Código incorrecto </label>
                        <?php } echo '<br>' ?>

                        <input type="text" name="codigoconfirmacion" style="width: 60%" placeholder="Digite código de confirmacón"  required>


                        <div style="">
                            <div class="button col-sm-6 col-sm-push-3" >
                                <button type="submit" name="CONFCARGA" value="1" class="btn btn-default spacing">REVERSAR</button>
                            </div>
                        </div>
                        <br>
                    </form>
                </div>
            </div>
        </div>
    </div>
<?php } ?>
<?php if (isset($_GET['revsuccessful'])) { ?>
    <!-- Modal confirmacion recarga-->
    <div class="modal fade" id="ModalReversoExitoso" role="dialog" style="margin-top: 15%;"  data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog">

            <!-- Modal content-->
            <div class="modal-content" style="border-radius:35px">

                <div class="modal-body" style="text-align: center;height: 200px;">

                    <p  style="font-size:24px;color:#888686;font-weight: bold;padding-top: 25px">Reverso realizado exitosamente
                    </p>
                    <div style="">
                        <div class="button col-sm-6 col-sm-push-3" >
                            <div class="row linkgenerico" style="/*padding-bottom: 100px; padding-left: 100px;*/">
                                <a  href="/portal/llaveMaestra/reverso" class="spacing">ACEPTAR</a>
                            </div>
                        </div>
                    </div>
                    <br>
                </div>
            </div>
        </div>
    </div>
<?php } ?>
<?php if (isset($_GET['errorlimitereverso'])) { ?>
    <!-- Modal confirmacion recarga-->
    <div class="modal fade" id="ModalErrorlimite" role="dialog" style="margin-top: 15%;"  data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog">

            <!-- Modal content-->
            <div class="modal-content" style="border-radius:35px">

                <div class="modal-body" style="text-align: center;height: 200px;">

                    <p  style="font-size:24px;color:#888686;font-weight: bold;padding-top: 25px">El monto excede el valor limite de reversos diarios permitidos.
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
<?php if (isset($_GET['errorsalta'])|| isset($_GET['errordifsalabono'])) { ?>
    <!-- Modal confirmacion recarga-->
    <div class="modal fade" id="ModalErrorSalTar" role="dialog" style="margin-top: 15%;"  data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog">

            <!-- Modal content-->
            <div class="modal-content" style="border-radius:35px">

                <div class="modal-body" style="text-align: center;height: 200px;">
                    
                    <p  style="font-size:24px;color:#888686;font-weight: bold;padding-top: 25px">
                        <?php echo (isset($_GET['errorsalta'])) ? 'Alguna tarjeta no posee los fondos suficientes para realizar la transacción.':'Uno o varios montos a reversar superan el abono realizado.' ?>
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
   

<script>
    var errsal = <?php if (isset($_GET['errorsalta'])) {   echo "1;";} else {    echo "0;";}?>
    var errsalabo = <?php if (isset($_GET['errordifsalabono'])) {   echo "1;";} else {    echo "0;";}?>
    var x = <?php if (isset($_GET['errorpkllavero'])) {   echo "1;";} else {    echo "0;";}?>
    var errorlimite = <?php if (isset($_GET['errorlimitereverso'])) {
    echo "1;";
} else {
    echo "0;";
} ?>
    var reversoOK = <?php
if (isset($_GET['revOK'])) {
    echo "1;";
} else {
    echo "0;";
}
?>
    var revsucc = <?php
if (isset($_GET['revsuccessful'])) {
    echo "1;";
} else {
    echo "0;";
}
?>
    $(document).ready(function () {
        $('body').on('click', '.checks span', function () {
            var id = $(this).attr('id');
            if (id != undefined) {

                var y = $('.noncheck');
                for (i = 0; i < y.length; i++) {
//                    console.log(id + " " + y[i].id)
                    if (y[i].id != id) {
                        y[i].style.display = "none";
                    }
                }
            }
        });


    });
    if ( errsal === 1 || errsalabo===1) {
        $("#ModalErrorSalTar").modal('show');
    }
    if ( x === 1) {
        $(".tnotifi").show();
    }
    if (reversoOK === 1) {
        $('#Modalcodauto').modal('show');
    }
    if (revsucc === 1) {
        $('#ModalReversoExitoso').modal('show');
    }
    $("#enviocodigoconf").submit(function () {
        $('#loader').modal('show');
    });
    if (errorlimite == 1) {
        $('#ModalErrorlimite').modal('show');
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
    };
</script>

