<?php
session_start();

// Destroy all session data
session_unset();
session_destroy();

// Clear any cookies if you're using them
if (isset($_COOKIE['user_token'])) {
    setcookie('user_token', '', time() - 3600, '/'); // Expire the cookie
}

// Optional: Clear cart cookie if stored in cookies instead of localStorage
if (isset($_COOKIE['cart'])) {
    setcookie('cart', '', time() - 3600, '/');
}

// Redirect to login page
header("Location: login.html");
exit();
?>