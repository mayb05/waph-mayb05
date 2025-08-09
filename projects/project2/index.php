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

  $u = isset($_POST['username']) ? trim($_POST['username']) : '';
  $p = isset($_POST['password']) ? trim($_POST['password']) : '';

  if ($u === '' || $p === '') {
    echo "<script>alert('Username and password are required.');window.location='form.php';</script>";
    exit;
  }

  $mysqli = new mysqli('localhost', 'mayb05', 'blaze', 'waph');
  if ($mysqli->connect_errno) {
      printf("Database connection failed: %s\n", $mysqli->connect_error);
      exit;
  }

  $prepared_sql = "SELECT username, firstname
                   FROM users
                   WHERE username = ? AND password = MD5(?)";

  if (!$stmt = $mysqli->prepare($prepared_sql)) {
      echo "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
      exit;
  }

  $stmt->bind_param("ss", $u, $p);

  if (!$stmt->execute()) {
      echo "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
      exit;
  }

  $stmt->bind_result($db_username, $db_firstname);

  if ($stmt->fetch()) {
      $_SESSION['username']  = $db_username;
      $_SESSION['firstname'] = $db_firstname;

      echo "<h2>Welcome " . htmlentities($db_firstname) . "!</h2>";
      echo "<h3>User Information</h3>";
      echo "<p><strong>Username:</strong> " . htmlentities($db_username) . "</p>";
      echo "<p><strong>First Name:</strong> " . htmlentities($db_firstname) . "</p>";
      echo "<p>Password can be changed using the link below.</p>";
  } else {
      echo "<script>alert('Invalid username/password');window.location='form.php';</script>";
      $stmt->close();
      $mysqli->close();
      exit;
  }

  $stmt->close();
  $mysqli->close();
?>
  <div class="mt-3">
    <a href="logout.php">Logout</a> - <a href="changepasswordform.php">Change Password</a>
  </div>
</div>
</body>
</html>
