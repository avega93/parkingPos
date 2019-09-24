
<a href="/administrador/usuario/agregar" class="btn btn-primary btn-sm btn-principales"><span class="glyphicon glyphicon-plus" aria-hidden="true"></span>&nbsp;&nbsp; Agregar</a>
<h2>Usuarios</h2>
<hr/>
<?php if ($alert == 'ok') { ?>
        <div class="alert alert-success">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
            <strong>Felicidades!</strong> La operaci&oacute;n ha sido completada.
        </div>
    <?php } ?>
<br/>
<div class="daos_grid_php" info="usuarios">
    <table class="table table-bordered table-hover table-condensed">
        <thead>
            <tr>
                <th order="id_admin_usuario" style="width: 40px !important"># ID</th>
                <th order="nombre_admin_estado_usuario">Estado</th>
                <th order="nombre_admin_rol">Rol</span></th>
                <th order="nombres_admin_usuario">Nombres</th>
                <th order="apellidos_admin_usuario">Apellidos</th>
                <th order="" style="width: 180px !important">Acciones</th>
            </tr>
        </thead>
        <tbody class="gridBody">
            <tr>
                <td>{id_admin_usuario}</td>
                <td>{nombre_admin_estado_usuario}</td>
                <td>{nombre_admin_rol}</td>
                <td>{nombres_admin_usuario}</td>
                <td>{apellidos_admin_usuario}</td>
                <td >
                    <div>
                        <a href="/administrador/usuario/agregar/{id_admin_usuario}" class="btn btn-warning btn-xs">
                            <span class="glyphicon glyphicon-edit" aria-hidden="true"></span>
                            &nbsp; Modificar
                        </a>
                        <a href="/administrador/usuario/eliminar/{id_admin_usuario}" class="btn btn-danger btn-xs">
                            <span class="glyphicon glyphicon-remove" aria-hidden="true"></span> 
                            &nbsp; Eliminar
                        </a>
                    </div>
                </td>
            </tr>
        </tbody>
    </table>
</div>