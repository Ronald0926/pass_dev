<?php
//$rol = $this->session->userdata("rol"); 
$rol = $_SESSION['rol']; 
?>
<div class="col-lg-2" ></div>
<div class="container col-lg-8" style=" margin-bottom: 200px; margin-top: -50px;">
    <hr style="border-top: 1px solid #eee0;">
    <h2 class="titulo-iz">Estado de cuenta</h2>
    <ul class="nav nav-tabs">
        <?php if(($rol==60) or ($rol==61)){?>
        <li><a href="/portal/llaveMaestra/estado"><?php echo (($rol == 61))  ? 'Llave Maestra' : 'Llavero' ?></a></li>
        <?php } ?>
        <?php if (($rol==60) or ($rol==61)) { ?>
        <li><a href="/portal/llaveMaestra/estadoTarjetas">Tarjetas</a></li>
         <?php } ?>
         <?php if (($rol==60) or ($rol==61)) { ?>
        <li class="active"><a data-toggle="tab" href="#abonos" >Dispersiones</a></li>
        <?php } ?>
        <?php if (($rol==60) or ($rol==61)) { ?>
            <li><a href="/portal/llaveMaestra/informeGrafico">Informes Graficos Transaccional</a></li>
            <?php } ?>
            <?php if (($rol==60) or ($rol==59) or ($rol==61)) { ?>
            <li><a href="/portal/llaveMaestra/consultaNotasContables">Nota Contable Prepago</a></li>
             <?php } ?>
            <?php if (($rol==60) or ($rol==61)) { ?>
            <li><a href="/portal/llaveMaestra/consultaFacturas">Facturas</a></li>
        <?php } ?>
    </ul>
    <div class="col-lg-3" >
        <form action="/portal/llaveMaestra/returnabonosllavero" method="POST">
            <div class="select">
                <select name="llavero" id="llavero" class="required" onchange="this.form.submit();">
                    <option value=""> Seleccione Llavero</option>
                    <?php foreach ($llaveros as $key => $value) { ?>
                        <option value="<?= $value['PK_LLAVERO_CODIGO'] ?>" <?php if ($value['PK_LLAVERO_CODIGO'] == $pk_llavero_codigo) echo 'selected'; ?>> <?= ucwords(strtolower($value['NOMBRE_LLAVERO'])) ?></option>
                    <?php } ?>
                </select>
                <div> <?php echo $nombrellaveroselect != "" ? $nombrellaveroselect : 'Seleccione Llavero*' ?></div>
            </div>
        </form>
    </div>
    <div class="tab-content">
        <div id="abonos" class="tab-pane fade in active">
            <form method="POST">
                <div class="container col-lg-12">
                    <div class="grid" >
                        <table class="table table-hover daos_datagrid">
                            <thead>
                                <tr>
                                    <th> Nombre </th>
                                    <th> T.D. </th>
                                    <th> No.Doc </th>
                                    <th> Producto </th>
                                    <th> Identificador </th>
                                    <th> No. Tarjeta </th>
                                    <th> Custodio </th>
                                    <th> Campaña </th>
                                    <th> Concepto </th>
                                    <th> Valor abono </th>
                                    <th> Fecha dispersión </th>
                                    <th> Legalización </th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($abonollaveros as $value) { ?> 
                                    <tr>
                                        <td><?= $value['NOMTAR'] ?></td>
                                        <td><?= $value['ABR'] ?></td>
                                        <td><?= $value['DOC'] ?></td>
                                        <td><?= $value['NOMPRO'] ?></td>
                                        <td><?= $value['IDENTIFICADOR'] ?></td>
                                        <td><?= $value['NUMTAR'] ?></td>
                                        <td><?= $value['NOMCUSTODIO'] ?></td>
                                        <td><?= $value['NOMCAMPANA'] ?></td>
                                        <td><?= $value['NOM_CONCEPTO'] ?> </td>
                                        <td>$ <?= number_format($value['MONTO_ABONO'], 0, ',', '.'); ?></td>
                                        <td><?= $value['FECHA_ABONO'] ?> </td>
                                        <td><a href="/portal/llaveMaestra/legalizacionAbono/<?= $value['ABOTAR_CODIGO'] ?>/<?= $value['PK_TARJET_CODIGO'] ?>/<?= $value['MONTO_ABONO'] ?>/<?= $pk_llavero_codigo ?>"> Ver Detalle</a></td>
                                    </tr>
                                <?php } ?> 
                            </tbody>
                        </table>                   
                    </div>                     
                </div> 
            </form>
        </div>
    </div>
</div>
<div class="col-lg-2" ></div>
<?php if (isset($_GET['aprobOk'])) { ?>
    <!-- Modal confirmacion recarga-->
    <div class="modal fade" id="ModalAprobaOk" role="dialog" style="margin-top: 15%;"  data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog">

            <!-- Modal content-->
            <div class="modal-content" style="border-radius:35px">

                <div class="modal-body" style="text-align: center;height: 200px;">

                    <p  style="font-size:24px;color:#888686;font-weight: bold;padding-top: 25px">Aprobación de legalizaciones exitosa.
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
    $('.add').click(function () {
        $('.block:last').before('<div class="form-group"><div class="block col-sm-6 "> <select name="productos"><option value=""></option><?php foreach ($productos as $value) { ?><option><?= $value['NOMBRE_PRODUCTO'] ?></option><?php } ?></select></div> <a class="remove btn btn-danger"> X </a></div>');
    });
    $('.optionBox').on('click', '.remove', function () {
        $(this).parent().remove();
    });
</script>
<script type="text/javascript">
    var aprobLega = <?= isset($_GET['aprobOk']) ? 1 : 0 ?>;
    if (aprobLega == 1) {
        $('#ModalAprobaOk').modal('show');
    }
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
</script>