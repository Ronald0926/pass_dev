<style>
    .btncrear{
        background: #FFFF !important;
        color: #366199;
        font-weight: bold;
        border-color:#eee0;
        border: solid 1px;
        border-radius: 30px;
        text-align: center;
        padding: 5px;
        width:25%;
        margin-right: 5px;
        -webkit-transition:all 0.9s ease;
        -moz-transition:all 0.9s ease;
        -o-transition:all 0.9s ease;
        -ms-transition:all 0.9s ease;
    }
    .btn:hover{
        color:#FFFF;
        border-color:#0c385e;
        background:  #0c385e !important;
        -webkit-transform:scale(1.0);
        -moz-transform:scale(1.0);
        -ms-transform:scale(1.0);
        -o-transform:scale(1.0);
        transform:scale(1.0);
    }
    /*Carrousel*/
    .carousel_campana {
        background-image: none !important;
    }

    hr.campana-hr {
        border: 0.5px solid #cacaca;
        border-radius: 5px;
        width: 100% !important;
        margin-top: 0%;
        margin-bottom: 0;
    }

    .titulo-campa {
        text-align: center;
        font-size: 22px;
        color: #FFFF;
        font-weight: bold;
        padding-bottom: 5px;
        padding-top: 0;
    }
    .nom{
        padding-top: 0px;
        font-size: 10px ;
        color: #FFFF;
        /*font-weight: bold;*/ 
    }
    .tipo{
        font-size: 15px ;
        color: #FFFF; 
        font-weight: bold; 
        /*        font-style: oblique;*/
        margin-top: -2%;
        text-transform: uppercase;
    }
    .box{
        width: 30%;
        height: 240px;
        margin-right: 2%;
        border-radius: 10px;
        background: #366199;
        -webkit-box-shadow: 8px 10px 10px 2px rgba(0,0,0,0.5);
        -moz-box-shadow: 8px 10px 10px 2px rgba(0,0,0,0.5);
        box-shadow: 8px 10px 10px 2px rgba(0,0,0,0.5);
    }
    .valor{
        font-size: 18px;
        border-radius: 7px;
        background: #e2e2e2;
        color:#366199;
        text-align: center;
        margin-top: 10px;
        font-weight: bold; 


    }
    .btn-circle {
        width: 30px;
        height: 30px;
        padding: 6px 0px;
        border-radius: 15px;
        text-align: center;
        font-size: 12px;
        line-height: 1.42857;
    }
    .inact{
        background: #979797 !important;
    }

</style>

<div class=" col-lg-1">
    <!--    <div style="margin-top: -5%;">
            <h3 class="subtitulo-iz" style="padding-left:  2%;"> Resumen general de mes actual </h3>
            <h4 style="text-align: left;padding-left:  2%;font-weight: bold;">Total Abonos <span style="font-weight: 100;margin-left: 20%">$ 2.150.000 COP</span></h4>
            <h4 style="text-align: left; padding-left:  2%;font-weight: bold;">Saldo Actual <span style="font-weight: 100;margin-left: 20%">$ 2.150.000 COP</span></h4>
            <h4 style="text-align: left; padding-left:  2%;font-weight: bold;">Total Reversos <span style="font-weight: 100;margin-left: 20%">$ 0 COP</span></h4>
    
    <select style="border-radius:20px">
            <option value="">Filtrar por</option>
            <option value="1">Caja MAestra</option>
            <option value="2">Reverso de Tarjeta</option>
            <option value="3">Número de Tarjeta</option>
        </select>
    
        </div>-->
