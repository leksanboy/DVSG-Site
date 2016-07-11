<form onsubmit="return postOperations(1);" action="<?php echo $urlWeb ?>pages/postFunctions/blog/edit/publicatePost.php" method="post" name="form1" id="formCreatePost" enctype="multipart/form-data">								
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
		<input name="titulo" type="text" value="<?php echo $row_GetPost['titulo']; ?>" class="title" id="titlePost" placeholder="Title"/>
		<div class="content" id="mensaje" contenteditable="true"><?php echo $row_GetPost['contenido']; ?></div>
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