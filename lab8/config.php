<?php
$DB_HOST = 'localhost';
$DB_USER = 'root';
$DB_PASS = 'root123';
$DB_NAME = 'dbwebsec_db';

ini_set('session.use_only_cookies', 1);
ini_set('session.cookie_httponly', 1);

ini_set('session.cookie_secure', 0);
ini_set('session.cookie_samesite', 'Strict');

session_name('CSRF_LAB_SESSID');
session_start();

$mysqli = @new mysqli($DB_HOST, $DB_USER, $DB_PASS, $DB_NAME);

if ($mysqli->connect_errno) {
die('Database connection failed: (' . $mysqli->connect_errno . ') ' . $mysqli->connect_error);
}
$mysqli->set_charset('utf8mb4');

function is_logged_in() {
return isset($_SESSION['user_id']);
}
function require_login() {
if (!is_logged_in()) {
header('Location: index.php?msg=login_required');
exit;

}

}
?>
