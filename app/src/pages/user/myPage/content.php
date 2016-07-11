<div class="userBox">
	<div class="backgroundImages">
		<div class="imgBox">
			<img src="<?php echo $row_SacarMiPerfil['avatar_bg1']; ?>" class="fadeImage"/>
			<img src="<?php echo $row_SacarMiPerfil['avatar_bg2']; ?>" class="fadeImage"/>
			<img src="<?php echo $row_SacarMiPerfil['avatar_bg3']; ?>" class="fadeImage"/>
		</div>
	</div>

	<div class="dataBox">
		<img src="<?php echo $row_SacarMiPerfil['avatar']; ?>"/>

		<div class="name">
			<?php echo $row_SacarMiPerfil['nombre']; ?>
		</div>

		<ul class="media">
			<li>
				<?php include("images/svg/photos.php");?>
				<span>Photos</span>
			</li>
			<li>
				<?php include("images/svg/music.php");?>
				<span>Songs</span>
			</li>
			<li>
				<?php include("images/svg/videos.php");?>
				<span>Videos</span>
			</li>
			<li>
				<?php include("images/svg/friends.php");?>
				<span>Friends</span>
			</li>
			<li>
				<?php include("images/svg/information.php");?>
				<span>Information</span>
			</li>
		</ul>
	</div>
</div>

<?php  if ($totalRows_SacarMisFotos!=''){?>
	<div class="photosBloque">
		<ul>
			<?php $contador=0; ?>
			<?php do { $contador++; ?> 
				<li>
					<a onclick="flotante_ver_img('<?php echo $_SESSION['MM_Id']?>','<?php echo $contador-1 ?>');">
						<img src="<?php echo $row_SacarMisFotos['nombre']?>" width="100%" height="100%"/>
					</a>
				</li>
			<?php } while ($row_SacarMisFotos = mysql_fetch_assoc($SacarMisFotos)); ?>
		</ul>
	</div>
<?php }?>

<div class="buttonCreate">Create post</div>

<center>
<br>-<br>-<br>-<br>-<br>-<br>-<br>-<br>-<br>-<br>-<br>-<br>-<br>-<br>-<br>-
<br>-<br>-<br>-<br>-<br>-<br>-<br>-<br>-<br>-<br>-<br>-<br>-<br>-<br>-<br>-
<br>-<br>-<br>-<br>-<br>-<br>-<br>-<br>-<br>-<br>-<br>-<br>-<br>-<br>-<br>-
</center>