SONGS LIST

<!-- TODO FILE UPLOAD INFO: https://jsfiddle.net/Sasa_Rafalsky/1qpzouko/ -->




<script type="text/javascript">
	//·····> Add new song
	function addSong(type){
		if (type==1) { // Open
			$('.messageModalWindow').toggleClass('modalDisplay');
			setTimeout(function() {
				$('.messageModalWindow').toggleClass('showModal');
			}, 100);
			$('body').toggleClass('modalHidden');

			var box = "<div class='head'>\
							UPLOAD SONG\
						</div>\
						<div class='body'>\
							<button>UPLOAD FILE SONG</button>\
						</div>\
						<form onSubmit='return false'>\
							<div class='answerBox'>\
								<textarea name='answer' placeholder='Write the message...'></textarea>\
							</div>\
							<div class='buttons'>\
								<button onClick='addSong(2)'>CLOSE</button>\
								<button onClick='addSong(3)'>SAVE</button>\
							</div>\
						</form>"

			$('.messageModalWindow .box').html(box);
		} else if (type==2) { //Close
			$('.messageModalWindow').toggleClass('modalDisplay');
			setTimeout(function() {
				$('.messageModalWindow').toggleClass('showModal');
			}, 100);
			$('body').toggleClass('modalHidden');
		} else if (type==3) { //Send answer
			$.ajax({ 
				type: 'POST',
				url: url + 'pages/user/messages/inbox/send.php',
				data: 'mensaje=' + content + '&destinatario=' + idSender,
				success: function(response){
					console.log('OK');
					$('.messageModalWindow .box .answerBox').slideToggle();
					setTimeout(function() {
						addSong(2);
					}, 1200);
				}
			});
		}
	}
</script>