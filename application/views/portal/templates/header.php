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
        <style>
            .clicMute {
                cursor: pointer;
                display: initial;
            }
        </style>
    </head>

    <body>
        <?php
        session_start();
        //$rol = $this->session->userdata("rol");
        $rol = $_SESSION['rol'];
        //$llave_maestra = $this->session->userdata("CODIGO_PRODUCTO");
        $llave_maestra = $_SESSION['CODIGO_PRODUCTO'];
        //$icono = $this->session->userdata('entidad');
        $icono = $_SESSION['entidad'];
        ?>

        <div class="container-fluid">
            <div class="row header">
                <div class="col-md-2 col-lg-2 col-sm-4 col-xs-4">
                </div>
                <div class="col-md-8">
                    <img class="logo-people"style="margin-bottom: 5%" src="/static/img/portal/logopeoplepass.png" />
                </div>
                 <div class="col-md-2" style="padding-right: 3%; " >
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
                                    <div class="perfil-interno">
                                        <div class="perfil-dropdown">
                                            <img src="<?= $icono['ICONO'] == '' ? '/static/img/portal/iconos/Iconos_Camara_azul.png' : $icono['ICONO'] ?>" class="img-circle" width="25%" />
                                            <h3>
                                                <?= $empresa ?>
                                            </h3>
                                            <div class="perfil-dropdown-ingreso">
                                                <span class="oblique">&uacute;ltimo ingreso: <?= $ultimaconexion ?></span>
                                            </div>
                                            <div class="perfil-dropdown-items">
                                                <?php if ($rol == 47 || $rol == 45 || $rol == 58) { ?>
                                                    <a href="/portal/entidad/actualizar">
                                                        Actualizaci&oacute;n de datos
                                                    </a>
                                                <?php } ?>
                                                <br />
                                                <?php if ($rol == 45 || $rol == 47 || $rol == 61) { ?>
                                                    <a href="/portal/usuariosCreacion/lista">
                                                        Administraci&oacute;n de Usuarios
                                                    </a>
                                                <?php } ?>
                                                <br />
                                                <?php if ($rol == 56 || $rol == 46 || $rol == 47 || $rol == 45 || $rol == 58 || $rol == 59 || $rol == 60 || $rol == 61) { ?>
                                                    <a href="/portal/login/cambiarContrasena">
                                                        Cambio de contrase&ntilde;a
                                                    </a>
                                                <?php } ?>
                                                <br />
                                                <?php if ($rol == 47 || $rol == 45 ) { ?>
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
                                        <div class="perfil-ultimo-acceso">
                                            <span class="oblique">&uacute;ltimo ingreso: <?= $ultimaconexion ?></span>
                                        </div>
                                        <?php  if ($rol==58 || $rol==56) {
                                            //$Estado = $this->session->userdata('ESTADO_ENTIDAD');
                                            $Estado = $_SESSION['ESTADO_ENTIDAD'];
                                            $Anticipo= $_SESSION['activeanticipo'];
                                            if(!empty($dataAnticipo)&& $Anticipo==1){  ?>
                                        <div>
                                            <span style="font-size: 1.2em">Cupo autorizado: <?= $dataAnticipo['CUPO_ANTICIPO'] ?></span> <br>
                                            <span style="font-size: 1.2em">Cupo disponible: <?= $dataAnticipo['CUPO_DISPONIBLE'] ?></span> <br>
                                            <span style="font-size: 1.2em">Cupo usado: <?= $dataAnticipo['CUPO_USADO'] ?></span> <br>
                                            <span style="font-size: 1.2em">Tiempo maximo: <?= $dataAnticipo['DIAS_ANTICIPO'] ?></span> <br>
                                            <span style="font-weight: bold;">Estado: <?= $Estado ?></span> <br>
                                        </div>
                                            <?php }
                                            
                                            } ?>
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
                                    <div class="notificacion-interno " >

                                    </div>
                                    <hr>
                                    <div class="notificacion-interno " onclick="this.style.display = 'none';" >
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


        <div class="modal fade and carousel slide centerModal" id="lightbox">
            <div class="modal-dialog modal-lg">
                <div class="modal-content borderVideo" id="tamano">
                    <div class="modal-body">
                        <div class="carousel-inner" style="text-align: center;">
                            <div class="item active ">
                                <h2>SOLICITUD DE TARJETAS UNO A UNO</h2>
                                <canvas id="cvnVideoOne" width="900" height="640"></canvas></br>
                                <div id="playPausecvnVideoOne"></div>
                                <div class="mutecvnVideoOne clicMute"></div><br></br></br>
                            </div>
                            <div class="item">
                                <h2>SOLICITUD MASIVA</h2>
                                <canvas id="cvnVideoTwo" width="900" height="640"></canvas><br>
                                <div id="playPausecvnVideoTwo"></div>
                                <div class="mutecvnVideoTwo clicMute"></div><br></br></br>
                            </div>
                            <div class="item">
                                <h2>ACTIVACION DE PEDIDO</h2>
                                <canvas id="cvnVideoThree" width="900" height="640"></canvas><br>
                                <div id="playPausecvnVideoThree"></div>
                                <div class="mutecvnVideoThree clicMute"></div><br></br></br>
                            </div>
                            <div class="item">
                                <h2>ABONOS UNO A UNO</h2>
                                <canvas id="cvnVideoFour" width="900" height="640"></canvas><br>
                                <div id="playPausecvnVideoFour"></div>
                                <div class="mutecvnVideoFour clicMute"></div><br></br></br>
                            </div>
                            <div class="item">
                                <h2>ABONOS MASIVOS</h2>
                                <canvas id="cvnVideoFive" width="900" height="640"></canvas><br>
                                <div id="playPausecvnVideoFive"></div>
                                <div class="mutecvnVideoFive clicMute"></div><br></br></br>
                            </div>
                            <div class="item">
                                <h2>PAGO DE MIS PEDIDOS</h2>
                                <canvas id="cvnVideoSix" width="900" height="640"></canvas><br>
                                <div id="playPausecvnVideoSix"></div>
                                <div class="mutecvnVideoSix clicMute"></div><br></br></br>
                            </div>
                            <div class="item">
                                <h2>ASIGNAR ROLES Y ADMINISTRAR USUARIOS</h2>
                                <canvas id="cvnVideoSeven" width="900" height="640"></canvas><br>
                                <div id="playPausecvnVideoSeven"></div>
                                <div class="mutecvnVideoSeven clicMute"></div><br></br></br>
                            </div>
                        </div><br><br>
                        <ol class="carousel-indicators">
                            <li data-target="#lightbox" data-slide-to="0" class="active"></li>
                            <li data-target="#lightbox" data-slide-to="1"></li>
                            <li data-target="#lightbox" data-slide-to="2"></li>
                            <li data-target="#lightbox" data-slide-to="3"></li>
                            <li data-target="#lightbox" data-slide-to="4"></li>
                            <li data-target="#lightbox" data-slide-to="5"></li>
                            <li data-target="#lightbox" data-slide-to="6"></li>
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