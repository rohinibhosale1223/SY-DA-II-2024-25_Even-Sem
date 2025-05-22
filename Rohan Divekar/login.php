<?php
session_start();
header('Content-Type: application/json');

// Database connection
$host = 'localhost';
$db = 'organic_vegetables';
$user = 'root';
$pass = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Database connection failed.']);
    exit;
}

// Validate login credentials
$email = $_POST['email'] ?? '';
$password = $_POST['password'] ?? '';

if ($email === '' || $password === '') {
    echo json_encode(['success' => false, 'message' => 'Email and password are required.']);
    exit;
}

// Simulate password verification (use hashing for real apps)
if ($password === 'ValidPassword') { // Replace with actual password validation
    $stmt = $pdo->prepare("INSERT INTO logins (email, timestamp) VALUES (?, NOW())");
    $stmt->execute([$email]);

    echo json_encode(['success' => true, 'message' => 'Login successful!']);
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid email or password.']);
}
?>
