<form onsubmit="return postOperations(1);" action="<?php echo $urlWeb ?>pages/postFunctions/notice/create/publicatePost.php" method="post" name="form1" id="formCreatePost" enctype="multipart/form-data">								
	
	<div id="crop-avatar">
		<div class="imgPreviewArea"></div>
	</div>

	<input type="file" name="imagen[]" value="" id="uploadImagesNoticePost" style="display:none" role="1" multiple>
	<label for="uploadImagesNoticePost">
		<div for="uploadImagesNoticePost" class="uploadImages">
			<?php include("images/svg/add-photo.php");?>
			add Photos
		</div>
	</label>

	<div class="createContainer">
		<input name="titulo" type="text" class="title" id="titlePost" placeholder="Title"/>
		<div class="edit" onclick="editCurrentNoticeColorBox();">
			<?php include("images/svg/picker.php");?>	
		</div>
		<div class="editBox">
			<input class="setColor" name="color" type="text" placeholder="0099ff" onkeyup="setEditTextColor(this.value);" maxlength="6"/>
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
		<div class="content" id="mensaje" contenteditable="true"></div>
		<script type="text/javascript">
			$('div[contenteditable=true]').keydown(function(e) {
			    if (e.keyCode == 13) {
			      document.execCommand('insertHTML', true, '<br>');
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