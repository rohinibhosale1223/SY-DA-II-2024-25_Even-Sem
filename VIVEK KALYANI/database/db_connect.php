<?php
$servername = "localhost";
$username = "root";         // Default XAMPP MySQL user
$password = "";             // No password by default
$database = "bookmyshow";   // Your database name

// Create connection
$conn = new mysqli($servername, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
