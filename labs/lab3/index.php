<?php
	session_start();   
	if (checklogin_mysql($_POST["username"],$_POST["password"])) {
		$username = $_POST["username"];
  		$password = $_POST["password"];
  		echo "got username=" . $username . ";password= $password";
?>
	<h2> Welcome <?php echo $_POST['username']; ?> !</h2>
<?php		
	}else{
		echo "<script>alert('Invalid username/password');window.location='form.php';</script>";
		die();
	}
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
