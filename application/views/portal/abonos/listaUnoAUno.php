<div style=" margin-bottom: 200px; margin-top: -50px;">
    <div class="container">
        <hr style="border-top: 1px solid #eee0;">
        <h2 class="titulo-iz">Solicitud de Abonos</h2>
        <ul class="nav nav-tabs">
            <li class="active"><a data-toggle="tab" href="#solicitudUno">Solicitud Uno a Uno</a></li>
            <li><a href="/portal/abonos/abonoMasivo">Solicitud Masiva</a></li>
        </ul>

        <div class="tab-content">
            <div id="solicitudUno" class="tab-pane fade in active">
                <h3 class="subtitulo-iz">Tarjeta - habiente</h3>
                <div class="container">
                    <form action="/portal/abonos/unoAUno<?= isset($_GET['sol'])?'/?sol='.$_GET['sol']:''?>" method="POST">
                        <input type="hidden" name="referidosestado" id="referidosestado" value="">
                        <input value="<?= $_GET['sol'] ?>" name="pksolicitudPrepepdido" hidden>

                        <div class="grid" style="margin: 2%;">

                            <table class="table table-hover daos_datagrid">
                                <thead>
                                    <tr>
                                        <th> Seleccionar </th>
                                        <th> Nombre </th>
                                        <th> T.D. </th>
                                        <th> No.Doc </th>
                                        <th> Producto </th>
                                        <th> Identificador </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $cuenta;
                                    $pedidoAbono = $this->session->userdata("pedidoAbono");
                                   

                                    foreach ($tarjetaHabiente as $value) {
                                        $cuenta = $value['CUENTA'];
                                        ?>

                                        <tr class="gradeC"> 
                                            <td>
                                                <input type="checkbox" 
                                                <?php if (in_array($cuenta, $pedidoAbono, true)) { ?>
                                                           checked
                                                       <?php } ?>   
                                                       name="<?= $value['CUENTA'] ?>" id="<?= $value['CUENTA'] ?>" onchange="referidos(<?= $value['CUENTA'] ?>)" value="<?= $value['CUENTA'] ?>"></td>
                                            <td><?= $value['NOMTAR'] ?></td>
                                            <td><?= $value['ABR'] ?></td>
                                            <td><?= $value['DOC'] ?></td>
                                            <td><?= $value['NOMPRO'] ?></td>
                                            <td><?= $value['IDENTIFICADOR'] ?></td>
                                        </tr>
                                    <?php } ?>   
                                </tbody>
                            </table>

                        </div> 
                        <div class="button col-md-4 col-md-push-4">
                            <button class="bottom" type="submit" > A G R E G A R  A L A  L I S T A</button>
                        </div>
                        <br>
                    </form>
                </div> 
            </div>

        </div>
    </div>
</div>
<script type="text/javascript">
    var llaves = "<?= $this->session->userdata("llavesTemp") ?>";
    $("#referidosestado").val(llaves);
    function referidos(idreferido) {
        var checkbox = document.getElementById(idreferido);
        var referidos = $("#referidosestado").val();


        if (checkbox.checked == true) {
            referidos = referidos + "," + idreferido;
            $("#referidosestado").val(referidos);
        } else {
            var siesweb = referidos.startsWith(idreferido);
            //alert(siesweb);
            if (siesweb) {
                var quitar = idreferido + ",";
                referidos = referidos.replace(quitar, "");
                $("#referidosestado").val(referidos);
            } else {
                var quitar = "," + idreferido;
                referidos = referidos.replace(quitar, "");
                $("#referidosestado").val(referidos);
            }
        }
    }
</script>