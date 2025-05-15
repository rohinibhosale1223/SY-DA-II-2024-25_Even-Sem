<?php
session_start();

// Example products data - replace this with your actual product database or data source
$products = [
    1 => ['name' => 'Dish 1', 'price' => 200],
    2 => ['name' => 'Dish 2', 'price' => 350],
    3 => ['name' => 'Dish 3', 'price' => 250],
    4 => ['name' => 'Dish 4', 'price' => 150],
];

function updateCart() {
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }
}

function addToCart($productId, $quantity = 1) {
    updateCart();
    $product = $products[$productId];

    if (isset($_SESSION['cart'][$productId])) {
        $_SESSION['cart'][$productId]['quantity'] += $quantity;
    } else {
        $_SESSION['cart'][$productId] = [
            'name' => $product['name'],
            'price' => $product['price'],
            'quantity' => $quantity
        ];
    }
}

function removeFromCart($productId) {
    updateCart();
    unset($_SESSION['cart'][$productId]);
}

function calculateTotal() {
    $total = 0;
    foreach ($_SESSION['cart'] as $item) {
        $total += $item['price'] * $item['quantity'];
    }
    return $total;
}

function checkout() {
    updateCart();
    // You can add your checkout logic here, like saving the order to the database.
    $_SESSION['cart'] = []; // Empty the cart after checkout
    return true;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        $productId = $_POST['product_id'];
        $action = $_POST['action'];

        if ($action === 'add') {
            $quantity = $_POST['quantity'] ?? 1;
            addToCart($productId, $quantity);
        } elseif ($action === 'remove') {
            removeFromCart($productId);
        } elseif ($action === 'checkout') {
            checkout();
        }
    }
}

updateCart();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Cart</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            text-align: center;
            background-color: #f4f4f4;
            margin: 0;
            padding: 20px;
        }
        .cart-container {
            max-width: 700px;
            margin: auto;
            background: white;
            padding: 20px;
            border-radius: 15px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }
        .cart-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 15px;
            border-bottom: 1px solid #ddd;
            font-size: 18px;
        }
        .remove-btn {
            background: #ff4d4d;
            color: white;
            border: none;
            padding: 7px 12px;
            border-radius: 7px;
            cursor: pointer;
            transition: background 0.3s;
        }
        .remove-btn:hover {
            background: #cc0000;
        }
        .total {
            font-size: 22px;
            font-weight: bold;
            margin-top: 15px;
        }
        .checkout-btn {
            margin-top: 20px;
            padding: 12px 24px;
            background: #28a745;
            color: white;
            font-size: 18px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            transition: background 0.3s;
        }
        .checkout-btn:hover {
            background: #218838;
        }
        .bill {
            margin-top: 25px;
            text-align: left;
            padding: 15px;
            border-radius: 10px;
            background: #f8f9fa;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }
        .bill-item {
            display: flex;
            justify-content: space-between;
            font-size: 18px;
            padding: 5px 0;
        }
        .product-link {
            text-decoration: none;
            color: #007bff;
            font-weight: bold;
        }
        .product-link:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <h1>Your Cart</h1>
    
    <div class="cart-container">
        <div id="cart-items">
            <?php
            if (empty($_SESSION['cart'])) {
                echo "<p>Your cart is empty.</p>";
            } else {
                $totalPrice = 0;
                foreach ($_SESSION['cart'] as $productId => $item) {
                    $totalPrice += $item['price'] * $item['quantity'];
                    echo '<div class="cart-item">';
                    echo '<span><a href="product.php?id=' . $productId . '" class="product-link">' . $item['name'] . '</a> - ₹' . $item['price'] . ' x ' . $item['quantity'] . '</span>';
                    echo '<form method="POST" style="display:inline;"><input type="hidden" name="product_id" value="' . $productId . '"><input type="hidden" name="action" value="remove"><button type="submit" class="remove-btn">Remove</button></form>';
                    echo '</div>';
                }
            }
            ?>
        </div>
        <p class="total">Total: ₹<?php echo number_format($totalPrice, 2); ?></p>
        <form method="POST">
            <input type="hidden" name="action" value="checkout">
            <button type="submit" class="checkout-btn">Checkout</button>
        </form>
        <div class="bill">
            <h3>Bill Details:</h3>
            <?php
            foreach ($_SESSION['cart'] as $item) {
                echo '<div class="bill-item"><span><a href="product.php?id=' . $productId . '" class="product-link">' . $item['name'] . '</a> x ' . $item['quantity'] . '</span><span>₹' . number_format($item['price'] * $item['quantity'], 2) . '</span></div>';
            }
            ?>
            <div class="bill-item"><strong>Total</strong><strong>₹<?php echo number_format($totalPrice, 2); ?></strong></div>
        </div>
    </div>
</body>
</html>
