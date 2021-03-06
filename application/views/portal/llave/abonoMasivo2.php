<style>
    #masivoIconos td,
    th {
        padding: 30px;
    }
    .lblsaldoabono{
        margin-top: 2%;
        width: 100%;
        text-align: center;
        background-color: #e3e3e3;
        font-size: 15px;
        font-weight: bold;   
        padding: 8px;
        color: #888;
        /* border: 1px solid;
         border-color: #979797;*/
        border-radius: 25px;
    }
    .label_carga{
        margin-top: 5%;
        font-size: 22px;
        font-weight: bold;   
        padding: 4px 25px 4px 25px;
        color: #366199;
        border: 1px solid;
        border-color: #366199;
        border-radius: 25px;
    }
    .input_carga{
        width: 220px;
        font-size: 15px;  
        padding: 8px 9px 8px 9px;
        color: #366199;
        border: 1px solid;
        border-color: #366199;
        border-radius: 25px;
    }
    .thtotal{
        background-color: #FDC500;
        border: none;
        border-radius: 25px 0px;  
        padding: 9px 0px 9px 20px !important;
    }
    ::-webkit-input-placeholder { /* Edge */
        color: #366199;
    }

    :-ms-input-placeholder { /* Internet Explorer 10-11 */
        color: #366199;
    }

    ::placeholder {
        color: #366199;
    }
    .red{
        color: red; 
    }
    .sel{
        /*margin-top: 70px;*/
        margin-top: 15px;
        font-size: 15px;
        /*        font-weight: bold;   */
        padding: 8px 15px 8px 15px;
        color: #366199;
        border: 1px solid;
        border-color: #366199;
        border-radius: 25px;
    }
    .sub-iz {
        text-align: left;
        font-size: 20px;
        color: #366199;
        font-weight: bold;
        padding-bottom: 40px;
    }
