<?php require('Connections/conexion.php');

	$_SESSION['comentarios']=0; //Los comentarios empiezan a contar en "Cero" --> more-comments.php
	$iddelmodel = UrlAmigableModel($_GET['modelo']);

	//Get Model data
	mysql_select_db($database_conexion, $conexion);
	$query_GetModel = sprintf("SELECT * FROM z_cars_models WHERE id=%s",$iddelmodel,"int");
	$GetModel = mysql_query($query_GetModel, $conexion) or die(mysql_error());
	$row_GetModel = mysql_fetch_assoc($GetModel);
	$totalRows_GetModel = mysql_num_rows($GetModel);

	//Get Model -> Brand Data
	$carmodel = GetCarsModelBrand($iddelmodel);

	mysql_select_db($database_conexion, $conexion);
	$query_GetBrand = sprintf("SELECT * FROM z_cars_brands WHERE brand=%s",
	GetSQLValueString($carmodel, "text"));
	$GetBrand = mysql_query($query_GetBrand, $conexion) or die(mysql_error());
	$row_GetBrand = mysql_fetch_assoc($GetBrand);
	$totalRows_GetBrand = mysql_num_rows($GetBrand);

	//Get images
	if($row_GetModel['images'] != ''){
        $imagenes=explode('-:#:-', $row_GetModel['images']);
        $cantidadImagenes=count($imagenes);  
    }

    //Views
    $updateSQL = sprintf("UPDATE z_cars_models SET views= views +1 WHERE id=%s",
    GetSQLValueString($iddelmodel, "int"));
    mysql_select_db($database_conexion, $conexion);
    $Result1 = mysql_query($updateSQL, $conexion) or die(mysql_error());

    //Comments Count
    mysql_select_db($database_conexion, $conexion);
    $query_GetCommentsCount= sprintf("SELECT * FROM z_cars_comments WHERE z_cars_comments.page =%s ORDER BY id",$iddelmodel,"int");
    $GetCommentsCount = mysql_query($query_GetCommentsCount, $conexion) or die(mysql_error());
    $row_GetCommentsCount = mysql_fetch_assoc($GetCommentsCount);
    $totalRows_GetCommentsCount = mysql_num_rows($GetCommentsCount);

    //Comments
    mysql_select_db($database_conexion, $conexion);
    $query_GetComments= sprintf("SELECT * FROM z_cars_comments WHERE z_cars_comments.page =%s ORDER BY id DESC LIMIT 4",$iddelmodel,"int");
    $GetComments = mysql_query($query_GetComments, $conexion) or die(mysql_error());
    $row_GetComments = mysql_fetch_assoc($GetComments);
    $totalRows_GetComments = mysql_num_rows($GetComments);

    $cometariosmostrar=$totalRows_GetCommentsCount-4;
