<style>
    .excel{
        background-image: url('/static/img/portal/solicitudTar/excel.png');
        width: 200px;
        height: 200px;
        background-repeat: no-repeat;
    }
    .excel:hover{
        background-image: url('/static/img/portal/solicitudTar/excel-hover.png');
        width: 200px;
        height: 200px;
        background-repeat: no-repeat;
    }
    .subir{
        background-image: url('/static/img/portal/solicitudTar/subir-excel.png');
        width: 200px;
        height: 200px;
        background-repeat: no-repeat;
    }
    .subir:hover{
        background-image: url('/static/img/portal/solicitudTar/subir-excel-hover.png');
        width: 200px;
        height: 200px;
        background-repeat: no-repeat;
    }

    .descargar{
        background-image: url('/static/img/portal/solicitudTar/descargar-plantilla.png');
        width: 199px;
        height: 40px;
        background-repeat: no-repeat;
    }
    .descargar:hover{
        background-image: url("/static/img/portal/solicitudTar/descargar-plantilla-hover.png");
        width: 199px;
        height: 40px;
        background-repeat: no-repeat;
    }

    /* td, th {
         padding: 30px;
     }*/
    .hiddenFileInput > input{
        height: 100%;
        width: 100;
        opacity: 0;
        cursor: pointer;
    }
    .hiddenFileInput{
        border: none;
        width: 120%;
        height: 50px;
        display: inline-block;
        overflow: hidden;
        margin-left: -5%;
        /*for the background, optional*/
        background: center center no-repeat;
        background-size: 100% 100%;
        background-image:  url(/static/img/portal/solicitudTar/cargar-archivo.png);
    }
    .hiddenFileInput:hover{
        border: none;
        width: 120%;
        height: 50px;
        display: inline-block;
        overflow: hidden;
        margin-left: -5%;
        /*for the background, optional*/
        background: center center no-repeat;
        background-size: 100% 100%;
        background-image:  url(/static/img/portal/solicitudTar/cargar-archivo-hover.png);
    }

    .hiddenFileDownload > a{
        height: 100%;
        width: 100;
        opacity: 0;
        cursor: pointer;
    }
    .hiddenFileDownload{
        border: none;
        width: 120%;
        height: 50px;
        display: inline-block;
        overflow: hidden;
        margin-left: -5%;
        /*for the background, optional*/
        background: center center no-repeat;
        background-size: 100% 100%;
        background-image:  url(/static/img/portal/solicitudTar/descargar-plantilla.png);
    }
    .hiddenFileDownload:hover{
        border: none;
        width: 120%;
        height: 50px;
        display: inline-block;
        overflow: hidden;
        margin-left: -5%;
        /*for the background, optional*/
        background: center center no-repeat;
        background-size: 100% 100%;
        background-image:  url(/static/img/portal/solicitudTar/descargar-plantilla-hover.png);
    }
    /*
        #pestanaSolicitud{
            background:red !important;
            border: none;
            border-bottom-color: transparent;
            border-radius: 20px 20px 0 0;
    
        }
        .nav-tabs>li.active>a {
            color: #fff;
            cursor: default;
            background-color: blue;
            border: none;
            border-bottom-color: transparent;
            border-radius: 20px 20px 0 0;
        }
        #pestanaSolicitud.active{
            background:#0c385e !important;
        }
    */
    #masivoIconos td,th{
        padding: 30px;
    }
    .glyphicon {
        font-size: 20px;
    }
    .hr{
        width: 100%;
        border-top:1px solid #afafaf;
    }
