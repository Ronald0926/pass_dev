<div style=" margin-bottom: 300px; margin-top: -50px;">
    <div style=" margin-bottom: 200px; margin-top: -50px;">
        <div class="container">
            <hr style="border-top: 1px solid #eee0;">
            <h2 class="titulo-iz">Consultas</h2>
            <ul class="nav nav-tabs">
                <li><a href="/portal/consultas/consultasAbonos">Abonos</a></li>
                <li><a href="/portal/consultas/consultasFacturas">Facturas</a></li>
                <li><a href="/portal/consultas/consultasTarjetas">Tarjetas</a></li>
                <li class="active"><a href="/portal/consultas/consultasBussines">Business</a></li>
                <li><a href="/portal/consultas/consultasPedidosTarjeta">Pedidos Tarjetas</a></li>
            </ul>
            <div id="bussines" class="tab-pane fade in active">
                <div class="grid" style="margin: 2%;">
                    <table class="table table-hover daos_datagrid">
                        <thead>
                            <tr>
                                <th> Id banco </th>
                                <th> I.D. </th>
                                <th> No.Tarjeta </th>
                                <th> Producto </th>
                                <th> Identificador </th>
                                <th> F.Solicitud </th>
                                <th> Estado de Tarjeta </th>
                                <th> Movimientos </th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($bussines as $value) { ?>
                                <tr class="gradeC">
                                    <td><?= $value['ID_EMPRESA']?></td>
                                    <td><?= $value['ID'] ?></td>
                                    <td><?= $value['NUMTAR'] ?></td>
                                    <td><?= $value['NOMPRO'] ?></td>
                                    <td><?= $value['DESCRIPCION'] ?></td>
                                    <td><?= $value['FEC'] ?></td>
                                    <td><?= $value['ESTTAR'] ?></td>
                                    <td>
                                        <!--comenta ronald 22/07/2020 si estado es inactivo no tiene moviminetos -->
                                        <?php  /* if ($value['ID_EMPRESA'] != null || $value['NUMTAR'] != null) {  */?> 
                                            <?php if ($value['ESTTAR_CODIGO']==1) { ?> 
                                                <a class="buttonDetail" href="/portal/consultas/verDetalle/<?= $value['ID_EMPRESA'] ?>/<?= $value['NUMTAR'] ?>/<?= $value['NOMPRO'] ?> ">Ver detalle</a>
                                            <?php } ?>
                                        </td>
                                    </tr>
                                <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
</div>