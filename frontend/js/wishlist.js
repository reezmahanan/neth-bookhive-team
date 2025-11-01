// Wishlist JavaScript
const API_BASE = 'http://localhost/NETH%20Bookhive/backend/api';

document.addEventListener('DOMContentLoaded', function() {
    // Check if user is logged in
    const user = JSON.parse(localStorage.getItem('user'));
    
    if (!user) {
        window.location.href = 'login.html';
        return;
    }

    loadWishlist(user.id);
    setupClearWishlist(user.id);
});

// Load Wishlist
async function loadWishlist(userId) {
    const wishlistContainer = document.getElementById('wishlistContainer');
    const wishlistCountEl = document.getElementById('wishlistCount');
    const clearBtn = document.getElementById('clearWishlistBtn');
    
    try {
        const response = await fetch(`${API_BASE}/wishlist.php?user_id=${userId}`);
        
        if (!response.ok) {
            throw new Error('Failed to fetch wishlist');
        }

        const items = await response.json();
        
        if (!items || items.length === 0) {
            wishlistContainer.innerHTML = `
                <div class="empty-wishlist">
                    <i class="fas fa-heart-broken"></i>
                    <h2>Your Wishlist is Empty</h2>
                    <p>Start adding books you love to your wishlist!</p>
                    <br>
                    <a href="shop.html" class="btn btn-primary">Browse Books</a>
                </div>
            `;
            wishlistCountEl.textContent = '0 items';
            clearBtn.style.display = 'none';
            return;
        }

        wishlistCountEl.textContent = `${items.length} item${items.length > 1 ? 's' : ''}`;
        clearBtn.style.display = 'inline-block';
        displayWishlist(items, wishlistContainer, userId);
        
    } catch (error) {
        console.error('Error loading wishlist:', error);
        wishlistContainer.innerHTML = `
            <div class="empty-wishlist">
                <i class="fas fa-exclamation-triangle"></i>
                <h2>Error Loading Wishlist</h2>
                <p>Unable to load your wishlist. Please try again later.</p>
                <br>
                <button class="btn btn-primary" onclick="location.reload()">Retry</button>
            </div>
        `;
    }
}

// Display Wishlist
function displayWishlist(items, container, userId) {
    const grid = document.createElement('div');
    grid.className = 'wishlist-grid';
    
    items.forEach(item => {
        const card = document.createElement('div');
        card.className = 'wishlist-card';
        
        const rating = parseFloat(item.average_rating) || 0;
        const stars = generateStars(rating);
        
        card.innerHTML = `
            <div class="wishlist-card-image">
                ${item.image_url ? `<img src="${item.image_url}" alt="${item.title}">` : '<i class="fas fa-book" style="font-size: 3rem; color: white;"></i>'}
                <button class="remove-wishlist" onclick="removeFromWishlist(${item.book_id}, ${userId})">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="wishlist-card-content">
                <h3 class="wishlist-card-title">${item.title}</h3>
                <p class="wishlist-card-author">by ${item.author}</p>
                <div class="wishlist-card-rating">
                    ${stars}
                    <span>(${rating.toFixed(1)})</span>
                </div>
                <div class="wishlist-card-price">Rs ${parseFloat(item.price).toFixed(2)}</div>
                ${item.stock > 0 ? 
                    `<div class="wishlist-card-actions">
                        <button class="btn-add-cart" onclick="addToCartFromWishlist(${item.book_id}, ${userId})">
                            <i class="fas fa-shopping-cart"></i> Add to Cart
                        </button>
                        <button class="btn-view" onclick="viewBookDetails(${item.book_id})">
                            <i class="fas fa-eye"></i>
                        </button>
                    </div>` :
                    `<div style="color: #e74c3c; font-weight: 600; text-align: center; padding: 0.75rem;">Out of Stock</div>`
                }
            </div>
        `;
        
        grid.appendChild(card);
    });
    
    container.innerHTML = '';
    container.appendChild(grid);
}

