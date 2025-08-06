<!-- addnewuser.php -->

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Login page - SecAD</title>
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

  if($_SERVER['REQUEST_METHOD'] == "POST") {
    $username = $_POST["username"];
    $password = $_POST["password"];
    $fullname = $_POST["fullname"];
    $repassword = $_POST["repassword"];

    // if (!filter_var($username, FILTER_VALIDATE_EMAIL)) {
    //     echo "<h1>Invalid email format for username!</h1>";
    //     exit();
    // }

    $password_pattern = "/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_])[A-Za-z\d\W_]{8,}$/";
    if (!preg_match($password_pattern, $password)) {
        echo "<h1>Password does not meet the required criteria!</h1>";
        exit();
    }


    if (isset($username) AND isset($password)) {
      if(addnewuser($username, $password, $fullname)) {
        echo "<h1>User registered successfully!</h1>";
        echo "<h1>Welcome $username !</h5>";
      } else {
        echo "Registration failed";
      }
    }
  }
?>

<a href="form.php">Login</a>