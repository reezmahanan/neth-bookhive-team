// Add to Cart Logic with Database Sync
async function addToCart(bookId, quantity = 1) {
    console.log('Adding to cart:', bookId);
    
    try {
        // Add to database
        const response = await fetch('backend/api/cart_api.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                book_id: bookId,
                quantity: quantity
            })
        });
        
        const data = await response.json();
        
        if (data.success) {
            // Also update localStorage as backup
            let cart = JSON.parse(localStorage.getItem('cart')) || [];
            const existing = cart.find(item => item.id === bookId);
            if (existing) {
                existing.qty += quantity;
            } else {
                cart.push({ id: bookId, qty: quantity });
            }
            localStorage.setItem('cart', JSON.stringify(cart));
            
            updateCartCount();
            showToast('Added to cart!');
        } else {
            showToast('Failed to add to cart', 'error');
        }
    } catch (error) {
        console.error('Error adding to cart:', error);
        // Fallback to localStorage only
        let cart = JSON.parse(localStorage.getItem('cart')) || [];
        const existing = cart.find(item => item.id === bookId);
        if (existing) {
            existing.qty += quantity;
        } else {
            cart.push({ id: bookId, qty: quantity });
        }
        localStorage.setItem('cart', JSON.stringify(cart));
        updateCartCount();
        showToast('Added to cart!');
    }
}

// Wishlist Logic
function addToWishlist(bookId) {
    console.log('Adding to wishlist:', bookId);
    let wishlist = JSON.parse(localStorage.getItem('wishlist')) || [];
    if (!wishlist.includes(bookId)) {
        wishlist.push(bookId);
        localStorage.setItem('wishlist', JSON.stringify(wishlist));
        showToast('Added to wishlist!');
    } else {
        showToast('Already in wishlist!');
    }
}

// Update Cart Count in Header
async function updateCartCount() {
    try {
        // Try to get from database first
        const response = await fetch('backend/api/cart_api.php?action=count');
        const data = await response.json();
        
        if (data.success) {
            const cartBadge = document.querySelector('.cart-count');
            if (cartBadge) {
                cartBadge.textContent = data.count;
            }
            return;
        }
    } catch (error) {
        console.error('Error getting cart count:', error);
    }
    
    // Fallback to localStorage
    let cart = JSON.parse(localStorage.getItem('cart')) || [];
    let totalItems = cart.reduce((sum, item) => sum + item.qty, 0);
    const cartBadge = document.querySelector('.cart-count');
    if (cartBadge) {
        cartBadge.textContent = totalItems;
    }
}

// Show Toast Notification
function showToast(message, type = 'success') {
    let toast = document.getElementById('toast');
    if (!toast) {
        toast = document.createElement('div');
        toast.id = 'toast';
        toast.style.cssText = `
            position: fixed;
            top: 80px;
            right: 30px;
            background: ${type === 'success' ? '#27ae60' : '#e74c3c'};
            color: #fff;
            padding: 16px 28px;
            border-radius: 8px;
            font-weight: bold;
            z-index: 9999;
            box-shadow: 0 4px 16px rgba(0,0,0,0.2);
            animation: slideIn 0.3s ease-out;
        `;
        document.body.appendChild(toast);
    }
    toast.textContent = message;
    toast.style.display = 'block';
    setTimeout(() => { 
        toast.style.animation = 'slideOut 0.3s ease-out';
        setTimeout(() => toast.style.display = 'none', 300);
    }, 2000);
}
// API Base URL
const API_BASE = 'http://localhost/NETH%20Bookhive/backend/api';

// DOM Content Loaded
document.addEventListener('DOMContentLoaded', function() {
    initializeApp();
    loadFeaturedBooks();
    setupEventListeners();
});

// Initialize Application
function initializeApp() {
    checkAuthStatus();
    updateCartCount();
    setupMobileMenu();
}

// Setup Event Listeners
function setupEventListeners() {
    // Category cards
    const categoryCards = document.querySelectorAll('.category-card');
    categoryCards.forEach(card => {
        card.addEventListener('click', function() {
            const category = this.getAttribute('data-category');
            window.location.href = `shop.html?category=${encodeURIComponent(category)}`;
        });
    });

    // Search functionality
    const searchBtn = document.getElementById('searchBtn');
    const searchInput = document.getElementById('searchInput');
    
    if (searchBtn && searchInput) {
        searchBtn.addEventListener('click', performSearch);
        searchInput.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                performSearch();
            }
        });
    }

    // Mobile menu toggle
    const hamburger = document.querySelector('.hamburger');
    if (hamburger) {
        hamburger.addEventListener('click', toggleMobileMenu);
    }
}

