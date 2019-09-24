<h2>Roles / <?= $operacion ?></h2>
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
            <label class="col-sm-2 control-label" for="inputNombres">Nombres</label>
            <div class="col-sm-5">
                <input class="form-control required" name="nombre_admin_rol" type="text" id="inputNombres" placeholder="Nombre" value="<?= $rol['nombre_admin_rol'] ?>">
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-2 control-label" for="inputNombres">Seleccione los privilegios</label>
            <div class="col-sm-9 checkbox">

                <?php
                $seccion = "";
                foreach ($privilegios as $privilegio):
                    if ($privilegio['codigo_admin_privilegios'] < 1000) {
                        if ($seccion != substr($privilegio['codigo_admin_privilegios'], 0, 1)) {
                            $seccion = substr($privilegio['codigo_admin_privilegios'], 0, 1)
                            ?>
                            <hr class="col-sm-10"/>
                            <?php
                        }
                    } else {
                        if ($seccion != substr($privilegio['codigo_admin_privilegios'], 0, 2)) {
                            $seccion = substr($privilegio['codigo_admin_privilegios'], 0, 2)
                            ?>
                            <hr class="col-sm-10"/>
                            <?php
                        }
                    }
                    ?>
                    <div class="alert alert-warning col-sm-5" style="margin-right: 10px">
                        <label>
                            <input type="checkbox" 
                            <?php
                            foreach ($privilegios_rol as $p_r) {
                                if ($p_r['id_admin_privilegios'] === $privilegio['id_admin_privilegios'])
                                    echo 'checked="true"';
                            }
                            ?>
                                   name="privilegio_<?= $privilegio['id_admin_privilegios'] ?>" value="<?= $privilegio['id_admin_privilegios'] ?>">
                            (<?= $privilegio['codigo_admin_privilegios'] ?>)
                            <?= $privilegio['descripcion_admin_privilegios'] ?>
                        </label>
                    </div>

                <?php endforeach; ?>

            </div>
        </div>


        <div class="form-group">
            <div class="col-sm-5 col-md-offset-2">
                <button type="submit" class="btn btn-primary">Guardar</button>
            </div>
        </div>
    </form>
</div>