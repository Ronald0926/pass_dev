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
<?php 
//$rol = $this->session->userdata("rol");
$rol = $_SESSION['rol'];
?>
<div class="loader" id="loader" hidden=""></div>
<div class="col-lg-2"></div>
<div class="container col-lg-8" style=" margin-bottom: 200px; margin-top: -50px;">
    <hr style="border-top: 1px solid #eee0;">
    <h2 class="titulo-iz">Solicitar devolución</h2>
     <?php if (isset($_GET['errordata'])) { ?>
        <div class="alert alert-info">
            <strong>No se ha seleccionado ningún producto</strong>
        </div>
    <?php } ?>
    <ul class="nav nav-tabs">
         <?php if(($rol==60)||($rol==61)){?>
        <li><a href="/portal/llaveMaestra/devolucion">Devolución</a></li>
         <?php }?>
         <?php if(($rol==60)||($rol==61)){?>
        <li  class="active"><a data-toggle="tab" href="#solicitarDevolucion" >Solicitar devolución</a></li>
        <?php }?>
        <?php if(($rol==60)||($rol==61)){?>
        <li><a href="/portal/llaveMaestra/reverso" >Reverso</a></li>
        <?php }?>
    </ul>
    <div class="col-lg-3">
        <form action="/portal/llaveMaestra/returnAbonosLegalizacion"  method="POST">
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
<!--        <div><label class="lblsaldoabono"><span>$ <?= number_format($saldo_llavero, 0, ',', '.'); ?></span></label></div>-->
    </div>
    <div class="tab-content">
        <div id="solicitarDevolucion" class="tab-pane fade in active">
            <form method="POST" action="/portal/llaveMaestra/solicitarDevolucion" id="formSolicitudDevolucion">
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
                                    <th> Numero Tarjeta </th>
                                    <th> Identificador </th>
                                    <th> Custodio </th>
                                    <th> Campaña </th>
                                    <th> Monto abonado </th>
                                    <!--<th> Fecha de dispersión </th>-->
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                foreach ($abonollaveros as $value) {
                                    ?>
                                    <tr class="gradeC">
                                        <td>
                                            <div class="login-checkbox" onclick="" style="padding-top: 5px">
                                                <input name="datath[]" class="chkmasiva" value="<?= $value['CODTH']?>,<?=$value['ABOTAR_CODIGO'] ?>" type="checkbox">
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
                                        <td><?= $value['IDENTIFICADOR']?></td>
                                        <td><?= $value['NOMCUSTODIO'] ?></td>
                                        <td><?= $value['CIUDAD'] ?></td>
                                        <td>$ <?= number_format($value['MONTO_ABONO'], 0, ',', '.'); ?></td>
                                        <!--<td><input  name="fecha/<?= $value['CUENTA'] ?>/<?= $value['ENTTAH'] ?>" type="date" min="<?= date("Y-m-d") ?>" class="tFecha" /></td>-->
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>

                    </div>
<!--                    <div class="button col-md-4 col-md-push-4">
                        <button type="submit" class="spacing">Solicitar devolución</button>
                    </div>-->
                </div>

            </form>
            <div class="button col-md-4 col-md-push-4">
                <button class="btn btn-default spacing" id="btnreverso" data-toggle="modal" data-target="#myModalSolicitud"> Solicitar devolución</button>
            </div>
            
        </div>
    </div>
</div>
<!-- Modal confirmacion solicitud devolucion-->
<div class="modal fade" id="myModalSolicitud" role="dialog" style="margin-top: 15%;"  data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content" style="border-radius:35px">

            <div class="modal-body" style="text-align: center;height: 230px;">

                <p  style="font-size:24px;color:#888686;font-weight: bold;padding-top: 25px">¿Desea solicitar la devolución del monto abonado a estas tarjetas?
                </p>
                <label id="nomllavero" style="font-size: 18px;color: #366199;font-weight: bold;"></label>

                <div style="">
                    <div class="button col-sm-6" >
                        <button type="button" name="ACEPTAR" value="1" class="btn btn-default"  onclick="
                                $('#formSolicitudDevolucion').submit();" >S I</button>
                    </div>
                    <div class="button col-sm-6" >
                        <button type="button" name="CANCELAR" class="btn btn-default" data-dismiss="modal">N O</button>
                    </div>
                </div>
                <br>
            </div>
        </div>
    </div>
</div>
<?php if (isset($solDevol)=='ok') { ?>
    <!-- Modal confirmacion recarga-->
    <div class="modal fade" id="ModalDevOk" role="dialog" style="margin-top: 15%;"  data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog">

            <!-- Modal content-->
            <div class="modal-content" style="border-radius:35px">

                <div class="modal-body" style="text-align: center;height: 200px;">

                    <p  style="font-size:24px;color:#888686;font-weight: bold;padding-top: 25px">La solicitud para realizar la devolución fue realizada exitosamente
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
    var x = <?php if (isset($_GET['errorpkllavero'])) {   echo "1;";} else {    echo "0;";}?>
    var errorpkcodigo = <?php if (isset($errorpkllavero)) {    echo "1;";} else {    echo "0;";}?>
    var devok=<?=isset($solDevol)=='ok'?1:0 ?>;
       if (errorpkcodigo === 1 || x===1) {
        $(".tnotifi").show();
        }
        if(devok==1){
             $('#ModalDevOk').modal('show');
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

    $("#formSolicitudDevolucion").submit(function () {
        $('#loader').modal('show');
    });
</script>