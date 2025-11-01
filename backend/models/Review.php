<?php
class Review {
    private $conn;
    private $table_name = "reviews";

    public $id;
    public $book_id;
    public $user_id;
    public $rating;
    public $review_text;
    public $created_at;
    public $updated_at;

    public function __construct($db) {
        $this->conn = $db;
    }

    /**
     * Create new review
     */
    public function create() {
        $query = "INSERT INTO " . $this->table_name . " 
                 SET book_id=:book_id, 
                     user_id=:user_id, 
                     rating=:rating, 
                     review_text=:review_text
                 ON DUPLICATE KEY UPDATE 
                     rating=:rating, 
                     review_text=:review_text,
                     updated_at=CURRENT_TIMESTAMP";
        
        $stmt = $this->conn->prepare($query);
        
        $this->review_text = htmlspecialchars(strip_tags($this->review_text));
        
        $stmt->bindParam(":book_id", $this->book_id);
        $stmt->bindParam(":user_id", $this->user_id);
        $stmt->bindParam(":rating", $this->rating);
        $stmt->bindParam(":review_text", $this->review_text);
        
        if($stmt->execute()) {
            $this->updateBookRating();
            return true;
        }
        return false;
    }

    /**
     * Update book's average rating and total reviews
     */
    private function updateBookRating() {
        $query = "UPDATE books 
                 SET average_rating = (
                     SELECT AVG(rating) FROM reviews WHERE book_id = :book_id
                 ),
                 total_reviews = (
                     SELECT COUNT(*) FROM reviews WHERE book_id = :book_id
                 )
                 WHERE id = :book_id";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":book_id", $this->book_id);
        $stmt->execute();
    }

    /**
     * Get reviews for a book
     */
    public function getBookReviews() {
        $query = "SELECT r.*, u.name as user_name, u.profile_picture
                 FROM " . $this->table_name . " r
                 INNER JOIN users u ON r.user_id = u.id
                 WHERE r.book_id = :book_id
                 ORDER BY r.created_at DESC";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":book_id", $this->book_id);
        $stmt->execute();
        
        return $stmt;
    }

    /**
     * Get user's review for a book
     */
    public function getUserReview() {
        $query = "SELECT * FROM " . $this->table_name . " 
                 WHERE book_id=:book_id AND user_id=:user_id";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":book_id", $this->book_id);
        $stmt->bindParam(":user_id", $this->user_id);
        $stmt->execute();
        
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Delete review
     */
    public function delete() {
        $query = "DELETE FROM " . $this->table_name . " 
                 WHERE book_id=:book_id AND user_id=:user_id";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":book_id", $this->book_id);
        $stmt->bindParam(":user_id", $this->user_id);
        
        if($stmt->execute()) {
            $this->updateBookRating();
            return true;
        }
        return false;
    }

    /**
     * Get book rating statistics
     */
    public function getRatingStats() {
        $query = "SELECT 
                 COUNT(*) as total_reviews,
                 AVG(rating) as average_rating,
                 SUM(CASE WHEN rating = 5 THEN 1 ELSE 0 END) as five_star,
                 SUM(CASE WHEN rating = 4 THEN 1 ELSE 0 END) as four_star,
                 SUM(CASE WHEN rating = 3 THEN 1 ELSE 0 END) as three_star,
                 SUM(CASE WHEN rating = 2 THEN 1 ELSE 0 END) as two_star,
                 SUM(CASE WHEN rating = 1 THEN 1 ELSE 0 END) as one_star
                 FROM " . $this->table_name . " 
                 WHERE book_id = :book_id";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":book_id", $this->book_id);
        $stmt->execute();
        
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
?>
