<?php
// register.php - User Registration Handler

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
    $fullName = sanitizeInput($_POST['fullName'] ?? '');
    $email = sanitizeInput($_POST['email'] ?? '');
    $phone = sanitizeInput($_POST['phone'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirmPassword = $_POST['confirmPassword'] ?? '';
    $termsAccepted = isset($_POST['terms']) && $_POST['terms'] === 'on';
    $newsletterSubscribed = isset($_POST['newsletter']) && $_POST['newsletter'] === 'on';
    
    // Validation array to collect errors
    $errors = [];
    
    // Validate full name
    if (empty($fullName)) {
        $errors[] = 'Full name is required';
    } elseif (strlen($fullName) < 2) {
        $errors[] = 'Full name must be at least 2 characters long';
    } elseif (strlen($fullName) > 100) {
        $errors[] = 'Full name must not exceed 100 characters';
    } elseif (!preg_match('/^[a-zA-Z\s\.\-\']+$/', $fullName)) {
        $errors[] = 'Full name contains invalid characters';
    }
    
    // Validate email
    if (empty($email)) {
        $errors[] = 'Email is required';
    } elseif (!validateEmail($email)) {
        $errors[] = 'Invalid email format';
    } elseif (strlen($email) > 100) {
        $errors[] = 'Email address is too long';
    }
    
    // Validate phone
    if (empty($phone)) {
        $errors[] = 'Phone number is required';
    } elseif (!preg_match('/^[\+]?[1-9][\d\s\-\(\)]{7,17}$/', $phone)) {
        $errors[] = 'Invalid phone number format';
    }
    
    // Validate password
    if (empty($password)) {
        $errors[] = 'Password is required';
    } elseif (strlen($password) < 8) {
        $errors[] = 'Password must be at least 8 characters long';
    } elseif (strlen($password) > 255) {
        $errors[] = 'Password is too long';
    } else {
        // Check password strength
        $hasLower = preg_match('/[a-z]/', $password);
        $hasUpper = preg_match('/[A-Z]/', $password);
        $hasNumber = preg_match('/[0-9]/', $password);
        $hasSpecial = preg_match('/[^A-Za-z0-9]/', $password);
        
        $strengthScore = $hasLower + $hasUpper + $hasNumber + $hasSpecial;
        
        if ($strengthScore < 3) {
            $errors[] = 'Password is too weak. Use uppercase, lowercase, numbers, and special characters';
        }
    }
    
    // Validate confirm password
    if (empty($confirmPassword)) {
        $errors[] = 'Password confirmation is required';
    } elseif ($password !== $confirmPassword) {
        $errors[] = 'Passwords do not match';
    }
    
    // Validate terms acceptance
    if (!$termsAccepted) {
        $errors[] = 'You must accept the terms and conditions';
    }
    
    // If there are validation errors, return them
    if (!empty($errors)) {
        sendJsonResponse(false, implode(', ', $errors));
    }
    
    // Check if email already exists
    $emailCheckStmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
    $emailCheckStmt->execute([$email]);
    
    if ($emailCheckStmt->fetch()) {
        sendJsonResponse(false, 'An account with this email address already exists');
    }
    
    // Clean phone number (remove spaces, dashes, parentheses)
    $cleanPhone = preg_replace('/[\s\-\(\)]/', '', $phone);
    
    // Hash the password securely
    $passwordHash = generateSecureHash($password);
    
    if (!$passwordHash) {
        sendJsonResponse(false, 'Error processing password. Please try again.');
    }
    
    // Insert new user into database
    $insertStmt = $pdo->prepare("
        INSERT INTO users (full_name, email, phone, password_hash, newsletter_subscribed, created_at) 
        VALUES (?, ?, ?, ?, ?, NOW())
    ");
    
    $success = $insertStmt->execute([
        $fullName,
        $email,
        $cleanPhone,
        $passwordHash,
        $newsletterSubscribed
    ]);
    
    if (!$success) {
        sendJsonResponse(false, 'Failed to create account. Please try again.');
    }
    
    // Get the newly created user ID
    $userId = $pdo->lastInsertId();
    
    // Prepare user data for response (excluding sensitive information)
    $userData = [
        'id' => $userId,
        'name' => $fullName,
        'email' => $email,
        'phone' => $cleanPhone,
        'newsletter_subscribed' => $newsletterSubscribed
    ];
    
    // Log the user in automatically after successful registration
    loginUser($userId, $userData);
    
    // Send welcome response
    $welcomeMessage = "Welcome to PureGlow, " . $fullName . "! Your account has been created successfully.";
    
    header('Location: login.html');
exit;

    if ($newsletterSubscribed) {
        $welcomeMessage .= " You're now subscribed to our newsletter for exclusive skincare tips and offers!";
    }
    
    sendJsonResponse(true, $welcomeMessage, $userData);
    
} catch (PDOException $e) {
    // Log database errors
    error_log("Database error in register.php: " . $e->getMessage());
    
    // Check for specific database errors
    if ($e->getCode() == 23000) { // Integrity constraint violation
        if (strpos($e->getMessage(), 'email') !== false) {
            sendJsonResponse(false, 'An account with this email address already exists');
        } else {
            sendJsonResponse(false, 'This information is already registered');
        }
    } else {
        sendJsonResponse(false, 'Database error occurred during registration');
    }
    
} catch (Exception $e) {
    // Log general errors
    error_log("General error in register.php: " . $e->getMessage());
    sendJsonResponse(false, 'An unexpected error occurred. Please try again.');
}

?>