</div>
<div class="container col-lg-10" style=" margin-bottom: 200px; margin-top: -50px;">
    <hr style="border-top: 1px solid #eee0;">
    <h2 class="titulo-iz">Gestión llaveros</h2>
    <!--    <div class="button btncrear col-sm-3" style="width:18% ;padding: 0px !important;margin-right: 5px;background: #FFFF !important"> 
            <button class="spacing" type="submit" onclick="cargando()"> Crear llavero <span class="glyphicon glyphicon glyphicon-plus"></span></button>
        </div> -->
    <a href="/portal/llaveMaestra/llavero" class="btn btncrear btn-lg col-sm-3 spacing" >
        <span class="glyphicon glyphicon-plus"></span> Crear llavero
    </a>
    <ul class="nav nav-tabs" id="idllaveros">
        <li id="1Modificar"><a data-toggle="tab" href="#llaveMaestra" >Modificar</a></li>
        <li id="2Desactivar"><a data-toggle="tab" a href="#desactivar" >Desactivar</a></li>
        <li id="3Activar"><a data-toggle="tab" a href="#activar" >Activar</a></li>
        <li id="4Eliminar"><a data-toggle="tab" a href="#eliminar" >Eliminar</a></li>
        <li id="5Carga"><a data-toggle="tab" a href="#carga" >Carga</a></li>
    </ul>

    <div class="tab-content">
        <div id="principal" class="tab-pane fade in active">


            <div class="container col-lg-12">
                <div class="grid" >
                    <div><h5 class="subtitulo-iz" style="padding-bottom: 5px">Llaveros activos</h5>
                        <div id="myCarousel" class="carousel slide" data-ride="carousel">
                            <div class="carousel-inner campana_inner">
                                <?php
                                $act = 0;
                                for ($a = 0; $a < count($llaveros); $a++) {
                                    if ($llaveros[$a]['ESTADO'] == '1') {
                                        $act++;
                                    }
                                }
