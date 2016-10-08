<?php require_once('../../../../Connections/conexion.php');
	$receiverValue = $_POST['receiverValue'];
	$idSessionUser = $_SESSION['MM_Id'];

	mysql_select_db($database_conexion, $conexion);
	$query_searchReceiverData = sprintf("SELECT u.name as sender_name, 
												u2.name as receiver_name, 
												u.Id as sender, 
												u2.Id as receiver 
											FROM z_friends f 
												INNER JOIN z_users u ON f.sender = u.id 
												INNER JOIN z_users u2 ON f.receiver = u2.id 
											WHERE ((u.id = $idSessionUser AND u2.name LIKE %s) 
												OR (u2.id = $idSessionUser AND u.name LIKE %s)) 
												AND f.status = 1",
	GetSQLValueString("%" . $receiverValue . "%", "text"),
	GetSQLValueString("%" . $receiverValue . "%", "text"));
	$searchReceiverData = mysql_query($query_searchReceiverData, $conexion) or die(mysql_error());
	$row_searchReceiverData = mysql_fetch_assoc($searchReceiverData);
	$totalRows_searchReceiverData = mysql_num_rows($searchReceiverData);
?>

<?php if ($totalRows_searchReceiverData!=0){
	do {
		if ($row_searchReceiverData['sender'] == $_SESSION['MM_Id']){ 
			$receiver = $row_searchReceiverData['receiver'];
		} else if ($row_searchReceiverData['receiver'] == $_SESSION['MM_Id']){ 
			$receiver = $row_searchReceiverData['sender'];
		} ?>

		<div class="receiverBox" onclick="newMessage(4, '<?php echo $receiver ?>', '<?php echo userName($receiver) ?>')">
			<div class="image">
				<img src="<?php echo avatar_user($receiver) ?>"/>
			</div>
			<div class="name">
				<?php echo userName($receiver) ?>
			</div>
		</div>
	<?php } while ($row_searchReceiverData = mysql_fetch_assoc($searchReceiverData));
} else { ?>
	<div class="noData">
		No results
	</div>
<?php } mysql_free_result($searchReceiverData);?>