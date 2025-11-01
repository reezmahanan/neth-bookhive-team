<?php
$page_title = "NETH BookHive - Your Ultimate Book Shopping Experience";
$extra_js = ['js/main.js'];
include 'includes/header.php';
?>

    <!-- Hero Section -->
    <section class="hero premium-hero">
        <div class="hero-overlay"></div>
        <div class="hero-content premium-content">
            <h1 class="gradient-title">Welcome to NETH BookHive</h1>
            <p class="hero-subtitle">Your Ultimate Student Book Shopping Experience</p>
            <div class="hero-buttons">
                <a href="shop.php" class="btn-premium btn-filled">
                    <i class="fas fa-book"></i> Browse Books
                </a>
                <a href="profile.html" class="btn-premium btn-outlined" id="heroProfileBtn" style="display: none;">
                    <i class="fas fa-user"></i> My Profile
                </a>
                <a href="login.html" class="btn-premium btn-outlined" id="heroLoginBtn">
                    <i class="fas fa-sign-in-alt"></i> Sign In
                </a>
            </div>
            <div class="admin-link-hero" style="margin-top: 30px;">
                <a href="../admin_login.php" style="color: rgba(255,255,255,0.7); text-decoration: none; font-size: 0.95rem; transition: color 0.3s;">
                    <i class="fas fa-shield-alt"></i> Admin Dashboard Login
                </a>
            </div>
        </div>
        
        <!-- Scroll Indicator -->
        <div class="scroll-indicator" onclick="document.querySelector('.premium-section').scrollIntoView({ behavior: 'smooth' })">
            <span>EXPLORE MORE</span>
            <i class="fas fa-chevron-down"></i>
        </div>
    </section>

    <!-- Stats Section -->
    <section class="premium-section">
        <div class="container">
            <div class="stats-premium">
                <div class="stat-item">
                    <span class="stat-number">5000+</span>
                    <span class="stat-label">Books Available</span>
                </div>
                <div class="stat-item">
                    <span class="stat-number">1200+</span>
                    <span class="stat-label">Happy Students</span>
                </div>
                <div class="stat-item">
                    <span class="stat-number">50+</span>
                    <span class="stat-label">Categories</span>
                </div>
                <div class="stat-item">
                    <span class="stat-number">24/7</span>
                    <span class="stat-label">Support</span>
                </div>
            </div>
        </div>
    </section>

    <!-- Featured Books -->
    <section class="featured-books-section-premium">
        <div class="container">
            <div class="section-header-premium">
                <h2 class="section-title-gradient">Featured Books</h2>
                <p class="section-subtitle-light">Discover our handpicked collection of bestsellers and student favorites</p>
            </div>
            
            <div class="books-grid-premium">
                <!-- Book 1 -->
                <div class="book-card-premium">
                    <div class="book-badge">Bestseller</div>
                    <div class="book-image-wrapper">
                        <img src="https://images.unsplash.com/photo-1544947950-fa07a98d237f?w=400&h=600&fit=crop" alt="To Kill a Mockingbird">
                        <div class="book-overlay">
                            <button class="btn-quick-view"><i class="fas fa-eye"></i> Quick View</button>
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
                            <span>4.8 (2.3k)</span>
                        </div>
                        <div class="book-footer">
                            <span class="book-price">$14.99</span>
                            <button class="btn-add-cart"><i class="fas fa-shopping-cart"></i></button>
                        </div>
                    </div>
                </div>

                <!-- Book 2 -->
                <div class="book-card-premium">
                    <div class="book-badge badge-new">New</div>
                    <div class="book-image-wrapper">
                        <img src="https://images.unsplash.com/photo-1512820790803-83ca734da794?w=400&h=600&fit=crop" alt="Pride and Prejudice">
                        <div class="book-overlay">
                            <button class="btn-quick-view"><i class="fas fa-eye"></i> Quick View</button>
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
                            <span>5.0 (1.8k)</span>
                        </div>
                        <div class="book-footer">
                            <span class="book-price">$9.99</span>
                            <button class="btn-add-cart"><i class="fas fa-shopping-cart"></i></button>
                        </div>
                    </div>
                </div>

                <!-- Book 3 -->
                <div class="book-card-premium">
                    <div class="book-badge badge-hot">Hot</div>
                    <div class="book-image-wrapper">
                        <img src="https://images.unsplash.com/photo-1497633762265-9d179a990aa6?w=400&h=600&fit=crop" alt="1984">
                        <div class="book-overlay">
                            <button class="btn-quick-view"><i class="fas fa-eye"></i> Quick View</button>
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
                            <span>4.7 (3.1k)</span>
                        </div>
                        <div class="book-footer">
                            <span class="book-price">$10.99</span>
                            <button class="btn-add-cart"><i class="fas fa-shopping-cart"></i></button>
                        </div>
                    </div>
                </div>

                <!-- Book 4 -->
                <div class="book-card-premium">
                    <div class="book-badge">Popular</div>
                    <div class="book-image-wrapper">
                        <img src="https://images.unsplash.com/photo-1543002588-bfa74002ed7e?w=400&h=600&fit=crop" alt="The Great Gatsby">
                        <div class="book-overlay">
                            <button class="btn-quick-view"><i class="fas fa-eye"></i> Quick View</button>
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
                            <span>4.5 (2.7k)</span>
                        </div>
                        <div class="book-footer">
                            <span class="book-price">$12.99</span>
                            <button class="btn-add-cart"><i class="fas fa-shopping-cart"></i></button>
                        </div>
                    </div>
                </div>

                <!-- Book 5 -->
                <div class="book-card-premium">
                    <div class="book-badge badge-new">New</div>
                    <div class="book-image-wrapper">
                        <img src="https://images.unsplash.com/photo-1589998059171-988d887df646?w=400&h=600&fit=crop" alt="Harry Potter">
                        <div class="book-overlay">
                            <button class="btn-quick-view"><i class="fas fa-eye"></i> Quick View</button>
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
                            <span>5.0 (4.5k)</span>
                        </div>
                        <div class="book-footer">
                            <span class="book-price">$24.99</span>
                            <button class="btn-add-cart"><i class="fas fa-shopping-cart"></i></button>
                        </div>
                    </div>
                </div>

                <!-- Book 6 -->
                <div class="book-card-premium">
                    <div class="book-badge badge-hot">Hot</div>
                    <div class="book-image-wrapper">
                        <img src="https://images.unsplash.com/photo-1519682337058-a94d519337bc?w=400&h=600&fit=crop" alt="The Hobbit">
                        <div class="book-overlay">
                            <button class="btn-quick-view"><i class="fas fa-eye"></i> Quick View</button>
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
                            <span>4.9 (3.8k)</span>
                        </div>
                        <div class="book-footer">
                            <span class="book-price">$16.99</span>
                            <button class="btn-add-cart"><i class="fas fa-shopping-cart"></i></button>
                        </div>
                    </div>
                </div>

                <!-- Book 7 -->
                <div class="book-card-premium">
                    <div class="book-badge">Trending</div>
                    <div class="book-image-wrapper">
                        <img src="https://images.unsplash.com/photo-1495446815901-a7297e633e8d?w=400&h=600&fit=crop" alt="The Catcher in the Rye">
                        <div class="book-overlay">
                            <button class="btn-quick-view"><i class="fas fa-eye"></i> Quick View</button>
                        </div>
                    </div>
                    <div class="book-content">
                        <h3>The Catcher in the Rye</h3>
                        <p class="book-author">by J.D. Salinger</p>
                        <div class="book-rating">
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="far fa-star"></i>
                            <span>4.3 (2.1k)</span>
                        </div>
                        <div class="book-footer">
                            <span class="book-price">$11.99</span>
                            <button class="btn-add-cart"><i class="fas fa-shopping-cart"></i></button>
                        </div>
                    </div>
                </div>

                <!-- Book 8 -->
                <div class="book-card-premium">
                    <div class="book-badge">Bestseller</div>
                    <div class="book-image-wrapper">
                        <img src="https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=400&h=600&fit=crop" alt="Sapiens">
                        <div class="book-overlay">
                            <button class="btn-quick-view"><i class="fas fa-eye"></i> Quick View</button>
                        </div>
                    </div>
                    <div class="book-content">
                        <h3>Sapiens</h3>
                        <p class="book-author">by Yuval Noah Harari</p>
                        <div class="book-rating">
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star-half-alt"></i>
                            <span>4.6 (5.2k)</span>
                        </div>
                        <div class="book-footer">
                            <span class="book-price">$18.99</span>
                            <button class="btn-add-cart"><i class="fas fa-shopping-cart"></i></button>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="section-cta">
                <a href="shop.php" class="btn-view-all">
                    View All Books <i class="fas fa-arrow-right"></i>
                </a>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section class="premium-section">
        <div class="container">
            <h2 class="section-title-premium">Why Choose BookHive?</h2>
            <div class="books-grid">
                <div class="feature-card-premium fade-in-up">
                    <div class="feature-icon">
                        <i class="fas fa-shipping-fast"></i>
                    </div>
                    <h3>Fast Delivery</h3>
                    <p>Get your books delivered within 2-3 business days across Sri Lanka</p>
                </div>
                <div class="feature-card-premium fade-in-up">
                    <div class="feature-icon">
                        <i class="fas fa-shield-alt"></i>
                    </div>
                    <h3>Secure Payment</h3>
                    <p>100% secure payment gateway with multiple payment options</p>
                </div>
                <div class="feature-card-premium fade-in-up">
                    <div class="feature-icon">
                        <i class="fas fa-tags"></i>
                    </div>
                    <h3>Best Prices</h3>
                    <p>Student-friendly pricing with amazing discounts and offers</p>
                </div>
                <div class="feature-card-premium fade-in-up">
                    <div class="feature-icon">
                        <i class="fas fa-headset"></i>
                    </div>
                    <h3>24/7 Support</h3>
                    <p>Round-the-clock customer support for all your queries</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Categories -->
    <section class="categories-section-premium">
        <div class="container">
            <h2 class="section-title-gradient">Browse Categories</h2>
            <p class="section-subtitle-dark">Explore our wide range of book genres</p>
            
            <div class="categories-grid-premium">
                <div class="category-card-premium" data-category="Fiction">
                    <div class="category-icon-wrapper">
                        <i class="fas fa-book"></i>
                    </div>
                    <h3>Fiction</h3>
                    <p>Dive into imaginary worlds</p>
                    <span class="category-arrow"><i class="fas fa-arrow-right"></i></span>
                </div>
                
                <div class="category-card-premium" data-category="Science Fiction">
                    <div class="category-icon-wrapper">
                        <i class="fas fa-rocket"></i>
                    </div>
                    <h3>Sci-Fi</h3>
                    <p>Explore futuristic tales</p>
                    <span class="category-arrow"><i class="fas fa-arrow-right"></i></span>
                </div>
                
                <div class="category-card-premium" data-category="Romance">
                    <div class="category-icon-wrapper">
                        <i class="fas fa-heart"></i>
                    </div>
                    <h3>Romance</h3>
                    <p>Love stories that touch hearts</p>
                    <span class="category-arrow"><i class="fas fa-arrow-right"></i></span>
                </div>
                
                <div class="category-card-premium" data-category="Mystery">
                    <div class="category-icon-wrapper">
                        <i class="fas fa-search"></i>
                    </div>
                    <h3>Mystery</h3>
                    <p>Unravel thrilling mysteries</p>
                    <span class="category-arrow"><i class="fas fa-arrow-right"></i></span>
                </div>
                
                <div class="category-card-premium" data-category="Biography">
                    <div class="category-icon-wrapper">
                        <i class="fas fa-user-tie"></i>
                    </div>
                    <h3>Biography</h3>
                    <p>Real lives, real stories</p>
                    <span class="category-arrow"><i class="fas fa-arrow-right"></i></span>
                </div>
                
                <div class="category-card-premium" data-category="History">
                    <div class="category-icon-wrapper">
                        <i class="fas fa-landmark"></i>
                    </div>
                    <h3>History</h3>
                    <p>Journey through time</p>
                    <span class="category-arrow"><i class="fas fa-arrow-right"></i></span>
                </div>
                
                <div class="category-card-premium" data-category="Self-Help">
                    <div class="category-icon-wrapper">
                        <i class="fas fa-lightbulb"></i>
                    </div>
                    <h3>Self-Help</h3>
                    <p>Transform your life</p>
                    <span class="category-arrow"><i class="fas fa-arrow-right"></i></span>
                </div>
                
                <div class="category-card-premium" data-category="Children">
                    <div class="category-icon-wrapper">
                        <i class="fas fa-child"></i>
                    </div>
                    <h3>Children</h3>
                    <p>Books for young readers</p>
                    <span class="category-arrow"><i class="fas fa-arrow-right"></i></span>
                </div>
            </div>
        </div>
    </section>

    <!-- Testimonials Section -->
    <section class="premium-section testimonials-section">
        <div class="container">
            <h2 class="section-title-premium">What Our Readers Say</h2>
            <p class="section-subtitle">Join thousands of happy book lovers</p>
            
            <div class="testimonials-grid">
                <div class="testimonial-card">
                    <div class="testimonial-header">
                        <div class="testimonial-avatar">
                            <i class="fas fa-user-graduate"></i>
                        </div>
                        <div class="testimonial-info">
                            <h4>Rahim Ahmed</h4>
                            <div class="rating">
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                            </div>
                        </div>
                    </div>
                    <p class="testimonial-text">"Amazing collection of books! Fast delivery and great customer service. I've been ordering from BookHive for 2 years now."</p>
                    <div class="testimonial-footer">
                        <i class="fas fa-check-circle"></i>
                        <span>Verified Purchase</span>
                    </div>
                </div>

                <div class="testimonial-card">
                    <div class="testimonial-header">
                        <div class="testimonial-avatar">
                            <i class="fas fa-user-graduate"></i>
                        </div>
                        <div class="testimonial-info">
                            <h4>Priya Fernando</h4>
                            <div class="rating">
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                            </div>
                        </div>
                    </div>
                    <p class="testimonial-text">"Best prices for students! The website is easy to use and I love the variety of books available. Highly recommended!"</p>
                    <div class="testimonial-footer">
                        <i class="fas fa-check-circle"></i>
                        <span>Verified Purchase</span>
                    </div>
                </div>

                <div class="testimonial-card">
                    <div class="testimonial-header">
                        <div class="testimonial-avatar">
                            <i class="fas fa-user-graduate"></i>
                        </div>
                        <div class="testimonial-info">
                            <h4>Kasun Silva</h4>
                            <div class="rating">
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star-half-alt"></i>
                            </div>
                        </div>
                    </div>
                    <p class="testimonial-text">"Great experience! Books arrived in perfect condition. The packaging was excellent and delivery was on time."</p>
                    <div class="testimonial-footer">
                        <i class="fas fa-check-circle"></i>
                        <span>Verified Purchase</span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Newsletter Section -->
    <section class="newsletter-section">
        <div class="container">
            <div class="newsletter-card">
                <div class="newsletter-content">
                    <div class="newsletter-icon">
                        <i class="fas fa-envelope-open-text"></i>
                    </div>
                    <h2>Get Latest Book Updates</h2>
                    <p>Subscribe to our newsletter and get exclusive deals, new arrivals, and reading recommendations delivered to your inbox!</p>
                    
                    <form id="newsletterForm" class="newsletter-form">
                        <div class="newsletter-input-group">
                            <i class="fas fa-envelope"></i>
                            <input type="email" id="newsletterEmail" placeholder="Enter your email address" required>
                            <button type="submit" class="btn-newsletter">
                                <span>Subscribe</span>
                                <i class="fas fa-paper-plane"></i>
                            </button>
                        </div>
                        <div id="newsletterMessage" class="newsletter-message"></div>
                    </form>
                    
                    <div class="newsletter-features">
                        <div class="newsletter-feature">
                            <i class="fas fa-gift"></i>
                            <span>Exclusive Offers</span>
                        </div>
                        <div class="newsletter-feature">
                            <i class="fas fa-bell"></i>
                            <span>New Arrivals</span>
                        </div>
                        <div class="newsletter-feature">
                            <i class="fas fa-tag"></i>
                            <span>Special Discounts</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Trust Badges -->
    <section class="trust-badges-section">
        <div class="container">
            <div class="trust-badges">
                <div class="trust-badge">
                    <i class="fas fa-shield-check"></i>
                    <span>Secure Checkout</span>
                </div>
                <div class="trust-badge">
                    <i class="fas fa-truck"></i>
                    <span>Free Shipping</span>
                </div>
                <div class="trust-badge">
                    <i class="fas fa-undo"></i>
                    <span>Easy Returns</span>
                </div>
                <div class="trust-badge">
                    <i class="fas fa-award"></i>
                    <span>Quality Assured</span>
                </div>
                <div class="trust-badge">
                    <i class="fas fa-headphones-alt"></i>
                    <span>24/7 Support</span>
                </div>
            </div>
        </div>
    </section>

    <!-- Contact Section -->
    <section class="contact-section-premium" id="contact">
        <div class="container">
            <div class="contact-wrapper">
                <div class="contact-info">
                    <h2 class="section-title-dark">Get in Touch</h2>
                    <p class="contact-description">Have questions? We'd love to hear from you. Send us a message and we'll respond as soon as possible.</p>
                    
                    <div class="contact-cards">
                        <div class="contact-card">
                            <div class="contact-icon">
                                <i class="fas fa-envelope"></i>
                            </div>
                            <div class="contact-details">
                                <h4>Email Us</h4>
                                <a href="mailto:info@nethbookhive.com">info@nethbookhive.com</a>
                                <a href="mailto:support@nethbookhive.com">support@nethbookhive.com</a>
                            </div>
                        </div>
                        
                        <div class="contact-card">
                            <div class="contact-icon">
                                <i class="fas fa-phone-alt"></i>
                            </div>
                            <div class="contact-details">
                                <h4>Call Us</h4>
                                <a href="tel:+94112345678">+94 11 234 5678</a>
                                <p>Mon-Fri, 9AM-6PM</p>
                            </div>
                        </div>
                        
                        <div class="contact-card">
                            <div class="contact-icon">
                                <i class="fas fa-map-marker-alt"></i>
                            </div>
                            <div class="contact-details">
                                <h4>Visit Us</h4>
                                <p>123 Book Street</p>
                                <p>Colombo 03, Sri Lanka</p>
                            </div>
                        </div>
                        
                        <div class="contact-card">
                            <div class="contact-icon">
                                <i class="fas fa-clock"></i>
                            </div>
                            <div class="contact-details">
                                <h4>Working Hours</h4>
                                <p>Monday - Friday: 9AM - 6PM</p>
                                <p>Saturday: 10AM - 4PM</p>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="contact-form-wrapper">
                    <form id="contactForm" class="contact-form-premium">
                        <h3>Send us a Message</h3>
                        
                        <div class="form-group-contact">
                            <label for="contactName">Your Name</label>
                            <input type="text" id="contactName" name="name" placeholder="John Doe" required>
                        </div>
                        
                        <div class="form-group-contact">
                            <label for="contactEmail">Email Address</label>
                            <input type="email" id="contactEmail" name="email" placeholder="john@example.com" required>
                        </div>
                        
                        <div class="form-group-contact">
                            <label for="contactSubject">Subject</label>
                            <input type="text" id="contactSubject" name="subject" placeholder="How can we help?" required>
                        </div>
                        
                        <div class="form-group-contact">
                            <label for="contactMessage">Message</label>
                            <textarea id="contactMessage" name="message" rows="5" placeholder="Tell us more..." required></textarea>
                        </div>
                        
                        <button type="submit" class="btn-contact-submit">
                            <span>Send Message</span>
                            <i class="fas fa-paper-plane"></i>
                        </button>
                        
                        <div id="contactFormMessage" class="contact-form-message"></div>
                    </form>
                </div>
            </div>
        </div>
    </section>

<?php include 'includes/footer-home.php'; ?>
