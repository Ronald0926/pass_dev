<style>
    #masivoIconos td,th{
        padding: 30px;
    }
    .tnotifi{
        color: red;
        padding-left:  15px;
    }
    .lblsaldoabono{
        margin-top: 2%;
        margin-left: 25%;
        width: 50%;
        text-align: center;
        background-color: #e3e3e3;
        font-size: 15px;
        font-weight: bold;   
        padding: 8px;
        color: #888;
        border: 1px solid;
        border-color: #979797;
        border-radius: 25px;
    }
</style>
<div class="loader" id="loader" hidden=""></div>
<div class="col-lg-2" ></div>
<div class="container col-lg-8" style=" margin-bottom: 200px; margin-top: -50px;">
    <hr style="border-top: 1px solid #eee0;">
    <h2 class="titulo-iz">Abono Tarjetas</h2>
    <?php if (isset($_GET['errordata'])) { ?>
        <div class="alert alert-info">
            <strong>No se ha seleccionado ningún producto</strong>
        </div>
    <?php } ?>
    <ul class="nav nav-tabs">
        <li ><a href="/portal/llaveMaestra/abono">Abono Uno a Uno</a></li>
        <li class="active"><a data-toggle="tab" href="#solicitudMasiva" style="/*border-top-left-radius:20px;border-top-right-radius:20px;background-color:#19548e;color:#FFF;height:35px */">Abono masivo</a></li>
    </ul>
    <div class="col-lg-3">
        <form action="/portal/llaveMaestra/abonomasivoreturntarjellavero" method="POST">
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
        <div><label class="lblsaldoabono"><span>$ <?= number_format($saldo_llavero, 0, ',', '.'); ?></span></label></div>
    </div>
    <div class="tab-content">
        <div id="solicitudMasiva" class="tab-pane fade in active">
            <form method="POST" action="/portal/llaveMaestra/abonomasivodatath" id="formabonomasivo">
                <input type="text" name="pk_llavero_codigo" id='pk_llavero_codigo' value="<?= $pk_llavero_codigo ?>" hidden>
                <div class="container col-lg-12">
                    <div class="grid">
                        <table class="table table-hover" id="tableabono">
                            <thead>
                                <tr>
                                    <th>
                                        <div class="login-checkbox "  id="chkTodo">
                                            <input type="checkbox" id="chkMasivaAll">
                                            <span>
                                                <div class="">
                                                    <span class="login-checkbox-check spnchktodo">
                                                    </span>
                                                </div>
                                            </span>
                                        </div>

                                    </th>
                                    <th> Nombre </th>
                                    <th> T.D. </th>
                                    <th> No.Doc </th>
                                    <th> Producto </th>
                                    <th> Número Tarjeta </th>
                                    <th> Identificador </th>
                                    <th> Custodio </th>
                                    <th> Campaña </th>
                                    <th> Ciudad </th>
                                    <!--<th> Fecha de dispersión </th>-->
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $documento;
                                //$pedidoAbono = $this->session->userdata("pedidoAbono");
                                $pedidoAbono = $_SESSION['pedidoAbono'];
                                foreach ($tarjetallavero as $value) {
                                    ?>
                                    <tr class="gradeC">
                                        <td>
                                            <div class="login-checkbox"  style="padding-top: 5px">
                                                <input name="tarjetasabono[]" class="chkmasiva" value="<?= $value['PK_TARJET_CODIGO'] ?>"  type="checkbox">
                                                <span>
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
                    <div class="button col-md-4 col-md-push-4">
                        <button type="submit" class="spacing">SIGUIENTE</button>
                    </div>
                </div>

            </form>

        </div>
    </div>
</div>
<?php
if (isset($_GET['abonoOK'])) {
//    $correodest = $this->session->userdata('CORREO_DES_ABONO');
    $correodest = $_SESSION["CORREO_DES_ABONO"];
    ?>
    <!-- Modal error-->
    <div class="modal fade" id="Modalcodauto" role="dialog" style="margin-top: 15%;"  data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog">

            <!-- Modal content-->
            <div class="modal-content" style="border-radius:35px">
                <button class="btn_cerrar_modal" data-dismiss="modal"></button>
                <div class="modal-body" style="text-align: center;height: 270px;">
                    <form  method="POST" action="/portal/llaveMaestra/verificar_codigo_abono_masivo" id="formverificarcodigo">
                        <div class="modal-header">
                            <h5 style="color: #366199;font-size: 20px;font-weight: bold; ">Código de confirmacón</h5>
                        </div>
                        <p  style="font-size:15px;color:#888686;font-weight: bold;padding-top: 5px">Hemos enviado el código de confirmación a su correo electrónico <?php echo $correodest ?> <!--o como SMS-->
                        </p>
                        <?php if (isset($_GET['error'])) { ?>
                            <label style="color: #FF0000" class="oblique">Código incorrecto </label>
                        <?php } echo '<br>' ?>

                        <input type="text" name="codigoconfirmacion" style="width: 60%" placeholder="Digite código de confirmacón"  required>


                        <div style="">
                            <div class="button col-sm-6 col-sm-push-3" >
                                <button type="submit" name="CONFCARGA" value="1" class="btn btn-default spacing">ABONAR</button>
                            </div>
                        </div>
                        <br>
                    </form>
                </div>
            </div>
        </div>
    </div>
<?php } ?>
<?php if (isset($_GET['abosuccessful'])) { ?>
    <!-- Modal confirmacion recarga-->
    <div class="modal fade" id="ModalAbonoExitoso" role="dialog" style="margin-top: 15%;"  data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog">

            <!-- Modal content-->
            <div class="modal-content" style="border-radius:35px">

                <div class="modal-body" style="text-align: center;height: 200px;">

                    <p  style="font-size:24px;color:#888686;font-weight: bold;padding-top: 25px">El abono fue realizado exitosamente
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
<?php if (isset($_GET['errorlimiteabono'])) { ?>
    <!-- Modal confirmacion recarga-->
    <div class="modal fade" id="ModalErrorlimite" role="dialog" style="margin-top: 15%;"  data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog">

            <!-- Modal content-->
            <div class="modal-content" style="border-radius:35px">

                <div class="modal-body" style="text-align: center;height: 200px;">

                    <p  style="font-size:24px;color:#888686;font-weight: bold;padding-top: 25px">El monto excede el valor limite de abonos diarios permitidos.
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
<?php if (isset($_GET['errorsaldoinsu'])) { ?>
    <!-- Modal error-->
    <div class="modal fade" id="ModalerrorTX" role="dialog" style="margin-top: 15%;"  data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog">

            <!-- Modal content-->
            <div class="modal-content" style="border-radius:35px">

                <div class="modal-body" style="text-align: center;height: 250px;">
                    <div class="modal-header">
                        <h5 style="color: #366199;font-size: 20px;font-weight: bold; ">Fondos insuficientes</h5>
                    </div>
                    <p  style="font-size:24px;color:#888686;font-weight: bold;padding-top: 25px">
                        Saldo insuficiente para realizar la transacción
                    </p>
                    <label id="nomllavero" style="font-size: 18px;color: #366199;font-weight: bold;"></label>

                    <div style="">
                        <div class="button col-sm-6 col-sm-push-3" >
                            <button type="button" name="CANCELAR" class="btn btn-default spacing" data-dismiss="modal">ACEPTAR</button>
                        </div>
                    </div>
                    <br>
                </div>
            </div>
        </div>
    </div>
<?php } ?>
<div class="col-lg-2" ></div>
<link type="text/css" href="//gyrocode.github.io/jquery-datatables-checkboxes/1.2.11/css/dataTables.checkboxes.css" rel="stylesheet" />
<script type="text/javascript" src="//gyrocode.github.io/jquery-datatables-checkboxes/1.2.11/js/dataTables.checkboxes.min.js"></script>
<script type="text/javascript">
                                                var errorsaldoinsu = <?php if (isset($_GET['errorsaldoinsu'])) {
    echo "1;";
} else {
    echo "0;";
} ?>
                                                var errorlimite = <?php if (isset($_GET['errorlimiteabono'])) {
    echo "1;";
} else {
    echo "0;";
} ?>
                                                var abonook = <?php if (isset($_GET['abonoOK'])) {
    echo "1;";
} else {
    echo "0;";
} ?>
                                                var abosuccessful = <?php if (isset($_GET['abosuccessful'])) {
    echo "1;";
} else {
    echo "0;";
}
?>
                                                $('#tableabono').on('page.dt', function () {
                                                    $("#chkMasivaAll").prop('checked', false);
                                                    $(".spnchktodo").css("display", "none");
                                                });
                                                if (errorsaldoinsu == 1) {
                                                    $('#ModalerrorTX').modal('show');
                                                }
                                                if (errorlimite == 1) {
                                                    $('#ModalErrorlimite').modal('show');
                                                }
                                                if (abonook == 1) {
                                                    $('#Modalcodauto').modal('show');
                                                }
                                                if (abosuccessful == 1) {
                                                    $('#ModalAbonoExitoso').modal('show');
                                                }
                                                function onAllTh() {
                                                    var referidos = [];
                                                    var valuein = $("#tarAbonoMa").val();
                                                    if (!$("#chkMasivaAll").prop("checked")) {
                                                        $(".spnmasiva").each(function () {
                                                            $(this).css("display", "inline");
                                                        });
                                                        $(".chkmasiva").each(function () {
                                                            $(this).prop('checked', true);
                                                            var et = $(this).val();
                                                            if (referidos == '') {
                                                                referidos.push(et);
//                    referidos = et;
                                                            } else {
//                    referidos = referidos + "," + et;
                                                                referidos.push(et);
                                                            }
                                                        });
                                                        $("#tarAbonoMa").val(referidos);
                                                    } else {
                                                        $(".spnmasiva").each(function () {
                                                            $(this).css("display", "none");
                                                        });
                                                        $(".chkmasiva").each(function () {
                                                            $(this).prop('checked', false);
                                                        });
                                                        $("#tarAbonoMa").val('');
                                                    }
                                                }
                                                ;

                                                function datatar(varabono) {
                                                    var referidos = $("#tarAbonoMa").val();
                                                    var checkbox = document.getElementById(varabono);
                                                    if (checkbox.checked == true) {
                                                        referidos = referidos + "," + varabono;
                                                    } else {
                                                        var siesweb = referidos.startsWith(varabono);
                                                        if (siesweb) {
                                                            var quitar = varabono + ",";
                                                            referidos = referidos.replace(quitar, "");
                                                            $("#tarAbonoMa").val(referidos);
                                                        } else {
                                                            var quitar = "," + varabono;
                                                            referidos = referidos.replace(quitar, "");
                                                            $("#tarAbonoMa").val(referidos);
                                                        }
                                                    }
                                                }
                                                ;



                                                $(document).ready(function () {
                                                    var table = $('#tableabono').DataTable({
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
                                                    $('#formabonomasivo tbody').on('change', 'input[type="checkbox"]', function () {
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
                                                    $('#formabonomasivo').on('submit', function (e) {
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
                                                $("#formverificarcodigo").submit(function () {
                                                    $('#loader').modal('show');
                                                });

</script>