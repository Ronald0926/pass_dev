<div class='row'>
    <div class='col-lg-4 col-lg-offset-1'>
        <h1>Ver Viaje</h1>
        <?php foreach ($viaje as $key => $value) { ?>
            
            <label type="text" style="padding-left:10px">
                Portador</label><br>
            <label type="text" style="padding-left:10px">
                <?=  $value['PORTAD']?> </label><br>
            <label type="text" style="padding-left:10px">
                Placa</label><br>
            <label type="text" style="padding-left:10px">
                <?=  $value['PLA']?></label><br>
            <label type="text" style="padding-left:10px">
                Ruta</label><br>
            <label type="text" style="padding-left:10px">
                <?=  $value['RUT']?></label><br>
            <label type="text" style="padding-left:10px">
                No.Tarjeta</label><br>
            <label type="text" style="padding-left:10px">
                <?=  $value['NUMTAR']?></label><br>
            <label type="text" style="padding-left:10px">
                Asignaciòn</label><br>
            <label type="text" style="padding-left:10px">
                <?=  $value['ASIGNA']?></label><br>
            <label type="text" style="padding-left:10px">
                Fecha inicio</label><br>
            <label type="text" style="padding-left:10px">
                 <?=  $value['INILAB']?></label><br>
        <?php }?>
                 <a type="button" class="btn btn-default" href="/portal/bono/gestionViaje">VOLVER</a>
    </div>
</div>