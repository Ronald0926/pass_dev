<div>
    <div class="container">
        <hr style="border-top: 1px solid #eee0;">
        <h2>Portadores</h2>
        <?php if ($error == 1) {?>
            SE HA CREADO CORRECTAMENTE
        <?php } ?>
        <ul class="nav nav-tabs">
            <li class="active"><a data-toggle="tab" href="#activos" >Portadores Activos</a></li>
            <li><a data-toggle="tab" href="#inactivos" >Portadores Inactivos</a></li>
"
        </ul>

        <div class="tab-content">
            <div id="activos" class="tab-pane fade in active">
                <h3>Abonos</h3>
                <div class="grid" style="margin: 2%;">
                    <table class="table table-hover daos_datagrid">
                        <thead>
                            <tr>
                                <th> Nombre </th>
                                <th> Tipo. Documento </th>
                                <th> Documento </th>
                                <th> Tipo Licencia </th>
                                <th> Estado </th>   
                                <th> Asignada </th>   
                                <th> Número </th>   

                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($poractivo as $value) { ?>
                                <tr class="gradeC"> 
                                    <td><?= $value['PORTAD'] ?></td>
                                    <td><?= $value['ABR'] ?></td>
                                    <td><?= $value['DOC'] ?></td>
                                    <td><?= $value['TIPLIC'] ?></td>
                                    <td><?= $value['ESTPOR'] ?></td>
                                    <td><?= $value['ASI'] ?></td>
                                    <td><?= $value['NUMTAR'] ?></td>
                                </tr>
                            <?php } ?>   
                        </tbody>
                    </table>
                </div> 
            </div>
            <div id="inactivos" class="tab-pane fade">
                 <div class="grid" style="margin: 2%;">
                    <table class="table table-hover daos_datagrid">
                        <thead>
                            <tr>
                                <th> Nombre </th>
                                <th> Tipo. Documento </th>
                                <th> Documento </th>
                                <th> Tipo Licencia </th>
                                <th> Estado </th>   
                                <th> Asignada </th>   
                                <th> Número </th>   

                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($porinacti as $value) { ?>
                                <tr class="gradeC"> 
                                    <td><?= $value['PORTAD'] ?></td>
                                    <td><?= $value['ABR'] ?></td>
                                    <td><?= $value['DOC'] ?></td>
                                    <td><?= $value['TIPLIC'] ?></td>
                                    <td><?= $value['ESTPOR'] ?></td>
                                    <td>No</td>
                                    <td>----</td>
                                </tr>
                            <?php } ?>   
                        </tbody>
                    </table>
                </div> 
            </div>
        </div>
        <div class="button col-sm-2">
            <a type="button" href="/portal/bono/crearPortador">
                              Crear Portador
            </a>
        </div>
        <div>
        </div>
