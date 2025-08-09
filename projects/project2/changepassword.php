<!-- changepassword.php -->
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Change Password - WAPH</title>
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
  require "session_auth.php"; 


  function sanitize($s) { return htmlspecialchars(trim((string)$s), ENT_QUOTES, 'UTF-8'); }


  $username    = isset($_POST['username'])    ? trim($_POST['username'])    : (isset($_REQUEST['username']) ? trim($_REQUEST['username']) : '');
  $newpassword = isset($_POST['newpassword']) ? trim($_POST['newpassword']) : (isset($_REQUEST['newpassword']) ? trim($_REQUEST['newpassword']) : '');

  if ($username === '' || $newpassword === '') {
      echo "<h3>Missing username or new password.</h3>";
      echo '<a href="index.php">Home</a>';
      exit;
  }

  $sessionUser = isset($_SESSION['username']) ? $_SESSION['username'] : null;
  if (!$sessionUser || strcasecmp($sessionUser, $username) !== 0) {
      echo "<h3>Unauthorized password change request.</h3>";
      echo '<a href="index.php">Home</a>';
      exit;
  }

  $pwPattern = '/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[!@#$%^&])[A-Za-z\d!@#$%^&]{8,}$/';
  if (!preg_match($pwPattern, $newpassword)) {
      echo "<h3>New password does not meet the required criteria.</h3>";
      echo '<a href="index.php">Home</a>';
      exit;
  }

  function changePassword($username, $newpassword) {

      $mysqli = new mysqli('localhost', 'mayb05', 'blaze', 'waph');
      if ($mysqli->connect_errno) {
          return [false, "Database connection failed: " . $mysqli->connect_error];
      }

      $sql = "UPDATE users SET password = MD5(?) WHERE username = ?";
      $stmt = $mysqli->prepare($sql);
      if (!$stmt) {
          $msg = "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
          $mysqli->close();
          return [false, $msg];
      }

      $stmt->bind_param("ss", $newpassword, $username);

      if (!$stmt->execute()) {
          $msg = "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
          $stmt->close();
          $mysqli->close();
          return [false, $msg];
      }

      $updated = ($stmt->affected_rows === 1);

      $stmt->close();
      $mysqli->close();

      if ($updated) {
          return [true, "Password updated successfully."];
      } else {
          return [false, "No changes made. Verify the username or use a different password."];
      }
  }

  list($ok, $message) = changePassword($username, $newpassword);

  if ($ok) {
      echo "<h2>Password Changed ✅</h2>";
      echo "<p>Username: <strong>" . sanitize($username) . "</strong></p>";
      echo "<p>" . sanitize($message) . "</p>";
  } else {
      echo "<h2>Change Failed ❌</h2>";
      echo "<p>" . sanitize($message) . "</p>";
  }
?>
  <div class="mt-3">
    <a href="index.php">Home</a> | <a href="logout.php">Logout</a>
  </div>
</div>
</body>
</html>
