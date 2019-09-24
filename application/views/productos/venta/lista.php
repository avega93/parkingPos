<?php if ($alert == 'ok') { ?>
    <div class="alert alert-success">
        <button type="button" class="close" data-dismiss="alert">&times;</button>
        Venta Registrada.
    </div>
<?php } ?>
<?php if ($alert == 'error') { ?>
    <div class="alert alert-danger">
        <button type="button" class="close" data-dismiss="alert">&times;</button>
        <strong>Error!</strong> Ocurrio un error, intente de nuevo
    </div>
<?php } ?>

<?php
$privilegios = $this->acceso->privilegios();
?>
<h2>Venta Productos</h2>
<hr/>
<br/>

<?php
    if ($registrar) {
        ?>

        <form class="form-horizontal daos_formulario" id="ventaProductos" action="" method="POST" autocomplete="off" role="form">
            
            <div class="form-group">

                <div class="col-sm-4">
                    <select class="form-control inputsEntrada" required id="id_par_producto" name="id_par_producto" >
                        <option value="">Seleccione un producto </option> 
                        <?php foreach($lista_productos as $producto){ ?>
                        <option value="<?=  $producto['id_par_producto'] ?>" data-price="<?=$producto['valor_par_producto']?>"><?=$producto['nombre_par_producto'] ?></option>
                        <?php  } ?>
                    </select>
                </div>
                <?php if(isset($privilegios[302])) { ?>
                <div class="col-sm-2">
                    <input class="form-control  inputsEntrada"  name="descuento" type="number" id="descuento" placeholder="Descuento?">
                </div>
                <?php } ?>
                <div class="col-sm-2">
                    <input class="form-control  inputsEntrada" required name="cantidad_par_producto" type="number" id="cantidad_par_producto" placeholder="Cant.">
                </div>
                <div class="col-sm-2">
                    <label class="inputsEntrada" id="precio_final"></label>
                </div>
                <div class="col-sm-2 ">
                    <button type="submit" id="sendForm" class="btn btn-primary inputsEntrada">Vender</button>
                </div>  
            </div>

        </form>
        <?php
    }
    ?>
    
<hr/>
<br/>
<div class="daos_grid_php" info="productos">
    <table class="table table-bordered table-hover table-condensed">
        <thead>
            <tr>
                <th order="p.id_par_producto" style="width: 40px !important"># ID</th>
                <th order="p.nombre_par_producto">Nombre</th>
                <th order="p.valor_par_producto">Valor</th>
            </tr>
        </thead>
        <tbody class="gridBody">
            <tr>
                <td>{id_par_producto}</td>
                <td>{nombre_par_producto}</td>
                <td>{valor_par_producto}</td>
            </tr>
        </tbody>
    </table>
</div>

<script>
    $('#id_par_producto,#descuento,#cantidad_par_producto').on('keyup change',function(){
       var precio = $('option:selected').attr('data-price');
       if(isNaN(precio)){
           precio = 0;
       }
       if(!$('#cantidad_par_producto').val()){
            $('#cantidad_par_producto').val('1');
        }
        precio = precio * $('#cantidad_par_producto').val();
        if($('#descuento').is(':visible')){
            precio = precio - ( precio *($('#descuento').val()/100));
        }
        
            
        $('#precio_final').text('$'+precio);
    });
    
    
</script>