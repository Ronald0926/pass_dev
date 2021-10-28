<style>
    #masivoIconos td,
    th {
        padding: 30px;
    }
     .tnotifi{
        color: red;
        padding-left:  15px;
        display: none;
    }
        .hiddenFileDownload > a{
        height: 100%;
        width: 100;
        opacity: 0;
        cursor: pointer;
    }
    .hiddenFileDownload{
        border: none;
        width: 120%;
        height: 50px;
        display: inline-block;
        overflow: hidden;
        margin-left: -5%;
        /*for the background, optional*/
        background: center center no-repeat;
        background-size: 100% 100%;
        background-image:  url(/static/img/portal/solicitudTar/descargar-plantilla.png);
    }
      .hiddenFileInput > input{
        height: 100%;
        width: 100;
        opacity: 0;
        cursor: pointer;
    }
    .hiddenFileInput{
        border: none;
        width: 245px;
        height: 34px;
        display: inline-block;
        overflow: hidden;
        margin-left: -5%;
        /*for the background, optional*/
        background: center center no-repeat;
        background-size: 100% 100%;
        background-image:  url(/static/img/portal/solicitudTar/cargar-archivo-new.png);
    }
     .descargar{
        background-image: url('/static/img/portal/solicitudTar/descargar-plantilla.png');
        width: 199px;
        height: 40px;
        background-repeat: no-repeat;
    }
       .excel-new{
        background-image: url('/static/img/portal/solicitudTar/excel-new.png');
        width: 245px;
        height: 212px;
        background-repeat: no-repeat;
    }
/*    .excel:hover{
        background-image: url('/static/img/portal/solicitudTar/excel-hover.png');
        width: 200px;
        height: 200px;
        background-repeat: no-repeat;
    }*/
    .subir{
        background-image: url('/static/img/portal/solicitudTar/subir-excel.png');
        width: 200px;
        height: 200px;
        background-repeat: no-repeat;
    }
</style>
<?php
session_start();
//$rol = $this->session->userdata("rol");
$rol = $_SESSION['rol'];
//$llave_maestra = $this->session->userdata("CODIGO_PRODUCTO");
?>
<div class="loader" id="loader" hidden=""></div>
<div class="col-lg-2"></div>
<div class="container col-lg-8" style="margin-bottom: 200px; margin-top: -50px;">
    <hr style="border-top: 1px solid #eee0;">
    <h2 class="titulo-iz">Asociación Tarjetas</h2>
    <ul class="nav nav-tabs">
        <li><a href="/portal/llaveMaestra/asociacion">Asociación uno a uno</a></li>
        <li class="active"><a data-toggle="tab" href="#solicitudMasiva">Asociación masiva</a></li>
        <li><a href="/portal/llaveMaestra/desasociacion">Desasociación</a></li>
    </ul>
          <div id="solicitudMasiva" class="tab-pane fade in active">
             

 <br><br><br>
        <input type="text" name="pk_llavero_codigo" id='pk_llavero_codigo' value="<?= $pk_llavero_codigo ?>" hidden>
        <div class="tab-content">
      
                <h3>Asociacion masiva tarjetas</h3>
                <p> Aqu&iacute;, podr&aacute;s asociar varias tarjetas a un llavero a la vez, descargando la plantilla, diligenciando los datos solicitados de las tarjetas y los colaboradores a quienes deseas proporcionarles estos productos, guardando los cambios de la plantilla, subi&eacute;ndola y envi&aacute;ndola desde la plataforma.</p>
                <div class="col-sm-4">
                    
                  
                            <tr>
                                <p>Asociacion masiva de tarjetas</p>
                            </tr>
                          
                             <form  method="post" action="solicitudAsocMasivaPlantilla/1" enctype="multipart/form-data" id="solicitudMas">        
                      
                                                                 
            <div class="select">
                <select name="pk_llavero" id="llavero" required>
                    <option value=""> Seleccione Llavero</option>
                    <?php foreach ($llaveros as $key => $value) { ?>
                        <option value="<?= $value['PK_LLAVERO_CODIGO'] ?>" <?php if ($value['PK_LLAVERO_CODIGO'] == $pk_llavero_codigo) echo 'selected'; ?>> <?= ucwords(strtolower($value['NOMBRE_LLAVERO'])) ?></option>
                    <?php } ?>
                </select>
                <div> <?php echo $nombrellaveroselect != "" ? $nombrellaveroselect : 'Seleccione Llavero*' ?></div>
            </div>
 
                                
                         
                           
                             
                                 <br><br>
                                    <a href="/portal/llaveMaestra/descargarPlantillaAsociacion" > <div class="excel-new"></div> </a>
                     
                          <br><br>
                                    <div style="display:inline-block; padding:0.35em 0.6em; border:0.1em solid #FFFFFF; margin:0 0.3em 0.3em 0; border-radius:0.12em; box-sizing: border-box; text-decoration:none; font-family:'Roboto',sans-serif; font-weight:300; color:#FFFFFF; text-align:center; transition: all 0.2s;">
                                            <span class="hiddenFileInput">
                                                <input type="file" name="file" required/>
                                            </span>
                                            <input type='hidden' id="status" value=''>
                                      

                                    </div>
                           
                             </form> 
                            <br>
                                    <div class="button" >
                                        <button style="max-width: 245px;" data-toggle="modal" data-target="#ModalConfSolMasi">Enviar Plantilla Subida</button>
                                    </div>

                </div>
                <hr>              
          

              
        </div>
                      
  </div>
