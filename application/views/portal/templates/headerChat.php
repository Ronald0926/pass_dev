<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="author" content="Nova Base S.A.S.">
    <link rel="shortcut icon" href="">
    <title>.::Online:Chat:.</title>

    <link href="/static/css/ui-lightness/jquery-ui-1.10.3.custom.min.css" rel="stylesheet">
    <link href="/static/css/bootstrap.min.css" rel="stylesheet">
    <link href="/static/css/bootstrap-theme.min.css" rel="stylesheet">
    <link href="/static/css/daos.css" rel="stylesheet">
    <link href="/static/css/portal/portal.css" rel="stylesheet">
    <link rel="shortcut icon" type="image/x-icon" href="/static/img/portal/favicon.ico" />
    <script type="text/javascript" src="/static/js/jquery-2.0.3.min.js"></script>
    <script type="text/javascript" src="/static/js/jquery-ui-1.10.3.custom.min.js"></script>
    <script type="text/javascript" src="/static/js/jquery.dataTables.js"></script>
    <script type="text/javascript" src="/static/js/jquery.validate.min.js"></script>
    <script type="text/javascript" src="/static/js/bootstrap.min.js"></script>
    <script type="text/javascript" src="/static/plugins/ckeditor/ckeditor.js"></script>
    <script type="text/javascript" src="/static/js/daos.js"></script>
    <script type="text/javascript" src="/static/js/portal/portal.js"></script>

</head>

<body>
    <div class="container-fluid">
        <div class="row header">
            <div style="text-align:right" class="col-md-7 col-lg-7 col-sm-4 col-xs-12 ">
                <img class="header-logo" src="/static/img/portal/logoInterno.jpg" />
            </div>
            <div class="col-md-3 col-lg-3 col-sm-4 col-xs-12 " style="padding-right: 3%">
                <table align="right">
                    <tr class="header-items">
                        <td>
                            <a href="#">
                                <div class="">
                                    &nbsp;
                                </div>
                            </a>
                        </td>
                        <td>
                            <a href="javascript:void(0)">
                                <div class="header-user">
                                    &nbsp;
                                </div>
                            </a>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="3" style="position: relative;">
                            <div class="perfil">
                                <div class="perfil-interno">
                                    <div class="perfil-dropdown">
                                        <img src="<?= $icono['ICONO'] ?>" class="img-circle" width="25%" />                                                                               
                                        <div class="perfil-dropdown-items">                                            
                                            <a href="/chat/login/cerrarSesion">
                                                Cerrar sesi&oacute;n
                                            </a>
                                            <br />
                                            &nbsp;
                                        </div>
                                    </div>
                                    <div class="perfil-nombre">
                                        <?= $usuario ?>
                                    </div>
                                    <div class="perfil-empresa">
                                        <?= $empresa ?>
                                    </div>
                                    <div class="perfil-ultimo-acceso">
                                        <span class="oblique"><h4>Bienvenido</h4><br><?php
                                                                        $usuario = $this->session->userdata;
                                                                        echo ucwords(strtolower($usuario['NOMBRE'] . ' ' . $usuario['APELLIDO']));
                                                                        ?></span>
                                    </div>
                                </div>
                            </div>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    </div>