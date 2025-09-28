<?php
// Database connection to phpMyAdmin
$conn = new mysqli(
    hostname: "db",
    username: "root",
    password: "rootpassword",
    database: "library_db"
);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
