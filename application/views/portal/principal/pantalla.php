
<?php
session_start();
//$rol = $this->session->userdata("rol");
$rol = $_SESSION['rol'];
//$llave_maestra = $this->session->userdata("CODIGO_PRODUCTO");
$llave_maestra = $_SESSION['PRODUCTOLLAVE']['CODIGO_PRODUCTO'];
?>
<div class="principal-contenedor" style="margin-bottom: 5%">
    <table align="center">
        <tr>
            <?php if (( $rol == 45 ) or ( $rol == 47)) { ?>
                <td>
                    <div class="principal-menu">
                        <a href="/portal/solicitudTarjetas/solicitud">
                            <table>
                                <tr>
                                    <td>
                                        <img class="principal-menu-img" src="/static/img/portal/menu/principal-i-solicitud.png" />
                                    </td>
                                    <td>
                                        <div style="padding-left: 20px;">
                                            <br/>
                                            Tarjetas
                                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                            <br/>
                                        </div>
                                    </td>
                                </tr>
                            </table>
                            <hr/>
                            <div class="principal-menu-foot">
                                Solicitud de tarjetas, Beneflex, Pasarela y Business
                            </div>
                        </a>
                    </div>
                </td>
            <?php } ?>
            <?php if (( $rol == 45 ) or ( $rol == 47)) { ?>
                <td>
                    <div class="principal-menu-dos">
                        <a href="/portal/abonos/unoAUno">
                            <table>
                                <tr>
                                    <td>
                                        <img class="principal-menu-img" src="/static/img/portal/menu/principal-i-abonos.png" />
                                    </td>
                                    <td>
                                        <div style="padding-left: 20px;" >
                                            <br/>
                                            Abonos 
                                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                            <br/>
                                        </div>
                                    </td>
                                </tr>
                            </table>
                            <hr/>
                            <div class="principal-menu-foot">
                                Abonar dinero Uno a Uno o masivamente
                            </div>
                        </a>
                    </div>
                </td>
            <?php } ?>
            <?php if (( $rol == 45 ) or ( $rol == 47) or ( $rol == 58)) { ?>
                <td>
                    <div class="principal-menu">
                        <a href="/portal/ordenPedido/lista">
                            <table>
                                <tr>
                                    <td>
                                        <img class="principal-menu-img" src="/static/img/portal/menu/principal-i-pagos.png" />
                                    </td>
                                    <td>
                                        <div style="padding-left: 20px;" >
                                            <br/>
                                            Pagos
                                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                            <br/>
                                        </div>
                                    </td>
                                </tr>
                            </table>
                            <hr/>
                            <div class="principal-menu-foot">
                                Pagos de solicitudes de tarjetas o de abonos
                            </div>
                        </a>
                    </div>
                </td>
            <?php } ?>

        </tr>
    </table>
    <table align="center">
        <tr>

            <?php if (( $rol == 45 ) or ( $rol == 47) or ( $rol == 46)) { ?>
                <td>
                    <div class="principal-menu-dos">
                        <a href="/portal/entregas/lista">
                            <table>
                                <tr>
                                    <td>
                                        <img class="principal-menu-img" src="/static/img/portal/menu/principal-i-bono.png" />
                                    </td>
                                    <td>
                                        <div style="padding-left: 20px;">
                                            <br/>
                                            Entregas
                                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                            <br/>
                                        </div>
                                    </td>
                                </tr>
                            </table>
                            <hr/>
                            <div class="principal-menu-foot">
                                Revisar el estado del pedido, confirmar pedido
                            </div>
                        </a>
                    </div>
                </td>
            <?php } ?>
                 <?php if (( $rol == 45 ) or ( $rol == 47) or ( $rol == 46) or ( $rol == 56) or ( $rol == 58)) { ?>
            <td>
                <div class="principal-menu">
                    <a href="/portal/consultas/consultasAbonos">
                        <table>
                            <tr>
                                <td>
                                    <img class="principal-menu-img" src="/static/img/portal/menu/principal-i-consultas.png" />
                                </td>
                                <td>
                                    <div style="
                                         padding-left: 20px;
                                         " >
                                        <br/>
                                        Consultas
                                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                        <br/>
                                    </div>
                                </td>
                            </tr>
                        </table>
                        <hr/>
                        <div class="principal-menu-foot">
                            Revisar informes sobre abonos, facturas y tarjetas
                        </div>
                    </a>
                </div>
            </td>
            <?php } ?>
            <?php if (( $rol == 45 ) or ( $rol == 47)) { ?>
                <td>
                    <!--  <div class="principal-menu-dos">
                          <a href="/portal/cotizacion/cotizar">
                              <table>
                                  <tr>
                                      <td>
                                          <img class="principal-menu-img" src="/static/img/portal/menu/principal-i-cotizacion.png" />
                                      </td>
                                      <td>
                                          <div style="padding-left: 20px;" >
                                              <br/>
                                              Cotización
                                              &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                              <br/>
                                          </div>
                                      </td>
                                  </tr>
                              </table>
                              <hr/>
                              <div class="principal-menu-foot">
                                  Pide nuevos productos para tu portafolio
                                  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                              </div>
                          </a>
                      </div>-->
                </td>
            <?php } ?>
            <td hidden>
                <div class="principal-menu">
                    <a href="/portal/soporte/categorias">
                        <table>
                            <tr>
                                <td>
                                    <img class="principal-menu-img" src="/static/img/portal/menu/principal-i-soporte.png" />
                                </td>
                                <td>
                                    <div style="padding-left: 20px;" >
                                        <br/>
                                        Soporte
                                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                        <br/>
                                    </div>
                                </td>
                            </tr>
                        </table>
                        <hr/>
                        <div class="principal-menu-foot">
                            Ayuda, quejas, reclamos o cambios a realizar
                        </div>
                    </a>
                </div>
            </td>
            <?php if ((( $rol == 59) or ( $rol == 60) or ( $rol == 61)) && $llave_maestra == 70) { ?>
                <td>
                    <div class="principal-menu-dos">
                        <a href="/portal/llaveMaestra/principal">
                            <table>
                                <tr>
                                    <td>
                                        <img class="principal-menu-img" src="/static/img/portal/menu/principal-llave.png" />
                                    </td>
                                    <td>
                                        <div style="padding-left: 20px;" >
                                            <br/>
                                            Llave Maestra
                                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                            <br/>
                                        </div>
                                    </td>
                                </tr>
                            </table>
                            <hr/>
                            <div class="principal-menu-foot">
                                Abonar a tus tarjetas
                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                            </div>
                        </a>
                    </div>
                </td>
            <?php } ?>
            <?php if (( $rol == 45 ) or ( $rol == 47)) { ?>
                <td>
                    <div class="principal-menu">
                        <a href="/portal/solicitudGestion/solicitudGes">
                            <table>
                                <tr>
                                    <td>
                                        <img class="principal-menu-img" src="/static/img/portal/menu/principal-i-gestion.png" />
                                    </td>
                                    <td>
                                        <div style="padding-left: 20px;" >
                                            <br/>
                                            Gestión ordenes
                                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                            <br/>
                                        </div>
                                    </td>
                                </tr>
                            </table>
                            <hr/>
                            <div class="principal-menu-foot">
                                Gestión de solicitudes y ordenes
                            </div>
                        </a>
                    </div>
                </td>
            <?php } ?>
        </tr>
    </table>
