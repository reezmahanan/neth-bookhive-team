    <!-- Premium Footer - Homepage Only -->
    <footer class="footer footer-premium">
        <div class="container">
            <div class="footer-content">
                <div class="footer-section footer-brand">
                    <div class="footer-logo">
                        <i class="fas fa-book-reader"></i>
                        <h3>NETH BookHive</h3>
                    </div>
                    <p>Your trusted destination for quality books and exceptional reading experiences. Serving students and book lovers across Sri Lanka.</p>
                    <div class="social-links">
                        <a href="#" class="social-link" title="Facebook" aria-label="Facebook">
                            <i class="fab fa-facebook-f"></i>
                        </a>
                        <a href="#" class="social-link" title="Twitter" aria-label="Twitter">
                            <i class="fab fa-twitter"></i>
                        </a>
                        <a href="#" class="social-link" title="Instagram" aria-label="Instagram">
                            <i class="fab fa-instagram"></i>
                        </a>
                        <a href="#" class="social-link" title="LinkedIn" aria-label="LinkedIn">
                            <i class="fab fa-linkedin-in"></i>
                        </a>
                    </div>
                </div>
                <div class="footer-section">
                    <h4>Quick Links</h4>
                    <a href="index.php"><i class="fas fa-home"></i> Home</a>
                    <a href="shop.php"><i class="fas fa-shopping-bag"></i> Shop</a>
                    <a href="about.php"><i class="fas fa-info-circle"></i> About</a>
                    <a href="#contact"><i class="fas fa-envelope"></i> Contact</a>
                    <a href="profile.html"><i class="fas fa-user"></i> My Account</a>
                    <a href="wishlist.html"><i class="fas fa-heart"></i> Wishlist</a>
                </div>
                <div class="footer-section">
                    <h4>Categories</h4>
                    <div class="footer-categories">
                        <span class="category-tag">Fiction</span>
                        <span class="category-tag">Non-Fiction</span>
                        <span class="category-tag">Mystery</span>
                        <span class="category-tag">Romance</span>
                        <span class="category-tag">Sci-Fi</span>
                        <span class="category-tag">Biography</span>
                        <span class="category-tag">History</span>
                        <span class="category-tag">Self-Help</span>
                    </div>
                </div>
                <div class="footer-section">
                    <h4>Get in Touch</h4>
                    <p><i class="fas fa-envelope"></i> info@nethbookhive.com</p>
                    <p><i class="fas fa-phone"></i> +94 11 234 5678</p>
                    <p><i class="fas fa-map-marker-alt"></i> Colombo 03, Sri Lanka</p>
                </div>
            </div>
            <div class="footer-bottom">
                <p>© 2025 NETH BookHive. All rights reserved.</p>
                <p>Made with ❤️ for Book Lovers</p>
            </div>
        </div>
    </footer>

    <script src="js/main.js"></script>
    <script src="js/auth.js"></script>
    <?php if(isset($extra_js)): ?>
        <?php foreach($extra_js as $js): ?>
            <script src="<?php echo $js; ?>"></script>
        <?php endforeach; ?>
    <?php endif; ?>
</body>
</html>
