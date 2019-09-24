<h2>Gasto / <?= $operacion ?></h2>
<hr/>
<div>


    <form class="form-horizontal daos_formulario" action="" method="POST">



        <div class="form-group">
            <label class="col-sm-2 control-label" for="detalle_par_gasto">Detalle</label>
            <div class="col-sm-5">
                <input value="<?=$gasto['detalle_par_gasto'] ?>" class="form-control required"   name="detalle_par_gasto" type="text" id="detalle_par_gasto" placeholder="Detalle" >
            </div>
        </div>
        
        
        <div class="form-group">
            <label class="col-sm-2 control-label" for="valor_par_gasto">Valor</label>
            <div class="col-sm-5">
                <input class="form-control required"  name="valor_par_gasto" type="number" id="valor_par_gasto" placeholder="Valor" value="<?=$gasto['valor_par_gasto'] ?>">
            </div>
        </div>
        


        <div class="form-group">
            <div class="col-sm-5 col-md-offset-2">
                <button type="submit" class="btn btn-primary">Guardar</button>
            </div>
        </div>
    </form>
</div>
