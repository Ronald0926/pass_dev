<!DOCTYPE html>
<html lang="en">

    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="author" content="Tecnolet SAS">
        <link rel="shortcut icon" href="">
        <title>.::Online::.</title>

        <link href="/static/css/ui-lightness/jquery-ui-1.10.3.custom.min.css" rel="stylesheet">
        <link href="/static/css/bootstrap.min.css" rel="stylesheet">
        <link href="/static/css/bootstrap-theme.min.css" rel="stylesheet">
        <link href="/static/css/daos.css" rel="stylesheet">
        <link href="/static/css/portal/ayuda.css" rel="stylesheet">

        <script type="text/javascript" src="/static/js/jquery-2.0.3.min.js"></script>
        <script type="text/javascript" src="/static/js/jquery-ui-1.10.3.custom.min.js"></script>
        <script type="text/javascript" src="/static/js/jquery.dataTables.min.js"></script>
        <script type="text/javascript" src="/static/js/jquery.validate.min.js"></script>
        <script type="text/javascript" src="/static/js/bootstrap.min.js"></script>
        <script type="text/javascript" src="/static/plugins/ckeditor/ckeditor.js"></script>
        <script type="text/javascript" src="/static/js/daos.js"></script>

    </head>

    <body style="padding: 0px; margin: 0px; ">
        <div class="ayuda-contenedor" style="background-image: url(/uploads/ayuda<?= $pantalla ?>.jpg);">
            <div class="ayuda-texto" style="">
                <?php echo ucfirst(strtolower($informacion['DESCRIPCION']));
                ?>
                <br />
                <div class="ayuda-btn">

                    <?php if ($pantalla >= $cantidad['CANTIDAD']) { ?>
                        <a href="/portal/principal/pantalla">
                            E M P E Z A R
                        </a>
                    <?php } else { ?>
                        <a href="/portal/ayuda/control/<?= $pantalla ?>">
                            S I G U I E N T E
                        </a>
                    <?php } ?>
                </div>
            </div>

        </div>
        <script>
            // Creamos un evento para que se ejecute cada vez que se pulse una tecla ESC
            $(document).keyup(function (e) {
                if (e.which == 27) {
                    window.location = "/portal/principal/pantalla";
                }
            });
            $(document).ready(function () {
                <?php if($pantalla==1 ) { ?>
                $(".ayuda-texto").css({"top": "43%", "left": "37%"});
                <?php }if($pantalla==2 ) { ?>
                $(".ayuda-texto").css({"top": "48%","left": "37%"});
                <?php } if($pantalla==3 ) { ?>
                $(".ayuda-texto").css({"top": "48%","left": "48%"});
                <?php } if($pantalla==4 ) { ?>
                $(".ayuda-texto").css({"top": "41%","left": "12%"});
                <?php } if($pantalla==5 ) { ?>
                $(".ayuda-texto").css({"top": "41%","left": "30%"});
                <?php } if($pantalla==6 ) { ?>
                $(".ayuda-texto").css({"top": "42%","left": "51%"});
                 <?php } if($pantalla==7 ) { ?>
                $(".ayuda-texto").css({"top": "43%","left": "14%"});
                 <?php } if($pantalla==8 ) { ?>
                $(".ayuda-texto").css({"top": "42%","left": "31%"});
                 <?php } if($pantalla==9 ) { ?>
                $(".ayuda-texto").css({"top": "43%","left": "36%"});
                 <?php } if($pantalla==10 ) { ?>
                $(".ayuda-texto").css({"top": "43%","left": "36%"});
                 <?php } if($pantalla==11 ) { ?>
                $(".ayuda-texto").css({"top": "45%","left": "34%"});
                 <?php } if($pantalla==12 ) { ?>
                $(".ayuda-texto").css({"top": "45%","left": "34%"});
                <?php  }?>
            });
        </script>
</html>