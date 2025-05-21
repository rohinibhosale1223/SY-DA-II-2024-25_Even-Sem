const API_URL = 'books.json'; // Replace with actual API or data file

document.addEventListener('DOMContentLoaded', () => {
  fetch(API_URL)
    .then(response => response.json())
    .then(data => renderBooks(data))
    .catch(error => {
      document.getElementById('bookList').innerHTML = `
        <p class="loading-text">⚠️ Failed to load books. Please try again later.</p>`;
      console.error("Error fetching books:", error);
    });
});

function renderBooks(books) {
  const bookList = document.getElementById('bookList');
  bookList.innerHTML = '';

  books.forEach(book => {
    const card = document.createElement('div');
    card.className = 'book-card';
    card.innerHTML = `
      <img src="${book.image}" alt="${book.title}">
      <h4>${book.title}</h4>
      <p>by ${book.author}</p>
      <p>${book.price}</p>
      <button class="add-to-cart">Add to Cart</button>
    `;
    bookList.appendChild(card);
  });

  attachCartListeners();
}

function attachCartListeners() {
  const cart = JSON.parse(localStorage.getItem('cart')) || [];

  document.querySelectorAll('.add-to-cart').forEach(button => {
    button.addEventListener('click', () => {
      const card = button.closest('.book-card');
      const title = card.querySelector('h4').textContent;
      const author = card.querySelector('p').textContent;
      const price = card.querySelectorAll('p')[1].textContent;
      const image = card.querySelector('img').src;

      const book = { title, author, price, image };
      cart.push(book);
      localStorage.setItem('cart', JSON.stringify(cart));

      button.textContent = "✓ Added";
      button.disabled = true;
      setTimeout(() => {
        button.textContent = "Add to Cart";
        button.disabled = false;
      }, 1500);
    });
  });
}