// Mobile Menu Functionality
function setupMobileMenu() {
    const hamburger = document.querySelector('.hamburger');
    const navMenu = document.querySelector('.nav-menu');
    
    if (hamburger && navMenu) {
        hamburger.addEventListener('click', () => {
            hamburger.classList.toggle('active');
            navMenu.classList.toggle('active');
        });

        // Close mobile menu when clicking on links
        document.querySelectorAll('.nav-link').forEach(link => {
            link.addEventListener('click', () => {
                hamburger.classList.remove('active');
                navMenu.classList.remove('active');
            });
        });
    }
}

function toggleMobileMenu() {
    const navMenu = document.querySelector('.nav-menu');
    const hamburger = document.querySelector('.hamburger');
    
    navMenu.classList.toggle('active');
    hamburger.classList.toggle('active');
}

// Load Featured Books
async function loadFeaturedBooks() {
    const featuredBooksContainer = document.getElementById('featuredBooks');
    if (!featuredBooksContainer) return;

    try {
        console.log('Fetching books from:', `${API_BASE}/books.php`); // Debug
        const response = await fetch(`${API_BASE}/books.php`);
        
        console.log('Response status:', response.status); // Debug
        console.log('Response OK:', response.ok); // Debug
        
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        
        const books = await response.json();
        console.log('Books received:', books); // Debug

        if (books && books.length > 0) {
            // Pick 4 random books for featured section
            const shuffled = books.sort(() => 0.5 - Math.random());
            const featuredBooks = shuffled.slice(0, 4);
            displayBooks(featuredBooks, featuredBooksContainer);
            // Animate book cards with GSAP
            setTimeout(() => {
                if (window.gsap) {
                    gsap.from('.book-card', {
                        duration: 0.8,
                        y: 40,
                        opacity: 0,
                        stagger: 0.15,
                        ease: 'power2.out'
                    });
                }
            }, 100); // Wait for DOM update
        } else {
            featuredBooksContainer.innerHTML = `
                <div class="empty-state">
                    <i class="fas fa-book"></i>
                    <p>No books available at the moment.</p>
                </div>
            `;
        }
    } catch (error) {
        console.error('Error loading featured books:', error);
        featuredBooksContainer.innerHTML = `
            <div class="message error">
                Failed to load books. Please try again later.
            </div>
        `;
    }
}

// Display Books in Grid
function displayBooks(books, container) {
    const colors = ['#667eea', '#764ba2', '#f093fb', '#f5576c', '#4facfe', '#00f2fe'];
    
    container.innerHTML = books.map((book, index) => {
        const color = colors[index % colors.length];
        const bookImage = book.image_url && book.image_url.includes('unsplash') 
            ? `<img src="${book.image_url}" alt="${book.title}" style="width: 100%; height: 100%; object-fit: cover; border-radius: 5px;" onerror="this.parentElement.innerHTML='<div class=\\'book-cover-content\\'><i class=\\'fas fa-book-open\\' style=\\'font-size: 3rem; color: rgba(255,255,255,0.9);\\'></i></div>'">` 
            : `<div class="book-cover-content">
                    <i class="fas fa-book-open" style="font-size: 3rem; color: rgba(255,255,255,0.9); margin-bottom: 10px;"></i>
                    <div style="font-size: 0.9rem; font-weight: bold; color: white; text-align: center; padding: 0 10px; line-height: 1.3;">
                        ${book.title}
                    </div>
                </div>`;
        
        return `
        <div class="book-card" data-book-id="${book.id}">
            <div class="book-image book-cover" style="background: linear-gradient(135deg, ${color} 0%, ${color}dd 100%);">
                ${bookImage}
            </div>
            <h3 class="book-title">${book.title}</h3>
            <p class="book-author">by ${book.author}</p>
            <p class="book-price">$${book.price}</p>
            <div class="book-actions">
                <button class="btn btn-primary btn-sm" onclick="viewBookDetails(${book.id})">
                    View Details
                </button>
                <button class="btn btn-success btn-sm" onclick="addToCart(${book.id}, 1)">
                    Add to Cart
                </button>
            </div>
        </div>
    `}).join('');
}

// View Book Details
function viewBookDetails(bookId) {
    window.location.href = `book-details.html?id=${bookId}`;
}

// Search Functionality
function performSearch() {
    const searchInput = document.getElementById('searchInput');
    const searchTerm = searchInput.value.trim();
    
    if (searchTerm) {
        window.location.href = `shop.html?search=${encodeURIComponent(searchTerm)}`;
    }
}

// Check Authentication Status
function checkAuthStatus() {
    const user = JSON.parse(localStorage.getItem('user'));
    const loginLink = document.getElementById('loginLink');
    const registerLink = document.getElementById('registerLink');
    const profileLink = document.getElementById('profileLink');
    const logoutLink = document.getElementById('logoutLink');
    const userName = document.getElementById('userName');

    if (user) {
        if (loginLink) loginLink.style.display = 'none';
        if (registerLink) registerLink.style.display = 'none';
        if (profileLink) profileLink.style.display = 'block';
        if (logoutLink) logoutLink.style.display = 'block';
        if (userName) {
            userName.style.display = 'inline';
            userName.textContent = `Hello, ${user.name}`;
        }
    } else {
        if (loginLink) loginLink.style.display = 'block';
        if (registerLink) registerLink.style.display = 'block';
        if (profileLink) profileLink.style.display = 'none';
        if (logoutLink) logoutLink.style.display = 'none';
        if (userName) userName.style.display = 'none';
    }

    // Setup logout functionality
    if (logoutLink) {
        logoutLink.addEventListener('click', function(e) {
            e.preventDefault();
            logout();
        });
    }
}

