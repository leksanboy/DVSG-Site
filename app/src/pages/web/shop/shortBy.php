<?php require_once('../../Connections/conexion.php');

	$_SESSION['indice']=0;
	
	if (isset($_SESSION['shopSearch'])) {
		$consulta = $_SESSION['shopSearch'];
	}else{
		$consulta = 'WHERE tipo=2';
	}
	mysql_select_db($database_conexion, $conexion);
	if ($_POST['valor']=='priceDESC') {
		$selectFrom = "ORDER BY price DESC";
		$query_AllPosts = "SELECT * FROM z_posts $consulta ".$selectFrom." LIMIT 2";
		$_SESSION['advancedSearchShortByItem'] = 1;
	}else if ($_POST['valor']=='priceASC') {
		$selectFrom = "ORDER BY price ASC";
		$query_AllPosts = "SELECT * FROM z_posts $consulta ".$selectFrom." LIMIT 2";
		$_SESSION['advancedSearchShortByItem'] = 2;
	}else if ($_POST['valor']=='yearDESC') {
		$selectFrom = "ORDER BY year DESC";
		$query_AllPosts = "SELECT * FROM z_posts $consulta ".$selectFrom." LIMIT 2";
		$_SESSION['advancedSearchShortByItem'] = 3;
	}else if ($_POST['valor']=='yearASC') {
		$selectFrom = "ORDER BY year ASC";
		$query_AllPosts = "SELECT * FROM z_posts $consulta ".$selectFrom." LIMIT 2";
		$_SESSION['advancedSearchShortByItem'] = 4;
	}else if ($_POST['valor']=='kmDESC') {
		$selectFrom = "ORDER BY km DESC";
		$query_AllPosts = "SELECT * FROM z_posts $consulta ".$selectFrom." LIMIT 2";
		$_SESSION['advancedSearchShortByItem'] = 5;
	}else if ($_POST['valor']=='kmASC') {
		$selectFrom = "ORDER BY km ASC";
		$query_AllPosts = "SELECT * FROM z_posts $consulta ".$selectFrom." LIMIT 2";
		$_SESSION['advancedSearchShortByItem'] = 6;
	}
	$AllPosts = mysql_query($query_AllPosts, $conexion) or die(mysql_error());
	$row_AllPosts = mysql_fetch_assoc($AllPosts);
	$totalRows_AllPosts = mysql_num_rows($AllPosts);

	$_SESSION['shopFilter'] = $selectFrom;
?>

<?php if($_SESSION['shopSearch'] !='') {?>
	<div class="results">
		<?php echo $_SESSION['countSearchShop'] ?> Results found
	</div>
<?php } ?>
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

<?php mysql_free_result($AllPosts); ?>