
<a href="/administrador/rol/agregar" class="btn btn-primary btn-sm btn-principales"><span class="glyphicon glyphicon-plus" aria-hidden="true"></span>&nbsp;&nbsp; Agregar</a>
<h2>Roles</h2>
<hr/>
<br/>
<div class="daos_grid_php" info="roles">
    <table class="table table-bordered table-hover table-condensed">
        <thead>
            <tr>
                <th order="id_admin_rol" style="width: 40px !important"># ID</th>
                <th order="nombre_admin_rol">Nombre</th>
                <th order="" style="width: 180px !important">Acciones</th>
            </tr>
        </thead>
        <tbody class="gridBody">
            <tr>
                <td>{id_admin_rol}</td>
                <td>{nombre_admin_rol}</td>
                <td >
                    <div>
                        <a href="/administrador/rol/agregar/{id_admin_rol}" class="btn btn-warning btn-xs"><span class="glyphicon glyphicon-edit" aria-hidden="true"></span> &nbsp; Modificar</a>
                        <a href="/administrador/rol/eliminar/{id_admin_rol}" class="btn btn-danger btn-xs"><span class="glyphicon glyphicon-remove" aria-hidden="true"></span> &nbsp; Eliminar</a>
                    </div>
                </td>
            </tr>
        </tbody>
    </table>
</div>