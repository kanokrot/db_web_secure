<?php
require_once 'config.php';
require_once 'utils.php'; 
require_login();
?>
<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>Vulnerable Profile - CSRF Lab</title>

</head>
<body>
<div>
    <h1>Vulnerable Profile</h1>
    <p>NO CSRF protection .</p>
    <div>
        <form method="post" action="change_password.php">
             <input type="hidden" name="csrf_token" value="<?php echo csrf_token(); ?>">
            <label>New Password</label>
            <input type="password" name="new_password" required> <br><br>
            <button type="submit">Change Password</button>
        </form>
    <?php if (isset($_GET['msg'])): ?>
        <div class="msg">
            <?php echo htmlspecialchars($_GET['msg'], ENT_QUOTES, 'UTF-8' ); ?>
        </div>
    <?php endif; ?>
    <p class="hint">Any site can craft a POST here while you're logged in .</p>
    </div>
    <div>
        <a href="login.php">Logout</a>
    </div>
</div>
</body>
</html>