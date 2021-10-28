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
        <li><a href="/portal/llaveMaestra/estado"><?php echo ($rol == 61) ? 'Llave Maestra' : 'Llavero' ?></a></li>
        <?php } ?>
        <?php if (($rol==60) or ($rol==61)) { ?>
        <li class="active"><a data-toggle="tab" href="#estadoTarjetas" >Tarjetas</a></li>
        <?php } ?>
        <?php if (($rol==60) or ($rol==61)) { ?>
        <li><a href="/portal/llaveMaestra/informeAbonos" >Dispersiones</a></li>
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

    <div class="tab-content">
        <div id="estadoTarjetas" class="tab-pane fade in active">

            <div class="container col-lg-12">
                <div class="grid" >
                    <table class="table table-hover daos_datagrid">
                        <thead>
                            <tr>
                                <th> I.D. </th>
                                <th> No. Tarjeta </th>
                                <th> Producto </th>
                                <th> Identificador </th>
                                <th> Saldo Actual</th>
                                <th> Portador </th>
                                <th> Llavero </th>
                                <th> Movimientos </th>
                            </tr>
                        </thead>

                        <tbody>
                            <?php foreach ($tarjetaEntidad as $value) { ?>
                                <tr>
                                    <td><?= $value['DOC'] ?></td>
                                    <td><?= $value['NUMTAR'] ?></td>
                                    <td><?= $value['NOMPRO'] ?></td>
                                    <td><?= $value['IDENTIFICADOR'] ?></td>
                                    <td><?= number_format($value['SALDO'], 0, ',', '.'); ?></td>
                                    <td><?= $value['NOMTAR'] ?></td>
                                    <td><?= $value['NOMBRE_LLAVERO'] ?></td>
                                    <?php if ($value['PK_LINPRO_CODIGO'] == 2) { ?>
                                        <td><a href="/portal/llaveMaestra/estadocuentadetalletarjeta/<?= $value['DOC'] ?>/<?= $value['PK_TARJET_CODIGO'] ?>"> Ver Detalle</a></td>
                                    <?php } else { ?>
                                        <td></td>
                                    <?php } ?>
                                </tr>
                            <?php } ?>
                        </tbody>

                    </table>                   
                </div>  
            </div>
        </div>
    </div>
</div>
<div class="col-lg-2" ></div>
