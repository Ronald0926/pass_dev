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
        <link href="/static/css/portal/portal.css" rel="stylesheet">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
        <script type="text/javascript" src="/static/js/jquery-2.0.3.min.js"></script>
        <script type="text/javascript" src="/static/js/jquery-ui-1.10.3.custom.min.js"></script>
        <script type="text/javascript" src="/static/js/jquery.dataTables.js"></script>
        <script type="text/javascript" src="/static/js/jquery.validate.min.js"></script>
        <script type="text/javascript" src="/static/js/bootstrap.min.js"></script>
        <script type="text/javascript" src="/static/plugins/ckeditor/ckeditor.js"></script>
        <script type="text/javascript" src="/static/js/daos.js"></script>
        <script type="text/javascript" src="/static/js/portal/portal.js"></script>
        <script type="text/javascript" src="/static/js/portal/helper.js"></script>

    </head>
    <body>
    <?php $icono = $this->session->userdata('entidad');?>
        <div class="container-fluid">
            <div class="row header header2">
                <div class="col-md-2 col-lg-2 col-sm-4 col-xs-4">
                    <a href="/portal/principal/pantalla"> <img class="header-logo" src="/static/img/portal/logoInterno.jpg" /></a>

                </div>
                <div class="col-md-8" >
                 <?php if (( $bonoTrans == null)) { ?>
                    <table align="center">
                        <tr>
                            <td>
                                <a href="/portal/bono/ingreso">
                                    <div class="header-modulo header-logo-transportador">
                                            &nbsp;
                                    </div>
                                </a>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                Bono <br>Transportador
                            </td>
                        </tr>
                    </table>
                <?php } ?>
                </div>
                <div class="col-md-2" style="padding-right: 3%">
                    <table align="right">
                        <div class="cant-noti" id="cant-noti" style="position: absolute;margin-left: 45%;" ><p id="numNoti" style="color: #FFCB0B"></p></div>
                        <tr class="header-items">
                            <td>
                                <a href="/portal/ayuda/control">
                                    <div class="header-help">
                                        &nbsp;

                                    </div>
                                </a>
                            </td>
                            <td>
                                <a id="header-bell" href="javascript:notifications()">
                                    <div class="header-bell">
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
                                    <div class="perfil-interno" >
                                        <div class="perfil-dropdown">
                                            <img src="<?= $icono['ICONO'] ?>" class="img-circle" width="25%" />
                                            <h3>
                                                <?= $empresa ?>
                                            </h3>
                                            <div class="perfil-dropdown-ingreso">
                                                <span class="oblique">&uacute;ltimo ingreso: <?= $ultimaconexion ?></span>
                                            </div>
                                            <div class="perfil-dropdown-items">
                                                <a href="/portal/entidad/actualizar">
                                                    Actualización de datos
                                                </a>
                                                <br/>
                                                <a href="portal/usuariosCreacion/lista">
                                                    Administración de Usuarios
                                                </a>
                                                <br/>
                                                <a href="/portal/login/cambiarContrasena">
                                                    Cambio de contrase&ntilde;a
                                                </a>
                                                <br/>
                                                <a href="/portal/campanasCreacion/lista">
                                                    Creaci&oacute;n de campa&ntilde;as
                                                </a>
                                                <hr/>
                                                <a href="/portal/login/cambioSesion">
                                                    Cambio de sesi&oacute;n
                                                </a>
                                                <br/>
                                                <a href="/portal/login/cerrarSesion">
                                                    Cerrar sesi&oacute;n
                                                </a>
                                                <br/>
                                                &nbsp;
                                            </div>
                                        </div>
                                        <div class="perfil-nombre">
                                             <?= $usuario?>
                                        </div>
                                        <div class="perfil-empresa">
                                            <?= $empresa?>
                                        </div>
                                        <div class="perfil-ultimo-acceso">
                                            <span class="oblique">&uacute;ltimo ingreso: <?= $ultimaconexion ?></span>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        <!--Notificacion-->
                        <tr>
                            <td colspan="3"  style="position: relative;">
                                <div class="puntero-notificacion"></div>
                                <div class="notificacion" id="notificacion">
                                    <div class="notificacion-interno " >
                                       
                                    </div>
                                    <hr>
                                    <div class="notificacion-interno " onclick="this.style.display='none';" >
                                        <div class="img-notificacion" >
                                            <div class="img-noti-envio"></div>
                                        </div>
                                       
                                    </div>
                                </div>
                            </td>
                        </tr>
                        <!--fin notificacion-->
                    </table>
                </div>
            </div>
        </div>