// Generate Stars
function generateStars(rating) {
    let stars = '';
    for (let i = 1; i <= 5; i++) {
        if (i <= rating) {
            stars += '<i class="fas fa-star" style="color: #f39c12;"></i>';
        } else if (i - 0.5 <= rating) {
            stars += '<i class="fas fa-star-half-alt" style="color: #f39c12;"></i>';
        } else {
            stars += '<i class="far fa-star" style="color: #f39c12;"></i>';
        }
    }
    return stars;
}

// Remove from Wishlist
async function removeFromWishlist(bookId, userId) {
    if (!confirm('Remove this book from your wishlist?')) {
        return;
    }
    
    try {
        const response = await fetch(`${API_BASE}/wishlist.php?user_id=${userId}&book_id=${bookId}`, {
            method: 'DELETE'
        });
        
        const result = await response.json();
        
        if (response.ok) {
            showMessage('Removed from wishlist', 'success');
            loadWishlist(userId);
        } else {
            showMessage(result.message || 'Failed to remove from wishlist', 'error');
        }
    } catch (error) {
        console.error('Error removing from wishlist:', error);
        showMessage('Failed to remove from wishlist', 'error');
    }
}

// Setup Clear Wishlist
function setupClearWishlist(userId) {
    document.getElementById('clearWishlistBtn').addEventListener('click', async function() {
        if (!confirm('Are you sure you want to clear your entire wishlist?')) {
            return;
        }
        
        try {
            const response = await fetch(`${API_BASE}/wishlist.php?action=clear&user_id=${userId}`, {
                method: 'DELETE'
            });
            
            const result = await response.json();
            
            if (response.ok) {
                showMessage('Wishlist cleared', 'success');
                loadWishlist(userId);
            } else {
                showMessage(result.message || 'Failed to clear wishlist', 'error');
            }
        } catch (error) {
            console.error('Error clearing wishlist:', error);
            showMessage('Failed to clear wishlist', 'error');
        }
    });
}

// Add to Cart from Wishlist
async function addToCartFromWishlist(bookId, userId) {
    try {
        // Add to cart
        const response = await fetch(`${API_BASE}/cart.php`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                user_id: userId,
                book_id: bookId,
                quantity: 1
            })
        });
        
        const result = await response.json();
        
        if (response.ok) {
            showMessage('Added to cart!', 'success');
            updateCartCount();
            
            // Ask if they want to remove from wishlist
            if (confirm('Book added to cart! Remove from wishlist?')) {
                await removeFromWishlist(bookId, userId);
            }
        } else {
            showMessage(result.message || 'Failed to add to cart', 'error');
        }
    } catch (error) {
        console.error('Error adding to cart:', error);
        showMessage('Failed to add to cart', 'error');
    }
}

// View Book Details
function viewBookDetails(bookId) {
    window.location.href = `book-details.html?id=${bookId}`;
}

// Show Message
function showMessage(message, type = 'info') {
    const messageDiv = document.createElement('div');
    messageDiv.className = `message ${type}`;
    messageDiv.textContent = message;
    messageDiv.style.cssText = `
        position: fixed;
        top: 100px;
        right: 20px;
        background: ${type === 'success' ? '#27ae60' : type === 'error' ? '#e74c3c' : '#3498db'};
        color: white;
        padding: 1rem 2rem;
        border-radius: 5px;
        box-shadow: 0 5px 15px rgba(0,0,0,0.2);
        z-index: 9999;
        animation: slideIn 0.3s ease;
    `;
    
    document.body.appendChild(messageDiv);
    
    setTimeout(() => {
        messageDiv.style.animation = 'slideOut 0.3s ease';
        setTimeout(() => messageDiv.remove(), 300);
    }, 3000);
}

// Add animations
const style = document.createElement('style');
style.textContent = `
    @keyframes slideIn {
        from {
            transform: translateX(100%);
            opacity: 0;
        }
        to {
            transform: translateX(0);
            opacity: 1;
        }
    }
    @keyframes slideOut {
        from {
            transform: translateX(0);
            opacity: 1;
        }
        to {
            transform: translateX(100%);
            opacity: 0;
        }
    }
`;
document.head.appendChild(style);
