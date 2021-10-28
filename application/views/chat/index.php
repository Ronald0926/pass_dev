
<body>
	 <script type="text/javascript" src="/static/js/portal/chat_online_sac.js"></script>
	<script async='async' src='//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js'></script>
	<script>
		(adsbygoogle = window.adsbygoogle || []).push({
			google_ad_client: "ca-pub-4529508631166774",
			enable_page_level_ads: true
		});
	</script>
	<div class="container">
		<div class="row">
			<div class="col-md-8 col-sm-6">
				<h4>Chat Online </h4>
			</div>

			<div class="col-md-2 col-sm-3">
				
			</div>
		</div>
		<div class="table-responsive">
			<div id="user_details"></div>
			<div id="user_model_details" class="ul_chat"></div>
		</div>
		<br />
		<br />
		<script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
		<!-- webslesson_mainblogsec_Blog1_1x1_as -->
		<ins class="adsbygoogle" style="display:block" data-ad-client="ca-pub-4529508631166774" data-ad-host="ca-host-pub-1556223355139109" data-ad-host-channel="L0007" data-ad-slot="6573078845" data-ad-format="auto"></ins>
		<script>
			(adsbygoogle = window.adsbygoogle || []).push({});
		</script>
		<br />
		<br />
	</div>
	<script>
		(function(i, s, o, g, r, a, m) {
			i['GoogleAnalyticsObject'] = r;
			i[r] = i[r] || function() {
				(i[r].q = i[r].q || []).push(arguments)
			}, i[r].l = 1 * new Date();
			a = s.createElement(o),
				m = s.getElementsByTagName(o)[0];
			a.async = 1;
			a.src = g;
			m.parentNode.insertBefore(a, m)
		})(window, document, 'script', 'https://www.google-analytics.com/analytics.js', 'ga');

		ga('create', 'UA-87739877-1', 'auto');
		ga('send', 'pageview');
	</script>
</body>
<style>
	.chat_message_area {
		position: relative;
		width: 100%;
		height: auto;
		background-color: #FFF;
		border: 1px solid #CCC;
		border-radius: 3px;
	}
	
	.image_upload {
		position: absolute;
		top: 3px;
		right: 3px;
	}

	.image_upload>form>input {
		display: none;
	}

	.image_upload img {
		width: 24px;
		cursor: pointer;
	}
</style>
<!-- <script>
	$(document).ready(function() {
		
		setInterval(function() {			
			fetch_user();
			// update_chat_history_data();
		}, 4000);

		function fetch_user() {
			$.ajax({
                    url: "/portal/ajax/fetch_user",
                })
                .done(function(msg) {
					$('#user_details').html(data);
                });
			
		}

		function update_last_activity() {
			$.ajax({
				url: "update_last_activity.php",
				success: function() {

				}
			})
		}

		function make_chat_dialog_box(to_user_id, to_user_name) {
			var modal_content = '<div id="user_dialog_' + to_user_id + '" class="user_dialog" title="You have chat with ' + to_user_name + '">';
			modal_content += '<div style="height:400px; border:1px solid #ccc; overflow-y: scroll; margin-bottom:24px; padding:16px;" class="chat_history" data-touserid="' + to_user_id + '" id="chat_history_' + to_user_id + '">';
			modal_content += fetch_user_chat_history(to_user_id);
			modal_content += '</div>';
			modal_content += '<div class="form-group">';
			modal_content += '<textarea name="chat_message_' + to_user_id + '" id="chat_message_' + to_user_id + '" class="form-control chat_message"></textarea>';
			modal_content += '</div><div class="form-group" align="right">';
			modal_content += '<button type="button" name="send_chat" id="' + to_user_id + '" class="btn btn-info send_chat">Send</button></div></div>';
			$('#user_model_details').html(modal_content);
		}

		$(document).on('click', '.start_chat', function() {
			var to_user_id = $(this).data('touserid');
			var to_user_name = $(this).data('tousername');
			make_chat_dialog_box(to_user_id, to_user_name);
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

		$(document).on('click', '.send_chat', function() {
			var to_user_id = $(this).attr('id');
			var chat_message = $('#chat_message_' + to_user_id).val();
			$.ajax({
				url: "insert_chat_sac.php",
				method: "POST",
				data: {
					to_user_id: to_user_id,
					chat_message: chat_message
				},
				success: function(data) {
					//$('#chat_message_'+to_user_id).val('');
					var element = $('#chat_message_' + to_user_id).emojioneArea();
					element[0].emojioneArea.setText('');
					$('#chat_history_' + to_user_id).html(data);
				}
			})
		});

		function fetch_user_chat_history(to_user_id) {
			$.ajax({
				url: "fetch_user_chat_history.php",
				method: "POST",
				data: {
					to_user_id: to_user_id
				},
				success: function(data) {
					$('#chat_history_' + to_user_id).html(data);
				}
			})
		}

		function update_chat_history_data() {
			$('.chat_history').each(function() {
				var to_user_id = $(this).data('touserid');
				fetch_user_chat_history(to_user_id);
			});
		}

		$(document).on('click', '.ui-button-icon', function() {
			$('.user_dialog').dialog('destroy').remove();

		});

		$(document).on('focus', '.chat_message', function() {
			var is_type = 'yes';
			$.ajax({
				url: "update_is_type_status.php",
				method: "POST",
				data: {
					is_type: is_type
				},
				success: function() {

				}
			})
		});

		$(document).on('blur', '.chat_message', function() {
			var is_type = 'no';
			$.ajax({
				url: "update_is_type_status.php",
				method: "POST",
				data: {
					is_type: is_type
				},
				success: function() {

				}
			})
		});

	});
</script> -->