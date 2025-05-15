<?php
session_start();

// Database connection
$host = 'localhost';
$db = 'gourmet_delight';
$user = 'root';  // Default user for XAMPP/WAMP
$pass = '';  // Leave blank for XAMPP/WAMP

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = $_POST['password'];

    // Validate inputs
    if (!preg_match('/^[a-zA-Z0-9]{6,}$/', $username)) {
        echo "Invalid username.";
        exit;
    }

    if (!preg_match('/^(?=.*[a-zA-Z])(?=.*\d)(?=.*[@#$%&!]).{6,}$/', $password)) {
        echo "Invalid password.";
        exit;
    }

    // Check if user exists
    $stmt = $conn->prepare("SELECT id, username, password FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();

        // Verify password
        if (password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            header("Location: index.php");  // Redirect to homepage after successful login
            exit;
        } else {
            echo "Invalid password.";
        }
    } else {
        echo "User does not exist.";
    }

    $stmt->close();
}

$conn->close();
?>
