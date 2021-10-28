<style>

</style>
<div class="loader" id="loader" hidden=""></div>
<div style=" margin-bottom: 200px; margin-top: -50px;">
    <div class="container">
        <hr style="border-top: 1px solid #eee0;">
        <h2 class="titulo-iz">Solicitud de Tarjetas Gestión</h2>
        <ul class="nav nav-tabs">
            <li class="active" id="solicitudUno"><a href="/portal/solicitudGestion/solicitudGes">Solicitud Uno a Uno</a></li>
            <li id="solicitudMasiva "><a  href="/portal/solicitudGestion/solicitudTarjetasMasivo">Solicitud Masiva</a></li>
        </ul>

        <div class="tab-content">
            <div id="solicitudUno" class="tab-pane fade in active">
                <h3 class="subtitulo-iz">Crear tarjetahabiente</h3>
                <div class="container">
                    <?php if ($error == 5) { ?>
                        <!---   <div class="alert alert-danger">
                               Debe completar el formulario antes de pulsar guardar!--> 
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
                    <?= $mensaje ?>
                <?php } ?> 

                <form method="post" action="solicitudGes" id="formSolicitudUno" >
                    <div class="col-sm-6">
                        <input type="text" class="textPatSt"  name="primerNombre" placeholder="Primer Nombre" autocomplete="nope" onselectstart="return false" onpaste="return false;" onCopy="return false" onCut="return false" onDrag="return false" onDrop="return false"  required>

                        <input type="text" class="textPatSt"  name="segundoNombre" placeholder="Segundo Nombre" autocomplete="nope" onselectstart="return false" onpaste="return false;" onCopy="return false" onCut="return false" onDrag="return false" onDrop="return false" >

                        <input type="text" class="textPatSt"  name="primerApellido" placeholder="Primer Apellido" autocomplete="nope" onselectstart="return false" onpaste="return false;" onCopy="return false" onCut="return false" onDrag="return false" onDrop="return false"  required>

                        <input type="text" class="textPatSt"  name="segundoApellido" placeholder="Segundo Apellido" autocomplete="nope" onselectstart="return false" onpaste="return false;" onCopy="return false" onCut="return false" onDrag="return false" onDrop="return false" >
                        <br> 
                        <br>
                        <div class="select">
                            <select name="tipoDocumento" id="tipoDocumento" class="required" required>
                                <option value=""> Seleccione Tipo Documento</option>
                                <?php foreach ($tipoDocumento as $key => $value) { ?>
                                    <option value="<?= $value['PK_TD_CODIGO'] ?>"> <?= ucwords(strtolower($value['NOMBRE'])) ?></option>
                                <?php } ?>
                            </select>
                            <div> Seleccione Tipo Documento</div>
                        </div>
                        <input type="text" pattern="^[1-9][0-9]+[0-9]" class="numPat" name="documento" placeholder="Número de Documento" required autocomplete="nope" onselectstart="return false" onpaste="return false;" onCopy="return false" onCut="return false" onDrag="return false" onDrop="return false" >

                        <input type="email" class="correoPat" name="correo" placeholder="Correo Electrónico" autocomplete="nope" onselectstart="return false" onpaste="return false;" onCopy="return false" onCut="return false" onDrag="return false" onDrop="return false"  required>
                        <br>
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
                        <div class="select">
                            <select name="departamentos" id="inputDepartamento" class="required" required>
                                <option value=""> Seleccione Departamento</option>
                                <?php foreach ($departamentos as $key => $value) { ?>
                                    <option value="<?= $value['PK_DEP_CODIGO'] ?>"> <?= $value['NOMBRE'] ?></option>
                                <?php } ?>
                            </select>
                            <div> Seleccione Departamento</div>
                        </div>
                        <br>
                        <div class="select">
                            <select name="ciudad" id="inputCiudad" class="required" required>
                                <option value=""> Seleccione Ciudad</option>
                            </select>
                            <div> Seleccione Ciudad</div>
                        </div>
                        <br>
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
                    <div class="col-sm-6">
                        <p class="titulo-mini-left">Agregar producto</p>
                        <div class="col-sm-2">
                            <input hidden type="checkbox">
                            <a class="add img-circle" style="background-color: #ffffff;">

                                <img   width="25%" style="border-radius:50%; width:100%;height:100%;" src="/static/img/portal/solicitudTar/agregarproducto.png">
                            </a>

                        </div>
                    </div>
                    <div class="optionBox ">
                        <div class="block">

                        </div>
                    </div>
                </form>
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
                            <p  style="font-size:18px;color:#0c385e;font-weight: bold">¡La solicitud fue realizada exitosamente!</p>
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
                    <!--  <div class="modal-header">
                          <button type="button" class="close" data-dismiss="modal">&times;</button>
                      </div>-->
                    <div class="modal-body" style="text-align: center;height: 230px;">
                        <div>
                            <br>    
                            <p style="font-size:18px;color:#888686;font-weight: bold"><?= $respues ?>!</p>
                            <br>
                            <br>
                            <div class="button" style="width:100px;margin-left:40%">
                                <button style=""type="button" name="close" class="btn btn-default" data-dismiss="modal">ACEPTAR</button>

                            </div>
                        </div>



                    </div>
                    <!--   <div class="modal-footer">
                          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                      </div>-->
                </div>
            </div>
        </div>
    </div>
<?php } ?>        
<!-- Modal confirmacion solicitud auno a uno-->
<div class="modal fade" id="ModalConfSolUno" role="dialog" style="margin-top: 15%;"  data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content" style="border-radius:35px">

            <div class="modal-body" style="text-align: center;height: 200px;">

                <p  style="font-size:24px;color:#888686;font-weight: bold;padding-top: 25px">
                    ¿ Desea continuar con la solicitud de tarjetas ?
                </p>
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

        if (count < 5) {
            count = count_click_add(count);
            $('.block:last').before(' \n\
        <div class="form-group" style="margin-top: 0.2%;margin-bottom:0px;"><br>\n\
            <div class=" col-sm-3 "style="vertical-align; " > \n\
                <select class="sel-dinamico" name="productos[]" style="border-radius=30px;" required>\n\
                    <option value="">Seleccione Producto</option>\n\
<?php foreach ($productos as $value) { ?> \n\ \n\
                                                        <option value=<?= $value['PK_PRODUCTO_CODIGO'] ?>>\n\
    <?= $value['NOMBRE_PRODUCTO'] ?></option><?php } ?></select> </div>\n\
 <a class="remove btn btn-danger" style="float: center; margin-top: 10px;    margin-right: -3%">X</a>\n\</div>');
        } else {

            alert("No es posible agregar mas tarjetas");
        }
    });

    $('.optionBox').on('click', '.remove', function () {

        count = count_click_add1(count);
        $(this).parent().remove();
    });

    function count_click_add(count) {

        count_click += 1;
        count += 1;
        return count;
    }

    function count_click_add1(count) {
        count = count - 1;
        return count;
    }

</script>
<script type="text/javascript">

    var boton = document.getElementById("btnSubmitSolicitudUno");
    boton.onclick = function (e) {
        var validado = $("#formSolicitudUno").valid();
        if (validado) {
            e.preventDefault();
            $('#ModalConfSolUno').modal('show');
        } else {
            e.preventDefault();
            $('#loader').modal('hide');
        }
    };
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


</script>