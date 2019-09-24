<option value="">Seleccione</option>
<?php foreach ($ciudad as $ciudad_item) { ?>
    <option value="<?= $ciudad_item['id_admin_ciudad'] ?>"><?= $ciudad_item['nombre_admin_ciudad'] ?></option>
<?php } ?>