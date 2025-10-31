// API Base URL
const API_BASE = 'http://localhost/NETH%20Bookhive/backend/api';

// Cart Page JavaScript
document.addEventListener('DOMContentLoaded', function() {
    initializeCart();
});

async function initializeCart() {
    await loadCartItems();
    setupCartEventListeners();
}

// Load Cart Items
async function loadCartItems() {
    const user = JSON.parse(localStorage.getItem('user'));
    const cartItemsContainer = document.getElementById('cartItems');
    const cartSummary = document.getElementById('cartSummary');
    
    if (!user) {
        showLoginPrompt();
        return;
    }

    try {
        const response = await fetch(`${API_BASE}/cart.php?user_id=${user.id}`);
        const cartItems = await response.json();

        if (cartItems.length > 0) {
            displayCartItems(cartItems);
            updateCartSummary(cartItems);
        } else {
            showEmptyCart();
        }
    } catch (error) {
        console.error('Error loading cart:', error);
        cartItemsContainer.innerHTML = `
            <div class="message error">
                Failed to load cart items. Please try again later.
            </div>
        `;
    }
}

// Display Cart Items
function displayCartItems(cartItems) {
    const container = document.getElementById('cartItems');
    const colors = ['#667eea', '#764ba2', '#f093fb', '#f5576c', '#4facfe', '#00f2fe'];
    
    container.innerHTML = cartItems.map(item => {
        const color = colors[(item.book_id - 1) % colors.length];
        const cartImage = item.image_url && item.image_url.includes('unsplash')
            ? `<img src="${item.image_url}" alt="${item.title}" style="width: 80px; height: 100px; object-fit: cover; border-radius: 5px;" onerror="this.parentElement.innerHTML='<div style=\\'background: linear-gradient(135deg, ${color} 0%, ${color}dd 100%); width: 80px; height: 100px; display: flex; align-items: center; justify-content: center; border-radius: 5px;\\'><i class=\\'fas fa-book\\' style=\\'color: white; font-size: 1.5rem;\\'></i></div>';">`
            : `<div style="background: linear-gradient(135deg, ${color} 0%, ${color}dd 100%); width: 80px; height: 100px; display: flex; align-items: center; justify-content: center; border-radius: 5px;">
                    <i class="fas fa-book" style="font-size: 1.5rem; color: rgba(255,255,255,0.9);"></i>
                </div>`;
        
        return `
        <div class="cart-item" data-book-id="${item.book_id}">
            <div class="cart-item-image" style="min-width: 80px;">
                ${cartImage}
            </div>
            <div class="cart-item-details">
                <h4 class="cart-item-title">${item.title}</h4>
                <p class="cart-item-author">by ${item.author}</p>
                <p class="cart-item-price">$${item.price}</p>
            </div>
            <div class="cart-item-quantity">
                <button class="quantity-btn" onclick="updateQuantity(${item.book_id}, ${item.quantity - 1})">
                    <i class="fas fa-minus"></i>
                </button>
                <span class="quantity">${item.quantity}</span>
                <button class="quantity-btn" onclick="updateQuantity(${item.book_id}, ${item.quantity + 1})">
                    <i class="fas fa-plus"></i>
                </button>
            </div>
            <div class="cart-item-total">
                $${(item.price * item.quantity).toFixed(2)}
            </div>
            <button class="btn btn-danger btn-sm" onclick="removeFromCart(${item.book_id})">
                <i class="fas fa-trash"></i>
            </button>
        </div>
    `}).join('');
}

// Update Cart Summary
function updateCartSummary(cartItems) {
    const subtotal = cartItems.reduce((sum, item) => sum + (item.price * item.quantity), 0);
    const shipping = subtotal > 0 ? 5.00 : 0; // $5 shipping
    const tax = subtotal * 0.08; // 8% tax
    const total = subtotal + shipping + tax;

    const summaryHTML = `
        <div class="summary-row">
            <span>Subtotal:</span>
            <span>$${subtotal.toFixed(2)}</span>
        </div>
        <div class="summary-row">
            <span>Shipping:</span>
            <span>$${shipping.toFixed(2)}</span>
        </div>
        <div class="summary-row">
            <span>Tax (8%):</span>
            <span>$${tax.toFixed(2)}</span>
        </div>
        <div class="summary-row summary-total">
            <span>Total:</span>
            <span>$${total.toFixed(2)}</span>
        </div>
        <button class="btn btn-primary btn-block" onclick="proceedToCheckout()">
            Proceed to Checkout
        </button>
    `;

    document.getElementById('cartSummary').innerHTML = summaryHTML;
}