</div>

  <!-- Modal confirmacion solicitud masiva-->
<div class="modal fade" id="ModalConfSolMasi" role="dialog" style="margin-top: 5%;"  data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content" style="border-radius:35px">

            <div class="modal-body" style="text-align: center;height: auto;">
                <div class="modal-header" style="padding:0px">
                    <h5 style="color: #366199;font-size: 24px;font-weight: bold; ">Confirmar solicitud</h5>
                </div>
                <br>
                <p  style="font-size:17px;color:#333;padding-top: 5px;text-align: center;"><span  class="glyphicon glyphicon-exclamation-sign"></span>
                    Desea continuar con la operaci&oacute;n.
                </p>
                <div style=" margin-bottom: 4em">
                    <div class="button col-sm-6" >
                        <button type="button" name="ACEPTAR" value="1" class="btn btn-default spacing" id="LbutonDacptar" onclick="
                                $('#solicitudMas').submit();" >SI</button>
                        <!-- <button type="button" name="ACEPTAR" value="1" class="btn btn-default spacing"   onclick="AceptarsolicitudMas1()" >SI</button>-->

                    </div>
                    <div class="button col-sm-6" >
                        <button type="button" name="CANCELAR" class="btn btn-default spacing" data-dismiss="modal">NO</button>
                    </div>
                </div>
                <br>
            </div>
        </div>
    </div>
</div>
<?php if ($error == 1) { ?>
    <div class="container" style="margin-top: 15%;">
        <!-- Modal -->
        <div class="modal fade" id="myModal" role="dialog" style="    margin-top: 15%;">
            <div class="modal-dialog">

                <!-- Modal content-->
                <div class="modal-content" style="border-radius:35px">
                    <div class="modal-body" style="text-align: center;height: 230px;">
                        <form action="/portal/solicitudTarjetas/nombreOrden" method="POST">
                            <p style="font-size:18px;color:#0c385e;font-weight: bold">¡Solicitud exitosa!</p>
                            <br>
                            <p style="font-size:18px;color:#888686;">Por favor asigne un nombre a la orden:</p>
                            <br>
                            <input type="hidden" name="codigo" value="<?= $codigosolicitud ?>">
                            <input type="text" class="textPat" name="nombreorden" style="width: 60%" placeholder="Ingrese un nombre para la orden" required>
                            <br>
                            <div class="button col-sm-6">
                                <button type="submit" name="ORDEN" value="1" class="btn btn-default">ORDEN DE PEDIDO</button>
                            </div>
                            <div class="button col-sm-6">
                                <button type="submit" name="SOLICITUD" value="2" class="btn btn-default">SOLICITAR ABONO</button>
                            </div>
                            <br>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php } ?>
  
  
  
  <!-- Modal creacion exitosa-->
