<option value="">Seleccione</option>
<? foreach ($ciudad as $ciudad_item) { ?>
    <option value="<?= $ciudad_item['id_admin_ciudad'] ?>"><?= $ciudad_item['nombre_admin_ciudad'] ?></option>
<? } 