
<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Collect form data
    $name     = trim($_POST['name']);
    $email    = trim($_POST['email']);
    $password = trim($_POST['password']);
    $confirm  = trim($_POST['confirm']);
    $role     = $_POST['role'];

    $errors = [];

    // Validate input
    if (empty($name) || empty($email) || empty($password) || empty($confirm) || empty($role)) {
        $errors[] = "All fields are required.";
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid email format.";
    }

    if ($password !== $confirm) {
        $errors[] = "Passwords do not match.";
    }

    if (strlen($password) < 6) {
        $errors[] = "Password must be at least 6 characters.";
    }

    // Save to file (or database)
    if (empty($errors)) {
        $userData = $name . "," . $email . "," . $password . "," . $role . "\n";
        file_put_contents("users.txt", $userData, FILE_APPEND);
        echo "<h3>Registration successful!</h3>";
        echo "<p><a href='login.html'>Click here to login</a></p>";
    } else {
        echo "<h3>Registration Failed:</h3><ul>";
        foreach ($errors as $error) {
            echo "<li>" . htmlspecialchars($error) . "</li>";
        }
        echo "</ul><p><a href='register.html'>Go back</a></p>";
    }
} else {
    echo "<p>Invalid request method.</p>";
}
?> 