<div class="modal fade" id="modalsuccess" role="dialog" style="    margin-top: 15%;">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content" style="border-radius:35px">

            <div class="modal-body" style="text-align: center;height: 200px;">

                <p  style="font-size:24px;color:#888686;font-weight: bold;padding-top: 25px">Tarjetas Asociadas Correctamente
                </p>

                <div style="padding-top: 25px">
                    <form method="post" action="/portal/llaveMaestra/asociacionMasiva">
                        <div class="button col-sm-6 col-md-push-3" >
                            <button type="submit" name="ACEPTAR" value="1" class="btn btn-default" >A C E P T A R</button>
                        </div>                    
                    </form>
                </div>
                <br>
            </div>
        </div>
    </div>
</div>
  
   <!-- Modal creacion exitosa-->
<div class="modal fade" id="modalcampos" role="dialog" style="    margin-top: 15%;">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content" style="border-radius:35px">

            <div class="modal-body" style="text-align: center;height: 200px;">

                <p  style="font-size:24px;color:#888686;font-weight: bold;padding-top: 25px">No Selecciono Ningun LLavero
                </p>

                <div style="padding-top: 25px">
                    <form method="post" action="/portal/llaveMaestra/asociacionMasiva">
                        <div class="button col-sm-6 col-md-push-3" >
                            <button type="submit" name="ACEPTAR" value="1" class="btn btn-default" >A C E P T A R</button>
                        </div>                    
                    </form>
                </div>
                <br>
            </div>
        </div>
    </div>
</div>
   
   <!-- Modal error asociacion-->
<div class="modal fade" id="modalerror" role="dialog" style="    margin-top: 15%;">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content" style="border-radius:35px">

            <div class="modal-body" style="text-align: center;height: 200px;">

                <p  style="font-size:24px;color:#888686;font-weight: bold;padding-top: 25px">Error Procesando el Archivo En Apolo
                </p>

                <div style="padding-top: 25px">
                    <form method="post" action="/portal/llaveMaestra/asociacionMasiva">
                        <div class="button col-sm-6 col-md-push-3" >
                            <button type="submit" name="ACEPTAR" value="1" class="btn btn-default" >A C E P T A R</button>
                        </div>                    
                    </form>
                </div>
                <br>
            </div>
        </div>
    </div>
</div>
   
      <!-- Modal error carga archivo-->
<div class="modal fade" id="modalerrorcarga" role="dialog" style="    margin-top: 15%;">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content" style="border-radius:35px">

            <div class="modal-body" style="text-align: center;height: 200px;">

                <p  style="font-size:24px;color:#888686;font-weight: bold;padding-top: 25px">Error No Se Ha Cargado Ningun Archivo
                </p>

                <div style="padding-top: 25px">
                    <form method="post" action="/portal/llaveMaestra/asociacionMasiva">
                        <div class="button col-sm-6 col-md-push-3" >
                            <button type="submit" name="ACEPTAR" value="1" class="btn btn-default" >A C E P T A R</button>
                        </div>                    
                    </form>
                </div>
                <br>
            </div>
        </div>
    </div>
</div>


<div class="col-lg-2" style=" /*margin-bottom: 200px; margin-top: -50px;*/"></div>

<?php if ($sucess == '1') { ?>
    <script>
        $('#modalsuccess').modal('show');
    </script>
<?php } elseif ($sucesse == '200') { ?>
    <script>
        $('#modalerror').modal('show');
    </script>
<?php }elseif ($sucesse == '300') { ?>
  <script>
        $('#modalcampos').modal('show');
    </script>
    <?php }elseif ($sucesse == '600') { ?>
  <script>
        $('#modalerrorcarga').modal('show');
    </script>
    <?php }?>
     <script>
        $("#solicitudMasiva").submit(function () {
                                $('#loader').modal('show');
                               $('#LbutonDacptar').attr("disabled", true);

                            });


                            $("#solicitudMas").submit(function () {
                                $('#loader').modal('show');
                                $('#LbutonDacptar2').attr("disabled", true);
                            });
                            var x = "<?= $ok ?>";
                            if (x == "1") {
                                $('#myModalok').modal('show');
                            }
    </script>
      <!-- 
