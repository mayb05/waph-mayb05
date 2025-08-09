<!-- changepasswordform.php -->

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
  /* require "session_auth.php";
  $rand= bin2hex(openssl_random_pseudo_bytes(16));
  $_SESSION["nocsrftoken"] = $rand; 
                <input type="hidden" name="nocsrftoken" value="<?php echo $rand; ?>" />
  */

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Change Password page - WAPH</title>
</head>
<body>
      	<h1>Change Password, SecAD</h1>
<?php
  //some code here
  echo "Current time: " . date("Y-m-d h:i:sa")
?>
          <form action="changepassword.php" method="POST" class="form login">
                Username: 
                <?php echo htmlentities($_SESSION["username"]); ?>
                <br>
                New Password: <input type="password" class="text_field" name="newpassword" /> <br>
                <button class="button" type="submit">
                  Change Password
                </button>
          </form>

</body>
</html>

