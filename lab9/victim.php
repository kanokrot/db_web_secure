<?php
session_start();
if (!isset($_SESSION['user'])) {
    $_SESSION['user'] = "student";
    session_regenerate_id(true);
}

if (!isset($_SESSION['USER_AGENT'])) {
    $_SESSION['USER_AGENT'] = $_SERVER['HTTP_USER_AGENT'];
} elseif ($_SESSION['USER_AGENT'] !== $_SERVER['HTTP_USER_AGENT']) {
    session_destroy();
    die("Session hijacking attempt detected!");
}

if (!isset($_SESSION['IP'])) {
    $_SESSION['IP'] = $_SERVER['REMOTE_ADDR'];
} elseif ($_SESSION['IP'] !== $_SERVER['REMOTE_ADDR']) {
    session_destroy();
    die("Session hijacking attempt detected (IP mismatch)!");
}
?>

<h2>Welcome <?php echo $_SESSION['user']; ?></h2>
<p>Your Session ID: <?php echo session_id(); ?></p>
<p><a href="logout.php">Logout</a></p>
