<style>
    #masivoIconos td,
    th {
        padding: 30px;
    }
    .tnotifi{
        color: red;
        padding-left:  15px;
    }
</style>
<div class="loader" id="loader" hidden=""></div>
<div class="col-lg-2"></div>
<div class="container col-lg-8" style="margin-bottom: 200px; margin-top: -50px;">
    <hr style="border-top: 1px solid #eee0;">
    <h2 class="titulo-iz">Desasociar Tarjetas</h2>
    <?php if (isset($_GET['errordata'])) { ?>
        <div class="alert alert-info">
            <strong>No se ha seleccionado ningún producto</strong>
        </div>
    <?php } ?>
    <ul class="nav nav-tabs">
        <li><a href="/portal/llaveMaestra/asociacion">Asociación uno a uno</a></li>
        <li><a href="/portal/llaveMaestra/asociacionMasiva">Asociación masiva</a></li>
        <li class="active"><a data-toggle="tab" href="#Desasociacion">Desasociación</a></li>
    </ul>

    <!--    <div class="col-lg-6"></div>-->
    <div class="col-lg-3">
        <?php if ($errorpkllavero == 1) { ?>
            <div class="row"><label class="tnotifi"> Por favor seleccione un llavero.</label></div>
        <?php } ?>
        <form action="/portal/llaveMaestra/desasociacion/1" method="POST">

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
    <div class="tab-content">
        <div id="Desasociacion" class="tab-pane fade in active">
            <form method="POST" action="/portal/llaveMaestra/desasociaciontarj" id="formdesasociar">
                <div class="container col-lg-12">
                    <div class="grid">
                        <table class="table table-hover " id="tbldesasociacion">
                            <thead>
                                <tr>
                                    <th> 
                                        <div class="login-checkbox "  id="chkTodo">
                                            <input type="checkbox" id="chkMasivaAll">
                                            <span>
                                                <div class="">
                                                    <span class="login-checkbox-check">
                                                    </span>
                                                </div>
                                            </span>
                                        </div>
                                    </th>
                                    <th> Nombre </th>
                                    <th> T.D. </th>
                                    <th> No.Doc </th>
                                    <th> Producto </th>
                                    <th> No. Tarjeta </th>
                                    <th> Identificador </th>
                                    <th> Custodio </th>
                                    <th> Campaña </th>
                                    <th> Ciudad </th>
                                    <!--<th> Saldo Pendiente por gastar </th>-->
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($tarjetallavero as $value) { ?>
                                    <tr class="gradeC">
                                        <td>
                                            <div class="login-checkbox" onclick="" style="padding-top: 5px">
                                                <input name="data[]" class="chkmasiva" value="<?= $value['PK_TARJET_CODIGO'] ?>,<?= $value['PKTAR'] ?>,<?php echo $pk_llavero_codigo ?>" type="checkbox">
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
                                        <td><?= $value['NOMPRO'] ?></td>
                                        <td><?= $value['NUMTAR'] ?></td>
                                        <td><?= $value['IDENTIFICADOR'] ?></td>
                                        <td><?= $value['NOMCUSTODIO'] ?></td>
                                        <td><?= $value['NOMCAMPANA'] ?></td>
                                        <td><?= $value['CIUDAD'] ?></td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </form>
        </div>
        <div class="button col-md-4 col-md-push-4">
            <button class="spacing" data-toggle="modal" data-target="#ModalConfDes">DESASOCIAR</button>
        </div>
    </div>
</div>

<!-- Modal confirmacion desasociacion-->
<div class="modal fade" id="ModalConfDes" role="dialog" style="margin-top: 15%;"  data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content" style="border-radius:35px">

            <div class="modal-body" style="text-align: center;height: 230px;">

                <p  style="font-size:24px;color:#888686;font-weight: bold;padding-top: 25px">¿Desea desasociar estas tarjetas?
                </p>
                <label id="nomllavero" style="font-size: 18px;color: #366199;font-weight: bold;">
                    Las tarjetas serán desasociadas de su llavero
                </label>

                <div style="">
                    <div class="button col-sm-6" >
                        <button type="button" name="ACEPTAR" value="1" class="btn btn-default"  onclick="
                                        $('#formdesasociar').submit();" >S I</button>
                    </div>
                    <div class="button col-sm-6" >
                        <button type="button" name="CANCELAR" class="btn btn-default" data-dismiss="modal">N O</button>
                    </div>
                </div>
                <br>
            </div>
        </div>
    </div>
</div>
<?php if (isset($_GET['ok'])) { ?>
    <!-- Modal confirmacion recarga-->
    <div class="modal fade" id="ModalDesExitosa" role="dialog" style="margin-top: 15%;"  data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog">

            <!-- Modal content-->
            <div class="modal-content" style="border-radius:35px">

                <div class="modal-body" style="text-align: center;height: 200px;">

                    <p  style="font-size:24px;color:#888686;font-weight: bold;padding-top: 25px">Las tarjetas fueron desasociadas correctamente!
                    </p>
                    <div style="">
                        <div class="button col-sm-6 col-sm-push-3" >
                            <button name="aceptar" data-dismiss="modal" class="btn btn-default spacing">ACEPTAR</button>
                        </div>
                    </div>
                    <br>
                </div>
            </div>
        </div>
    </div>
<?php } ?>

<div class="col-lg-2" style=" /*margin-bottom: 200px; margin-top: -50px;*/"></div>
<?php if (isset($_GET['ok'])) { ?>
    <script>
        $('#ModalDesExitosa').modal('show');
    </script>
<?php } ?>
<script type="text/javascript">
    function onAllTh() {
        if (!$("#chkMasivaAll").prop("checked")) {
            $(".spnmasiva").each(function () {
                $(this).css("display", "inline");
            });
            $(".chkmasiva").each(function () {
                $(this).prop('checked', true);
            });
        } else {
            $(".spnmasiva").each(function () {
                $(this).css("display", "none");
            });
            $(".chkmasiva").each(function () {
                $(this).prop('checked', false);
            });
        }
    } ;
    
                            $(document).ready(function () {
                                                    var table = $('#tbldesasociacion').DataTable({
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
                                                    $('#formdesasociar tbody').on('change', 'input[type="checkbox"]', function () {
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
                                                    $('#formdesasociar').on('submit', function (e) {
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
    
    $("#formdesasociar").submit(function () {
        $('#loader').modal('show');
    });
    
    
</script>