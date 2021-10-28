         <script type="text/javascript" src="/static/js/portal/chat_online2.js"></script>
         
<!--        18 diciembre 2019 se oculta chat Ronald
        <div id="live-chat">
             <div class="live-chat-header" id="header_chat">
                 <h4>People Pass</h4>
                  <span class="chat-message-counter">TotalMensaje</span> 
             </div>
             <div id="body_chat" class="chat frame live-chat-body">
                  <ul class="ul_chat" id="chat_history">
                     
                 </ul>  

                  <div class="chat_history" id="chat_history">

                 </div> 
                 <div id="user_model_details" class="ul_chat">

                 </div>                 
             </div>
              end chat 
         </div>-->
         <!-- end live-chat -->
         <div class="portal-footer">
             <div class="portal-footer-background"></div>
             <div class="container-fluid">
                 <div class="row">
                     <div class="col-sm-4">
                         <table>
                             <tr>
                                 <td>
                                     <strong>V_1.50.9.3 </strong>Siguenos en nuestras redes sociales &nbsp;&nbsp;
                                 </td>
                                 <td>
                                     <a href="https://es-la.facebook.com/peoplepassS.A/">
                                         <div class="portal-footer-redes portal-footer-redes-facebook">
                                             &nbsp;
                                         </div>
                                     </a>
                                 </td>
                                 <td>
                                     <a href="https://twitter.com/people-pass?lang=es">
                                         <div class="portal-footer-redes portal-footer-redes-twitter">
                                             &nbsp;
                                         </div>
                                     </a>
                                 </td>
                             </tr>
                         </table>
                     </div>
                     <div class="col-sm-5">
                         &copy; 2019 Peoplepass. Todos los derechos reservados. 
                     </div>
                     <div class="col-sm-3">
                         <a href="" class="portal-footer-download">
                             Descargar T&eacute;rminos y condiciones de uso <img src="/static/img/portal/download.png" width="16px" />
                         </a>
                     </div>
                 </div>
             </div>
         </div>
         </body>
         <script type="text/javascript">
           //  $(function() {
           //      $('[data-toggle="tooltip"]').tooltip()
           //  })();
          /* $.ajax({
                url: "/portal/ajax/notification/" + <?php echo $this->session->userdata("entidad")["PK_ENT_CODIGO"] ?>
                     })
                    .done(function (msg) {
                         $('#notificacion').html(msg);
                         $('#numNoti').html($('#numNotiback').text());
                          //$.each(noti, function(key, value ) {
                          //      console.log(key+'='+value.NOMBRE);
                          //  });
                    })
                      .fail(function(jqXHR, textStatus, errorThrown) {
                       console.log( "error".msg);
                    }); */
                    mantener(<?php echo $this->session->userdata['usuario']['PK_ENT_CODIGO']; ?>);
         </script>

         </html>