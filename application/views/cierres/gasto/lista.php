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
<?php if (isset($privilegios[411]) && $registrar) { ?>
    <a href="/cierres/gasto/agregar" class="btn btn-primary btn-sm btn-principales"><span class="glyphicon glyphicon-plus" aria-hidden="true"></span>&nbsp;&nbsp; Agregar</a>
<?php } ?>
<h2>Gastos Turno</h2>
<hr/>
<br/>
<?php if($registrar || $eliminar) { ?>
<div class="daos_grid_php" info="gastos">
    <table class="table table-bordered table-hover table-condensed">
        <thead>
            <tr>
                <th order="p.id_par_gasto" style="width: 40px !important"># ID</th>
                <th order="p.fecha_par_gasto">Fecha</th>
                <th order="p.valor_par_gasto">Valor</th>
                <th order="p.detalle_par_gasto">Detalle</th>
                <th order="">Acciones</th>
            </tr>
        </thead>
        <tbody class="gridBody">
            <tr>
                <td>{id_par_gasto}</td>
                <td>{fecha_par_gasto}</td>
                <td>{valor_par_gasto}</td>
                <td>{detalle_par_gasto}</td>
                <td >
                    <div>
                        <?php if (isset($privilegios[413])) { ?>
                            <a href="/cierres/gasto/eliminar/{id_par_gasto}" class="btn btn-warning btn-xs">
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
<?php } ?>