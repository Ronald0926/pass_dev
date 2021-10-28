<?php
// $rol = $this->session->userdata("rol"); 
$rol = $_SESSION['rol'];
?>
<div style="margin-top: 40px; padding: 50px">
    <table align="center">
        <tr>
            <?php if (($rol == 61)) { ?>
                <td>
                    <div class="principal-menu-dos">
                        <a href="/portal/llaveMaestra/gestion_llaveros">
                            <table>
                                <tr>
                                    <td>
                                        <img class="principal-menu-img" src="/static/img/portal/llave/llave-gestion.png" />
                                    </td>
                                    <td>
                                        <div style="
                                             padding-left: 20px;
                                             " >
                                            <br/>
                                            Gestión de Llaveros 
                                            <br/>
                                        </div>
                                    </td>
                                </tr>
                            </table>
                            <hr/>
                            <div class="principal-menu-foot">
                                Crea llaveros para distribuir tu carga maestra
                            </div>
                        </a>
                    </div>
                </td>
            <?php } ?>
            <?php if (( $rol == 59) or ( $rol == 61)) { ?>
                <td>
                    <div class="principal-menu">
                        <a href="/portal/llaveMaestra/carga">
                            <table>
                                <tr>
                                    <td>
                                        <img class="principal-menu-img" src="/static/img/portal/llave/llave-carga.png" />
                                    </td>
                                    <td>
                                        <div style="
                                             padding-left: 20px;
                                             " >
                                            <br/>
                                            Carga Maestra
                                            <br/>
                                        </div>
                                    </td>
                                </tr>
                            </table>
                            <hr/>
                            <div class="principal-menu-foot">
                                Carga dinero a tu Llave Maestra
                            </div>
                        </a>
                    </div>
                </td>
            <?php } ?>
            <?php if (( $rol == 60) or ( $rol == 61)) { ?>
                <td>
                    <div class="principal-menu-dos">
                        <a href="/portal/llaveMaestra/abono">
                            <table>
                                <tr>
                                    <td>
                                        <img class="principal-menu-img" src="/static/img/portal/llave/llave-abono.png" />
                                    </td>
                                    <td>
                                        <div style="
                                             padding-left: 20px;
                                             " >
                                            <br/>
                                            Abono Tarjetas
                                            <br/>
                                        </div>
                                    </td>
                                </tr>
                            </table>
                            <hr/>
                            <div class="principal-menu-foot">
                                Abonar a tus tarjetas
                            </div>
                        </a>
                    </div>
                </td>
            <?php } ?>
        </tr>
    </table>
    <table align="center">
        <tr>
            <?php if (( $rol == 59) or ( $rol == 60) or ( $rol == 61)) { ?>
                <td>
                    <div class="principal-menu">
                        <?php if (( $rol == 60) or ( $rol == 61)) { ?>
                         <a href="/portal/llaveMaestra/estado">
                            <?php } elseif ($rol == 59) { ?>
                                <a href="/portal/llaveMaestra/consultaNotasContables">
                                <?php } ?>
                            <table>
                                <tr>
                                    <td>
                                        <img class="principal-menu-img" src="/static/img/portal/llave/llave-estado.png" />
                                    </td>
                                    <td>
                                        <div style="
                                             padding-left: 20px;
                                             " >
                                            <br/>
                                            Estado de cuenta
                                            <br/>
                                        </div>
                                    </td>
                                </tr>
                            </table>
                            <hr/>
                            <div class="principal-menu-foot">
                                Revisar movimientos y transacciones
                            </div>
                        </a>
                    </div>
                </td>
            <?php } ?>
            <?php if (( $rol == 60) or ( $rol == 61)) { ?>
                <td>
                    <div class="principal-menu-dos">
                        <?php if (( $rol == 61)) { ?>
                            <a href="/portal/llaveMaestra/reverso">
                            <?php } elseif ($rol == 60) { ?>
                                <a href="/portal/llaveMaestra/devolucion">
                                <?php } ?>
                                <table>
                                    <tr>
                                        <td>
                                            <img class="principal-menu-img" src="/static/img/portal/llave/llave-reverso.png" />
                                        </td>
                                        <td>
                                            <div style="
                                                 padding-left: 20px;
                                                 " >
                                                <br/>
                                                Reverso y Devolución
                                                <br/>
                                            </div>
                                        </td>
                                    </tr>
                                </table>
                                <hr/>
                                <div class="principal-menu-foot">
                                    Reversa los saldos de tus tarjetas o solicita una devolución en cualquier momento
                                </div>
                            </a>
                    </div>
                </td>
            <?php } ?>
            <?php if (( $rol == 60) or ( $rol == 61)) { ?>
                <td><div class="principal-menu">
                        <a href="/portal/llaveMaestra/asociacion">
                            <table>
                                <tr>
                                    <td>
                                        <img class="principal-menu-img" src="/static/img/portal/llave/llave-asociacion.png" />
                                    </td>
                                    <td>
                                        <div style="
                                             padding-left: 20px;
                                             " >
                                            <br/>
                                            Asociacion de tarjetas
                                            <br/>
                                        </div>
                                    </td>
                                </tr>
                            </table>
                            <hr/>
                            <div class="principal-menu-foot">
                                Selecciona las tarjetas para abonar desde Llave Maestra
                            </div>
                        </a>
                    </div>

                </td>
            <?php } ?>
        </tr>
    </table>
</div>
