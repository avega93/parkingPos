<h2>Ingreso Productos / <?= $operacion ?></h2>
<hr/>
<div>
    

    <form class="form-horizontal daos_formulario" action="" method="POST">
        <div class="form-group">
            <label class="col-sm-2 control-label" for="nombre_par_producto">Nombre Producto</label>
            <div class="col-sm-5">
                <input class="form-control required" value="<?= $producto['nombre_par_producto'] ?>" name="nombre_par_producto" type="text" id="nombre_par_producto" placeholder="Nombre Producto">
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-2 control-label" for="valor_par_producto">Valor Producto</label>
            <div class="col-sm-5">
                <input class="form-control required" value="<?= $producto['valor_par_producto'] ?>" name="valor_par_producto" type="number" id="valor_par_producto" placeholder="Precio">
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-2 control-label" for="cantidad_par_producto">Cantidad Productos</label>
            <div class="col-sm-5">
                <input class="form-control required" value="<?= $producto['cantidad_par_producto'] ?>" name="cantidad_par_producto" type="number" id="cantidad_par_producto" placeholder="Cantidades Producto">
            </div>
        </div>
        
        <div class="form-group">
            <div class="col-sm-5 col-md-offset-2">
                <button type="submit" class="btn btn-primary">Guardar</button>
            </div>
        </div>
    </form>
</div>
