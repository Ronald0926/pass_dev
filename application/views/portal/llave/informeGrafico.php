<?php
//$rol = $this->session->userdata("rol");
$rol = $_SESSION['rol'];
?>
<div class="col-lg-2" ></div>
<div class="container col-lg-8" style=" margin-bottom: 200px; margin-top: -50px;">
    <hr style="border-top: 1px solid #eee0;">
    <h2 class="titulo-iz">Estado de cuenta</h2>
    <ul class="nav nav-tabs">
        <?php if(($rol==60) or ($rol==61)){?>
        <li><a href="/portal/llaveMaestra/estado"><?php echo (($rol == 61))  ? 'Llave Maestra' : 'Llavero' ?></a></li>
        <?php } ?>
        <?php if (($rol==60) or ($rol==61)) { ?>
        <li><a href="/portal/llaveMaestra/estadoTarjetas">Tarjetas</a></li>
        <?php } ?>
        <?php if (($rol==60) or ($rol==61)) { ?>
        <li><a href="/portal/llaveMaestra/informeAbonos">Dispersiones</a></li>
         <?php } ?>
        <?php if (($rol==60) or ($rol==61)) { ?>
        <li class="active"><a data-toggle="tab" href="#grafico">Informes Graficos Transaccional</a></li>
        <?php } ?>
        <?php if (($rol==60) or ($rol==59) or ($rol==61)) { ?>
            <li><a href="/portal/llaveMaestra/consultaNotasContables">Nota Contable Prepago</a></li>
        <?php } ?>
        <?php if (($rol==60) or ($rol==61)) { ?>
            <li><a href="/portal/llaveMaestra/consultaFacturas">Facturas</a></li>
        <?php } ?>
    </ul>
    <div class="tab-content">
        <div id="grafico" class="tab-pane fade in active">
            <form method="POST">
                <div class="container col-lg-12">
                        <!--<img src="/static/img/portal/llave/grafico.png" />  -->
                    <div id="donutchart" style="margin-left: 10%;width: 90%; height: 500px;"></div>

                </div> 
            </form>
        </div>
        <!--        <div class="row">
                    <div class="button col-sm-2">
                        <input type="hidden" value="" id="formFact">
                        <button id="createFactura" onclick="createPdf()">
                            Descargar PDF
                        </button>
                    </div>
                </div>-->
    </div>
</div>
<div class="col-lg-2" ></div>
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<script type="text/javascript">
    var carga =<?php echo $canttotalabonos ?>;
    var reverso =<?php echo $cantreversos ?>;
    var gasRepre =<?php echo $cantabono_gastosrepre ?>;
    var cajMenor =<?php echo $cantabono_cajamenor ?>;
    var bussCar =<?php echo $cantabono_bussinescar ?>;
    var medTrans =<?php echo $cantabono_mediostrans ?>;
    var mgasViaje =<?php echo $cantabono_gastosviaje ?>;
    google.charts.load("current", {packages: ["corechart"]});
    google.charts.setOnLoadCallback(drawChart);
    function drawChart() {
        var data = google.visualization.arrayToDataTable([
            ['Task', 'Grafico general'],
            ['Carga', carga],
            ['Reverso', reverso],
            ['Abono gastos de representación', gasRepre],
            ['Abono Caja Menor', cajMenor],
            ['Abono Business car', bussCar],
            ['Abono medios de transporte', medTrans],
            ['Abono gastos de viaje', mgasViaje]
        ]);
        var options = {
//                    title: 'Informe transaccional',
            pieHole: 0.5,
            titleTextStyle: {
                position: 'center',
                color: '#366199',
                fontSize: 35,
                fontName: 'Montserrat'
            },
            legend: {
                position: 'right',
                textStyle: {color: '#7d7d72', fontSize: 20, fontName: 'Montserrat'},
                alignment: 'center'
            },
            tooltip: {textStyle: {color: '#366199'},
                text: 'percentage'},
            colors: ['#366199', '#e9263d', '#ec8f6e', '#9e566f', '#f6c7b6', '#54b7e2', '#f6c7b6'],
        };
        var chart = new google.visualization.PieChart(document.getElementById('donutchart'));
        chart.draw(data, options);
    }
</script>