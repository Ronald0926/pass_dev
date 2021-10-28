<style>
    #masivoIconos td,
    th {
        padding: 30px;
    }
    .lblsaldoabono{
        margin-top: 2%;
        margin-left: 25%;
        width: 50%;
        text-align: center;
        background-color: #e3e3e3;
        font-size: 15px;
        font-weight: bold;   
        padding: 8px;
        color: #888;
        border: 1px solid;
        border-color: #979797;
        border-radius: 25px;
    }
    .tnotifi{
        color: red;
        padding-left:  15px;
        display: none;
    }
    .triangulo {
        margin-top: 7%;
        width: 0;
        height: 0;
        border-left: 30px solid #366199;
        border-top: 25px solid transparent;
        border-bottom: 25px solid transparent;
    }
</style>
<?php
//$rol = $this->session->userdata("rol"); 
$rol = $_SESSION['rol']; 
?>
<div class="loader" id="loader" hidden=""></div>
<div class="col-lg-2"></div>
<div class="container col-lg-8" style=" margin-bottom: 200px; margin-top: -50px;">
    <hr style="border-top: 1px solid #eee0;">
    <h2 class="titulo-iz">Devolución saldo llavero a llave maestra</h2>
    <ul class="nav nav-tabs">
        <?php if(($rol==60) || ($rol==61)){?>
        <li class="active"><a data-toggle="tab" href="#devolucion" >Devolución</a></li>
        <?php }?>
        <?php if(($rol==60) || ($rol==61)){?>
        <li><a href="/portal/llaveMaestra/solicitarDevolucion" >Solicitar devolución</a></li>
        <?php }?>
        <?php if(($rol==60) || ($rol==61)){?>
        <li><a href="/portal/llaveMaestra/reverso" >Reverso</a></li>
        <?php }?>
    </ul>
    
    
    <?php if (isset($_GET['errordata'])) { ?>
        <div class="alert alert-info">
            <strong>No se ha seleccionado ningún producto</strong>
        </div>
    <?php } ?>

    <div class="col-lg-3">
        <label>Seleccione un llavero*</label>
        <form action="/portal/llaveMaestra/devoluciondatallavero" method="POST">
            <div class="row"><label class="tnotifi"> Por favor seleccione un llavero.</label></div>
            <div class="select">
                <select name="pk_llavero" id="llavero"  required onchange="this.form.submit();">
                    <option value=""> Seleccione llavero</option>
                    <?php foreach ($llaveros as $key => $value) { ?>
                        <option value="<?= $value['PK_LLAVERO_CODIGO'] ?>" <?php if ($value['PK_LLAVERO_CODIGO'] == $pk_llavero_codigo) echo 'selected'; ?>> <?= ucwords(strtolower($value['NOMBRE_LLAVERO'])) ?></option>
                    <?php } ?>
                </select>
                <div> <?php echo $nombrellaveroselect != "" ? $nombrellaveroselect : 'Seleccione Llavero*' ?></div>
            </div>
        </form>
        <!--<div><label class="lblsaldoabono"><span>$ <?= number_format($saldo_llavero, 0, ',', '.'); ?></span></label></div>-->
    </div>
    <?php if (isset($pk_llavero_codigo)) { ?>
        <div class="tab-content">
            <div id="devolucion" class="tab-pane fade in active"> 
                <form method="POST" action="/portal/llaveMaestra/devolucionsaldollavero" id="formdevsaldo">
                    <input type="text" name="pk_llavero_codigo" id='pk_llavero_codigo' value="<?= $pk_llavero_codigo ?>" hidden>
                    <div class="container col-lg-12" >
                        <div class="grid">
                            <div class='col-md-4' style="background-color:#366199; color: white;">

                                <label required type="text" style="padding-left:10px">
                                    Nombre llavero : </label><br>
                                <label required type="text" style="padding-left:10px">
                                    <?php echo $nombrellaveroselect ?></label><br>

                                <label required type="text" style="padding-left:10px">
                                    Saldo disponible</label><br>
                                <label required type="text" style="padding-left:10px">
                                    $ <?= number_format($saldo_llavero, 0, ',', '.'); ?></label><br>
                                    
                            </div>
                            <div class='col-md-4'>
                                <div class="triangulo" style="margin-left: 45%;margin-top: 5%"></div>
                            </div>
                            <div class='col-md-4' style="background-color:#366199; color: white; height: 150px">

                                <label required type="text" style="padding-left:10px">
                                    Destino: </label><br>
                                <label required type="text" style="padding-left:10px">
                                    Llave maestra:  <?= $empresa ?></label><br>
                                <?php
                                //$rol = $this->session->userdata("rol");
                                $rol = $_SESSION['rol'];
                                if($rol==60 || $rol==61){ ?>    
                                <label required type="text" style="padding-left:10px">
                                    Saldo disponible</label><br>
                                <label required type="text" style="padding-left:10px">
                                    $ <?= number_format($saldo, 0, ',', '.'); ?></label><br>   
                                <?php }?>   
                                    
                            </div>
                            <div class='col-md-12'>
                                <label required type="text" style="padding-left:10px">
                                    Valor a devolver</label>
                                <input required name="monto_devolucion" type="text" placeholder="$0" style="width: 20%;border: 1px solid #757575; border-radius: 50px;padding: 5px 10px 5px 15px;" data-type="currency"><br>
                                <label required type="text" style="padding-left:10px">
                                    Fecha de operacion: <?php echo date('d/m/Y') ?></label><br>
                            </div>


                        </div>
                    </div>

                </form>
                <div class="button col-md-4 col-md-push-4">
                    <button class="spacing" data-toggle="modal" data-target="#ModalConfDev">DEVOLUCIÓN</button>
                    <!-- <button type="button" data-toggle="modal" data-target="#myModalReverso">Prueba modal</button> -->
                </div>
            </div>
        </div>
    <?php } ?>