</div>

<?php if (( $rol == 45 ) or ( $rol == 47) or ( $rol == 59) or ( $rol == 60) or ( $rol == 61)) { ?>
    <div class="container" style="margin-top: 15%;">
        <!-- Modal -->
        <div class="modal fade" id="ModalCorreo" role="dialog" style="    margin-top: 5%;margin-left: -7%;">
            <div class="modal-dialog" style="width: 50%;">

                <!-- Modal content-->
                <div class="modal-content" style="border-radius:35px;width: 95%;margin-left: auto;">
                    <!--<button class="btn_cerrar_modal" data-dismiss="modal"></button>-->
                    <div class="modal-body" style="text-align: center;height: auto;">
                        <div class="modal-header">
                            <h5 style="color: #366199;font-size: 20px;font-weight: bold; ">Notificación Facturación Electrónica</h5>
                        </div>
                        <p  style="font-size:14px;color:#888686;font-weight: bold;padding-top: 5px;text-align: justify;"> 
                            Buscando GARANTIZAR EL CONTACTO CON NUESTROS CLIENTES y el cumplimiento de la normativa de FACTURACION ELECTRONICA bajo decreto 358 del 5 de marzo de 2020 y 
                            la resolución vigente 00042 del 5 de mayo 2020, agradecemos de su colaboración con el diligenciamiento del formulario adjunto, cuyo objetivo obtener la 
                            información requerida y actualizada para prestar un servicio eficiente y de calidad garantizando el envío y recepción de la Facturación electrónica que se 
                            implementara a partir del 20 de octubre del año en curso.
                            Para el apropiado diligenciamiento de este formulario, por favor tenga presente los siguientes puntos:
                        </p>
                        <ul style="font-size:14px;color:#888686;text-align: justify;">
                            <li>En caso de tener más de un coordinador por favor diligenciar el formulario con el coordinador principal.</li>
                            <li>Los coordinadores adicionales y activos deberán diligenciar este formulario nuevamente sin el resto de la información ya que esta fue diligenciada por el coordinador del punto anterior.</li>
                            <li>Por favor diligencie solo un correo electrónico para Facturación electrónica.</li>
                        </ul>
                        <p style="font-size:14px;color:#888686;font-weight: bold;text-align: justify">Es importante resaltar que a partir del 20 de Octubre las facturas, se remitirán automaticamente al correo aquí diligenciado. <a style="color: #366199 !important;" href="https://docs.google.com/forms/d/e/1FAIpQLSeOZA4sz3EclbJU_LVv_DxJEe70NYQ7I4XayGd2ZQ_wptJVcg/viewform?usp=pp_url" target="_blank" rel="noopener noreferrer">Diligenciar formulario</a>
                            <br><br>De antemano gracias por su ayuda y colaboración.
                            <br>
                            <br>
                            PEOPLEPASS 
                            <br>
                            <br> 
                            Estos datos serán tratados confidencialmente y de conformidad con la Política de Tratamiento de Datos Personales de People Pass y con la legislación vigente en materia de Protección de Datos Personales.
                            Cualquier duda o inquietud contáctenos al correo: proteccion.datos.pp@peoplepass.com.co
                        </p>
                        <div style="margin-bottom: 40px">
                            <div class="button col-sm-6 col-sm-push-3" >
                                <button type="button" class="btn btn-default" id="modal1">ACEPTAR</button>
                            </div>
                        </div>
                        <br>
                    </div>                           
                    <!--PeoplePass, ahora factura electronicamente, para esto te invitamos a actualizar tus datos en el siguiente <a style="color: #366199 !important;" href="https://docs.google.com/forms/d/e/1FAIpQLSeOZA4sz3EclbJU_LVv_DxJEe70NYQ7I4XayGd2ZQ_wptJVcg/viewform?usp=pp_url" target="_blank">Diligenciar formulario</a>-->

                </div>
            </div>
        </div>
    </div>
<?php } ?>


