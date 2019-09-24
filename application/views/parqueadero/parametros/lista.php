<h2>Parametros BIKER48</h2>
<hr/>
<div>


    <form class="form-horizontal daos_formulario" action="" method="POST">
        <div class="form-group">
            <label class="col-sm-2 control-label" for="horario_atencion_par_parametros">Texto Horario Atencion</label>
            <div class="col-sm-8">
                <textarea class="form-control" id="horario_atencion_par_parametros" name="horario_atencion_par_parametros"><?= $parametros['horario_atencion_par_parametros'] ?></textarea>
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-2 control-label" for="mensaje_guardar_par_parametros">Texto Mensaje Guardar</label>
            <div class="col-sm-8">
                <textarea class="form-control" id="mensaje_guardar_par_parametros" name="mensaje_guardar_par_parametros"><?= $parametros['mensaje_guardar_par_parametros'] ?></textarea>
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-2 control-label" for="nota_par_parametros">Texto Nota</label>
            <div class="col-sm-8">
                <textarea class="form-control" id="nota_par_parametros" name="nota_par_parametros"><?= $parametros['nota_par_parametros'] ?></textarea>
            </div>
        </div>
        <div class="form-group">
            <div class="col-sm-5 col-md-offset-2">
                <button type="submit" class="btn btn-primary">Guardar</button>
            </div>
        </div>
    </form>
</div>
