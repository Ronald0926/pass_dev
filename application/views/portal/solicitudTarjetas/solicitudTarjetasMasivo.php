<style>
    .excel-new{
        background-image: url('/static/img/portal/solicitudTar/excel-new.png');
        width: 245px;
        height: 212px;
        background-repeat: no-repeat;
    }
/*    .excel:hover{
        background-image: url('/static/img/portal/solicitudTar/excel-hover.png');
        width: 200px;
        height: 200px;
        background-repeat: no-repeat;
    }*/
    .subir{
        background-image: url('/static/img/portal/solicitudTar/subir-excel.png');
        width: 200px;
        height: 200px;
        background-repeat: no-repeat;
    }
/*    .subir-new{
        background-image: url('/static/img/portal/solicitudTar/subir-excel-new.png');
        width: 245px;
        height: 212px;
        background-repeat: no-repeat;
    }*/
/*    .subir:hover{
        background-image: url('/static/img/portal/solicitudTar/subir-excel-hover.png');
        width: 200px;
        height: 200px;
        background-repeat: no-repeat;
    }*/

    .descargar{
        background-image: url('/static/img/portal/solicitudTar/descargar-plantilla.png');
        width: 199px;
        height: 40px;
        background-repeat: no-repeat;
    }
/*    .descargar:hover{
        background-image: url("/static/img/portal/solicitudTar/descargar-plantilla-hover.png");
        width: 199px;
        height: 40px;
        background-repeat: no-repeat;
    }*/

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
        width: 245px;
        height: 34px;
        display: inline-block;
        overflow: hidden;
        margin-left: -5%;
        /*for the background, optional*/
        background: center center no-repeat;
        background-size: 100% 100%;
        background-image:  url(/static/img/portal/solicitudTar/cargar-archivo-new.png);
    }
/*    .hiddenFileInput:hover{
        border: none;
        width: 120%;
        height: 50px;
        display: inline-block;
        overflow: hidden;
        margin-left: -5%;
        for the background, optional
        background: center center no-repeat;
        background-size: 100% 100%;
        background-image:  url(/static/img/portal/solicitudTar/cargar-archivo-hover.png);
    }*/

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
/*    .hiddenFileDownload:hover{
        border: none;
        width: 120%;
        height: 50px;
        display: inline-block;
        overflow: hidden;
        margin-left: -5%;
        for the background, optional
        background: center center no-repeat;
        background-size: 100% 100%;
        background-image:  url(/static/img/portal/solicitudTar/descargar-plantilla-hover.png);
    }*/
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
        padding: 5px;
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
            <li id="solicitudUno"><a href="/portal/solicitudTarjetas/solicitud">Solicitud Individual</a></li>
            <li class="active"  id="solicitudMasiva "><a  href="/portal/solicitudTarjetas/solicitudTarjetasMasivo">Solicitud Masiva</a></li>
            <li id="solicitudMasiva "><a  href="/portal/solicitudTarjetas/solicitudTarjetasMasivouau">Envios Personalizados</a></li>
        </ul>

        <div class="tab-content">
            <div id="solicitudMasiva" class="tab-pane fade in active">
                <h3>Solicitud masiva de tarjetas</h3>
                <p>1. Aqu&iacute;, podr&aacute;s solicitar varias tarjetas a la vez, descargando la plantilla, diligenciando los datos solicitados de las tarjetas y los colaboradores a quienes deseas proporcionarles estos productos, guardando los cambios de la plantilla, subi&eacute;ndola y envi&aacute;ndola desde la plataforma.</p>
                <p>2. Si deseas enviar una o varias tarjetas a una direcci&oacute;n espec&iacute;fica, dir&iacute;gete al m&oacute;dulo de &OpenCurlyDoubleQuote;env&iacute;os personalizados&CloseCurlyDoubleQuote; una vez realices la solicitud.</p>
                <div class="col-sm-4">
                    <table id="masivoIconos" cellspacing="10" cellpadding="10" border="0">
                        <tbody>
                            <tr>
                                <p>Solicitud masiva de tarjetas</p>
                            </tr>
                            <tr>
                                <td>
                                    <a href="/portal/solicitudTarjetas/descargarPlantilla" > <div class="excel-new"></div> </a>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <div style="padding-left: 13px;">
                                        <form  method="post" action="solicitudMasiva" enctype="multipart/form-data" id="solicitudMas">
                                            <span class="hiddenFileInput">
                                                <input type="file" name="file" />
                                            </span>
                                            <input type='hidden' id="status" value=''>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <div class="button" >
                                        <button style="max-width: 245px;" data-toggle="modal" data-target="#ModalConfSolMasi">Enviar Plantilla Subida</button>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <hr>
                
