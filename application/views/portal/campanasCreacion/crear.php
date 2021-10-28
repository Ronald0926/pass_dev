<div style="top:100px; padding-left: 2%; ">
    <div class="row ">
        <h6 class="titulo">CREAR CAMPAÑA</h6>
        <?php if ($ok == 1) { ?>
            <div class="alert alert-danger col-md-6 col-md-push-3">
                <strong>Ha ocurrido un error, intente nuevamente.</strong>
            </div>
            Ha ocurrido un error, intente nuevamente
        <?php } ?>
        <?php if ($error == 2) { ?>
            <div class="alert alert-danger col-md-6 col-md-push-3">
                <strong>El nombre de la campaña ya existe.</strong>
            </div>
        <?php } ?>
        <?php if ($ok == 3) { ?>
            Cambio realizado satisfactoriamente
        <?php } ?>

       

        
        
    </div>
    <div class="col-md-3"></div>
    <div class="col-md-6">
        <form method="post">

            <div class="row">
           
                <div class="col-md-5 col-md-offset-3 mx-auto">
                <input name="campana" placeholder="Nombre de la Campaña" class="required" class="textPat" required> <br>
                </div>
               
                
            </div>
            <div class="row">
                <?php foreach ($usuarios as $key => $value) { ?>
                    <div class="col-md-12" style="padding: 3px !important;">
                        <div class="col-md-6 text-center">
                            <div  class="login-checkbox" style="float:left;"  >
                                <input type="checkbox" name ="usuarios[]" value="<?= $value['CODIGOENT'] ?>"   /> 
                                <div class="">
                                    <span  class="login-checkbox-check" > 
                                    </span>
                                </div>
                            </div>
                            <label>
                                <?= $value['NOMBREENT'] . " " . $value['APELLIDO'] ?>
                            </label>
                        </div>
                        <div class="select col-md-6">
                            <select name="<?= $value['CODIGOENT'] ?>_roles" id="inputRol" class="campana sel<?= $value['CODIGOENT'] ?>" >
                                <option value="">Seleccione Rol</option>
                                <?php foreach ($roles as $key => $value) { ?>

                                    <option value="<?= $value['CODIGO'] ?>"> <?= $value['NOMBRE'] ?></option>
                                <?php } ?>
                            </select>
                            <!-- <div> Seleccione Rol</div> -->
                        </div>
                    </div>
                <?php } ?>
                <div class="row button col-md-6 col-md-push-3" style="margin-bottom: 8%">
                    <button type="submit">G U A R D A R </button>
                    <br><br>
                     <div class=" linkgenerico spacing">
                        <a href="/portal/campanasCreacion/lista"><span class="glyphicon glyphicon-chevron-left"></span>VOLVER</a>
                    </div> 
                </div>
            </div>
        </form>
       
    </div>
    <div class="col-md-3"></div>
</div>
<style>
    .select {

        appearance: none !important;
    }

    .campana {
        -moz-appearance: none;
        -webkit-appearance: none;
        -ms-appearance: none;
        -o-appearance: none;
        appearance: none;
        cursor: pointer;
        position: absolute !important;
        width: 100% !important;
        top: 0px !important;
        left: 0px !important;
        margin-top: 6px !important;
        opacity: 1 !important;
        text-align: left;
        border: none;
        border: 1px solid #888;
        padding: 4px 4px 4px 2px;
        border-radius: 15px;
        background-image: url(/static/img/portal/login/select1.jpg);
        background-size: 20px;
        background-position-x: 99%;
        background-position-y: 4px;
        background-repeat: no-repeat;
        padding-right: 10%;
        padding-left: 5px;


    }

    .dropdown-toggle::after {
        border: none;
    }
</style>

<script>
    $(document).ready(function () {
        var idIn = 0;
        $('body').on('click', '.login-checkbox', function () {
            //var id = $(this).attr('id');

            // $('#'+idIn).prop('required',true);
            if ($(this).children('input:checked').is(':checked')) {

                idIn = $(this).children('input:checked').val();
                addrequired(idIn);
            } else {
                removerequired(idIn);
            }
        });



    });
    function addrequired(id) {
        $(".sel" + id).prop('required', true);
    }
    function removerequired(id) {
        $(".sel" + id).prop('required', false);
    }
</script>