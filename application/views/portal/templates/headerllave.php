<!DOCTYPE html>
<html lang="es">

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
        <link rel="shortcut icon" type="image/x-icon" href="/static/img/portal/favicon.ico" />
        <script type="text/javascript" src="/static/js/jquery-2.0.3.min.js"></script>
        <script type="text/javascript" src="/static/js/jquery-ui-1.10.3.custom.min.js"></script>
        <script type="text/javascript" src="/static/js/jquery.dataTables.js"></script>
        <script type="text/javascript" src="/static/js/jquery.validate.min.js"></script>
        <script type="text/javascript" src="/static/js/bootstrap.min.js"></script>
        <script type="text/javascript" src="/static/plugins/ckeditor/ckeditor.js"></script>
        <script type="text/javascript" src="/static/js/daos.js"></script>
        <script type="text/javascript" src="/static/js/portal/portal.js"></script>
        <script type="text/javascript" src="/static/js/portal/video.js"></script>
        <script type="text/javascript" src="/static/js/portal/helper.js"></script>
        <style>
            .clicMute {
                cursor: pointer;
                display: initial;
            }
        </style>
    </head>
    <body>
        <?php
        //$rol = $this->session->userdata("rol");
        //$icono = $this->session->userdata('entidad');
        session_start();
        //$rol = $this->session->userdata("rol");
        $rol = $_SESSION['rol'];
        //$icono = $this->session->userdata('entidad');
        $icono = $_SESSION['entidad'];
        ?>
        <div class="container-fluid">
            <div class="row header">
                <div class="col-md-2 col-lg-2 col-sm-4 col-xs-4">
                </div>
                <div class="col-md-8" >
                    <a href="/portal/principal/pantalla"> <img class="logo-people" src="/static/img/portal/logopeoplepass.png" /></a>

                </div>
                <div class="col-md-2" style="padding-right: 3%">
                    <table align="right">
                        <div class="cant-noti" id="cant-noti" style="position: absolute;margin-left: 45%;" ><p id="numNoti" style="color: #FFCB0B"></p></div>
                         <tr class="header-items">

                            <td>
                                <a href="javascript:void(0)">
                                    <div class="header-help">
                                        &nbsp;
                                    </div>
                                </a>
                                <!-- <a href="/portal/ayuda/control">
                                    <div class="header-help">
                                        &nbsp;
    
                                    </div>
                                </a> -->
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
<?php if ($rol == 47 || $rol == 45 || $rol == 58) { ?>
                                                    <a href="/portal/entidad/actualizar">
                                                        Actualización de datos
                                                    </a>
                                                <?php } ?>
                                                <br />
<?php if ($rol == 47 || $rol == 45 || $rol == 61) { ?>
                                                    <a href="/portal/usuariosCreacion/lista">
                                                        Administración de Usuarios
                                                    </a>
                                                <?php } ?>
                                                <br />
<?php if ($rol == 56 || $rol == 46 || $rol == 47 || $rol == 45 || $rol == 58 || $rol == 59 || $rol == 60 || $rol == 61) { ?>
                                                    <a href="/portal/login/cambiarContrasena">
                                                        Cambio de contrase&ntilde;a
                                                    </a>
                                                <?php } ?>
                                                <br />
