function toggleCart() {
    const cartOverlay = document.getElementById('cartOverlay');
    cartOverlay.classList.toggle('translate-x-full');
}

function updateCartItemsUI() {
    const cartItems = document.getElementById('cartItems');
    cartItems.innerHTML = cart.items.map(item => `
        <div class="flex items-center space-x-4">
            <img src="${item.image}" alt="${item.name}" class="w-16 h-16 object-cover rounded">
            <div class="flex-1">
                <h4 class="font-semibold">${item.name}</h4>
                <div class="flex items-center space-x-2">
                    <input type="number" min="1" value="${item.quantity}" 
                           onchange="cart.updateQuantity(${item.id}, this.value)"
                           class="w-16 border rounded px-2 py-1">
                    <span>× $${item.price}</span>
                </div>
            </div>
            <button onclick="cart.removeItem(${item.id})" class="text-red-500 hover:text-red-700">
                <i class="fas fa-trash"></i>
            </button>
        </div>
    `).join('');
}

function proceedToCheckout() {
    if (cart.items.length > 0) {
        window.location.href = '/checkout.html';
    } else {
        showNotification('Your cart is empty!');
    }
}
