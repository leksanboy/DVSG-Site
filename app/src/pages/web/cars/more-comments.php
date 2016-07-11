<?php require_once('../../Connections/conexion.php');

	$_SESSION['comentarios'] = $_SESSION['comentarios'] + $_POST['cantidad'];

	mysql_select_db($database_conexion, $conexion);
	$query_GetMoreComments= sprintf("SELECT * FROM z_cars_comments WHERE page=%s ORDER BY id DESC LIMIT %s, 4",
	GetSQLValueString($_POST['idPage'],"int"),
	GetSQLValueString($_SESSION['comentarios'],"int"));
	$GetMoreComments = mysql_query($query_GetMoreComments, $conexion) or die(mysql_error());
	$row_GetMoreComments = mysql_fetch_assoc($GetMoreComments);
	$totalRows_GetMoreComments = mysql_num_rows($GetMoreComments);

?>
<?php if ($row_GetMoreComments !=""){?>
    <?php do { ?>
        <div class="box" id="comentariosPostId<?php echo $row_GetMoreComments['id'] ?>">
		    <div class="avatar">
		        <a href="<?php echo $urlWeb ?>id<?php echo $row_GetMoreComments['user']; ?>">
		            <img src="<?php echo avatar_user($row_GetMoreComments["user"]); ?>" width="36px" height="36px" style="border-radius: 50%"/>
		        </a>
		    </div>

		    <div class="name">
		        <a href="<?php echo $urlWeb ?>id<?php echo $row_GetMoreComments['user']; ?>">
		            <?php echo nombre($row_GetMoreComments['user']); ?>
		        </a>
		        <font size="-2"><?php echo tiempo_transcurrido($row_GetMoreComments['date']);?></font>
		    </div>

		    <?php if (isset ($_SESSION['MM_Id'])){ ?> 
		        <?php  if (($row_GetMoreComments['user'] == $_SESSION['MM_Id']) || (rango_admin ($_SESSION['MM_Id']) ==4)) {?>
		            <div class="delete" onClick="deleteComment('<?php echo $row_GetMoreComments['id'] ?>');">
		                <?php include("../../images/svg/close.php");?>
		            </div>
		        <?php }?>
		    <?php }?>

		    <div class="content">
		        <div class="inner">
		            <?php echo $row_GetMoreComments["comment"];?>
		        </div>
		    </div>
		</div>
    <?php } while ($row_GetMoreComments = mysql_fetch_assoc($GetMoreComments)); ?>
<?php } ?>
<?php mysql_free_result($GetMoreComments);?>