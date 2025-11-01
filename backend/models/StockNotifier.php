<?php
class StockNotifier {
    private $conn;
    private $admin_email = 'admin@nethbookhive.com'; // Change this to actual admin email
    
    public function __construct($db) {
        $this->conn = $db;
    }
    
    public function checkAndNotify() {
        include_once 'Book.php';
        $book = new Book($this->conn);
        
        $lowStockBooks = $book->getLowStockBooks(5);
        $outOfStockBooks = $book->getOutOfStockBooks();
        
        $lowStockList = [];
        $outOfStockList = [];
        
        while ($row = $lowStockBooks->fetch(PDO::FETCH_ASSOC)) {
            $lowStockList[] = $row;
        }
        
        while ($row = $outOfStockBooks->fetch(PDO::FETCH_ASSOC)) {
            $outOfStockList[] = $row;
        }
        
        if (count($lowStockList) > 0 || count($outOfStockList) > 0) {
            $this->sendLowStockEmail($lowStockList, $outOfStockList);
            $this->logNotification($lowStockList, $outOfStockList);
            return true;
        }
        
        return false;
    }
    
    private function sendLowStockEmail($lowStockBooks, $outOfStockBooks) {
        $subject = "Stock Alert - NETH Bookhive";
        
        $message = "<html><head><style>
            body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
            .header { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 20px; text-align: center; }
            .content { padding: 20px; }
            table { width: 100%; border-collapse: collapse; margin: 15px 0; }
            th, td { padding: 12px; text-align: left; border-bottom: 1px solid #ddd; }
            th { background-color: #f8f9fa; font-weight: bold; }
            .low-stock { background-color: #fff3cd; }
            .out-of-stock { background-color: #f8d7da; }
            .warning { color: #856404; font-weight: bold; }
            .danger { color: #721c24; font-weight: bold; }
        </style></head><body>";
        
        $message .= "<div class='header'><h2>📦 Stock Level Alert</h2></div>";
        $message .= "<div class='content'>";
        $message .= "<p>Hello Admin,</p>";
        $message .= "<p>This is an automated notification regarding stock levels at NETH Bookhive.</p>";
        
        if (count($lowStockBooks) > 0) {
            $message .= "<h3 class='warning'>⚠️ Low Stock Books (Less than 5 items)</h3>";
            $message .= "<table class='low-stock'>";
            $message .= "<tr><th>ID</th><th>Title</th><th>Author</th><th>Stock</th><th>Price</th></tr>";
            
            foreach ($lowStockBooks as $book) {
                $message .= "<tr>";
                $message .= "<td>{$book['id']}</td>";
                $message .= "<td>{$book['title']}</td>";
                $message .= "<td>{$book['author']}</td>";
                $message .= "<td><strong>{$book['stock_quantity']}</strong></td>";
                $message .= "<td>Rs {$book['price']}</td>";
                $message .= "</tr>";
            }
            
            $message .= "</table>";
        }
        
        if (count($outOfStockBooks) > 0) {
            $message .= "<h3 class='danger'>🚫 Out of Stock Books</h3>";
            $message .= "<table class='out-of-stock'>";
            $message .= "<tr><th>ID</th><th>Title</th><th>Author</th><th>Price</th></tr>";
            
            foreach ($outOfStockBooks as $book) {
                $message .= "<tr>";
                $message .= "<td>{$book['id']}</td>";
                $message .= "<td>{$book['title']}</td>";
                $message .= "<td>{$book['author']}</td>";
                $message .= "<td>Rs {$book['price']}</td>";
                $message .= "</tr>";
            }
            
            $message .= "</table>";
        }
        
        $message .= "<p><strong>Action Required:</strong> Please restock these items as soon as possible.</p>";
        $message .= "<p>You can manage inventory from the <a href='http://localhost/NETH%20Bookhive/admin_dashboard.php'>Admin Dashboard</a>.</p>";
        $message .= "<hr>";
        $message .= "<p style='color: #666; font-size: 0.9em;'>This is an automated email from NETH Bookhive Stock Management System.</p>";
        $message .= "</div></body></html>";
        
        $headers = "MIME-Version: 1.0" . "\r\n";
        $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
        $headers .= "From: noreply@nethbookhive.com" . "\r\n";
        
        // Send email (uncomment when email server is configured)
        // mail($this->admin_email, $subject, $message, $headers);
        
        // For development, log email content
        error_log("Stock Alert Email would be sent to: " . $this->admin_email);
        error_log($message);
        
        return true;
    }
    
    private function logNotification($lowStockBooks, $outOfStockBooks) {
        $query = "INSERT INTO stock_notifications (notification_type, book_count, notification_date, details) 
                 VALUES (:type, :count, NOW(), :details)";
        
        try {
            $stmt = $this->conn->prepare($query);
            
            $details = json_encode([
                'low_stock' => array_column($lowStockBooks, 'id'),
                'out_of_stock' => array_column($outOfStockBooks, 'id')
            ]);
            
            $type = 'stock_alert';
            $count = count($lowStockBooks) + count($outOfStockBooks);
            
            $stmt->bindParam(':type', $type);
            $stmt->bindParam(':count', $count);
            $stmt->bindParam(':details', $details);
            
            $stmt->execute();
        } catch (PDOException $e) {
            // Table might not exist yet, continue without logging
            error_log("Stock notification logging failed: " . $e->getMessage());
        }
    }
    
    public function getRecentNotifications($limit = 10) {
        $query = "SELECT * FROM stock_notifications 
                 ORDER BY notification_date DESC LIMIT :limit";
        
        try {
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt;
        } catch (PDOException $e) {
            return null;
        }
    }
}
?>
