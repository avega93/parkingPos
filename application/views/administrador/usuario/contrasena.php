<h2>Usuario / <?= $operacion ?></h2>
<hr/>
<div>
    <?php if ($alert == 'ok') { ?>
        <div class="alert alert-success">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
            <strong>Felicidades!</strong> La operaci&oacute;n ha sido completada. Debe iniciar sesi&oacute;n de nuevo.
        </div>
    <?php } ?>
    <?php if ($alert == 'contrasena') { ?>
        <div class="alert alert-success">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
            <strong>Ooops!</strong> La contrase&ntilde;a activa no es correcta.
        </div>
    <?php } ?>
    <?php if ($alert == 'ncontrasena') { ?>
        <div class="alert alert-success">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
            <strong>Ooops!</strong> Las dos contrase&ntilde;as nuevas no coinciden.
        </div>
    <?php } ?>
    <form class="form-horizontal daos_formulario" action="" method="POST">
        <div class="form-group">
            <label class="col-sm-2 control-label" for="inputContrasena">Contrase&ntilde;a activa</label>
            <div class="col-sm-5">
                <input class="form-control required" name="contrasena" type="password" id="inputContrasena" placeholder="Contrase&ntilde;a activa">
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-2 control-label" for="inputApellidos">Nueva contrase&ntilde;a</label>
            <div class="col-sm-5">
                <input class="form-control required" name="ncontrasena" type="password" id="inputApellidos" placeholder="Nueva contrase&ntilde;a">
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-2 control-label" for="inputEmail">Repita la nueva contrase&ntilde;a</label>
            <div class="col-sm-5">
                <input class="form-control required" name="n2contrasena" type="password" id="inputEmail" placeholder="Nueva contrase&ntilde;a">
            </div>
        </div>
        
        <div class="form-group">
            <div class="col-sm-5 col-md-offset-2">
                <button type="submit" class="btn btn-primary">Guardar</button>
            </div>
        </div>
    </form>
</div>