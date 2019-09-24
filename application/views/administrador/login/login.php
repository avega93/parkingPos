<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="author" content="SAJA SYSTEMS S.A.S.">
        <link rel="shortcut icon" href="/static/img/logo.png">
        <title>BIKER48</title>
        <link href="/static/css/bootstrap.min.css" rel="stylesheet">
        <link href="/static/css/bootstrap-theme.min.css" rel="stylesheet">
        <link href="/static/css/login.css" rel="stylesheet">
    </head>
    <body>
        <div class="container">
            <form class="form-signin" method="POST" autocomplete="off">
                <div class="login"></div>
                <!--<img src="/static/img/logo.png" width="50%" />-->
                <?php if ($error === 1): ?>
                    <div class="alert alert-danger">
                        <span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>
                        Los datos de acceso no son correctos.
                    </div>
                <?php endif; ?>
                <?php if ($error == 3): ?>
                    <div class="alert alert-success">
                        <span class="glyphicon glyphicon-check" aria-hidden="true"></span>
                        El correo de recuperaci&oacute;n de su contraseña ha sido enviado. 
                        El correo puede tardar hasta 20 minutos en llegar a su bandeja de entrada, 
                        si ha pasado este tiempo y no ha llegado el correo con su nueva contrase&ntilde;a por favor verifique su bandeja de SPAM
                    </div>
                <?php endif; ?>
                <?php if ($error === 2): ?>
                    <div class="alert alert-danger">
                        <span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>
                        Para realizar esta acci&oacute;n debe identificarse.
                    </div>
                <?php endif; ?>
                <h2 class="form-signin-heading from-signin-copy">Ingreso area segura </h2>
                <input type="text" name="mail" class="form-control" placeholder="Direcci&oacute;n E-mail" autofocus>
                <input type="password" name="password" class="form-control" placeholder="Contrase&ntilde;a">
                <button class="btn btn-large btn-block btn-primary" type="submit">Entrar ahora</button>
                <br/>
                <div style="text-align: center">
                    <a href="/recuperar">Recuperar contrase&ntilde;a</a>
                </div>
                <h2 class="form-signin-heading">&nbsp;</h2>
                <h6 class="form-signin-heading from-signin-copy">Desarrollado por - <a href="#">SAJA SYSTEMS</a> </h6>
                <h6 class="form-signin-heading from-signin-copy">Copyright &copy; 2019 - Todos los derechos reservados </h6>
            </form>
        </div>
    </body>
</html>
