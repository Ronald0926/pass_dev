<style>
    .productos{
        background-color: #d2d2d2;
        border-radius: 25px;
    }
    .online-check{
        width:15px;
    }

    .online-tasa1{
        margin-top: 20px;
        border-color:#1c5394;
        border-radius: 25px;
        /*        border-top-left-radius: 25px;
                border-bottom-left-radius: 25px;*/
        border-style:solid;
        border-width: 1px;
        color:#1c5394;
        height:280px;
    }

    .online-tasa{
        margin-top: 20px;
        border-color:#1c5394;
        border-radius: 25px;
        /*        border-top-left-radius: 25px;
                border-bottom-left-radius: 25px;*/
        border-style:solid;
        border-width: 1px;
        color:#1c5394;
        height:280px;
    }
    .online-tasa2 button{
        background-color: white;
        color:#1c5394;
        margin-top:60px;
    }
    .online-tasa2{
        border-radius:25px;
        background-color:#1c5394;
        color:white;
        margin:-5px;
        margin-top: -10px;
        padding: 20px;
        height:350px;
    }
    .online-tasa:hover{
        border-radius:25px;
        background-color:#1c5394;
        color:white;
        margin:-5px;
        margin-top: -10px;
        padding: 20px;
        height:350px;
    }
    .online-tasa:hover button{
        background-color: white;
        color:#1c5394;
        margin-top:60px;
    }
    .online-tasa h1{
        font-weight: bold;
        font-size: 50px;
        margin-top:-12px;
        margin-bottom:15px;
    }

    .online-tasa1:hover{
        border-radius:25px;
        background-color:#1c5394;
        color:white;
        margin:-5px;
        margin-top: -10px;
        padding: 20px;
        height:350px;
    }
    .online-tasa1:hover button{
        background-color: white;
        color:#1c5394;
        margin-top:60px;
    }
    .online-tasa3{
        margin-top: 20px;
        border-color:#1c5394;
        border-radius: 25px;
        /*        border-top-right-radius: 10px;
                border-bottom-right-radius: 10px;*/
        border-style:solid;
        border-width: 1px;
        color:#1c5394;
        height:280px;
    }
    .online-tasa3:hover{
        border-radius:25px;
        background-color:#1c5394;
        color:white;
        margin:-5px;
        margin-top: -10px;
        padding: 20px;
        height:350px;
    }
    .online-tasa3:hover button{
        background-color: white;
        color:#1c5394;
        margin-top:60px;
    }
    .online-tasa1 h5{
        font-weight: bold;
    }
    .online-tasa2 h5{
        font-weight: bold;
        text-align: center;
    }
    .online-tasa3 h5{
        font-weight: bold;
    }
    .online-tasa1 h1{
        font-weight: bold;
        font-size: 50px;
        margin-top:-12px;
        margin-bottom:15px;
    }
    .online-tasa3 h1{ 
        font-weight: bold;
        font-size: 50px;
        margin-top:-12px;
        margin-bottom:15px;
    }
    .online-tasa2 h1 {
        font-weight: bold;
        font-size: 65px;
        text-align: center;
        /*        margin-top: -10px;*/
    }
    .online-tasa:hover h1 {
        font-weight: bold;
        font-size: 65px;
        text-align: center;
        /*        margin-top: -10px;*/
    }
