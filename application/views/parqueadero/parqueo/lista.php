<?php if ($alert == 'ok') { ?>
    <div class="alert alert-success">
        <button type="button" class="close" data-dismiss="alert">&times;</button>
        Vehículo ingresado correctamente.
    </div>
<?php } ?>
<?php if ($alert == 'error') { ?>
    <div class="alert alert-danger">
        <button type="button" class="close" data-dismiss="alert">&times;</button>
        <strong>Error!</strong> Vehículo aun en el parqueadero.
    </div>
<?php } ?>
<?php if ($alert == 'mensualidad') { ?>
    <div class="alert alert-warning">
        <button type="button" class="close" data-dismiss="alert">&times;</button>
        <strong>Mensualidad!</strong>Placa de mensualidad.
    </div>
<?php } ?>
<?php if ($alert == 'mensualidadVencida') { ?>
    <div class="alert alert-danger">
        <button type="button" class="close" data-dismiss="alert">&times;</button>
        <strong>Mensualidad Vencida!</strong> Esta Mensualidad esta vencida, recuerde realizar el cobro.
    </div>
<?php } ?>
<?php
$privilegios = $this->acceso->privilegios();
?>
<h2>Vehiculos Actuales</h2>
<hr/>
<br/>

<?php
if (!$iniciar_turno) {
    if ($registrar) {
        ?>

        <form class="form-horizontal daos_formulario" action="" method="POST" autocomplete="off" role="form">
            <div class="form-group">

                <div class="col-sm-5">
                    <label class="col-sm-12 " for="placa_entrada">Placa</label>
                </div>
                <div class="col-sm-2">
                    <label class="col-sm-12 " for="casco1">Casco 1</label>
                </div>
                <div class="col-sm-2">
                    <label class="col-sm-12 " for="casco2">Casco 2</label>
                </div>
		        <div class="col-sm-1">
                    <label class="col-sm-12 " for="casco2">Tarifa especial</label>
                </div>

            </div>
            <div class="form-group">

                <div class="col-sm-5">
                    <input class="form-control required inputsEntrada"  style="text-transform:uppercase"  name="placa_par_activos" type="text" id="placa_entrada" placeholder="Inserte placa" maxlength="6" minlength="5" >
                </div>
                <div class="col-sm-2">
                    <input class="form-control  inputsEntrada"  name="casco1_par_activos" type="text" id="casco1" placeholder="">
                </div>
                <div class="col-sm-2">
                    <input class="form-control  inputsEntrada"  name="casco2_par_activos" type="text" id="casco2" placeholder="">
                </div>
		        <div class="col-sm-1">
                    <input class="form-control inputsEntrada"  name="tarifa_especial" type="checkbox" id="tarifa_especial" value="1">
                </div>
               
                <div class="col-sm-2">
                    <button type="submit" class="btn btn-primary inputsEntrada">Ingresar</button>
                </div>
                 
            </div>
            <div class="form-group group_tarifa_especial" style="display: none;">

                <div class="col-sm-4">
                    <label class="col-sm-12 " for="valor_tarifa_especial">Valor de tarifa especial</label>
                </div>
                <div class="col-sm-4">
                    <label class="col-sm-12 " for="horas_tarifa_especial">Horas de tarifa especial</label>
                </div>

            </div>
            <div class="form-group group_tarifa_especial" style="display: none;">
                 <div class="col-sm-4">
                    <input class="form-control inputsEntrada required no-validate"  name="valor_tarifa_especial" type="number" id="valor_tarifa_especial">
                </div>
                <div class="col-sm-4">
                    <input class="form-control inputsEntrada required no-validate"  name="horas_tarifa_especial" type="number" id="horas_tarifa_especial" >
                </div> 
            </div>

        </form>
        <?php
    }
} else {
    ?>
    <center>
        <div class="form-group col-md-12">
            <a type="submit" class="btn btn-primary inputsEntrada" href="/parqueadero/parqueo/iniciarTurno">Iniciar Turno</a>
        </div> 
    </center>
<?php } ?>
<hr/>
<br/>
<div >
    <table class="table table-bordered table-hover table-condensed table-striped table-biker" id="example" style="width:100%">
        <thead>
            <tr>
                <th  style="width: 40px !important"># ID</th>
                <th >Placa</th>
                <th >Hora Entrada</th>
                <th >Tipo</th>
                <th >Acciones</th>
            </tr>
        </thead>
        <tbody >
            <?php foreach($activos as $activo) { ?>
            <tr>
                <td><?=$activo['id_par_activos'] ?></td>
                <td><?=$activo['placa_par_activos']?></td>
                <td><?=$activo['entrada_par_activos']?></td>
                <td><?=$activo['nombre_par_vehiculo_tipo']?></td>
                <td >
                    <div>
                        <?php if (isset($privilegios[203])) { ?>
                            <a href="/parqueadero/parqueo/eliminar/<?=$activo['id_par_activos']?>" class="btn btn-danger btn-xs">
                                <span class="glyphicon glyphicon-remove" aria-hidden="true"></span> 
                                &nbsp; Eliminar
                            </a>
                        <?php } ?>
                        <?php if (isset($privilegios[201]) && $registrar) { ?>
                            <a href="/parqueadero/parqueo/pagar/<?=$activo['id_par_activos']?>" class="btn btn-success btn-xs">
                                <span class="fa fa-coins" aria-hidden="true"></span> 
                                &nbsp; Pagar
                            </a>
                        <?php } ?>
                        <?php if (isset($privilegios[201]) && $registrar) { ?>
                            <a href="/parqueadero/parqueo/reImprimir/<?=$activo['id_par_activos']?>" class="btn btn-success btn-xs">
                                <span class="glyphicon glyphicon-print" aria-hidden="true"></span>
                                &nbsp; Imprimir
                            </a>
                        <?php } ?>
                    </div>
                </td>
            </tr>
            <?php } ?>
        </tbody>
    </table>
