<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="author" content="Tecnolet SAS">
        <link rel="shortcut icon" href="">
        <title>.::Online::.</title>

        <link href="/static/css/ui-lightness/jquery-ui-1.10.3.custom.min.css" rel="stylesheet">
        <link href="/static/css/bootstrap.min.css" rel="stylesheet">
        <link href="/static/css/bootstrap-theme.min.css" rel="stylesheet">
        <link href="/static/css/daos.css" rel="stylesheet">
        <link href="/static/css/portal/login.css" rel="stylesheet">

        <script type="text/javascript" src="/static/js/jquery-2.0.3.min.js"></script>
        <script type="text/javascript" src="/static/js/jquery-ui-1.10.3.custom.min.js"></script>
        <script type="text/javascript" src="/static/js/jquery.dataTables.min.js"></script>
        <script type="text/javascript" src="/static/js/jquery.validate.min.js"></script>
        <script type="text/javascript" src="/static/js/bootstrap.min.js"></script>
        <script type="text/javascript" src="/static/plugins/ckeditor/ckeditor.js"></script>
        <script type="text/javascript" src="/static/js/daos.js"></script>
        <script type="text/javascript" src="/static/js/portal/login.js"></script>

    </head>
    <body>
        <div class="login-fondo">
            &nbsp;
        </div>
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-4 col-lg-4 col-sm-2 col-xs-0"></div>
                    <div class="col-sm-4 col-lg-4 col-sm-8 col-xs-12" >
                            <div class="login-form">

                                <form class="daos_formulario" action="/portal/login/validar" method="GET">
                                    <table>
                                        <tr>
                                            <td style="height: 300px; font-size: 19px;">
                                                Se ha enviado un mensaje a su correo electr&oacute;nico 
                                                para recuperar su contrase&ntilde;a
                                                <br/>
                                                <br/>
                                                <button type="submit">
                                                    VOLVER
                                                </button>
                                            </td>
                                        </tr>

                                    </table>
                                </form>

                            </div>
                </div>
             </div>
            <div class="col-md-4 col-lg-4 col-sm-1 col-xs-4"></div>      
        </div>
        <div class="login-footer">
            <div class="login-footer-background"></div>
            <div class="container-fluid">
                <div class="row">
                    <div class="col-sm-4">
                        <table>
                            <tr>
                                <td>
                                    Siguenos en nuestras redes sociales &nbsp;&nbsp;
                                </td>
                                <td>
                                    <a href="">
                                        <div class="login-footer-redes login-footer-redes-facebook">
                                            &nbsp;
                                        </div>
                                    </a>
                                </td>
                                <td>
                                    <a href="">
                                        <div class="login-footer-redes login-footer-redes-twitter">
                                            &nbsp;
                                        </div>
                                    </a>
                                </td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-sm-5" >
                        &copy; 2019 Peoplepass. Todos los derechos reservados.
                    </div>
                    <div class="col-sm-3">
                        <a href="" class="login-footer-download">
                            Ver Términos y condiciones de uso
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </body>
    <script type="text/javascript">
        $(function () {
            $('[data-toggle="tooltip"]').tooltip()
        })
    </script>
</html>