<?php
session_start();

include_once 'backend/config/database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';
    
    // Validate input
    if (empty($username) || empty($password)) {
        header('Location: admin_login.php?error=empty');
        exit();
    }
    
    // Connect to database to check admin credentials
    try {
        $database = new Database();
        $db = $database->getConnection();
        
        // Check if connection is successful
        if (!$db) {
            error_log("Admin auth error: Database connection failed");
            header('Location: admin_login.php?error=system&detail=connection');
            exit();
        }
        
        // Query to check if user is admin
        $query = "SELECT * FROM users WHERE email = :username AND is_admin = 1 LIMIT 1";
        $stmt = $db->prepare($query);
        $stmt->bindParam(':username', $username);
        $stmt->execute();
        
        if ($stmt->rowCount() > 0) {
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
            
            // Verify password
            if (password_verify($password, $user['password'])) {
                $_SESSION['admin_logged_in'] = true;
                $_SESSION['admin_username'] = $user['name'];
                $_SESSION['admin_id'] = $user['id'];
                $_SESSION['admin_email'] = $user['email'];
                header('Location: admin_dashboard.php');
                exit();
            }
        }
        
        // If we get here, login failed
        header('Location: admin_login.php?error=invalid');
        exit();
        
    } catch (PDOException $e) {
        // Database-specific error
        error_log("Admin auth PDO error: " . $e->getMessage());
        header('Location: admin_login.php?error=system&detail=database');
        exit();
    } catch (Exception $e) {
        // General error
        error_log("Admin auth error: " . $e->getMessage());
        header('Location: admin_login.php?error=system&detail=general');
        exit();
    }
} else {
    header('Location: admin_login.php');
    exit();
}
?>
