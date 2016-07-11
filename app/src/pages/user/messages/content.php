<form id="formOne" onSubmit="return false"> <!-- Inbox -->
	<?php if ($totalRows_inboxMessages == 0){ ?>
		<center>LA BANDEJA DE ENTRADA ESTA VACIA</center>
	<?php } else { ?>
		<?php do { ?>
			<div class="messageBox" id="message<?php echo $row_inboxMessages['id'] ?>">
				<div class="head">
					<div class="image" onClick="location.href='<?php echo $urlWeb ?>id<?php echo id_user($row_inboxMessages['envia']); ?>'">
						<img src="<?php echo avatar_user($row_inboxMessages['envia']); ?>"/>
					</div>
					<div class="name" onClick="location.href='<?php echo $urlWeb ?>id<?php echo id_user($row_inboxMessages['envia']); ?>'">
						<?php  echo nombre($row_inboxMessages['envia']); ?>
						<div class="date">
							<?php echo $row_inboxMessages['fecha']; ?>
						</div>
					</div>
					<div class="delete" onClick="deleteMessage(1, '<?php echo $row_inboxMessages['id'] ?>')">
						<?php include("images/svg/close.php"); ?>
					</div>
					<div class="deleteBoxConfirmation" id="delete<?php echo $row_inboxMessages['id'] ?>">
						<div class="text">Delete this message?</div>
						<div class="buttons">
							<button onClick="deleteMessage(1, <?php echo $row_inboxMessages['id'] ?>)">NO</button>
							<button onClick="deleteMessage(2, <?php echo $row_inboxMessages['id'] ?>)">YES</button>
						</div>
					</div>
					<?php if ($row_inboxMessages['estado'] == 0){ ?>
						<div class="glitch">
							<?php echo traducir(48,$_COOKIE['idioma'])?>
						</div>
					<?php } ?>
				</div>

				<div class="body" onClick="showMessageInbox(1, <?php echo $row_inboxMessages['id'] ?>, <?php echo $row_inboxMessages['envia'] ?>, '<?php echo $row_inboxMessages['mensaje'] ?>', '<?php echo avatar_user($row_inboxMessages['envia']); ?>', '<?php echo nombre($row_inboxMessages['envia']); ?>', '<?php echo $row_inboxMessages['fecha'] ?>')">
					<?php echo $row_inboxMessages['mensaje']; ?>
				</div>
			</div>
		<?php } while ($row_inboxMessages = mysql_fetch_assoc($inboxMessages)); ?>
	<?php }?>
</form>

<form id="formTwo" onSubmit="return false"> <!-- Outbox -->
	<?php if ($totalRows_outboxMessages == 0){ ?>
		<center>LA BANDEJA DE SALIDA ESTA VACIA</center>
	<?php } else { ?>
		<?php do { ?>
			<div class="messageBox" id="message<?php echo $row_outboxMessages['id'] ?>">
				<div class="head">
					<div class="image" onClick="location.href='<?php echo $urlWeb ?>id<?php echo id_user($row_outboxMessages['recibe']); ?>'">
						<img src="<?php echo avatar_user($row_outboxMessages['recibe']); ?>"/>
					</div>
					<div class="imageSender">
						<?php include("images/svg/send-to.php"); ?>
						<div class="image">
							<img src="<?php echo avatar_user($row_outboxMessages['envia']); ?>"/>
						</div>
					</div>
					<div class="name" onClick="location.href='<?php echo $urlWeb ?>id<?php echo id_user($row_outboxMessages['recibe']); ?>'">
						<?php  echo nombre($row_outboxMessages['recibe']); ?>
						<div class="date">
							<?php echo $row_outboxMessages['fecha']; ?>
						</div>
					</div>
					<div class="delete" onClick="deleteMessage(1, '<?php echo $row_outboxMessages['id'] ?>')">
						<?php include("images/svg/close.php"); ?>
					</div>
					<div class="deleteBoxConfirmation" id="delete<?php echo $row_outboxMessages['id'] ?>">
						<div class="text">Delete this message?</div>
						<div class="buttons">
							<button onClick="deleteMessage(1, <?php echo $row_outboxMessages['id'] ?>)">NO</button>
							<button onClick="deleteMessage(2, <?php echo $row_outboxMessages['id'] ?>)">YES</button>
						</div>
					</div>
				</div>

				<div class="body" onClick="showMessageOutbox(1, <?php echo $row_outboxMessages['id'] ?>, <?php echo $row_outboxMessages['recibe'] ?>, '<?php echo $row_outboxMessages['mensaje'] ?>', '<?php echo avatar_user($row_outboxMessages['envia']); ?>', '<?php echo nombre($row_outboxMessages['envia']); ?>', '<?php echo $row_outboxMessages['fecha'] ?>')">
					<?php echo $row_outboxMessages['mensaje']; ?>
				</div>
			</div>
		<?php } while ($row_outboxMessages = mysql_fetch_assoc($outboxMessages)); ?>
	<?php }?>
