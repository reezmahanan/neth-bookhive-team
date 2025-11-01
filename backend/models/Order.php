<?php
class Order {
    private $conn;
    private $table_name = "orders";

    public $id;
    public $user_id;
    public $order_number;
    public $total_amount;
    public $subtotal;
    public $shipping_cost;
    public $tax;
    public $status;
    public $payment_status;
    public $payment_method;
    public $payment_id;
    public $notes;
    public $created_at;
    public $updated_at;

    public function __construct($db) {
        $this->conn = $db;
    }

    /**
     * Generate unique order number
     */
    private function generateOrderNumber() {
        return 'ORD-' . date('Ymd') . '-' . strtoupper(substr(uniqid(), -6));
    }

    /**
     * Create new order
     */
    public function create() {
        $this->order_number = $this->generateOrderNumber();
        
        $query = "INSERT INTO " . $this->table_name . " 
                 SET user_id=:user_id, 
                     order_number=:order_number,
                     total_amount=:total_amount, 
                     subtotal=:subtotal,
                     shipping_cost=:shipping_cost,
                     tax=:tax,
                     status=:status,
                     payment_status=:payment_status,
                     payment_method=:payment_method,
                     payment_id=:payment_id,
                     notes=:notes";
        
        $stmt = $this->conn->prepare($query);
        
        $stmt->bindParam(":user_id", $this->user_id);
        $stmt->bindParam(":order_number", $this->order_number);
        $stmt->bindParam(":total_amount", $this->total_amount);
        $stmt->bindParam(":subtotal", $this->subtotal);
        $stmt->bindParam(":shipping_cost", $this->shipping_cost);
        $stmt->bindParam(":tax", $this->tax);
        $stmt->bindParam(":status", $this->status);
        $stmt->bindParam(":payment_status", $this->payment_status);
        $stmt->bindParam(":payment_method", $this->payment_method);
        $stmt->bindParam(":payment_id", $this->payment_id);
        $stmt->bindParam(":notes", $this->notes);
        
        if($stmt->execute()) {
            $this->id = $this->conn->lastInsertId();
            $this->addStatusHistory($this->id, $this->status, 'Order created');
            return true;
        }
        return false;
    }

    /**
     * Add item to order
     */
    public function addItem($order_id, $book_id, $quantity, $price) {
        $subtotal = $quantity * $price;
        
        $query = "INSERT INTO order_items 
                 SET order_id=:order_id, 
                     book_id=:book_id, 
                     quantity=:quantity, 
                     price=:price,
                     subtotal=:subtotal";
        
        $stmt = $this->conn->prepare($query);
        
        $stmt->bindParam(":order_id", $order_id);
        $stmt->bindParam(":book_id", $book_id);
        $stmt->bindParam(":quantity", $quantity);
        $stmt->bindParam(":price", $price);
        $stmt->bindParam(":subtotal", $subtotal);
        
        // Update book stock
        if($stmt->execute()) {
            $this->updateBookStock($book_id, $quantity);
            return true;
        }
        
        return false;
    }

    /**
     * Add shipping address
     */
    public function addShippingAddress($order_id, $data) {
        $query = "INSERT INTO shipping_addresses 
                 SET order_id=:order_id,
                     full_name=:full_name,
                     email=:email,
                     phone=:phone,
                     address_line1=:address_line1,
                     address_line2=:address_line2,
                     city=:city,
                     state=:state,
                     postal_code=:postal_code,
                     country=:country";
        
        $stmt = $this->conn->prepare($query);
        
        $stmt->bindParam(":order_id", $order_id);
        $stmt->bindParam(":full_name", $data['full_name']);
        $stmt->bindParam(":email", $data['email']);
        $stmt->bindParam(":phone", $data['phone']);
        $stmt->bindParam(":address_line1", $data['address_line1']);
        $stmt->bindParam(":address_line2", $data['address_line2']);
        $stmt->bindParam(":city", $data['city']);
        $stmt->bindParam(":state", $data['state']);
        $stmt->bindParam(":postal_code", $data['postal_code']);
        $stmt->bindParam(":country", $data['country']);
        
        return $stmt->execute();
    }

    /**
     * Update book stock after order
     */
    private function updateBookStock($book_id, $quantity) {
        $query = "UPDATE books SET stock_quantity = stock_quantity - :quantity WHERE id = :book_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":quantity", $quantity);
        $stmt->bindParam(":book_id", $book_id);
        return $stmt->execute();
    }

    /**
     * Add order status history
     */
    public function addStatusHistory($order_id, $status, $notes = '') {
        $query = "INSERT INTO order_status_history 
                 SET order_id=:order_id, status=:status, notes=:notes";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":order_id", $order_id);
        $stmt->bindParam(":status", $status);
        $stmt->bindParam(":notes", $notes);
        
        return $stmt->execute();
    }

    /**
     * Update order status
     */
    public function updateStatus($order_id, $status, $notes = '') {
        $query = "UPDATE " . $this->table_name . " SET status=:status WHERE id=:order_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":status", $status);
        $stmt->bindParam(":order_id", $order_id);
        
        if($stmt->execute()) {
            $this->addStatusHistory($order_id, $status, $notes);
            return true;
        }
        return false;
    }

    /**
     * Update payment status
     */
    public function updatePaymentStatus($order_id, $payment_status, $payment_id = null) {
        $query = "UPDATE " . $this->table_name . " 
                 SET payment_status=:payment_status";
        
        if($payment_id) {
            $query .= ", payment_id=:payment_id";
        }
        
        $query .= " WHERE id=:order_id";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":payment_status", $payment_status);
        $stmt->bindParam(":order_id", $order_id);
        
        if($payment_id) {
            $stmt->bindParam(":payment_id", $payment_id);
        }
        
        return $stmt->execute();
    }

    /**
     * Get user orders
     */
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

    /**
     * Get order by ID with full details
     */
    public function getOrderById($order_id) {
        $query = "SELECT o.*, 
                 sa.full_name, sa.email, sa.phone, 
                 sa.address_line1, sa.address_line2, sa.city, sa.state, sa.postal_code, sa.country
                 FROM " . $this->table_name . " o
                 LEFT JOIN shipping_addresses sa ON o.id = sa.order_id
                 WHERE o.id = :order_id";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":order_id", $order_id);
        $stmt->execute();
        
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Get order by order number
     */
    public function getOrderByNumber($order_number) {
        $query = "SELECT o.*, 
                 sa.full_name, sa.email, sa.phone, 
                 sa.address_line1, sa.address_line2, sa.city, sa.state, sa.postal_code, sa.country
                 FROM " . $this->table_name . " o
                 LEFT JOIN shipping_addresses sa ON o.id = sa.order_id
                 WHERE o.order_number = :order_number";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":order_number", $order_number);
        $stmt->execute();
        
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Get order items
     */
    public function getOrderItems($order_id) {
        $query = "SELECT oi.*, b.title, b.author, b.image_url
                 FROM order_items oi
                 JOIN books b ON oi.book_id = b.id
                 WHERE oi.order_id = :order_id";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":order_id", $order_id);
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Get order status history
     */
    public function getStatusHistory($order_id) {
        $query = "SELECT * FROM order_status_history 
                 WHERE order_id = :order_id 
                 ORDER BY created_at DESC";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":order_id", $order_id);
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>