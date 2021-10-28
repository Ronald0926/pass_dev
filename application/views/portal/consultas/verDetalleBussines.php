<div style=" margin-bottom: 300px; margin-top: -50px;">
    <div style=" margin-bottom: 200px; margin-top: -50px;">
        <div class="container">
            <hr style="border-top: 1px solid #eee0;">
            <h2 class="titulo-iz">Consultas</h2>


            <div id="abonos" class="tab-pane fade in active">
                <label class="">No Tarjeta:  </label><?= $enmascarado ?><br>
                <label class="">Producto:  </label><?= $tipo ?>
                <div class="grid" style="margin: 2%;">
                    <table id="" class="table table-hover daos_datagrid">
                        <thead>
                            <tr>
                                <th> IdBanco </th>
                                <th>Numero de tarjeta </th>
                             
                                <th> Nombre Producto </th>
                                <th>Identificador</th>
                                <th> Fecha  </th>
                                <th> Hora </th>
                                <th> Comercio </th>
                                <th> Valor </th>
                                <th>Tipo de transacción</th>
                                
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            foreach ($movimientos as $value) {
                                ?>
                                <tr class="gradeC">
                                    <td><?= $value['ID_EMPRESA'] ?></td>
                                    <td><?= $value['PAN_ENMASCARADO'] ?></td>
                                    <td><?= $value['NOMBRE_PRODUCTO'] ?></td>
                                    <td><?= $value['IDENTIFICADOR'] ?></td>
                                    <td width='20% '><?= $value['FECHA'] ?>
                                    <td> <?= $value['HORA_TRANSACCION']?></td>
                                    <td><?= $value['NOMBRE_COMERCIO'] ?></td>
                                    
                                   
                                   <!--- <td> //"$ " . number_format($value['NOMBRE'], 0) aaa</td>-->
                                  

                                   <?php /*9,30,25,24,20,1*/ if($value['ID_TIPO_MOVIMIENTO']=='9'|| $value['ID_TIPO_MOVIMIENTO']=='30'|| $value['ID_TIPO_MOVIMIENTO']=='25'||$value['ID_TIPO_MOVIMIENTO']=='24'||$value['ID_TIPO_MOVIMIENTO']=='20'|| $value['ID_TIPO_MOVIMIENTO']=='1' || $value['ID_TIPO_MOVIMIENTO']=='18' ){?>
                                    <td width='20%' style='color:red'><?= "-$ " . number_format($value['MONTO'], 0)?></td>
                                    <?php } else{ ?>
                                         <td width='20%'><?= "$ " . number_format($value['MONTO'], 0) ?></td>
                                    <?php } ?>
                                    <td> <?= $value['TIPO_MOVIMIENTO']?> </td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="button  col-md-4 col-md-push-4">

                <div class=" linkgenerico spacing">
                    <a href="/portal/consultas/consultasBussines">VOLVER</a>
                </div>
            </div>
        </div>
    </div>


</div>