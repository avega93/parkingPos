<h2>Privilegios / <?= $operacion ?></h2>
<hr/>
<div>
    <? if ($alert == 'ok') { ?>
        <div class="alert alert-success">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
            <strong>Felicidades!</strong> La operaci&oacute;n ha sido completada.
        </div>
    <? } ?>
    <form class="form-horizontal daos_formulario" action="" method="POST" >
        <div class="form-group">
            <label class="col-sm-2 control-label" for="inputCodigo">C&oacute;digo</label>
            <div class="col-sm-5">
                <input class="form-control required" name="codigo_admin_privilegios" type="text" id="inputCodigo" placeholder="Codigo" value="<?=$privilegio['codigo_admin_privilegios'] ?>">
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-2 control-label" for="inputDescripcion">Descripci&oacute;n</label>
            <div class="col-sm-5">
                <input class="form-control required" name="descripcion_admin_privilegios" type="text" id="inputNombres" placeholder="Descripcion" value="<?=$privilegio['descripcion_admin_privilegios'] ?>">
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-2 control-label" for="inputSeg_1">Segmento 1</label>
            <div class="col-sm-5">
                <input class="form-control " name="seg_1_admin_privilegios" type="text" id="inputSeg_1" placeholder="Segmento 1" value="<?=$privilegio['seg_1_admin_privilegios'] ?>">
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-2 control-label" for="inputSeg_2">Segmento 2</label>
            <div class="col-sm-5">
                <input class="form-control " name="seg_2_admin_privilegios" type="text" id="inputSeg_2" placeholder="Segmento 2" value="<?=$privilegio['seg_2_admin_privilegios'] ?>">
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-2 control-label" for="inputSeg_3">Segmento 3</label>
            <div class="col-sm-5">
                <input class="form-control " name="seg_3_admin_privilegios" type="text" id="inputSeg_3" placeholder="Segmento 3" value="<?=$privilegio['seg_3_admin_privilegios'] ?>">
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-2 control-label" for="inputSeg_4">Segmento 4</label>
            <div class="col-sm-5">
                <input class="form-control " name="seg_4_admin_privilegios" type="text" id="inputSeg_4" placeholder="Segmento 4" value="<?=$privilegio['seg_4_admin_privilegios'] ?>">
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-2 control-label" for="inputSeg_5">Segmento 5</label>
            <div class="col-sm-5">
                <input class="form-control " name="seg_5_admin_privilegios" type="text" id="inputSeg_5" placeholder="Segmento 5" value="<?=$privilegio['seg_5_admin_privilegios'] ?>">
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-2 control-label" for="inputSeg_6">Segmento 6</label>
            <div class="col-sm-5">
                <input class="form-control " name="seg_6_admin_privilegios" type="text" id="inputSeg_6" placeholder="Segmento 6" value="<?=$privilegio['seg_6_admin_privilegios'] ?>">
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-2 control-label" for="inputSeg_7">Segmento 7</label>
            <div class="col-sm-5">
                <input class="form-control " name="seg_7_admin_privilegios" type="text" id="inputSeg_7" placeholder="Segmento 7" value="<?=$privilegio['seg_7_admin_privilegios'] ?>">
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-2 control-label" for="inputSeg_8">Segmento 8</label>
            <div class="col-sm-5">
                <input class="form-control " name="seg_8_admin_privilegios" type="text" id="inputSeg_8" placeholder="Segmento 8" value="<?=$privilegio['seg_8_admin_privilegios'] ?>">
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-2 control-label" for="inputSeg_9">Segmento 9</label>
            <div class="col-sm-5">
                <input class="form-control " name="seg_9_admin_privilegios" type="text" id="inputSeg_9" placeholder="Segmento 9" value="<?=$privilegio['seg_9_admin_privilegios'] ?>">
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-2 control-label" for="inputSeg_10">Segmento 10</label>
            <div class="col-sm-5">
                <input class="form-control " name="seg_10_admin_privilegios" type="text" id="inputSeg_10" placeholder="Segmento 10" value="<?=$privilegio['seg_10_admin_privilegios'] ?>">
            </div>
        </div>
        <div class="form-group">
            <div class="col-sm-5 col-md-offset-2">
                <button type="submit" class="btn btn-primary">Guardar</button>
            </div>
        </div>
    </form>
</div>