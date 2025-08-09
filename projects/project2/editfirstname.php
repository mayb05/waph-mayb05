<!-- editfirstname.php -->
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Edit First Name - WAPH</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootswatch@5.3.3/dist/minty/bootstrap.min.css">
  <style>
    body { background: linear-gradient(to right, #a8edea, #fed6e3); min-height: 100vh; display: flex; flex-direction: column; justify-content: center; align-items: center; }
    .form-container { background-color: rgba(255,255,255,0.85); padding: 2rem; border-radius: .5rem; box-shadow: 0 0 10px rgba(0,0,0,.1); max-width: 640px; width: 100%; }
  </style>
</head>
<body>
<div class="form-container">
<?php
  require "session_auth.php";

  function h($s){ return htmlspecialchars((string)$s, ENT_QUOTES, 'UTF-8'); }

  if (empty($_SESSION['authenticated']) || $_SESSION['authenticated'] !== true || empty($_SESSION['username'])) {
      http_response_code(401);
      echo "<h3>Unauthorized: please log in.</h3><a href='form.php'>Login</a>";
      exit;
  }

  $username  = $_SESSION['username'];
  $firstname = isset($_POST['firstname']) ? trim($_POST['firstname']) : '';

  if ($firstname === '') {
      echo "<h3>Missing first name.</h3><a href='editfirstnameform.php'>Back</a>";
      exit;
  }

  // Allow letters, spaces, hyphens, apostrophes; max 50 chars
  if (!preg_match("/^[A-Za-z][A-Za-z '\-]{0,49}$/", $firstname)) {
      echo "<h3>Invalid first name format.</h3><p>Use letters, spaces, hyphens, and apostrophes (max 50).</p>";
      echo '<a href="editfirstnameform.php">Back</a>';
      exit;
  }

  $mysqli = new mysqli('localhost', 'mayb05', 'blaze', 'waph');
  if ($mysqli->connect_errno) {
      echo "<h2>Update Failed</h2><p>".h($mysqli->connect_error)."</p>";
      exit;
  }

  // Get current value to detect "no change"
  $sel = $mysqli->prepare("SELECT firstname FROM users WHERE username = ?");
  if (!$sel) {
      echo "<h2>Update Failed</h2><p>".h($mysqli->error)."</p>";
      $mysqli->close(); exit;
  }
  $sel->bind_param("s", $username);
  $sel->execute();
  $sel->bind_result($currentFirst);
  if (!$sel->fetch()) {
      echo "<h2>Update Failed</h2><p>User not found.</p>";
      $sel->close(); $mysqli->close(); exit;
  }
  $sel->close();

  if (hash_equals((string)$currentFirst, (string)$firstname)) {
      echo "<h2>No Change</h2><p>Your first name is already <strong>".h($firstname)."</strong>.</p>";
      echo '<div class="mt-3"><a href="index.php">Home</a></div>';
      $mysqli->close(); exit;
  }

  // Update
  $upd = $mysqli->prepare("UPDATE users SET firstname = ? WHERE username = ?");
  if (!$upd) {
      echo "<h2>Update Failed</h2><p>".h($mysqli->error)."</p>";
      $mysqli->close(); exit;
  }
  $upd->bind_param("ss", $firstname, $username);
  if (!$upd->execute()) {
      echo "<h2>Update Failed</h2><p>".h($upd->error)."</p>";
      $upd->close(); $mysqli->close(); exit;
  }

  if ($upd->affected_rows === 1) {
      // Keep session in sync
      $_SESSION['firstname'] = $firstname;
      echo "<h2>First Name Updated</h2>";
      echo "<p>Username: <strong>".h($username)."</strong></p>";
      echo "<p>New first name: <strong>".h($firstname)."</strong></p>";
  } else {
      // Very rare edge: double-check
      $chk = $mysqli->prepare("SELECT 1 FROM users WHERE username = ? AND firstname = ?");
      $chk->bind_param("ss", $username, $firstname);
      $chk->execute(); $chk->store_result();
      if ($chk->num_rows === 1) {
          $_SESSION['firstname'] = $firstname;
          echo "<h2>First Name Updated</h2><p>Change applied.</p>";
      } else {
          echo "<h2>Update Failed</h2><p>No changes made. Try a different name.</p>";
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
