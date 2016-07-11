<div class="logo" onClick="clickThePage(1)">
	<?php  if (is_file("images/svg/logo.php")){
		include_once("images/svg/logo.php");
		}
		else if(is_file("../images/svg/logo.php"))
		{
		include_once("../images/svg/logo.php");
		}
		else if(is_file("../../images/svg/logo.php"))
		{
		include_once("../../images/svg/logo.php");
		}
		else if(is_file("../../../images/svg/logo.php"))
		{
		include_once("../../../images/svg/logo.php");
	}?>
</div>

<?php if (isset ($_SESSION['MM_Id'])){ ?>
	<div class="menuLeft" onclick="toggleLeftSide(1)">
		<?php include_once("images/svg/menu.php"); ?>
	</div>

	<div class="menuRight" onclick="toggleRightSide(1)">
		<?php include_once("images/svg/circles.php"); ?>
	</div>
<?php }?>

<div class="searchButton" onClick="searchBox();">
	<?php  if (is_file("images/svg/search.php")){
		include_once("images/svg/search.php");
		}
		else if(is_file("../images/svg/search.php"))
		{
		include_once("../images/svg/search.php");
		}
		else if(is_file("../../images/svg/search.php"))
		{
		include_once("../../images/svg/search.php");
		}
		else if(is_file("../../../images/svg/search.php"))
		{
		include_once("../../../images/svg/search.php");
	}?>
</div>

<?php if (isset ($_SESSION['MM_Id'])){ ?>
	<div class="userData">
		<div class="name" onclick="location.href='<?php echo $urlWeb ?>me'">
			<?php echo $row_SacarMiNombreUser['nombre']; ?>&nbsp;<?php echo $row_SacarMiNombreUser['apellido']; ?>
		</div>
		<div class="avatar" onclick="openUserDataBox()">
			<img src="<?php echo $row_SacarMiNombreUser['avatar']; ?>">
		</div>
	</div>
	<div class="userDataBox">
		<div class="button" onClick="clickThePage(8)">
			<?php include("images/svg/add.php");?>
		</div>
		<div class="button">
			<?php include("images/svg/person.php");?>
		</div>
		<div class="button">
			<?php include("images/svg/messages.php");?>
		</div>
		<div class="button">
			<?php include("images/svg/settings.php");?>
		</div>
	</div>
<?php }?>