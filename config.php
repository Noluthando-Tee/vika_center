<?php
$servername = "localhost";
$username = "root";       // default XAMPP MySQL username
$password = "";           // default XAMPP MySQL password is empty
$dbname = "vika_academy"; // create this database in phpMyAdmin

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>

