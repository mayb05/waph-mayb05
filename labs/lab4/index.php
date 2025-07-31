<?php
	session_start();  
	if (isset($_POST["username"]) and isset($_POST['password'])){ 
		if (checklogin_mysql($_POST["username"],$_POST["password"])) {
			$_SESSION["authticated"] = TRUE;
			$_SESSION["username"] = $_POST['username'];
		}else{
			session_destroy();
			echo "<script>alert('Invalid username/password');window.location='form.php';</script>";
			die();
		}
	}
	if (!$_SESSION["authticated"] or $_SESSION['authticated'] != TRUE) {
		session_destroy();
		echo "<script>alert('You have not logged in. Please login first!');</script>";
		header("Refresh:0; form.php");
		die();
	}

?>
	<h2> Welcome <?php echo htmlentities($_SESSION['username']); ?> !</h2>

	<a href="logout.php">Logout</a>
<?php		
	function checklogin($username, $password) {
		$account = array("admin","1234");
		if (($username== $account[0]) and ($password == $account[1])) 
		  return TRUE;
		else 
		  return FALSE;
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
