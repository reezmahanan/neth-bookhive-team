// API Base URL
const API_BASE = 'http://localhost/NETH%20Bookhive/backend/api';

// Checkout Page JavaScript
document.addEventListener('DOMContentLoaded', function() {
    initializeCheckout();
});

async function initializeCheckout() {
    await loadCheckoutData();
    setupCheckoutForm();
}

// Load Checkout Data
async function loadCheckoutData() {
    const user = JSON.parse(localStorage.getItem('user'));
    
    if (!user) {
        showLoginPrompt();
        return;
    }

    try {
        // Load cart items
        const response = await fetch(`${API_BASE}/cart.php?user_id=${user.id}`);
        const cartItems = await response.json();

        if (cartItems.length > 0) {
            displayOrderSummary(cartItems);
            populateUserInfo(user);
        } else {
            showEmptyCart();
        }
    } catch (error) {
        console.error('Error loading checkout data:', error);
        showError('Failed to load checkout data');
    }
}

// Display Order Summary
function displayOrderSummary(cartItems) {
    const container = document.getElementById('orderSummary');
    const subtotal = cartItems.reduce((sum, item) => sum + (item.price * item.quantity), 0);
    const shipping = subtotal > 0 ? 5.00 : 0;
    const tax = subtotal * 0.08;
    const total = subtotal + shipping + tax;

    container.innerHTML = `
        <h3>Order Summary</h3>
        ${cartItems.map(item => `
            <div class="order-item">
                <div class="order-item-info">
                    <h4>${item.title}</h4>
                    <p>by ${item.author}</p>
                    <p>Quantity: ${item.quantity}</p>
                </div>
                <div class="order-item-price">
                    $${(item.price * item.quantity).toFixed(2)}
                </div>
            </div>
        `).join('')}
        <div class="order-totals">
            <div class="total-row">
                <span>Subtotal:</span>
                <span>$${subtotal.toFixed(2)}</span>
            </div>
            <div class="total-row">
                <span>Shipping:</span>
                <span>$${shipping.toFixed(2)}</span>
            </div>
            <div class="total-row">
                <span>Tax:</span>
                <span>$${tax.toFixed(2)}</span>
            </div>
            <div class="total-row grand-total">
                <span>Total:</span>
                <span>$${total.toFixed(2)}</span>
            </div>
        </div>
    `;
}

// Populate User Information
function populateUserInfo(user) {
    document.getElementById('email').value = user.email;
    // You can pre-populate other fields if you have more user data
}

// Setup Checkout Form
function setupCheckoutForm() {
    const checkoutForm = document.getElementById('checkoutForm');
    if (checkoutForm) {
        checkoutForm.addEventListener('submit', handleCheckout);
    }

    // Real-time validation
    setupFormValidation();
}

// Setup Form Validation
function setupFormValidation() {
    const inputs = document.querySelectorAll('#checkoutForm input, #checkoutForm select');
    inputs.forEach(input => {
        input.addEventListener('blur', validateField);
        input.addEventListener('input', clearFieldError);
    });
}

// Validate Field
function validateField(e) {
    const field = e.target;
    const value = field.value.trim();
    let isValid = true;
    let errorMessage = '';

    switch (field.id) {
        case 'fullName':
            if (value.length < 2) {
                isValid = false;
                errorMessage = 'Full name must be at least 2 characters long';
            }
            break;
        case 'email':
            if (!isValidEmail(value)) {
                isValid = false;
                errorMessage = 'Please enter a valid email address';
            }
            break;
        case 'phone':
            if (!isValidPhone(value)) {
                isValid = false;
                errorMessage = 'Please enter a valid phone number';
            }
            break;
        case 'address':
            if (value.length < 5) {
                isValid = false;
                errorMessage = 'Please enter a complete address';
            }
            break;
        case 'city':
            if (value.length < 2) {
                isValid = false;
                errorMessage = 'Please enter a valid city';
            }
            break;
        case 'zipCode':
            if (!isValidZipCode(value)) {
                isValid = false;
                errorMessage = 'Please enter a valid ZIP code';
            }
            break;
        case 'cardNumber':
            if (!isValidCardNumber(value)) {
                isValid = false;
                errorMessage = 'Please enter a valid card number';
            }
            break;
        case 'expiryDate':
            if (!isValidExpiryDate(value)) {
                isValid = false;
                errorMessage = 'Please enter a valid expiry date (MM/YY)';
            }
            break;
        case 'cvv':
            if (!isValidCVV(value)) {
                isValid = false;
                errorMessage = 'Please enter a valid CVV';
            }
            break;
    }

    if (!isValid) {
        showFieldError(field, errorMessage);
    } else {
        clearFieldError({ target: field });
    }

    return isValid;
}

// Validation Functions
function isValidEmail(email) {
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return emailRegex.test(email);
}

function isValidPhone(phone) {
    const phoneRegex = /^[\+]?[1-9][\d]{0,15}$/;
    return phoneRegex.test(phone.replace(/[\s\-\(\)]/g, ''));
}

function isValidZipCode(zipCode) {
    const zipRegex = /^\d{5}(-\d{4})?$/;
    return zipRegex.test(zipCode);
}

function isValidCardNumber(cardNumber) {
    // Simple validation - in real app, use proper card validation library
    const cleaned = cardNumber.replace(/\s/g, '');
    return /^\d{13,19}$/.test(cleaned);
}

function isValidExpiryDate(expiryDate) {
    const regex = /^(0[1-9]|1[0-2])\/([0-9]{2})$/;
    if (!regex.test(expiryDate)) return false;

    const [month, year] = expiryDate.split('/');
    const now = new Date();
    const currentYear = now.getFullYear() % 100;
    const currentMonth = now.getMonth() + 1;

    if (parseInt(year) < currentYear) return false;
    if (parseInt(year) === currentYear && parseInt(month) < currentMonth) return false;

    return true;
}

