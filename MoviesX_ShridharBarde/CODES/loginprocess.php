<?php

session_start();
include 'connect.php';


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';

    if (empty($username) || empty($password)) {
        echo "<script>alert('Please enter username and password.'); window.location='login.php';</script>";
        exit();
    }

    $stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $row = $result->fetch_assoc();

        if (password_verify($password, $row['password'])) {
            $_SESSION['username'] = $username;
            header("Location: Movie.html"); // ✅ redirect
            exit();
        } else {
            echo "<script>alert('Invalid password.'); window.location='login.php';</script>";
            exit();
        }
    } else {
        echo "<script>alert('User not found.'); window.location='login.php';</script>";
        exit();
    }
}
?>
