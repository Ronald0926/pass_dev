
    <body>
        <div class="login-fondo">
            &nbsp;
        </div>
        <div class="container-fluid" >
            <div class="row">
                <<div class="col-md-4 col-lg-4 col-sm-4 col-xs-4"></div>
                    <div class="col-sm-4 col-lg-4 col-sm-4 col-xs-4" >
                            <div class="login-form">
                                <img src="/static/img/logo.png" width="65%" />
                                <br/>
                                <br/>
                                <br/>
                                <?php if ($ok == 1) { ?>
                                    <div class="alert alert-danger">
                                        Los datos Ingresados son Incorrectos
                                    </div>
                                <?php } ?>
                                <form class="" action="" method="POST">
                                    <table>
                                        <tr>
                                            <td>
                                                <input required type="text" name="nit" placeholder="Digite el NIT de la empresa" />
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <select class="select-online " required  name="tipoDocumento" >
                                                    <option value=""> Seleccione el tipo de Documento</option>
                                                    <?php foreach ($tipoDocumento as $key => $value) { ?>
                                                        <option value="<?= $value['PK_TD_CODIGO'] ?>"> <?= $value['NOMBRE'] ?></option>
                                                    <?php } ?>
                                                </select>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <input required type="text" name="documento" placeholder="Digite el número de Documento" />
                                            </td>
                                        </tr>
                                    </table>
                                    <br/>
                                    <br/>
                                    <br/>
                                    <button type="submit">
                                        S I G U I E N T E
                                    </button>
                                    
                                            
                                </form>
                            </div>
                </div>
                <div class="col-md-4 col-lg-4 col-sm-4 col-xs-4"></div>
            </div>
        </div>
        