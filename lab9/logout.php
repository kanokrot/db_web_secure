<?php
session_start();
if (!isset($_SESSION['user'])) {
    $_SESSION['user'] = "student";
}
?>

<h2>Welcome <?php echo $_SESSION['user']; ?></h2>
<p>Your Session ID: <?php echo session_id(); ?></p>
<p><a href="logout.php">Logout</a></p>
