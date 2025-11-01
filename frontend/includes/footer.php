    <!-- Footer -->
    <footer class="footer footer-simple">
        <div class="container">
            <div class="footer-simple-content">
                <div class="footer-simple-left">
                    <h3><i class="fas fa-book-reader"></i> NETH BookHive</h3>
                    <p>© 2025 NETH BookHive. All rights reserved.</p>
                </div>
                <div class="footer-simple-links">
                    <a href="index.php">Home</a>
                    <a href="shop.php">Shop</a>
                    <a href="about.php">About</a>
                    <a href="cart.php">Cart</a>
                    <a href="profile.html">My Account</a>
                </div>
                <div class="footer-simple-social">
                    <a href="#" title="Facebook"><i class="fab fa-facebook-f"></i></a>
                    <a href="#" title="Twitter"><i class="fab fa-twitter"></i></a>
                    <a href="#" title="Instagram"><i class="fab fa-instagram"></i></a>
                    <a href="#" title="LinkedIn"><i class="fab fa-linkedin-in"></i></a>
                </div>
            </div>
        </div>
    </footer>

    <script src="js/accessibility.js"></script>
    <script src="js/main.js"></script>
    <script src="js/auth.js"></script>
    <?php if(isset($extra_js)): ?>
        <?php foreach($extra_js as $js): ?>
            <script src="<?php echo $js; ?>"></script>
        <?php endforeach; ?>
    <?php endif; ?>
</body>
</html>
