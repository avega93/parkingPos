<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="author" content="SAJA SYSTEMS">
        <link rel="shortcut icon" href="/static/img/logo.png">
        <title>BIKER48</title>
        <link href="/static/css/bootstrap.min.css" rel="stylesheet">
        <link href="/static/css/bootstrap-theme.min.css" rel="stylesheet">
        <link href="/static/css/login.css" rel="stylesheet">
    </head>
    <body>
        <div class="container">
            <form class="form-signin" method="POST">
                <img src="/static/img/logo.png" width="100%" />
                <?php if ($error === 1): ?>
                    <div class="alert alert-success">
                        Su nueva contrase&ntilde;a ha sido enviada a su E-Mail.
                    </div>
                <?php endif; ?>
                <?php if ($error === 2): ?>
                    <div class="alert alert-danger">
                        El usuario no existe en el sistema.
                    </div>
                <?php endif; ?>
                <h3 class="form-signin-heading from-signin-copy">Recuperar contrase&ntilde;a </h3>
                <input type="text" name="mail" class="form-control" placeholder="Direcci&oacute;n E-mail" autofocus>
                <button class="btn btn-large btn-block btn-primary" type="submit">Recuperar contrase&ntilde;a</button>
                <a class="btn btn-large btn-block btn-info" href="/" >Regresar</a>
                <h2 class="form-signin-heading">&nbsp;</h2>
                <h6 class="form-signin-heading from-signin-copy">Desarrollado por - <a href=#">SAJA SYSTEMS</a> </h6>
                <h6 class="form-signin-heading from-signin-copy">Copyright &copy; 2019 - Todos los derechos reservados </h6>
            </form>
        </div>
    </body>
</html>
