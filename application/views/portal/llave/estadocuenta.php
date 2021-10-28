<style>
    .menos{
        color:red !important;
    }
    .mas{
        color:green !important;
    }
</style>
<div class="col-lg-2" ></div>

<div class="container col-lg-8" style=" margin-bottom: 200px; margin-top: -50px;">
    <hr style="border-top: 1px solid #eee0;">
    <h2 class="titulo-iz">Estado de cuenta de la Tarjeta</h2>


    <form method="POST">
        <label class="lbl_linea">Saldo disponible: <br> <span>$ <?= number_format($saldotarjeta, 0, ',', '.'); ?></span></label>
        <label class="lbl_linea" style="padding-left: 5%"><?= $producto ?> <br> <span><?= $numtar ?></span></label>
        <div class="container col-lg-12">
            <div class="grid" style="margin: 2%;">
                <table class="table table-hover daos_datagrid">
                    <thead>
                        <tr>
                            <th> Fecha </th>
                            <th> Concepto </th>
                            <th> Valor </th>

                        </tr>
                    </thead>
                    <?php foreach ($movtarjeta as $value) { ?>
                        <tbody>
                            <tr>
                                <td><?= $value['FECHA_TRANSACCION'] ?></td>
                                <td><?= $value['NOMBRE_COMERCIO'] . ' - ' . $value['TIPO_MOVIMIENTO'] ?></td>
                                <?php if ($value['ID_TIPO_MOVIMIENTO'] == 8 || $value['ID_TIPO_MOVIMIENTO'] == 12 || $value['ID_TIPO_MOVIMIENTO'] == 19 || $value['ID_TIPO_MOVIMIENTO'] == 26 || $value['ID_TIPO_MOVIMIENTO'] == 27 || $value['ID_TIPO_MOVIMIENTO'] == 21 || $value['ID_TIPO_MOVIMIENTO'] == 23) {
                                    ?>
                                    <td class="mas">$ <?= number_format($value['MONTO'], 0, ',', '.'); ?></td>
                                <?php } else { ?> 
                                    <td  class="menos">$ <?= number_format($value['MONTO'], 0, ',', '.'); ?></td>
                                <?php } ?>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
                <div class="button col-sm-12" hidden="">
                    <button type="submit">Descargar PDF</button>
                </div>  
                <div class="button  col-md-4 col-md-push-4">

                    <div class=" linkgenerico spacing">
                        <a href="/portal/llaveMaestra/estadoTarjetas">VOLVER</a>
                    </div>
                </div>
            </div>                     
        </div> 
    </form>
</div>

<div class="col-lg-2" ></div>