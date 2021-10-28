<div style=" margin-bottom: 300px; margin-top: -50px;">
    <div style=" margin-bottom: 200px; margin-top: -50px;">
        <div class="container">
            <hr style="border-top: 1px solid #eee0;">
            <h2 class="titulo-iz">Consultas</h2>
            <ul class="nav nav-tabs">
                <li ><a  href="/portal/consultas/consultasAbonos">Abonos</a></li>
                <li ><a  href="/portal/consultas/consultasFacturas">Facturas</a></li>
                <li><a  href="/portal/consultas/consultasTarjetas">Tarjetas</a></li>
                <li ><a  href="/portal/consultas/consultasBussines">Business</a></li>
                <li  class="active"><a  href="/portal/consultas/consultasPedidosTarjeta">Pedidos Tarjetas</a></li>
            </ul>

                <div id="pedidosTarjetas" class="tab-pane fade in active">
                    <div class="grid" style="margin: 2%;">
                        <table class="table table-hover daos_datagrid">
                            <thead>
                                <tr>
                                    <th> No.de Pedido </th>
                                    <th> No.de Tarjetas </th>
                                    <th> Fecha Solicitado </th>
                                    <th> Fecha Recibido </th>
                                    <th> Confirmar Pedido </th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($pedidos as $value) { ?>
                                    <tr class="gradeC">
                                        <td><?= $value['NUMEROPEDIDO'] ?></td>
                                        <td><?= $value['CANTIDADTARJETAS'] ?></td>
                                        <td><?= $value['FECHASOLICITUD'] ?></td>
                                        <td><?= $value['FECHARECIBIDO'] ?></td>
                                        <td><?= $value['ESTADOPEDIDO'] ?></td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>