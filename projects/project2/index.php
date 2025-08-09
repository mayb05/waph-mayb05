<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Home - WAPH</title>
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
      max-width: 640px;
      width: 100%;
    }
  </style>
</head>
<body>
<div class="form-container">
<?php
  session_start();

  // Helper to escape HTML output
  function h($s) { return htmlspecialchars((string)$s, ENT_QUOTES, 'UTF-8'); }

  // Database login check
  function checklogin_mysql($username, $password) {
      $mysqli = new mysqli('localhost', 'mayb05', 'blaze', 'waph'); 
      if ($mysqli->connect_errno) {
          printf("Database connection failed: %s\n", $mysqli->connect_error);
          exit();
      }
      $prepared_sql = "SELECT username, firstname FROM users WHERE username = ? AND password = MD5(?)";
      $stmt = $mysqli->prepare($prepared_sql);
      $stmt->bind_param("ss", $username, $password);
      $stmt->execute();
      $result = $stmt->get_result();
      if ($result->num_rows === 1) {
          $row = $result->fetch_assoc();
          $stmt->close();
          $mysqli->close();
          return $row; // Return array with username + firstname
      }
      $stmt->close();
      $mysqli->close();
      return false;
  }

  // Handle login POST
  if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['username'], $_POST['password'])) {
      $u = trim($_POST['username']);
      $p = trim($_POST['password']);
      if ($row = checklogin_mysql($u, $p)) {
          // Successful login
          session_regenerate_id(true);
          $_SESSION['authenticated'] = true;
          $_SESSION['username'] = $row['username'];
          $_SESSION['firstname'] = $row['firstname'];
          $_SESSION['browser']   = $_SERVER['HTTP_USER_AGENT'];
      } else {
          echo "<script>alert('Invalid username/password');window.location='form.php';</script>";
          exit();
      }
  }

  // Check authentication
  if (empty($_SESSION['authenticated']) || $_SESSION['authenticated'] !== true) {
      echo "<script>alert('You have not logged in. Please login first');</script>";
      session_destroy();
      header("Refresh:0; url=form.php");
      exit();
  }
  if (!empty($_SESSION['browser']) && $_SESSION['browser'] !== $_SERVER['HTTP_USER_AGENT']) {
      echo "<script>alert('Session hijacking is detected!');</script>";
      session_destroy();
      header("Refresh:0; url=form.php");
      exit();
  }

  // Display user info (uses session values)
  echo "<h2>Welcome " . h($_SESSION['firstname']) . "!</h2>";
  echo "<h3>User Information</h3>";
  echo "<p><strong>Username:</strong> " . h($_SESSION['username']) . "</p>";
  echo "<p><strong>First Name:</strong> " . h($_SESSION['firstname']) . "</p>";
  echo "<p>Password can be changed using the link below.</p>";
?>
  <div class="mt-3">
    <a href="logout.php">Logout</a> - <a href="changepasswordform.php">Change Password</a>
  </div>
</div>
</body>
</html>
