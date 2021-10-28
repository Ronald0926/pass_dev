<div class='row'>
    <div class="col-4"></div>
    <div class='col-4'>
        <?php 
         if($llavero == 0){ ?>
             <div class="alert alert-warning">
                    <strong>Aun no cuentas con con un bolsillo, por favor cree uno!</strong>
            </div>
            
        <?php } ?>
        
        <h1 class="titulo">Crear bolsillo</h1>
        <form method="POST">
            <input type="text" style="padding-left:10px" name="nombllavero" placeholder="Nombre llavero*" value="" required><br>
            <input type="text" style="padding-left:10px" name="nombres" placeholder="Nombre*" value="" required><br>
            <input type="text" style="padding-left:10px" name="apellidos" placeholder="Apellido*" value="" required><br>
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
            <input type="text" style="padding-left:10px" name="documento" placeholder="Digite el número de documento*" value="" required>
            <input type="email" style="padding-left:10px" name="email" placeholder="Correo electrónico*" value="" required>
            <input type="number" style="padding-left:10px" name="monto" placeholder="Monto asignado*" value="" required>
            <div class="form-inline col-md-12">

                <div class="button  col-md-6 col-md-push-3">
                    <button type="submit">
                        C R E A R
                    </button>
                </div>

            </div>
        </form>
    </div>
    <div class="col-4"></div>

 

</div>
