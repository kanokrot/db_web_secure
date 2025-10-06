<?php
require_once 'config.php';
require_once 'utils.php';  
require_login();

if ($_SERVER['REQUEST_METHOD' ] !== 'POST') {
    header('Location: profile.php');
    exit;
}

if (!verify_csrf_token($_POST['csrf_token'] ?? '')) {
    die("CSRF validation failed");
}

$new = $_POST['new_password' ] ?? '';
if (!$new) {
    header('Location: profile.php?msg=Missing+password');
    exit;
}

$hash = password_hash($new, PASSWORD_BCRYPT);
$stmt = $mysqli->prepare("UPDATE users_csrf SET password_hash =? WHERE id =? ");
$stmt->bind_param('si', $hash, $_SESSION['user_id']);
$stmt->execute();
header('Location: profile.php?msg=Password+changed');