</style>
<div class="loader" id="loader" hidden=""></div>
<div class="col-lg-1"></div>
<div class="container col-lg-10" style=" margin-bottom: 200px; margin-top: -50px;">
    <hr style="border-top: 1px solid #eee0;">
    <div class="row">
        <div style="float:left;width: 25%">
            <h2 class="titulo-iz" >Abono Masivo Tarjetas</h2>
        </div>
        <div  style="float:left;text-align: center">
            <div class="label_carga">
                <?php echo $nombrellaveroselect != "" ? $nombrellaveroselect : 'Error' ?>
            </div>
            <label class="lblsaldoabono"><span>$ <?= number_format($saldo_llavero, 0, ',', '.'); ?></span></label>
        </div>
    </div>
    <ul class="nav nav-tabs">
        <li class="active"><a data-toggle="tab" href="#abonomasivo" style="/*border-top-left-radius:20px;border-top-right-radius:20px;background-color:#fdc500;color:#19548e;height:35px*/">Abono Masivo Tarjetas</a></li>
    </ul>
    <div class="col-lg-12">
        <div class="col-lg-3">
            <h3 class="sub-iz" style="padding-bottom: 0px"> Abono conjunto</h3>
            <div style="float:left"><input class="input_carga oblique MonAbono" type="text" name="montoabono" data-type="currency" id="montoabono" placeholder=" $ Digite valor para abonar" /></div>
            <div class="button" style="float:left; ">
                <button class="spacing" style="width: 170px;margin: 20px 0px 0px 15px" id="apabonocon"> Aplicar</button>
            </div>

        </div>
        <div class="col-lg-3">
            <h3 class="sub-iz" style="padding-bottom: 0px"> Concepto conjunto</h3>
            <select name="conceptoall" id="conceptosall" class="sel">
                <option value=""> Seleccione la opci??n</option>
                <?php foreach ($conceptos as $key => $value) { ?>
                    <option value="<?= $value['PK_CONCEPTO_CODIGO'] ?>"> <?= $value['NOMBRE'] ?></option>
                <?php } ?>
            </select>
            <div class="button" >
                <button class="spacing" style="width: 170px;margin: 20px 0px 0px 15px" id="apconall"> Aplicar</button>
            </div>
            <!--<h5 class="aplicar" style="padding-left: 15%;padding-top: 0.2em;">Aplicar concepto a todos</h5>-->

        </div>
        <div class="col-lg-3">
            <div class="thtotal" style="float:right; text-align: right;">
                <h5 style="margin: 0px;padding: 0px 30px 0px 10px"> Total para abonos</h5>
                <label style="padding-right: 30px;">$ <?= number_format($saldo_llavero, 0, ',', '.'); ?></label>
                <h5 style="margin: 0px;padding: 0px 30px 0px 10px"> Sumatoria abonos</h5>
                <label id="sumatoria" style="padding-right: 30px;">$ <?= number_format($sumatoriaabono, 0, ',', '.'); ?></label>
                <h5 style="margin: 0px;padding: 0px 30px 0px 10px"> Valor de diferencia</h5>
                <label id="diferencia" style="padding-right: 30px;">$ <?= number_format($difabono, 0, ',', '.'); ?></label>

            </div>
        </div>


    </div>
    <div class="tab-content">
        <div id="abonomasivo" class="tab-pane fade in active">
            <form method="POST" action="/portal/llaveMaestra/abonomasivofin" id="formabonomasivo">
                <div class="container col-lg-12">
                    <div class="grid">
                        <table class="table table-hover " id="tableabonomasivo">

                            <thead>
                                <tr>
                                    <th> Nombre </th>
                                    <th> T.D. </th>
                                    <th> No.Doc </th>
                                    <th> Producto </th>
                                    <th> N??mero Tarjeta </th>
                                    <th> Custodio </th>
                                    <th> Campa??a </th>
                                    <th> Ciudad </th>
                                    <th> Concepto </th>
                                    <th> Valor abono </th>
                                    <!--<th> Fecha de dispersi??n </th>-->
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                foreach ($tarjetallavero as $valuella) {
                                    ?>
                                    <tr class="gradeC">

                                        <td><?= $valuella['NOMTAR'] ?></td>
                                        <td><?= $valuella['ABR'] ?></td>
                                        <td><?= $valuella['DOC'] ?></td>
                                        <td><?= $valuella['NOMPRO'] ?></td>
                                        <td><?= $valuella['NUMTAR'] ?></td>
                                        <td><?= $valuella['NOMCUSTODIO'] ?></td>
                                        <td><?= $valuella['NOMCAMPANA'] ?></td>
                                        <td><?= $valuella['CIUDAD'] ?></td>
                                        <td>
                                            <div class="select form-group slconcepto" style="width: 195px !important;margin-bottom: 0px;margin-top: 0px">
                                                <select name="concepto/<?= $valuella['CODTH'] ?>/<?= $valuella['CODPROD'] ?>/<?= $valuella['PK_TARJET_CODIGO'] ?>/<?= $valuella['PKTAR'] ?>" id="<?= $valuella['PKTAR'] ?>" class="clasconceptos" required>
                                                    <option value=""> Seleccione la opci??n</option>
                                                    <?php foreach ($conceptos as $key => $value) { ?>
                                                        <option value="<?= $value['PK_CONCEPTO_CODIGO'] ?>"> <?= $value['NOMBRE'] ?></option>
                                                    <?php } ?>
                                                </select>
                                                <div class="divop<?= $valuella['PKTAR'] ?>"> Seleccione la opci??n </div>
                                            </div>

                                        </td>
                                        <td><input  type="text" name="monto/<?= $valuella['CODTH'] ?>/<?= $valuella['CODPROD'] ?>/<?= $valuella['PK_TARJET_CODIGO'] ?>/<?= $valuella['PKTAR'] ?>" placeholder="Digite la cantidad" class="MonAbono valabono" data-type="currency" onfocusout="sumar(this.value);" value="<?= $saldoporth ?>" required/></td>
                                       
                                    </tr>
                                <?php } ?>
                            </tbody>
                            <tfoot hidden id="tfoot">
                                <tr>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th colspan="4" style="text-align:right" class="label_carga">Total abono:</th>
                                    <th id="totalabonotable" class="thtotal"></th>
                                </tr>
                            </tfoot>
                        </table>

                    </div>
                    <div class="button col-md-2 col-md-push-5">
                        <button type="submit" class="spacing" id="submitabono">ABONAR<span class="glyphicon glyphicon-chevron-right"></span></button>
                        <br>
                        <br>
                        <div class=" linkgenerico spacing">
                            <a href="/portal/llaveMaestra/abonoMasivo"><span class="glyphicon glyphicon-chevron-left"></span>VOLVER</a>
                        </div> 
                    </div>
                </div>

            </form>
        </div>
    </div>
</div>


<div class="col-lg-1"></div>

<div class="container" style="margin-top: 15%;">
    <!-- Modal -->
    <div class="modal fade" id="myModalAbono" role="dialog" style="    margin-top: 15%;">
        <div class="modal-dialog">

            <!-- Modal content-->
            <div class="modal-content" style="border-radius:35px;">
                <div class="modal-body" style="text-align: center; ">
                    <div>
                        <br>
                        <p style="font-size:18px;color:#888686;font-weight: bold">??Abono exitoso!</p>
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

