<div class="col-2"></div>
<div class="col-8">
    <h1>Tarjetas</h1>
    <div class="grid" style="margin: 2%;">
        <table class="table table-hover daos_datagrid">
            <thead>
                <tr>
                    <th> No. Tarjeta </th>
                    <th> Estado </th>
                    <th> Asignada </th>
                </tr>
            </thead>
            <tbody>
                <?php
                foreach ($tarjetas as $value) { ?>
                    <tr class="gradeC">
                        <form id="update" action="/portal/bono/updateEstadoTarjeta">
                            <td><?= $value['NUMTAR'] ?></td>
                            <!-- <td><?= $value['ESTTAR'] ?></td>-->
                            <input type="text" name="data" value="<?= $value['NUMTAR'] ?> " hidden />
                            <td><select name="estado" onchange="updateStatusTarjet(this.value, <?= $value['IDTAR'] ?> )" required>
                                    <option value="">Seleccione el rol</option>
                                    <?php foreach ($estadoTarjetas as $estadoKey => $estadoValue) { ?>
                                        <option value="<?= $estadoValue['COD'] ?>" <?php if ($estadoValue['NOM'] == $value['ESTTAR']) { ?> SELECTED <?php } ?>>
                                            <?= $estadoValue['NOM'] ?></option>
                                    <?php } ?>
                                </select></td>
                            <?php if (is_null($value['MOV'])) { ?>
                                <td>No</td>
                            <?php } else { ?>
                                <td>Si</td>
                            <?php } ?>
                        </form>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</div>
<div class="col-2"></div>
<script>
    function updateStatusTarjet(estado, idtar) {
        $.ajax({
                url: "/portal/bono/updstatar/" + estado + "/" + idtar,
            })
            .done(function(data) {
                if (data == 1 || data == "1") {
                    location.reload();
                } else {
                    alert("System Error!");
                    alert(data);
                }
            });
    }
</script>