<div class="container" style="margin-top: 15%;">
    <!-- Modal -->
    <div class="modal fade" id="ModalEntregas" name="ModalEntregas" role="dialog" style="    margin-top: 5%;margin-left: -7%;">
        <div class="modal-dialog">

            <!-- Modal content-->
            <div class="modal-content" style="border-radius:35px;    width: 140%;">
                <button class="btn_cerrar_modal" data-dismiss="modal"></button>
                <div class="modal-body" style="text-align: center;height: auto;">
                    <div class="modal-header">
                        <h5 style="color: #366199;font-size: 20px;font-weight: bold; ">Notificación activación de remesas</h5>
                    </div>
                    <p  style="font-size:14px;color:#888686;font-weight: bold;padding-top: 5px;text-align: center;"> 
                        ¡Activa las remesas para que tus colaboradores reciban su beneficio a tiempo!
                    </p>
                    <div style="margin-bottom: 40px">
                        <div class="button col-sm-6 col-sm-push-3" >
                            <a href="/portal/entregas/lista" style="margin: 30px 0px;
                               background-color:#375986;
                               border-radius: 20px;
                               color: white;
                               padding: 12px 50px;
                               text-decoration: none;
                               font-family: Arial, Helvetica, sans-serif;" role="button" aria-pressed="true">Pulsa aquí y activala</a>
                        </div>
                    </div>
                    <br>
                </div>                           
                <!--PeoplePass, ahora factura electronicamente, para esto te invitamos a actualizar tus datos en el siguiente <a style="color: #366199 !important;" href="https://docs.google.com/forms/d/e/1FAIpQLSeOZA4sz3EclbJU_LVv_DxJEe70NYQ7I4XayGd2ZQ_wptJVcg/viewform?usp=pp_url" target="_blank">Diligenciar formulario</a>-->

            </div>
        </div>
    </div>
</div>


