<?php
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
    echo json_encode([]);
    exit;
}

// Fetch logged-in users
$stmt = $pdo->query("SELECT email, timestamp FROM logins ORDER BY timestamp DESC");
echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
?>
