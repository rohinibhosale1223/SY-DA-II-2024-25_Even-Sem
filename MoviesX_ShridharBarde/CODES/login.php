<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>MoviesX - Login</title>
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

    .login-container {
      background: black;
      padding: 30px;
      border-radius: 10px;
      color: white;
      width: 350px;
      text-align: center;
      margin-top: 80px;
    }

    input, button {
      width: 100%;
      padding: 10px;
      margin: 10px 0;
      border-radius: 5px;
      border: 2px solid transparent;
      transition: border 0.3s, box-shadow 0.3s;
      font-family: 'Orbitron', sans-serif;
      background: #111;
      color: white;
    }

    input.valid {
      border-color: #00ff99;
      box-shadow: 0 0 8px #00ff99;
    }

    input.invalid {
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
  </style>
</head>
<body>
  <nav class="nav">
    <a href="Movie.html">Home</a>
    <a href="products.html">Products</a>
    <a href="register.html">Register</a>
  </nav>

  <div class="login-container">
    <h2>Login to MoviesX</h2>
    <form id="loginForm">
      <input type="text" id="username" placeholder="Username" required />
      <div id="username-error" class="error"></div>

      <div class="password-container">
        <input type="password" id="password" placeholder="Password" required />
        <i class="fa fa-eye" id="togglePassword"></i>
      </div>
      <div id="password-error" class="error"></div>

      <button type="submit">Login</button>
    </form>
    <span class="back">Don't have an account? <a href="register.html">Register</a></span>
  </div>


  <script>
    const loginForm = document.getElementById('loginForm');
    const togglePassword = document.getElementById('togglePassword');
    const passwordInput = document.getElementById('password');

    // Show/hide password
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

    loginForm.addEventListener('input', (event) => {
      const username = document.getElementById('username');
      const password = document.getElementById('password');

      // Username validation
      if (event.target.id === 'username') {
        const isValid = username.value.length >= 6 && /[!@#$%^&*(),.?":{}|<>]/g.test(username.value);
        document.getElementById('username-error').textContent = isValid ? '' : 'Username must be at least 6 characters and contain a special character.';
        setValidationState(username, isValid);
      }

      // Password validation
      if (event.target.id === 'password') {
        const isValid = password.value.length >= 8 && /[0-9]/.test(password.value) &&
          /[a-z]/.test(password.value) && /[!@#$%^&*(),.?":{}|<>]/.test(password.value);
        document.getElementById('password-error').textContent = isValid ? '' : 'Password must be at least 8 characters, with one digit, one lowercase letter, and one special character.';
        setValidationState(password, isValid);
      }
    });

    loginForm.addEventListener('submit', (event) => {
      const usernameError = document.getElementById('username-error').textContent;
      const passwordError = document.getElementById('password-error').textContent;

      if (usernameError || passwordError) {
        event.preventDefault();
        alert("Please fix the errors before submitting.");
      }
    });
  </script>
  
</body>
</html>
