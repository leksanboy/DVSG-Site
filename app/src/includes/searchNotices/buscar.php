<?php require_once('../../Connections/conexion.php'); 

	$colname_SacarPost = $_POST['cadena'];

	mysql_select_db($database_conexion, $conexion);
	$query_SacarPost = sprintf("SELECT * FROM z_posts WHERE tipo=1 AND titulo LIKE %s ORDER BY id DESC", GetSQLValueString("%" . $colname_SacarPost . "%", "text"));
	$SacarPost = mysql_query($query_SacarPost, $conexion) or die(mysql_error());
	$row_SacarPost = mysql_fetch_assoc($SacarPost);
	$totalRows_SacarPost = mysql_num_rows($SacarPost);

?>
<?php  if ($totalRows_SacarPost == 0) {?>
	<div class="noSearchReusults">No results</div>
<?php } else { ?>

	<div class="showSearchReusults">
		<?php echo $totalRows_SacarPost ?> results found
	</div>

	<div class="showSearchReusultsItems">
		<?php do { $imagenes=explode('-:#:-', $row_SacarPost['imagen1']); ?>

			<?php 
				$idpost = $row_SacarPost['id'];

				mysql_select_db($database_conexion, $conexion);
			    $query_SacarComents = sprintf("SELECT * FROM z_coment WHERE z_coment.post =%s ORDER BY id DESC",$idpost,"int");
			    $SacarComents = mysql_query($query_SacarComents, $conexion) or die(mysql_error());
			    $row_SacarComents = mysql_fetch_assoc($SacarComents);
			    $totalRows_SacarComents = mysql_num_rows($SacarComents);
			?>
			<div class="searchBoxReusltItems" onclick="location.href='<?php echo $urlWeb ?><?php echo $row_SacarPost['urlamigable']; ?>.html'">
				<div class="image">
					<img src="<?php echo $urlWeb ?>imagenes_post/<?php echo $imagenes[0]; ?>"/>
				</div>
				<div class="title"><?php echo $row_SacarPost['titulo']; ?></div>
				
				<!-- <div class="analytics">
                    <?php include("../../images/svg/likes.php");?>
                    <span><?php echo count_likes_post($idpost);?></span>
                    <?php include("../../images/svg/views.php");?>
                    <span><?php echo $row_SacarPost['visitas'];?></span>
                    <?php include("../../images/svg/comments.php");?>
                    <span><?php echo $totalRows_SacarComents;?></span>
                </div> -->
			</div>
		<?php } while ($row_SacarPost = mysql_fetch_assoc($SacarPost)); ?>
	</div>

<?php } ?>

<?php mysql_free_result($SacarPost); ?>
<?php mysql_free_result($SacarComents); ?>