//                                var_dump($act);
                                $i = Count($llaveros);
                                if ($act > 0) {
                                    for ($k = 0; $k < ceil($i / 3); $k++) {
                                        if ($k == 0) {
                                            echo "<div class='item active' style='padding:15px 60px 272px 110px'>";

                                            for ($j = 0; $j < 3; $j++) {
                                                if (($llaveros[$j]['NOMBRE_LLAVERO'] != null || $llaveros[$j]['NOMBRE_LLAVERO'] != "") && $llaveros[$j]['ESTADO'] == '1') {
                                                    ?>
                                                    <!--<div class='item active ' style='padding:15px 60px 272px 110px'>-->

                                                    <div class="col-md-4 box">
                                                        <div class="row">
                                                            <div style="width: 75%;float:left;text-align: center;font-size: 1.2em;
                                                                 color: #FFFF;font-weight: bold;">
                                                                 <?= $llaveros[$j]['NOMBRE_LLAVERO'] ?>

                                                            </div>
                                                            <div style="width: 20%;float:right;"  class="icono" id="<?= $llaveros[$j]['PK_LLAVERO_CODIGO'] ?>"></div>
                                                        </div>

                                                        <hr class="campana-hr">
                                                        <h6 class="nom">Coordinador responsable</h6>
                                                        <h6 class="tipo"><?= $llaveros[$j]['NOMBRE_COOR_RES'] ?></h6>
                                                        <hr class="campana-hr">
                                                        <h6 class="nom">Administrador de pagos</h6>
                                                        <h6 class="tipo"><?= $llaveros[$j]['NOMBRE_ADM_PAGO'] ?></h6>
                                                        <hr class="campana-hr">
                                                        <div class="valor">$<?= $llaveros[$j]['SALDO'] ?></div>
                                                        <input name="idllavero" value="<?= $llaveros[$j]['PK_LLAVERO_CODIGO'] ?>" hidden>
                                                    </div>
                                                    <?php
                                                }
                                            }
                                            array_splice($llaveros, 0, 3);

                                            echo "</div>";
                                        } else {
                                            echo "<div class='item' style='padding:15px 60px 272px 110px'>";
                                            // var_dump($campanas);
                                            for ($l = 0; $l < 3; $l++) {
                                                if (($llaveros[$l]['NOMBRE_LLAVERO'] != null) && $llaveros[$l]['ESTADO'] == '1') {
                                                    ?>
                                                    <div class="col-md-4 box">
                                                        <div class="row">
                                                            <div style="width: 75%;float:left;text-align: center;font-size: 1.2em;
                                                                 color: #FFFF;font-weight: bold;">
                                                                 <?= $llaveros[$l]['NOMBRE_LLAVERO'] ?>
                                                            </div>
                                                            <div style="width: 20%;float:right;"  class="icono" id="<?= $llaveros[$l]['PK_LLAVERO_CODIGO'] ?>"></div>
                                                        </div>
                                                        <hr class="campana-hr">
                                                        <h6 class="nom">Coordinador responsable</h6>
                                                        <h6 class="tipo"><?= $llaveros[$l]['NOMBRE_COOR_RES'] ?></h6>
                                                        <hr class="campana-hr">
                                                        <h6 class="nom">Administrador de pagos</h6>
                                                        <h6 class="tipo"><?= $llaveros[$l]['NOMBRE_ADM_PAGO'] ?></h6>
                                                        <hr class="campana-hr">
                                                        <div class="valor">$<?= $llaveros[$l]['SALDO'] ?></div>
                                                        <input name="idllavero" value="<?= $llaveros[$l]['PK_LLAVERO_CODIGO'] ?>" hidden>
                                                    </div>
                                                    <?php
                                                }
                                                // var_dump($campanas);
                                            }
                                            array_splice($llaveros, 0, 3);
                                            echo "</div>";
                                        }
                                    }
                                } else {
                                    ?>
                                    <div class="oblique" style='font-size: 24px; color:#969696;'>
                                        NO tienes llaveros activos
                                    </div><?php } ?>
                            </div>


                        </div>
                        <?php if ($act > 0) { ?>
                            <!-- Left and right controls -->
                            <a class="left carousel-control carousel_campana" href="#myCarousel" data-slide="prev" style="width: 10% !important">
                                <span class="glyphicon glyphicon-chevron-left"></span>
                                <span class="sr-only"></span>
                            </a>
                            <a class="right carousel-control carousel_campana" href="#myCarousel" data-slide="next" style="width: 10% !important">
                                <span class="glyphicon glyphicon-chevron-right"></span>
                                <span class="sr-only"></span>
                            </a>
                        <?php } ?>
                    </div>
                </div>

            </div>  
            <div class="container col-lg-12">
                <div class="grid" >
                    <div><h5 class="subtitulo-iz" style="padding-bottom: 5px">Llaveros inactivos</h5>
                        <div id="myCarouselInac" class="carousel slide" data-ride="carousel">
                            <div class="carousel-inner campana_inner">
                                <?php
                                $inact = 0;
                                for ($i = 0; $i < count($llaverosInac); $i++) {
                                    if ($llaverosInac[$i]['ESTADO'] == '2') {
                                        $inact++;
                                    }
                                }
