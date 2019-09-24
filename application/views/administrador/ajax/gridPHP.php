<option value="">Seleccione</option>
<? foreach ($departamento as $departamento_item) { ?>
    <option value="<?= $departamento_item['id_admin_departamento'] ?>"><?= $departamento_item['nombre_admin_departamento'] ?></option>
<? } 