<?php if ($rol == 45 || $rol == 47) { ?>
                                                    <a href="/portal/campanasCreacion/lista">
                                                        Creaci&oacute;n de campa&ntilde;as
                                                    </a>
                                                <?php } ?>
                                                <hr />
                                                    <a href="/portal/login/cambioSesion">
                                                        Cambio de sesi&oacute;n
                                                    </a>
                                                <br />
                                                <a href="/portal/login/cerrarSesion">
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
<?php if ($rol == 59 || $rol == 61) { ?>
                                            <div class="perfil-ultimo-acceso">
                                                <span class="oblique">Saldo disponible para abonos: </span>
                                                <h3 class="saldo-menu">$<?= number_format($saldo, 0, ',', '.'); ?></h3>
                                            </div>
                                            <div class="perfil-ultimo-acceso">
                                                <span class="oblique">&uacute;ltimo ingreso: <?= $ultimaconexion ?></span>
                                            </div>
                                            <!--                                        <div class="perfil-saldo-canje">
                                               <span class="oblique">Saldo en canje: </span><h3 class="saldo-menu-canje">$<?= number_format($saldocanje, 0, ',', '.'); ?></h3>
                                            </div>-->
<?php } elseif ($rol == 60) { ?>
                                            <div class="perfil-ultimo-acceso">
                                                <span class="oblique">&uacute;ltimo ingreso: <?= $ultimaconexion ?></span>
                                            </div>
<?php } ?>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="3" style="position: relative;">
                                <div class="perfil-help">
                                    <div class="perfil-interno-help">
                                        <div class="perfil-dropdown-help">
                                            <div class="perfil-dropdown-items">
                                                <a href="#lightbox" data-toggle="modal">Ver Videos</a>
                                                <br />
                                                <a href="/portal/ayuda/control">
                                                    Realizar Tutorial
                                                </a>
                                                <br />
                                                &nbsp;
                                                <a href="/portal/ayuda/preguntasfrecuentes" >Preguntas frecuentes</a>
                                                <br />
                                                &nbsp;
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        <!--Notificacion-->
                        <tr>
                            <td colspan="3" style="position: relative;">
                                <div class="puntero-notificacion"></div>
                                <div class="notificacion" id="notificacion">
                                    <div class="notificacion-interno ">

                                    </div>
                                    <hr>
                                    <div class="notificacion-interno " onclick="this.style.display = 'none';">
                                        <div class="img-notificacion">
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

        <div class="modal fade and carousel slide centerModal" id="lightbox">
            <div class="modal-dialog modal-lg">
                <div class="modal-content borderVideo" >
                    <div class="modal-body ">
                        <div class="carousel-inner" style="text-align: center;">
                            <div class="item active ">
                                <!-- <video id="vOne" width="520" height="440" controls>
                                    <source src="/static/files/entidad/descarga_la_app_de_peoplepass.mp4" type="video/mp4">
                                    Your browser does not support the video tag.
                                </video> -->
                                <canvas id="cvnVideoOne" width="520" height="440"></canvas></br>
                                <div id="playPausecvnVideoOne"></div>
                                <div class="mutecvnVideoOne clicMute"></div><br></br></br>
                            </div>
                            <div class="item">
                                <!-- <video id="vTwo" width="520" height="440" controls>
                                    <source src="/static/files/entidad/video_como_consutar_tu_saldo_en_la_app_de_peoplepass.mp4" type="video/mp4">
                                    Your browser does not support the video tag.
                                </video> -->
                                <canvas id="cvnVideoTwo" width="520" height="440"></canvas><br>
                                <div id="playPausecvnVideoTwo"></div>
                                <div class="mutecvnVideoTwo clicMute"></div><br></br></br>
                            </div>
                            <div class="item">
                                <!-- <video id="vTwo" width="520" height="440" controls>
                                    <source src="/static/files/entidad/video_como_consutar_tu_saldo_en_la_app_de_peoplepass.mp4" type="video/mp4">
                                    Your browser does not support the video tag.
                                </video> -->
                                <canvas id="cvnVideoThree" width="520" height="440"></canvas><br>
                                <div id="playPausecvnVideoThree"></div>
                                <div class="mutecvnVideoThree clicMute"></div><br></br></br>
                            </div>
                            <div class="item">
                                <!-- <video id="vTwo" width="520" height="440" controls>
                                    <source src="/static/files/entidad/video_como_consutar_tu_saldo_en_la_app_de_peoplepass.mp4" type="video/mp4">
                                    Your browser does not support the video tag.
                                </video> -->
                                <canvas id="cvnVideoFour" width="520" height="440"></canvas><br>
                                <div id="playPausecvnVideoFour"></div>
                                <div class="mutecvnVideoFour clicMute"></div><br></br></br>
                            </div>
                            <div class="item">
                                <!-- <video id="vTwo" width="520" height="440" controls>
                                    <source src="/static/files/entidad/video_como_consutar_tu_saldo_en_la_app_de_peoplepass.mp4" type="video/mp4">
                                    Your browser does not support the video tag.
                                </video> -->
                                <canvas id="cvnVideoFive" width="520" height="440"></canvas><br>
                                <div id="playPausecvnVideoFive"></div>
                                <div class="mutecvnVideoFive clicMute"></div><br></br></br>
                            </div>
                        </div><br><br>
                        <ol class="carousel-indicators">
                            <li data-target="#lightbox" data-slide-to="0" class="active"></li>
                            <li data-target="#lightbox" data-slide-to="1"></li>
                            <li data-target="#lightbox" data-slide-to="2"></li>
                            <li data-target="#lightbox" data-slide-to="3"></li>
                            <li data-target="#lightbox" data-slide-to="4"></li>
                        </ol>
                        <!-- /.carousel-inner -->
                        <a class="left carousel-control" href="#lightbox" role="button" data-slide="prev">
                            <span class="glyphicon glyphicon-chevron-left"></span>
                        </a>
                        <a class="right carousel-control" href="#lightbox" role="button" data-slide="next">
                            <span class="glyphicon glyphicon-chevron-right"></span>
                        </a>
                    </div><!-- /.modal-body -->
                </div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->
        </div><!-- /.modal -->