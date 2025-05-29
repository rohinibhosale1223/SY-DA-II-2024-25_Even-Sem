<?php
$host = "localhost";
$dbname = "shop_zone"; // Replace with your DB name
$username = "root"; // Default XAMPP user
$password = "";     // Default XAMPP password (empty)

// Create connection
$conn = new mysqli($host, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>