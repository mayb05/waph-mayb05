<!-- addnewuser.php -->

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
  require "database.php";

  function sanitize_input($input) {
    $input = trim($input);
    $input = stripslashes($input);
    $input = htmlspecialchars($input, ENT_QUOTES, 'UTF-8');
    return $input;
  }
  
  if ($_SERVER['REQUEST_METHOD'] == "POST") {

    $username   = sanitize_input($_POST["username"]);
    $password   = sanitize_input($_POST["password"]);
    $firstname  = sanitize_input($_POST["firstname"]);
    $repassword = sanitize_input($_POST["repassword"]);

    if ($password !== $repassword) {
        echo "<h1>Passwords do not match!</h1>";
        exit();
    }

    $password_pattern = "/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[!@#$%^&*])[\w!@#$%^&*]{8,}$/";
    if (!preg_match($password_pattern, $password)) {
        echo "<h1>Password does not meet the required criteria!</h1>";
        exit();
    }

    if (!empty($username) && !empty($password) && !empty($firstname)) {
      if (addnewuser($username, $password, $firstname)) {
        echo "<h1>User registered successfully!</h1>";
        echo "<h1>Welcome $username!</h1>";
      } else {
        echo "<h1>Registration failed</h1>";
      }
    } else {
      echo "<h1>All fields are required!</h1>";
    }
  }
?>

<a href="form.php">Login</a>