//                                var_dump($inact);
                                $i = Count($llaverosInac);
                                if ($inact > 0) {
                                    for ($k = 0; $k < ceil($i / 3); $k++) {
                                        if ($k == 0) {
                                            echo "<div class='item active' style='padding:15px 60px 272px 110px'>";

                                            for ($j = 0; $j < 3; $j++) {
                                                if (($llaverosInac[$j]['NOMBRE_LLAVERO'] != null || $llaverosInac[$j]['NOMBRE_LLAVERO'] != "") && $llaverosInac[$j]['ESTADO'] == '2') {
                                                    ?>
                                                    <!--<div class='item active ' style='padding:15px 60px 272px 110px'>-->

                                                    <div class="col-md-4 box inact">
                                                        <div class="row">
                                                            <div style="width: 75%;float:left;text-align: center;font-size: 1.2em;
                                                                 color: #FFFF;font-weight: bold;">
                                                                 <?= $llaverosInac[$j]['NOMBRE_LLAVERO'] ?>

                                                            </div>
                                                            <div style="width: 20%;float:right;"  class="iconoAct" id="<?= $llaverosInac[$j]['PK_LLAVERO_CODIGO'] ?>"></div>
                                                        </div>

                                                        <hr class="campana-hr">
                                                        <h6 class="nom">Coordinador responsable</h6>
                                                        <h6 class="tipo"><?= $llaverosInac[$j]['NOMBRE_COOR_RES'] ?></h6>
                                                        <hr class="campana-hr">
                                                        <h6 class="nom">Administrador de pagos</h6>
                                                        <h6 class="tipo"><?= $llaverosInac[$j]['NOMBRE_ADM_PAGO'] ?></h6>
                                                        <hr class="campana-hr">
                                                        <div class="valor">$<?= $llaverosInac[$j]['SALDO'] ?></div>
                                                        <input name="idllavero" value="<?= $llaverosInac[$j]['PK_LLAVERO_CODIGO'] ?>" hidden>
                                                    </div>
                                                    <?php
                                                }
                                            }
                                            array_splice($llaverosInac, 0, 3);

                                            echo "</div>";
                                        } else {
                                            echo "<div class='item' style='padding:15px 60px 272px 110px'>";
                                            // var_dump($campanas);
                                            for ($l = 0; $l < 3; $l++) {
                                                if ($llaverosInac[$l]['NOMBRE_LLAVERO'] && $llaverosInac[$l]['ESTADO'] == '2') {
                                                    ?>
                                                    <div class="col-md-4 box inact">
                                                        <div class="row">
                                                            <div style="width: 75%;float:left;text-align: center;font-size: 1.2em;
                                                                 color: #FFFF;font-weight: bold;">
                                                                 <?= $llaverosInac[$l]['NOMBRE_LLAVERO'] ?>
                                                            </div>
                                                            <div style="width: 20%;float:right;"  class="iconoAct" id="<?= $llaverosInac[$l]['PK_LLAVERO_CODIGO'] ?>"></div>
                                                        </div>
                                                        <hr class="campana-hr">
                                                        <h6 class="nom">Coordinador responsable</h6>
                                                        <h6 class="tipo"><?= $llaverosInac[$l]['NOMBRE_COOR_RES'] ?></h6>
                                                        <hr class="campana-hr">
                                                        <h6 class="nom">Administrador de pagos</h6>
                                                        <h6 class="tipo"><?= $llaverosInac[$l]['NOMBRE_ADM_PAGO'] ?></h6>
                                                        <hr class="campana-hr">
                                                        <div class="valor">$<?= $llaverosInac[$l]['SALDO'] ?></div>
                                                        <input name="idllavero" value="<?= $llaverosInac[$l]['PK_LLAVERO_CODIGO'] ?>" hidden>
                                                    </div>
                                                    <?php
                                                }
                                                // var_dump($campanas);
                                            }
                                            array_splice($llaverosInac, 0, 3);
                                            echo "</div>";
                                        }
                                    }
                                } else {
                                    ?>
                                    <div class="oblique" style='font-size: 24px; color:#969696;'>
                                        NO tienes llaveros activos
                                    </div><?php } ?>
                            </div>


                        </div>
                        <?php if ($inact > 0) { ?>
                            <!-- Left and right controls -->
                            <a class="left carousel-control carousel_campana" href="#myCarouselInac" data-slide="prev" style="width: 10% !important">
                                <span class="glyphicon glyphicon-chevron-left"></span>
                                <span class="sr-only"></span>
                            </a>
                            <a class="right carousel-control carousel_campana" href="#myCarouselInac" data-slide="next" style="width: 10% !important">
                                <span class="glyphicon glyphicon-chevron-right"></span>
                                <span class="sr-only"></span>
                            </a>
                        <?php } ?>
                    </div>
                </div>
            </div>
        </div> 
    </div>


    <div id="modals">

    </div>


</div>
</div>
<div class="col-lg-1" ></div>

