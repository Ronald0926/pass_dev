<div class="loader" id="loader" hidden=""></div>
<div class='row'>
    <div class='col-md-3'></div>
    <div class='col-md-6'>
        <h1 class="titulo-iz">Reverso Tarjetas</h1>
        <form method="POST" action="/portal/llaveMaestra/reversosaldotarjeta" id="formreversosaldo">
            <div class='col-md-4' style="background-color:#366199; color: white;">

                <label required type="text" style="padding-left:10px">
                    Tarjeta a Reversar: </label><br>
                <label required type="text" style="padding-left:10px">
                    <?= $numtar ?></label><br>

                <label required type="text" style="padding-left:10px">
                    Saldo disponible:</label><br>
                <label required type="text" style="padding-left:10px">
                    $ <?= number_format($saldotarjeta, 0, ',', '.'); ?></label><br>

            </div>
            <div class='col-md-4'>
                <div class="triangulo" style="margin-left: 45%"></div>
            </div>
            <div class='col-md-4' style="background-color:#366199; color: white; height: 130px">

                <label  type="text" style="padding-left:10px">
                    Destino: </label><br>
                <label  type="text" style="padding-left:10px">
                    <?= $llave_maestra[0]["NOMBRE_LLAVERO"] ?></label><br>

                <label  type="text" style="padding-left:10px">
                    Saldo actual: </label><br> 
                <label  type="text" style="padding-left:10px">
                    $ <?= number_format($saldo_llavero, 0, ',', '.'); ?></label><br>



            </div>
            <div class='col-md-12'>
                <label required type="text" style="padding-left:10px">
                    Valor a reversar:</label>
                <div class="row" ><label class="tnotifi">Ingrese un valor valido.</label></div>
                <input required name="montoreverso" id="montoreverso" type="text" placeholder="$0" data-type="currency" style="width: 28%;border: 1px solid #757575; border-radius: 50px;padding: 5px 10px 5px 15px;"  onfocusout="validemonto(this.value)"><br>
                <label  type="text" style="padding-left:10px"><br>
                    Fecha de operacion: <?php echo date('d/m/Y') ?></label><br>
            </div>
        </form>
        <div class="button col-md-4 col-md-push-4">
            <button class="btn btn-default spacing" id="btnreverso" data-toggle="modal" data-target="#myModalReverso"> REVERSAR</button>
            <!-- <button type="button" data-toggle="modal" data-target="#myModalReverso">Prueba modal</button> -->
            <br><br>
            <div class=" linkgenerico spacing">
                <a href="/portal/llaveMaestra/reverso"><span class="glyphicon glyphicon-chevron-left"></span>VOLVER</a>
            </div>
        </div>
    </div>
    <div class='col-md-3'></div>
</div>
<!-- Modal -->
<div class="modal fade" id="myModal" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <?php if ($error == 1) { ?>
                    <h4 class="modal-title">Reverso Exitoso</h4>
                <?php } else { ?>
                    <h4 class="modal-title">Error en la creacion <?= $error ?></h4>
                <?php } ?>
            </div>
            <div class="modal-body">
                <p>Numero de movimiento: <?= $movimiento ?></p>

                <div class="button col-sm-6 CLOSE">
                    <button name="solabono" type="submit" class="btn btn-default" data-dismiss="modal">ACEPTAR</button>
                </div>
            </div>

        </div>

    </div>
</div>


<!-- Modal confirmacion reverso-->
<div class="modal fade" id="myModalReverso" role="dialog" style="margin-top: 15%;"  data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content" style="border-radius:35px">

            <div class="modal-body" style="text-align: center;height: 230px;">

                <p  style="font-size:24px;color:#888686;font-weight: bold;padding-top: 25px">¿Esta seguro de realizar el reverso?
                </p>
                <label id="nomllavero" style="font-size: 18px;color: #366199;font-weight: bold;"></label>

                <div style="">
                    <div class="button col-sm-6" >
                        <button type="button" name="ACEPTAR" value="1" class="btn btn-default"  onclick="
                                $('#formreversosaldo').submit();" >A C E P T A R</button>
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

<script type="text/javascript">
    $(document).ready(function () {
        var error = '<?= $error ?>';
        if (error != null && error != '') {
            $('#myModal').modal('show');
        }
    });
    function validemonto(monto) {
        var porciones = monto.split('.');
        var montom=CifrasEnLetras.dejarSoloCaracteresDeseados(porciones[0],"0123456789");
        var saldotarjeta =<?php echo $saldotarjeta ?>;
        var dif = saldotarjeta - parseInt(montom);
        if (dif < 0) {
            $(".tnotifi").show();
            $("#btnreverso").attr("disabled", true);
        } else {
            $(".tnotifi").hide();
            $('#btnreverso').removeAttr("disabled");
        }
    }
    var boton = document.getElementById("btnreverso");
    boton.onclick = function (e) {
        $("#montoreverso").val();
        if( $("#montoreverso").val().length <= 0){
            $(".tnotifi").show();
            $("#btnreverso").attr("disabled", true);
        }
    }
    $("#formreversosaldo").submit(function () {
        $('#loader').modal('show');
    });
</script>

<style> 
    .tnotifi{
        color: red;
        padding-left:  20px;
        display: none;
    }

    div.arrow {
        width: 6vmin;
        height: 6vmin;
        box-sizing: border-box;
        position: absolute;
        left: 50%;
        top: 50%;
        transform: rotate(45deg);

        &::before {
            content: '' !important;
            width: 100% !important;
            height: 100% !important;
            border-width: .8vmin .8vmin 0 0 !important;
            border-style: solid !important;
            border-color: #366199 !important;
            transition: .2s ease !important;
            display: block !important;
            transform-origin: 100% 0 !important;
        }
    }

    .triangulo {
        margin-top: 7%;
        width: 0;
        height: 0;
        border-left: 30px solid #366199;
        border-top: 25px solid transparent;
        border-bottom: 25px solid transparent;
    }
</style>