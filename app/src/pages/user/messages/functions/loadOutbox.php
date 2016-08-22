<?php require_once('../../../../Connections/conexion.php');
	$userId = $_SESSION['MM_Id'];
	
	//Outbox
	mysql_select_db($database_conexion, $conexion);
	$query_outboxMessages = sprintf("SELECT * FROM z_messages WHERE sender=%s ORDER BY id DESC LIMIT 99",$userId,"int");
	$outboxMessages = mysql_query($query_outboxMessages, $conexion) or die(mysql_error());
	$row_outboxMessages = mysql_fetch_assoc($outboxMessages);
	$totalRows_outboxMessages = mysql_num_rows($outboxMessages);
?>
<?php if ($totalRows_outboxMessages == 0){ ?>
	<center>LA BANDEJA DE SALIDA ESTA VACIA</center>
<?php } else { ?>
	<?php do { ?>
		<div class="messageBox" id="message<?php echo $row_outboxMessages['id'] ?>">
			<div class="head">
				<div class="image" onClick="location.href='<?php echo $urlWeb ?>id<?php echo userId($row_outboxMessages['receiver']); ?>'">
					<img src="<?php echo userAvatar($row_outboxMessages['receiver']); ?>"/>
				</div>
				<div class="imageSender">
					<?php include("../../../../images/svg/send-to.php"); ?>
					<div class="image">
						<img src="<?php echo userAvatar($row_outboxMessages['sender']); ?>"/>
					</div>
				</div>
				<div class="name" onClick="location.href='<?php echo $urlWeb ?>id<?php echo userId($row_outboxMessages['receiver']); ?>'">
					<?php  echo userName($row_outboxMessages['receiver']); ?>
					<div class="date">
						<?php echo timeAgo($row_outboxMessages['time']); ?>
					</div>
				</div>
				<div class="delete" onClick="deleteMessage(1, '<?php echo $row_outboxMessages['id'] ?>')">
					<?php include("../../../../images/svg/close.php"); ?>
				</div>
				<div class="deleteBoxConfirmation" id="delete<?php echo $row_outboxMessages['id'] ?>">
					<div class="text">Delete this message?</div>
					<div class="buttons">
						<button onClick="deleteMessage(1, <?php echo $row_outboxMessages['id'] ?>)">NO</button>
						<button onClick="deleteMessage(2, <?php echo $row_outboxMessages['id'] ?>)">YES</button>
					</div>
				</div>
			</div>

			<div class="body" onClick="showMessageOutbox(1, <?php echo $row_outboxMessages['id'] ?>, <?php echo $row_outboxMessages['receiver'] ?>, '<?php echo $row_outboxMessages['message'] ?>', '<?php echo userAvatar($row_outboxMessages['sender']); ?>', '<?php echo userName($row_outboxMessages['sender']); ?>', '<?php echo timeAgo($row_outboxMessages['time']); ?>')">
				<?php echo $row_outboxMessages['message']; ?>
			</div>
		</div>
	<?php } while ($row_outboxMessages = mysql_fetch_assoc($outboxMessages)); ?>
<?php }?>
<?php mysql_free_result($outboxMessages); ?>