<!--                <h3>Plantilla Uno a Uno</h3>
                <div class="col-sm-4">
                    <table id="masivoIconos" cellspacing="10" cellpadding="10" border="0">
                        <tbody>
                            <tr>
                                <td>
                                    <a href="/portal/solicitudTarjetas/descargarPlantillaUnoAUno" > <div class="excel"></div> </a>
                                </td>
                                <td> </td> 
                                <td>
                                    <div class="subir"></div> 
                                </td>
                            </tr>
                            <tr>
                                <td style="padding-bottom: 66px;">
                                    <a href="/portal/solicitudTarjetas/descargarPlantillaUnoAUno" ><span class="hiddenFileDownload"></span></a>
                                </td>
                                <td> </td>
                                <td>
                                    <div>
                                        <form  method="post" action="solicitudMasivaUnoAUno" enctype="multipart/form-data" id="solicitudMasuau">
                                            <span class="hiddenFileInput">
                                                <input type="file" name="fileunoauno" />
                                            </span>
                                        </form>
                                        <div class="button" >
                                            <button  data-toggle="modal" data-target="#ModalConfSolMasiuau">E N V I A R</button>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>-->
            </div>
        </div>
    </div>
    <?php if ($error == 1) { ?>
        <!-- Modal -->
        <div class="modal fade" id="myModalFinalizar" role="dialog" style="    margin-top: 15%;" data-backdrop="static" data-keyboard="false">
            <div class="modal-dialog">

                <!-- Modal content-->
                <div class="modal-content" style="border-radius:35px">
                    <div class="modal-body" style="text-align: center;height: auto;">
                        <div class="modal-header" style="padding:0px">
                            <h5 style="color: #366199;font-size: 24px;font-weight: bold; ">Nombrar orden</h5>
                        </div>
                        <div>
                            <br>
                            <form method="post" action="/portal/abonos/finalizarPrePedidoAbo" id="formNombreOrden" >
                                <input type="hidden" name="SoliTarMasi" value="1"> 
                                <input type="hidden" name="pedido" value="<?= $_GET['c'] ?>"> 
                                <p style="font-size:18px;color:#888686;">Por favor asigne un nombre a la orden:</p>

                                <input type="text" class="textPat"  name="nombreorden" style="width: 60%" placeholder="Ingrese un nombre para la orden" required>
                                <br><br>
                                <div class="button col-sm-6 col-sm-push-3" >
                                    <button type="submit" name="ACEPTAR" value="1" class="btn btn-default spacing" >CONFIRMAR</button>
                                </div>
                            </form>
                            <br><br><br><br>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php } ?>
<?php if ($error == 2) { ?>
        
        <!-- Modal -->
        <div class="modal fade" id="myModalFinalizar2" role="dialog" style="    margin-top: 15%;" data-backdrop="static" data-keyboard="false">
            <div class="modal-dialog">

                <!-- Modal content-->
                <div class="modal-content" style="border-radius:35px">
                    <div class="modal-body" style="text-align: center;height: auto;">
                        <div class="modal-header" style="padding:0px">
                            <h5 style="color: #366199;font-size: 24px;font-weight: bold; ">Se finalizo la carga del archivo 1 a 1</h5>
                        </div>
                        <div>
                            <p>Recuerde verificar la recepci&oacute;n de las tarjetas por las personas que usted asigne, una vez confirmada la recepci&oacute;n del pl&aacute;stico, su uso es exclusivamente su responsabilidad. </p>
                            <br>
                                    <button type="submit" id="ACEPTAR2" value="1" class="btn btn-default spacing" >CONFIRMAR</button>
                            </form
                            <br><br><br><br>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php } ?>
