<style>
    .productos{
        width: 100%;
        height: 350px;
        background-color: #d2d2d2;
        border-radius:25px;
    }
    .online-check{
        width:15px;
    }
    ::placeholder {
        color: #1b5e98;
        opacity: 1; /* Firefox */
    }

</style>
<div style="padding-left:  10%;margin-top: 5%">
    <div class="col-lg-4">

        <h1 style="color: #1C5394; padding-left:  1%;">Cotización</h1>
        <?php if ($error == 2) { ?>

            <div class="alert alert-danger">
                <strong>No se ha seleccionado ningun Producto!</strong>
            </div>
            if($succes == 1){
             <div class="alert alert-success">
                    <strong>Cotización creada con exito!</strong>
            </div>
            }
             if($succes == 0){
             <div class="alert alert-info">
                    <strong>Error al procesar la información!</strong>
            </div>
            }
        <?php } ?>
       
        <form method="POST" action="/portal/cotizacion/cotizar2" id="formCotizacion">
            <div class="select ">
                <select  name="lineaProductos[]" id="selectLineaProductos" class="required" required>
<!--                    <select  name="lineaProductos" id="selectLineaProductos" class="required" onchange="
                       $('.productosLinea').hide(0);
                       $('.linea' + $(this).val()).show(150);
                        " required>-->
                    <option value="">Seleccione Línea de productos</option>
                    <?php foreach ($lineaproductos as $key => $value) { ?>
                        <option value="<?= $value['CODIGO'] ?>" > Línea <?= $value['NOMBRE'] ?></option>
                    <?php } ?>
                </select>
                <div>Seleccione Línea de productos</div>
            </div>
            <br><br>

            <?php foreach ($productos as $key => $valueproducto) { ?>
                <table class="productosLinea linea<?= $valueproducto['CODIGOL'] ?>" style="display:none">
                    <tr>
                        <td style="width:  10%;">

                            <div class="login-checkbox" style="<?php
                            if ($valueproducto['ACTIVO'] == 1) {
                                echo 'pointer-events:none;';
                            }
                            ?>" data-codigo="<?= $valueproducto['CODIGO'] ?>">
                                <input type="checkbox" class="valCheck" name="check[]" value="<?= $valueproducto['CODIGO'] ?>"   <?= $valueproducto['ACTIVO'] == 1 ? 'checked=true' : '' ?>/> 
                                <div class="">
                                    <span class="login-checkbox-check" style=" <?= $valueproducto['ACTIVO'] == 1 ? 'display:inline' : '' ?>">
                                    </span>
                                </div>
                            </div> 
                        </td>
                        <td style="<?php
                        if ($valueproducto['ACTIVO'] == 1) {
                            echo 'text-decoration:line-through;text-decoration-color: mediumvioletred;';
                        }
                        ?>"><?= $valueproducto['NOMBRE_PRODUCTO'] ?> </td>
                        <td style="width: 35%;"><input class="block" type="number" name="value[]"  id="<?= $valueproducto['CODIGO'] ?>" value="<?php echo $valueproducto['CANTIDAD'] != "0" ? $valueproducto['CANTIDAD'] : '' ?>" placeholder="Cantidad de tarjetas" 
                            <?php /*
                              if ($valueproducto['ACTIVO'] == 1) {
                              echo 'readonly ';
                              } */
                            ?> readonly="readonly" ></td>
                    </tr>
                </table>
            
                
                
            <?php } ?>
            <br>
            <label style="color: #1C5394">Facturación actual: $<span id="abono_mensual" name="abono_mens"></span></label><br> 
            <input name="total_abonos" id="total_abonos" class="textPat" hidden>
            <input name="total_tarjetas" id="total_tarjetas" class="textPat" hidden>
            <label style="color: #1C5394">Facturación:</label>   <input type="number" class="block" name="facturacion" placeholder="" style="width: 50%;border-bottom: 1px solid #1C5394;" onchange="sumar(this.value);" min="1" pattern="^[0-9]+" required>
            <br> 
            <br>
            <div class="button">
                <button type="submit" onclick="sendNewTar();">C O N S U L T A R </button>
            </div>
        </form>
    </div>
    <div class="col-lg-6">
        <div class="">
            <h4 class="titulo-mini-left">Conoce nuestros productos</h4>  
            <div id="myCarousel" class="carousel slide" data-ride="carousel">
                <!-- Indicators 
                <ol class="carousel-indicators">
                    <li data-target="#myCarousel" data-slide-to="0" class="active"></li>
                    <li data-target="#myCarousel" data-slide-to="1"></li>
                    <li data-target="#myCarousel" data-slide-to="2"></li>
                </ol>-->

                <!-- Wrapper for slides -->
                <div class="carousel-inner" style="">
                    <div class="item active">
                        <div class='productos' style="padding:50px 70px 50px 70px">
                            <label class="lbl_linea">Linea beneflex</label>
                            <p class="productos_p">Nuestra línea de productos Beneflex está especialmente diseñada
                                para asistirle y brindarle una efectiva y eficiente solución para cada
                                una de las necesidades de la empresa, permitiendo enriquecer sus
                                ofertas laborales con planes de beneficios para sus colaboradores.</p>
                            <img style="padding-top:20px" src="/static/img/portal/cotizacion/pasarela_Beneflex.png"/>
                        </div>
                    </div>
                    
                    <div class="item ">
                        <div class='productos' style="padding:50px 70px 50px 70px">
                            <label class="lbl_linea">Linea beneflex</label>
                            <p class="productos_p">Nuestra línea de productos Beneflex está especialmente diseñada
                                para asistirle y brindarle una efectiva y eficiente solución para cada
                                una de las necesidades de la empresa, permitiendo enriquecer sus
                                ofertas laborales con planes de beneficios para sus colaboradores.</p>
                            <img style="padding-top:20px" src="/static/img/portal/cotizacion/pasarela_Beneflex-02.png"/>
                        </div>
                    </div>

                    <div class="item">
                        <div class='productos' style="padding:50px 70px 50px 70px">
                            <label class="lbl_linea">Linea business</label>
                            <p class="productos_p">Nuestra línea de productos Business apoya las necesidades
                                administrativas de la organización y la gestión operativa de
                                herramientas de trabajo; facilita la asignación y distribución de
                                recursos financieros con una correcta aplicación contable.</p>
                            <img style="padding-top:20px" src="/static/img/portal/cotizacion/pasarela_Business-04.png"/>
                        </div>
                    </div>
                    
                     <div class="item">
                        <div class='productos' style="padding:50px 70px 50px 70px">
                            <label class="lbl_linea">Linea business</label>
                            <p class="productos_p">Nuestra línea de productos Business apoya las necesidades
                                administrativas de la organización y la gestión operativa de
                                herramientas de trabajo; facilita la asignación y distribución de
                                recursos financieros con una correcta aplicación contable.</p>
                            <img style="padding-top:20px" src="/static/img/portal/cotizacion/pasarela_Business-05.png"/>
                        </div>
                    </div>

                    <div class="item">
                        <div class='productos' style="padding:50px 70px 50px 70px">

                            <label class="lbl_linea">Linea Pasarela</label>
                            <p class="productos_p">Nuestra línea de productos Pasarela ofrece soluciones efectivas para
                                que las empresas aumenten la motivación de su equipo de trabajo.
                                Facilita la logística y entrega de recompensas al legítimo beneficiario.</p>
                            <img style="padding-top:20px;align-items: center" src="/static/img/portal/cotizacion/pasarela.png"/>
                        </div>
                    </div>
                    <div class="item">
                        <div class='productos' style="padding:50px 70px 50px 70px">
                            <label class="lbl_linea">Linea Servicios</label>
                            <p class="productos_p">Nuestra línea de productos de servicios te ofrece un servicio
                                complementario a tu plataforma, como producto maestro, bono
                                transportador, y el servicio especial de notificaciones.</p>
                            <img style="padding-top:20px" src="/static/img/portal/cotizacion/pasarela.png"/>
                        </div>
                    </div>
                </div>

                <!-- Left and right controls -->
                <a class="left carousel-control" href="#myCarousel" data-slide="prev">
                    <span class="glyphicon glyphicon-chevron-left"></span>
                    <span class="sr-only"></span>
                </a>
                <a class="right carousel-control" href="#myCarousel" data-slide="next">
                    <span class="glyphicon glyphicon-chevron-right"></span>
                    <span class="sr-only"></span>
                </a>
            </div>
        </div>
    </div>

