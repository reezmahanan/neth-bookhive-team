<?php
$page_title = "Shopping Cart - NETH Bookhive";
$extra_js = ['js/cart.js'];
include 'includes/header.php';
?>

    <!-- Cart Section -->
    <section class="cart-section" style="min-height: 80vh; padding-top: 120px; background: #f8f9fa;">
        <div class="container">
            <h1 style="text-align: center; color: #2c3e50; margin-bottom: 40px;">🛒 Your Shopping Cart</h1>
            
            <div class="cart-content" style="background: white; border-radius: 15px; padding: 30px; box-shadow: 0 5px 20px rgba(0,0,0,0.1);">
                <div style="text-align: center; padding: 60px 20px;">
                    <i class="fas fa-shopping-cart" style="font-size: 5rem; color: #ddd; margin-bottom: 20px;"></i>
                    <h2 style="color: #666; margin-bottom: 15px;">Your cart is empty</h2>
                    <p style="color: #999; margin-bottom: 30px;">Add some books to get started!</p>
                    <button class="btn btn-primary" onclick="window.location.href='shop.php'" style="padding: 15px 40px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; border: none; border-radius: 10px; font-size: 1.1rem; font-weight: 600; cursor: pointer; transition: transform 0.3s;">
                        <i class="fas fa-book"></i> Browse Books
                    </button>
                </div>
            </div>
        </div>
    </section>

<?php include 'includes/footer.php'; ?>
