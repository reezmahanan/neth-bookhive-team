<?php
class Cart {
    private $conn;
    private $table_name = "cart_items";

    public $id;
    public $user_id;
    public $session_id;
    public $book_id;
    public $quantity;

    public function __construct($db) {
        $this->conn = $db;
    }

    // Add item to cart
    public function addToCart() {
        // Check if item already exists
        $checkQuery = "SELECT id, quantity FROM " . $this->table_name . " 
                      WHERE book_id = :book_id AND ";
        
        if ($this->user_id) {
            $checkQuery .= "user_id = :user_id";
        } else {
            $checkQuery .= "session_id = :session_id";
        }
        
        $checkStmt = $this->conn->prepare($checkQuery);
        $checkStmt->bindParam(':book_id', $this->book_id);
        
        if ($this->user_id) {
            $checkStmt->bindParam(':user_id', $this->user_id);
        } else {
            $checkStmt->bindParam(':session_id', $this->session_id);
        }
        
        $checkStmt->execute();
        
        if ($checkStmt->rowCount() > 0) {
            // Update existing item
            $row = $checkStmt->fetch(PDO::FETCH_ASSOC);
            $newQuantity = $row['quantity'] + $this->quantity;
            
            $updateQuery = "UPDATE " . $this->table_name . " 
                           SET quantity = :quantity, updated_at = NOW() 
                           WHERE id = :id";
            $updateStmt = $this->conn->prepare($updateQuery);
            $updateStmt->bindParam(':quantity', $newQuantity);
            $updateStmt->bindParam(':id', $row['id']);
            return $updateStmt->execute();
        } else {
            // Insert new item
            $query = "INSERT INTO " . $this->table_name . " 
                     SET user_id = :user_id, session_id = :session_id, 
                         book_id = :book_id, quantity = :quantity";
            $stmt = $this->conn->prepare($query);
            
            $stmt->bindParam(':user_id', $this->user_id);
            $stmt->bindParam(':session_id', $this->session_id);
            $stmt->bindParam(':book_id', $this->book_id);
            $stmt->bindParam(':quantity', $this->quantity);
            
            return $stmt->execute();
        }
    }

    // Get cart items for user or session
    public function getCartItems() {
        $query = "SELECT c.*, b.title, b.author, b.price, b.image_url, b.stock_quantity, b.category
                 FROM " . $this->table_name . " c 
                 JOIN books b ON c.book_id = b.id 
                 WHERE ";
        
        if ($this->user_id) {
            $query .= "c.user_id = :user_id";
        } else {
            $query .= "c.session_id = :session_id";
        }
        
        $query .= " ORDER BY c.created_at DESC";
        
        $stmt = $this->conn->prepare($query);
        
        if ($this->user_id) {
            $stmt->bindParam(':user_id', $this->user_id);
        } else {
            $stmt->bindParam(':session_id', $this->session_id);
        }
        
        $stmt->execute();
        return $stmt;
    }

    // Update quantity
    public function updateQuantity() {
        $query = "UPDATE " . $this->table_name . " 
                 SET quantity = :quantity, updated_at = NOW() 
                 WHERE book_id = :book_id AND ";
        
        if ($this->user_id) {
            $query .= "user_id = :user_id";
        } else {
            $query .= "session_id = :session_id";
        }
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':quantity', $this->quantity);
        $stmt->bindParam(':book_id', $this->book_id);
        
        if ($this->user_id) {
            $stmt->bindParam(':user_id', $this->user_id);
        } else {
            $stmt->bindParam(':session_id', $this->session_id);
        }
        
        return $stmt->execute();
    }

    // Remove item from cart
    public function removeFromCart() {
        $query = "DELETE FROM " . $this->table_name . " 
                 WHERE book_id = :book_id AND ";
        
        if ($this->user_id) {
            $query .= "user_id = :user_id";
        } else {
            $query .= "session_id = :session_id";
        }
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':book_id', $this->book_id);
        
        if ($this->user_id) {
            $stmt->bindParam(':user_id', $this->user_id);
        } else {
            $stmt->bindParam(':session_id', $this->session_id);
        }
        
        return $stmt->execute();
    }

    // Clear entire cart
    public function clearCart() {
        $query = "DELETE FROM " . $this->table_name . " WHERE ";
        
        if ($this->user_id) {
            $query .= "user_id = :user_id";
        } else {
            $query .= "session_id = :session_id";
        }
        
        $stmt = $this->conn->prepare($query);
        
        if ($this->user_id) {
            $stmt->bindParam(':user_id', $this->user_id);
        } else {
            $stmt->bindParam(':session_id', $this->session_id);
        }
        
        return $stmt->execute();
    }

    // Get cart count
    public function getCartCount() {
        $query = "SELECT SUM(quantity) as total FROM " . $this->table_name . " WHERE ";
        
        if ($this->user_id) {
            $query .= "user_id = :user_id";
        } else {
            $query .= "session_id = :session_id";
        }
        
        $stmt = $this->conn->prepare($query);
        
        if ($this->user_id) {
            $stmt->bindParam(':user_id', $this->user_id);
        } else {
            $stmt->bindParam(':session_id', $this->session_id);
        }
        
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row['total'] ? (int)$row['total'] : 0;
    }

    // Transfer cart from session to user (when user logs in)
    public function transferCart($fromSessionId, $toUserId) {
        $query = "UPDATE " . $this->table_name . " 
                 SET user_id = :user_id, session_id = NULL 
                 WHERE session_id = :session_id";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':user_id', $toUserId);
        $stmt->bindParam(':session_id', $fromSessionId);
        
        return $stmt->execute();
        $stmt->bindParam(3, $this->book_id);
        return $stmt->execute();
    }

    }
}
?>