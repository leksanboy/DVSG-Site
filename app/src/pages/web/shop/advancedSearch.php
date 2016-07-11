<?php require_once('../../Connections/conexion.php'); 

	$_SESSION['indice']=0;
	$consulta ='';

	//Vaciar la sesion de sort by(del filtro);
	if (isset($_SESSION['shopFilter'])) {
		$_SESSION['shopFilter'] = null;
		unset($_SESSION['shopFilter']);
	}
	if (isset($_SESSION['advancedSearchShortByItem'])) {
		$_SESSION['advancedSearchShortByItem'] = null;
		unset($_SESSION['advancedSearchShortByItem']);
		$sortByItem = 0;
	}

	//Filtros a Aplicar con validacion de campo vacio
	if(isset($_POST['selectBrand']) && $_POST['selectBrand']!='') {
		$consulta.="brand =".GetSQLValueString($_POST['selectBrand'], "text").' AND ';
		$_SESSION['itemBrand'] = $_POST['selectBrand'];
	}else{
		$_SESSION['itemBrand'] = 'selected';
	}
	if(isset($_POST['selectPriceFrom']) && $_POST['selectPriceFrom']!='') {
		$consulta.="price >=".GetSQLValueString($_POST['selectPriceFrom'], "text").' AND ';
		$_SESSION['itemPriceFrom'] = $_POST['selectPriceFrom'];
	}else{
		$_SESSION['itemPriceFrom'] = 'selected';
	}
	if(isset($_POST['selectPriceTo']) && $_POST['selectPriceTo']!='') {
		$consulta.="price <=".GetSQLValueString($_POST['selectPriceTo'], "text").' AND ';
		$_SESSION['itemPriceTo'] = $_POST['selectPriceTo'];
	}else{
		$_SESSION['itemPriceTo'] = 'selected';
	}
	if(isset($_POST['selectYearFrom']) && $_POST['selectYearFrom']!='') {
		$consulta.="year >=".GetSQLValueString($_POST['selectYearFrom'], "text").' AND ';
		$_SESSION['itemYearFrom'] = $_POST['selectYearFrom'];
	}else{
		$_SESSION['itemYearFrom'] = 'selected';
	}
	if(isset($_POST['selectYearTo']) && $_POST['selectYearTo']!='') {
		$consulta.="year <=".GetSQLValueString($_POST['selectYearTo'], "text").' AND ';
		$_SESSION['itemYearTo'] = $_POST['selectYearTo'];
	}else{
		$_SESSION['itemYearTo'] = 'selected';
	}
	if(isset($_POST['selectKmFrom']) && $_POST['selectKmFrom']!='') {
		$consulta.="km >=".GetSQLValueString($_POST['selectKmFrom'], "text").' AND ';
		$_SESSION['itemKmFrom'] = $_POST['selectKmFrom'];
	}else{
		$_SESSION['itemKmFrom'] = 'selected';
	}
	if(isset($_POST['selectKmTo']) && $_POST['selectKmTo']!='') {
		$consulta.="km <=".GetSQLValueString($_POST['selectKmTo'], "text").' AND ';
		$_SESSION['itemKmTo'] = $_POST['selectKmTo'];
	}else{
		$_SESSION['itemKmTo'] = 'selected';
	}
	if(isset($_POST['selectCountry']) && $_POST['selectCountry']!='') {
		$consulta.="countryVal =".GetSQLValueString($_POST['selectCountry'], "text").' AND ';
		$_SESSION['itemCountry'] = $_POST['selectCountry'];
	}else{
		$_SESSION['itemCountry'] = '';
	}
	if(isset($_POST['selectCity']) && $_POST['selectCity']!='') {
		$consulta.="cityVal =".GetSQLValueString($_POST['selectCity'], "text").' AND ';
		$_SESSION['itemCity'] = $_POST['selectCity'];
	}else{
		$_SESSION['itemCity'] = '';
	}
	if(isset($_POST['searchShop']) && $_POST['searchShop'] !=''){
		$partes = explode(" ", $_POST['searchShop']);
		$cantidad = count($partes);

		if ($cantidad>1){
			for ($i=0; $i < $cantidad; $i++) {
				$consulta.="titulo LIKE ".GetSQLValueString("%" . $partes[$i] . "%", "text").' AND ';
			}
		} else {
			$consulta.="titulo LIKE ".GetSQLValueString("%" . $partes[0] . "%", "text").' AND ';
		}
	}

	$consulta = substr ($consulta, 0, strlen($consulta) - 5);

	if ($consulta != '') {
		$consulta = 'WHERE tipo=2 AND '.$consulta;
	}else{
		$consulta = 'WHERE tipo=2';
	}

	//Datos
	mysql_select_db($database_conexion, $conexion);
	$query_AllPosts = "SELECT * FROM z_posts $consulta LIMIT 2";
	$AllPosts = mysql_query($query_AllPosts, $conexion) or die(mysql_error());
	$row_AllPosts = mysql_fetch_assoc($AllPosts);
	$totalRows_AllPosts = mysql_num_rows($AllPosts);

	//Count
	mysql_select_db($database_conexion, $conexion);
	$query_AllPostsCount = "SELECT * FROM z_posts $consulta";
	$AllPostsCount = mysql_query($query_AllPostsCount, $conexion) or die(mysql_error());
	$row_AllPostsCount = mysql_fetch_assoc($AllPostsCount);
	$totalRows_AllPostsCount = mysql_num_rows($AllPostsCount);

	$_SESSION['countSearchShop'] = $totalRows_AllPostsCount;
	$_SESSION['shopSearch'] = $consulta;
	$_SESSION['textShopSearch'] = $_POST['searchShop'];
?>
<?php if ($totalRows_AllPosts !='') { ?>
	<?php if($_SESSION['shopSearch'] !='') {?>
		<div class="results">
			<?php echo $_SESSION['countSearchShop'] ?> Results found
		</div>
	<?php } ?>
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
	<?php } while ($row_AllPosts = mysql_fetch_assoc($AllPosts));
} else{ ?>
	<div class="errorContainer">
		<div class="circleOne">
			<div class="text">404</div>
			<div class="subText">not results</div>
		</div>
	</div>
<?php }?>
<?php mysql_free_result($AllPosts); ?>
<?php mysql_free_result($AllPostsCount); ?>