<?php if ($alert == 'ok') { ?>
    <div class="alert alert-success">
        <button type="button" class="close" data-dismiss="alert">&times;</button>
        <strong>Felicidades!</strong> La operaci&oacute;n ha sido completada.
    </div>
<?php } ?>
<?php if ($alert == 'exist') { ?>
    <div class="alert alert-danger">
        <button type="button" class="close" data-dismiss="alert">&times;</button>
        <strong>Error!</strong> Tarifa previamente definida.
    </div>
<?php } ?>
<?php
$privilegios = $this->acceso->privilegios();
?>
<?php if (isset($privilegios[213])) { ?>
    <a href="/parqueadero/tarifas/agregar" class="btn btn-primary btn-sm btn-principales"><span class="glyphicon glyphicon-plus" aria-hidden="true"></span>&nbsp;&nbsp; Agregar</a>
<?php } ?>
<h2>Tarifas</h2>
<hr/>
<br/>

<div class="daos_grid_php" info="tarifas">
    <table class="table table-bordered table-hover table-condensed">
        <thead>
            <tr>
                <th order="p.id_par_tarifa" style="width: 40px !important"># ID</th>
                <th order="p.precio_par_tarifa">Precio</th>
                <th order="tv.nombre_par_vehiculo_tipo">Tipo</th>
                <th order="rt.nombre_par_res_temporal">Resolucion Temporal</th>
                <th order="p.editado_par_tarifa">fecha Editado</th>
                <th order="">Acciones</th>
            </tr>
        </thead>
        <tbody class="gridBody">
            <tr>
                <td>{id_par_tarifa}</td>
                <td>{precio_par_tarifa}</td>
                <td>{nombre_par_vehiculo_tipo}</td>
                <td>{nombre_par_res_temporal}</td>
                <td>{editado_par_tarifa}</td>
                <td >
                    <div>
                        <?php if (isset($privilegios[212])) { ?>
                            <a href="/parqueadero/tarifas/agregar/{id_par_tarifa}" class="btn btn-warning btn-xs">
                                <span class="glyphicon glyphicon-edit" aria-hidden="true"></span>
                                &nbsp; Modificar
                            </a>
                        <?php } ?>
                        <?php if (isset($privilegios[213])) { ?>
                            <a href="/parqueadero/tarifas/eliminar/{id_par_tarifa}" class="btn btn-danger btn-xs">
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