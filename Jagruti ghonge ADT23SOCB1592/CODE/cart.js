// cart.js

document.addEventListener("DOMContentLoaded", () => {
  const cartItemsContainer = document.getElementById("cart-items");
  const totalPriceEl = document.getElementById("total-price");

  function loadCart() {
    const cart = JSON.parse(localStorage.getItem("basketCart")) || [];
    cartItemsContainer.innerHTML = "";
    let total = 0;

    cart.forEach((item, index) => {
      const card = document.createElement("div");
      card.className = "cart-item";
      card.innerHTML = `
        <img src="${item.image}" alt="${item.name}" />
        <div>
          <h3>${item.name}</h3>
          <p>Price: ₹${item.price}</p>
          <button class="btn remove-item" data-index="${index}">Remove</button>
        </div>
      `;
      cartItemsContainer.appendChild(card);
      total += item.price;
    });

    totalPriceEl.textContent = total;
  }

  cartItemsContainer.addEventListener("click", (e) => {
    if (e.target.classList.contains("remove-item")) {
      const index = parseInt(e.target.getAttribute("data-index"));
      let cart = JSON.parse(localStorage.getItem("basketCart")) || [];
      cart.splice(index, 1);
      localStorage.setItem("basketCart", JSON.stringify(cart));
      loadCart();
    }
  });

  loadCart();
});
