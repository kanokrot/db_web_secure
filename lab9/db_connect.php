<?php
$servername = "localhost";
$username   = "root";
$password   = "root123";
$dbname     = "dbwebsec_db";

$db = new mysqli($servername, $username, $password, $dbname);
if ($db->connect_error) {
    die("Connection failed: " . $db->connect_error);
}
?>