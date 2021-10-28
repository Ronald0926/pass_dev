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
        <script type="text/javascript" src="/static/js/jquery-2.0.3.min.js"></script>
        <script type="text/javascript" src="/static/js/jquery-ui-1.10.3.custom.min.js"></script>
        <script type="text/javascript" src="/static/js/jquery.dataTables.min.js"></script>
        <script type="text/javascript" src="/static/js/jquery.validate.min.js"></script>
        <script type="text/javascript" src="/static/js/bootstrap.min.js"></script>
        <script type="text/javascript" src="/static/plugins/ckeditor/ckeditor.js"></script>
        <script type="text/javascript" src="/static/js/daos.js"></script>
        <script type="text/javascript" src="/static/js/portal/portal.js"></script>
        <script type="text/javascript" src="/static/js/portal/video.js"></script>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
        <script type="text/javascript" src="/static/js/portal/helper.js"></script>
        <script type="text/javascript" src="/static/js/portal/CifrasEnLetras.js"></script>
        <link href='https://cdn.datatables.net/buttons/1.5.6/css/buttons.dataTables.min.css' rel="stylesheet" />
        <!--<link href='https://cdn.datatables.net/1.10.19/css/jquery.dataTables.min.css' rel="stylesheet" />-->
        <link href="/static/css/portal/jquery.dataTables.min.css" rel="stylesheet">
        <!--<script type="text/javascript" src="https://code.jquery.com/jquery-3.3.1.js"></script> comento ronald 13/12/2019 falla en modal-->

        <script type="text/javascript" src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
        <script type="text/javascript" src="https://cdn.datatables.net/buttons/1.5.6/js/dataTables.buttons.min.js"></script>
        <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
        <script type="text/javascript" src="https://cdn.datatables.net/buttons/1.5.6/js/buttons.html5.min.js"></script>
        <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
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

        </style>
    </head>

    <body>
        <?php
        session_start();
        //$rol = $this->session->userdata("rol");
        $rol = $_SESSION['rol'];
        //$llave_maestra = $this->session->userdata("CODIGO_PRODUCTO");
        $llave_maestra = $_SESSION['PRODUCTOLLAVE']['CODIGO_PRODUCTO'];
        //$icono = $this->session->userdata('entidad');
        $icono = $_SESSION['entidad'];
        
        ?>
        <div class="container-fluid">
            <div class="row header header2">
                <div class="col-md-2 col-lg-2 col-sm-4 col-xs-4">
                    <a href="/portal/principal/pantalla"><img class="header-logo" src="/static/img/portal/logoInterno.jpg" /></a>
                </div>
                <div class="col-md-8">
                    <table align="center">
                        <tr>
                            <?php if (($rol == 45) or ( $rol == 47)) { ?>
                                <td>
                                    <a href="/portal/solicitudTarjetas/solicitud">
                                        <div class="header-modulo header-modulo-solicitud <?php if ($menu == 'solicitud') echo 'active-solicitud' ?>">
                                            &nbsp;
                                        </div>
                                    </a>
                                </td>
                            <?php } ?>
                            <?php if (($rol == 45) or ( $rol == 47)) { ?>
                                <td>
                                    <a href="/portal/abonos/unoAUno">
                                        <div class="header-modulo header-modulo-abonos <?php if ($menu == 'abonos') echo 'active-abonos' ?>">
                                            &nbsp;
                                        </div>
                                    </a>
                                </td>
                            <?php } ?>
                            <?php if (($rol == 45) or ( $rol == 47) or ($rol == 58)) { ?>
                                <td>
                                    <a href="/portal/ordenPedido/lista">
                                        <div class="header-modulo header-modulo-pagos <?php if ($menu == 'pagos') echo 'active-pagos' ?>">
                                            &nbsp;
                                        </div>
                                    </a>
                                </td>
                            <?php } ?>
                            <?php if (($rol == 45) or ( $rol == 47) or ($rol == 46)) { ?>
                                <td>
                                    <a href="/portal/entregas/lista">
                                        <div class="header-modulo header-modulo-entregas <?php if ($menu == 'entregas') echo 'active-entregas' ?>">
                                            &nbsp;
                                        </div>
                                    </a>
                                </td>
                            <?php } ?>
                                 <?php if (( $rol == 45 ) or ( $rol == 47) or ( $rol == 46) or ( $rol == 56) or ( $rol == 58)) { ?>   
                            <td>
                                <a href="/portal/consultas/consultasAbonos">
                                    <div class="header-modulo header-modulo-consultas <?php if ($menu == 'consultas') echo 'active-consultas' ?>">
                                        &nbsp;
                                    </div>
                                </a>
                            </td>
                              <?php } ?>
                            <?php if (($rol == 45) or ( $rol == 47)) { ?>
                            <td>
                                <a href="/portal/solicitudGestion/solicitudGes">
                                    <div class="header-modulo header-modulo-gestion <?php if ($menu == 'gestion') echo 'active-gestion' ?>">
                                        &nbsp;
                                    </div>
                                </a>
                            </td>
                            <?php } ?>
                            <?php if (($rol == 45) or ( $rol == 47)) { ?>
                                    <!--     <td>
                                            <a href="/portal/cotizacion/cotizar">
                                                <div class="header-modulo header-modulo-cotizacion <?php if ($menu == 'cotizacion') echo 'active-cotizacion' ?>">
                                                    &nbsp;
                                                </div>
                                            </a>
                                        </td> -->
                            <?php } ?>
                            <td hidden>
                                <a href="/portal/soporte/categorias">
                                    <div class="header-modulo header-modulo-soporte <?php if ($menu == 'soporte') echo 'active-soporte' ?>">
                                        &nbsp;
                                    </div>
                                </a>
                            </td>
                            <?php if ((( $rol == 59) or ( $rol == 60) or ( $rol == 61)) && $llave_maestra == 70) { ?>
                                <td>
                                    <a href="/portal/llaveMaestra/principal">
                                        <div class="header-modulo header-modulo-llavemaestra <?php if ($menu == 'llave_maestra') echo 'active-llave' ?>">
                                            &nbsp;
                                        </div>
                                    </a>
                                </td>
                            <?php } ?>
                        </tr>
                        <tr>
                            <?php if (($rol == 45) or ( $rol == 47)) { ?>
                                <td>Tarjetas</td>
                            <?php } ?>
                            <?php if (($rol == 45) or ( $rol == 47)) { ?>
                                <td>Abonos</td>
                            <?php } ?>
                            <?php if (($rol == 45) or ( $rol == 47) or ($rol == 58)) { ?>
                                <td>Pagos</td>
                            <?php } ?>
                            <?php if (($rol == 45) or ( $rol == 47) or ( $rol == 46)) { ?>
                                <td>Entregas</td>
                            <?php } ?>
                                <?php if (( $rol == 45 ) or ( $rol == 47) or ( $rol == 46) or ( $rol == 56) or ( $rol == 58)) { ?>               
                            <td>Consultas</td>
                             <?php } ?>
                            <?php if (($rol == 45) or ( $rol == 47)) { ?>
                            <td>Gestión</td>
                            <?php } ?>
                            <?php if (($rol == 45) or ( $rol == 47)) { ?>
                                    <!--  <td>Cotizaci&oacute;n</td>-->
                            <?php } ?>
                            
                            <td hidden>Soporte</td>
                            <?php if ((($rol == 59) or ($rol == 60) or ( $rol == 61)) && $llave_maestra == 70) { ?>
                                <td>Llave maestra</td>
                            <?php } ?>
                        </tr>
                    </table>
                </div>
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
                                                <?php if ($rol == 47 || $rol == 45 )    { ?>
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
                                            $Anticipo= $_SESSION['activeanticipo'];
                                             //$Estado = $this->session->userdata('ESTADO_ENTIDAD');
                                             $Estado = $_SESSION['ESTADO_ENTIDAD'];
                                     
                                            if(!empty($dataAnticipo)&& $Anticipo==1){  ?>
                                        <div>
                                            <span>Cupo autorizado: <?= $dataAnticipo['CUPO_ANTICIPO'] ?></span> <br>
                                            <span>Cupo disponible: <?= $dataAnticipo['CUPO_DISPONIBLE'] ?></span> <br>
                                            <span>Cupo usado: <?= $dataAnticipo['CUPO_USADO'] ?></span> <br>
                                            <span>Tiempo maximo: <?= $dataAnticipo['DIAS_ANTICIPO'] ?></span> <br>
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
                                                <a href="#lightbox" data-toggle="modal">Ver Videos</a></li>
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
                                <canvas id="cvnVideoOne" width="900" height="440"></canvas></br>
                                <div id="playPausecvnVideoOne"></div>
                                <div class="mutecvnVideoOne clicMute"></div><br></br></br>
                            </div>
                            <div class="item">
                                <h2>SOLICITUD MASIVA</h2>
                                <canvas id="cvnVideoTwo" width="900" height="440"></canvas><br>
                                <div id="playPausecvnVideoTwo"></div>
                                <div class="mutecvnVideoTwo clicMute"></div><br></br></br>
                            </div>
                            <div class="item">
                                <h2>ACTIVACION DE PEDIDO</h2>
                                <canvas id="cvnVideoThree" width="900" height="440"></canvas><br>
                                <div id="playPausecvnVideoThree"></div>
                                <div class="mutecvnVideoThree clicMute"></div><br></br></br>
                            </div>
                            <div class="item">
                                <h2>ABONOS UNO A UNO</h2>
                                <canvas id="cvnVideoFour" width="900" height="440"></canvas><br>
                                <div id="playPausecvnVideoFour"></div>
                                <div class="mutecvnVideoFour clicMute"></div><br></br></br>
                            </div>
                            <div class="item">
                                <h2>ABONOS MASIVOS</h2>
                                <canvas id="cvnVideoFive" width="900" height="440"></canvas><br>
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