<?php require_once('../../../../Connections/conexion.php');
	$userId = $_SESSION['MM_Id'];
	
	//Inbox
	mysql_select_db($database_conexion, $conexion);
	$query_inboxMessages = sprintf("SELECT * FROM z_messages WHERE receiver=%s ORDER BY id DESC LIMIT 99",$userId,"int");
	$inboxMessages = mysql_query($query_inboxMessages, $conexion) or die(mysql_error());
	$row_inboxMessages = mysql_fetch_assoc($inboxMessages);
	$totalRows_inboxMessages = mysql_num_rows($inboxMessages);
?>
<?php if ($totalRows_inboxMessages == 0){ ?>
	<center>LA BANDEJA DE ENTRADA ESTA VACIA</center>
<?php } else { ?>
	<?php do { ?>
		<div class="messageBox" id="message<?php echo $row_inboxMessages['id'] ?>">
			<div class="head">
				<div class="image" onclick="userPage(<?php echo $row_inboxMessages['sender']; ?>)">
					<img src="<?php echo userAvatar($row_inboxMessages['sender']); ?>"/>
				</div>
				<div class="name" onclick="userPage(<?php echo $row_inboxMessages['sender']; ?>)">
					<?php if ($row_inboxMessages['status'] == 0){ ?>
						<div class="glitch" style="background:#<?php echo $row_userData['primary_color']; ?>">
							<?php echo traducir(48,$_COOKIE['idioma'])?>
						</div>
					<?php } ?>
					<?php  echo userName($row_inboxMessages['sender']); ?>
					<div class="date">
						<?php echo timeAgo($row_inboxMessages['time']); ?>
					</div>
				</div>
				<div class="delete" onClick="deleteMessage(1, '<?php echo $row_inboxMessages['id'] ?>')">
					<?php include("../../../../images/svg/close.php"); ?>
				</div>
				<div class="deleteBoxConfirmation" id="delete<?php echo $row_inboxMessages['id'] ?>">
					<div class="text">Delete this message?</div>
					<div class="buttons">
						<button onClick="deleteMessage(1, <?php echo $row_inboxMessages['id'] ?>)">NO</button>
						<button onClick="deleteMessage(2, <?php echo $row_inboxMessages['id'] ?>)">YES</button>
					</div>
				</div>
			</div>

			<div class="body" onClick="showMessageInbox(1, <?php echo $row_inboxMessages['id'] ?>, <?php echo $row_inboxMessages['sender'] ?>, '<?php echo $row_inboxMessages['message'] ?>', '<?php echo userAvatar($row_inboxMessages['sender']); ?>', '<?php echo userName($row_inboxMessages['sender']); ?>', '<?php echo timeAgo($row_inboxMessages['time']); ?>')">
				<?php echo $row_inboxMessages['message']; ?>
			</div>
		</div>
	<?php } while ($row_inboxMessages = mysql_fetch_assoc($inboxMessages)); ?>
<?php }?>
<?php mysql_free_result($inboxMessages); ?>