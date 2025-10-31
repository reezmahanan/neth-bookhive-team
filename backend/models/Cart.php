<?php
class Cart {
    private $conn;
    private $table_name = "cart";

    public $id;
    public $user_id;
    public $book_id;
    public $quantity;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function addToCart() {
        $query = "INSERT INTO " . $this->table_name . " SET user_id=:user_id, book_id=:book_id, quantity=:quantity
                 ON DUPLICATE KEY UPDATE quantity = quantity + :quantity";
        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(":user_id", $this->user_id);
        $stmt->bindParam(":book_id", $this->book_id);
        $stmt->bindParam(":quantity", $this->quantity);

        return $stmt->execute();
    }

    public function getUserCart() {
        $query = "SELECT c.*, b.title, b.author, b.price, b.image_url 
                 FROM " . $this->table_name . " c 
                 JOIN books b ON c.book_id = b.id 
                 WHERE c.user_id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->user_id);
        $stmt->execute();
        return $stmt;
    }

    public function removeFromCart() {
        $query = "DELETE FROM " . $this->table_name . " WHERE user_id = ? AND book_id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->user_id);
        $stmt->bindParam(2, $this->book_id);
        return $stmt->execute();
    }

    public function updateQuantity() {
        $query = "UPDATE " . $this->table_name . " SET quantity = ? WHERE user_id = ? AND book_id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->quantity);
        $stmt->bindParam(2, $this->user_id);
        $stmt->bindParam(3, $this->book_id);
        return $stmt->execute();
    }

    public function clearCart() {
        $query = "DELETE FROM " . $this->table_name . " WHERE user_id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->user_id);
        return $stmt->execute();
    }
}
?>