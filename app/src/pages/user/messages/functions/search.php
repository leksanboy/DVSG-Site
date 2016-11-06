<?php require_once('../../../../Connections/conexion.php');
	$receiverValue = $_POST['receiverValue'];
	$idUser = $_SESSION['MM_Id'];

	mysql_select_db($database_conexion, $conexion);
	$query_searchData = sprintf("SELECT u.name as sender_name, 
										u2.name as receiver_name, 
										u.Id as sender, 
										u2.Id as receiver 
									FROM z_friends f 
										INNER JOIN z_users u ON f.sender = u.id 
										INNER JOIN z_users u2 ON f.receiver = u2.id 
									WHERE ((u.id = $idUser AND u2.name LIKE %s) 
										OR (u2.id = $idUser AND u.name LIKE %s)) 
										AND f.status = 2",
	GetSQLValueString("%" . $receiverValue . "%", "text"),
	GetSQLValueString("%" . $receiverValue . "%", "text"));
	$searchData = mysql_query($query_searchData, $conexion) or die(mysql_error());
	$row_searchData = mysql_fetch_assoc($searchData);
	$totalRows_searchData = mysql_num_rows($searchData);
?>

<?php if ($totalRows_searchData!=0){
	do {
		if ($row_searchData['sender'] == $_SESSION['MM_Id']){ 
			$receiver = $row_searchData['receiver'];
		} else if ($row_searchData['receiver'] == $_SESSION['MM_Id']){ 
			$receiver = $row_searchData['sender'];
		} ?>

		<div class="receiverBox" onclick="newMessage(4, '<?php echo $receiver ?>', '<?php echo userName($receiver) ?>')">
			<div class="image">
				<img src="<?php echo avatar_user($receiver) ?>"/>
			</div>
			<div class="name">
				<?php echo userName($receiver) ?>
			</div>
		</div>
	<?php } while ($row_searchData = mysql_fetch_assoc($searchData));
} ?>
	<div class="receiverBox" onclick="newMessage(4, '<?php echo $idUser ?>', '<?php echo userName($idUser) ?>')">
		<div class="image">
			<img src="<?php echo avatar_user($idUser) ?>"/>
		</div>
		<div class="name">
			<?php echo userName($idUser) ?>
		</div>
	</div>
<?php mysql_free_result($searchData);?>