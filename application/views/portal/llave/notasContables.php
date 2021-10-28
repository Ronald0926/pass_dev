<?php
//$rol = $this->session->userdata("rol");
$rol = $_SESSION['rol'];
?>
<div class="col-lg-1" ></div>
<div class="container col-lg-10" style=" margin-bottom: 200px; margin-top: -50px;">
    <hr style="border-top: 1px solid #eee0;">
    <h2 class="titulo-iz">Estado de cuenta</h2>
    <ul class="nav nav-tabs">
         <?php if(($rol==60) or ($rol==61)){?>
        <li><a href="/portal/llaveMaestra/estado"><?php echo (($rol==61)) ? 'Llave Maestra' : 'Llavero' ?></a></li>
         <?php } ?>
        <?php if (($rol==60) or ($rol == 61)) { ?>
        <li ><a data-toggle="tab" href="#estadoTarjetas" >Tarjetas</a></li>
         <?php } ?>
         <?php if (($rol==60) or ($rol == 61)) { ?>
        <li><a href="/portal/llaveMaestra/informeAbonos" >Dispersiones</a></li>
        <?php } ?>
        <?php if (($rol==60) or ($rol == 61)) { ?>
            <li><a href="/portal/llaveMaestra/informeGrafico">Informes Graficos Transaccional</a></li>
        <?php } ?>
        <?php if (($rol==60) or ($rol==59) or ($rol == 61)) { ?>
            <li class="active"><a href="#consultaNotasContables">Nota Contable Prepago</a></li>
        <?php } ?>
        <?php if (($rol==60) or ($rol == 61)) { ?>
            <li><a href="/portal/llaveMaestra/consultaFacturas">Facturas</a></li>
        <?php } ?>
    </ul>

    <div class="tab-content">
        <div id="estadoTarjetas" class="tab-pane fade in active">

            <div class="container col-lg-12">
                <div class="grid" >
                    <table class="table table-hover daos_datagrid">
                        <thead>
                            <tr>
                                <th> Descargar </th>
                                <th> Prefijo </th>
                                <th> No. Nota </th>
                                <th> Número Orden </th>
                                <th> Monto </th>
                                <th> Estado </th>
                                <th> Fecha Creación </th>
                                <th> Fecha Pago </th>
                                <th> Medio Pago </th>
                            </tr>
                        </thead>

                        <tbody>
                            <?php foreach ($notaContables as $value) { ?>
                                <tr class="gradeC">
                                    <td>
                                        <div class="login-checkbox" onclick="" style="padding-top: 5px">
                                            <span id="<?= $value['NUMERO_ORDEN'] ?>" data-estado="<?= $value['NUMERO_ORDEN'] ?>">
                                                <div class="">
                                                    <span class="login-checkbox-check noncheck" id="<?= $value['NUMERO_ORDEN'] ?>">
                                                    </span>
                                                </div>
                                            </span>
                                        </div>
                                        <br>
                                    </td>
                                    <td><?= $value['PREFIJO'] ?></td>
                                    <td><?= $value['NUMERO_NOTA'] ?></td>
                                    <td><?= $value['NUMERO_ORDEN'] ?></td>
                                    <td><?= "$ " . number_format($value['MONTO'], 0) ?></td>
                                    <td><?= $value['ESTADO'] ?></td>
                                    <td><?= $value['FECHA_CREACION'] ?></td>
                                    <td><?= $value['FECHA_PAGO'] ?></td>
                                    <td>
                                    <?php if (empty($value['MEDIO_PAGO'])) { ?>
                                        <a href="/portal/llaveMaestra/pagarNota/<?= $value['ID_NOTA_CONTABLE'] ?>" class="glyphicon glyphicon-credit-card"> PAGAR
                                        </a>
                                    <?php } else {?>
                                    <?= $value['MEDIO_PAGO'] ?>
                                    <?php } ?>
                                    </td>
                                </tr>
                            <?php } ?>
                        </tbody>

                    </table>   
                    <div class="row">
                        <!--    <div class="button col-sm-2">
                                <button type="submit">
                                    Descargar EXCEL
                                </button>
                            </div>-->
                        <div class="button col-sm-2">
                            <input type="hidden" value="" id="formNota">
                            <button id="createFactura" onclick="createPdf()">
                                Descargar PDF
                            </button>
                        </div>
                    </div>
                </div>  
            </div>
        </div>
    </div>
</div>

<script>
    $('body').on('click', '.gradeC span', function () {
        var id = $(this).attr('id');
        if (id !== undefined) {
            var status = $(this).data('estado');
            var y = $('.noncheck');
            for (i = 0; i < y.length; i++) {
                if (y[i].id != id) {
                    y[i].style.display = "none";
                }
            }
            $("#formNota").attr("value", "/wsonline2/pdfNotaContablePrepago/crear/" + status);
        }
    });
    function createPdf() {
        $(".loader").fadeIn();
        $.ajax({
            url: $("#formNota").val(),
        })
                .done(function (data) {
                    $(".loader").fadeOut("slow");
                    window.open(data);
                });
    }


</script>