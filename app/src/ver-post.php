<?php require_once('Connections/conexion.php');

    $_SESSION['comentarios']=0;

    $_GET['date'] = UrlAmigables($_GET['date']);
    $iddelpost= $_GET['date'];

    $updateSQL = sprintf("UPDATE z_posts SET visitas= visitas +1 WHERE id=%s",
    GetSQLValueString($iddelpost, "int"));
    mysql_select_db($database_conexion, $conexion);
    $Result1 = mysql_query($updateSQL, $conexion) or die(mysql_error());


    mysql_select_db($database_conexion, $conexion);
    $query_GetPost = sprintf("SELECT * FROM z_posts WHERE id=%s",$iddelpost,"int");
    $GetPost = mysql_query($query_GetPost, $conexion) or die(mysql_error());
    $row_GetPost = mysql_fetch_assoc($GetPost);
    $totalRows_GetPost = mysql_num_rows($GetPost);

    $contenido = $row_GetPost['contenido'];
    $contenido = strip_tags($contenido, '<div><img><br>');

    if($row_GetPost['imagen1'] != ''){
        $imagenes=explode('-:#:-', $row_GetPost['imagen1']);
        $cantidadImagenes=count($imagenes);  
    }

    $tipopost = $row_GetPost['tipo'];

    mysql_select_db($database_conexion, $conexion);
    $query_SacarComent2 = sprintf("SELECT * FROM z_coment WHERE z_coment.post =%s ORDER BY id DESC",$iddelpost,"int");
    $SacarComent2 = mysql_query($query_SacarComent2, $conexion) or die(mysql_error());
    $row_SacarComent2 = mysql_fetch_assoc($SacarComent2);
    $totalRows_SacarComent2 = mysql_num_rows($SacarComent2);

    //Comments Count
    mysql_select_db($database_conexion, $conexion);
    $query_GetCommentsCount= sprintf("SELECT * FROM z_posts_comments WHERE z_posts_comments.page =%s ORDER BY id",$iddelpost,"int");
    $GetCommentsCount = mysql_query($query_GetCommentsCount, $conexion) or die(mysql_error());
    $row_GetCommentsCount = mysql_fetch_assoc($GetCommentsCount);
    $totalRows_GetCommentsCount = mysql_num_rows($GetCommentsCount);

    //Comments
    mysql_select_db($database_conexion, $conexion);
    $query_GetComments= sprintf("SELECT * FROM z_posts_comments WHERE z_posts_comments.page =%s ORDER BY id DESC LIMIT 4",$iddelpost,"int");
    $GetComments = mysql_query($query_GetComments, $conexion) or die(mysql_error());
    $row_GetComments = mysql_fetch_assoc($GetComments);
    $totalRows_GetComments = mysql_num_rows($GetComments);

    $cometariosmostrar=$totalRows_GetCommentsCount-4;

    //Random Notices
    mysql_select_db($database_conexion, $conexion);
    $query_RandomNotice = "SELECT * FROM z_posts WHERE tipo=1 ORDER BY rand() LIMIT 3"; 
    $RandomNotice = mysql_query($query_RandomNotice, $conexion) or die(mysql_error());
    $row_RandomNotice = mysql_fetch_assoc($RandomNotice);
    $totalRows_RandomNotice = mysql_num_rows($RandomNotice);

