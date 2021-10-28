<div>

    <div class="grid" style="margin: 2%;">
        <h1>Rutas</h1>
        <?php if ($error == 1) {?>
            Ruta creada satisfactoriamente
        <?php }?>
        <table class="table table-hover daos_datagrid">
            <thead>
                <tr>
                    <th> No. Ruta </th>
                    <th> Punto de Inicio </th>
                    <th> Punto de Finalización </th>
                    <th> Gasto Apróx. </th>
                    <th> Kilómetros Apróx. </th>
                    <th> Consumo Gasolina </th>
                    <th> Estado </th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($rutas as $value) { ?>

                    <tr class="gradeC"> 
                        <td><?= $value['COD'] ?></td>
                        <td><?= $value['PUNINI'] ?></td>
                        <td><?= $value['PUNFIN'] ?></td>
                        <td><?= $value['COSTOT'] ?></td>
                        <td><?= $value['KILTOT'] ?></td>
                        <td><?= $value['GASTOT'] ?></td>
                        <td><?= $value['EST'] ?></td>

                    </tr>
                <?php } ?>   
            </tbody>
        </table>
        <div class="row">
            <div class="button col-sm-12">
                <a type="btn btn-default" href="/portal/bono/crearRuta">
                    Crear Ruta
                </a>
            </div>
        </div>
    </div> 

</div>
