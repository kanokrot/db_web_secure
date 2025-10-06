<?php
session_start();
include 'db_connect.php';

define('RECAPTCHA_SECRET_KEY', 'your-secret-key');

if (isset($_GET['logout'])) {
    session_unset();
    session_destroy();
    header("Location: login.php");
    exit;
}

$MAX_ATTEMPTS = 3;
$LOCK_SECONDS = 60;
$msg = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    $recaptcha_response = $_POST['g-recaptcha-response'] ?? '';
    if (empty($recaptcha_response)) {
        $msg = "Please complete the reCAPTCHA!";
    } else {
        $verify_url = 'https://www.google.com/recaptcha/api/siteverify';
        $data = ['secret' => RECAPTCHA_SECRET_KEY, 'response' => $recaptcha_response];
        $options = ['http' => ['method' => 'POST', 'content' => http_build_query($data)]];
        $result = file_get_contents($verify_url, false, stream_context_create($options));
        $response = json_decode($result);
        
        if (!$response->success) {
            $msg = "reCAPTCHA verification failed.";
        } else {
    
    if (!isset($conn) || $conn->connect_error) {
        $msg = "Internal error (DB).";
    } else {
        $username = $_POST['username'] ?? '';
        $password = $_POST['password'] ?? '';
        if ($username === '' || $password === '') {
            $msg = "Invalid Username or Password!";
        } else {
            $stmt = $conn->prepare("SELECT id, password, failed_attempts, lockout_until FROM users WHERE username = ?");
            $stmt->bind_param("s", $username);
            $stmt->execute();
            $user = $stmt->get_result()->fetch_assoc();
           
            $now = time();
           
            if (!$user) {
                $msg = "Invalid Username or Password!";
            } elseif (!empty($user['lockout_until']) && $now < (int)$user['lockout_until']) {
                $remain = (int)$user['lockout_until'] - $now;
                $msg = "Account locked. Try again in {$remain} seconds.";
            } elseif ($password === $user['password']) {
                $upd = $conn->prepare("UPDATE users SET failed_attempts = 0, lockout_until = NULL WHERE id = ?");
                if ($upd) { $upd->bind_param("i", $user['id']); $upd->execute(); $upd->close(); }
                session_regenerate_id(true);
                $_SESSION['user'] = $username;
                $msg = "Login Successful! Welcome " . htmlspecialchars($username) . " • <a href='?logout=1'>Logout</a>";
            } else {
                $failed = (int)$user['failed_attempts'] + 1;
                if ($failed >= $MAX_ATTEMPTS) {
                    $lockUntil = $now + $LOCK_SECONDS;
                    $upd = $conn->prepare("UPDATE users SET failed_attempts = ?, lockout_until = ? WHERE id = ?");
                    $upd->bind_param("iii", $failed, $lockUntil, $user['id']);
                    $upd->execute();
                    $upd->close();
                    $msg = "Account locked. Try again in {$LOCK_SECONDS} seconds.";
                } else {
                    $upd = $conn->prepare("UPDATE users SET failed_attempts = ?, lockout_until = NULL WHERE id = ?");
                    $upd->bind_param("ii", $failed, $user['id']);
                    $upd->execute();
                    $upd->close();
                    $msg = "Invalid Username or Password! Attempts: {$failed}";
                }
            }
        }
    }
    
        } 
    } 
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
</head>
<body>
    <?php if ($msg !== ""): ?>
        <p><?php echo $msg; ?></p>
    <?php endif; ?>
<h2>Login</h2>
<form method="post">
    Username: <input type="text" name="username">
    Password: <input type="password" name="password">
    <div class="g-recaptcha" data-sitekey="your-site-key"></div>
    <br>
    <button type="submit">Login</button>
</form>
</body>
</html>
