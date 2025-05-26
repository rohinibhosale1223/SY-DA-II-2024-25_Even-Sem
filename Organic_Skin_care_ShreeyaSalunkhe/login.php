<?php
// login.php - User Login Handler

require_once 'db.php';

// Enable CORS for frontend requests
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, GET, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

// Handle preflight requests
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    exit(0);
}

// Only process POST requests
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    sendJsonResponse(false, 'Invalid request method');
}

try {
    // Get and validate input data
    $email = sanitizeInput($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    
    // Validation
    $errors = [];
    
    if (empty($email)) {
        $errors[] = 'Email is required';
    } elseif (!validateEmail($email)) {
        $errors[] = 'Invalid email format';
    }
    
    if (empty($password)) {
        $errors[] = 'Password is required';
    }
    
    if (!empty($errors)) {
        sendJsonResponse(false, implode(', ', $errors));
    }
    
    // Check if user exists and verify password
    $stmt = $pdo->prepare("
        SELECT id, full_name, email, phone, password_hash 
        FROM users 
        WHERE email = ? AND created_at IS NOT NULL
    ");
    $stmt->execute([$email]);
    $user = $stmt->fetch();
    
    if (!$user) {
        // Don't reveal if email exists or not for security
        sendJsonResponse(false, 'Invalid email or password');
    }
    
    // Verify password
    if (!verifyPassword($password, $user['password_hash'])) {
        sendJsonResponse(false, 'Invalid email or password');
    }
    
    // Password is correct, log in the user
    loginUser($user['id'], $user);
    
    header("Location: home.html");
exit;


    // Update last login time
    $updateStmt = $pdo->prepare("UPDATE users SET updated_at = CURRENT_TIMESTAMP WHERE id = ?");
    $updateStmt->execute([$user['id']]);
    
    // Prepare user data to return (excluding password hash)
    $userData = [
        'id' => $user['id'],
        'name' => $user['full_name'],
        'email' => $user['email'],
        'phone' => $user['phone']
    ];
    
    
    
} catch (PDOException $e) {
    error_log("Database error in login.php: " . $e->getMessage());
    sendJsonResponse(false, 'Database error occurred');
} catch (Exception $e) {
    error_log("General error in login.php: " . $e->getMessage());
    sendJsonResponse(false, 'An error occurred during login');
}

?>