// Logout Function
function logout() {
    localStorage.removeItem('user');
    localStorage.removeItem('cart');
    window.location.href = 'index.html';
}

// Update Cart Count
function updateCartCount() {
    const cartCount = document.querySelector('.cart-count');
    if (!cartCount) return;

    const user = JSON.parse(localStorage.getItem('user'));
    if (user) {
        // For logged-in users, fetch cart from server
        fetchUserCartCount(user.id);
    } else {
        // For guests, use local storage
        const cart = JSON.parse(localStorage.getItem('cart')) || [];
        const totalItems = cart.reduce((sum, item) => sum + item.quantity, 0);
        cartCount.textContent = totalItems;
    }
}

// Fetch User Cart Count from Server
async function fetchUserCartCount(userId) {
    try {
        const response = await fetch(`${API_BASE}/cart.php?user_id=${userId}`);
        const cartItems = await response.json();
        
        const cartCount = document.querySelector('.cart-count');
        const totalItems = cartItems.reduce((sum, item) => sum + item.quantity, 0);
        cartCount.textContent = totalItems;
    } catch (error) {
        console.error('Error fetching cart count:', error);
    }
}

// Add to Cart Function
async function addToCart(bookId, quantity = 1) {
    const user = JSON.parse(localStorage.getItem('user'));
    
    if (!user) {
        showMessage('Please login to add items to cart', 'error');
        setTimeout(() => {
            window.location.href = 'login.html';
        }, 1500);
        return;
    }

    try {
        const response = await fetch(`${API_BASE}/cart.php`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                user_id: user.id,
                book_id: bookId,
                quantity: quantity
            })
        });

        const result = await response.json();

        if (response.ok) {
            showMessage('Book added to cart successfully!', 'success');
            updateCartCount();
        } else {
            showMessage(result.message || 'Failed to add book to cart', 'error');
        }
    } catch (error) {
        console.error('Error adding to cart:', error);
        showMessage('Failed to add book to cart', 'error');
    }
}

// Show Message Function
function showMessage(message, type = 'info') {
    // Remove existing messages
    const existingMessage = document.querySelector('.message');
    if (existingMessage) {
        existingMessage.remove();
    }

    // Create new message
    const messageDiv = document.createElement('div');
    messageDiv.className = `message ${type}`;
    messageDiv.textContent = message;

    // Add to page
    document.body.prepend(messageDiv);

    // Auto remove after 3 seconds
    setTimeout(() => {
        if (messageDiv.parentNode) {
            messageDiv.remove();
        }
    }, 3000);
}

// Utility function to get URL parameters
function getUrlParams() {
    const params = new URLSearchParams(window.location.search);
    const result = {};
    for (const [key, value] of params) {
        result[key] = value;
    }
    return result;
}

// Scroll Reveal Animations for Feature Cards
function initScrollAnimations() {
    const observerOptions = {
        threshold: 0.2,
        rootMargin: '0px 0px -50px 0px'
    };

    const observer = new IntersectionObserver((entries) => {
        entries.forEach((entry, index) => {
            if (entry.isIntersecting) {
                // Add staggered delay for each card
                setTimeout(() => {
                    entry.target.style.opacity = '1';
                    entry.target.style.transform = 'translateY(0)';
                }, index * 150);
                observer.unobserve(entry.target);
            }
        });
    }, observerOptions);

    // Observe all feature cards
    document.querySelectorAll('.feature-card-premium').forEach(card => {
        card.style.opacity = '0';
        card.style.transform = 'translateY(50px)';
        card.style.transition = 'all 0.8s cubic-bezier(0.175, 0.885, 0.32, 1.275)';
        observer.observe(card);
    });

    // Also animate section title
    const sectionTitle = document.querySelector('.section-title-premium');
    if (sectionTitle) {
        const titleObserver = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.style.opacity = '1';
                    entry.target.style.transform = 'translateY(0)';
                    titleObserver.unobserve(entry.target);
                }
            });
        }, observerOptions);

        sectionTitle.style.opacity = '0';
        sectionTitle.style.transform = 'translateY(30px)';
        sectionTitle.style.transition = 'all 0.8s ease-out';
        titleObserver.observe(sectionTitle);
    }
}

// Initialize animations when DOM is loaded
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initScrollAnimations);
} else {
    initScrollAnimations();
}