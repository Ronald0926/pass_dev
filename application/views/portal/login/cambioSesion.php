
<div style=" margin-bottom: 100px; margin-top: 50px;padding-left:25%;">
        <div class="col-sm-6" style="padding-left:2%;margin-bottom:5%;">
            <h1 class="titulo-iz">Cambio de sesión</h1>
            <?php if ($ok == 1) { ?>
            Los datos Ingresados son Incorrectos
            <?php } ?>
            <?php if ($ok == 2) { ?>
            Ha ocurrido un error, Intente Nuevamente
            <?php } ?>
            <form method="post">
                <div class="select select2">
                    <select name="empresas" id="inputEmpresa" required>
                        <option value=""> Seleccione Empresa</option>
                        <?php foreach ($empresas as $key => $value) { ?>
                            <option value="<?= $value['CODIGOEMPRESA'] ?>"> <?= $value['NOMBREEMPRESA'] ?></option>
                        <?php } ?>
                    </select>
                    <div> Seleccione empresa</div>
                </div>
                <br>
                <div class="select select2">
                    <select name="campana" id="inputCampana" required>
                        <option value=""> Seleccione Campaña</option>
                    </select>
                    <div> Seleccione campaña</div>
                </div>
                <br>
                <div class="select select2">
                    <select name="perfil" id="perfil" class="required" required>
                        <option value=""> Seleccione Perfil</option>
                        <?php foreach ($roles as $key => $value) { ?>
                            <option value="<?= $value['PK_TIPVIN_CODIGO'] ?>"> <?= $value['NOMBRE'] ?></option>
                        <?php } ?>
                    </select>
                    <div> Seleccione perfil</div>
                </div>
                <br>
                <div >
                    <input class="required"  style="width: 100%;" class="numPat" type="number" name="nitEmpresa" placeholder="Digite Nit de la empresa" required/>
                </div>
                <br>
                <div >
                <input class="required"  style="width: 100%;" type="password" class="textPat" name="contrasena" placeholder="Digite contraseña" required/>
                </div>
                <br>
                <div class="button">
                    <button type="submit">C A M B I A R</button>
                </div>
            </form>
        </div>
    </div>

 <script type="text/javascript">
        $(document).ready(function () {
//            $.ajax({
//                url: "/portal/ajax/campana/" + $('#inputEmpresa').val()
//            })
//                    .done(function (msg) {
//                        $('#inputCampana').html(msg)
//                        $('#inputCampana').val(<?= $campana['campana'] ?>)
//                    });

            $('#inputEmpresa').change(function () {

                $.ajax({
                    url: "/portal/ajax/onlineCampana/" + $('#inputEmpresa').val()
                })
                        .done(function (msg) {
                            $('#inputCampana').html(msg)
                            $('#inputCampana').trigger('change');
                        });
            });
        });
    </script>