function isValidCVV(cvv) {
    return /^\d{3,4}$/.test(cvv);
}

// Show Field Error
function showFieldError(field, message) {
    clearFieldError({ target: field });
    field.classList.add('error');
    
    const errorDiv = document.createElement('div');
    errorDiv.className = 'field-error';
    errorDiv.textContent = message;
    field.parentNode.appendChild(errorDiv);
}

// Clear Field Error
function clearFieldError(e) {
    const field = e.target;
    field.classList.remove('error');
    
    const existingError = field.parentNode.querySelector('.field-error');
    if (existingError) {
        existingError.remove();
    }
}

// Handle Checkout
async function handleCheckout(e) {
    e.preventDefault();
    
    const user = JSON.parse(localStorage.getItem('user'));
    if (!user) {
        showMessage('Please login to complete checkout', 'error');
        return;
    }

    // Validate all fields
    const form = e.target;
    const inputs = form.querySelectorAll('input, select');
    let allValid = true;

    inputs.forEach(input => {
        const event = new Event('blur');
        input.dispatchEvent(event);
        if (input.classList.contains('error')) {
            allValid = false;
        }
    });

    if (!allValid) {
        showMessage('Please fix the errors in the form', 'error');
        return;
    }

    // Show loading state
    const submitBtn = form.querySelector('button[type="submit"]');
    const originalText = submitBtn.textContent;
    submitBtn.disabled = true;
    submitBtn.innerHTML = '<div class="loading"></div> Processing Order...';

    try {
        // Simulate payment processing
        await processPayment();
        
        // Create order
        const orderData = collectOrderData(form, user.id);
        const orderResult = await createOrder(orderData);
        
        if (orderResult.success) {
            showOrderSuccess(orderResult.orderId);
        } else {
            throw new Error(orderResult.message);
        }
    } catch (error) {
        console.error('Checkout error:', error);
        showMessage('Checkout failed: ' + error.message, 'error');
    } finally {
        submitBtn.disabled = false;
        submitBtn.textContent = originalText;
    }
}

// Process Payment (Simulated)
function processPayment() {
    return new Promise((resolve, reject) => {
        // Simulate API call to payment gateway
        setTimeout(() => {
            // 90% success rate for demo
            if (Math.random() < 0.9) {
                resolve({ success: true });
            } else {
                reject(new Error('Payment processing failed. Please try again.'));
            }
        }, 2000);
    });
}

// Collect Order Data
function collectOrderData(form, userId) {
    const formData = new FormData(form);
    
    return {
        user_id: userId,
        shipping_address: {
            fullName: formData.get('fullName'),
            email: formData.get('email'),
            phone: formData.get('phone'),
            address: formData.get('address'),
            city: formData.get('city'),
            state: formData.get('state'),
            zipCode: formData.get('zipCode'),
            country: formData.get('country')
        },
        payment_method: formData.get('paymentMethod')
    };
}

// Create Order
async function createOrder(orderData) {
    // In a real application, this would call your order API
    // For demo purposes, we'll simulate order creation
    
    return new Promise((resolve) => {
        setTimeout(() => {
            const orderId = 'ORD' + Date.now();
            resolve({
                success: true,
                orderId: orderId,
                message: 'Order created successfully'
            });
        }, 1000);
    });
}

// Show Order Success
function showOrderSuccess(orderId) {
    const checkoutSection = document.querySelector('.checkout-section');
    checkoutSection.innerHTML = `
        <div class="order-success">
            <div class="success-icon">
                <i class="fas fa-check-circle"></i>
            </div>
            <h2>Order Confirmed!</h2>
            <p>Thank you for your purchase. Your order has been successfully placed.</p>
            <div class="order-details">
                <p><strong>Order ID:</strong> ${orderId}</p>
                <p><strong>Estimated Delivery:</strong> 3-5 business days</p>
            </div>
            <div class="success-actions">
                <a href="index.html" class="btn btn-primary">Continue Shopping</a>
                <a href="shop.html" class="btn btn-secondary">View Order Details</a>
            </div>
        </div>
    `;

    // Clear cart after successful order
    clearCartAfterOrder();
}

// Clear Cart After Order
async function clearCartAfterOrder() {
    const user = JSON.parse(localStorage.getItem('user'));
    if (user) {
        try {
            // Clear cart from server
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
            
            updateCartCount();
        } catch (error) {
            console.error('Error clearing cart after order:', error);
        }
    }
}

// Show Login Prompt
function showLoginPrompt() {
    const container = document.querySelector('.checkout-content');
    container.innerHTML = `
        <div class="empty-state">
            <i class="fas fa-sign-in-alt"></i>
            <h3>Please Login to Checkout</h3>
            <p>You need to be logged in to complete your purchase.</p>
            <a href="login.html" class="btn btn-primary">Login</a>
            <a href="register.html" class="btn btn-secondary">Register</a>
        </div>
    `;
}

// Show Empty Cart
function showEmptyCart() {
    const container = document.querySelector('.checkout-content');
    container.innerHTML = `
        <div class="empty-state">
            <i class="fas fa-shopping-cart"></i>
            <h3>Your Cart is Empty</h3>
            <p>Add some books to proceed to checkout.</p>
            <a href="shop.html" class="btn btn-primary">Browse Books</a>
        </div>
    `;
}

// Show Error
function showError(message) {
    const container = document.querySelector('.checkout-content');
    container.innerHTML = `
        <div class="message error">
            ${message}
        </div>
        <div style="text-align: center; margin-top: 2rem;">
            <a href="cart.html" class="btn btn-primary">Back to Cart</a>
        </div>
    `;
}