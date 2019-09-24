<?php if ($alert == 'ok') { ?>
    <div class="alert alert-success">
        <button type="button" class="close" data-dismiss="alert">&times;</button>
        <strong>Felicidades!</strong> La operaci&oacute;n ha sido completada.
    </div>
<?php } ?>
<?php if ($alert == 'exist') { ?>
    <div class="alert alert-danger">
        <button type="button" class="close" data-dismiss="alert">&times;</button>
        <strong>Error!</strong> Mensualidad previamente creada.
    </div>
<?php } ?>
<?php if ($alert == 'noPagar') { ?>
    <div class="alert alert-danger">
        <button type="button" class="close" data-dismiss="alert">&times;</button>
        <strong>Error!</strong> Esta mensualidad aun no esta vencida, por lo tanto no es posible pagarla aun.
    </div>
<?php } ?>
<?php
$privilegios = $this->acceso->privilegios();
?>
<?php if (isset($privilegios[221]) && $registrar) { ?>
    <a href="/parqueadero/mensualidad/agregar" class="btn btn-primary btn-sm btn-principales"><span class="glyphicon glyphicon-plus" aria-hidden="true"></span>&nbsp;&nbsp; Agregar</a>
<?php } ?>
<h2>Mensualidad</h2>
<hr/>
<br/>

<div class="daos_grid_php" info="mensualidad">
    <table class="table table-bordered table-hover table-condensed">
        <thead>
            <tr>
                <th order="p.placa_par_mensualidad">Placa</th>
                <th order="p.inicio_par_mensualidad">Fecha Inicio</th>
                <th order="p.fin_par_mensualidad">Fecha Fin</th>
                <th order="p.valor_par_mensualidad">Valor</th>
                <th order="p.modelo_par_mensualidad">Modelo</th>
                <th order="p.color_par_mensualidad">Color</th>
                <th order="p.nombre_par_mensualidad">Dueño</th>
                <th order="p.telefono_par_mensualidad">Telefono</th>
                <th order="tv.nombre_par_vehiculo_tipo">Tipo Vehiculo</th>
                <th order="p.estado_par_mensualidad">Estado</th>
                <th order="">Acciones</th>
            </tr>
        </thead>
        <tbody class="gridBody">
            <tr>
                <td>{placa_par_mensualidad}</td>
                <td>{inicio_par_mensualidad}</td>
                <td>{fin_par_mensualidad}</td>
                <td>{valor_par_mensualidad}</td>
                <td>{modelo_par_mensualidad}</td>
                <td>{color_par_mensualidad}</td>
                <td>{nombre_par_mensualidad}</td>
                <td>{telefono_par_mensualidad}</td>
                <td>{nombre_par_vehiculo_tipo}</td>
                <td >{estado}</td>
                <td >
                    <div>
                        <?php if (isset($privilegios[222])) { ?>
                            <a href="/parqueadero/mensualidad/agregar/{id_par_mensualidad}" class="btn btn-warning btn-xs">
                                <span class="glyphicon glyphicon-edit" aria-hidden="true"></span>
                                &nbsp; Modificar
                            </a>
                        <?php } ?>
                        <?php if (isset($privilegios[221]) && $registrar ) { ?>
                            <a href="/parqueadero/mensualidad/pagar/{id_par_mensualidad}" class="btn btn-success btn-xs">
                                <span class="glyphicon glyphicon-edit" aria-hidden="true"></span> 
                                &nbsp; Pagar
                            </a>
                        <?php } ?>
                        <?php if (isset($privilegios[223])) { ?>
                            <a href="/parqueadero/mensualidad/eliminar/{id_par_mensualidad}" class="btn btn-danger btn-xs">
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




