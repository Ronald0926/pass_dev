<div  style="margin-top: 40px; padding: 50px">
    <div class="row">
        <div class="col-sm-6"> 
            <div class="row">
                <div class="col-sm-2" style=" height: 100px;width: 100px; background-repeat: no-repeat;background-position: 50%;
                     border-radius: 50%; background-size: 100% auto; background-image:url('/static/img/portal/menu/menu-usuario.jpg');">
                </div>
                <div class="col-sm-4">
                    <h3><?= $entidad['NOMBREEMPRESA'] ?></h3>
                    <h5><?= $entidad['NOMBRE'] . ":" . $entidad['DOCUMENTO'] ?> </h5>
                </div>
            </div>
            <div class="row">
                <b>Teléfono: </b> <label style="color: grey"> <?= $telefono['DATO'] ?></label>
                <br>
                <b>Correo eléctronico:</b><label style="color: grey"><?= $infoentida['CORREO_ELECTRONICO'] ?></label>
                <br>
                <b>Dirección:</b><label style="color: grey"><?= $direccion ?></label>
                <br>  
                <label style="color: grey"><?= $piso ?></label>
                <br>
                <label style="color: grey"><?= $edificio ?></label>
                <br>
                <label style="color: grey"><?= $barrio ?></label>
                <br>
                <label style="color: grey"><?=$ciudad['NOMBRECIUDAD'].",".$ciudad['NOMBREPAIS']?></label>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    $(document).ready(function () {
        $.ajax({
            url: "/portal/ajax/ciudad/" + $('#inputDepartamento').val()
        })
                .done(function (msg) {
                    $('#inputCiudad').html(msg)
                    $('#inputCiudad').val(<?= $infoentida['CIUDAD'] ?>)
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
</script>

