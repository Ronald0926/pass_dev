<style>
    .menos{
        color:red !important;
    }
    .mas{
        color:green !important;
    }

</style>
<div class="loader" id="loader" hidden=""></div>
<div class="col-lg-2" ></div>

<div class="container col-lg-8" style=" margin-bottom: 200px; margin-top: -50px;">
    <hr style="border-top: 1px solid #eee0;">
    <h2 class="titulo-iz">Legalizaciones </h2>
    <?php if (isset($_GET['errordata'])) { ?>
        <div class="alert alert-info">
            <strong>No se ha seleccionado ningún producto</strong>
        </div>
    <?php } ?>

    <form  method="POST" id="formAprobarLega">
        <?php if($pk_linpro_codigo==2) {?>
        <label class="lbl_linea">Saldo disponible: <br> <span>$ <?= number_format($saldotarjeta, 0, ',', '.'); ?></span></label>
        <?php } ?>
        <label class="lbl_linea" style="padding-left: 5%"><?= $producto ?> <br> <span><?= $numtar ?></span></label>
        <label class="lbl_linea" style="padding-left: 5%">Monto abonado: <br> <span>$ <?= number_format($montoabono, 0, ',', '.'); ?></span></label>
        <label class="lbl_linea" style="padding-left: 5%;margin-bottom: 30px">Nombre llavero: <br> <span> <?= isset($nombre_llavero) ? $nombre_llavero : '' ?></span></label>
        <div class="container col-lg-12">
            <div class="grid" style="margin: 2%;">
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
                            <th> Fecha </th>
                            <th> Monto a legalizar </th>
                            <th> Estado</th>
                            <th> Url soporte</th>
                        </tr>
                    </thead>
                    <?php
                    foreach ($legalizacionesabono as $value) {
                        ?>
                        <tbody>
                            <tr>
                                <td>
                                    <?php if ($value['PK_LEGA'] != 1) { ?>
                                        <div class="login-checkbox"  style="padding-top: 5px">
                                            <input name="codigos[]" class="chkmasiva" value="<?= $value['PK_LEGALIZACION_CODIGO'] ?>,<?= $pk_abono ?>" type="checkbox">
                                            <span>
                                                <div class="">
                                                    <span class="login-checkbox-check spnmasiva">
                                                    </span>
                                                </div>
                                            </span>
                                        </div>
                                    <?php } ?>
                                </td>
                                <td><?= $value['FECHA_CREACION'] ?></td>
                                <td>$ <?= number_format($value['MONTO_LEGALIZADO'], 0, ',', '.'); ?></td>

                                <?php if ($value['PK_LEGA'] == 1) { ?>
                                    <td class="mas"><?= $value['ESTADO'] ?></td>
                                <?php } elseif ($value['PK_LEGA'] == 2 || $value['PK_LEGA'] == 3) { ?>  
                                    <td class="menos"><?= $value['ESTADO'] ?></td>
                                <?php } ?>
                                <td>
                                    <a href='<?= $value['URL_SOPORTE'] ?>' target="_blank">
                                        Ver soporte
                                    </a>
                                </td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>                     
        </div> 
    </form>
    <div class="button  col-md-2 col-md-push-5">
        <button class="spacing" data-toggle="modal" data-target="#ModalConfAproba">APROBAR</button>
        <br>
        <br>
        <form action="/portal/llaveMaestra/returnabonosllavero" method="POST">
            <input name="llavero"  value="<?= $pk_llavero ?>" hidden>
            <div class=" linkgenerico spacing">
                <button  type="submit" class="btn btn-default spacing">VOLVER</button>
                <!--<a href="/portal/llaveMaestra/informeAbonos">VOLVER</a>-->
            </div>
        </form>
    </div>
</div>

<div class="col-lg-2" ></div>
<!-- Modal confirmar aprobacion-->
<div class="modal fade" id="ModalConfAproba" role="dialog" style="margin-top: 15%;"  data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content" style="border-radius:35px">

            <div class="modal-body" style="text-align: center;height: 230px;">

                <p  style="font-size:24px;color:#888686;font-weight: bold;padding-top: 25px">¿Esta seguro desea aprobar estas legalizaciones?
                </p>
                <label id="nomllavero" style="font-size: 18px;color: #366199;font-weight: bold;"></label>

                <div style="">
                    <div class="button col-sm-6" >
                        <button type="button" name="ACEPTAR" value="1" class="btn btn-default"  onclick="
                                $('#formAprobarLega').submit();" >A C E P T A R</button>
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
<?php if (isset($excedeAbono) == 1) { ?>
    <!-- Modal confirmacion recarga-->
    <div class="modal fade" id="ModalExcede" role="dialog" style="margin-top: 15%;"  data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog">

            <!-- Modal content-->
            <div class="modal-content" style="border-radius:35px">

                <div class="modal-body" style="text-align: center;height: 220px;">
                    <p style="font-size:24px;color:#366199;font-weight: bold">¡Solicitud rechazada!</p>
                    <p  style="font-size:20px;color:#888686;font-weight: bold;padding-top: 15px">La solicitud fue rechazada debido a que el monto a legalizar excede el abono realizado.
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

<script type="text/javascript">
    var excede =<?= isset($excedeAbono) == 1 ? 1 : 0 ?>;
    if (excede == 1) {
        $('#ModalExcede').modal('show');
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

    $("#formAprobarLega").submit(function () {
        $('#loader').modal('show');
    });
</script>