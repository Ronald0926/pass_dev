<style>
    .table>tfoot>tr>th{
        text-align: center;
        border-top: 1px solid #a7a7a7;
        border-bottom: 0px solid;
    }
    .alerthid{
        display: none;
    }
</style>
<div class="loader" id="loader" hidden=""></div>
<div class="col-md-2"></div>
<div style=" margin-bottom: 200px; margin-top: -50px;" class="col-md-8">
    <div class="container-fluid">
        <hr style="border-top: 1px solid #eee0;">
        <h2 class="titulo-iz">Solicitud de Abonos</h2>
        <div class="alerthid">
            <div class="alert alert-info alerthid">
                <strong>No se ha agregado ningÃºn usuario</strong>
            </div>
        </div>
        <ul class="nav nav-tabs">
            <li class="active"><a data-toggle="tab" href="#solicitudUno">Solicitud Individual</a></li>
            <li><a href="/portal/abonos/abonoMasivo">Solicitud Masiva</a></li>
        </ul>
        <?php if ($error == 1) { ?>
            <div class="">Solicitud exitosa </div>

        <?php } ?>
        <div class="tab-content" style="margin: 2%;">
            <div id="solicitudUno" class="tab-pane fade in active">
                <h3>Solicitud individual de abonos a tarjetas</h3>
                <p>Aqu&iacute;, podr&aacute;s solicitar la realizaci&oacute;n de un abono, seleccionado una por una las tarjetas de los colaboradores,<br> 
                    el valor que deseas abonar a cada una y la fecha de la dispersi&oacute;n del dinero.</p>
                <br>
                <div class="">
                    <form action="/portal/abonos/insertUnoAUno" method="POST" id="forminsert">

                        <input value="<?= $pkcodSol ?>" name="pksolicitudPrepepdido" hidden>
                        <div class="grid">
                            <table class="table table-hover" id="tableabonounoauno">
                                <thead>
                                    <tr>
                                        <th> Nombre </th>
                                        <th> T.D. </th>
                                        <th> No.Doc </th>
                                        <th> Producto </th>
                                        <th>Identificador</th>
                                        <th> Abono </th>
                                        <th> Fecha de DispersiÃ³n </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $documento;
                                    $llaves_seleccion = $this->session->userdata("llavesTemp2");
                                    foreach ($llaves_seleccion as $value2) {
                                        foreach ($tarjetaHabiente as $value) {
                                            $nameinput++;
                                            if ($value2 == $value['CUENTA']) {
                                                if ($documento != $value['DOC']) {
                                                    $documento = $value['DOC'];
                                                    ?>

                                                    <tr class="gradeC">
                                                        <td><?= $value['NOMTAR'] ?></td>
                                                        <td><?= $value['ABR'] ?></td>
                                                        <td><?= $value['DOC'] ?></td>
                                                    <?php } else { ?>
                                                        <td></td>
                                                        <td></td>
                                                        <td></td>
                                                    <?php } ?>

                                                    <td class="tdNomPro"><?= $value['NOMPRO'] ?></td>
                                                    <td class="tdNomPro"><?= $value['IDENTIFICADOR'] ?></td>
                                                    <td><input required type="text" name="monto/<?= $value['CUENTA'] ?>/<?= $value['ENTTAH'] ?>/<?=$nameinput?>" data-type="currency" placeholder="Digite la cantidad" class="tCantidad" onfocusout="sumar(this.value);" /></td>
                                                    <td><input required name="fecha/<?= $value['CUENTA'] ?>/<?= $value['ENTTAH'] ?>/<?=$nameinput?>" type="date" min="<?= date("Y-m-d") ?>" class="tFecha"  /></td>
                                                </tr>
                                                <?php
                                            }
                                        }
                                    }
                                    ?> 
                                </tbody>
                            </table>

                        </div>
                        <div class="col-sm-5 col-lg-offset-4">
                            <!--<div class="button">
                                <button class="linkbutton spacing" formaction="/portal/abonos/listaUnoAUno">AGREGAR OTRO USUARIO</button>
                            </div>-->
                            <br>
                            <div class="button">
                                <button class="linkbutton spacing" formaction="/portal/abonos/insertUnoAUno" type="submit" id="soliAbono">SOLICITAR ABONO</button>
                            </div>
                        </div>
                    </form>
                    <form  action="/portal/abonos/listaUnoAUno<?php echo empty(isset($_GET['sol'])) ? '' : '/?sol=' . $_GET['sol'] ?>" method="POST">
                        <div class="button col-sm-5 col-lg-offset-4">
                            <button class="linkbutton spacing"  type="submit">AGREGAR OTRO USUARIO</button>
                        </div>
                    </form>
                </div>


            </div>

        </div>

        <!-- Modal -->
        <div class="modal fade" id="myModal" role="dialog" style="margin-top: 15%;">
            <div class="modal-dialog">
                <!-- Modal content-->
                <div class="modal-content" style="border-radius:35px">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <?php if ($error == 1) { ?>
                            <h4 class="modal-title">¡Abono aplicado exitosamente!</h4>
                        <?php } else { ?>
                            <h4 class="modal-title">Error en la creaci&oacute;n </h4>
                        <?php } ?>
                    </div>
                    <div class="modal-body" style="text-align: center;height: 200px;">
                        <p>Por favor asignar un nombre a la solicitud: </p>

                        <form method="POST">
                            <input required type="text" name="nomord" />
                            <input type="hidden" name="codord" value="<?= $codord ?>" />
                            <div class="button col-sm-6">
                                <button name="solorden" type="submit" value="solorden" formaction="/portal/abonos/nombraOrden" class="btn btn-default">ORDEN DE PEDIDO</button>
                            </div>
                        </form>
                        <form method="POST">
                            <div class="button col-sm-6">
                                <button name="solabono" value="solabono" type="submit" class="btn btn-default">SOLICITAR ABONO</button>
                            </div>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="col-md-2 padding_div" style="margin-top: 7%;">
    <div id="total">
        <div class="login-checkbox" onclick="">
            <span id="total">
                <div class="">
                    <span class="login-checkbox-check noncheck" id="totales">
                    </span>
                </div>
            </span>
        </div>
        <h5 class="aplicar" style="padding-left: 10%;">Aplicar cantidad a todos</h5>
    </div>
