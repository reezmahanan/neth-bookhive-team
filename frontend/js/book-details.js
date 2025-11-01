// API Base URL
const API_BASE = 'http://localhost/NETH%20Bookhive/backend/api';

// Book Details Page JavaScript
document.addEventListener('DOMContentLoaded', function() {
    loadBookDetails();
    
    // Load reviews if on book_details.php page
    const urlParams = new URLSearchParams(window.location.search);
    const bookId = urlParams.get('id');
    
    if (bookId && document.getElementById('reviewsList')) {
        loadReviews(bookId);
        setupReviewForm(bookId);
    }
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
                        <button class="btn btn-secondary" onclick="window.location.href='shop.php'">
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
            <a href="shop.php" class="btn btn-primary">Back to Shop</a>
        </div>
    `;
}

// Load reviews for the book
async function loadReviews(bookId) {
    try {
        const response = await fetch(`${API_BASE}/reviews.php?book_id=${bookId}`);
        const data = await response.json();
        
        const reviewsList = document.getElementById('reviewsList');
        
        if (data.success && data.reviews.length > 0) {
            reviewsList.innerHTML = data.reviews.map(review => `
                <div style="border-bottom:1px solid #eee;padding:20px 0;">
                    <div style="display:flex;justify-content:space-between;margin-bottom:8px;">
                        <strong style="color:var(--primary);">${review.user_name}</strong>
                        <span style="color:#999;font-size:0.9em;">${formatDate(review.created_at)}</span>
                    </div>
                    <div style="margin-bottom:8px;">
                        ${generateStars(review.rating)}
                    </div>
                    <p style="line-height:1.6;color:#555;">${review.review}</p>
                </div>
            `).join('');
        } else {
            reviewsList.innerHTML = '<p style="color:#666;font-style:italic;">No reviews yet. Be the first to review this book!</p>';
        }
    } catch (error) {
        console.error('Error loading reviews:', error);
    }
}

// Generate star HTML
function generateStars(rating) {
    let stars = '';
    for (let i = 1; i <= 5; i++) {
        if (i <= rating) {
            stars += '<i class="fas fa-star" style="color:#ffd700;"></i>';
        } else {
            stars += '<i class="far fa-star" style="color:#ddd;"></i>';
        }
    }
    return stars;
}

// Format date
function formatDate(dateString) {
    const options = { year: 'numeric', month: 'short', day: 'numeric' };
    return new Date(dateString).toLocaleDateString('en-US', options);
}

// Setup review form
function setupReviewForm(bookId) {
    const reviewForm = document.getElementById('reviewForm');
    if (!reviewForm) return;
    
    reviewForm.addEventListener('submit', async function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        const reviewData = {
            book_id: bookId,
            rating: formData.get('rating'),
            review: formData.get('review')
        };
        
        try {
            const response = await fetch(`${API_BASE}/reviews.php`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(reviewData)
            });
            
            const data = await response.json();
            
            if (data.success) {
                showToast('Review submitted successfully!');
                this.reset();
                loadReviews(bookId);
            } else {
                showToast('Failed to submit review: ' + data.message, 'error');
            }
        } catch (error) {
            console.error('Error submitting review:', error);
            showToast('Error submitting review', 'error');
        }
    });
}

// Toast notification
function showToast(message, type = 'success') {
    const toast = document.createElement('div');
    toast.style.cssText = `
        position:fixed;
        top:80px;
        right:20px;
        background:${type === 'success' ? 'var(--success)' : 'var(--danger)'};
        color:#fff;
        padding:16px 24px;
        border-radius:8px;
        box-shadow:0 4px 16px rgba(0,0,0,0.2);
        z-index:10000;
        animation:slideIn 0.3s ease-out;
    `;
    toast.textContent = message;
    document.body.appendChild(toast);
    
    setTimeout(() => {
        toast.style.animation = 'slideOut 0.3s ease-out';
        setTimeout(() => toast.remove(), 300);
    }, 3000);
}