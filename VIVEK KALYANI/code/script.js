function togglePassword(id) {
  const field = document.getElementById(id);
  field.type = field.type === 'password' ? 'text' : 'password';
}

function validateRegister() {
  const pass = document.getElementById("password").value;
  const confirm = document.getElementById("confirm-password").value;
  if (pass !== confirm) {
    alert("Passwords do not match!");
    return false;
  }
  return true;
}

function validateLogin() {
  const email = document.getElementById("login-email").value;
  const pass = document.getElementById("login-password").value;
  if (!email || !pass) {
    alert("Please fill in all fields.");
    return false;
  }
  return true;
}

// script.js
function displayCart() {
  const cartItems = JSON.parse(localStorage.getItem('cart')) || [];
  const cartItemsContainer = document.getElementById('cart-items');
  const cartTotalContainer = document.getElementById('cart-total');
  cartItemsContainer.innerHTML = '';
  let total = 0;

  cartItems.forEach(item => {
    const itemDiv = document.createElement('div');
    itemDiv.className = 'cart-item';
    itemDiv.innerHTML = `<p>${item.title} - ₹${item.price}</p>`;
    cartItemsContainer.appendChild(itemDiv);
    total += item.price;
  });

  cartTotalContainer.innerHTML = `<h3>Total: ₹${total}</h3>`;
}

window.onload = displayCart;

// script.js
let cart = [];

function addToCart(title, price) {
  cart.push({ title, price });
  alert(`${title} has been added to your cart.`);
  // Optionally, store cart in localStorage
  localStorage.setItem('cart', JSON.stringify(cart));
}

document.getElementById('trailerFrame').src = "https://www.youtube.com/embed/VIDEO_ID";


