<div>
    <h1>Tarjetas</h1>
    <div class="grid" style="margin: 2%;">
        <form method="POST">
        <table class="table table-hover daos_datagrid">
            <thead>
                <tr>
                    <th> Seleccion</th>
                    <th> Nombre </th>
                    <th> T.D </th>
                    <th> No.Doc </th>
                    <th> No. Tarjeta </th>
                    <th> Identificador </th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($tarjetas as $value) { ?>
                    <tr class="gradeC"> 
                        <td><input name="<?= $value['COD'] ?>" type="checkbox" class="only-one"></td>
                        <td><?= $value['RAZSOC'] ?></td>
                        <td><?= $value['TIPDOC'] ?></td>
                        <td><?= $value['DOCUME'] ?></td>
                        <td><?= $value['NUMTAR'] ?></td>
                        <td><?= $value['IDENTI'] ?></td>
                    </tr>
                <?php } ?>   
            </tbody>
        </table>
            <div class="button col-sm-12">
                <button type="submit">
                           SELECCIONAR
                </button>
            </div>
        </form>
    </div> 
</div>

<script>
    let Checked = null;
    //The class name can vary
    for (let CheckBox of document.getElementsByClassName('only-one')) {
        CheckBox.onclick = function () {
            if (Checked != null) {
                Checked.checked = false;
                Checked = CheckBox;
            }
            Checked = CheckBox;
        }
    }
</script>