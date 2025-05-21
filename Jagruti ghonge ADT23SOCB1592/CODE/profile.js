// profile.js

document.addEventListener("DOMContentLoaded", () => {
  const usernameDisplay = document.getElementById("profile-username");
  const emailDisplay = document.getElementById("profile-email");
  const logoutBtn = document.getElementById("logout-btn");

  const userData = JSON.parse(localStorage.getItem("basketUser"));

  if (userData) {
    usernameDisplay.textContent = `👤 ${userData.username}`;
    emailDisplay.textContent = `📧 ${userData.email}`;
  } else {
    usernameDisplay.textContent = "No user found";
    emailDisplay.textContent = "";
  }

  logoutBtn?.addEventListener("click", () => {
    localStorage.removeItem("basketUser");
    alert("Logged out!");
    window.location.href = "login.html";
  });
});
