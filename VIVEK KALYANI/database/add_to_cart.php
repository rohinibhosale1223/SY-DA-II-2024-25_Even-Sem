<?php include 'db_connect.php'; ?>

<?php
session_start();
$conn = new mysqli("localhost", "root", "", "bookmyshow");

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

$movie_id = $_POST['movie_id'];
$user_id = $_SESSION['user_id']; // set after login

$sql = "INSERT INTO cart (user_id, movie_id) VALUES ('$user_id', '$movie_id')";
if ($conn->query($sql) === TRUE) {
    echo "Added to cart!";
} else {
    echo "Error: " . $conn->error;
}
$conn->close();
?>
