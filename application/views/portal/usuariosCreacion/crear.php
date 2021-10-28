<div class="loader" id="loader" hidden=""></div>
<div class="col-md-3 col-sm-2" ></div>
<div class="col-md-6 col-sm-8" style="margin-top: 5px; padding: 50px; margin-bottom: 50px">
    <?php if ($ok == 1) { ?>
        Los datos Ingresados son Incorrectos
    <?php } elseif ($error == 99) { ?>
        <div class="alert alert-danger">No es posible crear el usuario</div>
    <?php } elseif ($error == 1) { ?>
        <div class="alert alert-danger">Error insesperado, por favor intente de nuevo.</div>
    <?php } elseif(!empty($resVinculExiste) && isset($resVinculExiste) ){?>
        <div class="alert alert-danger">Error <?php echo $resVinculExiste ?>, <?php echo $msgVinculExiste ?></div>
        <?php }?>
    <h1 class="titulo-iz">Crear nuevo usuario</h1>
    <form  method="post" id="formCreaUsuario">
        <div class="row"  >
         

                <input type="text" name="primerNombre" placeholder="Primer Nombre" value="" class="required textPat" required>

                <input type="text" name="segundoNombre" placeholder="Segundo Nombre" value="" class="required textPat" >

                <input type="text" name="primerApellido" placeholder="Primer Apellido" value="" class="required textPat" required>

                <input type="text" name="segundoApellido" placeholder="Segundo Apellido" value="" class="required textPat" >

                <div class="select">
                    <select name="tipoDocumento" id="tipoDocumento" class="required" required>
                        <option value=""> Seleccione Tipo Documento</option>
                        <?php foreach ($tipoDocumento as $key => $value) { ?>
                            <option value="<?= $value['PK_TD_CODIGO'] ?>"> <?= $value['NOMBRE'] ?></option>
                        <?php } ?>
                    </select>
                    <div> Seleccione Tipo Documento</div>
                </div>
                <input type="text" name="documento" placeholder="Documento (No puede iniciar en 0)" value="" class="required" pattern="^[1-9][0-9]*$" maxlength="11" required>

                <input type="text" name="fechaNacimiento" placeholder="Fecha de nacimiento" value="" class="required textPat"  onfocus="(this.type = 'date')"  style="width: 98%;" required>
                <span class="fa fa-calendar"></span>
                <br>
                <div class="select">
                    <select name="departamentos" id="inputDepartamento" class="required" required>
                        <option value="" > Seleccione Departamento</option>
                        <?php foreach ($departamentos as $key => $value) { ?>
                            <option value="<?= $value['PK_DEP_CODIGO'] ?>"> <?= $value['NOMBRE'] ?></option>
                        <?php } ?>
                    </select>
                    <div class="lower"> Seleccione Departamento</div>
                </div>
                <div class="select">
                    <select name="ciudad" id="inputCiudad" class="required" required>
                        <option value=""> Seleccione Ciudad</option>
                    </select>
                    <div> Seleccione Ciudad</div>
                </div>
                <input type="text" name="direccion" placeholder="Dirección" value="" class="required" maxlength="60" required>
                <input type="email" name="correo" placeholder="Correo" value="" class="required" required>
                <div class="select select2">
                    <select name="tipoVinculacion" id="tipoVinculacion" class="required" required>
                        <option value=""> Seleccione Rol</option>
                        <?php foreach ($tipoVinculacion as $key => $value) { ?>
                            <option value="<?= $value['PK_TIPVIN_CODIGO'] ?>"> <?= $value['NOMBRE'] ?></option>
                        <?php } ?>
                    </select>
                    <div> Seleccione Rol</div>
                </div>
                   <div class="col-md-12">
                <div class="select select2"style="display:none" id="conetenedorcapana" >
                    <select name="campana" id="campana" class="required" >
                        <option value=""> Seleccione Campaña</option>
                        <?php foreach ($campana as $key => $value) { ?>
                            <option value="<?= $value['PK_CAMPAN_CODIGO'] ?>"> <?= $value['NOMBRE'] ?></option>
                        <?php } ?>
                    </select>
                    <div> Seleccione Campaña</div>
                </div>

                <input type="number" pattern="^([3])\d{9}$" name="telefono" placeholder="Telefono" value="" class="required textPat"  required>
            </div>
            <div class="col-md-6" hidden>

                <br>
                <input type="text" pattern="^3[\d]{9}" name="celular" placeholder="Celular" value="" class="required textPat" hidden>
                <br>
                <br>
                <div class="select" hidden>
                    <select name="genero" id="inputDepartamento" class="required" >
                        <option value=""> Seleccione Genero</option>
                        <?php foreach ($generos as $key => $value) { ?>
                            <option value="<?= $value['PK_GEN_CODIGO'] ?>"> <?= $value['NOMBRE'] ?></option>
                        <?php } ?>
                    </select>
                    <div> Seleccione Genero</div>
                </div>
                <br>
                <div class="select" hidden>
                    <select name="estadoCivil" id="inputDepartamento" class="required" >
                        <option value=""> Seleccione Estado Civil</option>
                        <?php foreach ($estadoCivil as $key => $value) { ?>
                            <option value="<?= $value['PK_ESTCIV_CODIGO'] ?>"> <?= $value['NOMBRE'] ?></option>
                        <?php } ?>
                    </select>
                    <div> Seleccione Estado Civil</div>
                </div
                <br>


                <br>
                <input type="text" name="nacionalidad" placeholder="Nacionalidad" value="" class="required textPat" hidden>

                <br>
                <h4>Límite de uso:</h4>
                <input type="number" name="maxmonto" placeholder="Montos Máximos a usar " value="" class="required numPat" hidden><br>
                <input type="number" name="maxtarjetas" placeholder="No. Tarjetas máximas a pedir" value="" class="required numPat" hidden><br>
                <br>
            </div>
        </div>
        <div class="button col-sm-6">
            <button onclick="goBack()">V O L V E R</button> 
        </div>
        <div class="button col-sm-6"><button type="submit"> G U A R D A R </button></div>

        <br> 
        <!--<div class="col-sm-4" style="padding-top: -100px;">
            <h4>Permisos</h4>
        <?php foreach ($permisos as $key => $value) { ?>
            <?= $value['NOMBRE'] ?>   <input type="checkbox" 
            <?php
            foreach ($permisosrol as $valuerol) {
                if ($valuerol['PK_PEREMP_CODIGO'] == $value['PK_PEREMP_CODIGO'])
                    echo"checked='true'";
            }
            ?>  name="privilegios[]" value="<?= $value['PK_PEREMP_CODIGO'] ?>" /> <br>          
        <?php } ?>
        </div>-->
    </form>
</div>
<div class="col-md-3 col-sm-2" ></div>
<script>
    function goBack() {
        window.history.back();
    }
</script>


<script type="text/javascript">
    $(document).ready(function () {
        $.ajax({
            url: "/portal/ajax/ciudad/" + $('#inputDepartamento').val()
        })
                .done(function (msg) {
                    $('#inputCiudad').html(msg)
                    $('#inputCiudad').val(<?= $usuario['CIUDAD'] ?>)
                });

        $('#inputDepartamento').change(function () {

            $.ajax({
                url: "/portal/ajax/ciudad/" + $('#inputDepartamento').val()
            })
                    .done(function (msg) {
                        $('#inputCiudad').html(msg)
                    });
        });
    });
    $("#tipoVinculacion").change(function () {
      if($(this).val()=='45'){

      $("#conetenedorcapana").show();
      $("#campana").prop('required', true);
      }else{
        $("#conetenedorcapana").hide();
      } 
    })
    $("#formCreaUsuario").submit(function () {
       
        $('#loader').modal('show');

    });
</script>