<div class="modal fade"  id="notificacionactuliza" role="dialog" data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
        
          <h5 style="color: #366199;font-size: 20px;font-weight: bold; ">Actualización de Datos. </h5>
        </div>
        <div class="modal-body">
        <p  style="font-size:14px;color:#888686;font-weight: bold;padding-top: 5px;text-align: center;"class='razonsocial'>  </p>
        <input  type='hidden'id='correo' name='correo'value='' >
        <input  type='hidden' name='razon'value='' id='razon'>                                   
        <input  type='hidden' name='pkvinculcode'value='' id='pkvinculcode'>                                        
        </div>
        <div class="modal-footer justify-content-between">
        <a id='omitir'  style="
                               background-color:#375986;
                               border-radius: 20px;
                               color: white;
                               padding: 12px 50px;
                               text-decoration: none;
                               font-family: Arial, Helvetica, sans-serif;" role="button" aria-pressed="true">Omitir</a>
        <a id='Confirmar'  style="
                               background-color:#375986;
                               border-radius: 20px;
                               color: white;
                               padding: 12px 50px;
                               text-decoration: none;
                               font-family: Arial, Helvetica, sans-serif;" role="button" aria-pressed="true">Confirmar</a>

        <a id="actulizarfuc" style="
                               background-color:#375986;
                               border-radius: 20px;
                               color: white;
                               padding: 12px 50px;
                               text-decoration: none;
                               font-family: Arial, Helvetica, sans-serif;" role="button" aria-pressed="true" target="_blank">actualizar</a>

                    
        </div>
      </div>
                        
    </div>
  </div>
<script>

 


    (function () {

        if(<?= $rol ?> === 47) {
            validanotifacionmodal();
     
  
   }

   $("#actulizarfuc").click(function(){
    //portal/entidad/redirecionafuc
    $("#notificacionactuliza").modal('hide');
    //confirmar
    $('#ModalCorreo').modal('show');

    $.post(  '<?= base_url() . "portal/entidad/redirecionafuc" ?>', { pkvinculcode:  $("#pkvinculcode").val() })
  .done(function( data ) {
    console.log(data);
        window.open(data, '_blank');
  });
   })
 $("#Confirmar").click(function(){
    $("#notificacionactuliza").modal('hide');
    //confirmar
    $('#ModalCorreo').modal('show');
    $.post(  '<?= base_url() . "portal/entidad/confirmarfuc" ?>', { pkvinculcode:  $("#pkvinculcode").val() })
  .done(function( data ) {
    //alert( "Data Loaded: " + data );
  console.log(data);
  });
 })  
 $("#omitir").click(function(){
    $("#notificacionactuliza").modal('hide');
    $('#ModalCorreo').modal('show');
    $.post(  '<?= base_url() . "portal/entidad/enviomail" ?>', { correo: $("#correo").val(), razon:  $("#razon").val() })
  .done(function( data ) {
    //alert( "Data Loaded: " + data );
  console.log(data);
  });

 })
      if ( <?= $rol ?> === 45) {
      
        $('#ModalCorreo').modal('show');


        }
        $("#modal1").click(function () {
            $('#ModalCorreo').modal('hide');
            valida();
        });
        if (<?= $rol ?> === 46) {
            valida();
        }
       
    
function validanotifacionmodal(){ 


    $.getJSON(' <?= base_url() . "portal/entidad/datamayorano" ?>', function (data) {
             //console.log(data[0]['CORREO_ELECTRONICO']);
                if (data[0]['RAZON_SOCIAL'] && data[0]['CORREO_ELECTRONICO'] ) {
                    //console.log(data[0]['RAZON_SOCIAL']);
                $('.razonsocial').html(" ")
                $('.razonsocial').html(" Desea actualizar los datos para la empresa "+" "+data[0]['RAZON_SOCIAL']    )
                $("#razon").val('');
                $("#razon").val(data[0]['RAZON_SOCIAL']);
                
                $("#correo").val('')
                $("#correo").val(data[0]['CORREO_ELECTRONICO'])
                $("#pkvinculcode").val('')
                $("#pkvinculcode").val(data[0]['PK_VINCUL_CODIGO']);

               $("#notificacionactuliza").modal('show')
}else{
    $('#ModalCorreo').modal('show');

}


                //$('#ModalEntregas').modal('show');

            });
    
}

        function valida() {
            $.getJSON(' <?= base_url() . "portal/principal/validacionModal" ?>', function (data) {
                console.log('remsa',data.length)
                if (data.length > 0) {

                    $('#ModalEntregas').modal('show');
                }

                //$('#ModalEntregas').modal('show');

            });
        }
    })
    
    
    
    ()
</script>
