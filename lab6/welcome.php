<?php
session_start();
include 'db_connect.php';

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("location: login.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome</title>
</head>
<body>
    <div class="welcome-container">
        <h2>Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?>!</h2>
        <p>You have successfully logged in.</p>

        <h2>Search User</h2>
        <form action="welcome.php" method="POST">
            <label>Search ID:</label>
            <input type="text" name="id_input">
            <input type="submit" value="Submit">
        </form>
    </div>

<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_input = $_POST['id_input'];

    //ใช้ Prepared Statement
    $stmt = $conn->prepare("SELECT id, username FROM user_t WHERE id = ?");
    $stmt->bind_param("i", $id_input);  // i = integer
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $id = $row["id"];
            $username = $row["username"];

            echo "<pre>ID: {$id}<br />First name: {$username}<br /></pre>";
        }
    } else {
        echo "<pre></pre>";
    }
    $stmt->close();
}
$conn->close();
?>
</body>
</html>
