<?php
include 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $dob = $_POST["dob"];
    $password = password_hash($_POST["password"], PASSWORD_DEFAULT);

    $stmt = $conn->prepare("INSERT INTO users (username, dob, password) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $username, $dob, $password);

    if ($stmt->execute()) {
        echo "Registration successful!";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
}
?>

<!-- Simple registration form -->
<form method="post">
    Username: <input type="text" name="username" required><br>
    Date of Birth: <input type="date" name="dob" required><br>
    Password: <input type="password" name="password" required><br>
    <button type="submit">Register</button>
</form>