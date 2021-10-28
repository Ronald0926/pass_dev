
<?php if ($ERROR == 1) { ?>
    LOS SOLICITUD SE HA ENVIADO CORRECTAMENTE
    
<?php } elseif ($ERROR != 0)  { ?>
   <?= $ERROR ?>
<?php } ?>

<div class="col-md-2"></div>
<div class="col-md-8">
    <p class="titulo-iz-cat" style="color: #1C5394; padding-left:  1%;">SOPORTE</p>
    <p class="sub-actualizar" style="color: #1C5394; padding-left:  1%;">Elige una categoría </p>
    <table align="center" style="margin-top: 5%;">
        <tr>
            <td>
                <div class="soporte-menu">
                    <a href="/portal/soporte/tarjetas">
                        <table>
                            <tr>
                                <td> 
                                    <img class="soporte-menu-img" src="/static/img/portal/soporte/soporte-i-tarjetas.png" />
                                </td>
                            </tr>
                            <tr>
                                <td> 
                                    Tarjetas
                                </td>
                            </tr>
                        </table>
                    </a>
                </div>
            </td>
            <td>
                <div class="soporte-menu">
                    <a href="/portal/soporte/comercial/1">
                        <table>
                            <tr>
                                <td>
                                    <img class="soporte-menu-img" src="/static/img/portal/soporte/soporte-i-comercial.png" />
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    Comercial
                                </td>
                            </tr>
                        </table>
                    </a>
                </div>
            </td>
            <td>
                <div class="soporte-menu">
                    <a href="/portal/soporte/facturas">
                        <table>
                            <tr>
                                <td>
                                    <img class="soporte-menu-img" src="/static/img/portal/soporte/soporte-i-facturas.png" />
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    Facturas
                                </td>
                            </tr>
                        </table>
                    </a>
                </div>
            </td>
            <td>
                <div class="soporte-menu">
                    <a href="/portal/soporte/abonos">
                        <table>
                            <tr>
                                <td>
                                    <img class="soporte-menu-img" src="/static/img/portal/soporte/soporte-i-abonos.png" />
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    Abonos
                                </td>
                            </tr>
                        </table>
                    </a>
                </div>
            </td>
        </tr>
    </table>
    <table align="center">
        <tr>
            <td>
                <div class="soporte-menu">
                    <a href="/portal/soporte/entregas">
                        <table>
                            <tr>
                                <td>
                                    <img class="soporte-menu-img" src="/static/img/portal/soporte/soporte-i-entregas.png" />
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    Entregas
                                </td>
                            </tr>
                        </table>
                    </a>
                </div>
            </td>
            <td>
                <div class="soporte-menu">
                    <a href="/portal/soporte/plataforma">
                        <table>
                            <tr>
                                <td>
                                    <img class="soporte-menu-img" src="/static/img/portal/soporte/soporte-i-plataforma.png" />
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    Plataforma
                                </td>
                            </tr>
                        </table>
                    </a>
                </div>
            </td>
            <td>
                <div class="soporte-menu">
                    <a href="/portal/soporte/qys">
                        <table>
                            <tr>
                                <td>
                                    <img class="soporte-menu-img" src="/static/img/portal/soporte/soporte-i-quejasysoli.png" />
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    Quejas y solicitudes
                                </td>
                            </tr>
                        </table>
                    </a>
                </div>
            </td>
        </tr>
    </table>
</div>
<div class="col-md-2"></div>