?>
<!DOCTYPE html>
	<?php include_once("includes/fuckoff.php"); ?>
	<head>
		<meta charset="utf-8">
		<meta name="Author" content="Diesel vs. Gasoline" />
		<meta http-equiv="X-UA-Compatible" content="IE=EmulateIE7, IE=9" />
		<title>DVSG - <?php echo $row_GetModel['brand']; ?> <?php echo $row_GetModel['model']; ?></title>
		<?php include_once("includes/favicons.php"); ?>
	 	<link rel="stylesheet" type="text/css" href="<?php echo $urlWeb ?>styles/desktop.min.css"/>
	 	<link rel="stylesheet" type="text/css" href="<?php echo $urlWeb ?>styles/mobile.min.css"/>
	 	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
	</head>
	<body>
		<?php include_once("includes/analyticstracking.php");?>
		<?php include_once("includes/browsehappy.php");?>
		<div class="innerBody">
			<?php include_once("includes/leftBlockRight.php"); ?>
			<div class="header">
				<?php include_once("includes/headerEffect.php");?>
				<?php include_once("includes/headerButtons.php");?>
				<?php include_once("includes/searchNotices/buscador.php");?>
				<nav id="navItemTabs">
					<ul class="papertabs">
						<li>
							<a onclick="return createTimedLink(this, myFunction, 600);" href="<?php echo $urlWeb ?>brands/<?php echo $row_GetBrand['url']; ?>" class="active">
								<?php include_once("images/svg/back.php");?>
								Go Back
								<span class="paperripple">
									<span class="circleNav"</span>
								</span>
							</a>
						</li>
						<li>
							<a onClick="clickThePage(1)">
								HOME
								<span class="paperripple">
									<span class="circleNav"</span>
								</span>
							</a>
						</li>
						<li>
							<a onClick="clickThePage(2)">
								CARS
								<span class="paperripple">
									<span class="circleNav"</span>
								</span>
							</a>
						</li>
						<li>
							<a onClick="clickThePage(3)">
								SHOP
								<span class="paperripple">
									<span class="circleNav"</span>
								</span>
							</a>
						</li>
						<li>
							<a onClick="clickThePage(4)">
								VERSUS
								<span class="paperripple">
									<span class="circleNav"</span>
								</span>
							</a>
						</li>
						  <li>
							<a onClick="clickThePage(5)">
								BLOG
								<span class="paperripple">
									<span class="circleNav"</span>
								</span>
							</a>
						</li>
						<li>
							<a onClick="clickThePage(6)">
								SOCIAL NETWORK
								<span class="paperripple">
									<span class="circleNav"</span>
								</span>
							</a>
						</li>
					</ul>
				</nav>
			</div>
			<div class="innerBodyContent">
				<div class="pageBody pageCarModel">
					<div class="swiperModel" id="swiper-container">
						<div class="swiper-wrapper">
                            <?php if ($row_GetModel['images'] !=''){?> 
                                <?php for ($i=0; $i < $cantidadImagenes; $i++){ $nombreImagenes=$imagenes[$i]; ?>
                                    <div class="swiper-slide">
                                        <img src="<?php echo $nombreImagenes ?>"/>
                                    </div>
                                <?php } ?>
                            <?php }?>
                        </div>
						<div class="swiper-arrow-left"></div>
						<div class="swiper-arrow-right"></div>
						<center>
							<div class="pagination" id="pagination"></div>
						</center>
					</div>

					<div class="brand">
						<div class="logo">
							<img src="<?php echo $row_GetBrand['image']; ?>">
						</div>
						<div class="title">
							<?php echo $row_GetModel['brand']; ?>
							<div class="subtitle">
								<?php echo $row_GetModel['model']; ?>
							</div>
						</div>
					</div>

					<div class="content">
						<?php if ($row_GetModel['description']!='' || $row_GetModel['video']!='') { ?>
							<div class="dataBox">
								<?php if ($row_GetModel['description']!='') { ?>
									<div class="description">
										<?php echo $row_GetModel['description']; ?>
									</div>
								<?php } ?>
								<?php if ($row_GetModel['video']!='') { ?>
									<div class="video">
										<iframe src="<?php echo $row_GetModel['video']; ?>" frameborder="0" allowfullscreen></iframe>
									</div>
								<?php } ?>
							</div>
						<?php } ?>
						<div class="dataBox">
							<div class="title">Performance</div>
							<ul>
								<li>
									<span>Maximum speed (km/h)</span>
									<span>285 km/h</span>
								</li>
								<li>
									<span>Acceleration 0-100 km (s)</span>
									<span>3.1 s</span>
								</li>
								<li>
									<span>Acceleration 0-1000 m (s)</span>
									<span>8.4 s</span>
								</li>
								<li>
									<span>Urban consumption (L/100 km)</span>
									<span>11.7 L</span>
								</li>
								<li>
									<span>Extra-urban Fuel consumption (L/100km)</span>
									<span>10.2 L</span>
								</li>
								<li>
									<span>Average consumption (L/100km)</span>
									<span>10.8 L</span>
								</li>
								<li>
									<span>CO2 emissions (g/km)</span>
									<span>1.9 g</span>
								</li>

							</ul>
						</div>
						<div class="dataBox">
							<div class="title">Dimensions</div>
							<ul>
								<li>
									<span>Body type</span>
									<span>Coupe</span>
								</li>
								<li>
									<span>Number of doors</span>
									<span>5</span>
								</li>
								<li>
									<span>Weight (kg)</span>
									<span>1 253 kg</span>
								</li>
								<li>
									<span>Tank Volume (L)</span>
									<span>62 L</span>
								</li>
								<li>
									<span>Boot Volume (L)</span>
									<span>384 L</span>
								</li>
								<li>
									<span>Number of places</span>
									<span>4</span>
								</li>
								<li>
									<span>Wheelbase / Front track - rear (cm)</span>
									<span></span>
								</li>
							</ul>

							<div class="dimensions">
								<div>
									<img src="<?php echo $urlWeb ?>images/siluete-left.png">
									<div class="sideUp">1345</div>
									<div class="sideDown">2345</div>
								</div><div>
									<img src="<?php echo $urlWeb ?>images/siluete-front.png">
									<div class="frontUp">1345</div>
									<div class="frontDown">2345</div>
									<div class="frontHeight">1345</div>
									<div class="backUp">1345</div>
									<div class="backDown">2345</div>
								</div>
							</div>
						</div>
						<div class="dataBox">
							<div class="title">Motor</div>
							<ul>
								<li>
									<span>Fuel</span>
									<span>Gasoline</span>
								</li>
								<li>
									<span>Displacement</span>
									<span>3.1 s</span>
								</li>
								<li>
									<span>Maximum power CV-kW/rpm</span>
									<span>8.4 s</span>
								</li>
								<li>
									<span>Maximum torque Nm/rpm</span>
									<span>11.7 L</span>
								</li>
								<li>
									<span>Number of cylinders</span>
									<span>10.2 L</span>
								</li>
								<li>
									<span>Material block/butt</span>
									<span>10.8 L</span>
								</li>
								<li>
									<span>Bore x stroke (mm)</span>
									<span>1.9 g</span>
								</li>
								<li>
									<span>Distribution</span>
									<span>1.9 g</span>
								</li>
								<li>
									<span>Feeding</span>
									<span>1.9 g</span>
								</li>
								<li>
									<span>Starting the engine</span>
									<span>1.9 g</span>
								</li>
							</ul>
						</div>
						<div class="dataBox">
							<div class="title">Transmission</div>
							<ul>
								<li>
									<span>Wheel drive</span>
									<span>4x4</span>
								</li>
								<li>
									<span>Gearbox</span>
									<span>6</span>
								</li>
								<li>
									<span>Developments (km / h - 1.000 rpm)</span>
									<span>8.4 s</span>
								</li>

							</ul>
						</div>
						<div class="dataBox">
							<div class="title">Chassis</div>
							<ul>
								<li>
									<span>Front suspension (structure/spring)</span>
									<span>285 km/h</span>
								</li>
								<li>
									<span>Rear Suspension (structure/spring)</span>
									<span>3.1 s</span>
								</li>
								<li>
									<span>Front brakes (mm diameter)</span>
									<span>8.4 s</span>
								</li>
								<li>
									<span>Rear brakes (mm diameter)</span>
									<span>11.7 L</span>
								</li>
								<li>
									<span>Direction</span>
									<span>10.2 L</span>
								</li>
								<li>
									<span>Front tires</span>
									<span>10.8 L</span>
								</li>
								<li>
									<span>Rear tires</span>
									<span>1.9 g</span>
								</li>
								<li>
									<span>Front wheels</span>
									<span>1.9 g</span>
								</li>
								<li>
									<span>Rear wheels</span>
									<span>1.9 g</span>
								</li>

							</ul>
						</div>
					</div>

					<div class="pageAnalytics">
						<span onclick="likePage('<?php echo $iddelmodel; ?>');">
                            <?php if (comprobacionLikesCars($_SESSION['MM_Id'], $iddelmodel) == 'true'){ ?>
                                <span class="likeButton">
                                    <?php include_once("images/svg/likes.php");?>
                                </span>
                            <?php } else {?>
                                <span class="likeButton liked">
                                    <?php include_once("images/svg/likes.php");?>
                                </span>
                            <?php } ?>
                        </span>
                        <span class="totalLikes"><?php echo countLikesCars($iddelmodel); ?></span>
						
						<?php include_once("images/svg/views.php");?>
						<?php echo $row_GetModel['views']; ?>

						<?php include_once("images/svg/comments.php");?>
						<span class="totalComments"><?php echo $totalRows_GetCommentsCount; ?></span>
					</div>

					<?php include_once("pages/cars/comments.php");?>

				</div>
				<?php include_once("includes/footer.php");?>
			</div>
			<?php include_once("includes/cookies.php");?>
		</div>
		<div class="hiddenBody"></div>
		<script type="text/javascript">
			// ···> Likes
			function likePage(id){
	            $.post( url + 'pages/cars/likes.php', {iddelmodel: id})
	            .done(function(respuesta) {
	                if(respuesta=='Added'){
	                    var total = parseInt($('.totalLikes').html()) + 1;
	                    $('.totalLikes').html(total);
	                    $('.likeButton').addClass('liked');
	                } else if(respuesta=='Removed'){
	                    var total = parseInt($('.totalLikes').html()) - 1;
	                    $('.totalLikes').html(total);
	                    $('.likeButton').removeClass('liked');
	                }
	            });
	        }

	        // ···> Enable comments button
	        function commentEnable(value){
		        if(value != ''){
		            $(".button svg").css("fill","#09f");
		        }else{
		            $(".button svg").css("fill","#333");
		        }
		    }

		    // ···> New Comment
		    function newComment(comment, page) {
		        if (comment !== "") {
		            $.ajax({
		                type: "POST",
		                url: url + 'pages/cars/new-comment.php',
		                data: 'comment=' + comment + '&page=' + page,
		                success: function(htmlrespuesta) {
		                    tabla = $('#carsComments');
		                    tabla.prepend(htmlrespuesta);
		                    comentario.value = "";
		                    $('.noComents').hide();
		                    $(".button svg").css("fill","#333");

		                    var total = parseInt($('.pageAnalytics .totalComments').html()) + 1;
		                    $('.pageAnalytics .totalComments').html(total);
		                }
		            });
		        }
		    }

		    // ···> Delete comment
		    function deleteComment(valor) {
		        $.ajax({
		            type: 'POST',
		            url: url + 'pages/cars/delete-comment.php',
		            data: 'id=' + valor,
		            success: function(respuesta) {
		                $('#comentariosPostId' + valor).fadeOut(300);

		                var total = parseInt($('.pageAnalytics .totalComments').html()) - 1;
		                $('.pageAnalytics .totalComments').html(total);
		            }
		        });
		    }

		    // ···> Load more comments
		    function moreComments(cantidad, idPage) {
		        $.ajax({
		            type: 'POST',
		            url: url + 'pages/cars/more-comments.php',
		            data: 'cantidad=' + cantidad + '&idPage=' + idPage,
		            success: function(respuesta) {
		                console.log('RES:', respuesta);

		                if (respuesta !== '') {
		                    $('#carsComments').append(respuesta);

		                    var vartotal = parseInt($('#conteomascom').html()) - parseInt(4);
		                    $('#conteomascom').html(vartotal);

		                    var comprobar = parseInt($('#conteomascom').html());
		                    if (comprobar <= 0) {
		                        $('.showMoreComments').hide();
		                    }
		                } else {
		                    $('.showMoreComments').hide();
		                }
		            }
		        });
		    }
		</script>
        <script type="text/javascript" src="<?php echo $urlWeb ?>scripts/swiper/swiper-slider.min.js"></script>
        <script type="text/javascript" src="<?php echo $urlWeb ?>scripts/swiper/swiper-home.min.js"></script>
		<script type="text/javascript" src="<?php echo $urlWeb ?>scripts/efectos.min.js"></script>
	</body>
	<?php include_once("includes/aplazarscripts.php");?>
</html>
<?php mysql_free_result($GetModel); ?>
<?php mysql_free_result($GetBrand); ?>
<?php mysql_free_result($GetCommentsCount); ?>
<?php mysql_free_result($GetComments); ?>