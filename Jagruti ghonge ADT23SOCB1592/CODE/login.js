document.getElementById('login-form').addEventListener('submit', function(e) {
  e.preventDefault();
  // simple dummy validation for demo
  const username = this.username.value.trim();
  const password = this.password.value.trim();
  if(username === '' || password === '') {
    alert('Please enter username and password.');
    return;
  }
  // On successful login redirect to dashboard
  alert(`Welcome, ${username}! Redirecting to your dashboard.`);
  window.location.href = 'dashboard.html';
});
