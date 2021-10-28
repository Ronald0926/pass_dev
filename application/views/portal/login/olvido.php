
<body>
    <div class="login-fondo">
        &nbsp;
    </div>
    <div class="outer">
        <div class="middle">
            <div class="inner col-xs-10 col-sm-8 col-md-6 col-lg-5">
                <div class="wizard-card">
                    <div class="login-form">
                        <div class="img-banner">
                            <img src="/static/img/logo.png"  class="img-responsive center-block" />
                        </div>
                        <?php if ($ok == 1014) { ?>
                            <div class="alert alert-danger">
                                Los datos Ingresados son Incorrectos
                            </div>
                        <?php } ?>
                        <form class="daos_formulario" action="" method="POST">
                            <table>
                                <tr>
                                    <td>
                                        <div class="select">
                                            <select class="select-online required"  name="tipoDocumento" required>
                                                <option value=""> Seleccione el tipo de Documento</option>
                                                <?php foreach ($tipoDocumento as $key => $value) { ?>
                                                    <option value="<?= $value['PK_TD_CODIGO'] ?>"> <?= $value['NOMBRE'] ?></option>
                                                <?php } ?>
                                            </select>
                                            <div class="lower-case" >
                                                Seleccione el tipo de Documento
                                            </div>
                                        </div>
                                    </td>
                                    <td style="width: 20px">
                                        <!-- <button type="button" data-toggle="tooltip" data-placement="bottom" title="Tooltip on bottom" style="background-color: #FFF;">
                                            <img src="/static/img/portal/login/ayuda.png" width="20px" />
                                        </button>-->
                                        <img src="/static/img/portal/login/ayuda.png" width="20px" />
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <input class="required" type="text" class="textPat" name="documento"  placeholder="Digite el n&uacute;mero de Documento" required />
                                    </td>
                                    <td style="width: 20px">
                                        <!-- <button type="button" data-toggle="tooltip" data-placement="bottom" title="Tooltip on bottom" style="background-color: #FFF;">
                                            <img src="/static/img/portal/login/ayuda.png" width="20px" />
                                        </button>-->
                                        <img src="/static/img/portal/login/ayuda.png" width="20px" />
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <input class="required email" type="email" name="correo" class="correoPat" placeholder="Digite su correo electr&oacute;nico" required/>
                                    </td>
                                    <td style="width: 20px">
                                       <!-- <button type="button" data-toggle="tooltip" data-placement="bottom" title="Tooltip on bottom" style="background-color: #FFF;">
                                            <img src="/static/img/portal/login/ayuda.png" width="20px" />
                                        </button>-->
                                        <img src="/static/img/portal/login/ayuda.png" width="20px" />
                                    </td>
                                </tr>
                            </table>
                            <br/>
                            <br/>
                            <br/>
                            <button type="submit">
                                RECUPERAR CONTRASEÑA
                            </button>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>
