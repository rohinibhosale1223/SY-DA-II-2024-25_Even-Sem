
<?php include 'db_connect.php'; ?>
<?php
session_start();
$conn = new mysqli("localhost", "root", "", "bookmyshow");

$user_id = $_SESSION['user_id'];
$result = $conn->query("SELECT movies.title, movies.price FROM cart 
JOIN movies ON cart.movie_id = movies.id 
WHERE cart.user_id = '$user_id'");
?>

<!DOCTYPE html>
<html>
<head>
  <title>Your Cart</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>
  <h1>Your Cart</h1>
  <div class="cart-container">
    <?php while($row = $result->fetch_assoc()): ?>
      <div class="cart-item">
        <h3><?= $row['title'] ?></h3>
        <p>Price: ₹<?= $row['price'] ?></p>
      </div>
    <?php endwhile; ?>
  </div>
</body>
</html>
