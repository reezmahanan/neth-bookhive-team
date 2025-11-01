// API Base URL
const API_BASE = 'http://localhost/NETH%20Bookhive/backend/api';

// Shop Page JavaScript - Initialize immediately
(function() {
    console.log('Shop.js loading...');
    
    // Wait for DOM to be ready
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initShop);
    } else {
        initShop();
    }
    
    function initShop() {
        console.log('Initializing shop page...');
        const booksGrid = document.getElementById('booksGrid');
        
        if (!booksGrid) {
            console.error('Books grid not found!');
            return;
        }
        
        console.log('Books grid found, loading books...');
        loadAllBooks();
    }
})();

// Load All Books
function loadAllBooks() {
    const booksGrid = document.getElementById('booksGrid');
    
    console.log('loadAllBooks called');
    console.log('booksGrid element:', booksGrid);
    
    if (!booksGrid) {
        console.error('booksGrid element not found!');
        return;
    }

    // Show loading
    booksGrid.innerHTML = `
        <div class="loading-spinner-premium">
            <div class="spinner-circle"></div>
            <p>Loading books...</p>
        </div>
    `;

    const url = `${API_BASE}/books.php`;
    console.log('Fetching from:', url);
    
    fetch(url)
        .then(response => {
            console.log('Response received:', response.status);
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.json();
        })
        .then(books => {
            console.log('Books data:', books);
            console.log('Number of books:', books.length);
            
            if (books && books.length > 0) {
                displayBooks(books, booksGrid);
            } else {
                booksGrid.innerHTML = `
                    <div class="empty-state">
                        <i class="fas fa-book"></i>
                        <h3>No Books Available</h3>
                        <p>Check back later for new arrivals!</p>
                    </div>
                `;
            }
        })
        .catch(error => {
            console.error('Error:', error);
            booksGrid.innerHTML = `
                <div class="empty-state">
                    <i class="fas fa-exclamation-triangle"></i>
                    <h3>Error Loading Books</h3>
                    <p>${error.message}</p>
                    <button class="btn-add-to-cart" onclick="location.reload()" style="margin-top: 20px;">
                        <i class="fas fa-redo"></i> Retry
                    </button>
                </div>
            `;
        });
}

// Search Books
function searchBooks(searchTerm) {
    const booksGrid = document.getElementById('booksGrid');
    if (!booksGrid) return;

    booksGrid.innerHTML = `
        <div class="loading-spinner-premium">
            <div class="spinner-circle"></div>
            <p>Searching...</p>
        </div>
    `;
    
    fetch(`${API_BASE}/books.php?search=${encodeURIComponent(searchTerm)}`)
        .then(response => response.json())
        .then(books => {
            if (books.length > 0) {
                displayBooks(books, booksGrid);
                document.title = `Search: ${searchTerm} - NETH Bookhive`;
                const searchHeader = document.querySelector('.shop-header-premium h1');
                if (searchHeader) {
                    searchHeader.textContent = `Search Results: "${searchTerm}" (${books.length})`;
                }
            } else {
                booksGrid.innerHTML = `
                    <div class="empty-state">
                        <i class="fas fa-search"></i>
                        <h3>No Books Found</h3>
                        <p>No books found for "${searchTerm}". Try different keywords.</p>
                        <button class="btn-add-to-cart" onclick="loadAllBooks()">Browse All Books</button>
                    </div>
                `;
            }
        })
        .catch(error => {
            console.error('Error searching:', error);
            booksGrid.innerHTML = `
                <div class="empty-state">
                    <i class="fas fa-exclamation-triangle"></i>
                    <h3>Search Failed</h3>
                    <p>${error.message}</p>
                </div>
            `;
        });
}

// Load Books by Category
function loadBooksByCategory(category) {
    const booksGrid = document.getElementById('booksGrid');
    if (!booksGrid) return;

    booksGrid.innerHTML = `
        <div class="loading-spinner-premium">
            <div class="spinner-circle"></div>
            <p>Loading ${category} books...</p>
        </div>
    `;
    
    fetch(`${API_BASE}/books.php?category=${encodeURIComponent(category)}`)
        .then(response => response.json())
        .then(books => {
            if (books.length > 0) {
                displayBooks(books, booksGrid);
                document.title = `${category} - NETH Bookhive`;
                const shopHeader = document.querySelector('.shop-header-premium h1');
                if (shopHeader) {
                    shopHeader.textContent = `${category} Books (${books.length})`;
                }
            } else {
                booksGrid.innerHTML = `
                    <div class="empty-state">
                        <i class="fas fa-book"></i>
                        <h3>No ${category} Books</h3>
                        <p>No books found in this category yet.</p>
                        <button class="btn-add-to-cart" onclick="loadAllBooks()">Browse All Books</button>
                    </div>
                `;
            }
        })
        .catch(error => {
            console.error('Error loading category:', error);
            booksGrid.innerHTML = `
                <div class="empty-state">
                    <i class="fas fa-exclamation-triangle"></i>
                    <h3>Error Loading Category</h3>
                    <p>${error.message}</p>
                </div>
            `;
        });
}

