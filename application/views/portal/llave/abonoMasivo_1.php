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
<div class="col-lg-2" ></div>
<div class="container col-lg-8" style=" margin-bottom: 200px; margin-top: -50px;">
    <hr style="border-top: 1px solid #eee0;">
    <h2 class="titulo-iz">Abono Tarjetas</h2>
    <ul class="nav nav-tabs">
        <li ><a href="/portal/llaveMaestra/abono">Abono Uno a Uno</a></li>
        <li class="active"><a data-toggle="tab" href="#solicitudMasiva" style="/*border-top-left-radius:20px;border-top-right-radius:20px;background-color:#19548e;color:#FFF;height:35px */">Abono masivo</a></li>
    </ul>
    <div class="col-lg-3">
        <form action="/portal/llaveMaestra/abonomasivoreturntarjellavero" method="POST">
            <?php if ($errorpkllavero == 1) { ?>
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
            <form method="POST" action="/portal/llaveMaestra/abonoMasivo">
                <input type="text" name="pk_llavero_codigo" id='pk_llavero_codigo' value="<?= $pk_llavero_codigo ?>" hidden>
                <input name="tarjetasabonoh[]" id="tarAbonoMa" value="">

                <div class="container col-lg-12">
                    <div class="grid">
                        <table class="table table-hover daos_datagrid" id="tableabono">
                            <thead>
                                <tr>
                                    <th>
                                        <div class="login-checkbox " onclick="onAllTh()" id="chkTodo">
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
                                            <div class="login-checkbox" onclick="datatar(<?= $value['PK_TARJET_CODIGO'] ?>)" id="<?= $value['PK_TARJET_CODIGO'] ?>" style="padding-top: 5px">
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
                                        <td><?= $value['NOMCUSTODIO'] ?></td>
                                        <td><?= $value['NOMCAMPANA'] ?></td>
                                        <td><?= $value['CIUDAD'] ?></td>
                                        <!--<td><input  name="fecha/<?= $value['CUENTA'] ?>/<?= $value['ENTTAH'] ?>" type="date" min="<?= date("Y-m-d") ?>" class="tFecha" /></td>-->
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
<div class="container" style="margin-top: 15%;">
    <!-- Modal -->
    <div class="modal fade" id="myModalAbonoMasivo" role="dialog" style="    margin-top: 15%;">
        <div class="modal-dialog">

            <!-- Modal content-->
            <div class="modal-content" style="border-radius:35px;">
                <div class="modal-body" style="text-align: center; ">
                    <div>
                        <br>    
                        <p style="font-size:18px;color:#888686;font-weight: bold">¡Abono exitoso!</p>
                        <br>
                        <br>
                        <div class="button" style="width:100px;margin-left:40%">
                            <button style=""type="button" name="close" class="btn btn-default" data-dismiss="modal">ACEPTAR</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="col-lg-2" ></div>
<script type="text/javascript">

    $('#tableabono').on('page.dt', function () {
        $("#chkMasivaAll").prop('checked', false);
        $(".spnchktodo").css("display", "none");
    });


    function onAllTh() {
        var referidos =[];
        var valuein= $("#tarAbonoMa").val();
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
        }else{
            var siesweb=referidos.startsWith(varabono);
            if(siesweb){
                var quitar = varabono+",";
                referidos = referidos.replace(quitar,"");
                $("#tarAbonoMa").val(referidos);
            }else{
            var quitar = ","+varabono;
            referidos = referidos.replace(quitar,"");
            $("#tarAbonoMa").val(referidos);
            }
        }
    }
    ;
</script>