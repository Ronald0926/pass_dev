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
    .table>tfoot>tr>th{
        text-align: center;
        border-top: 1px solid #a7a7a7;
        border-bottom: 0px solid;
    }
    .table>tbody>tr>td{
        text-align: center;
    }
    .table>thead>tr>th{
        font-weight: bold;
        font-size: 20px;
        text-align: center;
    }

    #masivoIconos td,th{
        padding: 30px;
    }
</style>
<div class="loader" id="loader" hidden=""></div>
<div style=" margin-bottom: 200px; margin-top: -50px;">
    <div class="container">
        <hr style="border-top: 1px solid #eee0;">
        <h2 class="titulo-iz">Tarjetas</h2>
        <ul class="nav nav-tabs">
            <li class="active" id="solicitudUno"><a href="/portal/solicitudTarjetas/solicitud">Solicitud Individual</a></li>
            <li id="solicitudMasiva "><a  href="/portal/solicitudTarjetas/solicitudTarjetasMasivo">Solicitud Masiva</a></li>
            <li id="solicitudMasiva "><a  href="/portal/solicitudTarjetas/solicitudTarjetasMasivouau">Envios Personalizados</a></li>
        </ul>

        <div class="tab-content">
            <div id="solicitudUno" class="tab-pane fade in active">
                <h3>Solicitud Individual de tarjetas</h3>
                <p>1. Aqu&iacute;, pod&aacute;s solicitar tus tarjetas una por una, diligenciando los datos de la tarjeta y del colaborador a quien deseas proporcionarle este producto.</p>
                <p>2. Si deseas enviar una o varias tarjetas a una direcci&oacute;n espec&iacute;fica, Dir&iacute;gete al m&oacute;dulo de &OpenCurlyDoubleQuote;env&iacute;os personalizados&CloseCurlyDoubleQuote; una vez realices la solicitud.</p>
                <br>
                <h3 class="subtitulo-iz" style="padding-bottom: 5px;">Crear tarjetahabiente</h3>
                <div class="container">
                    <?php if ($error == 5) { ?>
                        <div class="alert alert-danger">
                            <?= $respues ?>
                        </div>
                    <?php } ?>
                    <?php if ($error == 1) { ?>             

                        <div class="alert alert-success">Solicitud Exitosa</div>
                    <?php } ?>
                    <?php if ($error == 2) { ?>
                        <div class="alert alert-danger">
                            Los datos Ingresados son Incorrectos 2
                            <?= $respues ?>
                        </div>
                    <?php } ?>
                    <?php if ($error == 3) { ?>
                        <div class="alert alert-danger">
                            Los datos Ingresados son Incorrectos 3
                            <?= $respues ?>
                        </div>
                    <?php } ?>
                    <?php if ($error == 4) { ?>
                        <div class="alert alert-danger">
                            <?= $mensaje ?>
                        </div>

                    <?php } ?> 
                </div>
                <form method="post" action="solicitud" id="formSolicitudUno" >
                    <input value="<?= $_GET['sol'] ?>" name="pksolicitudPrepepdido" hidden>
                    <div class="col-sm-6">
                        <p class="titulo-mini-left">Agregar producto</p>
                        <div class="col-sm-2">
                            <input hidden type="checkbox">
                            <a class="add img-circle" style="background-color: #ffffff;">
                                <img   width="25%" style="border-radius:50%; width:100%;height:100%;" src="/static/img/portal/solicitudTar/agregarproducto.png">
                            </a>
                        </div>
                        <div class="col-sm-4" id="selProduct" hidden>
                            <div class="form-group" style="margin-top: 0.2%;margin-bottom:0px;">
                                <div class="col-sm-3" style="vertical-align" > 
                                    <select class="sel-dinamico" name="productos[]" style="border-radius:30px" id="producto" required>
                                        <option value="">Seleccione Producto</option>
                                        <?php foreach ($productos as $value) { ?> 
                                            <option value=<?= $value['PK_PRODUCTO_CODIGO'] ?> > <?= $value['NOMBRE_PRODUCTO'] ?></option>
                                        <?php } ?>
                                    </select> 
                                </div>
                            </div>
                        </div>
                        <br><br><br><br>
                        <div class="select" id="sTipDoc">
                            <select name="tipoDocumento" id="tipoDocumento" class="required" required>
                                <option value=""> Seleccione Tipo Documento</option>
                                <?php foreach ($tipoDocumento as $key => $value) { ?>
                                    <option value="<?= $value['PK_TD_CODIGO'] ?>"> <?= ucwords(strtolower($value['NOMBRE'])) ?></option>
                                <?php } ?>
                            </select>
                            <div> Seleccione Tipo Documento</div>
                        </div>
                        <input type="text" pattern="^[1-9][0-9]+[0-9]" id="docu" class="numPat" name="documento" placeholder="N&uacute;mero de Documento" required autocomplete="nope" onselectstart="return false" onpaste="return false;" onCopy="return false" onCut="return false" onDrag="return false" onDrop="return false" >

                        <div id="seccion-recargar">

                            <input type="text" class="textPatSt" id="pNom" name="primerNombre" placeholder="Primer Nombre" autocomplete="nope" onselectstart="return false" onpaste="return false;" onCopy="return false" onCut="return false" onDrag="return false" onDrop="return false"  required>

                            <input type="text" class="textPatSt"  id="sNom" name="segundoNombre" placeholder="Segundo Nombre" autocomplete="nope" onselectstart="return false" onpaste="return false;" onCopy="return false" onCut="return false" onDrag="return false" onDrop="return false" >

                            <input type="text" class="textPatSt"  id="pApe" name="primerApellido" placeholder="Primer Apellido" autocomplete="nope" onselectstart="return false" onpaste="return false;" onCopy="return false" onCut="return false" onDrag="return false" onDrop="return false"  required>

                            <input type="text" class="textPatSt"  id="sApe" name="segundoApellido" placeholder="Segundo Apellido" autocomplete="nope" onselectstart="return false" onpaste="return false;" onCopy="return false" onCut="return false" onDrag="return false" onDrop="return false" >

                            <br> 

                            <input type="email" class="correoPat" name="correo" id="correo" placeholder="Correo Electr&oacute;nico" autocomplete="nope" onselectstart="return false" onpaste="return false;" onCopy="return false" onCut="return false" onDrag="return false" onDrop="return false"  required>
                            <br>

                            <input type="text" pattern="^3[0-9]{9}" maxlength="10" class="telefono" name="telefono" id="telefono" placeholder="Tel??fono Celular" autocomplete="nope" onselectstart="return false" onpaste="return false;" onCopy="return false" onCut="return false" onDrag="return false" onDrop="return false" title="Ejemplo 3001112233 " required>

                            <br>
                           <!-- <input type="date" name="fechaNacimiento" placeholder="Fecha de Nacimiento" required>
                            <br>
                            <br>
                            <div class="select" hidden>
                                <select name="genero" id="genero" class="required" required>
                                    <option value=""> Selecione Genero</option>
                            <?php foreach ($generos as $key => $value) { ?>
                                                                                                                                                    <option value="<?= $value['PK_GEN_CODIGO'] ?>"> <?= $value['NOMBRE'] ?></option>
                            <?php } ?>
                                </select>
                                <div>Selecione Genero</div>
                            </div>
                            <br>-->
                            <div class="select" id="sDepar">
                                <select name="departamentos" id="inputDepartamento" class="required" required>
                                    <option value=""> Seleccione Departamento</option>
                                    <?php foreach ($departamentos as $key => $value) { ?>
                                        <option value="<?= $value['PK_DEP_CODIGO'] ?>"> <?= $value['NOMBRE'] ?></option>
                                    <?php } ?>
                                </select>
                                <div> Seleccione Departamento</div>
                            </div>
                            <br>
                            <div class="select" id="sCiu" >
                                <select name="ciudad" id="inputCiudad" class="required" required>
                                    <option value=""> Seleccione Ciudad</option>
                                </select>
                                <div> Seleccione Ciudad</div>
                            </div>
                            <br>
                            <input type="text" class="textPatStNum"  id="identificador" name="identificador" placeholder="Identificador tarjeta" autocomplete="nope" onselectstart="return false" onpaste="return false;" onCopy="return false" onCut="return false" onDrag="return false" onDrop="return false" hidden required>


                        </div>
                        <div class="select">
                            <select name="custodio" id="custodio" class="required" required>
                                <option value=""> Asignar custodio</option>
                                <?php foreach ($custodios as $key => $value) { ?>
                                    <option value="<?= $value['CODIGOENTIDA'] ?>"> <?= $value['NOMBRE'] ?></option>
                                <?php } ?>
                            </select>
                            <div>Asignar custodio</div>
                        </div>
                        <br>
                        <div class="button col-md-12 col-md-push-6" >
                            <button class="button spacing" type="submit" id="btnSubmitSolicitudUno">Solicitar tarjeta</button>

                        </div>
                    </div>

                </form>
            </div>

            <?php if (!empty($solicitudespend)) { ?>
                <div class="col-sm-6" >
                    <h3 class="subtitulo-iz">Solicitud sin finalizar</h3>
                    <table class="table ">
                        <thead>
                            <tr class="trHead">
                                <th> No Solicitud </th>
                                <th> Fecha Solicitud </th>
                                <th> Completar </th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($solicitudespend as $key => $value) { ?>
                                <tr>
                                    <td style="padding-bottom:5px;font-weight: bold;color: #366199;font-size: 18px;"><?= $value['SOLICITUD'] ?></td>
                                    <td style="padding-bottom:5px;font-weight: bold;color: #366199;font-size: 18px;"><?= $value['FECHA_CREACION'] ?></td>
                                    <td style="padding-bottom:5px;font-weight: bold;color: #366199;font-size: 18px;"><a   href="/portal/solicitudTarjetas/lista/<?= $value['SOLICITUD'] ?>/1"> COMPLETAR  </a></td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            <?php } ?>
        </div> 
    </div>

</div>
</div>
<?php if ($error == 1) { ?>
    <script>
        //                alert("<?= $error ?>");
        //                mostrarmodal();
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
                    <div class="modal-body" style="text-align: center;height: 230px;">
                        <form action="/portal/solicitudTarjetas/nombreOrden" method="POST" >
                            <p  style="font-size:18px;color:#0c385e;font-weight: bold">???La solicitud fue realizada exitosamente!</p>
                            <br>
                            <p style="font-size:18px;color:#888686;">Por favor asigne un nombre a la orden:</p>
                            <br>
                            <input type="hidden" name="codigo" value="<?= $codigosolicitud ?>">
                            <input type="text" class="textPat"  name="nombreorden" style="width: 60%" placeholder="Ingrese un nombre para la orden" required>
                            <br>
                            <div class="button col-sm-6">
                                <button type="submit" name="ORDEN" value="1" class="btn btn-default" >ORDEN DE PEDIDO</button>
                            </div>
                            <div class="button col-sm-6">
                                <button type="submit" name="SOLICITUD" value="2" class="btn btn-default" >SOLICITAR ABONO</button>
                            </div>
                            <br>
                        </form>




                    </div>
                    <!--   <div class="modal-footer">
                          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                      </div>-->
                </div>
            </div>
        </div>
    </div>
<?php } ?>

<?php if ($error == 5) { ?>
    <div class="container" style="margin-top: 15%;">
        <!-- Modal -->
        <div class="modal fade" id="myModal1" role="dialog" style="    margin-top: 15%;">
            <div class="modal-dialog">
                <!-- Modal content-->
                <div class="modal-content" style="border-radius:35px">
                    <button class="btn_cerrar_modal" data-dismiss="modal"></button>
                    <div class="modal-body" style="text-align: center;height: auto;">
                        <div class="modal-header" style="padding:0px">
                            <h5 style="color: #366199;font-size: 24px;font-weight: bold; ">Error generado</h5>
                        </div>
                        <div>
                            <br>    
                            <p style="font-size:18px;color:#888686;font-weight: bold"><?= $respues ?>!</p>

                            <div class="button" style="width:100px;margin-left:40%">
                                <button style=""type="button" name="close" class="btn btn-default" data-dismiss="modal">ACEPTAR</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php } ?>        
<!-- Modal confirmacion solicitud auno a uno-->
<div class="modal fade" id="ModalConfSolUno" role="dialog" style="margin-top: 5%;"  data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content" style="border-radius:35px">

            <div class="modal-body" style="text-align: center;height: auto;">

                <label id="nomllavero" style="font-size: 18px;color: #366199;font-weight: bold;">???Desea continuar?</label>
                <table class="table table-hover dataSel" style="margin-top: 20px;width: 70%;margin-right: auto; margin-left: auto;">
                    <thead>
                        <tr style="font-weight: bold">
                            <td>Producto</td>
                            <td>Cantidad</td>
                        </tr>
                    </thead>
                    <tbody class="tblbody">
                    </tbody>
                    <tfoot>
                        <tr>
                            <th>Total</th>
                            <th class="thT"></th>
                        </tr>
                    </tfoot>
                </table>

                <div style=" margin-bottom: 3em">
                    <div class="button col-sm-6" >
                        <button type="button" name="ACEPTAR" value="1" class="btn btn-default"  onclick="
                                $('#formSolicitudUno').submit();" >A C E P T A R</button>
                    </div>
                    <div class="button col-sm-6" >
                        <button type="button" name="CANCELAR" class="btn btn-default" data-dismiss="modal">C A N C E L A R</button>
                    </div>
                </div>
                <br>
                <br>
            </div>
        </div>
    </div>
</div>


 <div class="container" style="margin-top: 15%;">
        <!-- Modal -->
        <div class="modal fade" id="modalCampos" role="dialog" style="    margin-top: 15%;">
            <div class="modal-dialog">
                <!-- Modal content-->
                <div class="modal-content" style="border-radius:35px">
                    <button class="btn_cerrar_modal" data-dismiss="modal"></button>
                    <div class="modal-body" style="text-align: center;height: auto;">
                        <div class="modal-header" style="padding:0px">
                            <h5 style="color: #366199;font-size: 24px;font-weight: bold; ">Formulario inv??lido</h5>
                        </div>
                        <div>
                            <br>    
                            <p style="font-size:18px;color:#888686;font-weight: bold">Por favor complete correctamente el formulario !</p>

                            <div class="button" style="width:100px;margin-left:40%">
                                <button style=""type="button" name="close" class="btn btn-default" data-dismiss="modal">ACEPTAR</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<script type="text/javascript">
    var count_click = 0;
    var count = 0;
    var res;
    $('.add').click(function () {
        $('#selProduct').show();
    });




</script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.3/dist/jquery.validate.js"></script>
<script type="text/javascript">
    
    
    $("#formSolicitudUno").submit(function () {
        $('#loader').modal('show');
    });
     $.validator.addMethod("formPhone", function (value, element) {
    var pattern = /^3[0-9]{9}/;
    return this.optional(element) || pattern.test(value);
  });

    $(document).ready(function () {
    
    $("#formSolicitudUno").validate({
		rules: {
			telefono: { required:true, maxlength: 10, minlength: 10, formPhone: true}
		},
		messages: {
			telefono : "El campo Tel??fono no contiene un formato correcto."
		}
	});
    

        $('#inputDepartamento').change(function () {
            $.ajax({
                url: "/portal/ajax/ciudad/" + $('#inputDepartamento').val()
            })
                    .done(function (msg) {
                        $('#inputCiudad').html(msg)
                    });
        });
        $('.sel-dinamico').change(function () {
            var valSele = $(this).val();
            var porciones = valSele.split('_');
            var pr = porciones[2];
            if (pr == 0) {
                $("#pNom").attr("hidden", true);
                $("#sNom").attr("hidden", true);
                $("#pApe").attr("hidden", true);
                $("#sApe").attr("hidden", true);
                $("#sTipDoc").attr("hidden", true);
                $("#docu").attr("hidden", true);
                $("#sDepar").attr("hidden", true);
                $("#sCiu").attr("hidden", true);
                $("#correo").attr("hidden", true);
                $("#telefono").attr("hidden", true);
//                $("#formSolicitudUno").find(':input').each(function () {
//                    this.disabled = true;
//                    
//                    $(this).prop('required', false);
//                });
                $('#identificador').removeAttr('hidden');
//                $("#identificador").removeAttr("disabled");
//                $("#identificador").prop('required', true);
//                $("#custodio").removeAttr("disabled");
//                $("#custodio").prop('required', true);
                $(".sel-dinamico").removeAttr('disabled');
            } else {
//                $("#formSolicitudUno").find(':input').each(function () {
//                    $(this).removeAttr('disabled');
////                    var elemento = this;
////                    elemento.attr("disabled", true);
////                    alert("elemento.id=" + elemento.id + ", elemento.value=" + elemento.value);
//                });
                $('#pNom').removeAttr('hidden');
                $('#sNom').removeAttr('hidden');
                $('#pApe').removeAttr('hidden');
                $('#sApe').removeAttr('hidden');
                $('#sTipDoc').removeAttr('hidden');
                $('#docu').removeAttr('hidden');
                $('#sDepar').removeAttr('hidden');
                $('#sCiu').removeAttr('hidden');
                $('#correo').removeAttr('hidden');
                $('#telefono').removeAttr('hidden');
                $("#identificador").attr("hidden", true);
//                $("#identificador").attr("disabled", true);
            }


        });

    });
    var x = "<?= $error ?>";
    if (x == "1") {
        $('#myModal').modal({backdrop: 'static', keyboard: false});
        $('#myModal').modal('show');
    } else if (x == "5") {

        $('#myModal1').modal('show');
    }
    
    
    
//    $('#formSolicitudUno').submit(function() {
//    if ( $.trim($("#producto").val()) === "" || $.trim($("#tipoDocumento").val()) === "" || $.trim($("#docu").val()) === "" || $.trim($("#pNom").val()) === "" || $.trim($("#pApe").val()) === "" || $.trim($("#correo").val()) === "" || $.trim($("#telefono").val()) === "" || $.trim($("#inputDepartamento").val()) === "" || $.trim($("#inputCiudad").val()) === "" || $.trim($("#custodio").val()) === "") {
//       $('#modalCampos').modal('show');
//        $('#loader').modal('hide');
//        $('#ModalConfSolUno').modal('hide');
//        return false;
//    }
//});
    
    var currentTODO = $('.dataSel');
    var boton = document.getElementById("btnSubmitSolicitudUno");
    boton.onclick = function (e) {
        $('#servicioSelecionado').val();
    
           
        var validado = $("#formSolicitudUno").valid();
        if (validado) {
            e.preventDefault();
            $('#ModalConfSolUno').modal('show');
        } else {
            e.preventDefault();
            $('#loader').modal('hide');
        }


        var x = $('.sel-dinamico');
        var tZ = 0; // zafiro
        var tGV = 0;  // GASTOS DE VIAJE
        var tMT = 0; // MEDIOS DE TRANSPORTE
        var tZP = 0; // Zafiro plus
        var tBS = 0; // Bienestar salud
        var tGL = 0; // gastos de legali
        var tPP = 0; // premio plus
        var tV = 0; //vestuario
        var tC = 0; // Combustible
        var tGC = 0; // Gastos corp
        var tCM = 0; // caja menor
        var tP = 0; //premio
        var tCA = 0; //canasta
        var tM = 0;  // market
        var tB = 0; //bienestar
        var tGR = 0; //gast repre
        var tBC = 0; //BUSSINESS CAR


        var container = currentTODO.find('.tblbody');

        for (j = 0; j < x.length; j++) {
            var porciones = x[j].value.split('_');
            var pr = porciones[0];
            if (pr === '298' || pr === '316') {
                tM++;
            } else if (pr === '303' || pr === '321') {
                tV++;
            } else if (pr === '300' || pr === '318') {
                tB++;
            } else if (pr === '302' || pr === '325') {
                tPP++;
            } else if (pr === '301' || pr === '330') {
                tMT++;
            } else if (pr === '322') {
                tZ++;
            } else if (pr === '331') {
                tGV++;
            } else if (pr === '323') {
                tZP++;
            } else if (pr === '319') {
                tBS++;
            } else if (pr === '329') {
                tGL++;
            } else if (pr === '320' || pr === '304') {
                tC++;
            } else if (pr === '328' || pr === '310') {
                tGC++;
            } else if (pr === '326') {
                tCM++;
            } else if (pr === '324') {
                tP++;
            } else if (pr === '317') {
                tCA++;
            } else if (pr === '332') {
                tGR++;
            } else if (pr === '327') {
                tBC++;
            }
        }
        var totalPro = tZ + tGV + tMT + tZP + tBS + tGL + tPP + tV + tC + tGC + tCM + tP + tCA + tM + tB + tGR + tBC;
        $('.thT').html(totalPro);
        var exiTM = currentTODO.find('.tM');
        if (tM > 0) {
            if (exiTM.length === 0) {
                container.append('<tr class="tM"><td>Market</td> <td id="vtM">' + tM + '</td></tr>');
            } else {
                $('#vtM').html(tM);
            }
        } else if (tM === 0 && exiTM.length > 0) {
            $('.tM').remove();
        }
        var exitV = currentTODO.find('.tV');
        if (tV > 0) {
            if (exitV.length === 0) {
                container.append('<tr class="tV"><td>Vestuario</td> <td id="vtV">' + tV + '</td></tr>');
            } else {
                $('#vtV').html(tV);
            }
        } else if (tV === 0 && exitV.length > 0) {
            $('.tV').remove();
        }
        var exitB = currentTODO.find('.tB');
        if (tB > 0) {
            if (exitB.length === 0) {
                container.append('<tr class="tB"><td>Bienestar</td><td id="vtB">' + tB + '</td></tr>');
            } else {
                $('#vtB').html(tB);
            }
        } else if (tB === 0 && exitB.length > 0) {
            $('.tB').remove();
        }
        var exitPP = currentTODO.find('.tPP');
        if (tPP > 0) {
            if (exitPP.length === 0) {
                container.append('<tr class="tPP"><td>Premio plus</td><td id="vtPP">' + tPP + '</td></tr>');
            } else {
                $('#vtPP').html(tPP);
            }
        } else if (tPP === 0 && exitPP.length > 0) {
            $('.tPP').remove();
        }
        var exitMT = currentTODO.find('.tMT');
        if (tMT > 0) {
            if (exitMT.length === 0) {
                container.append('<tr class="tMT"><td>Medios de transporte</td> <td id="vtMT">' + tMT + '</td></tr>');
            } else {
                $('#vtMT').html(tMT);
            }
        } else if (tMT === 0 && exitMT.length > 0) {
            $('.tMT').remove();
        }
        var exitZ = currentTODO.find('.tZ');
        if (tZ > 0) {
            if (exitZ.length === 0) {
                container.append('<tr class="tZ"><td>Zafiro</td> <td id="vtZ">' + tZ + '</td></tr>');
            } else {
                $('#vtZ').html(tZ);
            }
        } else if (tZ === 0 && exitZ.length > 0) {
            $('.tZ').remove();
        }
        var exitGV = currentTODO.find('.tGV');
        if (tGV > 0) {
            if (exitGV.length === 0) {
                container.append('<tr class="tGV"><td>Gastos viaje</td> <td id="vtGV">' + tGV + '</td></tr>');
            } else {
                $('#vtGV').html(tGV);
            }
        } else if (tGV === 0 && exitGV.length > 0) {
            $('.tGV').remove();
        }
        var exitZP = currentTODO.find('.tZP');
        if (tZP > 0) {
            if (exitZP.length === 0) {
                container.append('<tr class="tZP"><td>Zafiro plus</td> <td id="vtZP">' + tZP + '</td></tr>');
            } else {
                $('#vtZP').html(tZP);
            }
        } else if (tZP === 0 && exitZP.length > 0) {
            $('.tZP').remove();
        }
        var exitBS = currentTODO.find('.tBS');
        if (tBS > 0) {
            if (exitBS.length === 0) {
                container.append('<tr class="tBS"><td>Bienestar salud</td> <td id="vtBS">' + tBS + '</td></tr>');
            } else {
                $('#vtBS').html(tBS);
            }
        } else if (tBS === 0 && exitBS.length > 0) {
            $('.tBS').remove();
        }
        var exitGL = currentTODO.find('.tGL');
        if (tGL > 0) {
            if (exitGL.length === 0) {
                container.append('<tr class="tGL"><td>G de legalizaci&oacute;n</td> <td id="vtGL">' + tGL + '</td></tr>');
            } else {
                $('#vtGL').html(tGL);
            }
        } else if (tGL === 0 && exitGL.length > 0) {
            $('.tGL').remove();
        }

        var exitC = currentTODO.find('.tC');
        if (tC > 0) {
            if (exitC.length === 0) {
                container.append('<tr class="tC"><td>Combustible</td> <td id="vtC">' + tC + '</td></tr>');
            } else {
                $('#vtC').html(tC);
            }
        } else if (tC === 0 && exitC.length > 0) {
            $('.tC').remove();
        }
        var exitGC = currentTODO.find('.tGC');
        if (tGC > 0) {
            if (exitGC.length === 0) {
                container.append('<tr class="tGC"><td>gastos corporativos</td> <td id="vtGC">' + tGC + '</td></tr>');
            } else {
                $('#vtGC').html(tGC);
            }
        } else if (tGC === 0 && exitGC.length > 0) {
            $('.tGC').remove();
        }
        var exitCM = currentTODO.find('.tCM');
        if (tCM > 0) {
            if (exitCM.length === 0) {
                container.append('<tr class="tCM"><td>Caja menor</td> <td id="vtCM">' + tCM + '</td></tr>');
            } else {
                $('#vtCM').html(tCM);
            }
        } else if (tCM === 0 && exitCM.length > 0) {
            $('.tCM').remove();
        }
        var exitP = currentTODO.find('.tP');
        if (tP > 0) {
            if (exitP.length === 0) {
                container.append('<tr class="tP"><td>Premio</td> <td id="vtP">' + tP + '</td></tr>');
            } else {
                $('#vtP').html(tP);
            }
        } else if (tP === 0 && exitP.length > 0) {
            $('.tP').remove();
        }
        var exitCA = currentTODO.find('.tCA');
        if (tCA > 0) {
            if (exitCA.length === 0) {
                container.append('<tr class="tCA"><td>Canasta</td> <td id="vtCA">' + tCA + '</td></tr>');
            } else {
                $('#vtCA').html(tCA);
            }
        } else if (tCA === 0 && exitCA.length > 0) {
            $('.tCA').remove();
        }
        var exitGR = currentTODO.find('.tGR');
        if (tGR > 0) {
            if (exitGR.length === 0) {
                container.append('<tr class="tGR"><td>G de representaci&oacute;n</td> <td id="vtGR">' + tGR + '</td></tr>');
            } else {
                $('#vtGR').html(tGR);
            }
        } else if (tGR === 0 && exitGR.length > 0) {
            $('.tGR').remove();
        }
        var exitBC = currentTODO.find('.tBC');
        if (tBC > 0) {
            if (exitBC.length === 0) {
                container.append('<tr class="tBC"><td>Business car</td> <td id="vtBC">' + tBC + '</td></tr>');
            } else {
                $('#vtBC').html(tBC);
            }
        } else if (tBC === 0 && exitBC.length > 0) {
            $('.tBC').remove();
        }
    };

</script>
<script type="text/javascript">
    $(document).ready(function () {


        $('#docu').change(function () {
            if ($('#tipoDocumento').val() !== '' && $('#docu').val() !== '') {
                $.ajax({
                    url: "/portal/ajax/dataUser/" + $('#tipoDocumento').val() + "/" + $('#docu').val()
                })
                        .done(function (msg) {
                            if (msg.length > 0) {
                                $('#seccion-recargar').html(msg)
                            }
//                        console.log(msg);
                        });
            }


        });
    });
</script>
