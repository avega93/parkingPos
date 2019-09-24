<h2>Usuarios / <?= $operacion ?></h2>
<hr/>
<div>
    <?php if ($alert == 'ok') { ?>
        <div class="alert alert-success">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
            <strong>Felicidades!</strong> La operaci&oacute;n ha sido completada.
        </div>
    <?php } ?>

    <form class="form-horizontal daos_formulario" action="" method="POST">
        <div class="form-group">
            <label class="col-sm-2 control-label" for="inputRol">Rol</label>
            <div class="col-sm-5">
                <select class="form-control required" name="id_admin_rol" id="inputRol">
                    <option value="">Seleccione</option>
                    <?php foreach ($roles as $rol_item) { ?>
                        <option value="<?= $rol_item['id_admin_rol'] ?>" <?php if ($rol_item['id_admin_rol'] == $admin_usuario['id_admin_rol']) echo 'selected' ?>><?= $rol_item['nombre_admin_rol'] ?></option>
                    <?php } ?>
                </select>
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-2 control-label" for="inputEstado">Estado</label>
            <div class="col-sm-5">
                <select class="form-control required" name="id_admin_estado_usuario" id="inputEstado">
                    <option value="">Seleccione</option>
                    <?php foreach ($estados as $estado) { ?>
                        <option value="<?= $estado['id_admin_estado_usuario'] ?>" <?php if ($estado['id_admin_estado_usuario'] == $admin_usuario['id_admin_estado_usuario']) echo 'selected' ?>><?= $estado['nombre_admin_estado_usuario'] ?></option>
                    <?php } ?>
                </select>
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-2 control-label" for="inputNombres">Nombres</label>
            <div class="col-sm-5">
                <input class="form-control required" value="<?= $admin_usuario['nombres_admin_usuario'] ?>" name="nombres_admin_usuario" type="text" id="inputNombres" placeholder="Nombres">
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-2 control-label" for="inputApellidos">Apellidos</label>
            <div class="col-sm-5">
                <input class="form-control required" value="<?= $admin_usuario['apellidos_admin_usuario'] ?>" name="apellidos_admin_usuario" type="text" id="inputApellidos" placeholder="Apellidos">
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-2 control-label" for="inputEmail">Email</label>
            <div class="col-sm-5">
                <input class="form-control required email" value="<?= $admin_usuario['mail_admin_usuario'] ?>" name="mail_admin_usuario" type="text" id="inputEmail" placeholder="Email">
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-2 control-label" for="inputPais">Pa&iacute;s</label>
            <div class="col-sm-5">
                <select class="form-control required" name="id_admin_pais" id="inputPais">
                    <option value="">Seleccione</option>
                    <?php foreach ($pais as $pais_item) { ?>
                        <option value="<?= $pais_item['id_admin_pais'] ?>" <?php if ($pais_item['id_admin_pais'] == $admin_usuario['id_admin_pais']) echo 'selected' ?> ><?= $pais_item['nombre_admin_pais'] ?></option>
                    <?php } ?>
                </select>
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-2 control-label" for="inputDepartamento">Departamento</label>
            <div class="col-sm-5">
                <select class="form-control required" name="id_admin_departamento" id="inputDepartamento">
                    <option value="">Seleccione</option>
                </select>
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-2 control-label" for="inputCiudad">Ciudad</label>
            <div class="col-sm-5">
                <select class="form-control required" name="id_admin_ciudad" id="inputCiudad">
                    <option value="">Seleccione</option>
                </select>
            </div>
        </div>
        <div class="form-group">
            <div class="col-sm-5 col-md-offset-2">
                <button type="submit" class="btn btn-primary">Guardar</button>
            </div>
        </div>
    </form>
</div>
<script type="text/javascript">
    $(document).ready(function () {
        $.ajax({
            url: "/administrador/ajax/departamento/" + $('#inputPais').val()
        })
                .done(function (msg) {
                    $('#inputDepartamento').html(msg)
                    $('#inputDepartamento').val(<?= $admin_usuario['id_admin_departamento'] ?>)
                    $.ajax({
                        url: "/administrador/ajax/ciudad/" + $('#inputDepartamento').val()
                    })
                            .done(function (msg) {
                                $('#inputCiudad').html(msg)
                                $('#inputCiudad').val(<?= $admin_usuario['id_admin_ciudad'] ?>)
                            });
                });
        $('#inputPais').change(function () {
            $.ajax({
                url: "/administrador/ajax/departamento/" + $('#inputPais').val()
            })
                    .done(function (msg) {
                        $('#inputDepartamento').html(msg)
                    });
        });
        $('#inputDepartamento').change(function () {
            $.ajax({
                url: "/administrador/ajax/ciudad/" + $('#inputDepartamento').val()
            })
                    .done(function (msg) {
                        $('#inputCiudad').html(msg)
                    });
        });
    });
</script>