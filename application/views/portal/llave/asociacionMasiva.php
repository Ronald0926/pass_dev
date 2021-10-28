<style>
    #masivoIconos td,
    th {
        padding: 30px;
    }
     .tnotifi{
        color: red;
        padding-left:  15px;
        display: none;
    }
</style>
<div class="loader" id="loader" hidden=""></div>
<div class="col-lg-2"></div>
<div class="container col-lg-8" style="margin-bottom: 200px; margin-top: -50px;">
    <hr style="border-top: 1px solid #eee0;">
    <h2 class="titulo-iz">Asociación Tarjetas</h2>
    <ul class="nav nav-tabs">
        <li><a href="/portal/llaveMaestra/asociacion">Asociación uno a uno</a></li>
        <li class="active"><a data-toggle="tab" href="#solicitudMasiva">Asociación masiva</a></li>
        <li><a href="/portal/llaveMaestra/desasociacion">Desasociación</a></li>
    </ul>
    <div class="col-lg-6">
        <div class="login-checkbox col-md-6"  id="chkTodo">
            <input type="checkbox" id="chkMasivaAll">
            <span id="<?= $value['CODIGOORDEN'] ?>">
                <div class="">
                    <span class="login-checkbox-check">
                    </span>
                </div>
            </span>
        </div>
        <div class="col-md-6" style="padding: 1px;left: 16px;">Seleccionar Todo</div>
    </div>
    <div class="col-lg-3">
        <form action="/portal/llaveMaestra/returnTarjetasAsociacion/2" method="POST">
            <?php if ($errorpkllavero == 1 || isset($_GET['errorpk'])) { ?>
                <div class="row"><label class="tnotifi"> Por favor seleccione un llavero.</label></div>
            <?php } ?>
            <div class="select">
                <select name="pk_llavero" id="llavero"  required onchange="this.form.submit();">
                    <option value=""> Seleccione Llavero</option>
                    <?php foreach ($llaveros as $key => $value) { ?>
                        <option value="<?= $value['PK_LLAVERO_CODIGO'] ?>" <?php if ($value['PK_LLAVERO_CODIGO'] == $pk_llavero_codigo) echo 'selected'; ?>> <?= ucwords(strtolower($value['NOMBRE_LLAVERO'])) ?></option>
                    <?php } ?>
                </select>
                <div> <?php echo $nombrellaveroselect != "" ? $nombrellaveroselect : 'Seleccione Llavero*' ?></div>
            </div>
        </form>
    </div>
    <form method="POST" action="/portal/llaveMaestra/asociacionMasiva/1" id="formasociacionmasivo"><br>
        <input type="text" name="pk_llavero_codigo" id='pk_llavero_codigo' value="<?= $pk_llavero_codigo ?>" hidden>
        <div class="tab-content">
            <div id="solicitudUno" class="tab-pane fade in active">


                <!--<div class="row" style="float:right">
                    <table>
                        <tr>
                            <td><select style="border-radius:20px">
                                    <option value="">Seleccionar campaña</option>
                                    <option value="1">Campaña1</option>
                                    <option value="2">Campaña2</option>
                                </select></td>
                            <td><input style="border-radius:20px" type="text"></td>
                        </tr>
                    </table>
                </div>-->
                <div class="container col-lg-12">
                    <div class="grid">
                        <table class="table table-hover" id="tblmasiva">
                            <thead>
                                <tr>
                                    <th> Seleccionar </th>
                                    <th> Nombre </th>
                                    <th> T.D. </th>
                                    <th> No.Doc </th>
                                    <th> Número tarjeta</th>
                                    <th> Producto </th>
                                    <th> Identificador </th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $documento;
                                //$pedidoAbono = $this->session->userdata("pedidoAbono");
                                $pedidoAbono = $_SESSION['pedidoAbono'];

                                foreach ($tarjetaHabiente as $value) {
                                    //if ($documento != $value['DOC']) {
                                     //   $documento = $value['DOC'];
                                        ?>

                                        <tr class="gradeC">
                                            <td>
                                                <div class="login-checkbox" onclick="" style="padding-top: 5px">
                                                    <input name="usuarios[]" class="chkmasiva" value="<?= $value['CODPROD'] ?>,<?= $value['CODTH'] ?>,<?= $value['CODTAR'] ?>" type="checkbox">
                                                    <span id="<?= $value['CODIGOORDEN'] ?>">
                                                        <div class="">
                                                            <span class="login-checkbox-check spnmasiva">
                                                            </span>
                                                        </div>
                                                    </span>
                                                </div>
                                            </td>
                                            <td><?= $value['NOMTAR'] ?></td>
                                            <td><?= $value['ABR'] ?></td>
                                            <td><?= $value['DOC'] ?></td>
                                            <td><?= $value['NUMTAR'] ?></td>
                                        <?php //} else { ?>
<!--                                            <td>
                                                <div class="login-checkbox" onclick="" style="padding-top: 5px">
                                                    <input name="usuarios[]" class="chkmasiva" value="<?= $value['CODPROD'] ?>,<?= $value['CODTH'] ?>, <?= $value['CODTAR'] ?>" type="checkbox">
                                                    <span id="<?= $value['CODIGOORDEN'] ?>">
                                                        <div class="">
                                                            <span class="login-checkbox-check spnmasiva">
                                                            </span>
                                                        </div>
                                                    </span>
                                                </div>
                                            </td>
                                            <td></td>
                                            <td></td>
                                            <td></td>-->
                                        <?php //} ?>
                                        <td><?= $value['NOMPRO'] ?></td>
                                        <td><?= $value['IDENTIFICADOR'] ?></td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>

                    </div>
                    <div class="button col-md-4 col-md-push-4">
                        <button type="submit">Asociar</button>
                    </div>
                </div>
                </form>
            </div>

        </div>
        
</div>

<?php if ($error == 1) { ?>
    <div class="container" style="margin-top: 15%;">
        <!-- Modal -->
        <div class="modal fade" id="myModal" role="dialog" style="    margin-top: 15%;">
            <div class="modal-dialog">

                <!-- Modal content-->
                <div class="modal-content" style="border-radius:35px">
                    <div class="modal-body" style="text-align: center;height: 230px;">
                        <form action="/portal/solicitudTarjetas/nombreOrden" method="POST">
                            <p style="font-size:18px;color:#0c385e;font-weight: bold">¡Solicitud exitosa!</p>
                            <br>
                            <p style="font-size:18px;color:#888686;">Por favor asigne un nombre a la orden:</p>
                            <br>
                            <input type="hidden" name="codigo" value="<?= $codigosolicitud ?>">
                            <input type="text" class="textPat" name="nombreorden" style="width: 60%" placeholder="Ingrese un nombre para la orden" required>
                            <br>
                            <div class="button col-sm-6">
                                <button type="submit" name="ORDEN" value="1" class="btn btn-default">ORDEN DE PEDIDO</button>
                            </div>
                            <div class="button col-sm-6">
                                <button type="submit" name="SOLICITUD" value="2" class="btn btn-default">SOLICITAR ABONO</button>
                            </div>
                            <br>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php } ?>
<div class="container" style="margin-top: 15%;">
    <!-- Modal -->
    <div class="modal fade" id="myModalAsociacion" role="dialog" style="    margin-top: 15%;">
        <div class="modal-dialog">

            <!-- Modal content-->
            <div class="modal-content" style="border-radius:35px;">
                <div class="modal-body" style="text-align: center; ">
                    <div>
                        <br>
                        <p style="font-size:18px;color:#888686;font-weight: bold">Las tarjetas fueron asociadas correctamente!</p>
                        <br>
                        <br>
                        <div class="button" style="width:100px;margin-left:40%">
                            <button style="" type="button" name="close" class="btn btn-default" data-dismiss="modal">ACEPTAR</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="col-lg-2" style=" /*margin-bottom: 200px; margin-top: -50px;*/"></div>
<?php if ($accionOut == '1') { ?>
    <script>
        $('#myModalAsociacion').modal('show');
    </script>
<?php } ?>

<script>
    var errorpkcodigo = <?php if (isset($errorpkllavero)) {    echo "1;";} else {    echo "0;";}?>
       if (errorpkcodigo === 1) {
        $(".tnotifi").show();
        }
   
    
    $(document).ready(function () {
                                                    var table = $('#tblmasiva').DataTable({
                                                        "bJQueryUI": true,
                                                        "bSort": false,
                                                        "bPaginate": true,
                                                        "sPaginationType": "full_numbers",
                                                        "oLanguage": {
                                                            "sProcessing": "Procesando...",
                                                            "sLengthMenu": "Mostrar _MENU_ registros",
                                                            "sZeroRecords": "No se encontraron resultados",
                                                            "sEmptyTable": "Ningún dato disponible en esta tabla",
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
                                                                "sLast": "Último",
                                                                "sNext": "Siguiente",
                                                                "sPrevious": "Anterior"
                                                            },
                                                            "oAria": {
                                                                "sSortAscending": ": Activar para ordenar la columna de manera ascendente",
                                                                "sSortDescending": ": Activar para ordenar la columna de manera descendente"
                                                            }
                                                        }

                                                    });

                                                    // Handle click on "Select all" control
                                                    $('#chkTodo').on('click', function () {
                                                        
                                                        if( $('#chkMasivaAll').prop('checked') ) {
                                                             // Check/uncheck all checkboxes in the table
                                                            var rows = table.rows({'search': 'applied'}).nodes();
                                                            for (i = 0; i < rows.length; i++) {
                                                                $(".spnmasiva", rows[i]).css("display", "inline");
                                                                $('.chkmasiva', rows[i]).prop('checked', true);
                                                            }
                                                        }else{
                                                            var rows = table.rows({'search': 'applied'}).nodes();
                                                            for (i = 0; i < rows.length; i++) {
                                                                $(".spnmasiva", rows[i]).css("display", "none");
                                                                $('.chkmasiva', rows[i]).prop('checked', false);
                                                            }
                                                        }
                                                            
                                                        
                                                       
                                                    });

                                                    // Handle click on checkbox to set state of "Select all" control
                                                    $('#formasociacionmasivo tbody').on('change', 'input[type="checkbox"]', function () {
                                                        // If checkbox is not checked
                                                        if (!this.checked) {
                                                            var el = $('#chkTodo').get(0);
                                                            // If "Select all" control is checked and has 'indeterminate' property
                                                            if (el && el.checked && ('indeterminate' in el)) {
                                                                // Set visual state of "Select all" control 
                                                                // as 'indeterminate'
                                                                el.indeterminate = true;
                                                            }
                                                        }
                                                    });
                                                    $('#formasociacionmasivo').on('submit', function (e) {
                                                        var form = this;

                                                        // Iterate over all checkboxes in the table
                                                        table.$('input[type="checkbox"]').each(function () {
                                                            // If checkbox doesn't exist in DOM
                                                            if (!$.contains(document, this)) {
                                                                // If checkbox is checked
                                                                if (this.checked) {
                                                                    // Create a hidden element 
                                                                    $(form).append(
                                                                            $('<input>')
                                                                            .attr('type', 'hidden')
                                                                            .attr('name', this.name)
                                                                            .val(this.value)
                                                                            );
                                                                }
                                                            }
                                                        });

                                                        // FOR TESTING ONLY

                                                        // Output form data to a console
//      $('#example-console').text($(form).serialize()); 
//      console.log("Form submission", $(form).serialize()); 

                                                        // Prevent actual form submission
//      e.preventDefault();
                                                    });

                                                });
    $("#formasociacionmasivo").submit(function () {
        $('#loader').modal('show');
    });
</script>