<?php  if (isset($_SESSION['MM_Id'])){?>
    <div class="newComment">
        <form onsubmit="return false">
            <textarea name="comment" id="comentario" class="inputBox" onkeyup="commentEnable(this.value)" placeholder="Write a comment..."></textarea>
            <input type="hidden" name="page" value="<?php echo $iddelpost; ?>" />
            <input type="button" style="display:none" onclick="newComment(comment.value, page.value);" id="btn_comentario">
            <label for="btn_comentario">
                <div class="button">
                    <?php include_once("images/svg/send.php");?>
                </div>
            </label>
        </form>
    </div>
<?php } else {?>
    <div class="registerToComment">to comment create an account</div>
<?php }?>

<div class="comments" id="carsComments">
    <?php if ($row_GetComments !=""){?>
        <?php do { ?>
            <div class="box" id="comentariosPostId<?php echo $row_GetComments['id'] ?>">
                <div class="avatar">
                    <a href="<?php echo $urlWeb ?>id<?php echo $row_GetComments['user']; ?>">
                        <img src="<?php echo avatar_user($row_GetComments["user"]); ?>" width="36px" height="36px" style="border-radius: 50%"/>
                    </a>
                </div>

                <div class="name">
                    <a href="<?php echo $urlWeb ?>id<?php echo $row_GetComments['user']; ?>">
                        <?php echo nombre($row_GetComments['user']); ?>
                    </a>
                    <font size="-2"><?php echo tiempo_transcurrido($row_GetComments['date']);?></font>
                </div>

                <?php if (isset ($_SESSION['MM_Id'])){ ?> 
                    <?php  if (($row_GetComments['user'] == $_SESSION['MM_Id']) || (rango_admin ($_SESSION['MM_Id']) ==4)) {?>
                        <div class="delete" onClick="deleteComment('<?php echo $row_GetComments['id'] ?>','<?php echo $iddelpost ?>');">
                            <?php include("images/svg/close.php");?>
                        </div>
                    <?php }?>
                <?php }?>

                <div class="content">
                    <div class="inner">
                        <?php echo $row_GetComments["comment"];?>
                    </div>
                </div>
            </div>
        <?php } while ($row_GetComments = mysql_fetch_assoc($GetComments)); ?>
    <?php } else echo '<div class="noComents">NO COMMENTS</div>'?>
</div>

<?php  if (isset($_SESSION['MM_Id'])){?>
    <?php if ($totalRows_GetCommentsCount>4) { ?>
        <div class="showMoreComments" onclick="moreComments('4','<?php echo $iddelpost; ?>');">
            <?php echo 'Show a few more of '.'<span id="conteomascom">'.$cometariosmostrar.'</span>'.' comments';?>
        </div>
    <?php } ?>
<?php } ?>