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
        margin-top: 10%;
        font-size: 22px;
        font-weight: bold;   
        padding: 4px 25px 4px 25px;
        color: #366199;
        border: 1px solid;
        border-color: #366199;
        border-radius: 25px;
    }
    .input_carga{
        width: 250px;
        font-size: 15px;  
        padding: 8px 8px 8px 15px;
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
</style>
<div class="loader" id="loader" hidden=""></div>
<div class="col-lg-1"></div>
<div class="container col-lg-10" style=" margin-bottom: 200px; margin-top: -50px;">
    <hr style="border-top: 1px solid #eee0;">
    <div class="row">
        <div style="float:left;width: 90%">
            <h2 class="titulo-iz" >Reverso y devolución de tarjetas > Valor a reversar </h2>
        </div>
    </div>
    <div class="col-lg-9">
        <h3 class="subtitulo-iz" style="padding-bottom: 0px"> Reverso conjunto</h3>
        <!---->        <div style="float:left"><input class="input_carga oblique  MonReverso" type="text" data-type="currency" name="montoabono" id="montoabono" placeholder=" $ Digite valor para reversar" /></div>
        <div class="button" style="float:left;width: ">
            <button class="spacing" style="width: 170px;margin: 20px 0px 0px 15px" id="apreversocon"> Aplicar</button>
        </div>
    </div>
    <div class="tab-content">
        <div id="solicitudUno" class="tab-pane fade in active">
            <form method="POST" action="/portal/llaveMaestra/reversosaldotarjeta" id="formReverso">
                <div class="container col-lg-12">
                    <div class="grid">
                        <table class="table table-hover daos_datagrid">

                            <thead>
                                <tr>
                                    <th> Nombre </th>
                                    <th> T.D. </th>
                                    <th> No.Doc </th>
                                    <th> Producto </th>
                                    <th> Numero Tarjeta </th>
                                    <th> Identificador </th>
                                    <th> Custodio </th>
                                    <th> Campaña </th>
                                    <th> Ciudad </th>
                                    <th> Saldo Actual </th>
                                    <th> Abonado </th>
                                    <th> Valor reverso </th>
                                    <!--<th> Fecha de dispersión </th>-->
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                foreach ($tarjetaEntidad as $valuella) {
                                    ?>
                                    <tr class="gradeC">

                                        <td><?= $valuella['NOMTAR'] ?></td>
                                        <td><?= $valuella['ABR'] ?></td>
                                        <td><?= $valuella['DOC'] ?></td>
                                        <td><?= $valuella['NOMPRO'] ?></td>
                                        <td><?= $valuella['NUMTAR'] ?></td>
                                        <td><?= $valuella['IDENTIFICADOR']?></td>
                                        <td><?= $valuella['NOMCUSTODIO'] ?></td>
                                        <td><?= $valuella['NOMCAMPANA'] ?></td>
                                        <td><?= $valuella['CIUDAD'] ?></td>
                                        <td>$ <?= number_format($valuella['SALDO'], 0, ',', '.'); ?></td>
                                        <td>$ <?= number_format($valuella['MONTO_ABONO'], 0, ',', '.'); ?></td>
                                        <td><input type="text" name="monto/<?= $valuella['CODTH'] ?>/<?= $valuella['CODPROD'] ?>/<?= $valuella['PK_TARJET_CODIGO'] ?>/<?= $valuella['PKTAR'] ?>/<?=$valuella['ABOTAR_CODIGO']?>" id="<?= $valuella['PKTAR'] ?>" data-type="currency" placeholder="Digite la cantidad" class="MonReverso valreverso " onfocusout="sumar(this.value);" required/></td>

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
                                    <th colspan="4" style="text-align:right" class="label_carga">Total reverso:</th>
                                    <th id="totalabonotable" class="thtotal"></th>
                                </tr>
                            </tfoot>
                        </table>

                    </div>
                    <div class="button col-md-2 col-md-push-5">
                        <button  class="spacing" type="submit" id="btnSubmitReverso">REVERSAR<span class="glyphicon glyphicon-chevron-right"></span></button>
                        <br>
                        <br>
                        <div class=" linkgenerico spacing">
                            <a href="/portal/llaveMaestra/reverso"><span class="glyphicon glyphicon-chevron-left"></span>VOLVER</a>
                        </div> 
                    </div>
                </div>

            </form>
        </div>
    </div>
</div>


<div class="col-lg-1"></div>

<!-- Modal confirmacion reverso-->
<div class="modal fade" id="myModalReverso" role="dialog" style="margin-top: 15%;"  data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content" style="border-radius:35px">

            <div class="modal-body" style="text-align: center;height: 230px;">

                <p  style="font-size:24px;color:#888686;font-weight: bold;padding-top: 25px">¿Esta seguro de realizar el reverso?
                </p>
                <label id="nomllavero" style="font-size: 18px;color: #366199;font-weight: bold;"></label>

                <div style="">
                    <div class="button col-sm-6" >
                        <button type="button" name="ACEPTAR" value="1" class="btn btn-default"  onclick="
                                $('#formReverso').submit();" >A C E P T A R</button>
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

<script type="text/javascript">
    $("#formReverso").submit(function () {
        $('#loader').modal('show');
    });
    var boton = document.getElementById("btnSubmitReverso");
    boton.onclick = function (e) {
//        e.preventDefault();
        var validado = $("#formReverso").valid();
        if (validado) {
            e.preventDefault();
            $('#myModalReverso').modal('show');
        }else{
            e.preventDefault();
            $('#loader').modal('hide');
        }
//         var v=0;
//         var x = $('.MonReverso');
//         for (j = 1; j < x.length; j++) {
//             var id = $(x[j]).attr("id");
//             if(x[j].value==='' || x[j].value==='undefined'){
//                 e.preventDefault();
////                 $("#"+id).prop('required',true);
//                 v++;
//             }
//         }
//         if(v===0){
//             $('#myModalReverso').modal('show');
//         }

    };
    var boton = document.getElementById("apreversocon");
    boton.onclick = function (e) {
        var x = $('.MonReverso');
        var suma = 0;
        for (j = 1; j < x.length; j++) {
            var porciones = x[0].value.split('.');
            var montom = CifrasEnLetras.dejarSoloCaracteresDeseados(porciones[0], "0123456789");
            x[j].value = x[0].value;
            suma += parseInt(montom);
        }
        $('#tfoot').show();
        $("#totalabonotable").html('$ ' + number_format(suma, 2, ',', '.'));

    };

    function sumar(valor) {

        var sumatoria = 0;
        var y = $('.valreverso');
        for (j = 0; j < y.length; j++) {
            var porciones = y[j].value.split('.');
            valor = CifrasEnLetras.dejarSoloCaracteresDeseados(porciones[0], "0123456789");
            if (!isNaN(parseInt(valor))) {
                sumatoria += parseInt(valor);
            }

        }
        $('#tfoot').show();
        $("#totalabonotable").html('$ ' + number_format(sumatoria, 2, ',', '.'));
    }

</script>