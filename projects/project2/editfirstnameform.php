<!-- editfirstnameform.php -->
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
  require "session_auth.php"; // should set/verify $_SESSION['username'], $_SESSION['firstname']

  function h($s){ return htmlspecialchars((string)$s, ENT_QUOTES, 'UTF-8'); }

  if (empty($_SESSION['authenticated']) || $_SESSION['authenticated'] !== true) {
      header("Location: form.php"); exit;
  }
?>
  <h2>Edit First Name</h2>
  <p>Current: <strong><?= h($_SESSION['firstname'] ?? '') ?></strong></p>

  <form method="POST" action="editfirstname.php" class="mt-3">
    <div class="mb-3">
      <label for="firstname" class="form-label">New first name</label>
      <input type="text" class="form-control" id="firstname" name="firstname" required maxlength="50" placeholder="e.g., Bridget">
      <div class="form-text">Letters, spaces, hyphens, and apostrophes allowed (max 50).</div>
    </div>
    <button type="submit" class="btn btn-primary">Update First Name</button>
    <a href="index.php" class="btn btn-link">Cancel</a>
  </form>
</div>
</body>
</html>
