
<div style=" ">
  
        <div class="col-sm-3"></div>
        <div class="col-sm-6" style="margin-bottom: 10%">
            <h2 class="titulo-iz">Actualizar usuario</h2>
              <form  method="post" id="formmodificarusuario">
            <input type="text" name="primerNombre" placeholder="Primer Nombre" value="<?= $usuarioa['NOMBRE'] ?>" class="textPat" required><br>
            <input type="text" name="primerApellido" placeholder="Primer Apellido" value="<?= $usuarioa['APELLIDO'] ?>" class="textPat" required><br><br>
            <div class="select">
                <select name="departamentos" id="inputDepartamento" class="required" required>
                    <option value=""> Seleccione Departamento</option>
                    <?php foreach ($departamentos as $key => $value) { ?>
                        <option value="<?= $value['PK_DEP_CODIGO'] ?>" <?php if ($value['PK_DEP_CODIGO'] == $usuarioa['DEPARTAMENTO']) echo 'selected'; ?>> <?= $value['NOMBRE'] ?></option>
                    <?php } ?>
                </select>
                <div><?php echo $depentidad != "" ? $depentidad : 'Seleccione Departamento' ?></div>
            </div>  
            
            <br>
            <div class="select">
                <select name="ciudad" id="inputCiudad" class="required" >
                    <option value=""> Seleccione Ciudad</option>
                </select>
                <div id="divCiudad"><?php echo $ciuentidad != "" ? $ciuentidad : 'Seleccione Ciudad' ?></div>
            </div>
            <input type="text" name="direccion" placeholder="Dirección" value="<?= $direccion['DATO'] ?>" class="textPatDir" required><br>
            <input type="email" name="email" placeholder="E-mail" value="<?= $usuarioa['CORREO_ELECTRONICO'] ?>" required><br>
            
            <input type="number" name="telefono" placeholder="Telefono" value="<?= $telefono['DATO'] ?>" class="required textPat"  required>
        <!--    <h4>Límite de uso</h4>

            Montos Máximos a usar:        <input type="number" name="maxmonto" placeholder="" value="<?= $maxmonto['LIMITE_GASTO'] ?>" class="numPat" required><br>
            No. Tarjetas máximas a pedir: <input type="number" name="maxtarjetas" placeholder="" value="<?= $maxmonto['LIMITE_TARJETAS'] ?>" class="numPat" required><br>
            <br>-->
           </form>
            <br>
            <div class="row">
                <div class="button col-sm-6">
                    <button onclick="goBack()">V O L V E R</button>
                </div>
                <div class="button col-sm-6">
                    <button class="spacing" data-toggle="modal" data-target="#Modalconf" id="validate">G U A R D A R</button>
                </div>
            </div>  
        </div> 
        <div class="col-sm-3" >

            <?php foreach ($permisos as $key => $value) { ?>
                <?= $value['NOMBRE'] ?>   <input type="checkbox" 
                <?php
                foreach ($permisosrol as $valuerol) {
                    if ($valuerol['PK_PEREMP_CODIGO'] == $value['PK_PEREMP_CODIGO'])
                        echo"checked='true'";
                }
                ?>  name="privilegios[]" value="<?= $value['PK_PEREMP_CODIGO'] ?>" /> <br>          
                   <?php } ?>

        </div>
 
</div>

<!-- Modal confirmacion-->
<div class="modal fade" id="Modalconf" role="dialog" style="margin-top: 15%;"  data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content" style="border-radius:35px">
            <div class="modal-body" style="text-align: center;height: 230px;">

                <p  style="font-size:24px;color:#888686;font-weight: bold;padding-top: 25px">¿Desea Modificar este Usuario?
                </p>
                <label id="nomllavero" style="font-size: 18px;color: #366199;font-weight: bold;"></label>

                <div style="">
                    <div class="button col-sm-6" >
                        <button type="button" name="ACEPTAR" value="1" class="btn btn-default"  onclick="
                                $('#formmodificarusuario').submit();" >A C E P T A R</button>
                    </div>
                    <div class="button col-sm-6" >
                        <button type="button" name="CANCELAR" class="btn btn-default" data-dismiss="modal">C A N C E L A R</button>
                    </div>
                </div>
                <br>
            </div>
        </div>
    </div>
</div>
<script>
    function goBack() {
        window.history.go(-1);
    }
</script>


<script type="text/javascript">
    $(document).ready(function () {
        $.ajax({
            url: "/portal/ajax/ciudad/" + $('#inputDepartamento').val()
        })
                .done(function (msg) {
                    $('#inputCiudad').html(msg)
                    $('#inputCiudad').val(<?= $usuarioa['CIUDAD'] ?>)
                });

        $('#inputDepartamento').change(function () {
            $('#divCiudad').text("Seleccione Ciudad");
            $.ajax({
                url: "/portal/ajax/ciudad/" + $('#inputDepartamento').val()
            })
                    .done(function (msg) {
                        $('#inputCiudad').html(msg)
                    });
        });
    });
</script>