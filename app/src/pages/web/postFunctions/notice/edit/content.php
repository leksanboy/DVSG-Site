<form onsubmit="return postOperations(1);" action="<?php echo $urlWeb ?>pages/postFunctions/notice/edit/publicatePost.php" method="post" name="form1" id="formCreatePost" enctype="multipart/form-data">								
	<input type="hidden" name="id" value="<?php echo $row_GetPost['id']; ?>"/>
	<div id="crop-avatar">
		<div class="imgPreviewArea"></div>
	</div>

	<script type="text/javascript">
		$(document).ready(function() {
		    $.post(url + 'pages/postFunctions/subidaTemporal.php', {
		    }).done(function(htmlress) {
		        $('.imgPreviewArea').html(htmlress);
		        $(".imgPreviewArea img").on("load", function() {
		            $('.uploadImages').html('<svg xmlns="http://www.w3.org/2000/svg" width="0" height="0" viewBox="0 0 48 48"><path d="M38 26H26v12h-4V26H10v-4h12V10h4v12h12v4z"/></svg><svg xmlns="http://www.w3.org/2000/svg" width="0" height="0" viewBox="0 0 48 48"><circle cx="24" cy="24" r="6.4"/><path d="M18 4l-3.66 4H8c-2.21 0-4 1.79-4 4v24c0 2.21 1.79 4 4 4h32c2.21 0 4-1.79 4-4V12c0-2.21-1.79-4-4-4h-6.34L30 4H18zm6 30c-5.52 0-10-4.48-10-10s4.48-10 10-10 10 4.48 10 10-4.48 10-10 10z"/></svg> add Photos');
		        });
		    });
		});
	</script>

	<input type="file" onChange="uploadMore();" name="imagen[]" value="" id="uploadImagesNoticePost" style="display:none" role="1" multiple>
	<label for="uploadImagesNoticePost">
		<div for="uploadImagesNoticePost" class="uploadImages">
			<?php include("images/svg/add-photo.php");?>
			Upload Photos
		</div>
	</label>

	<div class="createContainer">
		<input name="titulo" value="<?php echo $row_GetPost['titulo']; ?>" type="text" class="title" id="titlePost" placeholder="Title" style="color: #<?php echo $row_GetPost['color']; ?>"/>
		<div class="edit" onclick="editCurrentNoticeColorBox();">
			<?php include("images/svg/picker.php");?>	
		</div>
		<div class="editBox">
			<input class="setColor" value="<?php echo $row_GetPost['color']; ?>" name="color" type="text" placeholder="0099ff" onkeyup="setEditTextColor(this.value);" maxlength="6"/>
			<?php include("images/svg/hashtag.php"); ?>
			<a class="button redColor"			onClick="editTextColor(1)"></a>
			<a class="button pinkColor"			onClick="editTextColor(2)"></a>
			<a class="button purpleColor"		onClick="editTextColor(3)"></a>
			<a class="button deepColor"			onClick="editTextColor(4)"></a>
			<a class="button blueColor"			onClick="editTextColor(5)"></a>
			<a class="button cyanColor"			onClick="editTextColor(6)"></a>
			<a class="button tealColor"			onClick="editTextColor(7)"></a>
			<a class="button greenColor"		onClick="editTextColor(8)"></a>
			<a class="button limeColor"			onClick="editTextColor(9)"></a>
			<a class="button yellowColor"		onClick="editTextColor(10)"></a>
			<a class="button orangeColor"		onClick="editTextColor(11)"></a>
			<a class="button greyColor"			onClick="editTextColor(12)"></a>
			<a class="button whiteColor"		onClick="editTextColor(13)"></a>
			<a class="button blackColor"		onClick="editTextColor(14)"></a>
		</div>
		<div class="content" id="mensaje" contenteditable="true"><?php echo $row_GetPost['contenido']; ?></div>
		<script type="text/javascript">
			$('div[contenteditable=true]').keydown(function(e) {
			    if (e.keyCode == 13) {
			      document.execCommand('insertHTML', true, '<br><br>');
			      return true;
			    }
			});
  		</script>
		<textarea style="display:none" id="contenidoPost" name="contenido"></textarea>
		<div class="panel">
			<input type='file' id="imagenEditCreateNoticePost" style="display:none"/>
			<label for="imagenEditCreateNoticePost">
				<div class="button">
					<?php include("images/svg/photos.php");?>
				</div>
			</label>
			<input type="file" name="imagen2" id="imagen" style="display:none">
			<div class="button" onclick="editor_sasa(video5);">
				<?php include("images/svg/videos.php");?>
			</div>
		
			<input type="submit" value="Publicate" class="btn createButton"/>
		</div>
	</div>

	<input type="hidden" name="MM_insert" value="form1" />
</form>