<div class='row'>
    <div class="col-4"></div>
    <div class='col-4'>
        <h1 class="titulo">Llave Maestra</h1>
        <form method="POST">
            <input type="text" style="padding-left:10px" name="nit" placeholder="Digite NIT de la empresa*" value=""><br>
            <div class="portal-paddin-0" style="padding-left: 0px;">
                <div class="select ">
                    <select name='tdocumento' class="required" required>
                        <option value="">Seleccione el tipo de Documento*</option>
                        <?php foreach ($tipoDocumento as $key => $value) { ?>
                            <option value="<?= $value['ABREVIACION'] ?>"> <?= ucfirst(strtolower($value['NOMBRE'])) ?></option>
                        <?php } ?>
                    </select>
                    <div>
                        Seleccione el tipo de Documento*
                    </div>
                </div>
            </div>
            <input type="text" style="padding-left:10px" name="documento" placeholder="Digite el número de documento*" value="">
            <input type="password" style="padding-left:10px;width: 97%;" 
                   id="contrasena" name="contrasena" placeholder="Digite su contraseña*" value="" class="textPat" required ><span id="icon" class="fa fa-eye-slash" onclick="myFunction()"></span><br><br>
            <div class="form-inline col-md-12">

                <div class="button  col-6">
                    <button type="submit">
                        I N G R E S A R
                    </button>
                </div>
                <div class="button col-6">
                    <button onclick="goBack()">
                        V O L V E R
                    </button>
                </div>

            </div>
        </form>
    </div>
    <div class="col-4"></div>
</div>
