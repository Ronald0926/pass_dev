
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
        <div class="button  col-md-2 col-md-push-5">
            <div class=" linkgenerico spacing">
                <a href="/portal/abonos/abonoMasivo"><span class="glyphicon glyphicon-chevron-left"></span>VOLVER</a>
            </div>
        </div>
    </div>
</div>