</div>

<script>
    var idInput = 0;
    var valueInput = 0;
    var nuevaCandena = 0;
    var abono = 0;
    var dataArray = [];
    var dataObject = {};
    var countObject = 0;
    function sendNewTar() {
        var dataSendArray = [];
        for (i = countObject; i < dataArray.length; i++) {
            var chech = dataArray[i].getElementsByClassName('valCheck');
            for (j = 0; j < chech.length; j++) {
                if (chech[j].checked) {
                    var dataSend = new Object();
                    dataSend.codigo = chech[j].value;
                    var input = dataArray[i].getElementsByClassName('block');
                    dataSend.valor = input[j].value;
                    dataSendArray.push(dataSend);
                }
            }
        }
        var myJSON = JSON.stringify(dataSendArray);
        console.log(myJSON);
        $.ajax({
            url: "/portal/cotizacion/cotizar2/" + myJSON,
        })
                .done(function (dataResponse) {
                    alert("dat");
                });
        //$("#formCotizacion").submit();
    }

    $(document).ready(function () {

        $('#selectLineaProductos').on('change', function () {
            $('.productosLinea').hide(0);
            $('.linea' + this.value).show(150);   

            
            dataArray = document.getElementsByClassName('linea' + $('#selectLineaProductos').val());
            countObject = 0;
            for (i = 0; i < dataArray.length; i++) {
                dataObject[i] = dataArray[i];
                var chech = dataArray[i].getElementsByClassName('valCheck');
                for (j = 0; j < chech.length; j++) {
                    if (chech[j].checked) {
                        countObject++;
                        delete dataObject[i];
                    }
                }
            }
        });
        var CTTarjetas = 0;
        var abonoActual = 0;
        var checkboxes = document.getElementsByTagName("INPUT");
        for (var x = 0; x < checkboxes.length; x++)
        {
            if (checkboxes[x].type == "checkbox")
            {
                //checkboxes[x].checked = false;
            }
        }
    });
    var total = 0;
