<option value="">Seleccione</option>
<? foreach ($opcion as $opcion_item) { ?>
    <option value="<?= $opcion_item['id_encu_opcion'] ?>"><?= $opcion_item['texto_encu_opcion'] ?></option>
<? } 