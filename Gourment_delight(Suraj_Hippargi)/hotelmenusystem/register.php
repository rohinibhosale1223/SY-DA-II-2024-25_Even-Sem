<?php
// Connection parameters
$servername = "localhost";
$username = "root";        // default XAMPP user
$password = "";            // default XAMPP password is empty
$dbname = "gourmet_delight"; // Replace with your actual database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

// Get form data and sanitize
$user = mysqli_real_escape_string($conn, $_POST['username']);
$dob = $_POST['dob'];
$pass = $_POST['password'];

// Hash password for security
$hashedPassword = password_hash($pass, PASSWORD_DEFAULT);

// Insert data into table
$sql = "INSERT INTO users (username, dob, password) VALUES ('$user', '$dob', '$hashedPassword')";

if ($conn->query($sql) === TRUE) {
  echo "<script>
          alert('Registration successful!');
          window.location.href='login.html';
        </script>";
} else {
  echo "Error: " . $sql . "<br>" . $conn->error;
}

$conn->close();
?>
