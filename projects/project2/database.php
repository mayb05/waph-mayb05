<!-- database.php -->
<?php
	$mysqli = new mysqli("localhost", "may", "blaze", "waph");
	if ($mysqli->connect_errno) {
		printf("Database connection failed: %s\n", $mysqli->connect_error);
		exit();
}

function changepassword($username, $newpassword) {
    global $mysqli;

    // Use a prepared statement to prevent SQL injection
    $stmt = $mysqli->prepare("UPDATE users SET password=md5(?) WHERE username=?");
    if (!$stmt) {
        return false;
    }

    $stmt->bind_param("ss", $newpassword, $username);
    if (!$stmt->execute()) {
        return false;
    }

    // Check that the update affected 1 row
    return $stmt->affected_rows === 1;
}



function addnewuser($username, $password, $fullname) {
	global $mysqli;
	$prepared_sql = "INSERT INTO users (username, password, fullname) VALUES (?, md5(?), ?);";
	if(!$stmt = $mysqli->prepare($prepared_sql))
		return FALSE;
	$stmt->bind_param("sss", $username, $password, $fullname);
	if(!$stmt->execute()) return FALSE;    
	return TRUE;
}
?>