<div class="loader" id="loader" hidden></div> 
<div style=" margin-bottom: 200px; margin-top: -50px;">
    <div class="container">
        <?php if ($codigoMensaje == 0) { ?>
            <div class="col-sm-12 alert alert-danger ">
                <?= $mensajeSistema ?>
            </div>
            <div class="card">
                <h3 class="subtitulo-iz" style="padding-bottom: 2px !important;">Facturación electrónica</h3>
                <div class="card-body">
                    <h4 style="color:#366199;">¡¡Por favor tener en cuenta!!</h4>
                    <p style="font-size:18px;color:#888686;font-weight: bold;padding-top: 5px;text-align: justify;">Nuestra facturación ahora es electrónicamente, por tal motivo tu representación grafica de la factura estará disponible en breve.
                    </p>
                </div>
            </div>
        <?php } ?>
        <?php if ($codigoMensaje == 1) { ?>
            <div class="col-sm-12 alert alert-success ">
                <?= $mensajeSistema ?> 
            </div>
            <div class="card">
                <h3 class="subtitulo-iz" style="padding-bottom: 2px !important;">Facturación electrónica</h3>
                <div class="card-body">
                    <h4 style="color:#366199;">¡¡Por favor tener en cuenta!!</h4>
                    <p style="font-size:18px;color:#888686;font-weight: bold;padding-top: 5px;text-align: justify;">Nuestra facturación ahora es electrónicamente, por tal motivo tu representación grafica de la factura estará disponible en breve.
                    </p>
                </div>
            </div>
        <?php } ?>
        <?php if ($codigoMensaje > 1) { ?>

            <div class="col-sm-12 alert alert-info ">
                <?= $mensajeSistema ?>
            </div>



            <div style=" margin-bottom: 200px; margin-top: -50px;">
                <div class="grid" style="margin: 8%;">
                    <table class="table table-hover daos_datagrid">
                        <thead>
                            <tr>
                                <th>Linea Archivo</th>
                                <th>Dato</th>
                                <th>Descripcion</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($errores as $value) { ?>
                                <tr class="gradeC">
                                    <td><?= $value['LINEA_ARCHIVO'] ?></td>
                                    <td><?= $value['DATO'] ?></td>
                                    <td><?= $value['DESCRIPCION'] ?></td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                    <div class="button  col-md-4 col-md-push-4">
                        <div class=" linkgenerico spacing">
                            <a href="/portal/solicitudGestion/generarOrden"><span class="glyphicon glyphicon-chevron-left"></span>VOLVER</a>
                        </div>
                    </div>
                </div>
            </div>
        <?php } ?>
        <?php if ($codigoMensaje == 1 || $codigoMensaje = 2) { ?>
            <div class="button  col-md-4 col-md-push-4">
                <div class=" linkgenerico spacing">
                    <a href="/portal/ordenPedido/lista">IR A PAGOS<span class="glyphicon glyphicon-chevron-right"></span></a>
                </div>
            </div>
        <?php } ?>
    </div>
</div>