// Display Books in Grid
function displayBooks(books, container) {
    console.log('displayBooks called with', books.length, 'books');
    console.log('Container element:', container);
    
    if (!books || books.length === 0) {
        console.error('No books to display');
        container.innerHTML = '<div class="empty-state"><p>No books available</p></div>';
        return;
    }
    
    // Clear the container
    container.innerHTML = '';
    console.log('Container cleared');
    
    // Create book cards
    books.forEach((book, index) => {
        try {
            console.log(`Creating card ${index + 1} for:`, book.title);
            
            const bookCard = document.createElement('div');
            bookCard.className = 'book-card';
            
            // Escape single quotes in title for onclick
            const safeTitle = (book.title || '').replace(/'/g, "\\'");
            const safeAuthor = (book.author || 'Unknown').replace(/'/g, "\\'");
            
            bookCard.innerHTML = `
                <div class="book-image">
                    <img src="${book.image_url || 'https://via.placeholder.com/200x280?text=No+Image'}" 
                         alt="${book.title}"
                         onerror="this.src='https://via.placeholder.com/200x280?text=No+Image'">
                    <div class="book-overlay">
                        <button class="btn-quick-view" onclick="viewBookDetails(${book.id})">
                            <i class="fas fa-eye"></i> View Details
                        </button>
                    </div>
                </div>
                <div class="book-info">
                    <h3>${book.title}</h3>
                    <p class="book-author">by ${book.author}</p>
                    <p class="book-category"><i class="fas fa-tag"></i> ${book.category}</p>
                    <div class="book-footer">
                        <span class="book-price">$${parseFloat(book.price).toFixed(2)}</span>
                        <button class="btn-add-to-cart" onclick="addToCart(${book.id}, '${safeTitle}', ${book.price})">
                            <i class="fas fa-shopping-cart"></i>
                        </button>
                    </div>
                </div>
            `;
            
            container.appendChild(bookCard);
            console.log(`Card ${index + 1} added to container`);
            
        } catch (error) {
            console.error(`Error creating card for book ${index}:`, error);
        }
    });
    
    console.log('All', books.length, 'book cards created successfully');
    console.log('Container children count:', container.children.length);
}

// View Book Details
function viewBookDetails(bookId) {
    window.location.href = `book-details.html?id=${bookId}`;
}

// Add to Cart
function addToCart(bookId, title, price) {
    // Get current cart from localStorage
    let cart = JSON.parse(localStorage.getItem('cart')) || [];
    
    // Check if book already in cart
    const existingItem = cart.find(item => item.id === bookId);
    
    if (existingItem) {
        existingItem.quantity += 1;
    } else {
        cart.push({
            id: bookId,
            title: title,
            price: parseFloat(price),
            quantity: 1
        });
    }
    
    // Save to localStorage
    localStorage.setItem('cart', JSON.stringify(cart));
    
    // Update cart count
    updateCartCount();
    
    // Show notification
    showNotification('Book added to cart!');
}

// Update Cart Count
function updateCartCount() {
    const cart = JSON.parse(localStorage.getItem('cart')) || [];
    const totalItems = cart.reduce((sum, item) => sum + item.quantity, 0);
    const cartCount = document.querySelector('.cart-count');
    if (cartCount) {
        cartCount.textContent = totalItems;
    }
}

// Show Notification
function showNotification(message) {
    // Check if notification already exists
    let notification = document.querySelector('.notification');
    
    if (!notification) {
        notification = document.createElement('div');
        notification.className = 'notification';
        document.body.appendChild(notification);
    }
    
    notification.textContent = message;
    notification.classList.add('show');
    
    setTimeout(() => {
        notification.classList.remove('show');
    }, 3000);
}

// Get URL Parameters
function getUrlParams() {
    const params = {};
    const searchParams = new URLSearchParams(window.location.search);
    for (const [key, value] of searchParams) {
        params[key] = value;
    }
    return params;
}

// Initialize search functionality
setTimeout(function() {
    // Search Button Event
    const searchBtn = document.getElementById('searchBtn');
    if (searchBtn) {
        searchBtn.addEventListener('click', function() {
            const searchInput = document.getElementById('searchInput');
            if (searchInput && searchInput.value.trim()) {
                searchBooks(searchInput.value.trim());
            }
        });
    }

    // Search on Enter key
    const searchInput = document.getElementById('searchInput');
    if (searchInput) {
        searchInput.addEventListener('keypress', function(e) {
            if (e.key === 'Enter' && this.value.trim()) {
                searchBooks(this.value.trim());
            }
        });
    }
    
    // Initialize cart count
    updateCartCount();
}, 500);