</style>
<div class="col-md-2" ></div>
    <!-- <div class="col-lg-4" hidden>
 
         <h1 style="color: #1C5394; padding-left:  1%;">Cotización</h1>
         <form method="POST">
             <select style="width:100%;border-radius:10px;padding-left:10px" name='tdocumento'><option value="0">Selecciones Linea de Productos*</option></select><br><br>
             <input class="online-check" type="checkbox"> <label>Tarjeta Market Vale</label> <a>Cantidad de tarjetas</a><br>
             <input class="online-check" type="checkbox"> <label>Tarjeta Alimentación</label> <a>Cantidad de tarjetas</a><br>
             <input class="online-check" type="checkbox"> <label>Tarjeta Combustible</label> <a>Cantidad de tarjetas</a><br>
             <input class="online-check" type="checkbox"> <label>Tarjeta Market Vale</label> <a>Cantidad de tarjetas</a><br>
             <input class="online-check" type="checkbox"> <label>Tarjeta Market Vale</label> <a>Cantidad de tarjetas</a>
             Facturación:____________________
         </form>
     </div>-->
    <div class="col-md-8" style="margin-top: 6%">
       
        <div class="row">
            <form  method="POST" id="formulariotasa1" action="/portal/cotizacion/activar" >
                <div class="online-tasa1 col-sm-4">
                    <h5>Tasa de Administración</h5>
                    <h1 id="h1porMayor"> <?= $porcentajemayor!=3500?$porcentajemayor.' %':$porcentajemayor ?> </h1>
                    <input name="porcentaje" id="porMayor" value="<?= $porcentajemayor ?>" hidden>
                    <p>Si se elige un abono cantidad
                        menor de tarjetas la tasa de
                        adiministración esta sería la tasa
                        de administración que se le
                        aplicaría en su siguiente compra.</p>
                    <div class="button col-sm-12">
                        <!--                        <button type="submit">
                                                    A C T I V A R
                                                </button>-->
                        <button type="button" data-toggle="modal" data-target="#myModaltasa1">A C T I V A R</button>
                    </div>
                </div>
            </form>
            <form  method="POST" id="formulariotasa2" action="/portal/cotizacion/activar" >
                <div class="online-tasa2 col-sm-4" >
                    <h5>Tasa de Administración</h5>
                    <h1 id="h1porCentro"><?= $porcentajecentro!=3500?$porcentajecentro.' %':$porcentajecentro ?> </h1>
                    <input name="porcentaje" id="porCentro" value="<?= $porcentajecentro ?>" hidden>
                    <p>Con las condiciones
                        elegidas esta sería la tasa
                        de admninistración que se
                        le aplicaría en su siguiente
                        compra.</p>
                    <div class="button col-sm-12" >
                        <!--                        <button  type="submit" style="/*background-color: white;color:#1c5394;margin-top:60px*/">
                                                    A C T I V A R
                                                </button>-->
                        <button type="button" data-toggle="modal" data-target="#myModaltasa2">A C T I V A R</button>
                    </div>
                </div>
            </form>
            <form  method="POST" id="formulariotasa3" action="/portal/cotizacion/activar" >
                <div class="online-tasa3 col-sm-4">
                    <h5>Tasa de Administración</h5>
                    <h1 id="h1porMenor"> <?= $porcentajemenor ?> %</h1>
                    <input name="porcentaje" id="porMenor" value="<?= $porcentajemenor ?>" hidden class="textPat">
                    <p>Si se elige ___ cantidad adicional
                        de tarjetas e incrementa el abono
                        un 10% esta sería la tasa de
                        adminsitración que se le aplicaría
                        en su siguiente compra.</p>
                    <div class="button col-sm-12">
                        <!--                        <button type="submit">
                                                    A C T I V A R
                                                </button>-->
                        <button type="button" data-toggle="modal" data-target="#myModaltasa3">A C T I V A R</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
<div class="col-md-2"></div>
<div class="container" style="margin-top: 15%;">
    <!-- Modal -->
    <div class="modal fade" id="myModaltasa3" role="dialog" style="    margin-top: 15%;">
        <div class="modal-dialog">

            <!-- Modal content-->
            <div class="modal-content" style="border-radius:35px">

                <div class="modal-body" style="text-align: center;height: 230px;">

                    <p  style="font-size:24px;color:#888686;font-weight: bold;padding-top: 25px">¡Su cotización estará activa para el
                        próximo pedido!
                    </p>
                    <br>

                    <div style="">
                        <div class="button col-sm-6" >
                            <button type="button" name="ACEPTAR" value="1" class="btn btn-default" onclick="
                                    $('#formulariotasa3').submit();
                                    " >A C E P T A R</button>
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
</div>
<div class="container" style="margin-top: 15%;">
    <!-- Modal -->
    <div class="modal fade" id="myModaltasa2" role="dialog" style="    margin-top: 15%;">
        <div class="modal-dialog">

            <!-- Modal content-->
            <div class="modal-content" style="border-radius:35px">

                <div class="modal-body" style="text-align: center;height: 230px;">

                    <p  style="font-size:24px;color:#888686;font-weight: bold;padding-top: 25px">¡Su cotización estará activa para el
                        próximo pedido!
                    </p>
                    <br>

                    <div style="">
                        <div class="button col-sm-6" >
                            <button type="button" name="ACEPTAR" value="1" class="btn btn-default" onclick="
                                    $('#formulariotasa2').submit();
                                    " >A C E P T A R</button>
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
</div>
<div class="container" style="margin-top: 15%;">
    <!-- Modal -->
    <div class="modal fade" id="myModaltasa1" role="dialog" style="    margin-top: 15%;">
        <div class="modal-dialog">

            <!-- Modal content-->
            <div class="modal-content" style="border-radius:35px">

                <div class="modal-body" style="text-align: center;height: 230px;">

                    <p  style="font-size:24px;color:#888686;font-weight: bold;padding-top: 25px">¡Su cotización estará activa para el
                        próximo pedido!
                    </p>
                    <br>

                    <div style="">
                        <div class="button col-sm-6" >
                            <button type="button" name="ACEPTAR" value="1" class="btn btn-default" onclick="
                                    $('#formulariotasa1').submit();
                                    " >A C E P T A R</button>
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
</div>
<script>
    $(document).ready(function () {



    });
    $(".online-tasa1").hover(function () {
        $(".online-tasa2").attr('class', 'online-tasa col-sm-4');
    }, function () {
        $(".online-tasa").attr('class', 'online-tasa2 col-sm-4');
    });
    $(".online-tasa3").hover(function () {
        $(".online-tasa2").attr('class', 'online-tasa col-sm-4');
    }, function () {
        $(".online-tasa").attr('class', 'online-tasa2 col-sm-4');
    });



</script>