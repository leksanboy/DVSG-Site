<div class="searchBox">
    <form name="form1" method="post">
        <div class="search">
        	<?php include("images/svg/search.php");?>
        </div>
        <input type="text" name="buscar" class="searchBoxInput" autocomplete="off" onKeyUp="searchNotices(buscar.value);" placeholder="Search..."/>
        <div class="remove" onClick="searchBoxDelete();">
        	<?php include("images/svg/clear.php");?>
        </div>
    </form>
</div>
<div class="noticesWindow"></div>