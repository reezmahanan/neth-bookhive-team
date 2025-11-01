<?php
session_start();
if (!isset($_SESSION['admin_logged_in'])) {
    header('Location: admin_login.php');
    exit();
}

include_once 'backend/config/database.php';
include_once 'backend/models/Book.php';
include_once 'backend/models/StockNotifier.php';

$database = new Database();
$db = $database->getConnection();
$bookModel = new Book($db);
$notifier = new StockNotifier($db);

// Handle manual notification trigger
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['send_notification'])) {
    $notifier->checkAndNotify();
    $notification_sent = true;
}

// Get low stock and out of stock books
$lowStockBooks = $bookModel->getLowStockBooks(5);
$outOfStockBooks = $bookModel->getOutOfStockBooks();

$lowStockList = [];
$outOfStockList = [];

while ($row = $lowStockBooks->fetch(PDO::FETCH_ASSOC)) {
    $lowStockList[] = $row;
}

while ($row = $outOfStockBooks->fetch(PDO::FETCH_ASSOC)) {
    $outOfStockList[] = $row;
}

// Get recent notifications
$recentNotifications = $notifier->getRecentNotifications(5);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Stock Monitoring - NETH Bookhive Admin</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="frontend/css/style.css">
    <style>
        body { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); min-height: 100vh; }
        .container { max-width: 1400px; padding: 40px 20px; }
        .header-section { background: #fff; padding: 30px; border-radius: 18px; margin-bottom: 30px; box-shadow: 0 4px 24px rgba(0,0,0,0.1); }
        .stats-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px; margin-bottom: 30px; }
        .stat-card { background: #fff; padding: 25px; border-radius: 18px; box-shadow: 0 4px 24px rgba(0,0,0,0.08); text-align: center; }
        .stat-card h3 { margin: 10px 0; font-size: 2.5em; }
        .stat-card.danger { border-left: 5px solid #dc3545; }
        .stat-card.warning { border-left: 5px solid #ffc107; }
        .stat-card.success { border-left: 5px solid #28a745; }
        .section-card { background: #fff; padding: 30px; border-radius: 18px; margin-bottom: 30px; box-shadow: 0 4px 24px rgba(0,0,0,0.1); }
        .stock-table { width: 100%; border-collapse: collapse; }
        .stock-table th, .stock-table td { padding: 15px; text-align: left; border-bottom: 1px solid #eee; }
        .stock-table th { background: #f8f9fa; font-weight: 600; }
        .stock-table tr:hover { background: #f8f9fa; }
        .badge { padding: 6px 12px; border-radius: 12px; font-size: 0.9em; font-weight: 600; }
        .badge-danger { background: #dc3545; color: #fff; }
        .badge-warning { background: #ffc107; color: #000; }
        .badge-success { background: #28a745; color: #fff; }
        .btn { padding: 12px 24px; border: none; border-radius: 8px; cursor: pointer; font-weight: 600; transition: all 0.3s; }
        .btn-primary { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: #fff; }
        .btn-primary:hover { transform: translateY(-2px); box-shadow: 0 4px 16px rgba(102, 126, 234, 0.4); }
        .alert { padding: 15px; border-radius: 8px; margin-bottom: 20px; }
        .alert-success { background: #d4edda; color: #155724; border: 1px solid #c3e6cb; }
        .notification-item { padding: 15px; border-bottom: 1px solid #eee; display: flex; justify-content: space-between; align-items: center; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header-section">
            <div style="display: flex; justify-content: space-between; align-items: center;">
                <div>
                    <h1><i class="fas fa-boxes"></i> Stock Level Monitoring</h1>
                    <p style="color: #666; margin-top: 10px;">Real-time inventory tracking and low stock alerts</p>
                </div>
                <div>
                    <a href="admin_dashboard.php" class="btn btn-primary"><i class="fas fa-arrow-left"></i> Back to Dashboard</a>
                </div>
            </div>
        </div>

        <?php if (isset($notification_sent)): ?>
            <div class="alert alert-success">
                <i class="fas fa-check-circle"></i> Stock alert notification has been sent successfully!
            </div>
        <?php endif; ?>

        <!-- Statistics -->
        <div class="stats-grid">
            <div class="stat-card danger">
                <i class="fas fa-times-circle" style="font-size: 2.5em; color: #dc3545;"></i>
                <h3><?php echo count($outOfStockList); ?></h3>
                <p style="color: #666;">Out of Stock</p>
            </div>
            <div class="stat-card warning">
                <i class="fas fa-exclamation-triangle" style="font-size: 2.5em; color: #ffc107;"></i>
                <h3><?php echo count($lowStockList); ?></h3>
                <p style="color: #666;">Low Stock (< 5)</p>
            </div>
            <div class="stat-card success">
                <i class="fas fa-check-circle" style="font-size: 2.5em; color: #28a745;"></i>
                <h3><?php echo count($lowStockList) + count($outOfStockList); ?></h3>
                <p style="color: #666;">Needs Attention</p>
            </div>
        </div>

        <!-- Send Notification Button -->
        <div class="section-card" style="text-align: center;">
            <h3><i class="fas fa-bell"></i> Manual Notification</h3>
            <p style="color: #666; margin: 10px 0 20px;">Send stock alert email to admin</p>
            <form method="POST" style="display: inline;">
                <button type="submit" name="send_notification" class="btn btn-primary">
                    <i class="fas fa-envelope"></i> Send Stock Alert Email
                </button>
            </form>
        </div>

        <!-- Out of Stock Books -->
        <?php if (count($outOfStockList) > 0): ?>
        <div class="section-card">
            <h2 style="color: #dc3545; margin-bottom: 20px;">
                <i class="fas fa-times-circle"></i> Out of Stock Books (<?php echo count($outOfStockList); ?>)
            </h2>
            <table class="stock-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Book Title</th>
                        <th>Author</th>
                        <th>Category</th>
                        <th>Price</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($outOfStockList as $book): ?>
                    <tr>
                        <td><?php echo $book['id']; ?></td>
                        <td><strong><?php echo htmlspecialchars($book['title']); ?></strong></td>
                        <td><?php echo htmlspecialchars($book['author']); ?></td>
                        <td><?php echo htmlspecialchars($book['category']); ?></td>
                        <td>Rs <?php echo $book['price']; ?></td>
                        <td><span class="badge badge-danger">Out of Stock</span></td>
                        <td>
                            <button onclick="restockBook(<?php echo $book['id']; ?>)" class="btn btn-primary" style="padding: 8px 16px; font-size: 0.9em;">
                                <i class="fas fa-plus"></i> Restock
                            </button>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <?php endif; ?>

        <!-- Low Stock Books -->
        <?php if (count($lowStockList) > 0): ?>
        <div class="section-card">
            <h2 style="color: #ffc107; margin-bottom: 20px;">
                <i class="fas fa-exclamation-triangle"></i> Low Stock Books (<?php echo count($lowStockList); ?>)
            </h2>
            <table class="stock-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Book Title</th>
                        <th>Author</th>
                        <th>Category</th>
                        <th>Price</th>
                        <th>Current Stock</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($lowStockList as $book): ?>
                    <tr>
                        <td><?php echo $book['id']; ?></td>
                        <td><strong><?php echo htmlspecialchars($book['title']); ?></strong></td>
                        <td><?php echo htmlspecialchars($book['author']); ?></td>
                        <td><?php echo htmlspecialchars($book['category']); ?></td>
                        <td>Rs <?php echo $book['price']; ?></td>
                        <td><span class="badge badge-warning"><?php echo $book['stock_quantity']; ?> left</span></td>
                        <td>
                            <button onclick="restockBook(<?php echo $book['id']; ?>)" class="btn btn-primary" style="padding: 8px 16px; font-size: 0.9em;">
                                <i class="fas fa-plus"></i> Restock
                            </button>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <?php endif; ?>

        <!-- Recent Notifications -->
        <?php if ($recentNotifications): ?>
        <div class="section-card">
            <h2 style="margin-bottom: 20px;"><i class="fas fa-history"></i> Recent Notifications</h2>
            <?php 
            $hasNotifications = false;
            while ($notification = $recentNotifications->fetch(PDO::FETCH_ASSOC)): 
                $hasNotifications = true;
            ?>
                <div class="notification-item">
                    <div>
                        <strong><?php echo ucfirst(str_replace('_', ' ', $notification['notification_type'])); ?></strong><br>
                        <small style="color: #666;"><?php echo date('F j, Y g:i A', strtotime($notification['notification_date'])); ?></small>
                    </div>
                    <div>
                        <span class="badge badge-warning"><?php echo $notification['book_count']; ?> books</span>
                    </div>
                </div>
            <?php endwhile; ?>
            <?php if (!$hasNotifications): ?>
                <p style="color: #666; text-align: center; padding: 20px;">No notifications yet.</p>
            <?php endif; ?>
        </div>
        <?php endif; ?>
    </div>

    <script>
        function restockBook(bookId) {
            const quantity = prompt('Enter quantity to add to stock:');
            if (quantity && !isNaN(quantity) && quantity > 0) {
                // This would call an API to update stock
                alert('Restock functionality will update stock by ' + quantity + ' for book ID: ' + bookId);
                window.location.href = 'admin_dashboard.php';
            }
        }
    </script>
</body>
</html>
