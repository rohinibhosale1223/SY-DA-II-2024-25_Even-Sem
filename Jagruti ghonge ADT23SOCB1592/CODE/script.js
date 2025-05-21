document.addEventListener('DOMContentLoaded', () => {
  const profileIcon = document.querySelector('.icons .profile-icon');
  const profilePopup = document.querySelector('.profile-popup');
  const navBox = document.querySelector('.nav-box');
  const menuToggle = document.querySelector('.menu-toggle');

  // Toggle profile popup
  profileIcon?.addEventListener('click', (e) => {
    e.stopPropagation(); // prevent triggering document click
    profilePopup.classList.toggle('show');
  });

  // Close profile popup when clicking outside
  document.addEventListener('click', (e) => {
    if (
      profilePopup.classList.contains('show') &&
      !profilePopup.contains(e.target) &&
      !profileIcon.contains(e.target)
    ) {
      profilePopup.classList.remove('show');
    }
  });

  // Mobile nav toggle
  menuToggle?.addEventListener('click', () => {
    navBox.classList.toggle('active');
  });
});
