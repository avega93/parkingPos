/*
 * Instructions
 * 
 * Data grid: daos_datagrid
 * Data grid php: daos_grid_php
 *                  formatear numero: .daos_grid_php_number 
 * Date picker: daos_datepicker
 * Editor de texto: ckeditor
 * Formulario validado: daos_formulario
 * 
 *
 */
//
//
// Crear las variable globales
//

//guarda las estructura de tr para el grid
var gridPHPInfo = {};
var gridPaginaActual = 1;
var gridLike = "";
var gridOrden = "";

//
//
// ESPERAR EL DOM
//
$(window).load(function () {
    /*
     * 
     * 
     * MASCARAS PARA NUMEROS
     * 
     * 
     */
    $('.number').keyup(function () {
        daos_input_number();
    });
    //arranca todas las mascaras
    daos_input_number();
});
function daos_input_number() {
    setTimeout(function () {
        $('.number').each(function () {
            numero = $(this).val();
            //si es vacio se coloca 0
            if (numero == '')
                numero = 0;
            //elimina el div de daos_input_number
            $(this).parent('div').find('.daos_input_number').remove();
            //verifica que sea un numero
            if (!isNaN(numero)) {
                $(this).parent('div').append("<div class='daos_input_number'>" + number_format(numero) + "</div>");
            }
        });
    }, 200);
}
function number_format(nStr) {
    nStr += '';
    x = nStr.split('.');
    x1 = x[0];
    x2 = x.length > 1 ? '.' + x[1] : '';
    var rgx = /(\d+)(\d{3})/;
    while (rgx.test(x1)) {
        x1 = x1.replace(rgx, '$1' + ',' + '$2');
    }
    return x1 + x2;
}
/*
 * 
 * 
 * MASCARAS PARA DEL GRID PHP
 * 
 * 
 */