?>
<!DOCTYPE html>
    <?php include_once("includes/fuckoff.php"); ?>
    <head>
        <meta charset="utf-8">
        <meta name="Author" content="Diesel vs. Gasoline" />
        <meta http-equiv="X-UA-Compatible" content="IE=EmulateIE7, IE=9" />
        <title>DVSG - <?php echo $row_GetPost['titulo']; ?></title>
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
                <?php if ($tipopost == 1){ ?>
                    <?php include_once("includes/menuPages/menuGoBack.php");?>
                <?php } ?>
                <?php if ($tipopost == 2){ ?>
                    <?php include_once("includes/menuPages/menuGoBackShop.php");?>
                <?php } ?>
                <?php if ($tipopost == 3){ ?>
                    <?php include_once("includes/menuPages/menuGoBackBlog.php");?>
                <?php } ?>
            </div>

            <div class="confirmationDeleteBox">
                <div class="text">You sure to want to delete this post?</div>
                <div class="button" onclick="deletePost(1)">NO</div>
                <div class="button" onclick="deletePost(2,'<?php echo $iddelpost; ?>')">YES</div>
            </div>

            <div class="innerBodyContent">
                <?php if ($tipopost == 1){ ?>
                    <div class="pageNoticePosts">
                        <div class="swiper" id="swiper-container">
                            <div class="swiper-wrapper">
                                <?php if ($row_GetPost['imagen1'] !=''){?> 
                                    <?php for ($i=0; $i < $cantidadImagenes; $i++){
                                    $nombreImagenes=$imagenes[$i];
                                    ?>
                                        <div class="swiper-slide">
                                            <img src="<?php echo $nombreImagenes ?>"/>
                                        </div>
                                    <?php } ?>
                                <?php }?>
                            </div>
                            <div class="swiper-arrow-left">x--</div>
                            <div class="swiper-arrow-right">--x</div>
                            <center>
                                <div class="pagination" id="pagination"></div>
                            </center>
                        </div>

                        <div class="title">
                            <div class="title"><?php echo $row_GetPost['titulo'];?></div>
                        </div>
                        <div class="date"><?php echo $row_GetPost['fecha'];?></div>
                        <div class="description">
                            <?php echo $contenido ?>
                        </div>

                        <div class="pageAnalytics">
                            <div class="actions">
                                <?php  if (isset($_SESSION['MM_Id'])){?>
                                    <?php  if (sacararango($_SESSION['MM_Id']) == 4) {?>
                                        <span onclick="deletePost(1)">
                                            <?php include("images/svg/rubbish.php");?>
                                            Delete
                                        </span>
                                        <span onclick="editPost('<?php echo $iddelpost; ?>')">
                                            <?php include("images/svg/edit.php");?>
                                            Edit
                                        </span>
                                    <?php }?>
                                <?php }?>
                            </div>
                            <span onclick="likePage('<?php echo $iddelpost; ?>');">
                                <?php if (comprobacionLikesPosts($_SESSION['MM_Id'], $iddelpost) == 'true'){ ?>
                                    <span class="likeButton">
                                        <?php include_once("images/svg/likes.php");?>
                                    </span>
                                <?php } else {?>
                                    <span class="likeButton liked">
                                        <?php include_once("images/svg/likes.php");?>
                                    </span>
                                <?php } ?>
                            </span>
                            <span class="totalLikes"><?php echo countLikesPosts($iddelpost); ?></span>
                            
                            <?php include("images/svg/views.php");?>
                            <?php echo $row_GetPost['visitas']; ?>

                            <?php include("images/svg/comments.php");?>
                            <span class="totalComments"><?php echo $totalRows_GetCommentsCount; ?></span>
                        </div>

                        <?php include_once("pages/posts/comments.php");?>

                        <div class="randomNotices">
                            <ul>
                                <?php do { $imagenes=explode('-:#:-', $row_RandomNotice['imagen1']); ?>
                                    <li onclick="location.href='<?php echo $urlWeb ?><?php echo $row_RandomNotice['urlamigable']; ?>.html'">
                                        <img src="<?php echo $imagenes[0]; ?>"/>
                                        <div class="title">
                                            <?php echo $row_RandomNotice['titulo']; ?>
                                        </div>
                                    </li>
                                <?php } while ($row_RandomNotice = mysql_fetch_assoc($RandomNotice)); ?>            
                            </ul>
                        </div>
                        <div class="advert">
                            <?php include_once("includes/addByGoogle.php");?>
                        </div>
                    </div>
                <?php } ?>

                <?php if ($tipopost == 2){ ?>
                    <div class="pageShopPosts">
                        <div class="swiper" id="swiper-container">
                            <div class="swiper-wrapper">
                                <?php if ($row_GetPost['imagen1'] !=''){?> 
                                    <?php for ($i=0; $i < $cantidadImagenes; $i++){
                                    $nombreImagenes=$imagenes[$i];
                                    ?>
                                        <div class="swiper-slide">
                                            <img src="<?php echo $nombreImagenes ?>"/>
                                        </div>
                                    <?php } ?>
                                <?php }?>
                            </div>
                            <div class="swiper-arrow-left">x--</div>
                            <div class="swiper-arrow-right">--x</div>
                            <center>
                                <div class="pagination" id="pagination"></div>
                            </center>
                        </div>

                        <div class="title">
                            <div class="title"><?php echo $row_GetPost['titulo'];?></div>
                            <div class="price"><?php echo number_format($row_GetPost['price'] , 0, ',', '.');?> <?php echo $row_GetPost['currency'];?></div>
                        </div>
                        <div class="date"><?php echo $row_GetPost['fecha'];?></div>

                        <div class="description">
                            <?php echo $row_GetPost['contenido'];?>
                            <br>
                            <div class="details">
                                <div class="headerTitle">Personal data</div>
                                <div class="boxOne">
                                    <div class="title">Publisher</div>
                                    <div class="content">
                                        <?php include_once("images/svg/person.php");?>
                                        <?php echo nombre($row_GetPost['autor']);?>
                                    </div>
                                </div>
                                <div class="boxOne">
                                    <div class="title">Country/City</div>
                                    <div class="content">
                                        <?php include_once("images/svg/location.php");?>
                                        <?php echo $row_GetPost['country'];?> / <?php echo $row_GetPost['city'];?>
                                    </div>
                                </div>
                                <div class="boxOne">
                                    <div class="title">Phone</div>
                                    <div class="content">
                                        <?php include_once("images/svg/phone.php");?>
                                        <?php echo $row_GetPost['phone'];?>
                                    </div>
                                </div>
                            </div>

                            <div class="details">
                            <div class="headerTitle">Car data</div>
                                <div class="boxTwo">
                                    <div class="title">KM</div>
                                    <div class="content"><?php echo $row_GetPost['km'];?> Km</div>
                                </div>
                                <div class="boxTwo">
                                    <div class="title">Year</div>
                                    <div class="content"><?php echo $row_GetPost['year'];?></div>
                                </div>
                                <div class="boxTwo">
                                    <div class="title">Body type</div>
                                    <div class="content"><?php echo $row_GetPost['bodytype'];?></div>
                                </div>
                                <div class="boxTwo">
                                    <div class="title">Power</div>
                                    <div class="content"><?php echo $row_GetPost['power'];?> HP</div>
                                </div>
                                <div class="boxTwo">
                                    <div class="title">Fuel</div>
                                    <div class="content"><?php echo $row_GetPost['fuel'];?></div>
                                </div>
                                <div class="boxTwo">
                                    <div class="title">Engine</div>
                                    <div class="content"><?php echo $row_GetPost['engine'];?></div>
                                </div>
                            </div>
                        </div>

                        <div class="pageAnalytics">
                            <div class="actions">
                                <?php  if (isset($_SESSION['MM_Id'])) {?>
                                    <?php  if (($_SESSION['MM_Id'] == $row_GetPost['autor']) || (sacararango($_SESSION['MM_Id']) == 4)){?>
                                        <span onclick="deletePost(1)">
                                            <?php include("images/svg/rubbish.php");?>
                                            Delete
                                        </span>
                                        <span onclick="editPost('<?php echo $iddelpost; ?>')">
                                            <?php include("images/svg/edit.php");?>
                                            Edit
                                        </span>
                                    <?php }?>
                                <?php }?>
                            </div>
                            <span onclick="likePage('<?php echo $iddelpost; ?>');">
                                <?php if (comprobacionLikesPosts($_SESSION['MM_Id'], $iddelpost) == 'true'){ ?>
                                    <span class="likeButton">
                                        <?php include_once("images/svg/likes.php");?>
                                    </span>
                                <?php } else {?>
                                    <span class="likeButton liked">
                                        <?php include_once("images/svg/likes.php");?>
                                    </span>
                                <?php } ?>
                            </span>
                            <span class="totalLikes"><?php echo countLikesPosts($iddelpost); ?></span>
                            
                            <?php include("images/svg/views.php");?>
                            <?php echo $row_GetPost['visitas']; ?>

                            <?php include("images/svg/comments.php");?>
                            <span class="totalComments"><?php echo $totalRows_GetCommentsCount; ?></span>
                        </div>

                        <?php include_once("pages/posts/comments.php");?>
                    </div>
                <?php } ?>

                <?php if ($tipopost == 3){ ?>
                    <div class="pageShopPosts">
                        <div class="swiper" id="swiper-container">
                            <div class="swiper-wrapper">
                                <?php if ($row_GetPost['imagen1'] !=''){?> 
                                    <?php for ($i=0; $i < $cantidadImagenes; $i++){
                                    $nombreImagenes=$imagenes[$i];
                                    ?>
                                        <div class="swiper-slide">
                                            <img src="<?php echo $nombreImagenes ?>"/>
                                        </div>
                                    <?php } ?>
                                <?php }?>
                            </div>
                            <div class="swiper-arrow-left">x--</div>
                            <div class="swiper-arrow-right">--x</div>
                            <center>
                                <div class="pagination" id="pagination"></div>
                            </center>
                        </div>

                        <div class="title">
                            <div class="title"><?php echo $row_GetPost['titulo'];?></div>
                        </div>
                        <div class="date"><?php echo $row_GetPost['fecha'];?></div>

                        <div class="description">
                            <?php echo $row_GetPost['contenido'];?>
                        </div>

                        <div class="pageAnalytics">
                            <div class="actions">
                                <?php  if (isset($_SESSION['MM_Id'])) {?>
                                    <?php  if (($_SESSION['MM_Id'] == $row_GetPost['autor']) || (sacararango($_SESSION['MM_Id']) == 4)){?>
                                        <span onclick="deletePost(1)">
                                            <?php include("images/svg/rubbish.php");?>
                                            Delete
                                        </span>
                                        <span onclick="editPost('<?php echo $iddelpost; ?>')">
                                            <?php include("images/svg/edit.php");?>
                                            Edit
                                        </span>
                                    <?php }?>
                                <?php }?>
                            </div>
                            <span onclick="likePage('<?php echo $iddelpost; ?>');">
                                <?php if (comprobacionLikesPosts($_SESSION['MM_Id'], $iddelpost) == 'true'){ ?>
                                    <span class="likeButton">
                                        <?php include_once("images/svg/likes.php");?>
                                    </span>
                                <?php } else {?>
                                    <span class="likeButton liked">
                                        <?php include_once("images/svg/likes.php");?>
                                    </span>
                                <?php } ?>
                            </span>
                            <span class="totalLikes"><?php echo countLikesPosts($iddelpost); ?></span>
                            
                            <?php include("images/svg/views.php");?>
                            <?php echo $row_GetPost['visitas']; ?>

                            <?php include("images/svg/comments.php");?>
                            <span class="totalComments"><?php echo $totalRows_GetCommentsCount; ?></span>
                        </div>

                        <?php include_once("pages/posts/comments.php");?>
                    </div>
                <?php } ?>
                
                <?php include_once("includes/footer.php");?>
            </div>
            <?php include_once("includes/cookies.php");?>
        </div>
        <div class="hiddenBody"></div>
        <script type="text/javascript" src="<?php echo $urlWeb ?>scripts/swiper/swiper-slider.min.js"></script>
        <script type="text/javascript" src="<?php echo $urlWeb ?>scripts/swiper/swiper-home.min.js"></script>
        <script type="text/javascript" src="<?php echo $urlWeb ?>scripts/efectos.min.js"></script>
        <script type="text/javascript">
            // ···> Likes
            function likePage(id){
                $.post( url + 'pages/posts/likes.php', {iddelpost: id})
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
                        url: url + 'pages/posts/new-comment.php',
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
            function deleteComment(valor, idPost) {
                console.log('-->', valor, idPost);
                $.ajax({
                    type: 'POST',
                    url: url + 'pages/posts/delete-comment.php',
                    data: 'id=' + valor + '&idPost=' + idPost,
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
                    url: url + 'pages/posts/more-comments.php',
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

            //
            function actionSettings(){
                alert("Workin' on!");
            }
        </script>
    </body>
    <?php include_once("includes/aplazarscripts.php");?>
</html>
<?php mysql_free_result($GetPost);?>
<?php mysql_free_result($GetComments); ?>
<?php mysql_free_result($GetCommentsCount); ?>
<?php mysql_free_result($RandomNotice); ?>