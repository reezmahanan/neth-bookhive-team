<?php
class Order {
    private $conn;
    private $table_name = "orders";

    public $id;
    public $user_id;
    public $total_amount;
    public $status;
    public $created_at;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function create() {
        $query = "INSERT INTO " . $this->table_name . " 
                 SET user_id=:user_id, total_amount=:total_amount, status=:status";
        
        $stmt = $this->conn->prepare($query);
        
        $stmt->bindParam(":user_id", $this->user_id);
        $stmt->bindParam(":total_amount", $this->total_amount);
        $stmt->bindParam(":status", $this->status);
        
        if($stmt->execute()) {
            $this->id = $this->conn->lastInsertId();
            return true;
        }
        return false;
    }

    public function addItem($order_id, $book_id, $quantity, $price) {
        $query = "INSERT INTO order_items 
                 SET order_id=:order_id, book_id=:book_id, quantity=:quantity, price=:price";
        
        $stmt = $this->conn->prepare($query);
        
        $stmt->bindParam(":order_id", $order_id);
        $stmt->bindParam(":book_id", $book_id);
        $stmt->bindParam(":quantity", $quantity);
        $stmt->bindParam(":price", $price);
        
        return $stmt->execute();
    }

    public function getUserOrders() {
        $query = "SELECT o.*, 
                 (SELECT COUNT(*) FROM order_items oi WHERE oi.order_id = o.id) as item_count
                 FROM " . $this->table_name . " o 
                 WHERE o.user_id = ? 
                 ORDER BY o.created_at DESC";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->user_id);
        $stmt->execute();
        
        return $stmt;
    }
}
?>