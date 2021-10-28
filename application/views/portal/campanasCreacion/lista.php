<div class="col-md-1"></div>
<div class="col-md-10">
    <h4 class="titulo-iz" style="padding-left:10%">Campañas</h4>
     
    <?php $success=$_SESSION['success']?> 
  
    <?php if ($success == 1) { ?>
             <div class="alert alert-success col-md-10 col-md-push-1">
                <strong>Campaña <?php $campana ?>creada exitosamente</strong>
            </div>
            <?php $_SESSION['success']=0?>     
        <?php } ?>

    
           
    <div id="myCarousel" class="carousel slide" data-ride="carousel">

        <div class="carousel-inner campana_inner">
            <?php $i = 0;
            // var_dump($campanas);
            $i = Count($campanas);
            for ($k = 0; $k < ceil($i / 3); $k++) {
                if ($k == 0) {
                    echo "<div class='item active' style='padding:15px 250px 50px 200px'>";

                    for ($j = 0; $j < 3; $j++) {


                        //   

                        ?>
                        <?php if ($campanas[$j]['NOMBRE'] != null ||  $campanas[$j]['NOMBRE'] != "") { ?>
                            <div class="col-md-4">
                                <div class="titulo-campa"><?= $campanas[$j]['NOMBRE'] ?>
                                </div>
                                <?php if ($campanas[$j]['NOMBRE'] != null) { ?>
                                    <hr class="campana-hr">
                                <?php } ?>
                                <?php foreach ($lista as $key => $valuelista) { ?>
                                    <?php if ($campanas[$j]['CODIGOCAMPAN'] == $valuelista['CODIGOCAMPANA']) { ?>
                                        <h6 class="nom"><?= $valuelista['NOMBREENT'] . " " . $valuelista['APELLIDO'] ?></h6>
                                        <h6 class="tipo"><?= $valuelista['TIPVIN'] ?></h6>
                                    <?php } ?>
                                <?php }
                            } ?>
                        </div>

                    <?php
                    }
                    array_splice($campanas, 0, 3);

                    echo "</div>";
                } else {
                    echo "<div class='item' style='padding:15px 250px 50px 200px'>";
                    // var_dump($campanas);
                    for ($l = 0; $l < 3; $l++) { ?>
                        <div class="col-md-4">
                            <h6 class="titulo-campa"><?= $campanas[$l]['NOMBRE'] ?>
                            </h6>
                            <?php if ($campanas[$l]['NOMBRE'] != null) { ?>
                                <hr class="campana-hr">
                            <?php } ?>
                            <?php foreach ($lista as $key => $valuelista) { ?>
                                <?php if ($campanas[$l]['CODIGOCAMPAN'] == $valuelista['CODIGOCAMPANA']) { ?>
                                    <h6 class="nom"><?= $valuelista['NOMBREENT'] . " " . $valuelista['APELLIDO'] ?></h6>
                                    <h6 class="tipo"><?= $valuelista['TIPVIN'] ?></h6>
                                <?php } ?>
                            <?php } ?>
                        </div>
                    <?php
                        // var_dump($campanas);
                    }
                    array_splice($campanas, 0, 3);
                    echo "</div>";
                }
            } ?>
        </div>
        <!-- Left and right controls -->
        <a class="left carousel-control carousel_campana" href="#myCarousel" data-slide="prev">
            <span class="glyphicon glyphicon-chevron-left"></span>
            <span class="sr-only"></span>
        </a>
        <a class="right carousel-control carousel_campana" href="#myCarousel" data-slide="next">
            <span class="glyphicon glyphicon-chevron-right"></span>
            <span class="sr-only"></span>
        </a>
    </div>
    <div class="col-md-5"></div>
    <div class="col-md-2" style="margin-bottom: 5%">
        <div class="row linkgenerico" style="/*padding-bottom: 100px; padding-left: 100px;*/">
            <a  href="/portal/campanasCreacion/crear" class="spacing">CREAR CAMPAÑA<span style="margin-left: 12px;margin-right: 0px"class="glyphicon glyphicon-plus"></span></a>
        </div>
    </div>
    
   
</div>
<div class="col-md-1"></div>

<style>
    .carousel_campana {
        background-image: none !important;
    }

    hr.campana-hr {
        border: 1px solid #366199;
        border-radius: 5px;
        width: 100% !important;
        margin-top: 0%;
    }

    .titulo-campa {
        text-align: left;
        font-size: 26px;
        color: #366199;
        font-weight: bold;
        padding-bottom: 0;
    }
    .nom{
        font-size: 17px ;
        color: #7d7d72;
        font-weight: bold; 
        }
    .tipo{
        font-size: 15px ;
        color: #98988c; 
        font-weight: bold; 
        font-style: oblique;
        margin-top: -2%;
        text-transform: lowercase;
        }
    
</style>
