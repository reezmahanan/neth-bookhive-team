<?php
$page_title = "Book Details - NETH Bookhive";
$extra_js = ['js/book-details.js'];
include 'includes/header.php';
?>

    <!-- Book Details Section -->
    <section class="book-details-section" style="min-height: 100vh; background: #f8f9fa; padding-top: 120px;">
        <div class="container">
            <div id="bookDetails" style="min-height: 400px;">
                <!-- Default book details shown immediately -->
                <div class="book-details">
                    <div class="book-details-image">
                        <div class="book-cover" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); width: 250px; height: 350px; margin: 0 auto; border-radius: 10px; display: flex; align-items: center; justify-content: center; flex-direction: column; box-shadow: 0 10px 30px rgba(0,0,0,0.2);">
                            <i class="fas fa-book-open" style="font-size: 4rem; color: white; margin-bottom: 15px;"></i>
                            <div style="font-size: 1.2rem; font-weight: bold; color: white; text-align: center; padding: 0 20px;">
                                Book Title
                            </div>
                        </div>
                    </div>
                    <div class="book-details-info">
                        <h1>To Kill a Mockingbird</h1>
                        <p class="book-details-author">by Harper Lee</p>
                        <p class="book-details-price">$14.99</p>
                        
                        <div class="book-meta" style="margin: 20px 0; padding: 15px; background: #fff; border-radius: 8px; border-left: 4px solid #667eea;">
                            <p style="margin-bottom: 10px;"><strong>Category:</strong> <span style="color: #667eea;">Fiction</span></p>
                            <p><strong>Availability:</strong> 
                                <span class="in-stock" style="color: #27ae60;">In Stock</span>
                            </p>
                        </div>
                        
                        <div class="book-details-description">
                            <h3 style="color: #2c3e50; margin-bottom: 15px; border-bottom: 2px solid #667eea; padding-bottom: 10px;">Description</h3>
                            <p style="line-height: 1.8; color: #555;">
                                A gripping, heart-wrenching, and wholly remarkable tale of coming-of-age in a South poisoned by virulent prejudice. 
                                Through the young eyes of Scout and Jem Finch, Harper Lee explores with rich humor and unswerving honesty 
                                the irrationality of adult attitudes toward race and class in the Deep South of the 1930s.
                            </p>
                        </div>
                        
                        <div class="purchase-section" style="margin-top: 30px;">
                            <div class="quantity-selector" style="display: flex; align-items: center; gap: 15px; margin-bottom: 20px;">
                                <label for="quantity" style="font-weight: 600; color: #2c3e50;">Quantity:</label>
                                <input type="number" id="quantity" name="quantity" value="1" min="1" max="10" style="width: 80px; padding: 10px; border: 2px solid #ddd; border-radius: 5px; font-size: 1rem;">
                            </div>
                            
                            <div class="action-buttons" style="display: flex; gap: 15px; flex-wrap: wrap;">
                                <button class="btn btn-primary" onclick="alert('Added to cart!')" style="padding: 12px 30px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; border: none; border-radius: 8px; font-size: 1rem; font-weight: 600; cursor: pointer; transition: transform 0.3s;">
                                    <i class="fas fa-cart-plus"></i> Add to Cart
                                </button>
                                <button class="btn btn-secondary" onclick="window.location.href='shop.php'" style="padding: 12px 30px; background: #6c757d; color: white; border: none; border-radius: 8px; font-size: 1rem; font-weight: 600; cursor: pointer; transition: transform 0.3s;">
                                    <i class="fas fa-arrow-left"></i> Continue Shopping
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

<?php include 'includes/footer.php'; ?>
