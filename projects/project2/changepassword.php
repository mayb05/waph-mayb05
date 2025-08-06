<!-- changepassword.php -->

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Login page - WAPH</title>
  <!-- Minty Bootswatch Theme -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootswatch@5.3.3/dist/minty/bootstrap.min.css">
  <style>
    body {
      background: linear-gradient(to right, #a8edea, #fed6e3);
      min-height: 100vh;
      display: flex;
      flex-direction: column;
      justify-content: center;
      align-items: center;
    }
    .form-container {
      background-color: rgba(255, 255, 255, 0.85);
      padding: 2rem;
      border-radius: 0.5rem;
      box-shadow: 0 0 10px rgba(0,0,0,0.1);
    }
  </style>
</head>
<body>
<div class="form-container">

<?php
	require "session_auth.php";
	require 'database.php';
	$username= $_SESSION["username"];//$_REQUEST['username'];
	$newpassword = $_REQUEST["newpassword"];
	$nocsrftoken = $_POST["nocsrftoken"];
	if(!isset($nocsrftoken) or ($nocsrftoken!=$_SESSION['nocsrftoken'])){
		echo "<script>alert('Cross-site request forgery is detected!');</script>";
		header("Refresh:0; url=logout.php");
		die();
	}

	if (isset($username) AND isset($newpassword)) {
		// echo "DEBUG:changepassword.php->Got: username=$username;newpassword=$newpassword\n";
		if (changepassword($username, $newpassword)) {
			echo "<h4>The new password has been set.</h4>";
		}else{
			echo "<h4>Error: Cannot change the password.</h4>";
		}
	}else{
		echo "No provided username/password to change.";
		exit();
	}
?>
<a href="index.php">Home</a> | <a href="logout.php">Logout</a>