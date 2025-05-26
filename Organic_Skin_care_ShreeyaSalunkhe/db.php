<?php
// db.php - Database Connection Configuration

// Database configuration
define('DB_HOST', 'localhost');
define('DB_NAME', 'skincare_shop');
define('DB_USER', 'root');
define('DB_PASS', '');

// Create connection
try {
    $pdo = new PDO(
        "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME,
        DB_USER,
        DB_PASS,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ]
    );
    
    // Set charset
    $pdo->exec("SET NAMES utf8mb4");
    
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}

// Function to create database tables if they don't exist
function createTables($pdo) {
    try {
        // Users table
        $userTable = "
            CREATE TABLE IF NOT EXISTS users (
                id INT AUTO_INCREMENT PRIMARY KEY,
                full_name VARCHAR(100) NOT NULL,
                email VARCHAR(100) UNIQUE NOT NULL,
                phone VARCHAR(20) NOT NULL,
                password_hash VARCHAR(255) NOT NULL,
                newsletter_subscribed BOOLEAN DEFAULT FALSE,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
            )
        ";
        
        // Products table
        $productTable = "
            CREATE TABLE IF NOT EXISTS products (
                id INT AUTO_INCREMENT PRIMARY KEY,
                name VARCHAR(100) NOT NULL,
                description TEXT,
                price DECIMAL(10, 2) NOT NULL,
                stock_quantity INT DEFAULT 0,
                image_url VARCHAR(255),
                category VARCHAR(50),
                is_active BOOLEAN DEFAULT TRUE,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
            )
        ";
        
        // Orders table
        $orderTable = "
            CREATE TABLE IF NOT EXISTS orders (
                id INT AUTO_INCREMENT PRIMARY KEY,
                user_id INT NOT NULL,
                total_amount DECIMAL(10, 2) NOT NULL,
                shipping_amount DECIMAL(10, 2) DEFAULT 0,
                tax_amount DECIMAL(10, 2) DEFAULT 0,
                promo_code VARCHAR(50),
                order_status ENUM('pending', 'processing', 'shipped', 'delivered', 'cancelled') DEFAULT 'pending',
                shipping_address TEXT,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
            )
        ";
        
        // Order items table
        $orderItemTable = "
            CREATE TABLE IF NOT EXISTS order_items (
                id INT AUTO_INCREMENT PRIMARY KEY,
                order_id INT NOT NULL,
                product_id INT NOT NULL,
                quantity INT NOT NULL,
                price DECIMAL(10, 2) NOT NULL,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE,
                FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE
            )
        ";
        
        // Cart table (for persistent cart storage)
        $cartTable = "
            CREATE TABLE IF NOT EXISTS cart (
                id INT AUTO_INCREMENT PRIMARY KEY,
                user_id INT NOT NULL,
                product_id INT NOT NULL,
                quantity INT NOT NULL,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
                FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE,
                UNIQUE KEY unique_user_product (user_id, product_id)
            )
        ";
        
        // Execute table creation
        $pdo->exec($userTable);
        $pdo->exec($productTable);
        $pdo->exec($orderTable);
        $pdo->exec($orderItemTable);
        $pdo->exec($cartTable);
        
        // Insert sample products if products table is empty
        $productCount = $pdo->query("SELECT COUNT(*) FROM products")->fetchColumn();
        if ($productCount == 0) {
            insertSampleProducts($pdo);
        }
        
        return true;
    } catch (PDOException $e) {
        error_log("Error creating tables: " . $e->getMessage());
        return false;
    }
}

// Function to insert sample products
function insertSampleProducts($pdo) {
    $products = [
        [
            'name' => 'Vitamin C Serum',
            'description' => 'Brighten and rejuvenate your skin with our organic vitamin C serum.',
            'price' => 29.99,
            'stock_quantity' => 50,
            'category' => 'serums'
        ],
        [
            'name' => 'Green Tea Cleanser',
            'description' => 'Gentle cleansing with antioxidant-rich green tea extract.',
            'price' => 19.99,
            'stock_quantity' => 75,
            'category' => 'cleansers'
        ],
        [
            'name' => 'Coconut Moisturizer',
            'description' => 'Deep hydration with organic coconut oil and natural botanicals.',
            'price' => 24.99,
            'stock_quantity' => 60,
            'category' => 'moisturizers'
        ],
        [
            'name' => 'Rose Hip Oil',
            'description' => 'Premium anti-aging oil with natural rose hip extract.',
            'price' => 34.99,
            'stock_quantity' => 40,
            'category' => 'oils'
        ],
        [
            'name' => 'Honey Face Mask',
            'description' => 'Nourishing face mask with organic honey and natural enzymes.',
            'price' => 22.99,
            'stock_quantity' => 35,
            'category' => 'masks'
        ],
        [
            'name' => 'Aloe Vera Gel',
            'description' => 'Soothing and healing gel for all skin types.',
            'price' => 16.99,
            'stock_quantity' => 80,
            'category' => 'treatments'
        ]
    ];
    
    $stmt = $pdo->prepare("
        INSERT INTO products (name, description, price, stock_quantity, category) 
        VALUES (?, ?, ?, ?, ?)
    ");
    
    foreach ($products as $product) {
        $stmt->execute([
            $product['name'],
            $product['description'],
            $product['price'],
            $product['stock_quantity'],
            $product['category']
        ]);
    }
}

// Utility functions
function sanitizeInput($data) {
    return htmlspecialchars(strip_tags(trim($data)));
}

function validateEmail($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL);
}

function generateSecureHash($password) {
    return password_hash($password, PASSWORD_ARGON2ID);
}

function verifyPassword($password, $hash) {
    return password_verify($password, $hash);
}

// Session management
function startSecureSession() {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
}

function isLoggedIn() {
    startSecureSession();
    return isset($_SESSION['user_id']) && !empty($_SESSION['user_id']);
}

function getCurrentUser($pdo) {
    if (!isLoggedIn()) {
        return null;
    }
    
    $stmt = $pdo->prepare("SELECT id, full_name, email, phone FROM users WHERE id = ?");
    $stmt->execute([$_SESSION['user_id']]);
    return $stmt->fetch();
}

function loginUser($userId, $userData) {
    startSecureSession();
    $_SESSION['user_id'] = $userId;
    $_SESSION['user_email'] = $userData['email'];
    $_SESSION['user_name'] = $userData['full_name'];
}

function logoutUser() {
    startSecureSession();
    session_unset();
    session_destroy();
}

// Error handling
function sendJsonResponse($success, $message, $data = null) {
    header('Content-Type: application/json');
    echo json_encode([
        'success' => $success,
        'message' => $message,
        'data' => $data
    ]);
    exit;
}

// Initialize database tables
createTables($pdo);

?>