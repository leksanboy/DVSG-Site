<?php require_once('../../Connections/conexion.php');
	$_SESSION['indice'] = '';
	$_SESSION['countSearchShop'] = '';

	$_SESSION['shopFilter'] = null;
	unset($_SESSION['shopFilter']);

	$_SESSION['advancedSearchShortByItem'] = null;
	unset($_SESSION['advancedSearchShortByItem']);
	$sortByItem = 0;

	$_SESSION['textShopSearch'] = null;
	unset($_SESSION['textShopSearch']);

	$_SESSION['itemBrand'] = null;
	unset($_SESSION['itemBrand']);

	$_SESSION['shopSearch'] = null;
	unset($_SESSION['shopSearch']);

	$_SESSION['itemPriceFrom'] = null;
	unset($_SESSION['itemPriceFrom']);

	$_SESSION['itemPriceTo'] = null;
	unset($_SESSION['itemPriceTo']);

	$_SESSION['itemYearFrom'] = null;
	unset($_SESSION['itemYearFrom']);

	$_SESSION['itemYearTo'] = null;
	unset($_SESSION['itemYearTo']);

	$_SESSION['itemKmFrom'] = null;
	unset($_SESSION['itemKmFrom']);

	$_SESSION['itemKmTo'] = null;
	unset($_SESSION['itemKmTo']);

	$_SESSION['itemCountry'] = null;
	unset($_SESSION['itemCountry']);

	$_SESSION['itemCity'] = null;
	unset($_SESSION['itemCity']);

	mysql_select_db($database_conexion, $conexion);
	$query_AllPosts = "SELECT * FROM z_posts WHERE tipo=2 ORDER BY id DESC LIMIT 2";
	$AllPosts = mysql_query($query_AllPosts, $conexion) or die(mysql_error());
	$row_AllPosts = mysql_fetch_assoc($AllPosts);
	$totalRows_AllPosts = mysql_num_rows($AllPosts);
?>
<?php do { $imagenesNotice = explode('-:#:-', $row_AllPosts['imagen1']); ?>
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