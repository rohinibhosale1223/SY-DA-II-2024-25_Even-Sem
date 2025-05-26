<?php include 'db_connect.php'; ?>

<?php
include 'db.php';
$email = $_POST['email'];
$password = $_POST['password'];

$sql = "SELECT * FROM users WHERE email='$email'";
$result = $conn->query($sql);
if ($result->num_rows == 1) {
  $row = $result->fetch_assoc();
  if (password_verify($password, $row['password'])) {
    echo "Login successful!";
  } else {
    echo "Incorrect password.";
  }
} else {
  echo "User not found.";
}
$conn->close();
?>
