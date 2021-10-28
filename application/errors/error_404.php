<!DOCTYPE html>
<html lang="en">
    <head>
        <title>404 Page Not Found</title>
        <style type="text/css">

            ::selection{ background-color: #E13300; color: white; }
            ::moz-selection{ background-color: #E13300; color: white; }
            ::webkit-selection{ background-color: #E13300; color: white; }

            body {
                background-color: #fff;
                margin: 40px;
                font: 13px/20px normal Helvetica, Arial, sans-serif;
                color: #4F5155;
            }

            a {
                color: #003399;
                background-color: transparent;
                font-weight: normal;
            }

            h1 {
                color: #444;
                background-color: transparent;
                border-bottom: 1px solid #D0D0D0;
                font-size: 19px;
                font-weight: normal;
                margin: 0 0 14px 0;
                padding: 14px 15px 10px 15px;
            }

            code {
                font-family: Consolas, Monaco, Courier New, Courier, monospace;
                font-size: 12px;
                background-color: #f9f9f9;
                border: 1px solid #D0D0D0;
                color: #002166;
                display: block;
                margin: 14px 0 14px 0;
                padding: 12px 10px 12px 10px;
            }

            #container {
                margin: 10px;
                border: 1px solid #D0D0D0;
                -webkit-box-shadow: 0 0 8px #D0D0D0;
            }

            p {
                margin: 12px 15px 12px 15px;
            }
        </style>
    </head>
    <body>
        <div id="container">
            <div class="col-md-2 col-lg-2 col-sm-4 col-xs-4">
            </div>
            <div class="col-md-8">
                <img class="logo-people"style="margin-bottom: 5%;margin-left: auto;margin-right: auto" src="/static/img/portal/logopeoplepass.png" />
            </div>
            <div class="col-md-2 col-lg-2 col-sm-4 col-xs-4">
            </div>
            <div class="col-md-8 col-md-push-2">
                <div class="p-3 mb-2 bg-primary text-white"><h2>Error inesperado</h2> Por favor intente más tarde.</div>
                <!--<h1><?php echo $heading; ?></h1>
                <?php echo $message; $this->load->helper('log4php'); ?>-->
                <?php log_info('APOLO_ERROR::::::::: ERROR 404 HEADING:' . $heading); ?>
                <?php log_info('APOLO_ERROR::::::::: ERROR 404 MESSAGE:' . $message); ?>
            </div>
        </div>
    </body>
</html>