<script>
    var errorpkcodigo = <?php if (isset($errorpkllavero)) {    echo "1;";} else {    echo "0;";}?>
       if (errorpkcodigo === 1) {
        $(".tnotifi").show();
        }
   
    
    $(document).ready(function () {
                                                    var table = $('#tblmasiva').DataTable({
                                                        "bJQueryUI": true,
                                                        "bSort": false,
                                                        "bPaginate": true,
                                                        "sPaginationType": "full_numbers",
                                                        "oLanguage": {
                                                            "sProcessing": "Procesando...",
                                                            "sLengthMenu": "Mostrar _MENU_ registros",
                                                            "sZeroRecords": "No se encontraron resultados",
                                                            "sEmptyTable": "Ningún dato disponible en esta tabla",
                                                            "sInfo": "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
                                                            "sInfoEmpty": "Mostrando registros del 0 al 0 de un total de 0 registros",
                                                            "sInfoFiltered": "(filtrado de un total de _MAX_ registros)",
                                                            "sInfoPostFix": "",
                                                            "sSearch": "",
                                                            "sUrl": "",
                                                            "sInfoThousands": ",",
                                                            "sLoadingRecords": "Cargando...",
                                                            "oPaginate": {
                                                                "sFirst": "Primero",
                                                                "sLast": "Último",
                                                                "sNext": "Siguiente",
                                                                "sPrevious": "Anterior"
                                                            },
                                                            "oAria": {
                                                                "sSortAscending": ": Activar para ordenar la columna de manera ascendente",
                                                                "sSortDescending": ": Activar para ordenar la columna de manera descendente"
                                                            }
                                                        }

                                                    });

                                                    // Handle click on "Select all" control
                                                    $('#chkTodo').on('click', function () {
                                                        
                                                        if( $('#chkMasivaAll').prop('checked') ) {
                                                             // Check/uncheck all checkboxes in the table
                                                            var rows = table.rows({'search': 'applied'}).nodes();
                                                            for (i = 0; i < rows.length; i++) {
                                                                $(".spnmasiva", rows[i]).css("display", "inline");
                                                                $('.chkmasiva', rows[i]).prop('checked', true);
                                                            }
                                                        }else{
                                                            var rows = table.rows({'search': 'applied'}).nodes();
                                                            for (i = 0; i < rows.length; i++) {
                                                                $(".spnmasiva", rows[i]).css("display", "none");
                                                                $('.chkmasiva', rows[i]).prop('checked', false);
                                                            }
                                                        }
                                                            
                                                        
                                                       
                                                    });

                                                    // Handle click on checkbox to set state of "Select all" control
                                                    $('#formasociacionmasivo tbody').on('change', 'input[type="checkbox"]', function () {
                                                        // If checkbox is not checked
                                                        if (!this.checked) {
                                                            var el = $('#chkTodo').get(0);
                                                            // If "Select all" control is checked and has 'indeterminate' property
                                                            if (el && el.checked && ('indeterminate' in el)) {
                                                                // Set visual state of "Select all" control 
                                                                // as 'indeterminate'
                                                                el.indeterminate = true;
                                                            }
                                                        }
                                                    });
                                                    $('#formasociacionmasivo').on('submit', function (e) {
                                                        var form = this;

                                                        // Iterate over all checkboxes in the table
                                                        table.$('input[type="checkbox"]').each(function () {
                                                            // If checkbox doesn't exist in DOM
                                                            if (!$.contains(document, this)) {
                                                                // If checkbox is checked
                                                                if (this.checked) {
                                                                    // Create a hidden element 
                                                                    $(form).append(
                                                                            $('<input>')
                                                                            .attr('type', 'hidden')
                                                                            .attr('name', this.name)
                                                                            .val(this.value)
                                                                            );
                                                                }
                                                            }
                                                        });

                                                        // FOR TESTING ONLY

                                                        // Output form data to a console
//      $('#example-console').text($(form).serialize()); 
//      console.log("Form submission", $(form).serialize()); 

                                                        // Prevent actual form submission
//      e.preventDefault();
                                                    });

                                                });
    $("#formasociacionmasivo").submit(function () {
        $('#loader').modal('show');
    });
</script>
  -->