<option value="">Seleccione</option>
<?php foreach ($ciudad as $ciudad_item) { ?>
    <option value="<?= $ciudad_item['PK_CIU_CODIGO'] ?>"><?= $ciudad_item['NOMBRE'] ?></option>
<?php } ?>