<!-- Modal confirmacion-->
<div class="modal fade" id="ModalConf" role="dialog" style="margin-top: 15%;"  data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content" style="border-radius:35px">

            <div class="modal-body" style="text-align: center;height: 230px;">

                <p  style="font-size:24px;color:#888686;font-weight: bold;padding-top: 25px">El llavero fue <?php echo $_GET['acc'] ?> exitosamente
                </p>
                <label id="nomllavero" style="font-size: 18px;color: #366199;font-weight: bold;"></label>

                <div style="">
                    <div class="button col-sm-6 col-sm-push-3" >
                        <button name="aceptar" data-dismiss="modal" class="btn btn-default spacing">ACEPTAR</button>
                    </div>
                </div>
                <br>
            </div>
        </div>
    </div>
</div>

<?php
if (isset($_GET['recargaok'])) {
    //$correodest = $this->session->userdata('CORREO_DES_CLLAVERO');
    $correodest = $_SESSION['CORREO_DES_CLLAVERO'];
    ?>
    <!-- Modal error-->
    <div class="modal fade" id="Modalcodauto" role="dialog" style="margin-top: 15%;"  data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog">

            <!-- Modal content-->
            <div class="modal-content" style="border-radius:35px">

                <div class="modal-body" style="text-align: center;height: 270px;">
                    <form  method="POST" action="/portal/llaveMaestra/verificar_codigo">
                        <div class="modal-header">
                            <h5 style="color: #366199;font-size: 20px;font-weight: bold; ">Código de confirmacón</h5>
                        </div>
                        <p  style="font-size:15px;color:#888686;font-weight: bold;padding-top: 5px">Hemos enviado el código de confirmación a su correo electrónico <?php echo $correodest ?> o como SMS
                        </p>
                        <?php if (isset($_GET['error'])) { ?>
                            <label style="color: #FF0000" class="oblique">Código incorrecto </label>
    <?php } echo '<br>' ?>

                        <input type="text" name="codigoconfirmacion" style="width: 60%" placeholder="Digite código de confirmacón"  required>


                        <div style="">
                            <div class="button col-sm-6 col-sm-push-3" >
                                <button type="submit" name="CONFCARGA" value="1" class="btn btn-default spacing">CARGAR</button>
                            </div>
                        </div>
                        <br>
                    </form>
                </div>
            </div>
        </div>
    </div>
<?php } ?>
<?php
if (isset($_GET['rsuccessful'])) {?>
<!-- Modal confirmacion recarga-->
<div class="modal fade" id="ModalRecargaExitosa" role="dialog" style="margin-top: 15%;"  data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content" style="border-radius:35px">

            <div class="modal-body" style="text-align: center;height: 200px;">

                <p  style="font-size:24px;color:#888686;font-weight: bold;padding-top: 25px">La carga fue realizada exitosamente
                </p>
                <div style="">
                    <div class="button col-sm-6 col-sm-push-3" >
                        <button name="aceptar" data-dismiss="modal" class="btn btn-default spacing">ACEPTAR</button>
                    </div>
                </div>
                <br>
            </div>
        </div>
    </div>
</div>
<?php } ?>
<script type="text/javascript">
    var recargaok = <?php
if (isset($_GET['recargaok'])) {
    echo "1;";
} else {
    echo "0;";
}
?>
    var x = <?php
if (isset($_GET['ok'])) {
    echo "1;";
} else {
    echo "0;";
}
?>
    var recargaexitosa = <?php if (isset($_GET['rsuccessful'])) {   echo "1;";} else {    echo "0;";}