function daos_grid_php_number() {
    setTimeout(function () {
        $('.daos_grid_php_number').each(function () {
            $(this).html(number_format($(this).html()));
        });
    }, 100);
}
//
//
// ESPERAR EL DOM
//
$(window).load(function () {
    /*
     * 
     * DATA GRID
     * 
     */
    $('.daos_datagrid').dataTable({
        "bJQueryUI": true,
        "bSort": true,
        "bPaginate": true,
        "sPaginationType": "full_numbers",
        "oLanguage": {
            "sProcessing": "Procesando...",
            "sLengthMenu": "Mostrar _MENU_ registros",
            "sZeroRecords": "No se encontraron resultados",
            "sEmptyTable": "Ningún dato disponible en esta tabla",
            "sInfo": "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
            "sInfoEmpty": "Mostrando registros del 0 al 0 de un total de 0 registros",
            "sInfoFiltered": "(filtrado de un total de _MAX_ registros)",
            "sInfoPostFix": "",
            "sSearch": "Buscar:",
            "sUrl": "",
            "sInfoThousands": ",",
            "sLoadingRecords": "Cargando...",
            "oPaginate": {
                "sFirst": "Primero",
                "sLast": "Último",
                "sNext": "Siguiente",
                "sPrevious": "Anterior"
            },
            "oAria": {
                "sSortAscending": ": Activar para ordenar la columna de manera ascendente",
                "sSortDescending": ": Activar para ordenar la columna de manera descendente"
            }
        }
    });
    /*
     * 
     * 
     * VALIDACIONES FORMULARIO
     * 
     * 
     */
    $.validator.addMethod(
            "regex",
            function (value, element, regexp) {
                var re = new RegExp(regexp);
                return this.optional(element) || re.test(value);
            },
            "Placa Incorrecta."
            );
    $(jQuery.extend(jQuery.validator.messages, {
        required: "Este campo es requerido",
        remote: "Porfavor corrije el valor de este campoPlease fix this field.",
        email: "Ingresa una dirección de correo válida.",
        url: "Ingresa una URL válida.",
        date: "Ingresa una fecha válida.",
        dateISO: "Ingresa una fehca válida (ISO).",
        number: "Ingresa un número válido.",
        digits: "Ingresa sólo letras.",
        creditcard: "Ingresa un número de tarjeta de crédito válido.",
        equalTo: "Ingresa el nuevo valor de nuevo.",
        accept: "Ingresa un valor con extensión válida.",
        maxlength: $.validator.format("Porfavor ingresa menos de {0} caractéres."),
        minlength: $.validator.format("Porfavor ingresa almenos {0} caractéres."),
        rangelength: $.validator.format("Porfavor ingresa un valor entre {0} y {1} caractéres de longitud."),
        range: $.validator.format("Ingresa un valor entre {0} y {1}."),
        max: $.validator.format("Ingresa un valor menor o igual que {0}."),
        min: $.validator.format("Ingresa un valor mayor o igual a {0}.")
    }));
    $(function () {
        $('.daos_formulario').validate({
            errorElement: "span",
            ignore: [],
            highlight: function (element) {
                $(element).closest('.control-group')
                        .removeClass('success').addClass('error');
            },
            success: function (element) {
                element
                        .text('Bien!').addClass('help-inline')
                        .closest('.control-group')
                        .removeClass('error').addClass('success');
            }
        });
    });
    /*
     * 
     * 
     * DATA PICKER
     * 
     * 
     */
    $(function () {
        $(".daos_datepicker").datepicker({
            dateFormat: "yy-mm-dd",
            changeMonth: true,
            changeYear: true
        });
    });
    /*
     * 
     * 
     * DATA GRID PHP
     * 
     * 
     */
    $(function () {

        tloading = '<tr>';
        tloading += '    <td colspan="***">';
        tloading += '        <div class="progress">';
        tloading += '            <div class="progress-bar progress-bar-warning progress-bar-striped active" role="progressbar"  style="min-width: 100%;">';
        tloading += '                Cargando...';
        tloading += '            </div>';
        tloading += '        </div>';
        tloading += '    </td>';
        tloading += '</tr>';
        thead = '<div class="daos_grid_filtros_top col-md-12">';
        thead += '    <form>';
        thead += '        <div style="float: left; padding: 5px;">Ver</div>';
        thead += '        <select class="form-control gridNumRow" style="float: left; width: 66px">';
        thead += '            <option>10</option>';
        thead += '            <option>25</option>';
        thead += '            <option>50</option>';
        thead += '            <option>100</option>';
        thead += '            <option>250</option>';
        thead += '            <option>500</option>';
        thead += '            <option>1000</option>';
        thead += '        </select>';
        thead += '        <div style="float: left; padding: 5px;">registros</div>';
        thead += '    </form>';
        thead += '    <div class="input-group" style="">';
        thead += '        <input type="text" class="form-control gridBtnBuscarInput" placeholder="Buscar por..." value="">';
        thead += '        <span class="input-group-btn">';
        thead += '            <button class="btn btn-default gridBtnBuscar" type="button">';
        thead += '                <span class="glyphicon glyphicon-search daos_grid_glyphicon" aria-hidden="true"></span>';
        thead += '            </button>';
        thead += '        </span>';
        thead += '    </div>';
        thead += '</div>';
        thead += '<div class="daos_grid_php_inner">';
        tfoot = '</div>';
        tfoot += '<div class="daos_grid_filtros_bottom col-md-12">';
        tfoot += '    <div class="daos_paginacion_texto col-md-6">Mostrando del <griddel>0</griddel> al <gridal>0</gridal> de <total>0</total> registros</div>';
        tfoot += '    <nav>';
        tfoot += '        <ul class="pagination daos_paginacion">';
        tfoot += '            <li class="gridPrimero"><a href="#"><span class="glyphicon glyphicon-fast-backward" aria-hidden="true"></span></a></li>';
        tfoot += '            <li class="gridAnterior"><a href="#"><span class="glyphicon glyphicon-step-backward" aria-hidden="true"></span></a></li>';
        tfoot += '            <li class="gridSiguiente"><a href="#"><span class="glyphicon glyphicon-step-forward" aria-hidden="true"></span></a></li>';
        tfoot += '            <li class="gridUltimo"><a href="#"><span class="glyphicon glyphicon-fast-forward" aria-hidden="true"></span></a></li>';
        tfoot += '        </ul>';
        tfoot += '    </nav>';
        tfoot += '</div>';
        torderup = '<span class="glyphicon glyphicon-arrow-up daos_grid_glyphicon" aria-hidden="true"></span>';
        torderdown = '<span class="glyphicon glyphicon-arrow-down daos_grid_glyphicon" aria-hidden="true">';
        //crear el marco base dentro de la tabla
        $(".daos_grid_php table").append('<tfoot></tfoot>');
        $(".daos_grid_php table tfoot").append($(".daos_grid_php thead").html());
        //carga los controles
        $(".daos_grid_php").html(thead + $(".daos_grid_php").html() + tfoot);
        //realiza la primera carga
        $(".daos_grid_php").each(function () {
            //carga los tr
            gridPHPInfo[$(this).attr("info")] = $(this).find('.gridBody').html();
            gridLoadData($(this));
        });
        //se inician los eventos para el numero de registros por pagina
        $('.gridNumRow').change(function () {
            gridPaginaActual = 1;
            gridLoadData($(this).parent('form').parent('.daos_grid_filtros_top').parent('.daos_grid_php'));
        });
        //carga de los eventos para la paginación general
        $('.gridPrimero').click(function () {
            $(this).parent('ul').find('.gridPaginaVariable').first().trigger('click');
        });
        $('.gridUltimo').click(function () {
            $(this).parent('ul').find('.gridPaginaVariable').last().trigger('click');
        });
        $('.gridAnterior').click(function () {
            gridPaginaActual = $(this).parent('ul').find('.active a').html() - 1;
            if (gridPaginaActual == 0)
                gridPaginaActual = 1;
            gridLoadData($(this).parent('ul').parent('nav').parent('.daos_grid_filtros_bottom').parent('.daos_grid_php'));
        });
        $('.gridSiguiente').click(function () {
            gridPaginaActual = ($(this).parent('ul').find('.active a').html() * 1) + 1;
            if (gridPaginaActual > $(this).parent('ul').find('.gridPaginaVariable').last().find('a').html())
                gridPaginaActual = $(this).parent('ul').find('.gridPaginaVariable').last().find('a').html();
            gridLoadData($(this).parent('ul').parent('nav').parent('.daos_grid_filtros_bottom').parent('.daos_grid_php'));
        });
        // activar el buscador
        $('.gridBtnBuscar').click(function () {
            gridPaginaActual = 1;
            gridLoadData($(this).parent('span').parent('div').parent('.daos_grid_filtros_top').parent('.daos_grid_php'));
        });
        //solo permitir buscar letras y numeros
        $('.gridBtnBuscarInput').keypress(function (e) {
            //permite el paso del signo +
            //permite el paso de los numero del 0 al 9 48..57
            //permite el paso de todas las letras a .... z 97..122
            //permite el paso de todas las letras A .... Z 65..90
            //permite el paso de ñ Ñ 209 241
            //permite el paso de especios 32
            if (!((48 <= e.which && e.which <= 57) || (65 <= e.which && e.which <= 90) || (97 <= e.which && e.which <= 122) || e.which == 43 || e.which == 209 || e.which == 241 || e.which == 32)) {
                e.preventDefault();
            }



        });
        $('.gridBtnBuscarInput').keyup(function () {
            $('.gridBtnBuscar').trigger('click');
        })

        //evento para ordenar
        $('.daos_grid_php').find('table thead tr th').click(function () {
            if ($(this).attr("order") != "") {
                gridOrden = $(this).attr("order");
                if ($(this).find('.glyphicon-arrow-down').length) {
                    $('.daos_grid_php').find('th').find('.daos_grid_glyphicon').remove();
                    $(this).append(torderup);
                    gridOrden = $(this).attr("order") + "-DESC";
                } else
                if ($(this).find('.glyphicon-arrow-up').length) {
                    $('.daos_grid_php').find('th').find('.daos_grid_glyphicon').remove();
                    gridOrden = "";
                } else {
                    $('.daos_grid_php').find('th').find('.daos_grid_glyphicon').remove();
                    $(this).append(torderdown);
                    gridOrden = $(this).attr("order") + "-ASC";
                }
                gridPaginaActual = 1;
                gridLoadData($(this).parent('tr').parent('thead').parent('table').parent('.daos_grid_php_inner').parent('.daos_grid_php'));
            }
        });
    });
});
function gridLoadData(grid) {

    //arranca el loading
    tloading = tloading.replace('***', grid.find('table thead tr th').length);
    grid.find('table tbody').html(tloading);
    //arma el campo buscar si es diferente de vacio
    if (grid.find('.gridBtnBuscarInput').val() != "") {
        //separa todos los campos de la tabla
        tr = gridPHPInfo[grid.attr("info")];
        cmp = new Array();
        $('.daos_grid_php').find('table thead tr').find('th').each(function () {
            if ($(this).attr('order') != "") {
                cmp[cmp.length] = $(this).attr('order');
            }
        });
        cmp2 = new Array();
        for (i = 0; i < cmp.length; i++) {
            cmp2Guarda = true;
            for (i2 = 0; i2 < cmp2.length; i2++) {
                if (cmp2[i2] == cmp[i]) {
                    cmp2Guarda = false;
                }
            }
            if (cmp2Guarda) {
                cmp2[cmp2.length] = cmp[i];
            }
        }
        cmp = cmp2;
        gridLike = "";
        busqueda = grid.find('.gridBtnBuscarInput').val().split('+');
        for (i = 0; i < cmp2.length; i++) {
            if (gridLike != "") {
                gridLike += " OR ";
            }
            for (j = 0; j < busqueda.length; j++) {
                if (busqueda[j] != "") {
                    if (j > 0)
                        gridLike += " OR ";
                    gridLike += "UPPER(" + cmp[i] + ")" + " LIKE UPPER('**" + busqueda[j] + "**')";
                }
            }
        }
        gridLike = encodeURIComponent(gridLike);
    }

    // lanza pa petición
    $.ajax({
        url: "/administrador/ajax/gridPHP/" + grid.attr("info") + "/" + grid.find('.gridNumRow').val() + "/" + ((gridPaginaActual - 1) * grid.find('.gridNumRow').val() + "/" + gridOrden + "?l=" + gridLike),
        cache: false,
        success: function (e) {
            datos = {};
            eval("datos = " + e);
            //carga en la tabla el total de registros
            grid.find('total').html(datos.total);
            //carga la informacion de los registros que se estan viendo
            grid.find('griddel').html(((gridPaginaActual - 1) * grid.find('.gridNumRow').val()) + 1);
            grid.find('gridal').html(((gridPaginaActual - 1) * grid.find('.gridNumRow').val()) + (grid.find('.gridNumRow').val() * 1));
            if (grid.find('gridal').html() > grid.find('total').html()) {
                grid.find('gridal').html(grid.find('total').html());
            }

            //elimina todos los numeros de paginas
            grid.find('.gridPaginaVariable').remove();
            //carga la paginacion
            npaginas = datos.total / grid.find('.gridNumRow').val();
            if (npaginas > 10) {
                if (gridPaginaActual > 1) {
                    grid.find('.gridSiguiente').before('<li class="gridPaginaVariable"><a href="#">' + 1 + '</a></li>');
                }
//                if (gridPaginaActual > 2) {
//                    grid.find('.gridSiguiente').before('<li class="gridPaginaVariable"><a href="#">' + 2 + '</a></li>');
//                }

                gridPaginaActual = gridPaginaActual * 1;
//                if (gridPaginaActual - 3 > 1) {
//                    grid.find('.gridSiguiente').before('<li class="gridPaginaVariable "><a href="#" style="font-weight: bold;">' + (gridPaginaActual - 3) + '</a></li>');
//                }
                if (gridPaginaActual - 2 > 1) {
                    grid.find('.gridSiguiente').before('<li class="gridPaginaVariable "><a href="#" style="font-weight: bold;">' + (gridPaginaActual - 2) + '</a></li>');
                }
                if (gridPaginaActual - 1 > 1) {
                    grid.find('.gridSiguiente').before('<li class="gridPaginaVariable "><a href="#" style="font-weight: bold;">' + (gridPaginaActual - 1) + '</a></li>');
                }

                grid.find('.gridSiguiente').before('<li class="gridPaginaVariable active"><a href="#" style="font-weight: bold;">' + (gridPaginaActual * 1) + '</a></li>');

                //redondea y fija la ultima pagina
                npaginasFinal = Math.round(npaginas);
                if (npaginasFinal < npaginas) {
                    npaginasFinal = npaginasFinal + 1;
                }

                gridPaginaActual = gridPaginaActual * 1;
                if (gridPaginaActual + 1 < npaginasFinal) {
                    grid.find('.gridSiguiente').before('<li class="gridPaginaVariable "><a href="#" style="font-weight: bold;">' + (gridPaginaActual + 1) + '</a></li>');
                }
                if (gridPaginaActual + 2 < npaginasFinal) {
                    grid.find('.gridSiguiente').before('<li class="gridPaginaVariable "><a href="#" style="font-weight: bold;">' + (gridPaginaActual + 2) + '</a></li>');
                }
//                if (gridPaginaActual + 3 < npaginasFinal) {
//                    grid.find('.gridSiguiente').before('<li class="gridPaginaVariable "><a href="#" style="font-weight: bold;">' + (gridPaginaActual + 3) + '</a></li>');
//                }
                
                
//                if (gridPaginaActual < npaginasFinal - 1) {
//                    grid.find('.gridSiguiente').before('<li class="gridPaginaVariable"><a href="#">' + (npaginasFinal - 1) + '</a></li>');
//                }
                if (gridPaginaActual < npaginasFinal) {
                    grid.find('.gridSiguiente').before('<li class="gridPaginaVariable"><a href="#">' + npaginasFinal + '</a></li>');
                }

            } else {
                for (i = 0; i < npaginas; i++) {
                    //selecciona la pagina activa
                    if (gridPaginaActual == i + 1)
                        grid.find('.gridSiguiente').before('<li class="gridPaginaVariable active"><a href="#">' + (i + 1) + '</a></li>');
                    else
                        grid.find('.gridSiguiente').before('<li class="gridPaginaVariable"><a href="#">' + (i + 1) + '</a></li>');
                }
            }

            //carga el evento para las paginas
            $('.gridPaginaVariable').click(function () {
                gridPaginaActual = $(this).find('a').html();
                gridLoadData(grid);
            });
            //separa todos los campos de la tabla
            tr = gridPHPInfo[grid.attr("info")];
            cmp = {};
            trs = tr.split("{");
            for (x = 1; x < trs.length; x++) {
                tmp = trs[x].split("}");
                cmp[x - 1] = tmp[0];
            }

            rows = "";
            for (i = 0; i < datos.rows.length; i++) {
                tr = gridPHPInfo[grid.attr("info")];
                for (j = 0; j < x - 1; j++) {
                    tr = tr.replace("{" + cmp[j] + "}", datos.rows[i][cmp[j]]);
                }
                rows += tr;
            }

            grid.find('.gridBody').html(rows);
            //borra el like
            gridLike = "";
            $(document).ready(function () {
                $('.btn-danger').click(function (event) {
                    return confirm("¿Realmente desea eliminar el registro?");
                });
            });

            daos_grid_php_number();
        }
    });
}


$(document).ready(function () {
    $("a").dblclick(function (e) {
        e.preventDefault();
        $(this).attr('disabled', 'disabled');
    });
    $(".btn").dblclick(function (e) {
        e.preventDefault();
        $(this).attr('disabled', 'disabled');
    });

    
    $("form").submit(function () {
        var isSubmitted = false;
        if (!isSubmitted) {
            isSubmitted = true;
        } else {
            return false;
        }
    });
    var date = new Date();
    var today = new Date(date.getFullYear(), date.getMonth(), date.getDate());
    $('#datepicker').datepicker({format: "yyyy-mm-dd",
        todayHighlight: true
    });
    $('#datepicker').datepicker("update", today);
    
    setTimeout(function(){if($("#placa_entrada").length){$("#placa_entrada").rules("add", { regex: "^[a-zA-Z]{3}[0-9]{2}[a-zA-Z0-9]{0,1}$" })}},100);
});

