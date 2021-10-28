<link href="https://gitcdn.github.io/bootstrap-toggle/2.2.2/css/bootstrap-toggle.min.css" rel="stylesheet">
<script src="https://gitcdn.github.io/bootstrap-toggle/2.2.2/js/bootstrap-toggle.min.js"></script>
<style>
    .tnotifi{
        color: red;
        padding-left:  15px;
        display: none;
    }
    .lblnoticarga{
        color: red;
        padding-left:  15px;
        padding-bottom: 0px;
        display: none;
    }
    .label_carga{
        font-size: 15px;
        font-weight: bold;   
        padding: 8px;
        color: #366199;
        border: 1px solid;
        border-color: #366199;
        border-radius: 25px;
    }
    .nom_llavero_carga{
        text-align: center ;
        font-size: 25px;
        font-weight: bold; 
        color: #366199;
        padding-bottom: 35px;
    }
    .label_saldo_in{
        background-color: #e3e3e3;
        font-size: 15px;
        font-weight: bold;   
        padding: 8px;
        color: #888;
        border: 1px solid;
        border-color: #979797;
        border-radius: 25px;
    }
</style>
<div class="loader" id="loader" hidden=""></div>
<div class='row'>
    <div class="col-4"></div>
    <div class='col-4' style="margin-bottom: 5%">
            <h1 class="titulo">Cargar  llavero</h1>

            <form  method="POST" id="formcargarllavero">
                <input type="number" name="pk_llavero" value="<?= $pk_llavero ?>" hidden>
                <input name="carga_llavero" value="1" hidden>
                <input type="text" style="padding-left:10px" name="nombllavero" id="nombllavero" placeholder="Nombre llavero*" value="<?= $nomllavero ?>" hidden><br>
                <div style="text-align: center">
                    <label class="nom_llavero_carga"><?= $nomllavero ?></label>
                </div>

                <div>
                    <label style="margin-top: 10px; font-size: 15px;padding-left:  8px">Coordinador responsable</label>
                    <div class="label_carga">
                        <?php echo $nomcoordinador != "" ? $nomcoordinador : 'Error' ?>
                    </div>
                    <!--
                    <label style="margin-top: 10px;font-size: 15px;padding-left:  8px">Administrador de pagos</label>
                    <div class="label_carga">
                        <?php echo $nomadmpago != "" ? $nomadmpago : 'Error' ?>
                    </div>
                    -->
                    <div class="label_saldo_in" style="text-align: center;margin-top: 20px" > 
                        <label style=""> $ <span  id="saldoanterior"></span></label>
                    </div>
                    <label style="margin-top: 10px;font-size: 16px;color:#366199;padding-left:8px;">Valor Carga</label>
                    <div class="row" style="padding-left:8px;"><label class="lblnoticarga"> Por favor ingrese valor.</label></div>
                    <input class="label_carga" name="valorCarga" style="text-align: center" id="valorCarga" data-type="currency" placeholder="Ingrese valor a cargar*" required>

                </div>
                <br>
        <!--<input class="numPat" type="text" style="padding-left:10px" name="monto" placeholder="Monto asignado*" value="" hidden>-->

            </form>
            <div class="button  col-md-6 col-md-push-3">
                <button class="spacing" data-toggle="modal" data-target="#ModalConfCarga">
                    CARGAR
                </button>
                <br><br>
                <div class=" linkgenerico spacing">
                    <a href="/portal/llaveMaestra/gestion_llaveros">VOLVER</a>
                </div>
            </div>

        </div>
        <!-- Modal confirmacion-->
        <div class="modal fade" id="ModalConfCarga" role="dialog" style="margin-top: 15%;"  data-backdrop="static" data-keyboard="false">
            <div class="modal-dialog">

                <!-- Modal content-->
                <div class="modal-content" style="border-radius:35px">

                    <div class="modal-body" style="text-align: center;height: 230px;">

                        <p  style="font-size:24px;color:#888686;font-weight: bold;padding-top: 25px">¿Esta seguro de realizar esta recarga?
                        </p>
                        <label id="nomllavero" style="font-size: 18px;color: #366199;font-weight: bold;"></label>

                        <div style="">
                            <div class="button col-sm-6" >
                                <button type="button" name="ACEPTAR" value="1" class="btn btn-default"  onclick="
                                            $('#formcargarllavero').submit();" >A C E P T A R</button>
                            </div>
                            <div class="button col-sm-6" >
                                <button type="button" name="CANCELAR" class="btn btn-default" data-dismiss="modal">C A N C E L A R</button>
                            </div>
                        </div>
                        <br>
                    </div>
                </div>
            </div>
        </div>
        <?php if (isset($error_tx)) { ?>
            <!-- Modal error-->
            <div class="modal fade" id="ModalerrorTX" role="dialog" style="margin-top: 15%;"  data-backdrop="static" data-keyboard="false">
                <div class="modal-dialog">

                    <!-- Modal content-->
                    <div class="modal-content" style="border-radius:35px">

                        <div class="modal-body" style="text-align: center;height: 250px;">
                            <div class="modal-header">
                                <h5 style="color: #366199;font-size: 20px;font-weight: bold; ">Fondos insuficientes</h5>
                            </div>
                            <p  style="font-size:24px;color:#888686;font-weight: bold;padding-top: 25px"><?php echo $error_tx ?>
                            </p>
                            <label id="nomllavero" style="font-size: 18px;color: #366199;font-weight: bold;"></label>

                            <div style="">
                                <div class="button col-sm-6 col-sm-push-3" >
                                    <button type="button" name="CANCELAR" class="btn btn-default spacing" data-dismiss="modal">ACEPTAR</button>
                                </div>
                            </div>
                            <br>
                        </div>
                    </div>
                </div>
            </div>
        <?php } ?>
           
    <div class="col-4"></div>
</div>

<script type="text/javascript">
    var errortx = <?php
    if (isset($error_tx)) {
        echo "1;";
    } else {
        echo "0;";
    }
    ?>
     var error_carga = '<?= $error_carga ?>';
        if (error_carga != null && error_carga != '') {
            $(".lblnoticarga").show();
        }    
    var valorantllavero=<?php echo $saldo_llavero ?>;
    $(document).ready(function () {
        var total1 = currencyFormat(valorantllavero);
                $('#saldoanterior').text(total1);
     
    });
       function currencyFormat(num) {
        return  parseFloat(num).toFixed(0).replace('.', ',').replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1.');
    }
    if (errortx === 1) {
        $("#ModalerrorTX").modal('show');
    }
    $("#formcargarllavero").submit(function () {
        $('#loader').modal('show');
    });
</script>