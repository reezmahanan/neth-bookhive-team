// API Base URL
const API_BASE = 'http://localhost/NETH%20Bookhive/backend/api';

// Book Details Page JavaScript
document.addEventListener('DOMContentLoaded', function() {
    loadBookDetails();
});

// Load Book Details
async function loadBookDetails() {
    const params = getUrlParams();
    const bookId = params.id;
    
    if (!bookId) {
        showError('No book specified');
        return;
    }

    try {
        const response = await fetch(`${API_BASE}/books.php?id=${bookId}`);
        const book = await response.json();

        if (book && book.title) {
            displayBookDetails(book);
        } else {
            showError('Book not found');
        }
    } catch (error) {
        console.error('Error loading book details:', error);
        showError('Failed to load book details');
    }
}

// Display Book Details
function displayBookDetails(book) {
    const container = document.getElementById('bookDetails');
    const colors = ['#667eea', '#764ba2', '#f093fb', '#f5576c', '#4facfe', '#00f2fe'];
    const color = colors[(book.id - 1) % colors.length];
    
    const bookImage = book.image_url && book.image_url.includes('unsplash')
        ? `<img src="${book.image_url}" alt="${book.title}" style="width: 250px; height: 350px; object-fit: cover; border-radius: 5px; box-shadow: 0 4px 8px rgba(0,0,0,0.2);" onerror="this.style.display='none'; this.parentElement.innerHTML='<div class=\\'book-image book-cover\\' style=\\'background: linear-gradient(135deg, ${color} 0%, ${color}dd 100%); width: 250px; height: 350px;\\'><div class=\\'book-cover-content\\'><i class=\\'fas fa-book-open\\' style=\\'font-size: 4rem; color: rgba(255,255,255,0.9);\\'></i></div></div>';">`
        : `<div class="book-image book-cover" style="background: linear-gradient(135deg, ${color} 0%, ${color}dd 100%); width: 250px; height: 350px;">
                <div class="book-cover-content">
                    <i class="fas fa-book-open" style="font-size: 4rem; color: rgba(255,255,255,0.9); margin-bottom: 15px;"></i>
                    <div style="font-size: 1.2rem; font-weight: bold; color: white; text-align: center; padding: 0 20px; line-height: 1.4;">
                        ${book.title}
                    </div>
                </div>
            </div>`;
    
    container.innerHTML = `
        <div class="book-details">
            <div class="book-details-image">
                ${bookImage}
            </div>
            <div class="book-details-info">
                <h1>${book.title}</h1>
                <p class="book-details-author">by ${book.author}</p>
                <p class="book-details-price">$${book.price}</p>
                
                <div class="book-meta">
                    <p><strong>Category:</strong> ${book.category}</p>
                    <p><strong>Availability:</strong> 
                        <span class="${book.stock_quantity > 0 ? 'in-stock' : 'out-of-stock'}">
                            ${book.stock_quantity > 0 ? `${book.stock_quantity} in stock` : 'Out of stock'}
                        </span>
                    </p>
                </div>
                
                <div class="book-details-description">
                    <h3>Description</h3>
                    <p>${book.description || 'No description available.'}</p>
                </div>
                
                <div class="purchase-section">
                    <div class="quantity-selector">
                        <label for="quantity">Quantity:</label>
                        <input type="number" id="quantity" name="quantity" value="1" min="1" max="${book.stock_quantity}">
                    </div>
                    
                    <div class="action-buttons">
                        <button class="btn btn-primary" onclick="addToCartFromDetails(${book.id})" 
                                ${book.stock_quantity === 0 ? 'disabled' : ''}>
                            <i class="fas fa-cart-plus"></i> Add to Cart
                        </button>
                        <button class="btn btn-secondary" onclick="window.location.href='shop.html'">
                            Continue Shopping
                        </button>
                    </div>
                </div>
            </div>
        </div>
    `;

    // Update page title
    document.title = `${book.title} - NETH Bookhive`;
}

// Add to Cart from Details Page
function addToCartFromDetails(bookId) {
    const quantityInput = document.getElementById('quantity');
    const quantity = parseInt(quantityInput.value) || 1;
    
    addToCart(bookId, quantity);
}

// Show Error
function showError(message) {
    const container = document.getElementById('bookDetails');
    container.innerHTML = `
        <div class="message error">
            ${message}
        </div>
        <div style="text-align: center; margin-top: 2rem;">
            <a href="shop.html" class="btn btn-primary">Back to Shop</a>
        </div>
    `;
}