</div>
<div class="col-lg-2"></div>

<?php if (isset($montovacio)) { ?>
    <!-- Modal error-->
    <div class="modal fade" id="ModalerrorTX" role="dialog" style="margin-top: 15%;"  data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog">

            <!-- Modal content-->
            <div class="modal-content" style="border-radius:35px">

                <div class="modal-body" style="text-align: center;height: 250px;">
                    <div class="modal-header">
                        <h5 style="color: #366199;font-size: 20px;font-weight: bold; ">Error en los datos ingresados</h5>
                    </div>
                    <p  style="font-size:24px;color:#888686;font-weight: bold;padding-top: 25px"><?php echo $montovacio ?>
                    </p>
                    <label id="nomllavero" style="font-size: 18px;color: #366199;font-weight: bold;"></label>

                    <div style="">
                        <div class="button col-sm-6 col-sm-push-3" >
                            <button type="button" name="CANCELAR" class="btn btn-default spacing" data-dismiss="modal">ACEPTAR</button>
                        </div>
                    </div>
                    <br>
                </div>
            </div>
        </div>
    </div>
<?php } ?>


<?php
if (isset($_GET['devolucionOK'])) {
//    $correodest = $this->session->userdata('CORREO_DES_DEVO_LLAVERO');
    $correodest=$_SESSION["CORREO_DES_DEVO_LLAVERO"] ;
    ?>
    <!-- Modal error-->
    <div class="modal fade" id="Modalcodauto" role="dialog" style="margin-top: 15%;"  data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog">

            <!-- Modal content-->
            <div class="modal-content" style="border-radius:35px">
                <button class="btn_cerrar_modal" data-dismiss="modal"></button>
                <div class="modal-body" style="text-align: center;height: 270px;">
                    <form  method="POST" action="/portal/llaveMaestra/verificar_codigo_devolucion" id="formverificarcodigo">
                        <div class="modal-header">
                            <h5 style="color: #366199;font-size: 20px;font-weight: bold; ">Código de confirmación</h5>
                        </div>
                        <p  style="font-size:15px;color:#888686;font-weight: bold;padding-top: 5px">Hemos enviado el código de confirmación a su correo electrónico <?php echo $correodest ?><!-- o como SMS -->
                        </p>
                        <?php if (isset($_GET['error'])) { ?>
                            <label style="color: #FF0000" class="oblique">Código incorrecto </label>
                        <?php } echo '<br>' ?>

                        <input type="text" name="codigoconfirmacion" style="width: 60%" placeholder="Digite código de confirmacón"  required>


                        <div style="">
                            <div class="button col-sm-6 col-sm-push-3" >
                                <button type="submit" name="CONFCARGA" value="1" class="btn btn-default spacing">ACEPTAR</button>
                            </div>
                        </div>
                        <br>
                    </form>
                </div>
            </div>
        </div>
    </div>
<?php } ?>
<?php if (isset($_GET['devosuccessful'])) { ?>
    <!-- Modal confirmacion recarga-->
    <div class="modal fade" id="ModaldevolucionExitoso" role="dialog" style="margin-top: 15%;"  data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog">

            <!-- Modal content-->
            <div class="modal-content" style="border-radius:35px">

                <div class="modal-body" style="text-align: center;height: 200px;">

                    <p  style="font-size:24px;color:#888686;font-weight: bold;padding-top: 25px">Devolución realizada exitosamente
                    </p>
                    <div style="">
                        <div class="button col-sm-6 col-sm-push-3" >
                            <div class="row linkgenerico" style="/*padding-bottom: 100px; padding-left: 100px;*/">
                                <a  href="/portal/llaveMaestra/devolucion" class="spacing">ACEPTAR</a>
                            </div>
                        </div>
                    </div>
                    <br>
                </div>
            </div>
        </div>
    </div>
<?php } ?>
<!-- Modal confirmacion devolucion-->
<div class="modal fade" id="ModalConfDev" role="dialog" style="margin-top: 15%;"  data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content" style="border-radius:35px">

            <div class="modal-body" style="text-align: center;height: 230px;">

                <p  style="font-size:24px;color:#888686;font-weight: bold;padding-top: 25px">¿Esta seguro de realizar esta devolución?
                </p>
                <label id="nomllavero" style="font-size: 18px;color: #366199;font-weight: bold;"></label>

                <div style="">
                    <div class="button col-sm-6" >
                        <button type="button" name="ACEPTAR" value="1" class="btn btn-default"  onclick="
                                        $('#formdevsaldo').submit();" >A C E P T A R</button>
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

<script type="text/javascript">
    var montovacio = <?php
if (isset($montovacio)) {
    echo "1;";
} else {
    echo "0;";
}
?>
    var devok = <?php
if (isset($_GET['devolucionOK'])) {
    echo "1;";
} else {
    echo "0;";
}
?>
    var devsucc = <?php
if (isset($_GET['devosuccessful'])) {
    echo "1;";
} else {
    echo "0;";
}
?>
    var errorpkcodigo = <?php
if (isset($errorpkllavero)) {
    echo "1;";
} else {
    echo "0;";
}
?>
    if (montovacio == 1) {
        $('#ModalerrorTX').modal('show');
    }
    if (devok == 1) {
        $('#Modalcodauto').modal('show');
    }
    if (errorpkcodigo === 1) {
        $(".tnotifi").show();
    }
    if ($('#pk_llavero_codigo').val() != '') {
        $(".tnotifi").hide();
    }
    if (devsucc === 1) {
        $('#ModaldevolucionExitoso').modal('show');
    }

    $("#formdevsaldo").submit(function () {
        $('#loader').modal('show');
    });
    $("#formverificarcodigo").submit(function () {
        $('#loader').modal('show');
    });

</script>