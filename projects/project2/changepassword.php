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
	$username = $_REQUEST["username"];
	$newpassword = $_REQUEST["newpassword"];
	if (isset($username) AND isset($newpassword)) {
		echo "DEBUG: changepassword.php->Got: username=$username; newpassword=$newpassword\n<br>";
	} else {
		echo "No provided username/password to change.";
		exit();
	}
?>
<a href="index.php">Home</a> | <a href="logout.php">Logout</a>