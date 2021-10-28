<div class='row'>
    <div class="col-4"></div>
    <div class='col-4'>
        <h1 class="titulo">Bono transportador</h1>
        <form method="POST">
            <input type="text"  name="nit" 
                   placeholder="Digite NIT de la empresa*" value="">
            <?php if (($error != 1) and ($error)) {?>
            Los datos ingresados son incorrectos: <?= $error?>
            
            <?php }?>
            <div class=" portal-paddin-0" style="padding-bottom:20px;padding-top: 20px">
                <div class="select">
                    <select name="rol" class="required" required>
                        <option value="">Seleccione el rol</option>
                        <?php foreach ($rol as $key => $value) { ?>
                            <option value="<?= $value['PK_TIPVIN_CODIGO'] ?>"> 
                                <?= ucfirst(strtolower($value['NOMBRE'])) ?></option>
                        <?php } ?>
                    </select>
                    <div style="margin-left: 0px">
                        Seleccione el rol
                    </div>
                </div>
            </div>
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
            <input type="text" style="padding-left:0px"  name="documento" placeholder="Digite el número de documento*" value="" class="textPat" required>
            <input type="password" style="width: 95%;" 
                   id="contrasena" name="contrasena" placeholder="Digite su contraseña*" value="" class="textPat" required ><span id="icon" class="fa fa-eye-slash" onclick="myFunction()"></span>
            
            <br><br>
            <div class="form-inline col-md-12">
            
                <div class="button  col-6">
                    <button type="submit">
                        I N G R E S A R
                    </button>
                </div>
            
            
                <div class="button col-6">
                    <button formaction="/portal/principal/pantalla">
                        V O L V E R
                    </button>
                </div>
            
            </div>
        </form>
    </div>
    <div class="col-4"></div>
</div>
<script>
function myFunction() {
  var x = document.getElementById("contrasena");
  var y = document.getElementById("icon");
  if (x.type === "password") {
    x.type = "text";
    y.className ="fa fa-eye";
  } else {
    x.type = "password";
    y.className ="fa fa-eye-slash";
  }
}
</script>