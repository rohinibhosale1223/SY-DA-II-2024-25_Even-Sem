<?php
// Database connection
$host = "localhost";
$user = "root";
$password = "";
$database = "da";

$conn = new mysqli($host, $user, $password, $database);

// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $name = $_POST['name'];
  $email = $_POST['email'];
  $role = $_POST['role'];
  $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Hashing the password

  $stmt = $conn->prepare("INSERT INTO users (name, email, role, password) VALUES (?, ?, ?, ?)");
  $stmt->bind_param("ssss", $name, $email, $role, $password);

  if ($stmt->execute()) {
    echo "<script>alert('Registration successful!');</script>";
  } else {
    echo "<script>alert('Error: " . $stmt->error . "');</script>";
  }

  $stmt->close();
  $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Library Registration | MyLibrary</title>
  <style>
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
      font-family: 'Segoe UI', sans-serif;
    }

    body {
      background-color: #f5f5f5;
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;
      height: 100vh;
    }

    .form-container {
      background-color: #fff;
      padding: 30px 40px;
      border-radius: 10px;
      box-shadow: 0 0 15px rgba(0,0,0,0.1);
      width: 100%;
      max-width: 400px;
    }

    .form-container h2 {
      text-align: center;
      color: #6F4F37;
      margin-bottom: 20px;
    }

    .form-group {
      margin-bottom: 15px;
    }

    label {
      display: block;
      margin-bottom: 6px;
      color: #333;
    }

    input, select {
      width: 100%;
      padding: 10px;
      border: 1px solid #ccc;
      border-radius: 6px;
    }

    input[type="checkbox"] {
      width: auto;
      margin-right: 8px;
    }

    .terms {
      display: flex;
      align-items: center;
      font-size: 14px;
      margin-top: 10px;
    }

    button {
      width: 100%;
      padding: 10px;
      background-color: #6DBF3A;
      color: #fff;
      border: none;
      border-radius: 6px;
      font-size: 16px;
      cursor: pointer;
      margin-top: 15px;
    }

    button:hover {
      background-color: #5aa732;
    }

    .login-link {
      text-align: center;
      margin-top: 15px;
      font-size: 14px;
    }

    .login-link a {
      color: #6F4F37;
      text-decoration: none;
    }

    .login-link a:hover {
      text-decoration: underline;
    }
  </style>
</head>
<body>

  <div class="form-container">
    <h2>Create Library Account</h2>
    <form action="" method="post">
      <div class="form-group">
        <label for="name">Full Name:</label>
        <input type="text" id="name" name="name" placeholder="Enter your full name" required />
      </div>

      <div class="form-group">
        <label for="email">Email Address:</label>
        <input type="email" id="email" name="email" placeholder="Enter your email" required />
      </div>

      <div class="form-group">
        <label for="role">Select Role:</label>
        <select id="role" name="role" required>
          <option value="">-- Select --</option>
          <option value="student">Student</option>
          <option value="faculty">Faculty</option>
          <option value="admin">Librarian/Admin</option>
        </select>
      </div>

      <div class="form-group">
        <label for="password">Create Password:</label>
        <input type="password" id="password" name="password" placeholder="Enter password" required />
      </div>

      <div class="terms">
        <input type="checkbox" id="terms" required />
        <label for="terms">I accept the terms & conditions</label>
      </div>

      <button type="submit">Register</button>
    </form>

    <div class="login-link">
      Already have an account? <a href="login.html">Login here</a>
    </div>
  </div>

</body>
</html>