?>
    if (recargaok == 1) {
        $('#Modalcodauto').modal('show');
    }
    if (recargaexitosa == 1) {
        $('#ModalRecargaExitosa').modal('show');
    }
    if (x == 1) {
        $('#ModalConf').modal('show');
    }
    $("#idllaveros li").click(function () {
        if (this.id == '1Modificar') {
            $('.desactivar').remove();
            $('.activar').remove();
            $('.eliminar').remove();
            $('.carga').remove();
            //modificar
            var clas = document.getElementsByClassName('icono');/*$('.icono');*/
            for (i = 0; i < clas.length; i++) {
                var mod = 'modificar';
                var id = $(clas[i]).attr("id");
//                console.log($('#Mod' + id + ''));
                if ($('#Mod' + id + '').length == 0) {
                    $('#' + id + '').append('<a href="/portal/llaveMaestra/llavero/' + id + '/' + mod + '" class="btn btn-default btn-circle modificar" style="margin-bottom: 8px;" id="Mod' + id + '"> <i class="fa fa-pencil"></i></a>');

                }
            }
        } else if (this.id == '2Desactivar') {
            //DESACTIVAR
            $('.modificar').remove();
            $('.activar').remove();
            $('.eliminar').remove();
            $('.carga').remove();
            var clas = document.getElementsByClassName('icono');
            for (i = 0; i < clas.length; i++) {
                var des = 'desactivar';
                var id = $(clas[i]).attr("id");
                if ($('#Des' + id + '').length == 0) {
                    $('#' + id + '').append('<button  data-toggle="modal" data-target="#Modaldesactivar' + id + '" class="btn btn-default btn-circle desactivar spacing" style="margin-bottom: 8px;z-index:1000;"><i class="fa fa-close"></i>');
                    $('#modals').append('<br> <div class="modal fade" id="Modaldesactivar' + id + '" role="dialog" style="margin-top: 15%;"  data-backdrop="static" data-keyboard="false"><div class="modal-dialog"> <div class="modal-content" style="border-radius:35px"><div class="modal-body" style="text-align: center;height: 230px;"> <p  style="font-size:24px;color:#888686;font-weight: bold;padding-top: 25px">¿Está seguro que quiere desactivar este llavero?</p> <label id="nomllavero" style="font-size: 18px;color: #366199;font-weight: bold;"></label> <div style=""><form method="POST"> <div class="button col-sm-6" ><button name="aceptar" type="submit"  formaction="/portal/llaveMaestra/accionGestionllavero/' + id + '/' + des + '" class="btn btn-default">S I</button>  </div>  </form>  <div class="button col-sm-6" >   <button type="button" name="CANCELAR" class="btn btn-default" data-dismiss="modal">N O</button></div> </div><br></div> </div> </div></div>');
//                    
//                    $('#' + id + '').append('<a href="/portal/llaveMaestra/llavero/' + id + '/' + des + '" class="btn btn-default btn-circle desactivar" style="margin-bottom: 8px;" id="Des' + id + '"> <i class="fa fa-close"></i></a>');
                }
            }
        } else if (this.id == '3Activar') {
            //ACTIVAR
            $('.desactivar').remove();
            $('.modificar').remove();
            $('.eliminar').remove();
            $('.carga').remove();
            var clas = document.getElementsByClassName('iconoAct');
            for (i = 0; i < clas.length; i++) {
                var act = 'activar';
                var id = $(clas[i]).attr("id");
                if ($('#Act' + id + '').length == 0) {
                    $('#' + id + '').append('<button  data-toggle="modal" data-target="#Modalactivar' + id + '" class="btn btn-default btn-circle activar spacing" style="margin-bottom: 8px;z-index:1000;"><i class="fa fa-power-off"></i>');
                    $('#modals').append('<br> <div class="modal fade" id="Modalactivar' + id + '" role="dialog" style="margin-top: 15%;"  data-backdrop="static" data-keyboard="false"><div class="modal-dialog"> <div class="modal-content" style="border-radius:35px"><div class="modal-body" style="text-align: center;height: 230px;"> <p  style="font-size:24px;color:#888686;font-weight: bold;padding-top: 25px">¿Desea activar este llavero?</p> <label id="nomllavero" style="font-size: 18px;color: #366199;font-weight: bold;"></label> <div style=""><form method="POST"> <div class="button col-sm-6" ><button name="aceptar" type="submit"  formaction="/portal/llaveMaestra/accionGestionllavero/' + id + '/' + act + '" class="btn btn-default">S I</button>  </div>  </form>  <div class="button col-sm-6" >   <button type="button" name="CANCELAR" class="btn btn-default" data-dismiss="modal">N O</button></div> </div><br></div> </div> </div></div>');
//                    $('#' + id + '').append('<a href="/portal/llaveMaestra/llavero/' + id + '/' + act + '" class="btn btn-default btn-circle activar" style="margin-bottom: 8px;" id="Act' + id + '"> <i class="fa fa-power-off"></i></a>');
                }
            }
        } else if (this.id == '4Eliminar') {
            //ELIMINAR
            $('.desactivar').remove();
            $('.modificar').remove();
            $('.carga').remove();
            $('.activar').remove();
            var clas = document.getElementsByClassName('icono');
            for (i = 0; i < clas.length; i++) {
                var eli = 'eliminar';
                var id = $(clas[i]).attr("id");
                if ($('#Eli' + id + '').length == 0) {
                    $('#' + id + '').append('<button  data-toggle="modal" data-target="#Modaleliminar' + id + '" class="btn btn-default btn-circle eliminar spacing" style="margin-bottom: 8px;z-index:1000;"><i class="fa fa-trash-o"></i>');
                    $('#modals').append('<br> <div class="modal fade" id="Modaleliminar' + id + '" role="dialog" style="margin-top: 15%;"  data-backdrop="static" data-keyboard="false"><div class="modal-dialog"> <div class="modal-content" style="border-radius:35px"><div class="modal-body" style="text-align: center;height: 230px;"> <p  style="font-size:24px;color:#888686;font-weight: bold;padding-top: 25px">¿Desea eliminar este llavero?</p> <label id="nomllavero" style="font-size: 18px;color: #366199;font-weight: bold;"></label> <div style=""><form method="POST"> <div class="button col-sm-6" ><button name="aceptar" type="submit"  formaction="/portal/llaveMaestra/accionGestionllavero/' + id + '/' + eli + '" class="btn btn-default">S I</button>  </div>  </form>  <div class="button col-sm-6" >   <button type="button" name="CANCELAR" class="btn btn-default" data-dismiss="modal">N O</button></div> </div><br></div> </div> </div></div>');
//                    $('#' + id + '').append('<a href="/portal/llaveMaestra/llavero/' + id + '/' + eli + '" class="btn btn-default btn-circle eliminar" style="margin-bottom: 8px;" id="Eli' + id + '"> <i class="fa fa-trash-o"></i></a>');
                }
            }
        } else if (this.id == '5Carga') {
            //CARGA
            $('.desactivar').remove();
            $('.modificar').remove();
            $('.activar').remove();
            $('.eliminar').remove();
            var clas = document.getElementsByClassName('icono');
            for (i = 0; i < clas.length; i++) {
                var carg = 'cargar';
                var id = $(clas[i]).attr("id");
                if ($('#Carga' + id + '').length == 0) {
//                    $('#' + id + '').append('<a href="/portal/llaveMaestra/llavero/' + id + '/' + carg + '" class="btn btn-default btn-circle carga" style="margin-bottom: 8px;" id="Carga' + id + '"> <i class="fa fa-battery-1"></i></a>');
                    $('#' + id + '').append('<a href="/portal/llaveMaestra/recargallavero/' + id + '/' + carg + '" class="btn btn-default btn-circle carga" style="margin-bottom: 8px;" id="Carga' + id + '"> <i class="fa fa-battery-1"></i></a>');

                }
            }
        }

//    alert($(this).attr('id')); // jQuery's .attr() method, same but more verbose
//    alert($(this).html()); // gets innerHTML of clicked li
//    alert($(this).text()); // gets text contents of clicked li
    });

//    $(".modificar").on("click", function (e) {
//        e.preventDefault();
//        $('#formgestionllaveros').attr('action', "/portal/llaveMaestra/llavero").submit();
//    });
//
//    var boton = document.getElementsByClassName("modificar");
//    boton.onclick = function (e) {
//        debugger;
//        var validado = $("#formllavero").valid();
//        if (validado) {
//            $('#Modalconf').modal('show');
//        }
//    };

</script>