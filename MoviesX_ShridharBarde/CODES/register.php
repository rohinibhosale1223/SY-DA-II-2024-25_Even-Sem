<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>MoviesX - Register</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"/>
  <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;700&display=swap" rel="stylesheet"/>
  <style>
    body {
      font-family: 'Orbitron', sans-serif;
      background: linear-gradient(to right, cyan, red);
      margin: 0;
      padding: 0;
      height: 100vh;
      display: flex;
      justify-content: center;
      align-items: center;
    }

    .nav {
      display: flex;
      justify-content: center;
      background: black;
      padding: 15px;
      position: fixed;
      top: 0;
      width: 100%;
      z-index: 1000;
    }

    .nav a {
      color: white;
      text-decoration: none;
      padding: 12px 20px;
      font-size: 18px;
      transition: 0.3s;
    }

    .nav a:hover {
      background: red;
      border-radius: 5px;
    }

    .register-container {
      background: black;
      padding: 30px;
      border-radius: 10px;
      color: white;
      width: 350px;
      text-align: center;
      margin-top: 80px;
    }

    input, select, button {
      width: 100%;
      padding: 10px;
      margin: 10px 0;
      border-radius: 5px;
      border: 2px solid transparent;
      font-family: 'Orbitron', sans-serif;
      background: #111;
      color: white;
    }

    input.valid, select.valid {
      border-color: #00ff99;
      box-shadow: 0 0 8px #00ff99;
    }

    input.invalid, select.invalid {
      border-color: #ff4d4d;
      box-shadow: 0 0 8px #ff4d4d;
    }

    button {
      background: red;
      color: white;
      cursor: pointer;
    }

    button:hover {
      background: cyan;
      color: black;
    }

    .back {
      color: white;
      text-decoration: none;
      display: block;
      margin-top: 10px;
    }

    .back a {
      color: cyan;
    }

    .error {
      color: red;
      font-size: 12px;
      text-align: left;
      margin-bottom: -5px;
    }

    .password-container {
      position: relative;
    }

    .password-container i {
      position: absolute;
      right: 10px;
      top: 35%;
      cursor: pointer;
      color: white;
    }

    input::placeholder,
    select {
      color: rgba(255, 255, 255, 0.6);
    }

    input[type="date"]::-webkit-datetime-edit {
      color: rgba(255, 255, 255, 0.6);
    }

    select:invalid {
      color: rgba(255, 255, 255, 0.6);
    }
  </style>
</head>
<body>

  <nav class="nav">
    <a href="Movie.html">Home</a>
    <a href="products.html">Products</a>
    <a href="login.html">Login</a>
  </nav>

  <div class="register-container">
    <h2>Create Your MoviesX Account</h2>
    <form id="registerForm" method="POST" action="register.php"> <!-- Use POST to send data to register.php -->
      <input type="text" name="username" id="username" placeholder="Username" required />
      <div id="username-error" class="error"></div>

      <input type="email" name="email" id="email" placeholder="Email" required />
      <div id="email-error" class="error"></div>

      <div class="password-container">
        <input type="password" name="password" id="password" placeholder="Password" required />
        <i class="fa fa-eye" id="togglePassword"></i>
      </div>
      <div id="password-error" class="error"></div>

      <input type="date" name="dob" id="dob" placeholder="Date of Birth" required />
      <div id="dob-error" class="error"></div>

      <select name="genre" id="genre" required>
        <option value="">Favorite Genre</option>
        <option>Action</option>
        <option>Comedy</option>
        <option>Drama</option>
        <option>Sci-Fi</option>
        <option>Horror</option>
        <option>Romance</option>
        <option>Thriller</option>
        <option>Animation</option>
      </select>
      <div id="genre-error" class="error"></div>

      <button type="submit">Register</button>
    </form>
    <span class="back">Already have an account? <a href="login.html">Login</a></span>
  </div>

  <script>
    const form = document.getElementById('registerForm');
    const togglePassword = document.getElementById('togglePassword');
    const passwordInput = document.getElementById('password');

    togglePassword.addEventListener('click', () => {
      const type = passwordInput.type === 'password' ? 'text' : 'password';
      passwordInput.type = type;
      togglePassword.classList.toggle('fa-eye');
      togglePassword.classList.toggle('fa-eye-slash');
    });

    function setValidationState(input, isValid) {
      input.classList.remove('valid', 'invalid');
      input.classList.add(isValid ? 'valid' : 'invalid');
    }

    form.addEventListener('input', (event) => {
      const username = document.getElementById('username');
      const email = document.getElementById('email');
      const password = document.getElementById('password');
      const dob = document.getElementById('dob');
      const genre = document.getElementById('genre');

      if (event.target.id === 'username') {
        const isValid = username.value.length >= 6 && /[!@#$%^&*(),.?":{}|<>]/g.test(username.value);
        document.getElementById('username-error').textContent = isValid ? '' : 'Username must be at least 6 characters and contain a special character.';
        setValidationState(username, isValid);
      }

      if (event.target.id === 'email') {
        const isValid = /@/.test(email.value);
        document.getElementById('email-error').textContent = isValid ? '' : 'Email must contain @';
        setValidationState(email, isValid);
      }

      if (event.target.id === 'password') {
        const isValid = password.value.length >= 8 && /[0-9]/.test(password.value) &&
          /[a-z]/.test(password.value) && /[!@#$%^&*(),.?":{}|<>]/.test(password.value);
        document.getElementById('password-error').textContent = isValid ? '' : 'Password must be at least 8 characters, with one digit, one lowercase letter, and one special character.';
        setValidationState(password, isValid);
      }

      if (event.target.id === 'dob') {
        const isValid = dob.value !== '';
        document.getElementById('dob-error').textContent = isValid ? '' : 'Please enter your Date of Birth.';
        setValidationState(dob, isValid);
      }

      if (event.target.id === 'genre') {
        const isValid = genre.value !== '';
        document.getElementById('genre-error').textContent = isValid ? '' : 'Please select a genre.';
        setValidationState(genre, isValid);
      }
    });

    form.addEventListener('submit', (event) => {
      event.preventDefault();

      const errors = [
        document.getElementById('username-error').textContent,
        document.getElementById('email-error').textContent,
        document.getElementById('password-error').textContent,
        document.getElementById('dob-error').textContent,
        document.getElementById('genre-error').textContent
      ];

      if (errors.some(err => err !== '')) {
        alert("Please fix the errors before submitting.");
      } else {
        form.submit();  // Submits the form to PHP backend
      }
    });
  </script>
  <?php
include 'connect.php'; // Include the database connection

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'] ?? '';
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
    $dob = $_POST['dob'] ?? '';
    $genre = $_POST['genre'] ?? '';

    // Validate the fields (optional)
    if (empty($username) || empty($email) || empty($password) || empty($dob) || empty($genre)) {
        echo "<script>alert('Please fill in all fields.'); window.location='register.php';</script>";
        exit();
    }

    // Hash the password before storing it
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Prepare SQL to insert the data into the users table
    $sql = "INSERT INTO users (username, email, password, dob, genre) 
            VALUES ('$username', '$email', '$hashedPassword', '$dob', '$genre')";

    if ($conn->query($sql) === TRUE) {
        echo "<script>alert('Registration successful!'); window.location='login.php';</script>";
        exit();
    } else {
        echo "<script>alert('Error: " . $conn->error . "'); window.location='register.php';</script>";
        exit();
    }
}
?>


</body>
</html>
