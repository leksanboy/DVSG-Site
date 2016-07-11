<form onsubmit="return postOperations(1);" action="<?php echo $urlWeb ?>pages/postFunctions/blog/create/publicatePost.php" method="post" name="form1" id="formCreatePost" enctype="multipart/form-data">								
	
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
		<br>

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