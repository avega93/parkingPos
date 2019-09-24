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
<h2>Venta Productos Turno</h2>
<hr/>
<br/>
<?php if($registrar || $eliminar) { ?>
<div class="daos_grid_php" info="productos">
    <table class="table table-bordered table-hover table-condensed">
        <thead>
            <tr>
                <th order="pr.nombre_par_producto">Producto</th>
                <th order="p.valor_par_venta_producto">Valor</th>
                <th order="p.fecha_par_venta_producto">Fecha</th>
                <th order="p.cantidad_par_venta_producto">Cantidad</th>
                <th order="">Acciones</th>
            </tr>
        </thead>
        <tbody class="gridBody">
            <tr>
                <td>{nombre_par_producto}</td>
                <td>{valor_par_venta_producto}</td>
                <td>{fecha_par_venta_producto}</td>
                <td>{cantidad_par_venta_producto}</td>
                <td >
                    <div>
                        <?php if (isset($privilegios[323])) { ?>
                            <a href="/productos/historial/eliminar/{id_par_venta_producto}" class="btn btn-warning btn-xs">
                                <span class="" aria-hidden="true"></span>
                                &nbsp; Eliminar
                            </a>
                        <?php } ?>
                        
                    </div>
                </td>
            </tr>
        </tbody>
    </table>
</div>
<?php } ?>