</form>

<script type="text/javascript">
	// MENU TABS
	var tabsInner = $(".papertabs li a");
	tabsInner.click(function() {
	    var content = this.hash.replace('/', '');
	    tabsInner.removeClass("active");
	    $(this).addClass("active");
	    $(".pageMessages").find('form').hide();
	    $(content).fadeIn(600);
	});

	//·····> Delete message on Inbox
	function deleteMessage(type, id){
		if (type==1) {
			$('#delete'+id).toggle();
		}else if (type==2) {
			console.log('DELETED', id);

			$.ajax({
				type: 'POST',
				url: url + 'pages/user/messages/inbox/delete.php',
				data: 'id=' + id,
				success: function(response){
					$('#message'+id).fadeOut(300);
				}
			});
		}
	}

	//·····> Show message on Inbox
	function showMessageInbox(type, id, idSender, content, image, name, date){
		if (type==1) { // Open
			$('.messageModalWindow').toggleClass('modalDisplay');
			setTimeout(function() {
				$('.messageModalWindow').toggleClass('showModal');
			}, 100);
			$('body').toggleClass('modalHidden');

			var box = "<div class='head'>\
							<div class='image'>\
								<img src='"+ image +"'/>\
							</div>\
							<div class='name'>\
								"+ name +"\
								<div class='date'>\
									"+ date +"\
								</div>\
							</div>\
						</div>\
						<div class='body'>\
							"+ content +"\
						</div>\
						<form onSubmit='return false'>\
							<div class='answerBox'>\
								<textarea name='answer' placeholder='Write the message...'></textarea>\
							</div>\
							<div class='buttons'>\
								<button onClick='showMessageInbox(2)'>CERRAR</button>\
								<button onClick='showMessageInbox(3)'>RESPONDER</button>\
								<button onClick='showMessageInbox(4, " + id + ", " + idSender + " ,answer.value)' id='sendButton'>ENVIAR</button>\
							</div>\
						</form>"

			$('.messageModalWindow .box').html(box);

			// ···>Pasar a leido
			$.ajax({ 
				type: 'POST',
				url: url + 'pages/user/messages/inbox/checkRead.php',
				data: 'idLeido=' + id,
				success: function(response){
					$('#message' + id + ' .head .glitch').hide();
				}
			});
		} else if (type==2) { //Close
			$('.messageModalWindow').toggleClass('modalDisplay');
			setTimeout(function() {
				$('.messageModalWindow').toggleClass('showModal');
			}, 100);
			$('body').toggleClass('modalHidden');
		} else if (type==3) { //Answer
			$('.messageModalWindow .box .answerBox').slideToggle();
			$('#sendButton').toggle();
		} else if (type==4) { //Send answer
			$.ajax({ 
				type: 'POST',
				url: url + 'pages/user/messages/inbox/send.php',
				data: 'mensaje=' + content + '&destinatario=' + idSender,
				success: function(response){
					console.log('OK');
					$('.messageModalWindow .box .answerBox').slideToggle();
					setTimeout(function() {
						showMessageInbox(2);
					}, 1200);
				}
			});
		}
	}

	//·····> Show message on Outbox
	function showMessageOutbox(type, id, idReceiver, content, image, name, date){
		if (type==1) { // Open
			$('.messageModalWindow').toggleClass('modalDisplay');
			setTimeout(function() {
				$('.messageModalWindow').toggleClass('showModal');
			}, 100);
			$('body').toggleClass('modalHidden');

			var box = "<div class='head'>\
							<div class='image'>\
								<img src='"+ image +"'/>\
							</div>\
							<div class='name'>\
								"+ name +"\
								<div class='date'>\
									"+ date +"\
								</div>\
							</div>\
						</div>\
						<div class='body'>\
							"+ content +"\
						</div>\
						<form onSubmit='return false'>\
							<div class='answerBox'>\
								<textarea name='answer' placeholder='Write the message...'></textarea>\
							</div>\
							<div class='buttons'>\
								<button onClick='showMessageOutbox(2)'>CERRAR</button>\
								<button onClick='showMessageOutbox(3)'>NUEVO</button>\
								<button onClick='showMessageOutbox(4, " + id + ", " + idReceiver + " ,answer.value)' id='sendButton'>ENVIAR</button>\
							</div>\
						</form>"

			$('.messageModalWindow .box').html(box);
		} else if (type==2) { //Close
			$('.messageModalWindow').toggleClass('modalDisplay');
			setTimeout(function() {
				$('.messageModalWindow').toggleClass('showModal');
			}, 100);
			$('body').toggleClass('modalHidden');
		} else if (type==3) { //Answer
			$('.messageModalWindow .box .answerBox').slideToggle();
			$('#sendButton').toggle();
		} else if (type==4) { //Send answer
			$.ajax({ 
				type: 'POST',
				url: url + 'pages/user/messages/inbox/send.php',
				data: 'mensaje=' + content + '&destinatario=' + idReceiver,
				success: function(response){
					console.log('OK');
					$('.messageModalWindow .box .answerBox').slideToggle();
					setTimeout(function() {
						showMessageOutbox(2);
					}, 1200);
				}
			});
		}
	}

	//·····> New message
	var receiverId;
	function newMessage(type, value1, value2){
		if (type==1) { //Open
			$('.messageModalWindow').toggleClass('modalDisplay');
			setTimeout(function() {
				$('.messageModalWindow').toggleClass('showModal');
			}, 100);
			$('body').toggleClass('modalHidden');

			var searchIcon = '<svg viewBox="0 0 48 48"><path d="M31 28h-1.59l-.55-.55C30.82 25.18 32 22.23 32 19c0-7.18-5.82-13-13-13S6 11.82 6 19s5.82 13 13 13c3.23 0 6.18-1.18 8.45-3.13l.55.55V31l10 9.98L40.98 38 31 28zm-12 0c-4.97 0-9-4.03-9-9s4.03-9 9-9 9 4.03 9 9-4.03 9-9 9z"/></svg>';
			var loaderIcon = '<svg viewBox="0 0 28 28"><g class="qp-circular-loader"><path class="qp-circular-loader-path" fill="none" d="M 14,1.5 A 12.5,12.5 0 1 1 1.5,14" stroke-linecap="round" /></g></svg>';
			var clearIcon = '<svg viewBox="0 0 48 48"><path d="M38 12.83L35.17 10 24 21.17 12.83 10 10 12.83 21.17 24 10 35.17 12.83 38 24 26.83 35.17 38 38 35.17 26.83 24z"/></svg>';

			var box = "<div class='head'>\
							<div class='search'>\
								<form>\
									<div class='searchIcon'>"+ searchIcon +"</div>\
									<div class='loaderIcon'>"+ loaderIcon +"</div>\
									<input name='search' onKeyUp='newMessage(3, search.value)' onFocus='newMessage(3, search.value)' placeholder='Search a friend...'/>\
									<div class='clearIcon' onClick='newMessage(6)'>"+ clearIcon +"</div>\
								</form>\
							</div>\
							<div class='searchReceiverDataList'></div>\
						</div>\
						<form onSubmit='return false' class='newMessageForm'>\
							<div class='answerBox'>\
								<textarea name='answer' placeholder='Write the message...'></textarea>\
							</div>\
							<div class='buttons'>\
								<button onClick='newMessage(2)'>CERRAR</button>\
								<button onClick='newMessage(5, answer.value)'>ENVIAR</button>\
							</div>\
						</form>"

			$('.messageModalWindow .box').html(box);
		} else if (type==2) { //Close
			$('.messageModalWindow').toggleClass('modalDisplay');
			setTimeout(function() {
				$('.messageModalWindow').toggleClass('showModal');
			}, 100);
			$('body').toggleClass('modalHidden');
		} else if (type==3) { //Search
			$('.messageModalWindow .box .head .search .searchIcon').hide();
			$('.messageModalWindow .box .head .search .loaderIcon').show();

			$.ajax({
				type: 'POST',
				url: url + 'pages/user/messages/inbox/searchReceiver.php',
				data: 'receiverValue=' + value1,
				success: function(response) {
					setTimeout(function() {
						$('.searchReceiverDataList').show();
						$('.searchReceiverDataList').html(response);

						$('.messageModalWindow .box .head .search .searchIcon').show();
						$('.messageModalWindow .box .head .search .loaderIcon').hide();
					}, 600);
				}
			});
		} else if (type==4) { //Get name
			receiverId = value1;
			$('.searchReceiverDataList').hide();
			$('.messageModalWindow .box .head .search form input').val(value2);
		} else if (type==5) { //Send
			$.ajax({ 
				type: 'POST',
				url: url + 'pages/user/messages/inbox/send.php',
				data: 'mensaje=' + value1 + '&destinatario=' + receiverId,
				success: function(response){
					$('.messageModalWindow .box .answerBox').slideToggle();
					setTimeout(function() {
						newMessage(2);
					}, 1200);
				}
			});
		} else if (type==6) { //Clear input
			$('.messageModalWindow .box .head .search form input').val('');
			$('.searchReceiverDataList').hide();
		}
	}
</script>
