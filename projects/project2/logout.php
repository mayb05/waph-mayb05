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
	session_start();
	session_destroy();
?>
<p> You are logged out! </p>

<a href="form.php">Login Again</a>