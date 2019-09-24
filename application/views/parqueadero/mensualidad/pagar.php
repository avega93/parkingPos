<h2>Mensualidad / <?= $operacion ?></h2>
<hr/>
<div>


    <form class="form-horizontal daos_formulario" action="" method="POST">



        <div class="form-group">
            <label class="col-sm-2 control-label" for="placa_entrada">Placa</label>
            <div class="col-sm-5">
                <input value="<?=$mensualidad['placa_par_mensualidad'] ?>" class="form-control required"  style="text-transform:uppercase"   type="text"   maxlength="6" minlength="5" disabled>
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-2 control-label" for="modelo_par_mensualidad">Modelo Vehiculo</label>
            <div class="col-sm-5">
                <input class="form-control"  name="modelo_par_mensualidad" type="text" id="modelo_par_mensualidad" placeholder="Modelo Vehiculo" value="<?=$mensualidad['modelo_par_mensualidad'] ?>">
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-2 control-label" for="color_par_mensualidad">Color Vehiculo</label>
            <div class="col-sm-5">
                <input class="form-control"  name="color_par_mensualidad" type="text" id="color_par_mensualidad" placeholder="Color Vehiculo" value="<?=$mensualidad['color_par_mensualidad'] ?>">
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-2 control-label" for="nombre_par_mensualidad">Nombre Dueño</label>
            <div class="col-sm-5">
                <input class="form-control"  name="nombre_par_mensualidad" type="text" id="nombre_par_mensualidad" placeholder="Nombre Dueño" value="<?=$mensualidad['nombre_par_mensualidad'] ?>">
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-2 control-label" for="telefono_par_mensualidad">Telefono contacto</label>
            <div class="col-sm-5">
                <input class="form-control"  name="telefono_par_mensualidad" type="number" id="telefono_par_mensualidad" placeholder="Telefono Contacto" value="<?=$mensualidad['telefono_par_mensualidad'] ?>">
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-2 control-label" for="valor_par_mensualidad">Precio</label>
            <div class="col-sm-5">
                <input class="form-control required"  name="valor_par_mensualidad" type="number" id="valor_par_mensualidad" placeholder="Precio" value="<?=$mensualidad['valor_par_mensualidad'] ?>">
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-2 control-label" for="inicio_par_mensualidad">Fecha Inicio</label>
            <div class="input-group date col-sm-5" id="datepicker">
                <input type="text" class="form-control" value="<?=$mensualidad['inicio_par_mensualidad'] ?>" name="inicio_par_mensualidad" >
                <div class="input-group-addon">
                    <span class="glyphicon glyphicon-th"></span>
                </div>
            </div>
        </div>


        <div class="form-group">
            <div class="col-sm-5 col-md-offset-2">
                <button type="submit" class="btn btn-primary">Guardar</button>
            </div>
        </div>
    </form>
</div>
