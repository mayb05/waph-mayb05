<!-- index.php -->
<?php
	$lifetime = 15 * 60; //15 minutes
	$path="/";
	$domain="192.168.56.101";
	$secure = TRUE;
	$httponly = TRUE;
	session_set_cookie_params($lifetime, $path, $domain, $secure, $httponly);

	session_start(); 

	if ( !$_SESSION["authenticated"] or $_SESSION["authenticated"] != TRUE) {
		echo "<script>alert('You have not logged in. Please login first');</script>";
		session_destroy();
		header("Refresh:0; url=form.php");
		die();
	}
	if($_SESSION["browser"] != $_SERVER['HTTP_USER_AGENT']){
		echo "<script>alert('Session hijacking is detected!');</script>";
		session_destroy();
		header("Refresh:0; url=form.php");
		die();
	}
?>