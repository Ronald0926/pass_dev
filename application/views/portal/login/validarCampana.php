<body>
    <div class="login-fondo">
        &nbsp;
    </div>
    <div class="outer">
        <div class="middle">
            <div class="inner col-xs-10 col-sm-8 col-md-6 col-lg-5">
                <div class="wizard-card">
                    <div class="img-banner">
                        <img src="/static/img/logo.png"  class="img-responsive center-block" />
                    </div>

                    <p class="titulo"><?php
                        $this->load->helper('log4php');
                        //$usuario = $this->session->userdata('usuario');
                        $usuario = $_SESSIOn['usuario'];
                        echo ucwords(strtolower($usuario['NOMBRE'] . ' ' . $usuario['APELLIDO']));
                        ?></p>
                    <label class="error"><?= $this->session->userdata('errorData'); ?></label>
                    <form class="daos_formulario" action="" method="POST">
                        <table>
                            <tr>
                                <td>
                                    <div class="form-inline form-group">
                                        <div class="col-md-6 " style="padding-right: 5px;">
                                            <div class="select select">
                                                <select name="campana" required>
                                                    <option value="">Seleccione la campaña</option>
                                                    <?php 
                                                    foreach ($campana as $key => $value) { 
                                                    log_info( ' Campanas '.$value['NOMBRE']);    
                                                        ?>    
                                                        <option value="<?= $value['PK_CAMPAN_CODIGO'] ?>"> <?= $value['NOMBRE'] ?></option>
                                                    <?php } ?>
                                                </select>
                                                <div class="lower-case" style="margin-right: 0px">
                                                    Seleccione la campana
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6 " style="padding-left: 5px;">
                                            <div class="select select">
                                                <select  name="rol" required>
                                                    <option value="">Seleccione el rol</option>
<?php foreach ($rol as $key => $value) { ?>
                                                        <option value="<?= $value['PK_TIPVIN_CODIGO'] ?>"> <?= $value['NOMBRE'] ?></option>
                                                    <?php } ?>
                                                </select>
                                                <div class="lower-case" style="margin-left: 0px">
                                                    Seleccione el rol
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <input required style="width: 95%;" type="password" id="contrasena" class="textPat" name="pass" placeholder="Digite su contraseña" />
                                    <span id="icon" class="fa fa-eye-slash" onclick="myFunction()"></span>
                                    <div class="login-olvido-contrasena-link">
                                        <a href="/portal/login/olvido">¿Olvid&oacute; su contrase&ntilde;a?</a>
                                    </div>
                                </td>
                            </tr>
                        </table>
                        <!-- Status message -->
<?php if (!empty($statusMsg)) { ?>
                            <p class="status-msg <?php echo $status; ?>"><?php echo $statusMsg; ?></p>
                        <?php } ?>
                        <!-- Google reCAPTCHA box -->
                        <div class="g-recaptcha pull-right"  data-sitekey="6Ldnk8QUAAAAADiIuip9tOZFabYevf9y9PzpJyU3" style="padding-bottom: 15px;padding-top: 15px"></div>
                        <br><label class="error"><?= $this->session->userdata('errorCaptcha'); ?></label>
                        <button type="submit">
                            A C E P T A R
                        </button>

                    </form>
                </div>

            </div>
        </div>    
    </div>  

