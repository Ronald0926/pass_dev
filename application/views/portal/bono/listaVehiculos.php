<div>  

    <div class="tab-content">
        <div id="poract" class="tab-pane fade in active">
            <div class="grid" style="margin: 2%;">                             
                <table class="table table-hover daos_datagrid">
                    <thead>
                        <tr>
                            <th> Placa </th>
                            <th> Marca </th>
                            <th> Modelo </th>
                            <th> Año </th>
                            <!-- <th> Asignada </th> -->
                            <!-- <th> Numero de Tarjeta </th>
                            <th> Estado </th> -->
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($vehiculos as $value) { ?>
                            <tr class="gradeC"> 
                                <td><?= $value['PLACA'] ?></td>
                                <td><?= $value['MARCA'] ?></td>
                                <td><?= $value['MODELO'] ?></td>
                                <td><?= $value['ANIO'] ?></td>
                                <!-- <td><?= $value['ASIGNA'] ?></td>
                                <td><?= $value['NUMTAR'] ?></td> -->
                                <!-- <td>
                                        <div class="dropdown">
                                            <button id="dLabel" type="button" 
                                                    data-toggle="dropdown" 
                                                    aria-haspopup="true" aria-expanded="false">
                                                <?= $value['NOMEST'] ?>
                                                <span class="caret"></span>
                                            </button>
                                            <ul class="dropdown-menu" aria-labelledby="dLabel">
                                                <?php 
                                                foreach ($estadoVehicu as $estadoKey => $estadoValue) {
                                                    if ($value['CODESTPOR'] != $estadoValue['COD']) {
                                                        ?>
                                                        <li><a href="/portal/bono/cambiarEstPor/<?= $value['CODPOR']?>/<?= $estadoValue['COD']?>"><?= $estadoValue['NOM'] ?></a></li>
                                                    <?php }
                                                }
                                                ?>
                                            </ul>
                                        </div>
                                    </td> -->
                                
                                
                            </tr>
                        <?php } ?>   
                    </tbody>
                </table>
                <div class="row">
                    <div class="button col-sm-12">
                        <a type="submit" href="/portal/bono/crearVehiculo">
                            CREAR VEHICULO
                        </a>
                    </div>
                </div>
            </div> 
        </div>        
    </div>
</div>
