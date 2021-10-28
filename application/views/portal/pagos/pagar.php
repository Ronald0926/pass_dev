<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModalCenter" id="modalProcessing" style="display:none"></button>

<!-- Modal -->
<div class="container" style="margin-top: 15%;">
    <!-- Modal -->
    <div class="modal fade" id="exampleModalCenter" role="dialog" style="    margin-top: 15%;">
        <div class="modal-dialog">

            <!-- Modal content-->
            <div class="modal-content" style="border-radius:35px">
                <!--  <div class="modal-header">
                              <button type="button" class="close" data-dismiss="modal">&times;</button>
                          </div>-->
                <div class="modal-body" style="text-align: center;height: 340px;">
                    <form action="/portal/abonos/nombreOrden" method="POST">
                        <p style="font-size:18px;color:#0c385e;font-weight: bold">¡Procesando Pago!</p>
                        <p style="font-size:18px;color:#888686;">Por favor espere mientras se confirma pago, continue con el proceso en la pasarela de pago, si ya finalizo puede cerrar este mensaje</p>
                        <div class="button col-sm-6">
                            <p style="font-size:18px;color:#0c385e;font-weight: bold">Total Pago!</p>
                            <p style="font-size:18px;color:#0c385e;font-weight: bold"><?= number_format($totalPago) ?></p>
                        </div>
                        <div class="button col-sm-6">
                            <p style="font-size:18px;color:#0c385e;font-weight: bold">¡Referencia!</p>
                            <p style="font-size:18px;color:#0c385e;font-weight: bold"><?= $referenciapago ?></p>
                        </div>
                        <p style="font-size:18px;color:#ff0000;font-weight: bold">¡ESTADO PENDIENTE DE APROBACION!</p>
                        <div class="button col-sm-12">
                            <button type="button" data-dismiss="modal" class="btn btn-default">Cerrar</button>
                        </div>  
                    </form>
                </div>
                <!--   <div class="modal-footer">
                              <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                          </div>-->
            </div>
        </div>
    </div>
</div>
<?php echo $htmlPay; ?>

<script>
    $(document).ready(function() {
        document.getElementById("modalProcessing").click();
    });
</script>