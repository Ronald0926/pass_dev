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
        <!--<script type="text/javascript" src="/static/js/jquery.validate.min.js"></script>-->
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
                <div class="col-sm-8 col-sm-offset-2">
                    <div class="row">
                        <div class="col-sm-10 col-sm-offset-1">
                            <div class="login-form">
                                <img src="/static/img/logo.png" width="70%" />
                                <br/>
                                <br/>
                                <div style="color: #666">
                                    <strong>Términos y Condiciones</strong>
                                </div>
                                <form class="daos_formulario" action="" method="POST">
                                    <input hidden="true" name="ok" value="ok"/>
                                    <table class="login-btn-terminos">
                                        <tr>
                                            <?php foreach ($terminos as $value) { ?>
                                            <tr>
                                                <td style="width: 100px;">
                                                    <div class="login-checkbox">
                                                        <input type="checkbox" name="check[]" value="<?= $value['CODIGO'] ?>" <?php if ($value['OBLIGATORIO'] == 1) echo 'required'; ?> /> 
                                                        <div class="">
                                                            <span class="login-checkbox-check">
                                                            </span>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td style="width: 50px">
                                                    <img src="/static/img/portal/pdf.jpg" width="35" />
                                                </td>
                                                <td>
                                                    <a href="<?= $value['URL']?>" target="_blank" style="color: #7575757!important; text-align: left">
                                                        <?= ucwords(strtolower($value['NOMBRE_POLITICA'])) ?>
                                                    </a>
                                                </td>
                                            </tr>
                                        <?php } ?>
                                    </table>
                                    <br/>
                                    <br/>
                                    <br/>
                                    <table style="width: 70%" align="center">
                                        <tr>
                                            <td style="width: 48%">
                                                <div class="login-btn-cancelar">
                                                    <a href="/" class="">
                                                        C a n c e l a r
                                                    </a>
                                                </div>
                                            </td>
                                            <td style="width: 4%">
                                                &nbsp;
                                            </td>
                                            <td>
                                                <button type="submit">
                                                    A c e p t a r
                                                </button>
                                            </td>
                                        </tr>
                                    </table>
                                </form>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
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