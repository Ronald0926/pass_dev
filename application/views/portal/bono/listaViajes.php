<div>
    <ul class="nav nav-tabs">
        <li class="active"><a data-toggle="tab" href="#poract" >Portadores Activos</a></li>
        <li><a data-toggle="tab" href="#porina" >Portadores Inactivos</a></li>

    </ul>

    <div class="tab-content">
        <div id="poract" class="tab-pane fade in active">
            <div class="grid" style="margin: 2%;">
                <h1>Rutas</h1>
                <?php if ($error == 1) { ?>
                    Ruta creada satisfactoriamente
                <?php } ?>
                <table class="table table-hover daos_datagrid">
                    <thead>
                        <tr>
                            <th> Portador </th>
                            <th> Placa </th>
                            <th> Ruta </th>
                            <th> No.Tarjeta </th>
                            <th> Fecha inicio </th>
                            <th> Estado </th>
                            <th> Ver </th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($viajesact as $value) { ?>

                            <tr class="gradeC"> 
                                <td><?= $value['PORTAD'] ?></td>
                                <td><?= $value['PLA'] ?></td>
                                <td><?= $value['RUT'] ?></td>
                                <td><?= $value['NUMTAR'] ?></td>
                                <td><?= $value['INILAB'] ?></td>
                                <td><?= $value['ESTADO'] ?></td>
                                <!--<select name="rol" class="required">
                                    <option value="">Seleccione el rol</option>
                                    <?php foreach ($rol as $key => $value) { ?>
                                    <option value="<?= $value['PK_TIPVIN_CODIGO'] ?>"> 
                                    <?= $value['NOMBRE'] ?></option>
                                    <?php } ?>
                                </select>--> 
                                <td>
                                    <form action="/portal/bono/verViaje" method="POST">
                                        <input type="hidden"name="viaje" readonly value="<?= $value['COD'] ?>">
                                        <button type="submit">Ver</button>
                                    </form> 
                                </td>
                            </tr>
                        <?php } ?>   
                    </tbody>
                </table>
                <div class="row">
                    <div class="button col-sm-12">
                        <a type="submit" href="/portal/bono/crearViaje">
                            CREAR VIAJE
                        </a>
                    </div>
                </div>
            </div> 
        </div>
        <div id="porina" class="tab-pane fade">
            <div class="grid" style="margin: 2%;">
                <h1>Rutas</h1>

                <table class="table table-hover daos_datagrid">
                    <thead>
                        <tr>
                            <th> Portador </th>
                            <th> Placa </th>
                            <th> Ruta </th>
                            <th> No.Tarjeta </th>
                            <th> Fecha inicio </th>
                            <th> Estado </th>
                            <th> Ver </th>

                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($viajesfin as $key => $value) { ?>

                            <tr class="gradeC"> 

                                <td><?= $value['PORTAD'] ?></td>
                                <td><?= $value['PLA'] ?></td>
                                <td><?= $value['RUT'] ?></td>
                                <td><?= $value['NUMTAR'] ?></td>
                                <td><?= $value['INILAB'] ?></td>
                                <td><?= $value['ESTADO'] ?></td>
                                <td>
                                    <form action="/portal/bono/verViaje" method="POST">
                                        <input type="hidden" name="viaje" readonly value="<?= $value['COD'] ?>">
                                        <button type="submit">Ver</button>
                                    </form> 
                                </td>

                            </tr>
                        <?php } ?>   
                    </tbody>
                </table>

                <div class="row">
                    <div class="button col-sm-12">
                        <a type="btn btn-default" href="/portal/bono/crearViaje">
                            CREAR VIAJE
                        </a>
                    </div>
                </div>
            </div> 
        </div>
    </div>
</div>
