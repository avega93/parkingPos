<?php if ($alert == 'ok') { ?>
    <div class="alert alert-success">
        <button type="button" class="close" data-dismiss="alert">&times;</button>
        <strong>Felicidades!</strong> La operaci&oacute;n ha sido completada.
    </div>
<?php } ?>
<?php if ($alert == 'exist') { ?>
    <div class="alert alert-danger">
        <button type="button" class="close" data-dismiss="alert">&times;</button>
        <strong>Error!</strong> Intente de nuevo.
    </div>
<?php } ?>
<?php
$privilegios = $this->acceso->privilegios();
?>
<?php if (isset($privilegios[311])) { ?>
    <a href="/productos/ingreso/agregar" class="btn btn-primary btn-sm btn-principales"><span class="glyphicon glyphicon-plus" aria-hidden="true"></span>&nbsp;&nbsp; Agregar</a>
<?php } ?>
<h2>Ingreso de productos</h2>
<hr/>
<br/>

<div class="daos_grid_php" info="productos">
    <table class="table table-bordered table-hover table-condensed">
        <thead>
            <tr>
                <th order="p.id_par_producto" style="width: 40px !important"># ID</th>
                <th order="p.nombre_par_producto">Nombre</th>
                <th order="p.valor_par_producto">Precio</th>
                <th order="p.cantidad_par_producto">Cantidad</th>
                <th order="">Acciones</th>
            </tr>
        </thead>
        <tbody class="gridBody">
            <tr>
                <td>{id_par_producto}</td>
                <td>{nombre_par_producto}</td>
                <td>{valor_par_producto}</td>
                <td>{cantidad_par_producto}</td>
                <td >
                    <div>
                        <?php if (isset($privilegios[312])) { ?>
                            <a href="/productos/ingreso/agregar/{id_par_producto}" class="btn btn-warning btn-xs">
                                <span class="glyphicon glyphicon-edit" aria-hidden="true"></span>
                                &nbsp; Modificar
                            </a>
                        <?php } ?>
                        <?php if (isset($privilegios[313])) { ?>
                            <a href="/productos/ingreso/eliminar/{id_par_producto}" class="btn btn-danger btn-xs">
                                <span class="glyphicon glyphicon-remove" aria-hidden="true"></span> 
                                &nbsp; Eliminar
                            </a>
                        <?php } ?>
                    </div>
                </td>
            </tr>
        </tbody>
    </table>
</div>