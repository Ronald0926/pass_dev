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
<div class="col-lg-2"></div>
<div class="container col-lg-8" style="margin-bottom: 200px; margin-top: -50px;">
    <hr style="border-top: 1px solid #eee0;">
    <h2 class="titulo-iz">Asociación Tarjetas</h2>
    <ul class="nav nav-tabs">
        <li class="active"><a data-toggle="tab" href="#solicitudUno" style="/*border-top-left-radius:20px;border-top-right-radius:20px;background-color:#fdc500;color:#19548e;height:35px*/">Asociación uno a uno</a></li>
        <li><a href="/portal/llaveMaestra/asociacionMasiva">Asociación masiva</a></li>
        <li><a href="/portal/llaveMaestra/desasociacion">Desasociación</a></li>
    </ul>
    <div class="col-lg-3">
            <form action="/portal/llaveMaestra/returnTarjetasAsociacion/1" method="POST">
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
<!--    <div class="col-lg-6"></div>-->
    <form method="POST" action="/portal/llaveMaestra/asociacion/1">
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
                        <table class="table table-hover daos_datagrid">
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
                                    //    $documento = $value['DOC'];
                                        ?>

                                        <tr class="gradeC">
                                            <td>
                                                <div class="login-checkbox" onclick="" style="padding-top: 5px">
                                                    <input name="usuarios[]" value="<?= $value['CODPROD'] ?>,<?= $value['CODTH'] ?>,<?= $value['CODTAR'] ?>" type="checkbox">
                                                    <span id="<?= $value['CODIGOORDEN'] ?>">
                                                        <div class="">
                                                            <span class="login-checkbox-check">
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
                                                    <input name="usuarios[]" value="<?= $value['CODPROD'] ?>,<?= $value['CODTH'] ?>,<?= $value['CODTAR'] ?>" type="checkbox">
                                                    <span id="<?= $value['CODIGOORDEN'] ?>">
                                                        <div class="">
                                                            <span class="login-checkbox-check">
                                                            </span>
                                                        </div>
                                                    </span>
                                                </div>
                                            </td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td><?= $value['NUMTAR'] ?></td>-->
                                        <?php //} ?>
                                        <td><?= $value['NOMPRO'] ?></td>
                                        <td><?= $value['IDENTIFICADOR'] ?></td>
                                        </tr>
                                    <?php } ?>
                            </tbody>
                        </table>

                    </div>
                    <div class="button col-md-4 col-md-push-4">
                        <button type="submit" class="spacing">ASOCIAR</button>
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

    <script type="text/javascript">
       var errorpkcodigo = <?php if (isset($errorpkllavero)) {    echo "1;";} else {    echo "0;";}?>
       if (errorpkcodigo === 1) {
        $(".tnotifi").show();
        }
    </script>