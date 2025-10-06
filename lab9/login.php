<?php
ini_set('session.use_only_cookies', 1);
ini_set('session.cookie_httponly', 1);
ini_set('session.cookie_secure', 1);
ini_set('session.cookie_samesite', 'Strict');

session_start();
include 'db_connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $u = $_POST['username'] ?? '';
    $p = $_POST['password'] ?? '';

    $stmt = $db->prepare("SELECT * FROM users_role WHERE username=? AND password=?");
    $stmt->bind_param("ss", $u, $p);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        session_regenerate_id(true);
        $_SESSION['user'] = $u;
        echo "Login success!<br>";
        echo "Session ID: " . session_id();
    } else {
        echo "Invalid login";
    }
}
?>

<h2>Log in (Session Hijacking)</h2>
<form method="post">
    Username: <input type="text" name="username"><br><br>
    Password: <input type="password" name="password"><br><br>
    <button type="submit">Login</button>
</form>
