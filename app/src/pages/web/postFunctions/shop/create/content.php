<form onsubmit="return postOperations(1);" action="<?php echo $urlWeb ?>pages/postFunctions/shop/create/publicatePost.php" method="post" name="form1" id="formCreatePost" enctype="multipart/form-data">								
	
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

		<div class="details">
			<div style="width:100%;float:left">CAR DATA</div>
            <div class="box">
                <div class="title">KM</div>
                <input type="number" name="km" class="content" placeholder="24.000 KM"/>
            </div>
            <div class="box">
                <div class="title">Power</div>
                <input type="number" name="power" class="content" placeholder="120 HP"/>
            </div>
            <div class="box">
                <div class="title">Year</div>
                <div class="select">
					<select name="year">
						<option value="" disabled="disabled">Year</option>
						<?php for ($i = date("Y"); $i >= 1987; $i--) { ?>
							<option value="<?php echo $i ?>"><?php echo $i ?></option>
						<?php } ?>
					</select>
				</div>
            </div>
            <div class="box">
                <div class="title">Fuel</div>
                <div class="select">
					<select name="fuel">
						<option value="" disabled="disabled">Fuel</option>
						<option value="gasoline">Gasoline</option>
						<option value="diesel">Diesel</option>
						<option value="electric">Electric</option>
						<option value="hibrid">Hibrid</option>
					</select>
				</div>
            </div>
            <div class="box">
                <div class="title">Engine</div>
                <div class="select">
					<select name="engine">
						<option value="" disabled="disabled">Engine</option>
						<option value="manual">Manual</option>
						<option value="authomatic">Authomatic</option>
					</select>
				</div>
            </div>
            <div class="box">
                <div class="title">Body type</div>
                 <div class="select">
					<select name="bodytype">
		                <option value="" disabled="disabled">Body type</option>
						<option value="sub">SUB</option>
						<option value="coupe">Coupe</option>
						<option value="4x4">4x4</option>
					</select>
				</div>
            </div>

            <div style="width:100%;float:left;margin:32px 0 0;">PERSONAL DATA</div>

            <div class="box">
                <div class="title">Country</div>
                <div class="select">
					<select name="countryVal" id="countries_states1" class="input-medium bfh-countries" data-country="ES"></select>
				</div>
				<textarea style="display:none" id="countryPost" name="country"></textarea>
            </div>
            <div class="box">
                <div class="title">City</div>
                 <div class="select">
					<select name="cityVal" id="cityPostSelect" class="input-medium bfh-states" data-country="countries_states1" data-state="MD"></select>
				</div>
				<textarea style="display:none" id="cityPost" name="city"></textarea>
            </div>

            <div class="box">
                <div class="title">Country Prefix</div>
                <div class="select">
					<select name="prefix" id="countries_phone1" class="form-control bfh-countries" data-country="ES"></select>
				</div>
            </div>
            <div class="box">
                <div class="title">Phone Number</div>
				<input type="text" name="phone" class="content form-control bfh-phone" data-country="countries_phone1">
            </div>

            <div class="box">
                <div class="title">Price</div>
				<input type="number" name="price" class="content">
            </div>
            <div class="box">
                <div class="title">Type of money</div>
                 <div class="select">
					<select name="currency">
		                <option value="" disabled="disabled">Money</option>
						<option value="$">Dollar '$'</option>
						<option value="€">Euro '€'</option>
					</select>
				</div>
            </div>
        </div>
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