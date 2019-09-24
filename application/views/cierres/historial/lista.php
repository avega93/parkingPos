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

<h2>Historial Cierre</h2>
<hr/>
<br/>

<div class="daos_grid_php" info="cierres">
    <table class="table table-bordered table-hover table-condensed">
        <thead>
            <tr>
                <th order="p.id_par_cierre" style="width: 40px !important"># ID</th>
                <th order="p.fecha_par_cierre">Fecha</th>
                <th order="p.valor_par_cierre">Valor</th>
                <th order="nombre">Encargado</th>
                <th order="">Acciones</th>
            </tr>
        </thead>
        <tbody class="gridBody">
            <tr>
                <td>{id_par_cierre}</td>
                <td>{fecha_par_cierre}</td>
                <td>{valor_par_cierre}</td>
                <td>{nombre}</td>
                <td >
                    <div>
                        <?php if (isset($privilegios[401])) { ?>
                            <a href="/cierres/historial/imprimir/{id_par_cierre}" class="btn btn-warning btn-xs">
                                <span class="glyphicon glyphicon-print" aria-hidden="true"></span>
                                &nbsp; Imprimir
                            </a>
                        <?php } ?>
                        
                    </div>
                </td>
            </tr>
        </tbody>
    </table>
</div>