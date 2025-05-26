<?php
require_once 'db.php';
header('Content-Type: application/json');
startSecureSession();

try {
    // Step 1: Decode JSON input
    $input = file_get_contents("php://input");
    $data = json_decode($input, true);

    // Debug log input
    file_put_contents('debug_input.json', $input);
    file_put_contents('debug_log.txt', "Raw input received\n", FILE_APPEND);

    if (!isset($data['user_email'], $data['cart'])) {
        throw new Exception('Invalid input. Missing user_email or cart data.');
    }

    $email = sanitizeInput($data['user_email']);
    $cartItems = $data['cart'];

    // Step 2: Get user ID from email
    $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if (!$user) {
        throw new Exception("User not found for email: $email");
    }

    $userId = $user['id'];
    file_put_contents('debug_log.txt', "User ID found: $userId\n", FILE_APPEND);

    // Step 3: Clear existing cart items for the user
    $pdo->prepare("DELETE FROM cart WHERE user_id = ?")->execute([$userId]);
    file_put_contents('debug_log.txt', "Existing cart cleared for user $userId\n", FILE_APPEND);

    // Step 4: Prepare insert statement
    $insertStmt = $pdo->prepare("
        INSERT INTO cart (user_id, product_id, quantity)
        VALUES (?, ?, ?)
    ");

    // Step 5: Add items to cart
    foreach ($cartItems as $item) {
        if (!isset($item['name'], $item['quantity'])) {
            file_put_contents('debug_log.txt', "Invalid cart item skipped\n", FILE_APPEND);
            continue; // skip invalid item
        }

        // Step 5a: Find product ID from name
        $productStmt = $pdo->prepare("SELECT id FROM products WHERE name = ?");
        $productStmt->execute([$item['name']]);
        $product = $productStmt->fetch();

        if ($product) {
            $productId = $product['id'];
            $quantity = max(1, intval($item['quantity'])); // avoid 0 or negative

            try {
                $insertStmt->execute([$userId, $productId, $quantity]);
                file_put_contents('debug_log.txt', "Inserted: product_id=$productId, quantity=$quantity\n", FILE_APPEND);
            } catch (PDOException $e) {
                file_put_contents('debug_log.txt', "Insert failed for product_id=$productId: " . $e->getMessage() . "\n", FILE_APPEND);
            }
        } else {
            file_put_contents('debug_log.txt', "Product not found: " . $item['name'] . "\n", FILE_APPEND);
        }
    }

    // Step 6: Success response
    echo json_encode([
        'success' => true,
        'message' => 'Cart updated successfully.'
    ]);

} catch (Exception $e) {
    file_put_contents('debug_log.txt', "General error: " . $e->getMessage() . "\n", FILE_APPEND);
    echo json_encode([
        'success' => false,
        'message' => 'Error: ' . $e->getMessage()
    ]);
}
