<?php
session_start();
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Origin: *");

include_once '../config/database.php';

$database = new Database();
$db = $database->getConnection();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    if (!isset($_POST['user_id'])) {
        echo json_encode(array("success" => false, "message" => "User ID required"));
        exit();
    }
    
    $user_id = $_POST['user_id'];
    
    // Check if file was uploaded
    if (!isset($_FILES['profile_picture'])) {
        echo json_encode(array("success" => false, "message" => "No file uploaded"));
        exit();
    }
    
    $file = $_FILES['profile_picture'];
    
    // Validate file
    $allowed_types = array('image/jpeg', 'image/jpg', 'image/png', 'image/gif');
    $max_size = 5 * 1024 * 1024; // 5MB
    
    if (!in_array($file['type'], $allowed_types)) {
        echo json_encode(array("success" => false, "message" => "Invalid file type. Only JPG, PNG, and GIF allowed"));
        exit();
    }
    
    if ($file['size'] > $max_size) {
        echo json_encode(array("success" => false, "message" => "File too large. Maximum 5MB allowed"));
        exit();
    }
    
    // Create upload directory if it doesn't exist
    $upload_dir = '../../frontend/uploads/profiles/';
    if (!file_exists($upload_dir)) {
        mkdir($upload_dir, 0777, true);
    }
    
    // Generate unique filename
    $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
    $filename = 'profile_' . $user_id . '_' . time() . '.' . $extension;
    $target_path = $upload_dir . $filename;
    
    // Get old profile picture to delete
    $query = "SELECT profile_picture FROM users WHERE id = :user_id";
    $stmt = $db->prepare($query);
    $stmt->bindParam(':user_id', $user_id);
    $stmt->execute();
    $old_picture = $stmt->fetchColumn();
    
    // Move uploaded file
    if (move_uploaded_file($file['tmp_name'], $target_path)) {
        // Delete old profile picture if exists
        if ($old_picture && file_exists('../../frontend/' . $old_picture)) {
            unlink('../../frontend/' . $old_picture);
        }
        
        // Update database
        $relative_path = 'uploads/profiles/' . $filename;
        $query = "UPDATE users SET profile_picture = :profile_picture WHERE id = :user_id";
        $stmt = $db->prepare($query);
        $stmt->bindParam(':profile_picture', $relative_path);
        $stmt->bindParam(':user_id', $user_id);
        
        if ($stmt->execute()) {
            echo json_encode(array(
                "success" => true, 
                "message" => "Profile picture uploaded successfully",
                "profile_picture" => $relative_path
            ));
        } else {
            echo json_encode(array("success" => false, "message" => "Database update failed"));
        }
    } else {
        echo json_encode(array("success" => false, "message" => "Failed to upload file"));
    }
} else {
    echo json_encode(array("success" => false, "message" => "Invalid request method"));
}
?>