</div>
<div class="col-md-2">
    <div id="totalF">
        <div class="login-checkbox" onclick="">
            <span id="totalF">
                <div class="">
                    <span class="login-checkbox-check noncheck" id="totalesF">
                    </span>
                </div>
            </span>
        </div>
        <h5 class="aplicar" style="padding-left: 10%;">Aplicar fecha a todos</h5>
    </div>
</div>


<!-- Modal confirmacion abono uno a uno-->
<div class="modal fade" id="ModalConSoli" role="dialog" style="margin-top: 5%;"  data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content" style="border-radius:35px">
            <button class="btn_cerrar_modal" data-dismiss="modal"></button>
            <div class="modal-body" style="text-align: center;height: auto;">
                <div class="modal-header" style="padding:0px">
                    <h5 style="color: #366199;font-size: 24px;font-weight: bold; ">¿Desea confirmar su solicitud de abono?</h5>
                </div>
                <table class="table table-hover dataSel" style="margin-top: 20px;width: 70%;margin-right: auto; margin-left: auto;">
                    <thead>
                        <tr style="font-weight: bold">
                            <td>Producto</td>
                            <td>Abono</td>
                            <td>Total</td>
                        </tr>
                    </thead>
                    <tbody class="tblbody">
                    </tbody>
                    <tfoot>
                        <tr>
                            <th>Total</th>
                            <th class="thA"></th>
                            <th class="thT"></th>
                        </tr>
                    </tfoot>
                </table>
                <div style=" margin-bottom: 3em">
                    <div class="button col-sm-6 col-sm-push-3">
                        <button type="button" name="ACEPTAR" value="1" class="btn btn-default spacing"  onclick="
                                $('#forminsert').submit();" >CONFIRMAR</button>
                    </div>
                    <!--                    <div class="button col-sm-6" >
                                            <button type="button" name="CANCELAR" class="btn btn-default" data-dismiss="modal">C A N C E L A R</button>
                                        </div>-->
                </div>
                <br>
                <br>
                <br>
            </div>
        </div>
    </div>
</div>


