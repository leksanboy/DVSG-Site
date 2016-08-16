<?php require_once('../../../../Connections/conexion.php');
	// Parameters
	$userId = $_SESSION['MM_Id'];
	$photoId = $_POST['photoId'];
	$commentText = $_POST['commentText'];
	$date = time();

	// Insert new one
	$insertSQL = sprintf("INSERT INTO z_photos_comments (user, photo, comment, date) VALUES (%s, %s, %s, %s)",
		GetSQLValueString($userId, "int"),
		GetSQLValueString($photoId, "int"),
		GetSQLValueString($commentText, "text"),
		GetSQLValueString($date, "text"));
	mysql_select_db($database_conexion, $conexion);
	$Result1 = mysql_query($insertSQL, $conexion) or die(mysql_error());

	// Get this new one
	mysql_select_db($database_conexion, $conexion);
	$query_GetNewComment = sprintf("SELECT * FROM z_photos_comments WHERE date = $date");
	$GetNewComment = mysql_query($query_GetNewComment, $conexion) or die(mysql_error());
	$row_GetNewComment = mysql_fetch_assoc($GetNewComment);
	$totalRows_GetNewComment = mysql_num_rows($GetNewComment);
?>
<div class="item" id="comment<?php echo $row_GetNewComment['id'] ?>">
    <div class="avatar">
        <a href="<?php echo $urlWeb ?>id<?php echo $row_GetNewComment['user']; ?>">
            <img src="<?php echo userAvatar($row_GetNewComment['user']); ?>" width="36px" height="36px" style="border-radius: 50%"/>
        </a>
    </div>

    <div class="name">
        <a href="<?php echo $urlWeb ?>id<?php echo $row_GetNewComment['user']; ?>">
            <?php echo userName($row_GetNewComment['user']); ?>
        </a>
        <font size="-2"><?php echo timeAgo($row_GetNewComment['date']);?></font>
    </div>

    <?php if (isset ($_SESSION['MM_Id'])){ ?> 
        <?php  if (($row_GetNewComment['user'] == $_SESSION['MM_Id']) || (rango_admin ($_SESSION['MM_Id']) ==4)) {?>
			<div class="delete" onClick="deleteComment(1, <?php echo $row_GetNewComment['id'] ?>)">
				<?php include("../../../images/svg/clear.php"); ?>
			</div>
			<div class="deleteBoxConfirmation" id="delete<?php echo $row_GetNewComment['id'] ?>">
				<div class="text">Delete this comment?</div>
				<div class="buttons">
					<button onClick="deleteComment(1, <?php echo $row_GetNewComment['id'] ?>)">NO</button>
					<button onClick="deleteComment(2, <?php echo $row_GetNewComment['id'] ?>)">YES</button>
				</div>
			</div>
        <?php }?>
    <?php }?>

    <div class="content">
        <div class="inner">
            <?php echo $row_GetNewComment["comment"];?>
        </div>
    </div>
</div>
<?php mysql_free_result($GetNewComment); ?>