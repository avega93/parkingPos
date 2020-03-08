<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="author" content="SAJA SYSTEMS">
        <link rel="shortcut icon" href="/static/img/logo.png">
        <title>BIKER48</title>

        <link href="/static/css/ui-lightness/jquery-ui-1.10.3.custom.min.css" rel="stylesheet">
        <link href="/static/css/bootstrap.min.css" rel="stylesheet">
        <link href="/static/css/bootstrap-theme.min.css" rel="stylesheet">
        <link href="/static/css/administrador.css" rel="stylesheet">
        <link href="/static/css/daos.css" rel="stylesheet">
        <link href="/static/fonts/fontawesome/css/all.css" rel="stylesheet">
        <link href="/static/plugins/datepicker/bootstrap-datepicker.min.css" rel="stylesheet">
        <link href="/static/plugins/bootstrap-dialog/bootstrap-dialog.min.css" rel="stylesheet">
        <link href="/static/plugins/datatables.min.css" rel="stylesheet">

        <script type="text/javascript" src="/static/js/jquery-2.0.3.min.js"></script>
        <script type="text/javascript" src="/static/js/jquery-ui-1.10.3.custom.min.js"></script>
        <script type="text/javascript" src="/static/js/jquery.dataTables.min.js"></script>
        <script type="text/javascript" src="/static/js/jquery.validate.min.js"></script>
        <script type="text/javascript" src="/static/js/bootstrap.min.js"></script>
        <script type="text/javascript" src="/static/plugins/ckeditor/ckeditor.js"></script>
        <script type="text/javascript" src="/static/plugins/datepicker/bootstrap-datepicker.min.js"></script>
        <script type="text/javascript" src="/static/js/daos.js"></script>
        <script type="text/javascript" src="/static/plugins/bootstrap-dialog/bootstrap-dialog.min.js"></script>
        <script type="text/javascript" src="/static/plugins/datatables.min.js"></script>


    </head>
    <body>
        <?php
        $privilegios = $this->acceso->privilegios();
        ?>
        <nav class="navbar navbar-default">
            <div class="container-fluid">
                <!-- Brand and toggle get grouped for better mobile display -->
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
                        <span class="sr-only">Toggle navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                    <a class="navbar-brand" href="#" style="padding: 0px;" >
                        <img alt="Brand" src="/static/img/logo-superior.png" width="150">
                    </a>
                </div>

                <!-- Collect the nav links, forms, and other content for toggling -->
                <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                    <ul class="nav navbar-nav navbar-right">

                        <?php if (isset($privilegios[100])) { ?>
                            <li class="<?php if ($menu_activo == 'usuario') echo 'active'; ?> dropdown">
                                <a href="#" class="dropdown-toggle " data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><span class="glyphicon glyphicon-check" aria-hidden="true"></span> &nbsp; Usuarios <b class="caret"></b></a>
                                <ul class="dropdown-menu">
                                    <?php if (isset($privilegios[103])) { ?>
                                        <li><a href="/administrador/usuario/lista">Lista de usuarios</a></li>
                                    <?php } ?>
                                    <?php if (isset($privilegios[102])) { ?>
                                        <li><a href="/administrador/rol/lista">Roles</a></li>
                                    <?php } ?>
                                    <?php if (isset($privilegios[101])) { ?>
                                        <li><a href="/administrador/privilegio/lista">Privilegios</a></li>
                                    <?php } ?>
                                </ul>
                            </li>
                        <?php } ?>
                        <?php if (isset($privilegios[200])) { ?>
                            <li class="<?php if ($menu_activo == 'parqueadero') echo 'active'; ?> dropdown">
                                <a href="#" class="dropdown-toggle " data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><span class="fa fa-motorcycle" aria-hidden="true"></span> &nbsp; Parqueadero <b class="caret"></b></a>
                                <ul class="dropdown-menu">
                                    <?php if (isset($privilegios[201])) { ?>
                                        <li><a href="/parqueadero/parqueo/lista"><i class="fa fa-parking"></i> Parqueo</a></li>
                                    <?php } ?>
                                    <?php if (isset($privilegios[210])) { ?>
                                        <li><a href="/parqueadero/tarifas/lista"><i class="fa fa-donate"></i> Tarifas</a></li>
                                    <?php } ?>
                                    <?php if (isset($privilegios[220])) { ?>
                                        <li><a href="/parqueadero/mensualidad/lista"><i class="glyphicon glyphicon-calendar"></i> Mensualidad</a></li>
                                    <?php } ?>
                                    <?php if (isset($privilegios[230])) { ?>
                                        <li><a href="/parqueadero/historial/lista"><i class="fa fa-history"></i> Historial</a></li>
                                    <?php } ?>
                                    <?php if (isset($privilegios[240])) { ?>
                                        <li><a href="/parqueadero/parametros/lista"><i class="glyphicon glyphicon-edit"></i> Parametros</a></li>
                                    <?php } ?>

                                </ul>
                            </li>
                        <?php } ?>
                        <?php if (isset($privilegios[300])) { ?>
                            <li class="<?php if ($menu_activo == 'productos') echo 'active'; ?> dropdown">
                                <a href="#" class="dropdown-toggle " data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><span  class="fa fa-store" aria-hidden="true"></span> &nbsp; Productos <b class="caret"></b></a>
                                <ul class="dropdown-menu">
                                    <?php if (isset($privilegios[301])) { ?>
                                        <li><a href="/productos/venta/lista"><i class="fa fa-cash-register"></i> Venta</a></li>
                                    <?php } ?>
                                    <?php if (isset($privilegios[310])) { ?>
                                        <li><a href="/productos/ingreso/lista"><i class="fa fa-box-open"></i> Crear e Ingresar</a></li>
                                    <?php } ?>
                                    <?php if (isset($privilegios[320])) { ?>
                                        <li><a href="/productos/historial/lista"><i class="fa fa-history"></i> Historial Venta</a></li>
                                    <?php } ?>

                                </ul>
                            </li>
                        <?php } ?>
                             <?php if (isset($privilegios[400])) { ?>
                            <li class="<?php if ($menu_activo == 'productos') echo 'active'; ?> dropdown">
                                <a href="#" class="dropdown-toggle " data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><span  class="fa fa-money-check-alt" aria-hidden="true"></span> &nbsp; Cierres <b class="caret"></b></a>
                                <ul class="dropdown-menu">
                                    <?php if (isset($privilegios[401])) { ?>
                                        <li><a href="/cierres/historial/lista"><i class="fa fa-history"></i> Historial</a></li>
                                    <?php } ?>
                                    <?php if (isset($privilegios[410])) { ?>
                                        <li><a href="/cierres/gasto/lista"><i class="fa fa-coins"></i> Gastos</a></li>
                                    <?php } ?>
                                    

                                </ul>
                            </li>
                        <?php } ?>
                        <li class="divider-vertical"></li>
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle " data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><span class="glyphicon glyphicon-user" aria-hidden="true"></span> &nbsp; Perfil <b class="caret"></b></a>
                            <ul class="dropdown-menu">
                                <?php if (isset($privilegios[104])) { ?>
                                    <li class="dropdown-header"><?= $this->session->userdata("nombres_admin_usuario") ?> <?= $this->session->userdata("apellidos_admin_usuario") ?></li>
                                    <li><a href="/contrasena"><span class="glyphicon glyphicon-edit" aria-hidden="true"></span> &nbsp; Cambiar contrase&ntilde;a</a></li>
                                    <li class="divider"></li>
                                <?php } ?>
                                <li class="dropdown-header">Seguridad</li>
                                <li><a href="/administrador/login/validar"><span class="glyphicon glyphicon-off" aria-hidden="true"></span> &nbsp; Salir</a></li>
                            </ul>
                        </li>
                    </ul>
                </div><!-- /.navbar-collapse -->
            </div><!-- /.container-fluid -->
        </nav>


        <!--contenedor-->
        <div class="container">
            <?php if (count($breadcrumb) > 0) { ?>
                <ul class="breadcrumb">
                    <?php foreach ($breadcrumb as $link) { ?>
                        <li><a href="<?= $link['link'] ?>"><?= $link['nombre'] ?></a> <span class="divider"></span></li>
                    <?php } ?>
                </ul>
            <?php } ?>
            <div class="well contenedor">