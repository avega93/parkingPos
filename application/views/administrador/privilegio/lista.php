
<a href="/administrador/privilegio/agregar" class="btn btn-primary btn-sm btn-principales"><span class="glyphicon glyphicon-plus" aria-hidden="true"></span>&nbsp;&nbsp; Agregar</a>
<h2>Privilegios</h2>
<hr/>
<br/>
<div class="daos_grid_php" info="privilegios">
    <table class="table table-bordered table-hover table-condensed">
        <thead>
            <tr>
                <th order="id_admin_privilegios" style="width: 40px !important"># ID</th>
                <th order="codigo_admin_privilegios">C&oacute;digo</th>
                <th order="descripcion_admin_privilegios">Descripci&oacute;n</th>
                <th order="" style="width: 180px !important">Acciones</th>
            </tr>
        </thead>
        <tbody class="gridBody">
            <tr>
                <td>{id_admin_privilegios}</td>
                <td>{codigo_admin_privilegios}</td>
                <td>{descripcion_admin_privilegios}</td>
                <td >
                    <div>
                        <a href="/administrador/privilegio/agregar/{id_admin_privilegios}" class="btn btn-warning btn-xs"><span class="glyphicon glyphicon-edit" aria-hidden="true"></span> &nbsp; Modificar</a>
                        <a href="/administrador/privilegio/eliminar/{id_admin_privilegios}" class="btn btn-danger btn-xs"><span class="glyphicon glyphicon-remove" aria-hidden="true"></span> &nbsp; Eliminar</a>
                    </div>
                </td>
            </tr>
        </tbody>
    </table>
</div>