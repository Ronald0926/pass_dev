<div class='row' style="padding-top: 5%">
<div class="col-4"></div>
<div class="col-4">
    <?php if ($ok == 1) { ?>
    <div class="alert alert-danger">
        <p> Se ha enviado contraseña al correo</p>
    </div>
    <?php } ?>
    <?php if ($ok == 2) { ?>
    <div class="alert alert-danger">
        <p><?=$codigo?>:La nueva contraseña invalida </p>
    </div>
    <?php } ?>
    <?php if ($ok == 3) { ?>
    <div class="alert alert-success">
        <p> Cambio realizado satisfactoriamente</p>
    </div>
    <?php } ?>
        <div>
            <h1 class="titulo">Cambio de contraseña</h1>
            <form  method="post">
            <input name="contrasenaActual" id="contrasena1" class="textPatCc" style="width: 95%;"  type="password" placeholder="Digite su contraseña actual" required><span id="icon1" class="fa fa-eye-slash" onclick="myFunctionVer(1)"></span> 
            <input name="contrasenaNueva" id="contrasena2" class="textPatCc"   type="password" style="width: 95%;" placeholder="Digite su contraseña nueva" required> <span id="icon2" class="fa fa-eye-slash" onclick="myFunctionVer(2)"></span> 
            <input name="contrasenaVerifica" id="contrasena3" class="textPatCc"  type="password" style="width: 95%;" placeholder="Digite nuevamente su contraseña nueva" required> <span id="icon3" class="fa fa-eye-slash" onclick="myFunctionVer(3)"></span>
            <br>
            <br>
            <label id='label2045' style="color: red;" hidden>La contraseña debe contener una letra mayúscula, una minúscula, un número, ser menor a 8 digitos y un caracter especial</label>
            <div class="button col-sm-6">
                <button onclick="goBack()">V O L V E R</button>
            </div>
            <div class="button col-sm-6">
                <button type="submit">G U A R D A R</button>
            </div>
        </form>
    </div>
</div>
</div>
<div class="col-4"></div>
<script>
       var erroroPass = <?php if (isset($codigo)==2045) {   echo "1;";} else {    echo "0;";} ?>
    function goBack() {
        window.history.back();
    }
    if (erroroPass == 1) {
        $('#label2045').show();
    }
</script>

