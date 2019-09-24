<h2>Tarifas / <?= $operacion ?></h2>
<hr/>
<div>
    

    <form class="form-horizontal daos_formulario" action="" method="POST">
        <div class="form-group">
            <label class="col-sm-2 control-label" for="id_par_vehiculo_tipo">Tipo de vehiculo</label>
            <div class="col-sm-5">
                <select class="form-control required" name="id_par_vehiculo_tipo" id="id_par_vehiculo_tipo">
                    <option value="">Seleccione</option>
                    <?php foreach ($vehiculos as $vehiculos_item) { ?>
                        <option value="<?= $vehiculos_item['id_par_vehiculo_tipo'] ?>" <?php if ($vehiculos_item['id_par_vehiculo_tipo'] == $tarifa['id_par_vehiculo_tipo']) echo 'selected' ?>><?= $vehiculos_item['nombre_par_vehiculo_tipo'] ?></option>
                    <?php } ?>
                </select>
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-2 control-label" for="id_par_res_temporal">Resolucion Temporal</label>
            <div class="col-sm-5">
                <select class="form-control required" name="id_par_res_temporal" id="id_par_res_temporal">
                    <option value="">Seleccione</option>
                    <?php foreach ($temporales as $temporales_item) { ?>
                        <option value="<?= $temporales_item['id_par_res_temporal'] ?>" <?php if ($temporales_item['id_par_res_temporal'] == $tarifa['id_par_res_temporal']) echo 'selected' ?>><?= $temporales_item['nombre_par_res_temporal'] ?></option>
                    <?php } ?>
                </select>
            </div>
        </div>
        
        <div class="form-group">
            <label class="col-sm-2 control-label" for="precio_par_tarifa">Precio</label>
            <div class="col-sm-5">
                <input class="form-control required" value="<?= $tarifa['precio_par_tarifa'] ?>" name="precio_par_tarifa" type="numeric" id="precio_par_tarifa" placeholder="Precio">
            </div>
        </div>
        
        <div class="form-group">
            <div class="col-sm-5 col-md-offset-2">
                <button type="submit" class="btn btn-primary">Guardar</button>
            </div>
        </div>
    </form>
</div>
