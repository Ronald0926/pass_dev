$(document).ready(function() {

    setInterval(function() {
        fetch_user();
        // update_chat_history_data();
    }, 2000);

    function fetch_user() {
        $.ajax({
                url: "/portal/ajax/fetch_user",
            })
            .done(function(msg) {
                $('#user_details').html(data);
            });
    }

    $(document).on('click', '.start_chat', function() {
        var to_user_id = $(this).data('touserid');
        var to_user_name = $(this).data('tousername');
        var pk_chat = $(this).data('pkchat');
        make_chat_dialog_box(to_user_id, to_user_name, pk_chat);
        $("#user_dialog_" + to_user_id).dialog({
            autoOpen: false,
            width: 400
        });
        $('#user_dialog_' + to_user_id).dialog('open');
        $('#chat_message_' + to_user_id).emojioneArea({
            pickerPosition: "top",
            toneStyle: "bullet"
        });
    });

    function make_chat_dialog_box(to_user_id, to_user_name, pk_chat) {
        var modal_content = '<div id="user_dialog_' + to_user_id + '" class="user_dialog" title="Chat con: ' + to_user_name + '">';
        modal_content += '<div style="height:400px; border:1px solid #ccc; overflow-y: scroll; margin-bottom:24px; padding:16px;" class="chat_history" data-touserid="' + to_user_id + '" id="chat_history_' + to_user_id + '">';
        modal_content += fetch_user_chat_history_sac(to_user_id, pk_chat);
        modal_content += '</div>';
        modal_content += '<div class="form-group">';
        modal_content += '<textarea name="chat_message_' + to_user_id + '" id="chat_message_' + to_user_id + '" class="form-control chat_message"></textarea>';
        modal_content += '</div><div class="form-group" align="right">';
        modal_content += '<button type="button" name="send_chat" id="' + to_user_id + '" class="btn btn-info send_chat" data-pkchat="' + pk_chat + '">Enviar</button></div></div>';
        $('#user_model_details').html(modal_content);
    }

    function fetch_user() {
        $.ajax({
                url: "/portal/ajax/fetch_user",
            })
            .done(function(dataResponse) {
                $('#user_details').html(dataResponse);
            });
    }

    /*Chat open and close*/
    $('.live-chat-body').slideToggle();
    $('.live-chat-header').on('click', function() {

        $('.chat').slideToggle(300, 'swing');
        $('.chat-message-counter').fadeToggle(300, 'swing');

    });

    $('.chat-close').on('click', function(e) {
            e.preventDefault();
            $('#live-chat').fadeOut(300);
        })
        /**********/
    $(document).on('click', '.send_chat', function() {
        var to_user_id = $(this).attr('id');
        var pk_chat = $(this).data('pkchat');
        var chat_message = $('#chat_message_' + to_user_id).val();
        if (chat_message != "" && chat_message != undefined && chat_message != null) {
            $.ajax({
                    url: "/portal/ajax/insertMessageSac/" + chat_message + "/" + pk_chat + "/" + to_user_id,
                })
                .done(function(data) {
                    $("#chat_history_" + to_user_id).html(data);
                });
        }
    });

    $('#send_keypress').keypress(function(e) {
        var key = e.which;
        if (key == 13) {
            var chat_message = $('#chat_message').val();
            $('#chat_message').val("");
            if (chat_message != "" && chat_message != undefined && chat_message != null) {
                $.ajax({
                        url: "/portal/ajax/sendMessageSac/" + chat_message,
                    })
                    .done(function(msg) {
                        initialChat();
                    });
            }
        }
    });

    function fetch_user_chat_history_sac(to_user_id, pk_chat) {
        $.ajax({
                url: "/portal/ajax/fetch_user_chat_history_sac/" + to_user_id + "/" + pk_chat,
            })
            .done(function(msg) {
                $("#chat_history_" + to_user_id).html(msg);
                // $("#chat_history_" + to_user_id).html(msg).scrollTop($("#chat_history" + to_user_id).prop('scrollHeight'));

            });
    }

    function update_chat_history_data() {
        $('.ul_chat').each(function() {
            var to_user_id = $(this).data('touserid');
            var pk_chat = $(this).data('pkchat');
            fetch_user_chat_history_sac(to_user_id, pk_chat);
        });

    }





});