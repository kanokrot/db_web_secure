<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Login</title>
</head>
<body>
    <div class="login-container">
        <h2>Login</h2>
        <form action="login.php" method="POST">
            <label>Username:</label><br>
            <input type="text" name="username" ><br>
            <label>Password:</label><br>
            <input type="password" name="password" ><br>
            <input type="submit" value="Login">
        </form>
    </div>

<?php
session_start();
include 'db_connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    // ฟังก์ชันกรองค่า
    $username = stripslashes($username);
    $username = mysqli_real_escape_string($conn, $username);

    $password = $_POST['password'];
    $password = stripslashes($password);
    $password = mysqli_real_escape_string($conn, $password);

    $sql = "SELECT id, username FROM user_t WHERE username = ? AND password = ?";

    // เตรียม statement object
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("ss", $username, $password);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $_SESSION['loggedin'] = true;
            $_SESSION['username'] = $row['username'];
            header("location: welcome.php");
        } else {
            echo "<p class='error-message'>Invalid username or password.</p>";
        }

        $stmt->close();
    } else {
        echo "<p class='error-message'>Error preparing statement: " . $conn->error . "</p>";
    }
}
?>
</body>
</html>
