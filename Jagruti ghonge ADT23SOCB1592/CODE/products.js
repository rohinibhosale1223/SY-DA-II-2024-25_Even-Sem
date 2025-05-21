// products.js

document.addEventListener("DOMContentLoaded", () => {
  const products = [
    { id: 1, name: "Fresh Apples", price: 99, image: "assets/product1.jpg" },
    { id: 2, name: "Organic Bananas", price: 79, image: "assets/product2.jpg" },
    { id: 3, name: "Whole Wheat Bread", price: 45, image: "assets/product3.jpg" },
    { id: 4, name: "Almond Milk", price: 120, image: "assets/product4.jpg" },
    { id: 5, name: "Brown Rice", price: 110, image: "assets/product5.jpg" },
    { id: 6, name: "Tomatoes (1kg)", price: 50, image: "assets/product6.jpg" },
    { id: 7, name: "Potatoes (1kg)", price: 30, image: "assets/product7.jpg" },
    { id: 8, name: "Green Tea", price: 150, image: "assets/product8.jpg" },
    { id: 9, name: "Cooking Oil", price: 160, image: "assets/product9.jpg" },
  ];

  const productList = document.getElementById("product-list");
  const searchInput = document.getElementById("search-input");

  function renderProducts(filteredProducts) {
    productList.innerHTML = "";
    filteredProducts.forEach((product) => {
      const card = document.createElement("div");
      card.className = "product-card";
      card.innerHTML = `
        <img src="${product.image}" alt="${product.name}" />
        <h3>${product.name}</h3>
        <p>Price: ₹${product.price}</p>
        <button class="btn add-to-cart" data-id="${product.id}">Add to Cart</button>
      `;
      productList.appendChild(card);
    });
  }

  function addToCart(productId) {
    let cart = JSON.parse(localStorage.getItem("basketCart")) || [];
    const product = products.find((p) => p.id === parseInt(productId));
    if (product) {
      cart.push(product);
      localStorage.setItem("basketCart", JSON.stringify(cart));
      alert("Added to cart!");
    }
  }

  productList.addEventListener("click", (e) => {
    if (e.target.classList.contains("add-to-cart")) {
      const id = e.target.getAttribute("data-id");
      addToCart(id);
    }
  });

  searchInput.addEventListener("input", () => {
    const query = searchInput.value.toLowerCase();
    const filtered = products.filter((p) => p.name.toLowerCase().includes(query));
    renderProducts(filtered);
  });

  renderProducts(products);
});
