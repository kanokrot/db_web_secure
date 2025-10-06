<?php
// utils.php — CSRF helpers
function csrf_token() {
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        $_SESSION['csrf_token_time'] = time();
    }
    return $_SESSION['csrf_token'];
}

function verify_csrf_token($token, $ttlSeconds = 1800) { // 30 min default
    if (empty($_SESSION['csrf_token']) || empty($token)) return false;
    if (!hash_equals($_SESSION['csrf_token'], $token)) return false;
    if (!empty($_SESSION['csrf_token_time']) && (time() - $_SESSION['csrf_token_time']) > $ttlSeconds) return false;
    return true;
}

function flash($key) {
    if (!empty($_SESSION['flash'][$key])) {
        $msg = $_SESSION['flash'][$key];
        unset($_SESSION['flash'][$key]);
        return htmlspecialchars($msg, ENT_QUOTES, 'UTF-8');
    }
    return '';
}

function set_flash($key, $msg) {
    $_SESSION['flash'][$key] = $msg;
}
?>
