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
      display: flex; flex-direction: column; justify-content: center; align-items: center;
    }
    .form-container {
      background-color: rgba(255, 255, 255, 0.85);
      padding: 2rem; border-radius: 0.5rem;
      box-shadow: 0 0 10px rgba(0,0,0,0.1);
      max-width: 640px; width: 100%;
    }
  </style>
</head>
<body>
<div class="form-container">
<?php
require "session_auth.php"; // must set $_SESSION['username'] when logged in

function sanitize($s){ return htmlspecialchars(trim((string)$s), ENT_QUOTES, 'UTF-8'); }

// 1) Require logged-in user
if (empty($_SESSION['username'])) {
  http_response_code(401);
  echo "<h3>Unauthorized: no active session.</h3><a href='index.php'>Home</a>";
  exit;
}
$username = $_SESSION['username'];

// 2) Get new password ONLY from POST
$newpassword = isset($_POST['newpassword']) ? trim($_POST['newpassword']) : '';
if ($newpassword === '') {
  echo "<h3>Missing new password.</h3><a href='index.php'>Home</a>";
  exit;
}

$pwPattern = '/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[!@#$%^&])[A-Za-z\d!@#$%^&]{8,}$/';
if (!preg_match($pwPattern, $newpassword)) {
  echo "<h3>New password does not meet the required criteria.</h3><a href='index.php'>Home</a>";
  exit;
}

$newHash = md5($newpassword);

$mysqli = new mysqli('localhost', 'mayb05', 'blaze', 'waph');
if ($mysqli->connect_errno) {
  echo "<h2>Change Failed</h2><p>Database connection failed: ".sanitize($mysqli->connect_error)."</p>";
  exit;
}


$sel = $mysqli->prepare("SELECT password FROM users WHERE username = ?");
if (!$sel) {
  echo "<h2>Change Failed</h2><p>".sanitize($mysqli->error)."</p>";
  $mysqli->close(); exit;
}
$sel->bind_param("s", $username);
$sel->execute();
$sel->store_result();

if ($sel->num_rows === 0) {
  echo "<h2>Change Failed</h2><p>User not found: <strong>".sanitize($username)."</strong></p>";
  $sel->close(); $mysqli->close(); exit;
}

$sel->bind_result($currentHash);
$sel->fetch();
$sel->close();

// Same password?
if (hash_equals($currentHash ?? '', $newHash)) {
  echo "<h2>No Change</h2><p>Your new password is the same as your current password.</p>";
  echo '<div class="mt-3"><a href="index.php">Home</a> | <a href="logout.php">Logout</a></div>';
  $mysqli->close(); exit;
}

$upd = $mysqli->prepare("UPDATE users SET password = ? WHERE username = ?");
if (!$upd) {
  echo "<h2>Change Failed</h2><p>".sanitize($mysqli->error)."</p>";
  $mysqli->close(); exit;
}
$upd->bind_param("ss", $newHash, $username);
if (!$upd->execute()) {
  echo "<h2>Change Failed</h2><p>".sanitize($upd->error)."</p>";
  $upd->close(); $mysqli->close(); exit;
}

if ($upd->affected_rows === 1) {
  echo "<h2>Password Changed</h2>";
  echo "<p>Username: <strong>".sanitize($username)."</strong></p>";
  echo "<p>Password updated successfully.</p>";
} else {
  $chk = $mysqli->prepare("SELECT 1 FROM users WHERE username = ? AND password = ?");
  $chk->bind_param("ss", $username, $newHash);
  $chk->execute(); $chk->store_result();
  if ($chk->num_rows === 1) {
    echo "<h2>Password Changed</h2><p>Update succeeded.</p>";
  } else {
    echo "<h2>Change Failed</h2><p>No changes made. Verify the user exists or try a different password.</p>";
  }
  $chk->close();
}

$upd->close();
$mysqli->close();
?>
  <div class="mt-3">
    <a href="index.php">Home</a> | <a href="logout.php">Logout</a>
  </div>
</div>
</body>
</html>