</div>
<?php if ($ok == 1) { ?>
    <div class="container" style="margin-top: 15%;">
        <div class="modal fade" id="myModalok" role="dialog" style="    margin-top: 15%;">
            <div class="modal-dialog">
                <div class="modal-content" style="border-radius:35px">
                    <button class="btn_cerrar_modal" data-dismiss="modal"></button>
                    <div class="modal-body" style="text-align: center;height: auto;">
                        <div class="modal-header" style="padding:0px">
                            <h5 style="color: #366199;font-size: 24px;font-weight: bold; ">�La informaci&oacute;n se guardo correctamente!</h5>
                        </div>
                        <br>
                        <div>
                            <p  style="font-size:18px;color:#333;font-weight: bold;padding-top: 5px;text-align: justify;padding-left: 4%; padding-right: 4%"><span  class="glyphicon glyphicon-exclamation-sign"></span>
                                Su solicitud "<strong style="color:#0c385e"><?php echo $nomSolicitud ?> </strong>" ha iniciado, por favor tenga en cuenta que las tarjetas no iniciar&aacute;n  su proceso de generaci&oacute;n y env&iacute;o
                                hasta que realice la respectiva factura, para ello puede dirigirse al modulo "Gesti&oacute;n de solicitudes y ordenes", generar la orden y factura correspondiente.
                            </p>
                            <br>
                            <div class="button col-sm-6 col-sm-push-3" >
                                <div class="row linkgenerico" style="/*padding-bottom: 100px; padding-left: 100px;*/">
                                    <!--<a  href="/portal/solicitudTarjetas/solicitudTarjetasMasivo" class="spacing">ACEPTAR</a>-->
                                    <button  onclick="prueba()" class="spacing">ACEPTAR</button>
                                    
                                </div>
                            </div>
                            <br>
                            <br>
                            <br>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php } ?>  
<div class="modal fade" id="myModalokunoauno" role="dialog" style="height: auto !important;margin-top: auto;">
            <div class="modal-dialog">
                <div class="modal-content" style="border-radius:35px">
                    <button class="btn_cerrar_modal" data-dismiss="modal"></button>
                    <div class="modal-body" style="text-align: center;height: auto;">
                        <div class="modal-header" style="padding:0px">
                            <h5 style="color: #366199;font-size: 24px;font-weight: bold; ">Env&iacute;o personalizado de tarjetas</h5>
                        </div>
                        <br>
                        <div>
                            <p  style="font-size:18px;color:#333;font-weight: bold;padding-top: 5px;text-align: justify;padding-left: 4%; padding-right: 4%">
                                Si desea enviar una o varias tarjetas de las que acaba de solicitar, a una direcci&oacute;n distinta a la de los custodios que tiene actualmente asignados, puede hacerlo siguiendo estos pasos.
                            </p>
                            <br>
                            <p  style="font-size:18px;color:#333;font-weight: bold;padding-top: 5px;text-align: justify;padding-left: 4%; padding-right: 4%">
                                1.Por favor descargue y diligencie la &OpenCurlyDoubleQuote; Plantilla Uno a Uno&CloseCurlyDoubleQuote;
                            </p>
                            <br>
                            <p  style="font-size:18px;color:#333;font-weight: bold;padding-top: 5px;text-align: justify;padding-left: 4%; padding-right: 4%">
                                2.En esta plantilla deber&aacute; diligenciar nuevamente los datos del producto solicitado,  los del tarjetahabiente y la nueva direcci&oacute;n de entrega.
                            </p>
                            <br>
                            <p  style="font-size:18px;color:#333;font-weight: bold;padding-top: 5px;text-align: justify;padding-left: 4%; padding-right: 4%">
                                3.En caso de ser una tarjeta corporativa, por favor diligencie adicional a los datos anteriores, los campos de Identificador, tel&eacute;fono, nombres y apellidos del tarjetahabiente.
                            </p>
                            <br>
                            <p  style="font-size:18px;color:#333;font-weight: bold;padding-top: 5px;text-align: justify;padding-left: 4%; padding-right: 4%">
                                4.Cada envio personalizado a una direcci&oacute;n distinta, tendr&aacute; un costo de $13.000
                            </p>
                            <br>
                            <p  style="font-size:18px;color:#333;font-weight: bold;padding-top: 5px;text-align: justify;padding-left: 4%; padding-right: 4%">
                                <a href="/static/img/portal/solicitudTar/PasoAPasoUnoAUno.gif" target=blank"">Si tiene dudas clic aqu&iacute;  </a> 
                            </p>
                            <br>
                            <div class="button col-sm-6 col-sm-push-3" >
                                <div class="row linkgenerico" style="/*padding-bottom: 100px; padding-left: 100px;*/">
                                    <a  href="/portal/solicitudTarjetas/solicitudTarjetasMasivo" class="spacing">ACEPTAR</a>
                                </div>
                            </div>
                            <br>
                            <br>
                            <br>
                        </div>
                    </div>
                </div>
            </div>
        </div>