// Show Empty Cart
function showEmptyCart() {
    const container = document.getElementById('cartItems');
    const summary = document.getElementById('cartSummary');
    
    container.innerHTML = `
        <div class="empty-state">
            <i class="fas fa-shopping-cart"></i>
            <h3>Your Cart is Empty</h3>
            <p>Add some books to get started!</p>
            <a href="shop.html" class="btn btn-primary">Browse Books</a>
        </div>
    `;
    
    summary.innerHTML = '';
}

// Show Login Prompt
function showLoginPrompt() {
    const container = document.getElementById('cartItems');
    const summary = document.getElementById('cartSummary');
    
    container.innerHTML = `
        <div class="empty-state">
            <i class="fas fa-sign-in-alt"></i>
            <h3>Please Login</h3>
            <p>You need to be logged in to view your cart.</p>
            <a href="login.html" class="btn btn-primary">Login</a>
            <a href="register.html" class="btn btn-secondary">Register</a>
        </div>
    `;
    
    summary.innerHTML = '';
}

// Setup Cart Event Listeners
function setupCartEventListeners() {
    // Clear cart button
    const clearCartBtn = document.getElementById('clearCartBtn');
    if (clearCartBtn) {
        clearCartBtn.addEventListener('click', clearCart);
    }
}

// Update Quantity
async function updateQuantity(bookId, newQuantity) {
    const user = JSON.parse(localStorage.getItem('user'));
    
    if (newQuantity < 1) {
        removeFromCart(bookId);
        return;
    }

    try {
        const response = await fetch(`${API_BASE}/cart.php`, {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                user_id: user.id,
                book_id: bookId,
                quantity: newQuantity
            })
        });

        if (response.ok) {
            await loadCartItems(); // Reload cart
            updateCartCount(); // Update cart count in header
        } else {
            showMessage('Failed to update quantity', 'error');
        }
    } catch (error) {
        console.error('Error updating quantity:', error);
        showMessage('Failed to update quantity', 'error');
    }
}

// Remove from Cart
async function removeFromCart(bookId) {
    const user = JSON.parse(localStorage.getItem('user'));
    
    if (!confirm('Are you sure you want to remove this item from your cart?')) {
        return;
    }

    try {
        const response = await fetch(`${API_BASE}/cart.php`, {
            method: 'DELETE',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                user_id: user.id,
                book_id: bookId
            })
        });

        if (response.ok) {
            showMessage('Item removed from cart', 'success');
            await loadCartItems(); // Reload cart
            updateCartCount(); // Update cart count in header
        } else {
            showMessage('Failed to remove item', 'error');
        }
    } catch (error) {
        console.error('Error removing from cart:', error);
        showMessage('Failed to remove item', 'error');
    }
}

// Clear Cart
async function clearCart() {
    const user = JSON.parse(localStorage.getItem('user'));
    
    if (!confirm('Are you sure you want to clear your entire cart?')) {
        return;
    }

    try {
        // Since we don't have a clear cart endpoint, remove items one by one
        const response = await fetch(`${API_BASE}/cart.php?user_id=${user.id}`);
        const cartItems = await response.json();
        
        for (const item of cartItems) {
            await fetch(`${API_BASE}/cart.php`, {
                method: 'DELETE',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    user_id: user.id,
                    book_id: item.book_id
                })
            });
        }
        
        showMessage('Cart cleared successfully', 'success');
        await loadCartItems(); // Reload cart
        updateCartCount(); // Update cart count in header
    } catch (error) {
        console.error('Error clearing cart:', error);
        showMessage('Failed to clear cart', 'error');
    }
}

// Proceed to Checkout
function proceedToCheckout() {
    const user = JSON.parse(localStorage.getItem('user'));
    if (!user) {
        showMessage('Please login to proceed to checkout', 'error');
        setTimeout(() => {
            window.location.href = 'login.html';
        }, 1500);
        return;
    }

    window.location.href = 'checkout.html';
}