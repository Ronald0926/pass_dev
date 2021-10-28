<div class='row'>
    <div class='col-lg-4 col-lg-offset-4'>
        <h1>Crear Vehiculo</h1>
        <form method="POST">
            <?php if ($error == 1 ){?>
                CREADO SATISFACTORIAMENTE
            <?php } ELSEif ($error != 1 and !is_null($error)){?>
                <?= $error ?>
            <?php } ?>
            <input required type="text" style="padding-left:10px" name="placa" class="textPat"
                   placeholder="Placa" value=""><br><br>
            <input required type="text" style="padding-left:10px" name="marca" class="textPat"
                   placeholder="Marca" value=""><br><br>
            <input required type="text" style="padding-left:10px" name="modelo" class="textPat"
                   placeholder="Modelo" value=""><br><br>
            <input required type="text" style="padding-left:10px" name="anio" class="numPat"
                   placeholder="Año" value=""><br><br>
            
 
            <select required style="width:100%;border-radius:10px;padding-left:10px" name='tcarga'>
                <option value="">Seleccione tipo de carga</option>
                <?php foreach ($tipoCarga as $key => $value) { ?>
                    <option value="<?= $value['PKY'] ?>"> <?= $value['NOM'] ?></option>
                <?php } ?>
            </select><br><br>
            <select required style="width:100%;border-radius:10px;padding-left:10px" name='tvehiculo'>
                <option value="">Seleccione tipo de vehiculo</option>
                <?php foreach ($tipoVehiculo as $key => $value) { ?>
                    <option value="<?= $value['PKY'] ?>"> <?= $value['NOM'] ?></option>
                <?php } ?>
            </select><br><br>
            <select required style="width:100%;border-radius:10px;padding-left:10px" name='estado'>
                <option value="">Seleccione el estado</option>
                <?php foreach ($estado as $key => $value) { ?>
                    <option value="<?= $value['PKY'] ?>"> <?= $value['NOM'] ?></option>
                <?php } ?>
            </select><br><br>
            
            <div class="button col-sm-6">
                <a href="/portal/bono/listaVehiculo" type="button" class="btn" 
                        >VOLVER</a>
            </div>
            <div class="button col-sm-6">
                <button name="solabono" type="submit" class="btn btn-default" 
                        >CREAR</button>
            </div>
        </form>
    </div>
</div>
