// API Base URL
const API_BASE = 'http://localhost/NETH%20Bookhive/backend/api';

// Shop Page JavaScript
document.addEventListener('DOMContentLoaded', function() {
    initializeShop();
});

async function initializeShop() {
    const params = getUrlParams();
    
    if (params.search) {
        document.getElementById('searchInput').value = params.search;
        await searchBooks(params.search);
    } else if (params.category) {
        await loadBooksByCategory(params.category);
    } else {
        await loadAllBooks();
    }
}

// Load All Books
async function loadAllBooks() {
    const booksGrid = document.getElementById('booksGrid');
    if (!booksGrid) return;

    try {
        showLoading(booksGrid);
        
        const response = await fetch(`${API_BASE}/books.php`);
        const books = await response.json();

        if (books.length > 0) {
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
    } catch (error) {
        console.error('Error loading books:', error);
        booksGrid.innerHTML = `
            <div class="message error">
                Failed to load books. Please try again later.
            </div>
        `;
    }
}

// Search Books
async function searchBooks(searchTerm) {
    const booksGrid = document.getElementById('booksGrid');
    if (!booksGrid) return;

    try {
        showLoading(booksGrid);
        
        const response = await fetch(`${API_BASE}/books.php?search=${encodeURIComponent(searchTerm)}`);
        const books = await response.json();

        if (books.length > 0) {
            displayBooks(books, booksGrid);
            
            // Update page title
            document.title = `Search: ${searchTerm} - NETH Bookhive`;
            
            // Show search results count
            const searchHeader = document.querySelector('.shop-header h1');
            if (searchHeader) {
                searchHeader.textContent = `Search Results for "${searchTerm}" (${books.length} books found)`;
            }
        } else {
            booksGrid.innerHTML = `
                <div class="empty-state">
                    <i class="fas fa-search"></i>
                    <h3>No Books Found</h3>
                    <p>No books found for "${searchTerm}". Try different keywords.</p>
                    <button class="btn btn-primary" onclick="loadAllBooks()">Browse All Books</button>
                </div>
            `;
        }
    } catch (error) {
        console.error('Error searching books:', error);
        booksGrid.innerHTML = `
            <div class="message error">
                Search failed. Please try again later.
            </div>
        `;
    }
}

// Load Books by Category
async function loadBooksByCategory(category) {
    const booksGrid = document.getElementById('booksGrid');
    if (!booksGrid) return;

    try {
        showLoading(booksGrid);
        
        const response = await fetch(`${API_BASE}/books.php?category=${encodeURIComponent(category)}`);
        const books = await response.json();

        if (books.length > 0) {
            displayBooks(books, booksGrid);
            
            // Update page title and header
            document.title = `${category} - NETH Bookhive`;
            const shopHeader = document.querySelector('.shop-header h1');
            if (shopHeader) {
                shopHeader.textContent = `${category} Books (${books.length})`;
            }
        } else {
            booksGrid.innerHTML = `
                <div class="empty-state">
                    <i class="fas fa-book"></i>
                    <h3>No ${category} Books</h3>
                    <p>No books found in this category yet.</p>
                    <button class="btn btn-primary" onclick="loadAllBooks()">Browse All Books</button>
                </div>
            `;
        }
    } catch (error) {
        console.error('Error loading books by category:', error);
        booksGrid.innerHTML = `
            <div class="message error">
                Failed to load category books. Please try again later.
            </div>
        `;
    }
}

// Show Loading State
function showLoading(container) {
    container.innerHTML = `
        <div class="empty-state">
            <div class="loading" style="width: 40px; height: 40px; margin: 0 auto 1rem;"></div>
            <p>Loading books...</p>
        </div>
    `;
}