<!-- Modal -->
<div class="modal fade" id="myModalFinalizar" role="dialog" style="    margin-top: 15%;" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content" style="border-radius:35px">
            <button class="btn_cerrar_modal" data-dismiss="modal"></button>
            <div class="modal-body" style="text-align: center;height: auto;">
                <div class="modal-header" style="padding:0px">
                    <h5 style="color: #366199;font-size: 24px;font-weight: bold; ">¿Desea confirmar su solicitud de abono?</h5>
                </div>
                <div>
                    <br>
                    <form method="post" action="/portal/abonos/finalizarPrePedidoAbo" id="formNombreOrden" >
                        <input type="hidden" name="pedido" value="<?= $pkcodsolicitud ?>"> 
                        <p style="font-size:18px;color:#888686;">Por favor asigne un nombre a la orden:</p>

                        <input type="text" class="textPat"  name="nombreorden" style="width: 60%" placeholder="Ingrese un nombre para la orden" required>
                        <br><br>
                        <div class="button col-sm-6 col-sm-push-3" >
                            <button type="submit" name="ACEPTAR" value="1" class="btn btn-default spacing" >CONFIRMAR</button>
                        </div>
                    </form>
                    <br><br>
                </div>
            </div>
        </div>
    </div>
</div>
<?php if ($ok == 1) { ?>
    <div class="container" style="margin-top: 15%;">
        <div class="modal fade" id="myModalok" role="dialog" style="    margin-top: 15%;">
            <div class="modal-dialog">
                <div class="modal-content" style="border-radius:35px">
                    <button class="btn_cerrar_modal" data-dismiss="modal"></button>
                    <div class="modal-body" style="text-align: center;height: auto;">
                        <div class="modal-header" style="padding:0px">
                            <h5 style="color: #366199;font-size: 24px;font-weight: bold; ">¡La informaci&oacute;n se guardo correctamente!</h5>
                        </div>
                        <br>
                        <div>
                            <p  style="font-size:18px;color:#333;font-weight: bold;padding-top: 5px;text-align: justify;"><span  class="glyphicon glyphicon-exclamation-sign"></span>
                                Su solicitud "<strong style="color:#0c385e"><?php echo $nomSolicitud ?> </strong>" ha iniciado, por favor tenga en cuenta que las tarjetas no iniciar&aacute;n  su proceso de generaci&oacute;n y env&iacute;o
                                hasta que realice la respectiva factura, para ello puede dirigirse al modulo "Gesti&oacute;n de solicitudes y ordenes", generar la orden y factura correspondiente.
                            </p>
                            <br>
                            <div class="button col-sm-6 col-sm-push-3" >
                                <div class="row linkgenerico" style="/*padding-bottom: 100px; padding-left: 100px;*/">
                                    <a  href="/portal/abonos/unoAUno" class="spacing">ACEPTAR</a>
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
    var x = "<?= $ok ?>";
    if (x == "1") {
        $('#myModalok').modal('show');
    }
    $("#forminsert").submit(function () {
        $('#loader').modal('show');
    });
    $("#formNombreOrden").submit(function () {
        $('#loader').modal('show');
    });
    var SolOk = "<?= $solAbonoOK ?>";
    if (SolOk == "1") {
        $('#myModalFinalizar').modal('show');
    }
    var table;
    var tM = 0, valTMar = 0;
    var tV = 0, valTVest = 0;
    var tB = 0, valTBien = 0;
    var tP = 0, valTPrem = 0;
    var tPP = 0, valTPremPlus = 0;
    var tMT = 0, valTMedT = 0;
    var tC = 0, valTCom = 0;
    var tZa = 0, valTZa = 0;
    var tZaP = 0, valTZaPl = 0;
    var tGR = 0;

    $(document).ready(function () {
        table = $('#tableabonounoauno').DataTable({
            "bJQueryUI": true,
            "bSort": false,
            "bPaginate": true,
            "sPaginationType": "full_numbers",
            "oLanguage": {
                "sProcessing": "Procesando...",
                "sLengthMenu": "Mostrar _MENU_ registros",
                "sZeroRecords": "No se encontraron resultados",
                "sEmptyTable": "NingÃºn dato disponible en esta tabla",
                "sInfo": "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
                "sInfoEmpty": "Mostrando registros del 0 al 0 de un total de 0 registros",
                "sInfoFiltered": "(filtrado de un total de _MAX_ registros)",
                "sInfoPostFix": "",
                "sSearch": "",
                "sUrl": "",
                "sInfoThousands": ",",
                "sLoadingRecords": "Cargando...",
                "oPaginate": {
                    "sFirst": "Primero",
                    "sLast": "Ãšltimo",
                    "sNext": "Siguiente",
                    "sPrevious": "Anterior"
                },
                "oAria": {
                    "sSortAscending": ": Activar para ordenar la columna de manera ascendente",
                    "sSortDescending": ": Activar para ordenar la columna de manera descendente"
                }
            }

        });

        $('body').on('click', '#total span', function () {
            var sumatoria = 0;
            var rows = table.rows({'search': 'applied'}).nodes();
            var rc = $(".tCantidad", rows[0]).val();
            for (i = 0; i < rows.length; i++) {
                $(".tCantidad", rows[i]).val(rc);
                var porciones = $(".tCantidad", rows[i]).val().split('.');
                valor = CifrasEnLetras.dejarSoloCaracteresDeseados(porciones[0], "0123456789");
                if (!isNaN(parseInt(valor))) {
                    sumatoria += parseInt(valor);
                }
            }
            $(".thT").html('$ ' + number_format(sumatoria, 2, ',', '.'));
        });
        $('body').on('click', '#totalF span', function () {
            var rows = table.rows({'search': 'applied'}).nodes();
            var rf = $(".tFecha", rows[0]).val();
            for (i = 0; i < rows.length; i++) {
                $(".tFecha", rows[i]).val(rf);
            }
        });

        $('#forminsert').on('submit', function (e) {

            var form = this;
            // Encode a set of form elements from all pages as an array of names and values
            var params = table.$('input,select,textarea').serializeArray();

            // Iterate over all form elements
            $.each(params, function () {
                // If element doesn't exist in DOM
                if (!$.contains(document, form[this.name])) {
                    // Create a hidden element
                    $(form).append(
                            $('<input>')
                            .attr('type', 'hidden')
                            .attr('name', this.name)
                            .val(this.value)
                            );
                }
            });
        });

    });
    //comentado 17 de 12 de diciembre 2019 Ronald
    //    var error = <?= $error ?>;
//    if (error != null) {
//        $('#myModal').modal('show');
//        $('#loader').hide();
//    }

    var boton = document.getElementById("soliAbono");
    boton.onclick = function (e) {
        if (!table.data().count()) {
            e.preventDefault();
            $('.alerthid').show();
            $('#ModalConSoli').modal('hide');
        } else {
            var validado = $("#forminsert").valid();
            if (validado) {
                sumar(1);
                e.preventDefault();
                $('#ModalConSoli').modal('show');
            } else {
                e.preventDefault();
                $('#loader').modal('hide');
            }
        }
    };
    $("#forminsert").submit(function () {
        $('#loader').modal('show');
    });


    function sumar(valor) {
        tM = 0, valTMar = 0;        //MARKET
        tV = 0, valTVest = 0;       //VESTUARIO
        tB = 0, valTBien = 0;       //BIENESTAR
        tP = 0, valTPrem = 0;       //PREMIO
        tPP = 0, valTPremPlus = 0;  //PREMIO PLUS
        tMT = 0, valTMedT = 0;      //MEDIOS DE TRANSPORTE
        tC = 0, valTCom = 0;        //COMBUSTIBLE             
        tZa = 0, valTZa = 0;        //ZAFIRO
        tZaP = 0, valTZaPl = 0;     //ZAFIRO PLUS
        //NUEVAS
        tGR = 0, valTGR = 0;             //GASTOS DE REPRESENTACION
        tCM = 0, valTCM = 0;            //CAJA MENOR
        tCan = 0, valTCan = 0;           //CANASTA
        tBs = 0, valTBs = 0;            //BIENESTAR SALUD
        tBus = 0, valTBus = 0;           //BUSINESS CAR
        tGC = 0, valTGC = 0;             //GASTOS CORPORATIVOS
        tGL = 0, valTGL = 0;             //GASTOS DE LEGALIZACION
        tGV = 0, valTGV = 0;              //GASTOS DE VIAJE

        var currentTODO = $('.dataSel');
        var container = currentTODO.find('.tblbody');
        var sumatoria = 0;
        var rows = table.rows({'search': 'applied'}).nodes();
        for (i = 0; i < rows.length; i++) {
            var porciones = $(".tCantidad", rows[i]).val().split('.');
            valor = CifrasEnLetras.dejarSoloCaracteresDeseados(porciones[0], "0123456789");
            if (!isNaN(parseInt(valor))) {
                valorC = parseInt(valor);
                sumatoria += parseInt(valor);
            }
            var nomPro = $(".tdNomPro", rows[i]).text();
            nomPro = nomPro.toUpperCase();
            if (nomPro === 'MARKET') {
                tM++;
                valTMar += valorC;
            } else if (nomPro === 'BIENESTAR') {
                tB++;
                valTBien += valorC;
            } else if (nomPro === 'PREMIO PLUS') {
                tPP++;
                valTPremPlus += valorC;
            } else if (nomPro === 'VESTUARIO') {
                tV++;
                valTVest += valorC;
            } else if (nomPro === 'ZARIO' || nomPro === 'ZAFIRO') {
                tZa++;
                valTZa += valorC;
            } else if (nomPro === 'ZAFIRO PLUS') {
                tZaP++;
                valTZaPl += valorC;
            } else if (nomPro === 'PREMIO') {
                tP++;
                valTPrem += valorC;
            } else if (nomPro === 'COMBUSTIBLE') {
                tC++;
                valTCom += valorC;
            } else if (nomPro === 'GASTOS DE RERESENTACION') {
                tGR++;
                valTGR += valorC;
            } else if (nomPro === 'MEDIOS DE TRANSPORTE') {
                tMT++;
                valTMedT += valorC;
            } else if (nomPro === 'CAJA MENOR') {
                tCM++;
                valTCM += valorC;
            } else if (nomPro === 'CANASTA') {
                tCan++;
                valTCan += valorC;
            } else if (nomPro === 'BIENESTAR SALUD') {
                tBs++;
                valTBs += valorC;
            } else if (nomPro === 'BUSINESS CAR') {
                tBus++;
                valTBus += valorC;
            } else if (nomPro === 'GASTOS CORPORATIVOS') {
                tGC++;
                valTGC += valorC;
            } else if (nomPro === 'GASTOS DE VIAJE') {
                tGV++;
                valTGV += valorC;
            } else if (nomPro === 'GASTOS DE LEGALIZACION') {
                tGL++;
                valTGL += valorC;
            }
        }
        var totalPro = tM + tB + tPP + tV + tZa + tZaP + tP + tC + tGR + tMT + tCM + tCan + tBs + tBus + tGC + tGV + tGL;
        $('.thA').html(totalPro);
        var exiTM = currentTODO.find('.tM');
        if (tM > 0) {
            if (exiTM.length === 0) {
                container.append('<tr class="tM"><td>Market</td> <td id="vCM">' + tM + '</td><td id="vtM">' + '$ ' + number_format(valTMar, 2, ',', '.') + '</td></tr>');
            } else {
                $('#vCM').html(tM);
                $('#vtM').html('$ ' + number_format(valTMar, 2, ',', '.'));
            }
        } else if (tM === 0 && exiTM.length > 0) {
            $('.tM').remove();
        }
        var exiTB = currentTODO.find('.tB');
        if (tB > 0) {
            if (exiTB.length === 0) {
                container.append('<tr class="tB"><td>Bienestar</td> <td id="vCB">' + tB + '</td><td id="vtB">' + '$ ' + number_format(valTBien, 2, ',', '.') + '</td></tr>');
            } else {
                $('#vCB').html(tB);
                $('#vtB').html('$ ' + number_format(valTBien, 2, ',', '.'));
            }
        } else if (tB === 0 && exiTB.length > 0) {
            $('.tB').remove();
        }
        var exiTPP = currentTODO.find('.tPP');
        if (tPP > 0) {
            if (exiTPP.length === 0) {
                container.append('<tr class="tPP"><td>Premio plus</td> <td id="vCPP">' + tPP + '</td><td id="vtPP">' + '$ ' + number_format(valTPremPlus, 2, ',', '.') + '</td></tr>');
            } else {
                $('#vCPP').html(tPP);
                $('#vtPP').html('$ ' + number_format(valTPremPlus, 2, ',', '.'));
            }
        } else if (tPP === 0 && exiTPP.length > 0) {
            $('.tPP').remove();
        }
        var exiTV = currentTODO.find('.tV');
        if (tV > 0) {
            if (exiTV.length === 0) {
                container.append('<tr class="tV"><td>Vestuario</td> <td id="vCV">' + tV + '</td><td id="vtV">' + '$ ' + number_format(valTVest, 2, ',', '.') + '</td></tr>');
            } else {
                $('#vCV').html(tV);
                $('#vtV').html('$ ' + number_format(valTVest, 2, ',', '.'));
            }
        } else if (tV === 0 && exiTV.length > 0) {
            $('.tV').remove();
        }
        var exiTZa = currentTODO.find('.tZa');
        if (tZa > 0) {
            if (exiTZa.length === 0) {
                container.append('<tr class="tZa"><td>Zafiro</td> <td id="vCZa">' + tZa + '</td><td id="vtZa">' + '$ ' + number_format(valTZa, 2, ',', '.') + '</td></tr>');
            } else {
                $('#vCZa').html(tZa);
                $('#vtZa').html('$ ' + number_format(valTZa, 2, ',', '.'));
            }
        } else if (tZa === 0 && exiTZa.length > 0) {
            $('.tZa').remove();
        }
        var exiTZaPl = currentTODO.find('.tZaP');
        if (tZaP > 0) {
            if (exiTZaPl.length === 0) {
                container.append('<tr class="tZaP"><td>Zafiro plus</td> <td id="vCZaP">' + tZaP + '</td><td id="vtZaP">' + '$ ' + number_format(valTZaPl, 2, ',', '.') + '</td></tr>');
            } else {
                $('#vCZaP').html(tZaP);
                $('#vtZaP').html('$ ' + number_format(valTZaPl, 2, ',', '.'));
            }
        } else if (tZaP === 0 && exiTZaPl.length > 0) {
            $('.tZaP').remove();
        }
        var exiTP = currentTODO.find('.tP');
        if (tP > 0) {
            if (exiTP.length === 0) {
                container.append('<tr class="tP"><td>Premio</td> <td id="vCP">' + tP + '</td><td id="vtP">' + '$ ' + number_format(valTPrem, 2, ',', '.') + '</td></tr>');
            } else {
                $('#vCP').html(tP);
                $('#vtP').html('$ ' + number_format(valTPrem, 2, ',', '.'));
            }
        } else if (tP === 0 && exiTP.length > 0) {
            $('.tP').remove();
        }
        var exiTC = currentTODO.find('.tC');
        if (tC > 0) {
            if (exiTC.length === 0) {
                container.append('<tr class="tC"><td>Combustible</td> <td id="vCC">' + tP + '</td><td id="vtC">' + '$ ' + number_format(valTCom, 2, ',', '.') + '</td></tr>');
            } else {
                $('#vCC').html(tC);
                $('#vtC').html('$ ' + number_format(valTCom, 2, ',', '.'));
            }
        } else if (tC === 0 && exiTC.length > 0) {
            $('.tC').remove();
        }
        var exiTGR = currentTODO.find('.tGR');
        if (tGR > 0) {
            if (exiTGR.length === 0) {
                container.append('<tr class="tGR"><td>Gastos de representaciÃ³n</td> <td id="vCGR">' + tGR + '</td><td id="vtGR">' + '$ ' + number_format(valTGR, 2, ',', '.') + '</td></tr>');
            } else {
                $('#vCGR').html(tGR);
                $('#vtGR').html('$ ' + number_format(valTGR, 2, ',', '.'));
            }
        } else if (tGR === 0 && exiTGR.length > 0) {
            $('.tGR').remove();
        }
        var exiTMT = currentTODO.find('.tMT');
        if (tMT > 0) {
            if (exiTMT.length === 0) {
                container.append('<tr class="tMT"><td>Medios de transporte</td> <td id="vCMT">' + tMT + '</td><td id="vtMT">' + '$ ' + number_format(valTMedT, 2, ',', '.') + '</td></tr>');
            } else {
                $('#vCMT').html(tMT);
                $('#vtMT').html('$ ' + number_format(valTMedT, 2, ',', '.'));
            }
        } else if (tMT === 0 && exiTMT.length > 0) {
            $('.tMT').remove();
        }
        var exiTCM = currentTODO.find('.tCM');
        if (tCM > 0) {
            if (exiTCM.length === 0) {
                container.append('<tr class="tCM"><td>Caja menor</td> <td id="vCCM">' + tCM + '</td><td id="vtCM">' + '$ ' + number_format(valTCM, 2, ',', '.') + '</td></tr>');
            } else {
                $('#vCCM').html(tCM);
                $('#vtCM').html('$ ' + number_format(valTCM, 2, ',', '.'));
            }
        } else if (tCM === 0 && exiTCM.length > 0) {
            $('.tCM').remove();
        }
        var exiTCAN = currentTODO.find('.tCAN');
        if (tCan > 0) {
            if (exiTCAN.length === 0) {
                container.append('<tr class="tCAN"><td>Canasta</td> <td id="vCCAN">' + tCan + '</td><td id="vtCAN">' + '$ ' + number_format(valTCan, 2, ',', '.') + '</td></tr>');
            } else {
                $('#vCCAN').html(tCan);
                $('#vtCAN').html('$ ' + number_format(valTCan, 2, ',', '.'));
            }
        } else if (tCan === 0 && exiTCAN.length > 0) {
            $('.tCAN').remove();
        }
        var exiTBS = currentTODO.find('.tBS');
        if (tBs > 0) {
            if (exiTBS.length === 0) {
                container.append('<tr class="tBS"><td>Bienestar salud</td> <td id="vCBS">' + tBs + '</td><td id="vtBS">' + '$ ' + number_format(valTBs, 2, ',', '.') + '</td></tr>');
            } else {
                $('#vCBS').html(tBs);
                $('#vtBS').html('$ ' + number_format(valTBs, 2, ',', '.'));
            }
        } else if (tBs === 0 && exiTBS.length > 0) {
            $('.tBS').remove();
        }
        var exiTBus = currentTODO.find('.tBus');
        if (tBus > 0) {
            if (exiTBus.length === 0) {
                container.append('<tr class="tBus"><td>Business car</td> <td id="vCBus">' + tBus + '</td><td id="vtBus">' + '$ ' + number_format(valTBus, 2, ',', '.') + '</td></tr>');
            } else {
                $('#vCBus').html(tBus);
                $('#vtBus').html('$ ' + number_format(valTBus, 2, ',', '.'));
            }
        } else if (tBus === 0 && exiTBus.length > 0) {
            $('.tBus').remove();
        }
        var exiTGC = currentTODO.find('.tGC');
        if (tGC > 0) {
            if (exiTGC.length === 0) {
                container.append('<tr class="tGC"><td>Gastos corporativos</td> <td id="vCGC">' + tGC + '</td><td id="vtGC">' + '$ ' + number_format(valTGC, 2, ',', '.') + '</td></tr>');
            } else {
                $('#vCGC').html(tGC);
                $('#vtGC').html('$ ' + number_format(valTGC, 2, ',', '.'));
            }
        } else if (tGC === 0 && exiTGC.length > 0) {
            $('.tGC').remove();
        }
        var exiTGV = currentTODO.find('.tGV');
        if (tGV > 0) {
            if (exiTGV.length === 0) {
                container.append('<tr class="tGV"><td>Gastos de viaje</td> <td id="vCGV">' + tGV + '</td><td id="vtGV">' + '$ ' + number_format(valTGV, 2, ',', '.') + '</td></tr>');
            } else {
                $('#vCGV').html(tGV);
                $('#vtGV').html('$ ' + number_format(valTGV, 2, ',', '.'));
            }
        } else if (tGV === 0 && exiTGV.length > 0) {
            $('.tGV').remove();
        }
        var exiTGL = currentTODO.find('.tGL');
        if (tGL > 0) {
            if (exiTGL.length === 0) {
                container.append('<tr class="tGL"><td>Gastos de legalizaciÃ³n</td> <td id="vCGL">' + tGL + '</td><td id="vtGL">' + '$ ' + number_format(valTGL, 2, ',', '.') + '</td></tr>');
            } else {
                $('#vCGL').html(tGL);
                $('#vtGL').html('$ ' + number_format(valTGL, 2, ',', '.'));
            }
        } else if (tGL === 0 && exiTGL.length > 0) {
            $('.tGL').remove();
        }

        $(".thT").html('$ ' + number_format(sumatoria, 2, ',', '.'));
    }
</script>
<style>
    .aplicar {
        color: #172E54 !important;
        font-size: 20px !important;
        font-weight: bold;
    }
    @media (max-width: 1800px) and (min-width: 1080px){
        .aplicar {
            padding-top:  4px;
            margin-left: 8px;
            font-size: 1.1vw !important;
        }
        .padding_div{
            margin-top: 12% !important;
        }
    }
</style>