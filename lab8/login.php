<?php
require_once 'config.php';
require_once 'utils.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

     if (!verify_csrf_token($_POST['csrf_token'] ?? '')) {
        die("CSRF validation failed");
    }
    
    $u = $_POST['username'] ?? '';
    $p = $_POST['password'] ?? '';

    $stmt = $mysqli->prepare("SELECT id, username, password_hash FROM users_csrf WHERE username = ? LIMIT 1");
    $stmt->bind_param('s', $u);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $hash = $row['password_hash'];
        $id = $row['id'];

        if (password_verify($p, $hash)) {
            $_SESSION['user_id'] = (int)$id;
            $_SESSION['username'] = $u;
            header('Location: profile.php');
            exit;
        }
    }
    header('Location: index.php?msg=Incorrect+username+or+password');
    exit;
}

header('Location: index.php');
exit;
