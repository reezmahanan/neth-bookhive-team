<?php
$page_title = "Shop - Browse Books";
$extra_js = ['js/shop.js'];
include 'includes/header.php';
?>

    <!-- Shop Header -->
    <section class="shop-header-premium">
        <div class="container">
            <h1 class="section-title-gradient">Our Book Collection</h1>
            <p class="shop-subtitle">Discover your next favorite book from our curated collection</p>
            <div class="search-bar-premium">
                <i class="fas fa-search search-icon"></i>
                <input type="text" id="searchInput" placeholder="Search books by title, author, or category...">
                <button id="searchBtn">
                    <i class="fas fa-arrow-right"></i>
                </button>
            </div>
        </div>
    </section>

    <!-- Books Grid -->
    <section class="books-section-premium">
        <div class="container">
            <div class="books-grid-shop" id="booksGrid">
                <!-- Sample Books -->
                <div class="book-card">
                    <div class="book-image">
                        <div style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); width: 100%; height: 300px; display: flex; align-items: center; justify-content: center; border-radius: 10px;">
                            <i class="fas fa-book-open" style="font-size: 3rem; color: white;"></i>
                        </div>
                        <div class="book-overlay">
                            <button class="btn-quick-view" onclick="window.location.href='book-details.php?id=1'">
                                <i class="fas fa-eye"></i> Quick View
                            </button>
                        </div>
                    </div>
                    <div class="book-content">
                        <h3>To Kill a Mockingbird</h3>
                        <p class="book-author">by Harper Lee</p>
                        <div class="book-rating">
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star-half-alt"></i>
                            <span>4.8</span>
                        </div>
                        <div class="book-footer">
                            <span class="book-price">$14.99</span>
                            <button class="btn-add-to-cart" onclick="alert('Added to cart!')">
                                <i class="fas fa-shopping-cart"></i> Add
                            </button>
                        </div>
                    </div>
                </div>

                <div class="book-card">
                    <div class="book-image">
                        <div style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); width: 100%; height: 300px; display: flex; align-items: center; justify-content: center; border-radius: 10px;">
                            <i class="fas fa-book" style="font-size: 3rem; color: white;"></i>
                        </div>
                        <div class="book-overlay">
                            <button class="btn-quick-view" onclick="window.location.href='book-details.php?id=2'">
                                <i class="fas fa-eye"></i> Quick View
                            </button>
                        </div>
                    </div>
                    <div class="book-content">
                        <h3>1984</h3>
                        <p class="book-author">by George Orwell</p>
                        <div class="book-rating">
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star-half-alt"></i>
                            <span>4.7</span>
                        </div>
                        <div class="book-footer">
                            <span class="book-price">$10.99</span>
                            <button class="btn-add-to-cart" onclick="alert('Added to cart!')">
                                <i class="fas fa-shopping-cart"></i> Add
                            </button>
                        </div>
                    </div>
                </div>

                <div class="book-card">
                    <div class="book-image">
                        <div style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%); width: 100%; height: 300px; display: flex; align-items: center; justify-content: center; border-radius: 10px;">
                            <i class="fas fa-book-reader" style="font-size: 3rem; color: white;"></i>
                        </div>
                        <div class="book-overlay">
                            <button class="btn-quick-view" onclick="window.location.href='book-details.php?id=3'">
                                <i class="fas fa-eye"></i> Quick View
                            </button>
                        </div>
                    </div>
                    <div class="book-content">
                        <h3>Pride and Prejudice</h3>
                        <p class="book-author">by Jane Austen</p>
                        <div class="book-rating">
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <span>5.0</span>
                        </div>
                        <div class="book-footer">
                            <span class="book-price">$9.99</span>
                            <button class="btn-add-to-cart" onclick="alert('Added to cart!')">
                                <i class="fas fa-shopping-cart"></i> Add
                            </button>
                        </div>
                    </div>
                </div>

                <div class="book-card">
                    <div class="book-image">
                        <div style="background: linear-gradient(135deg, #fa709a 0%, #fee140 100%); width: 100%; height: 300px; display: flex; align-items: center; justify-content: center; border-radius: 10px;">
                            <i class="fas fa-book-medical" style="font-size: 3rem; color: white;"></i>
                        </div>
                        <div class="book-overlay">
                            <button class="btn-quick-view" onclick="window.location.href='book-details.php?id=4'">
                                <i class="fas fa-eye"></i> Quick View
                            </button>
                        </div>
                    </div>
                    <div class="book-content">
                        <h3>The Great Gatsby</h3>
                        <p class="book-author">by F. Scott Fitzgerald</p>
                        <div class="book-rating">
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="far fa-star"></i>
                            <span>4.5</span>
                        </div>
                        <div class="book-footer">
                            <span class="book-price">$12.99</span>
                            <button class="btn-add-to-cart" onclick="alert('Added to cart!')">
                                <i class="fas fa-shopping-cart"></i> Add
                            </button>
                        </div>
                    </div>
                </div>

                <div class="book-card">
                    <div class="book-image">
                        <div style="background: linear-gradient(135deg, #30cfd0 0%, #330867 100%); width: 100%; height: 300px; display: flex; align-items: center; justify-content: center; border-radius: 10px;">
                            <i class="fas fa-book" style="font-size: 3rem; color: white;"></i>
                        </div>
                        <div class="book-overlay">
                            <button class="btn-quick-view" onclick="window.location.href='book-details.php?id=5'">
                                <i class="fas fa-eye"></i> Quick View
                            </button>
                        </div>
                    </div>
                    <div class="book-content">
                        <h3>Harry Potter Series</h3>
                        <p class="book-author">by J.K. Rowling</p>
                        <div class="book-rating">
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <span>5.0</span>
                        </div>
                        <div class="book-footer">
                            <span class="book-price">$24.99</span>
                            <button class="btn-add-to-cart" onclick="alert('Added to cart!')">
                                <i class="fas fa-shopping-cart"></i> Add
                            </button>
                        </div>
                    </div>
                </div>

                <div class="book-card">
                    <div class="book-image">
                        <div style="background: linear-gradient(135deg, #a8edea 0%, #fed6e3 100%); width: 100%; height: 300px; display: flex; align-items: center; justify-content: center; border-radius: 10px;">
                            <i class="fas fa-book-open" style="font-size: 3rem; color: #667eea;"></i>
                        </div>
                        <div class="book-overlay">
                            <button class="btn-quick-view" onclick="window.location.href='book-details.php?id=6'">
                                <i class="fas fa-eye"></i> Quick View
                            </button>
                        </div>
                    </div>
                    <div class="book-content">
                        <h3>The Hobbit</h3>
                        <p class="book-author">by J.R.R. Tolkien</p>
                        <div class="book-rating">
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star-half-alt"></i>
                            <span>4.9</span>
                        </div>
                        <div class="book-footer">
                            <span class="book-price">$16.99</span>
                            <button class="btn-add-to-cart" onclick="alert('Added to cart!')">
                                <i class="fas fa-shopping-cart"></i> Add
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

<?php
include 'includes/footer.php';
?>