<?php
foreach ($productos as $key => $valueproducto) {
    if ($valueproducto['ACTIVO'] == 1 && $valueproducto['CODIGO_PRODUCTO'] == 1) {
        ?>
            //console.log(<?php echo $valueproducto['ACTIVO'] ?>);
            //            console.log(<?php echo $valueproducto['CANTIDAD'] ?>);
            total +=<?php echo $valueproducto['CANTIDAD'] ?>;
        <?php
    }
    if ($valueproducto['NOMBRE_PRODUCTO'] == "ABONO MENSUAL") {
        ?>
            //$('#abono_mensual').text("<?php echo $valueproducto['CANTIDAD'] ?>") ;
            document.getElementById('abono_mensual').innerHTML = currencyFormat(<?php echo $valueproducto['CANTIDAD'] ?>);
            abono = parseInt(<?php echo $valueproducto['CANTIDAD'] ?>);
            $('#total_abonos').val(parseInt(<?php echo $valueproducto['CANTIDAD'] ?>));
        <?php
    }
}
?>
    sessionStorage.setItem('totalTarjetas', <?php echo total ?>);
    CTTarjetas =<?php echo total ?>;
    $('#total_tarjetas').val(CTTarjetas);
//    console.log(<?php echo total ?>);
    //sumartarjetas(<?php echo total ?>);    
    /*$('body').on('click', '.productosLinea input', function() {
     alert("entra "+this.id);             
     });*/
    $('body').on('click', '.productosLinea .login-checkbox', function () {
        //var id = $(this).attr('id');
        var idIn = $(this).data('codigo');
        // $('#'+idIn).prop('required',true);
        if ($(this).children('input:checked').is(':checked')) {
            addrequired(idIn);
            quitarReadOnly(idIn);
        } else {
            $("#" + idIn).val('');
            $("#" + idIn).attr('readonly', true);
        }
    });
    function quitarReadOnly(id) {
        // Eliminamos el atributo de solo lectura
        $("#" + id).removeAttr("readonly");
        // Eliminamos la clase que hace que cambie el color
        $("#" + id).removeClass("readOnly");
    }
    function addrequired(id) {
        $("#" + id).prop('required', true);
    }
    function currencyFormat(num) {
        return  parseFloat(num).toFixed(0).replace('.', ',').replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1.');
    }
    $(".block").on('paste', function (e) {
        e.preventDefault();
        //alert('Esta acción está prohibida');
    });
    $(".block").on('copy', function (e) {
        e.preventDefault();
        //alert('Esta acción está prohibida');
    });
    /* Sumar dos números. */
    function sumar(valor) {
        if (!valor == null || !valor == undefined || !valor == "" || !isNaN(valor)) {
            var totalFac = 0;
            valor = parseInt(valor); // Convertir el valor a un entero (número).                
            // Aquí valido si hay un valor en bono, si no hay datos, le pongo un cero "0".
            totalFac = (abono == null || abono == undefined || abono == "") ? 0 : abono;
            /* Esta es la suma. */
            totalFac = (parseInt(totalFac) + parseInt(valor));
            /*Formateo la cadena a moneda*/
            nuevaCandena = currencyFormat(totalFac);
            if (valor == null || valor == undefined || valor == "" || isNaN(valor)) {
                nuevaCandena = currencyFormat(abono);
            }
            // Colocar el resultado de la suma en el control "span".
            document.getElementById('abono_mensual').innerHTML = nuevaCandena;
            $('#total_abonos').val(totalFac);
        }
    }
    function replaceCadena(cadena, caracter, replaceto) {
        var result = 0;
        for (var i = 0; i < cadena.length; i++) {
            if (cadena[i].toLowerCase() === caracter)
                cadena = cadena.replace(caracter, replaceto);
            result = cadena;
        }
        return result;
    }
</script>