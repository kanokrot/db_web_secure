<?php
require_once 'config.php';
require_once 'utils.php';
?>
<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>CSRF Lab - Login</title>

</head>
<body>
<div >
    <h2>CSRF Lab</h2>
    <div >
    <h2>Login</h2>
    <form method="post" action="login.php">
        <input type="hidden" name="csrf_token" value="<?php echo csrf_token(); ?>">
        <label>Username</label>
        <input name="username" type="text" required> <br><br>
        <label>Password</label>
        <input name="password" type="password" required><br><br>
        <button type="submit">Login</button><br>
    </form>
    <?php if (isset($_GET['msg'])): ?>
        <div style="color: red;">
            <?php echo htmlspecialchars($_GET['msg'], ENT_QUOTES, 'UTF-8'); ?>
        </div>
    <?php endif; ?>
</div>

    <?php /*Show a password hash.
$new = 'password123';
$hash = password_hash($new, PASSWORD_BCRYPT);
echo "password123 password_hash: ". $hash;
*/?>
</div>
</body>
</html>

