// main.js

document.addEventListener("DOMContentLoaded", () => {
  const profileIcon = document.getElementById("profile-icon");
  const profilePopup = document.getElementById("profile-popup");

  profileIcon?.addEventListener("click", () => {
    profilePopup.style.display =
      profilePopup.style.display === "block" ? "none" : "block";
  });

  // Hide profile popup on outside click
  document.addEventListener("click", (e) => {
    if (
      !profilePopup.contains(e.target) &&
      e.target !== profileIcon &&
      profilePopup.style.display === "block"
    ) {
      profilePopup.style.display = "none";
    }
  });
});