<!-- Modal confirmacion solicitud masiva-->
<div class="modal fade" id="ModalConfSolMasi" role="dialog" style="margin-top: 5%;"  data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content" style="border-radius:35px">

            <div class="modal-body" style="text-align: center;height: auto;">
                <div class="modal-header" style="padding:0px">
                    <h5 style="color: #366199;font-size: 24px;font-weight: bold; ">Confirmar solicitud</h5>
                </div>
                <br>
                <p  style="font-size:17px;color:#333;padding-top: 5px;text-align: center;"><span  class="glyphicon glyphicon-exclamation-sign"></span>
                    Desea continuar con la operaci&oacute;n.
                </p>
                <div style=" margin-bottom: 4em">
                    <div class="button col-sm-6" >
                        <button type="button" name="ACEPTAR" value="1" class="btn btn-default spacing" id="LbutonDacptar" onclick="
                                $('#solicitudMas').submit();" >SI</button>
                        <!-- <button type="button" name="ACEPTAR" value="1" class="btn btn-default spacing"   onclick="AceptarsolicitudMas1()" >SI</button>-->

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

<div class="modal fade" id="ModalConfSolMasiuau" role="dialog" style="margin-top: 5%;"  data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content" style="border-radius:35px">

            <div class="modal-body" style="text-align: center;height: auto;">
                <div class="modal-header" style="padding:0px">
                    <h5 style="color: #366199;font-size: 24px;font-weight: bold; ">Confirmar solicitud</h5>
                </div>
                <br>
                <p  style="font-size:17px;color:#333;padding-top: 5px;text-align: center;"><span  class="glyphicon glyphicon-exclamation-sign"></span>
                    Desea continuar con la operaci&oacute;n.
                </p>
                <div style=" margin-bottom: 4em">
                    <div class="button col-sm-6" >
                        <button type="button" name="ACEPTAR" value="1"  id="LbutonDacptar2" class="btn btn-default spacing"   onclick="
                                $('#solicitudMasuau').submit();" >SI</button>
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

                                $('#myModalFinalizar').modal({backdrop: 'static', keyboard: false});
                                $('#myModalFinalizar').modal('show');

                            } else if (x == "5") {

                                $('#myModal1').modal('show');
                            } else if (x == "2"){

                                $('#myModalFinalizar2').modal('show');
                            }
                            var statSend = false;
                            /*function  AceptarsolicitudMas1(){
                                $('#loader').modal('show');
                                $("#solicitudMasiva").submit();
                                let status=$("status").val(1);
                                 if(status== '1'){
                                  $("#LbutonDacptar").
                                 }

                                //alert(1);
                            }*/
                         
                            $("#solicitudMasiva").submit(function () {
                                $('#loader').modal('show');
                               $('#LbutonDacptar').attr("disabled", true);

                            });


                            $("#solicitudMas").submit(function () {
                                $('#loader').modal('show');
                                $('#LbutonDacptar2').attr("disabled", true);
                            });
                            var x = "<?= $ok ?>";
                            if (x == "1") {
                                $('#myModalok').modal('show');
                            }
                            $("#formNombreOrden").submit(function () {
                                $('#loader').modal('show');
                            });
                            $("#ACEPTAR2").click(function () {
                                $('#myModalFinalizar2').modal('hide');
                            });
                            function prueba (){
                                $('#myModalok').modal('hide');
                                $('#myModalokunoauno').modal('show');
                            }
</script>