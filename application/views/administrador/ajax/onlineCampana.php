<option value="">Seleccione Campaña</option>
<?php foreach ($campana as $key => $value) { ?>
    <option value="<?= $value['CODIGOCAMPANA'] ?>"> <?= $value['NOMBRECAMPANA'] ?></option>
<?php } ?>