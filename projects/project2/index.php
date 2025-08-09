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
<?php
    require "database.php";
    session_start();   
    if (checklogin_mysql($_POST["username"],$_POST["password"])) {
        $username = $_POST["username"];
        $password = $_POST["password"];
?>
    <h2> Welcome <?php echo $_POST['username']; ?> !</h2>
    <h3> User information: <h3>
        <p> Username: <?php echo $_POST['username']; ?> </p>
        <p> Passwords to available to be changed in the change password form linked below. </p>
<?php       
    }else{
        echo "<script>alert('Invalid username/password');window.location='form.php';</script>";
        die();
    }
    function checklogin_mysql($username, $password) {
        $mysqli = new mysqli('localhost', 'mayb05','blaze','waph'); 
        if($mysqli->connect_errno){
            printf("database connection failed: %s\n" ,$mysqli->connect_error);
            exit();
        }
        //return FALSE;
        //$sql = "SELECT * FROM users WHERE username='" . $username . "'";
        //$sql = $sql . " AND password=md5('" . $password . "')";
        //echo "DEBUG>sql=$sql"; return TRUE;
        //$result = $mysqli->query($sql);
        $prepared_sql = "SELECT * FROM users WHERE username= ? " . " AND password=md5(?);";
        $stmt = $mysqli->prepare($prepared_sql);
        $stmt->bind_param("ss", $username,$password);
        $stmt->execute();
        $result=$stmt->get_result();
        if($result->num_rows ==1)
            return TRUE;
        return false;
    }

?>
<a href="logout.php">Logout</a> - <a href="changepasswordform.php">Change Password</a>