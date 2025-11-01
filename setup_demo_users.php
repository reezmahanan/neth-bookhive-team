<?php
/**
 * Setup Demo Users for NETH BookHive
 * 
 * This script creates demo admin and user accounts
 * Run this file once to set up test credentials
 */

error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<!DOCTYPE html>
<html>
<head>
    <title>Setup Demo Users - NETH BookHive</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            margin: 0;
            padding: 40px;
            min-height: 100vh;
        }
        .container {
            max-width: 800px;
            margin: 0 auto;
            background: white;
            border-radius: 20px;
            padding: 40px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
        }
        h1 {
            color: #667eea;
            margin-top: 0;
            display: flex;
            align-items: center;
            gap: 15px;
        }
        .success {
            background: #d4edda;
            color: #155724;
            padding: 15px;
            border-radius: 10px;
            margin: 20px 0;
            border-left: 5px solid #28a745;
        }
        .error {
            background: #f8d7da;
            color: #721c24;
            padding: 15px;
            border-radius: 10px;
            margin: 20px 0;
            border-left: 5px solid #dc3545;
        }
        .info {
            background: #d1ecf1;
            color: #0c5460;
            padding: 15px;
            border-radius: 10px;
            margin: 20px 0;
            border-left: 5px solid #17a2b8;
        }
        .credentials {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 10px;
            margin: 20px 0;
            border: 2px solid #667eea;
        }
        .cred-box {
            background: white;
            padding: 15px;
            margin: 10px 0;
            border-radius: 8px;
            border-left: 4px solid #667eea;
        }
        .cred-box h3 {
            margin-top: 0;
            color: #667eea;
        }
        .cred-item {
            margin: 8px 0;
            font-family: 'Courier New', monospace;
            font-size: 14px;
        }
        .cred-label {
            font-weight: bold;
            color: #495057;
            display: inline-block;
            width: 100px;
        }
        .btn {
            display: inline-block;
            padding: 12px 30px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            text-decoration: none;
            border-radius: 25px;
            margin: 10px 10px 10px 0;
            transition: transform 0.3s;
        }
        .btn:hover {
            transform: translateY(-2px);
        }
        code {
            background: #f8f9fa;
            padding: 2px 6px;
            border-radius: 4px;
            color: #e83e8c;
        }
    </style>
</head>
<body>
    <div class='container'>
        <h1>
            <svg width='40' height='40' viewBox='0 0 24 24' fill='#667eea'>
                <path d='M12 2L2 7v10c0 5.55 3.84 10.74 9 12 5.16-1.26 9-6.45 9-12V7l-10-5z'/>
            </svg>
            Setup Demo Users
        </h1>";

include_once 'backend/config/database.php';

try {
    $database = new Database();
    $db = $database->getConnection();
    
    if (!$db) {
        throw new Exception("Database connection failed");
    }
    
    echo "<div class='success'>✓ Database connection successful</div>";
    
    // Check if is_admin column exists
    $checkColumn = $db->query("SHOW COLUMNS FROM users LIKE 'is_admin'");
    if ($checkColumn->rowCount() == 0) {
        echo "<div class='info'>Adding is_admin column to users table...</div>";
        $db->exec("ALTER TABLE users ADD COLUMN is_admin TINYINT(1) DEFAULT 0 AFTER password");
        echo "<div class='success'>✓ Column is_admin added successfully</div>";
    } else {
        echo "<div class='info'>✓ Column is_admin already exists</div>";
    }
    
    // Create Admin User
    $adminEmail = 'admin@nethbookhive.com';
    $adminPassword = password_hash('admin123', PASSWORD_DEFAULT);
    $adminName = 'Admin User';
    
    // Check if admin exists
    $checkAdmin = $db->prepare("SELECT id FROM users WHERE email = ?");
    $checkAdmin->execute([$adminEmail]);
    
    if ($checkAdmin->rowCount() > 0) {
        // Update existing admin
        $stmt = $db->prepare("UPDATE users SET name = ?, password = ?, is_admin = 1 WHERE email = ?");
        $stmt->execute([$adminName, $adminPassword, $adminEmail]);
        echo "<div class='success'>✓ Admin account updated: $adminEmail</div>";
    } else {
        // Create new admin
        $stmt = $db->prepare("INSERT INTO users (name, email, password, is_admin, created_at) VALUES (?, ?, ?, 1, NOW())");
        $stmt->execute([$adminName, $adminEmail, $adminPassword]);
        echo "<div class='success'>✓ Admin account created: $adminEmail</div>";
    }
    
    // Create Regular User
    $userEmail = 'user@nethbookhive.com';
    $userPassword = password_hash('user123', PASSWORD_DEFAULT);
    $userName = 'NETH User';
    
    // Check if user exists
    $checkUser = $db->prepare("SELECT id FROM users WHERE email = ?");
    $checkUser->execute([$userEmail]);
    
    if ($checkUser->rowCount() > 0) {
        // Update existing user
        $stmt = $db->prepare("UPDATE users SET name = ?, password = ?, is_admin = 0 WHERE email = ?");
        $stmt->execute([$userName, $userPassword, $userEmail]);
        echo "<div class='success'>✓ User account updated: $userEmail</div>";
    } else {
        // Create new user
        $stmt = $db->prepare("INSERT INTO users (name, email, password, is_admin, created_at) VALUES (?, ?, ?, 0, NOW())");
        $stmt->execute([$userName, $userEmail, $userPassword]);
        echo "<div class='success'>✓ User account created: $userEmail</div>";
    }
    
    // Display credentials
    echo "
    <div class='credentials'>
        <h2 style='color: #667eea; margin-top: 0;'>🎉 Demo Credentials Ready!</h2>
        
        <div class='cred-box'>
            <h3>👨‍💼 Admin Account</h3>
            <div class='cred-item'>
                <span class='cred-label'>Email:</span> <code>admin@nethbookhive.com</code>
            </div>
            <div class='cred-item'>
                <span class='cred-label'>Password:</span> <code>admin123</code>
            </div>
            <div style='margin-top: 15px;'>
                <a href='admin_login.php' class='btn'>Login as Admin →</a>
            </div>
        </div>
        
        <div class='cred-box'>
            <h3>👤 User Account</h3>
            <div class='cred-item'>
                <span class='cred-label'>Name:</span> <code>NETH User</code>
            </div>
            <div class='cred-item'>
                <span class='cred-label'>Email:</span> <code>user@nethbookhive.com</code>
            </div>
            <div class='cred-item'>
                <span class='cred-label'>Password:</span> <code>user123</code>
            </div>
            <div style='margin-top: 15px;'>
                <a href='frontend/login.html' class='btn'>Login as User →</a>
            </div>
        </div>
    </div>
    
    <div class='info'>
        <strong>Note:</strong> These are demo accounts for testing purposes. 
        In production, use strong passwords and secure authentication methods.
    </div>
    
    <div style='margin-top: 30px; text-align: center;'>
        <a href='frontend/index.php' class='btn'>Go to Homepage</a>
        <a href='admin_dashboard.php' class='btn'>Admin Dashboard</a>
    </div>
    ";
    
} catch (Exception $e) {
    echo "<div class='error'>
        <strong>Error:</strong> " . htmlspecialchars($e->getMessage()) . "
        <br><br>
        <strong>Troubleshooting:</strong>
        <ul>
            <li>Make sure XAMPP is running</li>
            <li>Check that MySQL is running on port 3307</li>
            <li>Verify database 'bookstore' exists</li>
            <li>Check backend/config/database.php settings</li>
        </ul>
    </div>";
}

echo "
    </div>
</body>
</html>";
?>
