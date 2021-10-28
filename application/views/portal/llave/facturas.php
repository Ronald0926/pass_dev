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
        <li><a href="/portal/llaveMaestra/estado"><?php echo (($rol == 61)) ? 'Llave Maestra' : 'Llavero' ?></a></li>
         <?php } ?>
        <?php if (($rol==60) or ($rol==61)) { ?>
        <li><a href="/portal/llaveMaestra/estadoTarjetas" >Tarjetas</a></li>
          <?php } ?>
         <?php if (($rol==60) or ($rol==61)) { ?>
        <li><a href="/portal/llaveMaestra/informeAbonos" >Dispersiones</a></li>
        <?php } ?>
        <?php if (($rol==60) or ($rol==61)) { ?>
            <li><a href="/portal/llaveMaestra/informeGrafico">Informes Graficos Transaccional</a></li>
        <?php } ?>
           <?php if (($rol==60) or ($rol==59) or ($rol==61)) { ?>
            <li><a href="/portal/llaveMaestra/consultaNotasContables">Nota Contable Prepago</a></li>
        <?php } ?>
        <?php if (($rol==60) or ($rol==61)) { ?>
            <li class="active"><a href="#facturas">Facturas</a></li>
        <?php } ?>
    </ul>

    <div class="tab-content">
        <div id="facturas" class="tab-pane fade in active">

            <div class="container col-lg-12">
                <div class="grid" >
                    <table class="table table-hover daos_datagrid">
                        <thead>
                            <tr>
                                <th> Descargar </th>
                                <th> Nit </th>
                                <th> Razon Social</th>
                                <th> Número Factura </th>
                                <th> Estado </th>
                                <th> Monto </th>
                                <th> Fecha Creación </th>
                                <th> Fecha Pago </th>
                            </tr>
                        </thead>

                        <tbody>
                            <?php foreach ($Facturas as $value) { ?>
                                <tr class="gradeC">
                                    <td>
                                        <div class="login-checkbox" onclick="" style="padding-top: 5px">
                                            <span id="<?= $value['FAC'] ?>" data-estado="<?= $value['FAC'] ?>">
                                                <div class="">
                                                    <span class="login-checkbox-check noncheck" id="<?= $value['FAC'] ?>">
                                                    </span>
                                                </div>
                                            </span>
                                        </div>
                                        <br>
                                    </td>
                                    <td><?= $value['NIT'] ?></td>
                                    <td><?= $value['RAZON_SOCIAL'] ?></td>
                                     <td><?= $value['NUMERO_FACTURA'] ?></td>
                                    <td><?= $value['ESTADO'] ?></td>
                                    <td><?="$ " . number_format( $value['MONTO'],0) ?></td>
                                    <td><?= $value['FECHA_CREACION'] ?></td>
                                    <td><?= $value['FECHA_PAGO'] ?></td>
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
                            <input type="hidden" value="" id="formFact">
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
 <!-- Modal confirmacion recarga-->
    <div class="modal fade" id="ModalError" role="dialog" style="margin-top: 15%;"  data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog">

            <!-- Modal content-->
            <div class="modal-content" style="border-radius:35px">

                <div class="modal-body" style="text-align: center;height: 200px;">

                    <p  style="font-size:24px;color:#888686;font-weight: bold;padding-top: 25px">Por favor seleccioné una factura!
                    </p>
                    <div style="">
                        <div class="button col-sm-6 col-sm-push-3" >
                            <div class="row linkgenerico" style="/*padding-bottom: 100px; padding-left: 100px;*/">
                                 <button name="aceptar" data-dismiss="modal" class="btn btn-default spacing">ACEPTAR</button>
                            </div>
                        </div>
                    </div>
                    <br>
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
            $("#formFact").attr("value", "/wsonline2/factura/crear/" + status);
        }
    });
    function createPdf() {
        var str = $("#formFact").val();
        if(str.length!==0){
        $(".loader").fadeIn();
        $.ajax({
            url: $("#formFact").val(),
        })
                .done(function (data) {
                    $(".loader").fadeOut("slow");
                    window.open(data);
                });
            }else{
                $("#ModalError").modal('show');
            }
    }


</script>