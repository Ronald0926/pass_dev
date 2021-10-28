<div class='row'>
    <form method="POST">
        <div class='col-lg-4 col-lg-offset-1'>
            <h1>Crear Ruta</h1>

            <?php if ($error == 1) { ?>
                CREADO SATISFACTORIAMENTE
            <?php } ELSEif ($error != 1 and ! is_null($error)) { ?>
                <?= $error ?>
            <?php } ?>
            <input required type="text" style="padding-left:10px" name="nomrut"  class="textPat"
                   placeholder="Nombre de la Ruta" value=""><br><br>
            <input required type="text" style="padding-left:10px" name="cospro" class="textPat"
                   placeholder="Costo Aproximado" value=""><br><br>
            <input required type="text" style="padding-left:10px" name="kilpro"  class="textPat"
                   placeholder="Kilómetros Aproximado" value=""><br><br>
            <input required type="text" style="padding-left:10px" name="congas" class="textPat"
                   placeholder="Consumo Gasolina" value=""><br><br>
        </div>
        <div class='col-lg-4'>
            <div class='col-lg-6'>
                <label>Punto inicio</label>
            </div>
            <div class='col-lg-6'>
                <input required type="text" style="padding-left:10px" name="punini"  class="textPat"
                       placeholder="Nombre" value=""><br><br>
            </div>
            <div class='col-lg-6'>
                <label>Punto Final</label>
            </div>
            <div class='col-lg-6'>
                <input required type="text" style="padding-left:10px" name="punfin" class="textPat"
                       placeholder="Nombre" value=""><br><br>
            </div>
            <div class='col-lg-6'>
                Estado
            </div>
            <div class='col-lg-6'>
                <select required style="width:100%;border-radius:10px;padding-left:10px" name='estado'>
                    <option value="">Seleccione el estado</option>
                    <?php foreach ($estadoRuta as $key => $value) { ?>
                        <option value="<?= $value['PKY'] ?>"> <?= $value['NOM'] ?></option>
                    <?php } ?>
                </select><br><br>
            </div>

            <button type="submit" href="/portal/bono/bonoPortador" type="button" 
               class="btn btn-default" 
               >GUARDAR</button>

        </div>
    </form>

</div>
