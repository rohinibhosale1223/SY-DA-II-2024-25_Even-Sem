let cart = [];
let total = 0;

function addToCart(albumName, price) {
    cart.push({ name: albumName, price: price });
    total += price;
    updateCart();
}

function updateCart() {
    const cartList = document.getElementById('cart-items');
    cartList.innerHTML = '';
    cart.forEach((item, index) => {
        const li = document.createElement('li');
        li.textContent = `${item.name} - $${item.price.toFixed(2)}`;

        const removeBtn = document.createElement('button');
        removeBtn.textContent = 'Remove';
        removeBtn.onclick = () => removeFromCart(index);

        li.appendChild(removeBtn);
        cartList.appendChild(li);
    });
    document.getElementById('total-price').innerText = total.toFixed(2);
}

function removeFromCart(index) {
    total -= cart[index].price;
    cart.splice(index, 1);
    updateCart();
}
