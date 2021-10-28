<style>
    #titulo {
        color: #366199;
        font-size: 56px;

    }
</style>

<div style="padding-left:8%;">
    <h1 id="titulo">Entregas</h1>
</div>
<img id="estado_pedido" src="/static/img/portal/entregas/solicitado.png" style="width: 50%;height: 70%;" class="center-block">
<div style=" margin-bottom: 200px; margin-top: -50px;">
    <div class="grid" style="margin: 8%;margin-top: 6%;">
        <table id="tpedidos" class="table table-hover daos_datagrid">
            <thead>
                <tr>
                    <th> Estado de Pedido </th>
                    <th> No.de Pedido </th>
                    <th> No.de Remesa </th>
                    <th> No.de Tarjetas </th>
                    <th> Fecha Solicitado </th>
                    <th> Fecha Recibido </th>
                    <th> Confirmar Pedido </th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($pedidos as $value) { ?>
                    <tr class="checks">
                        <td>
                            <div class="login-checkbox" onclick="" style="padding-top: 5px;">
                                <span id="<?= $value['NUMEROPEDIDO'] ?>" data-estado="<?= $value['ESTADOPEDIDO'] ?>">
                                    <div class="">
                                        <span class="login-checkbox-check noncheck" id="<?= $value['NUMEROPEDIDO'] ?>">
                                        </span>
                                    </div>
                                </span>
                            </div>
                            <br>
                        </td>
                        <td>#<?= $value['NUMEROPEDIDO'] ?></td>
                        <td>#<?= $value['CODIGOENVIO'] ?></td>
                        <td><?= $value['CANTIDADTARJETAS'] ?></td>
                        <td><?= $value['FECHASOLICITUD'] ?></td>
                        <td><?= $value['FECHARECIBIDO'] ?></td>
                        <td align="center">
                            <?php if ($value['CODESTADOENVIO'] == 3 && $value['CANTTARACTIVAS'] == 0) { ?>
                                <a href="/portal/entregas/confirmarEntrega/<?= $value['NUMEROPEDIDO'] ?>">
                                    <img src="/static/img/portal/entregas/icono_seleccionar2.png" style="width: 20px;height: 20px;">
                                </a>
                            <?php } else if ($value['CODESTADOENVIO'] == 3 && ($value['CANTTARACTIVAS'] == $value['CANTIDADTARJETAS'])) { ?>
                                <a href="/portal/entregas/confirmarEntrega/<?= $value['NUMEROPEDIDO'] ?>">
                                    <img src="/static/img/portal/entregas/apagar.png" style="width: 45px;height: 40px;">
                                </a>
                            <?php } else if  ($value['CODESTADOENVIO'] == 3 && ($value['CANTTARACTIVAS'] != $value['CANTIDADTARJETAS'])) {?>
                                <a href="/portal/entregas/confirmarEntrega/<?= $value['NUMEROPEDIDO'] ?>">
                                    <img src="/static/img/portal/entregas/apagarAzul.png" style="width: 45px;height: 40px;">
                                </a>
                            <?php } ?>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>

        </table>
    </div>
</div>
<script>
    $(document).ready(function () {
        $('#tpedidos tbody').on('click', 'tr', function () {
            $(this).toggleClass('selected');
        });
        $('body').on('click', '.checks span', function () {
            var id = $(this).attr('id');
            if (id != undefined) {
                var status = $(this).data('estado');
                var y = $('.noncheck');
                for (i = 0; i < y.length; i++) {
//                    console.log(id + " " + y[i].id)
                    if (y[i].id != id) {
                        y[i].style.display = "none";
                    }
                }
                switch (status) {
                    case "NO PROCESADA":
                        $("#estado_pedido").attr("src", "/static/img/portal/entregas/solicitado.png");
                        break;
                    case "ENVIADO":
                        $("#estado_pedido").attr("src", "/static/img/portal/entregas/enviado.png");
                        break;
                    case "NO ENVIADO":
                        $("#estado_pedido").attr("src", "/static/img/portal/entregas/empacado.png");
                        break;
                    case "ENTREGADO":
                        $("#estado_pedido").attr("src", "/static/img/portal/entregas/recibido.png");
                        break;
                    case "PROCESADO":
                        $("#estado_pedido").attr("src", "/static/img/portal/entregas/procesado.png");
                        break;
                }
            }
        });

    });
</script>