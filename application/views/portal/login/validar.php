
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
                        <?php if ($ok == 1) { ?>
                            <div class="alert alert-danger">
                                Los datos Ingresados son Incorrectos
                            </div>
                        <?php } ?>
                        <?php if ($ok == 2) { ?>
                            <div class="alert alert-success">
                                La contraseña se ha restaurado correctamente
                            </div>
                        <?php } ?>
                        <?php if ($ok == 3) { ?>
                            <div class="alert alert-danger">
                                El usuario no tiene rol activo
                            </div>
                        <?php } ?>
                        <form class="" action="" method="POST">
                            <table>
                                <tr>
                                    <td>
                                        <input required type="number" name="nit" class="numPat"  placeholder="Digite el NIT de la empresa" required/>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <select class="select-online " required  name="tipoDocumento" required>
                                            <option value=""> Seleccione el tipo de Documento</option>
                                            <?php foreach ($tipoDocumento as $key => $value) { ?>
                                                <option value="<?= $value['PK_TD_CODIGO'] ?>"> <?= $value['NOMBRE'] ?></option>
                                            <?php } ?>
                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <input required type="text" name="documento" class="textPat" placeholder="Digite el número de Documento" required/>
                                    </td>
                                </tr>
                            </table>
                            <br/><br/>
                            <button type="submit">
                                S I G U I E N T E
                            </button>


                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>


    