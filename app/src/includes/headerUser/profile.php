<?php if (isset ($_SESSION['MM_Id'])){ ?>
	<div class="menuLeft" onclick="toggleLeftSide(1)">
		<?php include_once("images/svg/menu.php"); ?>
	</div>

	<div class="userName">
			<?php echo $row_SacarMiPerfil['nombre']; ?>
		</div>

	<div class="menuRight" onclick="toggleRightSide(1)">
		<?php include_once("images/svg/circles.php"); ?>
	</div>
<?php }?>