</div>
<?php if ($registrar) { ?>
    <br/>
    <center>
        <div class="form-group col-md-12">
            <!--<a type="submit" class="btn btn-danger inputsEntrada" href="/parqueadero/parqueo/finTurno">Finalizar Turno</a>-->
            <a type="submit" class="btn btn-warning inputsEntrada" id="finTurno" href="#">Finalizar Turno</a>
        </div> 
    </center>
    <br/>
<?php } ?>
<script>
    $(document).ready(function() {
    $('#example').DataTable( {
        "order": [[ 0, "desc" ]],
        "language"  : {
    "sProcessing":     "Procesando...",
    "sLengthMenu":     "Mostrar _MENU_ registros",
    "sZeroRecords":    "No se encontraron resultados",
    "sEmptyTable":     "Ningún dato disponible en esta tabla",
    "sInfo":           "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
    "sInfoEmpty":      "Mostrando registros del 0 al 0 de un total de 0 registros",
    "sInfoFiltered":   "(filtrado de un total de _MAX_ registros)",
    "sInfoPostFix":    "",
    "sSearch":         "Buscar:",
    "sUrl":            "",
    "sInfoThousands":  ",",
    "sLoadingRecords": "Cargando...",
    "oPaginate": {
        "sFirst":    "Primero",
        "sLast":     "Último",
        "sNext":     "Siguiente",
        "sPrevious": "Anterior"
    },
    "oAria": {
        "sSortAscending":  ": Activar para ordenar la columna de manera ascendente",
        "sSortDescending": ": Activar para ordenar la columna de manera descendente"
    }
}
    } );
} );
    $('#finTurno').click(function () {

        var sHtml = '';
        sHtml += '<center><form class="form-horizontal daos_formulario" id="formCierreTurno">';
        sHtml += '<h2 class="col-sm-12">Por favor ingrese el dinero actual</h2>';
        sHtml += '<h3 class="col-sm-12">NO tenga en cuenta el dinero de base</h3>';
        sHtml += '<div class="form-group"><label class="col-sm-2 control-label" for="b50">$50.000</label><div class="col-sm-4"><input id="b50" class="form-control required" name="b50" type="number" placeholder="Cantidad de billetes de $50.000"></div><label class="col-sm-2 control-label" for="b20">$20.000</label><div class="col-sm-4"><input id="b20"  class="form-control required" name="b20" type="number" placeholder="Cantidad de billetes de $20.000"></div></div>';
        sHtml += '<div class="form-group"><label class="col-sm-2 control-label" for="b10">$10.000</label><div class="col-sm-4"><input id="b10" class="form-control required" name="b10" type="number" placeholder="Cantidad de billetes de $10.000"></div><label class="col-sm-2 control-label" for="b5">$5.000</label><div class="col-sm-4"><input id="b5"  class="form-control required" name="b5" type="number" placeholder="Cantidad de billetes de $5.000"></div></div>';
        sHtml += '<div class="form-group"><label class="col-sm-2 control-label" for="b2">$2.000</label><div class="col-sm-4"><input id="b2" class="form-control required" name="b2" type="number" placeholder="Cantidad de billetes de $2.000"></div><label class="col-sm-2 control-label" for="b1">$1.000</label><div class="col-sm-4"><input id="b1"  class="form-control required" name="b1" type="number" placeholder="Cantidad de billetes de $1.000"></div></div>';
        sHtml += '<div class="form-group"><label class="col-sm-2 control-label" for="moneda">Monedas</label><div class="col-sm-4"><input id="moneda" class="form-control required" name="moneda" type="number" placeholder="Cantidad de monedas"></div></div>';
        sHtml += '</form></center>';
        BootstrapDialog.show({
            title: 'Fin Turno',
            message: sHtml,
            buttons: [{
                    id: 'btn-enviar',
                    label: 'Enviar',
                    icon: 'glyphicon glyphicon-send',
                    action: function (dialogRef) {
                        BootstrapDialog.show({
                            title: 'Fin Turno',
                            message: 'Desea finalizar turno con los valores ingresados?',
                            buttons: [{
                                    label: 'Enviar',
                                    id: 'btn-enviar-final',
                                    icon: 'glyphicon glyphicon-send',
                                    autospin: true,
                                    action: function (dialog) {
                                        $.each(BootstrapDialog.dialogs, function (id, dialog) {
                                            dialog.setClosable(false);
                                            dialog.enableButtons(false);
                                        });

                                        $.ajax({
                                            url: '/parqueadero/ajax/finTurno',
                                            dataType: "json",
                                            type: 'POST',
                                            contentType: 'application/x-www-form-urlencoded',
                                            data: $("#formCierreTurno").serialize(),
                                            success: function (data) {
                                                $('#btn-enviar').remove();
                                                $('#btn-cerrar').remove();
                                                $('#btn-enviar-final').remove();
                                                dialog.setClosable(true);
                                                dialog.enableButtons(true);
                                                var response = '';
                                                if (data === 1) {
                                                    response = 'Cierre realizado correctamente, puede cerrar sesion y finalizar su jornada.';
                                                } else {
                                                    response = 'Hubo un error intente de nuevo por favor';

                                                }
                                                dialogRef.setMessage(response);
                                                dialogRef.addButtons([{
                                                    id: 'btn-cerrar-nuevo',
                                                    label: 'Cerrar',
                                                    cssClass: 'btn-primary',
                                                    action: function () {
                                                        location.reload();
                                                    }
                                                }]);
                                            }

                                        });
                                    }
                                }, {
                                    label: 'Cerrar',
                                    cssClass: 'btn-primary',
                                    action: function (dialog) {
                                        dialog.close();
                                    }
                                }]
                        });
                    }
                }, {
                    id: 'btn-cerrar',
                    label: 'Cerrar',
                    cssClass: 'btn-primary',
                    action: function (dialog) {
                        dialog.close();
                    }
                }]
        });
    });
    $("#tarifa_especial").change(function() {
    if(this.checked) {
        $(".group_tarifa_especial").show();
        $("#valor_tarifa_especial").removeClass('no-validate');
        $("#horas_tarifa_especial").removeClass('no-validate');
    }else{
        $(".group_tarifa_especial").hide();
        $("#valor_tarifa_especial").addClass('no-validate');
        $("#horas_tarifa_especial").addClass('no-validate');
    }
});
</script>