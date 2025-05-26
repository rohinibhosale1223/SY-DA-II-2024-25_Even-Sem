<?php include 'db_connect.php'; ?>

<?php
$conn = new mysqli("localhost", "root", "", "bookmyshow");
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}
?>
