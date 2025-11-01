<div class="book-card" style="position:relative; background:#fff; border-radius:18px; box-shadow:0 4px 24px rgba(0,0,0,0.08); padding:24px 18px; text-align:center; margin:16px; transition:box-shadow 0.2s;">
    <?php if (!empty($book['original_price']) && $book['original_price'] > $book['price']): ?>
        <span style="position:absolute;top:18px;right:18px;background:#e74c3c;color:#fff;padding:4px 14px;border-radius:16px;font-weight:600;font-size:1em;">Sale</span>
    <?php endif; ?>
    <a href="book_details.php?id=<?php echo (int)$book['id']; ?>" style="text-decoration:none;color:inherit;">
        <div class="book-image" style="margin-bottom:12px;">
            <img src="<?php echo htmlspecialchars($book['image_url']); ?>" alt="<?php echo htmlspecialchars($book['title']); ?>" style="width:120px;height:170px;object-fit:cover;border-radius:8px;box-shadow:0 2px 8px rgba(0,0,0,0.08);">
        </div>
        <h3 style="font-size:1.2em;font-weight:700;margin-bottom:0.2em;"><?php echo htmlspecialchars($book['title']); ?></h3>
    </a>
    <p class="book-author" style="color:#666;margin-bottom:0.5em;">by <?php echo htmlspecialchars($book['author']); ?></p>
    <div class="book-rating" style="margin-bottom:0.5em;">
        <?php
        $rating = isset($book['rating']) ? floatval($book['rating']) : 0;
        $reviews = isset($book['reviews']) ? intval($book['reviews']) : rand(1000,4000);
        for ($i = 1; $i <= 5; $i++) {
            echo '<i class="fas fa-star" style="color:'.($i <= round($rating) ? '#ffc107' : '#ddd').';"></i>';
        }
        ?>
        <span style="color:#222;font-weight:600;">(<?php echo $reviews; ?>)</span>
    </div>
    <div class="book-pricing" style="margin-bottom:0.5em;">
        <span class="price" style="color:#27ae60;font-weight:bold;font-size:1.2em;">Rs <?php echo htmlspecialchars($book['price']); ?></span>
        <?php if (!empty($book['original_price']) && $book['original_price'] > $book['price']): ?>
            <span class="original-price" style="text-decoration:line-through;color:#aaa;margin-left:8px;">Rs <?php echo htmlspecialchars($book['original_price']); ?></span>
            <span class="discount" style="background:#e74c3c;color:#fff;border-radius:6px;padding:2px 8px;font-size:0.9em;margin-left:8px;">
                <?php echo round(100 * (1 - $book['price'] / $book['original_price'])); ?>% OFF
            </span>
        <?php endif; ?>
    </div>
    
    <!-- Stock Level Display -->
    <div style="margin:8px 0;font-size:0.9em;">
        <?php 
        $stock = isset($book['stock_quantity']) ? intval($book['stock_quantity']) : 0;
        if ($stock == 0): ?>
            <span style="background:#dc3545;color:#fff;padding:4px 12px;border-radius:12px;font-weight:600;">
                <i class="fas fa-times-circle"></i> Out of Stock
            </span>
        <?php elseif ($stock < 5): ?>
            <span style="background:#ffc107;color:#000;padding:4px 12px;border-radius:12px;font-weight:600;">
                <i class="fas fa-exclamation-triangle"></i> Low Stock: <?php echo $stock; ?> left
            </span>
        <?php else: ?>
            <span style="background:#28a745;color:#fff;padding:4px 12px;border-radius:12px;font-weight:600;">
                <i class="fas fa-check-circle"></i> In Stock (<?php echo $stock; ?>)
            </span>
        <?php endif; ?>
    </div>
    
    <div style="display:flex;justify-content:center;gap:10px;align-items:center;margin-top:10px;">
        <button class="btn btn-primary" onclick="addToCart(<?php echo (int)$book['id']; ?>)" <?php echo ($stock == 0) ? 'disabled style="opacity:0.5;cursor:not-allowed;"' : ''; ?>>
            <i class="fas fa-shopping-cart"></i> <?php echo ($stock == 0) ? 'Out of Stock' : 'Add to Cart'; ?>
        </button>
        <button class="wishlist-btn" onclick="addToWishlist(<?php echo (int)$book['id']; ?>)" title="Add to Wishlist" style="background:none;border:none;cursor:pointer;font-size:1.3em;color:#e74c3c;">
            <i class="fas fa-heart"></i>
        </button>
    </div>
</div>
