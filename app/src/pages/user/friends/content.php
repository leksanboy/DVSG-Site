<form id="formOne" onSubmit="return false"> <!-- my friends -->
	<?php if ($totalRows_myFriends == 0){ ?>
		<center>AÚN NO TIENES AMIGOS</center>
	<?php } else { ?>
		<?php do { 
			if ($row_myFriends['sender'] == $_SESSION['MM_Id']){ 
				$receiver = $row_myFriends['receiver'];
			} else if ($row_myFriends['receiver'] == $_SESSION['MM_Id']){ 
				$receiver = $row_myFriends['sender'];
			} ?>

			<div class="friendBox" id="friend<?php echo $row_myFriends['id'] ?>">
				<div class="head">
					<div class="image" onClick="location.href='<?php echo $urlWeb ?>id<?php echo $receiver ?>'">
						<img src="<?php echo avatar_user($receiver) ?>"/>
					</div>
					<div class="name" onClick="location.href='<?php echo $urlWeb ?>id<?php echo $receiver ?>'">
						<?php echo nombre($receiver) ?>
					</div>
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
				</div>
			</div>
		<?php } while ($row_myFriends = mysql_fetch_assoc($myFriends)); ?>
	<?php }?>
</form>

<form id="formTwo" onSubmit="return false"> <!-- pending received -->
	<?php if ($totalRows_pendingReceived == 0){ ?>
		<center>NO TIENES SOLICITUDES PENDIENTES</center>
	<?php } else { ?>
		<?php do { ?>
			<div class="friendBox" id="friend<?php echo $row_pendingReceived['id'] ?>">
				<div class="head">
					<div class="image" onClick="location.href='<?php echo $urlWeb ?>id<?php echo $row_pendingReceived['sender'] ?>'">
						<img src="<?php echo avatar_user($row_pendingReceived['sender']) ?>"/>
					</div>
					<div class="name" onClick="location.href='<?php echo $urlWeb ?>id<?php echo $row_pendingReceived['sender'] ?>'">
						<?php echo nombre($row_pendingReceived['sender']) ?>
					</div>
					<div class="buttons">
						<button onClick="statusFriend(<?php echo $row_pendingReceived['id'] ?>, 2)">CANCEL</button>
						<button onClick="statusFriend(<?php echo $row_pendingReceived['id'] ?>, 1)">ACCEPT</button>
					</div>
				</div>
			</div>
		<?php } while ($row_pendingReceived = mysql_fetch_assoc($pendingReceived)); ?>
	<?php }?>
</form>

<form id="formThree" onSubmit="return false"> <!-- pending sent -->
	<?php if ($totalRows_pendingSent == 0){ ?>
		<center>NO TIENES PETICIONES ENVIADAS</center>
	<?php } else { ?>
		<?php do { ?>
			<div class="friendBox" id="friend<?php echo $row_pendingSent['id'] ?>">
				<div class="head">
					<div class="image" onClick="location.href='<?php echo $urlWeb ?>id<?php echo $row_pendingSent['receiver'] ?>'">
						<img src="<?php echo avatar_user($row_pendingSent['receiver']) ?>"/>
					</div>
					<div class="name" onClick="location.href='<?php echo $urlWeb ?>id<?php echo $row_pendingSent['receiver'] ?>'">
						<?php echo nombre($row_pendingSent['receiver']) ?>
					</div>
					<div class="buttons">
						<button onClick="statusFriend(<?php echo $row_pendingSent['id'] ?>, 2)">CANCEL PETITION</button>
					</div>
				</div>
			</div>
		<?php } while ($row_pendingSent = mysql_fetch_assoc($pendingSent)); ?>
	<?php }?>
</form>

<script type="text/javascript">
	// MENU TABS
	var tabsInner = $(".papertabs li a");
	tabsInner.click(function() {
	    var content = this.hash.replace('/', '');
	    tabsInner.removeClass("active");
	    $(this).addClass("active");
	    $(".pageFriends").find('form').hide();
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

	//·····> Search friends
	function searchFriend(type){
		if (type==1) {
			console.log('searchFriend');
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
</script>