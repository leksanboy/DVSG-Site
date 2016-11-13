<?php require_once('../../../../Connections/conexion.php');
	$userId = $_SESSION['MM_Id'];
	
	//Outbox
	mysql_select_db($database_conexion, $conexion);
	$query_outboxMessages = sprintf("SELECT * FROM z_messages WHERE sender=%s AND is_deleted_sender = 0 ORDER BY id DESC LIMIT 99",$userId,"int");
	$outboxMessages = mysql_query($query_outboxMessages, $conexion) or die(mysql_error());
	$row_outboxMessages = mysql_fetch_assoc($outboxMessages);
	$totalRows_outboxMessages = mysql_num_rows($outboxMessages);
?>
<?php if ($totalRows_outboxMessages == 0){ ?>
	<center>LA BANDEJA DE SALIDA ESTA VACIA</center>
<?php } else { ?>
	<ul class="outboxListBox">
		<?php do { ?>
			<li id="message<?php echo $row_outboxMessages['id'] ?>">
				<div class="head">
					<div class="image" onclick="userPage(<?php echo $row_outboxMessages['receiver']; ?>)">
						<img src="<?php echo userAvatar($row_outboxMessages['receiver']); ?>"/>
					</div>
					<div class="imageSender">
						<?php include("../../../../images/svg/send-to.php"); ?>
						<div class="image">
							<img src="<?php echo userAvatar($row_outboxMessages['sender']); ?>"/>
						</div>
					</div>
					<div class="name" onclick="userPage(<?php echo $row_outboxMessages['receiver']; ?>)">
						<?php  echo userName($row_outboxMessages['receiver']); ?>
						<div class="date">
							<?php echo timeAgo($row_outboxMessages['time']); ?>
						</div>
					</div>
					<div class="delete" onClick="deleteMessage('outbox', 1, <?php echo $row_outboxMessages['id'] ?>)">
						<?php include("../../../../images/svg/close.php"); ?>
					</div>
					<div class="deleteBoxConfirmation" id="deleteOutbox<?php echo $row_outboxMessages['id'] ?>">
						<div class="text">Delete this message?</div>
						<div class="confirmation">
							<button onClick="deleteMessage('outbox', 1, <?php echo $row_outboxMessages['id'] ?>)">NO</button>
							<button onClick="deleteMessage('outbox', 2, <?php echo $row_outboxMessages['id'] ?>)">YES</button>
						</div>
					</div>
				</div>
				<div class="body" onClick="showMessageOutbox(1, <?php echo $row_outboxMessages['id'] ?>, <?php echo $row_outboxMessages['receiver'] ?>, '<?php echo $row_outboxMessages['message'] ?>', '<?php echo userAvatar($row_outboxMessages['sender']); ?>', '<?php echo userName($row_outboxMessages['sender']); ?>', '<?php echo timeAgo($row_outboxMessages['time']); ?>')">
					<?php echo $row_outboxMessages['message']; ?>
				</div>
			</li>
		<?php } while ($row_outboxMessages = mysql_fetch_assoc($outboxMessages)); ?>
	</ul>
<?php }?>
<?php mysql_free_result($outboxMessages); ?>