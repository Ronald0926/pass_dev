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
        <link href="/static/css/portal/soporte.css" rel="stylesheet">
        <link rel="shortcut icon" type="image/x-icon" href="/static/img/portal/favicon.ico" />
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

        <script type="text/javascript" src="/static/js/jquery-2.0.3.min.js"></script>
        <script type="text/javascript" src="/static/js/jquery-ui-1.10.3.custom.min.js"></script>
        <script type="text/javascript" src="/static/js/jquery.dataTables_1.10.19.min.js"></script>
        <!--<script type="text/javascript" src="/static/js/jquery.dataTables.min.js"></script> antigua comentada 26/09/2019-->
        <script type="text/javascript" src="/static/js/jquery.validate.min.js"></script>
        <script type="text/javascript" src="/static/js/bootstrap.min.js"></script>
        <script type="text/javascript" src="/static/plugins/ckeditor/ckeditor.js"></script>
        <script type="text/javascript" src="/static/js/daos.js"></script>
        <script type="text/javascript" src="/static/js/portal/portal.js"></script>
        <script type="text/javascript" src="/static/js/portal/video.js"></script>
        <script type="text/javascript" src="/static/js/portal/CifrasEnLetras.js"></script>
        <link href='https://cdn.datatables.net/buttons/1.5.6/css/buttons.dataTables.min.css' rel="stylesheet" />
        <!--<link href='https://cdn.datatables.net/1.10.19/css/jquery.dataTables.min.css' rel="stylesheet" />-->

        <script type="text/javascript" src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
        <script type="text/javascript" src="https://cdn.datatables.net/buttons/1.5.6/js/dataTables.buttons.min.js"></script>
        <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
        <script type="text/javascript" src="https://cdn.datatables.net/buttons/1.5.6/js/buttons.html5.min.js"></script>
        <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
        <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
        <style type="text/css">
            button.dt-button {
                width: 100px !important;
                background: #366199 !important;
                border-radius: 10px !important;
                color: white !important;
            }

            .button.dt-button {
                width: 100px !important;
                background: #366199 !important;
                border-radius: 10px !important;
                color: white !important;
            }

            #button.dt-button {
                width: 100px !important;
                background: #366199 !important;
                border-radius: 10px !important;
                color: white !important;
            }
            .clicMute {
                cursor: pointer;
                display: initial;
            }

        </style>
    </head>

    <body>
        <?php
        //$icono = $this->session->userdata('entidad');
        //$rol = $this->session->userdata("rol");     
        session_start();
        //$rol = $this->session->userdata("rol");
        $rol = $_SESSION['rol'];
        //$icono = $this->session->userdata('entidad');
        $icono = $_SESSION['entidad'];
        ?>

        <div class="container-fluid">
            <div class="row header header2">
                <div class="col-md-2 col-lg-2 col-sm-4 col-xs-4">
                    <a href="/portal/llaveMaestra/principal"><img class="header-logo" src="/static/img/portal/logoInterno.jpg" /></a>
                </div>
                <div class="col-md-8">
                    <table align="center">
                        <tr>
                            <td>
                                <a href="/portal/llaveMaestra/principal">
                                    <div class="header-modulo-llave header-menu-inicio">
                                        &nbsp;
                                    </div>
                                </a>
                            </td>
                            <?php if (($rol == 61)) { ?>
                                <td>
                                    <a href="/portal/llaveMaestra/gestion_llaveros">
                                        <div class="header-modulo-llave header-menu-bolsillo <?php if ($menu == 'bolsillo') echo 'active-bolsillo' ?>">
                                            &nbsp;
                                        </div>
                                    </a>

                                </td>
                            <?php } ?>
                            <?php if (($rol == 59) or ($rol == 61)) { ?>
                                <td>
                                    <a href="/portal/llaveMaestra/carga">
                                        <div class="header-modulo-llave header-menu-carga <?php if ($menu == 'carga') echo 'active-carga' ?>">
                                            &nbsp;
                                        </div>
                                    </a>
                                </td>
                            <?php } ?>
                            <?php if (($rol == 60) or ($rol == 61)) { ?>
                                <td>
                                    <a href="/portal/llaveMaestra/abono">
                                        <div class="header-modulo-llave header-menu-abono <?php if ($menu == 'abono') echo 'active-abono' ?>">
                                            &nbsp;
                                        </div>
                                    </a>
                                </td>
                            <?php } ?>
                            <?php if (($rol == 59) or ($rol == 60) or ($rol == 61)) { ?>
                                <td>
                                    <?php if (( $rol == 60) or ( $rol == 61)) { ?>
                         <a href="/portal/llaveMaestra/estado">
                            <?php } elseif ($rol == 59) { ?>
                                <a href="/portal/llaveMaestra/consultaNotasContables">
                                <?php } ?>
                                        <div class="header-modulo-llave header-menu-estado <?php if ($menu == 'estado') echo 'active-estado' ?>">
                                            &nbsp;
                                        </div>
                                    </a>
                                </td>
                            <?php } ?>
                            <?php if (($rol == 60) or ($rol == 61)) { ?>
                                <td>
                                    <?php if (($rol == 61)) { ?>
                                        <a href="/portal/llaveMaestra/reverso">
                                        <?php } elseif ($rol == 60) { ?>
                                            <a href="/portal/llaveMaestra/devolucion">
                                            <?php } ?>
                                            <div class="header-modulo-llave header-menu-reverso <?php if ($menu == 'reverso') echo 'active-reverso' ?>">
                                                &nbsp;
                                            </div>
                                        </a>
                                </td>
                            <?php } ?>
                            <?php if (($rol == 60) or ($rol == 61)) { ?>
                                <td>
                                    <a href="/portal/llaveMaestra/asociacion">
                                        <div class="header-modulo-llave header-menu-asociacion <?php if ($menu == 'asociacion') echo 'active-asociacion' ?>">
                                            &nbsp;
                                        </div>
                                    </a>
                                </td>
                            <?php } ?>
                        <!--<td>
                                <a href="/portal/llaveMaestra/principal">
                                    <div class="header-modulo-llave header-menu-llave <?php if ($llaveMaestra == '1') echo 'active-llave' ?>">
                                        &nbsp;
                                    </div>
                                </a>
                            </td>-->
                        </tr>
                        <tr>
                            <td>Inicio</td>
                            <?php if (($rol == 61)) { ?><td>Gestión <br> llaveros</td><?php } ?>
                            <?php if (($rol == 59) or ($rol == 61)) { ?><td>Carga<br>Maestra</td><?php } ?>
                            <?php if (($rol == 60) or ( $rol == 47) or ($rol == 61)) { ?><td>Abono<br>Tarjetas</td><?php } ?>
                            <?php if (($rol == 60) or ($rol == 59) or ($rol == 61)) { ?><td>Estado de<br>cuenta</td><?php } ?>
                            <?php if (($rol == 60) or ($rol == 61)) { ?><td>Reverso y<br>Devoluciones</td><?php } ?>
                            <?php if (($rol == 60) or ($rol == 61)) { ?><td>Asociación<br>Tarjetas</td><?php } ?>

                        <!--<td>Llave<br>Maestra</td>-->
                        </tr>
                    </table>
                </div>
                <!--                <div class="col-md-1" align="right">
                                        <table>
                                            <tr>
                                                <td>
                                                    <a href="/portal/llaveMaestra/ingreso"><img width="100px" height="100px" src="/static/img/portal/logollavemaestra.png" /> </a>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style='text-align: center'>
                                                    <label>Llave<br>Maestra</label>
                                                </td>
                                            </tr>
                                        </table>
                                    </div>-->
                <div class="col-md-2" style="padding-right: 3%">
                    <table align="right">
                        <div class="cant-noti" id="cant-noti" style="position: absolute;margin-left: 45%;">
                            <p id="numNoti" style="color: #FFCB0B"></p>
                        </div>
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
                                                <?php if ($rol == 47) { ?>
                                                    <a href="/portal/entidad/actualizar">
                                                        Actualización de datos
                                                    </a>
                                                <?php } ?>
                                                <br />
                                                <?php if ($rol == 61) { ?>
                                                    <a href="/portal/usuariosCreacion/lista">
                                                        Administración de Usuarios
                                                    </a>
                                                <?php } ?>
                                                <br />
                                                <?php if ($rol == 47 || $rol == 59 || $rol == 60 || $rol == 61) { ?>
                                                    <a href="/portal/login/cambiarContrasena">
                                                        Cambio de contrase&ntilde;a
                                                    </a>
                                                <?php } ?>
                                                <br />
                                                <?php if ($rol == 47) { ?>
                                                    <a href="/portal/campanasCreacion/lista">
                                                        Creaci&oacute;n de campa&ntilde;as
                                                    </a>
                                                <?php } ?>
                                                <hr />
                                                <?php if ($rol == 47) { ?>
                                                    <a href="/portal/login/cambioSesion">
                                                        Cambio de sesi&oacute;n
                                                    </a>
                                                <?php } ?>
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
                <div class="modal-content borderVideo">
                    <div class="modal-body ">
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