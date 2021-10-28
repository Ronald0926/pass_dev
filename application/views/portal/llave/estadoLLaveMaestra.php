<style>
    hr.est-hr {
        border: 1px solid #366199;
        border-radius: 5px;
        width: 100% !important;
        margin-top: -8%;
    }
    .menos{
        color:red !important;
    }
    .mas{
        color:green !important;
    }
</style>

<?php 
//$rol = $this->session->userdata("rol"); 
$rol = $_SESSION['rol']; 
?>
<div class=" col-lg-1"></div>
<div class="container col-lg-6" style=" margin-bottom: 200px;">
    <hr style="border-top: 1px solid #eee0;">
    <h2 class="titulo-iz">Estado de cuenta</h2>
    <ul class="nav nav-tabs">
        <?php if(($rol==60) or ($rol==61)){?>
        <li class="active"><a data-toggle="tab" href="#llaveMaestra"><?php echo (($rol==61))? 'Llave Maestra': 'Llavero'?></a></li>
       <?php }?>
        <?php if(($rol==60) or ($rol==61)){?>
        <li><a href="/portal/llaveMaestra/estadoTarjetas">Tarjetas</a></li>
         <?php }?>
         <?php if(($rol==60) or ($rol==61)){?>
        <li><a href="/portal/llaveMaestra/informeAbonos">Dispersiones</a></li>
        <?php }?>
        <?php if(($rol==60) or ($rol==61)){?>
        <li><a href="/portal/llaveMaestra/informeGrafico">Informes Graficos Transaccional</a></li>
        <?php }?>
        <?php if(($rol==60) or ($rol==59) or ($rol==61)){?>
        <li><a href="/portal/llaveMaestra/consultaNotasContables">Nota Contable Prepago</a></li>
          <?php }?>
         <?php if(($rol==60) or ($rol==61)){?>
        <li><a href="/portal/llaveMaestra/consultaFacturas">Facturas</a></li>
        <?php }?>
    </ul>

    <div class="col-lg-3" >
        <form action="/portal/llaveMaestra/returnmovllavero" method="POST">
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
        <div id="llaveMaestra" class="tab-pane fade in active">
            <form method="POST">
                <div class="container col-lg-12">

                    <div class="col-md-12">
                        <div class="grid">
                            <table class="table table-hover daos_datagrid">
                                <thead>
                                    <tr>
                                        <th> Fecha </th>
                                        <th> Concepto </th>
                                        <th> Valor </th>
                                        <th> Saldo Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    if ($consultallaveros==1) {
                                         foreach ($movllaveros as $value) { ?>
                                     <tr>
                                            <td><?= $value['FECHA'] ?></td>
                                            <td><?= $value['NOMBRE_MOV'] ?></td>
                                            <?php if ($value['PK_TIPMOV'] == 3) { ?>
                                                <td class="mas">$ <?= number_format($value['MONTO_MOV'], 0, ',', '.'); ?></td>
                                            <?php } elseif($value['PK_TIPMOV'] == 5) { ?>
                                                <td class="mas">$ <?= number_format($value['MONTO_MOV'], 0, ',', '.'); ?></td>
                                            <?php } elseif ($value['PK_TIPMOV'] == 4) { ?>
                                                <td class="menos">$ -<?= number_format($value['MONTO_MOV'], 0, ',', '.'); ?></td>
                                            <?php } elseif ($value['PK_TIPMOV'] == 6) { ?>
                                                <td class="menos">$ -<?= number_format($value['MONTO_MOV'], 0, ',', '.'); ?></td>
                                            <?php } else{?>
                                                <td >$ <?= number_format($value['MONTO_MOV'], 0, ',', '.'); ?></td>
                                            <?php }?>
                                            <td>$ <?= number_format($value['SALDO_ANT_MOV'], 0, ',', '.'); ?></td>
                                        </tr>
                                    <?php }
                                    }else{ ?>
                                    <?php foreach ($movllavemaestra as $value) { ?>
                                        <tr>
                                            <td><?= $value['FECHA'] ?></td>
                                            <td><?= $value['NOMBRE_MOV'] ?></td>
                                            <?php if ($value['NOMBRE_MOV'] == 'RECARGA LLAVERO') { ?>
                                                <td class="menos">$ -<?= number_format($value['MONTO_MOV'], 0, ',', '.'); ?></td>
                                            <?php } else { ?>
                                                <td class="mas">$ <?= number_format($value['MONTO_MOV'], 0, ',', '.'); ?></td>
                                        <?php } ?>
                                            <td>$ <?= number_format($value['SALDO_ANT_MOV'], 0, ',', '.'); ?></td>
                                        </tr>
                                    <?php } }?>  

                                </tbody>
                            </table>
<!--                            <div class="button col-sm-4 col-sm-push-4">
                                <button type="submit">Descargar PDF</button>
                            </div>-->
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<div class="col-lg-4 " style="margin-top: 10%;">
    <div class=" col-md-12" style=" ">
        <h3 class="subtitulo-iz" style=""> Resumen general de mes actual </h3>
        <hr class="est-hr">
        <div class="col-md-6">
            <h4 style="text-align: left;font-weight: bold;margin-bottom: 0px !important">Total Abonos </h4>
            <span style="font-size: 1.5em;color: #366199">$ <?= number_format($totalabonos, 0, ',', '.'); ?> COP</span>

            <h4 style="text-align: left;font-weight: bold;margin-bottom: 0px !important">Total Reversos</h4>
            <span style="font-size: 1.5em;color: #366199;margin-top: 0px">$ <?= number_format($totalreversos, 0, ',', '.'); ?> COP</span>

            <h4 style="text-align: left;font-weight: bold;margin-bottom: 0px !important">Total Devoluciones</h4>
            <span style="font-size: 1.5em;color: #366199">$ <?= number_format($totaldevoluciones, 0, ',', '.'); ?> COP</span>
        </div>
        <div class="col-md-6">
            <h4 style="text-align: left; font-weight: bold;margin-bottom: 0px !important">Saldo Actual </h4>
            <span style="font-size: 1.5em;color: #366199">$ <?= number_format($saldo, 0, ',', '.'); ?> COP</span>
        </div>
    </div>
</div>
<div class="col-lg-1"></div>
<script type="text/javascript">
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
</script>