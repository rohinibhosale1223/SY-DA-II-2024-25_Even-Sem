// register.js

document.addEventListener("DOMContentLoaded", () => {
  const form = document.getElementById("register-form");

  form.addEventListener("submit", (e) => {
    e.preventDefault();

    const username = document.getElementById("username").value;
    const email = document.getElementById("email").value;
    const password = document.getElementById("password").value;

    const userData = {
      username,
      email,
      password
    };

    localStorage.setItem("basketUser", JSON.stringify(userData));
    alert("Registration successful! Please log in.");
    window.location.href = "login.html";
  });
});
