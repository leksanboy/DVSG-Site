<?php require_once('../../../Connections/conexion.php');
	if (isset ($_SESSION['MM_Id'])){ 
		header("Location: " . $urlWeb);
	}

	function generateRandom($length = 10) {
	    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
	    $charactersLength = strlen($characters);
	    $randomString = '';
	    for ($i = 0; $i < $length; $i++) {
	        $randomString .= $characters[rand(0, $charactersLength - 1)];
	    }
	    return $randomString;
	}

	$email = $_POST['email'];
	$password = generateRandom();

	//User data
	mysql_select_db($database_conexion, $conexion);
	$query_userData = sprintf("SELECT id, name FROM z_users WHERE email = %s", 
	GetSQLValueString($email, "text"));
	$userData = mysql_query($query_userData, $conexion) or die(mysql_error());
	$row_userData = mysql_fetch_assoc($userData);
	$totalRows_userData = mysql_num_rows($userData);

	//Set new password
	$updateSQL = sprintf("UPDATE z_users SET password=%s WHERE id=%s",
		GetSQLValueString(md5($password), "text"),
		GetSQLValueString($row_userData['id'], "int"));
	mysql_select_db($database_conexion, $conexion);
	$Result1 = mysql_query($updateSQL, $conexion) or die(mysql_error());

	if ($row_userData['name'] == ''){
		echo 'false';
	} else {
		echo 'PASS:'.$password;
		//Welcome mail
		emailForgotPassword($row_userData['name'], $email, $password);
	}
?>