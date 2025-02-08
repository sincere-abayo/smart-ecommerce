// Cart state management
const cart = {
    items: [],
    total: 0,

    addItem(product) {
        const existingItem = this.items.find(item => item.id === product.id);
        
        if (existingItem) {
            existingItem.quantity += 1;
        } else {
            this.items.push({
                ...product,
                quantity: 1
            });
        }
        
        this.updateTotal();
        this.updateCartUI();
        this.saveToLocalStorage();
    },

    removeItem(productId) {
        this.items = this.items.filter(item => item.id !== productId);
        this.updateTotal();
        this.updateCartUI();
        this.saveToLocalStorage();
    },

    updateQuantity(productId, quantity) {
        const item = this.items.find(item => item.id === productId);
        if (item) {
            item.quantity = parseInt(quantity);
            this.updateTotal();
            this.updateCartUI();
            this.saveToLocalStorage();
        }
    },

    updateTotal() {
        this.total = this.items.reduce((sum, item) => sum + (item.price * item.quantity), 0);
    },

    updateCartUI() {
        const cartCount = document.querySelector('.cart-count');
        const cartTotal = document.querySelector('.cart-total');
        
        cartCount.textContent = this.items.reduce((sum, item) => sum + item.quantity, 0);
        cartTotal.textContent = `$${this.total.toFixed(2)}`;
    },

    saveToLocalStorage() {
        localStorage.setItem('cart', JSON.stringify(this.items));
    },

    loadFromLocalStorage() {
        const savedCart = localStorage.getItem('cart');
        if (savedCart) {
            this.items = JSON.parse(savedCart);
            this.updateTotal();
            this.updateCartUI();
        }
    }
};

// Add to cart button click handler
function handleAddToCart(productId) {
    const product = {
        id: productId,
        name: document.querySelector(`#product-${productId} h3`).textContent,
        price: parseFloat(document.querySelector(`#product-${productId} .text-blue-600`).textContent.replace('$', '')),
        image: document.querySelector(`#product-${productId} img`).src
    };
    
    cart.addItem(product);
    showNotification('Product added to cart!');
}

// Cart notification
function showNotification(message) {
    const notification = document.createElement('div');
    notification.className = 'fixed top-4 right-4 bg-green-500 text-white px-6 py-3 rounded-lg shadow-lg z-50 transform transition-transform duration-300 translate-y-0';
    notification.textContent = message;
    
    document.body.appendChild(notification);
    
    setTimeout(() => {
        notification.classList.add('translate-y-[-100%]');
        setTimeout(() => notification.remove(), 300);
    }, 2000);
}

// Initialize cart on page load
document.addEventListener('DOMContentLoaded', () => {
    cart.loadFromLocalStorage();
});

function proceedToCheckout() {
    // Validate cart has items
    if (cart.items.length > 0) {
        window.location.href = '/checkout/payment.html';
    } else {
        showNotification('Your cart is empty!');
    }
}