
<style>
                label {
	display: inline-block;
	max-width: 89%;
	margin-bottom: 5px;
	font-weight: 700
}
</style>
<div style=" margin-bottom: 300px; margin-top: -50px;">
    <div style=" margin-bottom: 200px; margin-top: -50px;">
        <div class="container" >
            <hr style="border-top: 1px solid #eee0;">
            <h2 class="titulo-iz" style="top:500px;">Consultas</h2>
            <br><br>
            <ul class="nav nav-tabs">
                <li class="active"><a href="/portal/consultas/consultasAbonos">Abonos</a></li>
                <li><a href="/portal/consultas/consultasFacturas">Facturas</a></li>
                <li><a href="/portal/consultas/consultasTarjetas">Tarjetas</a></li>
                <li><a href="/portal/consultas/consultasBussines">Business</a></li>
                <li><a href="/portal/consultas/consultasPedidosTarjeta">Pedidos Tarjetas</a></li>
            </ul>

            <div id="abonos" class="tab-pane fade in active">
                <!--<h1><?php // echo $a;  ?></h1>-->
                <div class="grid" style="margin: 2%;">
                    <table id="dataTableAbonos" class="table table-hover" >
                        <thead>
                            <tr>
                                <th> Factura </th>
                                <th> Orden </th>
                                <th> Producto </th>
                                <th> Valor </th>
                                <th> Estado </th>
                                <th> Identificaci&oacute;n </th>
                                <th> Nombre </th>
                                <th> Num Tarjeta </th>
                                <th> IdBanco </th>
                                <th> F.Pedido </th>
                                <th> F.Programada </th>
                                <th> F.Factura </th>
                                <th> F.Dispersada  </th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $temp = -1;
                            foreach ($abonosinfo as $value) {
                                ?>
                                <tr class="gradeC">
                                    <!-- <?php
                                    //var_dump('');
                                    foreach ($abonosnum as $key => $valuspan) {
                                        // var_dump ("hola ".$temp . ' != ' . $key .' and ' . $key .' == '.$value['FAC']);
                                        // exit;
                                        if ($temp != $key && $key == $value['FAC']) {
                                            //  var_dump ("\n asignacion ".$temp . ' ' . $key );
                                            $temp = $key;
                                            // var_dump($value['FAC']);
                                            ?>
                                                                        <td rowspan="<?= $valuspan ?>" vertical-align=center><?= $value['FAC'] ?></td>
                                            <?php
                                            //echo "fin";
                                        };
                                    }
                                    ?> -->
                                    <td vertical-align=center><?= $facturapref . ' ' . $value['FAC'] ?></td>
                                    <td><?= $value['ORDEN'] ?></td>
                                    <td><?= $value['NOMPRO'] ?></td>
                                    <td>$<?= number_format($value['MON']) ?></td>
                                    <td><?= $value['ESTMOV'] ?></td>
                                    <td><?= $value['DOC'] ?></td>
                                    <td><?= $value['NOMENT'] ?></td>
                                    <td><?= $value['NUMTAR'] ?></td>
                                    <td><?= $value['IDBANCO'] ?></td>
                                    <td><?= $value['FECCRE'] ?></td>
                                    <td><?= $value['FECPRO'] ?></td>
                                    <td><?= $value['FECFAC'] ?></td>
                                    <td><?= $value['FECDIS'] ?></td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                    <a href="/portal/consultas/consultasAbonostodo" class="button">Historial Completo</a>

                </div>
            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function () {
        var table = $('#dataTableAbonos').DataTable({
            "scrollX": true,
            "bJQueryUI": true,
            "bSort": false,
            "bPaginate": true,
            "sPaginationType": "full_numbers",
            "oLanguage": {
                "sProcessing": "Procesando...",
                "sLengthMenu": "Mostrar _MENU_ registros",
                "sZeroRecords": "No se encontraron resultados",
                "sEmptyTable": "Ningún dato disponible en esta tabla",
                "sInfo": "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
                "sInfoEmpty": "Mostrando registros del 0 al 0 de un total de 0 registros",
                "sInfoFiltered": "(filtrado de un total de _MAX_ registros)",
                "sInfoPostFix": "",
                "sSearch": "",
                "sUrl": "",
                "sInfoThousands": ",",
                "sLoadingRecords": "Cargando...",
                "oPaginate": {
                    "sFirst": "Primero",
                    "sLast": "Último",
                    "sNext": "Siguiente",
                    "sPrevious": "Anterior"
                },
                "oAria": {
                    "sSortAscending": ": Activar para ordenar la columna de manera ascendente",
                    "sSortDescending": ": Activar para ordenar la columna de manera descendente"
                }
            }

        });
    });
    
    
    
</script>