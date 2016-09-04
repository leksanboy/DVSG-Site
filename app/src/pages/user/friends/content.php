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
	<?php if ($totalRows_myFriends == 0){ ?>
		<center>Search a friend</center>
	<?php } else { ?>
		<?php do { 
			if ($row_myFriends['sender'] == $userPageId){ 
				$receiver = $row_myFriends['receiver'];
			} else if ($row_myFriends['receiver'] == $userPageId){ 
				$receiver = $row_myFriends['sender'];
			} ?>

			<div class="friendBox" id="friend<?php echo $row_myFriends['id'] ?>">
				<div class="head">
					<div class="image" onclick="userPage(<?php echo $receiver ?>)">
						<img src="<?php echo userAvatar($receiver) ?>"/>
					</div>
					<div class="name" onclick="userPage(<?php echo $receiver ?>)">
						<?php echo userName($receiver) ?>
					</div>
					<?php if ($row_userData['id'] == $_SESSION['MM_Id']) { ?>
						<div class="delete" onClick="deleteFriend(1, <?php echo $row_myFriends['id'] ?>)">
							<?php include("images/svg/close.php"); ?>
						</div>
						<div class="deleteBoxConfirmation" id="delete<?php echo $row_myFriends['id'] ?>">
							<div class="text">Delete from friends?</div>
							<div class="buttons">
								<button onClick="deleteFriend(1, <?php echo $row_myFriends['id'] ?>)">NO</button>
								<button onClick="deleteFriend(2, <?php echo $row_myFriends['id'] ?>)">YES</button>
							</div>
						</div>
					<?php } ?>
				</div>
			</div>
		<?php } while ($row_myFriends = mysql_fetch_assoc($myFriends)); ?>
	<?php } ?>
</formbox>

<formbox id="formTwo"> <!-- pending received -->
	<?php if ($totalRows_pendingReceived == 0){ ?>
		<center>Have not received requests</center>
	<?php } else { ?>
		<?php do { ?>
			<div class="friendBox" id="friend<?php echo $row_pendingReceived['id'] ?>">
				<div class="head">
					<div class="image" onclick="userPage(<?php echo $row_pendingReceived['sender'] ?>)">
						<img src="<?php echo userAvatar($row_pendingReceived['sender']) ?>"/>
					</div>
					<div class="name" onclick="userPage(<?php echo $row_pendingReceived['sender'] ?>)">
						<?php echo userName($row_pendingReceived['sender']) ?>
					</div>
					<div class="buttons">
						<button onClick="statusFriend(<?php echo $row_pendingReceived['id'] ?>, 2)">CANCEL</button>
						<button onClick="statusFriend(<?php echo $row_pendingReceived['id'] ?>, 1)">ACCEPT</button>
					</div>
				</div>
			</div>
		<?php } while ($row_pendingReceived = mysql_fetch_assoc($pendingReceived)); ?>
	<?php }?>
</formbox>

<formbox id="formThree"> <!-- pending sent -->
	<?php if ($totalRows_pendingSent == 0){ ?>
		<center>Have not sent requests</center>
	<?php } else { ?>
		<?php do { ?>
			<div class="friendBox" id="friend<?php echo $row_pendingSent['id'] ?>">
				<div class="head">
					<div class="image" onclick="userPage(<?php echo $row_pendingSent['receiver'] ?>)">
						<img src="<?php echo userAvatar($row_pendingSent['receiver']) ?>"/>
					</div>
					<div class="name" onclick="userPage(<?php echo $row_pendingSent['receiver'] ?>)">
						<?php echo userName($row_pendingSent['receiver']) ?>
					</div>
					<div class="buttons">
						<button onClick="statusFriend(<?php echo $row_pendingSent['id'] ?>, 2)">CANCEL PETITION</button>
					</div>
				</div>
			</div>
		<?php } while ($row_pendingSent = mysql_fetch_assoc($pendingSent)); ?>
	<?php }?>
</formbox>

<script type="text/javascript">
	// MENU TABS
	var tabsInner = $(".papertabs li a");
	tabsInner.click(function() {
	    var content = this.hash.replace('/', '');
	    tabsInner.removeClass("active");
	    $(this).addClass("active");
	    $(".pageFriends").find('formbox').hide();
	    $(content).fadeIn(600);
	});

	//·····> Delete a friend
	function deleteFriend(type, id){
		if (type==1) {
			$('#delete'+id).toggle();
		}else if (type==2) {
			$.ajax({
				type: 'POST',
				url: url + 'pages/user/friends/delete.php',
				data: 'id=' + id,
				success: function(response){
					$('#friend'+id).fadeOut(300);
				}
			});
		}
	}

	//·····> Status friends
	function statusFriend(id, value){
		$.ajax({
			type: 'POST',
			url: url + 'pages/user/friends/status.php',
			data: 'idPost=' + id + '&statusPost=' + value,
			success: function(response){
				$('#friend'+id).fadeOut(300);
			}
		});
	}

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

				// $(content).fadeIn(600);
			}, 600);
		}
	}
</script>