<div class='row'>
    <div class='col-lg-4 col-lg-offset-4'>
        <h1>Portador</h1>
        <form method="POST">
            <?php if ($error != 1 and !is_null($error)){?>
                <?= $error ?>
            <?php } ?>
            <input required type="text" style="padding-left:10px" name="nombre" class="textPat"
                   placeholder="Nombre" value=""><br><br>
            <input required type="text" style="padding-left:10px" name="apellido" class="textPat"
                   placeholder="Apellido" value=""><br><br>
            <select required style="width:100%;border-radius:10px;padding-left:10px" name='tdocumento'>
                <option value="">Seleccione el tipo de Documento*</option>
                <?php foreach ($tipoDocumento as $key => $value) { ?>
                    <option value="<?= $value['ABREVIACION'] ?>"> <?= $value['NOMBRE'] ?></option>
                <?php } ?>
            </select><br><br>
            <input required type="email" style="padding-left:10px" name="correo"  pattern="[A-Za-z0-9._%+-]{3,}@[A-Za-z]{3,}\.[A-Za-z]{2,}(?:\.[A-Za-z]{2,})?"
                   placeholder="Correo Electronico" value=""><br><br>
            <input required type="text" style="padding-left:10px" name="documento" class="textPat"
                   placeholder="Documento" value=""><br><br>
            <select required style="width:100%;border-radius:10px;padding-left:10px" name='tlicencia'>
                <option value="">Seleccione el tipo de licencia</option>
                <?php foreach ($tipoLicencia as $key => $value) { ?>
                    <option value="<?= $value['PK_TIPLIC_CODIGO'] ?>"> <?= $value['NOMBRE'] ?></option>
                <?php } ?>
            </select><br><br>
            <p required style="float:left;" >Estado</p><select style="border-radius:10px;padding-left:10px" name='estado'>
                <option value="">Estado</option>
                <?php foreach ($estadoPort as $key => $value) { ?>
                    <option value="<?= $value['PK_ESTPOR_CODIGO'] ?>"> <?= $value['NOMBRE'] ?></option>
                <?php } ?>
            </select><br><br>
            <div class="button col-sm-6">
                <a href="/portal/bono/bonoPortador" type="button" class="btn" 
                        >VOLVER</a>
            </div>
            <div class="button col-sm-6">
                <button name="solabono" type="submit" class="btn btn-default" 
                        >CREAR</button>
            </div>
        </form>
    </div>
</div>
