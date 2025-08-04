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
    justify-content: center;
    align-items: center;
    padding: 2rem;
    }
    .form-container {
    background-color: rgba(255, 255, 255, 0.95);
    padding: 2rem;
    border-radius: 1rem;
    box-shadow: 0 0 12px rgba(0,0,0,0.1);
    max-width: 1000px;
    width: 100%;
    }
    textarea {
    width: 100%;
    min-height: 80px;
    resize: vertical;
    }
    .comment-box {
    background-color: #f8f9fa;
    padding: 1rem;
    margin-top: 1rem;
    border-left: 4px solid #20c997;
    border-radius: 0.5rem;
    }
  </style>
</head>
<body>
<div class="form-container">

<?php
    $lifetime = 15 * 60;
    $path="/";
    $domain="*.project2.com";
    $secure = TRUE;
    $httponly = TRUE;
    session_set_cookie_params($lifetime, $path, $domain, $secure, $httponly);

    session_start();
    $mysqli = new mysqli("localhost", "may", "blaze", "waph");
    if ($mysqli->connect_errno) {
        printf("Database connection failed: %s\n", $mysqli->connect_error);
        exit();
    }

    $edit_id = isset($_GET['edit_id']) ? intval($_GET['edit_id']) : null;

    if (isset($_POST["username"]) and isset($_POST["password"])) {
        if (securechecklogin($_POST["username"], $_POST["password"])) {
            $_SESSION["logged"] = TRUE;
            $_SESSION["username"] = $_POST["username"];
            $_SESSION["browser"] = $_SERVER['HTTP_USER_AGENT'];
        }else{
            echo "<script>alert('Invalid username/password');</script>";
            session_destroy();
            header("Location: /project2/form.php");
            die();
        }
    }

    if (!isset($_SESSION["logged"]) or $_SESSION["logged"] != TRUE) {
        echo "<script>alert('You have not logged in. Please login first');</script>";
        header("Location: /project2/form.php");
        die();
    }

    if ($_SESSION["browser"] != $_SERVER['HTTP_USER_AGENT']) {
        echo "<script>alert('Session hijacking is detected!');</script>";
        header("Location: /project2/form.php");
        die();
    }

    function securechecklogin($username, $password) {
        global $mysqli;
        $prepared_sql = "SELECT username, superuser FROM users WHERE username= ? AND password=md5(?);";
        if(!$stmt = $mysqli->prepare($prepared_sql)) {
            echo "Prepared Statement Error";
            return FALSE;
        }
        $stmt->bind_param("ss", $username, $password);
        if (!$stmt->execute()) {
            echo "execute error";
            return false;
        }

        if(!$stmt->store_result()) {
            echo "store_result error";
            return false;
        }

        $result = $stmt;
        if($result->num_rows ==1) {
            $stmt->bind_result($username, $superuser);
            $stmt->fetch();
            $_SESSION["username"] = $username;
            $_SESSION["superuser"]= $superuser;
            return TRUE;
        }else {
            return FALSE;
        } 
    }

?>

<h2>Welcome <?php echo htmlentities($_SESSION["username"]); ?>!</h2>
<a href="/project2/logout.php">Logout</a>
<a href="/project2/changepasswordform.php">Change Password</a>

<!-- Create a Post -->
<h3>Create a Post</h3>
<form method="POST" action="create_post.php" class="mb-4">
    <textarea name="content" required placeholder="What's on your mind?"></textarea><br>
    <button class="btn btn-success mt-2" type="submit">Post</button>
</form>
<hr>

<?php
$stmt = $mysqli->prepare("SELECT posts.post_id, posts.content, posts.created_at, posts.username FROM posts ORDER BY posts.created_at DESC");
if ($stmt) {
    $stmt->execute();
    $stmt->store_result();
    $stmt->bind_result($post_id, $content, $created_at, $username);

    while ($stmt->fetch()) {
        echo "<div class='mb-4'>";
        echo "<h5>" . htmlentities($username) . "</h5>";
        //echo "<p>" . htmlentities($content) . "</p>";
        echo "<small class='text-muted'>Posted on " . htmlentities($created_at) . "</small>";

        if ($edit_id === $post_id && $_SESSION["username"] === $username) {
            // Show the edit form
            echo "<form method='POST' action='edit_post.php'>";
            echo "<textarea name='new_content' class='form-control' required>" . htmlentities($content) . "</textarea>";
            echo "<input type='hidden' name='post_id' value='" . $post_id . "'>";
            echo "<button type='submit' class='btn btn-success btn-sm mt-2'>Confirm</button>";
            echo "<a href='index.php' class='btn btn-secondary btn-sm mt-2 ms-1'>Cancel</a>";
            echo "</form>";
        } else {
            // Show the post normally
            echo "<p>" . htmlentities($content) . "</p>";

            if ($_SESSION["username"] === $username) {
                echo "<form method='GET' action='index.php' class='d-inline'>";
                echo "<input type='hidden' name='edit_id' value='" . $post_id . "'>";
                echo "<button class='btn btn-sm btn-warning'>Edit</button>";
                echo "</form>";
            }
        }


        echo "<form method='POST' action='create_comments.php' class='mt-2'>";
        echo "<input type='hidden' name='post_id' value='" . $post_id . "'>";
        echo "<textarea name='content' required placeholder='Add a comment...'></textarea>";
        echo "<button class='btn btn-sm btn-info mt-1' type='submit'>Comment</button>";
        echo "</form>";

        // if ($_SESSION["username"] === $username) {
        //     echo "<form method='GET' action='edit_post.php' class='d-inline'>";
        //     echo "<input type='hidden' name='post_id' value='" . $post_id . "'>";
        //     echo "<button class='btn btn-sm btn-warning ms-2' type='submit'>Edit</button>";
        //     echo "</form>";
        // }

        $stmt_comments = $mysqli->prepare("SELECT content, created_at, username FROM comments WHERE post_id = ? ORDER BY created_at ASC");
        if ($stmt_comments) {
            $stmt_comments->bind_param('i', $post_id);
            $stmt_comments->execute();
            $stmt_comments->bind_result($comment_content, $comment_created_at, $comment_username);

            while ($stmt_comments->fetch()) {
                echo "<div class='comment-box'>";
                echo "<h6>" . htmlentities($comment_username) . "</h6>";
                echo "<p>" . htmlentities($comment_content) . "</p>";
                echo "<small class='text-muted'>Commented on " . htmlentities($comment_created_at) . "</small>";
                echo "</div>";
            }
            $stmt_comments->close();
        }

        echo "</div><hr>";
    }
    $stmt->close();
}
?>

</div>
</body>
</html>
