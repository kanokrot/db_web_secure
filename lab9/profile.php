<?php
session_start();
include 'db_connect.php';

if (!isset($_SESSION['user'])) {
    die("Please login first!");
}

if (!isset($_SESSION['USER_AGENT'])) {
    $_SESSION['USER_AGENT'] = $_SERVER['HTTP_USER_AGENT'];
} elseif ($_SESSION['USER_AGENT'] !== $_SERVER['HTTP_USER_AGENT']) {
    session_destroy();
    die("Session hijacking attempt detected! (User Agent mismatch)");
}
if (!isset($_SESSION['IP'])) {
    $_SESSION['IP'] = $_SERVER['REMOTE_ADDR'];
} elseif ($_SESSION['IP'] !== $_SERVER['REMOTE_ADDR']) {
    session_destroy();
    die("Session hijacking attempt detected! (IP mismatch)");
}

$user = $_SESSION['user'];
$stmt = $db->prepare("SELECT username, email, role FROM users_role WHERE username=?");
$stmt->bind_param("s", $user);
$stmt->execute();

$result = $stmt->get_result();
if ($result->num_rows === 1) {
    $u = $result->fetch_assoc();
    echo "<h2>Welcome, " . htmlspecialchars($u['username']) . "</h2>";
    echo "<p>Email: " . htmlspecialchars($u['email']) . "</p>";
    echo "<p>Role: " . htmlspecialchars($u['role']) . "</p>";
    echo "<p>Session ID: " . session_id() . "</p>";
    echo "<a href='logout.php'>Logout</a>";
}
?>
