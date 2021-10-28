$(document).ready(function() {



    function initialChat() {
        setInterval(function() {
            update_last_activity();
            update_chat_history_data();
        }, 2000);
    }


    function update_last_activity() {
        $.ajax({
                url: "/portal/ajax/update_last_activity",
            })
            .done(function(dataResponse) {});
    }

    $('.chat').slideToggle();
    /*Chat open and close*/
    $('.live-chat-header').on('click', function() {

        $('.chat').slideToggle(300, 'swing');
        $('.chat-message-counter').fadeToggle(300, 'swing');
        make_chat_dialog_box();
        $("#user_dialog").dialog({
            autoOpen: false,
            width: 400
        });
        $('#user_dialog').dialog('open');
    });

    $('.chat-close').on('click', function(e) {
            e.preventDefault();
            $('#live-chat').fadeOut(300);
        })
        /**********/
    $(document).on('click', '.send_chat', function() {
        var chat_message = $('#chat_message').val();
         
        if (chat_message != "" && chat_message != undefined && chat_message != null) {
             var result = countChars1(chat_message) 
             
             if(result==true){
               $('#chat_message').val("");
                    $.ajax({
                    url: "/portal/ajax/sendMessageClient/" + chat_message,
                })
                .done(function(msg) {
                    initialChat();
                    // var element = $('#chat_message_' + to_user_id).emojioneArea();
                    // element[0].emojioneArea.setText('');
                    // $('#chat_history_' + to_user_id).html(data);
                });
            }
                
            }
        
    });
    $('#classSendMessage').keypress(function(e) {
        var key = e.which;
        if (key == 13) // the enter key code
        {
            var chat_message = $('#chat_message').val();
            $('#chat_message').val("");
            if (chat_message != "" && chat_message != undefined && chat_message != null) {
                
                
                $.ajax({
                    
                            
                        url: "/portal/ajax/sendMessageClient/" + chat_message,
                    })
                    .done(function(msg) {
                        initialChat();
                        // var element = $('#chat_message').emojioneArea();
                        // element[0].emojioneArea.setText('');
                        // $('#chat_history_' + to_user_id).html(data);
                    });

            }
        }
    });

    function fetch_user_chat_history() {
        $.ajax({
                url: "/portal/ajax/fetch_user_chat_history",
            })
            .done(function(data) {
                $('#chat_history').html(data);
                // $("#chat_history").html(data).scrollTop($("#chat_history").prop('scrollHeight'));
            })
            .fail(function() {
                var today = new Date();
                var time = today.getHours() + ":" + today.getMinutes() + ":" + today.getSeconds();
                $('#chat_history').html("<li>" +
                    "<div class='msj macro'>" +
                    "<div class='text text-r'>" +
                    "<p>'Bienvenido al chat People Pass'</p>" +
                    "<p><small>" + time + "</small></p> " +
                    "</div>" +
                    "</div>" +
                    "</li>");
            });
    }

    function update_chat_history_data() {
        $('.chat_history').each(function() {
            fetch_user_chat_history();
        });

    }

    function make_chat_dialog_box() {
        var modal_content = '<div class="chat_history" id="chat_history">';
        modal_content += fetch_user_chat_history();
        modal_content += '</div>';
        modal_content += '<div action="" method="post" class="formSendMessage">';
        modal_content += '<fieldset><div class="input-group"><input id="chat_message" type="text" onkeyup="countChars()"  class="form-control classSendMessage" placeholder="Escribe un mensaje" name="txtMessage" autofocus><span class="input-group-btn"> <button class="sendCuadrado send_chat" type="button"><img src="/static/img/portal/iconos/Iconos_Envio_2.png"></button></span>';
        modal_content += '</div></fieldset></div>';
        $('#user_model_details').html(modal_content);
        
    }


function countChars1(chat_message){
    var maxLength = 20;
    var strLength = $("#chat_message").val().length;
    var charRemain = (maxLength - strLength);
    var result;
    
    if(charRemain < 0){
      //  $('#chat_message').css("color", "red");
        //document.getElementById("chat_message").innerHTML = '<span style="color: red;">El maximo caracteres es '+maxLength+' characters</span>';
        result = false;
    }else{
     //      $('#chat_message').css("color", "black");
        result = true;
    }
    
    return result;
}

//$('input').on('keyup', function(){
//    console.log("Prueba");
//});


    // $(document).on('focus', '#chat_message', function() {
    //     var is_type = 'yes';
    //     $.ajax({
    //         url: "/portal/ajax/update_is_type_status",
    //         method: "POST",
    //         data: {
    //             is_type: is_type
    //         },
    //         success: function() {

    //         }
    //     })
    // });

    // $(document).on('blur', '#chat_message', function() {
    //     var is_type = 'no';
    //     $.ajax({
    //         url: "/portal/ajax/update_is_type_status",
    //         method: "POST",
    //         data: {
    //             is_type: is_type
    //         },
    //         success: function() {

    //         }
    //     })
    // });



}
        
        
        );

function countChars(){
    var maxLength = 20;
    var strLength = $("#chat_message").val().length;
    var charRemain = (maxLength - strLength);
    var result;
    
    if(charRemain < 0){
        $('#chat_message').css("color", "red");
        //document.getElementById("chat_message").innerHTML = '<span style="color: red;">El maximo caracteres es '+maxLength+' characters</span>';
        result = false;
    }else{
           $('#chat_message').css("color", "black");
        result = true;
    }
    
    return result;
}