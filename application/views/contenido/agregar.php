<h2><?= $objeto['nombre_conte_objeto'] ?> / <?= $operacion ?></h2>
<hr/>
<div>
    <?php if ($alert == 3) { ?>
        <div class="alert alert-danger">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
            <strong>Ooops!</strong> El archivo seleccionado no es valido.
        </div>
    <?php } ?>
    <?php if ($alert == 4) { ?>
        <div class="alert alert-danger">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
            <strong>Ooops!</strong> La imagen seleccionada no es admitida, solo gif|jpg|png|jpeg.
        </div>
    <?php } ?>
    <form class="form-horizontal daos_formulario" action="" method="POST" enctype="multipart/form-data">

        <?php foreach ($campos as $campo) { ?>
            <div class="form-group">
                <label class="col-sm-2 control-label" for="<?= $campo['id_conte_campo'] ?>"><?= $campo['nombre_conte_campo'] ?></label>
                <div class="col-sm-5">
                    <?php if ($campo['id_conte_tipo'] == 1) { ?>
                        <input class="form-control <?= $campo['clases_conte_campo'] ?>" maxlength="<?= $campo['tamano_conte_campo'] ?>" name="<?= $campo['id_conte_campo'] ?>" type="text" id="<?= $campo['id_conte_campo'] ?>" placeholder="<?= $campo['nombre_conte_campo'] ?>" value="<?= $campo['value'] ?>">
                    <?php } ?>
                    <?php if ($campo['id_conte_tipo'] == 2) { ?>
                        <textarea  class="form-control <?= $campo['clases_conte_campo'] ?>" style="width: 80%; min-height: 200px" name="<?= $campo['id_conte_campo'] ?>" id="<?= $campo['id_conte_campo'] ?>"><?= $campo['value'] ?></textarea>
                    <?php } ?>
                    <?php if ($campo['id_conte_tipo'] == 3) { ?>
                        <input class="form-control <?= $campo['clases_conte_campo'] ?>" name="file_<?= $campo['id_conte_campo'] ?>" type="file" id="<?= $campo['id_conte_campo'] ?>" placeholder="<?= $campo['nombre_conte_campo'] ?>" > (PDF)
                        <?php if ($campo['id_conte_tipo'] == 3 && $campo['value'] != "") { ?>
                            <br>
                            <a href="<?= $campo['value'] ?>" target="_blank">Ver actual</a> 
                        <?php } ?>
                    <?php } ?>
                    <?php if ($campo['id_conte_tipo'] == 4) { ?>
                        <input class="form-control <?= $campo['clases_conte_campo'] ?>" name="file_<?= $campo['id_conte_campo'] ?>" type="file" id="<?= $campo['id_conte_campo'] ?>" placeholder="<?= $campo['nombre_conte_campo'] ?>" > (JPG | GIF | PNG)
                        <?php if ($campo['id_conte_tipo'] == 4 && $campo['value'] != "") { ?>
                            <br>
                            <img src="<?= $campo['value'] ?>" width="200" />
                        <?php } ?>
                    <?php } ?>
                    <?php if ($campo['id_conte_tipo'] == 5) { ?>
                        <input class="form-control <?= $campo['clases_conte_campo'] ?>" maxlength="<?= $campo['tamano_conte_campo'] ?>" name="<?= $campo['id_conte_campo'] ?>" type="text" id="<?= $campo['id_conte_campo'] ?>" placeholder="<?= $campo['nombre_conte_campo'] ?>" value="<?= $campo['value'] ?>">
                        <?php if ($campo['id_conte_tipo'] == 5 && $campo['value'] != "") { ?>
                            <br>
                            <iframe width="300" height="200" src="<?= $campo['value'] ?>" frameborder="0" allowfullscreen></iframe>
                        <?php } ?>
                    <?php } ?>
                </div>
            </div>
        <?php } ?>

        <div class="form-group">
            <div class="col-sm-5 col-md-offset-2">
                <button type="submit" class="btn btn-primary">Guardar</button>
            </div>
        </div>
    </form>
</div>