<script type="text/javascript">
    var table;
    $("#formabonomasivo").submit(function () {
        $('#loader').modal('show');
    });

    var boton = document.getElementById("apabonocon");
    boton.onclick = function (e) {
        var x = $('.MonAbono');
        var suma = 0;
        for (j = 1; j < x.length; j++) {
            var porciones = x[0].value.split('.');
            var montom = CifrasEnLetras.dejarSoloCaracteresDeseados(porciones[0], "0123456789");
            x[j].value = x[0].value;
            suma += parseInt(montom);
        }
        // Update footer mostrar foot datatables
        var saldollavero =<?php echo $saldo_llavero ?>;
        var diferencia = saldollavero - suma;
        if (diferencia < 0) {
            $("#submitabono").attr("disabled", true);
            $("#diferencia").addClass("red");
        } else {
            $('#submitabono').removeAttr("disabled");
            $("#diferencia").removeClass('red');
        }
        $('#tfoot').show();
        $("#totalabonotable").html('$ ' + number_format(suma, 2, ',', '.'));
        $("#diferencia").text('$ ' + number_format(diferencia, 2, ',', '.'));
        $("#sumatoria").text('$ ' + number_format(suma, 2, ',', '.'));

    };

    function sumar(valor) {

        var sumatoria = 0;
        var y = $('.valabono');
        for (j = 0; j < y.length; j++) {
            var porciones = y[j].value.split('.');
            valor = CifrasEnLetras.dejarSoloCaracteresDeseados(porciones[0], "0123456789");
            if (!isNaN(parseInt(valor))) {
                sumatoria += parseInt(valor);
            }

        }
        var saldollavero =<?php echo $saldo_llavero ?>;
        var diferencia = saldollavero - sumatoria;
        if (diferencia < 0) {
            $("#submitabono").attr("disabled", true);
            $("#diferencia").addClass("red");
        } else {
            $("#diferencia").removeClass('red');
            $('#submitabono').removeAttr("disabled");
        }
        $("#diferencia").text('$ ' + number_format(diferencia, 2, ',', '.'));
        $("#sumatoria").text('$ ' + number_format(sumatoria, 2, ',', '.'));
        $('#tfoot').show();
        $("#totalabonotable").html('$ ' + number_format(sumatoria, 2, ',', '.'));
    }


    $(document).ready(function () {
        var filas =<?php echo $cantfilas ?>;
        table = $('#tableabonomasivo').DataTable({
            "bJQueryUI": true,
            "bSort": false,
            "bPaginate": true,
            "pageLength": filas,
            "sPaginationType": "full_numbers",
            "oLanguage": {
                "sProcessing": "Procesando...",
                "sLengthMenu": "Mostrar _MENU_ registros",
                "sZeroRecords": "No se encontraron resultados",
                "sEmptyTable": "Ning??n dato disponible en esta tabla",
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
                    "sLast": "??ltimo",
                    "sNext": "Siguiente",
                    "sPrevious": "Anterior"
                },
                "oAria": {
                    "sSortAscending": ": Activar para ordenar la columna de manera ascendente",
                    "sSortDescending": ": Activar para ordenar la columna de manera descendente"
                }
            }, "dom": 'Bfrtip',
            buttons: [
                {
                    extend: 'pdfHtml5',
                    exportOptions: {
                        modifier: {
                            page: 'current'
                        }
                    }
                },
                {extend: 'excelHtml5',
                    customize: function (xlsx) {
                        var sheet = xlsx.xl.worksheets['sheet1.xml'];

                        $('row c[r^="C"]', sheet).attr('s', '2');
                    }

                }
            ]

        });


    });


    var boton = document.getElementById("apconall");
    boton.onclick = function (e) {
        var multipleValues = $("#conceptosall").val();
        var textselected = $('select[name="conceptoall"] option:selected').text();
        var x = $('.clasconceptos');
        for (j = 0; j < x.length; j++) {
            var so = x[j].selectedIndex;
            var id = $(x[j]).attr("id");
            var sele=$('#' + id + 'option:selected').text();
//           if(parseInt(multipleValues)===so){
            if (so !== 0) {
                if (parseInt(multipleValues) !== so) {
                    $('#' + id + ' option:selected').removeAttr('selected');
                    
                    $('select#'+ id).prop('selectedIndex', multipleValues);
//                    $('#' + id + ' option').filter(function () {
//                                return $.trim($(this).val()) == multipleValues;
//                            }).attr('selected', true);
                            $('.divop' + id).text(textselected);
                }
            } else {
                if(so===0){
                    $('#' + id + ' option:selected').removeAttr('selected');
                }
                $('select#'+ id).prop('selectedIndex', multipleValues);
//                $('#' + id + ' option').filter(function () {
//                            return $.trim($(this).val()) == multipleValues;
//                        }).attr('selected', true);
                $('.divop' + id).text(textselected);
            }

//               $("#conceptos").attr('selectedIndex', '0').find("option:selected").removeAttr("selected");
//           }




//           selectByText(multipleValues);
//           $('.divop').text(textselected);
//           $(".clasconceptos").prop("selected", false);
        }
    };
    $('body').on('click', '#chkconcepto span', function () {
        var indiceDatos = $('#conceptos')[0].selectedIndex;
        var x = $('.slconcepto');
        for (j = 1; j < x.length; j++) {
            var child = $(this).children();
            $('#conceptos')[0].selectedIndex = indiceDatos;
            x[j].value = x[0].value;
        }

    });

    function selectByText(txt) {
        $('.clasconceptos option')
                .filter(function () {
                    return $.trim($(this).val()) == txt;
                })
                .attr('selected', true);
    }
</script>