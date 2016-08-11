<?php require_once('../../../Connections/conexion.php');

    $tiempocotejo= time();
    $insertSQL = sprintf("INSERT INTO z_cars_comments (user, time, comment, page) VALUES (%s, %s, %s, %s)",
    GetSQLValueString($_SESSION['MM_Id'], "int"),
    GetSQLValueString($tiempocotejo, "int"),
    GetSQLValueString(str_replace("\n",'<br/>',$_POST['comment']), "text"),
    GetSQLValueString($_POST['page'], "text"));
    mysql_select_db($database_conexion, $conexion);
    $Result1 = mysql_query($insertSQL, $conexion) or die(mysql_error());

    actualiazarCars('addComment', $_POST['page']);

    mysql_select_db($database_conexion, $conexion);
    $query_GetNewComment = sprintf("SELECT * FROM z_cars_comments WHERE time=%s",$tiempocotejo,"int");
    $GetNewComment = mysql_query($query_GetNewComment, $conexion) or die(mysql_error());
    $row_GetNewComment = mysql_fetch_assoc($GetNewComment);
    $totalRows_GetNewComment = mysql_num_rows($GetNewComment);
?>
<div class="box" id="comentariosPostId<?php echo $row_GetNewComment['id'] ?>">
    <div class="avatar">
        <a href="<?php echo $urlWeb ?>id<?php echo $row_GetNewComment['user']; ?>">
            <img src="<?php echo avatar_user($row_GetNewComment["user"]); ?>" width="36px" height="36px" style="border-radius: 50%"/>
        </a>
    </div>

    <div class="name">
        <a href="<?php echo $urlWeb ?>id<?php echo $row_GetNewComment['user']; ?>">
            <?php echo nombre($row_GetNewComment['user']); ?>
        </a>
        <font size="-2"><?php echo tiempo_transcurrido($row_GetNewComment['date']);?></font>
    </div>

    <?php if (isset ($_SESSION['MM_Id'])){ ?> 
        <?php  if (($row_GetNewComment['user'] == $_SESSION['MM_Id']) || (rango_admin ($_SESSION['MM_Id']) ==4)) {?>
            <div class="delete" onClick="deleteComment('<?php echo $row_GetNewComment['id'] ?>');">
                <?php include("../../images/svg/close.php");?>
            </div>
        <?php }?>
    <?php }?>

    <div class="content">
        <div class="inner">
            <?php echo $row_GetNewComment["comment"];?>
        </div>
    </div>
</div>
<?php mysql_free_result($GetNewComment);?>