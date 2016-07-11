<?php require_once('../../Connections/conexion.php');

	mysql_select_db($database_conexion, $conexion);
	$_SESSION['indice'] = $_SESSION['indice'] + $_POST['paginado'];
	$loadPosts = $_SESSION['indice'];

	if (isset($_SESSION['shopFilter'])) {
		if ($_SESSION['shopFilter']) {
			$shopFilter = $_SESSION['shopFilter'];
		}else{
			$shopFilter = "ORDER BY id DESC";
		}

		if ($_SESSION['shopSearch'] == '') {
			$consulta = 'WHERE tipo=2';
		}else{
			$consulta = $_SESSION['shopSearch'];
		}
	} else if (isset($_SESSION['shopSearch'])) {
		$consulta = $_SESSION['shopSearch'];
		$shopFilter = 'ORDER BY id DESC';
	}else{
		$consulta = 'WHERE tipo=2';
		$shopFilter = "ORDER BY id DESC";
	}
	
	$query_AllPosts = "SELECT * FROM z_posts $consulta $shopFilter LIMIT $loadPosts,2";
		
	$AllPosts = mysql_query($query_AllPosts, $conexion) or die(mysql_error());
	$row_AllPosts = mysql_fetch_assoc($AllPosts);
	$totalRows_AllPosts = mysql_num_rows($AllPosts);

?>
<?php if ($totalRows_AllPosts!='') { ?>
	<?php do { $imagenesNotice=explode('-:#:-', $row_AllPosts['imagen1']); ?>
		<div class="postContainer" onclick="location.href='<?php echo $urlWeb ?><?php echo $row_AllPosts['urlamigable']; ?>.html'">
			<div class="box" >
				<div class="image">
					<img src="<?php echo $imagenesNotice[0]; ?>"/>
				</div>
				<div class="title"><?php echo $row_AllPosts['titulo']; ?></div>
				<div class="city"><?php echo $row_AllPosts['country']; ?> / <?php echo $row_AllPosts['city']; ?></div>
				<div class="price"><?php echo number_format($row_AllPosts['price'] , 0, ',', '.');?> <?php echo $row_AllPosts['currency']; ?></div>
				<div class="bottom">
					<div class="block"><?php echo $row_AllPosts['km']; ?> km</div>
					<div class="block"><?php echo $row_AllPosts['year']; ?></div>
					<div class="block"><?php echo $row_AllPosts['fuel']; ?></div>
				</div>
			</div>
		</div>
	<?php } while ($row_AllPosts = mysql_fetch_assoc($AllPosts)); ?>
<?php } ?>
<?php mysql_free_result($AllPosts); ?>