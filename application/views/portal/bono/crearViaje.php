<div class='row'>
    <div class='col-lg-4 col-lg-offset-4'>
        <h1>Crear Viaje</h1>
        <form method="POST">
            <div class="col-md-6 portal-paddin-0" style="padding-left: 3px;">
                <div>
                    <select required name="portador" class="required">
                        <option value="">Seleccione un portador</option>
                        <?php foreach ($portador as $key => $value) { ?>
                            <option value="<?= $value['COD'] ?>"> 
                                <?= $value['PORTAD'] ?></option>
                        <?php } ?>
                    </select>
                    
                </div><br>
                 <div>
                    <select required name="vehiculo" class="required">
                        <option value="">Seleccione un vehiculo</option>
                        <?php foreach ($vehiculo as $key => $value) { ?>
                            <option value="<?= $value['COD'] ?>"> 
                                <?= $value['VEHICU'] ?></option>
                        <?php } ?>
                    </select>
                </div><br>
                <div>
                    <select required name="ruta" class="required">
                        <option value="">Seleccione una ruta</option>
                        <?php foreach ($ruta as $key => $value) { ?>
                            <option value="<?= $value['COD'] ?>"> 
                                <?= $value['NOM'] ?></option>
                        <?php } ?>
                    </select>
                </div><br>
                <div>
                    <select required name="tarjeta" class="required">
                        <option value="">Seleccione una tarjeta</option>
                        <?php foreach ($tarjeta as $key => $value) { ?>
                            <option value="<?= $value['COD'] ?>"> 
                                <?= $value['NUMTAR'] ?></option>
                        <?php } ?>
                    </select>
                    
                </div><br>
                <input required name="fecini" type="date" placeholder="Seleccione una fecha de inicio" class="textPat"></input><br>
            </div><br><br>
            <div>
                <div class="button">
                    <button type="submit">
                        VOLVER
                    </button>
                </div>
            </div>
            
                <div  class="button btn-default">
                    <button type="submit">
                        GUARDAR
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>