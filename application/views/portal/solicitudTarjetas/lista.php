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
    .msgalert{
        border-radius: 8px;
        border: 1px solid #003560;
        margin-bottom: 20px;
    }
    
</style>
<div class="loader" id="loader" hidden=""></div>
<div style=" margin-bottom: 200px; margin-top: -50px;">
    <div class="container">
        <hr style="border-top: 1px solid #eee0;">
        <h2 class="titulo-iz">Solicitud de Tarjetas</h2>
        <div class='msgalert'>
            <h3 style=" color: #00365E ; padding-left: 23px">La solicitud no se ha completado</h3>
            <p style="font-size: 15px; padding-left: 17px; color:#606060">Debe completar la solicitud dando clic en el botón <strong>FINALIZAR SOLICITUD</strong> para pedir la(s) tarjeta(s) o para cancelar posteriormente el pedido. </p>
        </div>

        <ul class="nav nav-tabs">
            <li class="active"><a data-toggle="tab" href="#solicitudUno">Solicitud Uno a Uno</a></li>
            <li><a data-toggle="tab" href="#solicitudMasiva">Solicitud Masiva</a></li>
        </ul>

        <div class="tab-content">
            <div id="solicitudUno" class="tab-pane fade in active">
                <h3 class="subtitulo-iz">Tarjeta - habiente</h3>
                <div class="container">
                    <div class="grid" style="margin: 2%;">
                        <table class="table table-hover daos_datagrid">
                            <thead>
                                <tr>
                                    <th> Nombre </th>
                                    <th> Documento </th>
                                    <th> Producto </th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $identificador = array();
                                foreach ($pedidos as $value) {
                                    if (!in_array($value['DOCUMENTO'], $identificador)) {
                                        ?>
                                        <tr class="gradeC">
                                            <td><?= $value['TARJETA_HABIENTE'] ?></td>
                                            <td><?= $value['DOCUMENTO'] ?></td>
                                            <td>
                                                <table>
                                                    <?php
                                                    foreach ($pedidos as $value2) {
                                                        if ($value['DOCUMENTO'] == $value2['DOCUMENTO']) {
                                                            ?>

                                                            <tr>
                                                                <td>
                                                                    <?= $value2['PRODUCTOS'] ?>
                                                                </td>
                                                            </tr>
                                                            <?php
                                                        }
                                                    }
                                                    ?>
                                                </table>
                                            </td>
                                        </tr>
                                        <?php
                                        array_push($identificador, $value['DOCUMENTO']);
                                    }
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>  
                    <div class=" col-md-10 col-md-push-3">

                        <div class="linkgenerico spacing" style="float: left">
                            <a   href="/portal/solicitudTarjetas/solicitud"> SOLICITAR OTRA  </a>
                        </div> 
                        <div class="button " style="float: left; margin-left: 15px">
                            <button  class="spacing"type="button" data-toggle="modal" data-target="#myModal" >
                                <?php if ($modelopago == 'PREPAGO') { ?>
                                    ORDEN DE &nbsp;PEDIO
                                <?php } else { ?>
                                    FINALIZAR &nbsp;  SOLICITUD
                                <?php } ?>
                            </button>
                        </div>
                        <!--                          <div class="linkgenerico spacing" style="float: left;margin-left: 15px">
                                                    <a  href="/portal/abonos/unoAUno"> SOLICITAR ABONO </a>
                                                </div> -->
                    </div>
                </div> 
            </div>
            <div id="solicitudMasiva" class="tab-pane fade">
                <h3>Plantilla</h3>
                <div class="col-sm-4">
                    <table id="masivoIconos" cellspacing="10" cellpadding="10" border="0">
                        <tbody>
                            <tr>
                                <td>
                                    <div class="excel"></div> 
                                </td>
                                <td> </td> 
                                <td>
                                    <div class="subir"></div> 
                                </td>
                            </tr>
                            <tr>
                                <td style="">
                                    <a href="/portal/solicitudTarjetas/descargarPlantilla" ><span class="hiddenFileDownload"></span></a>
                                </td>
                                <td> </td>
                                <td>
                                    <div>
                                        <form  method="post" action="solicitudMasiva" enctype="multipart/form-data">
                                            <span class="hiddenFileInput">
                                                <input type="file" name="file" />
                                            </span>
                                            <br>
                                            <div class="button" >
                                                <button type="submit" onclick="cargando()">E N V I A R</button>
                                            </div>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>

                </div>
            </div>
        </div>
    </div>
</div>



<!-- Modal -->
<div class="modal fade" id="myModal" role="dialog" style="    margin-top: 15%;" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content" style="border-radius:35px">
            <!--  <div class="modal-header">
                  <button type="button" class="close" data-dismiss="modal">&times;</button>
              </div>-->
            <div class="modal-body" style="text-align: center;height: 230px;">
                <div>

                    <form method="post" action="/portal/solicitudTarjetas/finalizarPrePedido" id="formularioOrdenes" >
                        <input type="hidden" name="pedido" value="<?= $pedidoActual ?>"> 
                        <p  style="font-size:18px;color:#0c385e;font-weight: bold">Nombrar solicitud</p>
                        <br>
                        <p style="font-size:18px;color:#888686;">Por favor asigne un nombre a la solicitud:</p>

                        <input type="text" class="textPat"  name="nombreorden" id='nomSol' style="width: 60%" placeholder="Ingrese un nombre para la orden" required>
                        <br><br>
                        <div class="button col-sm-6" >
                            <button type="submit" name="ACEPTAR" value="1" class="btn btn-default" >A C E P T A R</button>
                        </div>
                        <div class="button col-sm-6" >
                            <button type="button" class="btn btn-default" data-dismiss="modal">CANCELAR</button>
                        </div>
                    </form>


                </div>
            </div>
            <!--   <div class="modal-footer">
                  <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
              </div>-->
        </div>
    </div>
</div>

<?php if ($ok == 1) { ?>
    <div class="container" style="margin-top: 15%;">
        <div class="modal fade" id="myModalok" role="dialog" style="    margin-top: 15%;">
            <div class="modal-dialog">
                <div class="modal-content" style="border-radius:35px">
                    <div class="modal-body" style="text-align: center;height: auto;">
                        <div class="modal-header" style="padding:0px">
                            <h5 style="color: #366199;font-size: 24px;font-weight: bold; ">¡La información se guardo correctamente!</h5>
                        </div>
                        <br>
                        <div>
                            <p  style="font-size:18px;color:#333;font-weight: bold;padding-top: 5px;text-align: justify;"><span  class="glyphicon glyphicon-exclamation-sign"></span>
                                Su solicitud "<strong style="color:#0c385e"><?php echo $nomSolicitud ?> </strong>" ha iniciado, por favor tenga en cuenta que las tarjetas no iniciarán  su proceso de generación y envío
                                hasta que realice la respectiva factura, para ello puede dirigirse al modulo "Gestión de solicitudes y ordenes", generar la orden y factura correspondiente.
                            </p>
                            <br>
                            <div class="button col-sm-6 col-sm-push-3" >
                                <div class="row linkgenerico" style="/*padding-bottom: 100px; padding-left: 100px;*/">
                                    <a  href="/portal/solicitudTarjetas/solicitud" class="spacing">ACEPTAR</a>
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

<script type="text/javascript">
    $(".alrt").fadeTo(30000, 100).slideUp(2000, function () {
        $(".alrt").slideUp(300);
    });
    $('.add').click(function () {
        $('.block:last').before('<div class="form-group"><div class="block col-sm-6 "> <select name="productos"><option value=""></option><?php foreach ($productos as $value) { ?><option><?= $value['NOMBRE_PRODUCTO'] ?></option><?php } ?></select></div> <a class="remove btn btn-danger"> X </a></div>');
    });
    $('.optionBox').on('click', '.remove', function () {
        $(this).parent().remove();
    });
</script>
<script type="text/javascript">
    $(document).ready(function () {
        $('#inputDepartamento').change(function () {

            $.ajax({
                url: "/portal/ajax/ciudad/" + $('#inputDepartamento').val()
            })
                    .done(function (msg) {
                        $('#inputCiudad').html(msg)
                    });
        });
    });

    function openModal() {
        $('#myModal').modal('show');
    }
    function closeModal() {
        $('#myModal').modal('hiden');
    }
    function valor() {
        alert($('#pedido').val());
    }
    $("#formularioOrdenes").submit(function () {
        $('#loader').modal('show');
    });
    var x = "<?= $ok ?>";
    if (x == "1") {
        $('#myModalok').modal('show');
    }

    var nomsols = '<?= isset($nomSolicitud) ? $nomSolicitud : 0 ?>';
    window.addEventListener('beforeunload', function (e) {
        var somSol = $('#nomSol').val();
        if (somSol == '' && nomsols == 0) {
            // Cancel the event
            e.preventDefault(); // If you prevent default behavior in Mozilla Firefox prompt will always be shown
            // Chrome requires returnValue to be set
            e.returnValue = '';
        }


//    
    });



</script>