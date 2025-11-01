<?php
class Wishlist {
    private $conn;
    private $table_name = "wishlist";

    public $id;
    public $user_id;
    public $book_id;
    public $created_at;

    public function __construct($db) {
        $this->conn = $db;
    }

    /**
     * Add book to wishlist
     */
    public function add() {
        $query = "INSERT INTO " . $this->table_name . " 
                 SET user_id=:user_id, book_id=:book_id
                 ON DUPLICATE KEY UPDATE created_at=CURRENT_TIMESTAMP";
        
        $stmt = $this->conn->prepare($query);
        
        $stmt->bindParam(":user_id", $this->user_id);
        $stmt->bindParam(":book_id", $this->book_id);
        
        return $stmt->execute();
    }

    /**
     * Remove book from wishlist
     */
    public function remove() {
        $query = "DELETE FROM " . $this->table_name . " 
                 WHERE user_id=:user_id AND book_id=:book_id";
        
        $stmt = $this->conn->prepare($query);
        
        $stmt->bindParam(":user_id", $this->user_id);
        $stmt->bindParam(":book_id", $this->book_id);
        
        return $stmt->execute();
    }

    /**
     * Get user's wishlist
     */
    public function getUserWishlist() {
        $query = "SELECT w.*, 
                 b.title, b.author, b.price, b.image_url, b.stock, b.average_rating
                 FROM " . $this->table_name . " w
                 INNER JOIN books b ON w.book_id = b.id
                 WHERE w.user_id = :user_id
                 ORDER BY w.created_at DESC";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":user_id", $this->user_id);
        $stmt->execute();
        
        return $stmt;
    }

    /**
     * Check if book is in wishlist
     */
    public function isInWishlist() {
        $query = "SELECT id FROM " . $this->table_name . " 
                 WHERE user_id=:user_id AND book_id=:book_id";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":user_id", $this->user_id);
        $stmt->bindParam(":book_id", $this->book_id);
        $stmt->execute();
        
        return $stmt->rowCount() > 0;
    }

    /**
     * Get wishlist count
     */
    public function getCount() {
        $query = "SELECT COUNT(*) as count FROM " . $this->table_name . " 
                 WHERE user_id=:user_id";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":user_id", $this->user_id);
        $stmt->execute();
        
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row['count'];
    }

    /**
     * Clear entire wishlist
     */
    public function clearWishlist() {
        $query = "DELETE FROM " . $this->table_name . " WHERE user_id=:user_id";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":user_id", $this->user_id);
        
        return $stmt->execute();
    }
}
?>
