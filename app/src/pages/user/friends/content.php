<div class="searchBox">
	<form>
		<input name="search" onKeyUp="searchButton(1, search.value)" placeholder="Search a friend..."/>
		<div class="searchIcon">
			<?php include("images/svg/search.php");?>
		</div>
		<div class="loaderIcon">
			<?php include("images/svg/spinner.php");?>
		</div>
		<div class="clearIcon" onClick="searchButton(2)">
			<?php include("images/svg/clear.php");?>
		</div>
	</form>
</div>
<div class="searchBoxDataList"></div>

<formbox id="formOne"> <!-- my friends -->
</formbox>

<formbox id="formTwo"> <!-- pending received -->
</formbox>

<formbox id="formThree"> <!-- pending sent -->
</formbox>

<script type="text/javascript">
	var userId = <?php echo $userPageId ?>;

	// MENU TABS
	var tabsInner = $(".papertabs li a");
	var clickedTab;
	tabsInner.click(function() {
	    var content = this.hash.replace('/', '');
	    tabsInner.removeClass("active");
	    $(this).addClass("active");
	    $(".pageFriends").find('formbox').hide();
	    $(content).fadeIn();

	    //Clear search box if click in tab
	    $('.searchBoxDataList').html('');

	    if (content == '#formOne') {
	    	if (clickedTab != 'friends') {
	    		defaultLoad('friends');
	    		clickedTab = 'friends';
	    	}
	    } else if (content == '#formTwo') {
	    	if (clickedTab != 'received') {
	    		defaultLoad('received');
	    		clickedTab = 'received';
	    	}
	    } else if (content == '#formThree') {
	    	if (clickedTab != 'sent') {
	    		defaultLoad('sent');
	    		clickedTab = 'sent';
	    	}
	    }
	});

	//·····> load default
	function defaultLoad(type){
		if (type=='friends') {
			$.ajax({
				type: 'GET',
				url: '<?php echo $urlWeb ?>' + 'pages/user/friends/functions/loadFriends.php',
				data: 'userId=' + userId,
				success: function(response) {
					$('#formOne').fadeIn().html(response);
				}
			});
		} else if (type=='received') {
			$.ajax({
				type: 'GET',
				url: '<?php echo $urlWeb ?>' + 'pages/user/friends/functions/loadReceived.php',
				data: 'userId=' + userId,
				success: function(response) {
					$('#formTwo').fadeIn().html(response);
				}
			});
		} else if (type=='sent') {
			$.ajax({
				type: 'GET',
				url: '<?php echo $urlWeb ?>' + 'pages/user/friends/functions/loadSent.php',
				data: 'userId=' + userId,
				success: function(response) {
					$('#formThree').fadeIn().html(response);
				}
			});
		}
	};
	defaultLoad('friends');

	//·····> Search
	function searchButton(type, value){
		if (type==1 && value.trim().length > 0) { // Search
			$('.searchBox .searchIcon').hide();
			$('.searchBox .loaderIcon').show();

			$.ajax({
				type: 'POST',
				url: '<?php echo $urlWeb ?>' + 'pages/user/friends/search.php',
				data: 'titleValue=' + value,
				success: function(response) {
					setTimeout(function() {
						$('.searchBox .searchIcon').show();
						$('.searchBox .loaderIcon').hide();

						$(".pageFriends").find('formbox').hide();
						$('.searchBoxDataList').show();
						$('.searchBoxDataList').html(response);
					}, 600);
				}
			});
		} else if (type==2 || value.trim().length == 0) { // Reset
			$('.searchBox .searchIcon').hide();
			$('.searchBox .loaderIcon').show();
			$('.searchBox form input').val('');
			$('.searchBoxDataList').hide();

			setTimeout(function() {
				$('.searchBox .searchIcon').show();
				$('.searchBox .loaderIcon').hide();

				defaultLoad(clickedTab);
			}, 600);
		}
	}

	//·····> Delete a friend
	function deleteFriend(type, id, sender, receiver){
		if (type==1) {
			$('#delete'+id).toggle();
		}else if (type==2) {
			$.ajax({
				type: 'POST',
				url: url + 'pages/user/friends/status.php',
				data: 'status=' + 1 + '&sender=' + sender + '&receiver=' + receiver,
				success: function(response){
					$('#friend'+id).fadeOut(300);
				}
			});
		}
	}

	//·····> Status friends
	function statusFriend(status, sender, id){
		$.ajax({
			type: 'POST',
			url: url + 'pages/user/friends/status.php',
			data: 'status=' + status + '&receiver=' + <?php echo $_SESSION['MM_Id'] ?> + '&sender=' + sender,
			success: function(response){
				$('#friend'+id).fadeOut(300);
			}
		});
	}

	//·····> Status friends
	function statusFriendSearch(status, receiver, id){
		$.ajax({
			type: 'POST',
			url: url + 'pages/user/friends/status.php',
			data: 'status=' + status + '&sender=' + <?php echo $_SESSION['MM_Id'] ?> + '&receiver=' + receiver,
			success: function(response){
				if (status == 0) {
					$('.searchBoxDataList #friend'+ id +' .head .buttons button').attr("onclick","statusFriendSearch(1, "+receiver+", "+id+");").html('Remove');
				} else {
					$('.searchBoxDataList #friend'+ id +' .head .buttons button').attr("onclick","statusFriendSearch(0, "+receiver+", "+id+");").html('+ Add');
				}
			}
		});
	}

	//·····> Status friends
	function statusFriendOtherUser(status, receiver, id){
		$.ajax({
			type: 'POST',
			url: url + 'pages/user/friends/status.php',
			data: 'status=' + status + '&sender=' + <?php echo $_SESSION['MM_Id'] ?> + '&receiver=' + receiver,
			success: function(response){
				if (status == 0) {
					$('#formOne #friend'+ id +' .head .buttons button').attr("onclick","statusFriendSearch(1, "+receiver+", "+id+");").html('Remove');
				} else {
					$('#formOne #friend'+ id +' .head .buttons button').attr("onclick","statusFriendSearch(0, "+receiver+", "+id+");").html('+ Add');
				}
			}
		});
	}
</script>