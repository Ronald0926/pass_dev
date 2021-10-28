<div style=" margin-bottom: 300px; margin-top: -50px;">
    <div style=" margin-bottom: 200px; margin-top: -50px;">
        <div class="container">
            <hr style="border-top: 1px solid #eee0;">
            <h2 class="titulo-iz">Consultas</h2>
            <ul class="nav nav-tabs">
                <li><a href="/portal/consultas/consultasAbonos">Abonos</a></li>
                <li><a href="/portal/consultas/consultasFacturas">Facturas</a></li>
                <li class="active"><a href="/portal/consultas/consultasTarjetas">Tarjetas</a></li>
                <li><a href="/portal/consultas/consultasBussines">Business</a></li>
                <li><a href="/portal/consultas/consultasPedidosTarjeta">Pedidos Tarjetas</a></li>
            </ul>

            <div id="tarjetas" class="tab-pane fade in active">
                <div class="grid" style="margin: 2%;">
                    <table class="table table-hover daos_datagrid">
                        <thead>
                            <tr>
                                <th> F.Solicitud </th>
                                <th> Pedido </th>
                                <th> Tipo </th>
                                <th> No.Identifiaci&oacute;n </th>
                                <th> Nombre </th>
                                <th> No.Tarjeta </th>
                                <th> Identificador </th>
                                <th> Producto </th>
                                <th> Custodio </th>
                                <th> Estado de Tarjeta </th>

                            </tr>
                        </thead>
                        <tbody>

                            <?php foreach ($tarjeta as $value) { ?>
                                <tr class="gradeC">
                                    <td><?= $value['FEC'] ?></td>
                                    <td><?= $value ['PEDIDO'] ?></td>
                                    <td><?= $value['TIPDOC'] ?></td>
                                    <td><?= $value['DOC'] ?></td>
                                    <td><?= $value['NOMTH'] ?></td>
                                    <td><?= $value['NUMTAR'] ?></td>
                                    <td><?= $value['IDENTIFICADOR'] ?></td>
                                    <td><?= $value['NOMPRO'] ?></td>
                                    <td><?= $value['CUS'] ?></td>
                                    <td><?= $value['ESTTAR'] ?></td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                      <a href="/portal/consultas/consultasTarjetasTodas" class="button">Historial Completo</a>
                </div>
            </div>

        </div>
    </div>
</div>