<?php
// session_auth.php
$lifetime = 15 * 60; // 15 minutes
$path = "/";
$domain = $_SERVER['HTTP_HOST'];              // match current host automatically
$secure = !empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off'; // only true on HTTPS
$httponly = true;

session_set_cookie_params([
  'lifetime' => $lifetime,
  'path' => $path,
  'domain' => $domain,
  'secure' => $secure,
  'httponly' => $httponly,
  'samesite' => 'Lax'
]);

session_start();

// Basic checks
if (empty($_SESSION['authenticated']) || $_SESSION['authenticated'] !== true) {
  echo "<script>alert('You have not logged in. Please login first');</script>";
  session_destroy();
  header("Refresh:0; url=form.php");
  exit;
}
if (!empty($_SESSION['browser']) && $_SESSION['browser'] !== $_SERVER['HTTP_USER_AGENT']) {
  echo "<script>alert('Session hijacking is detected!');</script>";
  session_destroy();
  header("Refresh:0; url=form.php");
  exit;
}