</style>
<div class="loader" id="loader" hidden></div> 
<div style=" margin-bottom: 200px; margin-top: -50px;">
    <div class="container">
        <hr style="border-top: 1px solid #eee0;">
        <h2 class="titulo-iz">Solicitud de Tarjetas</h2>
        <ul class="nav nav-tabs">
            <li id="solicitudUno"><a href="/portal/solicitudGestion/solicitudGes">Solicitud Uno a Uno</a></li>
            <li class="active"  id="solicitudMasiva "><a  href="/portal/solicitudGestion/solicitudTarjetasMasivo">Solicitud Masiva</a></li>
        </ul>

        <div class="tab-content">
            <div id="solicitudMasiva" class="tab-pane fade in active">
                <h3>Plantilla</h3>
                <div class="col-sm-4">
                    <table id="masivoIconos" cellspacing="10" cellpadding="10" border="0">
                        <tbody>
                            <tr>
                                <td>
                                    <a href="/portal/solicitudGestion/descargarPlantilla" > <div class="excel"></div> </a>
                                </td>
                                <td> </td> 
                                <td>
                                    <div class="subir"></div> 
                                </td>
                            </tr>
                            <tr>
                                <td style="padding-bottom: 66px;">
                                    <a href="/portal/solicitudGestion/descargarPlantilla" ><span class="hiddenFileDownload"></span></a>
                                </td>
                                <td> </td>
                                <td>
                                    <div>
                                        <form  method="post" action="solicitudMasiva" enctype="multipart/form-data" id="solicitudMas">
                                            <span class="hiddenFileInput">
                                                <input type="file" name="file" />
                                            </span>
                                        </form>
                                        <div class="button" >
                                            <button  data-toggle="modal" data-target="#ModalConfSolMasi">E N V I A R</button>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>

                </div>
            </div>
        </div>
    </div>
    <?php if ($error == 1) { ?>
        <script>
        </script>
        <div class="container" style="margin-top: 15%;">
            <!-- Modal -->
            <div class="modal fade" id="myModal" role="dialog" style="    margin-top: 15%;">
                <div class="modal-dialog">

                    <!-- Modal content-->
                    <div class="modal-content" style="border-radius:35px">
                        <!--  <div class="modal-header">
                              <button type="button" class="close" data-dismiss="modal">&times;</button>
                          </div>-->
                        <div class="modal-body" style="text-align: center;height: auto;">
                            <div class="modal-header">
                                <h5 style="color: #366199;font-size: 20px;font-weight: bold; ">¡La solicitud fue realizada exitosamente!</h5>
                            </div>
                            <form action="/portal/solicitudGestion/nombreOrden" method="POST" >
                                <br>
                                <p style="font-size:18px;color:#888686;">Por favor asigne un nombre a la orden:</p>

                                <input type="hidden" name="codigo" value="<?= $codigosolicitud ?>">
                                <input type="text" class="textPat"  name="nombreorden" style="width: 60%" placeholder="Ingrese un nombre para la orden" required>
                                <br><br>
                                <div style=" margin-bottom: 4em">
                                    <div class="button col-sm-6">
                                        <button type="submit" name="ORDEN" value="1" class="btn btn-default" >ORDEN DE PEDIDO</button>
                                    </div>
                                    <div class="button col-sm-6">
                                        <button type="submit" name="FINALIZAR" value="2" class="btn btn-default" >FINALIZAR</button>
                                    </div>
                                </div>
                                <br>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php } ?>

</div>
<!-- Modal confirmacion solicitud masiva-->
<div class="modal fade" id="ModalConfSolMasi" role="dialog" style="margin-top: 15%;"  data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content" style="border-radius:35px">

            <div class="modal-body" style="text-align: center;height: auto;">
                <div class="modal-header">
                    <h5 style="color: #366199;font-size: 20px;font-weight: bold; ">Confirmación</h5>
                </div>
                <p  style="font-size:14px;color:#333;font-weight: bold;padding-top: 5px;text-align: center;">
                    ¿ Desea continuar con la solicitud? 
                </p>
                <div style=" margin-bottom: 4em">
                    <div class="button col-sm-6" >
                        <button type="button" name="ACEPTAR" value="1" class="btn btn-default spacing"  onclick="
                                $('#solicitudMas').submit();" >SI</button>
                    </div>
                    <div class="button col-sm-6" >
                        <button type="button" name="CANCELAR" class="btn btn-default spacing" data-dismiss="modal">NO</button>
                    </div>
                </div>
                <br>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript" src="/static/js/jquery-2.0.3.min.js"></script>
<script type="text/javascript" src="/static/js/bootstrap.min.js"></script>
<script type="text/javascript">

                            var x = "<?= $error ?>";

                            if (x == "1") {

                                $('#myModal').modal({backdrop: 'static', keyboard: false});
                                $('#myModal').modal('show');

                            } else if (x == "5") {

                                $('#myModal1').modal('show');
                            }
                            $("#solicitudMasiva").submit(function () {
                                $('#loader').modal('show');
                            });


</script>