
<?php
$privilegios = $this->acceso->privilegios();
?>
<h2>Historico Vehiculos</h2>
<hr/>
<br/>
<div class="daos_grid_php" info="historial">
    <table class="table table-bordered table-hover table-condensed">
        <thead>
            <tr>
                <th order="ph.id_par_historial" style="width: 40px !important"># ID</th>
                <th order="ph.placa_par_historial">Placa</th>
                <th order="ph.entrada_par_historial">Hora Entrada</th>
                <th order="ph.salida_par_historial">Hora Salida</th>
                <th order="ph.cobro_par_historial">Valor Pagado</th>
                <th order="">Estado</th>
                <th order="">Usuario</th>
                <th order="">Acciones</th>
            </tr>
        </thead>
        <tbody class="gridBody">
            <tr>
                <td>{id_par_historial}</td>
                <td>{placa_par_historial}</td>
                <td>{entrada_par_historial}</td>
                <td>{salida_par_historial}</td>
                <td>{cobro_par_historial}</td>
                <td>{eliminado}</td>
                <td>{nombre_usuario}</td>
                <td >
                    <div>
                        
                        <?php if (isset($privilegios[231])) { ?>
                            <a href="/parqueadero/historial/imprimir/{id_par_historial}" class="btn btn-success btn-xs">
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
