<formbox id="formOne"> <!-- Inbox -->
	<div class="pageLoader">
		<?php include("images/svg/spinner.php");?>
	</div>
</formbox>

<formbox id="formTwo"> <!-- Outbox -->
</formbox>

<script type="text/javascript">
	// MENU TABS
	var tabsInner = $(".papertabs li a");
	var clickedTab;
	tabsInner.click(function() {
	    var content = this.hash.replace('/', '');
	    tabsInner.removeClass("active");
	    $(this).addClass("active");
	    $(".pageMessages").find('formbox').hide();
	    $(content).fadeIn(600);

	    if (content == '#formOne') {
	    	if (clickedTab != 'inbox') {
	    		defaultLoad('inbox');
	    		clickedTab = 'inbox';
	    	}
	    } else if (content == '#formTwo') {
	    	if (clickedTab != 'outbox') {
	    		defaultLoad('outbox');
	    		clickedTab = 'outbox';
	    	}
	    }
	});

	//·····> load Inbox/Outbox
	function defaultLoad(type){
		if (type=='inbox') {
			$.ajax({
				type: 'GET',
				url: '<?php echo $urlWeb ?>' + 'pages/user/messages/functions/loadInbox.php',
				success: function(response) {
					$('#formOne').html(response);
				}
			});
		} else if (type=='outbox') {
			$.ajax({
				type: 'GET',
				url: '<?php echo $urlWeb ?>' + 'pages/user/messages/functions/loadOutbox.php',
				success: function(response) {
					$('#formTwo').html(response);
				}
			});
		}
	};
	defaultLoad('inbox');

	//·····> Delete message on Inbox
	function deleteMessage(category, type, id){
		if (category == 'inbox') {
			if (type==1) {
				$('#deleteInbox'+id).toggle();
			}else if (type==2) {
				$.ajax({
					type: 'POST',
					url: url + 'pages/user/messages/functions/delete.php',
					data: 'id=' + id + '&category='+ category,
					success: function(response){
						$('.inboxListBox #message'+id).fadeOut(300);
					}
				});
			}
		} else if (category == 'outbox') {
			if (type==1) {
				$('#deleteOutbox'+id).toggle();
			}else if (type==2) {
				$.ajax({
					type: 'POST',
					url: url + 'pages/user/messages/functions/delete.php',
					data: 'id=' + id + '&category='+ category,
					success: function(response){
						$('.outboxListBox #message'+id).fadeOut(300);
					}
				});
			}
		}
	}

	//·····> Show message on Inbox
	function showMessageInbox(type, id, idSender, content, image, name, date){
		if (type==1) { // Open
			$('.modalBox').toggleClass('modalDisplay');
			setTimeout(function() {
				$('.modalBox').toggleClass('showModal');
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
								<button onClick='showMessageInbox(3)' id='answerButton'>ANSWER</button>\
								<button onClick='showMessageInbox(4, " + id + ", " + idSender + " ,answer.value)' id='sendButton' style='display:none'>SEND</button>\
								<button onClick='showMessageInbox(2)'>CLOSE</button>\
							</div>\
						</form>"

			$('.modalBox .box').html(box);


			// ···>Pasar a leido
			var messagesCount = $('.countReceiver').html();
			$.ajax({ 
				type: 'POST',
				url: url + 'pages/user/messages/functions/checkRead.php',
				data: 'idLeido=' + id,
				success: function(response){
					if (messagesCount-1 == 0) {
						$('.countReceiver').hide();
					}else{
						$('.countReceiver').html(messagesCount-1);
					}

					$('#message' + id + ' .head .glitch').hide();
					$('#message' + id + ' .head .delete').show();
					$('#message' + id).removeClass('pendingMessage');
				}
			});
		} else if (type==2) { //Close
			$('.modalBox').toggleClass('modalDisplay');
			setTimeout(function() {
				$('.modalBox').toggleClass('showModal');
			}, 100);
			$('body').toggleClass('modalHidden');
		} else if (type==3) { //Answer
			$('.modalBox .box .answerBox').slideToggle();
			$('#answerButton').hide();
			$('#sendButton').show();
		} else if (type==4) { //Send answer
			if (content.trim() != '') {
				$.ajax({ 
					type: 'POST',
					url: url + 'pages/user/messages/functions/send.php',
					data: 'mensaje=' + content + '&destinatario=' + idSender,
					success: function(response){
						setTimeout(function() {
							showMessageInbox(2);
							$('.actionMessage').addClass('showMessageSettings');
						}, 600);

						setTimeout(function() {
	                		$('.actionMessage').removeClass('showMessageSettings');
	                	}, 5000);
					}
				});
			}
		}
	}

	//·····> Show message on Outbox
	function showMessageOutbox(type, id, idReceiver, content, image, name, date){
		if (type==1) { // Open
			$('.modalBox').toggleClass('modalDisplay');
			setTimeout(function() {
				$('.modalBox').toggleClass('showModal');
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
								<button onClick='showMessageOutbox(3)' id='answerButton'>REPLY</button>\
								<button onClick='showMessageOutbox(4, " + id + ", " + idReceiver + " ,answer.value)' id='sendButton' style='display:none'>SEND</button>\
								<button onClick='showMessageOutbox(2)'>CLOSE</button>\
							</div>\
						</form>"

			$('.modalBox .box').html(box);
		} else if (type==2) { //Close
			$('.modalBox').toggleClass('modalDisplay');
			setTimeout(function() {
				$('.modalBox').toggleClass('showModal');
			}, 100);
			$('body').toggleClass('modalHidden');
		} else if (type==3) { //Answer
			$('.modalBox .box .answerBox').slideToggle();
			$('#answerButton').hide();
			$('#sendButton').show();
		} else if (type==4) { //Send answer
			if (content.trim() != '') {
				$.ajax({ 
					type: 'POST',
					url: url + 'pages/user/messages/functions/send.php',
					data: 'mensaje=' + content + '&destinatario=' + idReceiver,
					success: function(response){
						setTimeout(function() {
							showMessageOutbox(2);
							$('.actionMessage').addClass('showMessageSettings');
						}, 600);

						setTimeout(function() {
	                		$('.actionMessage').removeClass('showMessageSettings');
	                	}, 5000);
					}
				});
			}
		}
	}

	//·····> New message
	var receiverId;
	function newMessage(type, value1, value2){
		if (type==1) { //Open
			$('.modalBox').toggleClass('modalDisplay');
			setTimeout(function() {
				$('.modalBox').toggleClass('showModal');
			}, 100);
			$('body').toggleClass('modalHidden');

			var loaderIcon		= '<?php include('images/svg/spinner.php');?>',
				searchIcon		= '<?php include('images/svg/search.php');?>',
				clearIcon		= '<?php include('images/svg/close.php');?>';

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
						<form onSubmit='return false' class='newMessageBox'>\
							<div class='answerBox'>\
								<textarea name='answer' placeholder='Write the message...'></textarea>\
							</div>\
							<div class='buttons'>\
								<button onClick='newMessage(5, answer.value)'>SEND</button>\
								<button onClick='newMessage(2)'>CLOSE</button>\
							</div>\
						</form>"

			$('.modalBox .box').html(box);
		} else if (type==2) { //Close
			$('.modalBox').toggleClass('modalDisplay');
			setTimeout(function() {
				$('.modalBox').toggleClass('showModal');
			}, 100);
			$('body').toggleClass('modalHidden');
		} else if (type==3) { //Search
			$('.modalBox .box .head .search .searchIcon').hide();
			$('.modalBox .box .head .search .loaderIcon').show();

			$.ajax({
				type: 'POST',
				url: url + 'pages/user/messages/functions/search.php',
				data: 'receiverValue=' + value1,
				success: function(response) {
					setTimeout(function() {
						$('.searchReceiverDataList').show();
						$('.searchReceiverDataList').html(response);

						$('.modalBox .box .head .search .searchIcon').show();
						$('.modalBox .box .head .search .loaderIcon').hide();
					}, 600);
				}
			});
		} else if (type==4) { //Get name
			receiverId = value1;
			$('.searchReceiverDataList').hide();
			$('.modalBox .box .head .search form input').val(value2);
		} else if (type==5) { //Send
			if (value1.trim() != '' && receiverId != undefined) {
				$.ajax({ 
					type: 'POST',
					url: url + 'pages/user/messages/functions/send.php',
					data: 'mensaje=' + value1 + '&destinatario=' + receiverId,
					success: function(response){
						setTimeout(function() {
							newMessage(2);
							$('.actionMessage').addClass('showMessageSettings');
						}, 600);

						setTimeout(function() {
	                		$('.actionMessage').removeClass('showMessageSettings');
	                	}, 5000);
					}
				});
			}
		} else if (type==6) { //Clear input
			$('.modalBox .box .head .search form input').val('');
			$('.searchReceiverDataList').hide();
			receiverId = undefined;
		}
	}
</script>
